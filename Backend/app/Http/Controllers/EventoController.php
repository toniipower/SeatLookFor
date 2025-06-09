<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Evento;
use App\Models\Asiento;
use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Models\Establecimiento;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreEventoRequest;
use Illuminate\Support\Facades\Validator;
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

    public function listar()
    {
        $eventos = Evento::with('establecimiento')->paginate(6);
        return view('Evento.listadoEventos', compact('eventos'));
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
        $establecimientos = Establecimiento::with("asientos")->get();;
        return view('Evento.CrearEvento', compact('establecimientos'));
    }

    public function obtenerZonas($idEst)
{
    $asientos = Asiento::where('idEst', $idEst)->get();

    $zonasAgrupadas = $asientos->groupBy('nombreZona');
    $zonasFinales = [];

    foreach ($zonasAgrupadas as $nombreZona => $grupo) {
        $zona = \App\Models\Zona::firstOrCreate([
            'nombre' => $nombreZona,
            'idEst' => $idEst,
        ]);

        $zonasFinales[] = [
            'idZona' => $zona->idZona,
            'nombre' => $zona->nombre,
            'asientos' => $grupo->map(function ($a) {
                return [
                    'id' => $a->idAsi,
                    'zona' => $a->zona,
                    'ejeX' => $a->ejeX,
                    'ejeY' => $a->ejeY,
                    'estado' => $a->estado,
                    'precio' => $a->precio,
                ];
            })->values(),
        ];
    }

    return response()->json($zonasFinales);
}



      public function guardar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'fecha' => 'required|date',
                'establecimiento_id' => 'required|exists:establecimiento,idEst',
                'estado' => 'required|in:activo,cancelado,completado',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        $zonasFinales = [];

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $evento = new Evento();
            $evento->titulo = $request->titulo;
            $evento->descripcion = $request->descripcion;
            $evento->fecha = $request->fecha;
            $evento->idEst = $request->establecimiento_id;
            $evento->estado = $request->estado;
            $evento->valoracion = '0';
            $evento->tipo = 'evento';
            $evento->ubicacion = 'Por determinar';
            $evento->categoria = 'general';
            $evento->duracion = '01:00:00';

            if ($request->hasFile('imagen')) {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . $imagen->getClientOriginalName();
                $imagen->move(public_path('images/eventos'), $nombreImagen);
                $evento->portada = 'images/eventos/' . $nombreImagen;
            } else {
                $evento->portada = 'images/eventos/default.jpg';
            }


            $evento->save();

            Log::info('Evento guardado exitosamente', ['id' => $evento->idEve]);

            return redirect()->route('eventos.listado')
                ->with('success', 'Evento creado exitosamente');
        } catch (Exception $e) {
            Log::error('Error al guardar evento: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error al crear el evento: ' . $e->getMessage())
                ->withInput();
            $zonasFinales[] = [
                'idZona' => $zona->idZona,
                'nombre' => $zona->nombre,
            ];
        }

        return response()->json($zonasFinales);
    }


    public function visualizar (Request $request){

        
    }
}

    

