<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Prospectos extends Model
{
    protected $table = 'Prospectos';

    public function getRouteKeyName()
    {
        return "idProspecto";
    }
}
