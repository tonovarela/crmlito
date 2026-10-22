<?php

namespace App\Http\Controllers;

use App\Presupuesto;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    /**
     * Función para almacenar un nuevo presupuesto.
     * @param  array  $data
     * @return \App\Presupuesto|\Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {
        try {
            return Presupuesto::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el presupuesto',
            ], 500);
        }
    }
}
