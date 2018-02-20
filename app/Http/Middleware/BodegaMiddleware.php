<?php

namespace App\Http\Middleware;

use Closure;

class BodegaMiddleware
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
        if (\Auth::user()->rol->nombre == 'Bodeguero' || \Auth::user()->rol->nombre == 'Administrador')
        {
            return $next($request);
        } else
        {
            abort(403);
        }
    }
}
