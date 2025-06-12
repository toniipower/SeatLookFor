<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReservaController extends Controller
{


public function crearReserva(Request $request)
{
    $validated = $request->validate([
        'totalPrecio' => 'required|numeric|min:0',
        'fechaReserva' => 'required|date',
        'idAsientos' => 'required|array|min:1',
        'idAsientos.*' => 'integer|exists:asiento,idAsi',
        'idEve' => 'required|integer|exists:evento,idEve',
    ]);

    $usuario = $request->user();
    $reservas = [];

    DB::beginTransaction();
    try {
        foreach ($validated['idAsientos'] as $idAsiento) {
            $reserva = Reserva::create([
                'totalPrecio' => $validated['totalPrecio'],
                'fechaReserva' => $validated['fechaReserva'],
                'idAsi' => $idAsiento,
                'idUsu' => $usuario->idUsu,
                'idEve' => $validated['idEve'],
            ]);

            $reservas[] = $reserva;
        }

        DB::commit();
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error al crear reservas.'], 500);
    }

    // Cargar datos relacionados
    $evento = \App\Models\Evento::with('establecimiento')->findOrFail($validated['idEve']);
    $asientos = \App\Models\Asiento::whereIn('idAsi', $validated['idAsientos'])->get();

    // Generar el PDF
    $pdf = Pdf::loadView('pdf.entrada', [
        'reservas' => $reservas,
        'usuario' => $usuario,
        'evento' => $evento,
        'establecimiento' => $evento->establecimiento,
        'asientos' => $asientos,
    ]);

    $filename = 'entrada_' . now()->timestamp . '.pdf';

    // DEVOLVER PDF PARA DESCARGA DIRECTA
    return $pdf->download($filename);
}
}
