<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\CondicionPago;
use App\Configuracion;
use App\ConversionUnidadMedida;
use App\EstadoOrdenPedido;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Precio;
use App\Producto;
use App\Salida;
use App\TipoDocumento;
use App\TipoMovimiento;
use App\UnidadMedida;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumeroALetras;

class OrdenPedidoController extends Controller
{
    public function OrdenPedidoLista(Request $request)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        $ordenesPedidos = OrdenPedido::whereBetween('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')])->get();
        if (\Auth::user()->rol->nombre == 'Vendedor')
        {
            $ordenesPedidos = $ordenesPedidos->where('vendedor_id','=',\Auth::user()->id); //whereVendedorId()->get();
        }
        return view('ordenPedido.ordenPedidoLista')
            ->with(['ordenesPedidos' => $ordenesPedidos])
            ->with(['extra' => $extra]);
    }

    public function OrdenPedidoListaBodega(Request $request)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        $estado_orden = EstadoOrdenPedido::whereCodigo('SP')->first();
        $ordenesPedidos = OrdenPedido::whereEstadoId($estado_orden->id)->get();
        return view('ordenPedido.ordenPedidoListaBodega')
            ->with(['ordenesPedidos' => $ordenesPedidos])
            ->with(['extra' => $extra]);
    }

    public function OrdenPedidoListaProcesadoBodega(Request $request)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        $ordenesPedidos = OrdenPedido::whereBetween('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')])->get();
        $ordenesPedidos = $ordenesPedidos->where('estado_id','>',1);
        return view('ordenPedido.ordenPedidoListaBodega')
            ->with(['ordenesPedidos' => $ordenesPedidos])
            ->with(['extra' => $extra]);
    }

    public function OrdenPedidoVer($id)
    {
        $orden_pedido = OrdenPedido::find($id);
        if ($orden_pedido->tipo_documento->codigo == 'FAC')
        {
            $iva = Configuracion::find(1)->iva;
            foreach ($orden_pedido->salidas as $salida)
            {
                $salida->precio_unitario = $salida->precio_unitario * $iva;
                $salida->venta_gravada = $salida->venta_gravada * $iva;
                $salida->venta_exenta = $salida->venta_exenta * $iva;
            }
            $orden_pedido->venta_total_con_iva = $orden_pedido->venta_total * $iva;
        }
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoVer')->with(['ordenPedido' => $orden_pedido])->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }

    public function OrdenPedidoVerBodega($id)
    {
        $ordenPedido = OrdenPedido::findOrFail($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoVerBodega')->with(['ordenPedido' => $ordenPedido])->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }

    public function OrdenPedidoNueva()
    {
        $unidad_medidas = UnidadMedida::all();
        $productos_todos = Producto::where('codigo','like','PT%')
            ->orWhere('codigo','like','RV%')
            ->orWhere('codigo','like','MR%')
            ->orWhere('codigo','like','BO%')->get();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $condiciones_pago = CondicionPago::all();
        $tipoDocumentos = TipoDocumento::all();
        return view('ordenPedido.ordenPedidoNueva')
            ->with(['condiciones_pago' => $condiciones_pago])
            ->with(['productos' => $productos_todos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['tipoDocumentos' => $tipoDocumentos]);
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
            'fecha' => 'required',
            'condicion_pago_id' => 'required',
            'producto_id.*' => 'required',
            'cantidad.*' => 'required',
            'numero' => 'numeric||nullable',
            'tipo_documento_id' => 'required',
        ]);

        // Variables
        $estado_orden = EstadoOrdenPedido::whereCodigo('SP')->first();
        $numero = ($request->input('numero') == null) ? 0 : $request->input('numero');

        // Se crea la instancia de orden de pedido
        $orden_pedido = OrdenPedido::create([
            'cliente_id' => $request->input('cliente_id'),
            'numero' => $numero,
            'detalle' => 'Orden de pedido ingresada por' . \Auth::user()->nombre,
            'fecha' => $request->input('fecha'),
            'fecha_entrega' => $request->input('fecha_entrega'),
            'condicion_pago_id' => $request->input('condicion_pago_id'),
            'vendedor_id' => \Auth::user()->id,
            'estado_id' => $estado_orden->id,
            'tipo_documento_id' => $request->input('tipo_documento_id'),
        ]);
        //        Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $orden_pedido->ruta_archivo = $archivo;
            $orden_pedido->save();
        }
        if ($request->input('numero') == null)
        {
            $orden_pedido->numero = $orden_pedido->id;
            $orden_pedido->save();
        }
        // Se guardan en variables los arrays recibidos del request
        $productos_id = $request->input('producto_id');
        $presentaciones_id = $request->input('presentacion_id');
        $cantidades = $request->input('cantidad');
        $tipo_ventas = $request->input('tipo_venta');
//        $precio = $request->input('precios_unitario'); // quitar despues
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
            $precio_unitario = $precio_presentacion->precio; // HABILITAR DESDES DE METER TOD O
//            $precio_unitario = $precio[$i];
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
                'descripcion_presentacion' => $precio_presentacion->nombre_factura,
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

    /**
     * @param $id
     * @return mixed
     */
    public function OrdenPedidoPDF($id)
    {
//        dd(Carbon::now());
        $ordenPedido = OrdenPedido::find($id);
        $iva = Configuracion::find(1)->iva;
        if ($ordenPedido->tipo_documento->codigo == 'FAC')
        {
            foreach ($ordenPedido->salidas as $salida)
            {
                $salida->precio_unitario = $salida->precio_unitario * $iva;
                $salida->venta_gravada = $salida->venta_gravada * $iva;
                $salida->venta_exenta = $salida->venta_exenta * $iva;
            }
        }
        $ordenPedido->iva = $ordenPedido->venta_total * 0.13;
        $ordenPedido->venta_total_con_iva = $ordenPedido->venta_total + $ordenPedido->iva;
        $ventaTotal = number_format($ordenPedido->venta_total_con_iva,2);
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
    public function OrdenPedidoBodegaPost(Request $request, $id)
    {
        $orden_pedido = OrdenPedido::find($id);
        $salidas = $orden_pedido->salidas;
        $cantidad = $request->input('cantidades');
        $max = sizeof($cantidad);
//        dd($cantidad);
        // Comprobacion si existencias alcanzan para procesar orden
        for ($i=0; $i<$max; $i++)
        {
            $producto = Producto::find($salidas[$i]->movimiento->producto_id);
            $cantidad_existencia = round($producto->cantidad_existencia,4);
            $cantidad_salida = round($cantidad[$i],4);
            $salidas[$i]->movimiento->cantidad = $cantidad_salida;
//            $salidas[$i]->save();
            if ($cantidad_existencia < $cantidad_salida)
            {
                // Mensaje de exito al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-check');
                session()->flash('mensaje.titulo', 'Upssss!');
                session()->flash('mensaje.contenido', 'No hay suficiente producto ' . $producto->nombre . ' para procesar la orden!');
                return redirect()->route('ordenPedidoVerBodega',['id' => $orden_pedido->id]);
            }
        }

        for ($i=0; $i<$max; $i++)
        {
            $producto = Producto::find($salidas[$i]->movimiento->producto_id);
            $cantidad_existencia = $producto->cantidad_existencia - $salidas[$i]->movimiento->cantidad;
            $costo_total = $salidas[$i]->movimiento->cantidad * $producto->costo;
            $costo_total_existencia = $cantidad_existencia * $producto->costo;
            // Actualizar movimiento
            $salidas[$i]->movimiento->costo_unitario = round($producto->costo,4);
            $salidas[$i]->movimiento->costo_total = round($costo_total,4);
            $salidas[$i]->movimiento->cantidad_existencia = round($cantidad_existencia,4);
            $salidas[$i]->movimiento->costo_unitario_existencia = round($producto->costo,4);
            $salidas[$i]->movimiento->costo_total_existencia = round($costo_total_existencia,4);
            $salidas[$i]->movimiento->fecha_procesado = Carbon::now();
            $salidas[$i]->movimiento->procesado = true;
            $salidas[$i]->movimiento->save();
            $salidas[$i]->save();
            $producto->cantidad_existencia = $salidas[$i]->movimiento->cantidad_existencia;
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
            try {
                $salida->movimiento->delete();
                $salida->delete();
            } catch (\Exception $e) {
                abort(403);
            }

        }
        try {
            $orden_pedido->delete();
        } catch (\Exception $e) {
            abort(403);
        }
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La orden de pedido fue eliminada correctamente!');
        return redirect()->route('ordenPedidoLista');
    }
}
