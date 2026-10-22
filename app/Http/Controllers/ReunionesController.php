<?php

namespace App\Http\Controllers;

use App\Http\Vistas\v_Reuniones;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReunionesController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Reuniones';

    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function apiReuniones()
    {

        $Login = session('usuario');
        $permisos = $this->permisosModulo($this->modulo);

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');


        $Reuniones = v_Reuniones::where('Usuario', $Login)->limit(20000);

        // switch ($rol) {
        //     case "3":
        //         $Reuniones = DB::select('exec GetEnviosCustomer @Login=' . $Login);
        //         break;
        //     case "4":
        //         $Reuniones = v_Reuniones::where('Login', $Login)->limit(20000);
        //         break;
        //     case "9":
        //         $Reuniones = DB::select('exec GetEnviosAuxiliar @Login=' . $Login);
        //         break;
        //     default:
        //         $Reuniones = DB::select('exec GetEnvios');
        //         break;
        // }

        return DataTables::of($Reuniones)
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
}
