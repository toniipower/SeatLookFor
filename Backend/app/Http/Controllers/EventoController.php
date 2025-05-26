<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Evento::all(), 200);
    }

    public function recientes($id)
    {
        $recientes = Evento::orderBy('fecha', 'desc')
            ->take($id)
            ->get();
    
        if ($recientes->isEmpty()) {
            return response()->json(['message' => 'No se encontraron eventos'], 404);
        }
    
        return response()->json(['recientes' => $recientes], 200);
    
    }



    public function mostrar($id)
    {
        $evento = Evento::with(['establecimiento.asientos'])->findOrFail($id);

        return response()->json([
            'evento' => [
                'idEve' => $evento->idEve,
                'titulo' => $evento->titulo,
                'fecha' => $evento->fecha,
                'valoracion' => $evento->valoracion,
                'descripcion' => $evento->descripcion,
            ],
            'establecimiento' => [
                'idEst' => $evento->establecimiento->idEst,
                'nombre' => $evento->establecimiento->nombre,
                'ubicacion' => $evento->establecimiento->ubicacion,
                'imagen' => $evento->establecimiento->imagen,
                'tipo' => $evento->establecimiento->tipo,
                'asientos' => $evento->establecimiento->asientos->map(function ($a) {
                    return [
                        'idAsi' => $a->idAsi,
                        'zona' => $a->zona,
                        'estado' => $a->estado,
                        'ejeX' => $a->ejeX,
                        'ejeY' => $a->ejeY,
                        'precio' => $a->precio,
                    ];
                }),
            ],
        ]);
    }
}



