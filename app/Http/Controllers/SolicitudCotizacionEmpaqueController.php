<?php

namespace App\Http\Controllers;

use App\SolicitudCotizacionEmpaque;
use Illuminate\Http\Request;

class SolicitudCotizacionEmpaqueController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param $data
     * @return \Illuminate\Http\Response|SolicitudCotizacionEmpaque
     */
    public function store($data)
    {
        try {
            return SolicitudCotizacionEmpaque::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el empaque',
            ], 500);
        }
    }

    /**
     * Función para actualizar una solicitud de cotización EMPAQUE.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = SolicitudCotizacionEmpaque::where('solicitud_cotizacion_id', $id)->first();
            if ($solicitud) {
                $solicitud->update($data);
            } else {
                $data['solicitud_cotizacion_id'] = $id;
                $solicitud = SolicitudCotizacionEmpaque::create($data);
            }
            return $solicitud;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización EMPAQUE',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
