<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\CondicionPago;
use App\Movimiento;
use App\OrdenPedido;
use App\Precio;
use App\Producto;
use App\Salida;
use App\TipoDocumento;
use App\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrdenMuestraController extends Controller
{
    public function OrdenMuestraLista(Request $request)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        $ordenesPedidos = OrdenPedido::where('tipo_orden_pedido_id','=',2)->whereBetween('fecha', [$fecha_inicio->format('Y-m-d'), $fecha_fin->format('Y-m-d')])->get();
        if (\Auth::user()->rol->nombre == 'Vendedor') {
            $ordenesPedidos = $ordenesPedidos->where('vendedor_id', '=', \Auth::user()->id); //whereVendedorId()->get();
        }
        return view('ordenMuestra.ordenMuestraLista')
            ->with(['ordenesPedidos' => $ordenesPedidos])
            ->with(['extra' => $extra]);
    }

    public function OrdenMuestraNueva()
    {
        $unidad_medidas = UnidadMedida::all();
        $productos_todos = Producto::where('codigo', 'like', 'PT%')
            ->orWhere('codigo', 'like', 'RV%')
            ->orWhere('codigo', 'like', 'MR%')
            ->orWhere('codigo', 'like', 'PM%')->get();
        $clientes = Cliente::all();
        $condiciones_pago = CondicionPago::all();
        $tipoDocumentos = TipoDocumento::all();
        return view('ordenMuestra.ordenMuestraNueva')
            ->with(['condiciones_pago' => $condiciones_pago])
            ->with(['productos' => $productos_todos])
            ->with(['clientes' => $clientes])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['tipoDocumentos' => $tipoDocumentos]);
    }

    public function OrdenMuestrasNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'cliente_id' => 'required',
            'fecha' => 'required',
            'producto_id.*' => 'required',
            'cantidad.*' => 'required',
            'numero' => 'numeric||nullable',
        ]);

        // Creando instancia de orden de pedido; estado_orden -> 1:Sin despachar
        $numero = ($request->input('numero') == null) ? 0 : $request->input('numero');

        $orden_pedido = OrdenPedido::create([
            'cliente_id' => $request->input('cliente_id'),
            'numero' => $numero,
            'detalle' => 'Orden de muestra ingresada por' . \Auth::user()->nombre,
            'fecha' => $request->input('fecha'),
            'fecha_entrega' => $request->input('fecha_entrega'),
            'condicion_pago_id' => 1, // 1 = Contado
            'vendedor_id' => \Auth::user()->id,
            'estado_id' => 1,
            'tipo_documento_id' => 1, // 1 = Factura consumidor final
            'tipo_orden_pedido_id' => 2, // 2 = Orden de muestra
        ]);
        //        Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $orden_pedido->ruta_archivo = $archivo;
            $orden_pedido->save();
        }
        if ($request->input('numero') == null) {
            $orden_pedido->numero = $orden_pedido->id;
            $orden_pedido->save();
        }
        // Se guardan en variables los arrays recibidos del request
        $productos_id = $request->input('producto_id');
        $presentaciones_id = $request->input('presentacion_id');
        $cantidades = $request->input('cantidad');
        // Se toma tamaño del array
        $dimension = sizeof($productos_id);
        $venta_exenta = 0.00;
        $venta_gravada = 0.00;
        $venta_total = 0.00;
        for ($i = 0; $i < $dimension; $i++) {
            // Se carga el producto
            $producto = Producto::find($productos_id[$i]);
            $cantidad = $cantidades[$i];
            $precio_presentacion = Precio::find($presentaciones_id[$i]);
            // Cantidad orden, cantidad real
            $cantidad_salida = $cantidad;
            $cantidad_salida_real = $cantidad_salida * $precio_presentacion->factor;
            // Costo
            $costo_unitario = $producto->costo;
            $costo_total = $cantidad_salida_real * $costo_unitario;
            // Precio
            $precio_unitario = $precio_presentacion->precio;
            $precio_total = $cantidad * $precio_unitario;
            // Unidad de medida y presentacion
            $unidad_medida = $precio_presentacion->unidad_medida;
            $descripcion_factura = $precio_presentacion->nombre_factura;
            // Cantidad de existencia se deja en 0
            $cantidad_existencia = 0;
            $costo_unitario_existencia = 0;
            $costo_total_existencia = 0;
            // Se determina que tipo de venta es
            $venta_gravada_salida = $precio_total;
            $venta_exenta_salida = 0.00;
            // Se crea la salida
            $salida = Salida::create([
                'orden_pedido_id' => $orden_pedido->id,
                'cantidad' => round($cantidad_salida, 4),
                'unidad_medida_id' => $unidad_medida->id,
                'precio_id' => $precio_presentacion->id,
                'descripcion_presentacion' => $descripcion_factura,
                'precio_unitario' => round($precio_unitario, 4),
                'venta_exenta' => round($venta_exenta_salida, 4),
                'venta_gravada' => round($venta_gravada_salida, 4),
            ]);
            // Se crea el movimiento; tipo_movimiento -> 4: Salida por orden
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => 4,
                'salida_id' => $salida->id,
                'fecha' => $orden_pedido->fecha,
                'detalle' => 'Salida de producto por orden de pedido n° ' . $orden_pedido->numero,
                'cantidad' => round($cantidad_salida_real, 4),
                'costo_unitario' => round($costo_unitario, 4),
                'costo_total' => round($costo_total, 4),
                'cantidad_existencia' => round($cantidad_existencia, 4),
                'costo_unitario_existencia' => round($costo_unitario_existencia, 4),
                'costo_total_existencia' => round($costo_total_existencia, 4),
            ]);
            $venta_exenta = $venta_total + $venta_exenta_salida;
            $venta_gravada = $venta_gravada + $venta_gravada_salida;
        }
        $venta_total = $venta_exenta + $venta_gravada;
        $orden_pedido->ventas_gravadas = round($venta_gravada, 4);
        $orden_pedido->ventas_exentas = round($venta_exenta, 4);
        $orden_pedido->venta_total = round($venta_total, 4);
        $orden_pedido->save();
        // Mensaje de éxito al guardar
        dd('Exito');
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue agregada correctamente!');
        return redirect()->route('ordenPedidoVer', ['id' => $orden_pedido->id]);
    }
}
