<?php

namespace App\Http\Controllers;

use App\ConversionUnidadMedida;
use App\UnidadMedida;
use Illuminate\Http\Request;

class DevController extends Controller
{
    public function select2()
    {
        return view('dev.prueba');
    }

    public function UnidadesMedidaJSON(Request $request)
    {
        $term = $request->term ?: '';
        $unidadesMedidas = UnidadMedida::where('nombre','like',$term.'%')->get();
//            App\Tag::where('name', 'like', $term.'%')->lists('name', 'id');
        $valid_um = [];
        foreach ($unidadesMedidas as $unidadMedida) {
            $valid_um[] = ['id' => $unidadMedida->id, 'text' => $unidadMedida->nombre];
        }
        return \Response::json($valid_um);
    }

    public function UnidadesConversionJSON(Request $request)
    {
        $unidadMedidaOrigen = $request->umo;
        $unidadMedida = UnidadMedida::find($unidadMedidaOrigen);
        $unidadesEquivalentes = $unidadMedida->conversiones;
        $valid = [];
        $valid[] = ['id' => $unidadMedida->id, 'text' => $unidadMedida->abreviatura, 'data-factor' => 69];
        foreach ($unidadesEquivalentes as $unidadEquivalente)
        {
            $valid[] = ['id' => $unidadEquivalente->unidadDestino->id, 'text' => $unidadEquivalente->unidadDestino->abreviatura, 'data-factor' => 69];
        }
        return \Response::json($valid);
    }

    public function FactorJSON(Request $request)
    {
        $unidadMedidaOrigen = $request->umo;
        $unidadMedidaDestino =$request->umd;
        $factor = ConversionUnidadMedida::where([
            ['unidadMedidaOrigen_id','=', $unidadMedidaOrigen],
            ['unidadMedidaDestino_id', '=', $unidadMedidaDestino],
        ])->first();
        $valid = $factor->factor;
        return \Response::json($valid);
    }
}
