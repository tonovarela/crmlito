<?php

namespace App\Http\Controllers;

use App\DetalleTacon1;

class DetalleTacon1Controller extends Controller
{
    /**
     * Función para almacenar un nuevo detalle del tacón.
     *
     * @param $data
     * @return \Illuminate\Http\Response|DetalleTacon1
     */
    public function store($data)
    {
        try {
            return DetalleTacon1::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el detalle del tacón',
            ], 500);
        }
    }

    /**
     * Función para actualizar un detalle del tacón.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = DetalleTacon1::where('solicitud_cotizacion_empaque_id', $id)->firstOrFail();
            $solicitud->update($data);
            return response()->json([
                'success' => true,
                'message' => 'Detalle del tacón actualizado correctamente',
                'data' => $solicitud,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización EMPAQUE',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
