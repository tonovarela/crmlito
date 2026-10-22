<?php

namespace App\Http\Controllers;

use App\BitacoraPresupuestos;
use Illuminate\Http\Request;

class BitacoraPresupuestosController extends Controller
{
    /**
     * Función para almacenar una nueva entrada en la bitácora de presupuestos.
     *
     * @param  array  $data
     * @return \App\BitacoraPresupuestos|\Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {
        try {
            $bitacora = BitacoraPresupuestos::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Entrada en la bitácora de presupuestos creada exitosamente',
                'data' => $bitacora,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la entrada en la bitácora de presupuestos',
            ], 500);
        }
    }
}
