<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Asiento;
use App\Models\Establecimiento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $evento = Evento::with(['establecimiento.asientos', 'comentarios'])->findOrFail($id);

        if (request()->wantsJson()) {
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

        return view('Evento.mostrarEvento', compact('evento'));
    }

    public function formularioCrear()
    {
        $establecimientos = Establecimiento::all();
        return view('Evento.CrearEvento', compact('establecimientos'));
    }

    public function obtenerZonas($idEst)
    {
        $zonas = Asiento::where('idEst', $idEst)
            ->select('zona')
            ->distinct()
            ->get()
            ->map(function ($asiento) {
                return [
                    'nombre' => $asiento->zona
                ];
            });

        return response()->json($zonas);
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

            \Log::info('Intentando guardar evento:', [
                'datos' => $evento->toArray(),
                'fillable' => $evento->getFillable()
            ]);

            $evento->save();

            \Log::info('Evento guardado exitosamente', ['id' => $evento->idEve]);

            return redirect()->route('eventos.listado')
                ->with('success', 'Evento creado exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al guardar evento: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error al crear el evento: ' . $e->getMessage())
                ->withInput();
        }
    }
}



