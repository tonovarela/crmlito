<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $table = 'incidenciasComisiones';

    protected $primaryKey = 'idIncidencia';

    protected $fillable = ['idIncidencia', 'monto', 'quincena', 'idMotivo', 'createdAt', 'usuarioId', 'IdVendedor', 'estatus', 'frecuencia'];

    protected $dateFormat = 'd-m-Y H:i:s';

    public $timestamps = false;
}
