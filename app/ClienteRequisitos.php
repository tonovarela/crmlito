<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteRequisitos extends Model
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
