<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Asiento;
use App\Models\Reserva;
use App\Models\Establecimiento;
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
    try {
        // Cargar evento con asientos y comentarios (desde tabla asientos_eventos)
        $evento = Evento::with(['asientos.usuariosComentaron', 'establecimiento'])->findOrFail($id);

        // Obtener los ID de los asientos reservados en este evento
        $asientosReservadosIds = Reserva::where('idEve', $id)->pluck('idAsi')->toArray();

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
            ],
            'asientos' => $evento->asientos->map(function ($a) use ($asientosReservadosIds) {
                return [
                    'idAsi' => $a->idAsi,
                    'zona' => $a->zona,
                    'estado' => in_array($a->idAsi, $asientosReservadosIds) ? 'reservado' : 'libre',
                    'ejeX' => $a->ejeX,
                    'ejeY' => $a->ejeY,
                    'precio' => $a->pivot->precio ?? null,
                    'comentarios' => $a->usuariosComentaron->map(function ($u) {
                        return [
                            'idUsu' => $u->idUsu,
                            'nombre' => $u->nombre,
                            'opinion' => $u->pivot->opinion,
                            'valoracion' => $u->pivot->valoracion,
                            'foto' => $u->pivot->foto,
                        ];
                    }),
                ];
            }),
        ]);

    } catch (\Exception $e) {
        // Si ocurre cualquier error, lo atrapamos
        return response()->json([
            'error' => 'Error al cargar los datos del evento.',
            'mensaje' => $e->getMessage()
        ], 500);
    }
}


    public function formularioCrear()
    {
        $establecimientos = Establecimiento::all();
        return view('eventos.CrearEvento', compact('establecimientos'));
    }

    public function obtenerZonas($idEst)
    {
        // Supongamos que 'nombreZona' está en la tabla asiento
        $asientos = Asiento::where('idEst', $idEst)->get();

        $zonasAgrupadas = $asientos->groupBy('nombreZona');

        $zonasFinales = [];

        foreach ($zonasAgrupadas as $nombreZona => $grupo) {
            // Crear zona si no existe aún
            $zona = \App\Models\Zona::firstOrCreate([
                'nombre' => $nombreZona,
                'idEst' => $idEst,
            ]);

            $zonasFinales[] = [
                'idZona' => $zona->idZona,
                'nombre' => $zona->nombre,
            ];
        }

        return response()->json($zonasFinales);
    }
}
