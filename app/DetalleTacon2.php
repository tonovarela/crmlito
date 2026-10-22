<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleTacon2 extends Model
{
    protected $table = 'detalle_tacon_2';
    public $timestamps = false;
    protected $fillable = [
        'sustrato_t2',
        'tinta_frente_t2',
        'tinta_vuelta_t2',
        
        'medida_final_t2',
        'medida_final_ancho_t2',
        'medida_final_largo_t2',
        'medida_final_alto_t2',

        'medida_extendida_t2',
        'medida_extendida_ancho_t2',
        'medida_extendida_largo_t2',
        'medida_extendida_alto_t2',

        'barniz_frente_t2',
        'barniz_vuelta_t2',
        'sustrato_empalme_t2',
        'requiere_ventana_t2',
        'puntaje_ventana_t2',
        'otros_empaque_t2',
        'metalizado_frente_t2',
        'metalizado_vuelta_t2',
        'laminado_frente_t2',
        'laminado_vuelta_t2',
        'barniz_sakurai_frente_t2',
        'barniz_sakurai_vuelta_t2',
        'realce_t2',
        'placa_realce_t2',
        'hot_stamping_t2',
        'placa_hot_stamping_t2',
        'color_hot_stamping_t2',
        'suaje_t2',
        'suajado_t2',
        'otros_acabados_t2',
        'solicitud_cotizacion_empaque_id',
        'fecha_creacion',
    ];
}
