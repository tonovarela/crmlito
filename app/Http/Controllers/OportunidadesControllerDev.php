<?php

namespace App\Http\Controllers;

use App\catEquipos;
use App\catEstatus;
use App\catOportExclusion;
use App\catOportMotivoPerdida;
use App\catOportProbabilidad;
use App\catOportTipos;
use App\Http\Modelos\catPlantillasAlertas;
use App\Http\Modelos\catTipoDocumentoOport;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\DocumentosOport;
use App\Http\Modelos\DocumentosOportMail;
use App\Http\Modelos\EntregaSucursalesOportunidades;
use App\Http\Modelos\IntegrantesRoles;
use App\Http\Modelos\Oportunidades;
use App\Http\Modelos\relOport_Documento;
use App\Http\Modelos\relOport_ElementoP;
use App\Http\Modelos\RetencionOportunidades;
use App\Http\Modelos\Sucursales;
use App\Http\Modelos\TiempoEntregaOportunidades;
use App\Http\Modelos\v_ordenesMetricsAll;
use App\Mail\SendMail;
use App\Mail\SendMailDocumento;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use App\v_Oportunidades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Response;

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
        $idrol = $permisos[0]->idRol;

        for ($i = 0; $i < $permisos_count; $i++) {

            $crearOpor = session('crearOpor');
            $editarOpor = session('editarOpor');
            $editarOportunidades = session('EditarOportunidades');

            if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                return view('oportunidades.index', compact('crearOpor', 'editarOpor', 'editarOportunidades', 'combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'idrol'));
            } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                return redirect()->route('Dashboard');
            }
        }
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

        if ($Login == null) {
            return view('iniciar');
        }

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
            default:
                $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                $combo_vendedores = DB::select('exec GetAgentesCombo');
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
                break;
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
                return view('oportunidades.create', compact('combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'agente_id', 'idEquipo_idAgente'));
            } else {
                if ($permiso->descAccion == 'Crear' && $permiso->descNivelPerm == 'Todo') {
                    return view('oportunidades.create', compact('combo_clientes', 'estatus', 'folio', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'agente_id', 'idEquipo_idAgente'));
                } else if ($permiso->descAccion == 'Crear' && $permiso->descNivelPerm == 'Ninguno') {
                    return back();
                }
            }
        }
    }

    public function clonarOportunidad(Request $request)
    {
        $fecha = new \DateTime();
        $oportunidad = Oportunidades::where('idOportunidad', $request->idOportunidad)->first();
        $idEstatus = 1;
        $idAtendido = null;
        $valor = 0;
        $folio = DB::table('Oportunidades')->max('folio') + 1;
        // $folio = -1;
        // return $oportunidad;

        DB::statement(
            'exec spInsertOportunidad ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $oportunidad->idCliente,
                $oportunidad->nomOportunidad,
                $idEstatus,
                $oportunidad->idTipo,
                $oportunidad->idExclusion,
                $oportunidad->idProbabilidad,
                $oportunidad->idAgente,
                $oportunidad->Maquila,
                $valor,
                $fecha->format('d-m-Y H:i:s'),
                $oportunidad->idUsuarioCreacion,
                $idAtendido,
                $oportunidad->idEquipo,
                $fecha->format('d-m-Y H:i:s'),
                $folio,
            ]
        );

        $oportunidad_nueva = Oportunidades::where('folio', $folio)->first();
        $elemento_presupuestado = relOport_ElementoP::where('idOportunidad', $request->idOportunidad)->first();

        if (count($elemento_presupuestado) > 0) {
            DB::statement(
                'exec spInsertRelacionEPOportunidad ?, ?, ?, ?',
                [
                    $oportunidad_nueva->idOportunidad,
                    $elemento_presupuestado->idElementoP,
                    $elemento_presupuestado->idUsuarioRelaciona,
                    $fecha->format('d-m-Y H:i:s'),
                ]
            );
        }

        $this->bitacora($this->modulo, 'Clonar ', 'Oportunidad ', $folio, $this->loginUsuario(), 5);

        return redirect()->route('Oportunidades');
    }

    public function store(Request $request)
    {
        $fecha = new \DateTime();

        $maquila = '';

        if ($request->Maquila === 'on') {
            $maquila = $request->maquila;
        }

        $this->validate($request, [
            'idCliente' => 'required', 'nomOportunidad' => 'required'
        ]);

        $cliente = Clientes::where('idCliente', $request->idCliente)->first();
        $folio = DB::table('Oportunidades')->max('folio') + 1;

        DB::statement(
            'exec spInsertOportunidad ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $request->idCliente,
                $request->nomOportunidad,
                1,
                $request->idTipo,
                $request->idExclusion,
                $request->idProbabilidad,
                $request->idAgente,
                $maquila,
                0,
                $fecha->format('d-m-Y H:i:s'),
                $request->idUsuario,
                $request->idAtendido,
                $request->idEquipo,
                $request->fecPrevCiere,
                $folio,
            ]
        );

        $this->bitacora($this->modulo, 'Agregar ', 'Oportunidad ', $folio, $this->loginUsuario(), 5);

        return redirect()->route('Oportunidades');
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

        if ($Login == null) {
            return view('iniciar');
        }

        $permisos =  $this->permisosModulo($this->modulo);
        $id_usuario = $this->idUsuario();

        $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $Login);

        $oportunidades = DB::select('exec GetOportunidad @idOportunidad=' . $id);

        $idrol = $permisos[0]->idRol;

        if ($idrol == 3) {
            $oportunidades = DB::select('exec GetOportunidadXCustomer ?, ?', [$id, $Login]);
        }

        $oportunidad = $oportunidades;

        $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);

        $idEquipo_idAgente = $oportunidad[0]->id_Agente;

        $idEquipo = $oportunidad[0]->idEquipo;

        if ($agentes == null) {
            if ($equipos == null) {
                $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                $combo_vendedores = DB::select('exec GetAgentesCombo');
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();
            } else {
                $equipos;
                $idEquipo_idAgente = $oportunidad[0]->id_Agente;
                $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $idEquipo_idAgente);
                // $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                $combo_vendedores = DB::select('exec GetAgentesCombo');
                $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();
            }
        } else {
            $agente_id = $agentes[0]->idAgente;
            $idEquipo_idAgente = $oportunidad[0]->id_Agente;
            $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $idEquipo_idAgente);
            $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
            $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();
        }

        $estatus = catEstatus::all();
        $tipos = catOportTipos::all();
        $previsiones = catOportExclusion::all();
        $probabilidades = catOportProbabilidad::all();

        $id_Cliente_INT = $oportunidad[0]->ClienteINT;
        $combo_cotizadores = DB::select('exec GetCotizadoresCombo');
        $motivoPerdida = catOportMotivoPerdida::all();
        $sucursales = Sucursales::where('idClienteINT', $id_Cliente_INT)->get();
        $sucursales_combo_entregas = Sucursales::orderBy('Nombre', 'asc')->where('idClienteINT', $id_Cliente_INT)->where('Estatus', 'ALTA')->get();

        $total_cantidad = 0;

        $editarOportunidades = session('editarOportunidades');

        foreach ($permisos as $permiso) {
            if ($editarOportunidades == 'EditarOportunidades' || $idrol == 1016) {
                return view('oportunidades.edit', compact('oportunidad', 'combo_clientes', 'estatus', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'idrol', 'idEquipo_idAgente', 'sucursales', 'sucursales_combo_entregas', 'total_cantidad'));
            } else {
                if ($Login == $oportunidad[0]->Login || $Login == $oportunidad[0]->LoginAuxiliar || $Login == $oportunidad[0]->LoginCustomer) {
                    if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Todo') {
                        return view('oportunidades.edit', compact('oportunidad', 'combo_clientes', 'estatus', 'tipos', 'previsiones', 'probabilidades', 'equipos', 'combo_vendedores', 'motivoPerdida', 'id_usuario', 'combo_cotizadores', 'idrol', 'idEquipo_idAgente', 'sucursales', 'sucursales_combo_entregas', 'total_cantidad'));
                    } else if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Ninguno') {
                        return back();
                    }
                } else {
                    return back();
                }
            }
        }
    }

    public function editarOportunidad(Request $request)
    {
        $id = $request->idOportunidad;
        $oportunidades = v_Oportunidades::where('idOportunidad', $id)->first();

        return $oportunidades;
    }

    public function obtenerRol()
    {
        $idRol = $this->idRol();

        return $idRol;
    }
    
    public function obtenerTipoOportunidad()
    {
        $tiposOport = catOportTipos::all();

        return $tiposOport;
    }

    public function obtenerMotivoPerdida()
    {
        $motivoPerdidaOport = catOportMotivoPerdida::all();

        return $motivoPerdidaOport;
    }

    public function obtenerPrevision()
    {
        $previsionOport = catOportExclusion::all();

        return $previsionOport;
    }

    public function obtenerProbabilidad()
    {
        $probabilidad = catOportProbabilidad::all();

        return $probabilidad;
    }

    public function obtenerEquipos()
    {
        $id_usuario = $this->idUsuario();
        $Login = $this->loginUsuario();
        $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
        $permisos =  $this->permisosModulo($this->modulo);
        $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $Login);

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        switch ($rol) {
            case '4':
                $equipos;
                $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
                break;
            case '9':
                $agente_id = $agentes[0]->idAgente;
                $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
                break;
            case '3':
                $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                break;
            default:
                $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                break;
        }

        return $equipos;
    }

    public function update(Request $request, $id)
    {

        $fecha = new \DateTime();

        $oportunidad = Oportunidades::where('idOportunidad', '=', $id)->first();
        // return $oportunidad;

        if ($request->Retencion == 'on') {
            $oportunidad->notaRetencion = $request->notaRetencion;
        } else {
            if ($oportunidad->notaRetencion == null) {
                $oportunidad->notaRetencion = $request->notaRetencion;
            }
        }

        $id_estatus = 0;

        if ($request->idStatus == '2') {
            $id_estatus = 2;
            if ($oportunidad->fecSolCotizacion == null) {
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
            if ($oportunidad->fecEsperaVobo != null) {
                $oportunidad->fecEsperaVobo = null;
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

        if ($request->idStatus == '3') {
            $id_estatus = 3;
            if ($oportunidad->fecSolCotizacion == null) {
                $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoCotizar == null) {
                $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecCotizando != null) {
                $oportunidad->fecCotizando = null;
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
            if ($oportunidad->fecResultadoF != null) {
                $oportunidad->fecResultadoF = null;
            }
            if ($oportunidad->fecOrdenConcluida != null) {
                $oportunidad->fecOrdenConcluida = null;
            }
            $oportunidad->idEstatus = $id_estatus;
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
                $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
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
            if ($oportunidad->fecResultadoF != null) {
                $oportunidad->fecResultadoF = null;
            }
            if ($oportunidad->fecOrdenConcluida != null) {
                $oportunidad->fecOrdenConcluida = null;
            }
            $oportunidad->idEstatus = $id_estatus;
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
            if ($oportunidad->fecRevision == null) {
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
            if ($oportunidad->fecResultadoF != null) {
                $oportunidad->fecResultadoF = null;
            }
            if ($oportunidad->fecOrdenConcluida != null) {
                $oportunidad->fecOrdenConcluida = null;
            }
            $oportunidad->idEstatus = $id_estatus;
        }

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
            if ($oportunidad->fecRevision == null) {
                $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecEsperaVobo == null) {
                $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoPresupuesto == null) {
                $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
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
            if ($oportunidad->fecRevision == null) {
                $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecEsperaVobo == null) {
                $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoPresupuesto == null) {
                $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecPresupTerminado == null) {
                $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecResultadoF != null) {
                $oportunidad->fecResultadoF = null;
            }
            if ($oportunidad->fecOrdenConcluida != null) {
                $oportunidad->fecOrdenConcluida = null;
            }
            $oportunidad->idEstatus = $id_estatus;
        }

        if ($request->idStatus == '9') {
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
                $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoPresupuesto == null) {
                $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecPresupTerminado == null) {
                $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecResultadoF == null) {
                $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecOrdenConcluida != null) {
                $oportunidad->fecOrdenConcluida = null;
            }
            $oportunidad->idEstatus = $id_estatus;
            // $id_estatus = 9;
            // if ($oportunidad->fecSolCotizacion == null) {
            //     $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecListoCotizar == null) {
            //     $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecCotizando == null) {
            //     $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecRevision == null) {
            //     $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecEsperaVobo == null) {
            //     $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecListoPresupuesto == null) {
            //     $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecPresupTerminado == null) {
            //     $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecResultadoF == null) {
            //     $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
            // }
            // if ($oportunidad->fecOrdenConcluida != null) {
            //     $oportunidad->fecOrdenConcluida = null;
            // }
            // $oportunidad->idEstatus = $id_estatus;
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
            if ($oportunidad->fecRevision == null) {
                $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecEsperaVobo == null) {
                $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoPresupuesto == null) {
                $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecPresupTerminado == null) {
                $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
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
                $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoPresupuesto == null) {
                $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecPresupTerminado == null) {
                $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecResultadoF == null) {
                $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecOrdenConcluida != null) {
                $oportunidad->fecOrdenConcluida = null;
            }
            $oportunidad->idEstatus = $id_estatus;
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
                $oportunidad->fecEsperaVobo = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecListoPresupuesto == null) {
                $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecPresupTerminado == null) {
                $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecResultadoF == null) {
                $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
            }
            if ($oportunidad->fecResultadoF == null) {
                $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
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

            $cuenta_usuario = $oportunidad_plantilla->CorreoAgente;
            $cuenta_cc1 = $alertasPlantillas[3]->Destinatario;
            $cuenta_cc2 = $alertasPlantillas[3]->CopiarA;
            $cuenta_cc3 = $oportunidad_plantilla->CorreoCustomer;
            $cuenta_cco = $alertasPlantillas[3]->CCO;

            // explode(", ", $cuentas_cco)

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

        $folio = $oportunidad->idOportunidad;
        $this->bitacora($this->modulo, 'Editar ', 'Oportunidad ', $request->id, $this->loginUsuario(), 2);

        return redirect()->route('Oportunidades');
    }

    public function destroy($id)
    {
    }

    public function apiOportunidades()
    {

        // $oportunidadesAll = DB::table('Oportunidades')->select('id_Oportunidad');
        // $oportunidadesAll = Oportunidades::all();

        $Login = $this->loginUsuario();

        $permisos = $this->permisosModulo($this->modulo);

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        switch ($rol) {
            case "3":
                // $Oportunidades = DB::table('v_OportunidadesGridCustomers')->where('Login=', $Login);
                $Oportunidades = DB::select('exec GetOportunidadesXCustomer @Login=' . $Login);
                break;
            case "1016":
                // $Oportunidades = DB::table('v_OportunidadesGridCustomers')->where('Login=', $Login);
                $Oportunidades = DB::select('exec GetOportunidadesXCustomer @Login=' . $Login);
                break;
            case "4":
                // $Oportunidades = v_Oportunidades::where('Login', $Login)->limit(count(Oportunidades::all()));
                // $Oportunidades = v_Oportunidades::where('Login', $Login)->limit(200);
                $Oportunidades = DB::table('v_OportunidadesGrid')->where('LoginAgente', $Login);
                break;
            case "9":
                $Oportunidades = DB::table('v_OportunidadesGridAuxiliares')->where('LoginAuxiliar', $Login)->orWhere('LoginAuxiliar2', $Login)->orWhere('LoginAuxiliar3', $Login)->orWhere('LoginAuxiliar4', $Login);
                // $Oportunidades = DB::select('exec GetOportunidadesXAuxiliar @Login=' . $Login);
                break;
            default:
                $Oportunidades = DB::table('v_OportunidadesGrid');
                // $Oportunidades = DB::select('exec Get_v_CatOportunidades');
                break;
        }

        return DataTables::of($Oportunidades)
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

        $idOportunidad = $request->idOportunidad;
        $Documentos = DB::select('exec sp_Documentos @evento="DocumentosOport", @id=' . $idOportunidad);
        return DataTables::of($Documentos)
            ->make(true);
    }

    public function deleteDocOport(Request $request)
    {
        $idDocumentodelete = $request->idDocumentodelete;
        $idDocumentodelete = (int)$idDocumentodelete;

        $documento = relOport_Documento::where('idDocumento', $idDocumentodelete)->first();

        DocumentosOport::where('idDocumento', $idDocumentodelete)->delete();

        $this->bitacora($this->modulo, 'Eliminar Documento ' . $documento->descDocumento . ' Tipo Documento ' . $documento->descTipoDocumento, ' Oportunidad ', $documento->idOportunidad, $this->loginUsuario(), 3);
    }

    public function updocoport(Request $request)
    {
        $idUsuarioReg = (int)$this->idUsuario();
        $idOportunidad = $request->idOportunidad;
        $idOportunidad = (int)$idOportunidad;
        $idTipoDocumento = $request->idTipoDocumento;
        $idTipoDocumento = (int)$idTipoDocumento;
        $file = $_FILES['file'];
        $Extencion = $file['type'];
        $Size = $file['size'];
        $descDocumento =  $file['name'];
        $Documento = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

        DB::statement(DB::raw(" EXEC sp_updateOportDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento"), [
            ':descDocumento' => $descDocumento,
            ':Documento' => $Documento,
            ':Extencion' => $Extencion,
            ':Size' => $Size,
            ':idUsuarioReg' => $idUsuarioReg,
            ':idOportunidad' => $idOportunidad,
            ':idTipoDocumento' => $idTipoDocumento
        ]);

        $tipo_documento = catTipoDocumentoOport::where('idTipoDocumento', $idTipoDocumento)->first();

        $this->bitacora($this->modulo, 'Agregar Documento ' . (string)$descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Oportunidad ', $request->idOportunidad, $this->loginUsuario(), 5);
    }

    public function downdocsOport($id)
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
            DB::statement(DB::raw(" EXEC sp_updateFirma ?,?"), [
                $idUsuario,
                $Firma,
            ]);
        } else {
            DB::statement(DB::raw(" EXEC sp_insertFirma ?,?"), [
                $idUsuario,
                $Firma,
            ]);
        }
    }

    public function cancelarOportunidad(Request $request)
    {
        $fecha = new \DateTime();

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
        $this->bitacora($this->modulo, 'Cancelar ', 'Oportunidad ', $request->id, $this->loginUsuario(), 6);

        return redirect()->route('Oportunidades');
    }

    public function GetCotizadores()
    {
        $cotizadores = DB::select('exec GetCotizadoresCombo');
        return $cotizadores;
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
        $this->bitacora($this->modulo, 'Cambiar Cotizador ', 'Oportunidad ', $request->id, $this->loginUsuario(), 2);
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

        $fecha = new \DateTime();

        $id = $request->idTiempoEntregaOprotunidad;

        $fechaAutoriza = $request->FechaAutoriza;
        $diasEntrega = null;
        $fecha_entrega = "";

        if ($fechaAutoriza == null) {
            $fechaAutoriza = $request->FechaArchivoAutorizado;
        }

        if ($request->FechaEntrega != null) {
            $fecha_entrega = Carbon::parse($request->FechaEntrega)->format('d/m/Y');
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

        DB::statement('exec spAgregarTiempoEntrega ?, ?, ?, ?, ?, ?, ?, ?, ?', [
            $request->idOportunidadTEntrega,
            $request->Autorizo,
            $request->DiasEntrega,
            $fechaAutoriza,
            $request->FechaEntrega,
            $request->Comentarios,
            $request->idUsuarioTEntrega,
            $fecha->format('d-m-Y H:i:s'),
            1
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

        $cuentas_cco = $alertasPlantillas[7]->CCO;

        $cuenta_cco = explode(", ", $cuentas_cco);

        $route = "oportunidades";
        $numOrdenMTC = $oportunidad->numOrdenMTC;

        switch ($usuarios) {
            case 1:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }

                break;

            case 2:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            case 3:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
                $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            case 4:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
                $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;
                $cuenta_fecha_entrega_4 = $usuarios_rol[3]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            case 5:

                $cuenta_fecha_entrega = $usuarios_rol[0]->Correo;
                $cuenta_fecha_entrega_2 = $usuarios_rol[1]->Correo;
                $cuenta_fecha_entrega_3 = $usuarios_rol[2]->Correo;
                $cuenta_fecha_entrega_4 = $usuarios_rol[3]->Correo;
                $cuenta_fecha_entrega_5 = $usuarios_rol[4]->Correo;

                if ($cuenta_auxiliar1 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar2 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar3 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else if ($cuenta_auxiliar4 == null) {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                } else {
                    Mail::to($cuenta_usuario)->cc([$cuenta_cc, $cuenta_auxiliar1, $cuenta_auxiliar2, $cuenta_auxiliar3, $cuenta_auxiliar4, $cuenta_fecha_entrega, $cuenta_fecha_entrega_2, $cuenta_fecha_entrega_3, $cuenta_fecha_entrega_4, $cuenta_fecha_entrega_5])->bcc($cuenta_cco)->send(new SendMail($alertasPlantillas[7], $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC));
                }
                break;

            default:
                break;
        }

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

        $id = $request->id;
        $nota = $request->notaSolicitud;
        $fecha = new \DateTime();
        // $folio = $id;

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

        $result = DocumentosOport::where('idDocumento', (int) $idDocumento)->first();

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

        $fecha = new \DateTime();

        $idOportunidad = $request->idOport;

        DB::statement('exec spUpdateArchivoAutorizado ?, ?, ?', [
            $idOportunidad,
            1,
            $fecha->format('d-m-Y H:i:s')
        ]);

        // $folio = $idOportunidad;

        $oportunidad = v_Oportunidades::where('idOportunidad', $idOportunidad)->first();

        $folio = $oportunidad->idOportunidad;

        $fecha_entrega = "";

        if ($oportunidad->FechaEntrega != null) {
            $fecha_entrega = $oportunidad->FechaEntrega;
        }

        $fecha_aut_archivo = $oportunidad->fecArchivoAutorizado;
        $numOrdenMTC = $oportunidad->numOrdenMTC;
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

        $usuarios_rol = IntegrantesRoles::where('idRol', '1013')->get();

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

        $fecha = new \DateTime();

        $idOportunidad = $request->idOport;

        DB::statement('exec spUpdateArchivoAutorizado ?, ?, ?', [
            $idOportunidad,
            0,
            null
        ]);

        // $folio = $idOportunidad;

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

        $usuarios_rol = IntegrantesRoles::where('idRol', '1013')->get();

        $usuarios = count($usuarios_rol);

        $cuenta_usuario = $oportunidad->CorreoAgente;
        $cuenta_auxiliar1 = $oportunidad->CorreoAuxiliar;
        $cuenta_auxiliar2 = $oportunidad->CorreoAuxiliar2;
        $cuenta_auxiliar3 = $oportunidad->CorreoAuxiliar3;
        $cuenta_auxiliar4 = $oportunidad->CorreoAuxiliar4;

        $cuentas_cco = $alertasPlantillas[11]->CCO;

        $cuenta_cco = explode(", ", $cuentas_cco);

        $route = "oportunidades";
        $numOrdenMTC = $oportunidad->numOrdenMTC;

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
        $year = $request->year;

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

    public function obtenerSolicitudesOP()
    {
        $idUsuario = $this->idUsuario();
        // $idUsuario = 2193;

        $solicitudes_op = DB::table('v_OportunidadesSolOP5Dias')->where('idCustomer', $idUsuario)->orderBy('folio', 'desc')->get();

        // foreach ($solicitudes_op as $solicitudes) {
        //     flash()->keep()->delay(2)->addInfo('Solicitó OP para la Oportunidad '. $solicitudes->folio, $solicitudes->AgenteVentas);
        // }

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

    // public function enviarDocumentoMail(Request $request)
    // {
    //     // return $request;

    //     $cuenta_agente = $request->cuentaAgente;
    //     $cuenta_destino = $request->cuentapara;
    //     $subject = $request->subjetDoc;
    //     $mensaje = $request->cuerpoDoc;
    //     $attachement = $request->archivoAdjunto;

    //     Mail::send(new SendMailDocumento($subject, $mensaje, $attachement, $cuenta_agente, $cuenta_destino));
    // }


    public function enviarDocumentoMail(Request $request)
    {
        $idOportunidad = $request->id_oport;
        $cuenta_agente = $request->cuentaAgente;
        $cuenta_destino = $request->cuentapara;
        $subject = $request->subjetDoc;
        $mensaje = $request->cuerpoDoc;
        $attachement = $request->archivoAdjunto;
        $docAdjunto = $request->docAdjunto;
        $Nombre = $request->Nombre;
        $ruta_firma = $request->ruta_firma;
        $password = $request->password;
        $cuentas = explode(", ", $cuenta_destino);

        $firma_archivo = $request->firma;
        // gerando nombre de archivo
        $caminhoDoArquivo = storage_path($docAdjunto);
        $nombre_firma = str_replace(' ', '', $Nombre) . '.png';

        // remover data type da string
        $base64 = str_replace('data:application/pdf;base64,', '', $attachement);
        $firma = str_replace('data:application/png;base64,', '', $firma_archivo);

        // salvar a string em uma pasta temporária para servir de anexo
        file_put_contents($caminhoDoArquivo, base64_decode($base64));
        file_put_contents($nombre_firma, base64_decode($firma));

        // return $ruta_firma;

        Mail::send(new SendMailDocumento($subject, $mensaje, $caminhoDoArquivo, $cuenta_agente, $cuentas, $Nombre, $password, $nombre_firma, $ruta_firma));

        DB::statement('exec spInsertCorreo ?,?,?,?,?,?', [
            $idOportunidad,
            $cuenta_agente,
            $cuenta_destino,
            $subject,
            $mensaje,
            $docAdjunto
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
                $Correos = DB::table('v_Correos')->where('LoginCustomer', $Login)->get();
                break;
            case "4":
                $Correos = DB::table('v_Correos')->where('Login', $Login)->get();
                break;
            case "9":
                $Correos = DB::table('v_Correos')->where('LoginAuxiliar', $Login)->get();
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

        $oportunidades = DB::table('v_OportunidadesPT20Dias')->where('idAgente', $idUsuario)->orWhere('idAuxiliar', $idUsuario)->orderBy('folio', 'desc')->get();

        return $oportunidades;
    }

    public function obtenerOportunidadSolOPCincoDias()
    {
        $idUsuario = $this->idUsuario();

        $oportunidades = DB::table('v_OportunidadesSolOP5Dias')->where('idCustomer', $idUsuario)->orderBy('folio', 'desc')->get();

        return $oportunidades;
    }
}
