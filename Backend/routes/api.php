<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Support\Facades\Auth;                
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use App\Http\Controllers\ApiAuthenticationController;


// Rutas públicas
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});



Route::post('/login', [ApiAuthenticationController::class, 'login'])->name('login');
Route::middleware('auth:sanctum')->post('/logout', [ApiAuthenticationController::class, 'logout']);


// Rutas de eventos
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/recientes/{id}', [EventoController::class, 'recientes']);
Route::get('/zonas-por-establecimiento/{idEst}', [EventoController::class, 'obtenerZonas']);

// Ruta para obtener el token CSRF
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

/* // Rutas de autenticación API
Route::post('/login', [AuthController::class, 'login'])->middleware('api');
Route::post('/register', [AuthController::class, 'register'])->middleware('api');
 */
// Rutas protegidas API
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Rutas de comentarios
    Route::post('/asientos/{id}/comentar', [ComentarioController::class, 'comentar']);
    Route::get('/asientos/{id}/comentarios', [ComentarioController::class, 'getComentarios']);

    Route::post('/reservas', [ReservaController::class, 'crearReserva']);
});

/**
 * Trae el evento, el establecimiento asignado y los asientos asignados
 */
Route::get('/eventos/{id}', [EventoController::class, 'mostrar']);

/**
 * Trae los comentarios de un evento
 */
Route::get('/eventos/{id}/comentarios', [EventoController::class, 'comentariosPorEvento']);

/**
 * 
 */

