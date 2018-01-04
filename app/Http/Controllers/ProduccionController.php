<?php

namespace App\Http\Controllers;

use App\Entrada;
use App\Formula;
use App\Movimiento;
use App\Produccion;
use App\Producto;
use App\Salida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduccionController extends Controller
{
    public function ProduccionLista()
    {
        $producciones = Produccion::all();
        return view('produccion.produccionLista')->with(['producciones' => $producciones]);
    }

    public function ProduccionVer($id)
    {
        $produccion = Produccion::find($id);
        $productos = Producto::all();
        $formulas = Formula::all();
        return view('produccion.produccionVer')
            ->with(['produccion' => $produccion])
            ->with(['formulas' => $formulas])
            ->with(['productos' => $productos]);
    }

    public function ProduccionNuevo()
    {
        $formulas = Formula::all();
        return view('produccion.produccionNuevo')->with(['formulas' => $formulas]);
    }

    public function ProduccionNuevoPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'formula_id' => 'required',
            'cantidad' => 'required',
            'fecha' => 'required',
        ]);

//        Crear la produccion
        $produccion = Produccion::create([
            'formula_id' => $request->input('formula_id'),
            'bodeguero_id' => Auth::user()->id,
            'cantidad' => $request->input('cantidad'),
            'fecha' => $request->input('fecha'),
            'detalle' => $request->input('detalle'),
        ]);

//        Se guardan en variales los componentes
        $formula = Formula::find($produccion->formula_id);

//        Se regristan las salidas
        foreach ($formula->componentes as $componente)
        {
//            Se carga el producto
            $producto = Producto::find($componente->producto_id);
//            Se calcula la cantidad y costo
            $cantidad = ($componente->porcentaje / 100) * $produccion->cantidad;
            $cuMovimiento = $producto->costo;
            $ctMovimiento = $cantidad * $cuMovimiento;
//            Calculo de existencias
            $cantidadExistencia = $producto->cantidadExistencia - $cantidad;
            $cuExistencia = $producto->costo;
            $ctExistencia = $cantidadExistencia * $cuExistencia;
//            Se crea el movimiento
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => 2,
                'fecha' => $produccion->fecha,
                'detalle' => 'Salida de producto por producción',
                'cantidadExistencia' => $cantidadExistencia,
                'costoUnitarioExistencia' => $cuExistencia,
                'costoTotalExistencia' => $ctExistencia,
                'procesado' => false,
            ]);
//            Se crea la salida
            $salida = Salida::create([
                'movimiento_id' => $movimiento->id,
                'produccion_id' => $produccion->id,
                'cantidad' => $cantidad,
                'precioUnitario' => 0.00,
                'ventaExenta' => 0.00,
                'ventaGravada' => 0.00,
                'costoUnitario' => $cuMovimiento,
                'costoTotal' => $ctMovimiento,
            ]);
//            Se actualiza la existencia del producto
            $producto->cantidadExistencia = $cantidadExistencia;
            $producto->costo = $cuExistencia;
            $producto->update();
        }
//        Se actualiza la cantidad del producto producido
//            Se carga el producto
        $producto = Producto::find($formula->producto_id);
//            Se calcula la cantidad y costo
        $cantidad = $produccion->cantidad;
        $cuMovimiento = $producto->costo;
        $ctMovimiento = $cantidad * $cuMovimiento;
//            Calculo de existencias
        $cantidadExistencia = $producto->cantidadExistencia + $cantidad;
        $cuExistencia = $producto->costo;
        $ctExistencia = $cantidadExistencia * $cuExistencia;
//            Se crea el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => 1,
            'fecha' => $produccion->fecha,
            'detalle' => 'Entrada de producto por producción',
            'cantidadExistencia' => $cantidadExistencia,
            'costoUnitarioExistencia' => $cuExistencia,
            'costoTotalExistencia' => $ctExistencia,
            'procesado' => false,
        ]);
//            Se crea la entrada
        $entrada = Entrada::create([
            'movimiento_id' => $movimiento->id,
            'produccion_id' => $produccion->id,
            'cantidad' => $cantidad,
            'costoUnitario' => $cuMovimiento,
            'costoTotal' => $ctMovimiento,
        ]);
        $producto->cantidadExistencia = $cantidadExistencia;
        $producto->update();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La producción fue agregada correctamente!');
        return redirect()->route('produccionVer', ['id' => $produccion->id]);
    }
}
