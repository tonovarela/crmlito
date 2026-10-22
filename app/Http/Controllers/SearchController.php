<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function codigoPostal(Request $request)
    {
        $id_codigo = $request->get('id_Codigo');

        $datos_codigo = DB::select('exec GetCodigoPostaleXidSAT @idCodigo=' . $id_codigo);

        return $datos_codigo;
    }
}
