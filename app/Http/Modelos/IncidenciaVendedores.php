<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class IncidenciaVendedores extends Model
{
    protected $table = 'incidenciasVendedores';

    protected $primaryKey = 'id';

    protected $fillable = ['id', 'vendedor', 'idIncidencia'];

    protected $dateFormat = 'd-m-Y H:i:s';

    public $timestamps = false;
}
