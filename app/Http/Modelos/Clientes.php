<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    // protected $table = 'v_catClientes';
    protected $table = 'v_catClientesR2';

    public function getRouteKeyName()
    {
        return "idClienteINT";
    }

}
