<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComentarioController extends Controller
{
    public function comentar(Request $request, $idAsi)
    {
        try {
            Log::info('Intentando crear comentario', [
                'idAsi' => $idAsi,
                'request_data' => $request->all()
            ]);

            if (!$request->user()) {
                Log::error('Usuario no autenticado');
                return response()->json([
                    'mensaje' => 'Usuario no autenticado'
                ], 401);
            }

            $request->validate([
                'opinion' => 'required|string',
                'valoracion' => 'required|numeric|min:0|max:5',
                'foto' => 'nullable|string',
            ]);

            $usuario = $request->user();
            Log::info('Usuario autenticado', ['idUsu' => $usuario->idUsu]);

            // Verificar que el asiento existe
            $asiento = Asiento::findOrFail($idAsi);
            Log::info('Asiento encontrado', ['idAsi' => $asiento->idAsi]);

            // Procesar la imagen si existe
            $fotoPath = null;
            if ($request->has('foto') && $request->foto) {
                // Decodificar la imagen base64
                $image_parts = explode(";base64,", $request->foto);
                $image_base64 = base64_decode($image_parts[1]);
                
                // Generar nombre único para la imagen
                $imageName = Str::random(40) . '.jpg';
                
                // Guardar la imagen en storage
                Storage::disk('public')->put('images/comentarios/' . $imageName, $image_base64);
                
                // Guardar la ruta en la base de datos
                $fotoPath = 'storage/images/comentarios/' . $imageName;
            }

          $comentario = Comentario::create([
                'idUsu' => $usuario->idUsu,
                'idAsi' => $idAsi,
                'opinion' => $request->opinion,
                'valoracion' => $request->valoracion,
                'foto' => $fotoPath,
                'idEve' => null
            ]);

            Log::info('Comentario guardado exitosamente', ['idCom' => $comentario->idCom ?? 'nuevo']);

            return response()->json([
                'mensaje' => 'Comentario guardado correctamente',
                'comentario' => $comentario
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Asiento no encontrado', ['idAsi' => $idAsi]);
            return response()->json([
                'mensaje' => 'El asiento no existe'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación', ['errors' => $e->errors()]);
            return response()->json([
                'mensaje' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar comentario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'mensaje' => 'Error al guardar el comentario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getComentarios($idAsi)
    {
        try {
            $asiento = Asiento::findOrFail($idAsi);
            $comentarios = Comentario::with('usuario')
                ->where('idAsi', $idAsi)
                ->get();

            return response()->json($comentarios);
        } catch (\Exception $e) {
            Log::error('Error al obtener comentarios', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'mensaje' => 'Error al obtener los comentarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
