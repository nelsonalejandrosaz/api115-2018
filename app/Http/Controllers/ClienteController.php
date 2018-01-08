<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\User;
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
        $vendedores = User::whereRolId(3)->get();
        return view('cliente.clienteVer')
            ->with(['cliente' => $cliente])
            ->with(['vendedores' => $vendedores]);
    }

    public function ClienteNuevo(Request $request)
    {
        $vendedores = User::whereRolId(3)->get();
        return view('cliente.clienteNuevo')
            ->with(['vendedores' => $vendedores]);
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
            'telefono2',
            'nit',
            'nrc',
            'vendedor_id'
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
        $vendedores = User::whereRolId(3)->get();
        return view('cliente.clienteEditar')
            ->with(['cliente' => $cliente])
            ->with(['vendedores' => $vendedores]);
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
            'telefono2',
            'nrc',
            'nit'
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
