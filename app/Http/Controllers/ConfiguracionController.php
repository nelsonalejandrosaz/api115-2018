<?php

namespace App\Http\Controllers;

use App\Ajuste;
use App\Categoria;
use App\Movimiento;
use App\Producto;
use App\TipoProducto;
use App\UnidadMedida;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ConfiguracionController extends Controller
{
    public function ImportarDatos()
    {
        return view('configuracion.importarDatos');
    }

    public function ImportarDatosPost(Request $request)
    {
        $this->validate($request, [
            'archivoXLSX' => 'required',
        ]);

        Excel::load(Input::file('archivoXLSX'), function($reader) {
            // Getting all results
            $results = $reader->get();
            $productos = $results[0];
            foreach ($productos as $producto)
            {
//                Inicio para guardar inventario
                $existenciaMin = 0;
                $existenciaMax = 100;
                $costo = 0.00;
                $precio = 0.00;
                $margenGanancia = 0;
                $unidadMedida = UnidadMedida::whereNombre($producto->unidad_de_medida)->first();
//                dd($unidadMedida);
                $tipoProducto = TipoProducto::whereNombre($producto->tipo_de_producto)->first();
                $categoria = Categoria::whereNombre($producto->categoria)->first();
                $productoDB = Producto::create([
                    'nombre' => $producto->nombre_producto,
                    'tipo_producto_id' => $tipoProducto->id,
                    'unidad_medida_id' => $unidadMedida->id,
                    'categoria_id' => $categoria->id,
                    'existenciaMin' => $existenciaMin,
                    'existenciaMax' => $existenciaMax,
                    'costo' => $costo,
                    'precio' => $precio,
                    'margenGanancia' => $margenGanancia,
                ]);
                if ($producto->codigo == null)
                {
//                  Asignacion del codigo del producto
                    $ids = $productoDB->id;
                    $ids = str_pad($ids, 5, '0', STR_PAD_LEFT);
                    $codigo = $productoDB->tipoProducto->codigo . $ids;
                    $productoDB->codigo = $codigo;
                    $productoDB->update();
                } else
                {
//                  Asignacion del codigo del producto
                    $productoDB->codigo = $producto->codigo;
                    $productoDB->update();
                }
//                Asignacion de inventario inicial

//              Variables
                $cantidadAjuste = ($producto->existencias == null) ? 0 : $producto->existencias;
                $valorUnitarioAjuste = 0.00;
                // Se crea el movimiento
                $movimiento = Movimiento::create([
                    'producto_id' => $productoDB->id,
                    'tipo_movimiento_id' => 3,
                    'fecha' => Carbon::now()->format('Y-d-m'),
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'cantidadExistencia' => $cantidadAjuste,
                    'costoUnitarioExistencia' => $valorUnitarioAjuste,
                    'costoTotalExistencia' => $cantidadAjuste * $valorUnitarioAjuste,
                ]);
                // Se crea el ajuste de entrada
                $ajuste = Ajuste::create([
                    'movimiento_id' => $movimiento->id,
                    'tipo_ajuste_id' => 1,
                    'detalle' => 'Ajuste de entrada por inicio de inventario ',
                    'fechaIngreso' => Carbon::now()->format('Y-d-m'),
                    'cantidadAjuste' => $cantidadAjuste,
                    'valorUnitarioAjuste' => $valorUnitarioAjuste,
                    'realizado_id' => Auth::user()->id,
                    'cantidadAnterior' => 0,
                    'valorUnitarioAnterior' => $productoDB->precio,
                ]);
                // Se actualiza la cantidad de producto despues de la entrada
                $productoDB->cantidadExistencia = $movimiento->cantidadExistencia;
                $productoDB->costo = $movimiento->costoUnitarioExistencia;
                $productoDB->save();
            }
            dd('bien');
        });

    }

}
