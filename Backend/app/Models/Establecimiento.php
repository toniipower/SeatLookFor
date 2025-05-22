<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Establecimiento extends Model
{
    /** @use HasFactory<\Database\Factories\EstablecimientoFactory> */
    use HasFactory;


    
    public $timestamps = false;
    protected $table = 'establecimiento';

    // Clave primaria 
    protected $primaryKey = 'idEst';

    protected $fillable=
    [
        'ubicacion',
        'nombre',
        'imagen'
    ];

    public function Asientos()
    {
        return $this->hasMany(Asiento::class);
    }
    public function Eventos()
    {
        return $this->hasMany(Evento::class);
    }
}
