<?php

use Illuminate\Support\Facades\Route;

// Define the routes for the ElementosPresupuestadosV2Controller
// Route::middleware(['accessElementosPresupuestados'])->resource('/elementos', 'ElementosPresupuestadosV2Controller')->names('elements');
Route::middleware(['accessElementosPresupuestados'])
->name('elements.')
->prefix('/elementosp')
->group(function () {
    Route::get('/index', 'ElementosPresupuestadosV2Controller@index')->name('index');
    Route::get('/create/{idOportunidad?}/{regresaOport?}', 'ElementosPresupuestadosV2Controller@create')->name('create');
    Route::post('/store', 'ElementosPresupuestadosV2Controller@store')->name('store');
    Route::get('/{id}/edit/{fromOport?}', 'ElementosPresupuestadosV2Controller@edit')->name('edit');
    Route::post('/update/{id}', 'ElementosPresupuestadosV2Controller@update')->name('update');
    Route::get('/sorted', 'ElementosPresupuestadosV2Controller@sorted')->name('sorted');
    Route::get('/ultimo-folio', 'ElementosPresupuestadosV2Controller@ultimoFolio')->name('ultimoFolio');
    Route::post('/clonar', 'ElementosPresupuestadosV2Controller@clonar')->name('clonar');
    Route::post('/guardarCambios', 'ElementosPresupuestadosV2Controller@guardarCambios')->name('guardarCambios');

    // Ruta para sortear los elementos presupuestados
    Route::get('/get-vendedores', 'ElementosPresupuestadosApiController@getVendedores')->name('getVendedores');
    Route::get('/get-clientes', 'ElementosPresupuestadosApiController@getClientes')->name('getClientes');
    Route::get('/get-contactos', 'ElementosPresupuestadosApiController@getContactos')->name('getContactos');

    // generate PDF for Elementos Presupuestados
    Route::get('/generarPdfElemento/{id}/pdf', 'ElementosPresupuestadosV2Controller@generarPdfElemento')->name('generarPdfElemento');
    Route::post('/changeEstatusEP', 'ElementosPresupuestadosV2Controller@changeEstatusEP')->name('changeEstatusEP');

    Route::post('/finalizarCambio', 'ElementosPresupuestadosV2Controller@finalizarCambio')->name('finalizarCambio');
    Route::post('/historicoCambios', 'ElementosPresupuestadosV2Controller@historicoCambios')->name('historicoCambios');
});

Route::get('/generarPdfElementoInter/{id}/pdf', 'ElementosPresupuestadosV2Controller@generarPdfElementoInter');

Route::post('/guardarCambiosPrecios', 'ElementosPresupuestadosV2Controller@guardarCambiosPrecios')->name('guardarCambiosPrecios');
Route::post('/changePrecios', 'ElementosPresupuestadosV2Controller@changePrecios')->name('changePrecios');
Route::post('/changePrecioAnterior', 'ElementosPresupuestadosV2Controller@changePrecioAnterior')->name('changePrecioAnterior');
Route::post('/changePrecioNuevo', 'ElementosPresupuestadosV2Controller@changePrecioNuevo')->name('changePrecioNuevo');
Route::post('/marcarRealizado', 'ElementosPresupuestadosV2Controller@marcarRealizado')->name('marcarRealizado');
Route::post('/marcarRealizadoCE', 'ElementosPresupuestadosV2Controller@marcarRealizadoCE')->name('marcarRealizadoCE');

Route::get('/get-oportunidades', 'ElementosPresupuestadosApiController@getOportunidades')->name('getOportunidadesEP');
Route::get('/get-oportunidades-general', 'ElementosPresupuestadosApiController@getOportunidadesGeneral')->name('getOportunidades');
Route::get('/get-oportunidad', 'ElementosPresupuestadosApiController@getOportunidad')->name('getOportunidad');
Route::get('/get-plantillas', 'ElementosPresupuestadosApiController@getPlantillas')->name('getPlantillas');
Route::get('/get-tipos-producto', 'ElementosPresupuestadosApiController@getTiposProducto')->name('getTiposProducto');
Route::get('/get-data-form', 'ElementosPresupuestadosApiController@getDataForm')->name('getDataForm');
Route::get('/get-list-relation-EPV2/{idOportunidad?}', 'ElementosPresupuestadosApiController@getListRelationEPV2')->name('getListRelationEPV2');
Route::get('/get-list-relation-EPV2-activos/{idOportunidad?}', 'ElementosPresupuestadosApiController@getListRelationEPV2Activos')->name('getListRelationEPV2Activos');

Route::get('/get-plantilla', 'ElementosPresupuestadosApiController@getPlantilla')->name('getPlantilla');
Route::get('/getCambiosPendientes', 'ElementosPresupuestadosApiController@getCambiosPendientes')->name('getCambiosPendientes');
Route::post('/obtenerNumPresupuestosEP', 'ElementosPresupuestadosV2Controller@obtenerNumPresupuestosEP')->name('obtenerNumPresupuestosEP');

Route::post('/cerrarYAplicarCambioEspecificaciones', 'ElementosPresupuestadosV2Controller@cerrarYAplicarCambioEspecificaciones')->name('cerrarYAplicarCambioEspecificaciones');
Route::post('/registrarNuevoCambioEspecificaciones', 'ElementosPresupuestadosV2Controller@registrarNuevoCambioEspecificaciones')->name('registrarNuevoCambioEspecificaciones');
Route::post('/getCambioEspecificacionesByIdEP', 'ElementosPresupuestadosV2Controller@getCambioEspecificacionesByIdEP')->name('getCambioEspecificacionesByIdEP');
Route::post('/validarElementosConCambiosPendientes', 'ElementosPresupuestadosV2Controller@validarElementosConCambiosPendientes')->name('validarElementosConCambiosPendientes');

Route::post('/getElementosPresupuestadosByIds', 'SolicitudCotizacionController@getElementosPresupuestadosByIds')->name('getElementosPresupuestadosByIds');
Route::post('/registrarNuevoPresupuesto', 'ElementosPresupuestadosV2Controller@registrarNuevoPresupuesto')->name('registrarNuevoPresupuesto');
Route::get('/obtenerFolioPresupuestosEP', 'ElementosPresupuestadosV2Controller@obtenerFolioPresupuestosEP')->name('obtenerFolioPresupuestosEP');
Route::get('/obtenerPrecioPresupuestosEP', 'ElementosPresupuestadosV2Controller@obtenerPrecioPresupuestosEP')->name('obtenerPrecioPresupuestosEP');

Route::post('/guardar-multipresupuesto-excel', 'ElementosPresupuestadosV2Controller@guardarMultipresupuestoExcel')->name('guardarMultipresupuestoExcel');

Route::get('/test2', 'ElementosPresupuestadosV2Controller@test2')->name('test2');