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
        'precio' => 'required|numeric|min:0',
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
                'precio' => $validated['precio'],
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

    // Generar el PDF
    $pdf = Pdf::loadView('pdf.entrada', ['reservas' => $reservas, 'usuario' => $usuario]);
    $filename = 'entrada_' . now()->timestamp . '.pdf';
    Storage::put('public/entradas/' . $filename, $pdf->output());

    return response()->json([
        'message' => 'Reservas creadas con éxito',
        'pdf_url' => asset('storage/entradas/' . $filename),
    ], 201);
}
}
