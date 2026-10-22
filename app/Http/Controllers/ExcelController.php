<?php

namespace App\Http\Controllers;

use App\Clientes;
use App\Exports\ClientesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
	/**

     * Create a new controller instance.

     *

     * @return void

     */

    public function importExportView(){

        return view('import_export');

    }



    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function importFile(Request $request){

        if($request->hasFile('sample_file')){

            $path = $request->file('sample_file')->getRealPath();

            $data = Excel::load($path)->get();



            if($data->count()){

                foreach ($data as $key => $value) {

                    $arr[] = ['title' => $value->title, 'body' => $value->body];

                }

                if(!empty($arr)){

                    DB::table('products')->insert($arr);

                    dd('Insert Recorded successfully.');

                }

            }

        }

        dd('Request data does not have any files to import.');      

    } 



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
