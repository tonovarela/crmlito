<?php

namespace App\Http\Controllers;

use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use App\v_Oportunidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class EntregasController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Entregas';

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
            $permisos_count = count($permisos);

            for ($i = 0; $i < $permisos_count; $i++) {

                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('entregas.index');
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
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

    public function apiOportunidades()
    {

        $Login = $this->loginUsuario();

        $permisos = $this->permisosModulo($this->modulo);

        session(['rol' => $permisos[0]->idRol]);

        $rol = session('rol');

        switch ($rol) {
            case "3":
                // $Oportunidades = v_Oportunidades::where('LoginCustomer', $Login)->limit(20000);
                $Oportunidades = DB::select('exec GetOportunidadesXCustomer @Login=' . $Login);
                break;
            case "4":
                // $Oportunidades = v_Oportunidades::where('Login', $Login)->orWhere('LoginAuxiliar', $Login)->limit(20000);
                $Oportunidades = v_Oportunidades::where('Login', $Login)->limit(20000);
                break;
            case "9":
                $Oportunidades = DB::select('exec GetOportunidadesXAuxiliar @Login=' . $Login);
                // $Oportunidades = v_Oportunidades::where('LoginAuxiliar', $Login)->limit(20000);
                break;
            default:
                $Oportunidades = DB::select('exec Get_v_CatOportunidades');
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
}
