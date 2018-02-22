<?php

namespace App\Http\Controllers;

use App\Abono;
use App\Ajuste;
use App\Cliente;
use App\CondicionPago;
use App\ConversionUnidadMedida;
use App\DetalleProduccion;
use App\Entrada;
use App\Formula;
use App\Movimiento;
use App\Municipio;
use App\OrdenPedido;
use App\Produccion;
use App\Producto;
use App\Rol;
use App\Salida;
use App\TipoDocumento;
use App\TipoMovimiento;
use App\UnidadMedida;
use App\User;
use App\Venta;
use Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;

class DevController extends Controller
{
    public function VentaSinOrden()
    {
        $tipo_documentos = TipoDocumento::all();
        $clientes = Cliente::all();
        $condiciones_pago = CondicionPago::all();
        return view('dev.venta-sin-orden')
            ->with(['tipoDocumentos' => $tipo_documentos])
            ->with(['clientes' => $clientes])
            ->with(['condiciones_pago' => $condiciones_pago]);
    }

    public function VentaAnuladaSinOrden()
    {
        $tipo_documentos = TipoDocumento::all();
        $clientes = Cliente::all();
        return view('dev.venta-anulada-sin-orden')
            ->with(['tipoDocumentos' => $tipo_documentos])
            ->with(['clientes' => $clientes]);
    }

    public function VentaAnuladaSinOrdenPost(Request $request)
    {
        $venta = Venta::create([
            'tipo_documento_id' => $request->input(''),
            'numero' => $request->input(''),
            'cliente_id' => $request->input(''),
            'vendedor_id' => Auth::user()->id,
            'fecha' => Carbon::now(),
            'estado_venta_id' => 3,
            'saldo' => 0.00,
            'venta_total' => 0.00,
            'venta_total_con_impuestos' => 0.00,
            'fecha_anulado' => Carbon::now(),
        ]);
        return view('dev.venta-anulada-sin-orden');
    }

    public function Corregir($id)
    {
        $producto = Producto::find($id);
        $unidad_medidas = UnidadMedida::all();
        if (\Auth::user()->rol->nombre == 'Vendedor')
        {
            return view('producto.productoPrecioV')
                ->with(['producto' => $producto])
                ->with(['unidad_medidas' => $unidad_medidas]);
        }
        return view('dev.productoPrecio')
            ->with(['producto' => $producto])
            ->with(['unidad_medidas' => $unidad_medidas]);
    }


    public function select2($id)
    {
        $orden_pedido = OrdenPedido::find($id);
        $productos = Producto::all();
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        $tipoDocumentos = TipoDocumento::all();
        return view('dev.venta-sin-orden')
            ->with(['orden_pedido' => $orden_pedido])
            ->with(['productos' => $productos])
            ->with(['clientes' => $clientes])
            ->with(['municipios' => $municipios])
            ->with(['tipoDocumentos' => $tipoDocumentos]);
    }

