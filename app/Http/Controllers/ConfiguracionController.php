<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Categoria;
use App\Cliente;
use App\ConversionUnidadMedida;
use App\Movimiento;
use App\Producto;
use App\Proveedor;
use App\TipoMovimiento;
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
                    'telefono_1' => $proveedorX->telefono_1,
                    'telefono_2' => $proveedorX->telefono_2,
                    'direccion' => $proveedorX->direccion,
                    'nombre_contacto' => $proveedorX->nombre_persona_contacto,
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
                    'telefono_1' => $clienteX->telefono_1,
                    'telefono_2' => $clienteX->telefono_2,
                    'direccion' => $clienteX->direccion,
                    'nombre_contacto' => $clienteX->contacto,
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
                $existencia_min = ($producto->existencia_minima == null) ? 0 : $producto->existencia_minima;
                $existencia_max = ($producto->existencia_maxima == null) ? 100 : $producto->existencia_maxima;
                $costo = ($producto->costo == null) ? 0.00 : $producto->costo;
                $precio = ($producto->precio == null) ? 0.00 : $producto->precio;
                $margen_ganancia = ($producto->margen_ganancia == null) ? 0 : $producto->margen_ganancia;
                $unidad_medida = UnidadMedida::whereNombre($producto->unidad_de_medida)->first();
                $tipo_producto = TipoProducto::whereNombre($producto->tipo_de_producto)->first();
                $categoria = Categoria::whereNombre($producto->categoria)->first();
                $productoDB = Producto::updateOrCreate([
                    'nombre' => $producto->nombre_producto,
                ],[
                    'tipo_producto_id' => $tipo_producto->id,
                    'unidad_medida_id' => $unidad_medida->id,
                    'categoria_id' => $categoria->id,
                    'existencia_min' => $existencia_min,
                    'existencia_max' => $existencia_max,
                    'costo' => $costo,
                    'precio' => $precio,
                    'precio_impuestos' => ($precio * 1.13),
                    'margen_ganancia' => $margen_ganancia,
                ]);
                if ($producto->codigo == null)
                {
//                  Asignacion del codigo del producto
                    $ids = $productoDB->id;
                    $ids = str_pad($ids, 5, '0', STR_PAD_LEFT);
                    $codigo = $productoDB->tipo_producto->codigo . $ids;
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
                $diferenciaCantidadAjuste = $cantidadAjuste - $productoDB->cantidad_existencia;
                $tipo_movimiento = TipoMovimiento::whereCodigo('AJSE')->first();

                // Se crea el ajuste de entrada
                $ajuste = Ajuste::create([
                    'tipo_ajuste_id' => 1,
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'fecha' => Carbon::now()->format('Y-d-m'),
                    'cantidad_ajuste' => $cantidadAjuste,
                    'valor_unitario_ajuste' => $valorUnitarioAjuste,
                    'realizado_id' => Auth::user()->id,
                    'cantidad_anterior' => 0,
                    'valor_unitario_anterior' => $productoDB->precio,
                    'diferencia_cantidad_ajuste' => $diferenciaCantidadAjuste,
                ]);

                // Se crea el movimiento
                $movimiento = Movimiento::create([
                    'producto_id' => $productoDB->id,
                    'tipo_movimiento_id' => $tipo_movimiento->id,
                    'ajuste_id' => $ajuste->id,
                    'fecha' => Carbon::now(),
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'cantidad_existencia' => $cantidadAjuste,
                    'costo_unitario_existencia' => $valorUnitarioAjuste,
                    'fecha_procesado' => Carbon::now(),
                    'procesado' => true,
                ]);

                // Se actualiza la cantidad de producto despues de la entrada
                $productoDB->cantidad_existencia = $movimiento->cantidad_existencia;
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
