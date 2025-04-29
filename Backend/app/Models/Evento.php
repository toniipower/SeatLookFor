<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    /** @use HasFactory<\Database\Factories\EventoFactory> */
    use HasFactory;


    protected $table = 'evento';

    // Clave primaria 
    protected $primaryKey = 'idEve';

    protected $fillable=
    [
        'estado',
        'valoracion',
        'tipo',
        'ubicacion',
        'titulo',
        'descripcion',
        'duracion',
        'fecha',
        'categoria'


    ];

}
