<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        Log::info('Intentando autenticar usuario');
        
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        Log::info('Usuario autenticado:', ['id' => $user->id, 'admin' => $user->admin]);

        if ($user->admin) {
            Log::info('Usuario es admin, redirigiendo a establecimiento');
            return redirect()->route('establecimiento.listado');
        } else {
            Log::info('Usuario normal, redirigiendo a landing page');
            return redirect('/');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(config('app.frontend_url', 'https://seatlook.duckdns.org'));
    }
}
