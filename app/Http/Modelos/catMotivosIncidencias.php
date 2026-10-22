<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class catMotivosIncidencias extends Model
{
    protected $table = 'catMotivosIncidencias';

    protected $primaryKey = 'idMotivoIncidencia';

    protected $fillable = [
        'incidencia',
        'recurrente',
        'frecuencia',
        'estatus',
        'tipo',
        'monto',
    ];

    protected $dateFormat = 'd-m-Y H:i:s';

    public $timestamps = false;
}
