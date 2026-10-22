<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudCotizacionPop extends Model
{
    public $timestamps = false;
    protected $table = 'solicitud_cotizacion_pop';

    protected $fillable = [
        'sustrato',
        'gramaje',
        'tinta_frente',
        'tinta_vuelta',
        'medida_final',
        'medida_extendida',
        'barniz_frente',
        'barniz_vuelta',
        'num_cambios',
        'trazo_cliente',
        'trazo_estructural',
        'sustrato_empalme',
        'otros_pop',
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
        'termodoblez',
        'hilo_monofilamento',
        'cm_hilo_x_pieza',
        'cinta_doble_cara',
        'cantidad_cintas_x_pieza',
        'medida_cada_cinta',
        'otros_acabados',
        'adicional',
        'empaque',
        'entrega',
        'lugar_entrega',
        'observaciones',
        // 'observaciones_presupuesto',
        
        'solicitud_cotizacion_id',
        'suajado',
    ];
}
