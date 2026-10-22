<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class OportunidadCambiosMinimos extends Model
{
    protected $table = 'OportunidadCambiosMinimos';

    protected $primaryKey = 'idOportunidadCambiosMinimos';

    public $timestamps = false;

    protected $guarded = [
        'idOportunidad',
        'idCambio',
        'cambio',
        'realizar',
        'realizado',
        'valor',
    ];
}
