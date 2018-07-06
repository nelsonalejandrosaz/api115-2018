<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Compra;
use App\ExportacionSac;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportarController extends Controller
{
    public function configuracionSAC()
    {
        $exportacion_sacs = ExportacionSac::all();
        return view('exportar.configuracion',[
            'exportacion_sacs' => $exportacion_sacs,
        ]);
    }

    public function store(Request $request)
    {
        $cuentas_generales = ExportacionSac::all();
        $fecha = Carbon::parse($request->input('fecha'))->format('Y/m/d');
        $fechaC = Carbon::parse($request->input('fecha'));
        $ventas_dia = Venta::whereFecha($fecha)->get();
        $compras_dia = Compra::whereFecha($fecha)->get();
        $tabla = collect();
//        dd($ventas_dia);
        // Ventas del dia
        foreach ($ventas_dia as $venta) {
            // Ventas contado
            if ($venta->condicion_pago_id = 1) {
                // Compara si se pago el documento este dia
                $abonos_dia = $venta->abonos->where('fecha','=',$fechaC);
                $saldo_pendiente = round($venta->venta_total_con_impuestos,2) - round($abonos_dia->sum('cantidad'),2);
                if ($saldo_pendiente == 0.00) {
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'Venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format($venta->venta_total_con_impuestos,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
                        'concepto' => 'Por venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($venta->venta_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
                        'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format(($venta->venta_total * 0.13),2),
                    ];
                    $tabla->push($fila);
                } else {
                    $saldo_pagado = round($venta->venta_total_con_impuestos,2) - $saldo_pendiente;
                    if ($saldo_pagado > 0) {
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format($saldo_pagado,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                    }
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(12)->id_cuenta, // 12 - Cuenta clientes varios
                        'concepto' => 'Venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format($saldo_pendiente,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
                        'concepto' => 'Por venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($venta->venta_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
                        'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format(($venta->venta_total * 0.13),2),
                    ];
                    $tabla->push($fila);
                }
            }
            // Venta Credito
            elseif ($venta->condicion_pago_id > 1) {
                if ($venta->cliente->retencion == false) {
                    // Logica venta credito sin retencion
                    $fila = [
                        'id_cuenta' => $venta->cliente->cuenta_contable,
                        'concepto' => 'Venta contribuyente # ' . $venta->numero,
                        'cargo' => number_format($venta->venta_total_con_impuestos,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas Contribuyentes
                        'concepto' => 'Por venta contribuyente # ' . $venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($venta->venta_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
                        'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format(($venta->venta_total * 0.13),2),
                    ];
                    $tabla->push($fila);
                } else {
                    // Logica venta credito con retencion
                    if(round($venta->venta_total) >= 100) {
                        // Logica Venta CCF con retencion
                        $retencion = $venta->venta_total * 0.01;
                        $venta_total = $venta->venta_total * 1.12;
                        $fila = [
                            'id_cuenta' => $venta->cliente->cuenta_contable,
                            'concepto' => 'Venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format($venta_total,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(6)->id_cuenta, // 6 - Anticipo a impuesto por venta  -- IVA RETENIDO
                            'concepto' => 'Anticipo a impuesto por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format($retencion,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas Contribuyentes
                            'concepto' => 'Por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
                            'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                    } else {
                        // Logica venta credito sin retencion
                        $fila = [
                            'id_cuenta' => $venta->cliente->cuenta_contable,
                            'concepto' => 'Venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas Contribuyentes
                            'concepto' => 'Por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
                            'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                    }
                }
            }
        }

        // Compras contado
        foreach ($compras_dia as $compra) {
            // Compra contado
            if ($compra->condicion_pago_id == 1) {
                // -- Locales sin percepcion
                if ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == false) {
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'Por compra # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA por compra # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Locales con percepcion
                elseif ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == true){
                    $percepcion = $compra->compra_total * 0.01;
                    $compra_total = $compra->compra_total * 1.14;
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(9)->id_cuenta, // 9 - IVA PERCIBIDO
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format($percepcion,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'Por compra # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA por compra # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'Por compra # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(10)->id_cuenta, // 10 - IVA compras importaciones
                        'concepto' => 'IVA por compra # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
            // Compra credito
            else {
                // -- Locales sin percepcion
                if ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == false) {
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'Por compra # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA por compra # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Locales con percepcion
                elseif ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == true){
                    $percepcion = $compra->compra_total * 0.01;
                    $compra_total = $compra->compra_total * 1.14;
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(9)->id_cuenta, // 9 - IVA PERCIBIDO
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format($percepcion,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'Por compra # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA por compra # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'Compra # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'Por compra # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(10)->id_cuenta, // 10 - IVA compras importaciones
                        'concepto' => 'IVA por compra # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }

        // Abonos
        $abonos = Abono::where('fecha','=',$fecha)->get();
//        dd($abonos);
        foreach ($abonos as $abono) {
            // Efectivo
            if ($abono->forma_pago_id == 1 && $abono->venta->condicion_pago_id > 1) {
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                    'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'Por abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } elseif(($abono->forma_pago_id == 2 || $abono->forma_pago_id == 3) && $abono->venta->condicion_pago_id > 1) {
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(11)->id_cuenta, // 11 - Cuentas corrientes
                    'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'Por abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            }
            if ($abono->forma_pago_id == 1 && $abono->venta->condicion_pago_id == 1) {
                if ($abono->fecha != $abono->venta->fecha) {
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                        'cargo' => number_format($abono->cantidad,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(12)->id_cuenta, // 12 - Cuenta clientes varios
                        'concepto' => 'Por abono a compra # ' . $abono->venta->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($abono->cantidad,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }
//        dd($tabla);
        $nombre_documento = 'datos-para-sac-dia-' . $fecha;
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('csv');
    }

    public function edit($id)
    {
        $exportacion_sac = ExportacionSac::find($id);
        return view('exportar.edit',[
            'exportacion_sac' => $exportacion_sac,
        ]);
    }

    public function update(Request $request, $id)
    {
        $exportacion_sac = ExportacionSac::find($id);
        $this->validate($request, [
            'id_cuenta' => 'required|numeric|min:0',
        ]);
        $exportacion_sac->update($request->all());
        return redirect()->route('exportar.configuracion');
    }
}
