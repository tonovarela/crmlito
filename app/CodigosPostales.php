<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodigosPostales extends Model
{
    protected $searchableColumns = ['idCodigo'];
    protected $table = 'CodigosPostalesSAT';
}
