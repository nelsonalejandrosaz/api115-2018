<?php

namespace App\Http\Controllers;

use App\ConversionUnidadMedida;
use App\Producto;
use App\UnidadMedida;
use Illuminate\Http\Request;
use Response;

class DevController extends Controller
{
    public function select2()
    {
        return view('configuracion.pruebas');
    }

    public function UnidadesMedidaJSON(Request $request)
    {
        $term = $request->term ?: '';
        $unidades_medidas = UnidadMedida::where('nombre','like',$term.'%')->get();
        $valid_um = [];
        foreach ($unidades_medidas as $unidad_medida) {
            $valid_um[] = ['id' => $unidad_medida->id, 'text' => $unidad_medida->nombre];
        }
        return Response::json($valid_um);
    }

    public function UnidadesConversionJSON(Request $request)
    {
        $unidad_medida_origen = $request->umo;
        $unidad_medida = UnidadMedida::find($unidad_medida_origen);
        $unidades_equivalentes = $unidad_medida->conversiones;
//        dd($unidades_equivalentes[0]);
        $valid = [];
        $valid[] = ['id' => $unidad_medida->id, 'text' => $unidad_medida->abreviatura, 'data-factor' => 69];
        foreach ($unidades_equivalentes as $unidad_equivalente)
        {
            $valid[] = ['id' => $unidad_equivalente->unidad_destino->id, 'text' => $unidad_equivalente->unidad_destino->abreviatura, 'data-factor' => 69];
        }
        return Response::json($valid);
    }

    public function FactorJSON(Request $request)
    {
        $unidad_medida_origen = $request->umo;
        $unidad_medida_destino =$request->umd;
        $factor = ConversionUnidadMedida::where([
            ['unidad_medida_origen_id','=', $unidad_medida_origen],
            ['unidad_medida_destino_id', '=', $unidad_medida_destino],
        ])->first();
        $valid = $factor->factor;
        return Response::json($valid);
    }

    public function ProductosPresentacionesJSON(Request $request, $id)
    {
        $producto = Producto::find($id);
        $precios = $producto->precios;
        return Response::json($precios);
    }
}
