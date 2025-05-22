<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    /** @use HasFactory<\Database\Factories\AsientoFactory> */
    use HasFactory;
    
    public $timestamps = false;

    protected $table = 'asiento';

    // Clave primaria 
    protected $primaryKey = 'idAsi';

    protected $fillable=
    [
        'estado',
        'zona',
        'ejeX',
        'ejeY',
        'estado',
        'idEst',
        'precio'
    
    ];

    public function opinion()
    {
        return $this->belongsToMany(Asiento::class, 'opinion', 'idUsu', 'idPer');
    }


    
    public function asientoReservado()
    {
        return $this->hasMany(Reserva::class,);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }


}
