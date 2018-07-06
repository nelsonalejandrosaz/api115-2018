<?php

namespace App\Http\Middleware;

use Closure;

class VendedorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Auth::user()->rol->nombre == 'Vendedor' || \Auth::user()->rol->nombre == 'Administrador')
        {
            return $next($request);
        } else
        {
            abort(403);
        }
    }
}
