<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\ConversionUnidadMedida;
use App\DetalleProduccion;
use App\Entrada;
use App\Formula;
use App\Movimiento;
use App\Produccion;
use App\Producto;
use App\Rol;
use App\Salida;
use App\TipoAjuste;
use App\TipoMovimiento;
use App\UnidadMedida;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProduccionController extends Controller
{
    public function ProduccionLista(Request $request)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        $producciones = Produccion::whereBetween('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')])->get();
        return view('produccion.produccionLista')
            ->with(['producciones' => $producciones])
            ->with(['extra' => $extra]);
    }

    public function ProduccionRevLista(Request $request)
    {
        $fecha_inicio = ($request->get('fecha_inicio') != null) ? Carbon::parse($request->get('fecha_inicio')) : Carbon::now()->subDays(15);
        $fecha_fin = ($request->get('fecha_fin') != null) ? Carbon::parse($request->get('fecha_fin')) : Carbon::now()->addDays(15);
        $extra['fecha_inicio'] = $fecha_inicio;
        $extra['fecha_fin'] = $fecha_fin;
        $producciones = Produccion::onlyTrashed()->whereBetween('fecha',[$fecha_inicio->format('Y-m-d'),$fecha_fin->format('Y-m-d')])->get();
        return view('produccion.produccionLista')
            ->with(['producciones' => $producciones])
            ->with(['extra' => $extra]);
    }

    public function ProduccionVer($id)
    {
        $produccion = Produccion::withTrashed()->find($id);
        $productos = Producto::all();
        $formula = Formula::find($produccion->formula_id);
        $rol_bodega = Rol::whereNombre('Bodeguero')->first();
        $bodegueros = User::whereRolId($rol_bodega->id)->get();
        return view('produccion.produccionVer')
            ->with(['produccion' => $produccion])
            ->with(['formula' => $formula])
            ->with(['productos' => $productos])
            ->with(['bodegueros' => $bodegueros]);
    }

    public function ProduccionNuevo()
    {
        $formulas = Formula::whereActiva(true)->get();
        $rol_bodega = Rol::whereNombre('Bodeguero')->first();
        $bodegueros = User::whereRolId($rol_bodega->id)->get();
        return view('produccion.produccionNuevo2')
            ->with(['formulas' => $formulas])
            ->with(['bodegueros' => $bodegueros]);
    }

    public function ProduccionNuevaPost(Request $request)
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

    public function ProduccionRevertir($id)
    {
//        dd('Voy en eliminar');
        $produccion = Produccion::find($id);
        // Retiramos por ajuste el producto ingresado en produccion
        $producto_producido = Producto::find($produccion->producto_id);
        // Variables para ajuste
        $tipo_ajuste = TipoAjuste::whereCodigo('SALERP')->first();
        $cantidad_ajuste = $producto_producido->cantidad_existencia - $produccion->cantidad;
        $diferencia_ajuste = $produccion->cantidad;
        // Se crea el ajuste
        $ajuste = Ajuste::create([
            'tipo_ajuste_id' => $tipo_ajuste->id,
            'detalle' => 'Ajuste de salida por error en producción',
            'fecha' => Carbon::now(),
            'cantidad_ajuste' => $cantidad_ajuste,
            'valor_unitario_ajuste' => $producto_producido->costo,
            'realizado_id' => Auth::user()->id,
            'cantidad_anterior' => $producto_producido->cantidad_existencia,
            'valor_unitario_anterior' => $producto_producido->costo,
            'diferencia_ajuste' => $diferencia_ajuste,
        ]);
        $tipo_movimiento = TipoMovimiento::whereCodigo('AJSS')->first();
        // Se crea el movimiento
        $movimiento = Movimiento::create([
            'producto_id' => $producto_producido->id,
            'tipo_movimiento_id' => $tipo_movimiento->id,
            'ajuste_id' => $ajuste->id,
            'fecha' => Carbon::now(),
            'detalle' => 'Salida por reversión en produccion n° ' . $produccion->id,
            'cantidad' => $diferencia_ajuste,
            'costo_unitario' => $producto_producido->costo,
            'costo_total' => $cantidad_ajuste * $producto_producido->costo,
            'cantidad_existencia' => $cantidad_ajuste,
            'costo_unitario_existencia' => $producto_producido->costo,
            'costo_total_existencia' => $cantidad_ajuste * $producto_producido->costo,
            'fecha_procesado' => Carbon::now(),
            'procesado' => true,
        ]);
        // Se actualiza la cantidad de producto despues de la entrada
        $producto_producido->cantidad_existencia = $movimiento->cantidad_existencia;
        $producto_producido->save();

        // Se reingresan los componentes de las formulas
        $tipo_ajuste = TipoAjuste::whereCodigo('ENTERP')->first();
        foreach ($produccion->salidas as $salida)
        {
            // Retiramos por ajuste el producto ingresado en produccion
            $producto_componente = Producto::find($salida->movimiento->producto_id);
            // Variables para ajuste
            $cantidad_ajuste = $producto_componente->cantidad_existencia + $salida->movimiento->cantidad;
            $diferencia_ajuste = $salida->movimiento->cantidad;
            // Se crea el ajuste
            $ajuste = Ajuste::create([
                'tipo_ajuste_id' => $tipo_ajuste->id,
                'detalle' => 'Ajuste de entrada por error en producción',
                'fecha' => Carbon::now(),
                'cantidad_ajuste' => $cantidad_ajuste,
                'valor_unitario_ajuste' => $producto_componente->costo,
                'realizado_id' => Auth::user()->id,
                'cantidad_anterior' => $producto_componente->cantidad_existencia,
                'valor_unitario_anterior' => $producto_componente->costo,
                'diferencia_ajuste' => $diferencia_ajuste,
            ]);
            $tipo_movimiento = TipoMovimiento::whereCodigo('AJSE')->first();
            // Se crea el movimiento
            $movimiento = Movimiento::create([
                'producto_id' => $producto_componente->id,
                'tipo_movimiento_id' => $tipo_movimiento->id,
                'ajuste_id' => $ajuste->id,
                'fecha' => Carbon::now(),
                'detalle' => 'Entrada por reversión de producción n°' . $produccion->id,
                'cantidad' => $diferencia_ajuste,
                'costo_unitario' => $producto_componente->costo,
                'costo_total' => $diferencia_ajuste * $producto_componente->costo,
                'cantidad_existencia' => $cantidad_ajuste,
                'costo_unitario_existencia' => $producto_componente->costo,
                'costo_total_existencia' => $cantidad_ajuste * $producto_componente->costo,
                'fecha_procesado' => Carbon::now(),
                'procesado' => true,
            ]);
            // Se actualiza la cantidad de producto despues de la entrada
            $producto_componente->cantidad_existencia = $movimiento->cantidad_existencia;
            $producto_componente->save();
        }
        $produccion->delete();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La producción fue revertida correctamente!');
        return redirect()->route('produccionLista');
    }

    public function ProduccionPreviaEliminar($id)
    {
        $produccion = Produccion::find($id);
        $produccion->forceDelete();
        // Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La previa de la producción fue eliminada correctamente!');
        return redirect()->route('produccionLista');
    }
}
