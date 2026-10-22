<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegrantesRoles extends Model
{
    protected $table = 'v_Roles';
    protected $primaryKey = 'idUsuario';
    public $timestamps = false;
}
