<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use App\Salida;
use Illuminate\Http\Request;

class OrdenPedidoController extends Controller
{
    public function OrdenPedidoLista()
    {
        $ordenesPedidos = OrdenPedido::all();
        return view('ordenPedido.ordenPedidoLista')->with(['ordenesPedidos' => $ordenesPedidos]);
    }

    public function OrdenPedidoVer($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoVer')->with(['ordenPedido' => $ordenPedido])->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }

    public function OrdenPedidoNueva()
    {
        $productosTodos = Producto::all();
        $productos = array();
        foreach ($productosTodos as $productoTodo) {
            if ($productoTodo->cantidadExistencia > 0) {
                array_push($productos, $productoTodo);
            }
        }
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoNueva')->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }

    public function OrdenPedidoNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'cliente_id' => 'required',
            'numero' => 'required',
            'fechaIngreso' => 'required',
            'productos_id.*' => 'required',
            'cantidades.*' => 'required | min:1',
            'precioUnitario.*' => 'required',
            'precioTotal.*' => 'required',
        ]);
//        dd($request);

//        Se crea la instancia de orden de pedido
        $ordenPedido = OrdenPedido::create([
            'cliente_id' => $request->input('cliente_id'),
            'municipio_id' => $request->input('municipio_id'),
            'direccion' => $request->input('direccion'),
            'numero' => $request->input('numero'),
            'fechaIngreso' => $request->input('fechaIngreso'),
            'fechaEntrega' => $request->input('fechaEntrega'),
            'condicionPago' => $request->input('condicionPago'),
            'vendedor_id' => \Auth::user()->id,
        ]);
        //        Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $ordenPedido->rutaArchivo = $archivo;
            $ordenPedido->save();
        }
//        Se guardan en variables los arrays recividos del request
        $productos_id = $request->input('productos_id');
        $cantidades = $request->input('cantidades');
        $preciosUnitarios = $request->input('preciosUnitarios');
        $ventasExentas = $request->input('ventasExentas');
        $ventasGravadas = $request->input('ventasGravadas');
//        Se toma tamaño del array
        $dimension = sizeof($productos_id);
        $ventaExenta = 0;
        $ventaGravada = 0;
        $ventaTotal = 0;
        for ($i = 0; $i < $dimension; $i++)
        {
//            Se carga el producto
            $producto = Producto::find($productos_id[$i]);
//            Calculo costo salida
            $cuMovimiento = $producto->costo;
            $ctMovimiento = $cantidades[$i] * $cuMovimiento;
//            Calculo de existencias
            $cantidadExistencia = $producto->cantidadExistencia - $cantidades[$i];
            $cuExistencia = $producto->costo;
            $ctExistencia = $cantidadExistencia * $cuExistencia;
//            Se crea el movimiento
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => 2,
                'fecha' => $ordenPedido->fechaIngreso,
                'detalle' => 'Salida de producto',
                'cantidadExistencia' => $cantidadExistencia,
                'costoUnitarioExistencia' => $cuExistencia,
                'costoTotalExistencia' => $ctExistencia,
                'procesado' => false,
            ]);
//            Se crea la salida
            $salida = Salida::create([
                'movimiento_id' => $movimiento->id,
                'orden_pedido_id' => $ordenPedido->id,
                'cantidad' => $cantidades[$i],
                'precioUnitario' => $preciosUnitarios[$i],
                'ventaExenta' => $ventasExentas[$i],
                'ventaGravada' => $ventasGravadas[$i],
                'costoUnitario' => $cuMovimiento,
                'costoTotal' => $ctMovimiento,
            ]);
//            Se actualiza la existencia del producto
            $producto->cantidadExistencia = $cantidadExistencia;
            $producto->costo = $cuExistencia;
            $producto->update();
            $ventaExenta += $ventasExentas[$i];
            $ventaGravada += $ventasGravadas[$i];
        }
        $ventaTotal = $ventaExenta + $ventaGravada;
        $ordenPedido->ventaTotal = $ventaTotal;
        $ordenPedido->save();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue agregada correctamente!');
        return redirect()->route('ordenPedidoVer', ['id' => $ordenPedido->id]);
    }
}
