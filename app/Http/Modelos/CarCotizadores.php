<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class CarCotizadores extends Model
{
    // Declaramos la tabla 
    protected $table = 'carCotizadores';

    // Declaramos las columnas de la tabla
    protected $fillable = [
        'idUsuarioCot',
        'counter',
        'date_current'
    ];
}
