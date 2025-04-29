<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    /** @use HasFactory<\Database\Factories\EstablecimientoFactory> */
    use HasFactory;



    protected $table = 'establecimiento';

    // Clave primaria 
    protected $primaryKey = 'idEst';

    protected $fillable=
    [
        'ubicacion',
        'nombre',


    ];
}
