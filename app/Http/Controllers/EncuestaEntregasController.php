<?php

namespace App\Http\Controllers;

use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class EncuestaEntregasController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public function actualizarEntrega(Request $request)
    {
        DB::statement('exec spUpdaterepEntrega ?,?,?', [
            $request->respuesta,
            strtoupper($request->motivoCalificacion),
            $request->id,
        ]);
    }

    public function repEntregas()
    {
        $login = $this->loginUsuario();
        $entregas = DB::table('v_repEntregas')->where('Login', $login)->where('respuesta', null)
                                              ->orWhere('LoginAuxiliar', $login)->where('respuesta', null)
                                              ->orWhere('LoginAuxiliar2', $login)->where('respuesta', null)
                                              ->orWhere('LoginAuxiliar3', $login)->where('respuesta', null)
                                              ->orWhere('LoginAuxiliar4', $login)->where('respuesta', null)->get();

        return DataTables::of($entregas)
            ->make(true);

    }
}
