<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\CondicionPago;
use App\ConversionUnidadMedida;
use App\EstadoOrdenPedido;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Precio;
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
        $productos_todos = Producto::all();
        $productos = [];
        foreach ($productos_todos as $producto) {
            if ($producto->precios->first()->precio != 0 ) {
                array_push($productos, $producto);
            }
        }
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $condiciones_pago = CondicionPago::all();
        return view('ordenPedido.ordenPedidoNueva')
            ->with(['condiciones_pago' => $condiciones_pago])
            ->with(['productos' => $productos_todos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['unidad_medidas' => $unidad_medidas]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * Estado: Revisado y funcionando
     * Fecha rev: 25/01/2018
     */
    public function OrdenPedidoNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'cliente_id' => 'required',
            'numero' => 'required',
            'fecha' => 'required',
            'condicion_pago_id' => 'required',
            'producto_id.*' => 'required',
            'cantidad.*' => 'required',
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
        // Se guardan en variables los arrays recibidos del request
        $productos_id = $request->input('producto_id');
        $presentaciones_id = $request->input('presentacion_id');
        $cantidades = $request->input('cantidad');
        $tipo_ventas = $request->input('tipo_venta');
        // Se toma tamaño del array
        $dimension = sizeof($productos_id);
        $venta_exenta = 0.00;
        $venta_gravada = 0.00;
        $venta_total = 0.00;
        for ($i = 0; $i < $dimension; $i++)
        {
            // Se carga el producto
            $producto = Producto::find($productos_id[$i]);
            $cantidad = $cantidades[$i];
            $precio_presentacion = Precio::find($presentaciones_id[$i]);
            $precio_unitario = $precio_presentacion->precio;
            $precio_total = $cantidad * $precio_unitario;
            $unidad_medida = $precio_presentacion->unidad_medida;
            // Si es la misma medida no se hacen conversiones; si no si se haran las conversiones de unidades
            if ($unidad_medida->id == $producto->unidad_medida_id)
            {
                // Calculo cantidad y costo de salida
                $cantidad_salida = $cantidad;
                $cantidad_salida_real = $cantidad;
                // Calculo costo salida
                $costo_unitario = $producto->costo;
                $costo_total = $cantidad_salida_real * $costo_unitario;
                // Calculo de cantidad y costos existencias
                $cantidad_existencia = $producto->cantidad_existencia - $cantidad_salida_real;
                $costo_unitario_existencia = $producto->costo;
                $costo_total_existencia = $cantidad_existencia * $costo_unitario_existencia;
            } else
            {
                // Calculo cantidad y costo de salida
                $factor = $precio_presentacion->factor;
                $cantidad_salida = $cantidad;
                $cantidad_salida_real = $cantidad * $factor;
                // Calculo costo de salida
                $costo_unitario = $producto->costo;
                $costo_total = $cantidad_salida_real * $costo_unitario;
                // Calculo de cantidad y costos existencias
                $cantidad_existencia = abs($producto->cantidad_existencia - $cantidad_salida_real);
                $costo_unitario_existencia = $producto->costo;
                $costo_total_existencia = $cantidad_existencia * $costo_unitario_existencia;
            }
            // Se determina que tipo de venta es
            if ($tipo_ventas[$i] == 0) {
                $venta_gravada_salida = $precio_total;
                $venta_exenta_salida = 0.00;
            } else
            {
                $venta_gravada_salida = 0.00;
                $venta_exenta_salida = $precio_total;
            }
            // Se crea la salida
            $salida = Salida::create([
                'orden_pedido_id' => $orden_pedido->id,
                'cantidad' => round($cantidad_salida,4),
                'unidad_medida_id' => $unidad_medida->id,
                'precio_id' => $precio_presentacion->id,
                'precio_unitario' => round($precio_unitario,4),
                'venta_exenta' => round($venta_exenta_salida,4),
                'venta_gravada' => round($venta_gravada_salida,4),
            ]);
            // Se crea el movimiento
            $tipo_movimiento = TipoMovimiento::whereCodigo('SALO')->first();
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'salida_id' => $salida->id,
                'fecha' => $orden_pedido->fecha,
                'detalle' => 'Salida de producto por orden de pedido n° '. $orden_pedido->numero,
                'cantidad' => round($cantidad_salida_real,4),
                'costo_unitario' => round($costo_unitario,4),
                'costo_total' => round($costo_total,4),
                'cantidad_existencia' => round($cantidad_existencia,4),
                'costo_unitario_existencia' => round($costo_unitario_existencia,4),
                'costo_total_existencia' => round($costo_total_existencia,4),
            ]);
            $venta_exenta = $venta_total + $venta_exenta_salida;
            $venta_gravada = $venta_gravada + $venta_gravada_salida;
        }
        $venta_total = $venta_exenta + $venta_gravada;
        $orden_pedido->ventas_gravadas = round($venta_gravada,4);
        $orden_pedido->ventas_exentas = round($venta_exenta,4);
        $orden_pedido->venta_total = round($venta_total,4);
        $orden_pedido->save();
        // Mensaje de éxito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue agregada correctamente!');
        return redirect()->route('ordenPedidoVer', ['id' => $orden_pedido->id]);
    }

    public function OrdenPedidoPDF($id)
    {
        $ordenPedido = OrdenPedido::find($id);
        $ventaTotal = number_format($ordenPedido->venta_total,2);
        $ordenPedido->ventaTotalLetras = NumeroALetras::convertir($ventaTotal,'dolares','centavos');
        $nombreArchivo = "orden-pedido-" . $ordenPedido->numero . "-" . Carbon::now()->format('d-m-Y');
        $pdf = PDF::loadView('pdf.ordenPedidoPDF',compact('ordenPedido'));
        return $pdf->stream($nombreArchivo);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Estado: Revisada y funcionando
     * Fecha rev: 25/01/2018
     */
    public function OrdenPedidoBodegaPost($id)
    {
        $orden_pedido = OrdenPedido::find($id);
        $salidas = $orden_pedido->salidas;
        // Comprobacion si existencias alcanzan para procesar orden
        foreach ($salidas as $salida)
        {
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidad_existencia = $producto->cantidad_existencia;
            $cantidad_existencia = round($cantidad_existencia,4);
            $cantidad_salida = round($salida->movimiento->cantidad,4);
            if ($cantidad_existencia < $cantidad_salida)
            {
                // Mensaje de exito al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-check');
                session()->flash('mensaje.titulo', 'Upssss!');
                session()->flash('mensaje.contenido', 'No hay suficiente producto para procesar la orden!');
                return redirect()->route('ordenPedidoVerBodega',['id' => $orden_pedido->id]);
            }
        }
        foreach ($salidas as $salida)
        {
            $producto = Producto::find($salida->movimiento->producto_id);
            $cantidad_existencia = $producto->cantidad_existencia - $salida->movimiento->cantidad;
            $costo_total = $salida->movimiento->cantidad * $producto->costo;
            $costo_total_existencia = $cantidad_existencia * $producto->costo;
            // Actualizar movimiento
            $salida->movimiento->costo_unitario = round($producto->costo,4);
            $salida->movimiento->costo_total = round($costo_total,4);
            $salida->movimiento->cantidad_existencia = round($cantidad_existencia,4);
            $salida->movimiento->costo_unitario_existencia = round($producto->costo,4);
            $salida->movimiento->costo_total_existencia = round($costo_total_existencia,4);
            $salida->movimiento->fecha_procesado = Carbon::now();
            $salida->movimiento->procesado = true;
            $salida->movimiento->save();
            $producto->cantidad_existencia = $salida->movimiento->cantidad_existencia;
            $producto->save();
        }
        $estado_orden = EstadoOrdenPedido::whereCodigo('PR')->first();
        $orden_pedido->estado_id = $estado_orden->id;
        $orden_pedido->update();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue procesada correctamente!');
        return redirect()->route('ordenPedidoListaBodega');
    }

    public function OrdenPedidoEliminar($id)
    {
        $orden_pedido = OrdenPedido::find($id);
        $salidas = $orden_pedido->salidas;
        foreach ($salidas as $salida)
        {
            $salida->movimiento->delete();
            $salida->delete();
        }
        $orden_pedido->delete();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue eliminada correctamente!');
        return redirect()->route('ordenPedidoLista');
    }
}
