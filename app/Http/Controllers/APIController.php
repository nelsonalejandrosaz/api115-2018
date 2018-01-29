<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use Illuminate\Http\Request;
use Response;

class APIController extends Controller
{
    public function ProductosPresentacionesJSON($id)
    {
        $producto = Producto::find($id);
        $precios = $producto->precios()->oldest()->get();
        foreach ($precios as $precio)
        {
            $unidad_medida_nombre = $precio->unidad_medida->abreviatura;
            $precio->unidad_medida_nombre = $unidad_medida_nombre;
        }
        return Response::json($precios);
    }

    public function ClientesVentasPendientesJSON($id)
    {
        $cliente = Cliente::find($id);
        $ordenes = $cliente->ordenes_pedidos;
        $ventas_con_saldo = [];
        foreach ($ordenes as $orden)
        {
            if ($orden->venta->saldo > 0)
            {
                array_push($ventas_con_saldo,$orden->venta);
            }
        }
        return Response::json($ventas_con_saldo);
    }
}
