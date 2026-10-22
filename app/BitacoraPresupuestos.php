<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BitacoraPresupuestos extends Model
{
    public $timestamps = false;
    protected $table = 'bitacora_presupuestos';
    protected $fillable = [
        'anterior',
        'nuevo',
        'usuario_id',
        'presupuesto_id',
    ];
}
