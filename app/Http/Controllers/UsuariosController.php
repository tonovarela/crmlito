<?php

namespace App\Http\Controllers;

use App\Http\Modelos\catModulos;
use App\Http\Modelos\Usuarios;
use App\Http\Modelos\v_catPermisos;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UsuariosController extends Controller
{
    use PermisosTrait;

    public $modulo = 'Usuarios';

    public function index()
    {
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
            $modulos = catModulos::all();
            $permisos_usuarios = v_catPermisos::all();


            $permisos =  $this->permisosModulo($this->modulo);
            $permisos_count = count($permisos);

            for ($i = 0; $i < $permisos_count; $i++) {

                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('usuarios.index', compact('permisos_usuarios', 'modulos'));
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }

        // }
    }

    public function store(Request $request)
    {
    }

    public function edit($id)
    {
        $usuario = Usuarios::where('Id_Usuario', $id)->first();
        return $usuario;
    }

    public function update(Request $request, $id)
    {
    }


    public function apiUsuarios()
    {
        $usuarios = Usuarios::all();

        $Login = session('usuario');
        $permisos = DB::select('exec GetPermisos ?, ?', [$Login, $this->modulo]);

        session(['rol' => $permisos[0]->idRol]);

        return DataTables::of($usuarios)
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
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function apiPermisos($id)
    {
        if ($id == null) {
            $id = 3;
        }

        $permisos_usuarios = v_catPermisos::where('Id_Usuario', $id);

        return DataTables::of($permisos_usuarios)
            // ->addColumn('inputEditar', function () {
            //     $sesionEditar = trim(session('editar'));
            //     $inputEditar = $sesionEditar;

            //     return $inputEditar;
            // })
            // ->addColumn('inputEliminar', function () {
            //     $sesionEliminar = trim(session('eliminar'));
            //     $inputEliminar = $sesionEliminar;

            //     return $inputEliminar;
            // })
            // ->addColumn('inputLogin', function () {
            //     $sesionLogin = trim(session('usuario'));
            //     $inputLogin = $sesionLogin;

            //     return $inputLogin;
            // })
            // ->addColumn('inputRol', function () {
            //     $sesionRol = session('rol');
            //     $inputRol = $sesionRol;

            //     return $inputRol;
            // })
            // ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function accesoCandy(Request $request)
    {
        DB::statement(
            'exec spAccesoCandy ?, ?',
            [
                $request->id_usuario,
                $request->check,
            ]
        );
    }

    public function accesoLito(Request $request)
    {
        DB::statement(
            'exec spAccesoLito ?, ?',
            [
                $request->id_usuario,
                $request->check,
            ]
        );
    }

    public function GetRoles()
    {
        $roles = DB::select('exec GetRolesCombo');
        return $roles;
    }

    public function cambiarRol(Request $request)
    {
        $rol_usuario = DB::select('exec GetRolesXUsuario @idUsuario=' . $request->id);

        $roles = DB::select('exec GetRolesCombo');

        $rol = $roles[$request->rol]->idRol;

        if ($rol_usuario == null) {
            DB::statement(
                'exec spAsignarRol ?, ?',
                [
                    $rol,
                    $request->id
                ]
            );
        } else {
            DB::statement(
                'exec spCambiarRol ?, ?',
                [
                    $rol,
                    $request->id
                ]
            );
        }
    }

    public function appsLito($id)
    {
        $apps_usuario = DB::select('exec GetUsuarios @Id_Usuario=' . $id);

        return $apps_usuario;
    }
}