    public function pruebaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'formula_id' => 'required',
            'cantidad' => 'required',
            'fecha' => 'required',
        ]);

        // Se carga la producion
        $formula = Formula::find($request->input('formula_id'));
        $cantidad_produccion = $request->input('cantidad');

        //        Crear la produccion
        $produccion = Produccion::create([
            'formula_id' => $request->input('formula_id'),
            'bodega_id' => Auth::user()->id,
            'producto_id' => $formula->producto_id,
            'cantidad' => $cantidad_produccion,
            'fecha' => $request->input('fecha'),
            'detalle' => $request->input('detalle'),
            'lote' => $request->input('lote'),
            'fecha_vencimiento' => $request->input('fecha_vencimiento'),
        ]);

        // Se realiza el detalle de la produccion
        $bodegueros = $request->input('fabricado_id');
        $max = sizeof($bodegueros);
        for ($i = 0; $i < $max; $i ++)
        {
            $detalle_controller = DetalleProduccion::create([
                'bodega_id' => $bodegueros[$i],
                'produccion_id' => $produccion->id,
            ]);
        }

        return redirect()->route('produccionPrevia',['id' => $produccion->id]);
    }

    public function ProduccionPrevia($id)
    {
        // Se carga la producion
        $productos = Producto::all();
        $produccion = Produccion::find($id);
        $formula = Formula::find($produccion->formula_id);
        $cantidad_produccion = $produccion->cantidad;
        $rol_bodega = Rol::whereNombre('Bodeguero')->first();
        $bodegueros = User::whereRolId($rol_bodega->id)->get();

        // Calculando cantidades
        foreach ($formula->componentes as $componente)
        {
            $componente->cantidad = ($cantidad_produccion * $componente->cantidad) / $formula->cantidad_formula;
        }

//        dd($formula);

        return view('produccion.produccionPrevia')
            ->with(['produccion' => $produccion])
            ->with(['formula' => $formula])
            ->with(['productos' => $productos])
            ->with(['bodegueros' => $bodegueros]);

    }

    public function ProduccionConfirmarPost(Request $request, $id)
    {
//        dd($request);
        $produccion = Produccion::find($id);
        $formula = Formula::find($produccion->formula_id);
        $componentes = $request->input('productos');
        $cantidades = $request->input('cantidades');
        $max = sizeof($cantidades);
        $unidad_medida_formula = UnidadMedida::whereAbreviatura('gr')->first();

//        dd($componentes);

        /**
         * Validación de existencias
         */
        for ($i = 0; $i < $max; $i++)
        {
            $producto = Producto::find($componentes[$i]);
            $cantidad = $cantidades[$i];
            $cantidad = round($cantidad,4);
            $cantidad_real = $cantidad / 1000;
            $cantidad_real = round($cantidad_real,4);
            $cantidad_producto = round($producto->cantidad_existencia,4);
            if ($cantidad_producto < $cantidad_real){
                // Mensaje de error al guardar
                session()->flash('mensaje.tipo', 'danger');
                session()->flash('mensaje.icono', 'fa-close');
                session()->flash('mensaje.titulo', 'Error!');
                session()->flash('mensaje.contenido', 'No hay suficiente ' . $producto->nombre . ' necesaria para generar la producción!');
                return redirect()->route('produccionPrevia',['id' => $produccion->id]);
            }
        }
        /**
         * Fin validación existencias
         */

        $costo_total_produccion = 0.00;
        // Se registran las salidas
        for ($i = 0; $i < $max; $i++)
        {
            // Se carga el producto
            $producto = Producto::find($componentes[$i]);
            $cantidad = $cantidades[$i];
            $cantidad = round($cantidad,4);
            $cantidad_real = $cantidad / 1000;
            $cantidad_real = round($cantidad_real,4);
            $cantidad_salida = $cantidad;
            // Se calcula la cantidad y costo
            // Calculo costo salida
            $costo_unitario_salida = $producto->costo;
            $costo_total_salida = $cantidad_real * $costo_unitario_salida;
            $costo_total_salida = round($costo_total_salida,4);
            // Calculo de cantidad y costos existencias
            $cantidad_existencia = $producto->cantidad_existencia - $cantidad_real;
            $costo_unitario_existencia = $producto->costo;
            $costo_total_existencia = $cantidad_existencia * $costo_unitario_existencia;
            $costo_total_existencia = round($costo_total_existencia,4);

            // Se crea la salida
            $salida = Salida::create([
                'produccion_id' => $produccion->id,
                'cantidad' => $cantidad_salida,
                'unidad_medida_id' => $unidad_medida_formula->id,
                'precio_unitario' => 0.00,
                'venta_exenta' => 0.00,
                'venta_gravada' => 0.00,
            ]);
            $tipo_movimiento = TipoMovimiento::whereCodigo('SALP')->first();
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'salida_id' => $salida->id,
                'fecha' => $produccion->fecha,
                'detalle' => 'Salida de producto por producción n° ' . $produccion->id,
                'cantidad' => $cantidad_real,
                'costo_unitario' => $costo_unitario_salida,
                'costo_total' => $costo_total_salida,
                'cantidad_existencia' => $cantidad_existencia,
                'costo_unitario_existencia' => $costo_unitario_existencia,
                'costo_total_existencia' => $costo_total_existencia,
                'fecha_procesado' => Carbon::now(),
                'procesado' => true,
            ]);

            $costo_total_produccion += $costo_total_salida;
            // Se actualiza la existencia del producto
            $producto->cantidad_existencia = $cantidad_existencia;
            $producto->save();
        }
        // Se actualiza la cantidad del producto producido
        // Se carga el producto
        $producto = Producto::find($formula->producto_id);
        // Se calcula la cantidad y costo
        $costo_unitario_produccion = $costo_total_produccion / $produccion->cantidad;
        $costo_unitario_produccion = round($costo_unitario_produccion,4);
        $cantidad = $produccion->cantidad;
        $costo_unitario_entrada = $costo_unitario_produccion;
        $costo_total_entrada = $cantidad * $costo_unitario_entrada;
        $costo_total_entrada = round($costo_total_entrada,4);
