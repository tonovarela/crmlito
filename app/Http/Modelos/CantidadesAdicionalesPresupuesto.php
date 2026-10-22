<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class CantidadesAdicionalesPresupuesto extends Model
{
    // Declaramos la tabla 
    protected $table = 'cantidades_adicionales_presupuesto';

    public $timestamps = false;

    // Declaramos las columnas de la tabla
    protected $fillable = [
        'cantidad',
        'precio',
        'usuario_id',
        'presupuesto_id',
        'solicitud_cotizacion_id',
    ];
}
