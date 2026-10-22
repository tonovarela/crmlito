<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ClientesSuc extends Model
{
    protected $table = 'v_catClientes';

    public function getRouteKeyName()
    {
        return "idClienteINT";
    }
}
