<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function AdministradorInicio()
    {
        $reportes = [];
        $productos = Producto::all();
        $porcentaje_stock = 0;
        $productos_existencia_alta = 0;
        $productos_existencia_media = 0;
        $productos_existencia_baja = 0;
        $clientes = Cliente::all();
        $clientes_con_saldo = Cliente::where('saldo','>',0)->get();
        $clientes_con_saldo = sizeof($clientes_con_saldo);
        foreach ($productos as $producto)
        {
            $porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            if ($porcentaje_stock >= 40)
            {
                // alta
                $productos_existencia_alta++;
            } elseif ($porcentaje_stock >= 20)
            {
                // media
                $productos_existencia_media++;
            } else
            {
                // baja
                $productos_existencia_baja++;
            }
        }
        $reportes['productos_existencia_alta'] = $productos_existencia_alta;
        $reportes['productos_existencia_media'] = $productos_existencia_media;
        $reportes['productos_existencia_baja'] = $productos_existencia_baja;
        $reportes['productos_total'] = sizeof($productos);
        $reportes['clientes_total'] = sizeof($clientes);
        $reportes['clientes_con_saldo'] = $clientes_con_saldo;
//        $reportes['productos'] = $productos;
//        dd(Venta::whereDate('fecha',Carbon::createFromDate(2018,1,2)->format('Y-m-d'))->get());
        return view('inicio.inicioAdministrador')
            ->with(['reportes' => $reportes]);
    }

    public function BodegaInicio()
    {

    }
}
