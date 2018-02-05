<?php

/*
 * Taken from
 * https://github.com/laravel/framework/blob/5.3/src/Illuminate/Auth/Console/stubs/make/controllers/HomeController.stub
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $rol = \Auth::user()->rol->nombre;
        if ($rol == "Administrador")
        {
            return redirect()->route('administradorInicio');
        }
        elseif ($rol == "Vendedor")
        {
            return redirect()->route('vendedorInicio');
        }
        else
        {
            return redirect()->route('bodegaInicio');
        }
//        return view('adminlte::home');
    }
}