<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait BitacoraSolicitudCotizacionTrait
{
    /**
     * Registra la bitácora de una solicitud de cotización.
     *
     * @param $seccion
     * @param $valor_anterior
     * @param $valor_nuevo
     * @param $solicitud_cotizacion_id
     * @param $usuario_id
     * @return void
     */
    public function registrarBitacoraSolicitudCotizacion($seccion, $cambio_previo, $cambio_nuevo, $solicitud_cotizacion_id, $usuario_id)
    {
        DB::table('BitacoraSolicitudCotizacion')->insert([
            'seccion' => $seccion,
            'cambio_previo' => $cambio_previo,
            'cambio_nuevo' => $cambio_nuevo,
            'solicitud_cotizacion_id' => $solicitud_cotizacion_id,
            'usuario_id' => $usuario_id,
        ]);
    }
}