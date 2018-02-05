<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Movimiento;
use App\Producto;
use App\TipoAjuste;
use App\TipoMovimiento;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjusteController extends Controller
{
    public function AjusteLista()
    {
        $ajustes = Ajuste::all();
        return view('ajuste.ajusteLista')->with(['ajustes' => $ajustes]);
    }

    public function AjusteNuevo()
    {
        $productos = Producto::all();
        $tipoAjustes = TipoAjuste::all();
        return view('ajuste.ajusteNuevo')->with(['productos' => $productos])->with(['tipoAjustes' => $tipoAjustes]);
    }

    public function AjusteNuevoPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'fecha' => 'required',
            'producto_id' => 'required',
            'tipo_ajuste_id' => 'required',
            'cantidad_ajuste' => 'required',
        ]);
        // Fin Validacion

        // Variables
        $cantidad_ajuste = $request->input('cantidad_ajuste');
        $tipo_ajuste = TipoAjuste::find($request->input('tipo_ajuste_id'));
        // Se carga el producto a ajustar
        $producto = Producto::find($request->input('producto_id'));
        $diferencia_ajuste = abs($cantidad_ajuste - $producto->cantidad_existencia);

        // Se crea el ajuste de entrada
        $ajuste = Ajuste::create([
            'tipo_ajuste_id' => $tipo_ajuste->id,
            'detalle' => $request->input('detalle'),
            'fecha' => $request->input('fecha'),
            'cantidad_ajuste' => $cantidad_ajuste,
            'valor_unitario_ajuste' => $producto->costo,
            'realizado_id' => Auth::user()->id,
            'cantidad_anterior' => $producto->cantidad_existencia,
            'valor_unitario_anterior' => $producto->costo,
            'diferencia_ajuste' => $diferencia_ajuste,
        ]);

        if ($ajuste->tipo_ajuste->tipo == 'ENTRADA')
        {
            $tipo_movimiento = TipoMovimiento::whereCodigo('AJSE')->first();
        } else
        {
            $tipo_movimiento = TipoMovimiento::whereCodigo('AJSS')->first();
        }
        // Se crea el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => $tipo_movimiento->id,
            'ajuste_id' => $ajuste->id,
            'fecha' => $request->input('fecha'),
            'detalle' => "Ajuste de " . strtolower($tipo_ajuste->tipo) . " por " . $tipo_ajuste->nombre. " realizado por " . Auth::user()->nombre,
            'cantidad' => $diferencia_ajuste,
            'costo_unitario' => $producto->costo,
            'costo_total' => $cantidad_ajuste * $producto->costo,
            'cantidad_existencia' => $cantidad_ajuste,
            'costo_unitario_existencia' => $producto->costo,
            'costo_total_existencia' => $cantidad_ajuste * $producto->costo,
            'fecha_procesado' => Carbon::now(),
            'procesado' => true,
        ]);

        // Se actualiza la cantidad de producto despues de la entrada
        $producto->cantidad_existencia = $movimiento->cantidad_existencia;
        $producto->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El ajuste fue realizado correctamente!');
        return redirect()->route('ajusteLista');
    }

    public function AjusteCostoNuevo()
    {
        $productos = Producto::all();
        $tipoAjustes = TipoAjuste::all();
        return view('ajuste.ajusteCostoNuevo')->with(['productos' => $productos])->with(['tipoAjustes' => $tipoAjustes]);
    }

    public function AjusteCostoNuevoPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'fecha' => 'required',
            'producto_id' => 'required',
            'tipo_ajuste_id' => 'required',
            'costo_ajuste' => 'required',
        ]);
        // Fin Validacion

        // Variables
        $costo_ajuste = $request->input('costo_ajuste');
        $tipo_ajuste = TipoAjuste::find($request->input('tipo_ajuste_id'));
        // Se carga el producto a ajustar
        $producto = Producto::find($request->input('producto_id'));
        $diferencia_ajuste = 0;

        // Se crea el ajuste de entrada
        $ajuste = Ajuste::create([
            'tipo_ajuste_id' => $tipo_ajuste->id,
            'detalle' => $request->input('detalle'),
            'fecha' => $request->input('fecha'),
            'cantidad_ajuste' => 0,
            'valor_unitario_ajuste' => $costo_ajuste,
            'realizado_id' => Auth::user()->id,
            'cantidad_anterior' => $producto->cantidad_existencia,
            'valor_unitario_anterior' => $producto->costo,
            'diferencia_ajuste' => $diferencia_ajuste,
        ]);

        $tipo_movimiento = TipoMovimiento::whereCodigo('AJSC')->first();
        // Se crea el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => $tipo_movimiento->id,
            'ajuste_id' => $ajuste->id,
            'fecha' => $request->input('fecha'),
            'detalle' => "Ajuste de " . strtolower($tipo_ajuste->tipo) . " por " . $tipo_ajuste->nombre. " realizado por " . Auth::user()->nombre,
            'cantidad' => 0,
            'costo_unitario' => 0,
            'costo_total' => 0,
            'cantidad_existencia' => $producto->cantidad_existencia,
            'costo_unitario_existencia' => $costo_ajuste,
            'costo_total_existencia' => $producto->cantidad_existencia * $costo_ajuste,
            'fecha_procesado' => Carbon::now(),
            'procesado' => true,
        ]);

        // Se actualiza la cantidad de producto después de la entrada
        $producto->costo = $costo_ajuste;
        $producto->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El ajuste fue realizado correctamente!');
        return redirect()->route('ajusteLista');
    }

    public function AjusteVer($id)
    {
        $ajuste = Ajuste::find($id);
        $productos = Producto::all();
        $tipoAjustes = TipoAjuste::all();
        return view('ajuste.ajusteVer')->with(['productos' => $productos])->with(['tipoAjustes' => $tipoAjustes])->with(['ajuste' => $ajuste]);
    }
}
