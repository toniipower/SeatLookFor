<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('api/eventos', [EventoController::class, 'index']);
Route::get('api/eventos/{id}', [EventoController::class, 'show']);
