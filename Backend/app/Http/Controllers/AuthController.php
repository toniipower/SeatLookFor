<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('web');
    }

    public function login(Request $request)
    {
        try {
            Log::info('Intento de login', ['email' => $request->email]);

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');
            
            // Intentar autenticar usando el modelo Usuario
            if (!Auth::guard('web')->attempt($credentials)) {
                Log::warning('Credenciales inválidas', ['email' => $request->email]);
                return response()->json([
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            $user = Auth::guard('web')->user();
            Log::info('Login exitoso', ['user_id' => $user->idUsu]);

            return response()->json([
                'user' => $user,
                'message' => 'Login exitoso'
            ]);

        } catch (ValidationException $e) {
            Log::error('Error de validación en login', [
                'errors' => $e->errors(),
                'email' => $request->email
            ]);
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en login', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return response()->json([
                'message' => 'Logout exitoso'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en logout', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al cerrar sesión'
            ], 500);
        }
    }

    public function user(Request $request)
    {
        try {
            if (!Auth::guard('web')->check()) {
                return response()->json([
                    'message' => 'No autenticado'
                ], 401);
            }

            return response()->json(Auth::guard('web')->user());
        } catch (\Exception $e) {
            Log::error('Error al obtener usuario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al obtener usuario'
            ], 500);
        }
    }

    public function handleAdminRedirect(Request $request)
    {
        $token = $request->query('token');
        
        if (!$token) {
            return redirect()->route('login');
        }

        $user = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        
        if (!$user) {
            return redirect()->route('login');
        }

        $user = $user->tokenable;

        if (!$user || !$user->admin) {
            return redirect()->route('login');
        }

        Auth::login($user);
        
        return redirect()->route('establecimiento.listado');
    }
}
