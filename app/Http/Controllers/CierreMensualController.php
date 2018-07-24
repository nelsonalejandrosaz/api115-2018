<?php

namespace App\Http\Controllers;

use App\Movimiento;
use App\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CierreMensualController extends Controller
{
    public function index(Request $request) {
        $mes = Carbon::parse('2018-04');
        $inicio_mes = $mes->startOfMonth()->format('Y-m-d');
        $fin_mes = $mes->endOfMonth()->format('Y-m-d');
        $tabla = collect();
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $movimientos_mes = Movimiento::where('producto_id','=',$producto->id)
                ->whereBetween('fecha_procesado',[$inicio_mes,$fin_mes])->where('tipo_movimiento_id','=',3)->get();
            $costo_mes = 0.00;
            foreach ($movimientos_mes as $movimiento) {
                $cantidad = $movimiento->cantidad;
                $costo_unitario = $movimiento->costo_unitario;
                $costo_mes += $cantidad * $costo_unitario;
            }
            if ($costo_mes > 0.00) {
                $fila = [
                    'codigo' => $producto->codigo,
                    'producto' => $producto->nombre,
                    'costo_vendido' => $costo_mes,
                ];
                $tabla->push($fila);
            }
        }

        return view('cierre.cierreMensual');
    }

    public function informeCostoVentas(Request $request)
    {
        $mes = ($request->input('mes') == null) ? Carbon::now() : Carbon::parse($request->input('mes'));
        $inicio_mes = $mes->startOfMonth()->format('Y-m-d');
        $fin_mes = $mes->endOfMonth()->format('Y-m-d');
        $datos['mes'] = $mes;
        $tabla = collect();
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $movimientos_mes = Movimiento::where('producto_id','=',$producto->id)
                ->whereBetween('fecha_procesado',[$inicio_mes,$fin_mes])->where('tipo_movimiento_id','=',3)->get();
            $cantidad_venta = 0.00;
            $costo_mes = 0.00;

            foreach ($movimientos_mes as $movimiento) {
                if ($movimiento->salida->orden_pedido->venta != null) {
                    if ($movimiento->salida->orden_pedido->venta->estado_venta_id != 3) {
                        // Aqui va la logica
                        $cantidad = $movimiento->cantidad;
                        $cantidad_venta += $cantidad;
                        $costo_unitario = $movimiento->costo_unitario;
                        $costo_mes += $cantidad * $costo_unitario;
                    }
                }

            }
            if ($costo_mes > 0.00) {
                $fila = [
                    'codigo' => $producto->codigo,
                    'producto' => $producto->nombre,
                    'cantidad_venta' => $cantidad_venta,
                    'costo_vendido' => $costo_mes,
                ];
                $tabla->push($fila);
            }
        }
        return view('informes.informeCostoVentas',[
            'datos' => $datos,
            'tabla' => $tabla->sortByDesc('costo_vendido'),
        ]);
    }

    public function informeCostoVentasExcel(Request $request)
    {
        $mes = ($request->input('mes') == null) ? Carbon::now() : Carbon::parse($request->input('mes'));
        $inicio_mes = $mes->startOfMonth()->format('Y-m-d');
        $fin_mes = $mes->endOfMonth()->format('Y-m-d');
        $datos['mes'] = $mes;
        $tabla = collect();
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $movimientos_mes = Movimiento::where('producto_id','=',$producto->id)
                ->whereBetween('fecha_procesado',[$inicio_mes,$fin_mes])->where('tipo_movimiento_id','=',3)->get();
            $cantidad_venta = 0.00;
            $costo_mes = 0.00;

            foreach ($movimientos_mes as $movimiento) {
                if ($movimiento->salida->orden_pedido->venta != null) {
                    if ($movimiento->salida->orden_pedido->venta->estado_venta_id != 3) {
                        // Aqui va la logica
                        $cantidad = $movimiento->cantidad;
                        $cantidad_venta += $cantidad;
                        $costo_unitario = $movimiento->costo_unitario;
                        $costo_mes += $cantidad * $costo_unitario;
                    }
                }

            }
            if ($costo_mes > 0.00) {
                $fila = [
                    'codigo' => $producto->codigo,
                    'producto' => $producto->nombre,
                    'cantidad_venta' => number_format($cantidad_venta,2),
                    'costo_vendido' => number_format($costo_mes,2),
                ];
                $tabla->push($fila);
            }
        }
        $nombre_documento = 'informe-costo-ventas-del-' . $mes->format('m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('xls');
    }

    public function informeCostoVentasSAC(Request $request)
    {
        $mes = ($request->input('mes') == null) ? Carbon::now() : Carbon::parse($request->input('mes'));
        $inicio_mes = $mes->startOfMonth()->format('Y-m-d');
        $fin_mes = $mes->endOfMonth()->format('Y-m-d');
        $datos['mes'] = $mes;
        $tabla = collect();
        $productos = Producto::all();

        foreach ($productos as $producto) {
            $movimientos_mes = Movimiento::where('producto_id','=',$producto->id)
                ->whereBetween('fecha_procesado',[$inicio_mes,$fin_mes])->where('tipo_movimiento_id','=',3)->get();
            $costo_mes = 0.00;
            foreach ($movimientos_mes as $movimiento) {
                if ($movimiento->salida->orden_pedido->venta != null) {
                    if ($movimiento->salida->orden_pedido->venta->estado_venta_id != 3) {
                        // Aqui va la logica
                        $cantidad = $movimiento->cantidad;
                        $costo_unitario = $movimiento->costo_unitario;
                        $costo_mes += $cantidad * $costo_unitario;
                    }
                }

            }
            if ($costo_mes > 0.00) {
                $fila = [
                    'codigo' => $producto->codigo,
                    'producto' => $producto->nombre,
                    'costo_vendido' => $costo_mes,
                ];
                $tabla->push($fila);
            }
        }

//        dd($tabla->sum('costo_vendido'));

        $tabla2 = collect();
        $fila = [
            'id_cuenta' => '110501', // Inventario
            'concepto' => 'COSTO DE VENTA DEL MES ' . $mes->format('m/Y'),
            'cargo' => round(0,2),
            'abono' => round(($tabla->sum('costo_vendido')),2),
        ];
        $tabla2->push($fila);
        $fila = [
            'id_cuenta' => '410101', // Costo de lo vendido
            'concepto' => 'COSTO DE VENTA DEL MES ' . $mes->format('m/Y'),
            'cargo' => round(($tabla->sum('costo_vendido')),2),
            'abono' => round(0,2),
        ];
        $tabla2->push($fila);

        $nombre_documento = 'sac-costo-ventas-del-' . $mes->format('m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla2) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla2) {

                $sheet->fromArray($tabla2);

            });
        })->download('csv');
    }

}
