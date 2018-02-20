<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Producto;
use App\TipoAjuste;
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
        $ventas = $cliente->ventas;
        $ventas_con_saldo = [];
        foreach ($ventas as $venta)
        {
            if ($venta->estado_venta_id == 1)
            {
                array_push($ventas_con_saldo,$venta);
            }
        }
        return Response::json($ventas_con_saldo);
    }

    public function TipoAjustesJSON($id)
    {
        $id = intval($id);
        switch ($id)
        {
            case 1:
                $tipo_ajustes = TipoAjuste::whereTipo('ENTRADA')->get();
                break;
            case 2:
                $tipo_ajustes = TipoAjuste::whereTipo('SALIDA')->get();
                break;
        }
        return Response::json($tipo_ajustes);
    }

    public function VersionFormulaJSON($id)
    {
        $producto = Producto::find($id);
        $version = ($producto->formulas()->count()) + 1 ;
        return Response::json($version);
    }
}
