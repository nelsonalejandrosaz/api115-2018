<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Cliente;
use App\CondicionPago;
use App\Configuracion;
use App\DetalleOtrasVentas;
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
        // id = 2 - Despachada
        $ordenesPedidoProcesadas = OrdenPedido::whereEstadoId(2)->get();
        if (Auth::user()->rol->nombre == 'Vendedor') {
            $ordenesPedidoProcesadas = OrdenPedido::whereEstadoId(2);
            $ordenesPedidoProcesadas = $ordenesPedidoProcesadas->where('vendedor_id', '=', Auth::user()->id)->get();
        }
        return view('venta.ventaLista')->with(['ordenesPedidos' => $ordenesPedidoProcesadas]);
    }

    public function VentaNueva($id)
    {
        $orden_pedido = OrdenPedido::find($id);
        $dia_hoy = Carbon::now();
        $cierre = Carbon::parse($dia_hoy->format('Y-m-d'));
        $cierre = $cierre->addHours(15)->addMinutes(30);
        if ($dia_hoy > $cierre) {
            $dia_hoy = $dia_hoy->addDay();
        }
        if ($orden_pedido->tipo_documento->codigo == 'FAC') {
            return view('venta.ventaFCFNuevo')
                ->with(['orden_pedido' => $orden_pedido])
                ->with(['dia' => $dia_hoy]);
        } else {
            return view('venta.ventaCCFNuevo')
                ->with(['orden_pedido' => $orden_pedido])
                ->with(['dia' => $dia_hoy]);
        }
    }

    public function VentaNuevaPost(Request $request, $id)
    {
        // Validacion
        $this->validate($request, [
            'numero' => 'required',
        ]);

        // Se verifica la fecha
        $fecha = Carbon::now();
        $cierre = Carbon::parse($fecha->format('Y-m-d'));
        $cierre = $cierre->addHours(15)->addMinutes(30);
        if ($fecha > $cierre) {
            $fecha = $fecha->addDay();
        }
        // Se carga la orden de pedido
        $orden_pedido = OrdenPedido::find($id);
        // Orden procesada = 3 ---- Venta pendiente de pago = 1
        $iva = Configuracion::find(1)->iva;
        $venta_total_con_impuestos = $orden_pedido->venta_total * $iva;
        // Se crea la venta
        $venta = Venta::create([
            'tipo_documento_id' => $orden_pedido->tipo_documento_id,
            'orden_pedido_id' => $orden_pedido->id,
            'condicion_pago_id' => $orden_pedido->condicion_pago_id,
            'numero' => $request->input('numero'),
            'fecha' => $fecha,
            'cliente_id' => $orden_pedido->cliente_id,
            'estado_venta_id' => 1,
            'vendedor_id' => $orden_pedido->vendedor_id,
            'saldo' => $venta_total_con_impuestos,
            'venta_total' => $orden_pedido->venta_total,
            'venta_total_con_impuestos' => $venta_total_con_impuestos,
        ]);
        // Se agrega el saldo al cliente
        $cliente = Cliente::find($venta->cliente_id);
        $cliente->saldo = $cliente->saldo + $venta_total_con_impuestos;
        $cliente->save();
        // Se cambia el estado de la orden de pedido
        $orden_pedido->estado_id = 3;
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
        // Se carga la venta y el IVA
        $venta = Venta::find($id);
        $iva = Configuracion::find(1)->iva;
        // Se verifica que la venta sea factura
        if ($venta->tipo_documento->codigo != 'FAC') {
            abort(404);
        }
//        dd($venta);
        if ($venta->detalle_otras_ventas->isNotEmpty()) {
            return view('venta.ventaFacturaEspecialVer')
                ->with(['venta' => $venta]);
        }
        foreach ($venta->orden_pedido->salidas as $salida) {
            $salida->precio_unitario = $salida->precio_unitario * $iva;
            $salida->venta_gravada = $salida->venta_gravada * $iva;
            $salida->venta_exenta = $salida->venta_exenta * $iva;
        }
        $venta->orden_pedido->ventas_exentas = $venta->orden_pedido->ventas_exentas * $iva;
        $venta->orden_pedido->ventas_gravadas = $venta->orden_pedido->ventas_gravadas * $iva;
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * $iva;
        return view('venta.ventaFacturaVer')
            ->with(['venta' => $venta]);
    }

    public function VentaVerCCF($id)
    {
        // Se carga la venta y el IVA
        $venta = Venta::find($id);
        $iva = Configuracion::find(1)->iva;
        if ($venta->tipo_documento->codigo != 'CCF') {
            abort(404);
        }
        $venta->orden_pedido->porcentaje_IVA = $venta->orden_pedido->ventas_gravadas * ($iva - 1);
        $venta->orden_pedido->venta_total = $venta->orden_pedido->venta_total * $iva;
        return view('venta.ventaCCFVer')
            ->with(['venta' => $venta]);
    }

    public function VentaLista(Request $request, $tipo)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        if (Auth::user()->rol->nombre == 'Vendedor') {
            $ventas = Venta::whereBetween('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')])->get();
            $ventas = $ventas->where('vendedor_id', '=', Auth::user()->id);
