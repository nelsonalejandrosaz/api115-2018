<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\CondicionPago;
use App\ConversionUnidadMedida;
use App\EstadoOrdenPedido;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use App\Salida;
use App\TipoMovimiento;
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
        $orden_pedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoVer')->with(['ordenPedido' => $orden_pedido])->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
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
        $unidad_medidas = UnidadMedida::all();
        $productosTodos = Producto::all()->where('precio','>',0);
        $productos = array();
        foreach ($productosTodos as $productoTodo) {
            if ($productoTodo->cantidadExistencia > 0 && $productoTodo->precio != 0) {
                array_push($productos, $productoTodo);
            }
        }
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $condiciones_pago = CondicionPago::all();
        return view('ordenPedido.ordenPedidoNueva')
            ->with(['condiciones_pago' => $condiciones_pago])
            ->with(['productos' => $productosTodos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['unidad_medidas' => $unidad_medidas]);
    }

    public function OrdenPedidoNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'cliente_id' => 'required',
            'numero' => 'required',
            'fecha' => 'required',
            'productos_id.*' => 'required',
            'cantidades.*' => 'required',
        ]);

        // Variables
        $estado_orden = EstadoOrdenPedido::whereCodigo('SP')->first();

        // Se crea la instancia de orden de pedido
        $orden_pedido = OrdenPedido::create([
            'cliente_id' => $request->input('cliente_id'),
            'numero' => $request->input('numero'),
            'detalle' => 'Orden de pedido ingresada por' . \Auth::user()->nombre,
            'fecha' => $request->input('fecha'),
            'fecha_entrega' => $request->input('fecha_entrega'),
            'condicion_pago_id' => $request->input('condicion_pago_id'),
            'vendedor_id' => \Auth::user()->id,
            'estado_id' => $estado_orden->id,
        ]);
        //        Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $orden_pedido->ruta_archivo = $archivo;
            $orden_pedido->save();
        }
        // Se guardan en variables los arrays recividos del request
        $productos_id = $request->input('productos_id');
        $unidades_medidas = $request->input('unidad_medida_id');
        $cantidades = $request->input('cantidades');
        $tipo_ventas = $request->input('tipoVenta');
        // Se toma tamaño del array
        $dimension = sizeof($productos_id);
        $venta_exenta = 0.00;
        $venta_gravada = 0.00;
        $venta_total = 0.00;
        for ($i = 0; $i < $dimension; $i++)
        {
            // Se carga el producto
            $producto = Producto::find($productos_id[$i]);
            $unidad_medida = $unidades_medidas[$i];
            // Si es la misma medida no se hacen conversiones; si no si se haran las conversiones de unidades
            if ($unidad_medida == $producto->unidad_medida_id)
            {
                // Calculo cantidad y costo de salida
                $cantidad_salida = $cantidades[$i];
                $cantidad_salida_ums = $cantidades[$i];
                // Calculo costo salida
                $cu_salida = $producto->costo;
                $ct_salida = $cantidad_salida * $cu_salida;
                // Calculo de cantidad y costos existencias
                $cantidad_existencia = $producto->cantidad_existencia - $cantidad_salida;
                $cu_existencia = $producto->costo;
                $ct_existencia = $cantidad_existencia * $cu_existencia;
                $precio_unitario_salida_ums = $producto->precio;
            } elseif ($producto->unidad_medida->conversiones->where('unidad_medida_destino_id',$unidad_medida)->first())
            {
                // Se busca el factor de conversion
                $factor = ConversionUnidadMedida::where([
                    ['unidad_medida_origen_id','=', $producto->unidad_medida_id],
                    ['unidad_medida_destino_id', '=', $unidad_medida],
                ])->first();
                // Se guarda la cantidad de salida
                $cantidad_salida = $cantidades[$i] / $factor->factor;
                $cantidad_salida_ums = $cantidades[$i];
                // Calculo costo salida
                $cu_salida = $producto->costo;
                $ct_salida = $cantidad_salida * $cu_salida;
                // Calculo de existencias
                $cantidad_existencia = $producto->cantidad_existencia - $cantidad_salida;
                $cu_existencia = $producto->costo;
                $ct_existencia = $cantidad_existencia * $cu_existencia;
                $precio_unitario_salida_ums = $producto->precio / $factor->factor;
            } else
            {
                dd('Error al procesar');
            }
            // Se calculan los precio unitarios y las ventas
            $precio_unitario_salida = $producto->precio;
            if ($tipo_ventas[$i] == 0) {
                $venta_gravada_salida = $precio_unitario_salida * $cantidad_salida;
                $venta_exenta_salida = 0.00;
            } else
            {
                $venta_exenta_salida = $precio_unitario_salida * $cantidad_salida;
                $venta_gravada_salida = 0.00;
            }

            // Se crea la salida
            $salida = Salida::create([
                'orden_pedido_id' => $orden_pedido->id,
                'cantidad' => $cantidad_salida,
                'cantidad_ums' => $cantidad_salida_ums,
                'unidad_medida_id' => $unidad_medida,
                'precio_unitario' => $precio_unitario_salida,
                'precio_unitario_ums' => $precio_unitario_salida_ums,
                'venta_exenta' => $venta_exenta_salida,
                'venta_gravada' => $venta_gravada_salida,
                'costo_unitario' => $cu_salida,
            ]);

            // Se crea el movimiento
            $tipo_movimiento = TipoMovimiento::whereCodigo('SALO')->first();
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'salida_id' => $salida->id,
                'fecha' => $orden_pedido->fecha,
                'detalle' => 'Salida de producto por orden de pedido n° '. $orden_pedido->numero ,
                'cantidad_existencia' => $cantidad_existencia,
                'costo_unitario_existencia' => $cu_existencia,
                'costo_total_existencia' => $ct_existencia,
                'procesado' => false,
            ]);

            $venta_exenta = $venta_total + $venta_exenta_salida;
            $venta_gravada = $venta_gravada + $venta_gravada_salida;
        }
        $venta_total = $venta_exenta + $venta_gravada;
        $orden_pedido->ventas_gravadas = (float) $venta_gravada;
        $orden_pedido->ventas_exentas = (float) $venta_exenta;
        $orden_pedido->venta_total = (float) $venta_total;
        $orden_pedido->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue agregada correctamente!');
        return redirect()->route('ordenPedidoVer', ['id' => $orden_pedido->id]);
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
        $orden_pedido = OrdenPedido::find($id);
        $salidas = $orden_pedido->salidas;
        foreach ($salidas as $salida)
        {
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidad_existencia = $producto->cantidad_existencia;
            if ($cantidad_existencia < $salida->cantidad)
            {
                // Mensaje de exito al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-check');
                session()->flash('mensaje.contenido', 'No hay suficiente producto para procesar la orden!');
                return redirect()->route('ordenPedidoVerBodega',['id' => $orden_pedido->id]);
            }
        }
        foreach ($salidas as $salida)
        {
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidad_existencia = $producto->cantidad_existencia - $salida->cantidad;
            $salida->movimiento->cantidad_existencia = $cantidad_existencia;
            $salida->movimiento->fecha_procesado = Carbon::now();
            $salida->movimiento->procesado = true;
            $salida->movimiento->save();
            $producto->cantidad_existencia = $cantidad_existencia;
            $producto->save();
        }
        $orden_pedido->estado_id = 2;
        $orden_pedido->update();
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue procesada correctamente!');
        return redirect()->route('ordenPedidoListaBodega');
    }
}
