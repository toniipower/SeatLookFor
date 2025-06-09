<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Auth;                
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


// Rutas públicas
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

// Rutas de eventos
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/recientes/{id}', [EventoController::class, 'recientes']);
Route::get('/zonas-por-establecimiento/{idEst}', [EventoController::class, 'obtenerZonas']);

// Ruta para obtener el token CSRF
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Rutas de autenticación con middleware de sesión
Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

// Ruta de registro sin middleware web
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    
    // Rutas de administrador
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', function () {
            return response()->json(['message' => 'Bienvenido al panel de administrador.']);
        });
    });
});

/**
 * Trae el evento, el establecimiento asignado y los asientos asignados
 */
Route::get('/eventos/{id}', [EventoController::class, 'mostrar']);


/**
 * Permite a los usuarios comentar sobre los asientos
 */

Route::middleware('auth:sanctum')->post('/asientos/{id}/comentar', [ComentarioController::class, 'comentar']);
