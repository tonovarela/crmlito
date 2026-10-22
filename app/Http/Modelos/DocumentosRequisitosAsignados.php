<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class DocumentosRequisitosAsignados extends Model
{
    protected $table = 'DocumentosRequisitosAsignados';
    protected $primaryKey = 'idDocumentosAsignados';
    public $timestamps = false;

    protected $guarded = [
        'idDocumentosAsignados',
        'created_at',
        'updated_at'
    ];

}
