<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PresupuestosEP extends Model
{
    public $timestamps = false;
    protected $table = 'presupuestosEP';
    protected $fillable = [
        'folio_metrics',
        'cantidad_1',
        'precio_1',
        'cantidad_2',
        'precio_2',
        'cantidad_3',
        'precio_3',
        'observaciones',
        'presupuesto_id',
        'solicitud_cotizacion_id',
        'cat_categoria_fsc_id',
        'cat_sub_categoria_fsc_id',
    ];
}
