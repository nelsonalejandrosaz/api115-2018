<?php

namespace App\Http\Controllers;

use App\Compra;
use App\Movimiento;
use App\Producto;
use App\Proveedor;
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
            'cantidades.*' => 'required',
            'valoresTotales.*' => 'required',
        ]);

//        Se crea una instancia de compra
        $compra = Compra::create([
            'fechaIngreso' => $request->input('fechaIngreso'),
            'numero' => $request->input('numero'),
            'proveedor_id' => $request->input('proveedor_id'),
            'ingresadoPor_id' => \Auth::user()->id,
            'detalle' => $request->input('detalle'),
            'revisado' => false,
        ]);
//        Se guarda el archivo subido
        if ($request->hasFile('archivo'))
        {
            $archivo = $request->file('archivo')->store('public');
            $compra->rutaArchivo = $archivo;
            $compra->update();
        }
//        Se guardan en variables los arrays recividos del request
        $productos_id = $request->input('productos_id');
        $cantidades = $request->input('cantidades');
        $valoresTotales = $request->input('valoresTotales');
//        Se toma el tamaño de un array
        $dimension = sizeof($productos_id);
        for ($i=0; $i < $dimension; $i++)
        {
//            Calculo de valor unitario de la entrada
            $vuMovimiento = $valoresTotales[$i] / $cantidades[$i];
//            Se carga el producto
            $producto = Producto::find($productos_id[$i]);
//            Calculo de existencias
            $cantidadExistencia = $producto->cantidad + $cantidades[$i];
            if ($producto->precioCompra == 0.00)
            {
                $vuExistencia = $vuMovimiento;
            } else
            {
                $vuExistencia = ($producto->precioCompra + $vuMovimiento) / 2;
            }
            $vtExistencia = $cantidadExistencia * $vuExistencia;
//            Se crea el movimiento
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'compra_id' => $compra->id,
                'tipo_movimiento_id' => 1,
                'fecha' => $compra->fechaIngreso,
                'detalle' => 'Ingreso de producto',
                'cantidadMovimiento' => $cantidades[$i],
                'valorUnitarioMovimiento' => $vuMovimiento,
                'valorTotalMovimiento' => $valoresTotales[$i],
                'cantidadExistencia' => $cantidadExistencia,
                'valorUnitarioExistencia' => $vuExistencia,
                'valorTotalExistencia' => $vtExistencia,
                'procesado' => false,
            ]);
//            Se actualiza la existencia del producto
            $producto->cantidad = $cantidadExistencia;
            $producto->precioCompra = $vuExistencia;
            $producto->update();
        }
        //        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La compra fue agregada correctamente!');
        return redirect()->route('compraVer',['id' => $compra->id]);
    }
}
