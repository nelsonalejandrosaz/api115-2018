<?php

namespace App\Http\Controllers;

use App\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function ClienteLista()
    {
        $clientes = Cliente::all();
        return view('cliente.clienteLista')->with(['clientes' => $clientes]);
    }

    public function ClienteVer(Request $request)
    {
        $cliente = Cliente::find($request->id);
        return view('cliente.clienteVer')->with(['cliente' => $cliente]);
    }

    public function ClienteNuevo(Request $request)
    {
        return view('cliente.clienteNuevo');
    }

    public function ClienteNuevoPost(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|unique:clientes',
            'telefono1' => 'min:8|nullable',
            'telefono2' => 'min:8|nullable',
        ]);
        $cliente = Cliente::create($request->only(
            'nombre',
            'nombreContacto',
            'direccion',
            'telefono1',
            'telefono2'
        ));
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El cliente fue agregado correctamente!');
        return redirect()->route('clienteVer',['id' => $cliente->id]);
    }

    public function ClienteEditar(Request $request)
    {
        $cliente = Cliente::find($request->id);
        return view('cliente.clienteEditar')->with(['cliente' => $cliente]);
    }

    public function ClienteEditarPut(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        $this->validate($request, [
            'nombre' => [
                'required',
                Rule::unique('clientes')->ignore($cliente->id),
            ],
            'telefono1' => 'min:8|nullable',
            'telefono2' => 'min:8|nullable',
        ]);

        $cliente->update($request->only(
            'nombre',
            'nombreContacto',
            'direccion',
            'telefono1',
            'telefono2'
        ));
//        Mensaje de exito al modificar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El cliente fue modificado correctamente!');
        return redirect()->route('clienteVer',['id' => $cliente->id]);
    }

    public function ClienteEliminar(Request $request)
    {
        $cliente = Cliente::find($request->id);
        $cliente->delete();
//        Mensaje de exito al eliminar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El cliente fue eliminado correctamente!');
        return redirect()->route('clienteLista');
    }
}
