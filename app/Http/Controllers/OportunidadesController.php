<?php

namespace App\Http\Controllers;

use App\catEquipos;
use App\catEstatus;
use App\catOportExclusion;
use App\catOportMotivoPerdida;
use App\catOportProbabilidad;
use App\catOportTipos;
use App\Http\Modelos\Bitacora;
use App\Http\Modelos\bitacoraTiemposEstatus;
use App\Http\Modelos\catPlantillasAlertas;
use App\Http\Modelos\catTipoDocumentoOport;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\DocumentosEstructural;
use App\Http\Modelos\DocumentosOport;
use App\Http\Modelos\DocumentosOportMail;
use App\Http\Modelos\ElementosPacabados;
use App\Http\Modelos\ElementosPacabadosGral;
use App\Http\Modelos\ElementosPempaque;
use App\Http\Modelos\ElementosPmedidas;
use App\Http\Modelos\EntregaSucursalesOportunidades;
use App\Http\Modelos\IntegrantesRoles;
use App\Http\Modelos\OportunidadCambiosMinimos;
use App\Http\Modelos\Oportunidades;
use App\Http\Modelos\relEstr_Documento;
use App\Http\Modelos\relOport_Documento;
use App\Http\Modelos\relOport_ElementoP;
use App\Http\Modelos\RetencionOportunidades;
use App\Http\Modelos\Sucursales;
use App\Http\Modelos\TiempoEntregaOportunidades;
use App\Http\Modelos\v_ElementosPresupuestados_All;
use App\Http\Modelos\v_ordenesMetricsAll;
use App\Http\Vistas\v_DocumentosOportunidades;
use App\Http\Vistas\v_Estructural;
use App\Http\Vistas\v_OportunidadesGrid;
use App\Http\Vistas\v_presupuestosMetricsAll;
use App\Mail\SendMail;
use App\Mail\SendMailDocumento;
use App\Mail\SendMailEstructural;
use App\Mail\SendMailProcessFt;
use App\SolicitudCotizacion;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use App\v_DocumentosOport;
use App\v_Oportunidades;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class OportunidadesController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;
    public $modulo = 'Oportunidades';

    public function index()
    {
        $this->permisos();

        $Login = $this->loginUsuario();

        if ($Login == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
        $permisos =  $this->permisosModulo($this->modulo);
        $id_usuario = $this->idUsuario();
        $folio = DB::select('exec spMaxFolio');
        $combo_clientes = DB::select('exec GetClientesCombo');
        $estatus = catEstatus::all();
        $tipos = catOportTipos::all();
        $previsiones = catOportExclusion::all();
        $probabilidades = catOportProbabilidad::all();
        $equipos = catEquipos::all();
        $combo_vendedores = DB::select('exec GetAgentesCombo');
        $combo_cotizadores = DB::select('exec GetCotizadoresCombo');
        $motivoPerdida = catOportMotivoPerdida::all();
        $permisos_count = count($permisos);
        $cotizadores = DB::table('v_enCancha')->orderBy('nombre', 'asc')->get();
        $idrol = $permisos[0]->idRol;
        $Asignados = DB::table('v_catAgentesCLogin2')->where('Puesto', 'like', '%VENDEDOR%')->orWhere('Puesto', 'DIRECTOR GENERAL')->orWhere('Puesto', 'DIRECTOR DE OPERACIONES')->orWhere('Puesto', 'JEFE DE COTIZACIONES')->orWhere('Puesto', 'GERENTE DE VENTAS')->orderBy('Nombre', 'asc')->orWhere('Puesto', 'ASESOR DE VENTAS')->get();
        $STS = DB::table('catOportEstatus')->where('descEstatus', '!=', 'GANADO')->where('descEstatus', '!=', 'Espera VoBo')->get();

        // return $OportunidadesPaginate;
        for ($i = 0; $i < $permisos_count; $i++) {

            $crearOpor = session('crearOpor');
            $editarOpor = session('editarOpor');
            $editarOportunidades = session('EditarOportunidades');
            $crearTickets = session('crearEstructural');

            if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                return view('oportunidades.index', compact('crearOpor', 'editarOpor', 'editarOportunidades', 'combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'idrol', 'crearTickets', 'Asignados', 'STS', 'cotizadores'));
            } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                return redirect()->route('Dashboard');
            }
        }
        // }
    }

    public function correos()
    {
        $this->permisos();
        $Login = $this->loginUsuario();

        if ($Login == null) {
            return view('iniciar');
        }

        $permisos =  $this->permisosModulo($this->modulo);
        $permisos_count = count($permisos);

        for ($i = 0; $i < $permisos_count; $i++) {

            if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                return view('oportunidades.index_correos');
            } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                return redirect()->route('Dashboard');
            }
        }
    }

    public function create()
    {
        $Login = $this->loginUsuario();
        if ($Login == 'smmorenoc') {
            $Login = 'smmoreno';
        }

        if ($Login == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
        $permisos =  $this->permisosModulo($this->modulo);
        $id_usuario = $this->idUsuario();
        $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $Login);
        $agente_id = 0;
        $idEquipo_idAgente = 0;
        $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        switch ($rol) {
            case '4':
                $equipos;
                $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
                $idEquipo = $equipos[0]->idEquipo;
                $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $id_usuario)->orWhere('idAgente2', $id_usuario)->get();
                break;
            case '9':
                $agente_id = $agentes[0]->idAgente;
                if ($id_usuario == 29) {
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
                } else {
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
                }
                $idEquipo = $equipos[0]->idEquipo;
                $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();
                if ($id_usuario == 29) {
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', 9)->orWhere('idAgente', 8357)->orWhere('idAgente', 29)->get();
                    // $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $agente_id)->get();
                } else {
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $agente_id)->get();
                }
                break;
            case '3':
                if ($id_usuario == 13582) {
                    $agente_id = $agentes[0]->idAgente;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $agente_id)->get();
                } else {
                    $equipos = catEquipos::where('estatus', 1)->orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->get();
                }
                break;
            default:
                $equipos = catEquipos::where('estatus', 1)->orderBy('idEquipo', 'asc')->get();
                $combo_vendedores = DB::select('exec GetAgentesCombo');
                // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->get();
                break;
        }

        if ($id_usuario == 29) {
            $combo_vendedores = collect($combo_vendedores)->filter(function ($vendedor) {
                return in_array($vendedor->Id_Usuario, [9, 8357, 29]);
            })->values()->all();
        }

        $folio = DB::table('Oportunidades')->max('folio') + 1;
        $estatus = catEstatus::all();
        $tipos = catOportTipos::all();
        $previsiones = catOportExclusion::all();
        $probabilidades = catOportProbabilidad::all();
        $combo_cotizadores = DB::select('exec GetCotizadoresCombo');
        $motivoPerdida = catOportMotivoPerdida::all();
        $editarOportunidades = session('editarOportunidades');

        foreach ($permisos as $permiso) {
            if ($editarOportunidades == 'EditarOportunidades') {
                return view('oportunidades.create', compact('combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'agente_id', 'idEquipo_idAgente', 'rol'));
            } else {
                if ($permiso->descAccion == 'Crear' && $permiso->descNivelPerm == 'Todo') {
                    return view('oportunidades.create', compact('combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'agente_id', 'idEquipo_idAgente', 'rol'));
                } else if ($permiso->descAccion == 'Crear' && $permiso->descNivelPerm == 'Ninguno') {
                    return back();
                }
            }
        }
        // }
    }

    public function clonarOportunidad(Request $request)
    {
        // return $request;
        $tipo_clonado = 1;
        $fecha = new \DateTime();

        if ($request->precios == 'false') {
            $tipo_clonado = 1;
            $idEstatus = 1;
            $idAtendido = null;
            $idCustomer = null;
        } else {
            $custLibre = null;
            $cotLibre = $this->cotizadorLibre();
            $tipo_clonado = 2;
            $idEstatus = 3;
            $idAtendido = $custLibre;
            $idCustomer = $cotLibre;
        }

        // return $idAtendido;

        $oportunidad = Oportunidades::where('idOportunidad', $request->idOportunidad)->first();
        $valor = 0;
        $folio = DB::table('Oportunidades')->max('folio') + 1;
        $idOportunidadNuevo = DB::table('Oportunidades')->max('idOportunidad') + 1;

        DB::statement(
            'exec spInsertOportunidadClonar ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $oportunidad->idCliente,
                $oportunidad->nomOportunidad,
                $idEstatus,
                $oportunidad->idTipo,
                $oportunidad->idExclusion,
                0, //Probabilidad
                $oportunidad->idAgente,
                $oportunidad->Maquila,
                $oportunidad->Premio,
                $valor,
                $fecha->format('d-m-Y H:i:s'),
                $oportunidad->idUsuarioCreacion,
                $idAtendido,
                $oportunidad->idEquipo,
                null, // fecReqPreCot,
                $folio,
                $oportunidad->prospecto,
                $tipo_clonado,
                $idCustomer,
                $oportunidad->idContacto,
                $oportunidad->nota,
                $oportunidad->requiereMaquilaExterior
            ]
        );

        $oportunidad_nueva = Oportunidades::where('folio', $folio)->first();

        $this->saveOrUpdateBitacora($oportunidad_nueva, $fecha->format('d-m-Y H:i:s'), $oportunidad->idUsuarioCreacion, ($request->precios == 'false' ? 1 : 3));

        // if ($tipo_clonado == 1 || $tipo_clonado == 2) {

        //     $elemento_presupuestado = relOport_ElementoP::where('idOportunidad', $request->idOportunidad)->first();

        //     if (count($elemento_presupuestado) > 0) {
        //         DB::statement(
        //             'exec spInsertRelacionEPOportunidad ?, ?, ?, ?',
        //             [
        //                 $oportunidad_nueva->idOportunidad,
        //                 $elemento_presupuestado->idElementoP,
        //                 $elemento_presupuestado->idUsuarioRelaciona,
        //                 $fecha->format('d-m-Y H:i:s'),
        //             ]
        //         );
        //     }
        // } else if ($tipo_clonado == 3) {

        $elemeto_PR = relOport_ElementoP::where('idOportunidad', $request->idOportunidad)->first();

        if ($elemeto_PR !== null) {
            $elemetoP = v_ElementosPresupuestados_All::where('idElementoP', $elemeto_PR->idElementoP)->first();

            $idTipoItem = $elemetoP->idTipoItem;
            $nomElementoP = $elemetoP->nomElementoP;
            $cant1 = $elemetoP->cant1;
            $cant2 = $elemetoP->cant2;
            $cant3 = $elemetoP->cant3;
            $cant4 = $elemetoP->cant4;
            $cant5 = 0;
            $numPresupuesto = null;
            $fecTopeCot = null;
            $fecReqEntProd = null;
            $esNuevo = $elemetoP->esNuevo;
            $preObjetivo = $elemetoP->preObjetivo;
            $preCompetencia = $elemetoP->preCompetencia;
            $nomCompetencia = $elemetoP->nomCompetencia;
            $idEstadoItem = $elemetoP->idEstadoItem;
            $porcMargen = $elemetoP->porcMargen;
            $porcFinanciamiento = $elemetoP->porcFinanciamiento;
            $porcComisionExt = $elemetoP->porcComisionExt;
            $porcComIsionInt = $elemetoP->porcComisionInt;
            $idTipoPreprensa = $elemetoP->idTipoPreprensa;
            $conRetoque = $elemetoP->conRetoque;
            $idTipoEncuadernado = $elemetoP->idTipoEncuadernado;
            $otroEncuadernado = $elemetoP->otroEncuadernado;
            $obsEncuadernado = $elemetoP->obsEncuadernado;
            $conFullfilment = $elemetoP->conFullfilment;
            $fecRegistro = $fecha->format('d-m-Y H:i:s');
            $idUsuarioRegistro = $this->idUsuario();
            $Decripcion = $elemetoP->Decripcion;
            $Observaciones = $elemetoP->Observaciones;
            $idTipoDistribucion = $elemetoP->idTipoDistribucion;
            $numPresupuestoMTC = null;
            $fecEdit = null;
            $idUsuarioEdit = null;
            $Cantidad = 0;
            $preUnit = 0;
            $Importe = 0;

            DB::statement(
                'exec spInsertElemento ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                           ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                           ?, ?, ?, ?, ?, ?',
                [
                    $idTipoItem,
                    $nomElementoP,
                    $cant1,
                    $cant2,
                    $cant3,
                    $cant4,
                    $cant5,
                    $numPresupuesto,
                    $fecTopeCot,
                    $fecReqEntProd,
                    $esNuevo,
                    $preObjetivo,
                    $preCompetencia,
                    $nomCompetencia,
                    $idEstadoItem,
                    $porcMargen,
                    $porcFinanciamiento,
                    $porcComisionExt,
                    $porcComIsionInt,
                    $idTipoPreprensa,
                    $conRetoque,
                    $idTipoEncuadernado,
                    $otroEncuadernado,
                    $obsEncuadernado,
                    $conFullfilment,
                    $fecRegistro,
                    $idUsuarioRegistro,
                    $Decripcion,
                    $Observaciones,
                    $idTipoDistribucion,
                    $numPresupuestoMTC,
                    $fecEdit,
                    $idUsuarioEdit,
                    $Cantidad,
                    $preUnit,
                    $Importe,
                ]
            );

            $elemento_id = DB::table('ElementosP')->max('idElementoP');
            $elemento_presupuestado_acabados = ElementosPacabados::where('idElementoP', $elemetoP->idElementoP)->get();

            if (count($elemento_presupuestado_acabados) > 0) {

                foreach ($elemento_presupuestado_acabados as $ep_acabado) {
                    $idElementoP = $elemento_id;
                    $numInterior = $ep_acabado->numInterior;
                    $conDoblado = $ep_acabado->conDoblado;
                    $conPerforado = $ep_acabado->conPerforado;
                    $conPersonalizado = $ep_acabado->conPersonalizado;
                    $conSuajado = $ep_acabado->conSuajado;
                    $conPegado = $ep_acabado->conPegado;
                    $conPlecado = $ep_acabado->conPlecado;
                    $conOtro = $ep_acabado->conOtro;
                    $descOtro = strtoupper($ep_acabado->descOtro);
                    $conGrabado = $ep_acabado->conGrabado;
                    $conHotStamping = $ep_acabado->conHotStamping;
                    $conGatefold = $ep_acabado->conGatefold;
                    $idTipoGatefold = $ep_acabado->idTipoGatefold;

                    DB::statement(
                        'exec spInsertEPAcabados ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                        [
                            $idElementoP,
                            $numInterior,
                            $conDoblado,
                            $conPerforado,
                            $conPersonalizado,
                            $conSuajado,
                            $conPegado,
                            $conPlecado,
                            $conOtro,
                            $descOtro,
                            $conGrabado,
                            $conHotStamping,
                            $conGatefold,
                            $idTipoGatefold,
                        ]
                    );
                }
            }

            $elemento_presupuestado_acabados_general = ElementosPacabadosGral::where('idElementoP', $elemetoP->idElementoP)->get();

            if (count($elemento_presupuestado_acabados_general) > 0) {
                foreach ($elemento_presupuestado_acabados_general as $ep_acabados_general) {
                    $idElementoP = $elemento_id;
                    $idAcabado = $ep_acabados_general->idAcabado;

                    DB::statement('exec spInsertEPAcabadosGral ?, ?', [
                        $idElementoP,
                        $idAcabado,
                    ]);
                }
            }

            $elemento_presupuestado_empaque = ElementosPempaque::where('idElementoP', $elemetoP->idElementoP)->get();

            if (count($elemento_presupuestado_empaque) > 0) {
                foreach ($elemento_presupuestado_empaque as $ep_empaque) {
                    $idElementoP = $elemento_id;
                    $idTipoEmpaque = $ep_empaque->idTipoEmpaque;
                    $obsEmpaque = $ep_empaque->obsEmpaque;

                    DB::statement('exec spInsertEPEmpaque ?, ?, ?', [
                        $idElementoP,
                        $idTipoEmpaque,
                        $obsEmpaque,
                    ]);
                }
            }

            $elemento_presupuestado_medidas = ElementosPmedidas::where('idElementoP', $elemetoP->idElementoP)->get();

            if (count($elemento_presupuestado_medidas) > 0) {
                foreach ($elemento_presupuestado_medidas as $ep_medidas) {
                    $idElementoP = $elemento_id;
                    $numInterior = $ep_medidas->numInterior;
                    $largoExtendido = $ep_medidas->largoExtendido;
                    $anchoExtendido = $ep_medidas->anchoExtendido;
                    $largoFinal = $ep_medidas->largoFinal;
                    $anchoFinal = $ep_medidas->anchoFinal;
                    $numPaginas = $ep_medidas->numPaginas;
                    $idTipoPapel = $ep_medidas->idTipoPapel;
                    $PapelEspecial = strtoupper($ep_medidas->PapelEspecial);
                    $numTintasF = $ep_medidas->numTintasF;
                    $numTintasV = $ep_medidas->numTintasV;
                    $barniz = $ep_medidas->barniz;
                    $idTipoBarniz = $ep_medidas->idTipoBarniz;
                    $numCarasLaminado = $ep_medidas->numCarasLaminado;
                    $idTipoLaminado = $ep_medidas->idTipoLaminado;
                    $nombreInterior = strtoupper($ep_medidas->nombreInterior);
                    $TipoPapelgrs = $ep_medidas->TipoPapelgrs;

                    DB::statement('exec spInsertEPMedidas ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                        $idElementoP,
                        $numInterior,
                        $largoExtendido,
                        $anchoExtendido,
                        $largoFinal,
                        $anchoFinal,
                        $numPaginas,
                        $idTipoPapel,
                        $PapelEspecial,
                        $numTintasF,
                        $numTintasV,
                        $barniz,
                        $idTipoBarniz,
                        $numCarasLaminado,
                        $idTipoLaminado,
                        $nombreInterior,
                        $TipoPapelgrs,
                    ]);
                }
            }

            $this->bitacora('Elementos P', 'Clonar ', 'Elemento ', $elemento_id, $this->loginUsuario(), 5);

            if ($elemeto_PR !== null) {
                DB::statement(
                    'exec spInsertRelacionEPOportunidad ?, ?, ?, ?',
                    [
                        $oportunidad_nueva->idOportunidad,
                        $elemeto_PR->idElementoP,
                        $elemeto_PR->idUsuarioRelaciona,
                        $fecha->format('d-m-Y H:i:s'),
                    ]
                );
            }
        }
        // }

        $this->bitacora($this->modulo, 'Clonar ', 'Nueva Oportunidad : ' . $folio, $idOportunidadNuevo, $this->loginUsuario(), 5);
        $this->bitacora($this->modulo, 'Agregar ', 'A partir de Oportunidad : ' . $request->idOportunidad, $request->idOportunidad, $this->loginUsuario(), 5);

        return redirect()->route('Oportunidades');
    }

    public function store(Request $request)
    {
        $fecha = new \DateTime();
        $maquila = '';
        $premio = 0;

        $cotizadorLibre = $this->cotizadorLibre();
        $custLibre = $this->customerLibre();

        if ($request->Maquila === 'on') {
            $maquila = $request->Maquila;
        }

        if ($request->Premio === 'on') {
            $premio = 1;
        }

        $this->validate($request, [
            'idCliente' => 'required', 'nomOportunidad' => 'required'
        ]);

        $folio = DB::table('Oportunidades')->max('folio') + 1;

        $prospecto = DB::table('v_catClientesActivosCombo')->where('idCliente', $request->idCliente)->where('idClienteINT', 0)->get();
        $op_prospecto = 0;

        if (count($prospecto) > 0) {
            if ($prospecto[0]->Tipo == 'PROSPECTO') {
                $op_prospecto = 1;
            }
        }

        $idUsuario = $this->idUsuario();
        $idRol = $this->idRol();

        $relacionAuxiliar = DB::table('rel_Agente_Aux')->where(function ($query) use ($idUsuario) {
            $query->where('idAuxiliar', $idUsuario)
                ->orWhere('idAuxiliar1', $idUsuario)
                ->orWhere('idAuxiliar2', $idUsuario)
                ->orWhere('idAuxiliar3', $idUsuario);
        })->first();

        $idAgenteEquipo = $idRol == 9 ? $relacionAuxiliar->idAgente : ($request->idAgente != null ? $request->idAgente : $idUsuario);

        if ($idUsuario == 29) {
            $idAgenteEquipo = $request->idAgente;
        }

        // Equipo asignado al usuario
        $equipo = DB::table('IntegrantesEquipos')
                        ->leftJoin('catEquipos', 'IntegrantesEquipos.idEquipo', '=', 'catEquipos.idEquipo')
                        ->where('IntegrantesEquipos.idUsuario', $idAgenteEquipo)
                        ->where('catEquipos.estatus', 1)
                        ->first();

        $agenteId = ($idRol == 9 && $idUsuario != 29) ? $relacionAuxiliar->idAgente : ($request->idAgente != null ? $request->idAgente : $idUsuario);

        $equipo = $equipo != null ? $equipo->idEquipo : (in_array($request->idUsuario, [3, 21, 12456]) ? 1 : null);
        $agente = $agenteId != null ? $agenteId : $request->idAgente;
        // return $this->customerAsignado();

        DB::statement(
            'exec spInsertOportunidad ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $request->idCliente,
                $request->nomOportunidad,
                1, //Estatus
                $request->idTipo,
                2, //Exclusion
                0, //Probabilidad
                $agente, // $request->idAgente,
                $maquila,
                $premio,
                0, //Valor
                $fecha->format('d-m-Y H:i:s'),
                $request->idUsuario,
                NULL, // $this->customerAsignado(),
                $equipo != null ? $equipo : null, // $request->idEquipo,
                null, // fecReqPreCot,
                $folio,
                $op_prospecto,
                0, //Tipo Clonado
                0, // Id Customer
                $request->idContacto,
                $request->nota,
                $request->seRequiereMaquila === 'on' ? 1 : 2, // seRequiereMaquila
                $request->tieneExcel === 'on' ? 1 : 0, // tieneExcel
            ]
        );

        // Buscamos la oportunidad
        $oportunidad = Oportunidades::where('folio', $folio)->first();
        // $oportunidad->idAtendido = $custLibre;
        // $oportunidad->idCustomer_ = $cotizadorLibre;
        // $oportunidad->save();

        // Guardamos en la bitacora de fechas
        $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $request->idUsuario, 1);

        $cadena = implode('|', $request->all());

        $this->bitacora($this->modulo, 'Agregar ', 'Oportunidad | ' . (string) $cadena, $folio, $this->loginUsuario(), 5);

        // $this->bitacora($this->modulo, 'Agregar ', 'Oportunidad ', $folio, $this->loginUsuario(), 5);

        return redirect()->route('Editar Oportunidad', ['id' => $oportunidad->idOportunidad]);
    }

    public function show(Request $request)
    {
        $id_etapa = $request->get('idEtapa');
        $estatus = DB::select('exec GetEstatusCombo @idEtapa=' . $id_etapa);

        return $estatus;
    }

    public function edit($id)
    {
        $Login = $this->loginUsuario();

        if ($Login == null && $id == -1) {
            return redirect(config('app.home_absolute_url'));
        }

        $usuario = $this->datosUsuario();

        if ($Login == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
        $permisos =  $this->permisosModulo($this->modulo);
        $id_usuario = $this->idUsuario();
        $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $Login);
        $idrol = $permisos[0]->idRol;
        $oportunidades = DB::select('exec GetOportunidad @idOportunidad=' . $id);

        $oportunidad = $oportunidades;
        // return $oportunidades;

        if ($idrol == 3) {
            $oportunidades = DB::select('exec GetOportunidadXCustomer ?, ?', [$id, $Login]);
        }

        // /******* Funcionalidad para validar si la oportunidad tiene un presupuesto registrado y por alguna razon no se ha realizado el update en la oportunidad *******/
        // Validamos si cuenta con un presupuesto ya registrado
        $presupuestos = DB::table('relOportunidadesPresupuestos')->where('idOportunidad', $oportunidad[0]->idOportunidad)->where('aplica', 1)->get();

        // Validamos si tiene al menos un presupuesto y si el estatus es 8
        if (count($presupuestos) > 0 && $oportunidad[0]->idEstatus == 8 && $oportunidad[0]->Valor == 0.0) {
            // sumamos los importes de los presupuestos
            $total_cantidad = 0;

            foreach ($presupuestos as $item) {
                $total_cantidad += $item->importe;
            }

            // Actualizamos el importe de la oportunidad
            DB::statement('exec sp_updateImportePresupuesto ?, ?', [
                $oportunidad[0]->idOportunidad,
                $total_cantidad,
            ]);
        }
        // /******* FIN Funcionalidad para validar si la oportunidad tiene un presupuesto registrado y por alguna razon no se ha realizado el update en la oportunidad *******/

        // /* Solo para refrescar */
        $oportunidades = DB::select('exec GetOportunidad @idOportunidad=' . $id);

        $oportunidad = $oportunidades;
        // return $oportunidades;

        if ($idrol == 3) {
            $oportunidades = DB::select('exec GetOportunidadXCustomer ?, ?', [$id, $Login]);
        }
        // /* fin Solo para refrescar */


        // Es subgerente y puede ver las oportunidades de su equipo
        $esSubGerente = DB::table('vendedoresAsignados')
                        ->where('idUsuario', $id_usuario)
                        ->where('idVendedor', $oportunidad[0]->id_Agente)
                        ->exists();

        if (!$esSubGerente) {
            // Validamos que otro vendedor no este viendo oportunidades que no le pertenecen
            if ($idrol == 4 || $idrol == 9) {
                $agente_oportunidad = $id_usuario;
    
                if ($idrol == 9) {
                    $agente_oportunidad = DB::table('rel_Agente_Aux')->where('idAuxiliar', $id_usuario)->orWhere('idAuxiliar1', $id_usuario)->orWhere('idAuxiliar2', $id_usuario)->orWhere('idAuxiliar3', $id_usuario)->first();
                    $agente_oportunidad = ($agente_oportunidad != null) ? $agente_oportunidad->idAgente : $oportunidad[0]->id_Agente;
                }
    
                // Verificamos si el cliente le pertenece al agente
                $cliente_oportunidad = DB::table('v_catClientesActivosCombo')->where('idCliente', $oportunidad[0]->idCliente)->first();
    
                if ($id_usuario != 29) {
                    $subAgentes = DB::table('rel_dosAgentes')->where('idAgentePrimario', $agente_oportunidad)->where('estatus', 1)->first();

                    if ($subAgentes != null) {
                        $subAgente1 = $subAgentes->idAgente1;
                        $subAgente2 = $subAgentes->idAgente2;
                    } else {
                        $subAgente1 = null;
                        $subAgente2 = null;
                    }

                    // Validar si el agente de la oportunidad es diferente al usuario que la esta queriendo ver
                    if (
                        ($agente_oportunidad != $oportunidad[0]->id_Agente) && ($cliente_oportunidad->idAgente != $agente_oportunidad) &&
                        (
                            ($subAgente1 != null && $subAgente1 != $oportunidad[0]->id_Agente && $subAgente1 != $cliente_oportunidad->idAgente) ||
                            ($subAgente2 != null && $subAgente2 != $oportunidad[0]->id_Agente && $subAgente2 != $cliente_oportunidad->idAgente)
                        )
                    ) {
                        return redirect()->route('Oportunidades');
                    }
                }
            }
        }

        $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
        // return $oportunidad;
        $idEquipo_idAgente = $oportunidad[0]->id_Agente;
        $idEquipo = $oportunidad[0]->idEquipo;
        $ClienteINT = $oportunidades[0]->ClienteINT;
        $contactos = DB::table('v_catContactos')->where('idClienteINT', $ClienteINT)->get();
        $tipo_barnices = DB::table('catEPtipoBarnices')->get();

        // return $contactos;
        if ($agentes == null || count($agentes) == 0) {
            if ($equipos == null) {
                $equipos = catEquipos::where('estatus', 1)->orderBy('idEquipo', 'asc')->get();
                $combo_vendedores = DB::select('exec GetAgentesCombo');
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
            } else {
                $equipos;
                $idEquipo_idAgente = $oportunidad[0]->id_Agente;
                $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $idEquipo_idAgente);
                $combo_vendedores = DB::select('exec GetAgentesCombo');
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();
            }
        } else {
            $agente_id = $agentes[0]->idAgente;
            $idEquipo_idAgente = $oportunidad[0]->id_Agente;
            $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $idEquipo_idAgente);
            $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
            $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->orWhere('idAgente2', $id_usuario)->get();
        }

        $estatus = catEstatus::all();
        $tipos = catOportTipos::all();
        $previsiones = catOportExclusion::all();
        $probabilidades = catOportProbabilidad::all();
        $id_Cliente_INT = $oportunidad[0]->ClienteINT;
        $combo_cotizadores = DB::select('exec GetCotizadoresCombo');
        $comboCot = DB::table('v_usuariosCotizadoresCompleteWithSomeCotizadores')->get();
        $comboCus = DB::table('v_usuariosCustomersComplete')->get();
        $motivoPerdida = catOportMotivoPerdida::all();
        $sucursales = Sucursales::where('idClienteINT', $id_Cliente_INT)->get();
        $sucursales_combo_entregas = Sucursales::orderBy('Nombre', 'asc')->where('idClienteINT', $id_Cliente_INT)->where('Estatus', 'ALTA')->get();
        $total_cantidad = 0;
        $editarOportunidades = session('editarOportunidades');
        $permisoEstructural = session('DiseñoEstructural');
        $folio = DB::table('Estructural')->max('master') + 1;
        $crearTickets = session('crearEstructural');
        $cuentas_cc = DB::table('v_DocumentosOportMailCC')->where('idOportunidad', (int)$id)->get();
        $revisa_oportunidad_combo = DB::table('RevisaOportunidad')->where('estatus', 1)->get();

        $oportunidadCambiosRealizar = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $id)->where('realizar', 1)->get();
        $oportunidadCambiosRealizados = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $id)->where('realizado', 1)->get();

        $total_realizar = count($oportunidadCambiosRealizar);
        $total_realizados = count($oportunidadCambiosRealizados);

        $total_cambios_realizados = $total_realizar - $total_realizados;

        if ($idrol == 4 || $idrol == 8 || $idrol == 9 || $idrol == 1 || $idrol == 7) {
            $disabledVentas = '';
            $hiddenPreregistro = 'block';
        } else {
            $disabledVentas = 'disabled';
            $hiddenPreregistro = 'none';
        }

        if ($idrol == 2 || $idrol == 8 || $idrol == 1 || $idrol == 7 || $idrol == 10 || $idrol == 3) {
            $disabledCotizadores = '';
        } else {
            $disabledCotizadores = 'disabled';
        }

        if ($idrol == 3 || $idrol == 1016 || $idrol == 1023 || $idrol == 8 || $idrol == 1 || $idrol == 7 || $idrol == 10 || ($idrol == 4 && $total_cambios_realizados != 0) || ($idrol == 9 && $total_cambios_realizados != 0)) {
            $disabledCustomers = '';
        } else {
            $disabledCustomers = 'disabled';
        }
        
        if ($idrol == 2 || $idrol == 3 || $idrol == 1016 || $idrol == 1023 || $idrol == 8 || $idrol == 1 || $idrol == 7 || $idrol == 10) {
            $disabledRealizado = 'true';
        } else {
            $disabledRealizado = 'false';
        }

        if ($idrol == 1023 || $idrol == 8 || $idrol == 1 || $idrol == 7 || $idrol == 10) {
            $disabledCustomerAsignado = '';
        } else {
            $disabledCustomerAsignado = 'disabled';
        }

        // Validamos si la oportunidad cuenta con cambios minimos
        $hasSolicitudesCambios = DB::table('solicitudCambiosCandy')->where('IdOportunidad', $oportunidad[0]->idOportunidad)->count();
        
        if ($oportunidad[0]->cambiosMinimos > 0) {
            $titulo_cambios_minimos = 'Solicitud de Cambios Minimos';
        } else {
            $titulo_cambios_minimos = '';
        }
        
        // if ($idrol == 3 || $idrol == 8 || $idrol == 1 || $idrol == 7 || $idrol == 10 || $idrol == 1023) {
        //     $disabledbtnPre = '';
        // } else {
        //     $disabledbtnPre = 'disable';
        // }

        foreach ($revisa_oportunidad_combo as $item) {
            if ($item->idRevisa == $oportunidad[0]->idRevisado) {
                $disabledRevisa = '';
            } else {
                $disabledRevisa = 'disabled';
            }
        }

        // Validacion para saber si puede interactuar con el cliente
        $finalValidation = $this->oportunidadCliente($oportunidad[0]->ClienteINT, $usuario[0]->Agente);

        if ($idrol == 4) {
            $oportunidadesCache = DB::table('v_OportunidadesXClientesInt')
                                    ->where('idAgente', $id_usuario)
                                    ->orderBy('folio', 'asc')
                                    ->get();
        } else {
            $oportunidadesCache = DB::table('v_OportunidadesXClientesInt')
                                    ->orderBy('folio', 'asc')
                                    ->get();
        }

        $catTiposProductos = DB::table('v_CatTiposProductos')
                                ->where('estatus', 1)
                                ->get();

        $catTiposProductosAgrupados = $catTiposProductos->groupBy('tipo')->map(function ($items, $tipo) {
                                        return [
                                            'label' => $tipo,
                                            'options' => $items->map(function ($item) {
                                                return [
                                                    'id' => $item->id,
                                                    'descripcion' => $item->descripcion,
                                                ];
                                            })->values()->toArray()
                                        ];
                                    })->values()->toArray();

        $documentosIds = DB::table('relOport_Documento')->where('idOportunidad', $oportunidad[0]->idOportunidad)->pluck('idDocumento')->toArray();

        // Tiene tripas ya con folio de preprensa
        $tieneTripasConFolioPreprensa = DB::table('DocumentosOport')
                                        ->whereIn('idDocumento', $documentosIds)
                                        ->where('idTipoDocumento', 13) // Tripas
                                        ->whereNotNull('folio_preprensa')
                                        ->where('folio_preprensa', '!=', '')
                                        ->exists();

        // Obtenemos el listado de motivos de rechazo de una oportunidad
        $motivosRechazoOportunidad = DB::table('catMotivosRechazoOport')
                                        ->where('estatus', 1)
                                        ->select('id', 'descripcion')
                                        ->get();

        $catCategoriasFsc = DB::table('cat_CategoriaFsc')->where('estatus', 1)->get();
        $catSubCategoriasFsc = DB::table('cat_SubCategoriaFsc')->where('estatus', 1)->get();

        // Total de elementos ya creados para la oportunidad
        $total_elementos_oportunidad = SolicitudCotizacion::where('oportunidad_id', $oportunidad[0]->idOportunidad)->count();

        $subAgentes = DB::table('rel_dosAgentes')->where('idAgentePrimario', $id_usuario)->where('estatus', 1)->first();

        if ($subAgentes != null) {
            $subAgente1 = $subAgentes->idAgente1;
            $subAgente2 = $subAgentes->idAgente2;
        } else {
            $subAgente1 = null;
            $subAgente2 = null;
        }

        $tieneSubAgentes = $subAgentes != null;

        // if ($oportunidad[0]->EsCliente == 1) {
        foreach ($permisos as $permiso) {
            if ($editarOportunidades == 'EditarOportunidades' || $idrol == 1016 || $idrol == 1023) {
                return view('oportunidades.edit', compact('oportunidad', 'comboCot', 'comboCus', 'combo_clientes', 'estatus', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'idrol', 'idEquipo_idAgente', 'sucursales', 'sucursales_combo_entregas', 'total_cantidad', 'contactos', 'permisoEstructural', 'tipo_barnices', 'folio', 'crearTickets', 'cuentas_cc', 'revisa_oportunidad_combo', 'total_cambios_realizados', 'disabledVentas', 'disabledCotizadores', 'disabledCustomers', 'disabledCustomerAsignado', 'disabledRevisa', 'hiddenPreregistro', 'disabledRealizado', 'titulo_cambios_minimos', 'finalValidation', 'oportunidadesCache', 'catTiposProductosAgrupados', 'tieneTripasConFolioPreprensa', 'motivosRechazoOportunidad', 'catCategoriasFsc', 'catSubCategoriasFsc', 'total_elementos_oportunidad', 'tieneSubAgentes'));
            } else {
                if ($finalValidation || $esSubGerente || $Login == $oportunidad[0]->Login || $Login == $oportunidad[0]->LoginAuxiliar || $Login == $oportunidad[0]->LoginAuxiliar2 || $Login == $oportunidad[0]->LoginAuxiliar3 || $Login == $oportunidad[0]->LoginAuxiliar4 || $Login == $oportunidad[0]->LoginCustomer || $id_usuario == $oportunidad[0]->idAgente2 || $id_usuario == $oportunidad[0]->idAgente3 || $subAgente1 == $oportunidad[0]->id_Agente || $subAgente2 == $oportunidad[0]->id_Agente) {
                    if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Todo') {
                        return view('oportunidades.edit', compact('oportunidad', 'comboCot', 'comboCus', 'combo_clientes', 'estatus', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'idrol', 'idEquipo_idAgente', 'sucursales', 'sucursales_combo_entregas', 'total_cantidad', 'contactos', 'permisoEstructural', 'tipo_barnices', 'folio', 'crearTickets', 'cuentas_cc', 'revisa_oportunidad_combo', 'total_cambios_realizados', 'disabledVentas', 'disabledCotizadores', 'disabledCustomers', 'disabledCustomerAsignado', 'disabledRevisa', 'hiddenPreregistro', 'disabledRealizado', 'titulo_cambios_minimos', 'finalValidation', 'oportunidadesCache', 'catTiposProductosAgrupados', 'tieneTripasConFolioPreprensa', 'motivosRechazoOportunidad', 'catCategoriasFsc', 'catSubCategoriasFsc', 'total_elementos_oportunidad', 'tieneSubAgentes'));
                    } else if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Ninguno') {
                        return back();
                    }
                } else {
                    return back();
                }
            }
        }
        // } elseif($oportunidad[0]->ProspectoActivo == 0 || $oportunidad[0]->CreaOportunidades == 0) {
        //     return back();
        // }
        // }
    }

    public function listadoRechazoOport(Request $request)
    {
        $oportunidadID = $request->get('oportunidadID');
        $motivosRechazoOportunidadList = DB::table('v_MotivosRechazoOport')->where('oportunidad_id', $oportunidadID)->orderBy('id', 'desc')->get();

        return DataTables::of($motivosRechazoOportunidadList)
            ->make(true);
    }

    public function oportunidadCliente($numInt, $agente)
    {
        try {
            $cliente = DB::table('lito.dbo.cte')->where('Agente', 'like', '%' . $agente . '%')->where('Cliente', $numInt)->first();

            if ($cliente != null) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            // return $cambios_x_realizar;
            $iU = $this->idUsuario();
            
            // $cambios_x_realizar = ($request->flagAddNewRequestChanges == "true");
            $cambios_x_realizar = $request->cambiosRealizadosEdit;

            $this->configMail();

            $fecha = new \DateTime();
            $oportunidad = Oportunidades::where('idOportunidad', '=', $id)->first();

            // return $request;
            $prospecto = DB::table('v_catClientesActivosCombo')->where('idCliente', $oportunidad->idCliente)->where('idClienteINT', 0)->get();

            if (count($prospecto) > 0) {
                if ($prospecto[0]->Tipo == 'PROSPECTO') {
                    $oportunidad->prospecto = 1;
                } else {
                    $oportunidad->prospecto = 0;
                }
            }

            if ($request->Retencion == 'on') {
                $oportunidad->notaRetencion = $request->notaRetencion;
            } else {
                if ($oportunidad->notaRetencion == null) {
                    $oportunidad->notaRetencion = $request->notaRetencion;
                }
            }
            
            // ELiminamos archivoAutorizado del request para que no intente guardarlo en la oportunidad
            if ($request->has('archivoAutorizado')) {
                $request->request->remove('archivoAutorizado');
            }

            // Validamos si se puede editar el motivo para solicitid de presupuesto
            if ($request->motivo_informativo_presupuesto != null && $request->motivo_informativo_presupuesto != '') {
                DB::statement('exec sp_ActualizarMotivoInformativoPresupuesto ?, ?', [
                    $oportunidad->idOportunidad,
                    strtoupper($request->motivo_informativo_presupuesto)
                ]);
            }

            $idUsuario = $this->idUsuario();

            $id_estatus = 0;
            $idAtendido = $request->idAtendido != null ? $request->idAtendido : $oportunidad->idAtendido;
            $idCustomer = $request->idCustomer != null ? $request->idCustomer : $oportunidad->idCustomer_;

            if ($request->idStatus == '2') {
                $id_estatus = 2;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->idEstatus = $id_estatus;
                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 2);

                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar != null) {
                    $oportunidad->fecListoCotizar = null;
                }
                if ($oportunidad->fecCotizando != null) {
                    $oportunidad->fecCotizando = null;
                }
                if ($oportunidad->fecRevision != null) {
                    $oportunidad->fecRevision = null;
                }
                if ($oportunidad->fecRevisionTec != null) {
                    $oportunidad->fecRevisionTec = null;
                }
                if ($oportunidad->fecEsperaVobo != null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto != null) {
                    $oportunidad->fecListoPresupuesto = null;
                }
                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
            }

            if ($request->idStatus == '3') {
                $id_estatus = 3;
                $cotizadorLibreUnico = $this->cotizadorLibre();
                $custLibreUnico = $this->customerLibre();

                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }

                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->idEstatus = $id_estatus;

                    // Validamos si es la primera vez la oportunidad realiza su recorrido normal(sin cambios)
                    if ($oportunidad->cambiosMinimos == 0 && $oportunidad->flagCambioEspecificaciones == 0 && $oportunidad->flagCambioPrecios == 0 && $oportunidad->flagCambioTexto == 0) {
                        // Entra al carrucel
                        DB::statement('exec sp_updateStatusAndCustomerCotizadorOport ?,?,?,?', [
                            $oportunidad->idOportunidad,
                            $custLibreUnico,
                            $cotizadorLibreUnico,
                            $id_estatus
                        ]);
                    } else {
                        // No entra
                        DB::statement('exec sp_updateStatusAndCustomerCotizadorOport ?,?,?,?', [
                            $oportunidad->idOportunidad,
                            $idAtendido,
                            $idCustomer,
                            $id_estatus
                        ]);
                    }

                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 3);

                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }

                if ($oportunidad->fecCotizando != null) {
                    $oportunidad->fecCotizando = null;
                }
                if ($oportunidad->fecRevision != null) {
                    $oportunidad->fecRevision = null;
                }
                if ($oportunidad->fecRevisionTec != null) {
                    $oportunidad->fecRevisionTec = null;
                }
                if ($oportunidad->fecEsperaVobo != null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto != null) {
                    $oportunidad->fecListoPresupuesto = null;
                }
                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
            }

            if ($request->idStatus == '4') {
                $id_estatus = 4;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {

                    if ($oportunidad->cotizador == null) {
                        DB::statement('exec sp_updateCustCotCandy ?,?,? ', [
                            $id,
                            1,
                            $oportunidad->idCustomer_
                        ]);
                    }

                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 4);

                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision != null) {
                    $oportunidad->fecRevision = null;
                }
                if ($oportunidad->fecRevisionTec != null) {
                    $oportunidad->fecRevisionTec = null;
                }
                if ($oportunidad->fecEsperaVobo != null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto != null) {
                    $oportunidad->fecListoPresupuesto = null;
                }
                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
                $oportunidad->idEstatus = $id_estatus;
            }

            if ($request->idStatus == '13') {
                $id_estatus = 13;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {

                    $oportunidad->idEstatus = $id_estatus;
                    $oportunidad->usuarioRevisionTec = 12520;

                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 13);

                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }

                if ($oportunidad->fecRevision != null) {
                    $oportunidad->fecRevision = null;
                }

                if ($oportunidad->fecEsperaVobo != null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto != null) {
                    $oportunidad->fecListoPresupuesto = null;
                }
                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
            }


            if ($request->idStatus == '5') {
                $id_estatus = 5;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }

                if ($oportunidad->fecRevision == null) {

                    $oportunidad->idEstatus = $id_estatus;

                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 5);

                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo != null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto != null) {
                    $oportunidad->fecListoPresupuesto = null;
                }
                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }

                $idRevisa = $this->idRevisado($request->tipoEspera);
                $oportunidad->idRevisado = $idRevisa;
            }
            // ========================= YA NO APLICA ESTATUS 6 ==========================================

            if ($request->idStatus == '6') {
                $id_estatus = 6;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoPresupuesto != null) {
                    $oportunidad->fecListoPresupuesto = null;
                }
                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
                $oportunidad->idEstatus = $id_estatus;
            }

            // ===========================================================================

            if ($request->idStatus == '7') {
                $id_estatus = 7;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }

                if ($oportunidad->fecListoPresupuesto == null) {

                    $oportunidad->idEstatus = $id_estatus;
                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 6);

                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }

                if ($oportunidad->fecPresupTerminado != null) {
                    $oportunidad->fecPresupTerminado = null;
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
            }

            if ($request->idStatus == '8') {
                $id_estatus = 8;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');

                    // Guardamos en la bitacora de fechas
                    $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 7);
                }
                if ($oportunidad->fecPrepedido != null) {
                    $oportunidad->fecPrepedido = null;
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }

                $oportunidad->idEstatus = $id_estatus;
                $oportunidad->solicita_prepedido = null;
                $oportunidad->solicitud_preprensa = null;
                $oportunidad->preprensa_concluida = 0;
                $oportunidad->numOrdenMTC = null;

                DB::statement('sp_changeStatusOportunidadCambiosMinimos ?,?', [
                    $oportunidad->idOportunidad,
                    $id_estatus
                ]);
            }

            if ($request->idStatus == '9') {
                $id_estatus = 14;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensa != null) {
                    $oportunidad->fecSolPreprensa = null;
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }

                // $idCustomer = $this->customerLibre();
                $oportunidad->idEstatus = $id_estatus;
            }
            
            if ($request->idStatusEnd == '16') {
                $id_estatus = 16;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida == null) {
                    $oportunidad->fecSolPreprensaConcluida = $fecha->format('d-m-Y H:i:s');
                }
                // if ($oportunidad->fecResultadoF != null) {
                //     $oportunidad->fecResultadoF = null;
                // }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }

                // $idCustomer = $this->customerLibre();
                $oportunidad->preprensa_concluida = 1;
                $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 10);
            }

            if ($request->idStatus == '10') {
                $id_estatus = 10;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida == null) {
                    $oportunidad->fecSolPreprensaConcluida = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecResultadoF == null) {
                    $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
                $oportunidad->idEstatus = $id_estatus;
            }

            if ($request->idStatus == '11') {
                $id_estatus = 11;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida == null) {
                    $oportunidad->fecSolPreprensaConcluida = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecResultadoF == null) {
                    $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }

                $oportunidad->idEstatus = $id_estatus;

                DB::statement('exec sp_updateStatusAndCustomerOport ?,?,?', [
                    $oportunidad->idOportunidad,
                    $idAtendido,
                    $id_estatus
                ]);
            }

            if ($request->idStatus == '12') {
                $id_estatus = 12;
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida == null) {
                    $oportunidad->fecSolPreprensaConcluida = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecResultadoF == null) {
                    $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecOrdenConcluida == null) {
                    $oportunidad->fecOrdenConcluida = $fecha->format('d-m-Y H:i:s');
                }
                $oportunidad->idEstatus = $id_estatus;
            }

            $oportunidad->fecUltimoCambio = $fecha->format('d-m-Y H:i:s');
            $oportunidad->idUsuarioUltCambio = $request->idUsuario;
            $oportunidad->update($request->all());
            $retenciones = RetencionOportunidades::where('idOportunidad', '=', $id)->get();
            $retencion = $retenciones->last();
            $retencion_oportunidad = [];

            if ($request->Retencion == 'on') {
                if ($retencion == null) {
                    $retencion_oportunidad = [
                        'idOportunidad' => $request->id,
                        'notaRetencion' => $request->notaRetencion,
                        'fecRetencionInicio' => $fecha->format('d-m-Y H:i:s'),
                        'fecRetencionFin' => null,
                        'idUsuario' => $request->idUsuario
                    ];
                    RetencionOportunidades::create($retencion_oportunidad);
                } else {
                    if ($retencion->fecRetencionFin != null) {
                        $retencion_oportunidad = [
                            'idOportunidad' => $request->id,
                            'notaRetencion' => $request->notaRetencion,
                            'fecRetencionInicio' => $fecha->format('d-m-Y H:i:s'),
                            'fecRetencionFin' => null,
                            'idUsuario' => $request->idUsuario
                        ];
                        RetencionOportunidades::create($retencion_oportunidad);
                    }
                }
            } else {
                if ($retencion != null) {
                    if ($retencion->fecRetencionInicio != null) {
                        $retencion_oportunidad = [
                            'idOportunidad' => $request->id,
                            'notaRetencion' => $retencion->notaRetencion,
                            'fecRetencionInicio' => $retencion->fecRetencionInicio,
                            'fecRetencionFin' => $fecha->format('d-m-Y H:i:s'),
                            'idUsuario' => $request->idUsuario
                        ];
                        $retencion->update($retencion_oportunidad);
                    }
                }
            }

            $oportunidad_plantilla = v_Oportunidades::where('idOportunidad', '=', $id)->first();

            // ================================== ENVIA CORREO ===============================================

            if ($oportunidad_plantilla->idEstatus == 8) {
                $folio = $oportunidad_plantilla->idOportunidad;
                $alertasPlantillas = catPlantillasAlertas::get();
                $estatus = $alertasPlantillas[3]->ValorCampo;
                $nombre_oportunidad = $oportunidad_plantilla->nomOportunidad;
                $fecha_oportunidad = Carbon::parse($oportunidad_plantilla->fecCreacion)->format('d/m/Y');
                $estatus_oportunidad = $estatus;
                $subject = $alertasPlantillas[3]->Asunto;
                $mensaje = $alertasPlantillas[3]->Mensaje;
                $nombre_agente = $oportunidad_plantilla->Agente;
                $route = "oportunidades";
                $nota = "";
                // $cuenta_usuario = $alertasPlantillas[3]->Destinatario;
                $cuenta_usuario = $oportunidad_plantilla->CorreoAgente;
                $cuenta_cc1 = $alertasPlantillas[3]->Destinatario;
                $cuenta_cc2 = $alertasPlantillas[3]->CopiarA;
                if ($oportunidad_plantilla->CorreoCustomer == null) {
                    $cuenta_cc3 = $cuenta_usuario;
                } else {
                    $cuenta_cc3 = $oportunidad_plantilla->CorreoCustomer;
                }
                $cuenta_cco = $alertasPlantillas[3]->CCO;
                $cuenta_auxiliar1 = $oportunidad_plantilla->CorreoAuxiliar;
                $cuenta_auxiliar2 = $oportunidad_plantilla->CorreoAuxiliar2;
                $cuenta_auxiliar3 = $oportunidad_plantilla->CorreoAuxiliar3;
                $cuenta_auxiliar4 = $oportunidad_plantilla->CorreoAuxiliar4;
                $fecha_solicitud = "";
                $fecha_entrega = "";
                $fecha_asignacion = "";
                $fecha_aut_archivo = "";
                $diasEntrega = null;
                $numOrdenMTC = null;

                // Mail::to($cuenta_usuario)->cc($cuenta_cc1)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[3], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[3], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[3], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_auxiliar2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[3], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_auxiliar2, $cuenta_auxiliar3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[3], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[3], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
            }

            if ($request->idStatus == '12') {
                $tickets = v_Estructural::where('idOportunidad',  $id)->where('Estado', 'CERRADO')->get();
                if (count($tickets) > 0) {
                    foreach ($tickets as $ticket) {
                        DB::statement('exec sp_reOpenTicket ?, ?, ?, ?, ?', [
                            strtoupper($this->jefeDiseñadores()),
                            'PENDIENTE DE ASIGNAR',
                            null,
                            null,
                            $ticket->Id_Ticket,
                        ]);

                        $motivo_cancelacion = null;
                        $folio = $oportunidad_plantilla->idOportunidad;
                        $alertasPlantillas = catPlantillasAlertas::get();
                        $cuenta_usuario = $alertasPlantillas[12]->CopiarA;
                        $cuenta_cc1 = $ticket->CorreoSolicitante;
                        $datos_usuario = $this->datosUsuario();
                        if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                            $cuenta_cc2 = $alertasPlantillas[12]->Destinatario;
                        } else {
                            if ($this->idRol() == 1) {
                                $cuenta_cc2 = $alertasPlantillas[12]->Destinatario;
                            } else {
                                $cuenta_cc2 = $datos_usuario[0]->Correo;
                            }
                        }
                        $cuenta_cc3 = $alertasPlantillas[12]->Destinatario;
                        $nombre_proyecto = $ticket->Tarea;
                        $folio = $ticket->master;
                        $subject = $alertasPlantillas[12]->Asunto;
                        $nombre_agente = $ticket->Agente;
                        $route = "estructural";
                        $fecha_solicitud = $ticket->FechaRegistro;
                        $mensaje = $alertasPlantillas[12]->Mensaje;
                        $asignadoA = strtoupper($this->jefeDiseñadores());

                        Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3])->send(new SendMailEstructural($alertasPlantillas[12], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, null));

                        $this->bitacora($this->modulo, 'Reapertura ', 'Ticket ', $ticket->master, $this->loginUsuario(), 2);
                    }
                }
            }

            // datos para la bitacora

            $valueComplete = [];

            foreach ($request->all() as $key => $value) {
                $valueComplete[] = "$key -> $value";
            }

            $cadena = implode('|', $valueComplete);

            $this->bitacora($this->modulo, 'Editar ', 'Oportunidad | ' . (string) $cadena, $request->id, $this->loginUsuario(), 2);
            // $this->bitacora($this->modulo, 'Editar ', 'Oportunidad con el Id Estatus ' . $id_estatus . ' - ', $request->id, $this->loginUsuario(), 2);

            return redirect()->back();
        } catch (\Exception $e) {
            abort(400);
        }
    }

    public function destroy($id)
    {
    }

    public function apiOportunidades(Request $request)
    {
        if ($request->texto == null) {
            $texto = '';
        } else {
            $texto = $request->texto;
        }

        $Login = $this->loginUsuario();
        $rol = $this->idRol();
        $id_usuario = $this->idUsuario();
        $usuario = $this->datosUsuario();

        switch ($rol) {
            case "2":
            case "7":
            case "1023":
            case "1016":
                if ($texto == '') {
                    $Oportunidades = DB::table('v_OportunidadesGrid');
                } else {
                    $Oportunidades = DB::select('exec sp_getApiOportunidadesFiltro ?,?,?,?', [$texto, $Login, 0, $id_usuario]);
                }
                break;
            case "3":
                if ($Login == 'smmorenoc') {
                    $Oportunidades = DB::select('exec GetOportunidadesXCustomerEspecial @Login=' . $Login);
                } else {
                    if ($texto == '') {
                        $Oportunidades = DB::table('v_OportunidadesGrid');//->where('LoginCustomer', $Login);
                        // $Oportunidades = DB::select('exec GetOportunidadesXCustomer @Login=' . $Login);
                        // $Oportunidades = DB::table('v_OportunidadesGrid')->where('LoginCustomer', $Login);
                    } else {
                        $Oportunidades = DB::select('exec sp_getApiOportunidadesFiltro ?,?,?,?', [$texto, $Login, 3, $id_usuario]);
                    }
                }
                break;
            case "4":
                if ($texto == '') {
                    $agentesSecundarios = DB::table('rel_dosAgentes')->where('idAgentePrimario', $id_usuario)->where('estatus', 1)->first();
                    // $Oportunidades = DB::table('v_OportunidadesGrid')->where('LoginAgente', $Login);

                    if ($agentesSecundarios != null) {
                        $Oportunidades = DB::table('v_OportunidadesVendedoresGrid')
                                            ->whereIn('vendedorFinal', [$id_usuario, $agentesSecundarios->idAgente1, $agentesSecundarios->idAgente2 ?? 0]);
                    } else {
                        $Oportunidades = DB::table('v_OportunidadesVendedoresGrid')
                                            ->where('vendedorFinal', $id_usuario)
                                            ->whereNotNull('Agente');
                    }

                } else {
                    $Oportunidades = DB::select('exec sp_getApiOportunidadesFiltro ?,?,?,?', [$texto, $Login, 4, $id_usuario]);
                }
                break;
            case "9":
                if ($texto == '') {
                    if ($id_usuario == 29) {
                        $default = [8357, 9, 29];

                        // Vendedores a cargo
                        $vendedoresAsignados = DB::table('vendedoresAsignados')
                            ->where('idUsuario', $id_usuario)
                            ->where('estatus', 1)
                            ->pluck('idVendedor')
                            ->toArray();

                        // Total a ver
                        $totalVer = array_merge($default, $vendedoresAsignados);

                        $Oportunidades = DB::table('v_OportunidadesGrid')->whereIn('idAgente', $totalVer);
                        // $Oportunidades = DB::table('v_OportunidadesGridAuxiliares')->where('LoginAuxiliar', 'LIKE', '%' . $Login . '%');
                    } else {

                        // Obtenemos el vendedor relacionado con el asistente
                        $vendedor = DB::table('rel_Agente_Aux')->where('idAuxiliar', $id_usuario)->orWhere('idAuxiliar1', $id_usuario)->orWhere('idAuxiliar2', $id_usuario)->orWhere('idAuxiliar3', $id_usuario)->first();

                        if ($vendedor != null) {
                            $Oportunidades = DB::table('v_OportunidadesVendedoresGrid')
                                            ->where('vendedorFinal', $vendedor->idAgente)
                                            ->whereNotNull('Agente');
                        } else {
                            $Oportunidades = DB::table('v_OportunidadesGrid')->where('LoginAuxiliar', 'LIKE', '%' . $Login . '%');
                        }
                        // $Oportunidades = DB::table('v_OportunidadesGrid')->where('LoginAuxiliar', 'LIKE', '%' . $Login . '%')->orWhere('Login', $Login);
                    }
                } else {
                    $Oportunidades = DB::select('exec sp_getApiOportunidadesFiltro ?,?,?,?', [$texto, $Login, 9, $id_usuario]);
                }
                break;
            case "10":
                if ($texto == '') {
                    $Oportunidades = DB::table('v_OportunidadesGrid');
                } else {
                    $Oportunidades = DB::select('exec sp_getApiOportunidadesFiltro ?,?,?,?', [$texto, $Login, 10, $id_usuario]);
                }
                break;
            default:
                if ($texto == '') {
                    $Oportunidades = DB::table('v_OportunidadesGrid');
                } else {
                    $Oportunidades = DB::select('exec sp_getApiOportunidadesFiltro ?,?,?,?', [$texto, $Login, 0, $id_usuario]);
                }
                break;
        }

        return DataTables::of($Oportunidades)
            ->addColumn('fecTope', function ($row) {
                return $row->fechaTopeOporCalc != null ? \DateTime::createFromFormat('Y-m-d H:i:s.u', $row->fechaTopeOporCalc)->setTimezone(new \DateTimeZone('America/Guatemala'))->format('d/m/Y H:i') : $row->fecTope;
            })
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editarOpor'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminarOpor'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
            })
            ->addColumn('inputEditarCliente', function () {
                $sesionEditarCliente = trim(session('editar'));
                $inputEditarCliente = $sesionEditarCliente;

                return $inputEditarCliente;
            })
            ->addColumn('inputLogin', function () {
                $sesionLogin = trim(session('usuario'));
                $inputLogin = $sesionLogin;

                return $inputLogin;
            })
            ->addColumn('inputRol', function () {
                $inputRol = $this->idRol();

                return $inputRol;
            })
            // Columna con el input para solicitar ficha técnica
            ->addColumn('inputSolicitarFt', function ($row) use ($rol, $id_usuario){
                if (in_array($rol, [3,9,10,11,1023]) && $row->Cancelada == 0 && $row->idEstatus != 10 && in_array($row->idEstatus, [9,11,12])) {
                    if ($row->hasFichaT == 1) {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled checked><label id="chkSolFt" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" ><label onclick="solicitarFichaTecnica('. $row->idOportunidad . ')" id="chkSolFt" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                } else if (in_array($rol, [8,1])  && $row->Cancelada == 0 && $row->idEstatus != 10) {
                    if ($row->hasFichaT == 1) {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled checked><label id="chkSolFt" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" ><label onclick="solicitarFichaTecnica('. $row->idOportunidad . ')" id="chkSolFt" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                }

                return '';
            })
            ->addColumn('tienePrepedido', function ($row) use ($rol, $id_usuario){
                $fecha = Carbon::parse($row->fecSolPreprensaConcluida)->format('d/m/Y H:i');
                if ($row->solicita_prepedido == 1) {
                    return $fecha;
                } else {
                    return '<span class="badge badge-danger"><i class="fa fa-times"></i></span>';
                }
            })
            ->addColumn('idUsuario', function () use ($id_usuario) {
                $idUsuario = $id_usuario;
                return $idUsuario;
            })
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputEditarCliente', 'inputLogin', 'inputRol', 'idUsuario', 'inputSolicitarFt', 'tienePrepedido'])
            ->make(true);
    }

    public function apiOportunidadesPanelVentas()
    {
        $Login = $this->loginUsuario();
        $permisos = $this->permisosModulo($this->modulo);
        session(['rol' => $permisos[0]->idRol]);
        $rol = session('rol');

        switch ($rol) {
            case "4":
                $Oportunidades_panel = DB::table('v_OportunidadesGrid')->where('LoginAgente', $Login)->where('idEstatus', 11)->where('Cancelada', 0)->where('archivoAutorizado', 1);
                break;
            case "9":
                $Oportunidades_panel = DB::table('v_OportunidadesGridAuxiliares')->where('LoginAuxiliar', 'LIKE', '%' . $Login . '%')->where('idEstatus', 11)->where('Cancelada', 0)->where('archivoAutorizado', 1);
                break;
            default:
                $Oportunidades_panel = DB::table('v_OportunidadesGrid')->where('idEstatus', 11)->where('Cancelada', 0)->where('archivoAutorizado', 1);
                break;
        }

        return DataTables::of($Oportunidades_panel)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editarOpor'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminarOpor'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
            })
            ->addColumn('inputEditarCliente', function () {
                $sesionEditarCliente = trim(session('editar'));
                $inputEditarCliente = $sesionEditarCliente;

                return $inputEditarCliente;
            })
            ->addColumn('inputLogin', function () {
                $sesionLogin = trim(session('usuario'));
                $inputLogin = $sesionLogin;

                return $inputLogin;
            })
            ->addColumn('inputRol', function () {
                $sesionRol = session('rol');
                $inputRol = $sesionRol;

                return $inputRol;
            })
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputEditarCliente', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function apiOportunidadesRetenidas($id)
    {
        if ($id == null) {
            $id = 1;
        }

        $Retenidas = DB::select('exec GetOportunidadRetenida @idOportunidad=' . $id);

        return DataTables::of($Retenidas)->make(true);
    }

    public function integrantesEquipos(Request $request)
    {

        if ($request->idEquipo == 1) {
            $agentes = DB::select('exec GetAgentesCombo');
        } else {
            $agentes = DB::select('exec GetEquiposIntegrantes @idEquipo=' . $request->idEquipo);
        }

        return $agentes;
    }

    public function apiOportDocs(Request $request)
    {
        $permisos = $this->permisosModulo($this->modulo);
        session(['rol' => $permisos[0]->idRol]);
        $idOportunidad = $request->idOportunidad;
        $oportunidad = Oportunidades::where('idOportunidad', '=', $idOportunidad)->first();
        $cancelada = $oportunidad->Cancelada;
        $Documentos = DB::select('exec sp_Documentos @evento="DocumentosOport", @id=' . $idOportunidad);
        $Documentos = collect($Documentos)->whereNotIn('idTipoDocumento', [5, 6, 7, 9, 12, 15, 16, 17, 18]);

        return DataTables::of($Documentos)
            ->addColumn('inputRol', function () {
                $sesionRol = session('rol');
                $inputRol = $sesionRol;

                return $inputRol;
            })
            ->addColumn('user', function () {
                $id = $this->idUsuario();
                return $id;
            })
            ->addColumn('oportunidad_cancelada', function () use($cancelada) {
                $oportunidad_cancelada = $cancelada;

                return $oportunidad_cancelada;
            })
            ->addColumn('oportEstatus', function () use($oportunidad) {
                return $oportunidad->idEstatus;
            })
            ->addColumn('usuario_revisa', function () use($cancelada, $oportunidad) {
                return $oportunidad->idRevisado;
            })
            ->rawColumns(['inputRol', 'oportunidad_cancelada', 'usuario_revisa'])
            ->make(true);
    }

    public function documentosValidados(Request $request)
    {
        try {
            $idOportunidad = $request->idOportunidad;
            $docs_validos = v_DocumentosOport::select('idOportunidad', 'Valido', 'idTipoDocumento')->where('idOportunidad', $idOportunidad)->where('Valido', 'Valido')->get();
            return response()->json($docs_validos->toArray());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los documentos validados'], 500);
        }
    }

    public function deleteDocOport(Request $request)
    {
        $idDocumentodelete = $request->idDocumentodelete;
        $idDocumentodelete = (int)$idDocumentodelete;
        $documento = relOport_Documento::where('idDocumento', $idDocumentodelete)->first();

        // validamos si el documento es estatus 4
        $doc = DB::table('DocumentosOport')->select(['tipoTripa'])->where('idDocumento', $idDocumentodelete)->first();

        // Eliminamos solo si exsite
        if ($doc != null) {
            // Validamos que sea rechazado o este rechazado
            if ($doc->tipoTripa == 4) {
                // Buscamos la relación con el motivo
                $existToDelete = DB::table('DocumentoOportMotivoRechazo')->where('idDocumento', $idDocumentodelete)->first();
                // Validamos si existe para eliminar
                if ($existToDelete != null) {
                    // Eliminamos la relación
                    DB::statement('exec sp_delete_rel_motivo_rechazo ?', [$idDocumentodelete]);
                }
            }
        }

        $idDocEstr = DB::table('DocumentosOport')->where('idDocumento', $idDocumentodelete)->value('idDocEst');

        DB::statement('exec sp_delete_docs_oport ?', [$idDocumentodelete]);
        // DocumentosOport::where('idDocumento', $idDocumentodelete)->delete();

        if ($idDocEstr != null) {
            relEstr_Documento::where('idDocumento', $idDocEstr)->delete();
            DocumentosEstructural::where('id_documentosEstructural', $idDocEstr)->delete();
        }

        $this->bitacora($this->modulo, 'Eliminar Documento ' . $documento->descDocumento . ' Tipo Documento ' . $documento->descTipoDocumento, ' Oportunidad ' . $documento->idOportunidad, $idDocumentodelete, $this->loginUsuario(), 3);
    }

    public function autorizarArchivo(Request $request)
    {
        $idDocumento = $request->id_documento;
        $oportunidad = Oportunidades::where('idOportunidad', $request->idOportunidadPdf)->first();
        $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 24)->first();

        // $id = $request->idOportunidadPdf;
        $checked = 0;
        if ($request->checked == 1) {
            $checked = 1;
        }
        $idDocumento = (int)$idDocumento;

        DB::table('bitacoraAutorizacionPresupuestos')->insert([
            'idDocumento' => $idDocumento,
            'idOportunidad' => $request->idOportunidadPdf,
            'idUsuario' => $this->idUsuario(),
            'movimiento' => ($checked == 1) ? 'Archivo Autorizado' : 'Archivo Rechazado',
        ]);

        DB::statement('exec sp_autorizarArchivo ?, ?, ?, ?', [
            $checked,
            $idDocumento,
            $this->idUsuario(),
            $request->fecAutoriza,
        ]);

        DB::table('motivo_rechazo_presupuestos')->insert([
            'documento_id' => $idDocumento,
            'motivo' => $request->motivo,
        ]);

        /* Acción para regresar a listo p/presupuesto a la oportunidad */

        if ($checked == 0) {
            if ($oportunidad != null) {
                $fecha = new \DateTime();
                $id_estatus = 7;
                $idUsuario = $this->idUsuario();

                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidad->idOportunidad)
                    ->update([
                        'idEstatus' => $id_estatus, 
                        'fecUltimoCambio' => $fecha->format('d-m-Y H:i:s'), 
                        'idUsuarioUltCambio' => $idUsuario,
                        'fecPresupTerminado' => null,
                        'fecResultadoF' => null,
                        'fecOrdenConcluida' => null,
                        'fecListoPresupuesto' => $fecha->format('d-m-Y H:i:s'),
                        // Este campo es para validar si al menos una vez se ha rechazado el presupuesto, para mostrar la tabla de solicitud de cambios
                        'fueRechazadoPres' => 1,
                        'Valor' => 0,
                        // 'flagCambioTexto' => 1,
                    ]);

                // $idCustomer = $this->customerLibre();
                // DB::statement('exec sp_actualizarIdAtendido ?,?', [$oportunidad->idOportunidad, $idCustomer]);

                // Guardamos en la bitacora de fechas
                $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 6);

                // Enviamos correo al cotizador
                // if ($alertasPlantilla != null) {
                //     $cotizadorCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idCustomer_)->first();

                //     if ($cotizadorCorreo != null) {
                //         $correoCotizador = $cotizadorCorreo->Correo;
                //         $nombre_oportunidad = $oportunidad->nomOportunidad;
                //         $folio = $oportunidad->idOportunidad;
                //         $fecha_oportunidad = $oportunidad->fecCreacion;
                //         $estatus_oportunidad = $alertasPlantilla->ValorCampo;
                //         $subject = $alertasPlantilla->Asunto;
                //         $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                //         $route = "oportunidades";
                //         $nota = $request->motivo;
                //         $fecha_solicitud = $oportunidad->fecSolCotizacion;
                //         $fecha_asignacion = $oportunidad->fecListoCotizar;
                //         $fecha_entrega = $oportunidad->fecCotizando;
                //         $fecha_aut_archivo = $oportunidad->fecRevision;
                //         $diasEntrega = null;
                //         $mensaje = $alertasPlantilla->Mensaje;
                //         $numOrdenMTC = $oportunidad->ops;
                //         $rejectDocument = 1;

                //         // Enviamos el correo al cotizador
                //         Mail::to($correoCotizador)->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));

                //         // Pruebas
                //         //Mail::to('cgonzalez@litoprocess.com')->cc(['maflores@litoprocess.com'])->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
                //     }
                // }
            }
        } else {
            DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidad->idOportunidad)
                    ->update([
                        'flagCambioTexto' => 0,
                    ]);
            
            $idEps = DB::table('presupuestoPdfEP')->where('idDocumento', $idDocumento)->get()->pluck('idEP')->toArray();

            foreach ($idEps as $idEp) {
                SolicitudCotizacion::where('id', $idEp)
                    ->update([
                        'tiene_cambio_texto' => 0,
                        'resolvio_cambio_texto' => 0,
                    ]);
            }
        }

        /* Fin de la acción */

        if ($checked == 1) {
            $valor = ' Autorizado por ' . $this->loginUsuario();
        } else {
            $valor = ' No Autorizado por ' . $this->loginUsuario();
        }

        $this->bitacora($this->modulo, 'Documento ' . $idDocumento . ' / Documento ' . $valor, ' / Id ', $idDocumento, $this->loginUsuario(), 2);

        return response()->json([
            'response' => true,
            'message' => 'Documento actualizado correctamente',
        ]);
    }

    /**
     * Función para realizar el cambio de texto
     * 
     * @param int $idOportunidad
     * @return mixed
     */
    public function cambioTexto($idOportunidad)
    {
        try {
            $oportunidad = Oportunidades::where('idOportunidad', $idOportunidad)->first();

            if ($oportunidad != null) {
                $fecha = new \DateTime();
                $id_estatus = 7;
                $idUsuario = $this->idUsuario();

                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidad->idOportunidad)
                    ->update([
                        'idEstatus' => $id_estatus, 
                        'fecUltimoCambio' => $fecha->format('d-m-Y H:i:s'), 
                        'idUsuarioUltCambio' => $idUsuario,
                        'fecPresupTerminado' => null,
                        'fecResultadoF' => null,
                        'fecOrdenConcluida' => null,
                        'fecListoPresupuesto' => $fecha->format('d-m-Y H:i:s'),
                        'flagCambioTexto' => 1,
                    ]);

                // Guardamos en la bitacora de fechas
                $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 6);
            }
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al realizar el cambio de texto',
            ]);
        }
    }

    /**
     * Función para finalizar cambio de textos
     * 
     * @param int $idOportunidad
     * @return mixed
     */
    public function finalizarCambioTexto($idOportunidad)
    {
        try {
            $oportunidad = Oportunidades::where('idOportunidad', $idOportunidad)->first();

            if ($oportunidad != null) {
                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidad->idOportunidad)
                    ->update([
                        'flagCambioTexto' => 0,
                    ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al finalizar el cambio de texto',
            ]);
        }
    }

    public function rechazarDocumento(Request $request)
    {
        try {
            $idDocumento = (int)$request->id_documento;

            $idUsuario = $this->idUsuario();

            if ($request->idOportunidad == null && $request->motivoRejectId == null) {
                return response()->json([
                    'response' => false
                ]);
            }

            DB::statement('exec sp_asociarMotivoRechazoTripa ?,?,?,?,?', [
                $request->idOportunidad,
                $idDocumento,
                $request->motivoRejectId,
                $idUsuario,
                ($request->notes != '' ? $request->notes : null),
            ]);

            $this->bitacora($this->modulo, 'Documento ' . $idDocumento . ' / Documento Rechazado ', ' / Id ', $idDocumento, $this->loginUsuario(), 2);

            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false
            ]);
        }
    }

    /**
     * Función para obtener el tipo de motivo seleccionado
     * 
     * @param Request $request
     * @return mixed
     */
    public function getMotivoRechazoDoc(Request $request)
    {
        try {
            // Obtenemos el motivo de rechazo de acuerdo al id del documento
            $rejectedRegister = DB::table('v_DocumentosRechazadosMotivo')->select(['descripcion', 'notas', 'idMotivoRechazo'])->where('idDocumento', $request->id_documento)->first();

            return response()->json([
                'response' => true,
                'message' => ($rejectedRegister != null ? (($rejectedRegister->idMotivoRechazo != 7) ? $rejectedRegister->descripcion : $rejectedRegister->notas) : 'Sin motivo de rechazó'),
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema";
        }
    }

    public function updocoport(Request $request)
    {
        $idUsuarioReg = (int)$this->idUsuario();
        $idOportunidad = $request->idOportunidad;
        $idOportunidad = (int)$idOportunidad;
        $idTipoDocumento = $request->idTipoDocumento;

        // $ticketsRelacionados = DB::table('relOport_Estructural')->where('idOportunidad', $idOportunidad)->get()->pluck('folio')->toArray();
        // $tickets = DB::table('Estructural')->whereIn('folio', $ticketsRelacionados)->select(['folio', 'master', 'id_cliente'])->get();

        $idTipoDocumento = (int)$idTipoDocumento;
        $file = $_FILES['fileOport'];
        $Extencion = $file['type'];
        $Size = $file['size'];
        $descDocumento =  $file['name'];
        $Documento = base64_encode(file_get_contents($_FILES["fileOport"]["tmp_name"]));

        DB::statement(" EXEC sp_updateOportDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento", [
            ':descDocumento' => $descDocumento,
            ':Documento' => $Documento,
            ':Extencion' => $Extencion,
            ':Size' => $Size,
            ':idUsuarioReg' => $idUsuarioReg,
            ':idOportunidad' => $idOportunidad,
            ':idTipoDocumento' => $idTipoDocumento
        ]);

        // foreach ($tickets as $key => $value) {
        //     DB::statement(" EXEC sp_updateEstructuralDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:folio, :idCliente,:idTipoDocumento", [
        //         ':descDocumento' => $descDocumento,
        //         ':Documento' => $Documento,
        //         ':Extencion' => $Extencion,
        //         ':Size' => $Size,
        //         ':idUsuarioReg' => $idUsuarioReg,
        //         ':folio' => $value->folio,
        //         ':idCliente' => $value->id_cliente,
        //         ':idTipoDocumento' => 9
        //     ]);
        // }

        $tipo_documento = catTipoDocumentoOport::where('idTipoDocumento', $idTipoDocumento)->first();

        if ($idTipoDocumento != 0) {
            $this->bitacora($this->modulo, 'Agregar Documento ' . (string)$descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Oportunidad ', $request->idOportunidad, $this->loginUsuario(), 5);
        }
    }

    public function downdocsOport($id)
    {
        $result = DocumentosOport::where('idDocumento', (int) $id)->first();
        $file = $result->Documento;
        $Tipo = $result->Extension;
        $base64 = base64_decode($file);
        return Response::make($base64, 200, ['Content-Type' => $Tipo, 'Content-Disposition' => 'attachment; filename="' . $result->descDocumento . '"',]);
    }

    public function downdocsOportunidades($id)
    {
        $result = DocumentosOport::where('idDocumento', (int) $id)->first();
        $file = $result->Documento;
        $Tipo = $result->Extension;
        $base64 = base64_decode($file);
        return Response::make($base64, 200, ['Content-Type' => $Tipo, 'Content-Disposition' => 'attachment; filename="' . $result->descDocumento . '"',]);
    }

    public function upFirma(Request $request)
    {
        $idUsuario = $request->id_usuario;
        $Firma = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));
        $firmas = DB::table('FirmasUsuario')->where('idUsuario', $idUsuario)->get();

        if (count($firmas) > 0) {
            DB::statement(" EXEC sp_updateFirma ?,?", [
                $idUsuario,
                $Firma,
            ]);
        } else {
            DB::statement(" EXEC sp_insertFirma ?,?", [
                $idUsuario,
                $Firma,
            ]);
        }
    }

    public function cancelarOportunidad(Request $request)
    {
        $fecha = new \DateTime();

        $idUsuario = $this->idUsuario();

        DB::statement(
            'exec spCancelarOportunidad ?, ?, ?, ?, ?',
            [
                $request->id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                true,
                $request->motivoCancelacion,
                $request->id
            ]
        );

        // Realizamos la liberación del ticket
        DB::statement('exec sp_updateOportunidadLiberada ?,?,?,?', [
            $request->id,
            0,
            $request->motivoCancelacion,
            $idUsuario,
        ]);

        $this->bitacora($this->modulo, 'Cancelar ', 'Oportunidad ', $request->id, $this->loginUsuario(), 6);

        return redirect()->route('Oportunidades');
    }

    public function GetCotizadores()
    {
        $cotizadores = DB::select('exec GetCotizadoresCombo');
        return $cotizadores;
    }

    public function getRevisa()
    {
        $revisa = DB::table('RevisaOportunidad')->where('estatus', 1)->get();
        return $revisa;
    }

    public function cambiarCustomer(Request $request)
    {

        $cotizadores = DB::select('exec GetCotizadoresCombo');
        $cotizador = $cotizadores[$request->customer]->Id_Usuario;
        $fecha = new \DateTime();

        DB::statement(
            'exec spCambiarCustomer ?, ?, ?, ?',
            [
                $request->id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $cotizador,
                $request->id
            ]
        );

        $this->bitacora($this->modulo, 'Cambiar Atendido por ', 'Oportunidad ', $request->id, $this->loginUsuario(), 2);
    }

    public function cambiarCotizador(Request $request)
    {

        $cotizadores = DB::select('exec GetCotizadoresCombo');
        $cotizador = $cotizadores[$request->cotizador]->Id_Usuario;
        $fecha = new \DateTime();

        DB::statement(
            'exec spCambiarCotizador ?, ?, ?, ?',
            [
                $request->id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $cotizador,
                $request->id
            ]
        );

        $this->bitacora($this->modulo, 'Cambiar Cotizado por ', 'Oportunidad ', $request->id, $this->loginUsuario(), 2);
    }

    public function cambiarRevisado(Request $request)
    {

        $cotizadores = DB::select('exec GetRevisaCombo');
        $cotizador = $cotizadores[$request->revisado]->idRevisa;
        $fecha = new \DateTime();

        DB::statement(
            'exec spCambiarRevisado ?, ?, ?, ?',
            [
                $request->id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $cotizador,
                $request->id
            ]
        );

        $this->bitacora($this->modulo, 'Cambiar Revisado por ', 'Oportunidad ', $request->id, $this->loginUsuario(), 2);
    }

    public function getDocumentosXidOportunidad(Request $request)
    {
        $documentos_cotizacion = DB::select('exec GetDocumentosXIdOportunidad @idOportunidad=' . $request->idOportunidad);
        return $documentos_cotizacion;
    }

    public function apiSucursalesEntrega(Request $request)
    {
        $idOportunidad = $request->idOportunidad;
        $sucursales_entrega = DB::select('exec GetSucursalesEntregaXOportunidad @idOportunidad=' . $idOportunidad);
        return DataTables::of($sucursales_entrega)
            ->make(true);
    }

    public function guardarCantidad(Request $request)
    {
        DB::statement(
            'exec spAgregarCantidadEntregaOport ?, ?, ?',
            [
                $request->cantidad,
                $request->seleccionado,
                $request->idSucursalEnt
            ]
        );

        $this->bitacora($this->modulo, 'Agregar Cantidad Entrega ', 'Id Sucursal ', $request->idSucursalEnt, $this->loginUsuario(), 2);
    }

    public function agregarCantidad(Request $request)
    {
        $total_cantidad = DB::select('exec GetCantidadTotalEntrega @idOportunidad=' . $request->idOportunidad);

        if (count($total_cantidad) > 0) {

            if (($total_cantidad[0]->CantidadTotal + $request->cantidad) > $request->totalCantidadOP) {
                return false;
            } else {
                DB::statement(
                    'exec spInsertSucursalesOportunidad ?, ?, ?, ?, ?, ?',
                    [
                        $request->idSucursalEnt,
                        $request->idCliente,
                        $request->idOportunidad,
                        true,
                        $request->cantidad,
                        $request->fecEntregaInput,
                    ]
                );

                $this->bitacora($this->modulo, 'Agregar Cantidad Entrega ', 'Id Sucursal ', $request->idSucursalEnt, $this->loginUsuario(), 5);
            }
        } else if ($total_cantidad == null) {

            if ((0 + $request->cantidad) > $request->totalCantidadOP) {
                return false;
            } else {
                DB::statement(
                    'exec spInsertSucursalesOportunidad ?, ?, ?, ?, ?, ?',
                    [
                        $request->idSucursalEnt,
                        $request->idCliente,
                        $request->idOportunidad,
                        true,
                        $request->cantidad,
                        $request->fecEntregaInput,
                    ]
                );

                $this->bitacora($this->modulo, 'Agregar Cantidad Entrega ', 'Id Sucursal ', $request->idSucursalEnt, $this->loginUsuario(), 5);
            }
        }
    }

    public function eliminarCantidad(Request $request)
    {
        $id = $request->id;
        EntregaSucursalesOportunidades::where('idSucEntrega', $id)->delete();

        $this->bitacora($this->modulo, 'Eliminar Cantidad Entrega ', 'Id Sucursal ', $id, $this->loginUsuario(), 3);
    }

    public function getEPXOport(Request $request)
    {
        $ep_oportunidad = DB::select('exec GetEPXOport @idOportunidad=' . $request->idOportunidad);
        return $ep_oportunidad;
    }

    public function addOrEditTiempoEntrega(Request $request)
    {
        // return $request;
        $this->configMail();

        $fecha = new \DateTime();
        $fechaAutoriza = $request->FechaAutoriza;
        $diasEntrega = null;
        $fecha_entrega = "";
        $fechaPrimeraEntrega = "";
        $fechaUltimaEntrega = "";

        if ($fechaAutoriza == null) {
            $fechaAutoriza = $request->FechaArchivoAutorizado;
        }

        if ($request->FechaEntrega != null) {
            $fecha_entrega = Carbon::parse($request->FechaEntrega)->format('d/m/Y');
        }

        if ($request->FechaPrimeraEntrega != null) {
            $fechaPrimeraEntrega = Carbon::parse($request->FechaPrimeraEntrega)->format('d/m/Y');
            $fechaUltimaEntrega = Carbon::parse($request->FechaUltimaEntrega)->format('d/m/Y');
            $fecha_entrega_mail = 'Fecha Primera Entrega: ' . $fechaPrimeraEntrega . ' - Fecha Última Entrega: ' . $fechaUltimaEntrega;
        }

        if ($request->DiasEntrega != null) {
            $diasEntrega = $request->DiasEntrega;
        }

        $fecha_solicitud = "";
        $fecha_asignacion = $fecha->format('d-m-Y H:i:s');
        $fecha_aut_archivo = $fechaAutoriza;
        $idOportunidad = $request->idOportunidadTEntrega;
        $oportunidad = v_Oportunidades::where('idOportunidad', $idOportunidad)->first();
        $folio = $oportunidad->idOportunidad;

        DB::statement('exec spActualizarEstatusTiempoEntrega ?, ?', [
            $oportunidad->idOportunidad,
            0
        ]);

        DB::statement('exec spAgregarTiempoEntrega ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
            $request->idOportunidadTEntrega,
            $request->Autorizo,
            $request->DiasEntrega,
            $fechaAutoriza,
            $request->FechaEntrega,
            $request->Comentarios,
            $request->idUsuarioTEntrega,
            $fecha->format('d-m-Y H:i:s'),
            1,
            $fechaPrimeraEntrega,
            $fechaUltimaEntrega,

        ]);

        $tipo = 5;
        $Accion = "Agregar";
        $alertasPlantillas = catPlantillasAlertas::get();
        $nombre_oportunidad = $oportunidad->nomOportunidad;
        $fecha_oportunidad = Carbon::parse($oportunidad->fecCreacion)->format('d/m/Y');
        $estatus_oportunidad = $oportunidad->descEstatus;
        $subject = $alertasPlantillas[7]->Asunto;
        $nombre_agente = $oportunidad->Agente;
        $nota = $request->Comentarios;
        $cuenta_cc = 'mcharabati@litoprocess.com';
        $usuarios_rol = IntegrantesRoles::where('idRol', '1013')->get();
        $usuarios = count($usuarios_rol);
        $mensaje = $alertasPlantillas[7]->Mensaje;
        $cuenta_usuario = $oportunidad->CorreoAgente;
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;
        $cuenta_auxiliar2 = $oportunidad->CorreoAuxiliar2;
        $cuenta_auxiliar3 = $oportunidad->CorreoAuxiliar3;
        $cuenta_auxiliar4 = $oportunidad->CorreoAuxiliar4;
        $cuenta_customer = $oportunidad->CorreoCustomer;
        $cuentas_cco = $alertasPlantillas[7]->CCO;
        $cuenta_cco = explode(", ", $cuentas_cco);
        $route = "oportunidades";
        $numOrdenMTC = $oportunidad->ops;
        $grupoEntregas = 'fentregacandy@litoprocess.com';

        if ($cuenta_auxiliar1 == null) {
            Mail::to($cuenta_usuario)->cc([$cuenta_cc, $grupoEntregas, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        } else if ($cuenta_auxiliar2 == null) {
            Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $grupoEntregas, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        } else if ($cuenta_auxiliar3 == null) {
            Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $grupoEntregas, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        } else if ($cuenta_auxiliar4 == null) {
            Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $grupoEntregas, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        } else {
            Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $grupoEntregas, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        }

        // switch ($usuarios) {
        //     case 1:

        //         $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;

        //         if ($cuenta_auxiliar1 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar2 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar3 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar4 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         }

        //         break;

        //     case 2:

        //         $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
        //         $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;

        //         if ($cuenta_auxiliar1 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar2 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar3 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar4 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         }
        //         break;

        //     case 3:

        //         $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
        //         $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
        //         $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;

        //         if ($cuenta_auxiliar1 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar2 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar3 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar4 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         }
        //         break;

        //     case 4:

        //         $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
        //         $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
        //         $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;
        //         $cuenta_fecha_entrega_4 = $usuarios_rol[3]->Correo;

        //         if ($cuenta_auxiliar1 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar2 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar3 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar4 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         }
        //         break;

        //     case 5:

        //         $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
        //         $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
        //         $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;
        //         $cuenta_fecha_entrega_4 = $usuarios_rol[3]->Correo;
        //         $cuenta_fecha_entrega_5 = $usuarios_rol[4]->Correo;

        //         if ($cuenta_auxiliar1 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar2 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar3 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else if ($cuenta_auxiliar4 == null) {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         } else {
        //             Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5, $cuenta_customer])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega_mail, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
        //         }
        //         break;

        //     default:
        //         break;
        // }

        $this->bitacora($this->modulo, $Accion . ' Tiempo Entrega ', 'Id Oportunidad ', $request->idOportunidadTEntrega, $this->loginUsuario(), $tipo);
    }

    public function getTiempoEntrega($id)
    {
        $TiempoEntrega = TiempoEntregaOportunidades::where('idTiempoEntregaOprotunidad', $id)->first();
        return $TiempoEntrega;
    }

    public function apiTiempoEntrega($id)
    {
        $TiempoEntrega = DB::select('exec GetTiemposEntregas @idOportunidad=' . $id);
        return DataTables::of($TiempoEntrega)
            ->make(true);
    }

    public function solicitarFechaEntrega(Request $request)
    {
        $this->configMail();

        $id = $request->id;
        $nota = $request->notaSolicitud;
        $fecha = new \DateTime();
        $existe_solicitud = v_Oportunidades::where('idOportunidad', $id)->first();

        DB::statement('exec spUpdateSolicitarFechaEntrega ?, ?, ?, ?', [
            $id,
            1,
            $nota,
            $fecha->format('d-m-Y H:i:s'),
        ]);

        $oportunidad = v_Oportunidades::where('idOportunidad', $id)->first();
        $folio = $oportunidad->idOportunidad;
        $fecha_solicitud = $oportunidad->fechaSolicitudFechaEntrega;
        $fecha_entrega = "";
        $fecha_asignacion = "";
        $fecha_aut_archivo = "";
        $diasEntrega = null;
        $alertasPlantillas = catPlantillasAlertas::get();
        $nombre_oportunidad = $oportunidad->nomOportunidad;
        $fecha_oportunidad = Carbon::parse($oportunidad->fecCreacion)->format('d/m/Y');
        $estatus_oportunidad = $oportunidad->descEstatus;
        $subject = $alertasPlantillas[6]->Asunto;
        $mensaje = $alertasPlantillas[6]->Mensaje;

        if ($existe_solicitud->fechaSolicitudFechaEntrega != null) {
            $subject = 'Solicitud Nueva Fecha Entrega';
            $mensaje = 'Se solicitó nueva fecha de entrega para la Oportunidad';
        }

        $folio_oportunidad = $folio;
        $nombre_agente = $oportunidad->Agente;
        $usuarios_rol = IntegrantesRoles::where('idRol', '1013')->get();
        $usuarios = count($usuarios_rol);
        $cuenta_agente = $oportunidad->CorreoAgente;
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;

        if ($cuenta_auxiliar1 != null) {
            $cuentas = $alertasPlantillas[6]->CopiarA . ', ' . $cuenta_agente . ', ' . $cuenta_auxiliar1;
        } else {
            $cuentas = $alertasPlantillas[6]->CopiarA . ', ' . $cuenta_agente;
        }

        $cuenta_cc = explode(", ", $cuentas);
        $cuentas_cco = $alertasPlantillas[6]->CCO;
        $cuenta_cco = explode(", ", $cuentas_cco);
        $route = "oportunidades";
        $numOrdenMTC = null;

        switch ($usuarios) {
            case 1:

                $cuenta_to = [$usuarios_rol[0]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[6], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 2:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[6], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 3:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo, $usuarios_rol[2]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[6], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 4:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo, $usuarios_rol[2]->Correo, $usuarios_rol[3]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[6], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 5:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo, $usuarios_rol[2]->Correo, $usuarios_rol[3]->Correo, $usuarios_rol[4]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[6], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            default:
                break;
        }

        $this->bitacora($this->modulo, 'Solicitar Fecha Entrega ', 'Oportunidad Id ', $id, $this->loginUsuario(), 2);
    }

    public function getDocumentoOport(Request $request)
    {
        $idDocumento = $request->idDocumento;
        $result = v_DocumentosOportunidades::where('idDocumento', (int) $idDocumento)->first();

        return $result;
    }

    public function obtenerCC(Request $request)
    {
        $idOportunidad = $request->idOportunidad;
        $cuentapara = $request->cuentapara;
        $result = DB::table('v_DocumentosOportMailCC')->where('idOportunidad', (int)$idOportunidad)->where('CorreoCC', '!=', $cuentapara)->get();

        return $result;
    }

    public function getDocumentoOportMail(Request $request)
    {
        $idDocumento = $request->idDocumento;
        $result = DocumentosOportMail::where('idDocumento', (int) $idDocumento)->first();

        return $result;
    }

    public function GetMaxDiasEntrega(Request $request)
    {
        $idOportunidad = $request->idOport;
        $result = v_Oportunidades::where('idOportunidad', (int) $idOportunidad)->first();

        return $result;
    }

    public function updateFechaEntregaDias(Request $request)
    {
        $idOportunidad = $request->idOport;
        $diasEntrega = $request->DiasEntrega;
        $fechaEntrega = $request->fechaEntrega;
        $fechaArchivo = Carbon::parse($request->fechaArchivo)->format('d/m/Y');

        DB::statement('exec sp_updateFechaEntregaDias ?, ?, ?, ?', [
            $idOportunidad,
            $diasEntrega,
            $fechaEntrega,
            $fechaArchivo,
        ]);
    }

    public function autorizarArchivoOportunidad(Request $request)
    {
        $this->configMail();

        $fecha = new \DateTime();
        $idOportunidad = $request->idOport;

        DB::statement('exec spUpdateArchivoAutorizado ?, ?, ?', [
            $idOportunidad,
            1,
            $fecha->format('d-m-Y H:i:s')
        ]);

        $oportunidad = v_Oportunidades::where('idOportunidad', $idOportunidad)->first();
        $folio = $oportunidad->idOportunidad;
        $fecha_entrega = "";

        if ($oportunidad->FechaEntrega != null) {
            $fecha_entrega = $oportunidad->FechaEntrega;
        }

        $fecha_aut_archivo = $oportunidad->fecArchivoAutorizado;
        $numOrdenMTC = $oportunidad->ops;
        $fecha_solicitud = "";
        $fecha_asignacion = "";
        $diasEntrega = null;

        if ($oportunidad->DiasEntrega != null) {
            $diasEntrega = $oportunidad->DiasEntrega;
        }

        $alertasPlantillas = catPlantillasAlertas::get();
        $nombre_oportunidad = $oportunidad->nomOportunidad;
        $fecha_oportunidad = Carbon::parse($oportunidad->fecCreacion)->format('d/m/Y');
        $estatus_oportunidad = $oportunidad->descEstatus;
        $subject = $alertasPlantillas[10]->Asunto;
        $folio_oportunidad = $folio;
        $nombre_agente = $oportunidad->Agente;
        $usuarios_rol = IntegrantesRoles::where('idRol', '1013')->orWhere('idUsuario', 5331)->get();
        $usuarios = count($usuarios_rol);
        $cuenta_agente = $oportunidad->CorreoAgente;
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;
        $mensaje = $alertasPlantillas[10]->Mensaje;

        if ($cuenta_auxiliar1 != null) {
            $cuentas = $alertasPlantillas[10]->CopiarA . ', ' . $cuenta_agente . ', ' . $cuenta_auxiliar1;
        } else {
            $cuentas = $alertasPlantillas[10]->CopiarA . ', ' . $cuenta_agente;
        }

        $cuenta_cc = explode(", ", $cuentas);
        $cuentas_cco = $alertasPlantillas[10]->CCO;
        $cuenta_cco = explode(", ", $cuentas_cco);
        $route = "oportunidades";
        $nota = "";

        switch ($usuarios) {
            case 1:

                $cuenta_to = [$usuarios_rol[0]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[10], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 2:
                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[10], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 3:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo, $usuarios_rol[2]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[10], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 4:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo, $usuarios_rol[2]->Correo, $usuarios_rol[3]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[10], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            case 5:

                $cuenta_to = [$usuarios_rol[0]->Correo, $usuarios_rol[1]->Correo, $usuarios_rol[2]->Correo, $usuarios_rol[3]->Correo, $usuarios_rol[4]->Correo];

                Mail::to($cuenta_to)->cc($cuenta_cc)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[10], $nombre_oportunidad, $folio_oportunidad, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                break;

            default:
                break;
        }

        $this->bitacora($this->modulo, 'Autorizar Archivo ', 'Oportunidad Id ', $idOportunidad, $this->loginUsuario(), 2);
    }

    public function desautorizarArchivoOportunidad(Request $request)
    {
        $this->configMail();

        $fecha = new \DateTime();
        $idOportunidad = $request->idOport;

        DB::statement('exec spUpdateArchivoAutorizado ?, ?, ?', [
            $idOportunidad,
            0,
            null
        ]);

        $oportunidad = v_Oportunidades::where('idOportunidad', $idOportunidad)->first();
        $folio = $oportunidad->idOportunidad;
        $diasEntrega = $oportunidad->DiasEntrega;

        if ($diasEntrega != null) {
            DB::statement('exec spActualizarTiempoEntrega ?, ?, ?, ?, ?', [
                $oportunidad->idTiempoEntregaOprotunidad,
                null,
                null,
                $this->idUsuario(),
                $fecha->format('d-m-Y H:i:s'),
            ]);
        } else {
            DB::statement('exec spActualizarTiempoEntrega ?, ?, ?, ?, ?', [
                $oportunidad->idTiempoEntregaOprotunidad,
                $oportunidad->FechaAutoriza,
                $oportunidad->FechaEntrega,
                $this->idUsuario(),
                $fecha->format('d-m-Y H:i:s'),
            ]);
        }

        $fechaAutoriza = $oportunidad->FechaAutoriza;
        $fecha_entrega = $oportunidad->FechaEntrega;
        $fecha_solicitud = "";
        $fecha_asignacion = "";
        $fecha_aut_archivo = $fechaAutoriza;
        $alertasPlantillas = catPlantillasAlertas::get();
        $nombre_oportunidad = $oportunidad->nomOportunidad;
        $fecha_oportunidad = Carbon::parse($oportunidad->fecCreacion)->format('d/m/Y');
        $estatus_oportunidad = $oportunidad->descEstatus;
        $subject = $alertasPlantillas[11]->Asunto;
        $mensaje = $alertasPlantillas[11]->Mensaje;
        $nombre_agente = $oportunidad->Agente;
        $nota = "";
        $cuenta_cc = 'mcharabati@litoprocess.com';
        $usuarios_rol = IntegrantesRoles::where('idRol', '1013')->orWhere('idUsuario', 5331)->get();
        $usuarios = count($usuarios_rol);
        $cuenta_usuario = $oportunidad->CorreoAgente;
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;
        $cuenta_auxiliar2 = $oportunidad->CorreoAuxiliar2;
        $cuenta_auxiliar3 = $oportunidad->CorreoAuxiliar3;
        $cuenta_auxiliar4 = $oportunidad->CorreoAuxiliar4;
        $cuentas_cco = $alertasPlantillas[11]->CCO;
        $cuenta_cco = explode(", ", $cuentas_cco);
        $route = "oportunidades";
        $numOrdenMTC = $oportunidad->ops;

        switch ($usuarios) {
            case 1:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }

                break;

            case 2:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            case 3:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
                $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            case 4:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
                $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;
                $cuenta_fecha_entrega_4 = $usuarios_rol[3]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            case 5:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
                $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;
                $cuenta_fecha_entrega_4 = $usuarios_rol[3]->Correo;
                $cuenta_fecha_entrega_5 = $usuarios_rol[4]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[11], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            default:
                break;
        }

        $this->bitacora($this->modulo, 'Desautorizar Archivo ', 'Oportunidad Id ', $idOportunidad, $this->loginUsuario(), 2);
    }

    public function getOportunidad(Request $request)
    {
        $idOportunidad = $request->idOportunidad;
        $result = v_Oportunidades::where('idOportunidad', (int) $idOportunidad)->first();

        return $result;
    }

    public function getHollidays(Request $request)
    {
        $result = DB::table('v_catHollidays')->get();

        return $result;
    }

    public function obtenerOPs(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $autocomplate = v_ordenesMetricsAll::orderby('NumOrdem', 'asc')->select('NumOrdem')->get();
        } else {
            $autocomplate = v_ordenesMetricsAll::orderby('NumOrdem', 'asc')->select('NumOrdem')->where('NumOrdem', 'like', '%' . $search . '%')->get();
        }

        $response = array();

        foreach ($autocomplate as $autocomplate) {
            $response[] = array("value" => $autocomplate->NumOrdem, "label" => $autocomplate->NumOrdem);
        }

        echo json_encode($response);
        exit;
    }

    public function validarFolioMetrics($folio)
    {
        $search = $folio;
        $autocomplate = v_ordenesMetricsAll::orderby('NumOrdem', 'asc')->select('NumOrdem')->where('NumOrdem', $search)->first();
        return response()->json([
            'existe' => $autocomplate != null ? true : false,
            'folio' => $autocomplate->NumOrdem ?? null
        ]);
    }

    public function obtenerSolicitudesOP()
    {
        $idUsuario = $this->idUsuario();
        $solicitudes_op = DB::table('v_OportunidadesSolOP5Dias')->where('idCustomer', $idUsuario)->orderBy('folio', 'desc')->get();

        return $solicitudes_op;
    }

    public function obtenerOMTCs(Request $request)
    {
        $search = $request->search;

        if ($search != '') {
            $autocomplate = v_ordenesMetricsAll::orderby('NumOrdem', 'asc')->select('NumOrdem')->where('NumOrdem', $search)->first();
        } else {
            $autocomplate = null;
        }

        return $autocomplate;
    }

    /**
     * Función para enviar documentos por correo electrónico
     * 
     * @param Request $request
     * @return void
     */
    public function enviarDocumentoMail(Request $request)
    {
        try {
        // Obtenemos el Id de la oportunidad por medio del request
        $idOportunidad = $request->id_oport;

        $archivosAdicionales = $request->documentosAdicionales;

        $attachments = [];
        if (is_array($archivosAdicionales)) {
            foreach ($archivosAdicionales as $archivo) {
                // Guarda el archivo en storage/app/tmp_documentos/
                $path = $archivo->store('tmp_documentos');
                $fullPath = storage_path('app/' . $path);
                $originalName = $archivo->getClientOriginalName();
                $attachments[] = [
                    'path' => $fullPath,
                    'name' => $originalName
                ];
            }
        }

        // Buscamos y obtenemos dentro de la tabla (vista) de oportunidades la oportunidad por medio del Id que ya hemos obtenido
        $oportunidad = v_Oportunidades::where('idOportunidad', $idOportunidad)->first();

        // Obtenemos los correo auxiliares de la oportunidad
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;
        $cuenta_auxiliar2 = $oportunidad->CorreoAuxiliar2;
        $cuenta_auxiliar3 = $oportunidad->CorreoAuxiliar3;
        $cuenta_auxiliar4 = $oportunidad->CorreoAuxiliar4;

        // Obtenemos el correo del agente
        $cuenta_agente = $request->cuentaAgente;

        // Obtenemos el correo del destino
        $cuenta_destino = $request->cuentapara;

        // Obtenemos las cuentas para copias a partir del requets recibido
        $cuentacc = $request->cuentacc;

        // Inicializamos la cadena que contendra las cuentas auxiliares, asociadas dentro de la oportunidad
        $cuentasAuxiliar = null;
        
        // Validamos primero que los cuentas auxiliares cuenten con información y no solo sean nulas
        if ($cuenta_auxiliar1 != null && $cuenta_auxiliar2 != null && $cuenta_auxiliar3 != null && $cuenta_auxiliar4 != null) {
            $cuentasAuxiliar = $cuenta_auxiliar1 . ', ' . $cuenta_auxiliar2 . ', ' . $cuenta_auxiliar3 . ', ' . $cuenta_auxiliar4;
        }
        // Validamos que solo la cuenta Aux4 este en nulo para que la omitamos dentro de la concatenación de la cadena principal 
        else if ($cuenta_auxiliar1 != null && $cuenta_auxiliar2 != null && $cuenta_auxiliar3 != null && $cuenta_auxiliar4 == null) {
            $cuentasAuxiliar = $cuenta_auxiliar1 . ', ' . $cuenta_auxiliar2 . ', ' . $cuenta_auxiliar3;
        }
        // Validamos que solo la cuenta Aux4 y Aux3 esten como nulas para que las omitamos dentro de la concatenación de la cadena principal 
        else if ($cuenta_auxiliar1 != null && $cuenta_auxiliar2 != null && $cuenta_auxiliar3 == null && $cuenta_auxiliar4 == null) {
            $cuentasAuxiliar = $cuenta_auxiliar1 . ', ' . $cuenta_auxiliar2;
        }
        // Validamos que solo la cuenta Aux4, Aux3, Aux2 esten como nulas para que las omitamos dentro de la concatenación de la cadena principal 
        else if ($cuenta_auxiliar1 != null && $cuenta_auxiliar2 == null && $cuenta_auxiliar3 == null && $cuenta_auxiliar4 == null) {
            $cuentasAuxiliar = $cuenta_auxiliar1;
        } 
        // En el caso contrario solo mantenemos la cadena vacia y la consideramos como null
        else {
            $cuentasAuxiliar = null;
        }

        // Validamos que las cuentas con copias sean nulas y las cuentas auxiliares contengan la cadena
        if ($cuentacc == null && $cuentasAuxiliar != null) {
            // Si es el caso concatenamos las cuentas auxiliares con la cuenta del agente
            $cuenta_cc = $cuentasAuxiliar . ', ' . $cuenta_agente;
        }
        // Validamos que las cuentas con copia contengan información al igual que las cuentas auxliares
        else if ($cuentacc != null && $cuentasAuxiliar != null) {
            // Si es el caso concatenamos las cuentas con copia, las cuentas auxiliares y la cuenta agente
            $cuenta_cc = $cuentacc . ', ' . $cuentasAuxiliar . ', ' . $cuenta_agente;
        }
        // Validamos que las cuentas con copia contengan información y las auxiliares no
        else if ($cuentacc != null && $cuentasAuxiliar == null) {
            // En el caso de que si solo concatenamos las cuentas con copia y la cuenta del agente
            $cuenta_cc = $cuentacc . ', ' . $cuenta_agente;
        } 
        // Validamos que las cuentas con copia y las cuentas auxiliares sean nulas
        else if ($cuentacc == null && $cuentasAuxiliar == null) {
            // En el caso de que si solo agregamos la cuenta del agente
            $cuenta_cc = $cuenta_agente;
        }
        
        // Obtenemos el asunto y lo colocamos en mayusculas
        $subject = strtoupper($request->subjetDoc);

        // Obtenemos el mensaje
        $mensaje = $request->cuerpoDoc;

        // Obtenemos el archivo adjunto en base 64
        $attachement = $request->archivoAdjunto;

        // Archivo adjunto que se encuentra en el storage
        $docAdjunto = $request->docAdjunto;

        // Nombre del agente
        $Nombre = $request->Nombre;

        // Ruta de la firma
        $ruta_firma = $request->ruta_firma;

        // Contraseña del correo del agente
        $password = $request->password;

        // Obtenemos las cuentas del destino en un array
        $cuentas = explode(", ", $cuenta_destino);
        
        // Validamos de nueva cuenta si las cuentas con copia estan vacias
        if ($cuenta_cc == null || $cuenta_cc == '') {
            // En caso de que si asignamos la cuenta del agente
            $cuentas_cc = $cuenta_agente;
        } else {
            // En caso contrario generamos un arreglo de cuentas con copia ya anteriormente obtenida
            $cuentas_cc = explode(", ", $cuenta_cc);
        }
        
        // Obtenemos la firma del archivo
        $firma_archivo = $request->firma;

        // Ruta del archivo (se sanitiza el nombre para evitar que caracteres como "/" o "\" se interpreten como subcarpetas inexistentes)
        $nombreArchivoTemporal = uniqid() . '_' . str_replace(['/', '\\'], '-', $docAdjunto);
        $caminhoDoArquivo = storage_path($nombreArchivoTemporal);
        
        // Nombre de la firma
        $nombre_firma = str_replace(' ', '', $Nombre) . '.png';
        
        // Eliminamos formato de base 64
        $base64 = str_replace('data:application/pdf;base64,', '', $attachement);

        // Eliminamos formato de cadena de base 64
        $firma = str_replace('data:application/png;base64,', '', $firma_archivo);

        // Colocamos en un nuevo archivo el documento
        file_put_contents($caminhoDoArquivo, base64_decode($base64));

        // Colocamos en un nuevo archivo la firma 
        file_put_contents($nombre_firma, base64_decode($firma));
        
        // return $request;
        
        // Ejecutamos el comando config:clear
        Artisan::call('config:clear');

        // config(['mail.driver' => 'smtp']);
        // config(['mail.host' => 'smtp.mailtrap.io']);
        // config(['mail.port' => '2525']);
        // config(['mail.username' => '30c21fac841b00']);
        // config(['mail.password' => '934eb3e2544936']);
        // config(['mail.encryption' => 'tls']);

        // Realizamos la configuración del correo para que se envie el correo a la persona correspondiente
        config(['mail.default' => 'smtp']);
        config(['mail.mailers.smtp.host' => 'smtp.gmail.com']);
        config(['mail.mailers.smtp.port' => 587]);
        config(['mail.mailers.smtp.username' => $cuenta_agente]);
        config(['mail.mailers.smtp.password' => $password]);
        // Descarta el mailer ya creado para que tome las credenciales nuevas
        Mail::purge('smtp');

        // Enviamos el correo ya con los datos previos
        Mail::send(new SendMailDocumento($subject, $mensaje, $caminhoDoArquivo, $cuenta_agente, $cuentas, $cuentas_cc, $Nombre, $password, $nombre_firma, $ruta_firma, $attachments, $docAdjunto));

        // Elimina los archivos temporales después de enviar el correo
        foreach ($attachments as $file) {
            if (file_exists($file['path'])) {
                unlink($file['path']);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Correo enviado correctamente.']);

        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Error al enviar el correo']);
        }
    }

    public function insertarCorreo(Request $request)
    {
        $idOportunidad = $request->id_oport;

        $oportunidad = v_Oportunidades::where('idOportunidad', $idOportunidad)->first();
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;
        $cuenta_agente = $request->cuentaAgente;
        $cuenta_destino = $request->cuentapara;
        $cuentacc = $request->cuentacc;

        if ($cuentacc == null && $cuenta_auxiliar1 != null) {
            $cuenta_cc = $cuenta_auxiliar1 . ', ' . $cuenta_agente;
        } else if ($cuentacc != null && $cuenta_auxiliar1 != null) {
            $cuenta_cc = $cuentacc . ', ' . $cuenta_auxiliar1 . ', ' . $cuenta_agente;
        } else if ($cuentacc != null && $cuenta_auxiliar1 == null) {
            $cuenta_cc = $cuentacc . ', ' . $cuenta_agente;
        } else if ($cuentacc == null && $cuenta_auxiliar1 == null) {
            $cuenta_cc = $cuenta_agente;
        }

        $subject = strtoupper($request->subjetDoc);
        $mensaje = null;
        $docAdjunto = $request->docAdjunto;

        if ($cuenta_cc == null || $cuenta_cc == '') {
            $cuentas_cc = $cuenta_agente;
        } else {
            $cuentas_cc = $cuenta_cc;
        }

        DB::statement('exec spInsertCorreo ?,?,?,?,?,?,?', [
            $idOportunidad,
            $cuenta_agente,
            $cuenta_destino,
            $subject,
            $mensaje,
            $docAdjunto,
            $cuentas_cc,
        ]);
    }

    public function apiCorreos()
    {
        $Login = $this->loginUsuario();
        $permisos = $this->permisosModulo($this->modulo);
        session(['rol' => $permisos[0]->idRol]);
        $rol = session('rol');

        switch ($rol) {
            case "3":
                $Correos = DB::table('v_Correos')->where('LoginCustomer', $Login)->get();
                break;
            case "1016":
            case "1023":
                $Correos = DB::table('v_Correos')->where('LoginCustomer', $Login)->get();
                break;
            case "4":
                $Correos = DB::table('v_Correos')->where('Login', $Login)->get();
                break;
            case "9":
                $Correos = DB::table('v_Correos')->where('LoginAuxiliar', $Login)->orWhere('LoginAuxiliar2', $Login)->orWhere('LoginAuxiliar3', $Login)->orWhere('LoginAuxiliar4', $Login)->get();
                break;
            default:
                $Correos = DB::table('v_Correos')->get();
                break;
        }

        return DataTables::of($Correos)->make(true);
    }

    public function obtenerOportunidadPTVeinteDias()
    {
        $idUsuario = $this->idUsuario();
        // $Login = $this->loginUsuario();
        // $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $Login);

        $oportunidades = DB::table('v_OportunidadesPT20Dias')->where('idAgente', $idUsuario)
            ->orWhere('idAuxiliar', $idUsuario)
            ->orWhere('idAuxiliar1', $idUsuario)
            ->orWhere('idAuxiliar2', $idUsuario)
            ->orWhere('idAuxiliar3', $idUsuario)
            ->orderBy('folio', 'desc')->get();

        return $oportunidades;
    }

    public function obtenerOportunidadSolOPCincoDias()
    {
        $idUsuario = $this->idUsuario();
        $oportunidades = DB::table('v_OportunidadesSolOP5Dias')->where('idCustomer', $idUsuario)->orderBy('folio', 'desc')->get();

        return $oportunidades;
    }

    public function validarTripa(Request $request)
    {
        // return $request;
        $tipo = 3;

        $idDocumento = $request->idDocumento;
        $check = $request->check;
        if ($check == 'true') {
            $tipo = 2;
        }

        DB::statement('exec sp_validarDocumento ?, ?', [
            $idDocumento,
            $tipo,
        ]);
    }

    public function validarTripaC(Request $request)
    {
        // return $request;
        $tipo = 3;

        $idDocumento = $request->idDocumento;
        $check = $request->check;
        if ($check == 'true') {
            $tipo = 5;
        }

        DB::statement('exec sp_validarDocumento ?, ?', [
            $idDocumento,
            $tipo,
        ]);
    }

    public function produccionTripa(Request $request)
    {
        $tipo = 2;

        $idDocumento = $request->idDocumento;
        $check = $request->check;
        $notas_produccion = strtoupper($request->notasProduccion);
        // Actualizamos la oportunidad a ganada
        $oportunidad = Oportunidades::find($request->id);

        if ($check == 'true') {
            $documentosIds = DB::table('relOport_Documento')->where('idOportunidad', $request->id)->pluck('idDocumento')->toArray();

            // Validamos si tiene alguna otra tripa en producción
            $tripasEnProd = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 1)
                            ->first();

            $todasTripasEnProd = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 1)
                            ->count();

            $tipo = 1;

            DB::statement('exec sp_validarDocumento ?, ?', [
                $idDocumento,
                $tipo,
            ]);

            DB::table('DocumentosOport')->where('idDocumento', $idDocumento)
                ->update([
                    'genero_produccion' => 1,
                    'resolvio_produccion' => 0,
                    'estatus_doc_id' => 3,
                ]);

            $todasTripasEnTripasPreprensa = DB::table('DocumentosOport')
                        ->whereIn('idDocumento', $documentosIds)
                        ->where('tipoTripa', 6) // Tipo tripa 6 para preprensa
                        ->count();

            // return $oportunidad;

            $refresh = false;
    
            // DB::statement('exec sp_actualizarNotasProduccion ?, ?, ?, ?', [
            //     $notas_produccion,
            //     $id_estatus,
            //     $idCustomer,
            //     $request->id,
            // ]);

            DB::table('notas_produccion_tripas')->insert([
                'nota' => $notas_produccion,
                'oportunidad_id' => $request->id,
                'documento_id' => $idDocumento,
            ]);

            $totalTripasPreprensa = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 6) // Tipo tripa 6 para preprensa
                            ->count();

            $fecha = new \DateTime();
            // Actualiza estatus de oportunidad a En Producción
            if ($todasTripasEnTripasPreprensa == 0) {
    
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida == null) {
                    $oportunidad->fecSolPreprensaConcluida = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecResultadoF == null) {
                    $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
                $oportunidad->idEstatus = 11; // En Producción
                $oportunidad->genero_produccion = 1;
                $oportunidad->estatus_doc_id = 3;
                $oportunidad->preprensa_concluida = 1;
                $oportunidad->save();

                $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 28)->first();

                $oportunidad = DB::table('Oportunidades')->where('idOportunidad', $request->id)->first();
                
                if ($alertasPlantilla != null) {
                    $customerCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAtendido)->first();

                    if ($customerCorreo != null) {
                        $correoCustomer = $customerCorreo->Correo;
                        $nombre_oportunidad = $oportunidad->nomOportunidad;
                        $folio = $oportunidad->idOportunidad;
                        $fecha_oportunidad = $oportunidad->fecCreacion;
                        $estatus_oportunidad = $alertasPlantilla->ValorCampo;
                        $subject = $alertasPlantilla->Asunto;
                        $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                        $route = "oportunidades";
                        $nota = $request->motivo;
                        $fecha_solicitud = null;
                        $fecha_asignacion = null;
                        $fecha_entrega = null;
                        $fecha_aut_archivo = null;
                        $diasEntrega = null;
                        $mensaje = $alertasPlantilla->Mensaje;
                        $numOrdenMTC = $oportunidad->ops;
                        $rejectDocument = 2;

                        // Enviamos el correo al cotizador
                        Mail::to($correoCustomer)
                        ->cc([$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA])
                        ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));

                        // Pruebas
                        // Mail::to('cgonzalez@litoprocess.com')->cc(['edmond@litoprocess.com', 'maflores@litoprocess.com'])->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));

                    }
                }

                $refresh = true;
            }
    
        } else {
            DB::statement('exec sp_validarDocumento ?, ?', [
                $idDocumento,
                $tipo,
            ]);

            $refresh = false;
        }

        return response()->json(['response' => true, 'message' => 'Estatus de producción actualizado correctamente.', 'refresh' => $refresh]);
    }

    public function actualizarNombreDocumento(Request $request)
    {

        $idDocumento = $request->idDocumento;
        $nombre = $request->nombre;

        DB::statement('exec spUpdateNombreDocumento ?, ?', [
            $idDocumento,
            $nombre,
        ]);
    }

    // public function obtenerEmlMsg(Request $request)
    // {

    //     $client = new \GuzzleHttp\Client();
    //     $res = $client->request('GET', 'http://192.168.2.222/lectoremail/api/documento/53017', ['documento' => (string)$request->idDocumento]);

    //     return $res;

    //     // $eml_msg = Http::get('http://192.168.2.222/lectoremail/api/documento/' . $request->idDocumento);
    //     // return $eml_msg;
    // }

    public function addValorOportunidad(Request $request)
    {
        $idOportunidad = $request->idOportunidad;
        $numPresupuestoMTC = $request->numPresupuestoMTC;
        DB::statement('SET NOCOUNT ON;exec sp_EPaddValorOportunidad :numPresupuestoMTC,:idElementoP', [':numPresupuestoMTC' => $numPresupuestoMTC, ':idElementoP' => $idOportunidad]);
    }

    public function obtenerNumPresupuestos(Request $request)
    {
        $search = $request->search;

        if ($search != '') {
            $autocomplate = v_presupuestosMetricsAll::orderby('NumPresupuesto', 'asc')->select('NumPresupuesto', 'Importe')->where('NumPresupuesto', $search)->first();
        } else {
            $autocomplate = null;
        }

        return $autocomplate;
    }

    public function agregarPresupuesto(Request $request)
    {
        $presupuesto = $request->presupuesto;
        $valor = $request->valor;
        $idOportunidad = $request->idOportunidad;

        DB::statement('exec sp_insertarImportePresupuesto ?, ?, ?', [
            $presupuesto,
            $valor,
            $idOportunidad,
        ]);
    }

    public function agregarOPMetrics(Request $request)
    {
        $opMetrics = $request->opMetrics;
        $idOportunidad = $request->idOportunidad;

        DB::statement('exec sp_insertarOPMetrics ?, ?', [
            $opMetrics,
            $idOportunidad,
        ]);
    }

    public function apiNumPresupuestos(Request $request)
    {
        $num_presupuestos = DB::table('relOportunidadesPresupuestos')->where('idOportunidad', $request->idOportunidad)->get();
        return DataTables::of($num_presupuestos)->make(true);
    }

    public function apiOportunidadCambios(Request $request)
    {
        $rol = $this->idRol();
        $oportunidadCambios = DB::table('v_OportunidadCambiosMinimos')->where('idOportunidad', $request->idOportunidad)->where('estatus', 1)->get();
        $catCambios = DB::table('catOportunidadCambiosMinimos')->where('estatus', 1)->get();

        if (count($oportunidadCambios) == 0) {

            $catCambios = DB::table('catOportunidadCambiosMinimos')->where('estatus', 1)->get();
            foreach ($catCambios as $catCambio) {
                DB::statement('exec sp_insert_catCambios ?,?,?', [$request->idOportunidad, $catCambio->idCatCambio, $catCambio->cambio]);
            }
        } else if (count($oportunidadCambios) < count($catCambios)) {

            foreach ($catCambios as $catCambio) {

                $catIdCambio = DB::table('catOportunidadCambiosMinimos')->where('idCatCambio', $catCambio->idCatCambio)->get();


                for ($i = 0; $i < count($catIdCambio); $i++) {
                    $idCambio = $catIdCambio[$i]->idCatCambio;
                    $oportunidadCambios = DB::table('v_OportunidadCambiosMinimos')->where('idOportunidad', $request->idOportunidad)->where('idCambio', $idCambio)->where('estatus', 1)->get();
                    // return $oportunidadCambios;

                    if (count($oportunidadCambios) == 0) {
                        DB::statement('exec sp_insert_catCambios ?,?,?', [$request->idOportunidad, $catIdCambio[0]->idCatCambio, $catIdCambio[0]->cambio]);
                    }
                }
            }
        }

        $oportunidadCambiosNuevo = DB::table('v_OportunidadCambiosMinimos')->where('idOportunidad', $request->idOportunidad)->where('estatus', 1)->orderBy('idCambio', 'asc')->get();

        return DataTables::of($oportunidadCambiosNuevo)
            ->addColumn('rol', function () use ($rol) {
                $idRol = $rol;

                return $idRol;
            })
            ->make(true);
    }

    public function apiNumOPMetrics(Request $request)
    {
        $num_op_metrics = DB::table('relOportunidadesOPMetrics')->where('idOportunidad', $request->idOportunidad)->get();
        return DataTables::of($num_op_metrics)->make(true);
    }

    public function activarPresupuesto(Request $request)
    {
        $id = $request->id;
        $checked = $request->checked;

        DB::statement('exec sp_updateAplicaPresupuesto ?, ?', [
            $id,
            $checked,
        ]);

        $presupuestos = DB::table('relOportunidadesPresupuestos')->where('idrelOportunidadPresupuesto', $id)->get();

        $idOportunidad = $presupuestos[0]->idOportunidad;

        $acumulado = DB::table('v_SumImportePresupuestos')->where('idOportunidad', $idOportunidad)->where('aplica', 1)->get();

        if (count($acumulado) == 0) {
            $importe = 0;
        } else {
            $importe = $acumulado[0]->importe;
        }

        DB::statement('exec sp_updateImportePresupuesto ?, ?', [
            $idOportunidad,
            $importe,
        ]);

        return $importe;
    }

    public function activarRealizar(Request $request)
    {
        $id = $request->id;
        $checked = $request->checked;
        $idOportunidad = $request->idOportunidad;
        $idUsuario = $this->idUsuario();

        DB::statement('exec sp_updateRealizarCambio ?, ?', [
            $id,
            $checked,
        ]);

        if ($checked == 0) {
            DB::statement('exec sp_updateCambioRealizado ?, ?', [
                $id,
                $checked,
            ]);

            DB::statement('exec sp_updateValorCambio ?,?', [
                $id,
                null,
            ]);
            $this->storeBitacoraCambiosMinimos($id, 'Acción realizar', 'Se realizó la marcación como realizar', "Se realizó la marcación como no realizar", $idUsuario);
        } else {
            $this->storeBitacoraCambiosMinimos($id, 'Acción realizar', "Se realizó la marcación como no realizar", 'Se realizó la marcación como realizar', $idUsuario);
        }

        $oportunidadCambiosRealizar = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('realizar', 1)->get();
        $oportunidadCambiosRealizados = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('realizado', 1)->get();

        $total_realizar = count($oportunidadCambiosRealizar);
        $total_realizados = count($oportunidadCambiosRealizados);

        $total_cambios_realizados = $total_realizar - $total_realizados;
        return $total_cambios_realizados;
    }

    public function asignarValorCambio(Request $request)
    {
        $id = $request->id;
        $valor = $request->valor;
        $idOportunidad = $request->idOportunidad;
        $idUsuario = $this->idUsuario();

        $previousChange =  DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('idOportunidadCambiosMinimos', $id)->first();

        if ($previousChange != null) {
            $previousChange = $previousChange->valor;
        } else {
            $previousChange = NULL;
        }

        DB::statement('exec sp_updateValorCambio ?,?', [
            $id,
            $valor,
        ]);

        $oportunidadCambiosRealizar = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('realizar', 1)->get();
        $oportunidadCambiosRealizados = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('realizado', 1)->get();

        $total_realizar = count($oportunidadCambiosRealizar);
        $total_realizados = count($oportunidadCambiosRealizados);

        $total_cambios_realizados = $total_realizar - $total_realizados;

        $this->storeBitacoraCambiosMinimos($id, 'Asignación de valores', $previousChange, "Solicitud de cambio: " . $valor, $idUsuario);

        return $total_cambios_realizados;
    }

    /**
     * Función para realizar el registro de la bitacora
     * 
     * @param
     * @return mixed
     */
    public function storeBitacoraCambiosMinimos($solicitudCambiosId, $section, $previousChange, $newChange, $userId)
    {
        try {
            // Ejecutamos el procedimiento para guardar la bitacora de solicitudes
            DB::statement('exec sp_storeBitacoraSolicitudes ?,?,?,?,?', [
                $solicitudCambiosId,
                $section,
                $previousChange,
                $newChange,
                $userId
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema";
        }
    }

    public function activarRealizado(Request $request)
    {
        $id = $request->id;
        $checked = $request->checked;
        $idOportunidad = $request->idOportunidad;

        $idUsuario = $this->idUsuario();

        DB::statement('exec sp_updateCambioRealizado ?, ?', [
            $id,
            $checked,
        ]);

        
        if ($checked == 0) {
            $this->storeBitacoraCambiosMinimos($id, 'Acción realizado', 'Se realizó la marcación como realizado', "Se realizó la marcación como no realizado", $idUsuario);
        } else {
            $this->storeBitacoraCambiosMinimos($id, 'Acción realizado', 'Se realizó la marcación como no realizado', "Se realizó la marcación como realizado", $idUsuario);
            Oportunidades::where('idOportunidad', $idOportunidad)->update(['cambiosMinimos' => 0]);
        }

        $oportunidadCambiosRealizar = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('realizar', 1)->get();
        $oportunidadCambiosRealizados = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $idOportunidad)->where('realizado', 1)->get();

        $total_realizar = count($oportunidadCambiosRealizar);
        $total_realizados = count($oportunidadCambiosRealizados);

        $total_cambios_realizados = $total_realizar - $total_realizados;

        return $total_cambios_realizados;
    }

    public function activarOPMetrics(Request $request)
    {
        $id = $request->id;
        $checked = $request->checked;

        DB::statement('exec sp_updateAplicaOPMetrics ?, ?', [
            $id,
            $checked,
        ]);

        $op_metrics = DB::table('relOportunidadesOPMetrics')->where('idrelOportunidadOPMetrics', $id)->get();

        $idOportunidad = $op_metrics[0]->idOportunidad;

        $op_aplicadas = DB::table('relOportunidadesOPMetrics')->where('idOportunidad', $idOportunidad)->where('aplica', 1)->get();

        // return $op_aplicadas;



        // if (count($op_aplicadas) == 0) {
        //     $aplicadas = '';
        // } else {
        //     for ($i = 0; $i < count($obtener_cuenta_cc); $i++) {
        //         $obtener_cc[$i] = $obtener_cuenta_cc[$i];
        //     }
        //     $cc = implode(', ', array_column($obtener_cc, 'CorreoCC'));
        //     if ($cuenta_auxiliar1 == '' || $cuenta_auxiliar1 == null) {
        //         $cuenta_cc = $cc;
        //     } else {
        //         $cuenta_cc = $cc . ', ' . $cuenta_auxiliar1;
        //     }
        // }


        if (count($op_aplicadas) == 0) {
            $aplicadas = '';
        } else if (count($op_aplicadas) == 1) {
            $aplicadas = $op_aplicadas[0]->numOPMetrics;
        } else if (count($op_aplicadas) > 1) {
            for ($i = 0; $i < count($op_aplicadas); $i++) {
                $obtener_aplicadas[$i] = $op_aplicadas[$i];
            }
            $aplicadas = implode(', ', array_column($obtener_aplicadas, 'numOPMetrics'));
        }

        // DB::statement('exec sp_updateImportePresupuesto ?, ?', [
        //     $idOportunidad,
        //     $importe,
        // ]);

        return $aplicadas;
    }

    public function tripaCCRealizada(Request $request) {
        DB::statement('exec sp_tripaCCRealizada ?',[
            $request->idDocumento,
        ]);
    }
    
    /**
     * Función para realizar el cambio de estatus a la tripa con notas de correcciones
     * 
     * @return void
     */
    public function tripaCCorreccioneRealizada(Request $request)
    {
        DB::statement('exec sp_tripaConCorreccionesRealizada ?',[
            $request->idDocumento,
        ]);
    }

    /**
     * Función para obtener el motivo del rechazo de una tripa
     * 
     * @param Request $request
     * @return mixed
     */
    public function getMotivosRechazoDocumento(Request $request)
    {
        try {
            return response()->json([
                'response' => true,
                'catMotivos' => DB::table('cat_motivosRechazo')->where('estatus', 1)->get()->toArray()
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un error al obtener la información";
        }
    }

    /**
     * Función para validar si se actualiza o se registra desde cierto punto de un cambio de estatus
     * 
     * @
     */
    public function saveOrUpdateBitacora($oportunidad, $fecha, $idUsuario, $typeDate, $hasCambios = false)
    {
        try {
            $bitacora = bitacoraTiemposEstatus::where('idOportunidad', $oportunidad->idOportunidad)->orderBy('ciclo', 'desc')->first();

            if ($bitacora != null) {

                // Registramos uno nuevo hasta cambios minimos
                if ($hasCambios) {
                    $bitacoraNewCm = new bitacoraTiemposEstatus();
                    $bitacoraNewCm->idOportunidad = $oportunidad->idOportunidad;
                    $bitacoraNewCm->fecListoCotizar = $this->transformDate($fecha);
                    $bitacoraNewCm->idUsuarioListCot = $idUsuario;
                    $bitacoraNewCm->ciclo = $bitacora->ciclo + 1;
                    $bitacoraNewCm->save();
                }

                $bitacora = bitacoraTiemposEstatus::where('idOportunidad', $oportunidad->idOportunidad)->orderBy('ciclo', 'desc')->first();

                if ($typeDate == 2) {
                    $bitacora->fecSolCotizacion = $this->transformDate($fecha);
                    $bitacora->idUsuarioSolCot = $idUsuario;
                }
                if ($typeDate == 3) {
                    $bitacora->fecListoCotizar = $this->transformDate($fecha);
                    $bitacora->idUsuarioListCot = $idUsuario;
                }
                if ($typeDate == 4) {
                    $bitacora->fecCotizando = $this->transformDate($fecha);
                    $bitacora->idUsuarioCot = $idUsuario;
                }
                if ($typeDate == 13) {
                    $bitacora->fecRevisionTec = $this->transformDate($fecha);
                    $bitacora->idUsuarioRevisionTec = $idUsuario;
                }
                if ($typeDate == 5) {
                    $bitacora->fecRevision = $this->transformDate($fecha);
                    $bitacora->idUsuarioRev = $idUsuario;
                }
                if ($typeDate == 6) {
                    $bitacora->fecListoPresupuesto = $this->transformDate($fecha);
                    $bitacora->idUsuarioListPre = $idUsuario;
                }
                if ($typeDate == 7) {
                    $bitacora->fecPresupTerminado = $this->transformDate($fecha);
                    $bitacora->idUsuarioPresupTerm = $idUsuario;
                }
                if ($typeDate == 8) {
                    $bitacora->fecPrepedido = $this->transformDate($fecha);
                    $bitacora->idUsuarioPrepedido = $idUsuario;
                }
                if ($typeDate == 9) {
                    $bitacora->fecSolPreprensa = $this->transformDate($fecha);
                    $bitacora->idUsuarioSolPreprensa = $idUsuario;
                }
                if ($typeDate == 10) {
                    $bitacora->fecSolPreprensaConcluida = $this->transformDate($fecha);
                    $bitacora->idUsuarioSolPreprensaConcluida = $idUsuario;
                }

                $bitacora->save();

                if ($typeDate != 2) {
                    // Actualizamos la fecha tope
                    $this->actualizarFechaTopeOport($typeDate, $oportunidad);
                }

            } else {
                $bitacora = new bitacoraTiemposEstatus();
                $bitacora->idOportunidad = $oportunidad->idOportunidad;
                
                if ($typeDate == 1) {
                    $bitacora->fecCreacion = $this->transformDate($fecha);
                    $bitacora->idUsuarioPreReg = $idUsuario;
                }
                if ($typeDate == 2) {
                    $bitacora->fecSolCotizacion = $this->transformDate($fecha);
                    $bitacora->idUsuarioSolCot = $idUsuario;
                }
                if ($typeDate == 3) {
                    $bitacora->fecListoCotizar = $this->transformDate($fecha);
                    $bitacora->idUsuarioListCot = $idUsuario;
                }
                if ($typeDate == 4) {
                    $bitacora->fecCotizando = $this->transformDate($fecha);
                    $bitacora->idUsuarioCot = $idUsuario;
                }
                if ($typeDate == 13) {
                    $bitacora->fecRevisionTec = $this->transformDate($fecha);
                    $bitacora->idUsuarioRevisionTec = $idUsuario;
                }
                if ($typeDate == 5) {
                    $bitacora->fecRevision = $this->transformDate($fecha);
                    $bitacora->idUsuarioRev = $idUsuario;
                }
                if ($typeDate == 6) {
                    $bitacora->fecListoPresupuesto = $this->transformDate($fecha);
                    $bitacora->idUsuarioListPre = $idUsuario;
                }
                if ($typeDate == 7) {
                    $bitacora->fecPresupTerminado = $this->transformDate($fecha);
                    $bitacora->idUsuarioPresupTerm = $idUsuario;
                }
                if ($typeDate == 8) {
                    $bitacora->fecPrepedido = $this->transformDate($fecha);
                    $bitacora->idUsuarioPrepedido = $idUsuario;
                }
                if ($typeDate == 9) {
                    $bitacora->fecSolPreprensa = $this->transformDate($fecha);
                    $bitacora->idUsuarioSolPreprensa = $idUsuario;
                }
                if ($typeDate == 10) {
                    $bitacora->fecSolPreprensaConcluida = $this->transformDate($fecha);
                    $bitacora->idUsuarioSolPreprensaConcluida = $idUsuario;
                }

                $bitacora->ciclo = 1;

                $bitacora->save();
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * Función para realizar el cambio de fecha al formato que lo reconoce la db
     * 
     * @param $fecha
     * @return mixed
     */
    public function transformDate($fecha)
    {
        try {
            $date = \DateTime::createFromFormat('d-m-Y H:i:s', $fecha);
            $date->setTimezone(new \DateTimeZone('America/Guatemala'));
            return $date->format('d-m-Y H:i:s');
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema";
        }
    }

    /**
     * Función para actualizar la fecha tope de la oportunidad (Nuevo campo)
     * 
     * @param $type
     * @param $oportunidad
     */
    public function actualizarFechaTopeOport($type, $oportunidad)
    {
        try {
            $timezone = new \DateTimeZone('America/Guatemala');

            // Oportunidad con fecha tope
            $oportunidadFt = DB::table('v_OportunidadesGrid')->where('idOportunidad', $oportunidad->idOportunidad)->first();

            if ($type == 2) {
                $finalDate = \DateTime::createFromFormat('d/m/Y H:i', $oportunidadFt->fecTope, $timezone);
            } else {
                $format = $oportunidad->fechaTopeOporCalc == null ? 'd/m/Y H:i' : 'Y-m-d H:i:s.u';
                $value = $oportunidad->fechaTopeOporCalc == null ? $oportunidadFt->fecTope : $oportunidad->fechaTopeOporCalc;
                $finalDate = \DateTime::createFromFormat($format, $value, $timezone);
            }

            $dateString = $finalDate->format('d-m-Y H:i:s');
            $f = DB::selectOne("select dbo.fTopeDate(:date) as result", ['date' => $dateString]);
            $finalDate = \DateTime::createFromFormat('Y-m-d H:i:s.u', $f->result, $timezone);

            if ($finalDate === false) {
                $finalDate = \DateTime::createFromFormat('Y-m-d H:i:s', $f->result, $timezone);
            }

            // Validar si la hora sobrepasa las 7:30 (19:30)
            $hour = (int)$finalDate->format('H');
            $minutes = (int)$finalDate->format('i');
            $currentTime = $hour * 60 + $minutes;
            $limitTime = 19 * 60 + 30; // 7:30 PM = 19:30

            if ($currentTime > $limitTime) {
                // Si sobrepasa las 7:30, establecer a las 9:00 (09:00)
                $finalDate->setTime(9, 0, 0);
                
                // Validar si es viernes (5), si es así, pasar al lunes
                $dayOfWeek = (int)$finalDate->format('N'); // 1=lunes, 5=viernes
                if ($dayOfWeek == 5) {
                    $finalDate->modify('+3 days'); // Viernes + 3 días = Lunes
                }
            }

            // Actualizamos la fecha tope de la oportunidad con la fecha calculada y ajustada
            $oportunidad->fechaTopeOporCalc = $finalDate->format('d-m-Y H:i:s');
            $oportunidad->save();
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al actualizar la fecha tope del proyecto";
        }
    }

    /**
     * Función para obtener la tripa autorizada sin cambios de una oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function getTripaAutSinCam(Request $request)
    {
        try {
            // Obtenemos las tripas de la oportunidad
            $tripas = DB::table('relOport_Documento')->where('idOportunidad', $request->idOportunidad)->get()->pluck('idDocumento')->toArray();

            // Buscamos y filtramos las tripas que han sido aprobadas sin cambios
            $tripasAutSinCambios = DB::table('DocumentosOport')->whereIn('idDocumento', $tripas)->where('tipoTripa', 2)->first();

            // Obtenemos el nombre del vendedor y el nombre del cliente
            $q = DB::select('select o.nomOportunidad, ct.Nombre as cteNombre, cl.Nombre as vendedorNombre from Oportunidades o
                                    left outer join Clientes c on c.idCliente = o.idCliente
                                    left outer join Lito.dbo.Cte ct on ct.Cliente = c.idClienteINT
                                    left outer join ControlLogin.dbo.cat_Usuarios cl on cl.Id_Usuario = o.idAgente
                                    where idOportunidad = ' . $request->idOportunidad);

            $oport = Oportunidades::where('idOportunidad', $request->idOportunidad)->first();

            // El nombre sera el folio de la oportunidad y el nombre de la oportunidad
            $nombre = $oport->folio . ' - ' . $oport->nomOportunidad;

            // Regresamos el nombre de la tripa autorizada
            return response()->json([
                'response' => true,
                'nomTrabajo' => $nombre,
                'nomVendedor' => $q[0]->vendedorNombre,
                'cteNombre' => $q[0]->cteNombre
            ]);
        } catch (\Exception $e) {
            return 'Lo sentimos ocurrió un problema';
        }
    }

    /**
     * Funcion para poder guardar la información de la ficha en la tabla de --
     * 
     * @param Request $request
     * @return mixed
     */
    public function solicitarInformacionFichaTecnica(Request $request)
    {
        try {
            // Obtenemos la oportunidad
            $oportunidad = Oportunidades::where('idOportunidad', $request->idOportunidad)->first();

            // Obtenemos el id del solicitante
            $idSolicitante = $this->idUsuario();

            // Obtenemos el nombre del cliente
            $nomCliente = DB::select('select o.nomOportunidad, ct.Nombre from Oportunidades o
                                            left outer join Clientes c on c.idCliente = o.idCliente
                                            left outer join Lito.dbo.Cte ct on ct.Cliente = c.idClienteINT where o.idOportunidad = ' . $oportunidad->idOportunidad);
            $nomCliente = $nomCliente[0]->Nombre;
            if ($request->nomTrabajo == null) {
                $nomTrabajo = $oportunidad->folio . ' - ' . $request->nomOportunidad;
            } else {
                $nomTrabajo = $request->nomTrabajo;
            }

            // Ejecución del store que realizará el guardado de la informacion principal de la ficha
            DB::statement('exec spStoreFichaCandy ?,?,?', [
                $nomTrabajo,
                $request->idOportunidad,
                $idSolicitante
            ]);
            
            // Ejecución del store que realizará el guardado en la tabla de marco
            DB::statement('exec spGuardarFicha ?,?,?', [
                $nomTrabajo,
                $nomCliente,
                $oportunidad->idOportunidad
            ]);

            // Actualizamos el campo de candy para marcar que se ha solicitado la ficha tecnica
            $oportunidad->hasFichaT = 1;
            $oportunidad->save();

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un error al informar sobre la ficha técnica";
        }
    }

    /**
     * Función para actualizar los precios (actualizar carrucel de cots y cust)
     *
     * @param Request $request
     * @return void
     */
    public function updatePrices (Request $request)
    {
        try {
            DB::statement('exec sp_updatePricesOportunidad ?,?,?,?', [
                $request->idOportunidad,
                $request->estatus,
                null,
                null
            ]);

            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return 'Lo sentimos ocurrió, un problema al actualizar los precios';
        }
    }

    /**
     * Función para obtener el listado de cambios minimos relacionados con la oportunidad
     *
     * @param Request $request
     * @return mixed
     */
    public function getListSolicitudes(Request $request)
    {
        try {
            // Obtenemos los cambios minimos relacionados a esta oportunidad
            $seguimiento = DB::table('v_SolicitudesCambiosCandy')->where('idOportunidad', $request->idOportunidadP)->orderBy('created_at', 'desc')->get();

            // Obtenemos el rol del usuario
            $rolId = $this->idRol();

            // Regresamos la datatable para mostrarlo en el modal
            return DataTables::of($seguimiento)
                            ->addColumn('acciones', function ($row) use ($rolId) {
                                if ($rolId == 4 || $rolId == 9) {
                                    return "<div></div>";
                                } else {
                                    if ($row->realizado == 1) {
                                        return "<div></div>";
                                    } else {
                                        return "
                                            <div>
                                                <div><a class='cursor-pointer' title='Eliminar' style='color: #000' onclick='eliminarSolicitudCambio(" . json_encode($row->idSolicitudCambios) . ")' ><img src='" . asset('img/eliminar.png') . "' width='20px'></a></div>
                                            </div>
                                        ";
                                    }
                                }
                            })
                            ->addColumn('realizado', function ($row) use ($rolId) {
                                // 1 = realizar, 2 = realizado
                                $cadenaOnclick = "onclick='changeStatusAction(" . json_encode($row->idSolicitudCambios) . ", this, 2)' name='realizado_" . $row->idSolicitudCambios . "'";

                                if ($rolId == 4 || $rolId == 9) {
                                    return '<div class="content-static"><div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->realizado ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div></div>';
                                } else {
                                    if ($row->realizado == 1) {
                                        return '<div class="content-static"><div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->realizado ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div></div>';
                                    } else {
                                        return '<div class="content-notes"><div class="custom-switch p-0"><input type="checkbox" class="custom-control-input custom-control-input-mda ctrl-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div></div>';
                                    }
                                }
                            })
                            ->rawColumns(['acciones', 'realizado'])
                            ->make(true);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema al obtener el listado";
        }
    }

    /**
     * Función para agregar un nuevo cambio
     *
     * @param Request $request
     * @return mixed
     */
    public function addNewChange(Request $request)
    {
        try {
            // Realizamos las validaciones
            $validator = $this->validateFormUpdatePedidos($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // obtenemos el usuario que esta loggueado
            $idUsuario = $this->idUsuario();

            // Realizamos el guardado del cambio reciente
            DB::statement('exec sp_insertNewChangeSeguimiento ?,?,?', [
                $request->cambio,
                $request->idOportunidad,
                $idUsuario,
            ]);

            // Obtenemos el id de la solicitud de cambios que recien se acaba de crear
            $solicitudCambiosId = DB::table('solicitudCambiosCandy')
                                        ->where('cambioDescripcion', $request->cambio)
                                        ->where('IdUsuario', $idUsuario)
                                        ->where('IdOportunidad', $request->idOportunidad)
                                        ->first()->idSolicitudCambios;

            // Generamos la bitacora de cambios
            $this->storeBitacoraSolicitudes('Creación de solicitud de cambios', NULL, "Creación del cambio: " . $request->cambio, $idUsuario, $solicitudCambiosId);

            // Regresamos la respuesta para validar
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar el cambio";
        }
    }

    /**
     * Función para realizar las validaciones de la actualizacion de comisiones
     *
     * @param $request
     * @return mixed
     */
    public function validateFormUpdatePedidos($request)
    {
        // Realizamos las validaciones
        $validator = Validator::make(
            $request->all(),
            [
                'cambio' => ['required', 'string', 'min:5', 'max:255'],
            ],
            [
                'cambio.required' => 'Se requiere el cambio',
                'cambio.string' => 'Verifica que sea un texto',
                'cambio.min' => 'Se requiere que contenga mínimo 5 caracteres',
                'cambio.max' => 'No puedes sobrepasar los 255 caracteres',
            ]
        );

        // Validamos si hay errores
        if ($validator->fails()) {
            $validatorMsg = $validator->errors()->messages();
            $final = [];

            // Colocamos los campos que se van a validar
            $fields = [
                'cambio'
            ];

            // Se ocupara esta cuenta para que dependiendo de cuantos campos se agreguen se puedan ir agregando a la funcion que obtiene la cadena
            $finalResponse = [
                'errores' => count($fields),
            ];

            // Recorrido para llenar los inputs
            foreach ($fields as $field) {
                $final['error_' . $field] = $this->concatMessagesError($validatorMsg, $field);
            }

            // Recorrido para asignar el valor del mensaje al campo
            foreach ($final as $key => $data) {
                $finalResponse[$key] = $data['cadena'];
            }

            // Regresamos la respuesta con los errores
            return response()->json($finalResponse);
        }
    }

    /**
     * Función para optimizar la solución de mensajes de error
     *
     * @return mixed
     */
    public function concatMessagesError($validatorMsg, $field)
    {
        // Declaramos la cadena
        $cadena = '';

        // Validamos si existe el campo dentro del validador
        if (isset($validatorMsg[$field])) {
            // Recorremos y concatenamos los mensajes
            foreach ($validatorMsg[$field] as $errorMot) {
                $cadena .= '<span class="text-danger text-xs" style="padding-top: 5px; padding-left: 5px;">' . $errorMot . '</span><br>';
            }
        }

        // Regresamos la cadena
        return [
            'cadena' => $cadena
        ];
    }

    /**
     * Función para actualizar el estatus del cambio
     *
     * @param Request $request
     * @return void
     */
    public function updateChangeEstatus(Request $request)
    {
        try {
            // Realizamos el guardado del cambio de estatus del cambio de seguimiento
            DB::statement('exec sp_updateStatusCambioSeguimiento ?, ?, ?', [
                $request->idSolCom,
                $request->element === 'true' ? 1 : 0,
                $request->type,
            ]);

            // obtenemos el usuario que esta loggueado
            $idUsuario = $this->idUsuario();

            // Guardamos la bitacora del cambios
            $this->storeBitacoraSolicitudes('Actualización de estatus de la solicitud de cambios', ($request->element === 'true' ? 'Cambio sin realizar' : 'Cambio realizado'), "Actualización del estatus: " . ($request->element === 'true' ? 'Realizado' : 'Sin realizar'), $idUsuario, $request->idSolCom);

            // Regresamos la respuesta json
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al actualizar el estatus";
        }
    }

    /**
     * Función para eliminar una solicitud de cambios
     *
     * @param Request $request
     * @return mixed
     */
    public function deleteSolicitudCambios(Request $request)
    {
        try {
            // Obtenemos el cambio que se eliminara
            $cambio = DB::table('solicitudCambiosCandy')
                                        ->where('idSolicitudCambios', $request->idSolCom)
                                        ->first()->cambioDescripcion;
            
            // Ejecutamos el procedimiento para eliminar la solicitud
            DB::statement('exec sp_deleteSolicitudCambios ?', [
                $request->idSolCom
            ]);

            // obtenemos el usuario que esta loggueado
            $idUsuario = $this->idUsuario();

            // Guardamos la bitacora del cambios
            $this->storeBitacoraSolicitudes('Eliminación de solicitud de cambios', NULL, "Eliminación de la solicitud: " . $cambio, $idUsuario, $request->idSolCom);

            // Regresamos la respuesta para que valide que se realizó de forma correcta
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al eliminar el cambio";
        }
    }

    /**
     * Función para obtener las solicitudes de cambio de una oportunidad
     *
     * @param Request $request
     * @return mixed
     */
    public function getTotalSolicitudes(Request $request)
    {
        try {
            // Obtenemos los cambios minimos relacionados a esta oportunidad
            $seguimiento = DB::table('v_SolicitudesCambiosCandy')->where('idOportunidad', $request->idOportunidad)->get();

            // Realizados
            $realizados = collect($seguimiento)->where('realizado', 1)->count();

            // No realizados
            $noRealizados = collect($seguimiento)->where('realizado', 0)->count();

            // Regresamos los valores en json
            return response()->json([
                'totalCambiosAbiertos' => $noRealizados,
                'totalCambiosRealizados' => $realizados,
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema";
        }
    }

    /**
     * Función para obtener los cambios minimos que se hayan registrado en la oportunidad
     *
     * @param Request $request
     * @return mixed
     */
    public function getAllPreviousCambiosMinimos(Request $request)
    {
        try {
            // Obtenemos los cambios minimos relacionados a esta oportunidad
            $cambiosMinimos = DB::table('OportunidadCambiosMinimos')->where('idOportunidad', $request->idOportunidad)->where('realizar', 1)->get();

            // variable con la cadena final para mostarse en el front
            $finalStringCambios = '';

            // Validamos si existen cambios minimos
            if (count($cambiosMinimos) > 0) {
                foreach ($cambiosMinimos as $cambio) {
                    $cadena = '
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <p><strong>'.$cambio->cambio.':</strong> </p>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="text-adap cursor-pointer" onclick="showCompleteDetail('.$cambio->idOportunidadCambiosMinimos.')">'.($cambio->valor != null ? $cambio->valor : 'Sin valor').'</p>
                        </div>
                        <div class="col-md-4 text-center">
                            '.($cambio->realizado == 1 ? 'Realizado' : 'No realizado' ).'
                        </div>
                    </div>
                    ';

                    $finalStringCambios.=$cadena;
                }
            }

            // Regresamos los valores en json
            return response()->json([
                'finalList' => $finalStringCambios,
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema";
        }
    }

    /**
     * Función para obtener el valor del cambio minimo
     * 
     * @param Request $request
     * @return mixed
     */
    public function getCambioMinimoContent(Request $request)
    {
        try {
            // Cambio minimo
            $cambiosMinimos = DB::table('OportunidadCambiosMinimos')->where('idOportunidadCambiosMinimos', $request->idCambioMinimo)->first();

            // Regresamos la respuesta
            return response()->json([
                'response' => true,
                'titulo' => $cambiosMinimos->cambio,
                'cambio' => $cambiosMinimos->valor
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para realizar el guardado de la bitacora de solicitud de cambios
     * 
     * @param $section
     * @param $previousChange
     * @param $newChange
     * @param $userId
     * @param $solicitudCambiosId
     * @return mixed
     */
    public function storeBitacoraSolicitudes($section, $previousChange, $newChange, $userId, $solicitudCambiosId)
    {
        try {
            // Ejecutamos el procedimiento para guardar la bitacora de solicitudes
            DB::statement('exec sp_storeBitacoraSolicitudes ?,?,?,?,?', [
                $solicitudCambiosId,
                $section,
                $previousChange,
                $newChange,
                $userId
            ]);

            // Regresamos la respuesta para validar que se realizó la operación
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un error al realizar la operación";
        }
    }

    /**
     * Función para guardar el cambio de si una oportunidad es trabajo repetitiva
     * 
     * @param Request $request
     * @return mixed
     */
    public function setRepetitivo(Request $request)
    {
        try {
            // Realizamos el guardado del cambio
            DB::statement('exec sp_updateRepetitivo ?,?', [
                $request->idOportunidad,
                $request->isRepetitivo,
            ]);

            // Regresamos la respuesta para validar que se realizó la operación
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al realizar la operación";
        }
    }

    /**
     * Función para liberar una oportunidad y continuar con el flujo de la oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function liberarOportunidad(Request $request)
    {
        try {
            $idUsuario = $this->idUsuario();

            $oportunidad_plantilla = v_Oportunidades::where('idOportunidad', $request->idOportunidad)->first();
            
            $fecha = new DateTime();
            $fecha = $fecha->format('d-m-Y H:i:s');

            if ($request->tipo == 0) {
                // Realizamos la liberación del ticket
                DB::statement('exec sp_updateOportunidadLiberada ?,?,?,?', [
                    $request->idOportunidad,
                    $request->tipo,
                    $request->motivo,
                    $idUsuario,
                ]);

                // ================================== ENVIA CORREO ===============================================

                $folio = $oportunidad_plantilla->idOportunidad;
                $alertasPlantillas = catPlantillasAlertas::get();
                $estatus = $alertasPlantillas[20]->ValorCampo;
                $nombre_oportunidad = $oportunidad_plantilla->nomOportunidad;
                $fecha_oportunidad = Carbon::parse($oportunidad_plantilla->fecCreacion)->format('d/m/Y');
                $estatus_oportunidad = $estatus;
                $subject = $alertasPlantillas[20]->Asunto;
                $mensaje = $alertasPlantillas[20]->Mensaje;
                $nombre_agente = $oportunidad_plantilla->Agente;
                $route = "oportunidades";
                $nota = "";
                // $cuenta_usuario = $alertasPlantillas[20]->Destinatario;
                $cuenta_usuario = $oportunidad_plantilla->CorreoAgente;
                $cuenta_cc1 = $alertasPlantillas[20]->Destinatario;
                $cuenta_cc2 = $alertasPlantillas[20]->CopiarA;
                if ($oportunidad_plantilla->CorreoCustomer == null) {
                    $cuenta_cc3 = $cuenta_usuario;
                } else {
                    $cuenta_cc3 = $oportunidad_plantilla->CorreoCustomer;
                }
                $cuenta_cco = $alertasPlantillas[20]->CCO;
                $cuenta_auxiliar1 = $oportunidad_plantilla->CorreoAuxiliar;
                $cuenta_auxiliar2 = $oportunidad_plantilla->CorreoAuxiliar2;
                $cuenta_auxiliar3 = $oportunidad_plantilla->CorreoAuxiliar3;
                $cuenta_auxiliar4 = $oportunidad_plantilla->CorreoAuxiliar4;
                $fecha_solicitud = "";
                $fecha_entrega = "";
                $fecha_asignacion = "";
                $fecha_aut_archivo = "";
                $diasEntrega = null;
                $numOrdenMTC = null;

                $todosCorreos = [];
                $todosCorreos[] = $cuenta_cc1;
                $todosCorreos[] = $cuenta_cc2;
                $todosCorreos[] = $cuenta_cc3;
                $todosCorreos[] = $cuenta_auxiliar1;
                $todosCorreos[] = $cuenta_auxiliar2;
                $todosCorreos[] = $cuenta_auxiliar3;
                $todosCorreos[] = $cuenta_auxiliar4;

                // Eliminamos los correos duplicados, vacios y nulos
                $todosCorreos = array_unique(array_filter($todosCorreos));

                $this->storeBitacoraSolicitudes('Rechazó de oportunidad', null, "Rechazó de oportunidad por motivo de: " . $request->motivo, $idUsuario, $request->idOportunidad);
                
                // Enviamos el correo al agente
                Mail::to($cuenta_usuario)->cc($todosCorreos)->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[20], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                // pruebas
                // Mail::to('cgonzalez@litoprocess.com')->send(new SendMail($alertasPlantillas[20], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } else {
                // Realizamos la liberación de la oportunidad
                DB::statement('exec sp_updateOportunidadLiberada ?,?,?,?', [
                    $request->idOportunidad,
                    $request->tipo,
                    $request->motivo,
                    $idUsuario,
                ]);

                // Guardamos la bitacora de la liberación de la oportunidad
                $this->storeBitacoraSolicitudes('Liberación de oportunidad', null, "Liberación de la oportunidad", $idUsuario, $request->idOportunidad);
            }

            // Regresamos la respuesta para validar que se realizó la operación
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al realizar la operación";
        }
    }

    /**
     * Función para obtener la nota de rechazó de un presupuesto
     * 
     * @param Request $request
     * @return mixed
     */
    public function getNotaRechazoPresupuesto(Request $request)
    {
        try {
            // Obtenemos la nota de rechazó del presupuesto
            $motivo = DB::table('motivo_rechazo_presupuestos')->where('documento_id', $request->id_documento)->first()->motivo;

            // Regresamos la respuesta
            return response()->json([
                'response' => true,
                'motivo' => $motivo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => "Lo sentimos, ocurrió un problema al obtener la nota de rechazó"
            ]);
        }
    }
    
    /**
     * Función para obtener la última nota de rechazó de un presupuesto
     * 
     * @param Request $request
     * @return mixed
     */
    public function getUltimaNotaRechazoPresupuesto(Request $request)
    {
        try {
            $ultimoPresupuestoRechazado = DB::table('oportunidades')->where('idOportunidad', $request->idOportunidad)
                ->value('ult_presupuesto_id');
            
            // Obtenemos la nota de rechazó del presupuesto
            $motivo = DB::table('motivo_rechazo_presupuestos')->where('documento_id', $ultimoPresupuestoRechazado)->first()->motivo;

            // Regresamos la respuesta
            return response()->json([
                'response' => true,
                'motivo' => $motivo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => "Lo sentimos, ocurrió un problema al obtener la nota de rechazó"
            ]);
        }
    }

    public function cambiarAListoParaCotizar($idOportunidad)
    {
        $oportunidad = Oportunidades::where('idOportunidad', $idOportunidad)
                        ->first();

        if ($oportunidad) {
            $fecha = new \DateTime();
            $id_estatus = 3;
            $idUsuario = $this->idUsuario();

            DB::table('Oportunidades')
                ->where('idOportunidad', $oportunidad->idOportunidad)
                ->update([
                    'idEstatus' => $id_estatus, 
                    'fecUltimoCambio' => $fecha->format('d-m-Y H:i:s'), 
                    'idUsuarioUltCambio' => $idUsuario,

                    'fecListoCotizar' => $fecha->format('d-m-Y H:i:s'),

                    'fecRevisionTec' => null,
                    'fecCotizando' => null,
                    'fecRevision' => null,
                    'fecEsperaVobo' => null,
                    'fecListoPresupuesto' => null,

                    'fecPresupTerminado' => null,
                    'fecResultadoF' => null,
                    'fecOrdenConcluida' => null,
                ]);

            // Guardamos en la bitacora de fechas
            $this->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 3, true);

            return response()->json([
                'response' => true,
                'message' => "Oportunidad actualizada a 'Listo para Cotizar'"
            ]);
        }
    }

    /**
     * Función para obtener el listado de relaciones de cambios menores de acuerdo al id de la oportunidad
     * 
     * @param int|null $idOportunidad
     * @return mixed
     */
    public function getListRelationsCambiosMenores($idOportunidad = null)
    {
        try {
            $listado = DB::table('v_OportunidadCambiosMinimos')
                            ->where('idOportunidad', $idOportunidad)
                            ->where('estatus', 1)
                            ->orderBy('cambio', 'asc')
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
     * Función para guardar los cambios mínimos de precios de la oportunidad
     *
     * @param Request $request
     * @return mixed
     */
    public function guardarCambiosMinimos(Request $request)
    {
        $cambios = json_decode($request->input('cambios'), true);
        $idOportunidad = $request->input('idOportunidad');

        try {
            foreach ($cambios as $key => $value) {
                $elemento = OportunidadCambiosMinimos::findOrFail($key);
                $elemento->valor = $value['cambio_minimo'];
                $elemento->realizar = 1;
                $elemento->realizado = 0;
                $elemento->save();

                // Registramos la bitácora de marcado como realizado
                $this->storeBitacoraCambiosMinimos(
                    $elemento->idOportunidadCambiosMinimos,
                    'Cambio mínimo guardado',
                    null,
                    "Cambio mínimo guardado: " . $elemento->cambio,
                    $this->idUsuario()
                );
            }

            DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->update(['cambiosMinimos' => 1]);

            (new OportunidadesController())->cambiarAListoParaCotizar($idOportunidad);

            return response()->json([
                'response' => true,
                'message' => 'Cambios mínimos guardados correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Cambios mínimos no guardados correctamente',
            ], 500);
        }
    }

        /**
     * Función para marcar como realizado el cambio de precios de un elemento presupuestado.
     * 
     * @param Request $request
     * @return mixed
     */
    public function marcarRealizadoCM(Request $request)
    {
        $id = $request->input('id');

        try {
            $elemento = OportunidadCambiosMinimos::findOrFail($id);
            $elemento->realizado = 1;
            $elemento->save();

            // Validamos si ya no hay más elementos relacionados a esta oportunidad
            $oportunidadId = $elemento->idOportunidad;

            $elementosRelacionados = DB::table('v_OportunidadCambiosMinimos')->where('idOportunidad', $oportunidadId)
                ->where('estatus', 1)
                ->where('realizado', 0)
                ->count();

            if ($elementosRelacionados === 0) {
                DB::table('Oportunidades')
                    ->where('idOportunidad', $oportunidadId)
                    ->update(['cambiosMinimos' => 0]);
            } else {
                DB::table('Oportunidades')
                ->where('idOportunidad', $oportunidadId)
                ->update(['cambiosMinimos' => 1]);
            }

            // Registramos la bitácora de marcado como realizado
            $this->storeBitacoraCambiosMinimos(
                $elemento->idOportunidadCambiosMinimos,
                'Marcado como realizado',
                null,
                "Cambio mínimo marcado como realizado: " . $elemento->cambio,
                $this->idUsuario()
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
     * Función para rechazar una oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function rechazarOportunidad(Request $request)
    {
        $id = $request->input('idOportunidad');
        $motivo = json_decode($request->input('motivo'), true);
        $idUsuario = $this->idUsuario();

        $motivoId = $motivo['motivoId'];
        $motivoTxt = $motivo['motivoTxt'];

        $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 25)->first();

        try {
            $oportunidad = Oportunidades::findOrFail($id);
            $oportunidad->idEstatus = 1;
            $oportunidad->fecSolCotizacion = null;
            $oportunidad->fecListoCotizar = null;
            $oportunidad->fecCotizando = null;
            $oportunidad->save();

            DB::table('OportMotivoRechazo')->insert([
                'motivo_id' => $motivoId,
                'oportunidad_id' => $oportunidad->idOportunidad,
            ]);

            DB::table('motivosRechazoOportunidades')->insert([
                'motivo' => $motivoTxt,
                'oportunidad_id' => $oportunidad->idOportunidad,
                'usuario_id' => $idUsuario
            ]);

            // Registramos la bitácora de rechazo
            $this->bitacora('Rechazó oportunidad', 'Rechazar ', 'Oportunidad ', $oportunidad->idOportunidad, $this->loginUsuario(), 9);

            if ($alertasPlantilla != null) {
                $vendedorCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->first();

                if ($vendedorCorreo != null) {
                    $correoAgente = $vendedorCorreo->Correo;
                    $nombre_oportunidad = $oportunidad->nomOportunidad;
                    $folio = $oportunidad->idOportunidad;
                    $fecha_oportunidad = $oportunidad->fecCreacion;
                    $estatus_oportunidad = $alertasPlantilla->ValorCampo;
                    $subject = $alertasPlantilla->Asunto;
                    $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                    $route = "oportunidades";
                    $nota = $request->motivo;
                    $fecha_solicitud = $oportunidad->fecSolCotizacion;
                    $fecha_asignacion = $oportunidad->fecListoCotizar;
                    $fecha_entrega = $oportunidad->fecCotizando;
                    $fecha_aut_archivo = $oportunidad->fecRevision;
                    $diasEntrega = null;
                    $mensaje = 'Se notifica que una oportunidad ha sido rechazada, por el motivo: ' . $motivoTxt;
                    $numOrdenMTC = $oportunidad->ops;
                    $rejectDocument = 1;

                    // Obtenemos los auxiliares de correo
                    $auxiliares = DB::table('rel_Agente_Aux')
                        ->where('idAgente', $oportunidad->idAgente)
                        ->first();

                    if ($auxiliares) {
                        $todosCorreos = DB::table('v_catUsuarios')
                            ->whereIn('Id_Usuario', [
                                $auxiliares->idAuxiliar ?? 0,
                                $auxiliares->idAuxiliar2 ?? 0,
                                $auxiliares->idAuxiliar3 ?? 0,
                                $auxiliares->idAuxiliar4 ?? 0,
                            ])
                            ->pluck('Correo')
                            ->toArray();
                    } else {
                        $todosCorreos = [];
                    }

                    // Enviamos el correo al cotizador
                    Mail::to($correoAgente)
                    ->cc(array_merge([$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA], $todosCorreos))
                    ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));

                    // Pruebas
                    // Mail::to('cgonzalez@litoprocess.com')->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
                }
            }

            return response()->json([
                'response' => true,
                'message' => 'Oportunidad rechazada correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al rechazar la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para obtener una oportunidad por su id y para presupuesto
     * 
     * @param $idOportunidad
     * @return mixed
     */
    public function getOportunidadParaPresupuesto($idOportunidad)
    {
        return DB::table('v_OportunidadPresupuesto')
                ->where('idOportunidad', $idOportunidad)
                ->first();
    }

    /**
     * Función para obtener una respuesta por CURL
     * 
     * @param $idDocumento
     * @return mixed
     */
    public function getDataCurl($idDocumento)
    {
        // Conexión con el ws para obtener la información de acuerdo al archivo que se pasa
        $endpoint = config('ws_mv.url');

        // Iniciamos el CURL
        $curl = curl_init();

        // prod
        $url = ($endpoint . $idDocumento);

        // if (env('APP_MODE') === '/crmlitotest') {
        //     $url = ($endpoint . $idDocumento) . '?test';
        // } else {
        //     $url = ($endpoint . $idDocumento);
        // }

        // Realizamos la petición al endpoint para obtener la información
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        // Obtenemos la respuesta de la consulta de la API
        $response = curl_exec($curl);

        // Cerramos la consulta
        curl_close($curl);
        
        return $response;
    }

    /**
     * Función para obtener las cantidades ya resumidas de una tripa
     * 
     * @param Request $request
     * @return mixed
     */
    public function obtenerCantidadesTripa(Request $request)
    {
        try {
            // Obtenemos la data del api de lector de tripas
            $data = $this->getDataCurl($request->idDocumento);
            
            // Validamos que haya data
            if (!$data) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se pudo obtener la información del documento.'
                ], 500);
            }
    
            // Decodificamos la data obtenida
            $data = json_decode($data, true);
    
            // Obtenemos las partidas del documento
            $partidas = $data['partidas'] ?? [];
    
            $todasLasCantidades = [];
    
            // Recorremos las partidas para obtener las cantidades
            foreach ($partidas as $partida) {
                $cantidad = $partida['cantidad'] ?? 0;
    
                // Las vamos guardando en el array de cantidades resumidas
                $todasLasCantidades[] = $cantidad;
            }

            return response()->json([
                'response' => true,
                'cantidades' => $todasLasCantidades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Ocurrió un error al procesar la información.'
            ], 500);
        }
    }

    /**
     * Función para solicitar los cambios de una oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function solicitarCambiosOportunidad(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');
            
            $oportunidad = Oportunidades::where('idOportunidad', $idOportunidad)->first();

            if ($oportunidad) {
                // Obtenemos los EP con solicitud de cambio
                $epsConSolicitudCambio = SolicitudCotizacion::where('oportunidad_id', $idOportunidad)
                    ->where(function ($query) {
                        $query->where('tiene_cambio_especificaciones', 1)
                            ->orWhere('tiene_cambios_minimos', 1);
                    })
                    ->get();

                $prioridadPredominante = $epsConSolicitudCambio->max('cambio_prioridad');

                if ($prioridadPredominante == 2) {
                    $oportunidad->flagCambioEspecificaciones = 1; // Estatus para cambio de especificaciones
                } elseif ($prioridadPredominante == 1) {
                    $oportunidad->cambiosMinimos = 1; // Estatus para cambios mínimos
                }

                $oportunidad->preSolicitoCambios = 0;
                $oportunidad->solicitoCambios = 1;
                $oportunidad->save();

                $this->cambiarAListoParaCotizar($idOportunidad);

                $this->storeBitacoraSolicitudes(
                    'Solicitud de cambios en oportunidad',
                    null,
                    'Se ha solicitado cambios en la oportunidad',
                    $this->idUsuario(),
                    $idOportunidad
                );

                // Regresamos la respuesta json
                return response()->json([
                    'response' => true
                ]);
            }

            return response()->json([
                'response' => false,
                'message' => 'Oportunidad no encontrada'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Ocurrió un error al solicitar los cambios en la oportunidad'
            ], 500);
        }
    }
}
