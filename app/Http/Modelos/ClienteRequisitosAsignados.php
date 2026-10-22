<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ClienteRequisitosAsignados extends Model
{
    protected $table = 'ClienteRequisitosAsignados';
    protected $primaryKey = 'idDocumentosAministrativos';
    public $timestamps = false;

    protected $guarded = [
        'idDocumentosAministrativos',
        'created_at',
        'updated_at'
    ];
}
