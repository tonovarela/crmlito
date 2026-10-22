<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class bitacoraTiemposEstatus extends Model
{
    /**
     * Nombre de la tabla
     * 
     * @var string $table
     */
    protected $table = 'bitacoraTiemposEstatusOport';

    /**
     * Llave primaria de la tabla
     * 
     * @var string $primaryKey
     */
    protected $primaryKey = 'IdBitacoraTiempo';

    /**
     * Deshabilitamos temporalmente los timestamps
     * 
     * @var $timestamps
     */
    public $timestamps = false;
}


