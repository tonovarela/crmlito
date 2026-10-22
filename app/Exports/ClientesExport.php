<?php

namespace App\Exports;

use App\Clientes;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Clientes::limit(2500)->get();
    }
}
