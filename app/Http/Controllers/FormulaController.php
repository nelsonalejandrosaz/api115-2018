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
        // Validacion
        $this->validate($request, [
            'producto_id' => 'required',
            'fecha' => 'required',
            'productos.*' => 'required',
            'porcentajes.*' => 'required',
        ]);
        // Fin Validacion

        // Crear una instancia de Formula
        $formula = Formula::create([
            'producto_id' => $request->input('producto_id'),
            'fecha' => $request->input('fecha'),
            'ingresado_id' => Auth::user()->id,
            'descripcion' => $request->input('descripcion'),
        ]);

        $productos_id = $request->input('productos');
        $porcentajes = $request->input('porcentajes');
        $max = sizeof($productos_id);

        for ($i=0; $i < $max ; $i++) {
            // Crea y guarda los componentes de la formula
            Componente::create([
                'formula_id' => $formula->id,
                'producto_id' => $productos_id[$i],
                'porcentaje' => $porcentajes[$i],
            ]);
        }

        // Mensaje de exito de ingreso
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La fórmula fue ingresada correctamente!');
        return redirect()->route('formulaVer',$formula->id);
    }

    public function FormulaEditar($id)
    {
        $formula = Formula::find($id);
        $unidadMedidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaEditar')->with(['formula' => $formula])->with(['unidadMedidas' => $unidadMedidas])->with(['productos' => $productos]);
    }

    public function FormulaEditarPut(Request $request)
    {
        // Validacion
        $this->validate($request, [
            'producto_id' => 'required',
            'fechaIngreso' => 'required',
            'productos.*' => 'required',
            'porcentajes.*' => 'required',
        ]);
        // dd($request);
        // Fin Validacion
        $formula = Formula::find($request->id);
        $formula->producto_id = $request->input('producto_id') ;
        $formula->fechaIngreso = $request->input('fechaIngreso');
        $formula->ingresadoPor = $request->input('ingresadoPor');
        $formula->descripcion = $request->input('descripcion');
        $formula->save();
        $productos_id = $request->input('productos');
        $porcentajes = $request->input('porcentajes');
        $componentes = $formula->componentes;
        $max = sizeof($productos_id);
        for ($i=0; $i < $max ; $i++) {
            foreach ($componentes as $componente) {
                if ($componente->producto_id == $productos_id[$i]) {
                    $existe = true;
                    $componente_id = $componente->id;
                }
            }
            if ($existe) {
                $componente = Componente::find($componente_id);
                $componente->porcentaje = $porcentajes[$i];
            } else {
                // Crea y guarda los componentes de la formula
                Componente::create([
                    'formula_id' => $formula->id,
                    'producto_id' => $productos_id[$i],
                    'porcentaje' => $porcentajes[$i],
                ]);
            }
            $existe = false;
        }
        dd($formula->componentes);
    }



}
