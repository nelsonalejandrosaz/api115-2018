<?php

namespace App\Http\Controllers;

use App\Compra;
use App\CondicionPago;
use App\Configuracion;
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
        foreach ($compra->entradas as $entrada) {
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
        $iva = Configuracion::find(1)->iva;

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
                'cantidad' => round($cantidades[$i],4),
                'costo_unitario' => round($costo_unitario_entrada,4),
                'costo_total' => round($costo_totales[$i],4),
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
                'cantidad' => round($cantidades[$i],4),
                'costo_unitario' => round($costo_unitario_entrada,4),
                'costo_total' => round($costo_totales[$i],4),
                'cantidad_existencia' => round($cantidad_existencia,4),
                'costo_unitario_existencia' => round($costo_unitario_existencia,4),
                'costo_total_existencia' => round($costo_total_existencia,4),
                'fecha_procesado' => Carbon::now(),
                'procesado' => false,
            ]);
            // Se actualiza la existencia del producto
//            $producto->cantidad_existencia = $cantidad_existencia;
//            $producto->costo = $costo_unitario_existencia;
//            $producto->save();
            $compra_total += $costo_totales[$i];
        }
        $compra->compra_total = round($compra_total,4);
        $compra_total_con_impuestos = $compra_total * $iva;
        $compra->compra_total_con_impuestos = round($compra_total_con_impuestos,4);
        $compra->saldo = round($compra_total,4);
        $compra->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La compra fue ingresada correctamente! Por favor verifique en cantidades antes de procesar');
        return redirect()->route('compraVer', ['id' => $compra->id]);
    }

    public function CompraProcesar($id)
    {
        $compra = Compra::find($id);
        $entradas = $compra->entradas;
        $proveedor = Proveedor::find($compra->proveedor_id);
        foreach ($entradas as $entrada)
        {
            $producto = Producto::find($entrada->movimiento->producto_id);
            $cantidad_existencia = $entrada->movimiento->cantidad_existencia;
            $costo_unitario_existencia = $entrada->movimiento->costo_unitario_existencia;
            $entrada->movimiento->procesado = true;
            $entrada->movimiento->fecha_procesado = Carbon::now();
            $entrada->movimiento->save();
            $producto->cantidad_existencia = round($cantidad_existencia,4);
            $producto->costo = round($costo_unitario_existencia,4);
            $producto->save();
        }
        $compra->estado_compra_id = EstadoCompra::whereCodigo('PROCE')->first()->id;
        $compra->save();
        $proveedor->saldo = $proveedor->saldo + $compra->compra_total;
        $proveedor->save();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La compra fue procesada e ingresada a bodega correctamente!');
        return redirect()->route('compraVer', ['id' => $compra->id]);
    }

    public function CompraEliminar($id)
    {
        $compra = Compra::find($id);
        $entradas = $compra->entradas;
        foreach ($entradas as $entrada)
        {
            $entrada->movimiento->delete();
            $entrada->delete();
        }
        $compra->delete();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La compra fue eliminada correctamente!');
        return redirect()->route('compraLista');
    }
}
