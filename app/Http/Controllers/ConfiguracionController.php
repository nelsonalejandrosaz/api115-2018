<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Categoria;
use App\Cliente;
use App\ConversionUnidadMedida;
use App\Movimiento;
use App\Producto;
use App\Proveedor;
use App\TipoProducto;
use App\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ConfiguracionController extends Controller
{
    public function ImportarDatos()
    {
        return view('configuracion.importarDatos');
    }

    public function ImportarDatosPost(Request $request)
    {
        $this->validate($request, [
            'archivoXLSX' => 'required',
        ]);

        Excel::load(Input::file('archivoXLSX'), function($reader) {
            // Getting all results
            $results = $reader->get();
//            dd($results[5]);

            /**
             * Código para guardar categorias
             */
            $categorias = $results[2];
//            dd($categorias);
            foreach ($categorias as $categoriaX)
            {
                $categoria = Categoria::updateOrCreate([
                    'codigo' => $categoriaX->codigo,
                ],[
                    'nombre' => $categoriaX->nombre_categoria,
                    'descripcion' => $categoriaX->descripcion,
                ]);
            }
            /**
             * Fin de código para guardar categorías
             */

            /**
             * Código para guardar proveedores
             */
            $proveedores = $results[4];
            foreach ($proveedores as $proveedorX)
            {
                $proveedor = Proveedor::updateOrCreate([
                    'nombre' => $proveedorX->nombre_o_empresa_proveedor,
                ],[
                    'telefono1' => $proveedorX->telefono_1,
                    'telefono2' => $proveedorX->telefono_2,
                    'direccion' => $proveedorX->direccion,
                    'nombreContacto' => $proveedorX->nombre_persona_contacto,
                ]);
            }
            /**
             * Fin código para guardar proveedores
             */

            /**
             * Código para guardar clientes
             */
            $clientes = $results[5];
            foreach ($clientes as $clienteX)
            {
                $cliente = Cliente::updateOrCreate([
                    'nombre' => $clienteX->nombre_o_empresa_cliente,
                ],[
                    'telefono1' => $clienteX->telefono_1,
                    'telefono2' => $clienteX->telefono_2,
                    'direccion' => $clienteX->direccion,
                    'nombreContacto' => $clienteX->contacto,
                    'nrc' => $clienteX->num_registro,
                ]);
            }
            /**
             * Fin código para guardar clientes
             */

            /**
             * Código para guardar los productos con su inventario inicial
             */
            // Se selecciona la hoja correspondiente a productos
            $productos = $results[0];
            foreach ($productos as $producto)
            {
//                dd($producto);
                // Inicio para guardar inventario
                $existenciaMin = ($producto->existencia_minima == null) ? 0 : $producto->existencia_minima;
                $existenciaMax = ($producto->existencia_maxima == null) ? 100 : $producto->existencia_maxima;
                $costo = ($producto->costo == null) ? 0.00 : $producto->costo;
                $precio = ($producto->precio == null) ? 0.00 : $producto->precio;
                $margenGanancia = ($producto->margen_ganancia == null) ? 0 : $producto->margen_ganancia;
                $unidadMedida = UnidadMedida::whereNombre($producto->unidad_de_medida)->first();
                $tipoProducto = TipoProducto::whereNombre($producto->tipo_de_producto)->first();
                $categoria = Categoria::whereNombre($producto->categoria)->first();
                $productoDB = Producto::updateOrCreate([
                    'nombre' => $producto->nombre_producto,
                ],[
                    'tipo_producto_id' => $tipoProducto->id,
                    'unidad_medida_id' => $unidadMedida->id,
                    'categoria_id' => $categoria->id,
                    'existenciaMin' => $existenciaMin,
                    'existenciaMax' => $existenciaMax,
                    'costo' => $costo,
                    'precio' => $precio,
                    'precioConImpuestos' => ($precio * 1.13),
                    'margenGanancia' => $margenGanancia,
                ]);
                if ($producto->codigo == null)
                {
//                  Asignacion del codigo del producto
                    $ids = $productoDB->id;
                    $ids = str_pad($ids, 5, '0', STR_PAD_LEFT);
                    $codigo = $productoDB->tipoProducto->codigo . $ids;
                    $productoDB->codigo = $codigo;
                    $productoDB->update();
                } else
                {
//                  Asignacion del codigo del producto
                    $productoDB->codigo = $producto->codigo;
                    $productoDB->update();
                }
                // Asignacion de inventario inicial
                // Variables
                $cantidadAjuste = ($producto->existencias == null) ? 0 : $producto->existencias;
                $valorUnitarioAjuste = $productoDB->costo;
                $diferenciaCantidadAjuste = $cantidadAjuste - $productoDB->cantidadExistencia;
                // Se crea el movimiento
                $movimiento = Movimiento::create([
                    'producto_id' => $productoDB->id,
                    'tipo_movimiento_id' => 3,
                    'fecha' => Carbon::now(),
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'cantidadExistencia' => $cantidadAjuste,
                    'costoUnitarioExistencia' => $valorUnitarioAjuste,
                    'costoTotalExistencia' => $cantidadAjuste * $valorUnitarioAjuste,
                    'fechaProcesado' => Carbon::now(),
                    'procesado' => true,
                ]);
                // Se crea el ajuste de entrada
                $ajuste = Ajuste::create([
                    'movimiento_id' => $movimiento->id,
                    'tipo_ajuste_id' => 1,
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'fechaIngreso' => Carbon::now()->format('Y-d-m'),
                    'cantidadAjuste' => $cantidadAjuste,
                    'valorUnitarioAjuste' => $valorUnitarioAjuste,
                    'realizado_id' => Auth::user()->id,
                    'cantidadAnterior' => 0,
                    'valorUnitarioAnterior' => $productoDB->precio,
                    'diferenciaCantidadAjuste' => $diferenciaCantidadAjuste,
                ]);
                // Se actualiza la cantidad de producto despues de la entrada
                $productoDB->cantidadExistencia = $movimiento->cantidadExistencia;
                $productoDB->save();
            }
            /**
             * Fin código para guardar los productos con su inventario inicial
             */
        });
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La importación de la configuración inicial fue ejecutada correctamente!');
        return redirect()->route('inventarioLista');

    }

    public function ConversionUnidadesLista()
    {
        $factores = ConversionUnidadMedida::all();
        return view('configuracion.conversionesUMLista')->with(['factores' => $factores]);
    }

    public function ConversionUnidadesVer($id)
    {
        $unidadMedidas = UnidadMedida::all();
        $factor = ConversionUnidadMedida::find($id);
        return view('configuracion.conversionesUMVer')
            ->with(['unidadMedidas' => $unidadMedidas])
            ->with(['factor' => $factor]);
    }

    public function ConversionUnidadesNuevo()
    {
        $unidadMedidas = UnidadMedida::all();
        return view('configuracion.conversionesUM')->with(['unidadMedidas' => $unidadMedidas]);
    }

    public function ConversionUnidadesNuevoPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'codigo' => 'required',
            'nombre' => 'required',
            'unidadMedidaOrigen_id' => 'required',
            'unidadMedidaDestino_id' => 'required',
            'factor' => 'required',
        ]);

        $factor = ConversionUnidadMedida::create([
            'codigo' => $request->input('codigo'),
            'nombre' => $request->input('nombre'),
            'unidadMedidaOrigen_id' => $request->input('unidadMedidaOrigen_id'),
            'unidadMedidaDestino_id' => $request->input('unidadMedidaDestino_id'),
            'factor' => $request->input('factor'),
        ]);

        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La conversion de unidad fue agregada correctamente!');
        return redirect()->route('conversionUnidadesVer', ['id' => $factor->id]);

    }

}
