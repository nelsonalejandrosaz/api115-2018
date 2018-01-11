<?php

namespace App\Http\Controllers;

use App\Cliente;
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
        return view('venta.ventaFacturaLista')->with(['ventas' => $ventas]);
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

        // Se carga la orden de pedido
        $ordenPedido = OrdenPedido::find($id);
        // Se crea la venta
        $venta = Venta::create([
            'tipo_documento_id' => $request->input('tipo_documento_id'),
            'numero' => $request->input('numero'),
            'fechaIngreso' => $request->input('fechaIngreso'),
            'vendedor_id' => $ordenPedido->vendedor_id,
            'nit' => $request->input('nit'),
            'nrc' => $request->input('nrc'),
        ]);
        $ordenPedido->venta_id = $venta->id;
        $ordenPedido->estado_id = 3;
        $ordenPedido->save();
        if ($venta->tipoDocumento->codigo == 'FAC')
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
        $salidas = $venta->ordenPedido->salidas;
        $ordenPedido = $venta->ordenPedido;
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $tipoDocumentos = TipoDocumento::all();
        foreach ($salidas as $salida)
        {
            $salida->precioUnitario = $salida->precioUnitario * 1.13;
            $salida->ventaGravada = $salida->ventaGravada * 1.13;
            $salida->ventaExenta = $salida->ventaExenta * 1.13;
        }
        $ordenPedido->ventasExentas = $ordenPedido->ventasExentas * 1.13;
        $ordenPedido->ventasGravadas = $ordenPedido->ventasGravadas * 1.13;
        $ordenPedido->ventaTotal = $ordenPedido->ventaTotal * 1.13;
        return view('venta.ventaFacturaVer')
            ->with(['ordenPedido' => $ordenPedido])
            ->with(['venta' => $venta])
            ->with(['productos' => $productos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['tipoDocumentos' => $tipoDocumentos])
            ->with(['salidas' => $salidas]);

    }

    public function VentaVerCCF($id)
    {
        // Se carga la venta
        $venta = Venta::find($id);
        $salidas = $venta->ordenPedido->salidas;
        $ordenPedido = $venta->ordenPedido;
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $tipoDocumentos = TipoDocumento::all();
        $ordenPedido->porcentajeIVA = $ordenPedido->ventasGravadas * 0.13;
        $ordenPedido->ventaTotal = $ordenPedido->ventaTotal * 1.13;
        return view('venta.ventaCCFVer')
            ->with(['ordenPedido' => $ordenPedido])
            ->with(['venta' => $venta])
            ->with(['productos' => $productos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['tipoDocumentos' => $tipoDocumentos])
            ->with(['salidas' => $salidas]);
    }

    public function VentaFacturaPDF($id)
    {
        $venta = Venta::find($id);
        $venta->ordenPedido->vendedor->nombreCompleto = $venta->ordenPedido->vendedor->nombre . " " . $venta->ordenPedido->vendedor->apellido;
        foreach ($venta->ordenPedido->salidas as $salida)
        {
            $salida->precioUnitario = $salida->precioUnitario * 1.13;
            $salida->ventaGravada = $salida->ventaGravada * 1.13;
            $salida->ventaExenta = $salida->ventaExenta * 1.13;
        }
        $venta->ordenPedido->ventasExentas = $venta->ordenPedido->ventasExentas * 1.13;
        $venta->ordenPedido->ventasGravadas = $venta->ordenPedido->ventasGravadas * 1.13;
        $venta->ordenPedido->ventaTotal = $venta->ordenPedido->ventaTotal * 1.13;
        $ventaTotal = number_format($venta->ordenPedido->ventaTotal,2);
        $venta->ordenPedido->ventaTotalLetras = NumeroALetras::convertir($ventaTotal,'dolares','centavos');
        $pdf = PDF::loadView('pdf.facturaPDF',compact('venta'));
        $nombreFactura = "Factura numero " . $venta->numero . ".pdf";
        return $pdf->stream($nombreFactura);
    }

    public function VentaCCFPDF($id)
    {
        $venta = Venta::find($id);
        $venta->ordenPedido->vendedor->nombreCompleto = $venta->ordenPedido->vendedor->nombre . " " . $venta->ordenPedido->vendedor->apellido;
        $venta->ordenPedido->porcentajeIVA = $venta->ordenPedido->ventasGravadas * 0.13;
        $venta->ordenPedido->ventaTotal = $venta->ordenPedido->ventaTotal * 1.13;
        $ventaTotal = number_format($venta->ordenPedido->ventaTotal,2);
        $venta->ordenPedido->ventaTotalLetras = NumeroALetras::convertir($ventaTotal,'dolares','centavos');
        $pdf = PDF::loadView('pdf.creditoFiscalPDF',compact('venta'));
        $nombreFactura = "Factura numero " . $venta->numero . ".pdf";
        return $pdf->stream($nombreFactura);
    }
}
