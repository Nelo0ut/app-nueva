<?php

namespace App\Http\Middleware;

use Closure;

class PermisoEntidad
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
        if($this->permiso())
            return $next($request);

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect('/')->with('error', 'No tiene permiso');
    }

    private function permiso()
    {
        //dd(session()->get('role_id') == 3 || session()->get('role_id') == 4);
        return session()->get('role_id') == 3 || session()->get('role_id') == 4;
    }
}
