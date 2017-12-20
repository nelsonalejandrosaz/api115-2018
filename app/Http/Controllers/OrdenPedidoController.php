<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Municipio;
use App\OrdenPedido;
use App\Producto;
use Illuminate\Http\Request;

class OrdenPedidoController extends Controller
{
    public function OrdenPedidoLista()
    {
        $ordenesPedidos = OrdenPedido::all();
        return view('ordenPedido.ordenPedidoLista')->with(['ordenesPedidos' => $ordenesPedidos]);
    }

    public function OrdenPedidoNueva()
    {
        $productosTodos = Producto::all();
        $productos = array();
        foreach ($productosTodos as $productoTodo) {
            if ($productoTodo->cantidad > 0) {
                array_push($productos, $productoTodo);
            }
        }
        $clientes = Cliente::all();
        $municipios = Municipio::all();
        return view('ordenPedido.ordenPedidoNueva')->with(['productos' => $productos])->with(['clientes' => $clientes])->with(['municipios' => $municipios]);
    }
}
