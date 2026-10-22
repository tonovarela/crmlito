<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class incidenciasMontos extends Model
{
    protected $table = 'incidenciasMontos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'monto',
        'idMotivoIncidencia',
        'idVendedor'
    ];

    protected $dateFormat = 'd-m-Y H:i:s';

    public $timestamps = false;
}
