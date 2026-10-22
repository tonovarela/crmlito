<?php

namespace App\Http\Controllers;

use App\DetalleTacon1;
use App\DetalleTacon2;
use App\Http\Requests\StoreElementosPresupuestadosRequest;
use App\Http\Requests\StoreSolicitudCotizacionComercialRequest;
use App\Http\Requests\StoreSolicitudCotizacionDisplayRequest;
use App\Http\Requests\StoreSolicitudCotizacionEmpaqueRequest;
use App\Http\Requests\StoreSolicitudCotizacionMaquilaRequest;
use App\Http\Requests\StoreSolicitudCotizacionPopRequest;
use App\Http\Requests\StoreSolicitudCotizacionRequest;
use App\SolicitudCotizacion;
use App\SolicitudCotizacionComercial;
use App\SolicitudCotizacionDisplay;
use App\SolicitudCotizacionEmpaque;
use App\SolicitudCotizacionMaquila;
use App\SolicitudCotizacionPop;
use App\Traits\BitacoraSolicitudCotizacionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\OportunidadesController;
use App\Http\Modelos\CantidadesAdicionalesPresupuesto;
use App\Http\Modelos\catTipoDocumentoOport;
use App\Http\Modelos\DocumentosOport;
use App\Http\Modelos\GruposBitacoraCambios;
use App\Http\Modelos\MotivoCambioEspecificaciones;
use App\Oportunidades;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Support\Facades\Storage;

