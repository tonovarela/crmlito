<?php

namespace App\Http\Controllers;

use App\catEquipos;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\ClientesCombo;
use App\Http\Modelos\Event;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $moduloLlamadas = 'Llamadas';
    public $moduloReuniones = 'Reuniones';

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
            $permisos_llamadas =  $this->permisosModulo($this->moduloLlamadas);
            $permisos_reuniones =  $this->permisosModulo($this->moduloReuniones);

            $permisos_count = count($permisos_llamadas);

            $datos_usuario = DB::select('exec GetUsuario @Login=' . $this->loginUsuario());
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

            switch ($rol) {
                case '4':
                    $equipos;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $id_usuario);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = ClientesCombo::orderBy('razonsocial', 'asc')->where('idAgente', $id_usuario)->get();
                    // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $id_usuario)->select('razonsocial', 'idCliente', 'ClienteINT')->get();
                    break;
                case '9':
                    $agente_id = $agentes[0]->idAgente;
                    $equipos = DB::select('exec GetEquiposUsuarioCurrent @idUsuario=' . $agente_id);
                    $idEquipo = $equipos[0]->idEquipo;
                    $idEquipo_idAgente = $equipos[0]->Id_Usuario;
                    $combo_vendedores = DB::select('exec GetAgentesEquipoCurrent @idEquipo=' . $idEquipo);
                    $combo_clientes = ClientesCombo::orderBy('razonsocial', 'asc')->where('idAgente', $agente_id)->get();
                    // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idAgente', $agente_id)->select('razonsocial', 'idCliente', 'ClienteINT')->get();
                    break;
                case '3':
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    $combo_clientes = ClientesCombo::orderBy('razonsocial', 'asc')->get();
                    // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->select('razonsocial', 'idCliente', 'ClienteINT')->get();
                    break;
                default:
                    $equipos = catEquipos::orderBy('idEquipo', 'asc')->get();
                    $combo_vendedores = DB::select('exec GetAgentesCombo');
                    // $combo_clientes = DB::select('exec GetClientesCombo');
                    $combo_clientes = ClientesCombo::orderBy('razonsocial', 'asc')->get();
                    // $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('razonsocial', '!=', null)->where('razonsocial', '!=', "")->select('razonsocial', 'idCliente', 'ClienteINT')->get();
                    break;
            }

            $folio = DB::table('LlamadasReuniones')->max('folio') + 1;

            for ($i = 0; $i < $permisos_count; $i++) {

                $crearLlamada = session('crearLlamada');
                $editarLlamada = session('editarLlamada');

                $crearReunion = session('crearReunion');
                $editarReunion = session('editarReunion');

                if ($permisos_llamadas[$i]->descAccion == 'AccesoModulo' && $permisos_llamadas[$i]->descNivelPerm == 'Todo') {
                    return view('agenda.index', compact('crearLlamada', 'editarLlamada', 'crearReunion', 'editarReunion', 'nombre_usuario', 'folio', 'id_usuario', 'rol', 'agente_id', 'combo_clientes', 'equipos', 'combo_vendedores', 'estados', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio'));
                } else if ($permisos_llamadas[$i]->descAccion == 'AccesoModulo' && $permisos_llamadas[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
    }

    public function eventos(Request $request)
    {

        $Agente = $request->Agente;

        if ($Agente == 'TODOS') {
            $Agente = 0;
        }

        try {
            if ($Agente == 0) {
                $eventos = Event::All();
            } else {
                $eventos = DB::select('exec GetAgendaAuxiliar @id_agente=' . (int)$Agente);
                // $eventos = Event::where('Id_Usuario', (int)$Agente)->get();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            dd($ex->getMessage());
        }

        return $eventos;
    }
}
