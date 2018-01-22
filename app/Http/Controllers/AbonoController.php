<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Cliente;
use App\Venta;
use Illuminate\Http\Request;

class AbonoController extends Controller
{
    public function AbonoLista()
    {
        $abonos = Abono::all();
        return view('abono.abonoLista')
            ->with(['abonos' => $abonos]);
    }

    public function AbonoVer($id)
    {
        $abono = Abono::find($id);
        $venta = Venta::find($abono->venta_id);
        $cliente = Cliente::find($abono->cliente_id);
        return view('abono.abonoVer')
            ->with(['abono' => $abono])
            ->with(['cliente' => $cliente])
            ->with(['venta' => $venta]);
    }

    public function AbonoNuevo($id)
    {
        $venta = Venta::find($id);
        $cliente = Cliente::find($venta->orden_pedido->cliente_id);
        return view('abono.abonoNuevo')
            ->with(['cliente' => $cliente])
            ->with(['venta' => $venta]);
    }

    public function AbonoNuevoPost(Request $request, $id)
    {
        /**
         * Validacion
         */
        $this->validate($request, [
            'fecha' => 'required',
            'cantidad' => 'required'
        ]);

        // Variables
        $venta = Venta::find($id);
        $cliente = Cliente::find($venta->orden_pedido->cliente_id);
        $cantidad = $request->input('cantidad');
        $venta->saldo = $venta->saldo - $cantidad;
        $cliente->saldo = $cliente->saldo - $cantidad;
        // Se crea el abono
        $abono = Abono::create([
            'fecha' => $request->input('fecha'),
            'cantidad' => $cantidad,
            'detalle' => $request->input('detalle'),
            'venta_id' => $venta->id,
            'cliente_id' => $cliente->id,
        ]);
        if ($venta->saldo >= 0.00)
        {
            $venta->save();
        } else
        {
            $abono->delete();
            // Mensaje de error
            session()->flash('mensaje.tipo', 'warning');
            session()->flash('mensaje.icono', 'fa-close');
            session()->flash('mensaje.titulo','Upssss');
            session()->flash('mensaje.contenido', 'El abono es mayor al saldo de la factura!');
            return redirect()->route('abonoNuevo',['id' => $venta->id]);
        }
        if ($cliente->saldo >= 0.00)
        {
            $cliente->save();
        } else
        {
            $abono->delete();
            // Mensaje de error
            session()->flash('mensaje.tipo', 'warning');
            session()->flash('mensaje.icono', 'fa-close');
            session()->flash('mensaje.titulo','Upssss');
            session()->flash('mensaje.contenido', 'El abono es mayor al saldo de la factura!');
            return redirect()->route('abonoNuevo',['id' => $venta->id]);
        }
        // Mensaje de exito
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El abono fue ingresado correctamente!');
        return redirect()->route('abonoVer',['id' => $abono->id]);
    }
}
