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

    public function CuentasPorCobrar()
    {
        $clientes = Cliente::where('saldo', '>', 0)->get();
        $documentos_pendientes = 0;
        foreach ($clientes as $cliente)
        {
            $ventas = $cliente->ventas->where('estado_venta_id','=',1);
            $documentos_pendientes = $ventas->count();
            $cliente->documentos_pendientes = $documentos_pendientes;
            $documentos_pendientes = 0;
        }
        return view('cliente.clienteSaldoLista')->with(['clientes' => $clientes]);
    }

    public function CuentasPorCobrarVer($id)
    {
        $cliente = Cliente::find($id);
        $cxc = $cliente->ventas->where('estado_venta_id','=',1);
        $total_saldo_pendiente = $cxc->sum('saldo');
        return view('cliente.cuentasPorCobrarVer')
            ->with(['cliente' => $cliente])
            ->with(['cxc' => $cxc])
            ->with(['total_saldo_pendiente' => $total_saldo_pendiente]);
    }

    public function ClienteVentaLista()
    {
        $clientes = Cliente::all();
        foreach ($clientes as $cliente)
        {
            $numero_ventas = $cliente->ventas->count();
            $numero_ventas_pendientes = $cliente->ventas->where('estado_venta_id','=',1)->count();
            $cliente->numero_ventas = $numero_ventas;
            $cliente->numero_ventas_pendientes = $numero_ventas_pendientes;
        }
        return view('cliente.clienteVentaLista')
            ->with(['clientes' => $clientes]);
    }

    public function VentasPorClienteVer($id)
    {
        $cliente = Cliente::find($id);
        $total_saldo = $cliente->ventas->sum('venta_total_con_impuestos');
        $cxc = $cliente->ventas->where('estado_venta_id','=',1);
        $total_saldo_pendiente = $cxc->sum('saldo');
        return view('cliente.ventasPorClienteVer')
            ->with(['cliente' => $cliente])
            ->with(['total_saldo' => $total_saldo])
            ->with(['total_saldo_pendiente' => $total_saldo_pendiente]);
    }
}