class ElementosPresupuestadosV2Controller extends Controller
{
    use BitacoraSolicitudCotizacionTrait;
    use PermisosTrait;
    use BitacoraTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = auth()->user()->rol->idRol;
        return view('elementos_presupuestados_v2.index', compact('role'));
    }

    /**
     * Display a listing of the resource sorted by a specific field.
     *
     * @return \Illuminate\Http\Response
     */
    public function sorted(Request $request)
    {
        try {
            $sortBy = $request->query('sort_by', 'id');
            $order = $request->query('order', 'asc');
            $search = $request->query('search', '');

            $role = auth()->user()->rol->idRol;
            $userId = auth()->user()->Id_Usuario;

            // Si es auxiliar
            if ($role == 9) {
                $agente = DB::table('rel_Agente_Aux')
                            ->where(function ($query) use ($userId) {
                                $query->where('idAuxiliar', $userId)
                                    ->orWhere('idAuxiliar1', $userId)
                                    ->orWhere('idAuxiliar2', $userId)
                                    ->orWhere('idAuxiliar3', $userId);
                            })->first();

                // Si se encuentra un agente asociado al auxiliar, obtenemos su ID
                if ($agente) {
                    $userId = $agente->idAgente;
                }
            }

            $elementos = DB::table('v_solicitudesCotizacion')
                                ->when($search, function ($query) use ($search) {
                                    return $query->where(function ($query) use ($search) {
                                        $query->where('nombre_proyecto', 'like', "%{$search}%")
                                            ->orWhere('folio', 'like', "%{$search}%")
                                            ->orWhere('vendedor', 'like', "%{$search}%")
                                            ->orWhere('cliente', 'like', "%{$search}%")
                                            ->orWhere('plantilla', 'like', "%{$search}%")
                                            ->orWhere('tipo_producto', 'like', "%{$search}%");
                                    });
                                })
                            ->when(!in_array($role, [1, 8, 10, 1013]), function ($query) use ($userId) {
                                return $query->where(function ($query) use ($userId) {
                                    $query->where('usuario_id', $userId)
                                        ->orWhere('vendedor_id', $userId);
                                });
                            })
                            ->where('estatus', 1)
                            ->orderBy($sortBy, $order)
                            ->paginate(10);

            return response()->json([
                'data' => $elementos,
                'title' => 'Lista de Elementos Presupuestados',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los elementos presupuestados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int|null  $idOportunidad
     * @param  int|null  $regresaOport
     * @return \Illuminate\Http\Response
     */
    public function create($idOportunidad = null, $regresaOport = null)
    {
        $oportunidad = collect();
        $agentesIds = [];

        $idUsuario = auth()->user()->Id_Usuario;
        $role = auth()->user()->rol->idRol;
        $agentesIds = [$idUsuario];

        // Si se proporciona un ID de oportunidad verificamos si existe
        if ($idOportunidad) {
            $oportunidad = DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->first();

            // Si la oportunidad existe, verificamos si el usuario tiene acceso
            if ($oportunidad) {

                // Si el usuario es auxiliar, buscamos el agente asociado
                if ($role == 9) {
                    $agentes = DB::table('rel_Agente_Aux')
                        ->where(function ($query) use ($idUsuario) {
                            $query->where('idAuxiliar', $idUsuario)
                                ->orWhere('idAuxiliar1', $idUsuario)
                                ->orWhere('idAuxiliar2', $idUsuario)
                                ->orWhere('idAuxiliar3', $idUsuario);
                        })->get()->pluck('idAgente')->toArray();

                    // Si se encuentra un agente asociado al auxiliar, obtenemos su ID
                    if (count($agentes) > 0) {
                        $agentesIds = array_merge($agentesIds, $agentes);
                    }
                }

                if (!in_array($role, [1, 8, 10])) {
                    if (!(in_array($oportunidad->idAgente, $agentesIds) || in_array($oportunidad->idUsuarioCreacion, $agentesIds))) {
                        $oportunidad = collect(); // Limpiamos la oportunidad si el usuario no tiene acceso
                    }
                }
            }
        }

        return view('elementos_presupuestados_v2.create', [
            'folio' => DB::table('solicitud_cotizacion')->max('folio') + 1,
            'oportunidad' => $oportunidad,
            'regresaOport' => $regresaOport,
            'idRol' => $role,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreElementosPresupuestadosRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Vamos decodificando los datos de la solicitud
        $generales = json_decode($request->input('general'));
        $pop = json_decode($request->input('pop'));
        $display = json_decode($request->input('display'));
        $empaque = json_decode($request->input('empaque'));
        $maquila = json_decode($request->input('maquila'));
        $comercial = json_decode($request->input('comercial'));

        // Validamos los campos generales
        $generalesVal = (array)$generales;
        $a = $generalesVal['cat_EP_tipoProducto_id'];
        $generalesVal['cat_EP_tipoProducto_id'] = explode('-', $a)[0]; // Extraer el ID de la plantilla del valor seleccionado
        $validatorGeneral = Validator::make($generalesVal, (new StoreSolicitudCotizacionRequest())->rules(), (new StoreSolicitudCotizacionRequest())->messages());

        if ($generales->plantilla_id == 4) {
            // Validamos los campos específicos de POP
            $validatorPop = Validator::make((array)$pop, (new StoreSolicitudCotizacionPopRequest())->rules(), (new StoreSolicitudCotizacionPopRequest())->messages());
        }
        
        if ($generales->plantilla_id == 3) {
            // Validamos los campos específicos de DISPLAY
            $validatorDisplay = Validator::make((array)$display, (new StoreSolicitudCotizacionDisplayRequest())->rules(), (new StoreSolicitudCotizacionDisplayRequest())->messages());
        }

        if ($generales->plantilla_id == 2) {
            // Validamos los campos específicos de EMPAQUE
            $dataEmpaque = (array)$empaque;
            if ($generalesVal['cat_EP_tipoProducto_id'] == 10) { // Si es bolsa no se valida
                $dataEmpaque['naTacon1'] = true;
                $dataEmpaque['naTacon2'] = true;
            } else {
                $dataEmpaque['naTacon1'] = $request->naTacon1 == "true" ? true : false;
                $dataEmpaque['naTacon2'] = $request->naTacon2 == "true" ? true : false;
            }
            $validatorEmpaque = Validator::make($dataEmpaque, (new StoreSolicitudCotizacionEmpaqueRequest())->rules(), (new StoreSolicitudCotizacionEmpaqueRequest())->messages());
        }

        if ($generales->plantilla_id == 6) {
            // Validamos los campos específicos de MAQUILA
            $validatorMaquila = Validator::make((array)$maquila, (new StoreSolicitudCotizacionMaquilaRequest())->rules(), (new StoreSolicitudCotizacionMaquilaRequest())->messages());
        }

        if ($generales->plantilla_id == 1) {
            // Validamos los campos específicos de COMERCIAL
            $validatorComercial = Validator::make(array_merge((array)$comercial, ['naEditorialForro' => $request->naEditorialForro, 'naEditorialGuarda' => $request->naEditorialGuarda, 'naAcabadoForro' => $request->naAcabadoForro]), (new StoreSolicitudCotizacionComercialRequest())->rules(), (new StoreSolicitudCotizacionComercialRequest())->messages());
        }

        // Si hay errores en la validación general o en la validación de POP, retornamos un error
        if ($validatorGeneral->fails() || 
            (isset($validatorPop) && $validatorPop->fails()) || 
            (isset($validatorDisplay) && $validatorDisplay->fails()) || 
            (isset($validatorEmpaque) && $validatorEmpaque->fails()) || 
            (isset($validatorMaquila) && $validatorMaquila->fails()) || 
            (isset($validatorComercial) && $validatorComercial->fails())) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'general' => $validatorGeneral->errors(),
                    'pop' => isset($validatorPop) ? $validatorPop->errors() : [],
                    'display' => isset($validatorDisplay) ? $validatorDisplay->errors() : [],
                    'empaque' => isset($validatorEmpaque) ? $validatorEmpaque->errors() : [],
                    'maquila' => isset($validatorMaquila) ? $validatorMaquila->errors() : [],
                    'comercial' => isset($validatorComercial) ? $validatorComercial->errors() : [],
                ],
            ], 422);
        }

        // Adicionamos los campos validados a la solicitud de cotización
        $validadosGeneral = array_merge((array)$generales, [
            'usuario_id' => auth()->id(),
            'folio' => DB::table('solicitud_cotizacion')->max('folio') + 1,
        ]);
        $validadosGeneral['cat_EP_tipoProducto_id'] = explode('-', $validadosGeneral['cat_EP_tipoProducto_id'])[0]; // Extraer el ID de la plantilla del valor seleccionado

        // Si hay validación de POP, la adicionamos también
        $validadosPop = isset($validatorPop) ? (array)$pop : [];

        // Si hay validación de DISPLAY, la adicionamos también
        $validadosDisplay = isset($validatorDisplay) ? (array)$display : [];

        // Si hay validación de EMPAQUE, la adicionamos también
        $validadosEmpaque = isset($validatorEmpaque) ? (array)$empaque : [];

        // Si hay validación de MAQUILA, la adicionamos también
        $validadosMaquila = isset($validatorMaquila) ? (array)$maquila : [];

        // Si hay validación de COMERCIAL, la adicionamos también
        $validadosComercial = isset($validatorComercial) ? (array)$comercial : [];

        try {
            // Registramos primero la solicitud de cotización
            $solicitud = (new SolicitudCotizacionController())->store($validadosGeneral);

            // Si la plantilla es POP, registramos los detalles de POP
            if ($generales->plantilla_id == 4) {
                $popDetalles = array_merge($validadosPop, [
                    'solicitud_cotizacion_id' => $solicitud->id,
                ]);
                (new SolicitudCotizacionPopController())->store($popDetalles);
            }

            // Si la plantilla es DISPLAY, registramos los detalles de DISPLAY
            if ($generales->plantilla_id == 3) {
                $displayDetalles = array_merge($validadosDisplay, [
                    'solicitud_cotizacion_id' => $solicitud->id,
                ]);
                (new SolicitudCotizacionDisplayController())->store($displayDetalles);
            }

            // Si la plantilla es EMPAQUE, registramos los detalles de EMPAQUE
            if ($generales->plantilla_id == 2) {
                $empaqueGeneral = [];
                $empaqueT1 = [];
                $empaqueT2 = [];

                foreach ($validadosEmpaque as $key => $value) {
                    if (substr($key, -3) === '_t1') {
                        $empaqueT1[$key] = $value;
                    } elseif (substr($key, -3) === '_t2') {
                        $empaqueT2[$key] = $value;
                    } else {
                        $empaqueGeneral[$key] = $value;
                    }
                }

                $empaqueDetalles = array_merge($empaqueGeneral, [
                    'solicitud_cotizacion_id' => $solicitud->id,
                    'na_tacon_1' => ($request->naTacon1 === 'true') ? 1 : 0,
                    'na_tacon_2' => ($request->naTacon2 === 'true') ? 1 : 0,
                ]);
                $se = (new SolicitudCotizacionEmpaqueController())->store($empaqueDetalles);

                // Registramos los detalles de TACO TIPO 1 si existen
                if (!empty($empaqueT1)) {
                    $empaqueT1Detalles = array_merge($empaqueT1, [
                        'solicitud_cotizacion_empaque_id' => $se->id,
                    ]);
                    (new DetalleTacon1Controller())->store($empaqueT1Detalles);
                }
                
                // Registramos los detalles de TACO TIPO 2 si existen
                if (!empty($empaqueT2)) {
                    $empaqueT2Detalles = array_merge($empaqueT2, [
                        'solicitud_cotizacion_empaque_id' => $se->id,
                    ]);
                    (new DetalleTacon2Controller())->store($empaqueT2Detalles);
                }
            }

            // Si la plantilla es MAQUILA, registramos los detalles de MAQUILA
            if ($generales->plantilla_id == 6) {
                $maquilaDetalles = array_merge($validadosMaquila, [
                    'solicitud_cotizacion_id' => $solicitud->id,
                ]);
                (new SolicitudCotizacionMaquilaController())->store($maquilaDetalles);
            }

            // Si la plantilla es COMERCIAL, registramos los detalles de COMERCIAL
            if ($generales->plantilla_id == 1) {
                $comercialDetalles = array_merge($validadosComercial, [
                    'solicitud_cotizacion_id' => $solicitud->id,
                    'na_editorial_forro' => ($request->naEditorialForro === 'true') ? 1 : 0,
                    'na_editorial_guarda' => ($request->naEditorialGuarda === 'true') ? 1 : 0,
                    'na_acabado_forro' => ($request->naAcabadoForro === 'true') ? 1 : 0,
                ]);
                (new SolicitudCotizacionComercialController())->store($comercialDetalles);
            }

            $solicitudCreada = DB::table('v_solicitudesCotizacion')
                ->where('id', $solicitud->id)
                ->first();

            // Registramos la bitácora de la solicitud de cotización
            $this->registrarBitacoraSolicitudCotizacion(
                'Creación de Solicitud de Cotización - ' . $solicitudCreada->plantilla,
                null,
                'Registro de solicitud de cotización para ' . $solicitudCreada->plantilla . ' con ID: ' . $solicitud->id. ', Valores: ' . json_encode($request->all()),
                $solicitud->id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'success' => true,
                'message' => 'Elemento presupuestado guardado correctamente',
                'data' => $solicitud,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el elemento presupuestado',
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param  int  $fromOport
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $fromOport = null)
    {
        $userId = auth()->user()->Id_Usuario;
        $role = auth()->user()->rol->idRol;
        $agentesIds = [$userId];

        // Si es auxiliar
        if ($role == 9) {
            $agentes = DB::table('rel_Agente_Aux')
                        ->where(function ($query) use ($userId) {
                            $query->where('idAuxiliar', $userId)
                                ->orWhere('idAuxiliar1', $userId)
                                ->orWhere('idAuxiliar2', $userId)
                                ->orWhere('idAuxiliar3', $userId);
                        })->get()->pluck('idAgente')->toArray();

            // Si se encuentra un agente asociado al auxiliar, obtenemos su ID
            if (count($agentes) > 0) {
                $agentesIds = array_merge($agentesIds, $agentes);
            }
        }

        $data = [];

        $solicitud = DB::table('v_solicitudesCotizacion')
                        ->when(!in_array($role, [1, 8, 10, 2, 3, 1013]), function ($query) use ($agentesIds) {
                            return $query->where(function ($query) use ($agentesIds) {
                                $query->whereIn('usuario_id', $agentesIds)
                                    ->orWhereIn('vendedor_id', $agentesIds);
                            });
                        })
                        ->where('id', $id)
                        ->first();

        if (!$solicitud) {
            return redirect()->back();
        }

        $solicitudPop = SolicitudCotizacionPop::where('solicitud_cotizacion_id', $id)->first();
        $solicitudDisplay = SolicitudCotizacionDisplay::where('solicitud_cotizacion_id', $id)->first();
        $solicitudMaquila = SolicitudCotizacionMaquila::where('solicitud_cotizacion_id', $id)->first();
        $solicitudComercial = SolicitudCotizacionComercial::where('solicitud_cotizacion_id', $id)->first();
        $dataCambios = MotivoCambioEspecificaciones::where('solicitud_cotizacion_id', $id)->first();

        $solicitudEmpaqueCompleto = DB::table('v_SolicitudEmpaqueCompleto')
                                ->where('solicitud_cotizacion_id', $id)->first();

        $grupo = GruposBitacoraCambios::where('solicitudCotizacionId', $id)->where('estatus', 0)->orderBy('id', 'desc')->first();

        $observacionesAdicionales = DB::table('observacionesExtrasEP')
                                    ->where('solicitudCotizacionId', $id)
                                    ->get();

        if ($grupo) {
            $bitacoraCambios = DB::table('v_BitacoraCambiosEspMin')->where('grupoId', $grupo->id)->orderBy('fechaRegistro', 'desc')->get();
        }

        $oportunidad = Oportunidades::where('idOportunidad', $solicitud->oportunidad_id)->first();

        $data['solicitud'] = [
            'solicitud' => $solicitud,
            'solicitudPop' => $solicitudPop,
            'solicitudDisplay' => $solicitudDisplay,
            'solicitudEmpaque' => $solicitudEmpaqueCompleto,
            'solicitudMaquila' => $solicitudMaquila,
            'solicitudComercial' => $solicitudComercial,
            'fromOport' => $fromOport,
            'dataCambios' => $dataCambios,
            'role' => $role,
            'oportunidad' => $oportunidad,
            'camposQueCambiaron' => isset($bitacoraCambios) ? $bitacoraCambios->pluck('campo')->unique() : [],
            'bitacoraCambios' => $bitacoraCambios ?? collect(),
            'observacionesAdicionales' => $observacionesAdicionales->count() > 0 ? $observacionesAdicionales->pluck('observacion') : collect(),
        ];

        // eliminamos los vacios
        $data['solicitud'] = array_filter($data['solicitud'], function ($value) {
            return !is_null($value) && $value !== '';
        });

        return view('elementos_presupuestados_v2.edit', $data);
    }

    /**
     * Función para tratar el registro de la bitacora de cambios realizados en el EP
     * 
     * @param Request $request
     * @param int $idSolicitud
     * @return void
     */
    private function bitacoraCambiosEspecificacionesYMinimos(Request $request, int $idSolicitud)
    {
        try {
            $seRealizoCambioMinimo = $request->seRealizoCambioMinimo;
            $seRealizoCambioEspecificaciones = $request->seRealizoCambioEspecificaciones;

            $seSolicitaCammbioTexto = $request->esParaCambioTexto == "true" ? true : false;

            if ($seSolicitaCammbioTexto && ($seRealizoCambioMinimo == 'false' && $seRealizoCambioEspecificaciones == 'false')) {
                $general = json_decode($request->general, true);
                
                $solicitud = DB::table('v_solicitudesCotizacion')->where('id', $idSolicitud)->first();

                // Actualizamos la bandera de cambio de texto en la solicitud
                DB::table('solicitud_cotizacion')->where('id', $idSolicitud)->update(['tiene_cambio_texto' => 1, 'resolvio_cambio_texto' => 0]);

                if ($solicitud->observaciones_presupuesto != $general['observaciones_presupuesto']) {
                    (new OportunidadesController())->cambioTexto($general['oportunidad_id']);
                }
            } else {
                $idUsuario = $this->idUsuario();
                $solicitud = SolicitudCotizacion::where('id', $idSolicitud)->first();
    
                $observacionesExtras = json_decode($request->observacionesExtras, true) ?? [];
                $bitacoraCambiosGeneral = json_decode($request->bitacoraCambiosGeneral, true) ?? [];
                $bitacoraCambiosComercial = json_decode($request->bitacoraCambiosComercial, true) ?? [];
                $bitacoraCambiosDisplay = json_decode($request->bitacoraCambiosDisplay, true) ?? [];
                $bitacoraCambiosEmpaque = json_decode($request->bitacoraCambiosEmpaque, true) ?? [];
                $bitacoraCambiosPop = json_decode($request->bitacoraCambiosPop, true) ?? [];
                $bitacoraCambiosMaquila = json_decode($request->bitacoraCambiosMaquila, true) ?? [];
    
                if ($solicitud->plantilla_id == 1) {
                    $bitacoraFinal = array_merge($bitacoraCambiosGeneral, $bitacoraCambiosComercial);
                }
    
                if ($solicitud->plantilla_id == 3) {
                    $bitacoraFinal = array_merge($bitacoraCambiosGeneral, $bitacoraCambiosDisplay);
                }
    
                if ($solicitud->plantilla_id == 2) {
                    $bitacoraFinal = array_merge($bitacoraCambiosGeneral, $bitacoraCambiosEmpaque);
                }
    
                if ($solicitud->plantilla_id == 4) {
                    $bitacoraFinal = array_merge($bitacoraCambiosGeneral, $bitacoraCambiosPop);
                }
                
                if ($solicitud->plantilla_id == 6) {
                    $bitacoraFinal = array_merge($bitacoraCambiosGeneral, $bitacoraCambiosMaquila);
                }
    
                if (count($bitacoraFinal) > 0) {
                    // Obtenemos el ultimo grupo de cambios registrado y no finalizado
                    $grupoId = GruposBitacoraCambios::firstOrCreate([
                                    'solicitudCotizacionId' => $idSolicitud,
                                    'idUsuario' => $idUsuario,
                                    'estatus' => 0,
                                    'ciclo' => DB::table('gruposBitacoraCambios')->where('solicitudCotizacionId', $idSolicitud)->max('ciclo') + 1,
                                ])->id;
                
                    // Registramos los cambios en la bitacora
                    foreach ($bitacoraFinal as $cambio) {
                        DB::table('bitacoraCambiosMinEsp')->insert([
                            'valorAnterior' => $cambio['valorAnterior'],
                            'valorNuevo' => $cambio['valorNuevo'],
                            'campo' => $cambio['campo'],
                            'tipo' => $cambio['tipo'],
                            'tipoCampo' => $cambio['tipoCampo'],
                            'idUsuario' => $idUsuario,
                            'categoria' => $cambio['categoria'],
                            'campoEtiqueta' => $cambio['campoEtiqueta'],
                            'grupoId' => $grupoId,
                        ]);
                    }
                }
    
                // Guardado de observaciones extras
                if (count($observacionesExtras) > 0) {
                    // Antes de que se realice el guardado, eliminamos las observaciones extras previas
                    DB::table('observacionesExtrasEP')->where('solicitudCotizacionId', $idSolicitud)->delete();
    
                    // Realizamos el guardado de las observaciones extras
                    foreach ($observacionesExtras as $obs) {
                        DB::table('observacionesExtrasEP')->insert([
                            'observacion' => $obs,
                            'solicitudCotizacionId' => $idSolicitud,
                            'idUsuario' => $idUsuario,
                        ]);
                    }
                }
    
                // cambio de banderas para la solicitud de cambios minimos y de especificaciones
                if ((($seRealizoCambioEspecificaciones == "true" && $seRealizoCambioMinimo == "false") || ($seRealizoCambioEspecificaciones == "true" && $seRealizoCambioMinimo == "true")) || count($observacionesExtras) > 0) {
                    $solicitud->tiene_cambio_especificaciones = 1;
                    $solicitud->tiene_cambios_minimos = 0;
                    $solicitud->resolvio_cambio_especificaciones = 0;
                    $solicitud->cambio_prioridad = 2;
                }
    
                if ($seRealizoCambioMinimo == "true" && $seRealizoCambioEspecificaciones == "false") {
                    $solicitud->tiene_cambios_minimos = 1;
                    $solicitud->tiene_cambio_especificaciones = 0;
                    $solicitud->resolvio_cambios_minimos = 0;
                    $solicitud->cambio_prioridad = 1;
                }
    
                $solicitud->save();
    
                // Actualizamos la bandera de precambios en la oportunidad
                if ($solicitud->oportunidad_id) {
                    $oportunidad = Oportunidades::where('idOportunidad', $solicitud->oportunidad_id)->first();
                    if ($oportunidad) {
                        $oportunidad->preSolicitoCambios = 1;
                        $oportunidad->solicitoCambios = 0;
                        $oportunidad->finalizoCambios = 0;
                        $oportunidad->save();
                    }
                }
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la bitácora de cambios',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $previoGeneral = DB::table('v_solicitudesCotizacion')->where('id', $id)->first();

        // Vamos decodificando los datos de la solicitud
        $generales = json_decode($request->input('general'));
        $pop = json_decode($request->input('pop'));
        $display = json_decode($request->input('display'));
        $empaque = json_decode($request->input('empaque'));
        $maquila = json_decode($request->input('maquila'));
        $comercial = json_decode($request->input('comercial'));

        $estatusOportunidad = $request->estatusOportunidad;

        // Tratamiento de la bitacora de cambios de especificaciones y mínimos
        if ($estatusOportunidad == 8 || $estatusOportunidad == 7) {
            $this->bitacoraCambiosEspecificacionesYMinimos($request, $id);
        }
        
        // Validamos los campos generales
        $generalesVal = (array)$generales;
        $a = $generalesVal['cat_EP_tipoProducto_id'];
        $generalesVal['cat_EP_tipoProducto_id'] = explode('-', $a)[0]; // Extraer el ID de la plantilla del valor seleccionado
        $validatorGeneral = Validator::make($generalesVal, (new StoreSolicitudCotizacionRequest())->rules(), (new StoreSolicitudCotizacionRequest())->messages());

        if ($generales->plantilla_id == 4) {
            // Validamos los campos específicos de POP
            $validatorPop = Validator::make((array)$pop, (new StoreSolicitudCotizacionPopRequest())->rules(), (new StoreSolicitudCotizacionPopRequest())->messages());
        }
        
        if ($generales->plantilla_id == 3) {
            // Validamos los campos específicos de DISPLAY
            $validatorDisplay = Validator::make((array)$display, (new StoreSolicitudCotizacionDisplayRequest())->rules(), (new StoreSolicitudCotizacionDisplayRequest())->messages());
        }

        if ($generales->plantilla_id == 2) {
            // Validamos los campos específicos de EMPAQUE
            $dataEmpaque = (array)$empaque;

            if ($generalesVal['cat_EP_tipoProducto_id'] == 10) { // Si es bolsa no se valida
                $dataEmpaque['naTacon1'] = true;
                $dataEmpaque['naTacon2'] = true;
            } else {
                $dataEmpaque['naTacon1'] = $request->naTacon1 == "true" ? true : false;
                $dataEmpaque['naTacon2'] = $request->naTacon2 == "true" ? true : false;
            }

            $validatorEmpaque = Validator::make($dataEmpaque, (new StoreSolicitudCotizacionEmpaqueRequest())->rules(), (new StoreSolicitudCotizacionEmpaqueRequest())->messages());
        }

        if ($generales->plantilla_id == 6) {
            // Validamos los campos específicos de MAQUILA
            $validatorMaquila = Validator::make((array)$maquila, (new StoreSolicitudCotizacionMaquilaRequest())->rules(), (new StoreSolicitudCotizacionMaquilaRequest())->messages());
        }

        if ($generales->plantilla_id == 1) {
            // Validamos los campos específicos de COMERCIAL
            $validatorComercial = Validator::make(array_merge((array)$comercial, ['naEditorialForro' => $request->naEditorialForro, 'naEditorialGuarda' => $request->naEditorialGuarda, 'naAcabadoForro' => $request->naAcabadoForro]), (new StoreSolicitudCotizacionComercialRequest())->rules(), (new StoreSolicitudCotizacionComercialRequest())->messages());
        }

        // Si hay errores en la validación general o en la validación de POP, retornamos un error
        if ($validatorGeneral->fails() || 
            (isset($validatorPop) && $validatorPop->fails()) || 
            (isset($validatorDisplay) && $validatorDisplay->fails()) || 
            (isset($validatorEmpaque) && $validatorEmpaque->fails()) || 
            (isset($validatorMaquila) && $validatorMaquila->fails()) || 
            (isset($validatorComercial) && $validatorComercial->fails())) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'general' => $validatorGeneral->errors(),
                    'pop' => isset($validatorPop) ? $validatorPop->errors() : [],
                    'display' => isset($validatorDisplay) ? $validatorDisplay->errors() : [],
                    'empaque' => isset($validatorEmpaque) ? $validatorEmpaque->errors() : [],
                    'maquila' => isset($validatorMaquila) ? $validatorMaquila->errors() : [],
                    'comercial' => isset($validatorComercial) ? $validatorComercial->errors() : [],
                ],
            ], 422);
        }

        // Adicionamos los campos validados a la solicitud de cotización
        $validadosGeneral = (array)$generales;
        $validadosGeneral['cat_EP_tipoProducto_id'] = explode('-', $validadosGeneral['cat_EP_tipoProducto_id'])[0]; // Extraer el ID de la plantilla del valor seleccionado

        // Si hay validación de POP, la adicionamos también
        $validadosPop = isset($validatorPop) ? (array)$pop : [];

        // Si hay validación de DISPLAY, la adicionamos también
        $validadosDisplay = isset($validatorDisplay) ? (array)$display : [];

        // Si hay validación de EMPAQUE, la adicionamos también
        $validadosEmpaque = isset($validatorEmpaque) ? (array)$empaque : [];

        // Si hay validación de MAQUILA, la adicionamos también
        $validadosMaquila = isset($validatorMaquila) ? (array)$maquila : [];

        // Si hay validación de COMERCIAL, la adicionamos también
        $validadosComercial = isset($validatorComercial) ? (array)$comercial : [];

        try {
            // Actualizamos primero la solicitud de cotización
            $solicitud = (new SolicitudCotizacionController())->update($validadosGeneral, $id);

            // Si la plantilla es POP, actualizamos los detalles de POP
            if ($generales->plantilla_id == 4) {
                $previoPlantilla = SolicitudCotizacionPop::where('solicitud_cotizacion_id', $solicitud->id)->first();

                (new SolicitudCotizacionPopController())->update($validadosPop, $solicitud->id);
            }

            // Si la plantilla es DISPLAY, actualizamos los detalles de DISPLAY
            if ($generales->plantilla_id == 3) {
                $previoPlantilla = SolicitudCotizacionDisplay::where('solicitud_cotizacion_id', $solicitud->id)->first();

                (new SolicitudCotizacionDisplayController())->update($validadosDisplay, $solicitud->id);
            }

            // Si la plantilla es EMPAQUE, actualizamos los detalles de EMPAQUE
            if ($generales->plantilla_id == 2) {
                $previoPlantilla = SolicitudCotizacionEmpaque::where('solicitud_cotizacion_id', $solicitud->id)->first();

                $empaqueGeneral = [];
                $empaqueT1 = [];
                $empaqueT2 = [];

                foreach ($validadosEmpaque as $key => $value) {
                    if (substr($key, -3) === '_t1') {
                        $empaqueT1[$key] = $value;
                    } elseif (substr($key, -3) === '_t2') {
                        $empaqueT2[$key] = $value;
                    } else {
                        $empaqueGeneral[$key] = $value;
                    }
                }

                $empaqueDetalles = array_merge($empaqueGeneral, [
                    'solicitud_cotizacion_id' => $solicitud->id,
                    'na_tacon_1' => ($request->naTacon1 === 'true') ? 1 : 0,
                    'na_tacon_2' => ($request->naTacon2 === 'true') ? 1 : 0,
                ]);
                $se = (new SolicitudCotizacionEmpaqueController())->update($empaqueDetalles, $solicitud->id);

                // Registramos los detalles de TACO TIPO 1 si existen
                if (!empty($empaqueT1)) {
                    $empaqueT1Detalles = array_merge($empaqueT1, [
                        'solicitud_cotizacion_empaque_id' => $se->id,
                    ]);
                    (new DetalleTacon1Controller())->update($empaqueT1Detalles, $se->id);
                }
                
                // Registramos los detalles de TACO TIPO 2 si existen
                if (!empty($empaqueT2)) {
                    $empaqueT2Detalles = array_merge($empaqueT2, [
                        'solicitud_cotizacion_empaque_id' => $se->id,
                    ]);
                    (new DetalleTacon2Controller())->update($empaqueT2Detalles, $se->id);
                }
            }

            // Si la plantilla es MAQUILA, actualizamos los detalles de MAQUILA
            if ($generales->plantilla_id == 6) {
                $previoPlantilla = SolicitudCotizacionMaquila::where('solicitud_cotizacion_id', $solicitud->id)->first();

                (new SolicitudCotizacionMaquilaController())->update($validadosMaquila, $solicitud->id);
            }

            // Si la plantilla es COMERCIAL, actualizamos los detalles de COMERCIAL
            if ($generales->plantilla_id == 1) {
                $previoPlantilla = SolicitudCotizacionComercial::where('solicitud_cotizacion_id', $solicitud->id)->first();

                $comercialDetalles = array_merge($validadosComercial, [
                    'na_editorial_forro' => ($request->naEditorialForro === 'true') ? 1 : 0,
                    'na_editorial_guarda' => ($request->naEditorialGuarda === 'true') ? 1 : 0,
                    'na_acabado_forro' => ($request->naAcabadoForro === 'true') ? 1 : 0,
                ]);

                (new SolicitudCotizacionComercialController())->update($comercialDetalles, $solicitud->id);
            }

            $solicitudActualizada = SolicitudCotizacion::where('id', $solicitud->id)->first();

            // Registramos la bitácora de la solicitud de cotización
            $this->registrarBitacoraSolicitudCotizacion(
                'Actualización de Solicitud de Cotización - ' . $solicitudActualizada->plantilla,
                json_encode($previoGeneral) . ' | ' . json_encode($previoPlantilla),
                'Actualización de solicitud de cotización para ' . $solicitudActualizada->plantilla . ' con ID: ' . $solicitud->id. ', Valores: ' . json_encode($request->all()),
                $solicitud->id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cotización actualizada correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de cotización',
            ], 500);
        }
    }

    /**
     * Función para generar el PDF del elemento presupuestado (interno solo detalles).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarPdfElementoInter($id)
    {
        try {
            $solicitud = DB::table('v_solicitudesCotizacion')
                            ->where('id', $id)
                            ->first();

            $solicitudPop = DB::table('v_SolicitudCotizacionPop')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudDisplay = DB::table('v_SolicitudCotizacionDisplay')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudEmpaque = DB::table('v_SolicitudCotizacionEmpaque')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudMaquila = DB::table('v_SolicitudCotizacionMaquila')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudComercial = DB::table('v_SolicitudCotizacionComercial')->where('solicitud_cotizacion_id', $id)->first();

            $data['solicitud'] = [
                'solicitud' => $solicitud,
                'solicitudPop' => $solicitudPop,
                'solicitudDisplay' => $solicitudDisplay,
                'solicitudEmpaque' => $solicitudEmpaque,
                'solicitudMaquila' => $solicitudMaquila,
                'solicitudComercial' => $solicitudComercial,
            ];

            // eliminamos los vacios
            $data['solicitud'] = array_filter($data['solicitud'], function ($value) {
                return !is_null($value) && $value !== '';
            });

            // Generamos el PDF utilizando la vista y los datos obtenidos
            $dompdf = App::make("dompdf.wrapper");
            $dompdf->loadView("pdf/elementosv2", ['dto' => $data]);

            // return $dompdf->download("ElementoPresupuestado.pdf");//Para descargar
            return $dompdf->stream(); //Para visualizar en el navegador
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF',
                'error' => 'La solicitud no existe o no tiene detalles',
            ], 500);
        }
    }

    /**
     * Función para generar el PDF del elemento presupuestado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generarPdfElemento($id)
    {
        try {
            $userId = auth()->user()->Id_Usuario;
            $role = auth()->user()->rol->idRol;
            $agentesIds = [$userId];

            // Si es auxiliar
            if ($role == 9) {
                $agentes = DB::table('rel_Agente_Aux')
                            ->where(function ($query) use ($userId) {
                                $query->where('idAuxiliar', $userId)
                                    ->orWhere('idAuxiliar1', $userId)
                                    ->orWhere('idAuxiliar2', $userId)
                                    ->orWhere('idAuxiliar3', $userId);
                            })->get()->pluck('idAgente')->toArray();

                // Si se encuentra un agente asociado al auxiliar, obtenemos su ID
                if (count($agentes) > 0) {
                    $agentesIds = array_merge($agentesIds, $agentes);
                }
            }

            $data = [];

            $solicitud = DB::table('v_solicitudesCotizacion')
                            ->when(!in_array($role, [1, 8, 10, 2, 3, 1013]), function ($query) use ($agentesIds) {
                                return $query->where(function ($query) use ($agentesIds) {
                                    $query->whereIn('usuario_id', $agentesIds)
                                        ->orWhereIn('vendedor_id', $agentesIds);
                                });
                            })
                            ->where('id', $id)
                            ->first();

            if (!$solicitud) {
                return redirect()->back();
            }

            $solicitudPop = DB::table('v_SolicitudCotizacionPop')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudDisplay = DB::table('v_SolicitudCotizacionDisplay')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudEmpaque = DB::table('v_SolicitudCotizacionEmpaque')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudMaquila = DB::table('v_SolicitudCotizacionMaquila')->where('solicitud_cotizacion_id', $id)->first();
            $solicitudComercial = DB::table('v_SolicitudCotizacionComercial')->where('solicitud_cotizacion_id', $id)->first();

            $data['solicitud'] = [
                'solicitud' => $solicitud,
                'solicitudPop' => $solicitudPop,
                'solicitudDisplay' => $solicitudDisplay,
                'solicitudEmpaque' => $solicitudEmpaque,
                'solicitudMaquila' => $solicitudMaquila,
                'solicitudComercial' => $solicitudComercial,
            ];

            // eliminamos los vacios
            $data['solicitud'] = array_filter($data['solicitud'], function ($value) {
                return !is_null($value) && $value !== '';
            });

            // Generamos el PDF utilizando la vista y los datos obtenidos
            $dompdf = App::make("dompdf.wrapper");
            $dompdf->loadView("pdf/elementosv2", ['dto' => $data]);

            // return $dompdf->download("ElementoPresupuestado.pdf");//Para descargar
            return $dompdf->stream(); //Para visualizar en el navegador
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Función para realizar el cambio de estatus de una solicitud de cotización.
     *
     * @param Request $request
     * @return mixed
     */
    public function changeEstatusEP(Request $request)
    {
        $id = $request->input('id');
        $estatus = $request->input('estatus');

        try {
            $solicitud = SolicitudCotizacion::findOrFail($id);
            $solicitud->estatus = $estatus;
            $solicitud->save();

            // Validamos si ya no hay mas elementos relacionados a esta oportunidad
            $oportunidadId = $solicitud->oportunidad_id;

            $elementosRelacionados = SolicitudCotizacion::where('oportunidad_id', $oportunidadId)
                ->where('estatus', 1)
                ->count();

            if ($elementosRelacionados == 0) {
                // Si no hay más elementos, actualizamos el flag de cambio de precios
                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidadId)
                    ->update([
                        'flagCambioPrecios' => 0,
                        'flagCambioEspecificaciones' => 0,
                    ]);
            }

            // Registramos la bitácora de cambio de estatus
            $this->registrarBitacoraSolicitudCotizacion(
                'Cambio de Estatus de Solicitud de Cotización',
                null,
                'Cambio de estatus a ' . $estatus . ' para la solicitud de cotización con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Estatus actualizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al actualizar el estatus',
            ], 500);
        }
    }

    /**
     * Función para cambiar los precios de un elemento presupuestado.
     *
     * @param Request $request
     * @return mixed
     */
    public function guardarCambiosPrecios(Request $request)
    {
        $cambios = json_decode($request->input('cambios'), true);

        try {
            foreach ($cambios as $key => $value) {
                $elemento = SolicitudCotizacion::findOrFail($key);
                $elemento->precio_anterior = $value['precioAnterior'];
                $elemento->precio_nuevo = $value['precioNuevo'];
                $elemento->tiene_cambio_precios = 1; // Indicamos que hay un cambio de precios
                $elemento->resolvio_cambio_precios = 0; // Reseteamos el flag de resolución de cambio de precios
                $elemento->save();

                DB::table('Oportunidades')
                    ->where('idOportunidad', $elemento->oportunidad_id)
                    ->update(['flagCambioPrecios' => 1]);

                (new OportunidadesController())->cambiarAListoParaCotizar($elemento->oportunidad_id);

                $this->registrarBitacoraSolicitudCotizacion(
                    'Cambio de Precio Anterior',
                    null,
                    'Cambio de precio anterior a ' . $value['precioAnterior'] . ' y precio nuevo a ' . $value['precioNuevo'] . ' para el elemento presupuestado con ID: ' . $key,
                    $key,
                    auth()->user()->Id_Usuario
                );
            }

            return response()->json([
                'response' => true,
                'message' => 'Precio anterior actualizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al actualizar el precio anterior',
            ], 500);
        }
    }

    /**
     * Función para cambiar los precios de un elemento presupuestado.
     *
     * @param Request $request
     * @return mixed
     */
    public function changePrecios(Request $request)
    {
        $id = $request->input('id');
        $precioAnterior = $request->input('precioAnterior');
        $precioNuevo = $request->input('precioNuevo');

        try {
            $elemento = SolicitudCotizacion::findOrFail($id);
            $elemento->precio_anterior = $precioAnterior;
            $elemento->precio_nuevo = $precioNuevo;
            $elemento->tiene_cambio_precios = 1; // Indicamos que hay un cambio de precios
            $elemento->resolvio_cambio_precios = 0; // Reseteamos el flag de resolución de cambio de precios
            $elemento->save();

            DB::table('Oportunidades')
                ->where('idOportunidad', $elemento->oportunidad_id)
                ->update(['flagCambioPrecios' => 1]);

            (new OportunidadesController())->cambiarAListoParaCotizar($elemento->oportunidad_id);

            $this->registrarBitacoraSolicitudCotizacion(
                'Cambio de Precio Anterior',
                null,
                'Cambio de precio anterior a ' . $precioAnterior . ' y precio nuevo a ' . $precioNuevo . ' para el elemento presupuestado con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Precio anterior actualizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al actualizar el precio anterior',
            ], 500);
        }
    }

    /**
     * Función para cambiar el precio anterior de un elemento presupuestado.
     *
     * @param Request $request
     * @return mixed
     */
    public function changePrecioAnterior(Request $request)
    {
        $id = $request->input('id');
        $valor = $request->input('valor');

        try {
            $elemento = SolicitudCotizacion::findOrFail($id);
            $elemento->precio_anterior = $valor;
            $elemento->tiene_cambio_precios = 1; // Indicamos que hay un cambio de precios
            $elemento->resolvio_cambio_precios = 0; // Reseteamos el flag de resolución de cambio de precios
            $elemento->save();

            DB::table('Oportunidades')
                ->where('idOportunidad', $elemento->oportunidad_id)
                ->update(['flagCambioPrecios' => 1]);

            (new OportunidadesController())->cambiarAListoParaCotizar($elemento->oportunidad_id);

            $this->registrarBitacoraSolicitudCotizacion(
                'Cambio de Precio Anterior',
                null,
                'Cambio de precio anterior a ' . $valor . ' para el elemento presupuestado con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Precio anterior actualizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al actualizar el precio anterior',
            ], 500);
        }
    }

    /**
     * Función para cambiar el precio nuevo de un elemento presupuestado.
     *
     * @param Request $request
     * @return mixed
     */
    public function changePrecioNuevo(Request $request)
    {
        $id = $request->input('id');
        $valor = $request->input('valor');

        try {
            $elemento = SolicitudCotizacion::findOrFail($id);
            $elemento->precio_anterior = $elemento->precio_nuevo; // Guardamos el precio nuevo como precio anterior
            $previoGeneral = $elemento->precio_nuevo; // Guardamos el precio nuevo previo para la bitácora
            $elemento->precio_nuevo = $valor;
            $elemento->tiene_cambio_precios = 1; // Indicamos que hay un cambio de precios
            $elemento->resolvio_cambio_precios = 0; // Reseteamos el flag de resolución de cambio de precios
            $elemento->save();

            DB::table('Oportunidades')
                ->where('idOportunidad', $elemento->oportunidad_id)
                ->update(['flagCambioPrecios' => 1]);

            (new OportunidadesController())->cambiarAListoParaCotizar($elemento->oportunidad_id);

            $this->registrarBitacoraSolicitudCotizacion(
                'Cambio de Precio Nuevo',
                'Precio Anterior: ' . $previoGeneral . ' | Precio Nuevo: ' . $valor,
                'Cambio de precio nuevo a ' . $valor . ' para el elemento presupuestado con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Precio nuevo actualizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al actualizar el precio nuevo',
            ], 500);
        }
    }

    /**
     * Función para marcar como realizado el cambio de especificaciones de un elemento presupuestado.
     * 
     * @param Request $request
     * @return mixed
     */
    public function marcarRealizadoCE(Request $request)
    {
        $id = $request->input('id');

        try {
            $elemento = SolicitudCotizacion::findOrFail($id);
            $elemento->resolvio_cambio_especificaciones = 0;

            // Validamos si ya no hay más elementos relacionados a esta oportunidad
            $oportunidadId = $elemento->oportunidad_id;

            $elementosRelacionados = SolicitudCotizacion::where('oportunidad_id', $oportunidadId)
                ->where('resolvio_cambio_especificaciones', 1)
                ->count();

            $elemento->tiene_cambio_especificaciones = 2; // Reseteamos el flag de cambio de especificaciones
            $elemento->save();

            if ($elementosRelacionados === 0) {
                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidadId)
                    ->update(['flagCambioEspecificaciones' => 0]);
            } else {
                DB::table('Oportunidades')
                ->where('idOportunidad', $oportunidadId)
                ->update(['flagCambioEspecificaciones' => 1]);
            }

            // Registramos la bitácora de marcado como realizado
            $this->registrarBitacoraSolicitudCotizacion(
                'Marcado como Realizado',
                null,
                'Marcado como realizado el cambio de precios al elemento presupuestado con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Elemento presupuestado marcado como realizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al marcar el elemento presupuestado como realizado',
            ], 500);
        }
    }

    /**
     * Función para marcar como realizado el cambio de precios de un elemento presupuestado.
     * 
     * @param Request $request
     * @return mixed
     */
    public function marcarRealizado(Request $request)
    {
        $id = $request->input('id');

        try {
            $elemento = SolicitudCotizacion::findOrFail($id);
            $elemento->resolvio_cambio_precios = 0;

            // Validamos si ya no hay más elementos relacionados a esta oportunidad
            $oportunidadId = $elemento->oportunidad_id;

            $elemento->tiene_cambio_precios = 2; // Reseteamos el flag de cambio de precios
            $elemento->save();

            $elementosRelacionados = SolicitudCotizacion::where('oportunidad_id', $oportunidadId)
                ->where('resolvio_cambio_precios', 1)
                ->count();

            if ($elementosRelacionados === 0) {
                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidadId)
                    ->update(['flagCambioPrecios' => 0]);
            } else {
                DB::table('Oportunidades')
                ->where('idOportunidad', $oportunidadId)
                ->update(['flagCambioPrecios' => 1]);
            }

            // Registramos la bitácora de marcado como realizado
            $this->registrarBitacoraSolicitudCotizacion(
                'Marcado como Realizado',
                null,
                'Marcado como realizado el cambio de precios al elemento presupuestado con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Elemento presupuestado marcado como realizado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al marcar el elemento presupuestado como realizado',
            ], 500);
        }
    }

    /**
     * Obtener el ultimo folio de solicitud de cotización registrado.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function ultimoFolio()
    {
        try {
            $ultimoFolio = DB::table('solicitud_cotizacion')->max('folio') + 1;

            return response()->json([
                'success' => true,
                'folio' => $ultimoFolio,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el último folio',
            ], 500);
        }
    }

    /**
     * Función para clonar un elemento presupuestado.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clonar(Request $request)
    {
        try {
            $id = $request->input('id');
            $fecha = new \DateTime();

            // Clonar el elemento presupuestado
            $elemento = SolicitudCotizacion::findOrFail($id);
            $nuevoElemento = $elemento->replicate();
            $nuevoElemento->folio = DB::table('solicitud_cotizacion')->max('folio') + 1;
            $nuevoElemento->nombre_proyecto = $request->nombre_proyecto;
            $nuevoElemento->fecha_registro = $fecha->format('d-m-Y H:i:s');
            $nuevoElemento->fecha_actualizacion = $fecha->format('d-m-Y H:i:s');
            $nuevoElemento->save();

            $tipo = $elemento->plantilla_id;

            switch ($tipo) {
                case 4: // POP
                    $detallesPop = SolicitudCotizacionPop::where('solicitud_cotizacion_id', $id)->first();
                    if ($detallesPop) {
                        $nuevoDetallesPop = $detallesPop->replicate();
                        $nuevoDetallesPop->solicitud_cotizacion_id = $nuevoElemento->id;
                        $nuevoDetallesPop->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                        $nuevoDetallesPop->save();
                    }
                    break;
                case 3: // DISPLAY
                    $detallesDisplay = SolicitudCotizacionDisplay::where('solicitud_cotizacion_id', $id)->first();
                    if ($detallesDisplay) {
                        $nuevoDetallesDisplay = $detallesDisplay->replicate();
                        $nuevoDetallesDisplay->solicitud_cotizacion_id = $nuevoElemento->id;
                        $nuevoDetallesDisplay->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                        $nuevoDetallesDisplay->save();
                    }
                    break;
                case 2: // EMPAQUE
                    $detallesEmpaque = SolicitudCotizacionEmpaque::where('solicitud_cotizacion_id', $id)->first();
                    if ($detallesEmpaque) {
                        $nuevoDetallesEmpaque = $detallesEmpaque->replicate();
                        $nuevoDetallesEmpaque->solicitud_cotizacion_id = $nuevoElemento->id;
                        $nuevoDetallesEmpaque->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                        $nuevoDetallesEmpaque->save();

                        $nuevoDetalleEmpaqueTacon1 = DetalleTacon1::where('solicitud_cotizacion_empaque_id', $detallesEmpaque->id)->first();
                        
                        if ($nuevoDetalleEmpaqueTacon1) {
                            $nuevoTacon1 = $nuevoDetalleEmpaqueTacon1->replicate();
                            $nuevoTacon1->solicitud_cotizacion_empaque_id = $nuevoDetallesEmpaque->id;
                            $nuevoTacon1->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                            $nuevoTacon1->save();
                        }

                        $nuevoDetalleEmpaqueTacon2 = DetalleTacon2::where('solicitud_cotizacion_empaque_id', $detallesEmpaque->id)->first();
                        
                        if ($nuevoDetalleEmpaqueTacon2) {
                            $nuevoTacon2 = $nuevoDetalleEmpaqueTacon2->replicate();
                            $nuevoTacon2->solicitud_cotizacion_empaque_id = $nuevoDetallesEmpaque->id;
                            $nuevoTacon2->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                            $nuevoTacon2->save();
                        }
                    }
                    break;
                case 6: // MAQUILA
                    $detallesMaquila = SolicitudCotizacionMaquila::where('solicitud_cotizacion_id', $id)->first();
                    if ($detallesMaquila) {
                        $nuevoDetallesMaquila = $detallesMaquila->replicate();
                        $nuevoDetallesMaquila->solicitud_cotizacion_id = $nuevoElemento->id;
                        $nuevoDetallesMaquila->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                        $nuevoDetallesMaquila->save();
                    }
                    break;
                case 1: // COMERCIAL
                    $detallesComercial = SolicitudCotizacionComercial::where('solicitud_cotizacion_id', $id)->first();
                    if ($detallesComercial) {
                        $nuevoDetallesComercial = $detallesComercial->replicate();
                        $nuevoDetallesComercial->solicitud_cotizacion_id = $nuevoElemento->id;
                        $nuevoDetallesComercial->fecha_creacion = $fecha->format('d-m-Y H:i:s'); // Reseteamos la fecha de registro
                        // Aquí puedes agregar más campos específicos si es necesario
                        $nuevoDetallesComercial->save();
                    }
                    break;
            }

            // Registrar la bitácora de clonación
            $this->registrarBitacoraSolicitudCotizacion(
                'Clonación',
                null,
                'Clonado el elemento presupuestado con ID: ' . $id,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'success' => true,
                'message' => 'Elemento presupuestado clonado correctamente',
                'data' => $nuevoElemento,
                'id' => $nuevoElemento->id,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al clonar el elemento presupuestado',
            ], 500);
        }
    }

    /**
     * Función para realizar el guardado del motivo de cambio de especificaciones
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardarCambios(Request $request)
    {
        $id = $request->input('id');
        $mensaje = $request->input('mensaje');

        $validator = Validator::make($request->all(), [
            'mensaje' => 'required|string',
        ], [
            'mensaje.required' => 'El campo mensaje es obligatorio.',
            'mensaje.string' => 'El campo mensaje debe ser una cadena de texto.',
        ])->validate();

        // Validamos que el motivo no esté vacío
        if (isset($validator) && $validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $cambios = MotivoCambioEspecificaciones::where('solicitud_cotizacion_id', $id)->first();
            $solicitud = SolicitudCotizacion::findOrFail($id);

            $anterior = '';

            if ($cambios) {
                $anterior = $cambios->mensaje; // Guardamos el mensaje anterior para la bitácora
                $cambios->mensaje = $mensaje;
                $cambios->usuario_id = auth()->user()->Id_Usuario;
                $cambios->save();
            } else {
                MotivoCambioEspecificaciones::create([
                    'mensaje' => $mensaje,
                    'solicitud_cotizacion_id' => $id,
                    'usuario_id' => auth()->user()->Id_Usuario,
                ]);
            }

            $solicitud->tiene_cambio_especificaciones = 1; // Indicamos que hay un cambio de especificaciones
            $solicitud->save();

            DB::table('Oportunidades')
                ->where('idOportunidad', $solicitud->oportunidad_id)
                ->update(['flagCambioEspecificaciones' => 1]);

            (new OportunidadesController())->cambiarAListoParaCotizar($solicitud->oportunidad_id);

            // Registramos la bitácora de guardado de cambios
            $this->registrarBitacoraSolicitudCotizacion(
                'Guardado de Motivo de Cambio de Especificaciones',
                'Anterior: ' . $anterior . '',
                'Motivo guardado: ' . $mensaje . ' para el elemento presupuestado con Folio: ' . $solicitud->folio,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Motivo de cambio de especificaciones guardado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al guardar el motivo de cambio de especificaciones',
            ], 500);
        }
    }

    /**
     * Función para registrar los comentarios para una solicitud de cotización.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarNuevoCambioEspecificaciones()
    {
        try {
            $id = request()->input('id');
            $mensaje = request()->input('mensaje');

            $cambios = MotivoCambioEspecificaciones::where('solicitud_cotizacion_id', $id)->first();
            $solicitud = SolicitudCotizacion::findOrFail($id);

            $anterior = '';

            if ($cambios) {
                $anterior = $cambios->mensaje; // Guardamos el mensaje anterior para la bitácora
                $cambios->mensaje = $mensaje;
                $cambios->usuario_id = auth()->user()->Id_Usuario;
                $cambios->save();
            } else {
                MotivoCambioEspecificaciones::create([
                    'mensaje' => $mensaje,
                    'solicitud_cotizacion_id' => $id,
                    'usuario_id' => auth()->user()->Id_Usuario,
                ]);
            }

            $solicitud->tiene_cambio_especificaciones = 1; // Indicamos que hay un cambio de especificaciones
            $solicitud->save();

            // Registramos la bitácora de guardado de cambios
            $this->registrarBitacoraSolicitudCotizacion(
                'Guardado de Motivo de Cambio de Especificaciones',
                'Anterior: ' . $anterior . '',
                'Motivo guardado: ' . $mensaje . ' para el elemento presupuestado con Folio: ' . $solicitud->folio,
                $id,
                auth()->user()->Id_Usuario
            );

            return response()->json([
                'response' => true,
                'message' => 'Motivo de cambio de especificaciones guardado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al guardar el motivo de cambio de especificaciones',
            ], 500);
        }
    }

    /**
     * Función para aplicar cambios de especificaciones a la oportunidad.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cerrarYAplicarCambioEspecificaciones(Request $request)
    {
        try {
            $id = $request->input('id');

            DB::table('Oportunidades')
                ->where('idOportunidad', $id)
                ->update(['flagCambioEspecificaciones' => 1]);

            (new OportunidadesController())->cambiarAListoParaCotizar($id);

            return response()->json([
                'response' => true,
                'message' => 'Cambios de especificaciones aplicados correctamente a la oportunidad',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al aplicar los cambios de especificaciones a la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para obtener el motivo de cambio de especificaciones de un elemento presupuestado.
     *
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCambioEspecificacionesByIdEP(Request $request)
    {
        try {
            $id = $request->input('id');

            $cambios = MotivoCambioEspecificaciones::where('solicitud_cotizacion_id', $id)->first();

            if (!$cambios) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontró el motivo de cambio de especificaciones para el elemento presupuestado con ID: ' . $id,
                ], 200);
            }

            return response()->json([
                'response' => true,
                'mensaje' => $cambios->mensaje,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener el motivo de cambio de especificaciones',
            ], 500);
        }
    }

    /**
     * Función para validar si existen elementos con cambios pendientes en una oportunidad.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validarElementosConCambiosPendientes(Request $request)
    {
        try {
            $oportunidadId = $request->input('idOportunidad');

            $elementosConCambiosPendientes = SolicitudCotizacion::where('oportunidad_id', $oportunidadId)
                ->where('tiene_cambio_especificaciones', 1)
                ->count();

            return response()->json([
                'response' => true,
                'tieneCambiosPendientes' => $elementosConCambiosPendientes > 0,
                'cantidad' => $elementosConCambiosPendientes,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al validar los elementos con cambios pendientes',
            ], 500);
        }
    }

    /**
     * Función para registrar la información del nuevo presupuesto.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarNuevoPresupuesto(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');
            $idUsuario = $request->input('idUsuario');
            $elementos = json_decode($request->input('elementos'), true);
            $tituloPresupuesto = $request->input('tituloPresupuesto');

            // 1° Guardamos el nuevo presupuesto
            $presupuesto = (new PresupuestoController())->store([
                'oportunidad_id' => $idOportunidad,
                'usuario_id' => $idUsuario,
                'estatus' => 1, // 1: Activo, 2: rechazado, 3: aprobado
            ]);

            $totalAdicionales = 0;
            $totalGeneral = 0;
            $totalElemento = 0;

            // 2° Registramos los elementos presupuestados
            foreach ($elementos as $elemento) {
                if ($elemento['plantillaId'] == 2) {
                    $empaqueGeneral = [];
                    $empaqueT1 = [];
                    $empaqueT2 = [];

                    foreach ($elemento['medidasInputs'] as $key => $value) {
                        if (substr($key, -3) === '_t1') {
                            $empaqueT1[$key] = $value;
                        } elseif (substr($key, -3) === '_t2') {
                            $empaqueT2[$key] = $value;
                        } else {
                            $empaqueGeneral[$key] = $value;
                        }
                    }

                    $empaqueDetalles = array_merge($empaqueGeneral, [
                        'solicitud_cotizacion_id' => $elemento['idElementoP'],
                    ]);
                    $se = (new SolicitudCotizacionEmpaqueController())->update($empaqueDetalles, $elemento['idElementoP']);

                    // Registramos los detalles de TACO TIPO 1 si existen
                    if (!empty($empaqueT1)) {
                        $empaqueT1Detalles = array_merge($empaqueT1, [
                            'solicitud_cotizacion_empaque_id' => $se->id,
                        ]);
                        (new DetalleTacon1Controller())->update($empaqueT1Detalles, $se->id);
                    }
                    
                    // Registramos los detalles de TACO TIPO 2 si existen
                    if (!empty($empaqueT2)) {
                        $empaqueT2Detalles = array_merge($empaqueT2, [
                            'solicitud_cotizacion_empaque_id' => $se->id,
                        ]);
                        (new DetalleTacon2Controller())->update($empaqueT2Detalles, $se->id);
                    }
                }

                if ($elemento['plantillaId'] == 3) {
                    (new SolicitudCotizacionDisplayController())->update($elemento['medidasInputs'], $elemento['idElementoP']);
                }

                (new PresupuestosEPController())->store([
                    'folio_metrics' => $elemento['folio_metrics'],
                    'cantidad_1' => (int)str_replace(',', '', $elemento['cantidad_1']),
                    'precio_1' => str_replace(',', '', $elemento['precio_1']),
                    'cantidad_2' => (int)str_replace(',', '', $elemento['cantidad_2']),
                    'precio_2' => str_replace(',', '', $elemento['precio_2']),
                    'cantidad_3' => (int)str_replace(',', '', $elemento['cantidad_3']),
                    'precio_3' => str_replace(',', '', $elemento['precio_3']),
                    'presupuesto_id' => $presupuesto->id,
                    'solicitud_cotizacion_id' => $elemento['idElementoP'],
                    'cat_categoria_fsc_id' => isset($elemento['fsc']['categoria']) && $elemento['fsc']['categoria'] != 'null' ? $elemento['fsc']['categoria'] : 0,
                    'cat_sub_categoria_fsc_id' => isset($elemento['fsc']['subcategoria']) && $elemento['fsc']['subcategoria'] != 'null' ? $elemento['fsc']['subcategoria'] : 0,
                ]);

                $totalGeneral += ((int)str_replace(',', '', $elemento['cantidad_1']) * str_replace(',', '', $elemento['precio_1'])) +
                                ((int)str_replace(',', '', $elemento['cantidad_2']) * str_replace(',', '', $elemento['precio_2'])) +
                                ((int)str_replace(',', '', $elemento['cantidad_3']) * str_replace(',', '', $elemento['precio_3']));
                
                foreach ($elemento['adicionales'] as $adicional) {
                    (new SolicitudCotizacionController())->storeAdditionalQuantitiesAndPrices([
                        'cantidad' => (int)str_replace(',', '', $adicional['cantidad']),
                        'precio' => str_replace(',', '', $adicional['precio']),
                        'usuario_id' => $idUsuario,
                        'presupuesto_id' => $presupuesto->id,
                        'solicitud_cotizacion_id' => $elemento['idElementoP'],
                    ]);

                    $totalAdicionales += (int)str_replace(',', '', $adicional['cantidad']) * str_replace(',', '', $adicional['precio']);
                }

                $totalElemento += $totalGeneral + $totalAdicionales;
            }

            Oportunidades::where('idOportunidad', $idOportunidad)->update([
                'Valor' => $totalElemento,
            ]);

            // 3° Registramos la bitácora del nuevo presupuesto
            $bitacora = (new BitacoraPresupuestosController())->store([
                'anterior' => null,
                'nuevo' => 'Nuevo presupuesto creado con ID: ' . $presupuesto->id,
                'usuario_id' => $idUsuario,
                'presupuesto_id' => $presupuesto->id,
            ]);

            // Guardamos la el archivo PDF del presupuesto
            $this->storePDFPresupuesto($presupuesto->id, $idOportunidad, $idUsuario, $tituloPresupuesto);

            return response()->json([
                'response' => true,
                'valorOportunidad' => $totalElemento,
                'message' => 'El presupuesto se generó correctamente, lo puedes encontrar en la sección de documentos.',
            ], 200);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'response' => false,
                'message' => 'Error al generar el presupuesto',
            ], 500);
        }
    }

    /**
     * Función para obtener la data que se mostrara en el pdf del presupuesto.
     *
     * @param int $id
     * @return mixed
     */
    public function getDataPDFPresupuesto($id)
    {
        try {
            $dataPresupuesto = (new PresupuestoController())->getPresupuestoById($id);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener la data del presupuesto',
            ], 500);
        }
    }

    /**
     * Funcion para obtener la data del presupuesto para mostrar en el PDF.
     * 
     * @param int $id
     * @param int $idOportunidad
     * @return array
     */
    public function getDataPDF($id, $idOportunidad)
    {
        try {
            // Contamos cuantos presupuestos tiene la oportunidad y obtenemos el último
            $ultimoP = DB::table('Presupuestos')
                                    ->where('oportunidad_id', $idOportunidad)
                                    ->count() + 1;

            $plantillaID = DB::table('v_PresupuestosEP')->where('presupuesto_id', $id)->first();

            if ($plantillaID == null) {
                // Si la plantilla no es comercial, no generamos el PDF
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontró información para generar el PDF del presupuesto con ID: ' . $id,
                ], 200);
            }

            switch ($plantillaID->plantilla_id) {
                case 1:
                    // Plantilla Comercial
                    $presupuestosEP = DB::table('v_PresupuestoComercial')
                                        ->where('presupuesto_id', $id)->get();
                break;
                case 2:
                    // Plantilla Empaque
                    $presupuestosEP = DB::table('v_PresupuestoEmpaque')
                                        ->where('presupuesto_id', $id)->get();
                break;
                case 3:
                    // Plantilla DISPLAY
                    $presupuestosEP = DB::table('v_PresupuestoDisplay')
                                        ->where('presupuesto_id', $id)->get();
                break;
                case 4:
                    // Plantilla POP
                    $presupuestosEP = DB::table('v_PresupuestoPop')
                                        ->where('presupuesto_id', $id)->get();
                break;
                case 6:
                    // Plantilla MAQUILA
                    $presupuestosEP = DB::table('v_PresupuestoMaquila')
                                        ->where('presupuesto_id', $id)->get();
                break;
                default:
                    // Si la plantilla no es comercial, no generamos el PDF
                    return response()->json([
                        'response' => false,
                        'message' => 'La plantilla del presupuesto con ID: ' . $id . ' no es comercial, no se genera el PDF.',
                    ], 200);
            }

            $cantidadesAdicionales = CantidadesAdicionalesPresupuesto::whereIn('presupuesto_id', $presupuestosEP->pluck('presupuesto_id')->unique()->toArray())
                                ->get();

            $elementosTitulos = $presupuestosEP->pluck('nombre_proyecto')->toArray();
            $elementosFolios = $presupuestosEP->pluck('folio_metrics')->toArray();
            $elementosIds = $presupuestosEP->pluck('solicitud_cotizacion_id')->toArray();
            
            // Unimos los títulos con comas
            $elementosTitulos = implode(", ", $elementosTitulos);
            $elementosFolios = implode("_", $elementosFolios);

            if ($presupuestosEP->isNotEmpty()) {
                $data = [
                    'elementosTitulos' => $elementosTitulos,
                    'presupuesto' => $presupuestosEP->first(),
                    'presupuestos' => $presupuestosEP,
                    'cantidadesAdicionales' => $cantidadesAdicionales,
                    'plantilla' => $plantillaID->plantilla_id,
                ];
            } else {
                $data = [];
            }
    
            if (empty($data)) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontró información para el presupuesto con ID: ' . $id,
                ], 404);
            }

            return [
                'response' => true,
                'data' => $data,
                'ultimoP' => $ultimoP,
                'elementosFolios' => $elementosFolios,
                'elementosIds' => $elementosIds,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener la data para el PDF del presupuesto con ID: ' . $id,
            ], 500);
        }
    }

    /**
     * Función para generar y guardar el PDF del presupuesto.
     *
     * @param int $id
     * @param int $idOportunidad
     * @param int $idUsuario
     * @return mixed
     */
    public function storePDFPresupuesto($id, $idOportunidad, $idUsuario, $tituloPdf = '')
    {
        try {
            $dataA = $this->getDataPDF($id, $idOportunidad);
            $elementosFolios = $dataA['elementosFolios'];
            $elementosIds = $dataA['elementosIds'];
            $ultimoP = $dataA['ultimoP'];
            $data = $dataA['data'];
    
            $dompdf = App::make("dompdf.wrapper");
            // Configuramos a tamaño carta
            $dompdf->setPaper('letter');
            
            $titulo = str_replace(" ", "_", "Presupuesto_".$elementosFolios);
            
            // Agregamos la fecha y hora actual al título
            $fechaHoraActual = date('Y_m_d');
            $titulo = substr($titulo, 0, 100); // Limitamos el título a 100 caracteres
            $titulo .= '_' . $fechaHoraActual . '_v' . $ultimoP;

            $titulo = $tituloPdf != '' ? $tituloPdf : $titulo;

            $data['titulo'] = $titulo;
            // Cargamos la vista
            $dompdf->loadView("pdf/presupuesto-candy", $data);
    
            // Guardar el PDF en storage/app/public/presupuesto.pdf
            $output = $dompdf->output();
            $filename = $titulo . '.pdf';
            Storage::disk('public')->put($filename, $output);
    
            // Datos para el guardado en la base de datos
            $size = Storage::disk('public')->size($filename);
    
            // Obtener el contenido y convertirlo a base64
            $pdfContent = Storage::disk('public')->get($filename);
    
            // Archivo
            $archivo = base64_encode($pdfContent);
    
            $idTipoDocumento = 1;
            $Extencion = 'application/pdf';
            $Size = $size;
            $descDocumento = $titulo . '.pdf';
            $Documento = $archivo;
    
            DB::statement(DB::raw(" EXEC sp_updateOportDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento,:tipoPresupuesto"), [
                ':descDocumento' => $descDocumento,
                ':Documento' => $Documento,
                ':Extencion' => $Extencion,
                ':Size' => $Size,
                ':idUsuarioReg' => $idUsuario,
                ':idOportunidad' => $idOportunidad,
                ':idTipoDocumento' => $idTipoDocumento,
                ':tipoPresupuesto' => 1 // EPS
            ]);
    
            $tipo_documento = catTipoDocumentoOport::where('idTipoDocumento', $idTipoDocumento)->first();
            
            $idDocumentoInsertado = DB::table('DocumentosOport')
                                        ->where('descDocumento', $descDocumento)
                                        ->where('Extension', $Extencion)
                                        ->where('idTipoDocumento', $idTipoDocumento)
                                        ->where('Size', $Size)
                                        ->where('idUsuarioReg', $idUsuario)
                                        ->orderBy('idDocumento', 'desc')
                                        ->first();

            if ($idDocumentoInsertado) {
                $idDocumento = $idDocumentoInsertado->idDocumento;
                
                foreach ($elementosIds as $idElemento) {
                    DB::table('presupuestoPdfEP')->insert([
                        'idDocumento' => $idDocumento,
                        'idEp' => $idElemento,
                    ]);
                }
            }
    
            if ($idTipoDocumento != 0) {
                $this->bitacora('Presupuesto', 'Agregar Documento ' . (string)$descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Oportunidad ', $idOportunidad, $this->loginUsuario(), 5);
            }
    
            // Al guardarlo lo eliminamos
            Storage::disk('public')->delete($filename);
    
            return response()->json([
                'response' => true,
                'message' => 'PDF del presupuesto generado y guardado correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al generar y guardar el PDF del presupuesto',
            ], 500);
        }
    }

    /**
     * Función para obtener los precios totales de las cantidades de un elemento presupuestado en base a un número de presupuesto.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPrecioPresupuestosEP(Request $request)
    {
        try {
            $search = $request->search;
            $ep = $request->input('ep');
            $cantidad = str_replace(',', '', $request->input('cantidad'));

            $elemento = SolicitudCotizacion::where('id', $ep)
                                        ->first();

            $precio = DB::table('v_PresupuestosMetrics')->where('NumPresupuesto', $search)->where('folioEP', $elemento->folio)->where('Cantidad', (int)$cantidad)->first();

            return response()->json([
                'response' => true,
                'precio' => $precio != null ? $precio->precioUnitario : 0,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener las cantidades totales de los elementos presupuestados',
            ], 500);
        }
    }

    /**
     * Función para obtener las cantidades totales de los elementos presupuestados de una oportunidad.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerNumPresupuestosEP(Request $request)
    {
        try {
            $search = $request->search;
            $ep = $request->input('ep');

            $elemento = SolicitudCotizacion::where('id', $ep)
                                        ->first();

            $cantidad1 = DB::table('v_PresupuestosMetrics')->where('NumPresupuesto', $search)->where('folioEP', $elemento->folio)->where('Cantidad', (int)$elemento->cantidad_cotizar_1)->first();
            $cantidad2 = DB::table('v_PresupuestosMetrics')->where('NumPresupuesto', $search)->where('folioEP', $elemento->folio)->where('Cantidad', (int)$elemento->cantidad_cotizar_2)->first();
            $cantidad3 = DB::table('v_PresupuestosMetrics')->where('NumPresupuesto', $search)->where('folioEP', $elemento->folio)->where('Cantidad', (int)$elemento->cantidad_cotizar_3)->first();

            $cantidadesTotales = [
                'cantidad_1' => $cantidad1 != null ? $cantidad1->precioUnitario : 0,
                'cantidad_2' => $cantidad2 != null ? $cantidad2->precioUnitario : 0,
                'cantidad_3' => $cantidad3 != null ? $cantidad3->precioUnitario : 0,
            ];

            return response()->json([
                'response' => true,
                'data' => $cantidadesTotales,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener las cantidades totales de los elementos presupuestados',
            ], 500);
        }
    }

    /**
     * Función para obtener el folio de presupuesto asociado a un elemento presupuestado.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerFolioPresupuestosEP(Request $request)
    {
        try {
            $folio = $request->input('folio');

            $numPresupuestos = DB::table('v_PresupuestosMetrics')
                                ->where('folioEP', $folio)
                                ->orderBy('NumPresupuesto', 'DESC')
                                ->first();

            return response()->json([
                'response' => true,
                'numPresupuesto' => $numPresupuestos != null ? $numPresupuestos->NumPresupuesto : null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener los números de presupuesto asociados al elemento presupuestado',
            ], 500);
        }
    }

    /**
     * Función para registar multielementos para una oportunidad.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardarMultipresupuestoExcel(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');
            $elementos = json_decode($request->input('dataExcel'), true);
            $archivos = $request->file('files');
            $valorOportunidad = str_replace(',', '', $request->input('valorOportunidad'));
            $dataArchivos = [];

            foreach ($archivos as $archivo) {
                // Solo imagenes png, jpg o jpeg
                if (in_array($archivo->getClientOriginalExtension(), ['png', 'jpg', 'jpeg'])) {
                    $archivoBase = base64_encode(file_get_contents($archivo->getRealPath()));
                    $dataArchivos[] = 'data:image/png,jpg;base64,' . $archivoBase;
                }
            }

            $idUsuario = $this->idUsuario();

            DB::table('multielementos')
                ->insert([
                    'data' => json_encode($elementos),
                    'oportunidad_id' => $idOportunidad,
                    'usuario_id' => $idUsuario,
                ]);

            $oportunidad = (new OportunidadesController())->getOportunidadParaPresupuesto($idOportunidad);

            DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->update([
                    'Valor' => $valorOportunidad,
                ]);

            $data = [
                'oportunidad' => $oportunidad,
                'data_excel' => $elementos,
                'archivos' => $dataArchivos,
            ];

            if (empty($data)) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontró información para la oportunidad: ' . $oportunidad->folio,
                ], 404);
            }
    
            $dompdf = App::make("dompdf.wrapper");
            // Configuramos a tamaño carta
            $dompdf->setPaper('letter');
            
            $titulo = str_replace(" ", "_", "Presupuesto_Multielementos_".$oportunidad->folio);
            
            // Agregamos la fecha y hora actual al título
            $fechaHoraActual = date('Y_m_d');
            $titulo = substr($titulo, 0, 100); // Limitamos el título a 100 caracteres
            $titulo .= '_' . $fechaHoraActual;

            $data['titulo'] = $titulo;
            // Cargamos la vista
            $dompdf->loadView("pdf/presupuesto-multiple", $data);
    
            // Guardar el PDF en storage/app/public/presupuesto.pdf
            $output = $dompdf->output();
            $filename = $titulo . '.pdf';
            Storage::disk('public')->put($filename, $output);
    
            // Datos para el guardado en la base de datos
            $size = Storage::disk('public')->size($filename);
    
            // Obtener el contenido y convertirlo a base64
            $pdfContent = Storage::disk('public')->get($filename);
    
            // Archivo
            $archivo = base64_encode($pdfContent);
    
            $idTipoDocumento = 1;
            $Extencion = 'application/pdf';
            $Size = $size;
            $descDocumento = $titulo . '.pdf';
            $Documento = $archivo;
    
            DB::statement(DB::raw(" EXEC sp_updateOportDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento,:tipoPresupuesto"), [
                ':descDocumento' => $descDocumento,
                ':Documento' => $Documento,
                ':Extencion' => $Extencion,
                ':Size' => $Size,
                ':idUsuarioReg' => $idUsuario,
                ':idOportunidad' => $idOportunidad,
                ':idTipoDocumento' => $idTipoDocumento,
                ':tipoPresupuesto' => 2 // Excel multielementos
            ]);
    
            $tipo_documento = catTipoDocumentoOport::where('idTipoDocumento', $idTipoDocumento)->first();
    
            if ($idTipoDocumento != 0) {
                $this->bitacora('Presupuesto', 'Agregar Documento ' . (string)$descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Oportunidad ', $idOportunidad, $this->loginUsuario(), 5);
            }
    
            // Al guardarlo lo eliminamos
            Storage::disk('public')->delete($filename);

            return response()->json([
                'response' => true,
                'message' => 'Elementos presupuestados guardados correctamente para el presupuesto.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al guardar los elementos presupuestados para el presupuesto.',
            ], 500);
        }
    }

    /**
     * Finalizar cambio de un elemento presupuestado
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function finalizarCambio(Request $request)
    {
        try {
            $id = $request->input('id');

            $elemento = SolicitudCotizacion::findOrFail($id);

            if ($elemento->cambio_prioridad == 2) {
                $elemento->tiene_cambio_especificaciones = 0;
            }

            if ($elemento->cambio_prioridad == 1) {
                $elemento->tiene_cambios_minimos = 0;
            }
            
            $elemento->cambio_prioridad = 0;
            $elemento->save();

            $finalizoCambios = false;


            // Validamos si la oportunidad tiene más elementos con cambios pendientes, si no tiene ninguno, finalizamos la realización de cambios para la oportunidad.
            $elementosConCambiosPendientes = SolicitudCotizacion::where('oportunidad_id', $elemento->oportunidad_id)
                                                                ->where(function($q) {
                                                                    $q->where('cambio_prioridad', '1')
                                                                        ->orWhere('cambio_prioridad', '2');
                                                                })
                                                                ->count();

            if ($elementosConCambiosPendientes == 0) {
                DB::table('Oportunidades')
                    ->where('idOportunidad', $elemento->oportunidad_id)
                    ->update([
                        'flagCambioEspecificaciones' => 0,
                        'cambiosMinimos' => 0,
                        'finalizoCambios' => 1,
                        'solicitoCambios' => 0
                    ]);

                $finalizoCambios = true;
            }

            GruposBitacoraCambios::where('solicitudCotizacionId', $id)
                                ->update(['estatus' => 1]); // 0: En proceso, 1: Finalizado

            return response()->json([
                'response' => true,
                'message' => 'Cambio de especificaciones finalizado correctamente para el elemento presupuestado.',
                'finalizoCambios' => $finalizoCambios,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al finalizar el cambio de especificaciones para el elemento presupuestado.',
            ], 500);
        }
    }

    /**
     * Función para obtener el histórico de cambios de un elemento presupuestado.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function historicoCambios(Request $request)
    {
        try {
            $id = $request->input('id');

            $gruposDeCambios = GruposBitacoraCambios::where('solicitudCotizacionId', $id)
                                            ->orderBy('fechaRegistro', 'DESC')
                                            ->get()->pluck('id')->toArray();

            $bitacoraCambios = DB::table('v_BitacoraCambiosEspMin')
                                            ->whereIn('grupoId', $gruposDeCambios)
                                            ->orderBy('fechaRegistro', 'DESC')
                                            ->get();

            return response()->json([
                'response' => true,
                'cambios' => $bitacoraCambios,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener el histórico de cambios para el elemento presupuestado.',
            ], 500);
        }
    }

    /**
     * Función para obtener el estatus actual de una oportunidad y saber si se ha rechazado al menos una vez
     * 
     * @param Request $request
     * @return mixed
     */
    public function obtenerEstatusOportunidad(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');
            $oportunidad = Oportunidades::findOrFail($idOportunidad);

            return response()->json([
                'response' => true,
                'estatus' => $oportunidad->idEstatus,
                'tieneRechazoAlMenosUnaVez' => $oportunidad->fueRechazadoPres == 1 || $oportunidad->idEstatus == 8 || $oportunidad->idEstatus == 7,
                'finalizoCambios' => ($oportunidad->finalizoCambios == 1 || $oportunidad->finalizoCambios == null) && $oportunidad->flagCambioEspecificaciones == 0,
                'tipoOportunidad' => $oportunidad->tieneExcel,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener el estatus de la oportunidad.',
            ], 500);
        }
    }
}
