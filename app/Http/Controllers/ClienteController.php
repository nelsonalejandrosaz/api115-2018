<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\EstadoVenta;
use App\Municipio;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Monolog\Handler\IFTTTHandler;

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
        $vendedores = User::whereRolId(2)->get();
        $municipios = Municipio::all();
        return view('cliente.clienteVer')
            ->with(['cliente' => $cliente])
            ->with(['vendedores' => $vendedores])
            ->with(['municipios' => $municipios]);
    }

    public function ClienteNuevo(Request $request)
    {
        $vendedores = User::whereRolId(2)->get();
        $municipios = Municipio::all();
        return view('cliente.clienteNuevo')
            ->with(['vendedores' => $vendedores])
            ->with(['municipios' => $municipios]);
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
            'nombre_alternativo',
            'nombre_contacto',
            'direccion',
            'telefono_1',
            'telefono_2',
            'correo',
            'nit',
            'nrc',
            'vendedor_id',
            'giro',
            'municipio_id'
        ));
//        Mensaje de exito al guardar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El cliente fue agregado correctamente!');
        return redirect()->route('clienteVer', ['id' => $cliente->id]);
    }

    public function ClienteEditar(Request $request)
    {
        $cliente = Cliente::find($request->id);
        $vendedores = User::whereRolId(2)->get();
        $municipios = Municipio::all();
        return view('cliente.clienteEditar')
            ->with(['cliente' => $cliente])
            ->with(['vendedores' => $vendedores])
            ->with(['municipios' => $municipios]);
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
            'nombre_alternativo',
            'nombre_contacto',
            'direccion',
            'telefono_1',
            'telefono_2',
            'correo',
            'nrc',
            'nit',
            'giro',
            'vendedor_id',
            'municipio_id'
        ));
//        Mensaje de exito al modificar
        session()->flash('mensaje.tipo', 'success');
        session()->flash('mensaje.icono', 'fa-check');
        session()->flash('mensaje.contenido', 'El cliente fue modificado correctamente!');
        return redirect()->route('clienteVer', ['id' => $cliente->id]);
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

    public function ClienteSaldoLista()
    {
        $clientes = Cliente::where('saldo', '>', 0)->get();
        $estado_venta = EstadoVenta::whereCodigo('PP')->first();
        $documentos_pendientes = 0;
        foreach ($clientes as $cliente) {
            $ordenes = $cliente->ordenes_pedidos;
//            dd(isset($ordenes[0]->venta));
            foreach ($ordenes as $orden) {
                $isset = isset($orden->venta);
                if ($isset) {
                    if ($orden->venta->estado_venta->id == $estado_venta->id) {
                        $documentos_pendientes++;
                    }
                }
            }
            $cliente->documentos_pendientes = $documentos_pendientes;
            $documentos_pendientes = 0;
        }
        return view('cliente.clienteSaldoLista')->with(['clientes' => $clientes]);
    }

    public function ClienteSaldoVer($id)
    {
        $cliente = Cliente::find($id);
        return view('cliente.clienteSaldoVer')->with(['cliente' => $cliente]);
    }

    public function ClienteVentaLista()
    {
        $clientes = Cliente::all();
        foreach ($clientes as $cliente)
        {
            $ventas = $cliente->ventas;
            $numero_ventas = $ventas->count();
            $numero_ventas_pendientes = 0;
            foreach ($ventas as $venta)
            {
                if ($venta->estado_venta_id == 1)
                {
                    $numero_ventas_pendientes ++;
                }
            }
            $cliente->numero_ventas = $numero_ventas;
            $cliente->numero_ventas_pendientes = $numero_ventas_pendientes;
        }
        return view('cliente.clienteVentaLista')->with(['clientes' => $clientes]);
    }
}
