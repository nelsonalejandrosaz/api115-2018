<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Cliente;
use App\TipoAbono;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbonoController extends Controller
{

    /**
     * @return $this
     * Estado: Revisada y funcionando
     * Fecha rev: 20-03-18
     * Observaciones: Falta filtrar por fechas
     */
    public function AbonoLista()
    {
        $abonos = Abono::all();
        return view('abono.abonoLista')
            ->with(['abonos' => $abonos]);
    }

    /**
     * @param $id
     * @return mixed
     *
     */
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

    /**
     * @return $this
     * Estado: Revisado y funcionando
     * Fecha rev: 20-03-18
     */
    public function AbonoNuevoSinVenta()
    {
        $clientes = Cliente::where('saldo','>',0)->get();
        $tipo_abonos = TipoAbono::all();
        return view('abono.abonoNuevoSinDocumento')
            ->with(['clientes' => $clientes])
            ->with(['tipo_abonos' => $tipo_abonos]);
    }

    public function AbonoNuevo($id)
    {
        $venta = Venta::find($id);
        $cliente = Cliente::find($venta->orden_pedido->cliente_id);
        return view('abono.abonoNuevo')
            ->with(['cliente' => $cliente])
            ->with(['venta' => $venta]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function AbonoNuevoPost(Request $request, $id)
    {
        /**
         * Validacion
         */
        $this->validate($request, [
            'fecha' => 'required',
            'cantidad' => 'required',
            'forma_pago_id' => 'required',
        ]);

        // Variables
        $venta = Venta::find($id);
        $cliente = Cliente::find($venta->orden_pedido->cliente_id);
        $cantidad = round($request->input('cantidad'),2);
        if ($cantidad > round($venta->saldo,2))
        {
            // Mensaje de exito
            session()->flash('mensaje.tipo', 'error');
            session()->flash('mensaje.icono', 'fa-close');
            session()->flash('mensaje.titulo', 'Ups!!');
            session()->flash('mensaje.contenido', 'El abono es mayor al saldo de la venta!');
            return redirect()->route('abonoNuevoSinVenta');
        }
        $saldo_anterior = $venta->saldo;
        $venta->saldo = round($venta->saldo,2) - $cantidad;
        $cliente->saldo = round($cliente->saldo,2) - $cantidad;
        // Se crea el abono
        $abono = Abono::create([
            'fecha' => $request->input('fecha'),
            'cantidad' => $cantidad,
            'detalle' => $request->input('detalle'),
            'venta_id' => $venta->id,
            'cliente_id' => $cliente->id,
            'recibo_caja' => $request->input('recibo_caja'),
        ]);

        if ($venta->saldo == 0.00)
        {
            $venta->estado_venta_id = 2;
            $venta->fecha_abono = Carbon::now();
            $venta->save();
        }
        // Mensaje de exito
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El abono fue ingresado correctamente!');
        return redirect()->route('abonoVer',['id' => $abono->id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function AbonoNuevoSinVentaPost(Request $request)
    {
        /**
         * Validacion
         */
        $this->validate($request, [
            'fecha' => 'required',
            'cantidad' => 'required',
            'forma_pago_id' => 'required',
            'cliente_id' => 'required',
            'venta_id' => 'required',
        ]);

        // Variables
        $cliente = Cliente::find($request->input('cliente_id'));
        $venta = Venta::find($request->input('venta_id'));
        $cantidad = round($request->input('cantidad'),2);
        if ($cantidad > round($venta->saldo,2))
        {
            // Mensaje de exito
            session()->flash('mensaje.tipo', 'error');
            session()->flash('mensaje.icono', 'fa-close');
            session()->flash('mensaje.titulo', 'Ups!!');
            session()->flash('mensaje.contenido', 'El abono es mayor al saldo de la venta!');
            return redirect()->route('abonoNuevoSinVenta');
        }
        $saldo_anterior = $venta->saldo;
        $venta->saldo = round($venta->saldo,2) - $cantidad;
        $cliente->saldo = round($cliente->saldo,2) - $cantidad;
        // Se crea el abono
        $abono = Abono::create([
            'fecha' => $request->input('fecha'),
            'cantidad' => $cantidad,
            'detalle' => $request->input('detalle'),
            'venta_id' => $venta->id,
            'cliente_id' => $cliente->id,
            'forma_pago_id' => $request->input('forma_pago_id'),
            'recibo_caja' => $request->input('recibo_caja'),
        ]);

        if ($venta->saldo == 0.00)
        {
            $venta->estado_venta_id = 2;
            $venta->fecha_liquidado = Carbon::now();
        }
        $venta->save();
        $cliente->save();
        // Mensaje de exito
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El abono fue ingresado correctamente!');
        return redirect()->route('abonoVer',['id' => $abono->id]);
    }

    public function revertirAbono($id) {
        $abono = Abono::find($id);
//        dd($abono);
        $cantidad = round($abono->cantidad,2);
        $cliente = Cliente::find($abono->venta->cliente_id);
        $venta = Venta::find($abono->venta_id);
        $cliente->saldo = $cliente->saldo + $cantidad;
        $cliente->save();
        $venta->saldo = $venta->saldo + $cantidad;
        $venta->estado_venta_id = 1;
        $venta->save();
        $abono->delete();
        // Mensaje de exito
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El abono fue revertido correctamente!');
        return redirect()->route('abonoLista');
    }
}
