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
        $unidad_medida = $produccion->formula->producto->unidad_medida_id;
        foreach ($produccion->salidas as $salida)
        {
//            dd($salida->movimiento);
            if ($salida->movimiento->producto->unidad_medida->conversiones->where('unidad_medida_destino_id',$unidad_medida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidad_medida_origen_id','=', $salida->movimiento->producto->unidad_medida_id],
                    ['unidad_medida_destino_id', '=', $unidad_medida],
                ])->first();
                $salida->cantidad = $salida->cantidad / $factor->factor;
            }
        }
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
        ]); // Falta el detalle de los lotes y fecha vencimiento

//        Se guardan en variales los componentes
        $formula = Formula::find($produccion->formula_id);
        $unidad_medida = $formula->producto->unidad_medida_id;
        $costo_total_produccion = 0.00;

        /**
         * Validacion de existencias
         */
        foreach ($formula->componentes as $componente)
        {
            $producto = Producto::find($componente->producto_id);
            $cantidad = ($componente->porcentaje / 100) * $produccion->cantidad;
//            Se calcula la cantidad y costo

            // Si es la misma medida no se hacen conversiones; si no si se haran las conversiones de unidades
            if ($unidad_medida == $producto->unidad_medida_id)
            {
                $cantidad_salida = $cantidad;
                if ($producto->cantidad_existencia < $cantidad_salida){
                    // No alcanza la existencia para produccion
                    $produccion->delete();
                    // Mensaje de error al guardar
                    session()->flash('mensaje.tipo', 'danger');
                    session()->flash('mensaje.icono', 'fa-close');
                    session()->flash('mensaje.titulo', 'Error!');
                    session()->flash('mensaje.contenido', 'No hay suficiente materia prima necesaria para generar la producción!');
                    return redirect()->route('produccionNuevo');
                }

            } elseif ($producto->unidad_medida->conversiones->where('unidad_medida_destino_id',$unidad_medida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidad_medida_origen_id','=', $producto->unidad_medida_id],
                    ['unidad_medida_destino_id', '=', $unidad_medida],
                ])->first();
                // Se guarda la cantidad de salida
                $cantidad_salida = $cantidad / $factor->factor;
                if ($producto->cantidad_existencia < $cantidad_salida){
                    // No alcanza la existencia para produccion
                    $produccion->delete();
                    // Mensaje de error al guardar
                    session()->flash('mensaje.tipo', 'danger');
                    session()->flash('mensaje.icono', 'fa-close');
                    session()->flash('mensaje.titulo', 'Error!');
                    session()->flash('mensaje.contenido', 'No hay suficiente materia prima necesaria para generar la producción!');
                    return redirect()->route('produccionNuevo');
                }
            } else
            {
                $produccion->delete();
                // Mensaje de error al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-close');
                session()->flash('mensaje.titulo', 'Error!');
                session()->flash('mensaje.contenido', 'No existe la conversion de unidades necesaria para generar la producción!');
                return redirect()->route('produccionNuevo');
            }
        }
        /**
         * Fin validacion existencias
         */

