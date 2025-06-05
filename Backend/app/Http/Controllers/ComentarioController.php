<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function comentar(Request $request, $idAsi)
{
    $request->validate([
        'opinion' => 'required|string',
        'valoracion' => 'required|numeric|min:0|max:5',
        'foto' => 'nullable|string',
    ]);

    $usuario = $request->user(); // Usuario autenticado

    // Verificar que el asiento existe
    $asiento = Asiento::findOrFail($idAsi);

    // Insertar o actualizar el comentario
    $usuario->asientosComentados()->syncWithoutDetaching([
        $idAsi => [
            'opinion' => $request->opinion,
            'valoracion' => $request->valoracion,
            'foto' => $request->foto,
        ],
    ]);

    return response()->json([
        'mensaje' => 'Comentario guardado correctamente',
    ]);
}

}
