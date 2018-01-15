<?php

namespace App\Http\Controllers;

use App\ConversionUnidadMedida;
use App\Entrada;
use App\Formula;
use App\Movimiento;
use App\Produccion;
use App\Producto;
use App\Salida;
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
        $unidadMedida = $produccion->formula->producto->unidad_medida_id;
        foreach ($produccion->salidas as $salida)
        {
//            $salida = Salida::find(1);
            if ($salida->movimiento->producto->unidadMedida->conversiones->where('unidadMedidaDestino_id',$unidadMedida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidadMedidaOrigen_id','=', $salida->movimiento->producto->unidad_medida_id],
                    ['unidadMedidaDestino_id', '=', $unidadMedida],
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
            'bodeguero_id' => Auth::user()->id,
            'cantidad' => $request->input('cantidad'),
            'fecha' => $request->input('fecha'),
            'detalle' => $request->input('detalle'),
        ]);

//        Se guardan en variales los componentes
        $formula = Formula::find($produccion->formula_id);
        $unidadMedida = $formula->producto->unidad_medida_id;
        $costoTotalProduccion = 0.00;

        /**
         * Validacion de existencias
         */
        foreach ($formula->componentes as $componente)
        {
            $producto = Producto::find($componente->producto_id);
            $cantidad = ($componente->porcentaje / 100) * $produccion->cantidad;
//            Se calcula la cantidad y costo

            // Si es la misma medida no se hacen conversiones; si no si se haran las conversiones de unidades
            if ($unidadMedida == $producto->unidad_medida_id)
            {
                $cantidadSalida = $cantidad;
//                dd($producto->cantidadExistencia);
                if ($producto->cantidadExistencia < $cantidadSalida){
                    // No alcanza la existencia para produccion
                    $produccion->delete();
                    // Mensaje de error al guardar
                    session()->flash('mensaje.tipo', 'danger');
                    session()->flash('mensaje.icono', 'fa-close');
                    session()->flash('mensaje.titulo', 'Error!');
                    session()->flash('mensaje.contenido', 'No hay suficiente materia prima necesaria para generar la producción!');
                    return redirect()->route('produccionNuevo');
                }

            } elseif ($producto->unidadMedida->conversiones->where('unidadMedidaDestino_id',$unidadMedida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidadMedidaOrigen_id','=', $producto->unidad_medida_id],
                    ['unidadMedidaDestino_id', '=', $unidadMedida],
                ])->first();
                // Se guarda la cantidad de salida
                $cantidadSalida = $cantidad / $factor->factor;
                if ($producto->cantidadExistencia < $cantidadSalida){
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
            if ($unidadMedida == $producto->unidad_medida_id)
            {
                // Calculo cantidad y costo de salida
                $cantidadSalida = $cantidad;
                $cantidadSalidaOP = $cantidad;
                // Calculo costo salida
                $cuSalida = $producto->costo;
                $ctSalida = $cantidadSalida * $cuSalida;
                $ctSalida = round($ctSalida,3);
                // Calculo de cantidad y costos existencias
                $cantidadExistencia = $producto->cantidadExistencia - $cantidadSalida;
                $cuExistencia = $producto->costo;
                $ctExistencia = $cantidadExistencia * $cuExistencia;
            } elseif ($producto->unidadMedida->conversiones->where('unidadMedidaDestino_id',$unidadMedida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidadMedidaOrigen_id','=', $producto->unidad_medida_id],
                    ['unidadMedidaDestino_id', '=', $unidadMedida],
                ])->first();
                // Se guarda la cantidad de salida
                $cantidadSalida = $cantidad / $factor->factor;
                $cantidadSalidaOP = $cantidad;
                $cantidadSalida = round($cantidadSalida,3);
                // Calculo costo salida
                $cuSalida = $producto->costo;
                $ctSalida = $cantidadSalida * $cuSalida;
                // Calculo de existencias
                $cantidadExistencia = $producto->cantidadExistencia - $cantidadSalida;
                $cuExistencia = $producto->costo;
                $ctExistencia = $cantidadExistencia * $cuExistencia;
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
            // Aqui va validacion existencia de producto

            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => 2,
                'fecha' => $produccion->fecha,
                'detalle' => 'Salida de producto por producción',
                'cantidadExistencia' => $cantidadExistencia,
                'costoUnitarioExistencia' => $cuExistencia,
                'costoTotalExistencia' => $ctExistencia,
                'fechaProcesado' => Carbon::now(),
                'procesado' => true,
            ]);
//            Se crea la salida
            $salida = Salida::create([
                'movimiento_id' => $movimiento->id,
                'produccion_id' => $produccion->id,
                'cantidad' => $cantidadSalida,
                'cantidadOP' => $cantidadSalidaOP,
                'unidad_medida_id' => $unidadMedida,
                'precioUnitario' => 0.00,
                'precioUnitarioOP' => 0.00,
                'ventaExenta' => 0.00,
                'ventaGravada' => 0.00,
                'costoUnitario' => $cuSalida,
                'costoTotal' => $ctSalida,
            ]);
            $costoTotalProduccion += $ctSalida;
//            Se actualiza la existencia del producto
            $producto->cantidadExistencia = $cantidadExistencia;
            $producto->costo = $cuExistencia;
            $producto->update();
        }
//        Se actualiza la cantidad del producto producido
//            Se carga el producto
        $producto = Producto::find($formula->producto_id);
//            Se calcula la cantidad y costo
        $costoUnitarioProduccion = $costoTotalProduccion / $produccion->cantidad;
        $cantidad = $produccion->cantidad;
        $cuEntrada = $costoUnitarioProduccion;
        $ctEntrada = $cantidad * $cuEntrada;
//            Calculo de existencias
        $cantidadExistencia = $producto->cantidadExistencia + $cantidad;
        /**
         * Asignacion de costo bajo costo promedio ponderado
         */
        if ($producto->costo == 0.00) {
            $cuExistencia = $cuEntrada;
            $ctExistencia = $cuEntrada * $cantidadExistencia;
        } else {
            $ctExistencia = $producto->costo * $producto->cantidadExistencia;
            $cuExistencia = ($ctExistencia + $ctEntrada) / $cantidadExistencia;
        }
//            Se crea el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => 1,
            'fecha' => $produccion->fecha,
            'detalle' => 'Entrada de producto por producción',
            'cantidadExistencia' => $cantidadExistencia,
            'costoUnitarioExistencia' => $cuExistencia,
            'costoTotalExistencia' => $ctExistencia,
            'fechaProcesado' => Carbon::now(),
            'procesado' => true,
        ]);
//            Se crea la entrada
        $entrada = Entrada::create([
            'movimiento_id' => $movimiento->id,
            'produccion_id' => $produccion->id,
            'cantidad' => $cantidad,
            'costoUnitario' => $cuEntrada,
            'costoTotal' => $ctEntrada,
        ]);
        $producto->cantidadExistencia = $cantidadExistencia;
        $producto->costo = $cuEntrada;
        $producto->update();
//        $produccion->save();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La producción fue agregada correctamente!');
        return redirect()->route('produccionVer', ['id' => $produccion->id]);
    }
}
