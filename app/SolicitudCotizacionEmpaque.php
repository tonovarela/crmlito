<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudCotizacionEmpaque extends Model
{
    public $timestamps = false;
    protected $table = 'solicitud_cotizacion_empaque';
    protected $fillable = [
        'sustrato',
        'gramaje',
        'tinta_frente',
        'tinta_vuelta',
        'medida_final',
        'medida_final_ancho_general',
        'medida_final_largo_general',
        'medida_final_alto_general',

        'medida_extendida',
        'medida_extendida_ancho_general',
        'medida_extendida_largo_general',
        'medida_extendida_alto_general',

        'barniz_frente',
        'barniz_vuelta',
        'sustrato_empalme',
        'requiere_ventana',
        'puntaje_ventana',
        'otros_empaque',
        'metalizado_frente',
        'metalizado_vuelta',
        'laminado_frente',
        'laminado_vuelta',
        'barniz_sakurai_frente',
        'barniz_sakurai_vuelta',
        'realce',
        'placa_realce',
        'hot_stamping',
        'placa_hot_stamping',
        'color_hot_stamping',
        'suaje',
        'otros_acabados',
        'tipo_pegue_armado',
        'empaque',
        'entrega',
        'lugar_entrega',
        'observaciones',
        // 'observaciones_presupuesto',
        
        'solicitud_cotizacion_id',
        'suajado',
        'na_tacon_1',
        'na_tacon_2',
    ];
}
