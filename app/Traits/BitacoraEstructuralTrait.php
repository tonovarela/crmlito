<?php

namespace App\Traits;

use App\Http\Modelos\BitacoraEstructural;
use Illuminate\Support\Facades\DB;

trait BitacoraEstructuralTrait
{
    public static function bitacoraEstructural($desc_movimiento, $id_Folio, $id, $usuario, $anterior, $fecha_movimiento)
    {
        // DB::statement('exec sp_InsertBitacoraEstructural ?,?,?,?,?,?', [
        //     $usuario,
        //     $desc_movimiento,
        //     $fecha_movimiento,
        //     $id_Folio,
        //     $id,
        //     $anterior,
        // ]);

        BitacoraEstructural::create([
            'usuario' => $usuario,
            'movimiento' => $desc_movimiento,
            'fecha_movimiento' => $fecha_movimiento,
            'folio_Ticket' => $id_Folio,
            'id_ticket' => $id,
            'anterior' => $anterior,
        ]);
    }
}
