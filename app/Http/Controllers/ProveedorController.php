<?php

namespace App\Http\Controllers;

use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    public function ProveedorLista()
    {
        $proveedores = Proveedor::all();
        return view('proveedor.proveedorLista')->with(['proveedores' => $proveedores]);
    }

    public function ProveedorVer(Request $request)
    {
        $proveedor = Proveedor::find($request->id);
        return view('proveedor.proveedorVer')->with(['proveedor' => $proveedor]);
    }

    public function ProveedorNuevo(Request $request)
    {
        return view('proveedor.proveedorNuevo');
    }

    public function ProveedorNuevoPost(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|unique:proveedores|max:140',
            'telefono_1' => 'min:8|nullable',
            'telefono_2' => 'min:8|nullable',
        ]);

        $proveedor = Proveedor::create($request->only(
            'nombre',
            'nombre_contacto',
            'direccion',
            'telefono_1',
            'telefono_2'
        ));
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El proveedor fue agregado correctamente!');
        return redirect()->route('proveedorVer',['id' => $proveedor->id]);
    }

    public function ProveedorEditar(Request $request)
    {
        $proveedor = Proveedor::find($request->id);
        return view('proveedor.proveedorEditar')->with(['proveedor' => $proveedor]);
    }

    public function ProveedorEditarPut(Request $request, $id)
    {
        $proveedor = Proveedor::find($id);
        $this->validate($request, [
            'nombre' => [
                'required',
                Rule::unique('proveedores')->ignore($proveedor->id),
            ],
            'telefono_1' => 'min:8|nullable',
            'telefono_2' => 'min:8|nullable',
        ]);

        $proveedor->update($request->only(
            'nombre',
            'nombre_contacto',
            'direccion',
            'telefono_1',
            'telefono_2'
        ));
//        Mensaje de exito al modificar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El proveedor fue modificado correctamente!');
        return redirect()->route('proveedorVer',['id' => $proveedor->id]);
    }

    public function ProveedorEliminar(Request $request)
    {
        $proveedor = Proveedor::find($request->id);
        $proveedor->delete();
//        Mensaje de exito al eliminar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El proveedor fue eliminado correctamente!');
        return redirect()->route('proveedorLista');
    }

}
