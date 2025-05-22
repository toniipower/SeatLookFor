<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
               $user = Auth::user();

        // Si no está autenticado, regresa un error
        if (!$user) {
            return response()->json(['error' => 'No autenticado.'], 401);
        }

        // Si es admin, continúa al panel de administración
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Si no es admin, redirige a la aplicación Angular
        return response()->json(['redirect' => '/angular-app'], 403);
    }

    
    
}
