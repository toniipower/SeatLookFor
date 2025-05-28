<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;

// Ruta de prueba para verificar que el archivo se carga correctamente
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

// Rutas de eventos
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/recientes/{id}', [EventoController::class, 'recientes']);


// Rutas protegidas para administradores
Route::middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Bienvenido al panel de administrador.']);
    });

/**
 * Trae el evento, el establecimiento asignado y los asientos asignados
 */

});
Route::get('/eventos/{id}', [EventoController::class, 'mostrar']);



Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
