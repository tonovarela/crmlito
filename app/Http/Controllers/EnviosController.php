<?php

namespace App\Http\Controllers;

use App\catEquipos;
use App\catOportMotivoPerdida;
use App\catOportTipos;
use App\Http\Modelos\catOportEstatusEnv;
use App\Http\Modelos\catOportUnidadEnv;
use App\Http\Modelos\catPlantillasAlertas;
use App\Http\Modelos\catTipoDocumentoOportEnv;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\DocumentosOportEnv;
use App\Http\Modelos\EncuestaEnvios;
use App\Http\Modelos\OportunidadesEnv;
use App\Http\Modelos\relOportEnv_Documento;
use App\Http\Modelos\RetencionOportunidadesEnv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\PermisosTrait;
use App\Http\Modelos\v_Envios;
use App\Http\Modelos\v_ListadoOportunidades;
use App\Http\Modelos\v_ordenesMetrics;
use App\Mail\AsignacionCotizacion;
use App\Mail\SendMail;
use App\Traits\BitacoraTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

class EnviosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    private $opcConSeguro = 0;
    private $opcConfirmarEnvio = 0;
    private $opcMasEconomico = 0;
    private $opcMasRapido = 0;
    private $muestra = 0;
    private $id_estatus = 0;
    private $modulo = 'Envios';
    private $moduloBitacora = 'Oportunidades Envíos';

    public function index()
    {
        $this->permisos();

        $permisos = $this->permisosModulo($this->modulo);

        $permisos_count = count($permisos);
        $Login = $this->loginUsuario();

        if ($this->loginUsuario() == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
            $datos_usuario = DB::select('exec GetUsuario @Login=:Login', ['Login' => $this->loginUsuario()]);

            $id_usuario = $datos_usuario[0]->Id_Usuario;

            $nombre_usuario = $datos_usuario[0]->Nombre;

            $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $this->loginUsuario());

            $agente_id = 0;
            $idEquipo_idAgente = 0;

            $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);

            $rol = $this->idRol();

            switch ($rol) {
                case '4':
                    $equipos;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();
                    break;
                case '9':
                    $agente_id = $agentes[0]->idAgente;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();
                    break;
                case '3':
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                    break;
                case '1012':
                case '1015':
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                    // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', 'like', '%volks%')->get();
                    // $combo_clientes_all = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                    break;
                default:
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                    break;
            }

            $folio = DB::table('OportunidadesEnv')->max('Folio') + 1;
            $folio_envio = '';
            $estatus = catOportEstatusEnv::all();
            $unidad_medida = catOportUnidadEnv::all();
            $tipos = catOportTipos::all();
            $oportunidades = v_ListadoOportunidades::orderBy('nomOportunidad', 'asc')->get();

            $motivoPerdida = catOportMotivoPerdida::all();

            for ($i = 0; $i < $permisos_count; $i++) {

                $crearEnv = session('crearEnv');
                $editarEnv = session('editarEnv');
                $editarEnvios = session('editarEnvios');

                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('oportunidades_envios.index', compact('crearEnv', 'editarEnv', 'editarEnvios', 'combo_clientes', 'estatus', 'folio', 'folio_envio', 'tipos', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'unidad_medida', 'nombre_usuario', 'agentes', 'oportunidades', 'agente_id', 'rol'));
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
    }

    public function getClientes(Request $request)
    {
        $datos_usuario = DB::select('exec GetUsuario @Login=:Login', ['Login' => $this->loginUsuario()]);

        $id_usuario = $datos_usuario[0]->Id_Usuario;

        $rol = $this->idRol();
        $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $this->loginUsuario());

        switch ($rol) {
            case '4':
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();
                break;
            case '9':
                $agente_id = $agentes[0]->idAgente;

                if ($agentes[0]->idAuxiliar == 29) {
                    $combo_clientes = Clientes::where('Estatus', 'ALTA')->where('idAgente', $agente_id)->orWhere('idAgente', 9)->orderBy('razonsocial', 'asc')->get();
                } else {
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();
                }

                break;
            case '3':
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                break;
            case '1012':
            case '1015':
                $combo_clientes = DB::select('exec GetClientesCombo');

                // if ($request->id == 0) {
                //     $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', 'like', '%volks%')->get();
                // } else {
                //     $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                // }
                break;
            default:
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                break;
        }

        return $combo_clientes;
    }


    public function create()
    {
    }

    public function store(Request $request)
    {
        // return $request;
        $this->configMail();

        $fecha = new \DateTime();

        if ($request->opcConSeguro === 'on') {
            $this->opcConSeguro = 1;
        }

        if ($request->opcConfirmarEnvio === 'on') {
            $this->opcConfirmarEnvio = 1;
        }

        if ($request->opcMasEconomico === 'on') {
            $this->opcMasEconomico = 1;
        }

        if ($request->opcMasRapido === 'on') {
            $this->opcMasRapido = 1;
        }

        if ($request->muestra === 'on') {
            $this->muestra = 1;
            $this->id_estatus = 2;
        } else {
            $this->muestra = 0;
            $this->id_estatus = 1;
        }

        $idOportunidad = null;

        if ($request['idOportunidadRelacion'] == "Oportunidad") {
            $idOportunidad;
        } else {
            $idOportunidad = $request['idOportunidadRelacion'];
        }

        $folio = DB::table('OportunidadesEnv')->max('Folio') + 1;

        $data = [
            'fecCreacion' => $fecha->format('d-m-Y H:i:s'),
            'idAgente' => $request['idAgente'],
            'idCliente' => $request['idCliente'],
            'idEquipo' => $request['idEquipo'],
            'idEstatus' => $this->id_estatus,
            'idtipo' => $request['idtipo'],
            'idUnidadPeso' => $request['idUnidadPeso'],
            'idUsuarioCreacion' => $request['idUsuario'],
            'nomOportunidad' => $request['nomOportunidad'],
            'pesoPieza' => $request['pesoPieza'],
            'tamPieza' => $request['tamPieza'],
            'totDestinos' => $request['totDestinos'],
            'opcConSeguro' => $this->opcConSeguro,
            'opcConfirmarEnvio' => $this->opcConfirmarEnvio,
            'opcMasEconomico' => $this->opcMasEconomico,
            'opcMasRapido' => $this->opcMasRapido,
            'idOportunidadRelacion' => $idOportunidad,
            'Folio' => $folio,
            'muestra' => $this->muestra
        ];

        OportunidadesEnv::create($data);

        if ($this->muestra == 1) {

            $folio_nuevo = DB::table('OportunidadesEnv')->max('Folio');

            $oportunidad = v_Envios::where('folio', $folio_nuevo)->first();

            $estatus_crece = "SolicitudCotizacion";

            $alertasPlantillas = catPlantillasAlertas::get();

            $nombre_oportunidad = $oportunidad->nomOportunidad;

            $fecha_oportunidad = Carbon::parse($oportunidad->fecCreacion)->format('d/m/Y');

            $estatus_oportunidad = $estatus_crece;

            $subject = $alertasPlantillas[9]->Asunto;

            $mensaje = $alertasPlantillas[9]->Mensaje;

            $nombre_agente = $oportunidad->Agente;

            $cuenta_cc = $oportunidad->Correo;

            $route = "envios";

            $nota = "";

            $cuenta_usuario = $alertasPlantillas[9]->Destinatario;
            $cuenta_cc2 = $alertasPlantillas[9]->CopiarA;
            $cuenta_cc3 = $oportunidad->correo_customer;
            $cuenta_cc4 = 'cgonzalez@litoprocess.com';
            $cuenta_cc5 = 'envios@litoprocess.com';
            $cuenta_auxiliar1 = $oportunidad->correo_auxiliar;
            $cuenta_auxiliar2 = $oportunidad->CorreoAuxiliar2;
            $cuenta_auxiliar3 = $oportunidad->CorreoAuxiliar3;
            $cuenta_auxiliar4 = $oportunidad->CorreoAuxiliar4;

            $fecha_solicitud = "";
            $fecha_entrega = "";
            $fecha_asignacion = "";
            $fecha_aut_archivo = "";
            $diasEntrega = null;
            $numOrdenMTC = null;

            if ($cuenta_cc3 == null && $cuenta_auxiliar1 == null) {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_cc4, $cuenta_cc5])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } elseif ($cuenta_cc3 == null && $cuenta_auxiliar1 != null) {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar1])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } elseif ($cuenta_auxiliar1 == null) {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } else if ($cuenta_auxiliar2 == null) {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } else if ($cuenta_auxiliar3 == null) {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar2])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } else if ($cuenta_auxiliar4 == null) {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar2, $cuenta_auxiliar3])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            } else {
                Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
            }
        }

        $this->bitacora($this->moduloBitacora, 'Agregar ', 'Oportunidad Envío ', $folio, $this->loginUsuario(), 5);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $oportunidad = v_Envios::find($id);

        return $oportunidad;
    }

    public function update(Request $request, $id)
    {

        $this->configMail();

        $fecha = new \DateTime();

        $oportunidad_Envio = OportunidadesEnv::where('idOportunidad', '=', $id)->first();

        $opcMasEconomico = $oportunidad_Envio->opcMasEconomico;
        $opcMasRapido = $oportunidad_Envio->opcMasRapido;
        $opcConSeguro = $oportunidad_Envio->opcConSeguro;
        $muestra = $oportunidad_Envio->muestra;

        $data_encuesta = [];

        $fecSolCotizacion = $oportunidad_Envio->fecSolCotizacion;
        $fecResultadoF = $oportunidad_Envio->fecResultadoF;
        $fecCotTerminada = $oportunidad_Envio->fecCotTerminada;

        if ($request['idOportunidadRelacion'] == "Oportunidad") {
            $idOportunidad = null;
        } else {
            $idOportunidad = $request['idOportunidadRelacion'];
        }

        if ($request->idStatus == '2') {

            $estatus_crece = "SolicitudCotizacion";

            $folio = $id;

            $oportunidad = v_Envios::where('idOportunidad', $id)->first();

            if ($oportunidad->idEstatus !== '2') {

                $alertasPlantillas = catPlantillasAlertas::get();

                $nombre_oportunidad = $oportunidad->nomOportunidad;

                $fecha_oportunidad = Carbon::parse($oportunidad->fecCreacion)->format('d/m/Y');

                $estatus_oportunidad = $estatus_crece;

                $subject = $alertasPlantillas[9]->Asunto;

                $mensaje = $alertasPlantillas[9]->Mensaje;

                $nombre_agente = $oportunidad->Agente;

                $cuenta_cc = $oportunidad->Correo;

                $route = "envios";

                $nota = "";

                $cuenta_usuario = $alertasPlantillas[9]->Destinatario;
                $cuenta_cc2 = $alertasPlantillas[9]->CopiarA;
                if ($oportunidad->correo_customer == null) {
                    $cuenta_cc3 = $alertasPlantillas[9]->Destinatario;
                } else {
                    $cuenta_cc3 = $oportunidad->correo_customer;
                }
                
                $cuenta_cc4 = 'cgonzalez@litoprocess.com';
                $cuenta_cc5 = 'envios@litoprocess.com';
                $cuenta_auxiliar1 = $oportunidad->correo_auxiliar;
                $cuenta_auxiliar2 = $oportunidad->CorreoAuxiliar2;
                $cuenta_auxiliar3 = $oportunidad->CorreoAuxiliar3;
                $cuenta_auxiliar4 = $oportunidad->CorreoAuxiliar4;

                $fecha_solicitud = "";
                $fecha_entrega = "";
                $fecha_asignacion = "";
                $fecha_aut_archivo = "";
                $diasEntrega = null;
                $numOrdenMTC = null;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar2])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar2, $cuenta_auxiliar3])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_cc2, $cuenta_auxiliar1, $cuenta_cc3, $cuenta_cc4, $cuenta_cc5, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4])->send(new SendMail($alertasPlantillas[9], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
            }


            if ($oportunidad_Envio->fecSolCotizacion == null) {
                $fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad_Envio->fecListoCotizar != null) {
                $fecListoCotizar = null;
            }
            if ($oportunidad_Envio->fecCotizando != null) {
                $fecCotizando = null;
            }
            if ($oportunidad_Envio->fecEsperaVobo != null) {
                $fecEsperaVobo = null;
            }
            if ($oportunidad_Envio->fecCotTerminada != null) {
                $fecCotTerminada = null;
            }
            if ($oportunidad_Envio->fecResultadoF != null) {
                $fecResultadoF = null;
            }
        }

        if ($request->idStatus == '6') {

            $estatus_crece = "CotEnvioTerminada";

            if ($oportunidad_Envio->fecSolCotizacion == null) {
                $fecSolCotizacion;
            }
            if ($oportunidad_Envio->fecCotTerminada == null) {
                $fecCotTerminada = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad_Envio->fecResultadoF != null) {
                $fecResultadoF = null;
            }
        }

        if ($request->idStatus == '7') {

            $estatus_crece = "CotAutorizada";

            if ($request['servAdecuado'] == "SI") {
                $request['servAdecuadoNoOpciones'] = null;
            }

            $data_encuesta = [
                'idOportunidadEnv' => (int)$id,
                'servicioAdecuado' => $request['servAdecuado'],
                'numFactura' => $request['numFactura'],
                'importeFactura' => $request['importeFactura'],
                'opcionNo' => $request['servAdecuadoNoOpciones']
            ];

            EncuestaEnvios::create($data_encuesta);

            DB::statement(
                'exec spUpdateCrece ?, ?, ?, ?',
                [
                    $id,
                    $request->idCotizacionAutorizada,
                    true,
                    strtoupper($request->numOrdenMTC)
                ]
            );

            if ($oportunidad_Envio->fecSolCotizacion == null) {
                $fecSolCotizacion;
            }
            if ($oportunidad_Envio->fecCotTerminada == null) {
                $fecCotTerminada;
            }
            if ($oportunidad_Envio->fecResultadoF == null) {
                $fecResultadoF = $fecha->format('d-m-Y H:i:s');
            }

            $listCorreos = DB::table('catCorreosEnvios')->where('activo', 1)->get()->pluck('correo')->toArray();

            $opEnv = DB::table('OportunidadesEnv')->where('idOportunidad', $id)->first();

            Mail::to('envios@litoprocess.com')->cc($listCorreos)->send(new AsignacionCotizacion($opEnv->Folio, $opEnv->nomOportunidad, $request->idCotizacionAutorizada));
        }

        if ($request->idStatus == '8') {

            $estatus_crece = "Cancelado";

            if ($oportunidad_Envio->fecSolCotizacion == null) {
                $fecSolCotizacion;
            }
            if ($oportunidad_Envio->fecCotTerminada == null) {
                $fecCotTerminada;
            }
            if ($oportunidad_Envio->fecResultadoF == null) {
                $fecResultadoF = $fecha->format('d-m-Y H:i:s');
            }
        }

        if ($request->opcMasEconomico === 'on') {
            $opcMasEconomico = 1;
        } else {
            $opcMasEconomico = 0;
        }

        if ($request->opcMasRapido === 'on') {
            $opcMasRapido = 1;
        } else {
            $opcMasRapido = 0;
        }

        if ($request->opcConSeguro === 'on') {
            $opcConSeguro = 1;
        } else {
            $opcConSeguro = 0;
        }

        if ($request->muestra === 'on') {
            $muestra = 1;
        } else {
            $muestra = 0;
        }

        if ($request->opcConfirmarEnvio === 'on') {
            $opcConfirmarEnvio = 1;
        } else {
            $opcConfirmarEnvio = 0;
        }

        if ($request->Retencion == 'on') {
            $retenida = 1;
        } else {
            $retenida = 0;
        }

        $data = [
            'fecUltimoCambio' => $fecha->format('d-m-Y H:i:s'),
            'idAgente' => $request['idAgente'],
            'idCliente' => $request['idCliente'],
            'idEquipo' => $request['idEquipo'],
            'idEstatus' => $request->idStatus,
            'idtipo' => $request['idtipo'],
            'idUnidadPeso' => $request['idUnidadPeso'],
            'idUsuarioUltCambio' => $request['idUsuario'],
            'nomOportunidad' => $request['nomOportunidad'],
            'pesoPieza' => $request['pesoPieza'],
            'tamPieza' => $request['tamPieza'],
            'totDestinos' => $request['totDestinos'],
            'opcConSeguro' => $opcConSeguro,
            'opcConfirmarEnvio' => $opcConfirmarEnvio,
            'opcMasEconomico' => $opcMasEconomico,
            'opcMasRapido' => $opcMasRapido,
            'notaRetencion' => $request['notaRetencion'],
            'fecSolCotizacion' => $fecSolCotizacion,
            'fecResultadoF' => $fecResultadoF,
            'fecCotTerminada' => $fecCotTerminada,
            'idCotizacionAutorizada' => $request['idCotizacionAutorizada'],
            'idOportunidadRelacion' => $idOportunidad,
            'retenida' => $retenida,
            'numOrdenMTC' => strtoupper($request['numOrdenMTC']),
            'muestra' => $muestra
        ];

        $retenciones = RetencionOportunidadesEnv::where('idOportunidad', '=', $id)->get();

        $retencion = $retenciones->last();

        $retencion_oportunidad = [];

        if ($request->Retencion == 'on') {
            if ($retencion == null) {
                $retencion_oportunidad = [
                    'idOportunidad' => $id,
                    'notaRetencion' => $request->notaRetencion,
                    'fecRetencionInicio' => $fecha->format('d-m-Y H:i:s'),
                    'fecRetencionFin' => null,
                    'idUsuario' => $request->idUsuario
                ];
                RetencionOportunidadesEnv::create($retencion_oportunidad);

                DB::statement('exec spUpdateCreceRetencion ?, ?, ?', [
                    $id,
                    'CotEnvioRetenida',
                    ''
                ]);
            } else {
                if ($retencion->fecRetencionFin != null) {
                    $retencion_oportunidad = [
                        'idOportunidad' => $id,
                        'notaRetencion' => $request->notaRetencion,
                        'fecRetencionInicio' => $fecha->format('d-m-Y H:i:s'),
                        'fecRetencionFin' => null,
                        'idUsuario' => $request->idUsuario
                    ];
                    RetencionOportunidadesEnv::create($retencion_oportunidad);

                    DB::statement('exec spUpdateCreceRetencion ?, ?, ?', [
                        $id,
                        'CotEnvioRetenida',
                        ''
                    ]);
                }
            }
        } else {
            if ($retencion != null) {
                if ($retencion->fecRetencionInicio != null) {
                    $retencion_oportunidad = [
                        'idOportunidad' => $id,
                        'notaRetencion' => $retencion->notaRetencion,
                        'fecRetencionInicio' => $retencion->fecRetencionInicio,
                        'fecRetencionFin' => $fecha->format('d-m-Y H:i:s'),
                        'idUsuario' => $request->idUsuario
                    ];
                    $retencion->update($retencion_oportunidad);

                    DB::statement('exec spUpdateCreceRetencion ?, ?, ?', [
                        $id,
                        $estatus_crece,
                        ''
                    ]);
                }
            }
        }

        $oportunidad_Envio->update($data);

        $this->bitacora($this->moduloBitacora, 'Editar ', 'Oportunidad Envío ', $id, $this->loginUsuario(), 2);
    }

    public function destroy($id)
    {
    }

    public function apiEnvios()
    {
        $Login = session('usuario');
        $permisos = $this->permisosModulo($this->modulo);

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        switch ($rol) {
            case "3":
                $Envios = DB::select('exec GetEnviosCustomer @Login=' . $Login);
                break;
            case "1016":
                // $Envios = DB::select('exec GetEnviosCustomer @Login=' . $Login);
                $Envios = DB::select('exec GetEnvios');
                break;
            case "4":
                // $Envios = v_Envios::where('Login', $Login)->limit(20000);
                $Envios = DB::table('v_Envios_Agentes')->where('Login', $Login);
                break;
            case "9":
                $Envios = DB::select('exec GetEnviosAuxiliar @Login=' . $Login);
                break;
            default:
                $Envios = DB::select('exec GetEnvios');
                break;
        }

        return DataTables::of($Envios)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editarEnv'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEditarEnvios', function () {
                $sesionEditarEnvios = trim(session('editarEnvios'));
                $inputEditarEnvios = $sesionEditarEnvios;

                return $inputEditarEnvios;
            })
            ->addColumn('inputEditarClientes', function () {
                $sesionEditarClientes = trim(session('editarClientes'));
                $inputEditarClientes = $sesionEditarClientes;

                return $inputEditarClientes;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminarEnv'));
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
            ->addColumn('importeFactura', function ($row) {
                return ($row->importeFactura ? number_format($row->importeFactura, 2) : null );
            })
            ->rawColumns(['inputEditar', 'importeFactura', 'inputEditarEnvios', 'inputEliminar', 'inputEditarClientes', 'inputEditarCliente', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function apiOportunidadesEnviosRetenidas($id)
    {
        if ($id == null) {
            $id = 1;
        }

        $Retenidas = DB::select('exec GetOportunidadEnvRetenida @idOportunidad=' . $id);

        return DataTables::of($Retenidas)->make(true);
    }

    public function apiEnvDocs(Request $request, $id)
    {
        $idOportunidad = $id;
        $Documentos = DB::select('exec sp_Documentos @evento="DocumentosOportEnv", @id=' . $idOportunidad);

        return DataTables::of($Documentos)
            ->make(true);
    }
    public function downdocsoport($id)
    {
        $result = DocumentosOportEnv::where('idDocumento', (int) $id)->first();
        $file = $result->Documento;
        $Tipo = $result->Extension;
        $Extencion = explode('/', $Tipo);
        $base64 = base64_decode($file);
        return Response::make($base64, 200, [
            'Content-Type' => $Tipo, 'Content-Disposition' => 'attachment; filename="' . $result->descDocumento . '"',
        ]);
    }
    public function deleteDocEnv(Request $request)
    {
        $idDocumentoEnvdelete = $request->idDocumentoEnvdelete;
        $idDocumentoEnvdelete = (int)$idDocumentoEnvdelete;
        DocumentosOportEnv::where('idDocumento', $idDocumentoEnvdelete)->delete();

        $documento = relOportEnv_Documento::where('idDocumento', $idDocumentoEnvdelete)->first();

        $this->bitacora($this->moduloBitacora, 'Eliminar Documento ' . $documento->descDocumento . ' Tipo Documento ' . $documento->descTipoDocumento, ' Oportunidad Envío ', $documento->idOportunidad, $this->loginUsuario(), 3);
    }

    public function updocOportEnv(Request $request)
    {
        $idUsuarioReg = (int)$this->idUsuario();
        $idOportunidad = $request->idOportunidadEnv;
        $idOportunidad = (int)$idOportunidad;
        $idTipoDocumento = $request->idTipoDocumento;
        $idTipoDocumento = (int)$idTipoDocumento;
        $file = $_FILES['file'];
        $Extencion = $file['type'];
        $Size = $file['size'];
        $descDocumento = $file['name'];
        $Documento = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

        DB::statement(" EXEC sp_updateEnvDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento", [
            ':descDocumento' => $descDocumento,
            ':Documento' => $Documento,
            ':Extencion' => $Extencion,
            ':Size' => $Size,
            ':idUsuarioReg' => $idUsuarioReg,
            ':idOportunidad' => $idOportunidad,
            ':idTipoDocumento' => $idTipoDocumento
        ]);

        $tipo_documento = catTipoDocumentoOportEnv::where('idTipoDocumento', $idTipoDocumento)->first();

        $this->bitacora($this->moduloBitacora, 'Agregar Documento ' . (string)$descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Oportunidad Envío ', $idOportunidad, $this->loginUsuario(), 5);
    }

    public function getOportunidades(Request $request)
    {
        $idCliente = $request->idCliente;
        $oportunidades_combo = DB::select('exec GetOportunidadesXClienteCombo @idCliente=' . $idCliente);

        return $oportunidades_combo;
    }

    public function getOportunidadesAsignadas(Request $request)
    {
        $idOportunidad = $request->idOportunidad;
        $oportunidades_asignadas = DB::select('exec GetOportunidadesAsignadas @idOportunidad=' . $idOportunidad);

        return $oportunidades_asignadas;
    }

    public function getFoliosCot(Request $request)
    {
        $idOportunidad = $request->idOportunidad;
        $folios_envios = DB::select('exec GetFolioCotApro @idOportunidadEnv=' . $idOportunidad);

        return $folios_envios;
    }

    public function obtenerOPsEnvios(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $autocomplate = v_ordenesMetrics::orderby('NumOrdem', 'asc')->select('NumOrdem')->get();
        } else {
            $autocomplate = v_ordenesMetrics::orderby('NumOrdem', 'asc')->select('NumOrdem')->where('NumOrdem', 'like', '%' . $search . '%')->get();
        }

        $response = array();
        foreach ($autocomplate as $autocomplate) {
            $response[] = array("value" => $autocomplate->NumOrdem, "label" => $autocomplate->NumOrdem);
        }

        echo json_encode($response);
        exit;
    }

    public function obtenerOMTCsEnvios(Request $request)
    {
        $search = $request->search;

        if ($search != '') {
            $autocomplate = v_ordenesMetrics::orderby('NumOrdem', 'asc')->select('NumOrdem')->where('NumOrdem', $search)->first();
        } else {
            $autocomplate = null;
        }

        return $autocomplate;
    }

    public function cancelarEnvio(Request $request)
    {


        $fecha = new \DateTime();

        DB::statement(
            'exec spCancelarEnvio ?, ?, ?, ?, ?',
            [
                $request->id_suario,
                $fecha->format('Y-m-d H:i:s'),
                true,
                $request->motivoCancelacion,
                $request->id
            ]
        );

        DB::statement('exec spUpdateCreceRetencion ?, ?, ?', [
            $request->id,
            'Cancelado',
            ''
        ]);

        $this->bitacora($this->moduloBitacora, 'Cancelar ', 'Oportunidad Envío ', $request->id, $this->loginUsuario(), 6);
    }

    public function infoEnvio(Request $request)
    {
        $encuesta_envio = DB::select('exec GetEnvioInfo @idOportunidadEnv=' . $request->id);

        $opciones_no = $request->servAdecuadoNoOpciones;
        if ($request->servAdecuado == "SI") {
            $opciones_no = null;
        }

        if ($encuesta_envio == null) {

            DB::statement('exec spAgregarInfoEnvio ?, ?, ?, ?, ?', [
                (int)$request->id,
                $request['servAdecuado'],
                $request['numFactura'],
                $request['importeFactura'],
                $opciones_no
            ]);
        } else {
            DB::statement('exec spActualizarInfoEnvio ?, ?, ?, ?, ?', [
                (int)$request->id,
                $request['servAdecuado'],
                $request['numFactura'],
                $request['importeFactura'],
                $opciones_no
            ]);
        }

        $this->bitacora($this->moduloBitacora, 'Editar ', 'Oportunidad Envío ', $request->id, $this->loginUsuario(), 2);
    }

    public function getDocumentoEnvio(Request $request)
    {
        $idDocumento = $request->idDocumento;

        $result = DocumentosOportEnv::where('idDocumento', (int) $idDocumento)->first();

        return $result;
    }

    public function getValorOportunidad(Request $request)
    {
        $idOport = $request->idOportunidad;

        $oportunidad_valor = DB::table('v_ValorOportunidad')->where('idOportunidad', $idOport)->get();

        if ($oportunidad_valor != null) {
            $valor = $oportunidad_valor[0]->Valor;
        } else {
            $valor = 0;
        }

        return $valor;
    }
}
