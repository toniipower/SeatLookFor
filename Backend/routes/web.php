<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Debug de rutas
Route::get('/debug-routes', function() {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'methods' => $route->methods(),
            'middleware' => $route->middleware(),
        ];
    });
    dd($routes->toArray());
});

Route::middleware(['web', 'auth'])->get('/test-auth', function () {
    return auth()->check() ? 'Autenticado' : 'No autenticado';
});


// Rutas públicas
Route::get('/login', function () {
    return redirect('http://localhost:4200/login');
})->name('login');



Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/* // Rutas de autenticación
require __DIR__.'/auth.php'; */

// Rutas protegidas
/* Route::middleware('auth')->group(function () { */
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /************************************************************************************* */
    /*                              Establecimiento
    /************************************************************************************* */
    Route::middleware(['web','auth'])->group(function () {
    Route::get('/establecimientos', [EstablecimientoController::class, 'listar'])->name('establecimiento.listado');

    });
    Route::get('/establecimientos/crear', function () {
        return view('Establecimiento.Crear');
    })->name('establecimientos.crear');
    Route::post('/establecimientos/guardar', [EstablecimientoController::class, 'guardar'])->name('establecimiento.guardar');
    Route::post('/establecimientos/asientos', [EstablecimientoController::class, 'guardarAsientos'])->name('establecimientos.asientos.guardar');
    Route::get('/establecimientos/{idEst}', [EstablecimientoController::class, 'mostrar'])->name('establecimiento.mostrar');
    Route::post('/establecimientos/{id}/eliminar', [EstablecimientoController::class, 'eliminar'])->name('establecimiento.eliminar');
    Route::post('/establecimientos/eliminar/{id}', [EstablecimientoController::class, 'eliminar'])->name('establecimiento.eliminar');

    
    /************************************************************************************* */
    /*                              Eventos
    /************************************************************************************* */
    Route::get('/eventos', [EventoController::class, 'listar'])->name('eventos.listado');
    Route::get('/eventos/crear', [EventoController::class, 'formularioCrear'])->name('eventos.crear');
    Route::post('/eventos/guardar', [EventoController::class, 'guardar'])->name('eventos.guardar');
    Route::get('/eventos/ver/{idEve}', [EventoController::class, 'ver'])->name('eventos.ver');
    Route::get('/zonas-por-establecimiento/{idEst}', [EventoController::class, 'obtenerZonas'])->name('zonas.porEstablecimiento');
    Route::post('/eventos/eliminar/{id}', [EventoController::class, 'eliminar'])->name('eventos.eliminar');
    Route::post('/eventos/estado/{id}', [EventoController::class, 'cambiarEstado'])->name('eventos.estado');

/* }); */

/* Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    return response()->json([
        'message' => 'Login correcto',
        'user' => Auth::user()
    ]);
}); */


Route::post('/login', [AuthenticatedSessionController::class, 'store']);
