<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function CategoriaLista()
    {
        $categorias = Categoria::all();
        return view('categoria.categoriaLista')->with(['categorias' => $categorias]);
    }

    public function CategoriaVer($id)
    {
        $categoria = Categoria::find($id);
        return view('categoria.categoriaVer')->with(['categoria' => $categoria]);
    }

    public function CategoriaNuevo()
    {
        return view('categoria.categoriaNuevo');
    }

    public function CategoriaNuevoPost(Request $request)
    {
        $this->validate($request, [
            'codigo' => 'required|unique:categorias',
            'nombre' => 'required',
        ]);

        $categoria = Categoria::create($request->only(
            'codigo',
            'nombre',
            'descripcion'
        ));

//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La categoría fue ingresada correctamente!');
        return redirect()->route('categoriaVer', ['id' => $categoria->id]);
    }

    public function CategoriaEditar($id)
    {
        $categoria = Categoria::find($id);
        return view('categoria.categoriaEditar')->with(['categoria' => $categoria]);
    }

    public function CategoriaEditarPut(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        $this->validate($request, [
            'codigo' => [
                'required',
                Rule::unique('categorias')->ignore($categoria->id),
            ],
            'nombre' => 'required',
        ]);

        $categoria->update($request->only(
            'codigo',
            'nombre',
            'descripcion'
        ));

//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La categoría fue modificada correctamente!');
        return redirect()->route('categoriaVer', ['id' => $categoria->id]);
    }

    public function CategoriaEliminar(Request $request)
    {
        $categoria = Categoria::find($request->id);
        $categoria->delete();
//        Mensaje de exito al eliminar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'La categoría fue eliminada correctamente!');
        return redirect()->route('categoriaLista');
    }
}
