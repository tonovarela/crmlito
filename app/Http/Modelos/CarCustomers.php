<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class CarCustomers extends Model
{
    // Declaramos la tabla 
    protected $table = 'carCustomers';

    // Declaramos las columnas de la tabla
    protected $fillable = [
        'idUsuarioCus',
        'counter',
        'date_current'
    ];
}
