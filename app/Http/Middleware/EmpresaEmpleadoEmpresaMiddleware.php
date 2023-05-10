<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EmpresaEmpleadoEmpresaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'empleado' || Auth::user()->role === 'empresa') {
            return $next($request);
        }

        return redirect()->route('easyappointments');
    }
}
