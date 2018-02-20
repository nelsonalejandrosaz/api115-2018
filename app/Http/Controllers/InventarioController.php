<?php

namespace App\Http\Controllers;

use App\Movimiento;
use App\Producto;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function InventarioLista()
    {
        if (Auth::user()->rol->nombre == 'Administrador')
        {
            $productos = Producto::all();
            foreach ($productos as $producto) {
                $producto->costo_total = $producto->cantidad_existencia * $producto->costo;
                $producto->porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            }
            return view('inventario.inventarioLista')->with(['productos' => $productos]);
        } elseif(Auth::user()->rol->nombre == 'Bodeguero')
        {
            $productos = Producto::all();
            foreach ($productos as $producto) {
                $producto->costo_total = $producto->cantidad_existencia * $producto->costo;
                $producto->porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            }
            return view('inventario.inventarioListaBodega')->with(['productos' => $productos]);
        } else
        {
            $productos = Producto::where('codigo','like','PT%')
                ->orWhere('codigo','like','RV%')
                ->orWhere('codigo','like','MR%')
                ->orWhere('codigo','like','PM%')->get();
            foreach ($productos as $producto) {
                $producto->costo_total = $producto->cantidad_existencia * $producto->costo;
                $producto->porcentaje_stock = ($producto->cantidad_existencia / ($producto->existencia_max - $producto->existencia_min)) * 100;
            }
            return view('inventario.inventarioListaVB')->with(['productos' => $productos]);
        }
    }

    public function InventarioKardex(Request $request)
    {
        $producto = Producto::find($request->id);
        $mes_actual = Carbon::now()->format('m');
        $anio_actual = Carbon::now()->format('Y');
        $mes['inicio'] = $inicio_mes = Carbon::parse('first day of this month')->format('Y-m-d');
        $mes['fin'] = $final_mes = Carbon::parse('last day of this month')->format('Y-m-d');
        $movimientos = $producto->movimientos()
            ->whereYear('fecha','=',$anio_actual)
            ->whereMonth('fecha','=',$mes_actual)
            ->where('procesado','=',true)
            ->orderBy('fecha_procesado','asc')->get();
        if (Auth::user()->rol->nombre == 'Bodeguero')
        {
            return view('inventario.kardexBodega')
                ->with(['movimientos' => $movimientos])
                ->with(['producto' => $producto])
                ->with(['mes' => $mes]);
        }
        return view('inventario.kardex')
            ->with(['movimientos' => $movimientos])
            ->with(['producto' => $producto])
            ->with(['mes' => $mes]);
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
        $mes['inicio'] = $fecha_inicio;
        $mes['fin'] = $fecha_fin;
        $movimientos = $producto->movimientos()->whereBetween('fecha',[$fecha_inicio,$fecha_fin])->where('procesado','=',true)->orderBy('fecha_procesado','asc')->get();
//            ->where('procesado','=',true)->orderBy('fecha_procesado','asc')->get();
        foreach ($movimientos as $movimiento)
        {
            $movimiento->costo_total_existencia = $movimiento->cantidad_existencia * $movimiento->costo_unitario_existencia;
        }
        if (Auth::user()->rol->nombre == 'Bodeguero')
        {
            return view('inventario.kardexBodega')
                ->with(['movimientos' => $movimientos])
                ->with(['producto' => $producto])
                ->with(['mes' => $mes]);
        }
        return view('inventario.kardex')
            ->with(['movimientos' => $movimientos])
            ->with(['producto' => $producto])
            ->with(['mes' => $mes]);
    }
}
