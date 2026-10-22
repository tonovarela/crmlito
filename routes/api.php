<?php

use App\Contactos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('contactos', function ($id) {
    return datatables(DB::select('exec GetContactosXidCliente @idClienteINT=' . $id))->toJson();
});
// Route::post('upDocsClientes', 'ClientesController@updocctes')->name('cliente.updocctes');///addFileDoics
Route::get('downDocsClientes/{id}', 'ClientesController@downdocsclientes')->name('cliente.downDocsClientes');///addFileDoics
// Route::post('upDocsOportunidades', 'OportunidadesController@updocoport');
Route::get('downDocsOportunidades/{id}', 'OportunidadesController@downdocsoport');
// Route::post('upDocsEnvios', 'EnviosController@updocOportEnv');
Route::get('downDocsEnvios/{id}', 'EnviosController@downdocsoport');
Route::get('pdf/generar/{id}','ElementosController@download');
Route::get('pdf/generar_ticket/{id}','EstructuralController@download_ticket');
Route::patch('addValorOportunidad', 'ElementosController@addValorOportunidad');
// Dasboards
Route::get('obt_DasboardsGraficaBar','PanelController@DasboardsGrafica');
Route::get('obt_DasboardsGraficaPie','PanelController@DasboardsTiemposEstatus');
Route::get('obt_DasboardsOportunidadesValor','PanelController@DasboardsOportunidadesValor');
Route::get('obt_DasboardsGraficaProbabilidad','PanelController@DasboardsProbabilidad');
Route::get('obt_DasboardsboxVentas','PanelController@DasboardsboxVentas');
Route::get('obt_DasboardsEstatusXagente','PanelController@DasboardsEstatusXagente');
Route::get('obt_DasboardsEstatusXagenteLlamadas','PanelController@DasboardsEstatusXagenteLlamadas');
Route::get('obt_DasboardsEstatusXagenteReuniones','PanelController@DasboardsEstatusXagenteReuniones');
Route::get('obt_DasboardsGraficaVentasVsMeta','PanelController@DasboardsGraficaVentasVsMeta');
Route::get('obt_DasboardsGraficaAgendaLlamadas','PanelController@DasboardsGraficaAgendaLlamadas');
Route::get('obt_DasboardsGraficaAgendaReuniones','PanelController@DasboardsGraficaAgendaReuniones');