<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleTacon1 extends Model
{
    protected $table = 'detalle_tacon_1';
    public $timestamps = false;
    protected $fillable = [
        'sustrato_t1',
        'tinta_frente_t1',
        'tinta_vuelta_t1',
        
        'medida_final_t1',
        'medida_final_ancho_t1',
        'medida_final_largo_t1',
        'medida_final_alto_t1',

        'medida_extendida_t1',
        'medida_extendida_ancho_t1',
        'medida_extendida_largo_t1',
        'medida_extendida_alto_t1',

        'barniz_frente_t1',
        'barniz_vuelta_t1',
        'sustrato_empalme_t1',
        'requiere_ventana_t1',
        'puntaje_ventana_t1',
        'otros_empaque_t1',
        'metalizado_frente_t1',
        'metalizado_vuelta_t1',
        'laminado_frente_t1',
        'laminado_vuelta_t1',
        'barniz_sakurai_frente_t1',
        'barniz_sakurai_vuelta_t1',
        'realce_t1',
        'placa_realce_t1',
        'hot_stamping_t1',
        'placa_hot_stamping_t1',
        'color_hot_stamping_t1',
        'suaje_t1',
        'suajado_t1',
        'otros_acabados_t1',
        'solicitud_cotizacion_empaque_id',
        'fecha_creacion',
    ];
}
