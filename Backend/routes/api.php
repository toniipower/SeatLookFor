<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\EventoController;
=======
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
>>>>>>> eca7604983edcef35320f56e96c2c0a0152d6d3c

// Ruta de prueba para verificar que el archivo se carga correctamente
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

// Rutas de eventos
Route::get('/eventos', [EventoController::class, 'index']);
Route::get('/recientes/{id}', [EventoController::class, 'recientes']);
