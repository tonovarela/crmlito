<?php

namespace App\Traits;

use App\Http\Modelos\Bitacora;

trait BitacoraTrait
{
    public static function bitacora($modulo, $desc_movimiento, $tipo_documento, $id, $login, $idAccion)
    {
        if ($login == null || $login == "") {
            $login = session('usuario');
        }

        $date = new \DateTime();

        Bitacora::create([
            'modulo' => $modulo,
            'usuario' => $login,
            'movimiento' => $desc_movimiento. $tipo_documento . ' ' . $id,
            'fecha_movimiento' => $date->format('d-m-Y H:i:s'),
            'idAccion' => $idAccion,
            'idDocumento' => $id,
        ]);
    }
}
