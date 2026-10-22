<?php

namespace App\Http\Controllers;

use App\Exports\ClientesExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function exportFile($type){

        return Excel::download(new ClientesExport, 'Clientes.xlsx');

        // return Excel::create('hdtuto_demo', function($excel) use ($clientes) {

        //     $excel->sheet('sheet name', function($sheet) use ($clientes)

        //     {

        //         $sheet->fromArray($clientes);

        //     });

        // })->download($type);

    }      

}
