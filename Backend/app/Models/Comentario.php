<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Comentario extends Model
{
    use HasFactory;

    
    protected $table = 'comentario';

    // Clave primaria 
    protected $primaryKey = 'idCom';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'valoracion',
        'comentarioPadre',
        'apellidos',
        'texto',
    ];

}
