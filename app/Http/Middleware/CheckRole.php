<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if($request->user()->isSuperAdmin()){
            return $next($request);
        }

        if($role === 'colaborator' && $request->user()->isAdmin()){
            return $next($request);
        }
        
        if ($request->user() &&  !$request->user()->hasRole($role)) {
            return redirect()->route('proyectos.index')->with('error', 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}
