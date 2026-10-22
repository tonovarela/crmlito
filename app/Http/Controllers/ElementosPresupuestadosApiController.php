<?php

namespace App\Http\Controllers;

use App\Http\Modelos\GruposBitacoraCambios;
use App\SolicitudCotizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ElementosPresupuestadosApiController extends Controller
{
    use \App\Traits\ElementosPresupuestados\CatDetallesTrait;

    /**
     * Function to get the list of vendors
     * 
     * @return \Illuminate\Http\Response
     */
    public function getVendedores(Request $request)
    {
        try {
            $query = $request->input('q');

            $vendedores = DB::table('v_listAgentesComisiones')
                ->where('NombreAgente', 'like', '%' . $query . '%')
                ->select('Agente', 'NombreAgente')
                ->get();

            return response()->json($vendedores);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los vendedores',
            ], 500);
        }
    }

    /**
     * Function to get the list of clients
     * 
     * @return \Illuminate\Http\Response
     */
    public function getClientes(Request $request)
    {
        try {
            $query = $request->input('q');

            $clientes = DB::table('v_catClientes')
                ->where('razonsocial', 'like', '%' . $query . '%')
                ->select('ClienteINT', 'razonsocial')
                ->get();

            if (auth()->user()->hasRole('Administrador')) {
                $clientes = $clientes->map(function ($cliente) {
                    return [
                        'ClienteINT' => $cliente->ClienteINT,
                        'razonsocial' => $cliente->razonsocial,
                    ];
                });
            } elseif (auth()->user()->hasRole('Vendedor')) {
                $clientes = $clientes->filter(function ($cliente) {
                    return $cliente->Agente === auth()->user()->Agente || $cliente->Agente === auth()->user()->Agente3;
                })->map(function ($cliente) {
                    return [
                        'ClienteINT' => $cliente->ClienteINT,
                        'razonsocial' => $cliente->razonsocial,
                    ];
                });
            }

            return response()->json($clientes);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los clientes',
            ], 500);
        }
    }

    /**
     * Function to get the list of contacts
     * 
     * @return \Illuminate\Http\Response
     */
    public function getContactos(Request $request)
    {
        try {
            $query = $request->input('q');
            $clienteId = $request->input('clienteId');

            $contactos = DB::table('v_catContactos')
                ->where('razonsocial', 'like', '%' . $query . '%')
                ->where('ClienteINT', $clienteId)
                ->select('ContactoINT', 'razonsocial')
                ->get();

            return response()->json($contactos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los contactos',
            ], 500);
        }
    }

    /**
     * Function to get the list of opportunities
     * 
     * @return \Illuminate\Http\Response
     */
    public function getOportunidades(Request $request)
    {
        try {
            $querySearch = $request->input('query');

            $idRol = auth()->user()->rol->idRol;
            $idUsuario = auth()->user()->Id_Usuario;

            // Si es auxiliar
            if ($idRol == 9) {
                $agente = DB::table('rel_Agente_Aux')
                            ->where(function ($query) use ($idUsuario) {
                                $query->where('idAuxiliar', $idUsuario)
                                    ->orWhere('idAuxiliar1', $idUsuario)
                                    ->orWhere('idAuxiliar2', $idUsuario)
                                    ->orWhere('idAuxiliar3', $idUsuario);
                            })->first();

                // Si se encuentra un agente asociado al auxiliar, obtenemos su ID
                if ($agente) {
                    $idUsuario = $agente->idAgente;
                }
            }

            $oportunidades = DB::table('v_OportunidadesXClientesIntTodosVendedores')
                ->when($querySearch !== null && $querySearch !== '', function ($query) use ($querySearch) {
                    return $query->where(function ($q) use ($querySearch) {
                        $q->where('folio', 'like', '%' . $querySearch . '%')
                            ->orWhere('nomOportunidad', 'like', '%' . $querySearch . '%');
                    });
                })
                ->when(!in_array($idRol, [1, 8, 10]), function ($query) use ($idUsuario) {
                    return $query->where('idAgente', $idUsuario);
                })
                ->orderBy('folio', 'desc')
                ->get();

            return response()->json($oportunidades);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las oportunidades',
            ], 500);
        }
    }
    
    /**
     * Function to get the list of opportunities generals
     * 
     * @return \Illuminate\Http\Response
     */
    public function getOportunidadesGeneral(Request $request)
    {
        try {
            $querySearch = $request->input('query');

            $idRol = auth()->user()->rol->idRol;
            $idUsuario = auth()->user()->Id_Usuario;

            // Si es auxiliar
            if ($idRol == 9) {
                $agente = DB::table('rel_Agente_Aux')
                            ->where(function ($query) use ($idUsuario) {
                                $query->where('idAuxiliar', $idUsuario)
                                    ->orWhere('idAuxiliar1', $idUsuario)
                                    ->orWhere('idAuxiliar2', $idUsuario)
                                    ->orWhere('idAuxiliar3', $idUsuario);
                            })->first();

                // Si se encuentra un agente asociado al auxiliar, obtenemos su ID
                if ($agente) {
                    $idUsuario = $agente->idAgente;
                }
            }

            $oportunidades = DB::table('v_OportunidadesXClientesIntTodosVendedores')
                ->when($querySearch !== null && $querySearch !== '', function ($query) use ($querySearch) {
                    return $query->where(function ($q) use ($querySearch) {
                        $q->where('folio', 'like', '%' . $querySearch . '%')
                            ->orWhere('nomOportunidad', 'like', '%' . $querySearch . '%');
                    });
                })
                ->when(!in_array($idRol, [1, 8, 10]), function ($query) use ($idUsuario) {
                    return $query->where('idAgente', $idUsuario);
                })
                ->orderBy('folio', 'desc')
                ->get();

            return response()->json($oportunidades);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las oportunidades',
            ], 500);
        }
    }

    /**
     * Function to get the list of opportunities
     * 
     * @return \Illuminate\Http\Response
     */
    public function getOportunidad(Request $request)
    {
        try {
            $id = $request->input('idOportunidad');

            $idRol = auth()->user()->rol->idRol;
            $idUsuario = auth()->user()->Id_Usuario;

            $oportunidad = DB::table('v_OportunidadesXClientesIntTodosVendedores')
                ->where('idOportunidad', $id)
                ->orderBy('folio', 'asc')
                ->first();

            return response()->json($oportunidad);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las oportunidades',
            ], 500);
        }
    }

    /**
     * Función para obtener el listado de plantillas de elementos presupuestados
     * 
     * @return \Illuminate\Http\Response
     */
    public function getPlantillas(Request $request)
    {
        try {
            $pep = DB::table('cat_EP_plantillas')
                ->select('id', 'Descripcion', 'Estatus')
                ->where('Estatus', 1)
                ->get();

            return response()->json($pep);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las plantillas de elementos presupuestados',
            ], 500);
        }
    }

    /**
     * Function to get the list of product types
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTiposProducto(Request $request)
    {
        try {
            $tiposProducto = DB::table('v_catTiposPlantillasES')
                ->select('id', 'Descripcion', 'plantilla_id', 'plantilla')
                ->get();

            // Agrupar por plantilla (plantilla_id y plantilla)
            $agrupados = $tiposProducto->groupBy('plantilla')->map(function ($items, $plantilla) {
                return [
                    'label' => $plantilla,
                    'options' => $items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'plantilla_id' => $item->plantilla_id,
                            'descripcion' => $item->Descripcion,
                        ];
                    })->values()->toArray()
                ];
            })->values()->toArray();

            return response()->json($agrupados);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los tipos de producto',
            ], 500);
        }
    }

    /**
     * Función para obtener toda la data de los formularios dinámicos
     * 
     * @return \Illuminate\Http\Response
     */
    public function getDataForm(Request $request)
    {
        try {
            $idSolicitud = $request->input('idSolicitud', 0);
            $idPlantilla = $request->input('idPlantilla', 0);

            $solicitud = DB::table('solicitud_cotizacion_comercial')
                                    ->where('solicitud_cotizacion_id', $idSolicitud)
                                    ->first();

            $dataFormAdditional = [];

            if ($solicitud != null) {
                $listadoSustratos = [
                    $solicitud->sustrato_forro,
                    $solicitud->sustrato_interiores,
                    $solicitud->sustrato_guardas,
                ];

                $dataFormAdditional = DB::table('cat_EP_detalle')
                    ->select('id', 'descripcion', 'idTipo', 'idPlantilla', 'estatus')
                    ->when(count($listadoSustratos) > 0, function ($query) use ($listadoSustratos) {
                        return $query->whereIn('id', $listadoSustratos);
                    })
                    ->orderBy('idTipo', 'asc')
                    ->get();
            } else {
                $listadoSustratos = [];
            }

            $dataForm = DB::table('cat_EP_detalle')
                ->where('idPlantilla', 'like', "%$idPlantilla%")
                ->select('id', 'descripcion', 'idTipo', 'idPlantilla', 'estatus')
                ->where('estatus', 1)
                ->orderBy('idTipo', 'asc')
                ->get();

            if (count($dataFormAdditional) > 0) {
                // Cambiamos la familia del grupo
                $dataFormAdditional = $dataFormAdditional->map(function ($item) {
                    $item->idTipo = 50; // Asignar el idTipo de Sustratos
                    $item->fromOldVersion = true;
                    return $item;
                });

                $dataForm = $dataForm->merge($dataFormAdditional)->unique('id');
            }

            $dataAgrupada = $dataForm->groupBy('idTipo')->map(function ($items) {
                list($itemsNA, $itemsResto) = $items->partition(function ($item) {
                    return strtoupper(trim((string) $item->descripcion)) === 'N/A';
                });

                return $itemsNA->concat($itemsResto)->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'descripcion' => $item->descripcion,
                        'idPlantilla' => $item->idPlantilla,
                        'estatus' => $item->estatus,
                        'fromOldVersion' => $item->fromOldVersion ?? false,
                    ];
                })->values()->toArray();
            })->toArray();

            // Convertir a un array asociativo con el idTipo como clave
            $dataAgrupada = collect($dataAgrupada)->mapWithKeys(function ($items, $key) {
                return [$key => $items];
            })->toArray();

            return response()->json(collect($dataAgrupada));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los datos del formulario',
            ], 500);
        }
    }

    /**
     * Función para obtener el listado de relaciones de elementos presupuestados de acuerdo al id de la oportunidad
     * 
     * @param int|null $idOportunidad
     * @return mixed
     */
    public function getListRelationEPV2($idOportunidad = null)
    {
        try {
            $listado = DB::table('v_solicitudesCotizacion')
                            ->where('oportunidad_id', $idOportunidad)
                            ->where('estatus', 1) // Excluir estatus 0 (cancelado)
                            ->orderBy('folio', 'asc')
                            ->get();

            return DataTables::of($listado)->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el listado de relaciones de elementos presupuestados',
            ], 500);
        }
    }

    /**
     * Función para obtener el listado de relaciones de elementos presupuestados de acuerdo al id de la oportunidad
     * 
     * @param int|null $idOportunidad
     * @return mixed
     */
    public function getListRelationEPV2Activos($idOportunidad = null)
    {
        try {
            $listado = DB::table('v_solicitudesCotizacion')
                            ->where('oportunidad_id', $idOportunidad)
                            ->where('estatus', 1)
                            ->orderBy('folio', 'asc')
                            ->get();
            
            $noResultados = $listado->count();

            return DataTables::of($listado)
                    ->addColumn('noResultados', function() use ($noResultados) {
                        return $noResultados;
                    })
                    ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el listado de relaciones de elementos presupuestados',
            ], 500);
        }
    }

    /**
     * Función para obtener todos los cambios pendientes de una oportunidad
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getCambiosPendientes(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');

            $oportunidad = DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->first();

            if (!$oportunidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Oportunidad no encontrada',
                ], 404);
            }

            $data = [];

            $vExceles = DB::table('v_ExcelEP')
                            ->where('idOportunidad', $idOportunidad)
                            ->where('solicitoCambios', 1)
                            ->get();

            if (count($vExceles) > 0) {
                foreach ($vExceles as $excel) {
                    $data[] = [
                        'id' => $excel->id,
                        'folio' => $excel->folio,
                        'descripcion' => 'El Excel EP con folio (' . $excel->folio . ') tiene cambios pendientes por aprobar',
                        'tipo_cambio' => 1,
                    ];
                }
            }

            // Obtenemos los elementos con cambios de especificación pendientes
            $elementosConCambios = DB::table('v_solicitudesCotizacion')
                ->where('oportunidad_id', $idOportunidad)
                ->where('tiene_cambio_especificaciones', 1)
                ->get();

            if (count($elementosConCambios) > 0) {
                if (count($elementosConCambios) > 0) {
                    foreach ($elementosConCambios as $elemento) {
                        // Total de cambios pendientes por elemento
                        $gruposCambios = GruposBitacoraCambios::where('solicitudCotizacionId', $elemento->id)
                                                            ->where('estatus', 0)->get();

                        $totalCambiosPorElemento = DB::table('bitacoraCambiosMinEsp')
                                                        ->whereIn('grupoId', $gruposCambios->pluck('id'))
                                                        ->count();

                        $data[] = [
                            'id' => $elemento->id,
                            'folio' => $elemento->folio,
                            'descripcion' => 'Este elemento tiene un total de (' . $totalCambiosPorElemento . ') cambios, pendientes por aprobar',
                            'tipo_cambio' => 1,
                        ];
                    }
                }
            }
            
            // Obtenemos los elementos con cambios minimos pendientes
            $cambiosMinimos = DB::table('v_solicitudesCotizacion')
                ->where('oportunidad_id', $idOportunidad)
                ->where('tiene_cambios_minimos', 1)
                ->get();

            if (count($cambiosMinimos) > 0) {
                if (count($cambiosMinimos) > 0) {
                    foreach ($cambiosMinimos as $elemento) {
                        // Total de cambios pendientes por elemento
                        $gruposCambios = GruposBitacoraCambios::where('solicitudCotizacionId', $elemento->id)
                                                            ->where('estatus', 0)->get();

                        $totalCambiosPorElemento = DB::table('bitacoraCambiosMinEsp')
                                                        ->whereIn('grupoId', $gruposCambios->pluck('id'))
                                                        ->count();

                        $data[] = [
                            'id' => $elemento->id,
                            'folio' => $elemento->folio,
                            'descripcion' => 'Este elemento tiene un total de (' . $totalCambiosPorElemento . ') cambios mínimos, pendientes por aprobar',
                            'tipo_cambio' => 3,
                        ];
                    }
                }
            }

            if ($oportunidad->flagCambioPrecios == 1) {
                // Obtenemos los elementos con cambios de precios pendientes
                $elementosConCambiosPrecios = DB::table('v_solicitudesCotizacion')
                    ->where('oportunidad_id', $idOportunidad)
                    ->where('tiene_cambio_precios', 1)
                    ->get();

                if (count($elementosConCambiosPrecios) > 0) {
                    foreach ($elementosConCambiosPrecios as $elemento) {
                        $data[] = [
                            'id' => $elemento->id,
                            'folio' => $elemento->folio,
                            'descripcion' => $elemento->nombre_proyecto,
                            'tipo_cambio' => 2,
                        ];
                    }
                }
            }

            // Cambios minimos
            // $cambiosMinimos = DB::table('OportunidadCambiosMinimos')
            //                     ->where('idOportunidad', $idOportunidad)
            //                     ->where('realizar', 1)
            //                     ->where('realizado', 0)
            //                     ->get();
            
            // if (count($cambiosMinimos) > 0) {
            //     foreach ($cambiosMinimos as $index => $cambio) {
            //         $data[] = [
            //             'id' => $cambio->idOportunidadCambiosMinimos,
            //             'folio' => $index + 1, // Asignar un folio secuencial
            //             'descripcion' => $cambio->cambio,
            //             'tipo_cambio' => 3,
            //         ];
            //     }
            // }

            // if ($oportunidad->flagCambioTexto == 1) {
            //     // Cambios de texto
            //     $data[] = [
            //         'id' => 0,
            //         'folio' => 1,
            //         'descripcion' => 'Cambio de texto (Se requiere aprovar el presupuesto)',
            //         'tipo_cambio' => 4,
            //     ];
            // }

            if (count($data) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay cambios pendientes para esta oportunidad',
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los cambios pendientes',
            ], 500);
        }
    }
}
