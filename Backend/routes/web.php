<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Rutas públicas
Route::post('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('establecimiento/{id}', [EstablecimientoController::class, 'eliminar'])
        ->name('establecimiento.eliminar');
    Route::get('/establecimientos/crear', function () {
        return view('Establecimiento.Crear');
    })->name('establecimientos.crear');
    Route::post('/establecimientos/asientos', [EstablecimientoController::class, 'guardarAsientos'])
        ->name('establecimientos.asientos.guardar');
    Route::post('/Establecimientos/guardar', [EstablecimientoController::class, 'guardar'])
        ->name('establecimiento.guardar');
    Route::get('/establecimientos/{idEst}', [EstablecimientoController::class, 'mostrar'])
        ->name('establecimiento.mostrar');

    // Ruta principal para el dashboard o listado
    Route::get('/establecimientos', [EstablecimientoController::class, 'index'])
        ->name('establecimientos.index');
});


/* Route::get('/admin/login', function (Request $request) {
    $token = $request->query('token');
    $userId = Cache::pull("admin-login:$token");

    if (!$userId) {
        abort(403, 'Token invÃ¡lido o expirado');
    }

    Auth::loginUsingId($userId);
    // dd("pacoooooooooo");
    return redirect('/establecimientos');
}); */

Route::get('/establecimientos', [EstablecimientoController::class, 'listar'])
    ->middleware('auth')
    ->name('establecimiento.listado');



Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    return response()->json([
        'message' => 'Login correcto',
        'user' => Auth::user()
    ]);
});