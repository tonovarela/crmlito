<?php

namespace App\Http\Controllers;

use App\SolicitudCotizacionComercial;
use Illuminate\Http\Request;

class SolicitudCotizacionComercialController extends Controller
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
            return SolicitudCotizacionComercial::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el comercial',
            ], 500);
        }
    }

    /**
     * Función para actualizar una solicitud de cotización comercial.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = SolicitudCotizacionComercial::where('solicitud_cotizacion_id', $id)->first();
            if ($solicitud) {
                $solicitud->update($data);
            } else {
                $data['solicitud_cotizacion_id'] = $id;
                $solicitud = SolicitudCotizacionComercial::create($data);
            }
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cotización comercial actualizada correctamente',
                'data' => $solicitud,
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización comercial',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
