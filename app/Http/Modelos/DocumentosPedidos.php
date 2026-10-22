<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class DocumentosPedidos extends Model
{
    protected $table = 'DocumentosPedidos';
    protected $primaryKey = 'idDocumento';
    public $timestamps = false;

    protected $guarded = [
        'idDocumento',
        'created_at',
        'updated_at'
    ];
}
