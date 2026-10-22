<?php

namespace App\Http\Middleware;

use Closure;

class AccesoElementosPresupuestadosMiddleware
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
        if (auth()->user() != null) {
            if (auth()->user()->canDo('AccesoModulo', 'Elementos Presupuestados')) {
                return $next($request);
            }
        }

        return redirect()->route('Dashboard');
    }
}
