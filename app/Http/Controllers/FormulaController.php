<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;

class FormulaController extends Controller
{
    public function FormulaLista()
    {
        $productos = Producto::all();
        return view('formula.formulaLista')->with(['productos' => $productos]);
    }
}
