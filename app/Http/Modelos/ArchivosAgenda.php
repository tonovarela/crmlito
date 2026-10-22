<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class ArchivosAgenda extends Model
{
    protected $table = 'archivosAgenda';
    public $timestamps = false;
    protected $fillable = [
        'archivo',
        'idUsuario',
        'idRelacion',
        'nombreArchivo',
        'extension',
        'peso'
    ];
}
