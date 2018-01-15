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
            $producto->costoTotal = $producto->cantidadExistencia * $producto->costo;
            $producto->porcentajeStock = abs(($producto->cantidadExistencia) / ($producto->existenciaMax - $producto->existenciaMin) * 100);
        }
        return view('inventario.inventarioLista')->with(['productos' => $productos]);
    }

    public function InventarioKardex(Request $request)
    {

//        dd(Carbon::now());
        $producto = Producto::find($request->id);
//        Movimiento::find(1)->ajuste
        $movimientos = $producto->movimientos()->where('procesado','=',true)->orderBy('fechaProcesado','asc')->get();
        return view('inventario.kardex')->with(['movimientos' => $movimientos])->with(['producto' => $producto]);
    }
}
