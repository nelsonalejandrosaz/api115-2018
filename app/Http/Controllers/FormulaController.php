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
    /**
     * @return $this
     * Estado: Revisada y funcionando
     * Fecha rev: 13-03-18
     */
    public function FormulaLista()
    {
        $formulas = Formula::whereActiva(true)->get();
        return view('formula.formulaLista')
            ->with(['formulas' => $formulas]);
    }

    /**
     * @return $this
     * Estado:
     * Fecha rev:
     */
    public function FormulaDesactivadasLista()
    {
        $formulas = Formula::whereActiva(false)->get();
        return view('formula.formulaDesactivadaLista')
            ->with(['formulas' => $formulas]);
    }

    public function FormulaVer($id)
    {
        $formula = Formula::findOrFail($id);
        $unidad_medidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaVer')
            ->with(['formula' => $formula])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['productos' => $productos]);
    }

    /**
     * @return $this
     * Estado: Revisado y funcionando
     * Fecha rev: 13-03-18
     * Observacion: Deja pasar sin componentes en la formula
     */
    public function FormulaNuevo()
    {
        $unidad_medidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaNuevo')
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['productos' => $productos]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     * Estado: Revisado y funcionado
     * Fecha rev: 13-03-18
     */
    public function FormulaNuevoPost(Request $request)
    {
        // Validación de datos
        $this->validate($request, [
            'producto_id' => 'required',
            'cantidad_formula' => 'required',
            'fecha' => 'required',
        ]);

        // Se carga el producto
        $producto = Producto::find($request->input('producto_id'));
        // Se crea y guarda la instancia de formula
        $formula = Formula::create([
            'producto_id' => $producto->id,
            'fecha' => $request->input('fecha'),
            'ingresado_id' => Auth::user()->id,
            'descripcion' => $request->input('descripcion'),
            'cantidad_formula' => $request->input('cantidad_formula'),
            'activa' => true,
            'version' => $request->input('version'),
        ]);

        // Se guardan las variables del request
        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
        $max = sizeof($productos);

        // Se recorre el array y se guardan los componentes de la formula
        for ($i=0; $i < $max ; $i++) {
            // Crea y guarda los componentes de la formula
            Componente::create([
                'formula_id' => $formula->id,
                'producto_id' => $productos[$i],
                'cantidad' => $cantidades[$i],
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

    /**
     * @param $id
     * @return mixed
     * Estado: Revisada y funcionando con bugs
     * Fecha rev: 13-03-18
     * Observaciones: La suma de la formula no la hace
     */
    public function FormulaEditar($id)
    {
        $formula = Formula::find($id);
        $producto = Producto::find($formula->producto_id);
        $unidad_medidas = UnidadMedida::all();
        $productos = Producto::all();
        return view('formula.formulaEditar')
            ->with(['formula' => $formula])
            ->with(['unidad_medidas' => $unidad_medidas])
            ->with(['productos' => $productos]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Estado: Revisada y funcionado
     * Fecha rev: 13-03-18
     */
    public function FormulaEditarPut(Request $request, $id)
    {
        // Validación de datos
        $this->validate($request, [
            'cantidad_formula' => 'required',
        ]);

        // Se carga la formula
        $formula = Formula::find($id);
        // Se carga el producto
        $producto = Producto::find($formula->producto_id);
        // Se crea y guarda la instancia de formula
        $formula->cantidad_formula = $request->input('cantidad_formula');
        $formula->descripcion = $request->input('descripcion');
        $formula->save();
        // Se guardan las variables del request
        $productos = $request->input('productos');
        $cantidades = $request->input('cantidades');
        $max = sizeof($productos);

        // Se recorre el array y se guardan los componentes de la formula
        for ($i=0; $i < $max ; $i++) {
            // Crea y guarda los componentes de la formula
            Componente::updateOrCreate([
                'formula_id' => $formula->id,
                'producto_id' => $productos[$i]],
                ['cantidad' => $cantidades[$i],
            ]);
        }

        // Mensaje de exito de ingreso
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La fórmula fue actualizada correctamente!');
        return redirect()->route('formulaVer',$formula->id);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Estado: Revisada y funcionando
     * Fecha rev: 13-03-18
     */
    public function FormulaEliminar($id)
    {
        $formula = Formula::find($id);
        $formula->activa = false;
        $formula->save();
        // Mensaje de exito de ingreso
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La fórmula fue desactivada correctamente!');
        return redirect()->route('formulaDesactivadasLista');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * Estado: Sin revisar
     * Fecha rev:
     */
    public function FormulaActivarPost($id)
    {
        $formula = Formula::find($id);
        $producto = Producto::find($formula->producto_id);
        $formula_activa = $producto->formula()->where('activa','=','1')->first();
        $formula_activa->activa = false;
        $formula_activa->save();
        $formula->activa = true;
        $formula->save();
        // Mensaje de exito de ingreso
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La fórmula fue activada correctamente!');
        return redirect()->route('formulaVer',$formula->id);
    }

    /**
     * @param $id
     * @throws \Exception
     * Estado: Revisada y funcionando
     * Fecha rev: 13-03-18
     */
    public function ComponenteEliminar($id)
    {
        $componente = Componente::find($id);
        $formula = Formula::find($componente->formula_id);
        $componente->delete();
    }

}
