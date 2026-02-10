<?php

namespace App\Http\Middleware;

use Closure;

class AuthKey
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
        return response()->json(['message' => 'App Key not found', 'token' => $request], 401);
        $token = $request;
        if($token != 'Hola'){
            return response()->json(['message' => 'App Key not found', 'token' => $token], 401);
        }
        return $next($request);
    }
}
