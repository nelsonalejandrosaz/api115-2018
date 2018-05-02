<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Ajuste;
use App\Cliente;
use App\Compra;
use App\Configuracion;
use App\EstadoVenta;
use App\Formula;
use App\Movimiento;
use App\Produccion;
use App\Producto;
use App\Proveedor;
use App\TipoProducto;
use App\User;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InformesController extends Controller
{
    public function facturacionDia($rango)
    {
        switch ($rango) {
            case 'dia':
                $dia_inicio = Carbon::now();
                $dia_fin = Carbon::now();
                $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
                $fcf_dia = $ventas->where('tipo_documento_id', '=', 1)->get();
                $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
                $ccf_dia = $ventas->where('tipo_documento_id', '=', 2)->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
                break;
            case 'semana':
                $dia_inicio = Carbon::now()->startOfWeek();
                $dia_fin = Carbon::now()->endOfWeek();
                $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
                $fcf_dia = $ventas->where('tipo_documento_id', '=', 1)->get();
                $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
                $ccf_dia = $ventas->where('tipo_documento_id', '=', 2)->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
                break;
            case 'mes':
                $dia_inicio = Carbon::now()->startOfMonth();
                $dia_fin = Carbon::now()->endOfMonth();
                $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
                $fcf_dia = $ventas->where('tipo_documento_id', '=', 1)->get();
                $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
                $ccf_dia = $ventas->where('tipo_documento_id', '=', 2)->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
        }

//        dd($ccf_dia);
        $monto_dia = 0.00;
        $monto_iva_dia = 0.00;
        $monto_total_dia = 0.00;
        foreach ($fcf_dia as $venta) {
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
        foreach ($ccf_dia as $venta) {
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
        $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
        $fcf_dia = $ventas->where('tipo_documento_id', '=', 1)->get();
        $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
        $ccf_dia = $ventas->where('tipo_documento_id', '=', 2)->get();
        $extra['dia'] = null;
        $extra['dia_inicio'] = $dia_inicio;
        $extra['dia_fin'] = $dia_fin;

//        dd($ccf_dia);
        $monto_dia = 0.00;
        $monto_iva_dia = 0.00;
        $monto_total_dia = 0.00;
        foreach ($fcf_dia as $venta) {
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
        foreach ($ccf_dia as $venta) {
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

    public function FacturacionInformeExcelPost(Request $request)
    {

        $dia_inicio = Carbon::parse($request->input('fecha_inicio'));
        $dia_fin = Carbon::parse($request->input('fecha_fin'));
        $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
        $fcf_dia = $ventas->where('tipo_documento_id', '=', 1)->get();
        $ventas = Venta::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')]);
        $ccf_dia = $ventas->where('tipo_documento_id', '=', 2)->get();

        $monto_dia = 0.00;
        $monto_iva_dia = 0.00;
        $monto_total_dia = 0.00;
        $datos = [];
        foreach ($fcf_dia as $venta) {
            $monto = $venta->venta_total;
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
                'Monto' => round($venta->venta_total, 2),
                'Monto IVA' => round($monto_iva, 2),
                'Monto Total' => round($monto_total, 2),
            ];
            $datos[] = $fila;
        }
        foreach ($ccf_dia as $venta) {
            $monto = $venta->venta_total;
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
                'Monto' => round($venta->venta_total, 2),
                'Monto IVA' => round($monto_iva, 2),
                'Monto Total' => round($monto_total, 2),
            ];
            $datos[] = $fila;
        }
        // Extra para informe
        $fila = ['TOTALES', '', '', round($monto_dia, 2), round($monto_iva_dia, 2), round($monto_total_dia, 2)];
        $datos[] = $fila;
        $nombre_documento = 'facturacion-del-' . $dia_inicio->format('d-m-Y') . '-al-' . $dia_fin->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($datos) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');

    }

    public function Abonos($rango)
    {
        switch ($rango) {
            case 'dia':
                $dia_inicio = Carbon::now();
                $dia_fin = Carbon::now();
                $abonos = Abono::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')])->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
                break;
            case 'semana':
                $dia_inicio = Carbon::now()->startOfWeek();
                $dia_fin = Carbon::now()->endOfWeek();
                $abonos = Abono::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')])->get();
                $extra['dia'] = null;
                $extra['dia_inicio'] = $dia_inicio;
                $extra['dia_fin'] = $dia_fin;
//                dd($abonos);
                break;
            case 'mes':
                $dia_inicio = Carbon::now()->startOfMonth();
                $dia_fin = Carbon::now()->endOfMonth();
                $abonos = Abono::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')])->get();
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
        foreach ($abonos as $abono) {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT') {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU') {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN') {
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
        $abonos = Abono::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')])->get();

        $extra['dia'] = null;
        $extra['dia_inicio'] = $dia_inicio;
        $extra['dia_fin'] = $dia_fin;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono) {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT') {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU') {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN') {
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
        $abonos = Abono::whereBetween('fecha', [$dia_inicio->format('Y-m-d'), $dia_fin->format('Y-m-d')])->get();
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono) {
            $fila = [
                'Tipo documento' => $abono->venta->tipo_documento->nombre,
                'Numero' => $abono->venta->numero,
                'Cliente' => $abono->venta->cliente->nombre,
                'Tipo pago' => $abono->forma_pago->nombre,
                'Cantidad abono' => round($abono->cantidad),
                'Total documento' => round($abono->venta->venta_total_con_impuestos, 2)];
            $datos[] = $fila;
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT') {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU') {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN') {
                $abono_retencion += $abono->cantidad;
            }
            $documento_total += $abono->venta->venta_total_con_impuestos;
        }
        // Extra para informe
        $fila = ['TOTAL EFECTIVO', $abono_efectivo];
        $datos[] = $fila;
        $fila = ['TOTAL CHEQUE', $abono_cheque];
        $datos[] = $fila;
        $fila = ['TOTAL RETENCIONES', $abono_retencion];
        $datos[] = $fila;
        $fila = ['TOTAL ABONOS', $abono_total];
        $datos[] = $fila;
        $nombre_documento = 'abonos-del-' . $dia_inicio->format('d-m-Y') . '-al-' . $dia_fin->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($datos) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');
    }

    public function InformeLista()
    {
        return view('informes.informeLista');
    }

    public function ProductosPreciosInforme()
    {
        $productos_pt = TipoProducto::where('codigo', '=', 'PT')->first()->productos;
        $productos_rv = TipoProducto::where('codigo', '=', 'RV')->first()->productos;
        $productos_mr = TipoProducto::where('codigo', '=', 'MR')->first()->productos;
        $productos_pm = TipoProducto::where('codigo', '=', 'PM')->first()->productos;
        $productos['productos_pt'] = $productos_pt;
        $productos['productos_rv'] = $productos_rv;
        $productos['productos_mr'] = $productos_mr;
        $productos['productos_pm'] = $productos_pm;
        $extra['dia'] = Carbon::now();
        return view('informes.productoPreciosInforme')
            ->with(['productos' => $productos])
            ->with(['extra' => $extra]);
    }

    public function ProductosPreciosInformeExcel()
    {
        $productos = Producto::all();
        $extra['dia'] = Carbon::now();
        $tabla = collect();
        foreach ($productos as $producto) {
            $fila = [
                'presentacion' => $producto->nombre,
                'unidad_medida' => null,
                'precio' => null,
                'precio_con_iva' => null,
                'peso_kilogramo' => null,
            ];
            $tabla->push($fila);
            foreach ($producto->precios as $precio) {
                $fila = [
                    'presentacion' => $precio->presentacion,
                    'unidad_medida' => $precio->unidad_medida->abreviatura,
                    'precio' => number_format($precio->precio, 2),
                    'precio_con_iva' => number_format($precio->precio * 1.13, 2),
                    'peso_kilogramo' => number_format($precio->factor, 4),
                ];
                $tabla->push($fila);
            }
        }
        $nombre_documento = 'informe-de-precios-productos-al-' . Carbon::now()->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function CXCAntiguedad()
    {
        $extra['dia'] = Carbon::now();
        $estado_venta = EstadoVenta::whereCodigo('PP')->first();
        $ventas = Venta::where([
            ['estado_venta_id', '=', $estado_venta->id],
            ['fecha', '!=', $extra['dia']->format('Y-m-d')]
        ])->orderBy('fecha', 'desc')->get();
        foreach ($ventas as $venta) {
            $venta->antiguedad = $venta->fecha->diffInDays(Carbon::now());
        }
        return view('informes.cxcAntiguedad')
            ->with(['ventas' => $ventas])
            ->with(['extra' => $extra]);
    }

    public function CXCAntiguedadExcel()
    {
        $datos = [];
        $extra['dia'] = Carbon::now();
        $estado_venta = EstadoVenta::whereCodigo('PP')->first();
        $ventas = Venta::where([
            ['estado_venta_id', '=', $estado_venta->id],
            ['fecha', '!=', $extra['dia']->format('Y-m-d')]
        ])->orderBy('fecha', 'desc')->get();
        $total_saldos = 0.00;
        foreach ($ventas as $venta) {
            $venta->antiguedad = $venta->fecha->diffInDays(Carbon::now());
            $fila = [
                'Vendedor' => $venta->vendedor->nombre,
                'Cliente' => $venta->cliente->nombre,
                'N° documento' => $venta->numero,
                'Tipo doc' => $venta->tipo_documento->codigo,
                'Fecha' => $venta->fecha->format('d/m/Y'),
                'Valor doc' => number_format($venta->venta_total_con_impuestos, 2),
                'Saldo pendiente' => number_format($venta->saldo, 2),
                'Antigüedad' => $venta->antiguedad,
            ];
            $total_saldos += $venta->saldo;
            $datos[] = $fila;
        }
        $fila = ['TOTAL SALDO PENDIENTE', '', '', '', '', '', number_format($total_saldos, 2)];
        $datos[] = $fila;
        $nombre_documento = 'informe-de-antiguedad-saldos-al-' . Carbon::now()->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($datos) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');
    }


    public function IngresoDiario(Request $request)
    {
        $dia = ($request->input('fecha') == null) ? Carbon::now()->format('Y-m-d') : $request->input('fecha');
        $abonos = Abono::where('fecha', '=', $dia)->get();
        $ventaCredito = Venta::where('fecha', '=', $dia)->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $abono_deposito = 0.00;
        $documento_total = 0.00;
        $credito_total = 0.00;
        foreach ($abonos as $abono) {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT') {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU') {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN') {
                $abono_retencion += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'DEPOS') {
                $abono_deposito += $abono->cantidad;
            }
            $dev[] = $abono->venta;
            $documento_total += $abono->venta->saldo;
        }
        // Ventas contado
        $ventasContadoArray = [];
        $cobrosArray = [];
        foreach ($abonos as $abono) {
            if ($abono->venta->fecha->format('d/m/Y') == $dia) {
                $ventasContadoArray[] = $abono;
            } else {
                $cobrosArray[] = $abono;
            }
        }
        $ventas_contado = collect($ventasContadoArray);
//        dd($ventas_contado->isNotEmpty());
        $cobros = collect($cobrosArray);

        // Ventas al credito
        $ventaCreditoArray = [];
        foreach ($ventaCredito as $venta) {
            if ($venta->abonos->isEmpty() && $venta->estado_venta_id != 3) {
                $ventaCreditoArray[] = $venta;
                $documento_total += $venta->saldo;
            }
        }
        $ventaCredito = collect($ventaCreditoArray);
        $ventas_anuladas = Venta::where('fecha_anulado', '=', $dia)->get();
//        dd($ventas_anuladas->isEmpty());
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        $extra['abono_deposito'] = $abono_deposito;
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
        $abonos = Abono::where('fecha', '=', $dia)->get();
        $ventaCredito = Venta::where('fecha', '=', $dia)->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        $credito_total = 0.00;
        foreach ($abonos as $abono) {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT') {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU') {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN') {
                $abono_retencion += $abono->cantidad;
            }
            $dev[] = $abono->venta;
            $documento_total += $abono->venta->saldo;
        }

        // Ventas contado
        $ventasContadoArray = [];
        $cobrosArray = [];
        foreach ($abonos as $abono) {
            if ($abono->venta->fecha->format('d/m/Y') == $dia) {
                $ventasContadoArray[] = $abono;
            } else {
                $cobrosArray[] = $abono;
            }
        }
        $ventas_contado = collect($ventasContadoArray);
        $cobros = collect($cobrosArray);

        // Ventas al credito
        $ventaCreditoArray = [];
        foreach ($ventaCredito as $venta) {
            if ($venta->abonos->isEmpty() && $venta->estado_venta_id != 3) {
                $ventaCreditoArray[] = $venta;
                $documento_total += $venta->saldo;
            }
        }
        $ventaCredito = collect($ventaCreditoArray);
        $ventas_anuladas = Venta::where('fecha_anulado', '=', $dia)->get();
        // Extra para informe
        $extra['abono_total'] = $abono_total;
        $extra['documento_total'] = $documento_total;
        $extra['abono_efectivo'] = $abono_efectivo;
        $extra['abono_cheque'] = $abono_cheque;
        $extra['abono_retencion'] = $abono_retencion;
        $nombre_documento = 'ingresos-diarios-del-' . $dia;
        Excel::create($nombre_documento, function ($excel) use ($datos) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($datos) {

                $sheet->fromArray($datos);

            });
        })->download('xls');
    }

    public function IngresoVentasPost(Request $request)
    {
        $dia = Carbon::parse($request->input('fecha'));
        $abonos = Abono::where('fecha', '=', $dia->format('Y-m-d'))->get();
        $ventaCredito = Venta::where([
            ['fecha', '=', $dia->format('Y-m-d')],
            ['estado_venta_id', '!=', 3]
        ])->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $documento_total = 0.00;
        foreach ($abonos as $abono) {
            $abono_total += $abono->cantidad;
            if ($abono->forma_pago->codigo == 'EFECT') {
                $abono_efectivo += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'CHEQU') {
                $abono_cheque += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'RETEN') {
                $abono_retencion += $abono->cantidad;
            }
            $dev[] = $abono->venta;
            $documento_total += $abono->venta->saldo;
        }

        // Ventas contado
        $ventasContadoArray = [];
        $cobrosArray = [];
        foreach ($abonos as $abono) {
            if ($abono->venta->fecha->format('d/m/Y') == $dia->format('d/m/Y')) {
                $ventasContadoArray[] = $abono;
            } else {
                $cobrosArray[] = $abono;
            }
        }
        $ventas_contado = collect($ventasContadoArray);
//        dd($ventas_contado->isNotEmpty());
        $cobros = collect($cobrosArray);

        // Ventas al credito
        $ventaCreditoArray = [];
        foreach ($ventaCredito as $venta) {
            if ($venta->abonos->isEmpty() && $venta->estado_venta_id != 3) {
                $ventaCreditoArray[] = $venta;
                $documento_total += $venta->saldo;
            }
        }
        $ventaCredito = collect($ventaCreditoArray);
        $ventas_anuladas = Venta::where('fecha_anulado', '=', $dia->format('Y-m-d'))->get();
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

    public function Ventas(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $total_dias = $fecha_inicio->diffInDays($fecha_fin);
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        $tabla = collect();
        $fecha = Carbon::parse($fecha_inicio);
        for ($i = 0; $i < $total_dias; $i++) {
            if (!$fecha->isSunday()) {
                $valor = $ventas->where('fecha', '=', $fecha)->sum('venta_total');
                $valor = round($valor, 2);
                $iva = $valor * 0.13;
                $iva = round($iva, 2);
                $total = $valor + $iva;
                $fila = ['fecha' => $fecha->format('d/m/Y'), 'valor' => $valor, 'iva' => $iva, 'total' => $total];
                $tabla->push($fila);
            }
            $fecha->addDay();
        }
        return view('informes.informeVentas')
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function VentasPorCliente(Request $request)
    {
        $clientes = Cliente::all();
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $cliente = ($request->input('cliente_id') == null) ? null : Cliente::find($request->input('cliente_id'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $tabla = collect();
//        $ventas = $cliente->ventas;
//        dd($ventas->whereIn('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')]));
        if ($cliente != null) {
            $ventas = Venta::where('cliente_id', '=', $cliente->id)
                ->where('estado_venta_id', '!=', 3)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
            foreach ($ventas as $venta) {
                $fila = [
                    'fecha' => $venta->fecha->format('d/m/Y'),
                    'tipo_documento' => $venta->tipo_documento->nombre,
                    'numero' => $venta->numero,
                    'total' => $venta->venta_total_con_impuestos,
                ];
                $tabla->push($fila);
            }
        }
        return view('informes.informeVentasPorCliente')
            ->with(['clientes' => $clientes])
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function VentasPorVendedor(Request $request)
    {
        $vendedores = User::whereRolId(2)->get();
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $vendedor = ($request->input('vendedor_id') == null) ? null : User::find($request->input('vendedor_id'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $tabla = collect();
//        $ventas = $cliente->ventas;
//        dd($ventas->whereIn('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')]));
        if ($vendedor != null) {
            $ventas = Venta::where('vendedor_id', '=', $vendedor->id)
                ->where('estado_venta_id', '=', 2)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
            foreach ($ventas as $venta) {
                $fila = [
                    'fecha' => $venta->fecha->format('d/m/Y'),
                    'tipo_documento' => $venta->tipo_documento->nombre,
                    'numero' => $venta->numero,
                    'total' => $venta->venta_total_con_impuestos,
                ];
                $tabla->push($fila);
            }
        }
        return view('informes.informeVentasPorVendedor')
            ->with(['vendedores' => $vendedores])
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function Producciones(Request $request)
    {
        $productos = Producto::all();
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $producto = ($request->input('producto_id') == null) ? null : Producto::find($request->input('producto_id'));
        $nombre_producto = ($producto == null) ? "Sin producto" : $producto->nombre;
        $datos = [];
        $tabla = collect();
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $datos += ['nombre_producto' => $nombre_producto];
        if ($producto != null) {
            $producciones = Produccion::where('producto_id', '=', $producto->id)
                ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
            foreach ($producciones as $produccion) {
                $fila = [
                    'fecha' => $produccion->fecha->format('d/m/Y'),
                    'cantidad' => $produccion->cantidad,
                    'costo_unitario' => $produccion->entrada->movimiento->costo_unitario,
                    'costo_total' => $produccion->entrada->movimiento->costo_total,
                ];
                $tabla->push($fila);
            }
        }
        return view('informes.informeProducciones')
//            ->with(['producto' => $producto])
            ->with(['productos' => $productos])
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function MovimientosAjuste(Request $request)
    {
        $productos = Producto::all();
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $producto = ($request->input('producto_id') == null) ? null : Producto::find($request->input('producto_id'));
        $nombre_producto = ($producto == null) ? "Sin producto" : $producto->nombre;
        $datos = [];
        $tabla = collect();
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $datos += ['nombre_producto' => $nombre_producto];
        if ($producto != null) {
            $movimientos = Movimiento::whereBetween('tipo_movimiento_id', [5, 6])
                ->where('producto_id', '=', $producto->id)->get();
            foreach ($movimientos as $movimiento) {
                $fila = [
                    'tipo_movimiento' => $movimiento->ajuste->tipo_ajuste->tipo,
                    'fecha' => $movimiento->fecha->format('d/m/Y'),
                    'tipo_ajuste' => $movimiento->ajuste->tipo_ajuste->nombre,
                    'cantidad' => $movimiento->cantidad,
                    'costo_unitario' => $movimiento->costo_unitario,
                    'costo_total' => $movimiento->costo_total,
                ];
                $tabla->push($fila);
            }
        }
//        dd($tabla);
        return view('informes.informeMovimientosAjustes')
            ->with(['productos' => $productos])
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function ProductosExistenciasInforme()
    {
        $productos = Producto::all();
        $datos = [];
        $datos += ['dia' => Carbon::now()];
        $tabla = collect();
        foreach ($productos as $producto) {
            $porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            if ($porcentaje_stock >= 40) {
                $estado_stock = 1;
            } elseif ($porcentaje_stock >= 20) {
                $estado_stock = 2;
            } else {
                $estado_stock = 3;
            }
            $fila = [
                'tipo_producto' => $producto->tipo_producto->codigo,
                'codigo' => $producto->codigo,
                'nombre_producto' => $producto->nombre,
                'existencia' => $producto->cantidad_existencia,
                'unidad_medida' => $producto->unidad_medida->abreviatura,
                'existencia_min' => $producto->existencia_min,
                'existencia_max' => $producto->existencia_max,
                'estado' => $estado_stock,
            ];
            $tabla->push($fila);
        }
        return view('informes.productoExistenciaInforme')
            ->with(['tabla' => $tabla])
            ->with(['datos' => $datos]);
    }

    public function ProductosExistenciasInformeExcel()
    {
        $productos = Producto::all();
        $datos = [];
        $datos += ['dia' => Carbon::now()];
        $tabla = collect();
        foreach ($productos as $producto) {
            $porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            if ($porcentaje_stock >= 40) {
                $estado_stock = "Alto";
            } elseif ($porcentaje_stock >= 20) {
                $estado_stock = "Medio";
            } else {
                $estado_stock = "Bajo";
            }
            $fila = [
                'tipo_producto' => $producto->tipo_producto->nombre,
                'codigo' => $producto->codigo,
                'nombre_producto' => $producto->nombre,
                'existencia' => $producto->cantidad_existencia,
                'unidad_medida' => $producto->unidad_medida->abreviatura,
                'existencia_min' => $producto->existencia_min,
                'existencia_max' => $producto->existencia_max,
                'estado' => $estado_stock,
            ];
            $tabla->push($fila);
        }
        $nombre_documento = 'informe-de-existencia-al-' . Carbon::now()->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function Compras(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        $tabla = collect();
        foreach ($compras as $compra) {
            $total = $compra->compra_total_con_impuestos;
            $total_sin_iva = $compra->compra_total;
            $iva = $total - $total_sin_iva;
            $fila = [
                'fecha' => $compra->fecha->format('d/m/Y'),
                'numero' => $compra->numero,
                'proveedor' => $compra->proveedor->nombre,
                'total_sin_iva' => $total_sin_iva,
                'iva' => $iva,
                'total' => $total,
            ];
            $tabla->push($fila);
        }
        return view('informes.informeCompras')
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function ComprasExcel(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        $tabla = collect();
        foreach ($compras as $compra) {
            $total = $compra->compra_total_con_impuestos;
            $total_sin_iva = $compra->compra_total;
            $iva = $total - $total_sin_iva;
            $fila = [
                'fecha' => $compra->fecha->format('d/m/Y'),
                'numero' => $compra->numero,
                'proveedor' => $compra->proveedor->nombre,
                'total_sin_iva' => number_format($total_sin_iva, 2),
                'iva' => number_format($iva, 2),
                'total' => number_format($total, 2),
            ];
            $tabla->push($fila);
        }
        $nombre_documento = 'informe-de-compras-del-' . $fecha_inicio->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function ComprasProveedor(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
//        dd();
        if ($request->input('proveedor_id') == 'todos') {
            $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        } else {
            $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->where('proveedor_id', '=', $request->input('proveedor_id'))->get();
        }
        $compras_provedor = $compras->groupBy('proveedor_id');
        $tabla_agrupada = collect();
        $tabla = collect();
        $provedores = Proveedor::all();
//        $dev = ['proveedor' => 'El manguito','compras' => [1,2,3,4]];
//        dd($compras_provedor);
        foreach ($compras_provedor as $proveedor_id => $compras) {
            $nombre_proveedor = $provedores->where('id', '=', $proveedor_id)->first();
            $nombre_proveedor = $nombre_proveedor->nombre;
            foreach ($compras as $compra) {
                $total = $compra->compra_total_con_impuestos;
                $total_sin_iva = $compra->compra_total;
                $iva = $total - $total_sin_iva;
                $fila = [
                    'proveedor' => $compra->proveedor->nombre,
                    'fecha' => $compra->fecha->format('d/m/Y'),
                    'numero' => $compra->numero,
                    'total_sin_iva' => $total_sin_iva,
                    'iva' => $iva,
                    'total' => $total,
                ];
                $tabla->push($fila);
            }
            $fila_agrupada = ['proveedor' => $nombre_proveedor, 'compras' => $tabla];
            $tabla_agrupada->push($fila_agrupada);
            $tabla = collect();
        }
//        dd($tabla_agrupada);
        return view('informes.informeComprasVendedor')
            ->with(['proveedores' => $provedores])
            ->with(['datos' => $datos])
            ->with(['tabla_agrupada' => $tabla_agrupada]);
    }

    public function ComprasProveedorExcel(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
//        dd();
        if ($request->input('proveedor_id') == 'todos') {
            $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        } else {
            $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->where('proveedor_id', '=', $request->input('proveedor_id'))->get();
        }
        $tabla = collect();
        foreach ($compras->sortBy('proveedor_id') as $compra) {
            $total = $compra->compra_total_con_impuestos;
            $total_sin_iva = $compra->compra_total;
            $iva = $total - $total_sin_iva;
            $fila = [
                'proveedor' => $compra->proveedor->nombre,
                'fecha' => $compra->fecha->format('d/m/Y'),
                'numero' => $compra->numero,
                'total_sin_iva' => number_format($total_sin_iva, 2),
                'iva' => number_format($iva, 2),
                'total' => number_format($total, 2),
            ];
            $tabla->push($fila);
        }
        $nombre_documento = 'informe-compras-proveedor-del-' . Carbon::now()->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function LibroCompras(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        $tabla = collect();
        foreach ($compras as $compra) {
            if ($compra->proveedor->nacional){
                $interna = number_format($compra->compra_total,2);
                $exportacion =  number_format(0,2);
            } else {
                $interna = number_format(0,2);
                $exportacion = number_format($compra->compra_total,2);
            }
            if ($compra->proveedor->percepcion == true && $compra->compra_total >= 100){
                $percepcion = number_format(($compra->compra_total * 0.01),2);
            } else {
                $percepcion = '-';
            }
            $fila = [
                'FECHA EMISION' => $compra->fecha->format('d/m/Y'),
                'N° DE COMPRA' => $compra->numero,
                'N° DE REGISTRO' => $compra->proveedor->nrc,
                'NOMBRE DEL PROVEEDOR' => $compra->proveedor->nombre,
                'INTERNAS E' => '-',
                'IMPORTACION E' => '-',
                'INTERNAS G' => $interna,
                'IMPORTACION G' => $exportacion,
                'IVA CCF' => number_format(($compra->compra_total * 0.13),2),
                'TOTAL DE COMPRAS' => number_format(($compra->compra_total * 1.13),2),
                'RETENCION A TERCEROS' => '-',
                'PERCEPCION' => $percepcion,
            ];
            $tabla->push($fila);
        }
        return view('informes.informeLibroCompras')
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function LibroComprasExcel(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $compras = Compra::whereBetween('fecha', [$fecha_inicio, $fecha_fin])->get();
        $tabla = collect();
        $fila = [
            'LGL S.A. DE C.V'
        ];
        $tabla->push($fila);
        $fila = [
            'LIBRO DE COMPRAS'
        ];
        $tabla->push($fila);
        $rango_fecha = 'Del ' . $fecha_inicio->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y');
        $rango_fecha = strtoupper($rango_fecha);
        $fila = [
            $rango_fecha, '', '' , '', '', 'REGISTRO: 76461-2', '', 'NIT: 0614-251093-106-9'
        ];
        $tabla->push($fila);
        $fila = [
            'FECHA EMISION',
            'N° DE COMPRA',
            'N° DE REGISTRO',
            'NOMBRE DEL PROVEEDOR',
            'INTERNAS E',
            'IMPORTACION E',
            'INTERNAS G',
            'IMPORTACION G',
            'IVA CCF',
            'TOTAL DE COMPRAS',
            'RETENCION A TERCEROS',
            'PERCEPCION',
        ];
        $tabla->push($fila);
        foreach ($compras as $compra) {
            if ($compra->proveedor->nacional){
                $interna = number_format($compra->compra_total,2);
                $exportacion = number_format(0,2);
            } else {
                $interna = number_format(0,2);
                $exportacion = number_format($compra->compra_total,2);
            }
            if ($compra->proveedor->percepcion == true && $compra->compra_total >= 100){
                $percepcion = number_format(($compra->compra_total * 0.01),2);
            } else {
                $percepcion = '-';
            }
            $fila = [
                'FECHA EMISION' => $compra->fecha->format('d/m/Y'),
                'N° DE COMPRA' => $compra->numero,
                'N° DE REGISTRO' => $compra->proveedor->nrc,
                'NOMBRE DEL PROVEEDOR' => $compra->proveedor->nombre,
                'INTERNAS E' => '-',
                'IMPORTACION E' => '-',
                'INTERNAS G' => $interna,
                'IMPORTACION G' => $exportacion,
                'IVA CCF' => number_format(($compra->compra_total * 0.13),2),
                'TOTAL DE COMPRAS' => number_format(($compra->compra_total * 1.13),2),
                'RETENCION A TERCEROS' => '-',
                'PERCEPCION' => $percepcion,
            ];
            $tabla->push($fila);
        }
        $nombre_documento = 'informe-libro-compras-del-' . $fecha_inicio->format('d-m-Y') . ' al ' . $fecha_fin->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function LibroVentasCCF(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('tipo_documento_id','=',2)
            ->where('estado_venta_id','!=',3)->get();
        $tabla = collect();
        foreach ($ventas as $venta) {
            if ($venta->cliente->retencion == true && $venta->venta_total >= 100){
                $retencion = number_format(($venta->venta_total * 0.01),2);
            } else {
                $retencion = '-';
            }
            $fila = [
                'FECHA EMISION' => $venta->fecha->format('d/m/Y'),
                'N° DE VENTA' => $venta->numero,
                'N° DE REGISTRO' => $venta->cliente->nrc,
                'NOMBRE DEL CLIENTE' => $venta->cliente->nombre,
                'PROPIAS EXENTAS' => '-',
                'PROPIAS GRAVADAS' => number_format($venta->venta_total,2),
                'PROPIAS DEB FISCAL' => number_format(($venta->venta_total * 0.13),2),
                'TERCEROS EXENTAS' => '-',
                'TERCEROS GRAVADAS' => '-',
                'TERCEROS DEB FISCAL' => '-',
                'IVA RETENIDO' => $retencion,
                'TOTAL' => number_format(($venta->venta_total * 1.13),2),
            ];
            $tabla->push($fila);
        }
        return view('informes.informeLibroVentasCCF')
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function LibroVentasCCFExcel(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('tipo_documento_id','=',2)
            ->where('estado_venta_id','!=',3)->get();
        $tabla = collect();
        // Cabeceras
        $fila = [
            'LGL S.A. DE C.V'
        ];
        $tabla->push($fila);
        $fila = [
            'LIBRO DE VENTAS CCF'
        ];
        $tabla->push($fila);
        $rango_fecha = 'Del ' . $fecha_inicio->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y');
        $rango_fecha = strtoupper($rango_fecha);
        $fila = [
            $rango_fecha, '', '' , '', '', 'REGISTRO: 76461-2', '', 'NIT: 0614-251093-106-9'
        ];
        $tabla->push($fila);
        $fila = [
            'FECHA EMISION',
            'N° DE VENTA',
            'N° DE REGISTRO',
            'NOMBRE DEL CLIENTE',
            'PROPIAS EXENTAS',
            'PROPIAS GRAVADAS',
            'PROPIAS DEB FISCAL',
            'TERCEROS EXENTAS',
            'TERCEROS GRAVADAS',
            'TERCEROS DEB FISCAL',
            'IVA RETENIDO',
            'TOTAL',
        ];
        $tabla->push($fila);
        // Fin cabeceras
        foreach ($ventas as $venta) {
            if ($venta->cliente->retencion == true && $venta->venta_total >= 100){
                $retencion = number_format(($venta->venta_total * 0.01),2);
            } else {
                $retencion = '-';
            }
            $fila = [
                'FECHA EMISION' => $venta->fecha->format('d/m/Y'),
                'N° DE VENTA' => $venta->numero,
                'N° DE REGISTRO' => $venta->cliente->nrc,
                'NOMBRE DEL CLIENTE' => $venta->cliente->nombre,
                'PROPIAS EXENTAS' => '-',
                'PROPIAS GRAVADAS' => number_format($venta->venta_total,2),
                'PROPIAS DEB FISCAL' => number_format(($venta->venta_total * 0.13),2),
                'TERCEROS EXENTAS' => '-',
                'TERCEROS GRAVADAS' => '-',
                'TERCEROS DEB FISCAL' => '-',
                'IVA RETENIDO' => $retencion,
                'TOTAL' => number_format(($venta->venta_total * 1.13),2),
            ];
            $tabla->push($fila);
        }
        $nombre_documento = 'informe-libro-ventas-ccf-del-' . $fecha_inicio->format('d-m-Y') . ' al ' . $fecha_fin->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function LibroVentasFAC(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('tipo_documento_id','=',1)
            ->where('estado_venta_id','!=',3)->get();
        $tabla = collect();
        foreach ($ventas as $venta) {
            if ($venta->cliente->retencion == true && $venta->venta_total >= 100){
                $retencion = number_format(($venta->venta_total * 0.01),2);
            } else {
                $retencion = '-';
            }
            $fila = [
                'FECHA EMISION' => $venta->fecha->format('d/m/Y'),
                'N° DE VENTA' => $venta->numero,
                'N° DE REGISTRO' => $venta->cliente->nrc,
                'NOMBRE DEL CLIENTE' => $venta->cliente->nombre,
                'PROPIAS EXENTAS' => '-',
                'PROPIAS GRAVADAS' => number_format($venta->venta_total,2),
                'PROPIAS DEB FISCAL' => number_format(($venta->venta_total * 0.13),2),
                'TERCEROS EXENTAS' => '-',
                'TERCEROS GRAVADAS' => '-',
                'TERCEROS DEB FISCAL' => '-',
                'IVA RETENIDO' => $retencion,
                'TOTAL' => number_format(($venta->venta_total * 1.13),2),
            ];
            $tabla->push($fila);
        }
        return view('informes.informeLibroVentasFAC')
            ->with(['datos' => $datos])
            ->with(['tabla' => $tabla]);
    }

    public function LibroVentasFACExcel(Request $request)
    {
        $fecha_inicio = ($request->input('fecha_inicio') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_inicio'));
        $fecha_fin = ($request->input('fecha_fin') == null) ? Carbon::now() : Carbon::parse($request->input('fecha_fin'));
        $datos = [];
        $datos += ['fecha_inicio' => $fecha_inicio];
        $datos += ['fecha_fin' => $fecha_fin];
        $ventas = Venta::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('tipo_documento_id','=',1)
            ->where('estado_venta_id','!=',3)->get();
        $tabla = collect();
        // Cabeceras
        $fila = [
            'LGL S.A. DE C.V'
        ];
        $tabla->push($fila);
        $fila = [
            'LIBRO DE VENTAS FACTURAS'
        ];
        $tabla->push($fila);
        $rango_fecha = 'Del ' . $fecha_inicio->format('d/m/Y') . ' al ' . $fecha_fin->format('d/m/Y');
        $rango_fecha = strtoupper($rango_fecha);
        $fila = [
            $rango_fecha, '', '' , '', '', 'REGISTRO: 76461-2', '', 'NIT: 0614-251093-106-9'
        ];
        $tabla->push($fila);
        $fila = [
            'FECHA EMISION',
            'N° DE VENTA',
            'N° DE REGISTRO',
            'NOMBRE DEL CLIENTE',
            'PROPIAS EXENTAS',
            'PROPIAS GRAVADAS',
            'PROPIAS DEB FISCAL',
            'TERCEROS EXENTAS',
            'TERCEROS GRAVADAS',
            'TERCEROS DEB FISCAL',
            'IVA RETENIDO',
            'TOTAL',
        ];
        $tabla->push($fila);
        // Fin cabeceras
        foreach ($ventas as $venta) {
            if ($venta->cliente->retencion == true && $venta->venta_total >= 100){
                $retencion = number_format(($venta->venta_total * 0.01),2);
            } else {
                $retencion = '-';
            }
            $fila = [
                'FECHA EMISION' => $venta->fecha->format('d/m/Y'),
                'N° DE VENTA' => $venta->numero,
                'N° DE REGISTRO' => $venta->cliente->nrc,
                'NOMBRE DEL CLIENTE' => $venta->cliente->nombre,
                'PROPIAS EXENTAS' => '-',
                'PROPIAS GRAVADAS' => number_format($venta->venta_total,2),
                'PROPIAS DEB FISCAL' => number_format(($venta->venta_total * 0.13),2),
                'TERCEROS EXENTAS' => '-',
                'TERCEROS GRAVADAS' => '-',
                'TERCEROS DEB FISCAL' => '-',
                'IVA RETENIDO' => $retencion,
                'TOTAL' => number_format(($venta->venta_total * 1.13),2),
            ];
            $tabla->push($fila);
        }
        $nombre_documento = 'informe-libro-ventas-fac-del-' . $fecha_inicio->format('d-m-Y') . ' al ' . $fecha_fin->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

}