//        Se regristan las salidas
        foreach ($formula->componentes as $componente)
        {
//            Se carga el producto
            $producto = Producto::find($componente->producto_id);
            $cantidad = ($componente->porcentaje / 100) * $produccion->cantidad;
//            Se calcula la cantidad y costo

            // Si es la misma medida no se hacen conversiones; si no si se haran las conversiones de unidades
            if ($unidad_medida == $producto->unidad_medida_id)
            {
                // Calculo cantidad y costo de salida
                $cantidad_salida = $cantidad;
                $cantidad_salida_ums = $cantidad;
                // Calculo costo salida
                $cu_salida = $producto->costo;
                $ct_salida = $cantidad_salida * $cu_salida;
                $ct_salida = round($ct_salida,3);
                // Calculo de cantidad y costos existencias
                $cantidad_existencia = $producto->cantidad_existencia - $cantidad_salida;
                $cu_existencia = $producto->costo;
                $ct_existencia = $cantidad_existencia * $cu_existencia;
            } elseif ($producto->unidad_medida->conversiones->where('unidad_medida_destino_id',$unidad_medida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidad_medida_origen_id','=', $producto->unidad_medida_id],
                    ['unidad_medida_destino_id', '=', $unidad_medida],
                ])->first();
                // Se guarda la cantidad de salida
                $cantidad_salida = $cantidad / $factor->factor;
                $cantidad_salida_ums = $cantidad;
                $cantidad_salida = round($cantidad_salida,3);
//                $cantidad_salida_ums = round($cantidad,3);
                // Calculo costo salida
                $cu_salida = $producto->costo;
                $ct_salida = $cantidad_salida * $cu_salida;
                // Calculo de existencias
                $cantidad_existencia = $producto->cantidad_existencia - $cantidad_salida;
                $cu_existencia = $producto->costo;
                $ct_existencia = $cantidad_existencia * $cu_existencia;
            } else
            {
                $produccion->delete();
                // Mensaje de error al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-close');
                session()->flash('mensaje.titulo', 'Error!');
                session()->flash('mensaje.contenido', 'No existe la conversion de unidades necesaria para generar la producción!');
                return redirect()->route('produccionNuevo');
            }

            // Se crea la salida
            $salida = Salida::create([
                'produccion_id' => $produccion->id,
                'cantidad' => $cantidad_salida,
                'cantidad_ums' => $cantidad_salida_ums,
                'unidad_medida_id' => $unidad_medida,
                'precio_unitario' => 0.00,
                'precio_unitario_ums' => 0.00,
                'venta_exenta' => 0.00,
                'venta_gravada' => 0.00,
                'costo_unitario' => $cu_salida,
                'costo_total' => $ct_salida,
            ]);

            $tipo_movimiento = TipoMovimiento::whereCodigo('SALP')->first();
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'salida_id' => $salida->id,
                'fecha' => $produccion->fecha,
                'detalle' => 'Salida de producto por producción',
                'cantidad_existencia' => $cantidad_existencia,
                'costo_unitario_existencia' => $cu_existencia,
                'costo_total_existencia' => $ct_existencia,
                'fecha_procesado' => Carbon::now(),
                'procesado' => true,
            ]);

            $costo_total_produccion += $ct_salida;
//            Se actualiza la existencia del producto
            $producto->cantidad_existencia = $cantidad_existencia;
            $producto->costo = $cu_existencia;
            $producto->update();
        }
//        Se actualiza la cantidad del producto producido
//            Se carga el producto
        $producto = Producto::find($formula->producto_id);
//            Se calcula la cantidad y costo
        $costo_unitario_produccion = $costo_total_produccion / $produccion->cantidad;
        $cantidad = $produccion->cantidad;
        $cu_entrada = $costo_unitario_produccion;
        $ct_entrada = $cantidad * $cu_entrada;
//            Calculo de existencias
        $cantidad_existencia = $producto->cantidad_existencia + $cantidad;
        /**
         * Asignacion de costo bajo costo promedio ponderado
         */
        if ($producto->costo == 0.00) {
            $cu_existencia = $cu_entrada;
            $ct_existencia = $cu_entrada * $cantidad_existencia;
        } else {
            $ct_existencia = $producto->costo * $producto->cantidad_existencia;
            $cu_existencia = ($ct_existencia + $ct_entrada) / $cantidad_existencia;
        }

        // Se crea la entrada
        $entrada = Entrada::create([
            'produccion_id' => $produccion->id,
            'unidad_medida_id' => $produccion->formula->producto->unidad_medida_id,
            'cantidad' => $cantidad,
            'cantidad_ums' => $cantidad,
            'costo_unitario' => $cu_entrada,
            'costo_unitario_ums' => $cu_entrada,
            'costo_total' => $ct_entrada,
        ]);

//            Se crea el movimiento de entrada
        $tipo_movimiento = TipoMovimiento::whereCodigo('ENTP')->first();
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => $tipo_movimiento->id,
            'entrada_id' => $entrada->id,
            'fecha' => $produccion->fecha,
            'detalle' => 'Entrada de producto por producción',
            'cantidad_existencia' => $cantidad_existencia,
            'costo_unitario_existencia' => $cu_existencia,
            'costo_total_existencia' => $ct_existencia,
            'fecha_procesado' => Carbon::now(),
            'procesado' => true,
        ]);

        $producto->cantidad_existencia = $cantidad_existencia;
        $producto->costo = $cu_entrada;
        $producto->save();

//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La producción fue agregada correctamente!');
        return redirect()->route('produccionVer', ['id' => $produccion->id]);
    }
}
