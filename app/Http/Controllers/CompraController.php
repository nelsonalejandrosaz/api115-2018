<?php

namespace App\Http\Controllers;

use App\Compra;
use App\CondicionPago;
use App\Entrada;
use App\EstadoCompra;
use App\Movimiento;
use App\Producto;
use App\Proveedor;
use App\TipoMovimiento;
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
        foreach ($compra->entradas as $entrada)
        {
            $entrada->costo_total = $entrada->cantidad * $entrada->costo_unitario;
        }
        return view('compra.compraVer')->with(['compra' => $compra])->with(['productos' => $productos])->with(['proveedores' => $proveedores]);
    }

    public function CompraNueva()
    {
        $productos = Producto::all();
        $proveedores = Proveedor::all();
        $condiciones_pago = CondicionPago::all();
        return view('compra.compraNueva')
            ->with(['productos' => $productos])
            ->with(['proveedores' => $proveedores])
            ->with(['condiciones_pago' => $condiciones_pago]);
    }

    public function CompraNuevaPost(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'fecha' => 'required',
            'proveedor_id' => 'required',
            'numero' => 'required',
            'condicion_pago_id' => 'required',
            'productos_id.*' => 'required',
            'cantidades.*' => 'required | min:1',
            'costo_unitarios.*' => 'required',
        ]);

        // Variables
        $estado_compra = EstadoCompra::whereCodigo('INGRE')->first();

        // Se crea una instancia de compra
        $compra = Compra::create([
            'fecha' => $request->input('fecha'),
            'numero' => $request->input('numero'),
            'proveedor_id' => $request->input('proveedor_id'),
            'ingresado_id' => \Auth::user()->id,
            'detalle' => $request->input('detalle'),
            'condicion_pago_id' => $request->input('condicion_pago_id'),
            'estado_compra_id' => $estado_compra->id,
        ]);
        // Se guarda el archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo')->store('public');
            $compra->ruta_archivo = $archivo;
            $compra->save();
        }
        // Se guardan en variables los arrays recibidos del request
        $productos_id = $request->input('productos_id');
        $cantidades = $request->input('cantidades');
        $costo_unitarios = $request->input('costo_unitarios');
        $costo_totales = $request->input('costo_totales');
        // Se toma el tamaño de un array
        $dimension = sizeof($productos_id);
        $compra_total = 0;
        for ($i = 0; $i < $dimension; $i++) {
            // Calculo de valor unitario de la entrada
            $costo_unitario_entrada = $costo_totales[$i] / $cantidades[$i];
            // Se carga el producto
            $producto = Producto::find($productos_id[$i]);
//            Calculo de existencias
            $cantidad_existencia = $producto->cantidad_existencia + $cantidades[$i];
            /**
             * Asignación de costo bajo costo promedio ponderado
             */
            if ($producto->costo == 0.00) {
                $costo_unitario_existencia = $costo_unitario_entrada;
            } else {
                $costo_total_existencia = $producto->costo * $producto->cantidad_existencia;
                $costo_total_entrada = $costo_totales[$i];
                $costo_unitario_existencia = ($costo_total_existencia + $costo_total_entrada) / $cantidad_existencia;
            }
            // Se crea la entrada
            $entrada = Entrada::create([
                'compra_id' => $compra->id,
                'unidad_medida_id' => $producto->unidad_medida_id,
                'cantidad' => $cantidades[$i],
                'costo_unitario' => $costo_unitario_entrada,
                'costo_total' => $costo_totales[$i],
            ]);
            // Se crea el movimiento
            $tipo_movimiento = TipoMovimiento::whereCodigo('ENTC')->first();
            $costo_total_existencia = $cantidad_existencia * $costo_unitario_existencia;
            $movimiento = Movimiento::create([
                'producto_id' => $producto->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'entrada_id' => $entrada->id,
                'fecha' => $compra->fecha,
                'detalle' => 'Ingreso de producto por compra con documento n° ' . $compra->numero,
                'cantidad' => $cantidades[$i],
                'costo_unitario' => $costo_unitario_entrada,
                'costo_total' => $costo_totales[$i],
                'cantidad_existencia' => $cantidad_existencia,
                'costo_unitario_existencia' => $costo_unitario_existencia,
                'costo_total_existencia' => $costo_total_existencia,
                'fecha_procesado' => Carbon::now(),
                'procesado' => true,
            ]);
            // Se actualiza la existencia del producto
            $producto->cantidad_existencia = $cantidad_existencia;
            $producto->costo = $costo_unitario_existencia;
            $producto->save();
            $compra_total += $costo_totales[$i];
        }
        $compra->compra_total = $compra_total;
        $compra_total_con_impuestos = $compra_total * 1.13;
        $compra->compra_total_con_impuestos = $compra_total_con_impuestos;
        $compra->saldo = $compra_total;
        $compra->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La compra fue agregada correctamente!');
        return redirect()->route('compraVer', ['id' => $compra->id]);
    }
}