//            Calculo de existencias
        $cantidad_existencia = $producto->cantidad_existencia + $cantidad;
        /**
         * Asignacion de costo bajo costo promedio ponderado
         */
        if ($producto->costo == 0.00) {
            $costo_unitario_existencia = $costo_unitario_entrada;
        } else {
            $costo_total_existencia = $producto->costo * $producto->cantidad_existencia;
            $costo_unitario_existencia = ($costo_total_existencia + $costo_total_entrada) / $cantidad_existencia;
            $costo_unitario_existencia = round($costo_unitario_existencia,4);
        }
        $costo_total_existencia = $costo_unitario_existencia * $cantidad_existencia;
        $costo_total_existencia = round($costo_total_existencia,4);

        // Se crea la entrada
        $entrada = Entrada::create([
            'produccion_id' => $produccion->id,
            'unidad_medida_id' => $producto->unidad_medida_id,
            'cantidad' => $cantidad,
            'costo_unitario' => $costo_unitario_entrada,
            'costo_total' => $costo_total_entrada,
        ]);
        // Se crea el movimiento de entrada
        $tipo_movimiento = TipoMovimiento::whereCodigo('ENTP')->first();
        $movimiento = Movimiento::create([
            'producto_id' => $producto->id,
            'tipo_movimiento_id' => $tipo_movimiento->id,
            'entrada_id' => $entrada->id,
            'fecha' => $produccion->fecha,
            'detalle' => 'Entrada de producto por producción n° ' . $produccion->id,
            'cantidad' => $cantidad,
            'costo_unitario' => $costo_unitario_entrada,
            'costo_total' => $costo_total_entrada,
            'cantidad_existencia' => $cantidad_existencia,
            'costo_unitario_existencia' => $costo_unitario_existencia,
            'costo_total_existencia' => $costo_total_existencia,
            'fecha_procesado' => Carbon::now(),
            'procesado' => true,
        ]);
        // Se actualiza el producto con la entrada de la producción
        $producto->cantidad_existencia = $cantidad_existencia;
        $producto->costo = $costo_unitario_existencia;
        $producto->save();
        $produccion->procesado = true;
        $produccion->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La producción fue agregada correctamente!');
        return redirect()->route('produccionVer', ['id' => $produccion->id]);

    }

    public function UnidadesMedidaJSON(Request $request)
    {
        $term = $request->term ?: '';
        $unidades_medidas = UnidadMedida::where('nombre', 'like', $term . '%')->get();
        $valid_um = [];
        foreach ($unidades_medidas as $unidad_medida) {
            $valid_um[] = ['id' => $unidad_medida->id, 'text' => $unidad_medida->nombre];
        }
        return Response::json($valid_um);
    }

    public function UnidadesConversionJSON(Request $request)
    {
        $unidad_medida_origen = $request->umo;
        $unidad_medida = UnidadMedida::find($unidad_medida_origen);
        $unidades_equivalentes = $unidad_medida->conversiones;
//        dd($unidades_equivalentes[0]);
        $valid = [];
        $valid[] = ['id' => $unidad_medida->id, 'text' => $unidad_medida->abreviatura, 'data-factor' => 69];
        foreach ($unidades_equivalentes as $unidad_equivalente) {
            $valid[] = ['id' => $unidad_equivalente->unidad_destino->id, 'text' => $unidad_equivalente->unidad_destino->abreviatura, 'data-factor' => 69];
        }
        return Response::json($valid);
    }

    public function FactorJSON(Request $request)
    {
        $unidad_medida_origen = $request->umo;
        $unidad_medida_destino = $request->umd;
        $factor = ConversionUnidadMedida::where([
            ['unidad_medida_origen_id', '=', $unidad_medida_origen],
            ['unidad_medida_destino_id', '=', $unidad_medida_destino],
        ])->first();
        $valid = $factor->factor;
        return Response::json($valid);
    }

    public function ProductosPresentacionesJSON(Request $request, $id)
    {
        $producto = Producto::find($id);
        $precios = $producto->precios;
        return Response::json($precios);
    }
}
