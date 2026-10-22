<?php

namespace App\Http\Controllers;

use App\Http\Modelos\CantidadesAdicionalesPresupuesto;
use App\SolicitudCotizacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudCotizacionController extends Controller
{
    /**
     * Función para almacenar una nueva solicitud de cotización.
     *
     * @param  array  $data
     * @return SolicitudCotizacion|\Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {
        try {
            return SolicitudCotizacion::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud de cotización',
            ], 500);
        }
    }

    /**
     * Función para actualizar una solicitud de cotización.
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(array $data, int $id)
    {
        try {
            $solicitud = SolicitudCotizacion::findOrFail($id);
            $solicitud->update($data);
            return $solicitud->fresh(); // Retorna la solicitud actualizada
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Función para obtener multiples solicitudes de cotización por sus IDs.
     *
     * @param Request $request
     * @return mixed
     */
    public function getElementosPresupuestadosByIds(Request $request)
    {
        try {
            $ids = $request->input('ids', []);
            $data = DB::table('v_solicitudesCotizacion')->whereIn('id', $ids)->get();
            $idOportunidad = $request->input('idOportunidad');

            $elementosFolios = $data->pluck('folio')->implode('_');
            $titulo = str_replace(" ", "_", "Presupuesto_".$elementosFolios);
            $ultimoP = DB::table('Presupuestos')->where('oportunidad_id', $idOportunidad)->count() + 1;
            
            // Agregamos la fecha y hora actual al título
            $fechaHoraActual = date('Y_m_d');
            $titulo = substr($titulo, 0, 100); // Limitamos el título a 100 caracteres
            $titulo .= '_' . $fechaHoraActual . '_v' . $ultimoP;

            return response()->json([
                'response' => true,
                'data' => $data,
                'tituloPresupuesto' => $titulo,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener las solicitudes de cotización',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Función para almacenar cantidades y precios adicionales asociados a una solicitud de cotización.
     * 
     * @param array $data
     * @return \App\Http\Modelos\CantidadesAdicionalesPresupuesto|\Illuminate\Http\JsonResponse
     */
    public function storeAdditionalQuantitiesAndPrices(array $data)
    {
        try {
            return CantidadesAdicionalesPresupuesto::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear las cantidades y precios adicionales',
            ], 500);
        }
    }
}
