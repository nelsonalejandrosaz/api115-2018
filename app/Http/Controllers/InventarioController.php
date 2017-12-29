<?php

namespace App\Http\Controllers;

use App\Producto;
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
        $producto = Producto::find($request->id);
        $movimientos = $producto->movimientos;

        // $kardex = Kardex::where('producto_id',$request->id)->first();
        // $movimientos = Movimiento::where('kardex_id', $kardex->id)->get();

        // foreach ($movimientos as $movimiento) {
        //        if ($movimiento->entrada != null) {
        //            $movimiento->entrada->valorTotal = $movimiento->entrada->cantidad * $movimiento->entrada->valorUnitario;
        //        }
        //        if ($movimiento->salida != null) {
        //            $movimiento->salida->valorTotal = $movimiento->salida->cantidad * $movimiento->salida->valorUnitario;
        //        }
        // 	$movimiento->valorTotalExistencia = $movimiento->cantidadExistencia * $movimiento->valorUnitarioExistencia;
        // }
        return view('inventario.kardex')->with(['movimientos' => $movimientos])->with(['producto' => $producto]);
    }
}
