<?php

namespace App\Http\Controllers;

use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AdminCusCotController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;
    public $modulo = 'Admin_Users_Cust_Cot';

    /**
     * Función para ingresar al modulo de la sección
     * 
     * @return view
     */
    public function index()
    {
        // Asignamos permisos
        $this->permisos();

        // Obtenemos el login del usuario autenticado
        $Login = $this->loginUsuario();

        // Verificamo que haya ingresado
        if ($Login == null) {
            return view('iniciar');
        }
        
        // Obtenemos la fecha actual
        $fecha = new \DateTime();
        $dia = $fecha->format('D');

        // Obtenemos los permisos por módulo
        $permisos =  $this->permisosModulo($this->modulo);

        // Validamos si existen permisos si no regresar al dashboard
        if (count($permisos) == 0) {
            return redirect()->route('Dashboard');
        }

        // Obtenemos el Id del usuario autenticado
        $id_usuario = $this->idUsuario();

        // Obtenemos el id rol de los permisos del módulo
        $idrol = $permisos[0]->idRol;
        
        // validamos que cuente con los permisos para poder acceder al módulo
        if ($this->searchValue('AccesoModulo', 'descAccion', $permisos) && $this->searchValue('Todo', 'descNivelPerm', $permisos)) {
            return view('admin.users-cot-cust', compact('idrol'));
        } else if ($this->searchValue('AccesoModulo', 'descAccion', $permisos) && $this->searchValue('Ninguno', 'descNivelPerm', $permisos)) {
            return redirect()->route('Dashboard');
        }
    }

    /**
     * Función para buscar validar si existe un valor entre una matriz de datos
     * 
     * @param $key
     * @param $attribute
     * @param $data
     * @return bolean
     */
    public function searchValue($key, $attribute, $data)
    {
        return collect($data)->contains($attribute, $key);
    }

    /**
     * Función para obtener el listado de los cotizadores
     * 
     * @return mixed
     */
    public function apiGetCotizadores()
    {
        $cotizadores = DB::table('v_usuariosCotizadoresComplete');

        return DataTables::of($cotizadores)
            ->addColumn('active', function ($row) {
                return $row->active ? 'Activo' : 'Inactivo'; 
            })
            ->addColumn('accion', function ($row) {
                
                if ($row->active) {
                    $cadena = '<div class="toggle-content"><button class="btn btn-danger" id="checkUser'.$row->Id_Usuario.'" onclick="activateUser('.$row->Id_Usuario.', 0, 1)">Desactivar</button></div>';
                } else {
                    $cadena = '<div class="toggle-content"><button class="btn btn-success" id="checkUser'.$row->Id_Usuario.'" onclick="activateUser('.$row->Id_Usuario.', 1, 1)">Activar</button></div>';
                }                
                return $cadena;
            })
            ->rawColumns(['active', 'accion'])
            ->make(true);
    }
    
    /**
     * Función para obtener el listado de los customers
     * 
     * @return mixed
     */
    public function apiGetCustomers()
    {
        $customers = DB::table('v_usuariosCustomersComplete');

        return DataTables::of($customers)
            ->addColumn('active', function ($row) {
                return $row->active ? 'Activo' : 'Inactivo'; 
            })
            ->addColumn('accion', function ($row) {
                if ($row->active) {
                    $cadena = '<div class="toggle-content"><button class="btn btn-danger" id="checkUser'.$row->Id_Usuario.'" onclick="activateUser('.$row->Id_Usuario.', 0, 2)">Desactivar</button></div>';
                } else {
                    $cadena = '<div class="toggle-content"><button class="btn btn-success" id="checkUser'.$row->Id_Usuario.'" onclick="activateUser('.$row->Id_Usuario.', 1, 2)">Activar</button></div>';
                }                
                return $cadena;
            })
            ->rawColumns(['active', 'accion'])
            ->make(true);
    }

    /**
     * Función para desactivar o activar un usuario customer o cotizador
     * 
     * type => 1 cotizador, 2 customer
     * @param Request $request
     * @return mixed
     */
    public function changeActiveUser(Request $request)
    {
        // Buscamos en el carrucel al usuario para cambiarle el estatus
        switch ($request->type) {
            case 1:
                DB::statement('exec sp_updateActiveCarrucelCot ?, ?', [
                    $request->typeAction,
                    $request->id_user
                ]);
                break;
            case 2:
                DB::statement('exec sp_updateActiveCarrucelCust ?, ?', [
                    $request->typeAction,
                    $request->id_user
                ]);
                break;
        }

        // Bitacora para guardar la accion realizada
       $this->bitacora(
            $this->modulo, 

            // Accion realizada
            ($request->typeAction == 1 ? 'Activación ' : 'Desactivación '), 

            // A quien se realizó
            ($request->type == 1 ? 'Cotizador ' : 'Customer '), 

            // Id del usuario al que se le aplico la acción
            $request->id_user, 
            
            // usuario que realiza la acción
            $this->loginUsuario(),

            // Acción
            2
        );
    }
}
