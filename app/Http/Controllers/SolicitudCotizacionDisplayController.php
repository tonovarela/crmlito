<?php

namespace App\Http\Controllers;

use App\SolicitudCotizacionDisplay;
use Illuminate\Http\Request;

class SolicitudCotizacionDisplayController extends Controller
{
    /**
     * Función para registrar una solicitud de cotización con detalles de POP.
     * 
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        try {
            return SolicitudCotizacionDisplay::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud de cotización DISPLAY',
            ], 500);
        }
    }

    /**
     * Función para actualizar una solicitud de cotización DISPLAY.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = SolicitudCotizacionDisplay::where('solicitud_cotizacion_id', $id)->first();
            if ($solicitud) {
                $solicitud->update($data);
            } else {
                $data['solicitud_cotizacion_id'] = $id;
                $solicitud = SolicitudCotizacionDisplay::create($data);
            }
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cotización DISPLAY actualizada correctamente',
                'data' => $solicitud,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización display',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
