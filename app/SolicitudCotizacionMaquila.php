<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudCotizacionMaquila extends Model
{
    public $timestamps = false;
    protected $table = 'solicitud_cotizacion_maquila';
    protected $fillable = [
        'empaque',
        'entrega',
        'lugar_entrega',
        'descripcion',
        'observaciones',
        // 'observaciones_presupuesto',
        'instalacion',
        'proveedor_sugerido',
        'solicitud_cotizacion_id',
        'suajado',
    ];
}
