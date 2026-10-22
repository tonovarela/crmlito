<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'v_catClientes';

    public function getRouteKeyName()
    {
        return "idClienteINT";
    }

}
