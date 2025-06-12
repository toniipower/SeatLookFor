<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Zona; // Asegúrate de importar el modelo

use Illuminate\Http\Request;
use App\Models\Establecimiento;
use App\Http\Requests\StoreEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;

class EstablecimientoController extends Controller
{
  

    /**
     * Listado de los establecimientos 
     */
    public function listar(Request $request)
    {   

   // Carga todos los establecimientos, 6 por página
    $establecimientos = Establecimiento::paginate(6);

    return view('Establecimiento.listadoEstablecimiento', compact('establecimientos'));
    }


/*         public function guardarAsientos(Request $request)
    {
        foreach ($request->asientos as $asiento) {
            Asiento::create([
                'establecimiento_id' => $request->establecimiento_id,
                'fila' => $asiento['fila'],
                'columna' => $asiento['columna'],
                'codigo' => $asiento['codigo'],
            ]);
        }

        return response()->json(['message' => 'Asientos guardados correctamente.']);
    } */

    /**
     * Guarda los datos del establecimiento nuevo y sus asientos
     */


public function guardar(Request $request)
{
    // Validar establecimiento
    $request->validate([
        'nombre' => 'required|string|max:255',
        'ubicacion' => 'required|string|max:255',
        'imagen' => 'required|string|max:255',
        
    ]);

    // Crear el establecimiento
    $establecimiento = Establecimiento::create([
        'nombre' => $request->input('nombre'),
        'ubicacion' => $request->input('ubicacion'),
        'imagen' => $request->input('imagen')
    ]);

    // Procesar los asientos desde el JSON
    $asientosRaw = $request->input('asientos');
    $asientos = json_decode($asientosRaw, true);

    if (!is_array($asientos) || empty($asientos)) {
        return back()->with('error', 'No se proporcionaron asientos válidos.')->withInput();
    }

    // Extraer zonas únicas, excluyendo el "escenario"
    $zonas = collect($asientos)
        ->pluck('zona')
        ->filter(fn($z) => strtolower($z) !== 'escenario')
        ->unique();
  
        
        
   foreach ($zonas as $zonaNombre) {
    if (strlen($zonaNombre) > 5) {
        return back()->withErrors(['zona' => "La zona '$zonaNombre' no puede tener más de 5 caracteres."])->withInput();
    }

    Zona::firstOrCreate([
        'nombre' => $zonaNombre,
        'idEst' => $establecimiento->idEst,
    ]);
}


    // Guardar los asientos
    foreach ($asientos as $item) {
        if (!isset($item['estado'], $item['zona'], $item['ejeX'], $item['ejeY'], $item['precio'])) {
            continue;
        }

        Asiento::create([
            'estado' => $item['estado'],
            'zona' => $item['zona'],
            'ejeX' => $item['ejeX'],
            'ejeY' => $item['ejeY'],
            'precio' => $item['precio'],
            'idEst' => $establecimiento->idEst,
        ]);
    }

    return to_route('establecimiento.listado');
}


/**
 * Muestra un establecimiento
 *
 * @param [type] $idEst
 * @return void
 */
public function mostrar($idEst)
{
    $establecimiento = Establecimiento::with(['asientos', 'eventos'])->findOrFail($idEst);

    return view('Establecimiento.mostrarEstablecimiento', compact('establecimiento'));
}


public function eliminar($id)
{
    try {
        $establecimiento = Establecimiento::with(['asientos.usuariosComentaron', 'eventos'])->findOrFail($id);

        // Si hay eventos asociados, no permitir borrar
        if ($establecimiento->eventos->count() > 0) {
            return redirect()->back()->withErrors(['error' => 'No se puede eliminar el establecimiento porque tiene eventos asociados.']);
        }

        foreach ($establecimiento->asientos as $asiento) {
            $asiento->usuariosComentaron()->detach();
            $asiento->eventos()->detach();
            $asiento->delete();
        }

        $establecimiento->delete();

        return redirect()->route('establecimiento.listado')->with('success', 'Establecimiento y sus asientos fueron eliminados correctamente.');
    } catch (\Exception $e) {
        \Log::error('Error al eliminar el establecimiento: ' . $e->getMessage());
        return redirect()->back()->withErrors(['error' => 'No se pudo eliminar el establecimiento.']);
    }
}


}
