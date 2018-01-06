<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use App\TipoDocumento;
use App\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function VentaListaOrdenesProcesadas()
    {
        $ventas = Venta::all();
        $ordenesPedidoProcesadas = OrdenPedido::whereProcesado(true)->get();
        return view('venta.ventaLista')->with(['ordenesPedidos' => $ordenesPedidoProcesadas]);
    }

    public function VentaNueva($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $tipoDocumentos = TipoDocumento::all();
        return view('venta.ventaNuevo')
            ->with(['ordenPedido' => $ordenPedido])
            ->with(['productos' => $productos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['tipoDocumentos' => $tipoDocumentos]);
    }

    public function VentaNuevaPost(Request $request, $id)
    {
        // Validacion
        $this->validate($request, [
            'fechaIngreso' => 'required',
            'nrc' => 'required',
            'numero' => 'required',
            'tipo_documento_id.*' => 'required',
        ]);

        dd($request);

        // Se carga la orden de pedido
        $ordenPedido = OrdenPedido::find($id);
        // Se crea la venta
        $venta = Venta::create([
            'tipo_documento_id' => $request->input('tipo_documento_id'),
            'numero' => $request->input('numero'),
            'fechaIngreso' => $request->input('fechaIngreso'),
            'vendedor_id' => $ordenPedido->vendedor_id,
            'nit' => $request->input('nit'),
        ]);
        if ($venta->tipoDocumento->codigo == 'FAC')
        {
            // Mensaje de exito al guardar
            session()->flash('mensaje.tipo', 'success');
            session()->flash('mensaje.icono', 'fa-check');
            session()->flash('mensaje.contenido', 'La factura fue procesada correctamente!');
            return redirect()->route('ordenPedidoListaBodega');
        } else
        {
            // Mensaje de exito al guardar
            session()->flash('mensaje.tipo', 'success');
            session()->flash('mensaje.icono', 'fa-check');
            session()->flash('mensaje.contenido', 'El crédito fiscal fue procesada correctamente!');
            return redirect()->route('ordenPedidoListaBodega');
        }

    }
}
