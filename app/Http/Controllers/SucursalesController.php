<?php

namespace App\Http\Controllers;

use App\Http\Modelos\ClientesSuc;
use App\Http\Modelos\Sucursales;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SucursalesController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Sucursales';

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
            $id_usuario = $this->loginUsuario();

            $combo_clientes = DB::select('exec GetClientesCombo');
            $rol = session('rol');

            return view('sucursales.index', compact('id_usuario', 'combo_clientes', 'rol'));
        // }
    }

    public function store(Request $request)
    {
        $id_usuario = $this->idUsuario();

        $fecha = new \DateTime();

        $misma_direccion = 0;
        $idClienteINT = $request->idClienteINT;

        $estatus = 'ALTA';

        $Nombre = $request->Nombre;
        $Encargado = $request->Encargado;
        $eMail1 = $request->eMail1;
        $Direccion = $request->Direccion;
        $DireccionNumero = $request->DireccionNumero;
        $DireccionNumeroInt = $request->DireccionNumeroInt;
        $Colonia = $request->Colonia;
        $Delegacion = $request->Delegacion;
        $CodigoPostal = $request->CodigoPostal;
        $Estado = $request->Estado;
        $Poblacion = $request->Poblacion;
        $Pais = $request->Pais;
        $Telefonos = $request->Telefonos;

        // if ($request->mismaDireccion == 1) {
        //     $misma_direccion = 1;
        // }    

        DB::statement(
            'exec spInsertClienteSucursal ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $idClienteINT,
                $Nombre,
                $Encargado,
                $eMail1,
                $Direccion,
                $DireccionNumeroInt,
                $DireccionNumero,
                $Colonia,
                $Delegacion,
                $Poblacion,
                $Estado,
                $Pais,
                $CodigoPostal,
                $Telefonos,
                $estatus,
                $id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $misma_direccion,
                0
            ]
        );

        $folio = DB::getPdo()->lastInsertId();

        $cadena = implode('|', $request->all());

        $this->bitacora($this->modulo, 'Agregar ', 'Sucursal | ' . (string)$cadena, $folio, $this->loginUsuario(), 5);
    }

    public function edit($id)
    {
        $Sucursal = Sucursales::where('idSucursal', $id)->first();
        return $Sucursal;
    }

    public function getDireccionFiscal($id)
    {
        $suc_misma_direccion = Sucursales::where('idClienteINT', $id)->where('mismaDireccion', 1)->first();

        return $suc_misma_direccion;
    }

    public function update(Request $request)
    {
        $id_usuario = $this->idUsuario();

        $fecha = new \DateTime();
        $misma_direccion = 0;

        // if ($request->mismaDireccion == 1) {
        //     $misma_direccion = 1;
        // }    

        DB::statement(
            'exec spUpdateSucursal ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $request->idClienteINTSuc,
                $request->idSucursalINT,
                $request->Nombre,
                $request->Encargado,
                $request->eMail1,
                $request->Direccion,
                $request->DireccionNumeroInt,
                $request->DireccionNumero,
                $request->Colonia,
                $request->Delegacion,
                $request->Poblacion,
                $request->Estado,
                $request->Pais,
                $request->CodigoPostal,
                $request->Telefonos,
                $id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $request->idSucursal,
                $misma_direccion
            ]
        );

        $Sucursal = Sucursales::where('idSucursal', $request->idSucursal)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($Sucursal, true)));

        $this->bitacora($this->modulo, 'Editar ', 'Sucursal / ' . $cadena  . ' / ', $request->idSucursal, $this->loginUsuario(), 2);
    }

    public function apiSucursales(Request $request)
    {
        $id = $request->idClienteINT;

        $id_usuario = $this->loginUsuario();

        $permisos = $this->permisosModulo('Cuentas');

        session(['rol' => $permisos[0]->idRol]);

        session(['id_usuario_suc' => $id_usuario]);

        $sucursales = DB::select('exec GetSucursalesXidCliente @idClienteINT=' . $id);

        return DataTables::of($sucursales)
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
            ->rawColumns(['actionDetail', 'inputEditar', 'inputEliminar', 'inputLogin', 'inputRol', 'input_idUsuario'])
            ->make(true);
    }

    public function apiSucursalesTodas(Request $request)
    {
        $id_usuario = $this->idUsuario();

        $permisos = $this->permisosModulo('Cuentas');

        session(['rol' => $permisos[0]->idRol]);

        session(['id_usuario_suc' => $id_usuario]);

        $sucursales = Sucursales::limit(50000);

        return DataTables::of($sucursales)
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
            ->rawColumns(['actionDetail', 'inputEditar', 'inputEliminar', 'inputLogin', 'inputRol', 'input_idUsuario'])
            ->make(true);
    }

    public function darDeBajaSucursal(Request $request)
    {
        $sucursal = DB::select('exec GetSucursalXid @idSucursal=' . $request->id);

        $fecha = new \DateTime();

        $estatus = "";

        $idUsuario = $this->idUsuario();
        $id_sucursal_INT = $sucursal[0]->idSucursalINT;
        $id_cliente_INT = $sucursal[0]->idClienteINT;

        if (trim($sucursal[0]->Estatus) === "BAJA") {
            $estatus = "ALTA";
        } else {
            $estatus = "BAJA";
        }

        DB::statement(
            'exec spDeleteSucursal ?, ?, ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $estatus,
                $request->id,
                $id_sucursal_INT,
                $id_cliente_INT
            ]
        );

        $Sucursal_ = Sucursales::where('idSucursal', $request->id)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($Sucursal_, true)));

        $this->bitacora($this->modulo, 'Dar de Baja ', 'Sucursal / ' . $cadena  . ' / ', $request->id, $this->loginUsuario(), 3);
    }

    public function sucPrincipal(Request $request)
    {
        $sucursal = DB::select('exec GetSucursalXid @idSucursal=' . $request->id);

        $fecha = new \DateTime();

        $principal = 0;
        $id_cliente_INT = (int)$sucursal[0]->idClienteINT;
        $idSucursalINT = $sucursal[0]->idSucursalINT;
        $id_usuario = $this->idUsuario();
        $id_sucursal = $request->id;
        $sucursal_principal = Sucursales::where('idClienteINT', $id_cliente_INT)->where('principal', 1)->first();

        if ($sucursal_principal == null) {
            $suc_Principal = null;
        } else {
            $suc_Principal = $sucursal_principal->idSucursal;
        }

        if ($sucursal[0]->principal == 0) {
            $principal = 1;
        }

        DB::statement(
            'exec spPrincipalSucursal ?, ?, ?, ?, ?, ?',
            [
                $id_usuario,
                $fecha->format('Y-m-d H:i:s'),
                $principal,
                $id_sucursal,
                $suc_Principal,
                $idSucursalINT
            ]
        );

        $Sucursal_ = Sucursales::where('idSucursal', $id_sucursal)->first();

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($Sucursal_, true)));

        $this->bitacora($this->modulo, 'Asignar Principal ', 'Sucursal / ' . $cadena  . ' / ', $id_sucursal, $this->loginUsuario(), 3);
    }

    public function direccionPrincipal($id)
    {

        $id_cliente_INT = $id;

        $direccion_principal = ClientesSuc::where('ClienteINT', $id_cliente_INT)->first();

        return $direccion_principal;
    }
}
