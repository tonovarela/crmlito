<?php

namespace App\Http\Controllers;

use App\Http\Modelos\Contactos;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ContactosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = "Contactos";

    public function index()
    {
        $Login = session('usuario');

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
            $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
            $id_usuario = $datos_usuario[0]->Id_Usuario;
            $combo_clientes = DB::select('exec GetClientesCombo');
            $permisos = $this->permisosModulo('Cuentas');
            $permisos_count = count($permisos);
            $idrol = $permisos[0]->idRol;

            for ($i = 0; $i < $permisos_count; $i++) {

                $crear = session('crear');
                $editar = session('editar');
                $editarClientes = session('editarClientes');

                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('contactos.index', compact('id_usuario', 'combo_clientes', 'crear', 'editar', 'editarClientes', 'idrol'));
                    // return view('clientes.index', compact('list_column', 'crear', 'editar', 'editarClientes', 'id_cliente_int', 'agentes', 'cat_situacion', 'cat_giros', 'cat_subgiros', 'id_usuario', 'cat_requisitos', 'cat_regimen', 'cat_condiciones', 'idrol', 'cat_metodo_revision', 'cat_ent_compl_pago', 'paises', 'uso_cfdi'));
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
    }

    public function store(Request $request)
    {
        // return $request;

        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $id_usuario = $datos_usuario[0]->Id_Usuario;

        $fecha = new \DateTime();

        $cfdenviar = 0;
        $contactocxp = 0;
        $principal = 0;

        if ($request->CFD_Enviar == 'on') {
            $cfdenviar = 1;
        } else {
            $cfdenviar = 0;
        }

        if ($request->contactoCXP == 'on') {
            $contactocxp = 1;
        } else {
            $contactocxp = 0;
        }

        if ($request->principal == 'on') {
            $principal = 1;
        } else {
            $principal = 0;
        }

        DB::statement(
            'exec spInsertClienteContacto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $request->idClienteINT,
                $request->Tratamiento,
                $request->Nombre,
                $request->Cargo,
                $request->Departamento,
                $request->Telefonos,
                $request->email,
                $cfdenviar,
                $contactocxp,
                $id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                null,
                null,
                $principal
            ]
        );

        $folio = DB::table('Contactos')->max('idContacto');

        $cadena = implode('|', $request->all());

        $this->bitacora($this->modulo, 'Agregar ', 'Contacto | ' . (string)$cadena, $folio, $this->loginUsuario(), 5);
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        $contacto = Contactos::where('idContacto', $id)->first();
        return $contacto;
    }

    public function update(Request $request)
    {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $id_usuario = $datos_usuario[0]->Id_Usuario;

        $fecha = new \DateTime();

        $cfdenviar = 0;
        $contactocxp = 0;
        $principal = 0;

        if ($request->CFD_Enviar == 'on') {
            $cfdenviar = 1;
        } else {
            $cfdenviar = 0;
        }

        if ($request->contactoCXP == 'on') {
            $contactocxp = 1;
        } else {
            $contactocxp = 0;
        }

        if ($request->principal == 'on') {
            $principal = 1;
        } else {
            $principal = 0;
        }

        if ($request->idClienteINTCto == null) {
            $idCliente = $request->idClienteINT;
        } else {
            $idCliente = $request->idClienteINTCto;
        }

        DB::statement(
            'exec spUpdateClienteContacto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $idCliente,
                $request->Tratamiento,
                $request->Nombre,
                $request->Cargo,
                $request->Departamento,
                $request->Telefonos,
                $request->email,
                $cfdenviar,
                $contactocxp,
                $id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $principal,
                $request->idContactoINT
            ]
        );

        $folio = $request->idContactoINT;

        $contacto = Contactos::where('idContactoINT', $request->idContactoINT)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($contacto, true)));

        $this->bitacora($this->modulo, 'Editar ', 'Contacto / ' . $cadena  . ', "principal":' . $principal . ' / ', $folio, $this->loginUsuario(), 2);
    }

    public function destroy($id)
    {
    }

    public function apiContactos(Request $request)
    {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $id_usuario = $datos_usuario[0]->Id_Usuario;

        session(['id_usuario_suc' => $id_usuario]);

        $id = $request->idClienteINT;
        $contacto_principal = DB::select('exec GetContactoPrincipal @ClienteINT=' . $id);

        session(['ctoPrincipal' => $contacto_principal[0]->idContactoPrincipal]);


        // $contacto_principal = session('ctoPrincipal');

        $contactos = DB::select('exec GetContactosXidCliente @idClienteINT=' . $id);

        // var_dump($contactos);

        $cto = $contacto_principal[0]->idContactoPrincipal;
        $rol = session('rol');

        return DataTables::of($contactos)
            ->addColumn('enviar_cfd', function ($contacto) use ($rol) {
                if ($rol != 1013 && $rol != 1015) {
                    if ($contacto->CFD_Enviar == 1) {
                        $enviar_cfd = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="updateEnviarCFD(' . $contacto->idContactoINT . ', ' . $contacto->idClienteINT . ', ' . $contacto->CFD_Enviar . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        $enviar_cfd = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="updateEnviarCFD(' . $contacto->idContactoINT . ', ' . $contacto->idClienteINT . ', ' . $contacto->CFD_Enviar . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                } else {
                    if ($contacto->CFD_Enviar == 1) {
                        $enviar_cfd = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                    } else {
                        $enviar_cfd = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                    }
                }

                return $enviar_cfd;
            })
            ->addColumn('contacto_cxp', function ($contacto) use ($rol) {

                if ($rol != 1013 && $rol != 1015) {
                    if ($contacto->contactoCXP == 1) {
                        $contacto_cxp = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="updateContactoCXP(' . $contacto->idContactoINT . ', ' . $contacto->idClienteINT . ', ' . $contacto->contactoCXP . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        $contacto_cxp = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="updateContactoCXP(' . $contacto->idContactoINT . ', ' . $contacto->idClienteINT . ', ' . $contacto->contactoCXP . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                } else {
                    if ($contacto->contactoCXP == 1) {
                        $contacto_cxp = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                    } else {
                        $contacto_cxp = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                    }
                }

                return $contacto_cxp;
            })
            ->addColumn('principal', function ($contacto) use ($cto, $rol) {

                if ($rol != 1013 && $rol != 1015) {
                    if ($contacto->idContactoINT == $cto) {
                        // $principal = '<a onclick="updatePrincipal(' . $contacto->idContactoINT . ', ' . $cto . ')"><div class="custom-control custom-switch"><input id="idContactoPrincipal" name="idContactoPrincipal" type="checkbox" class="custom-control-input" checked><label class="custom-control-label" for="idContactoPrincipal"></label></div></a>';
                        $principal = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="updatePrincipal(' . $contacto->idClienteINT . ', ' . $cto . ', ' . $contacto->idContactoINT . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        $principal = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="updatePrincipal(' . $contacto->idClienteINT . ', ' . 0 . ', ' . $contacto->idContactoINT . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                } else {
                    if ($contacto->idContactoINT == $cto) {
                        // $principal = '<a onclick="updatePrincipal(' . $contacto->idContactoINT . ', ' . $cto . ')"><div class="custom-control custom-switch"><input id="idContactoPrincipal" name="idContactoPrincipal" type="checkbox" class="custom-control-input" checked><label class="custom-control-label" for="idContactoPrincipal"></label></div></a>';
                        $principal = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                    } else {
                        $principal = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                    }
                }

                return $principal;
            })
            ->addColumn('cto_activo', function ($contacto) use ($rol) {
                if ($rol != 1013 && $rol != 1015) {
                    if ($contacto->activo == 0 || $contacto->activo == null) {
                        $activo = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"  id="activo_ctecto_' . $contacto->idContactoINT . '"><label onclick="updateActivo(' . $contacto->idContactoINT . ', ' . $contacto->idClienteINT . ', ' . $contacto->activo . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        $activo = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"  id="activo_ctecto_' . $contacto->idContactoINT . '" checked><label onclick="updateActivo(' . $contacto->idContactoINT . ', ' . $contacto->idClienteINT . ', ' . $contacto->activo . ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                } else {
                    if ($contacto->activo == 0 || $contacto->activo == null) {
                        $activo = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"  id="activo_ctecto_' . $contacto->idContactoINT . '" disabled><label class="custom-control-label" disabled></label></div>';
                    } else {
                        $activo = '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"  id="activo_ctecto_' . $contacto->idContactoINT . '" checked disabled><label class="custom-control-label" disabled></label></div>';
                    }
                }

                return $activo;
            })
            ->addColumn('cto_principal', function ($contacto) use ($cto) {
                if ($contacto->idContactoINT == $cto) {
                    $cto_principal = $cto;
                } else {
                    $cto_principal = $cto;
                }

                return $cto_principal;
            })
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editar'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminar'));
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
            ->addColumn('input_idUsuario', function () {
                $id_usuario_suc = session('id_usuario_suc');
                $input_idUsuario = $id_usuario_suc;

                return $input_idUsuario;
            })
            ->rawColumns(['enviar_cfd', 'contacto_cxp', 'principal', 'cto_activo', 'cto_principal', 'inputEditar', 'inputEliminar', 'inputLogin', 'inputRol', 'input_idUsuario'])
            ->make(true);
    }

    public function apiContactosTodos(Request $request)
    {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $id_usuario = $datos_usuario[0]->Id_Usuario;

        $permisos = DB::select('exec GetPermisos ?, ?', [$Login, 'Cuentas']);

        session(['rol' => $permisos[0]->idRol]);

        $contactos = Contactos::limit(50000);

        return DataTables::of($contactos)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editar'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminar'));
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
            ->addColumn('input_idUsuario', function () use ($id_usuario) {
                $id_usuario_suc = $id_usuario;
                $input_idUsuario = $id_usuario_suc;

                return $input_idUsuario;
            })
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol'])
            ->make(true);



        // $contactos = Contactos::limit(50000);


        // return DataTables::of($contactos)
        // ->make(true);

    }

    public function updatePrincipal(Request $request)
    {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $idUsuario = $datos_usuario[0]->Id_Usuario;

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $request->idCliente);

        $fecha = new \DateTime();

        $principal = $request->Principal;

        $idContacto = $request->idContacto;


        $idCliente = $request->idCliente;

        if ($principal == 0) {
            $principal = 1;
        } else {
            $principal = 0;
        }

        $contacto = Contactos::where('idContactoINT', $idContacto)->first();

        $nombre = $contacto->Nombre;

        DB::statement(
            'exec spUpdatePrincipal ?, ?, ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $principal,
                $nombre,
                $idContacto,
                $idCliente
            ]
        );

        $folio = $request->idContacto;

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($contacto, true)));

        $this->bitacora($this->modulo, 'Editar ', 'Contacto Principal / ' . $cadena  . ', "principal":' . $principal . ' / ', $folio, $this->loginUsuario(), 2);
    }

    public function updateCXP(Request $request)
    {
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $idUsuario = $datos_usuario[0]->Id_Usuario;

        $fecha = new \DateTime();
        $idContacto = $request->idContacto;

        if ($request->idUsuario == null || $request->idUsuario == "") {
            $cxp = $request->check;
        } else {
            if ($request->cxp == 0) {
                $cxp = 1;
            } else {
                $cxp = 0;
            }
        }

        DB::statement(
            'exec spUpdateCXP ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $cxp,
                $idContacto,
            ]
        );
        $folio = $request->idContacto;

        $contacto = Contactos::where('idContactoINT', $idContacto)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($contacto, true)));

        $this->bitacora($this->modulo, 'Editar ', 'Contacto CXP / ' . $cadena . ' / ', $folio, $this->loginUsuario(), 2);
    }

    public function updateCFD(Request $request)
    {
        // return $request;
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $idUsuario = $datos_usuario[0]->Id_Usuario;

        $fecUltCambio = new \DateTime();

        if ($request->idUsuario == null || $request->idUsuario == "") {
            $CFD = $request->check;
        } else {
            if ($request->cfd == 0) {
                $CFD = 1;
            } else {
                $CFD = 0;
            }
        }

        $IDCont = $request->idContacto;

        DB::statement(
            'exec spUpdateCFD ?, ?, ?, ?',
            [
                $idUsuario,
                $fecUltCambio->format('Y-m-d H:i:s'),
                $CFD,
                $IDCont,
            ]
        );
        $folio = $request->idContacto;

        $contacto = Contactos::where('idContactoINT', $IDCont)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($contacto, true)));

        $this->bitacora($this->modulo, 'Editar ', 'Contacto CFD / ' . $cadena . ' / ', $folio, $this->loginUsuario(), 2);
    }

    public function updateActivo(Request $request)
    {

        // return $request;
        $Login = session('usuario');
        $datos_usuario = DB::select('exec GetUsuario @Login=' . $Login);
        $idUsuario = $datos_usuario[0]->Id_Usuario;

        $fecUltCambio = new \DateTime();

        if ($request->idUsuario == null || $request->idUsuario == "") {
            $activo = $request->check;
        } else {
            if ($request->activo == 0) {
                $activo = 1;
            } else {
                $activo = 0;
            }
        }

        $IDCont = $request->idContacto;

        DB::statement(
            'exec spUpdateContactoActivo ?, ?, ?, ?',
            [
                $idUsuario,
                $fecUltCambio->format('Y-m-d H:i:s'),
                $activo,
                $IDCont,
            ]
        );
        $folio = $request->idContacto;

        $contacto = Contactos::where('idContactoINT', $IDCont)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($contacto, true)));

        $this->bitacora($this->modulo, $request->title, ' Contacto / ' . $cadena . ' / ', $folio, $this->loginUsuario(), 2);
    }

    /**
     * Función para actualizar el campo de esEnvio de una sucursal, recibe el id de la sucursal y el valor del checkbox (1 o 0) para actualizarlo en la base de datos.
     * 
     * @param Request $request
     * @return void
     */
    public function actualizarEnvio(Request $request)
    {
        try {
            $idContacto = $request->idContacto;
            $esEnvio = $request->esEnvio;

            DB::table('Contactos')->where('idContacto', $idContacto)
                ->update(['esEnvio' => (int)$esEnvio]);

            return response()->json(['success' => true, 'message' => 'Actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar']);
        }
    }
}
