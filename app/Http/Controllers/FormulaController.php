<?php

namespace App\Http\Controllers;

use App\Componente;
use App\Formula;
use App\Producto;
use App\UnidadMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormulaController extends Controller
{
    public function FormulaLista()
    {
        $formulas = Formula::all();
//        dd($formulas);
        return view('formula.formulaLista')
            ->with(['formulas' => $formulas]);
    }

    public function FormulaVer($id)
    {
        $formula = Formula::find($id);
        $unidad_medidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaVer')
            ->with(['formula' => $formula])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['productos' => $productos]);
    }

    public function FormulaNuevo()
    {
        $unidad_medidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaNuevo')
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['productos' => $productos]);
    }

    public function FormulaNuevoPost(Request $request)
    {
        // Validación de datos
        $this->validate($request, [
            'producto_id' => 'required',
            'fecha' => 'required',
            'productos.*' => 'required',
            'porcentajes.*' => 'required',
        ]);


        // Se carga el producto
        $producto = Producto::find($request->input('producto_id'));
        // Se crea y guarda la instancia de formula
        $formula = Formula::create([
            'producto_id' => $producto->id,
            'fecha' => $request->input('fecha'),
            'ingresado_id' => Auth::user()->id,
            'descripcion' => $request->input('descripcion'),
            'activa' => true,
        ]);

        // Se guardan las variables del request
        $productos_id = $request->input('productos');
        $porcentajes = $request->input('porcentajes');
        $max = sizeof($productos_id);

        // Se recorre el array y se guardan los componentes de la formula
        for ($i=0; $i < $max ; $i++) {
            // Crea y guarda los componentes de la formula
            Componente::create([
                'formula_id' => $formula->id,
                'producto_id' => $productos_id[$i],
                'porcentaje' => $porcentajes[$i],
            ]);
        }

        // Se guarda en producto que posee una formula asociada
        $producto->formula_activa = true;
        $producto->saveOrFail();

        // Mensaje de exito de ingreso
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La fórmula fue ingresada correctamente!');
        return redirect()->route('formulaVer',$formula->id);
    }

    public function FormulaEditar($id)
    {
        $formula = Formula::find($id);
        $formula->version = $formula->version + 1;
        $unidad_medidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaEditar')->with(['formula' => $formula])->with(['unidad_medidas' => $unidad_medidas])->with(['productos' => $productos]);
    }

    public function FormulaEditarPut(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'fecha_modificacion' => 'required',
            'productos.*' => 'required',
            'porcentajes.*' => 'required',
        ]);
        dd($request);

        // Se carga la formula y el producto
        $formula_anterior = Formula::find($request->id);
        $producto = Producto::find($formula_anterior->producto_id);

        // Se crea una nueva formula
        $formula_nueva = Formula::create([
            'producto_id' => $formula_anterior->producto->id,
            'fecha' => $request->input('fecha_modificacion'),
            'ingresado_id' => Auth::user()->id,
            'descripcion' => $request->input('descripcion'),
            'activa' => true,
            'version' => $formula_anterior->version + 1,
        ]);

        // Se guardan las variables del request
        $productos_id = $request->input('productos');
        $porcentajes = $request->input('porcentajes');
        $max = sizeof($productos_id);

        // Se recorre el array y se guardan los componentes de la formula
        for ($i=0; $i < $max ; $i++) {
            // Crea y guarda los componentes de la formula
            Componente::create([
                'formula_id' => $formula_nueva->id,
                'producto_id' => $productos_id[$i],
                'porcentaje' => $porcentajes[$i],
            ]);
        }
        // Se guarda en producto que posee una formula asociada y se desactiva la formula anterior
        $formula_anterior->activa = false;
        $formula_anterior->save();
        $producto->formula_activa = true;
        $producto->save();

        // Mensaje de exito de ingreso
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La fórmula fue ingresada correctamente!');
        return redirect()->route('formulaVer',$formula_nueva->id);

    }



}
