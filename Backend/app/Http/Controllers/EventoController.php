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




    



public function Evento($id)
{
    // Traemos el evento, el establecimiento y todos los asientos
    $evento = Evento::with(['establecimiento.asientos'])->find($id);

    if (!$evento) {
        return response()->json([
            'message' => 'Evento no encontrado.'
        ], 404);
    }

    // Obtenemos los IDs de los asientos reservados para este evento
    $asientosReservadosIds = $evento->reservas()->pluck('asiento_id')->toArray();

    return response()->json([
        'data' => [
            'id' => $evento->idEve,
            'nombre' => $evento->nombre,
            'fecha' => $evento->fecha,
            'ubicacion' => $evento->ubicacion,
            'establecimiento' => [
                'id' => $evento->establecimiento->idEst,
                'nombre' => $evento->establecimiento->nombre,
                'direccion' => $evento->establecimiento->direccion,
                'asientos' => $evento->establecimiento->asientos->map(function ($asiento) use ($asientosReservadosIds) {
                    return [
                        'id' => $asiento->idAsi,
                        'fila' => $asiento->fila,
                        'columna' => $asiento->columna,
                        'reservado' => in_array($asiento->id, $asientosReservadosIds),
                    ];
                }),
            ]
        ]
    ]);
}

}
