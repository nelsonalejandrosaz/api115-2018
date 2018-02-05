<?php

namespace App\Http\Controllers;

use App\ConversionUnidadMedida;
use App\Entrada;
use App\Formula;
use App\Movimiento;
use App\Produccion;
use App\Producto;
use App\Salida;
use App\TipoMovimiento;
use App\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduccionController extends Controller
{
    public function ProduccionLista()
    {
        $producciones = Produccion::all();
        return view('produccion.produccionLista')->with(['producciones' => $producciones]);
    }

    public function ProduccionVer($id)
    {
        $produccion = Produccion::find($id);
        $productos = Producto::all();
        $formulas = Formula::all();
        return view('produccion.produccionVer')
            ->with(['produccion' => $produccion])
            ->with(['formulas' => $formulas])
            ->with(['productos' => $productos]);
    }

    public function ProduccionNuevo()
    {
        $formulas = Formula::all();
        return view('produccion.produccionNuevo')->with(['formulas' => $formulas]);
    }

    public function ProduccionNuevoPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'formula_id' => 'required',
            'cantidad' => 'required',
            'fecha' => 'required',
        ]);

//        Crear la produccion
        $produccion = Produccion::create([
            'formula_id' => $request->input('formula_id'),
            'bodega_id' => Auth::user()->id,
            'cantidad' => $request->input('cantidad'),
            'fecha' => $request->input('fecha'),
            'detalle' => $request->input('detalle'),
            'lote' => $request->input('lote'),
            'fecha_vencimiento' => $request->input('fecha_vencimiento'),
        ]); // Falta el detalle de los lotes y fecha vencimiento

//        Se guardan en variales los componentes
        $formula = Formula::find($produccion->formula_id);
        $unidad_medida_formula = UnidadMedida::whereAbreviatura('gr')->first();
        $factor_gramos = 1000;
        $costo_total_produccion = 0.00;
        $cantidad_produccion = floatval($request->input('cantidad'));
        $cantidad_produccion = $cantidad_produccion * $factor_gramos;

        /**
         * Validación de existencias
         */
        foreach ($formula->componentes as $componente)
        {
//            dd($cantidad_produccion);
            $producto = Producto::find($componente->producto_id);
            $cantidad = ($componente->porcentaje / 100) * $cantidad_produccion;
            $cantidad = round($cantidad,4);
            $cantidad_real = $cantidad / $factor_gramos;
            $cantidad_real = round($cantidad_real,4);
            $cantidad_producto = round($producto->cantidad_existencia,4);
            if ($cantidad_producto < $cantidad_real){
                // No alcanza la existencia para produccion
                $produccion->delete();
                // Mensaje de error al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-close');
                session()->flash('mensaje.titulo', 'Error!');
                session()->flash('mensaje.contenido', 'No hay suficiente ' . $producto->nombre . ' necesaria para generar la producción!');
                return redirect()->route('produccionNuevo');
            }
        }
        /**
         * Fin validación existencias
         */

        // Se registran las salidas
        foreach ($formula->componentes as $componente)
        {
            // Se carga el producto
            $producto = Producto::find($componente->producto_id);
            $cantidad = ($componente->porcentaje / 100) * $cantidad_produccion;
            $cantidad = round($cantidad,4);
            $cantidad_real = $cantidad / $factor_gramos;
            $cantidad_real = round($cantidad_real,4);
            $cantidad_salida = $cantidad;
            // Se calcula la cantidad y costo
            // Calculo costo salida
            $costo_unitario_salida = $producto->costo;
            $costo_total_salida = $cantidad_real * $costo_unitario_salida;
            $costo_total_salida = round($costo_total_salida,4);
//            $costo_total_salida = round($ct_salida,3);
            // Calculo de cantidad y costos existencias
            $cantidad_existencia = $producto->cantidad_existencia - $cantidad_real;
            $costo_unitario_existencia = $producto->costo;
            $costo_total_existencia = $cantidad_existencia * $costo_unitario_existencia;
            $costo_total_existencia = round($costo_total_existencia,4);

            // Se crea la salida
            $salida = Salida::create([
                'produccion_id' => $produccion->id,
                'cantidad' => $cantidad_salida,
                'unidad_medida_id' => $unidad_medida_formula->id,
                'precio_unitario' => 0.00,
                'venta_exenta' => 0.00,
                'venta_gravada' => 0.00,
            ]);
            $tipo_movimiento = TipoMovimiento::whereCodigo('SALP')->first();
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'salida_id' => $salida->id,
                'fecha' => $produccion->fecha,
                'detalle' => 'Salida de producto por producción n° ' . $produccion->id,
                'cantidad' => $cantidad_real,
                'costo_unitario' => $costo_unitario_salida,
                'costo_total' => $costo_total_salida,
                'cantidad_existencia' => $cantidad_existencia,
                'costo_unitario_existencia' => $costo_unitario_existencia,
                'costo_total_existencia' => $costo_total_existencia,
                'fecha_procesado' => Carbon::now(),
                'procesado' => true,
            ]);

            $costo_total_produccion += $costo_total_salida;
            // Se actualiza la existencia del producto
            $producto->cantidad_existencia = $cantidad_existencia;
            $producto->save();
        }
        // Se actualiza la cantidad del producto producido
        // Se carga el producto
        $producto = Producto::find($formula->producto_id);
        // Se calcula la cantidad y costo
        $costo_unitario_produccion = $costo_total_produccion / $produccion->cantidad;
        $costo_unitario_produccion = round($costo_unitario_produccion,4);
        $cantidad = $produccion->cantidad;
        $costo_unitario_entrada = $costo_unitario_produccion;
        $costo_total_entrada = $cantidad * $costo_unitario_entrada;
        $costo_total_entrada = round($costo_total_entrada,4);
