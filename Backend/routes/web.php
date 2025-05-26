<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstablecimientoController;

/* Route::get('/', function () {
    return view('welcome');
}); */

/* Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::post('/', [EstablecimientoController::class, 'listar'])->name('establecimiento.listado');
Route::get('establecimiento', [EstablecimientoController::class, 'listar'])->name('establecimiento.listado');
Route::post('establecimiento/{$id}', [EstablecimientoController::class, 'eliminar'])->name('establecimiento.eliminar');


Route::get('/establecimientos/crear', function () {
    return view('Establecimiento.Crear');
})->name('establecimientos.crear');

Route::post('/establecimientos/asientos', [EstablecimientoController::class, 'guardarAsientos'])
    ->name('establecimientos.asientos.guardar');


Route::post('/Establecimientos/guardar', [EstablecimientoController::class, 'guardar'])->name('establecimiento.guardar');


Route::get('/establecimientos/{idEst}', [EstablecimientoController::class, 'mostrar'])->name('establecimiento.mostrar');



require __DIR__.'/auth.php';
