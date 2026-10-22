<?php

namespace App\Http\Controllers;

use App\SolicitudCotizacionMaquila;
use Illuminate\Http\Request;

class SolicitudCotizacionMaquilaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param $data
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        try {
            return SolicitudCotizacionMaquila::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la maquila',
            ], 500);
        }
    }

    /**
     * Función para actualizar una solicitud de cotización maquila.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = SolicitudCotizacionMaquila::where('solicitud_cotizacion_id', $id)->first();
            if ($solicitud) {
                $solicitud->update($data);
            } else {
                $data['solicitud_cotizacion_id'] = $id;
                $solicitud = SolicitudCotizacionMaquila::create($data);
            }
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cotización maquila actualizada correctamente',
                'data' => $solicitud,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización maquila',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
