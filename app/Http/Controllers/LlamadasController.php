<?php

namespace App\Http\Controllers;

use App\Http\Modelos\ArchivosAgenda;
use App\Http\Modelos\Llamadas;
use App\Http\Vistas\v_Llamadas;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LlamadasController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Llamadas';
    public $modulo_bitacora = 'Llamadas / Reuniones';

    public function store(Request $request)
    {
        $fecha = new \DateTime();

        $idUsuario = $this->idUsuario();
        $folio = DB::table('LlamadasReuniones')->max('folio') + 1;
        $agentes = DB::table('rel_Agente_Aux')->where('idAuxiliar', $idUsuario)->first();
        $agente = null;

        if ($agentes != null) {
            $agente = $agentes->idAgente;
        }
        else {
            $agente = $idUsuario;
        }

        $prospecto = DB::table('v_catClientesActivosCombo')->where('idCliente', $request->relacionadoCon)->where('idClienteINT', 0)->get();
        $op_prospecto = 0;
        
        if (count($prospecto) > 0 ) {
            if ($prospecto[0]->Tipo == 'PROSPECTO') {
                $op_prospecto = 1;
            }
        }

        // return $op_prospecto;
        
        $data = [
            'asuntoLugar' => strtoupper($request['asuntoLugar']),
            'descripcion' => strtoupper($request['descripcion']),
            'id_relacionadoCon' => $request['relacionadoCon'],
            'fecInicio' => Carbon::parse($request->fecInicio)->format('d-m-Y H:i:s'),
            'id_estado' => 1,
            'id_direccion' => $request['direccion'],
            'id_usuario' => $idUsuario,
            'fecCreacion' => $fecha->format('d-m-Y H:i:s'),
            'id_tipoRepeticion' => $request['tipoRepeticion'],
            'id_horaRecordatorio' => $request['horaRecordatorio'],
            'observaciones' => strtoupper($request['observaciones']),
            'tipo' => $request['tipo'],
            'folio' => $folio,
            'id_agente' => $agente,
            'id_contacto' => $request['id_contacto'],
            'tel_contacto' => $request['tel_contacto'],
            'prospecto' => $op_prospecto,
        ];

        $llamada = Llamadas::create($data);

        // Validamos y guardamos el archivo adjunto
        if ($request->hasFile('minutaFile')) {
            $Documento = base64_encode(file_get_contents($request->file('minutaFile')->getRealPath()));

            $existingFile = ArchivosAgenda::where('idRelacion', $llamada->idLlamadasReuniones)->first();

            if ($existingFile) {
                $existingFile->update([
                    'archivo' => $Documento,
                    'idUsuario' => $idUsuario,
                    'nombreArchivo' => $request->file('minutaFile')->getClientOriginalName(),
                    'extension' => $request->file('minutaFile')->getClientOriginalExtension(),
                    'peso' => $request->file('minutaFile')->getSize(),
                ]);
            } else {
                ArchivosAgenda::create([
                        'archivo' => $Documento,
                        'idUsuario' => $idUsuario,
                        'idRelacion' => $llamada->idLlamadasReuniones,
                        'nombreArchivo' => $request->file('minutaFile')->getClientOriginalName(),
                        'extension' => $request->file('minutaFile')->getClientOriginalExtension(),
                    'peso' => $request->file('minutaFile')->getSize(),
                ]);
            }
        }
        
        $this->bitacora($this->modulo_bitacora, 'Agregar ', 'Llamada / Reunión', $folio, $this->loginUsuario(), 5);

        return $llamada;

    }

    public function edit($id)
    {
        $llamadas = Llamadas::where('idLlamadasReuniones', $id)
                    ->select('LlamadasReuniones.*', 'ControlLogin.dbo.cat_Usuarios.Nombre', 'archivosAgenda.*')
                    ->join('ControlLogin.dbo.cat_Usuarios', 'ControlLogin.dbo.cat_Usuarios.Id_Usuario', '=', 'LlamadasReuniones.id_agente')
                    ->leftJoin('archivosAgenda', 'archivosAgenda.idRelacion', '=', 'LlamadasReuniones.idLlamadasReuniones')
                    ->first();
        return $llamadas;
    }

    public function update(Request $request, $id)
    {
        try {
            $llamada = Llamadas::where('idLlamadasReuniones', $id)->first();
            
            $fecha = new \DateTime();
            $idUsuario = $this->idUsuario();
            $idEstado = $llamada->id_estado;

            $prospecto = DB::table('v_catClientesActivosCombo')->where('idCliente', $request->relacionadoCon)->where('idClienteINT', 0)->get();
            $op_prospecto = 0;
            
            if (count($prospecto) > 0 ) {
                if ($prospecto[0]->Tipo == 'PROSPECTO') {
                    $op_prospecto = 1;
                }
            }

            $llamada->asuntoLugar = strtoupper($request['asuntoLugar']);
            $llamada->descripcion = strtoupper($request['descripcion']);
            $llamada->id_relacionadoCon = $request['relacionadoCon'];
            $llamada->fecInicio = Carbon::parse($request->fecInicio)->format('d-m-Y H:i:s');
            $llamada->id_estado = $idEstado;
            $llamada->id_direccion = $request['direccion'];
            $llamada->id_usuarioUltCambio = $idUsuario;
            $llamada->fecUltimoCambio = $fecha->format('d-m-Y H:i:s');
            $llamada->id_tipoRepeticion = $request['tipoRepeticion'];
            $llamada->id_horaRecordatorio = $request['horaRecordatorio'];
            $llamada->observaciones = strtoupper($request['observaciones']);
            $llamada->tipo = $request['tipo'];
            $llamada->id_contacto = $request['id_contacto'];
            $llamada->tel_contacto = $request['tel_contacto'];
            $llamada->prospecto = $op_prospecto;

            // return $llamada;

            $llamada->update();

            // Validamos y guardamos el archivo adjunto
            if ($request->hasFile('minutaFile')) {
                $Documento = base64_encode(file_get_contents($request->file('minutaFile')->getRealPath()));

                $existingFile = ArchivosAgenda::where('idRelacion', $llamada->idLlamadasReuniones)->first();

                if ($existingFile) {
                    $existingFile->update([
                        'archivo' => $Documento,
                        'idUsuario' => $idUsuario,
                        'nombreArchivo' => $request->file('minutaFile')->getClientOriginalName(),
                        'extension' => $request->file('minutaFile')->getClientOriginalExtension(),
                        'peso' => $request->file('minutaFile')->getSize(),
                    ]);
                } else {
                    ArchivosAgenda::create([
                            'archivo' => $Documento,
                            'idUsuario' => $idUsuario,
                            'idRelacion' => $llamada->idLlamadasReuniones,
                            'nombreArchivo' => $request->file('minutaFile')->getClientOriginalName(),
                            'extension' => $request->file('minutaFile')->getClientOriginalExtension(),
                        'peso' => $request->file('minutaFile')->getSize(),
                    ]);
                }
            }

            $this->bitacora($this->modulo_bitacora, 'Actualizar ', 'Llamada / Reunión', $llamada->folio, $this->loginUsuario(), 2);

            return response()->json([
                'status' => 'success',
                'message' => 'Llamada / Reunión actualizada correctamente'
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lo sentimos ocurrió un error, intentelo más tarde'
            ]);
        }
    }

    private function sendMessage($message, $recipient)
    {
        $auth_basic = base64_encode(config('services.labsmobile.user') . ":" . config('services.labsmobile.token'));
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.labsmobile.com/json/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{"message":"' .  $message . '", "tpoa":"Sender","recipient":[' . $recipient . ']}',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic " . $auth_basic,
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function apiLlamadas()
    {
        $Login = session('usuario');
        $permisos = $this->permisosModulo($this->modulo);

        $idUsuario = $this->idUsuario();

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        $agentes = DB::table('rel_Agente_Aux')->where('idAuxiliar', $idUsuario)->orWhere('idAuxiliar1', $idUsuario)->orWhere('idAuxiliar2', $idUsuario)->orWhere('idAuxiliar3', $idUsuario)->first();

        switch ($rol) {
            case "4":
                $Llamadas = v_Llamadas::where('Id_Usuario', $idUsuario)->where('tipo', 'Llamadas')->limit(20000);
                break;
            case "9":
                $Llamadas = DB::select('exec GetLlamadasAuxiliar @id_agente=' . $agentes->idAgente);
                break;
            default:
                $Llamadas = v_Llamadas::where('tipo', 'Llamadas');
                break;
        }

        return DataTables::of($Llamadas)
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
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function apiReuniones()
    {

        $Login = session('usuario');
        $permisos = $this->permisosModulo($this->modulo);

        $idUsuario = $this->idUsuario();

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        $agentes = DB::table('rel_Agente_Aux')->where('idAuxiliar', $idUsuario)->orWhere('idAuxiliar1', $idUsuario)->orWhere('idAuxiliar2', $idUsuario)->orWhere('idAuxiliar3', $idUsuario)->first();

        switch ($rol) {
            case "4":
                $reuniones = v_Llamadas::where('Id_Usuario', $idUsuario)->where('tipo', 'Reuniones')->limit(20000);
                break;
            case "9":
                $reuniones = DB::select('exec GetReunionesAuxiliar @id_agente=' . $agentes->idAgente);
                break;
            default:
                $reuniones = v_Llamadas::where('tipo', 'Reuniones');
                break;
        }

        return DataTables::of($reuniones)
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
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function cancelarLlamada(Request $request)
    {
        $fecha = new \DateTime();

        $llamada = Llamadas::where('idLlamadasReuniones', $request->id)->first();

        DB::statement(
            'exec spCancelarLlamada ?, ?, ?, ?, ?',
            [
                $request->id_suario,
                $fecha->format('Y-m-d H:i:s'),
                true,
                strtoupper($request->motivoCancelacion),
                $request->id
            ]
        );

        $this->bitacora($this->modulo_bitacora, 'Cancelar ', 'Llamada / Reunión ', $llamada->folio, $this->loginUsuario(), 6);
    }

    public function realizada(Request $request)
    {
        $fecha = new \DateTime();

        $llamada = Llamadas::where('idLlamadasReuniones', $request->id)->first();

        DB::statement(
            'exec spLlamadaReunionRealizada ?, ?, ?, ?',
            [
                $request->id_suario,
                $fecha->format('Y-m-d H:i:s'),
                true,
                $request->id
            ]
        );

        $this->bitacora($this->modulo_bitacora, 'Actualizar ', 'Llamada / Reunión', $llamada->folio, $this->loginUsuario(), 2);
    }

    public function updateLlamada(Request $request)
    {

        $llamada = Llamadas::where('idLlamadasReuniones', $request->id)->first();
        $fecha = new \DateTime();
        $idUsuario = $this->idUsuario();
        $idEstado = $llamada->id_estado;

        $llamada->asuntoLugar = strtoupper($request['asuntoLugar']);
        $llamada->descripcion = strtoupper($request['descripcion']);
        $llamada->id_relacionadoCon = $request['relacionadoCon'];
        $llamada->fecInicio = Carbon::parse($request->fecInicio)->format('d-m-Y H:i:s');
        $llamada->id_estado = $idEstado;
        $llamada->id_direccion = $request['direccion'];
        $llamada->id_usuarioUltCambio = $idUsuario;
        $llamada->fecUltimoCambio = $fecha->format('d-m-Y H:i:s');
        $llamada->id_tipoRepeticion = $request['tipoRepeticion'];
        $llamada->id_horaRecordatorio = $request['horaRecordatorio'];
        $llamada->observaciones = strtoupper($request['observaciones']);
        $llamada->tipo = $request['tipo'];
        $llamada->id_contacto = $request['id_contacto'];
        $llamada->tel_contacto = $request['tel_contacto'];

        $llamada->update();

        $this->bitacora($this->modulo_bitacora, 'Actualizar ', 'Llamada / Reunión', $llamada->folio, $this->loginUsuario(), 2);
    }

    public function getContactos (Request $request) {

        $cliente = DB::table('v_catClientesR')->where('idCliente', $request->idCliente)->first();
        $contactos = DB::table('v_catContactos')->where('idClienteINT', $cliente->ClienteINT)->where('Nombre', '!=', NULL)->distinct()->get();

        return $contactos;
    }

    public function getContactosOportunidades (Request $request) {

        $cliente = DB::table('v_catClientesR')->where('idCliente', $request->idCliente)->first();
        $contactosOpor = DB::table('v_catContactos')->where('idClienteINT', $cliente->ClienteINT)->where('Nombre', '!=', NULL)->where('activo', 1)->distinct()->get();

        return $contactosOpor;
    }

    public function getTelefonosContacto (Request $request) {

        $telefonos_contacto = DB::table('v_catContactos')->where('idContacto', (int)$request->idContacto)->where('Telefonos', '!=', NULL)->get();

        return $telefonos_contacto;
    }

    /**
     * Función para obtener la tabla de comisiones de un cliente
     * 
     * @param Request $request
     * @return mixed
     */
    public function getUserTblCom(Request $request)
    {   
        try {
            $client = DB::table('v_catClientesR2')->where('idCliente', $request->idCliente)->first();
        
            if ($client != null) {
                return response()->json([
                    'tbl' => $client->TablaComision 
                ]);    
            } else {
                return response()->json([
                    'tbl' => null 
                ]);
            }   
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un error, intentelo más tarde";
        }
    }
}
