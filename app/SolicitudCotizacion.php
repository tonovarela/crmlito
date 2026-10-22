<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudCotizacion extends Model
{
    public $timestamps = false;
    protected $table = 'solicitud_cotizacion';
    protected $fillable = [
        'oportunidad_id',
        'vendedor_id',
        'vendedor_abr',
        'cliente_id',
        'contacto_id',
        'nombre_proyecto',
        'comision_externa',
        'cantidad_cotizar_1',
        'cantidad_cotizar_2',
        'cantidad_cotizar_3',
        'cat_EP_tipoProducto_id',
        'plantilla_id',
        'usuario_id',
        'folio',
        'tiene_cambio_especificaciones',
        'resolvio_cambio_especificaciones',
        'tiene_cambio_precios',
        'resolvio_cambio_precios',
        'tiene_cambios_minimos',
        'resolvio_cambios_minimos',
        'observaciones_presupuesto',
    ];
}
