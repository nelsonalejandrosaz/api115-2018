<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Cliente;
use App\Configuracion;
use App\EstadoVenta;
use App\Producto;
use App\TipoProducto;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InformesController extends Controller
{
    public function facturacionDia($rango)
    {
        switch ($rango)
        {
            case 'dia':
                $dia_inicio = Carbon::now();
                $dia_fin = Carbon::now();
                $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
                $fcf_dia = $ventas->where('tipo_documento_id','=',1)->get();
                $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
                $ccf_dia = $ventas->where('tipo_documento_id','=',2)->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
                break;
            case 'semana':
                $dia_inicio = Carbon::now()->startOfWeek();
                $dia_fin = Carbon::now()->endOfWeek();
                $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
                $fcf_dia = $ventas->where('tipo_documento_id','=',1)->get();
                $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
                $ccf_dia = $ventas->where('tipo_documento_id','=',2)->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
                break;
            case 'mes':
                $dia_inicio = Carbon::now()->startOfMonth();
                $dia_fin = Carbon::now()->endOfMonth();
                $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
                $fcf_dia = $ventas->where('tipo_documento_id','=',1)->get();
                $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
                $ccf_dia = $ventas->where('tipo_documento_id','=',2)->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
        }

//        dd($ccf_dia);
        $monto_dia = 0.00;
        $monto_iva_dia = 0.00;
        $monto_total_dia = 0.00;
        foreach ($fcf_dia as $venta)
        {
            $monto = $venta->venta_total;
//            $iva = Configuracion::find(1)->iva;
            $monto_total = $venta->venta_total_con_impuestos;
            $monto_iva = $monto_total - $monto;
            $venta->monto = $monto;
            $venta->monto_iva = $monto_iva;
            $monto_dia += $monto;
            $monto_iva_dia += $monto_iva;
            $monto_total_dia += $monto_total;
        }
        foreach ($ccf_dia as $venta)
        {
            $monto = $venta->venta_total;
//            $iva = Configuracion::find(1)->iva;
            $monto_total = $venta->venta_total_con_impuestos;
            $monto_iva = $monto_total - $monto;
            $venta->monto = $monto;
            $venta->monto_iva = $monto_iva;
            $monto_dia += $monto;
            $monto_iva_dia += $monto_iva;
            $monto_total_dia += $monto_total;
        }
        $extra['monto_dia'] = $monto_dia;
        $extra['monto_iva_dia'] = $monto_iva_dia;
        $extra['monto_total_dia'] = $monto_total_dia;
//        $extra['dia'] = $dia;
        return view('informes.facturacionDia')
            ->with(['fcf_dia' => $fcf_dia])
            ->with(['ccf_dia' => $ccf_dia])
            ->with(['extra' => $extra]);
//        dd($ventas_dia);
    }

    public function facturacionDiaInformeFechaPost(Request $request)
    {

        $dia_inicio = Carbon::parse($request->input('fecha_inicio'));
        $dia_fin = Carbon::parse($request->input('fecha_fin'));
        $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
        $fcf_dia = $ventas->where('tipo_documento_id','=',1)->get();
        $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
        $ccf_dia = $ventas->where('tipo_documento_id','=',2)->get();
        $extra['dia'] = null;
        $extra['dia_inicio'] = $dia_inicio;
        $extra['dia_fin'] = $dia_fin;

//        dd($ccf_dia);
        $monto_dia = 0.00;
        $monto_iva_dia = 0.00;
        $monto_total_dia = 0.00;
        foreach ($fcf_dia as $venta)
        {
            $monto = $venta->orden_pedido->venta_total;
//            $iva = Configuracion::find(1)->iva;
            $monto_total = $venta->venta_total_con_impuestos;
            $monto_iva = $monto_total - $monto;
            $venta->monto = $monto;
            $venta->monto_iva = $monto_iva;
            $monto_dia += $monto;
            $monto_iva_dia += $monto_iva;
            $monto_total_dia += $monto_total;
        }
        foreach ($ccf_dia as $venta)
        {
            $monto = $venta->orden_pedido->venta_total;
//            $iva = Configuracion::find(1)->iva;
            $monto_total = $venta->venta_total_con_impuestos;
            $monto_iva = $monto_total - $monto;
            $venta->monto = $monto;
            $venta->monto_iva = $monto_iva;
            $monto_dia += $monto;
            $monto_iva_dia += $monto_iva;
            $monto_total_dia += $monto_total;
        }
        $extra['monto_dia'] = $monto_dia;
        $extra['monto_iva_dia'] = $monto_iva_dia;
        $extra['monto_total_dia'] = $monto_total_dia;
//        $extra['dia'] = $dia;
        return view('informes.facturacionDia')
            ->with(['fcf_dia' => $fcf_dia])
            ->with(['ccf_dia' => $ccf_dia])
            ->with(['extra' => $extra]);
//        dd($ventas_dia);
    }

    public function FacturacionInformeExcelPost(Request $request)
    {

        $dia_inicio = Carbon::parse($request->input('fecha_inicio'));
        $dia_fin = Carbon::parse($request->input('fecha_fin'));
        $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
        $fcf_dia = $ventas->where('tipo_documento_id','=',1)->get();
        $ventas = Venta::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')]);
        $ccf_dia = $ventas->where('tipo_documento_id','=',2)->get();

        $monto_dia = 0.00;
        $monto_iva_dia = 0.00;
        $monto_total_dia = 0.00;
        $datos = [];
        foreach ($fcf_dia as $venta)
        {
            $monto = $venta->orden_pedido->venta_total;
//            $iva = Configuracion::find(1)->iva;
            $monto_total = $venta->venta_total_con_impuestos;
            $monto_iva = $monto_total - $monto;
            $venta->monto = $monto;
            $venta->monto_iva = $monto_iva;
            $monto_dia += $monto;
            $monto_iva_dia += $monto_iva;
            $monto_total_dia += $monto_total;
            $fila = [
                'Numero' => $venta->numero,
                'Cliente' => $venta->cliente->nombre,
                'Vendedor' => $venta->vendedor->nombre,
                'Monto' => round($venta->venta_total,2),
                'Monto IVA' => round($monto_iva,2),
                'Monto Total' => round($monto_total,2),
            ];
            $datos[] = $fila;
        }
        foreach ($ccf_dia as $venta)
        {
            $monto = $venta->orden_pedido->venta_total;
//            $iva = Configuracion::find(1)->iva;
            $monto_total = $venta->venta_total_con_impuestos;
            $monto_iva = $monto_total - $monto;
            $venta->monto = $monto;
            $venta->monto_iva = $monto_iva;
            $monto_dia += $monto;
            $monto_iva_dia += $monto_iva;
            $monto_total_dia += $monto_total;
            $fila = [
                'Numero' => $venta->numero,
                'Cliente' => $venta->cliente->nombre,
                'Vendedor' => $venta->vendedor->nombre,
                'Monto' => round($venta->venta_total,2),
                'Monto IVA' => round($monto_iva,2),
                'Monto Total' => round($monto_total,2),
            ];
            $datos[] = $fila;
        }
        // Extra para informe
        $fila = ['TOTALES','','',round($monto_dia,2),round($monto_iva_dia,2),round($monto_total_dia,2)];
        $datos[] = $fila;
        $nombre_documento = 'facturacion-del-' . $dia_inicio->format('d-m-Y') . '-al-' . $dia_fin->format('d-m-Y');
        Excel::create($nombre_documento, function($excel) use($datos) {
            $excel->sheet('Abonos diarios', function($sheet) use($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');

    }

    public function Abonos($rango)
    {
        switch ($rango)
        {
            case 'dia':
                $dia_inicio = Carbon::now();
                $dia_fin = Carbon::now();
                $abonos = Abono::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')])->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
                break;
            case 'semana':
                $dia_inicio = Carbon::now()->startOfWeek();
                $dia_fin = Carbon::now()->endOfWeek();
                $abonos = Abono::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')])->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
                break;
            case 'mes':
                $dia_inicio = Carbon::now()->startOfMonth();
                $dia_fin = Carbon::now()->endOfMonth();
                $abonos = Abono::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')])->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
        }

        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono)
        {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT')
            {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU')
            {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN')
            {
                $abono_retencion += $abono->cantidad;
            }
            $documento_total += $abono->venta->venta_total_con_impuestos;
        }
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        return view('informes.abonosDia')
            ->with(['abonos' => $abonos])
            ->with(['extra' => $extra]);
    }

    public function AbonosFechaPost(Request $request)
    {
        $dia_inicio = Carbon::parse($request->input('fecha_inicio'));
        $dia_fin = Carbon::parse($request->input('fecha_fin'));
        $abonos = Abono::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')])->get();

        $extra['dia'] = null;
        $extra['dia_inicio'] = $dia_inicio;
        $extra['dia_fin'] = $dia_fin;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono)
        {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT')
            {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU')
            {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN')
            {
                $abono_retencion += $abono->cantidad;
            }
            $documento_total += $abono->venta->venta_total_con_impuestos;
        }
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        return view('informes.abonosDia')
            ->with(['abonos' => $abonos])
            ->with(['extra' => $extra]);
    }

    public function AbonosFechaExcelPost(Request $request)
    {
        $datos = [];
        $dia_inicio = Carbon::parse($request->input('fecha_inicio'));
        $dia_fin = Carbon::parse($request->input('fecha_fin'));
        $abonos = Abono::whereBetween('fecha',[$dia_inicio->format('Y-m-d'),$dia_fin->format('Y-m-d')])->get();
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono)
        {
            $fila = [
                'Tipo documento' => $abono->venta->tipo_documento->nombre,
                'Numero' => $abono->venta->numero,
                'Cliente' => $abono->venta->cliente->nombre,
                'Tipo pago' => $abono->forma_pago->nombre,
                'Cantidad abono' => round($abono->cantidad),
                'Total documento' => round($abono->venta->venta_total_con_impuestos,2)];
            $datos[] = $fila;
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT')
            {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU')
            {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN')
            {
                $abono_retencion += $abono->cantidad;
            }
            $documento_total += $abono->venta->venta_total_con_impuestos;
        }
        // Extra para informe
        $fila = ['TOTAL EFECTIVO',$abono_efectivo];
        $datos[] = $fila;
        $fila = ['TOTAL CHEQUE',$abono_cheque];
        $datos[] = $fila;
        $fila = ['TOTAL RETENCIONES',$abono_retencion];
        $datos[] = $fila;
        $fila = ['TOTAL ABONOS',$abono_total];
        $datos[] = $fila;
        $nombre_documento = 'abonos-del-' . $dia_inicio->format('d-m-Y') . '-al-' . $dia_fin->format('d-m-Y');
        Excel::create($nombre_documento, function($excel) use($datos) {
            $excel->sheet('Abonos diarios', function($sheet) use($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');
    }

    public function InformeLista()
    {
        return view('informes.informeLista');
    }

    public function ProductosExistenciasInforme()
    {
        $productos_mp = TipoProducto::where('codigo','=','MP')->first()->productos;
        $productos_pt = TipoProducto::where('codigo','=','PT')->first()->productos;
        $productos_rv = TipoProducto::where('codigo','=','RV')->first()->productos;
        $productos_mr = TipoProducto::where('codigo','=','MR')->first()->productos;
        $productos_pm = TipoProducto::where('codigo','=','PM')->first()->productos;
        $productos['productos_mp'] = $productos_mp;
        $productos['productos_pt'] = $productos_pt;
        $productos['productos_rv'] = $productos_rv;
        $productos['productos_mr'] = $productos_mr;
        $productos['productos_pm'] = $productos_pm;
        foreach ($productos as $producto_cat) {
            foreach ($producto_cat as $producto) {
                $producto->costo_total = $producto->cantidad_existencia * $producto->costo;
                $producto->porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            }
        }
        $extra['dia'] = Carbon::now();
        foreach ($productos as $producto)
        {
            null;
        }
        return view('informes.productoExistenciaInforme')
            ->with(['productos' => $productos])
            ->with(['extra' => $extra]);
    }

    public function ProductosPreciosInforme()
    {
        $productos_pt = TipoProducto::where('codigo','=','PT')->first()->productos;
        $productos_rv = TipoProducto::where('codigo','=','RV')->first()->productos;
        $productos_mr = TipoProducto::where('codigo','=','MR')->first()->productos;
        $productos_pm = TipoProducto::where('codigo','=','PM')->first()->productos;
        $productos['productos_pt'] = $productos_pt;
        $productos['productos_rv'] = $productos_rv;
        $productos['productos_mr'] = $productos_mr;
        $productos['productos_pm'] = $productos_pm;
        $extra['dia'] = Carbon::now();
        return view('informes.productoPreciosInforme')
            ->with(['productos' => $productos])
            ->with(['extra' => $extra]);
    }

    public function CXCAntiguedad()
    {
        $extra['dia'] = Carbon::now();
        $estado_venta = EstadoVenta::whereCodigo('PP')->first();
        $ventas = Venta::where([
            ['estado_venta_id','=',$estado_venta->id],
            ['fecha','!=',$extra['dia']->format('Y-m-d')]
        ])->orderBy('fecha','desc')->get();
        foreach ($ventas as $venta)
        {
            $venta->antiguedad = $venta->fecha->diffInDays(Carbon::now());
        }
        return view('informes.cxcAntiguedad')
            ->with(['ventas' => $ventas])
            ->with(['extra' => $extra]);
    }

    public function IngresoDiario(Request $request)
    {
        $dia = ($request->input('fecha') == null) ? Carbon::now()->format('Y-m-d') : $request->input('fecha');
        $abonos = Abono::where('fecha','=',$dia)->get();
        $ventaCredito = Venta::where('fecha','=',$dia)->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        $credito_total = 0.00;
        foreach ($abonos as $abono)
        {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT')
            {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU')
            {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN')
            {
                $abono_retencion += $abono->cantidad;
            }
            $dev[] = $abono->venta;
            $documento_total += $abono->venta->saldo;
        }

        // Ventas contado
        $ventasContadoArray = [];
        $cobrosArray = [];
        foreach ($abonos as $abono)
        {
            if ($abono->venta->fecha->format('d/m/Y') == $dia)
            {
                $ventasContadoArray[] = $abono;
            } else
            {
                $cobrosArray[] = $abono;
            }
        }
        $ventas_contado = collect($ventasContadoArray);
//        dd($ventas_contado->isNotEmpty());
        $cobros = collect($cobrosArray);

        // Ventas al credito
        $ventaCreditoArray = [];
        foreach ($ventaCredito as $venta)
        {
            if ($venta->abonos->isEmpty() && $venta->estado_venta_id != 3)
            {
                $ventaCreditoArray[] = $venta;
                $documento_total += $venta->saldo;
            }
        }
        $ventaCredito = collect($ventaCreditoArray);
        $ventas_anuladas = Venta::where('fecha_anulado','=',$dia)->get();
//        dd($ventas_anuladas->isEmpty());
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        return view('informes.ingresosDiariosInforme')
            ->with(['abonos' => $abonos])
            ->with(['extra' => $extra])
            ->with(['ventas_credito' => $ventaCredito])
            ->with(['ventas_contado' => $ventas_contado])
            ->with(['ventas_anuladas' => $ventas_anuladas])
            ->with(['cobros' => $cobros]);
    }

    public function IngresoDiarioExcel(Request $request)
    {
        $datos = [];
        $dia = ($request->input('fecha') == null) ? Carbon::now()->format('Y-m-d') : $request->input('fecha');
        $abonos = Abono::where('fecha','=',$dia)->get();
        $ventaCredito = Venta::where('fecha','=',$dia)->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        $credito_total = 0.00;
        foreach ($abonos as $abono)
        {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT')
            {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU')
            {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN')
            {
                $abono_retencion += $abono->cantidad;
            }
            $dev[] = $abono->venta;
            $documento_total += $abono->venta->saldo;
        }

        // Ventas contado
        $ventasContadoArray = [];
        $cobrosArray = [];
        foreach ($abonos as $abono)
        {
            if ($abono->venta->fecha->format('d/m/Y') == $dia)
            {
                $ventasContadoArray[] = $abono;
            } else
            {
                $cobrosArray[] = $abono;
            }
        }
        $ventas_contado = collect($ventasContadoArray);
        $cobros = collect($cobrosArray);

        // Ventas al credito
        $ventaCreditoArray = [];
        foreach ($ventaCredito as $venta)
        {
            if ($venta->abonos->isEmpty() && $venta->estado_venta_id != 3)
            {
                $ventaCreditoArray[] = $venta;
                $documento_total += $venta->saldo;
            }
        }
        $ventaCredito = collect($ventaCreditoArray);
        $ventas_anuladas = Venta::where('fecha_anulado','=',$dia)->get();
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        $nombre_documento = 'ingresos-diarios-del-' . $dia;
        Excel::create($nombre_documento, function($excel) use($datos) {
            $excel->sheet('Abonos diarios', function($sheet) use($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');
    }

    public function IngresoVentasPost(Request $request)
    {
        $dia = Carbon::parse($request->input('fecha'));
        $abonos = Abono::where('fecha','=',$dia->format('Y-m-d'))->get();
        $ventaCredito = Venta::where([
            ['fecha','=',$dia->format('Y-m-d')],
            ['estado_venta_id','!=',3]
        ])->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono)
        {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT')
            {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU')
            {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN')
            {
                $abono_retencion += $abono->cantidad;
            }
            $dev[] = $abono->venta;
            $documento_total += $abono->venta->saldo;
        }

        // Ventas contado
        $ventasContadoArray = [];
        $cobrosArray = [];
        foreach ($abonos as $abono)
        {
            if ($abono->venta->fecha->format('d/m/Y') == $dia->format('d/m/Y'))
            {
                $ventasContadoArray[] = $abono;
            } else
            {
                $cobrosArray[] = $abono;
            }
        }
        $ventas_contado = collect($ventasContadoArray);
//        dd($ventas_contado->isNotEmpty());
        $cobros = collect($cobrosArray);

        // Ventas al credito
        $ventaCreditoArray = [];
        foreach ($ventaCredito as $venta)
        {
            if ($venta->abonos->isEmpty() && $venta->estado_venta_id != 3)
            {
                $ventaCreditoArray[] = $venta;
                $documento_total += $venta->saldo;
            }
        }
        $ventaCredito = collect($ventaCreditoArray);
        $ventas_anuladas = Venta::where('fecha_anulado','=',$dia->format('Y-m-d'))->get();
//        dd($ventas_anuladas->isEmpty());
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        return view('informes.ingresosInforme')
            ->with(['abonos' => $abonos])
            ->with(['extra' => $extra])
            ->with(['ventas_credito' => $ventaCredito])
            ->with(['ventas_contado' => $ventas_contado])
            ->with(['ventas_anuladas' => $ventas_anuladas])
            ->with(['cobros' => $cobros]);
    }

    public function ProductoMovimiento(Request $request)
    {
        $dia_inicio = ($request->input('fecha_inicio') != null) ? Carbon::parse($request->input('fecha_inicio')) : Carbon::now()->subDays(30);
        $dia_fin = ($request->input('fecha_fin') != null) ? Carbon::parse($request->input('fecha_fin')) : Carbon::now();
        $extra['dia'] = null;
        $extra['dia_inicio'] = $dia_inicio;
        $extra['dia_fin'] = $dia_fin;
        $productos = Producto::all();
        $producto = ($request->input('producto_id') != null) ? Producto::find($request->input('producto_id')) : null;
//        dd($producto);
        return view('informes.productoMovimiento')
            ->with(['productos' => $productos])
            ->with(['producto_seleccion' => $producto])
            ->with(['extra' => $extra]);
    }
}
