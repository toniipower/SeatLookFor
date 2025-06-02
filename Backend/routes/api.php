<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;                
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use Illuminate\Support\Str;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


// Rutas públicas
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

// Rutas de eventos
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/recientes/{id}', [EventoController::class, 'recientes']);

// Ruta para obtener el token CSRF
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// Rutas de autenticación con middleware de sesión
Route::middleware(['web'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

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



/* Route::post('/login', [AuthController::class, 'login']);


Route::get('/admin/login', function (Request $request) {
    $token = $request->query('token');

    $userId = Cache::pull("admin-login:$token"); // una vez usado, se borra

    if (!$userId) {
        abort(403, 'Token invÃ¡lido o expirado');
    }

    Auth::loginUsingId($userId);

    return redirect('/establecimientos'); // o donde quieras
}); */




