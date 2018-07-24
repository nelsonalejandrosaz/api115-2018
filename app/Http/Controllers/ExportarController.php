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

    public function store3(Request $request) {
        $cuentas_generales = ExportacionSac::all();
        $fecha = Carbon::parse($request->input('fecha'));
        $ventas_dia = Venta::whereFecha($fecha->format('Y/m/d'))->get();
        $compras_dia = Compra::whereFecha($fecha->format('Y/m/d'))->get();
        $abonos_dia = Abono::whereFecha($fecha->format('Y/m/d'))->get();
        $tabla = collect();

        // Variables
//        $suma_ventas_fac = $ventas_dia->where('tipo_documento_id',1)->sum('venta_total_con_impuestos');
        $suma_ventas_fac = 0.00;
        $suma_abonos_fac = 0.00;

        // Ventas del dia
        foreach ($ventas_dia as $venta) {
            // Ventas FAC
            if ($venta->tipo_documento_id == 1) {
                if ($venta->fecha_liquidado == $fecha) {
                    // Logica venta fac pagada
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
                    // Logica venta fac no pagada
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(12)->id_cuenta, // 12 - Cuenta clientes varios
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
                }
            } else {
                // Logica venta ccf al credito
                $fila = [
                    'id_cuenta' => $venta->cliente->cuenta_contable, // CxC de cliente
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
            }
        } // Fin ventas del dia
        // Abonos
        foreach ($abonos_dia as $abono) {

        }

    }

    public function store(Request $request)
    {
        $cuentas_generales = ExportacionSac::all();
        $fecha = Carbon::parse($request->input('fecha'))->format('Y/m/d');
        $fechaC = Carbon::parse($request->input('fecha'));
        $ventas_dia = Venta::whereFecha($fecha)->where('estado_venta_id','!=','3')->get();
        $compras_dia = Compra::whereFecha($fecha)->get();
        $tabla = collect();

        //
        $suma_caja_ventas = 0.00;
        $suma_ventas_fac = 0.00;
        $suma_ventas_ccf = 0.00;
        $suma_iva_fac = 0.00;
        $suma_iva_ccf = 0.00;


        // Ventas del dia
        foreach ($ventas_dia as $venta) {
            // Ventas contado
            if ($venta->condicion_pago_id == 1) {
                // Compara si se pago el documento este dia
                $abonos_dia = $venta->abonos->where('fecha','=',$fechaC);
                $saldo_pendiente = round($venta->venta_total_con_impuestos,2) - round($abonos_dia->sum('cantidad'),2);
                if ($saldo_pendiente == 0.00) {
                    // Comparar si es FAC o CCF
                    if ($venta->tipo_documento_id == 1) { // Es FAC
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += $venta->venta_total_con_impuestos;
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
                            'concepto' => 'Por venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $suma_ventas_fac += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
                            'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_fac += number_format(($venta->venta_total * 0.13),2);
                    } else { // Es CCF
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta por contribuyente # ' . $venta->numero,
                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += $venta->venta_total_con_impuestos;
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 2 - Ventas CCF
                            'concepto' => 'Por venta Venta por contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $suma_ventas_ccf += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito CCF
                            'concepto' => 'IVA por Venta por contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                    }
                } else { // No se pago el documento en el dia
                    $saldo_pagado = round($venta->venta_total_con_impuestos,2) - $saldo_pendiente;
                    // Comprobar si es FAC o CCF
                    if ($venta->tipo_documento_id == 1) { // Es FAC
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format($saldo_pagado,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += $venta->venta_total_con_impuestos;
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(12)->id_cuenta, // 12 - Cuenta clientes varios
                            'concepto' => 'FAC # ' . $venta->numero,
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
                        $suma_ventas_fac += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
                            'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_fac += number_format(($venta->venta_total * 0.13),2);
                    } else { // Es CCF
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format($saldo_pagado,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += number_format($saldo_pagado,2);
                        $fila = [
                            'id_cuenta' => $venta->cliente->cuenta_contable, // CxC de cliente
                            'concepto' => 'CCF # ' . $venta->numero,
                            'cargo' => number_format($saldo_pendiente,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas CCF
                            'concepto' => 'CCF # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $suma_ventas_ccf += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas CCF
                            'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                    }
                }
            }
            // Venta Credito
            elseif ($venta->condicion_pago_id > 1) {
                // Logica venta credito
                $fila = [
                    'id_cuenta' => $venta->cliente->cuenta_contable,
                    'concepto' => 'CCF # ' . $venta->numero,
                    'cargo' => number_format($venta->venta_total_con_impuestos,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas Contribuyentes
                    'concepto' => 'POR CCF # ' . $venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($venta->venta_total,2),
                ];
                $tabla->push($fila);
                $suma_ventas_ccf += number_format($venta->venta_total,2);
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
                    'concepto' => 'IVA POR CCF # ' . $venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format(($venta->venta_total * 0.13),2),
                ];
                $tabla->push($fila);
                $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                $abonos_ccf = $venta->abonos;
                if ($abonos_ccf->isNotEmpty()) {

                    foreach ($abonos_ccf as $abono) {
                        // Efectivo
                        if ($abono->forma_pago_id == 1 || $abono->forma_pago_id == 2) {
                            $fila = [
                                'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                                'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                                'cargo' => number_format($abono->cantidad,2),
                                'abono' => number_format(0,2),
                            ];
                            $tabla->push($fila);
                            $suma_caja_ventas += number_format($abono->cantidad,2);
                            $fila = [
                                'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                                'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                                'cargo' => number_format(0,2),
                                'abono' => number_format($abono->cantidad,2),
                            ];
                            $tabla->push($fila);
                        } elseif(($abono->forma_pago_id == 3)) {
                            $fila = [
                                'id_cuenta' => '1101030102', // 11 - Cuentas corrientes Agricola
                                'concepto' => 'ABONO A COMPRAS # ' . $abono->venta->numero,
                                'cargo' => number_format($abono->cantidad,2),
                                'abono' => number_format(0,2),
                            ];
                            $tabla->push($fila);
                            $fila = [
                                'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                                'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                                'cargo' => number_format(0,2),
                                'abono' => number_format($abono->cantidad,2),
                            ];
                            $tabla->push($fila);
                        } elseif ($abono->forma_pago_id == 4) { // Retencion
                            $fila = [
                                'id_cuenta' => $cuentas_generales->find(6)->id_cuenta, // 6 - IVA retenido por ventas
                                'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                                'cargo' => number_format($abono->cantidad,2),
                                'abono' => number_format(0,2),
                            ];
                            $tabla->push($fila);
                            $fila = [
                                'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                                'concepto' => 'COMPROBANTE DE RETENCION ### POR VENTA # ' . $abono->venta->numero,
                                'cargo' => number_format(0,2),
                                'abono' => number_format($abono->cantidad,2),
                            ];
                            $tabla->push($fila);
                        } elseif(($abono->forma_pago_id == 5)) {
                            $fila = [
                                'id_cuenta' => '1101030101', // 11 - Cuentas corrientes Citi
                                'concepto' => 'ABONO A COMPRA # ' . $abono->venta->numero,
                                'cargo' => number_format($abono->cantidad,2),
                                'abono' => number_format(0,2),
                            ];
                            $tabla->push($fila);
                            $fila = [
                                'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                                'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                                'cargo' => number_format(0,2),
                                'abono' => number_format($abono->cantidad,2),
                            ];
                            $tabla->push($fila);
                        } elseif(($abono->forma_pago_id == 6)) {
                            $fila = [
                                'id_cuenta' => '1101030103', // 11 - Cuentas corrientes Scotiablank
                                'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                                'cargo' => number_format($abono->cantidad,2),
                                'abono' => number_format(0,2),
                            ];
                            $tabla->push($fila);
                            $fila = [
                                'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                                'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                                'cargo' => number_format(0,2),
                                'abono' => number_format($abono->cantidad,2),
                            ];
                            $tabla->push($fila);
                        }
                    }

                }
            }
        }

        // Compras
        foreach ($compras_dia as $compra) {
            // Compra contado
            if ($compra->condicion_pago_id == 1) {
                // -- Locales sin percepcion
                if ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == false) {
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
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
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(9)->id_cuenta, // 9 - IVA PERCIBIDO
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format($percepcion,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(10)->id_cuenta, // 10 - IVA compras importaciones
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
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
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
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
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(9)->id_cuenta, // 9 - IVA PERCIBIDO
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format($percepcion,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(8)->id_cuenta, // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(7)->id_cuenta, // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(10)->id_cuenta, // 10 - IVA compras importaciones
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }

        // Abonos
        $abonos = Abono::where('fecha','=',$fecha)->get();
        $abonos_nd = collect();
        foreach ($abonos as $abono) {
            if ($abono->venta->fecha != $fechaC) {
                $abonos_nd->push($abono);
            }
        }
//        dd($abonos_nd);
        foreach ($abonos_nd as $abono) {
            // Efectivo
            if ($abono->forma_pago_id == 1 || $abono->forma_pago_id == 2) {
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                    'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $suma_caja_ventas += number_format($abono->cantidad,2);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } elseif(($abono->forma_pago_id == 3)) {
                $fila = [
                    'id_cuenta' => '1101030102', // 11 - Cuentas corrientes Agricola
                    'concepto' => 'ABONO A COMPRAS # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } elseif ($abono->forma_pago_id == 4) { // Retencion
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(6)->id_cuenta, // 6 - IVA retenido por ventas
                    'concepto' => 'COMPROBANTE DE RETENCION ### POR VENTA # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'COMPROBANTE DE RETENCION ### POR VENTA # ' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } elseif(($abono->forma_pago_id == 5)) {
                $fila = [
                    'id_cuenta' => '1101030101', // 11 - Cuentas corrientes Citi
                    'concepto' => 'ABONO A COMPRA # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } elseif(($abono->forma_pago_id == 6)) {
                $fila = [
                    'id_cuenta' => '1101030103', // 11 - Cuentas corrientes Scotiablank
                    'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' #' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            }
        }

        $tabla = $tabla->groupBy('id_cuenta');
        $tabla2 = collect();

//        dd($tabla);

        foreach ($tabla as $item) {
            $cargo = 0.00;
            $abono = 0.00;
            $codigo_cuenta = $item->first()['id_cuenta'];
            if ($codigo_cuenta == '110101') { // Caja general
                $concepto = "INGRESO DE ESTE DIA";
                $cargo += $item->sum('cargo');
                $abono += $item->sum('abono');
                if ($cargo > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format($cargo,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla2->push($fila);
                }
                if ($abono > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($abono,2),
                    ];
                    $tabla2->push($fila);
                }
            } elseif ($codigo_cuenta == '1101030101' || $codigo_cuenta == '1101030102' || $codigo_cuenta == '1101030103') { // Depositos
                $concepto = "INGRESO DE ESTE DIA ABONOS";
                $cargo += $item->sum('cargo');
                $abono += $item->sum('abono');
                if ($cargo > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format($cargo,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla2->push($fila);
                }
                if ($abono > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($abono,2),
                    ];
                    $tabla2->push($fila);
                }
            } elseif ($codigo_cuenta == '51010101' || $codigo_cuenta == '51010102') { // Ventas del dia
                $concepto = "VENTAS DE ESTE DIA";
                $cargo += $item->sum('cargo');
                $abono += $item->sum('abono');
                if ($cargo > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format($cargo,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla2->push($fila);
                }
                if ($abono > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($abono,2),
                    ];
                    $tabla2->push($fila);
                }
            } elseif ($codigo_cuenta == '210601' || $codigo_cuenta == '210602') { // IVA por ventas
                $concepto = 'IVA VENTAS DE ESTE DIA';
                $cargo += $item->sum('cargo');
                $abono += $item->sum('abono');
                if ($cargo > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format($cargo,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla2->push($fila);
                }
                if ($abono > 0) {
                    $fila = [
                        'id_cuenta' => $codigo_cuenta,
                        'concepto' => $concepto,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($abono,2),
                    ];
                    $tabla2->push($fila);
                }
            } else {
                foreach ($item as $value) {
                    $fila = [
                        'id_cuenta' => $value['id_cuenta'],
                        'concepto' => $value['concepto'],
                        'cargo' => number_format($value['cargo'],2),
                        'abono' => number_format($value['abono'],2),
                    ];
                    $tabla2->push($fila);
                }
            }
        }


//        dd($tabla2->sum('cargo'));

        $nombre_documento = 'datos-para-sac-dia-' . $fecha;
        Excel::create($nombre_documento, function ($excel) use ($tabla2) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla2) {

                $sheet->fromArray($tabla2);

            });
        })->download('csv');
    }

    public function store2(Request $request)
    {
        $cuentas_generales = ExportacionSac::all();
        $fecha = Carbon::parse($request->input('fecha'))->format('Y/m/d');
        $fechaC = Carbon::parse($request->input('fecha'));
        $ventas_dia = Venta::whereFecha($fecha)->where('estado_venta_id','!=','3')->get();
        $compras_dia = Compra::whereFecha($fecha)->get();
        $tabla = collect();

        //
        $suma_caja_ventas = 0.00;
        $suma_ventas_fac = 0.00;
        $suma_ventas_ccf = 0.00;
        $suma_iva_fac = 0.00;
        $suma_iva_ccf = 0.00;


        // Ventas del dia
        foreach ($ventas_dia as $venta) {
            // Ventas contado
            if ($venta->condicion_pago_id == 1) {
                // Compara si se pago el documento este dia
                $abonos_dia = $venta->abonos->where('fecha','=',$fechaC);
                $saldo_pendiente = round($venta->venta_total_con_impuestos,2) - round($abonos_dia->sum('cantidad'),2);
                if ($saldo_pendiente == 0.00) {
                    // Comparar si es FAC o CCF
                    if ($venta->tipo_documento_id == 1) { // Es FAC
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += $venta->venta_total_con_impuestos;
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
                            'concepto' => 'Por venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $suma_ventas_fac += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
                            'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_fac += number_format(($venta->venta_total * 0.13),2);
                    } else { // Es CCF
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta por contribuyente # ' . $venta->numero,
                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += $venta->venta_total_con_impuestos;
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 2 - Ventas CCF
                            'concepto' => 'Por venta Venta por contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $suma_ventas_ccf += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito CCF
                            'concepto' => 'IVA por Venta por contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                    }
                } else { // No se pago el documento en el dia
                    $saldo_pagado = round($venta->venta_total_con_impuestos,2) - $saldo_pendiente;
                    // Comprobar si es FAC o CCF
                    if ($venta->tipo_documento_id == 1) { // Es FAC
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format($saldo_pagado,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += $venta->venta_total_con_impuestos;
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
                        $suma_ventas_fac += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
                            'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_fac += number_format(($venta->venta_total * 0.13),2);
                    } else { // Es CCF
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                            'concepto' => 'Venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format($saldo_pagado,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $suma_caja_ventas += number_format($saldo_pagado,2);
                        $fila = [
                            'id_cuenta' => $venta->cliente->cuenta_contable, // CxC de cliente
                            'concepto' => 'Por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format($saldo_pendiente,2),
                            'abono' => number_format(0,2),
                        ];
                        $tabla->push($fila);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas CCF
                            'concepto' => 'Por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format($venta->venta_total,2),
                        ];
                        $tabla->push($fila);
                        $suma_ventas_ccf += number_format($venta->venta_total,2);
                        $fila = [
                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas CCF
                            'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
                            'cargo' => number_format(0,2),
                            'abono' => number_format(($venta->venta_total * 0.13),2),
                        ];
                        $tabla->push($fila);
                        $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                    }
                }
            }
            // Venta Credito
            elseif ($venta->condicion_pago_id > 1) {
                // Logica venta credito
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
                $suma_ventas_ccf += number_format($venta->venta_total,2);
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
                    'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format(($venta->venta_total * 0.13),2),
                ];
                $tabla->push($fila);
                $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
            }
        }

        // Compras
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
        $abonos_nd = collect();
        foreach ($abonos as $abono) {
            if ($abono->venta->fecha != $fechaC) {
                $abonos_nd->push($abono);
            }
        }
//        dd($abonos_nd);
        foreach ($abonos_nd as $abono) {
            // Efectivo
            if ($abono->forma_pago_id == 1 || $abono->forma_pago_id == 2) {
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
                    'concepto' => 'Abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
                $suma_caja_ventas += number_format($abono->cantidad,2);
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => 'Por abono a compra # ' . $abono->venta->numero,
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } elseif(($abono->forma_pago_id == 3)) {
                $fila = [
                    'id_cuenta' => '1101030102', // 11 - Cuentas corrientes Agricola
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
            } elseif ($abono->forma_pago_id == 4) { // Retencion
                $fila = [
                    'id_cuenta' => $cuentas_generales->find(6)->id_cuenta, // 6 - IVA retenido por ventas
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
            } elseif(($abono->forma_pago_id == 5)) {
                $fila = [
                    'id_cuenta' => '1101030101', // 11 - Cuentas corrientes Citi
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
            } elseif(($abono->forma_pago_id == 6)) {
                $fila = [
                    'id_cuenta' => '1101030103', // 11 - Cuentas corrientes Scotiablank
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
        }

//        $tabla = $tabla->groupBy('id_cuenta');
        $tabla2 = collect();



//        dd($tabla2->sum('abono'));

        $nombre_documento = 'datos-para-sac-dia-' . $fecha;
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('csv');
    }

    public function store77(Request $request)
{
    $cuentas_generales = ExportacionSac::all();
    $fecha = Carbon::parse($request->input('fecha'))->format('Y/m/d');
    $fechaC = Carbon::parse($request->input('fecha'));
    $ventas_dia = Venta::whereFecha($fecha)->where('estado_venta_id','!=','3')->get();
    $compras_dia = Compra::whereFecha($fecha)->get();
    $tabla = collect();

    //
    $suma_caja_ventas = 0.00;
    $suma_ventas_fac = 0.00;
    $suma_ventas_ccf = 0.00;
    $suma_iva_fac = 0.00;
    $suma_iva_ccf = 0.00;


    // Ventas del dia
    foreach ($ventas_dia as $venta) {
        // Ventas contado
        if ($venta->condicion_pago_id == 1) {
            // Compara si se pago el documento este dia
            $abonos_dia = $venta->abonos->where('fecha','=',$fechaC);
            $saldo_pendiente = round($venta->venta_total_con_impuestos,2) - round($abonos_dia->sum('cantidad'),2);
            if ($saldo_pendiente == 0.00) {
                // Comparar si es FAC o CCF
                if ($venta->tipo_documento_id == 1) { // Es FAC
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
//                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
//                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
//                            'abono' => number_format(0,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_caja_ventas += $venta->venta_total_con_impuestos;
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
//                            'concepto' => 'Por venta consumidor final # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format($venta->venta_total,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_ventas_fac += number_format($venta->venta_total,2);
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
//                            'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format(($venta->venta_total * 0.13),2),
//                        ];
//                        $tabla->push($fila);
                    $suma_iva_fac += number_format(($venta->venta_total * 0.13),2);
                } else { // Es CCF
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
//                            'concepto' => 'Venta por contribuyente # ' . $venta->numero,
//                            'cargo' => number_format($venta->venta_total_con_impuestos,2),
//                            'abono' => number_format(0,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_caja_ventas += $venta->venta_total_con_impuestos;
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 2 - Ventas CCF
//                            'concepto' => 'Por venta Venta por contribuyente # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format($venta->venta_total,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_ventas_ccf += number_format($venta->venta_total,2);
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito CCF
//                            'concepto' => 'IVA por Venta por contribuyente # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format(($venta->venta_total * 0.13),2),
//                        ];
//                        $tabla->push($fila);
                    $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                }
            } else { // No se pago el documento en el dia
                $saldo_pagado = round($venta->venta_total_con_impuestos,2) - $saldo_pendiente;
                // Comprobar si es FAC o CCF
                if ($venta->tipo_documento_id == 1) { // Es FAC
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
//                            'concepto' => 'Venta consumidor final # ' . $venta->numero,
//                            'cargo' => number_format($saldo_pagado,2),
//                            'abono' => number_format(0,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_caja_ventas += $venta->venta_total_con_impuestos;
                    $fila = [
                        'id_cuenta' => $cuentas_generales->find(12)->id_cuenta, // 12 - Cuenta clientes varios
                        'concepto' => 'Venta consumidor final # ' . $venta->numero,
                        'cargo' => number_format($saldo_pendiente,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
//                            'concepto' => 'Por venta consumidor final # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format($venta->venta_total,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_ventas_fac += number_format($venta->venta_total,2);
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a Consumidor Final
//                            'concepto' => 'IVA por venta consumidor final # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format(($venta->venta_total * 0.13),2),
//                        ];
//                        $tabla->push($fila);
                    $suma_iva_fac += number_format(($venta->venta_total * 0.13),2);
                } else { // Es CCF
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
//                            'concepto' => 'Venta contribuyente # ' . $venta->numero,
//                            'cargo' => number_format($saldo_pagado,2),
//                            'abono' => number_format(0,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_caja_ventas += number_format($saldo_pagado,2);
                    $fila = [
                        'id_cuenta' => $venta->cliente->cuenta_contable, // CxC de cliente
                        'concepto' => 'Por venta contribuyente # ' . $venta->numero,
                        'cargo' => number_format($saldo_pendiente,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas CCF
//                            'concepto' => 'Por venta contribuyente # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format($venta->venta_total,2),
//                        ];
//                        $tabla->push($fila);
                    $suma_ventas_ccf += number_format($venta->venta_total,2);
//                        $fila = [
//                            'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas CCF
//                            'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
//                            'cargo' => number_format(0,2),
//                            'abono' => number_format(($venta->venta_total * 0.13),2),
//                        ];
//                        $tabla->push($fila);
                    $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
                }
            }
        }
        // Venta Credito
        elseif ($venta->condicion_pago_id > 1) {
            // Logica venta credito
            $fila = [
                'id_cuenta' => $venta->cliente->cuenta_contable,
                'concepto' => 'Venta contribuyente # ' . $venta->numero,
                'cargo' => number_format($venta->venta_total_con_impuestos,2),
                'abono' => number_format(0,2),
            ];
            $tabla->push($fila);
//                $fila = [
//                    'id_cuenta' => $cuentas_generales->find(4)->id_cuenta, // 4 - Ventas Contribuyentes
//                    'concepto' => 'Por venta contribuyente # ' . $venta->numero,
//                    'cargo' => number_format(0,2),
//                    'abono' => number_format($venta->venta_total,2),
//                ];
//                $tabla->push($fila);
            $suma_ventas_ccf += number_format($venta->venta_total,2);
//                $fila = [
//                    'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
//                    'concepto' => 'IVA por venta contribuyente # ' . $venta->numero,
//                    'cargo' => number_format(0,2),
//                    'abono' => number_format(($venta->venta_total * 0.13),2),
//                ];
//                $tabla->push($fila);
            $suma_iva_ccf += number_format(($venta->venta_total * 0.13),2);
        }
    }

    // Compras
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
    $abonos_nd = collect();
    foreach ($abonos as $abono) {
        if ($abono->venta->fecha != $fechaC) {
            $abonos_nd->push($abono);
        }
    }
//        dd($abonos_nd);
    foreach ($abonos_nd as $abono) {
        // Efectivo
        if ($abono->forma_pago_id == 1 || $abono->forma_pago_id == 2) {
//                $fila = [
//                    'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
//                    'concepto' => 'Abono a compra # ' . $abono->venta->numero,
//                    'cargo' => number_format($abono->cantidad,2),
//                    'abono' => number_format(0,2),
//                ];
//                $tabla->push($fila);
            $suma_caja_ventas += number_format($abono->cantidad,2);
            $fila = [
                'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                'concepto' => 'Por abono a compra # ' . $abono->venta->numero,
                'cargo' => number_format(0,2),
                'abono' => number_format($abono->cantidad,2),
            ];
            $tabla->push($fila);
        } elseif(($abono->forma_pago_id == 3)) {
            $fila = [
                'id_cuenta' => '1101030102', // 11 - Cuentas corrientes Agricola
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
        } elseif ($abono->forma_pago_id == 4) { // Retencion
            $fila = [
                'id_cuenta' => $cuentas_generales->find(6)->id_cuenta, // 6 - IVA retenido por ventas
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
        } elseif(($abono->forma_pago_id == 5)) {
            $fila = [
                'id_cuenta' => '1101030101', // 11 - Cuentas corrientes Citi
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
        } elseif(($abono->forma_pago_id == 5)) {
            $fila = [
                'id_cuenta' => '1101030103', // 11 - Cuentas corrientes Scotiablank
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
    }
    // Caja
    $fila = [
        'id_cuenta' => $cuentas_generales->find(1)->id_cuenta, // 1 - Caja general
        'concepto' => 'Ingresos de este dia ',
        'cargo' => number_format($suma_caja_ventas, 2),
        'abono' => number_format(0, 2),
    ];
    $tabla->push($fila);
    // Ventas Consumidor final
    $fila = [
        'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
        'concepto' => 'Ventas de este dia',
        'cargo' => number_format(0, 2),
        'abono' => number_format($suma_ventas_fac, 2),
    ];
    $tabla->push($fila);
    // Ventas contribuyentes
    $fila = [
        'id_cuenta' => $cuentas_generales->find(2)->id_cuenta, // 2 - Ventas Consumidor Final
        'concepto' => 'Ventas de este dia',
        'cargo' => number_format(0, 2),
        'abono' => number_format($suma_ventas_ccf, 2),
    ];
    // IVA consumidor final
    $fila = [
        'id_cuenta' => $cuentas_generales->find(3)->id_cuenta, // 3 - IVA Débito Ventas a consumidor final
        'concepto' => 'Ventas de este dia',
        'cargo' => number_format(0, 2),
        'abono' => number_format($suma_iva_fac, 2),
    ];
    $tabla->push($fila);
    // IVA contribuyentes
    $fila = [
        'id_cuenta' => $cuentas_generales->find(5)->id_cuenta, // 5 - IVA Débito Ventas a Contribuyentes
        'concepto' => 'Ventas de este dia',
        'cargo' => number_format(0, 2),
        'abono' => number_format($suma_iva_ccf, 2),
    ];
    $tabla->push($fila);


    dd($suma_caja_ventas);


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

    public function show(Request $request) {

        $dia = ($request->input('dia') == null) ? Carbon::now()->format('Y-m-d') : $request->input('dia');
        $fecha = Carbon::parse($dia);
        $abonos = Abono::where('fecha', '=', $dia)->get();
        $ventaCredito = Venta::where('fecha', '=', $dia)->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $abono_deposito_ba = 0.00;
        $abono_deposito_bc = 0.00;
        $abono_deposito_bs = 0.00;
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
            } elseif ($abono->forma_pago->codigo == 'DEPBA') {
                $abono_deposito_ba += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'DEPBC') {
                $abono_deposito_bc += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'DEPBS') {
                $abono_deposito_bs += $abono->cantidad;
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
            $abonoZ = $venta->abonos->where('fecha','=',$fecha);
            if ($abonoZ->isEmpty() && $venta->estado_venta_id != 3) {
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
        $extra['abono_deposito_ba'] = $abono_deposito_ba;
        $extra['abono_deposito_bc'] = $abono_deposito_bc;
        $extra['abono_deposito_bs'] = $abono_deposito_bs;

        // Aqui va lo de la exportacion
        $tabla = collect();

        $ventas_dia_abono = collect();
        $cobros_otros_dias = collect();
        foreach ($cobros as $cobro) {
            if ($cobro->venta->fecha == $fecha) {
                $ventas_dia_abono->push($cobro);
            } else {
                $cobros_otros_dias->push($cobro);
            }
        }

        $ventasX = Venta::where('fecha','=',$dia);
        $fcf_dia = $ventasX->where('tipo_documento_id', '=', 1)->get();
        $ventasX = Venta::where('fecha','=',$dia);
        $ccf_dia = $ventasX->where('tipo_documento_id', '=', 2)->get();

        // CUENTA CAJA
        $caja = $abono_efectivo + $abono_cheque;
        $fila = [
            'id_cuenta' => '110101',
            'concepto' => 'INGRESOS DE ESTE DIA ',
            'cargo' => $caja,
            'abono' => 0,
        ];
        $tabla->push($fila);
        // CUENTA BANCO AGRICOLA
        if ($abono_deposito_ba > 0) {
            foreach ($abonos as $abono) {
                if ($abono->forma_pago->codigo == 'DEPBA') {
                    $fila = [
                        'id_cuenta' => '1101030102',
                        'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero,
                        'cargo' => $abono->cantidad,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
            }
        }
        // CUENTA CITI BANK
        if ($abono_deposito_bc > 0) {
            foreach ($abonos as $abono) {
                if ($abono->forma_pago->codigo == 'DEPBC') {
                    $fila = [
                        'id_cuenta' => '1101030101',
                        'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero,
                        'cargo' => $abono->cantidad,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
            }
        }
        // CUENTA BANCO SCOTIABANK
        if ($abono_deposito_bs > 0) {
            foreach ($abonos as $abono) {
                if ($abono->forma_pago->codigo == 'DEPBS') {
                    $fila = [
                        'id_cuenta' => '1101030103',
                        'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero,
                        'cargo' => $abono->cantidad,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
            }
        }
        // CUENTAS POR COBRAR
        foreach ($ventaCredito as $venta) {
            if ($venta->cliente->cuenta_contable != null) {
                $fila = [
                    'id_cuenta' => $venta->cliente->cuenta_contable,
                    'concepto' => $venta->tipo_documento->codigo . ' ' . $venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => $venta->venta_total_con_impuestos,
                    'abono' => 0,
                ];
                $tabla->push($fila);
            } else {
                // CUENTA POR COBRAR GENERAL
                $fila = [
                    'id_cuenta' => '11030101999',
                    'concepto' => $venta->tipo_documento->codigo . ' ' . $venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => $venta->venta_total_con_impuestos,
                    'abono' => 0,
                ];
                $tabla->push($fila);
            }
        }
        // CUENTAS POR COBRAR CON ABONO
        foreach ($ventas_dia_abono as $abono) {
            $ventaY = $abono->venta;
            $abonosY = $ventaY->abonos->where('fecha','=',$fecha);
            $saldoY = round($ventaY->venta_total_con_impuestos,2) - round($abonosY->sum('cantidad'),2);
            if ($saldoY > 0.00) {
                if ($abono->venta->cliente->cuenta_contable != null) {
                    $fila = [
                        'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                        'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                        'cargo' => $saldoY,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                } else {
                    $fila = [
                        'id_cuenta' => '11030101999',
                        'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                        'cargo' => $saldoY,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }

            }
        }
        // IVA RETENIDO
        foreach ($abonos as $abono) {
            if ($abono->forma_pago->codigo == 'RETEN') {
                $fila = [
                    'id_cuenta' => '210605',
                    'concepto' => 'RETENCION DE ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => $abono->cantidad,
                    'abono' => 0,
                ];
                $tabla->push($fila);
            }
        }
        // CUENTAS POR COBRAR DESCARGO
        foreach ($cobros_otros_dias as $abono) {
            if ($abono->venta->cliente->cuenta_contable != null) {
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => 0,
                    'abono' => $abono->cantidad,
                ];
                $tabla->push($fila);
            } else {
                $fila = [
                    'id_cuenta' => '11030101999',
                    'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => 0,
                    'abono' => $abono->cantidad,
                ];
                $tabla->push($fila);
            }
        }
        // IVA variables
        $total_ventas_fac = $fcf_dia->sum('venta_total');
        $total_ventas_ccf = $ccf_dia->sum('venta_total');
        $iva_venta_fac = $fcf_dia->sum('venta_total_con_impuestos') - $fcf_dia->sum('venta_total');
        $iva_venta_ccf = $ccf_dia->sum('venta_total_con_impuestos') - $ccf_dia->sum('venta_total');
        // IVA
        $fila = [
            'id_cuenta' => '210602',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => 0,
            'abono' => $iva_venta_fac,
        ];
        $tabla->push($fila);
        $fila = [
            'id_cuenta' => '210601',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => 0,
            'abono' => $iva_venta_ccf,
        ];
        $tabla->push($fila);
        // VENTAS
        $fila = [
            'id_cuenta' => '51010102',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => 0,
            'abono' => $total_ventas_fac,
        ];
        $tabla->push($fila);
        $fila = [
            'id_cuenta' => '51010101',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => 0,
            'abono' => $total_ventas_ccf,
        ];
        $tabla->push($fila);

        // COMPRAS
        $compras_dia = Compra::whereFecha($dia)->get();
        foreach ($compras_dia as $compra) {
            // Compra contado
            if ($compra->condicion_pago_id == 1) {
                // -- Locales sin percepcion
                if ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == false) {
                    $fila = [
                        'id_cuenta' => '110101', // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => 0,
                        'abono' => $compra->compra_total_con_impuestos,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total * 0.13,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
                // -- Locales con percepcion
                elseif ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == true){
                    $percepcion = $compra->compra_total * 0.01;
                    $compra_total = $compra->compra_total * 1.14;
                    $fila = [
                        'id_cuenta' => '110101', // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => 0,
                        'abono' => $compra_total,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110704', // 9 - IVA PERCIBIDO
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => $percepcion,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => ($compra->compra_total * 0.13),
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => '110101', // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => 0,
                        'abono' => $compra->compra_total_con_impuestos,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110702', // 10 - IVA compras importaciones
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => ($compra->compra_total * 0.13),
                        'abono' => 0,
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
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => 0,
                        'abono' => $compra->compra_total_con_impuestos,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => ($compra->compra_total * 0.13),
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
                // -- Locales con percepcion
                elseif ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == true){
                    $percepcion = $compra->compra_total * 0.01;
                    $compra_total = $compra->compra_total * 1.14;
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => 0,
                        'abono' => $compra_total,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110704', // 9 - IVA PERCIBIDO
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => $percepcion,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => ($compra->compra_total * 0.13),
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => 0,
                        'abono' => $compra->compra_total_con_impuestos,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => $compra->compra_total,
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110702', // 10 - IVA compras importaciones
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => ($compra->compra_total * 0.13),
                        'abono' => 0,
                    ];
                    $tabla->push($fila);
                }
            }
        }

        return view('exportar.informeExportacionSAC',[
            'tabla' => $tabla,
            'fecha' => $fecha,
        ]);
    }

    public function showExcel(Request $request) {

        $dia = ($request->input('dia') == null) ? Carbon::now()->format('Y-m-d') : $request->input('dia');
        $fecha = Carbon::parse($dia);
        $abonos = Abono::where('fecha', '=', $dia)->get();
        $ventaCredito = Venta::where('fecha', '=', $dia)->get();
        $extra['dia'] = $dia;
        $abono_total = 0.00;
        $abono_efectivo = 0.00;
        $abono_cheque = 0.00;
        $abono_retencion = 0.00;
        $abono_deposito_ba = 0.00;
        $abono_deposito_bc = 0.00;
        $abono_deposito_bs = 0.00;
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
            } elseif ($abono->forma_pago->codigo == 'DEPBA') {
                $abono_deposito_ba += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'DEPBC') {
                $abono_deposito_bc += $abono->cantidad;
            } elseif ($abono->forma_pago->codigo == 'DEPBS') {
                $abono_deposito_bs += $abono->cantidad;
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
        $extra['abono_deposito_ba'] = $abono_deposito_ba;
        $extra['abono_deposito_bc'] = $abono_deposito_bc;
        $extra['abono_deposito_bs'] = $abono_deposito_bs;

        // Aqui va lo de la exportacion
        $tabla = collect();

        $ventas_dia_abono = collect();
        $cobros_otros_dias = collect();
        foreach ($cobros as $cobro) {
            if ($cobro->venta->fecha == $fecha) {
                $ventas_dia_abono->push($cobro);
            } else {
                $cobros_otros_dias->push($cobro);
            }
        }

        $ventasX = Venta::where('fecha','=',$dia);
        $fcf_dia = $ventasX->where('tipo_documento_id', '=', 1)->get();
        $ventasX = Venta::where('fecha','=',$dia);
        $ccf_dia = $ventasX->where('tipo_documento_id', '=', 2)->get();

        // CUENTA CAJA
        $caja = $abono_efectivo + $abono_cheque;
        $fila = [
            'id_cuenta' => '110101',
            'concepto' => 'INGRESOS DE ESTE DIA ',
            'cargo' => number_format($caja,2),
            'abono' => number_format(0,2),
        ];
        $tabla->push($fila);
        // CUENTA BANCO AGRICOLA
        if ($abono_deposito_ba > 0) {
            foreach ($abonos as $abono) {
                if ($abono->forma_pago->codigo == 'DEPBA') {
                    $fila = [
                        'id_cuenta' => '1101030102',
                        'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero,
                        'cargo' => number_format($abono->cantidad,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }
        // CUENTA CITI BANK
        if ($abono_deposito_bc > 0) {
            foreach ($abonos as $abono) {
                if ($abono->forma_pago->codigo == 'DEPBC') {
                    $fila = [
                        'id_cuenta' => '1101030101',
                        'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero,
                        'cargo' => number_format($abono->cantidad,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }
        // CUENTA BANCO SCOTIABANK
        if ($abono_deposito_bs > 0) {
            foreach ($abonos as $abono) {
                if ($abono->forma_pago->codigo == 'DEPBS') {
                    $fila = [
                        'id_cuenta' => '1101030103',
                        'concepto' => 'CANCELACION ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero,
                        'cargo' => number_format($abono->cantidad,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }
        // CUENTAS POR COBRAR
        foreach ($ventaCredito as $venta) {
            if ($venta->cliente->cuenta_contable != null) {
                $fila = [
                    'id_cuenta' => $venta->cliente->cuenta_contable,
                    'concepto' => $venta->tipo_documento->codigo . ' ' . $venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => number_format($venta->venta_total_con_impuestos,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
            } else {
                // CUENTA POR COBRAR GENERAL
                $fila = [
                    'id_cuenta' => '11030101999',
                    'concepto' => $venta->tipo_documento->codigo . ' ' . $venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => number_format($venta->venta_total_con_impuestos,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
            }
        }
        // CUENTAS POR COBRAR CON ABONO
        foreach ($ventas_dia_abono as $abono) {
            if ($abono->venta->saldo > 0) {
                if ($abono->venta->cliente->cuenta_contable != null) {
                    $fila = [
                        'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                        'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                        'cargo' => number_format($abono->venta->saldo,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                } else {
                    $fila = [
                        'id_cuenta' => '11030101999',
                        'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                        'cargo' => number_format($abono->venta->saldo,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }

            }
        }
        // IVA RETENIDO
        foreach ($abonos as $abono) {
            if ($abono->forma_pago->codigo == 'RETEN') {
                $fila = [
                    'id_cuenta' => '210605',
                    'concepto' => 'RETENCION DE ' . $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => number_format($abono->cantidad,2),
                    'abono' => number_format(0,2),
                ];
                $tabla->push($fila);
            }
        }
        // COMPRAS

        // CUENTAS POR COBRAR DESCARGO
        foreach ($cobros_otros_dias as $abono) {
            if ($abono->venta->cliente->cuenta_contable != null) {
                $fila = [
                    'id_cuenta' => $abono->venta->cliente->cuenta_contable,
                    'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            } else {
                $fila = [
                    'id_cuenta' => '11030101999',
                    'concepto' => $abono->venta->tipo_documento->codigo . ' ' . $abono->venta->numero . ' ' . $fecha->format('d/m/Y'),
                    'cargo' => number_format(0,2),
                    'abono' => number_format($abono->cantidad,2),
                ];
                $tabla->push($fila);
            }
        }
        $total_ventas_fac = $fcf_dia->sum('venta_total');
        $total_ventas_ccf = $ccf_dia->sum('venta_total');
        $iva_venta_fac = $fcf_dia->sum('venta_total_con_impuestos') - $fcf_dia->sum('venta_total');
        $iva_venta_ccf = $ccf_dia->sum('venta_total_con_impuestos') - $ccf_dia->sum('venta_total');
        // IVA
        $fila = [
            'id_cuenta' => '210602',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => number_format(0,2),
            'abono' => number_format($iva_venta_fac,2),
        ];
        $tabla->push($fila);
        $fila = [
            'id_cuenta' => '210601',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => number_format(0,2),
            'abono' => number_format($iva_venta_ccf,2),
        ];
        $tabla->push($fila);
        // VENTAS
        $fila = [
            'id_cuenta' => '51010102',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => number_format(0,2),
            'abono' => number_format($total_ventas_fac,2),
        ];
        $tabla->push($fila);
        $fila = [
            'id_cuenta' => '51010101',
            'concepto' => 'VENTAS DE ESTE DIA',
            'cargo' => number_format(0,2),
            'abono' => number_format($total_ventas_ccf,2),
        ];
        $tabla->push($fila);

        // COMPRAS
        $compras_dia = Compra::whereFecha($dia)->get();
        foreach ($compras_dia as $compra) {
            // Compra contado
            if ($compra->condicion_pago_id == 1) {
                // -- Locales sin percepcion
                if ($compra->proveedor->nacional == true && $compra->proveedor->percepcion == false) {
                    $fila = [
                        'id_cuenta' => '110101', // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
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
                        'id_cuenta' => '110101', // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110704', // 9 - IVA PERCIBIDO
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format($percepcion,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => '110101', // 1 - Caja general
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110702', // 10 - IVA compras importaciones
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
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
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
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
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra_total,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110704', // 9 - IVA PERCIBIDO
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format($percepcion,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110701', // 8 - IVA compras locales
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
                // -- Importacion
                elseif ($compra->proveedor->nacional == false) {
                    $fila = [
                        'id_cuenta' => $compra->proveedor->cuenta_contable, // CxP Proveedor
                        'concepto' => 'COMPRA # ' . $compra->numero,
                        'cargo' => number_format(0,2),
                        'abono' => number_format($compra->compra_total_con_impuestos,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110501', // 7 - INVENTARIOS DE MATERIA PRIMA
                        'concepto' => 'POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format($compra->compra_total,2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                    $fila = [
                        'id_cuenta' => '110702', // 10 - IVA compras importaciones
                        'concepto' => 'IVA POR COMPRA # ' . $compra->numero,
                        'cargo' => number_format(($compra->compra_total * 0.13),2),
                        'abono' => number_format(0,2),
                    ];
                    $tabla->push($fila);
                }
            }
        }

        $nombre_documento = 'datos-para-sac-dia-' . $fecha->format('d-m-Y');
        Excel::create($nombre_documento, function ($excel) use ($tabla) {
            $excel->sheet('Abonos diarios', function ($sheet) use ($tabla) {

                $sheet->fromArray($tabla);

            });
        })->download('csv');
    }
}
