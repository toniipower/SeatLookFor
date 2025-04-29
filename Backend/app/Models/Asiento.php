<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asiento extends Model
{
    /** @use HasFactory<\Database\Factories\AsientoFactory> */
    use HasFactory;
    


    protected $table = 'asiento';

    // Clave primaria 
    protected $primaryKey = 'idAsi';

    protected $fillable=
    [
        'estado',
        'fila',
        'columna',


    ];
}
