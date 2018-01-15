<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\ConversionUnidadMedida;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use App\Salida;
use App\UnidadMedida;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumeroALetras;

class OrdenPedidoController extends Controller
{
    public function OrdenPedidoLista()
    {
        $ordenesPedidos = OrdenPedido::all();
        return view('ordenPedido.ordenPedidoLista')->with(['ordenesPedidos' => $ordenesPedidos]);
    }

    public function OrdenPedidoListaBodega()
    {
        $ordenesPedidos = OrdenPedido::whereEstadoId(1)->get();
        return view('ordenPedido.ordenPedidoListaBodega')->with(['ordenesPedidos' => $ordenesPedidos]);
    }

    public function OrdenPedidoVer($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoVer')->with(['ordenPedido' => $ordenPedido])->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }

    public function OrdenPedidoVerBodega($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoVerBodega')->with(['ordenPedido' => $ordenPedido])->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }

    public function OrdenPedidoNueva()
    {
        $unidadMedidas = UnidadMedida::all();
        $productosTodos = Producto::all()->where('precio','>',0);
        $productos = array();
        foreach ($productosTodos as $productoTodo) {
            if ($productoTodo->cantidadExistencia > 0 && $productoTodo->precio != 0) {
                array_push($productos, $productoTodo);
            }
        }
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoNueva')
            ->with(['productos' => $productosTodos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['unidadMedidas' => $unidadMedidas]);
    }

    public function OrdenPedidoNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'cliente_id' => 'required',
            'numero' => 'required',
            'fechaIngreso' => 'required',
            'productos_id.*' => 'required',
            'cantidades.*' => 'required',
            'municipio_id' => 'required',
        ]);

        // Se crea la instancia de orden de pedido
        $ordenPedido = OrdenPedido::create([
            'cliente_id' => $request->input('cliente_id'),
            'municipio_id' => $request->input('municipio_id'),
            'direccion' => $request->input('direccion'),
            'numero' => $request->input('numero'),
            'fechaIngreso' => $request->input('fechaIngreso'),
            'fechaEntrega' => $request->input('fechaEntrega'),
            'condicionPago' => $request->input('condicionPago'),
            'vendedor_id' => \Auth::user()->id,
            'estado_id' => 1,
        ]);
        //        Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $ordenPedido->rutaArchivo = $archivo;
            $ordenPedido->save();
        }
        // Se guardan en variables los arrays recividos del request
        $productos_id = $request->input('productos_id');
        $unidadesMedidas = $request->input('unidad_medida_id');
        $cantidades = $request->input('cantidades');
        $tipoVentas = $request->input('tipoVenta');
        //$preciosUnitarios = $request->input('preciosUnitarios');
        //$ventasExentas = $request->input('ventasExentas');
        //$ventasGravadas = $request->input('ventasGravadas');
        // Se toma tamaño del array
        $dimension = sizeof($productos_id);
        $ventaExenta = 0.00;
        $ventaGravada = 0.00;
        $ventaTotal = 0.00;
        for ($i = 0; $i < $dimension; $i++)
        {
            // Se carga el producto
            $producto = Producto::find($productos_id[$i]);
            $unidadMedida = $unidadesMedidas[$i];
            // Si es la misma medida no se hacen conversiones; si no si se haran las conversiones de unidades
            if ($unidadMedida == $producto->unidad_medida_id)
            {
                // Calculo cantidad y costo de salida
                $cantidadSalida = $cantidades[$i];
                $cantidadSalidaOP = $cantidades[$i];
                // Calculo costo salida
                $cuSalida = $producto->costo;
                $ctSalida = $cantidadSalida * $cuSalida;
                // Calculo de cantidad y costos existencias
                $cantidadExistencia = $producto->cantidadExistencia - $cantidadSalida;
                $cuExistencia = $producto->costo;
                $ctExistencia = $cantidadExistencia * $cuExistencia;
                $precioUnitarioSalidaOP = $producto->precio;
            } elseif ($producto->unidadMedida->conversiones->where('unidadMedidaDestino_id',$unidadMedida)->first())
            {
                // Se busca el factor de conversion
//                dd($producto->unidad_medida_id);
                $factor = ConversionUnidadMedida::where([
                    ['unidadMedidaOrigen_id','=', $producto->unidad_medida_id],
                    ['unidadMedidaDestino_id', '=', $unidadMedida],
                ])->first();
                // Se guarda la cantidad de salida
                $cantidadSalida = $cantidades[$i] / $factor->factor;
                $cantidadSalidaOP = $cantidades[$i];
                // Calculo costo salida
                $cuSalida = $producto->costo;
                $ctSalida = $cantidadSalida * $cuSalida;
                // Calculo de existencias
                $cantidadExistencia = $producto->cantidadExistencia - $cantidadSalida;
                $cuExistencia = $producto->costo;
                $ctExistencia = $cantidadExistencia * $cuExistencia;
                $precioUnitarioSalidaOP = $producto->precio / $factor->factor;
            } else
            {
                dd('Error al procesar');
            }
            // Se calculan los precio unitarios y las ventas
            $precioUnitarioSalida = $producto->precio;
            if ($tipoVentas[$i] == 0) {
                $ventaGravadaSalida = $precioUnitarioSalida * $cantidadSalida;
                $ventaExentaSalida = 0.00;
            } else
            {
                $ventaExentaSalida = $precioUnitarioSalida * $cantidadSalida;
                $ventaGravadaSalida = 0.00;
            }
            // Se crea el movimiento
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
            // Se crea la salida
            $salida = Salida::create([
                'movimiento_id' => $movimiento->id,
                'orden_pedido_id' => $ordenPedido->id,
                'cantidad' => $cantidadSalida,
                'cantidadOP' => $cantidadSalidaOP,
                'unidad_medida_id' => $unidadMedida,
                'precioUnitario' => $precioUnitarioSalida,
                'precioUnitarioOP' => $precioUnitarioSalidaOP,
                'ventaExenta' => $ventaExentaSalida,
                'ventaGravada' => $ventaGravadaSalida,
                'costoUnitario' => $cuSalida,
                'costoTotal' => $ctSalida,
            ]);
            // Se actualiza la existencia del producto
            //$producto->cantidadExistencia = $cantidadExistencia;
            //$producto->costo = $cuExistencia;
            //$producto->update();
            $ventaExenta = $ventaTotal + $ventaExentaSalida;
            $ventaGravada = $ventaGravada + $ventaGravadaSalida;
        }
        $ventaTotal = $ventaExenta + $ventaGravada;
        $ordenPedido->ventasGravadas = (float) $ventaGravada;
        $ordenPedido->ventasExentas = (float) $ventaExenta;
        $ordenPedido->ventaTotal = (float) $ventaTotal;
        $ordenPedido->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue agregada correctamente!');
        return redirect()->route('ordenPedidoVer', ['id' => $ordenPedido->id]);
    }

    public function OrdenPedidoPDF($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $ventaTotal = number_format($ordenPedido->ventaTotal,2);
        $ordenPedido->ventaTotalLetras = NumeroALetras::convertir($ventaTotal,'dolares','centavos');
        $nombreArchivo = "orden-pedido-" . $ordenPedido->numero . "-" . Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('pdf.ordenPedidoPDF',compact('ordenPedido'));
        return $pdf->stream($nombreArchivo);
    }

    public function OrdenPedidoBodegaPost($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $salidas = $ordenPedido->salidas;
        foreach ($salidas as $salida)
        {
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidadExistencia = $producto->cantidadExistencia;
            if ($cantidadExistencia < $salida->cantidad)
            {
                // Mensaje de exito al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-check');
                session()->flash('mensaje.contenido', 'No hay suficiente producto para procesar la orden!');
                return redirect()->route('ordenPedidoVerBodega',['id' => $ordenPedido->id]);
            }
        }
        foreach ($salidas as $salida)
        {
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidadExistencia = $producto->cantidadExistencia - $salida->cantidad;
            $salida->movimiento->cantidadExistencia = $cantidadExistencia;
            $salida->movimiento->fechaProcesado = Carbon::now();
            $salida->movimiento->procesado = true;
            $salida->movimiento->save();
            $producto->cantidadExistencia = $cantidadExistencia;
            $producto->save();
        }
        $ordenPedido->estado_id = 2;
        $ordenPedido->update();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue procesada correctamente!');
        return redirect()->route('ordenPedidoListaBodega');
    }
}
