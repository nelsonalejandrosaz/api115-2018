<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Cliente;
use App\Configuracion;
use App\EstadoOrdenPedido;
use App\EstadoVenta;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use App\TipoAjuste;
use App\TipoDocumento;
use App\TipoMovimiento;
use App\Venta;
use Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumeroALetras;

class VentaController extends Controller
{
    public function VentaOrdenesLista()
    {
        $ordenesPedidoProcesadas = OrdenPedido::whereEstadoId(2)->get();
        if (Auth::user()->rol->nombre == 'Vendedor')
        {
            $ordenesPedidoProcesadas = OrdenPedido::whereEstadoId(2);
            $ordenesPedidoProcesadas = $ordenesPedidoProcesadas->where('vendedor_id','=',Auth::user()->id)->get();
        }
        return view('venta.ventaLista')->with(['ordenesPedidos' => $ordenesPedidoProcesadas]);
    }

    public function VentaLista($filtro)
    {
        switch ($filtro)
        {
            case 'todo':
                $ventas = Venta::where('tipo_documento_id','>','0');
                break;
            case 'factura':
                $ventas = Venta::where('tipo_documento_id', '=', '1');
                break;
            case 'ccf':
                $ventas = Venta::where('tipo_documento_id', '=', '2');
                break;
            case 'anulada':
                $ventas = Venta::where('estado_venta_id', '=', '3');
                break;
        }
        if (Auth::user()->rol->nombre == 'Vendedor')
        {
            $ventas = $ventas->where('vendedor_id','=',Auth::user()->id)->get();
        } else
        {
            $ventas = $ventas->get();
        }
        return view('venta.ventaFacturaLista')->with(['ventas' => $ventas]);
    }

    public function VentasTodoLista(Request $request)
    {

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
        $dia_hoy = Carbon::now();
        $cierre = Carbon::parse($dia_hoy->format('Y-m-d'));
        $cierre = $cierre->addHours(15)->addMinutes(30);
        if ($dia_hoy > $cierre)
        {
            $dia_hoy = $dia_hoy->addDay();
        }
        if ($orden_pedido->tipo_documento->codigo == 'FAC')
        {
            return view('venta.ventaFCFNuevo')
                ->with(['orden_pedido' => $orden_pedido])
                ->with(['productos' => $productos])
                ->with(['clientes' => $clientes])
                ->with(['municipios' => $municipios])
                ->with(['tipoDocumentos' => $tipoDocumentos])
                ->with(['dia' => $dia_hoy]);
        } else
        {
            return view('venta.ventaCCFNuevo')
                ->with(['orden_pedido' => $orden_pedido])
                ->with(['productos' => $productos])
                ->with(['clientes' => $clientes])
                ->with(['municipios' => $municipios])
                ->with(['tipoDocumentos' => $tipoDocumentos])
                ->with(['dia' => $dia_hoy]);
        }
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
        $iva = Configuracion::find(1)->iva;
        $venta_total_con_impuestos = $orden_pedido->venta_total * 1.13;
        // Se crea la venta
        $venta = Venta::create([
            'tipo_documento_id' => $request->input('tipo_documento_id'),
            'orden_pedido_id' => $orden_pedido->id,
            'numero' => $request->input('numero'),
            'fecha' => $request->input('fecha'),
            'cliente_id' => $orden_pedido->cliente_id,
            'estado_venta_id' => $estado_venta->id,
            'vendedor_id' => $orden_pedido->vendedor_id,
            'saldo' => $venta_total_con_impuestos,
            'venta_total' => $orden_pedido->venta_total,
            'venta_total_con_impuestos' => $venta_total_con_impuestos,
        ]);
        // Se agrega el saldo al cliente  agregar el iva
        $cliente = Cliente::find($orden_pedido->cliente_id);
        $cliente->saldo = $cliente->saldo + $venta_total_con_impuestos;
        $cliente->save();
        //
        $orden_pedido->estado_id = $estado_orden_pedido->id;
        $orden_pedido->save();
        if ($venta->tipo_documento->codigo == 'FAC') {
            // Mensaje de exito al guardar
            session()->flash('mensaje.tipo', 'success');
            session()->flash('mensaje.icono', 'fa-check');
            session()->flash('mensaje.contenido', 'La factura fue procesada correctamente!');
            return redirect()->route('ventaVerFactura', ['id' => $venta->id]);
        } else {
            // Mensaje de exito al guardar
            session()->flash('mensaje.tipo', 'success');
            session()->flash('mensaje.icono', 'fa-check');
            session()->flash('mensaje.contenido', 'El crédito fiscal fue procesada correctamente!');
            return redirect()->route('ventaVerCFF', ['id' => $venta->id]);
        }
    }

