<?php

namespace App\Http\Controllers;

use App\PresupuestosEP;
use App\SolicitudCotizacion;
use App\SolicitudCotizacionDisplay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestosEPController extends Controller
{
    /**
     * Función para almacenar un nuevo presupuesto EP.
     * 
     * @param  array  $data
     * @return \App\PresupuestosEP|\Illuminate\Http\JsonResponse
     */
    public function store(array $data)
    {
        try {
            return PresupuestosEP::create($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el presupuesto EP',
            ], 500);
        }
    }

    /**
     * Función para obtener los valores FSC. de una cadena específica.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerValoresFSC(Request $request)
    {
        $folioMetrics = $request->input('folio');

        try {
            $valoresFSC = DB::table('MetricsWeb.dbo.orchdr')
                            ->select(['usr_fsc', 'usr_pureza'])
                            ->where('numorcamento', $folioMetrics)
                            ->first();

            // Obtenemos los ids de los catalogos de FSC para mostrar los nombres en lugar de los ids
            if ($valoresFSC) {
                $idCategoria = DB::table('cat_CategoriaFsc')
                                        ->where('categoria', $valoresFSC->usr_pureza)
                                        ->first();

                $idSubCategoria = DB::table('cat_SubCategoriaFsc')
                                        ->where('subcategoria', $valoresFSC->usr_fsc)
                                        ->first();
            }

            return response()->json([
                'success' => true,
                'data' => $valoresFSC,
                'idCategoria' => $idCategoria->id ?? null,
                'idSubCategoria' => $idSubCategoria->id ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los valores FSC',
            ], 500);
        }
    }

    /**
     * Función para obtener las medidas EP. de una cadena específica.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerMedidasEP(Request $request)
    {
        $idEp = $request->input('ep');

        try {
            $ep = SolicitudCotizacion::find($idEp);

            if (!$ep) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el EP',
                ], 404);
            }

            switch ($ep->plantilla_id) {
                case 3: // Display
                    $epComplemento = DB::table('v_SolicitudCotizacionDisplay')->where('solicitud_cotizacion_id', $idEp)->first();
                    $medidas = [
                        'medida_final_ancho' => $epComplemento->medida_final_ancho,
                        'medida_final_largo' => $epComplemento->medida_final_largo,
                        'medida_final_alto' => $epComplemento->medida_final_alto,

                        'medida_extendida_ancho' => $epComplemento->medida_extendida_ancho,
                        'medida_extendida_largo' => $epComplemento->medida_extendida_largo,
                        'medida_extendida_alto' => $epComplemento->medida_extendida_alto,
                    ];
                    break;
                case 2: // Empaques
                    $epComplemento = DB::table('v_SolicitudCotizacionEmpaque')->where('solicitud_cotizacion_id', $idEp)->first();
                    $medidas = [
                        'medida_extendida_alto_general' => $epComplemento->medida_extendida_alto_general,
                        'medida_extendida_alto_t1' => $epComplemento->medida_extendida_alto_t1,
                        'medida_extendida_alto_t2' => $epComplemento->medida_extendida_alto_t2,
                        'medida_extendida_ancho_general' => $epComplemento->medida_extendida_ancho_general,
                        'medida_extendida_ancho_t1' => $epComplemento->medida_extendida_ancho_t1,
                        'medida_extendida_ancho_t2' => $epComplemento->medida_extendida_ancho_t2,
                        'medida_extendida_largo_general' => $epComplemento->medida_extendida_largo_general,
                        'medida_extendida_largo_t1' => $epComplemento->medida_extendida_largo_t1,
                        'medida_extendida_largo_t2' => $epComplemento->medida_extendida_largo_t2,

                        'medida_final_alto_general' => $epComplemento->medida_final_alto_general,
                        'medida_final_alto_t1' => $epComplemento->medida_final_alto_t1,
                        'medida_final_alto_t2' => $epComplemento->medida_final_alto_t2,
                        'medida_final_ancho_general' => $epComplemento->medida_final_ancho_general,
                        'medida_final_ancho_t1' => $epComplemento->medida_final_ancho_t1,
                        'medida_final_ancho_t2' => $epComplemento->medida_final_ancho_t2,
                        'medida_final_largo_general' => $epComplemento->medida_final_largo_general,
                        'medida_final_largo_t1' => $epComplemento->medida_final_largo_t1,
                        'medida_final_largo_t2' => $epComplemento->medida_final_largo_t2,

                        'tiene_tacon_1' => $epComplemento->na_tacon_1 != 1,
                        'tiene_tacon_2' => $epComplemento->na_tacon_2 != 1,
                    ];
                    break;
                default:
                    $medidas = [];
            }

            return response()->json([
                'success' => true,
                'data' => $medidas,
                'plantilla_id' => $ep->plantilla_id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las medidas EP',
            ], 500);
        }
    }
}
