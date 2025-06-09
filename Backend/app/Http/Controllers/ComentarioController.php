<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Comentario;
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

        $usuario = $request->user();

        // Verificar que el asiento existe
        $asiento = Asiento::findOrFail($idAsi);

        // Crear o actualizar comentario del usuario sobre ese asiento
        Comentario::updateOrCreate(
            [
                'idUsu' => $usuario->idUsu,
                'idAsi' => $idAsi,
            ],
            [
                'opinion' => $request->opinion,
                'valoracion' => $request->valoracion,
                'foto' => $request->foto,
            ]
        );

        return response()->json([
            'mensaje' => 'Comentario guardado correctamente',
        ]);
    }
}
