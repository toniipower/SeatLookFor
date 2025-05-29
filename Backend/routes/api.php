<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;                
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventoController;
use Illuminate\Support\Str;


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
});

/**
 * Trae el evento, el establecimiento asignado y los asientos asignados
 */

Route::get('/eventos/{id}', [EventoController::class, 'mostrar']);



Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});
Route::middleware('auth:sanctum', 'admin')->get('/user', function (Request $request) {
    
    return view('Establecimiento.Crear');

});


Route::middleware('auth:sanctum')->post('/admin-token', function (Request $request) {
    $user = $request->user();

    if ($user->role !== 'admin') {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $temporaryToken = base64_encode(Str::random(40)); // ejemplo
    Cache::put("admin-login:$temporaryToken", $user->id, now()->addMinutes(2));

    return response()->json([
        'redirect_url' => url("/admin/login?token=$temporaryToken")
    ]);
});




Route::get('/admin/login', function (Request $request) {
    $token = $request->query('token');

    $userId = Cache::pull("admin-login:$token"); // una vez usado, se borra

    if (!$userId) {
        abort(403, 'Token inválido o expirado');
    }

    Auth::loginUsingId($userId);

    return redirect('/admin/dashboard'); // o donde quieras
});

