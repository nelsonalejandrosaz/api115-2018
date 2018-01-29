<?php

namespace App\Http\Controllers;

use App\Movimiento;
use App\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function InventarioLista()
    {
        $productos = Producto::all();
        foreach ($productos as $producto) {
            $producto->costo_total = $producto->cantidad_existencia * $producto->costo;
            $producto->porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
        }
//        dd($productos[15]);
        return view('inventario.inventarioLista')->with(['productos' => $productos]);
    }

    public function InventarioKardex(Request $request)
    {
        $producto = Producto::find($request->id);
        $movimientos = $producto->movimientos()
            ->where('procesado','=',true)
            ->orderBy('fecha_procesado','asc')->get();
        return view('inventario.kardex')
            ->with(['movimientos' => $movimientos])
            ->with(['producto' => $producto]);
    }

    public function InventarioKardexPost(Request $request)
    {
        /**
         * Validacion de datos
         */
        $this->validate($request,[
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
        ]);

        $fecha_inicio = $request->input('fecha_inicio');
        $fecha_fin = $request->input('fecha_fin');
        $producto = Producto::find($request->id);
        $movimientos = $producto->movimientos()->whereBetween('fecha',[$fecha_inicio,$fecha_fin])->where('procesado','=',true)->orderBy('fecha_procesado','asc')->get();
//            ->where('procesado','=',true)->orderBy('fecha_procesado','asc')->get();
        foreach ($movimientos as $movimiento)
        {
            $movimiento->costo_total_existencia = $movimiento->cantidad_existencia * $movimiento->costo_unitario_existencia;
        }
        return view('inventario.kardex')->with(['movimientos' => $movimientos])->with(['producto' => $producto]);
    }
}
