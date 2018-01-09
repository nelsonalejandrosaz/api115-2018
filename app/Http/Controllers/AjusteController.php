<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Movimiento;
use App\Producto;
use App\TipoAjuste;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AjusteController extends Controller
{
    public function AjusteLista()
    {
        $ajustes = Ajuste::all();
        return view('ajuste.ajusteLista')->with(['ajustes' => $ajustes]);
    }

    public function AjusteNuevo()
    {
        $productos = Producto::all();
        $tipoAjustes = TipoAjuste::all();
        return view('ajuste.ajusteNuevo')->with(['productos' => $productos])->with(['tipoAjustes' => $tipoAjustes]);
    }

    public function AjusteNuevoPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'fechaIngreso' => 'required',
            'producto_id' => 'required',
            'tipo_ajuste_id' => 'required',
            'cantidadAjuste' => 'required',
            'valorUnitarioAjuste' => 'required',
        ]);
        // Fin Validacion

//        Variables
        $cantidadAjuste = $request->input('cantidadAjuste');
        $valorUnitarioAjuste = $request->input('valorUnitarioAjuste');
        // Se carga el producto a ajustar
        $producto = Producto::find($request->input('producto_id'));
        // Se crea el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => 3,
            'fecha' => $request->input('fechaIngreso'),
            'detalle' => 'Ajuste de entrada de producto realizado por ' . Auth::user()->nombre,
            'cantidadExistencia' => $cantidadAjuste,
            'costoUnitarioExistencia' => $valorUnitarioAjuste,
            'costoTotalExistencia' => $cantidadAjuste * $valorUnitarioAjuste,
            'fechaProcesado' => Carbon::now(),
            'procesado' => true,
        ]);
        // Se crea el ajuste de entrada
        $ajuste = Ajuste::create([
            'movimiento_id' => $movimiento->id,
            'tipo_ajuste_id' => $request->input('tipo_ajuste_id'),
            'detalle' => $request->input('detalle'),
            'fechaIngreso' => $request->input('fechaIngreso'),
            'cantidadAjuste' => $cantidadAjuste,
            'valorUnitarioAjuste' => $valorUnitarioAjuste,
            'realizado_id' => Auth::user()->id,
            'cantidadAnterior' => $producto->cantidadExistencia,
            'valorUnitarioAnterior' => $producto->precio,
        ]);
        // Los nuevos valores de inventario despues del ajuste
//        $cantidadExistencia = $ajuste->cantidad;
//        $valorUnitarioExistencia = $ajuste->precioCompra;
//        $valorTotalExistencia = $valorUnitarioExistencia * $cantidadExistencia;
        // Se completa el movimiento
//        $movimiento->cantidadExistencia = $cantidadExistencia;
//        $movimiento->valorUnitarioExistencia = $valorUnitarioExistencia;
//        $movimiento->valorTotalExistencia = $valorTotalExistencia;
//        $movimiento->save();
        // Se actualiza la cantidad de producto despues de la entrada
        $producto->cantidadExistencia = $movimiento->cantidadExistencia;
        $producto->costo = $movimiento->costoUnitarioExistencia;
        $producto->save();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El ajuste fue realizado correctamente!');
        return redirect()->route('ajusteLista');
    }

    public function AjusteVer($id)
    {
        $ajuste = Ajuste::find($id);
        $productos = Producto::all();
        $tipoAjustes = TipoAjuste::all();
        return view('ajuste.ajusteVer')->with(['productos' => $productos])->with(['tipoAjustes' => $tipoAjustes])->with(['ajuste' => $ajuste]);

    }
}
