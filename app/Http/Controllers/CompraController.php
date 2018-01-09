<?php

namespace App\Http\Controllers;

use App\Compra;
use App\Entrada;
use App\Movimiento;
use App\Producto;
use App\Proveedor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    public function CompraLista()
    {
        $compras = Compra::all();
        return view('compra.compraLista')->with(['compras' => $compras]);
    }

    public function CompraVer($id)
    {
        $compra = Compra::find($id);
        $productos = Producto::all();
        $proveedores = Proveedor::all();

        return view('compra.compraVer')->with(['compra' => $compra])->with(['productos' => $productos])->with(['proveedores' => $proveedores]);
    }

    public function CompraNueva()
    {
        $productos = Producto::all();
        $proveedores = Proveedor::all();
        return view('compra.compraNueva')->with(['productos' => $productos])->with(['proveedores' => $proveedores]);
    }

    public function CompraNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'fechaIngreso' => 'required',
            'proveedor_id' => 'required',
            'productos_id.*' => 'required',
            'numero' => 'required',
            'cantidades.*' => 'required | min:1',
            'costoUnitarios.*' => 'required',
            'costoTotales.*' => 'required',
        ]);

//        Se crea una instancia de compra
        $compra = Compra::create([
            'fechaIngreso' => $request->input('fechaIngreso'),
            'numero' => $request->input('numero'),
            'proveedor_id' => $request->input('proveedor_id'),
            'ingresado_id' => \Auth::user()->id,
            'detalle' => $request->input('detalle'),
            'revisado' => false,
        ]);
//        Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $compra->rutaArchivo = $archivo;
            $compra->update();
        }
//        Se guardan en variables los arrays recibidos del request
        $productos_id = $request->input('productos_id');
        $cantidades = $request->input('cantidades');
        $costoUnitarios = $request->input('costoUnitarios');
        $costoTotales = $request->input('costoTotales');
//        Se toma el tamaño de un array
        $dimension = sizeof($productos_id);
        $compraTotal = 0;
        for ($i = 0; $i < $dimension; $i++) {
//            Calculo de valor unitario de la entrada
            $cuMovimiento = $costoTotales[$i] / $cantidades[$i];
//            Se carga el producto
            $producto = Producto::find($productos_id[$i]);
//            Calculo de existencias
            $cantidadExistencia = $producto->cantidadExistencia + $cantidades[$i];
            if ($producto->costo == 0.00) {
                $cuExistencia = $cuMovimiento;
            } else {
                $cuExistencia = ($producto->costo + $cuMovimiento) / 2;
            }
            $ctExistencia = $cantidadExistencia * $cuExistencia;
//            Se crea el movimiento
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => 1,
                'fecha' => $compra->fechaIngreso,
                'detalle' => 'Ingreso de producto',
                'cantidadExistencia' => $cantidadExistencia,
                'costoUnitarioExistencia' => $cuExistencia,
                'costoTotalExistencia' => $ctExistencia,
                'fechaProcesado' => Carbon::now(),
                'procesado' => true,
            ]);
//            Se crea la entrada
            $entrada = Entrada::create([
                'movimiento_id' => $movimiento->id,
                'compra_id' => $compra->id,
                'cantidad' => $cantidades[$i],
                'costoUnitario' => $cuMovimiento,
                'costoTotal' => $costoTotales[$i],
            ]);
//            Se actualiza la existencia del producto
            $producto->cantidadExistencia = $cantidadExistencia;
            $producto->costo = $cuExistencia;
            $producto->update();
            $compraTotal += $costoTotales[$i];
        }
        $compra->compraTotal = $compraTotal;
        $compra->save();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La compra fue agregada correctamente!');
        return redirect()->route('compraVer', ['id' => $compra->id]);
    }
}
