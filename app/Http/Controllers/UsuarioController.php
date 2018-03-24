<?php

namespace App\Http\Controllers;

use App\Rol;
use App\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function UsuarioLista()
    {
        $usuarios = User::all();
        return view('usuario.usuarioLista')
            ->with(['usuarios' => $usuarios]);
    }

    public function UsuarioNuevo()
    {
        $roles = Rol::all();
        return view('usuario.usuarioNuevo')
            ->with(['roles' => $roles]);
    }

    public function UsuarioNuevoPost(Request $request)
    {
        $usuario = User::create([
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'username' => $request->input('username'),
            'telefono' => $request->input('telefono'),
            'rol_id' => $request->input('rol_id'),
            'ruta_imagen' => 'man.png',
        ]);
        //        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El usuario fue agregado correctamente!');
        return redirect()->route('usuarioVer', ['id' => $usuario->id]);

    }

    public function UsuarioVer($id)
    {
        $usuario = User::find($id);
        return view('usuario.usuarioVer')
            ->with(['usuario' => $usuario]);
    }

    public function UsuarioEditar($id)
    {
        $usuario = User::find($id);
        $roles = Rol::all();
        return view('usuario.usuarioEditar')
            ->with(['roles' => $roles])
            ->with(['usuario' => $usuario]);
    }

    public function UsuarioEditarPut(Request $request, $id)
    {
        $usuario = User::find($id);
        $usuario->update([
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'telefono' => $request->input('telefono'),
            'rol_id' => $request->input('rol_id'),
            'ruta_imagen' => 'man.png',
        ]);
        if ($request->input('password') != null)
        {
            $usuario->password = bcrypt($request->input('password'));
            $usuario->save();
        }
        //        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El usuario fue actualizado correctamente!');
        return redirect()->route('usuarioVer', ['id' => $usuario->id]);
    }
}