    public function VentaVerFactura($id)
    {
        // Se carga la venta
        $venta = Venta::find($id);
        $productos = Producto::all();
        $salidas = $venta->orden_pedido->salidas;
        foreach ($salidas as $salida) {
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
        foreach ($venta->orden_pedido->salidas as $salida) {
            $salida->precio_unitario = $salida->precio_unitario * 1.13;
            $salida->venta_gravada = $salida->venta_gravada * 1.13;
            $salida->venta_exenta = $salida->venta_exenta * 1.13;
        }
        $venta->orden_pedido->ventas_exentas = $venta->orden_pedido->ventas_exentas * 1.13;
        $venta->orden_pedido->ventas_gravadas = $venta->orden_pedido->ventas_gravadas * 1.13;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * 1.13;
        $venta_total = number_format($venta->orden_pedido->venta_total, 2);
        $venta->orden_pedido->venta_total_letras = NumeroALetras::convertir($venta_total, 'dolares', 'centavos');
        return view('pdf.facturaPDF')->with(['venta' => $venta]);
        $pdf = PDF::loadView('pdf.facturaPDF', compact('venta'));
        $nombre_factura = "Factura numero " . $venta->numero . ".pdf";
        return $pdf->stream($nombre_factura);
    }

    public function VentaCCFPDF($id)
    {
        $venta = Venta::find($id);
        $venta->orden_pedido->vendedor->nombreCompleto = $venta->orden_pedido->vendedor->nombre . " " . $venta->orden_pedido->vendedor->apellido;
        $venta->orden_pedido->porcentaje_IVA = $venta->orden_pedido->ventas_gravadas * 0.13;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * 1.13;
        $ventaTotal = number_format($venta->orden_pedido->venta_total, 2);
        $venta->orden_pedido->venta_total_letras = NumeroALetras::convertir($ventaTotal, 'dolares', 'centavos');
        return view('pdf.creditoFiscalPDF')->with(['venta' => $venta]);
        $pdf = PDF::loadView('pdf.creditoFiscalPDF', compact('venta'));
        $nombre_factura = "CCF numero " . $venta->numero . ".pdf";
        return $pdf->stream($nombre_factura);
    }

    public function VentaAnular($id)
    {
        $venta = Venta::find($id);
        $salidas = $venta->orden_pedido->salidas;
        foreach ($salidas as $salida)
        {
            // Variables
            // Se carga el producto
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidad_ajuste = $producto->cantidad_existencia + $salida->movimiento->cantidad;
            $diferencia_ajuste = $salida->movimiento->cantidad;
            $tipo_ajuste = TipoAjuste::whereCodigo('ENTANU')->first();

            // Se crea el ajuste de entrada
            $ajuste = Ajuste::create([
                'tipo_ajuste_id' => $tipo_ajuste->id,
                'detalle' => 'Ajuste de entrada por anulación de documento de venta',
                'fecha' => Carbon::now(),
                'cantidad_ajuste' => $cantidad_ajuste,
                'valor_unitario_ajuste' => $producto->costo,
                'realizado_id' => Auth::user()->id,
                'cantidad_anterior' => $producto->cantidad_existencia,
                'valor_unitario_anterior' => $producto->costo,
                'diferencia_ajuste' => $diferencia_ajuste,
            ]);

            $tipo_movimiento = TipoMovimiento::whereCodigo('AJSE')->first();
            // Se crea el movimiento
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'ajuste_id' => $ajuste->id,
                'fecha' => Carbon::now(),
                'detalle' => 'Ajuste de entrada por anulación de documento venta n°: ' . $venta->numero,
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
        }
        // Se actualiza el estado de la venta y se resta el saldo al cliente
        $cliente = Cliente::find($venta->orden_pedido->cliente_id);
        $cliente->saldo = $cliente->saldo - $venta->saldo;
        $cliente->save();
        $estado_venta = EstadoVenta::whereCodigo('AN')->first();
        $venta->estado_venta_id = $estado_venta->id;
        $venta->fecha_anulado = Carbon::now();
        $venta->orden_pedido->venta_total = 0;
        $venta->orden_pedido->save();
        $venta->saldo = 0;
        $venta->venta_total_con_impuestos = 0;
        $venta->venta_total = 0;
        $venta->fecha_anulado = Carbon::now();
        $venta->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El documento de la venta fue anulada correctamente!');
        return redirect()->route('ventaLista',['filtro' => 'todo']);
    }
}
