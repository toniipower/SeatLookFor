<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Establecimiento;
use App\Http\Requests\StoreEstablecimientoRequest;
use App\Http\Requests\UpdateEstablecimientoRequest;

class EstablecimientoController extends Controller
{
  

    /**
     * Show the form for creating a new resource.
     */
    public function listar(Request $request)
    {   

       
        $establecimientos = Establecimiento::all();
        
        return view('Establecimiento.listadoEstablecimiento',['establecimientos'=> $establecimientos]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function eliminar(Establecimiento $establecimiento)
    {
        $establecimiento->delete();
        return to_route("Establecimiento.listar");
        
    }

    /**
     * Display the specified resource.
     */
    public function crear(Establecimiento $establecimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Establecimiento $establecimiento)
    {
        //
    }


}
