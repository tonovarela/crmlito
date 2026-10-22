<?php

namespace App\Http\Controllers;

use App\Http\Modelos\BitacoraEstructural;
use App\Http\Modelos\catPlantillasAlertas;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\Contactos;
use App\Http\Modelos\DocumentosEstrMail;
use App\Http\Modelos\DocumentosEstructural;
use App\Http\Modelos\DocumentosOport;
use App\Http\Modelos\Estructural;
use App\Http\Modelos\relEstr_Documento;
use App\Http\Vistas\v_Estructural;
use App\Http\Vistas\v_EstructuralXId;
use App\Mail\SendMailEstructural;
use App\Traits\BitacoraEstructuralTrait;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class EstructuralController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;
    use BitacoraEstructuralTrait;
    public $modulo = 'Diseño Estructural';

    public function index()
    {
        $permisos =  $this->permisosModulo($this->modulo);
        $idrol = $permisos[0]->idRol;
        $id_usuario = $this->idUsuario();

        // Gustavo
        if ($id_usuario == 4264) {
            $idrol = 1026;
        }

        //ignacio r
        if ($id_usuario == 2115) {
            $idrol = 1;
        }

        $tipo_barnices = DB::table('catEPtipoBarnices')->get();
        $estatus = DB::table('catEstructuralEstatus')->where('Estado', 1)->get();
        $folio = DB::table('Estructural')->max('master') + 1;
        $combo_clientes = DB::select('exec GetClientesCombo');
        $agentes = $this->agentes();
        if (count($agentes) == 0) {
            $agentes = $this->datosUsuario();
            $idAgente = $agentes[0]->Id_Usuario;
        } else {
            $idAgente = $agentes[0]->idAgente;
        }
        $id = 0;
        $oportunidades = DB::select('exec GetOportunidad @idOportunidad=' . $id);
        $oportunidad = $oportunidades;

        switch ($idrol) {
            case '4':
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $id_usuario)->get();
                break;
            case '9':
                if ($id_usuario == 29) {
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $idAgente)->orWhere('idAgente', 9)->get();
                } else {
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $idAgente)->get();
                }
                break;
            case '3':
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->get();
                break;
            case '1022':
                $combo_clientes = DB::table('v_catClientesActivosCombo')->where('idClienteINT', 22590)->orderBy('razonsocial', 'asc')->get();
                break;
            default:
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->get();
                break;
        }

        $cuentas_cc = null;

        $crearTickets = ($idrol == 1024) ? 'No crear' : session('crearEstructural');

        // Obtenemos los agentes de la base de datos de acuerdo a los tickets
        $agentesDb = DB::table('v_Estructural')->select('Agente')->distinct()->get()->pluck('Agente')->toArray();
        $agentesDb = DB::table('ControlLogin.dbo.cat_Usuarios')->whereIn('Nombre', $agentesDb)->where('Estatus', 'Activo')->whereNotNull('Agente')->select('Nombre')->orderBy('Nombre')->get();

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
                                ->orderBy('descripcion', 'asc')
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


        $catMotivosReEstr = DB::table('catMotivosReTicket')->get();

        return view('estructural.index', compact('tipo_barnices', 'estatus', 'folio', 'combo_clientes', 'oportunidad', 'crearTickets', 'idrol', 'cuentas_cc', 'agentesDb', 'id_usuario', 'oportunidadesCache', 'catMotivosReEstr', 'catTiposProductosAgrupados'));
    }

    public function create()
    {
    }

    public function guardarTicket(Request $request)
    {
        $this->configMail();

        $fecha = $request->fechaRegistroEstr;

        $catYProd = explode('-', $request->tipo_producto);

        $usuario = $this->datosUsuario();
        $master = DB::table('Estructural')->max('master') + 1;
        $folio_consecutivo = DB::table('Estructural')->max('folio_consecutivo') + 1;

        $nombreCliente = $request->nomClienteEstr;
        $id_clienteINT = $request->idClienteEstr;
        $idAgente = $request->idAgenteEstr;
        $agentes = $this->agentes();
        $id_usuario = $usuario[0]->Id_Usuario;

        if (count($agentes) == 0) {
            $idAgente = $usuario[0]->Id_Usuario;
            $nombre_agente = strtoupper($usuario[0]->Nombre);
        } else {
            $idAgente = $agentes[0]->idAgente;
            $nombre_agente = strtoupper($agentes[0]->NombreAgente);
        }

        $correo_usuario = $usuario[0]->Correo;
        $nombre_usuario = strtoupper($usuario[0]->Nombre);

        $ametropolitana = 0;
        if ($request->ametropolitana == 'on') {
            $ametropolitana = 1;
        }
        $armado = 0;
        if ($request->armado == 'on') {
            $armado = 1;
        }
        if ($request->barnices == null || $request->barnices == '') {
            $barnices = $request->barnices;
        } else {
            $barnices = implode(", ", $request->barnices);
        }

        $request['barnices'] = $barnices;

        $bolsa = 0;
        if ($request->bolsa == 'on') {
            $bolsa = 1;
        }
        $cajaindividual = 0;
        if ($request->cajaindividual == 'on') {
            $cajaindividual = 1;
        }
        $cajamaster = 0;
        if ($request->cajamaster == 'on') {
            $cajamaster = 1;
        }
        $cant1 = $request->cant1;
        $cant2 = $request->cant2;
        $cant3 = $request->cant3;
        $cant4 = $request->cant4;
        $cant5 = $request->cant5;
        $conAcetato = 0;
        if ($request->conAcetato == 'on') {
            $conAcetato = 1;
        }
        $descripcion = strtoupper($request->descripcion);
        $desplegado = 0;
        if ($request->desplegado == 'on') {
            $desplegado = 1;
        }
        $dummy = 0;
        if ($request->dummy == 'on') {
            $dummy = 1;
        }
        $ecpAlto1 = $request->ecpAlto1;
        $ecpAlto2 = $request->ecpAlto2;
        $ecpAlto3 = $request->ecpAlto3;
        $ecpAlto4 = $request->ecpAlto4;
        $ecpCant1 = $request->ecpCant1;
        $ecpCant2 = $request->ecpCant2;
        $ecpCant3 = $request->ecpCant3;
        $ecpCant4 = $request->ecpCant4;
        $ecpDescr1 = strtoupper($request->ecpDescr1);
        $ecpDescr2 = strtoupper($request->ecpDescr2);
        $ecpDescr3 = strtoupper($request->ecpDescr3);
        $ecpDescr4 = strtoupper($request->ecpDescr4);
        $ecpFondo1 = $request->ecpFondo1;
        $ecpFondo2 = $request->ecpFondo2;
        $ecpFondo3 = $request->ecpFondo3;
        $ecpFondo4 = $request->ecpFondo4;
        $ecpFrente1 = $request->ecpFrente1;
        $ecpFrente2 = $request->ecpFrente2;
        $ecpFrente3 = $request->ecpFrente3;
        $ecpFrente4 = $request->ecpFrente4;
        $emplayado = 0;
        if ($request->emplayado == 'on') {
            $emplayado = 1;
        }
        $enpaquetes = 0;
        if ($request->enpaquetes == 'on') {
            $enpaquetes = 1;
        }
        $esp1 = 0;
        if ($request->esp1 == 'on') {
            $esp1 = 1;
        }
        $esp2 = 0;
        if ($request->esp2 == 'on') {
            $esp2 = 1;
        }
        $esp3 = 0;
        if ($request->esp3 == 'on') {
            $esp3 = 1;
        }
        $esp4 = 0;
        if ($request->esp4 == 'on') {
            $esp4 = 1;
        }
        $especifique = strtoupper($request->especifique);
        $flauta1 = $request->flauta1;
        $flauta2 = $request->flauta2;
        $flauta3 = $request->flauta3;
        $flauta4 = $request->flauta4;
        $foraneo = 0;
        if ($request->foraneo == 'on') {
            $foraneo = 1;
        }
        $individual = 0;
        if ($request->individual == 'on') {
            $individual = 1;
        }
        $instructivo = 0;
        if ($request->instructivo == 'on') {
            $instructivo = 1;
        }
        $instructivos = 0;
        if ($request->instructivos == 'on') {
            $instructivos = 1;
        }
        $materiales1 = strtoupper($request->materiales1);
        $materiales2 = strtoupper($request->materiales2);
        $materiales3 = strtoupper($request->materiales3);
        $materiales4 = strtoupper($request->materiales4);
        $medida = $request->medida;
        $mgAlto = $request->mgAlto;
        $mgFondo = $request->mgFondo;
        $mgFrente = $request->mgFrente;
        $nomOportunidadEstr = strtoupper($request->nomOportunidadEstr);
        $otroProductotexto = strtoupper($request->otroProductotexto);
        $otrosAcabados = strtoupper($request->otrosAcabados);
        $categoria = strtoupper($catYProd[0]);
        $producto = strtoupper($catYProd[1]);
        $puntos1 = $request->puntos1;
        $puntos2 = $request->puntos2;
        $puntos3 = $request->puntos3;
        $puntos4 = $request->puntos4;

        // Seccion de Entregado por el cliente
        $entPlano = 0;
        if ($request->entPlano == 'on') {
            $entPlano = 1;
        }
        $entRender = 0;
        if ($request->entRender == 'on') {
            $entRender = 1;
        }
        $entFoto = 0;
        if ($request->entFoto == 'on') {
            $entFoto = 1;
        }
        $entMuestraFisica = 0;
        if ($request->entMuestraFisica == 'on') {
            $entMuestraFisica = 1;
        }
        $entProducto = 0;
        if ($request->entProducto == 'on') {
            $entProducto = 1;
        }

        $aConsideracion = 0;
        if ($request->aConsideracion == 'on') {
            $aConsideracion = 1;
        }

        $cotizacion = 0;
        if ($request->cotizacion == 'on') {
            $cotizacion = 1;
        }
        $render = 0;
        if ($request->render == 'on') {
            $render = 1;
        }
        $suajado = 0;
        if ($request->suajado == 'on') {
            $suajado = 1;
        }
        $tipo_impresion = $request->tipo_impresion;
        $transporte = 0;
        if ($request->transporte == 'on') {
            $transporte = 1;
        }
        $trazo = 0;
        if ($request->trazo == 'on') {
            $trazo = 1;
        }
        $liberado = 0;

        $data = [
            'master' => $master,
            'folio_consecutivo' => $folio_consecutivo,
            'cotizacion' => $cotizacion,
            'dum' => $dummy,
            'render' => $render,
            'trazo' => $trazo,
            'render_dinamico' => $request->render_dinamico == 'on' ? 1 : 0,
            'instructivo' => $instructivo,
            'nombreCliente' => $nombreCliente,
            'nombre' => $nomOportunidadEstr,
            'descripcion' => $descripcion,
            'id_agente' => $idAgente,
            //'fecha_registro' => $fecha,
            'departamento' => 'VENTAS',
            'correo_usuario' => $correo_usuario,
            'nombre_usuario' => $nombre_usuario,
            'Estado' => 'PENDIENTE DE ASIGNAR',
            'Categoria' => $categoria,
            'ametropolitana' => $ametropolitana,
            'armado' => $armado,
            'barnices' => $barnices,
            'bolsa' => $bolsa,
            'cajaindividual' => $cajaindividual,
            'cajamaster' => $cajamaster,
            'cant1' => $cant1,
            'cant2' => $cant2,
            'cant3' => $cant3,
            'cant4' => $cant4,
            'cant5' => $cant5,
            'conAcetato' => $conAcetato,
            'desplegado' => $desplegado,
            'ecpAlto1' => $ecpAlto1,
            'ecpAlto2' => $ecpAlto2,
            'ecpAlto3' => $ecpAlto3,
            'ecpAlto4' => $ecpAlto4,
            'ecpCant1' => $ecpCant1,
            'ecpCant2' => $ecpCant2,
            'ecpCant3' => $ecpCant3,
            'ecpCant4' => $ecpCant4,
            'ecpDescr1' => $ecpDescr1,
            'ecpDescr2' => $ecpDescr2,
            'ecpDescr3' => $ecpDescr3,
            'ecpDescr4' => $ecpDescr4,
            'ecpFondo1' => $ecpFondo1,
            'ecpFondo2' => $ecpFondo2,
            'ecpFondo3' => $ecpFondo3,
            'ecpFondo4' => $ecpFondo4,
            'ecpFrente1' => $ecpFrente1,
            'ecpFrente2' => $ecpFrente2,
            'ecpFrente3' => $ecpFrente3,
            'ecpFrente4' => $ecpFrente4,
            'emplayado' => $emplayado,
            'enpaquetes' => $enpaquetes,
            'esp1' => $esp1,
            'esp2' => $esp2,
            'esp3' => $esp3,
            'esp4' => $esp4,
            'especifique' => $especifique,
            'flauta1' => $flauta1,
            'flauta2' => $flauta2,
            'flauta3' => $flauta3,
            'flauta4' => $flauta4,
            'foraneo' => $foraneo,
            'individual' => $individual,
            'instructivos' => $instructivos,
            'materiales1' => $materiales1,
            'materiales2' => $materiales2,
            'materiales3' => $materiales3,
            'materiales4' => $materiales4,
            'medida' => $medida,
            'mgAlto' => $mgAlto,
            'mgFondo' => $mgFondo,
            'mgFrente' => $mgFrente,
            'otroProductotexto' => $otroProductotexto,
            'otrosAcabados' => $otrosAcabados,
            'producto' => $producto,
            'puntos1' => $puntos1,
            'puntos2' => $puntos2,
            'puntos3' => $puntos3,
            'puntos4' => $puntos4,
            'suajado' => $suajado,
            'tipo_impresion' => $tipo_impresion,
            'transporte' => $transporte,
            'id_cliente' => $id_clienteINT,
            'nombre_agente' => $nombre_agente,
            'id_usuario' => $id_usuario,
            'liberado' => $liberado,
            'tipo_medida_general' => $request->tipoMedidaGeneral,
            'tipo_medida_producto' => $request->tipoMedidaProducto,

            'entPlano' => $entPlano,
            'entRender' => $entRender,
            'entFoto' => $entFoto,
            'entMuestraFisica' => $entMuestraFisica,
            'entProducto' => $entProducto,
            'consideracionDisign' => $aConsideracion,
        ];

        $newRegister=Estructural::create($data);        
        $estructural = Estructural::max('folio');

        $Documentos = DB::table('DocumentosEstructural')->where('idCliente', (int)$id_clienteINT)->where('folio', 0)->get();

        if (count($Documentos) > 0) {
            foreach ($Documentos as $doc) {
                DB::statement('exec sp_updateDocumentosEstr ?, ?, ?', [
                    $estructural,
                    $doc->idCliente,
                    $doc->id_documentosEstructural,
                ]);
            }
        }

        DB::statement(
            'exec spInsertRelacionESTROportunidad ?, ?, ?, ?',
            [
                (int)$request->idOportunidadEstrNew,
                $estructural,
                $this->idUsuario(),
                $fecha,
            ]
        );

        $motivo_cancelacion = null;
        $motivo_cambio_fecha = null;
        $ticket = v_Estructural::where('Id_Ticket', $estructural)->first();
        $alertasPlantillas = catPlantillasAlertas::get();
        $cuenta_usuario = $ticket->CorreoAgente;
        $cuenta_cc1 = $ticket->CorreoSolicitante;
        $datos_usuario = $this->datosUsuario();
        if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
            $cuenta_cc2 = $alertasPlantillas[15]->Destinatario;
        } else {
            if ($this->idRol() == 1) {
                $cuenta_cc2 = $alertasPlantillas[15]->Destinatario;
            } else {
                $cuenta_cc2 = $datos_usuario[0]->Correo;
            }
        }
        $cuenta_cc3 = $alertasPlantillas[15]->Destinatario;
        $nombre_proyecto = $ticket->Tarea;
        $folio = $ticket->master;
        $subject = $alertasPlantillas[15]->Asunto;
        $nombre_agente = $ticket->Agente;
        $route = "estructural";
        $fecha_solicitud = $ticket->FechaRegistro;
        $mensaje = $alertasPlantillas[15]->Mensaje;
        $asignadoA = $request->operador;

        // Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3])->send(new SendMailEstructural($alertasPlantillas[15], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha));

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($request->all(), true)));
        $this->bitacora($this->modulo, 'Agregar ', 'Ticket | ' . $cadena . ' | ', $estructural, $this->loginUsuario(), 5);
        $this->bitacoraEstructural('TICKET CREADO', $folio , $ticket->Id_Ticket, $nombre_usuario, null, $fecha);

        return response()->json([
            'response' => true,
            'message' => 'Ticket creado correctamente',
            'idTicketEstr' => $estructural,
            'folio_consecutivo' => $folio_consecutivo,
        ]);
    }

    public function actualizarTicket(Request $request)
    {
        $fecha = DB::select('SELECT GETDATE() AS fecha')[0]->fecha;

        $anterior = Estructural::where('folio', $request->idTicketEstr)->first();
        $relacionAnterior = DB::table('relOport_Estructural')->where('folio', $request->idTicketEstr)->first();

        $catYProd = explode('-', $request->tipo_producto);

        $fecha_cambio = $request->fechaCambioEstr;
        $usuario = $this->datosUsuario();
        $ticket = Estructural::where('folio', $request->idTicketEstr)->first();
        $master = $ticket->master;
        $folio_consecutivo = $ticket->folio_consecutivo;

        $nombreCliente = $request->nomClienteEstr;
        $id_clienteINT = $request->idClienteEstr;

        $idAgente = $ticket->id_agente;
        $nombre_agente = strtoupper($ticket->nombre_agente);
        $id_usuario = $ticket->id_usuario;
        $id_usuario_cambio = $usuario[0]->Id_Usuario;

        $correo_usuario = $ticket->correo_usuario;
        $nombre_usuario = strtoupper($ticket->nombre_usuario);

        $ametropolitana = 0;
        if ($request->ametropolitana == 'on') {
            $ametropolitana = 1;
        }
        $armado = 0;
        if ($request->armado == 'on') {
            $armado = 1;
        }
        if ($request->barnices == null || $request->barnices == '') {
            $barnices = $request->barnices;
        } else {
            $barnices = implode(", ", $request->barnices);
        }

        $request['barnices'] = $barnices;

        $bolsa = 0;
        if ($request->bolsa == 'on') {
            $bolsa = 1;
        }
        $cajaindividual = 0;
        if ($request->cajaindividual == 'on') {
            $cajaindividual = 1;
        }
        $cajamaster = 0;
        if ($request->cajamaster == 'on') {
            $cajamaster = 1;
        }
        $cant1 = $request->cant1;
        $cant2 = $request->cant2;
        $cant3 = $request->cant3;
        $cant4 = $request->cant4;
        $cant5 = $request->cant5;
        $conAcetato = 0;
        if ($request->conAcetato == 'on') {
            $conAcetato = 1;
        }
        $cotizacion = 0;
        if ($request->cotizacion == 'on') {
            $cotizacion = 1;
        }
        $descripcion = strtoupper($request->descripcion);
        $desplegado = 0;
        if ($request->desplegado == 'on') {
            $desplegado = 1;
        }
        $dummy = 0;
        if ($request->dummy == 'on') {
            $dummy = 1;
        }
        $ecpAlto1 = $request->ecpAlto1;
        $ecpAlto2 = $request->ecpAlto2;
        $ecpAlto3 = $request->ecpAlto3;
        $ecpAlto4 = $request->ecpAlto4;
        $ecpCant1 = $request->ecpCant1;
        $ecpCant2 = $request->ecpCant2;
        $ecpCant3 = $request->ecpCant3;
        $ecpCant4 = $request->ecpCant4;
        $ecpDescr1 = strtoupper($request->ecpDescr1);
        $ecpDescr2 = strtoupper($request->ecpDescr2);
        $ecpDescr3 = strtoupper($request->ecpDescr3);
        $ecpDescr4 = strtoupper($request->ecpDescr4);
        $ecpFondo1 = $request->ecpFondo1;
        $ecpFondo2 = $request->ecpFondo2;
        $ecpFondo3 = $request->ecpFondo3;
        $ecpFondo4 = $request->ecpFondo4;
        $ecpFrente1 = $request->ecpFrente1;
        $ecpFrente2 = $request->ecpFrente2;
        $ecpFrente3 = $request->ecpFrente3;
        $ecpFrente4 = $request->ecpFrente4;
        $emplayado = 0;
        if ($request->emplayado == 'on') {
            $emplayado = 1;
        }
        $enpaquetes = 0;
        if ($request->enpaquetes == 'on') {
            $enpaquetes = 1;
        }
        $esp1 = 0;
        if ($request->esp1 == 'on') {
            $esp1 = 1;
        }
        $esp2 = 0;
        if ($request->esp2 == 'on') {
            $esp2 = 1;
        }
        $esp3 = 0;
        if ($request->esp3 == 'on') {
            $esp3 = 1;
        }
        $esp4 = 0;
        if ($request->esp4 == 'on') {
            $esp4 = 1;
        }
        $especifique = strtoupper($request->especifique);
        $flauta1 = $request->flauta1;
        $flauta2 = $request->flauta2;
        $flauta3 = $request->flauta3;
        $flauta4 = $request->flauta4;
        $foraneo = 0;
        if ($request->foraneo == 'on') {
            $foraneo = 1;
        }
        $individual = 0;
        if ($request->individual == 'on') {
            $individual = 1;
        }
        $instructivo = 0;
        if ($request->instructivo == 'on') {
            $instructivo = 1;
        }
        $instructivos = 0;
        if ($request->instructivos == 'on') {
            $instructivos = 1;
        }
        $materiales1 = strtoupper($request->materiales1);
        $materiales2 = strtoupper($request->materiales2);
        $materiales3 = strtoupper($request->materiales3);
        $materiales4 = strtoupper($request->materiales4);
        $medida = $request->medida;
        $mgAlto = $request->mgAlto;
        $mgFondo = $request->mgFondo;
        $mgFrente = $request->mgFrente;
        $nomOportunidadEstr = strtoupper($request->nomOportunidadEstr);
        $otroProductotexto = strtoupper($request->otroProductotexto);
        $otrosAcabados = strtoupper($request->otrosAcabados);
        $categoria = strtoupper($catYProd[0]);
        $producto = strtoupper($catYProd[1]);
        $puntos1 = $request->puntos1;
        $puntos2 = $request->puntos2;
        $puntos3 = $request->puntos3;
        $puntos4 = $request->puntos4;
        $render = 0;
        if ($request->render == 'on') {
            $render = 1;
        }
        $suajado = 0;
        if ($request->suajado == 'on') {
            $suajado = 1;
        }
        // $tipoUsuario = $request->tipoUsuario;
        $tipo_impresion = $request->tipo_impresion;
        $transporte = 0;
        if ($request->transporte == 'on') {
            $transporte = 1;
        }
        $trazo = 0;
        if ($request->trazo == 'on') {
            $trazo = 1;
        }

        // Seccion de Entregado por el cliente
        $entPlano = 0;
        if ($request->entPlano == 'on') {
            $entPlano = 1;
        }
        $entRender = 0;
        if ($request->entRender == 'on') {
            $entRender = 1;
        }
        $entFoto = 0;
        if ($request->entFoto == 'on') {
            $entFoto = 1;
        }
        $entMuestraFisica = 0;
        if ($request->entMuestraFisica == 'on') {
            $entMuestraFisica = 1;
        }
        $entProducto = 0;
        if ($request->entProducto == 'on') {
            $entProducto = 1;
        }

        $aConsideracion = 0;
        if ($request->aConsideracion == 'on') {
            $aConsideracion = 1;
        }

        $data = [
            'master' => $master,
            'folio_consecutivo' => $folio_consecutivo,
            'cotizacion' => $cotizacion,
            'dum' => $dummy,
            'render' => $render,
            'trazo' => $trazo,
            'render_dinamico' => $request->render_dinamico == 'on' ? 1 : 0,
            'instructivo' => $instructivo,
            'nombreCliente' => $nombreCliente,
            'nombre' => $nomOportunidadEstr,
            'descripcion' => $descripcion,
            'id_agente' => $idAgente,
            'fecha_registro' => $ticket->fecha_registro,
            'departamento' => 'VENTAS',
            'correo_usuario' => $correo_usuario,
            'nombre_usuario' => $nombre_usuario,
            'Estado' => $ticket->Estado,
            'Categoria' => $categoria,
            'ametropolitana' => $ametropolitana,
            'armado' => $armado,
            'barnices' => $barnices,
            'bolsa' => $bolsa,
            'cajaindividual' => $cajaindividual,
            'cajamaster' => $cajamaster,
            'cant1' => $cant1,
            'cant2' => $cant2,
            'cant3' => $cant3,
            'cant4' => $cant4,
            'cant5' => $cant5,
            'conAcetato' => $conAcetato,
            'desplegado' => $desplegado,
            'ecpAlto1' => $ecpAlto1,
            'ecpAlto2' => $ecpAlto2,
            'ecpAlto3' => $ecpAlto3,
            'ecpAlto4' => $ecpAlto4,
            'ecpCant1' => $ecpCant1,
            'ecpCant2' => $ecpCant2,
            'ecpCant3' => $ecpCant3,
            'ecpCant4' => $ecpCant4,
            'ecpDescr1' => $ecpDescr1,
            'ecpDescr2' => $ecpDescr2,
            'ecpDescr3' => $ecpDescr3,
            'ecpDescr4' => $ecpDescr4,
            'ecpFondo1' => $ecpFondo1,
            'ecpFondo2' => $ecpFondo2,
            'ecpFondo3' => $ecpFondo3,
            'ecpFondo4' => $ecpFondo4,
            'ecpFrente1' => $ecpFrente1,
            'ecpFrente2' => $ecpFrente2,
            'ecpFrente3' => $ecpFrente3,
            'ecpFrente4' => $ecpFrente4,
            'emplayado' => $emplayado,
            'enpaquetes' => $enpaquetes,
            'esp1' => $esp1,
            'esp2' => $esp2,
            'esp3' => $esp3,
            'esp4' => $esp4,
            'especifique' => $especifique,
            'flauta1' => $flauta1,
            'flauta2' => $flauta2,
            'flauta3' => $flauta3,
            'flauta4' => $flauta4,
            'foraneo' => $foraneo,
            'individual' => $individual,
            'instructivos' => $instructivos,
            'materiales1' => $materiales1,
            'materiales2' => $materiales2,
            'materiales3' => $materiales3,
            'materiales4' => $materiales4,
            'medida' => $medida,
            'mgAlto' => $mgAlto,
            'mgFondo' => $mgFondo,
            'mgFrente' => $mgFrente,
            'otroProductotexto' => $otroProductotexto,
            'otrosAcabados' => $otrosAcabados,
            'producto' => $producto,
            'puntos1' => $puntos1,
            'puntos2' => $puntos2,
            'puntos3' => $puntos3,
            'puntos4' => $puntos4,
            'suajado' => $suajado,
            'tipo_impresion' => $tipo_impresion,
            'transporte' => $transporte,
            'id_cliente' => $id_clienteINT,
            'nombre_agente' => $nombre_agente,
            'id_usuario' => $id_usuario,
            'id_usuario_cambio' => $id_usuario_cambio,
            'fecha_cambio' => $fecha_cambio,
            'tipo_medida_general' => $request->tipoMedidaGeneral,
            'tipo_medida_producto' => $request->tipoMedidaProducto,
            'entPlano' => $entPlano,
            'entRender' => $entRender,
            'entFoto' => $entFoto,
            'entMuestraFisica' => $entMuestraFisica,
            'entProducto' => $entProducto,
            'consideracionDisign' => $aConsideracion,
        ];

        $ticket->update($data);

        $estructural = $ticket->folio;

        $oportunidadNueva = $request->idOportunidadEstrNew;

        if ($relacionAnterior->idOportunidad != $oportunidadNueva) {
            // Eliminamos la relación con la oportunidad anterior
            DB::table('relOport_Estructural')->where('folio', $estructural)->delete();
    
            DB::statement(
                'exec spInsertRelacionESTROportunidad ?, ?, ?, ?',
                [
                    (int)$request->idOportunidadEstrNew,
                    $estructural,
                    $this->idUsuario(),
                    $fecha,
                ]
            );
        }

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($request->all(), true)));
        $this->bitacora($this->modulo, 'Editar ', 'Ticket | ' . $cadena . ' | ', $estructural, $this->loginUsuario(), 2);
        $this->bitacoraEstructural('TICKET ACTUALIZADO', $ticket->master , $ticket->folio, $nombre_usuario, null, $fecha_cambio);

        return response()->json([
            'response' => true,
            'message' => 'Ticket actualizado correctamente',
            'idTicketEstr' => $estructural,
            'folio_consecutivo' => $folio_consecutivo,
        ]);
    }

    // public function store(Request $request)
    // {
    // }

    // public function show($id)
    // {
    // }

    // public function edit($id)
    // {
    // }

    // public function update(Request $request, $id)
    // {
    // }

    // public function destroy($id)
    // {
    // }

    public function apiEstructural(Request $request)
    {
        $getResults = 1;
        try {
        if ($request->texto == null) {
            $texto = '';
        } else {
            $texto = $request->texto;
        }

        if ($request->abiertos == 'ABIERTOS') {
            $abiertos = "ABIERTOS";
        } else {
            $abiertos = "CERRADOS";

            if (
                $request->searchFolio == null &&
                $request->searchLiberado == null &&
                $request->searchCliente == null &&
                $request->searchTitulo == null &&
                $request->selectSituacion == null &&
                $request->selectOperador == null &&
                $request->selectEstatus == null &&
                $request->selectAgente == null &&
                $request->searchMotivoReapertura == null &&
                $request->searchOportunidad == null
            ) {
                $getResults = 0;
            }
        }

        $rol = $this->idRol();

        $idUsuario = $this->idUsuario();

        // Solo para gustavo
        if ($idUsuario == 4264) {
            $rol = 1026;
        }

        //ignacio r
        if ($idUsuario == 2115) {
            $rol = 1;
        }

        $agentes = $this->agentes();
        $diseñadores = $this->datosUsuario();
        $diseñador = null;
        if (count($diseñadores) > 0) {
            $diseñador = $diseñadores[0]->Nombre;
        }

        if (count($agentes) == 0) {
            $agentes = $this->datosUsuario();
            $agente = $agentes[0]->Id_Usuario;
        } else {
            $agente = $agentes[0]->idAgente;
        }

        if(!($request->idOportunidad == 0)) {
            $getResults = 1;
        }

        if ($getResults == 0) {
            $Estructural = collect([]);
        } else {
            if ($request->id_cliente_int > 0) {
                $Estructural = DB::select('exec sp_getEstructuralCliente ?', [$request->id_cliente_int]);
                // $Estructural = DB::table('v_Estructural');
            } else {
                switch ($rol) {
                    case "3":
                    case "1016":
                        if ($request->idOportunidad == 0) {
                            $Estructural = DB::select('exec sp_getApiEstructural ?,?,?', [$texto, $abiertos, 2]);
                        } else {
                            $Estructural = DB::select('exec sp_getEstructural ?', [$request->idOportunidad]);
                        }
                        break;
                    case "4":
                    case "9":
    
                        if ($idUsuario == 29) {
                            if ($request->idOportunidad == 0) {
                                $Estructural = DB::select('exec sp_getApiEstructuralAgenteEspecial ?,?,?,?', [$texto, $agente, 9, $abiertos]);
                            } else {
                                $Estructural = DB::select('exec sp_getEstructural ?', [$request->idOportunidad]);
                            }
                            break;
                        }
    
                    case "1022":
                        if ($request->idOportunidad == 0) {
                            $Estructural = DB::select('exec sp_getApiEstructuralAgente ?,?,?', [$texto, $agente, $abiertos]);
                        } else {
                            $Estructural = DB::select('exec sp_getEstructural ?', [$request->idOportunidad]);
                        }
                        break;
                    case "1021":
                        if ($request->idOportunidad == 0) {
                            $Estructural = DB::select('exec sp_getApiEstructuralDiseñador ?,?,?,?', [$texto, $diseñador, $abiertos, 1]);
                        } else {
                            $Estructural = DB::select('exec sp_getEstructuralLiberado ?', [$request->idOportunidad]);
                        }
                        break;
                    case "1020":
                        if ($request->idOportunidad == 0) {
                            $Estructural = DB::select('exec sp_getApiEstructural ?,?,?', [$texto, $abiertos, 2]);
                        } else {
                            $Estructural = DB::select('exec sp_getEstructuralLiberado ?', [$request->idOportunidad]);
                        }
                        break;
                    case "10":
                        if ($request->idOportunidad == 0) {
                            $Estructural = DB::select('exec sp_getApiEstructural ?,?,?', [$texto, $abiertos, 2]);
                        } else {
                            $Estructural = DB::select('exec sp_getEstructural ?', [$request->idOportunidad]);
                        }
                        break;
                    default:
                        if ($request->idOportunidad == 0) {
                            $Estructural = DB::select('exec sp_getApiEstructural ?,?,?', [$texto, $abiertos, 2]);
                        } else {
                            $Estructural = DB::select('exec sp_getEstructural ?', [$request->idOportunidad]);
                        }
                        break;
                }
            }
        }

        // De acuerdo a los resultados filtramos los resultados
        if (count($Estructural) > 0) {
            $Estructural = collect($Estructural)->filter(function ($item) use ($request) {
                return collect([
                    'searchFolio' => 'master',
                    'searchLiberado' => 'liberado',
                    'searchCliente' => 'Cliente',
                    'searchTitulo' => 'Titulo',
                    'selectSituacion' => 'Situacion',
                    'selectOperador' => 'Asignado',
                    'selectEstatus' => 'Estado',
                    'selectAgente' => 'Agente',
                    'searchMotivoReapertura' => 'motivoEstructural',
                    'searchOportunidad' => 'FolioOportunidad',
                ])->every(function ($field, $filter) use ($request, $item) {
                    if ($request->$filter != null && $request->$filter != '') {
                        return stripos($item->$field, $request->$filter) !== false;
                    }
                    return true;
                });
            });
        } else {
            $Estructural = collect([]);
        }

        // Eliminamos los elementos nulos y duplicados
        $Estructural = $Estructural->filter(function ($item) {
            return $item !== null;
        })->unique('Id_Ticket')->values();

        return DataTables::of($Estructural)
            ->addColumn('inputLogin', function () {
                $sesionLogin = trim(session('usuario'));
                $inputLogin = $sesionLogin;

                return $inputLogin;
            })
            ->addColumn('inputRol', function () use ($idUsuario) {
                $inputRol = $this->idRol();

                // Solo para gustavo
                if ($idUsuario == 4264) {
                    $inputRol = 1026;
                }

                //ignacio r
                if ($idUsuario == 2115) {
                    $inputRol = 1;
                }

                return $inputRol;
            })
            ->addColumn('horasInp', function ($row) use ($idUsuario) {

                $inputRol = $this->idRol();

                // Solo para gustavo
                if ($idUsuario == 4264) {
                    $inputRol = 1026;
                }

                //ignacio r
                if ($idUsuario == 2115) {
                    $inputRol = 1;
                }

                if (($inputRol == 1021 || $inputRol == 1020 || $inputRol == 1 || $inputRol == 8) && $row->Asignado != 'SIN ASIGNAR' && ($row->Estado != 'CERRADO' && $row->Estado != 'CANCELADO' && $row->Estado != 'RECHAZADO')) {
                    return '<div><input type="number" name="horas" onchange="guardarHoras(this.value, '.$row->Id_Ticket.', '.$row->horas.')" class="no-styles" value="'.$row->horas.'" /></div>';
                } else {
                    return '<div>'.$row->horas.'</div>';
                }
                
            })
            ->addColumn('id_Usuario', function ($row) use ($idUsuario) {
                return $idUsuario;
            })
            ->rawColumns(['inputLogin', 'inputRol', 'horasInp', 'id_Usuario'])
            ->make(true);
        } catch (\Exception $e) {
            return "Error al cargar la tabla";
        }
    }

    public function getEstatusESTR()
    {
        $estatus_estr = DB::select('exec sp_getCatEstructuralEstatus');
        return $estatus_estr;
    }

    public function getOperadorESTR()
    {
        $operador_estr = DB::select('exec sp_getCatEstructuralOperadores');
        return $operador_estr;
    }

    public function cambiarEstatusESTR(Request $request)
    {
        $this->configMail();

        $Fechafinalizado = null;
        $motivo_cancelacion = null;
        $id_usuario_cambio = $this->idUsuario();
        $FechaCambio = $request->fecha_cerrado;

        if ($request->estatus == 'CERRADO' || $request->estatus == 'CANCELADO') {
            $Fechafinalizado = $request->fecha_cerrado;
            $motivo_cancelacion = strtoupper($request->motivoCancelacion);
        }

        $datos_usuario = $this->datosUsuario();
        $nombre_asignado = $datos_usuario[0]->Nombre;


        $rol = $this->idRol();
        $ticket = v_Estructural::where('Id_Ticket', $request->id)->first();
        $alertasPlantillas = catPlantillasAlertas::get();
        $cuenta_usuario = $ticket->CorreoAgente;

        $cuentasAuxiliares = [];

        $rel = DB::table('rel_Agente_Aux')->where('idAgente', $ticket->id_agente)->first();
        if ($rel != null) {
            $cuentasAuxiliares = [
                $rel->idAuxiliar,
                $rel->idAuxiliar1,
                $rel->idAuxiliar2,
                $rel->idAuxiliar3,
            ];

            // eLIMINAMOS LOS NULOS Y DUPLICADOS
            $cuentasAuxiliares = array_filter($cuentasAuxiliares, function ($value) {
                return $value !== null;
            });
            $cuentasAuxiliares = array_unique($cuentasAuxiliares);
            $cuentasAuxiliares = DB::table('v_catUsuariosAll')->whereIn('Id_Usuario', $cuentasAuxiliares)->pluck('Correo')->toArray();
        }

        $cuenta_cc1 = $ticket->CorreoSolicitante;
        $datos_usuario = $this->datosUsuario();
        $nombre_proyecto = $ticket->Tarea;
        $folio = $ticket->master;
        $nombre_agente = $ticket->Agente;
        $route = "estructural";
        $fecha_solicitud = $ticket->FechaRegistro;
        $asignadoA = $ticket->Asignado;
        $motivo_cambio_fecha = null;

        if ($rol == 1 || $rol == 1020 || $rol == 8) {
            $ticket_atendiendose = v_Estructural::where('Asignado', $asignadoA)->where('Estado', 'ATENDIENDOSE')->first();
        } else {
            $ticket_atendiendose = v_Estructural::where('Asignado', $nombre_asignado)->where('Estado', 'ATENDIENDOSE')->first();
        }

        if ($ticket_atendiendose !== null) {
            DB::statement('exec sp_UpdateEstatusESTR ?, ?, ?, ?, ?, ?', [
                $ticket_atendiendose->Id_Ticket,
                'PAUSADO',
                $Fechafinalizado,
                $motivo_cancelacion,
                $id_usuario_cambio,
                $FechaCambio
            ]);
        }

        DB::statement('exec sp_UpdateEstatusESTR ?, ?, ?, ?, ?, ?', [
            $request->id,
            $request->estatus,
            $Fechafinalizado,
            $motivo_cancelacion,
            $id_usuario_cambio,
            $FechaCambio
        ]);

        $ticket_cotizacion = v_Estructural::where('cotizacion', 1)->where('Id_Ticket', $request->id)->where('FolioOportunidad', null)->first();

        if ($ticket_cotizacion !== null) {
            $id_Equipo = DB::table('v_catEquiposIntegrantes')->where('Nombre', $ticket_cotizacion->Agente)->get();
            $id_ClienteINT = Clientes::where('idCliente', $ticket_cotizacion->idCliente)->first();
            $id_Contacto = Contactos::where('idClienteINT', $id_ClienteINT->ClienteINT)->where('activo', 1)->first();
    
            if ($request->estatus == 'CERRADO' && $ticket_cotizacion !== null) {
                $fecha = new \DateTime();
                $maquila = '';
                
                $folio = DB::table('Oportunidades')->max('folio') + 1;
                
                $prospecto = DB::table('v_catClientesActivosCombo')->where('idCliente', $request->idCliente)->where('idClienteINT', 0)->get();
                $op_prospecto = 0;
                
                if (count($prospecto) > 0) {
                    if ($prospecto[0]->Tipo == 'PROSPECTO') {
                        $op_prospecto = 1;
                    }
                }
                    
                DB::statement(
                    'exec spInsertOportunidad ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                    [
                        $ticket_cotizacion->idCliente,
                        $ticket_cotizacion->Tarea,
                        2,
                        1,
                        1,
                        1,
                        $ticket_cotizacion->id_agente,
                        $maquila,
                        0,
                        $fecha->format('d-m-Y H:i:s'),
                        $this->idUsuario(),
                        null,
                        $id_Equipo[0]->idEquipo,
                        $fecha->format('d-m-Y H:i:s'),
                        $folio,
                        $op_prospecto,
                        $id_Contacto->idContacto,
                    ]
                );
    
                $this->bitacora($this->modulo, 'Agregar ', 'Oportunidad desde Diseño Estructural ', $folio, $this->loginUsuario(), 5);
            }

        }

        if ($request->estatus == 'CANCELADO') {
            if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                $cuenta_cc2 = $alertasPlantillas[13]->Destinatario;
            } else {
                if ($this->idRol() == 1) {
                    $cuenta_cc2 = $alertasPlantillas[13]->Destinatario;
                } else {
                    $cuenta_cc2 = $datos_usuario[0]->Correo;
                }
            }
            $cuenta_cc3 = $alertasPlantillas[13]->Destinatario;
            $subject = $alertasPlantillas[13]->Asunto;
            $mensaje = $alertasPlantillas[13]->Mensaje;

            Mail::to($cuenta_usuario)->cc(array_merge([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3], $cuentasAuxiliares))->send(new SendMailEstructural($alertasPlantillas[13], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha));
        }

        if ($request->estatus == 'CERRADO') {
            $motivo_cancelacion = strtoupper($request->motivoCancelacion);

            DB::table('motivosEstructural')->insert([
                'motivo' => $motivo_cancelacion,
                'estatus' => $request->estatus,
                'ticket_id' => $request->id,
                'usuario_id' => $id_usuario_cambio,
            ]);

            if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                $cuenta_cc2 = $alertasPlantillas[14]->Destinatario;
            } else {
                if ($this->idRol() == 1) {
                    $cuenta_cc2 = $alertasPlantillas[14]->Destinatario;
                } else {
                    $cuenta_cc2 = $datos_usuario[0]->Correo;
                }
            }
            $cuenta_cc3 = $alertasPlantillas[14]->Destinatario;
            $subject = $alertasPlantillas[14]->Asunto;
            if ($ticket_cotizacion !== null){
                $mensaje = $alertasPlantillas[14]->Mensaje . ' y se generó una nueva Oportunidad con base en este Ticket';
            } else {
                $mensaje = $alertasPlantillas[14]->Mensaje;
            }

            Mail::to($cuenta_usuario)->cc(array_merge([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3], $cuentasAuxiliares))->send(new SendMailEstructural($alertasPlantillas[14], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha));
        }

        $anterior = $ticket->Estado;
        if ($request->estatus == 'CANCELADO') {
            $this->bitacoraEstructural('EL ESTATUS DEL TICKET CAMBIO A : ' . $request->estatus . ' POR MOTIVO DE: ' . $motivo_cancelacion, $folio , $request->id, $nombre_asignado, $anterior, $FechaCambio);
        } else {
            $this->bitacoraEstructural('EL ESTATUS DEL TICKET CAMBIO A : ' . $request->estatus, $folio , $request->id, $nombre_asignado, $anterior, $FechaCambio);
        }

        if ($request->estatus == 'NOTIFICAR A VENTAS') {
            DB::table('motivosEstructural')->insert([
                'motivo' => $motivo_cancelacion,
                'estatus' => $request->estatus,
                'ticket_id' => $request->id,
                'usuario_id' => $id_usuario_cambio,
            ]);

            if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                $cuenta_cc2 = $alertasPlantillas[21]->Destinatario;
            } else {
                if ($this->idRol() == 1) {
                    $cuenta_cc2 = $alertasPlantillas[21]->Destinatario;
                } else {
                    $cuenta_cc2 = $datos_usuario[0]->Correo;
                }
            }
            $cuenta_cc3 = $alertasPlantillas[21]->Destinatario;
            $subject = $alertasPlantillas[21]->Asunto;
            $mensaje = $alertasPlantillas[21]->Mensaje;
            $motivo_cancelacion = strtoupper($request->motivoCancelacion);
            $copiarA = $alertasPlantillas[21]->CopiarA;

            $finalCopia = explode(',', $copiarA);
            $copias = array_merge([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3], $finalCopia);
            // Eliminamos los espacios en blanco de las copias y duplicados
            $copias = array_unique(array_filter(array_map('trim', $copias)));
            $copias = array_merge($copias, $cuentasAuxiliares);

            Mail::to($cuenta_usuario)->cc($copias)->send(new SendMailEstructural($alertasPlantillas[21], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, null));
            // Pruebas
            // Mail::to('cgonzalez@litoprocess.com')->send(new SendMailEstructural($alertasPlantillas[21], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, null));

            $this->bitacoraEstructural('TICKET NOTIFICADO A VENTAS', $folio , $request->id, $nombre_asignado, $anterior, $FechaCambio);
        }
        
        if ($request->estatus == 'REABRIR TICKET') {
            DB::table('motivosEstructural')->insert([
                'motivo' => $motivo_cancelacion,
                'estatus' => $request->estatus,
                'ticket_id' => $request->id,
                'usuario_id' => $id_usuario_cambio,
            ]);

            DB::table('Estructural')->where('folio', $request->id)->update([
                'cat_motivo_re_ticket_id' => $request->motivoReapertura,
            ]);

            if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                $cuenta_cc2 = $alertasPlantillas[22]->Destinatario;
            } else {
                if ($this->idRol() == 1) {
                    $cuenta_cc2 = $alertasPlantillas[22]->Destinatario;
                } else {
                    $cuenta_cc2 = $datos_usuario[0]->Correo;
                }
            }
            $cuenta_cc3 = $alertasPlantillas[22]->Destinatario;
            $subject = $alertasPlantillas[22]->Asunto;
            $mensaje = $alertasPlantillas[22]->Mensaje;
            $motivo_cancelacion = strtoupper($request->motivoCancelacion);

            Mail::to($cuenta_usuario)->cc(array_merge([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3], $cuentasAuxiliares))->send(new SendMailEstructural($alertasPlantillas[22], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, null));
            // Pruebas
            // Mail::to('cgonzalez@litoprocess.com')->send(new SendMailEstructural($alertasPlantillas[22], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, null));

            $this->bitacoraEstructural('TICKET REABIERTO', $folio , $request->id, $nombre_asignado, $anterior, $FechaCambio);
        }

        return $ticket_cotizacion;
    }

    public function cambiarOperadorESTR(Request $request)
    {
        $this->configMail();

        $id_usuario_cambio = $this->idUsuario();
        $FechaCambio = $request->fecha_cambio;
        $motivo_cancelacion = null;
        
        $ticket = v_Estructural::where('Id_Ticket', $request->id)->first();
        
        $motivo_cambio_fecha = $ticket->motivoCambioFecha;
        
        DB::statement('exec sp_UpdateOperadorESTR ?, ?, ?, ?', [
            $request->id,
            $request->operador,
            $FechaCambio,
            $id_usuario_cambio,
        ]);

        $alertasPlantillas = catPlantillasAlertas::get();
        $cuenta_usuario = $ticket->CorreoOperador;

        if ($cuenta_usuario == null || $cuenta_usuario == '') {
            // Buscamos el operador en la tabla de usuarios
            $cuenta_usuario = DB::table('v_OperadoresEstructural')->where('Nombre', $request->operador)->first();

            if ($cuenta_usuario != null) {
                $cuenta_usuario = $cuenta_usuario->Correo;
            } else {
                $cuenta_usuario = null;
            }
        }

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
        $asignadoA = $request->operador;

        if ($request->operador == 'SIN ASIGNAR') {
            DB::statement('exec sp_UpdateEstatusESTR ?, ?, ?, ?, ?, ?', [
                $request->id,
                'PENDIENTE DE ASIGNAR',
                null,
                null,
                $id_usuario_cambio,
                $FechaCambio,
            ]);

            DB::statement('exec sp_asignarFechaCompromisoESTR ?, ?, ?, ?, ?', [
                $request->id,
                null,
                $id_usuario_cambio,
                $FechaCambio,
                $motivo_cambio_fecha,
            ]);
        } else {
            DB::statement('exec sp_UpdateEstatusESTR ?, ?, ?, ?, ?, ?', [
                $request->id,
                'PENDIENTE DE ATENDER',
                null,
                null,
                $id_usuario_cambio,
                $FechaCambio,
            ]);
        }

        $this->bitacoraEstructural('TICKET ASIGNADO A : ' . $request->operador, $folio , $request->id, $datos_usuario[0]->Nombre, $ticket->Asignado, $FechaCambio);

        Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3])->send(new SendMailEstructural($alertasPlantillas[12], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, null));
    }

    public function asignarFechaCompromiso(Request $request)
    {
        $this->configMail();

        $id_usuario_cambio = $this->idUsuario();
        $FechaCambio = $request->fecha_cambio;
        $motivo_cambio_fecha = strtoupper($request->motivoCambioFecha);
        $motivo_cancelacion = null;

        $ticket = v_Estructural::where('Id_Ticket', $request->id)->first();

        DB::statement('exec sp_asignarFechaCompromisoESTR ?, ?, ?, ?, ?', [
            $request->id,
            $request->fecha,
            $id_usuario_cambio,
            $FechaCambio,
            $motivo_cambio_fecha,
        ]);
        DB::table("Estructural")->where(["folio"=>$request->id])->update(["FechaRespuesta"=>$FechaCambio]);


    

        if ($motivo_cambio_fecha != '') {
            $alertasPlantillas = catPlantillasAlertas::get();
            $cuenta_usuario = $ticket->CorreoAgente;

            $cuentasAuxiliares = [];
            $rel = DB::table('rel_Agente_Aux')->where('idAgente', $ticket->id_agente)->first();
            if ($rel != null) {
                $cuentasAuxiliares = [
                    $rel->idAuxiliar,
                    $rel->idAuxiliar1,
                    $rel->idAuxiliar2,
                    $rel->idAuxiliar3,
                ];

                // eLIMINAMOS LOS NULOS Y DUPLICADOS
                $cuentasAuxiliares = array_filter($cuentasAuxiliares, function ($value) {
                    return $value !== null;
                });
                $cuentasAuxiliares = array_unique($cuentasAuxiliares);
                $cuentasAuxiliares = DB::table('v_catUsuariosAll')->whereIn('Id_Usuario', $cuentasAuxiliares)->pluck('Correo')->toArray();
            }

            $cuenta_cc1 = $ticket->CorreoSolicitante;
            $datos_usuario = $this->datosUsuario();
            if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                $cuenta_cc2 = $alertasPlantillas[16]->Destinatario;
            } else {
                if ($this->idRol() == 1) {
                    $cuenta_cc2 = $alertasPlantillas[16]->Destinatario;
                } else {
                    $cuenta_cc2 = $datos_usuario[0]->Correo;
                }
            }
            $cuenta_cc3 = $alertasPlantillas[16]->Destinatario;
            $nombre_proyecto = $ticket->Tarea;
            $folio = $ticket->master;
            $subject = $alertasPlantillas[16]->Asunto;
            $nombre_agente = $ticket->Agente;
            $route = "estructural";
            $fecha_solicitud = $ticket->FechaRegistro;
            $mensaje = $alertasPlantillas[16]->Mensaje;
            $asignadoA = $ticket->Asignado;

            Mail::to($cuenta_usuario)->cc(array_merge([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3], $cuentasAuxiliares))->send(new SendMailEstructural($alertasPlantillas[16], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha));
        }

        $datos_usuario = $this->datosUsuario();
        $nombre_usuario = $datos_usuario[0]->Nombre;

        $fecha_compromiso = Carbon::parse($request->fecha)->format('d/m/Y');

        if ($motivo_cambio_fecha != '') {
            $this->bitacoraEstructural('FECHA COMPROMISO CAMBIADA : ' . $fecha_compromiso . ' POR MOTIVO DE: ' . $motivo_cambio_fecha, $ticket->master , $ticket->Id_Ticket, $nombre_usuario, $ticket->FechaCompromiso, $FechaCambio);
        } else {
            $this->bitacoraEstructural('FECHA COMPROMISO ASIGNADA : ' . $fecha_compromiso, $ticket->master , $ticket->Id_Ticket, $nombre_usuario, $ticket->FechaCompromiso, $FechaCambio);
        }
    }

    public function obtenerTicket(Request $request)
    {
        $ticket = v_EstructuralXId::where('folio', $request->id)->first();
        return $ticket;
    }

    public function obtenerClientesCombo()
    {
        $permisos =  $this->permisosModulo($this->modulo);
        $idrol = $permisos[0]->idRol;
        $id_usuario = $this->idUsuario();

        // Solo para gustavo
        if ($id_usuario == 4264) {
            $idrol = 1026;
        }

        //ignacio r
        if ($id_usuario == 2115) {
            $idrol = 1;
        }

        $agentes = $this->agentes();
        if (count($agentes) == 0) {
            $agentes = $this->datosUsuario();
            $idAgente = $agentes[0]->Id_Usuario;
        } else {
            $idAgente = $agentes[0]->idAgente;
        }

        switch ($idrol) {
            case '4':
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $id_usuario)->get();
                break;
            case '9':
                if ($id_usuario == 29) {
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $idAgente)->orWhere('idAgente', 9)->get();
                } else {
                    $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->where('idAgente', $idAgente)->get();
                }
                break;
            case '3':
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->get();
                break;
            case '1022':
                $combo_clientes = DB::table('v_catClientesActivosCombo')->where('idClienteINT', 22590)->orderBy('razonsocial', 'asc')->get();
                break;
            default:
                $combo_clientes = DB::table('v_catClientesActivosCombo')->orderBy('razonsocial', 'asc')->get();
                break;
        }

        return $combo_clientes;
    }

    public function obtenerBarnices()
    {
        $tipo_barnices = DB::table('catEPtipoBarnices')->get();
        return $tipo_barnices;
    }

    public function obtenerFolio()
    {
        $folio = Estructural::max('master') + 1;
        return $folio;
    }

    public function DErelation(Request $request)
    {
        $fecha = new DateTime();
        $data = $request->all();
        $de = $data['DErelations'];

        foreach ($de as $key => $value) {
            foreach ($value as $key1 => $value1) {
                // return $value1;
                DB::statement(
                    'exec spInsertRelacionESTROportunidad ?, ?, ?, ?',
                    [
                        (int)$value1['idOportunidadDE'],
                        (int)$value1['idDE'],
                        $this->idUsuario(),
                        $fecha->format('d-m-Y H:i:s'),
                    ]
                );
            }
        }
    }

    public function apiEstructuralDocs(Request $request)
    {
        // return $request;
        $idCliente = null;

        if ($request->idTicket == 0 || $request->idTicket == null) {

            $Documentos = DB::select('exec sp_DocumentosEstr @idEstructural=0, @idCliente=' . (int)$request->IdCliente);

            // $Documentos = DB::table('v_DocumentosEstructural')->where('idCliente', (int)$request->IdCliente)->where('folio', 0)->get();
        } else {
            $Documentos = DB::select('exec sp_DocumentosEstr @idEstructural=' . $request->idTicket . ', @idCliente=' . (int)$request->IdCliente);

            // $Documentos = DB::table('v_DocumentosEstructural')->where('folio', $request->idTicket)->get();
        }

        $idUsuario = $this->idUsuario();
        $rol = $this->idRol();

        // Solo tipos de documentos activos
        if ($rol == 1020 || $rol == 1021 || $rol == 1 || $rol == 8) {
            $Documentos = collect($Documentos)->filter(function ($item) {
                return in_array($item->idTipoDocumento, [10, 11]);
            });
        } elseif($rol == 1022 || $rol == 1021 || $rol == 1026) {
            $Documentos = collect($Documentos)->filter(function ($item) {
                return in_array($item->idTipoDocumento, [10]);
            });
        } else {
            $Documentos = collect($Documentos)->filter(function ($item) {
                return in_array($item->idTipoDocumento, [11]);
            });
        }

        $Documentos = collect($Documentos)->filter(function ($item) {
            return $item->estatus == 1;
        });

        // Solo para gustavo
        if ($idUsuario == 4264) {
            $rol = 1026;
        }

        return DataTables::of($Documentos)
            ->addColumn('idUsuario', function ($row) use ($idUsuario) {
                return $idUsuario;
            })
            ->addColumn('rol', function ($row) use ($rol) {
                return $rol;
            })
            ->make(true);
    }

    public function upDocsEstructural(Request $request)
    {
        try {
            $idUsuarioReg = (int)$this->idUsuario();
            $folio = $request->folio;
            $folio = (int)$folio;
            $cliente = $this->getTicketEstrOport($folio);
            $cliente = (int)$cliente;
            $idTipoDocumento = $request->idTipoDocumento;
            $idTipoDocumento = (int)$idTipoDocumento;
            $fecha = $request->fecha_cambio;
            // $file = $request->file('files');
            $file = $_FILES['fileEstr'];

            // Validar que el archivo no pese más de 15 MB
            if ($file['size'] > 15 * 1024 * 1024) {
            // if ($file['size'] > 10 * 1024 * 1024) {
                return response()->json(['error' => 'El archivo no puede pesar más de 15 MB.', 'response' => 'error'], 200);
            }

            $Extencion = $file['type'];
            $Size = $file['size'];
            $descDocumento =  $file['name'];
            $Documento = base64_encode(file_get_contents($_FILES["fileEstr"]["tmp_name"]));

            DB::statement(" EXEC sp_updateEstructuralDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:folio, :idCliente,:idTipoDocumento", [
                ':descDocumento' => $descDocumento,
                ':Documento' => $Documento,
                ':Extencion' => $Extencion,
                ':Size' => $Size,
                ':idUsuarioReg' => $idUsuarioReg,
                ':folio' => $folio,
                ':idCliente' => $cliente,
                ':idTipoDocumento' => $idTipoDocumento
            ]);

            $idDocumentoEstructural = DB::table('DocumentosEstructural')->where('idTipoDocumento', $idTipoDocumento)->where('idUsuarioReg', $idUsuarioReg)->max('id_documentosEstructural');

            if ($idTipoDocumento != 11) {
                DB::statement(" EXEC sp_updateOportDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento", [
                    ':descDocumento' => $descDocumento,
                    ':Documento' => $Documento,
                    ':Extencion' => $Extencion,
                    ':Size' => $Size,
                    ':idUsuarioReg' => $idUsuarioReg,
                    ':idOportunidad' => $request->idOportunidad,
                    ':idTipoDocumento' => 20
                ]);

                $idDocumentoOport = DB::table('DocumentosOport')->where('idTipoDocumento', 20)->where('idUsuarioReg', $idUsuarioReg)->max('idDocumento');

                DB::table('DocumentosOport')->where('idDocumento', $idDocumentoOport)->update(['idDocEst' => $idDocumentoEstructural]);
            }

            $this->bitacora($this->modulo, 'Agregar Documento ' . (string)$descDocumento, ' Ticket ', $request->folio, $this->loginUsuario(), 5);

            $ticket = Estructural::where('folio', $folio)->first();

            $tipoDoc = DB::table('catTipoDocumentoEstr')->where('idTipoDocumento', $idTipoDocumento)->first();

            if (in_array($idTipoDocumento, [1, 2, 4])) {
                $this->bitacoraEstructural('DOCUMENTO DE TIPO: ' . $tipoDoc->descTipoDocumento . ' AGREGADO AL TICKET, NOMBRE DOCUMENTO: ' . $descDocumento, $ticket->master , $folio, $this->loginUsuario(), null, $fecha);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al subir el documento', 'response' => 'error'], 500);
        }
    }

    public function getDocumentoEstructural(Request $request)
    {
        $idDocumento = $request->idDocumento;
        $result = DocumentosEstructural::where('id_documentosEstructural', (int) $idDocumento)->first();

        return $result;
    }

    public function getBriefEstructural(Request $request)
    {
        $idDocumento = $request->idDocumento;

        $ticket = Estructural::where('folio', $idDocumento)->first();
        $dompdf = App::make("dompdf.wrapper");





        $result = DocumentosEstructural::where('id_documentosEstructural', (int) $idDocumento)->first();

        return $result;
    }

    public function downDocsEstructural($idDoc)
    {
        $idDocumentosAsignados = $idDoc;
        $result = DocumentosEstructural::where('id_documentosEstructural', $idDocumentosAsignados)->first();

        $file = $result->Documento;
        $Tipo = $result->Extension;
        $descDocumento = $result->descDocumento;

        $base64 = base64_decode($file);

        return Response::make($base64, 200, ['Content-Type' => $Tipo, 'Content-Disposition' => 'attachment; filename="' . $descDocumento . '"']);
    }

    public function deleteDocsEstructural(Request $request)
    {
        $idDocumentoEstrdelete = $request->idDocumentoEstrdelete;
        $idDocumentoEstrdelete = (int)$idDocumentoEstrdelete;
        $documento = DocumentosEstructural::where('id_documentosEstructural', $idDocumentoEstrdelete)->first();
        DocumentosEstructural::where('id_documentosEstructural', $idDocumentoEstrdelete)->delete();
        relEstr_Documento::where('idDocumento', $idDocumentoEstrdelete)->delete();
        DocumentosOport::where('idDocEst', $idDocumentoEstrdelete)->delete();

        $this->bitacora($this->modulo, 'Eliminar Documento ' . $documento->descDocumento, ' Ticket ', $documento->folio, $this->loginUsuario(), 3);
    }

    public function download_ticket($idTicket)
    {
        $ticket = Estructural::where('folio', $idTicket)->first();
        $dompdf = App::make("dompdf.wrapper");
        $dompdf->loadView("pdf/brief_estructural", ['dto' => $ticket]);

        return $dompdf->stream(); //Para visualizar en el navegador

    }

    public function liberar(Request $request)
    {
        $fecha = new DateTime();

        DB::statement(
            'exec sp_liberarTicket ?, ?, ?, ?',
            [
                1,
                $request->id_ticket,
                $this->idUsuario(),
                $fecha->format('d-m-Y H:i:s'),
            ]
        );

        $ticket = v_Estructural::where('Id_Ticket', $request->id_ticket)->first();
        $datos_usuario = $this->datosUsuario();
        $nombre_usuario = $datos_usuario[0]->Nombre;

        $this->bitacoraEstructural('TICKET LIBERADO POR ' . $nombre_usuario, $ticket->master, $request->id_ticket, $nombre_usuario, null, $fecha->format('d-m-Y H:i:s'));
    }

    public function getDocumentoEstrMail(Request $request)
    {
        $idDocumento = $request->idDocumento;
        $result = DocumentosEstrMail::where('idDocumento', (int) $idDocumento)->first();

        return $result;
    }

    public function apiBitacoraEstructural(Request $request)
    {
        $bitacora = BitacoraEstructural::where('id_ticket', $request->id)->get();
        return DataTables::of($bitacora)
            ->make(true);
    }

    public function enviarMovimientoEstr(Request $request){
        $this->configMail();

        $ticket = v_Estructural::where('Id_Ticket', $request->id)->first();

        $datos_usuario = $this->datosUsuario();
        $nombre_usuario = $datos_usuario[0]->Nombre;

        $alertasPlantillas = catPlantillasAlertas::get();
        $cuenta_usuario = $ticket->CorreoSolicitante;
        if ($ticket->CorreoOperador == null) {
            $cuenta_cc1 = $ticket->CorreoSolicitante;
        } else {
            $cuenta_cc1 = $ticket->CorreoOperador;
        }
        
        if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
            $cuenta_cc2 = $alertasPlantillas[18]->Destinatario;
        } else {
            if ($this->idRol() == 1) {
                $cuenta_cc2 = $alertasPlantillas[18]->Destinatario;
            } else {
                $cuenta_cc2 = $datos_usuario[0]->Correo;
            }
        }
        $cuenta_cc3 = $alertasPlantillas[18]->Destinatario;
        $nombre_proyecto = $ticket->Tarea;
        $folio = $ticket->master;
        $subject = $alertasPlantillas[18]->Asunto;
        $nombre_agente = $ticket->Agente;
        $route = "estructural";
        $fecha_solicitud = $ticket->FechaRegistro;
        $mensaje = strtoupper($request->movimiento);
        $asignadoA = $ticket->Asignado;

        Mail::to($cuenta_usuario)->cc([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3])->send(new SendMailEstructural($alertasPlantillas[18], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, null, null));

        $this->bitacoraEstructural(strtoupper($request->movimiento) , $ticket->master, $ticket->Id_Ticket, $nombre_usuario, null, $request->fechaBitacoraEstr);

    }

    /**
     * Función para actualizar las horas de un ticket
     * 
     * @param Request $request
     * @return mixed
     */
    public function storeHoursTicket(Request $request)
    {
        try {
            if ($request->horas == null || $request->horas == '') {
                return response()->json(['response' => false]);
            }

            // Validar que el ticket existe y actualizar las horas
            $ticket = Estructural::where('folio', $request->id_ticket)->first();

            if ($ticket == null) {
                return response()->json(['response' => false, 'message' => 'Ticket no encontrado.']);
            }

            $horasPrevias = $ticket->horas;

            $ticket->horas = $request->horas;
            $ticket->save();

            $usuario = $this->datosUsuario()[0]->Nombre;

            // Guardar en la bitácora
            $this->bitacoraEstructural('HORAS '. ($horasPrevias == null ? 'REGISTRADAS' : 'ACTUALIZADAS') . ': ' . $request->horas, $ticket->master, $ticket->folio, $usuario, $horasPrevias, DB::raw('GETDATE()'));

            return response()->json(['response' => true, 'message' => 'Horas actualizadas correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['response' => false, 'message' => 'Error al actualizar las horas.']);
        }
    }

    /**
     * Función para guardar el cambio de si una oportunidad es trabajo repetitiva
     * 
     * @param Request $request
     * @return mixed
     */
    public function liberarTicket(Request $request)
    {
        try {
            $idUsuario = $this->idUsuario();

            $ticket = Estructural::where('folio', $request->id_ticket)->first();
            
            $fecha = new DateTime();
            $fecha = $fecha->format('d-m-Y H:i:s');

            if ($request->tipo == 0) {
                // Realizamos la liberación del ticket
                DB::statement('exec sp_registerMotivoEstructural ?,?,?', [
                    $request->motivo,
                    $request->id_ticket,
                    $idUsuario
                ]);

                // Realizamos el guardado del cambio
                DB::statement('exec sp_updateTicketLiberado ?,?', [
                    $request->id_ticket,
                    $request->tipo
                ]);

                // Actualizamos el estatus del ticket
                DB::statement('exec sp_UpdateEstatusESTR ?, ?, ?, ?, ?, ?', [
                    $request->id_ticket,
                    'RECHAZADO',
                    $fecha,
                    $request->motivo,
                    $idUsuario,
                    $fecha,
                ]);

                $motivo_cancelacion = strtoupper($request->motivo);
                $motivo_cambio_fecha = null;
                $ticket = v_Estructural::where('Id_Ticket', $request->id_ticket)->first();
                $alertasPlantillas = catPlantillasAlertas::get();
                $cuenta_usuario = $ticket->CorreoAgente;

                $cuentasAuxiliares = [];
                $rel = DB::table('rel_Agente_Aux')->where('idAgente', $ticket->id_agente)->first();
                if ($rel != null) {
                    $cuentasAuxiliares = [
                        $rel->idAuxiliar,
                        $rel->idAuxiliar1,
                        $rel->idAuxiliar2,
                        $rel->idAuxiliar3,
                    ];

                    // ELIMINAMOS LOS NULOS Y DUPLICADOS
                    $cuentasAuxiliares = array_filter($cuentasAuxiliares, function ($value) {
                        return $value !== null;
                    });
                    $cuentasAuxiliares = array_unique($cuentasAuxiliares);
                    $cuentasAuxiliares = DB::table('v_catUsuariosAll')->whereIn('Id_Usuario', $cuentasAuxiliares)->pluck('Correo')->toArray();
                }

                $cuenta_cc1 = $ticket->CorreoSolicitante;
                $datos_usuario = $this->datosUsuario();
                if ($datos_usuario[0]->Correo == null || $datos_usuario[0]->Correo == '') {
                    $cuenta_cc2 = $alertasPlantillas[19]->Destinatario;
                } else {
                    if ($this->idRol() == 1) {
                        $cuenta_cc2 = $alertasPlantillas[19]->Destinatario;
                    } else {
                        $cuenta_cc2 = $datos_usuario[0]->Correo;
                    }
                }
                $cuenta_cc3 = $alertasPlantillas[19]->Destinatario;
                $nombre_proyecto = $ticket->Tarea;
                $folio = $ticket->master;
                $subject = $alertasPlantillas[19]->Asunto;
                $nombre_agente = $ticket->Agente;
                $route = "estructural";
                $fecha_solicitud = $ticket->FechaRegistro;
                $mensaje = $alertasPlantillas[19]->Mensaje;
                $asignadoA = $request->operador;

                $this->bitacoraEstructural('TICKET RECHAZADO POR MOTIVO DE: ' . strtoupper($request->motivo), $ticket->master, $request->id_ticket, $datos_usuario[0]->Nombre, null, $fecha);
                
                // Enviamos el correo al agente
                Mail::to($cuenta_usuario)->cc(array_merge([$cuenta_cc1, $cuenta_cc2, $cuenta_cc3], $cuentasAuxiliares))->send(new SendMailEstructural($alertasPlantillas[19], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha));
                // pruebas
                // Mail::to('cgonzalez@litoprocess.com')->send(new SendMailEstructural($alertasPlantillas[19], $nombre_proyecto, $folio, $subject, $nombre_agente, $route, $fecha_solicitud, $mensaje, $asignadoA, $motivo_cancelacion, $motivo_cambio_fecha));

            } else {
                // Realizamos el guardado del cambio
                DB::statement('exec sp_updateTicketLiberado ?,?', [
                    $request->id_ticket,
                    $request->tipo
                ]);

                // Actualizamos el estatus del ticket
                DB::statement('exec sp_UpdateEstatusESTR ?, ?, ?, ?, ?, ?', [
                    $request->id_ticket,
                    'PENDIENTE DE ASIGNAR',
                    $fecha,
                    $request->motivo,
                    $idUsuario,
                    $fecha,
                ]);

                DB::table('Estructural')->where('folio', $request->id_ticket)->update(['FechaRespuesta' => $fecha, 'Asignado' => null, 'FechaCompromiso' => null]);

                $this->bitacoraEstructural('TICKET SE HA LIBERADO', $ticket->master, $ticket->folio, $this->datosUsuario()[0]->Nombre, null, $fecha);
            }

            // Guardamos en la bitácora

            // Regresamos la respuesta para validar que se realizó la operación
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al realizar la operación";
        }
    }

    /**
     * Obtenemos el ticket completo
     * 
     * @return mixed
     */
    public function getTicketEstrOport($id)
    {
        $ticket = v_Estructural::where('Id_Ticket', $id)->first();
        $cliente = DB::table('Clientes')->where('idCliente', $ticket->idCliente)->first();
        return $cliente->idClienteINT;
    }
}
