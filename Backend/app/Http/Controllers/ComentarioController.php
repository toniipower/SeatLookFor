<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Asiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opinion' => 'required|string',
            'valoracion' => 'required|numeric|min:1|max:5',
            'foto' => 'nullable|image|max:2048', // máximo 2MB
            'idAsi' => 'required|exists:asiento,idAsi',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['idUsu'] = auth()->id();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/comentarios');
            $data['foto'] = Storage::url($path);
        }

        $comentario = Comentario::create($data);

        return response()->json($comentario, 201);
    }

    public function getComentariosAsiento($idAsiento)
    {
        $comentarios = Comentario::with('usuario')
            ->where('idAsi', $idAsiento)
            ->get();

        return response()->json($comentarios);
    }

    public function destroy($idComentario)
    {
        $comentario = Comentario::findOrFail($idComentario);
        
        // Verificar que el usuario es el propietario del comentario
        if ($comentario->idUsu !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Eliminar la imagen si existe
        if ($comentario->foto) {
            $path = str_replace('/storage/', '', $comentario->foto);
            Storage::delete('public/' . $path);
        }

        $comentario->delete();
        return response()->json(null, 204);
    }
} 