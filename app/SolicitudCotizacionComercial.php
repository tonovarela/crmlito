<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudCotizacionComercial extends Model
{
    public $timestamps = false;
    protected $table = 'solicitud_cotizacion_comercial';
    protected $fillable = [
        'num_paginas_forro',
        'sustrato_forro',
        'gramaje_forro',
        'tinta_frente_forro',
        'tinta_vuelta_forro',
        'medida_final_forro',
        'medida_extendida_forro',
        'barniz_frente_forro',
        'barniz_vuelta_forro',

        'num_paginas_interiores',
        'sustrato_interiores',
        'gramaje_interiores',
        'tinta_frente_interiores',
        'tinta_vuelta_interiores',
        'medida_final_interiores',
        'medida_extendida_interiores',
        'barniz_frente_interiores',
        'barniz_vuelta_interiores',

        'num_paginas_guardas',
        'sustrato_guardas',
        'gramaje_guardas',
        'tinta_frente_guardas',
        'tinta_vuelta_guardas',
        'medida_final_guardas',
        'medida_extendida_guardas',
        'barniz_frente_guardas',
        'barniz_vuelta_guardas',

        'metalizado_frente_forro',
        'metalizado_vuelta_forro',
        'laminado_frente_forro',
        'laminado_vuelta_forro',
        'barniz_sakurai_frente_forro',
        'barniz_sakurai_vuelta_forro',
        'realce_forro',
        'placa_realce_forro',
        'hot_stamping_forro',
        'placa_hot_stamping_forro',
        'color_hot_stamping_forro',
        'suaje_forro',

        'metalizado_frente_interiores',
        'metalizado_vuelta_interiores',
        'laminado_frente_interiores',
        'laminado_vuelta_interiores',
        'barniz_sakurai_frente_interiores',
        'barniz_sakurai_vuelta_interiores',
        'doblez',
        'embolsado_retractilado',
        'perforado',
        'suaje_interiores',
        'otros_interiores',

        'encuadernado',
        'empaque',
        'entrega',
        'lugar_entrega',
        'observaciones',
        // 'observaciones_presupuesto',

        'solicitud_cotizacion_id',

        'na_editorial_forro',
        'na_editorial_guarda',
        'na_acabado_forro',

        'suajado',
        'suajado_forro',
    ];
}
