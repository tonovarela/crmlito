<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudCotizacionDisplay extends Model
{
    public $timestamps = false;
    protected $table = 'solicitud_cotizacion_display';
    protected $fillable = [
        'sustrato',
        'gramaje',
        'tinta_frente',
        'medida_final',
        'medida_extendida',
        'sustrato_empalme',
        'otros_display',

        'metalizado_frente',
        'metalizado_vuelta',
        'laminado_frente',
        'laminado_vuelta',
        'barniz_sakurai_frente',
        'barniz_sakurai_vuelta',

        'medida_final_ancho',
        'medida_final_largo',
        'medida_final_alto',

        'medida_extendida_ancho',
        'medida_extendida_largo',
        'medida_extendida_alto',
        
        'otros_metalizado_frente',
        // 'otros_metalizado_vuelta',
        // 'otros_laminado_frente',
        // 'otros_laminado_vuelta',
        // 'otros_barniz_frente',
        // 'otros_barniz_vuelta',

        'tipo_pegue_armado',
        'empaque_primario',
        'empaque_secundario',
        'lugar_entrega',
        'observaciones',
        // 'observaciones_presupuesto',
        
        'solicitud_cotizacion_id',
        'suajado',
    ];
}
