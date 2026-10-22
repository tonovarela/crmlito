<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ClientesCombo extends Model
{
    protected $table = 'v_catClientesActivosCombo';

    public function getRouteKeyName()
    {
        return "idClienteINT";
    }
}
