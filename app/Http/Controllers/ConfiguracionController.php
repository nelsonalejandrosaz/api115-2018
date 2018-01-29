<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Categoria;
use App\Cliente;
use App\ConversionUnidadMedida;
use App\Movimiento;
use App\Precio;
use App\Producto;
use App\Proveedor;
use App\TipoAjuste;
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
//            dd($results);

            /**
             * Código para guardar categorias
             */
            $categorias = $results[2];
            foreach ($categorias as $categoriaX)
            {
                Categoria::updateOrCreate([
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
             * Código para guardar tipo de productos
             */
            $tipo_productos = $results[3];
            foreach ($tipo_productos as $tipo_producto_X)
            {
                TipoProducto::updateOrCreate([
                    'codigo' => $tipo_producto_X->codigo,
                ],[
                    'nombre' => $tipo_producto_X->nombre_tipo,
                ]);
            }
            /**
             * Fin de código para guardar tipo de productos
             */

            /**
             * Código para guardar proveedores
             */
            $proveedores = $results[4];
            foreach ($proveedores as $proveedorX)
            {
                Proveedor::updateOrCreate([
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
                Cliente::updateOrCreate([
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
            foreach ($productos as $producto_x)
            {
                // Inicio para guardar inventario
                $existencia_min = ($producto_x->existencia_minima == null) ? 0 : $producto_x->existencia_minima;
                $existencia_max = ($producto_x->existencia_maxima == null) ? 100 : $producto_x->existencia_maxima;
                $costo = ($producto_x->costo == null) ? 0.00 : round($producto_x->costo,2);
                $precio = ($producto_x->precio == null) ? 0.00 : round($producto_x->precio,2);
                $unidad_medida = UnidadMedida::whereNombre($producto_x->unidad_de_medida)->first();
                $tipo_producto = TipoProducto::whereNombre($producto_x->tipo_de_producto)->first();
                $categoria = Categoria::whereNombre($producto_x->categoria)->first();
                $factor_volumen = $producto_x->factor_de_volumen;
                $producto = Producto::updateOrCreate([
                    'nombre' => $producto_x->nombre_producto,
                ],[
                    'tipo_producto_id' => $tipo_producto->id,
                    'unidad_medida_id' => $unidad_medida->id,
                    'categoria_id' => $categoria->id,
                    'existencia_min' => $existencia_min,
                    'existencia_max' => $existencia_max,
                    'costo' => $costo,
                    'factor_volumen' => $factor_volumen,
                ]);
                // Asignacion del codigo del producto
                $ids = $producto->id;
                $ids = str_pad($ids, 4, '0', STR_PAD_LEFT);
                $codigo = $producto->tipo_producto->codigo. $producto->categoria->codigo . $ids;
                $producto->codigo = $codigo;
                $producto->save();
                // Asignación de precio del producto
                $precio = Precio::updateOrCreate([
                    'producto_id' => $producto->id,
                    'presentacion' => $unidad_medida->nombre,
                ],[
                    'unidad_medida_id' => $unidad_medida->id,
                    'precio' => $precio,
                    'factor' => 1,
                ]);
                // Asignacion de inventario inicial
                // Variables
                $cantidad_ajuste = ($producto_x->existencias == null) ? 0 : $producto_x->existencias;
                $valor_unitario_ajuste = $producto->costo;
                $valor_total_ajuste = $cantidad_ajuste * $valor_unitario_ajuste;
                $diferencia_ajuste = $cantidad_ajuste - $producto->cantidad_existencia;
                $tipo_movimiento = TipoMovimiento::whereCodigo('AJSE')->first();
                $tipo_ajuste = TipoAjuste::whereCodigo('ENTINI')->first();
                // Se crea el ajuste de entrada
                $ajuste = Ajuste::create([
                    'tipo_ajuste_id' => $tipo_ajuste->id,
                    'detalle' => 'Ajuste de entrada por inicio de inventario',
                    'fecha' => Carbon::createFromDate(2018,1,1)->format('Y-d-m'),
                    'cantidad_ajuste' => $cantidad_ajuste,
                    'valor_unitario_ajuste' => $valor_unitario_ajuste,
                    'realizado_id' => Auth::user()->id,
                    'cantidad_anterior' => 0,
                    'valor_unitario_anterior' => 0,
                    'diferencia_ajuste' => $diferencia_ajuste,
                ]);
                // Se crea el movimiento
                $movimiento = Movimiento::create([
                    'producto_id' => $producto->id,
                    'tipo_movimiento_id' => $tipo_movimiento->id,
                    'ajuste_id' => $ajuste->id,
                    'fecha' => Carbon::createFromDate(2018,1,1),
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'cantidad' => $cantidad_ajuste,
                    'costo_unitario' => $valor_unitario_ajuste,
                    'costo_total' => $valor_total_ajuste,
                    'cantidad_existencia' => $cantidad_ajuste,
                    'costo_unitario_existencia' => $valor_unitario_ajuste,
                    'costo_total_existencia' => $valor_total_ajuste,
                    'fecha_procesado' => Carbon::createFromDate(2018,1,1),
                    'procesado' => true,
                ]);
                // Se actualiza la cantidad de producto después de la entrada
                $producto->cantidad_existencia = $movimiento->cantidad_existencia;
                $producto->save();
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
