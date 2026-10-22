<?php

namespace App\Http\Modelos;

use Illuminate\Database\Eloquent\Model;

class catEstatusTblPedidos extends Model
{
    /**
     * Nombre de la tabla principal en la base datos
     * 
     * @var string $table
    */
    protected $table = 'catEstatusTblPedidos';

    /**
     * Nombre de la llave primaria que esta en la tabla
     * 
     * @var $primaryKey
    */
    protected $primaryKey = 'idEstatus';

    /**
     * Listado de campos que se encuentran registrados en la tabla
     * 
     * @var array $fillable
    */
    protected $fillable = [
        'estatus',
        'activo'
    ];
    
    /**
     * Desactivación de fechas
     * 
     * @var bool $timestamps
     */
    public $timestamps = false;
}