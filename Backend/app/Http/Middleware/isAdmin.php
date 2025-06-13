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

    if (!$user) {
        return $request->expectsJson()
            ? response()->json(['error' => 'No autenticado.'], 401)
            : redirect()->route('login');
    }

    if ($user->admin) {
        return $next($request);
    }

    return $request->expectsJson()
        ? response()->json(['error' => 'Acceso denegado.'], 403)
        : redirect('/'); // o una vista de "acceso denegado"
}    
    
}
