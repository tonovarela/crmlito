<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class GruposBitacoraCambios extends Model
{
    protected $table = 'gruposBitacoraCambios';

    public $timestamps = false;

    protected $fillable = [
        'estatus',
        'idUsuario',
        'solicitudCotizacionId',
        'ciclo'
    ];

}
