<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\EstadoOrdenPedido;
use App\EstadoVenta;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use App\TipoDocumento;
use App\Venta;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use NumeroALetras;

class VentaController extends Controller
{
    public function VentaOrdenesLista()
    {
        $ordenesPedidoProcesadas = OrdenPedido::whereEstadoId(2)->get();
        return view('venta.ventaLista')->with(['ordenesPedidos' => $ordenesPedidoProcesadas]);
    }

    public function VentaFacturaLista()
    {
        $ventas = Venta::whereTipoDocumentoId(1)->get();
        return view('venta.ventaFacturaLista')->with(['ventas' => $ventas]);
    }

    public function VentaCCFLista()
    {
        $ventas = Venta::whereTipoDocumentoId(2)->get();
        return view('venta.ventaCCFLista')->with(['ventas' => $ventas]);
    }

    public function VentaNueva($id)
    {
        $orden_pedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $tipoDocumentos = TipoDocumento::all();
        return view('venta.ventaNuevo')
            ->with(['orden_pedido' => $orden_pedido])
            ->with(['productos' => $productos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['tipoDocumentos' => $tipoDocumentos]);
    }

    public function VentaNuevaPost(Request $request, $id)
    {
        // Validacion
        $this->validate($request, [
            'fecha' => 'required',
            'numero' => 'required',
            'tipo_documento_id' => 'required',
        ]);

        // Se carga la orden de pedido
        $orden_pedido = OrdenPedido::find($id);
        // Se busca el estado de la orden de pedido y de factura
        $estado_orden_pedido = EstadoOrdenPedido::whereCodigo('FC')->first();
        $estado_venta = EstadoVenta::whereCodigo('PP')->first();
        $venta_total_con_impuestos = $orden_pedido->venta_total * 1.13;
        // Se crea la venta
        $venta = Venta::create([
            'tipo_documento_id' => $request->input('tipo_documento_id'),
            'orden_pedido_id' => $orden_pedido->id,
            'numero' => $request->input('numero'),
            'fecha' => $request->input('fecha'),
            'estado_venta_id' => $estado_venta->id,
            'vendedor_id' => $orden_pedido->vendedor_id, // Quitar despues
            'saldo' => $venta_total_con_impuestos,
            'venta_total_con_impuestos' => $venta_total_con_impuestos,
        ]);
        // Se agrega el saldo al cliente  agregar el iva
        $cliente = Cliente::find($orden_pedido->cliente_id);
        $cliente->saldo = $cliente->saldo + $venta_total_con_impuestos;
        $cliente->save();
        //
        $orden_pedido->estado_id = $estado_orden_pedido->id;
        $orden_pedido->save();
        if ($venta->tipo_documento->codigo == 'FAC')
        {
            // Mensaje de exito al guardar
            session()->flash('mensaje.tipo', 'success');
            session()->flash('mensaje.icono', 'fa-check');
            session()->flash('mensaje.contenido', 'La factura fue procesada correctamente!');
            return redirect()->route('ventaVerFactura',['id' => $venta->id]);
        } else
        {
            // Mensaje de exito al guardar
            session()->flash('mensaje.tipo', 'success');
            session()->flash('mensaje.icono', 'fa-check');
            session()->flash('mensaje.contenido', 'El crédito fiscal fue procesada correctamente!');
            return redirect()->route('ventaVerCFF',['id' => $venta->id]);
        }
    }

    public function VentaVerFactura($id)
    {
        // Se carga la venta
        $venta = Venta::find($id);
        $productos = Producto::all();
        $salidas = $venta->orden_pedido->salidas;
        foreach ($salidas as $salida)
        {
            $salida->precio_unitario = $salida->precio_unitario * 1.13;
            $salida->venta_gravada = $salida->venta_gravada * 1.13;
            $salida->venta_exenta = $salida->venta_exenta * 1.13;
        }
        $venta->orden_pedido->ventas_exentas = $venta->orden_pedido->ventas_exentas * 1.13;
        $venta->orden_pedido->ventas_gravadas = $venta->orden_pedido->ventas_gravadas * 1.13;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * 1.13;
        return view('venta.ventaFacturaVer')
            ->with(['venta' => $venta])
            ->with(['productos' => $productos])
            ->with(['salidas' => $salidas]);
    }

    public function VentaVerCCF($id)
    {
        // Se carga la venta
        $venta = Venta::find($id);
        $productos = Producto::all();
        $salidas = $venta->orden_pedido->salidas;
        $venta->orden_pedido->porcentaje_IVA = $venta->orden_pedido->ventas_gravadas * 0.13;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * 1.13;
        return view('venta.ventaCCFVer')
            ->with(['venta' => $venta])
            ->with(['productos' => $productos])
            ->with(['salidas' => $salidas]);
    }

    public function VentaFacturaPDF($id)
    {
        $venta = Venta::find($id);
        $venta->orden_pedido->vendedor->nombreCompleto = $venta->orden_pedido->vendedor->nombre . " " . $venta->orden_pedido->vendedor->apellido;
        foreach ($venta->orden_pedido->salidas as $salida)
        {
            $salida->precioUnitario = $salida->precioUnitario * 1.13;
            $salida->ventaGravada = $salida->ventaGravada * 1.13;
            $salida->ventaExenta = $salida->ventaExenta * 1.13;
        }
        $venta->orden_pedido->ventas_exentas = $venta->orden_pedido->ventas_exentas * 1.13;
        $venta->orden_pedido->ventas_gravadas = $venta->orden_pedido->ventas_gravadas * 1.13;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * 1.13;
        $venta_total = number_format($venta->orden_pedido->venta_total,2);
        $venta->orden_pedido->venta_total_letras = NumeroALetras::convertir($venta_total,'dolares','centavos');
        $pdf = PDF::loadView('pdf.facturaPDF',compact('venta'));
        $nombre_factura = "Factura numero " . $venta->numero . ".pdf";
        return $pdf->stream($nombre_factura);
    }

    public function VentaCCFPDF($id)
    {
        $venta = Venta::find($id);
        $venta->orden_pedido->vendedor->nombreCompleto = $venta->orden_pedido->vendedor->nombre . " " . $venta->orden_pedido->vendedor->apellido;
        $venta->orden_pedido->porcentaje_IVA = $venta->orden_pedido->ventas_gravadas * 0.13;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * 1.13;
        $ventaTotal = number_format($venta->orden_pedido->venta_total,2);
        $venta->orden_pedido->venta_total_letras = NumeroALetras::convertir($ventaTotal,'dolares','centavos');
        $pdf = PDF::loadView('pdf.creditoFiscalPDF',compact('venta'));
        $nombre_factura = "CCF numero " . $venta->numero . ".pdf";
        return $pdf->stream($nombre_factura);
    }
}
