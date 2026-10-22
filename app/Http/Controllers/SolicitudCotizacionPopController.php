<?php

namespace App\Http\Controllers;

use App\SolicitudCotizacionPop;
use Illuminate\Http\Request;

class SolicitudCotizacionPopController extends Controller
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
            // Aquí se llamaría al método de SolicitudCotizacionController para registrar la solicitud
            return SolicitudCotizacionPop::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud de cotización POP',
            ], 500);
        }
    }

    /**
     * Función para actualizar una solicitud de cotización POP.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = SolicitudCotizacionPop::where('solicitud_cotizacion_id', $id)->first();
            if ($solicitud) {
                $solicitud->update($data);
            } else {
                $data['solicitud_cotizacion_id'] = $id;
                $solicitud = SolicitudCotizacionPop::create($data);
            }
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cotización POP actualizada correctamente',
                'data' => $solicitud,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización POP',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
