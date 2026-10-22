<?php

namespace App\Http\Controllers;

use App\catEquipos;
use App\Http\Modelos\Clientes;
use App\Traits\PermisosTrait;
use App\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PanelController extends Controller
{
    use PermisosTrait;

    public $moduloLlamadas = 'Llamadas';
    public $moduloReuniones = 'Reuniones';

    public function index(Request $request)
    {

        Artisan::call('config:cache');
        // // $this->permisos();

        // $Login = $this->loginUsuario();

        // $usuario = base64_decode($request->usuario);
        // $user = base64_decode($request->usuario);

        // if ($usuario != '') {
        //     session(['usuario' => $user]);
        // }

        // // $mfecha = $fecha->month;
        // // $dfecha = $fecha->day;
        // // $afecha = $fecha->year;

        // // return $años;

        // $user = session('usuario');


        // if ($user == '') {
        //     $datos_usuario = 'Usuario';
        //     session(['nombre' => 'Usuario']);
        //     return view('iniciar');
        // } else {
        //     // $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        //     // $fecha = new \DateTime();
        //     // $mes = $meses[($fecha->format('n')) - 1];
        //     // $año = $fecha->format('Y');
        //     // $años_oport = DB::table('Oportunidades')->select('fecCreacion')->get();
        //     // $años = [];

        //     // foreach ($años_oport as $key => $años_o) {
        //     //     $fecha_años = Carbon::parse($años_o->fecCreacion)->format('Y');
        //     //     $años = Arr::add($años, $fecha_años, $fecha_años);
        //     // }

        //     $this->permisos();
        //     // $permisos =  $this->permisosXusuario($user);
        //     $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
        //     session(['nombre' => $datos_usuario[0]->Nombre]);
        //     session(['idUsuario' => $datos_usuario[0]->Id_Usuario]);
        //     // $Rol = $this->idRol();

        //     // $Agentes = DB::table('v_catAgentesCLogin')->orderBy('Nombre')->get();

        //     // $permisos_llamadas =  $this->permisosModulo($this->moduloLlamadas);
        //     // $permisos_reuniones =  $this->permisosModulo($this->moduloReuniones);

        //     // $permisos_count = count($permisos_llamadas);
        //     // $id_usuario = $datos_usuario[0]->Id_Usuario;
        //     // $nombre_usuario = $datos_usuario[0]->Nombre;
        //     // $rol = $this->idRol();
        //     // $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $this->loginUsuario());
        //     // $agente_id = 0;
        //     // $idEquipo_idAgente = 0;
        //     // $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
        //     // $estados = DB::table('catEstadosLlamadas')->get();
        //     // $direcciones = DB::table('catDireccionLlamadas')->get();
        //     // $tipoRepeticionLlamadas = DB::table('catTipoRepeticionLlamadas')->get();
        //     // $horasRecordatorio = DB::table('catHoraRecordatorioLlamadas')->get();

        //     // $cardsLlamadas = DB::table('v_dashboards_Cards_Llamadas')->orderBy('id_estado')->get();
        //     // $cardsReuniones = DB::table('v_dashboards_Cards_Reuniones')->orderBy('id_estado')->get();
        //     // $Eficiencia = $this->eficiencia($mes, $año);
        //     // $List = DB::table('v_dashboards_List')->where('ST_Oport', 'sin iniciar')->get();

        //     // switch ($rol) {
        //     //     case '4':
        //     //         $equipos;
        //     //         $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
        //     //         $idEquipo = $equipos[0]->idEquipo;
        //     //         $idEquipo_idAgente = $equipos[0]->Id_Usuario;
        //     //         $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
        //     //         $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();

        //     //         $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->where('Id_Usuario', $id_usuario)->get();
        //     //         $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->where('Id_Usuario', $id_usuario)->get();

        //     //         $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

        //     //         $active = 'active';
        //     //         $show = 'show';
        //     //         $selected = 'true';

        //     //         break;
        //     //     case '9':
        //     //         $agente_id = $agentes[0]->idAgente;
        //     //         $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
        //     //         $idEquipo = $equipos[0]->idEquipo;
        //     //         $idEquipo_idAgente = $equipos[0]->Id_Usuario;
        //     //         $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
        //     //         $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();

        //     //         $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->where('Id_Usuario', $idEquipo_idAgente)->get();
        //     //         $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->where('Id_Usuario', $idEquipo_idAgente)->get();

        //     //         $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

        //     //         $active = 'active';
        //     //         $show = 'show';
        //     //         $selected = 'true';

        //     //         break;
        //     //         case '3':
        //     //             $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
        //     //             $combo_vendedores = DB::select('exec GetAgentesCombo');
        //     //         $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

        //     //         $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
        //     //         $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

        //     //         $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

        //     //         $active = '';
        //     //         $show = '';
        //     //         $selected = 'false';

        //     //         break;
        //     //     default:
        //     //         $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
        //     //         $combo_vendedores = DB::select('exec GetAgentesCombo');
        //     //         $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

        //     //         $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
        //     //         $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

        //     //         $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

        //     //         $active = '';
        //     //         $show = '';
        //     //         $selected = 'false';

        //     //         break;
        //     // }

        //     // $folio = DB::table('LlamadasReuniones')->max('folio') + 1;
        //     // $crearLlamada = session('crearLlamada');
        //     // $editarLlamada = session('editarLlamada');
        //     // $crearReunion = session('crearReunion');
        //     // $editarReunion = session('editarReunion');

        //     // return view('panel', compact('datos_usuario', 'cards', 'cardsLlamadas', 'cardsReuniones', 'List', 'permisos', 'Agentes', 'Rol', 'List_Llamadas', 'List_Reuniones', 'crearLlamada', 'editarLlamada', 'crearReunion', 'editarReunion', 'nombre_usuario', 'folio', 'id_usuario', 'rol', 'agente_id', 'combo_clientes', 'equipos', 'combo_vendedores', 'estados', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'idEquipo_idAgente', 'active', 'show', 'selected', 'años', 'año', 'mes', 'meses'));
        //     return view('pagina_panel');
        // }
    }

    public function panel(Request $request)
    {

        $Login = $this->loginUsuario();

        $usuario = base64_decode($request->usuario);
        $user = base64_decode($request->usuario);

        if ($usuario != '') {
            session(['usuario' => $user]);
        }

        $user = session('usuario');

        if ($user == null) {
            return view('errors.no-autenticado');
        }

        if ($user == '') {
            $datos_usuario = 'Usuario';
            session(['nombre' => 'Usuario']);
            return view('iniciar');
        } else {
            $fecha = new \DateTime();
            $dia = $fecha->format('D');
            $this->permisos();
            $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
            session(['nombre' => $datos_usuario[0]->Nombre]);
            session(['idUsuario' => $datos_usuario[0]->Id_Usuario]);

            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

            $fecha = new \DateTime();
            $mes = $meses[($fecha->format('n')) - 1];
            $año = $fecha->format('Y');
            $años_oport = DB::table('Oportunidades')->select('fecCreacion')->orderBy('fecCreacion', 'DESC')->get();

            $ultimo_año = Carbon::parse($años_oport[0]->fecCreacion)->format('Y');

            if ($año > $ultimo_año) {
                $año = $ultimo_año;
            }

            $años = [];

            foreach ($años_oport as $key => $años_o) {
                $fecha_años = Carbon::parse($años_o->fecCreacion)->format('Y');
                $años = Arr::add($años, $fecha_años, $fecha_años);
            }

            // No todos los roles llenan estos listados; en PHP 8 compact() falla si no existen
            $List = collect();
            $List_tripas = collect();

            $permisos =  $this->permisosXusuario($user);

            $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
            session(['nombre' => $datos_usuario[0]->Nombre]);
            session(['idUsuario' => $datos_usuario[0]->Id_Usuario]);
            $Rol = $this->idRol();

            $Agentes = DB::table('v_catAgentesCLogin')->orderBy('Nombre')->get();

            $permisos_llamadas =  $this->permisosModulo($this->moduloLlamadas);
            $permisos_reuniones =  $this->permisosModulo($this->moduloReuniones);

            $permisos_count = count($permisos_llamadas);
            $id_usuario = $datos_usuario[0]->Id_Usuario;
            $nombre_usuario = $datos_usuario[0]->Nombre;
            $rol = $this->idRol();
            $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $this->loginUsuario());
            $agente_id = 0;
            $idEquipo_idAgente = 0;
            $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
            $estados = DB::table('catEstadosLlamadas')->get();
            $direcciones = DB::table('catDireccionLlamadas')->get();
            $tipoRepeticionLlamadas = DB::table('catTipoRepeticionLlamadas')->get();
            $horasRecordatorio = DB::table('catHoraRecordatorioLlamadas')->get();

            $cardsLlamadas = DB::table('v_dashboards_Cards_Llamadas')->orderBy('id_estado')->get();
            $cardsReuniones = DB::table('v_dashboards_Cards_Reuniones')->orderBy('id_estado')->get();

            $Login = $this->loginUsuario();

            switch ($rol) {
                case '4':
                    $equipos;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();

                    $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->where('Id_Usuario', $id_usuario)->get();
                    $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->where('Id_Usuario', $id_usuario)->get();
                    $List_ventas = DB::table('v_dashboards_List_ventas')->where('LoginAgente', $Login)->get();

                    $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

                    $active = 'active';
                    $show = 'show';
                    $selected = 'true';

                    break;
                case '9':
                    $agente_id = $agentes[0]->idAgente;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();

                    $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->where('Id_Usuario', $idEquipo_idAgente)->get();
                    $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->where('Id_Usuario', $idEquipo_idAgente)->get();
                    $List_ventas = DB::table('v_dashboards_List_ventas')->where('LoginAuxiliar', 'LIKE', '%' . $Login . '%')->get();

                    $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

                    $active = 'active';
                    $show = 'show';
                    $selected = 'true';

                    break;
                case "2";
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

                    $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
                    $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

                    $List = DB::table('v_dashboards_List')->where('idAtendido', $id_usuario)->get();
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                    // $List = DB::table('v_dashboards_List')->where('idCustomer_', $id_usuario)->whereIn('ST_Oport', [3, 4])->get();
                    $List_ventas = DB::table('v_dashboards_List_ventas')->get();
                    // $Eficiencia = $this->eficiencia($mes, $año);
                    $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

                    $active = '';
                    $show = '';
                    $selected = 'false';

                    break;

                case '3':
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

                    $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
                    $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

                    // $List = DB::table('v_dashboards_List')->get();
                    // $List = DB::table('v_dashboards_List_x_customer')->where('Id_Usuario', 'like', '%'.$id_usuario.'%')->get();
                    $List = DB::table('v_dashboards_List')->where('idAtendido', $id_usuario)->get();
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                    // $List = DB::table('v_dashboards_List')->where('idAtendido', $id_usuario)->whereIn('ST_Oport', [3, 4, 7, 8, 11])->get();
                    $List_ventas = DB::table('v_dashboards_List_ventas')->get();
                    // $Eficiencia = $this->eficiencia($mes, $año);
                    // $Eficiencia = null;

                    $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

                    $active = '';
                    $show = '';
                    $selected = 'false';

                    break;
                default:
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

                    $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
                    $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

                    if ($rol == 10) {
                        $List = DB::table('v_dashboards_List')->where('idAtendido', $id_usuario)->get();
                        $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                        // $List = DB::table('v_dashboards_List')->where('idCustomer_', $id_usuario)->whereIn('ST_Oport', [3, 4])
                        //                                       ->orWhere('idRevisado', $id_usuario)->where('ST_Oport', 5)
                        //                                       ->orWhere('idAtendido', $id_usuario)->where('ST_Oport', [7, 8, 11])->get();
                    } else if ($rol == 1023) {
                        $List = DB::table('v_dashboards_List')->where('idAtendido', $id_usuario)->get();
                        $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                        // $List = DB::table('v_dashboards_List')->where('idCustomer_', $id_usuario)->whereIn('ST_Oport', [3, 4])
                        //                                       ->orWhere('idRevisado', $id_usuario)->where('ST_Oport', 5)
                        //                                       ->orWhere('idAtendido', $id_usuario)->where('ST_Oport', [2, 7, 8, 11])->get();
                    } else {
                        $List = DB::table('v_dashboards_List')->get();
                        $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->get();
                        // $List = DB::table('v_dashboards_List')->whereIn('ST_Oport', [2, 3, 4, 5, 7, 8, 11])->get();
                    }

                    $List_ventas = DB::table('v_dashboards_List_ventas')->get();
                    // $Eficiencia = $this->eficiencia($mes, $año);
                    $cards = $this->cards($rol, $id_usuario, $mes, $año, $agente_id);

                    $active = '';
                    $show = '';
                    $selected = 'false';

                    break;
            }

            $folio = DB::table('LlamadasReuniones')->max('folio') + 1;
            $crearLlamada = session('crearLlamada');
            $editarLlamada = session('editarLlamada');
            $crearReunion = session('crearReunion');
            $editarReunion = session('editarReunion');

            /******************************************/
            // Espacio de autenticación
            if (auth()->user() == null) {
                // La almacenamos en el usuario
                $user = User::where('login', $user)->where('estatus', 'ACTIVO')->first();

                // Validamos si el usuario no existe para redireccionar a litoapps
                if ($user == null && auth()->user() == null) {
                    // redireccionamos a litoapps
                    //return redirect('https://servicios.litoprocess.com/litoapps/index.php');
                }

                if (auth()->user() == null) {
                    // Validamos si el usuario tiene permisos para acceder
                    if ($user->TipoUsuarioCRM == null && $user->EstatusCRM == null) {
                        //return redirect('https://servicios.litoprocess.com/litoapps/index.php');
                    }

                    // Loggeamos al usuario
                    auth()->login($user);
                } else {
                    if ($user != null) {
                        // Validamos si el usuario que se logea es el mismo que el que ya existe
                        if (auth()->user()->Login != $user->Login) {

                            // Desloggeamos al usuario
                            $this->logoutUser();

                            // Loggeamos al usuario
                            auth()->login($user);
                        }
                    }
                }
            } else {
                // Validamos si el usuario que se logea es el mismo que el que ya existe
                if (auth()->user()->Login != $user) {

                    // Desloggeamos al usuario
                    $this->logoutUser();
                    // Loggeamos al usuario
                    $user = User::where('login', $user)->first();
                    auth()->login($user);
                }
            }
            /******************************************/

            return view('panel', compact('datos_usuario', 'cards', 'cardsLlamadas', 'cardsReuniones', 'List', 'permisos', 'Agentes', 'Rol', 'List_Llamadas', 'List_Reuniones', 'crearLlamada', 'editarLlamada', 'crearReunion', 'editarReunion', 'nombre_usuario', 'folio', 'id_usuario', 'rol', 'agente_id', 'combo_clientes', 'equipos', 'combo_vendedores', 'estados', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'idEquipo_idAgente', 'active', 'show', 'selected', 'años', 'año', 'mes', 'meses', 'List_ventas', 'List_tripas'));
            // }
        }
    }

    /**
     * Función para cerrar la sesión del usuario
     *
     * @return void
     */
    public function logoutUser()
    {
        auth()->guard()->logout();
    }

    public function apiTripasCCambios()
    {
        $rol = $this->idRol();
        $user = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
        $id_usuario = $datos_usuario[0]->Id_Usuario;
        switch ($rol) {
            case 2;
                $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                $total_tripas = count($List_tripas);
                break;
            case '3':
                $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                $total_tripas = count($List_tripas);
                break;
            default:
                if ($rol == 10) {
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                    $total_tripas = count($List_tripas);
                } else if ($rol == 1023) {
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->where('idAtendido', $id_usuario)->get();
                    $total_tripas = count($List_tripas);
                } else {
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_cambios')->get();
                    $total_tripas = count($List_tripas);
                }
                break;
        }

        return DataTables::of($List_tripas)
            ->addColumn('totalTripas', function () use($total_tripas) {
                $totalTripas = $total_tripas;
                return $totalTripas;
            })
            ->make(true);
    }

    /**
     * Función para obtener el listado de documentos con correcciones relacionados a una oportunidad
     * 
     * @return mixed
     */
    public function getListCorrecciones()
    {
        try {
            $rol = $this->idRol();
            $user = session('usuario');
            $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
            $id_usuario = $datos_usuario[0]->Id_Usuario;
            switch ($rol) {
                case 2;
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_correcciones')->where('idAtendido', $id_usuario)->get();
                    $total_tripas = count($List_tripas);
                    break;
                case '3':
                    $List_tripas = DB::table('v_dashboard_List_Tripas_c_correcciones')->where('idAtendido', $id_usuario)->get();
                    $total_tripas = count($List_tripas);
                    break;
                default:
                    if ($rol == 10) {
                        $List_tripas = DB::table('v_dashboard_List_Tripas_c_correcciones')->get();
                        $total_tripas = count($List_tripas);
                    } else if ($rol == 1023) {
                        $List_tripas = DB::table('v_dashboard_List_Tripas_c_correcciones')->where('idAtendido', $id_usuario)->get();
                        $total_tripas = count($List_tripas);
                    } else {
                        $List_tripas = DB::table('v_dashboard_List_Tripas_c_correcciones')->get();
                        $total_tripas = count($List_tripas);
                    }
                    break;
            }

            return DataTables::of($List_tripas)
                ->addColumn('totalTripasCorrect', function () use($total_tripas) {
                    $totalTripas = $total_tripas;
                    return $totalTripas;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Ocurrió un error al obtener el listado de documentos con correcciones.']);
        }
    }

    public function eficiencia($mes, $año)
    {
        if ($año == 'TODOS' && $mes == 'TODOS') {
            $eficiencia = DB::select('exec sp_dashboards_Eficiencia_Todo');
        } else if ($año != 'TODOS' && $mes != 'TODOS') {
            $eficiencia = DB::select('exec sp_dashboards_Eficiencia_Mes_Año @año=' . $año . ', @mes=' . $mes);
        } else if ($año != 'TODOS' && $mes == 'TODOS') {
            $eficiencia = DB::select('exec sp_dashboards_Eficiencia_Año @año=' . $año);
        } else if ($año == 'TODOS' && $mes != 'TODOS') {
            $eficiencia = DB::select('exec sp_dashboards_Eficiencia_Mes @mes=' . $mes);
        }

        return $eficiencia;
    }

    public function cards($rol, $id_usuario, $mes, $año, $agente_id)
    {

        switch ($rol) {
            case '4':
                $cards = DB::select('exec sp_dashboard_cardsXAgente_3 @idAgente=' . $id_usuario . ', @mes=' . $mes . ', @año=' . $año);
                break;
            case '9':
                $cards = DB::select('exec sp_dashboard_cardsXAgente_3 @idAgente=' . $agente_id . ', @mes=' . $mes . ', @año=' . $año);
                break;
            case '3':
                $cards = DB::select('exec sp_dashboard_cards @mes=' . $mes . ', @año=' . $año);
                break;
            default:
                $cards = DB::select('exec sp_dashboard_cards @mes=' . $mes . ', @año=' . $año);
                break;
        }

        return $cards;
    }

    public function index2(Request $request)
    {
        // // $this->permisos();

        // $Login = $this->loginUsuario();

        // $usuario = base64_decode($request->usuario);
        // $user = base64_decode($request->usuario);

        // if ($usuario != '') {
        //     session(['usuario' => $user]);
        // }

        // $user = session('usuario');

        // if ($user == '') {
        //     $datos_usuario = 'Usuario';
        //     session(['nombre' => 'Usuario']);
        //     return view('iniciar');
        // } else {
        //     $this->permisos();
        //     $permisos =  $this->permisosXusuario($user);
        //     $datos_usuario = DB::select('exec GetUsuario @Login=' . $user);
        //     session(['nombre' => $datos_usuario[0]->Nombre]);
        //     session(['idUsuario' => $datos_usuario[0]->Id_Usuario]);
        //     $Rol = $this->idRol();

        //     $Agentes = DB::table('v_catAgentesCLogin')->orderBy('Nombre')->get();

        //     $permisos_llamadas =  $this->permisosModulo($this->moduloLlamadas);
        //     $permisos_reuniones =  $this->permisosModulo($this->moduloReuniones);

        //     $permisos_count = count($permisos_llamadas);
        //     $id_usuario = $datos_usuario[0]->Id_Usuario;
        //     $nombre_usuario = $datos_usuario[0]->Nombre;
        //     $rol = $this->idRol();
        //     $agentes =  DB::select('exec GetAuxiliaresAgentes @Usuario=' . $this->loginUsuario());
        //     $agente_id = 0;
        //     $idEquipo_idAgente = 0;
        //     $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
        //     $estados = DB::table('catEstadosLlamadas')->get();
        //     $direcciones = DB::table('catDireccionLlamadas')->get();
        //     $tipoRepeticionLlamadas = DB::table('catTipoRepeticionLlamadas')->get();
        //     $horasRecordatorio = DB::table('catHoraRecordatorioLlamadas')->get();

        //     $cardsLlamadas = DB::table('v_dashboards_Cards_Llamadas')->orderBy('id_estado')->get();
        //     $cardsReuniones = DB::table('v_dashboards_Cards_Reuniones')->orderBy('id_estado')->get();
        //     $Eficiencia = DB::table('v_dashboards_Eficiencia')->get();
        //     $List = DB::table('v_dashboards_List')->where('ST_Oport', 'sin iniciar')->get();

        //     switch ($rol) {
        //         case '4':
        //             $equipos;
        //             $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
        //             $idEquipo = $equipos[0]->idEquipo;
        //             $idEquipo_idAgente = $equipos[0]->Id_Usuario;
        //             $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
        //             $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->get();

        //             $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->where('Id_Usuario', $id_usuario)->get();
        //             $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->where('Id_Usuario', $id_usuario)->get();

        //             $cards = DB::select('exec sp_dashboard_cardsXagente @agente=' . $id_usuario);

        //             $active = 'active';
        //             $show = 'show';
        //             $selected = 'true';

        //             break;
        //         case '9':
        //             $agente_id = $agentes[0]->idAgente;
        //             $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
        //             $idEquipo = $equipos[0]->idEquipo;
        //             $idEquipo_idAgente = $equipos[0]->Id_Usuario;
        //             $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
        //             $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->get();

        //             $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->where('Id_Usuario', $idEquipo_idAgente)->get();
        //             $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->where('Id_Usuario', $idEquipo_idAgente)->get();

        //             $cards = DB::select('exec sp_dashboard_cardsXagente @agente=' . $agente_id);

        //             $active = 'active';
        //             $show = 'show';
        //             $selected = 'true';

        //             break;
        //         case '3':
        //             $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
        //             $combo_vendedores = DB::select('exec GetAgentesCombo');
        //             $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

        //             $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
        //             $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

        //             $cards = DB::table('v_dashboards_Cards')->orderBy('valorEstatus')->get();

        //             $active = '';
        //             $show = '';
        //             $selected = 'false';

        //             break;
        //         default:
        //             $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
        //             $combo_vendedores = DB::select('exec GetAgentesCombo');
        //             $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->get();

        //             $List_Llamadas = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Llamadas')->get();
        //             $List_Reuniones = DB::table('v_dashboards_List_Llamadas_Reuniones')->where('tipo', 'Reuniones')->get();

        //             $cards = DB::table('v_dashboards_Cards')->orderBy('valorEstatus')->get();

        //             $active = '';
        //             $show = '';
        //             $selected = 'false';

        //             break;
        //     }

        //     $folio = DB::table('LlamadasReuniones')->max('folio') + 1;
        //     $crearLlamada = session('crearLlamada');
        //     $editarLlamada = session('editarLlamada');
        //     $crearReunion = session('crearReunion');
        //     $editarReunion = session('editarReunion');

        //     return view('panel_2', compact('datos_usuario', 'cards', 'cardsLlamadas', 'cardsReuniones', 'List', 'permisos', 'Agentes', 'Rol', 'List_Llamadas', 'List_Reuniones', 'crearLlamada', 'editarLlamada', 'crearReunion', 'editarReunion', 'nombre_usuario', 'folio', 'id_usuario', 'rol', 'agente_id', 'combo_clientes', 'equipos', 'combo_vendedores', 'estados', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'idEquipo_idAgente', 'active', 'show', 'selected'));
        // }
    }

    public function DasboardsGrafica(Request $request)
    {
        $año = $request->Año;

        try {
            // $Semanas = DB::table('v_dashboards_Semanas')->orderBy('Semana')->get();
            $Semanas = DB::select('exec sp_dashboard_semanas @año=' . $año);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $Semanas;
    }

    public function DasboardsTiemposEstatus()
    {
        try {
            $Tiempos = DB::select('SET NOCOUNT ON ;exec sp_dasboards_TiemposEstatus');
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $Tiempos;
    }

    public function DasboardsOportunidadesValor(Request $request)
    {
        // return $request;
        $mes = $request->Mes;
        $año = $request->Año;
        $agente = $request->Agente;

        try {
            $Oportunidades_Valor = DB::select('SET NOCOUNT ON ;exec sp_dashboard_oportunidades_valor ?,?', [$agente, $mes]);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $Oportunidades_Valor;
    }

    public function DasboardsProbabilidad(Request $request)
    {
        $Agente = $request->Agente;

        try {
            if ($Agente != 'TODOS') {
                $Tiempos = DB::table('v_dashboards_ProbabilidadXagente')->where('idAgente', $Agente)->get();
            } else {
                $Tiempos = DB::table('v_dashboards_Probabilidad')->get();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
        }

        return $Tiempos;
    }

    public function DasboardsboxVentas(Request $request)
    {
        $Agente = $request->Agente;

        try {
            $BoxVentas = DB::select('exec sp_DashboardRanking @agente=' . $Agente);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $BoxVentas;
    }

    public function DasboardsEstatusXagente(Request $request)
    {

        // return $request;

        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $fecha = new \DateTime();
        $mes1 = $meses[($fecha->format('n')) - 1];
        $año1 = $fecha->format('Y');

        $Agente = $request->Agente;
        $año2 = $request->Año;
        $mes2 = $request->Mes;

        if ($mes2 == null) {
            $mes = $mes1;
        } else {
            $mes = $mes2;
        }

        if ($año2 == null) {
            $año = $año1;
        } else {
            $año = $año2;
        }

        $id_usuario = (in_array($request->Agente, [8357, 29])) ? 29 : $request->Agente;

        // return $año1;

        // Verificamos si la persona que ingresa es un subgerente
        $esSubGerente = DB::table('vendedoresAsignados')
                        ->where('idUsuario', $id_usuario)
                        ->exists();

        if ($esSubGerente) {
            try {
                $BoxVentas = DB::select('exec sp_dashboard_cards_all_subgerentes @subgerente=' . $id_usuario . ', @mes=' . $mes . ', @año=' . $año);
            } catch (\Illuminate\Database\QueryException $ex) {
                dd($ex->getMessage());
                // Note any method of class PDOException can be called on $ex.
            }
        } else {
            try {
                if ($Agente <= 0 && $mes == 'TODOS' && $año == 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cards_all');
                    // $BoxVentas = DB::table('v_dashboards_Cards')->orderBy('valorEstatus')->get();
                } else if ($Agente <= 0 && $mes != 'TODOS' && $año == 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cards_all_mes @mes=' . $mes);
                } else if ($Agente <= 0 && $mes == 'TODOS' && $año != 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cards_all_año @año=' . $año);
                } else if ($Agente > 0 && $mes == 'TODOS' && $año == 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cards_all_agente @agente=' . $Agente);
                } else if ($Agente > 0 && $mes != 'TODOS' && $año != 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cardsXAgente_3 @idAgente=' . $Agente . ', @mes=' . $mes . ', @año=' . $año);
                } else if ($Agente > 0 && $mes == 'TODOS' && $año != 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cards_all_agente_mes @idAgente=' . $Agente . ', @año=' . $año);
                } else if ($Agente > 0 && $mes != 'TODOS' && $año == 'TODOS') {
                    $BoxVentas = DB::select('exec sp_dashboard_cards_all_agente_año @idAgente=' . $Agente . ', @mes=' . $mes);
                } else {
                    $BoxVentas = $this->cards(1, $Agente, $mes, $año, $Agente);
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                // Note any method of class PDOException can be called on $ex.
            }
        }

        return $BoxVentas;
    }

    public function DasboardsEstatusXagenteLlamadas(Request $request)
    {
        $Agente = $request->Agente;

        try {
            // $BoxAgenda = DB::table('v_dashboards_Cards_Llamadas')->orderBy('id_estado')->get();

            $BoxAgenda = DB::select('exec sp_dashboards_Agenda_Llamadas @IdAgente=' . $Agente);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
        }

        return $BoxAgenda;
    }

    public function DasboardsEstatusXagenteReuniones(Request $request)
    {
        $Agente = $request->Agente;

        try {
            // $BoxAgenda = DB::table('v_dashboards_Cards_Reuniones')->orderBy('id_estado')->get();
            $BoxAgenda = DB::select('exec sp_dashboards_Agenda_Reuniones @IdAgente=' . $Agente);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
        }

        return $BoxAgenda;
    }

    public function DasboardsGraficaVentasVsMeta(Request $request)
    {
        $Agente = $request->Agente;
        $Año = $request->Año;

        // return $request;
        try {
            $BoxVentas = DB::select('exec sp_dashboards_Metas_2 @IdAgente=' . $Agente . ', @Año=' . $Año);
            // if ($Agente == 0) {
            //     $BoxVentas = DB::select('exec sp_dashboards_Metas_2 @IdAgente=' . $Agente . ', @Año=' . $Año);
            // } else {
            //     $BoxVentas = DB::select('exec sp_dashboards_Metas_2 @IdAgente=' . $Agente . ', @Año=' . $Año);
            // }
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
            // Note any method of class PDOException can be called on $ex.
        }

        return $BoxVentas;
    }

    public function DasboardsGraficaAgendaLlamadas(Request $request)
    {
        $Agente = $request->Agente;

        try {
            $BoxAgenda = DB::select('exec sp_dashboards_Agenda_Llamadas @IdAgente=' . $Agente);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
        }

        return $BoxAgenda;
    }

    public function DasboardsGraficaAgendaReuniones(Request $request)
    {
        $Agente = $request->Agente;

        try {
            $BoxAgenda = DB::select('exec sp_dashboards_Agenda_Reuniones @IdAgente=' . $Agente);
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
        }

        return $BoxAgenda;
    }
}