//            $ventas = Venta::where('vendedor_id', '=', Auth::user()->id)->get();
        } else {
            $ventas = Venta::whereBetween('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')])->get();
        }
        switch ($tipo) {
            case 'todo':
                return view('venta.ventaFacturadasLista')
                    ->with(['ventas' => $ventas])
                    ->with(['titulo' => "Ventas"])
                    ->with(['extra' => $extra]);
            case 'factura':
                $ventas = $ventas->where('tipo_documento_id', '=', 1);
                return view('venta.ventaFacturadasLista')
                    ->with(['ventas' => $ventas])
                    ->with(['titulo' => "Facturas Consumidor Final"])
                    ->with(['extra' => $extra]);
            case 'ccf':
                $ventas = $ventas->where('tipo_documento_id', '=', 2);
                return view('venta.ventaFacturadasLista')
                    ->with(['ventas' => $ventas])
                    ->with(['titulo' => "Comprobantes de crédito fiscal"])
                    ->with(['extra' => $extra]);
            case 'anulada':
                $ventas = $ventas->where('estado_venta_id', '=', 3);
                return view('venta.ventaFacturadasLista')
                    ->with(['ventas' => $ventas])
                    ->with(['titulo' => "Documentos anulados"])
                    ->with(['extra' => $extra]);
        }
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
//        $pdf = PDF::loadView('pdf.facturaPDF', compact('venta'));
//        $nombre_factura = "Factura numero " . $venta->numero . ".pdf";
//        return $pdf->stream($nombre_factura);
    }

    public function VentaFacturaEspecialPDF($id)
    {
        $venta = Venta::find($id);
        $venta->vendedor->nombreCompleto = $venta->vendedor->nombre . " " . $venta->vendedor->apellido;
        $venta_total = number_format($venta->venta_total, 2);
        $venta->venta_total_letras = NumeroALetras::convertir($venta_total, 'dolares', 'centavos');
        return view('pdf.facturaPDFEspecial')->with(['venta' => $venta]);
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
        foreach ($salidas as $salida) {
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
                'costo_total' => $diferencia_ajuste * $producto->costo,
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
        return redirect()->route('ventaLista', ['filtro' => 'todo']);
    }

    public function VentaAnuladaSinOrdenNueva()
    {
        $tipo_documentos = TipoDocumento::all();
        $clientes = Cliente::all();
        return view('venta.venta-anulada-sin-orden')
            ->with(['tipoDocumentos' => $tipo_documentos])
            ->with(['clientes' => $clientes]);
    }

    public function VentaAnuladaSinOrdenNuevaPost()
    {

    }

    public function VentaSinOrdenNueva()
    {
        $tipo_documentos = TipoDocumento::all();
        $clientes = Cliente::all();
        $condiciones_pago = CondicionPago::all();
        return view('venta.venta-sin-orden')
            ->with(['tipoDocumentos' => $tipo_documentos])
            ->with(['clientes' => $clientes])
            ->with(['condiciones_pago' => $condiciones_pago]);
    }

    public function VentaSinOrdenPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'cliente_id' => 'required',
            'numero' => 'required',
            'tipo_documento_id' => 'required',
            'condicion_pago_id' => 'required',
            'comision' => 'required',
        ]);
//        dd($request);
        // Se verifica la fecha
        $fecha = Carbon::now();
        $cierre = Carbon::parse($fecha->format('Y-m-d'));
        $cierre = $cierre->addHours(15)->addMinutes(30);
        if ($fecha > $cierre) {
            $fecha = $fecha->addDay();
        }
        // Venta pendiente de pago = 1
        $iva = Configuracion::find(1)->iva;
        $venta_total_con_impuestos = $request->input('comision');
        // Se crea la venta
        $venta = Venta::create([
            'tipo_documento_id' => 1,
            'orden_pedido_id' => 0,
            'condicion_pago_id' => $request->input('condicion_pago_id'),
            'numero' => $request->input('numero'),
            'fecha' => $fecha,
            'cliente_id' => $request->input('cliente_id'),
            'estado_venta_id' => 1,
            'vendedor_id' => 1,
            'saldo' => $venta_total_con_impuestos,
            'venta_total' => $venta_total_con_impuestos,
            'suma' => $request->input('suma'),
            'flete' => $request->input('flete'),
            'venta_total_con_impuestos' => $venta_total_con_impuestos,
        ]);
        // Se agrega el detalle de la venta
        $detalle = $request->input('detalle');
        $venta_gravada = $request->input('venta_gravada');
        $max = sizeof($detalle);
        for ($i = 0; $i < $max; $i++) {
            $detalle_venta = DetalleOtrasVentas::create([
                'venta_id' => $venta->id,
                'detalle' => $detalle[$i],
                'cantidad' => 1,
                'precio_unitario' => $venta_gravada[$i],
                'venta_exenta' => 0,
                'venta_gravada' => $venta_gravada[$i],
                'venta_total' => $venta_gravada[$i],
            ]);
        }
        // Se agrega el saldo al cliente
        $cliente = Cliente::find($venta->cliente_id);
        $cliente->saldo = $cliente->saldo + $venta_total_con_impuestos;
        $cliente->save();
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

}
