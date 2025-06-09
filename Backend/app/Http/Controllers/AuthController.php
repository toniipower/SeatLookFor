<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class AuthController extends BaseController
{
    public function __construct()
    {
        // Quitamos el middleware web para el registro
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
            
            if (!Auth::attempt($credentials)) {
                Log::warning('Credenciales inválidas', ['email' => $request->email]);
                return response()->json([
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            $user = Auth::user();
            
            // Si es una petición API (desde el frontend)
            if ($request->expectsJson()) {
                // Revocar tokens anteriores
                $user->tokens()->delete();
                // Crear nuevo token
                $token = $user->createToken('auth-token')->plainTextToken;
                
                Log::info('Login API exitoso', ['user_id' => $user->idUsu]);

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Login exitoso'
                ]);
            }
            
            // Si es una petición web (desde el backend)
            Log::info('Login web exitoso', ['user_id' => $user->idUsu]);
            
            if ($user->admin) {
                return redirect()->route('dashboard');
            }
            
            return redirect()->route('home');

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
            // Si es una petición API
            if ($request->expectsJson()) {
                $request->user()->currentAccessToken()->delete();
            } else {
                // Si es una petición web
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
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

    public function register(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:usuario',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'estado' => true,
                'admin' => false
            ]);

            event(new Registered($usuario));

            return response()->json([
                'message' => 'Usuario registrado exitosamente',
                'user' => $usuario
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en registro', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Error al registrar usuario'
            ], 500);
        }
    }
}
