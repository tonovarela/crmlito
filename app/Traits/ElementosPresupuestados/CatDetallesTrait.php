<?php

namespace App\Traits\ElementosPresupuestados;

use Illuminate\Support\Facades\DB;

trait CatDetallesTrait
{
    public $todosLosDetalles = [];

    /**
     * Get the list of templates.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTemplates()
    {
        try {
            $this->todosLosDetalles = DB::table('cat_EP_detalle')->get();
            return $this->todosLosDetalles;
        } catch (\Exception $e) {
            return collect();
        }
    }
}