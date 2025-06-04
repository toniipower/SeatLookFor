<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class zona extends Model
{
    protected $table = 'zona';

    // Clave primaria 
    protected $primaryKey = 'idZona';
     public $timestamps = false;
    protected $fillable=
    [
       
        "estado",
        "zona"


    ];



    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'zona-eventos', 'idZona', 'idEve');
    }
}
