<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class MotivoCambioEspecificaciones extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $table = 'motivo_cambio_especificaciones';
    protected $fillable = [
        'mensaje',
        'solicitud_cotizacion_id',
        'usuario_id',
    ];
}
