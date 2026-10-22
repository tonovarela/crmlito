<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Presupuesto extends Model
{
    public $timestamps = false;
    protected $table = 'presupuestos';
    protected $fillable = [
        'folio',
        'estatus',
        'usuario_id',
        'oportunidad_id',
    ];

    /**
     * Agregamos el valor por defecto del folio
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($presupuesto) {
            if (empty($presupuesto->folio)) {
                $presupuesto->folio = DB::table('presupuestos')->max('folio') + 1;
            }
        });
    }
}