//            Calculo de existencias
        $cantidad_existencia = $producto->cantidad_existencia + $cantidad;
        /**
         * Asignacion de costo bajo costo promedio ponderado
         */
        if ($producto->costo == 0.00) {
            $costo_unitario_existencia = $costo_unitario_entrada;
        } else {
            $costo_total_existencia = $producto->costo * $producto->cantidad_existencia;
            $costo_unitario_existencia = ($costo_total_existencia + $costo_total_entrada) / $cantidad_existencia;
            $costo_unitario_existencia = round($costo_unitario_existencia,4);
        }
        $costo_total_existencia = $costo_unitario_existencia * $cantidad_existencia;
        $costo_total_existencia = round($costo_total_existencia,4);

        // Se crea la entrada
        $entrada = Entrada::create([
            'produccion_id' => $produccion->id,
            'unidad_medida_id' => $produccion->formula->producto->unidad_medida_id,
            'cantidad' => $cantidad,
            'costo_unitario' => $costo_unitario_entrada,
            'costo_total' => $costo_total_entrada,
        ]);
        // Se crea el movimiento de entrada
        $tipo_movimiento = TipoMovimiento::whereCodigo('ENTP')->first();
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => $tipo_movimiento->id,
            'entrada_id' => $entrada->id,
            'fecha' => $produccion->fecha,
            'detalle' => 'Entrada de producto por producción n° ' . $produccion->id,
            'cantidad' => $cantidad,
            'costo_unitario' => $costo_unitario_entrada,
            'costo_total' => $costo_total_entrada,
            'cantidad_existencia' => $cantidad_existencia,
            'costo_unitario_existencia' => $costo_unitario_existencia,
            'costo_total_existencia' => $costo_total_existencia,
            'fecha_procesado' => Carbon::now(),
            'procesado' => true,
        ]);
        // Se actualiza el producto con la entrada de la producción
        $producto->cantidad_existencia = $cantidad_existencia;
        $producto->costo = $costo_unitario_existencia;
        $producto->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La producción fue agregada correctamente!');
        return redirect()->route('produccionVer', ['id' => $produccion->id]);
    }
}
