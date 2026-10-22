<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('panel', 'PanelController@index')->name('Panel');

Route::get('repEntregas', 'EncuestaEntregasController@repEntregas')->name('repEntregas');

Route::patch('actualizarEntrega', 'EncuestaEntregasController@actualizarEntrega')->name('actualizarEntrega');

Route::get('/', 'PanelController@panel')->name('Dashboard');

Route::get('dashboard2', 'PanelController@index2')->name('Dashboard2');

Route::get('apiTripasCCambios', 'PanelController@apiTripasCCambios')->name('apiTripasCCambios');

Route::resource('oportunidades', 'OportunidadesController')->except('update')->names([
    'index' => 'Oportunidades',
    'create' => 'Nueva Oportunidad',
    'edit' => 'Editar Oportunidad'
]);

Route::put('oportunidades/{id}', 'OportunidadesController@update')->name('oportunidades.update');

Route::patch('cancelarOportunidad', 'OportunidadesController@cancelarOportunidad')->name('oportunidad.cancelarOportunidad');

Route::get('getCotizadores', 'OportunidadesController@GetCotizadores')->name('getCotizadores');

Route::get('getRevisa', 'OportunidadesController@getRevisa')->name('getRevisa');

Route::patch('cambiarCotizador', 'OportunidadesController@cambiarCotizador')->name('oportunidad.cambiarCotizador');

Route::patch('cambiarCustomer', 'OportunidadesController@cambiarCustomer')->name('oportunidad.cambiarCustomer');

Route::patch('cambiarRevisado', 'OportunidadesController@cambiarRevisado')->name('oportunidad.cambiarRevisado');

Route::get('apiSucursalesEntrega', 'OportunidadesController@apiSucursalesEntrega')->name('apiSucursalesEntrega');

Route::patch('guardarCantidad', 'OportunidadesController@guardarCantidad')->name('oportunidad.guardarCantidad');

Route::post('agregarCantidad', 'OportunidadesController@agregarCantidad')->name('oportunidad.agregarCantidad');

Route::patch('eliminarCantidad', 'OportunidadesController@eliminarCantidad')->name('oportunidad.eliminarCantidad');

Route::get('getTiempoEntrega/{id}', 'OportunidadesController@getTiempoEntrega')->name('getTiempoEntrega');

Route::patch('addOrEditTiempoEntrega', 'OportunidadesController@addOrEditTiempoEntrega')->name('oportunidad.addOrEditTiempoEntrega');

Route::get('api/tiempoEntrega/{id}', 'OportunidadesController@apiTiempoEntrega')->name('api.tiempoEntrega');

Route::patch('solicitarFechaEntrega', 'OportunidadesController@solicitarFechaEntrega')->name('oportunidad.solicitarFechaEntrega');

Route::get('downDocsOport/{id}', 'OportunidadesController@downDocsOportunidades')->name('downDocsOport');

Route::post('liberarOportunidad', 'OportunidadesController@liberarOportunidad')->name('liberarOportunidad');

// Route::get('downDocuments/{id}', 'OportunidadesController@downDocsAdmin');

Route::get('getDocumentoOport', 'OportunidadesController@getDocumentoOport')->name('getDocumentoOport');

Route::get('getDocumentoOportMail', 'OportunidadesController@getDocumentoOportMail')->name('getDocumentoOportMail');

Route::get('obtenerCC', 'OportunidadesController@obtenerCC')->name('obtenerCC');

Route::patch('autorizarArchivoOportunidad', 'OportunidadesController@autorizarArchivoOportunidad')->name('oportunidad.autorizarArchivoOportunidad');

Route::patch('desautorizarArchivoOportunidad', 'OportunidadesController@desautorizarArchivoOportunidad')->name('oportunidad.desautorizarArchivoOportunidad');

Route::get('getOportunidad', 'OportunidadesController@getOportunidad')->name('oportunidades.getOportunidad');

Route::get('getHollidays', 'OportunidadesController@getHollidays')->name('getHollidays');

Route::get('GetMaxDiasEntrega', 'OportunidadesController@GetMaxDiasEntrega')->name('GetMaxDiasEntrega');

Route::patch('updateFechaEntregaDias', 'OportunidadesController@updateFechaEntregaDias')->name('updateFechaEntregaDias');

Route::patch('validarTripa', 'OportunidadesController@validarTripa')->name('validarTripa');

Route::patch('validarTripaC', 'OportunidadesController@validarTripaC')->name('validarTripaC');

Route::patch('produccionTripa', 'OportunidadesController@produccionTripa')->name('produccionTripa');

Route::patch('activarPresupuesto', 'OportunidadesController@activarPresupuesto')->name('activarPresupuesto');

Route::post('liberarTicket', 'EstructuralController@liberarTicket')->name('liberarTicket');

Route::patch('activarRealizar', 'OportunidadesController@activarRealizar')->name('activarRealizar');

Route::patch('asignarValorCambio', 'OportunidadesController@asignarValorCambio')->name('asignarValorCambio');

Route::patch('activarRealizado', 'OportunidadesController@activarRealizado')->name('activarRealizado');

Route::patch('activarOPMetrics', 'OportunidadesController@activarOPMetrics')->name('activarOPMetrics');

Route::get('obtenerOMTCs', 'OportunidadesController@obtenerOMTCs')->name('obtenerOMTCs');

Route::get('obtenerNumPresupuestos', 'OportunidadesController@obtenerNumPresupuestos')->name('obtenerNumPresupuestos');

Route::post('agregarPresupuesto', 'OportunidadesController@agregarPresupuesto')->name('agregarPresupuesto');

Route::post('agregarOPMetrics', 'OportunidadesController@agregarOPMetrics')->name('agregarOPMetrics');

Route::get('obtenerOPs', 'OportunidadesController@obtenerOPs')->name('obtenerOPs');

Route::post('enviarDocumentoMail', 'OportunidadesController@enviarDocumentoMail')->name('enviarDocumentoMail');

Route::post('insertarCorreo', 'OportunidadesController@insertarCorreo')->name('insertarCorreo');

Route::get('apiCorreos', 'OportunidadesController@apiCorreos')->name('apiCorreos');

Route::get('apiNumPresupuestos', 'OportunidadesController@apiNumPresupuestos')->name('apiNumPresupuestos');

Route::get('apiOportunidadCambios', 'OportunidadesController@apiOportunidadCambios')->name('apiOportunidadCambios');

Route::get('apiNumOPMetrics', 'OportunidadesController@apiNumOPMetrics')->name('apiNumOPMetrics');

Route::get('correos', 'OportunidadesController@correos')->name('Correos');

Route::post('upFirma', 'OportunidadesController@upFirma')->name('upFirma');

Route::get('cards/{rol}/{id_usuario}/{mes}/{anio}/{agente_id}', 'PanelController@cards')->name('cards');

Route::get('eficiencia/{mes}/{anio}', 'PanelController@eficiencia')->name('eficiencia');

Route::get('obtenerSolicitudesOP', 'OportunidadesController@obtenerSolicitudesOP')->name('obtenerSolicitudesOP');

Route::get('obtenerOportunidadPTVeinteDias', 'OportunidadesController@obtenerOportunidadPTVeinteDias')->name('obtenerOportunidadPTVeinteDias');

Route::post('clonarOportunidad', 'OportunidadesController@clonarOportunidad')->name('clonarOportunidad');

Route::get('editarOportunidad', 'OportunidadesController@editarOportunidad')->name('editarOportunidad');

Route::get('obtenerRol', 'OportunidadesController@obtenerRol')->name('obtenerRol');

Route::get('obtenerTipoOportunidad', 'OportunidadesController@obtenerTipoOportunidad')->name('obtenerTipoOportunidad');

Route::get('obtenerMotivoPerdida', 'OportunidadesController@obtenerMotivoPerdida')->name('obtenerMotivoPerdida');

Route::get('obtenerPrevision', 'OportunidadesController@obtenerPrevision')->name('obtenerPrevision');

Route::get('obtenerProbabilidad', 'OportunidadesController@obtenerProbabilidad')->name('obtenerProbabilidad');

Route::get('obtenerEquipos', 'OportunidadesController@obtenerEquipos')->name('obtenerEquipos');

Route::get('obtenerOportunidadSolOPCincoDias', 'OportunidadesController@obtenerOportunidadSolOPCincoDias')->name('obtenerOportunidadSolOPCincoDias');

Route::get('tripaCCRealizada', 'OportunidadesController@tripaCCRealizada')->name('tripaCCRealizada');

Route::get('getListRelationsCambiosMenores/{idOportunidad}', 'OportunidadesController@getListRelationsCambiosMenores')->name('getListRelationsCambiosMenores');
Route::post('guardarCambiosMinimos', 'OportunidadesController@guardarCambiosMinimos')->name('guardarCambiosMinimos');
Route::post('marcarRealizadoCM', 'OportunidadesController@marcarRealizadoCM')->name('marcarRealizadoCM');

// Ruta para cambiar el estatus de las tripas con notas de correcciones
Route::get('tripaCCorreccioneRealizada', 'OportunidadesController@tripaCCorreccioneRealizada')->name('tripaCCorreccioneRealizada');





Route::resource('clientes', 'ClientesController')->except('create')->names([
    'index' => 'Clientes',
    'edit' => 'Editar Cliente'
]);

Route::resource('candidatos', 'ProspectosController')->except('create')->names([
    'index' => 'Candidatos',
    'edit' => 'Editar Candidato'
]);

Route::patch('/darDeBajaProspecto/', 'ProspectosController@darDeBajaProspecto')->name('prospecto.darDeBajaProspecto');

Route::patch('/asignaOportunidades/', 'ProspectosController@asignaOportunidades')->name('prospecto.asignaOportunidades');

Route::patch('cambiarAgenteProspecto', 'ProspectosController@cambiarAgente')->name('prospecto.cambiarAgenteProspecto');

Route::patch('convertirCliente', 'ProspectosController@convertirCliente')->name('convertirCliente'); ///deleteFileDoics

// ==============================USUARIOS

Route::resource('usuarios', 'UsuariosController')->except('create, show, destroy')->names([
    'index' => 'Usuarios'
]);

Route::get('api/usuarios', 'UsuariosController@apiUsuarios')->name('api.usuarios');

Route::patch('accesoCandy', 'UsuariosController@accesoCandy')->name('acceso.candy');

Route::patch('accesoLito', 'UsuariosController@accesoLito')->name('acceso.lito');

Route::get('api/permisos/{id}', 'UsuariosController@apipermisos')->name('api.permisos');

Route::get('getRoles', 'UsuariosController@GetRoles')->name('getRoles');

Route::patch('cambiarRol', 'UsuariosController@cambiarRol')->name('usuario.cambiarRol');

Route::get('appsLito/{id}', 'UsuariosController@appsLito')->name('appsLito');







Route::post('api/upDocsClientes', 'ClientesController@updocctes')->name('cliente.updocctes'); ///addFileDoics

Route::patch('/deleteDocsClientes/', 'ClientesController@deletedocctes')->name('cliente.deletedocctes'); ///deleteFileDoics

Route::patch('/darDeBaja/', 'ClientesController@darDeBaja')->name('cliente.darDeBaja');

Route::patch('/actualizarBuro/', 'ClientesController@actualizarBuro')->name('cliente.actualizarBuro');

Route::patch('cambiarAgente', 'ClientesController@cambiarAgente')->name('cliente.cambiarAgente');

Route::patch('actualizarTop', 'ClientesController@actualizarTop')->name('cliente.actualizarTop');

Route::get('api/clientes', 'ClientesController@apiClientes')->name('api.clientes');

Route::get('api/prospectos', 'ProspectosController@apiProspectos')->name('api.prospectos');

Route::get('api/oportunidades', 'OportunidadesController@apiOportunidades')->name('api.oportunidades');

Route::get('api/OportunidadesPanelVentas', 'OportunidadesController@apiOportunidadesPanelVentas')->name('api.OportunidadesPanelVentas');

Route::get('api/oportunidades/docs', 'OportunidadesController@apiOportDocs')->name('api.oportunidades.docs');

Route::get('documentosValidados', 'OportunidadesController@documentosValidados')->name('documentosValidados');

Route::get('obtenerNombreUsuario', 'OportunidadesController@obtenerNombreUsuario')->name('obtenerNombreUsuario');

Route::get('obtenerEmlMsg', 'OportunidadesController@obtenerEmlMsg')->name('obtenerEmlMsg');

Route::patch('addValorOportunidad', 'OportunidadesController@addValorOportunidad');

Route::patch('autorizarArchivo', 'OportunidadesController@autorizarArchivo')->name('autorizarArchivo');

Route::patch('rechazarDocumento', 'OportunidadesController@rechazarDocumento')->name('rechazarDocumento');

Route::patch('actualizarNombreDocumento', 'OportunidadesController@actualizarNombreDocumento')->name('actualizarNombreDocumento');

Route::post('api/upDocsOportunidades', 'OportunidadesController@updocoport');

Route::patch('/deleteDocsOportunidades/', 'OportunidadesController@deleteDocOport')->name('oportunidad.deleteDocsOportunidades'); ///deleteFileDoics

Route::get('/getDocumentosXidOportunidad/', 'OportunidadesController@getDocumentosXidOportunidad')->name('oportunidad.getDocumentosXidOportunidad');

Route::get('usuariosAutCredito', 'ClientesController@usuariosAutCredito')->name('usuariosAutCredito');

Route::get('/getEPXOport/', 'OportunidadesController@getEPXOport')->name('oportunidad.getEPXOport');

Route::get('getDocumentoCliente', 'ClientesController@getDocumentoCliente')->name('getDocumentoCliente');

Route::patch('convertirEnProspecto', 'ClientesController@convertirEnProspecto')->name('convertirEnProspecto'); ///deleteFileDoics



// ==============================SUCURSALES

Route::resource('sucursales', 'SucursalesController')->except('create')->names([
    'index' => 'Sucursales'
]);

Route::get('api/sucursales', 'SucursalesController@apiSucursales')->name('api.sucursales');

Route::get('api/sucursales/all', 'SucursalesController@apiSucursalesTodas')->name('api.sucursales.all');


Route::patch('/darDeBajaSucursal/', 'SucursalesController@darDeBajaSucursal')->name('sucursal.darDeBajaSucursal');

Route::patch('/sucPrincipal/', 'SucursalesController@sucPrincipal')->name('sucursal.sucPrincipal');

Route::get('direccionPrincipal/{id}', 'SucursalesController@direccionPrincipal')->name('direccionPrincipal');

Route::get('getDireccionFiscal/{id}', 'SucursalesController@getDireccionFiscal')->name('getDireccionFiscal');








Route::resource('contactos', 'ContactosController')->except('create')->names([
    'index' => 'Contactos'
    ]);
    
    
Route::post('/actualizarEnvio', 'ContactosController@actualizarEnvio')->name('contacto.actualizarEnvio');




Route::get('api/oportRetenidas/{id}', 'OportunidadesController@apiOportunidadesRetenidas')->name('api.oportRetenidas');

Route::get('api/contactos', 'ContactosController@apiContactos')->name('api.contactos');

Route::get('api/contactos/all', 'ContactosController@apiContactosTodos')->name('api.contactos.all');

Route::get('api/clientes/docs', 'ClientesController@apiCtesDocs')->name('api.clientes.docs');

Route::patch('/updatePrincipal/', 'ContactosController@updatePrincipal')->name('update.principal');

Route::patch('/updateCXP/', 'ContactosController@updateCXP')->name('update.contactocxp');

Route::patch('/updateCFD/', 'ContactosController@updateCFD')->name('update.contactocfd');

Route::patch('/updateActivo/', 'ContactosController@updateActivo')->name('update.activo');

Route::get('getEquipos', 'OportunidadesController@integrantesEquipos')->name('getEquipos');

Route::get('getAgentes', 'ClientesController@GetAgentes')->name('getAgentes');

Route::get('/getEstatus/', 'OportunidadesController@status')->name('oportunidades.status');

Route::get('/codigos', 'ClientesController@find')->name('codigosP');

Route::get('codigos-ajax', 'ClientesController@codigosAjax');

Route::get('/buscarcp', 'SearchController@codigoPostal')->name('buscarCP');

//---- DISEÑO ESTRUCTURAL

Route::get('apiEstructural', 'EstructuralController@apiEstructural')->name('apiEstructural');

Route::get('apiBitacoraEstructural', 'EstructuralController@apiBitacoraEstructural')->name('apiBitacoraEstructural');

Route::post('guardarTicket', 'EstructuralController@guardarTicket')->name('guardarTicket');

Route::patch('actualizarTicket', 'EstructuralController@actualizarTicket')->name('actualizarTicket');

Route::get('getEstatusESTR', 'EstructuralController@getEstatusESTR')->name('getEstatusESTR');

Route::get('obtenerFolio', 'EstructuralController@obtenerFolio')->name('obtenerFolio');

Route::get('getOperadorESTR', 'EstructuralController@getOperadorESTR')->name('getOperadorESTR');

Route::get('obtenerTicket', 'EstructuralController@obtenerTicket')->name('obtenerTicket');

Route::get('obtenerClientesCombo', 'EstructuralController@obtenerClientesCombo')->name('obtenerClientesCombo');

Route::get('obtenerBarnices', 'EstructuralController@obtenerBarnices')->name('obtenerBarnices');

Route::patch('asignarFechaCompromiso', 'EstructuralController@asignarFechaCompromiso')->name('asignarFechaCompromiso');

Route::patch('cambiarEstatusESTR', 'EstructuralController@cambiarEstatusESTR')->name('cambiarEstatusESTR');

Route::patch('cambiarOperadorESTR', 'EstructuralController@cambiarOperadorESTR')->name('cambiarOperadorESTR');

Route::patch('DErelation', 'EstructuralController@DErelation')->name('DErelation');

Route::get('apiEstructuralDocs', 'EstructuralController@apiEstructuralDocs')->name('apiEstructuralDocs');

Route::post('upDocsEstructural', 'EstructuralController@upDocsEstructural')->name('upDocsEstructural');

Route::get('getDocumentoEstructural', 'EstructuralController@getDocumentoEstructural')->name('getDocumentoEstructural');

Route::get('getBriefEstructural', 'EstructuralController@getBriefEstructural')->name('getBriefEstructural');

Route::get('downDocsEstructural/{id}', 'EstructuralController@downDocsEstructural');

Route::patch('deleteDocsEstructural', 'EstructuralController@deleteDocsEstructural')->name('deleteDocsEstructural');

Route::patch('liberar', 'EstructuralController@liberar')->name('liberar');

Route::get('getDocumentoEstrMail', 'EstructuralController@getDocumentoEstrMail')->name('getDocumentoEstrMail');

Route::get('getTicketEstrOport', 'EstructuralController@getTicketEstrOport')->name('getTicketEstrOport');

Route::post('enviarMovimientoEstr', 'EstructuralController@enviarMovimientoEstr')->name('enviarMovimientoEstr');

Route::post('storeHoursTicket', 'EstructuralController@storeHoursTicket')->name('storeHoursTicket');

Route::resource('/estructural', 'EstructuralController')->except('update')->names([
    'index' => 'DiseñoEstructural',
    'create' => 'Nuevo Ticket',
    'edit' => 'Editar Ticket',
]);

//---- ELEMENTOS PRESUPUESTADOS

Route::get('elementos/create/{id}', 'ElementosController@create')->name('elementos.create');

Route::resource('/elementosv2', 'ElementosController')->except('update')->names([
    'index' => 'ElementosPresupuestados',
    'create' => 'Nuevo Elemento',
    'edit' => 'Editar Elemento',
]);
Route::post('elementos/{id}', 'ElementosController@update')->name('elementos.update');
Route::get('tblelementos/{id}', 'ElementosController@apiElementos')->name('tblelementos_r');
Route::get('/getCatalogoPapel/', 'ElementosController@catalogoPapel')->name('getCatalogoPapel');
Route::get('/getCombos/', 'ElementosController@getCombos')->name('getgetCombos');
Route::get('/getCombosInfoGral/', 'ElementosController@getCombosInfoGral')->name('getgetCombosInfoGral');
Route::get('/gettblelementosp/', 'ElementosController@gettblelementosp')->name('gettblelementosp');
Route::patch('EPrelation', 'ElementosController@EPrelation')->name('elementos.EPrelation');
Route::get('obtNotas', 'ElementosController@obtNotas')->name('elementos.obtNotas');
Route::patch('Addnotas', 'ElementosController@Addnotas')->name('elementos.Addnotas');
Route::post('clonarElemento', 'ElementosController@clonarElemento')->name('clonarElemento');
// Route::patch('addOrEditTiempoEntrega', 'OportunidadesController@addOrEditTiempoEntrega')->name('oportunidad.addOrEditTiempoEntrega');


///----CLIENTES OPORTUNIDADES-----
Route::get('/tblClientesOport/{id}', 'ClientesController@tblClientesOport')->name('tblClientesOport');
///-------------------------------


Route::get('export-file/{type}', 'ExcelController@exportFile')->name('export.file');

Route::resource('requisitos', 'RequisitosController')->except('create')->names([
    'index' => 'Requisitos',
]);

Route::get('api/requisitos', 'RequisitosController@apiRequisitos')->name('api.requisitos');

Route::patch('/updateEstatus/', 'RequisitosController@updateEstatus')->name('update.estatus');

Route::resource('envios', 'EnviosController')->names([
    'index' => 'Envíos',
    'create' => 'Nuevo Envío',
    'edit' => 'Editar Envío'
]);

Route::get('api/envios', 'EnviosController@apiEnvios')->name('api.envios');

Route::get('api/oportEnvRetenidas/{id}', 'EnviosController@apiOportunidadesEnviosRetenidas')->name('api.oportEnvRetenidas');

Route::get('api/envios/docs/{id}', 'EnviosController@apiEnvDocs')->name('api.envios.docs');

Route::post('api/upDocsEnvios', 'EnviosController@updocOportEnv');

Route::patch('/deleteDocsEnvios/', 'EnviosController@deleteDocEnv')->name('envio.deleteDocEnv');

Route::get('getOportunidades', 'EnviosController@getOportunidades')->name('envio.getOportunidades');

Route::get('getOportunidadesAsignadas', 'EnviosController@getOportunidadesAsignadas')->name('envio.getOportunidadesAsignadas');

Route::get('getFoliosCot', 'EnviosController@getFoliosCot')->name('envio.getFoliosCot');

Route::get('obtenerOPsEnvios', 'EnviosController@obtenerOPsEnvios')->name('obtenerOPsEnvios');

Route::get('obtenerOMTCsEnvios', 'EnviosController@obtenerOMTCsEnvios')->name('obtenerOMTCsEnvios');

Route::patch('cancelarEnvio', 'EnviosController@cancelarEnvio')->name('envio.cancelarEnvio');

Route::patch('infoEnvio', 'EnviosController@infoEnvio');

// Route::get('downDocuments/{id}', 'EnviosController@downDocsAdmin');

Route::get('getDocumentoEnvio', 'EnviosController@getDocumentoEnvio')->name('getDocumentoEnvio');

Route::get('getClientes', 'EnviosController@getClientes')->name('getClientes');

Route::get('getValorOportunidad', 'EnviosController@getValorOportunidad')->name('getValorOportunidad');




Route::resource('documentos_administrativos', 'DocumentosAdministrativosController')->except('create, store, show, destroy')->names([
    'index' => 'Documentos Administrativos',
]);

Route::get('apiPedidos', 'DocumentosAdministrativosController@apiPedidos')->name('apiPedidos');

Route::post('agregarRequisitos', 'DocumentosAdministrativosController@agregarRequisitos');

Route::get('listaRequisitos', 'DocumentosAdministrativosController@listaRequisitos')->name('listaRequisitos');

Route::post('api/upDocsAdmin', 'DocumentosAdministrativosController@updocadmins');

Route::patch('/deleteDocsAdmins/', 'DocumentosAdministrativosController@deleteDocAdmins')->name('oportunidad.deleteDocOport');

Route::get('downDocsAdmin/{id}/{tipo}', 'DocumentosAdministrativosController@downDocsAdmin');

Route::get('getDocumento', 'DocumentosAdministrativosController@getDocumento')->name('getDocumento');


// ============================================================= ENTREGAS ===========================================================

Route::resource('entregas', 'EntregasController')->except('update')->names([
    'index' => 'Entregas',
]);


// ============================================================= LLAMADAS - REUNIONES ===========================================================

Route::resource('agenda', 'AgendaController')->except('update')->names([
    'index' => 'Agenda',
]);

// Route::get('calendario','AgendaController@calender')->name('calendario');

Route::get('eventos', 'AgendaController@eventos')->name('eventos');

Route::resource('llamadas', 'LlamadasController')->names([
    'index' => 'Llamadas'
]);

Route::get('api/Llamadas', 'LlamadasController@apiLlamadas')->name('apiLlamadas');

Route::patch('cancelarLlamada', 'LlamadasController@cancelarLlamada')->name('llamada.cancelarLlamada');

Route::patch('realizada', 'LlamadasController@realizada')->name('llamada.realizada');

Route::patch('updateLlamada', 'LlamadasController@updateLlamada')->name('llamada.updateLlamada');

Route::resource('reuniones', 'ReunionesController')->except('update')->names([
    'index' => 'Reuniones',
]);

Route::get('api/Reuniones', 'LlamadasController@apiReuniones')->name('apiReuniones');

Route::get('getContactos', 'LlamadasController@getContactos')->name('getContactos');

Route::get('getContactosOportunidades', 'LlamadasController@getContactosOportunidades')->name('getContactosOportunidades');
Route::post('getUserTblCom', 'LlamadasController@getUserTblCom')->name('getUserTblCom');

Route::get('getTelefonosContacto', 'LlamadasController@getTelefonosContacto')->name('getTelefonosContacto');

Route::get('getTelefonosContacto', 'LlamadasController@getTelefonosContacto')->name('getTelefonosContacto');


// Ruta para obtener los documentos con correcciones
Route::get('api/getListCorrecciones', 'PanelController@getListCorrecciones')->name('getListCorrecciones');

/**
 * Rutas para modulos de administración de usuarios customers y cotizadores
 * 
 * @return mixed
 */
Route::get('/activacion-cotizadores-customers', 'AdminCusCotController@index')->name('Administracion de cotizadores y customers');
Route::get('/apiGetCotizadores', 'AdminCusCotController@apiGetCotizadores')->name('apiGetCotizadores');
Route::get('/apiGetCustomers', 'AdminCusCotController@apiGetCustomers')->name('apiGetCustomers');
Route::post('/changeActiveUser', 'AdminCusCotController@changeActiveUser')->name('changeActiveUser');

Route::post('/rechazarOportunidad', 'OportunidadesController@rechazarOportunidad')->name('rechazarOportunidad');


Route::get('/logout', function () {
    Session::flush();
    return redirect('https://servicios.litoprocess.com/litoapps');
})->name('Logout');

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return "Cache is cleared";
})->name('cache');

/**
 * Sección de pedidos
 * 
 * 
 */
// Rutas para la administración de pedidos
Route::get('/admin-comisiones', 'AdminPedidosController@index')->name('Administracion de pedidos');
Route::get('api/getPedidos', 'AdminPedidosController@getPedidos')->name('get-pedidos.index');
Route::post('api/getPedidos/tbl3', 'AdminPedidosController@getPedidos')->name('get-pedidos');
Route::post('/getConsecutivo', 'AdminPedidosController@getConsecutivo');
Route::post('/updateComision', 'AdminPedidosController@updateComision');
Route::post('/updateRetenido', 'AdminPedidosController@updateRetenido');
Route::post('/updateFirma', 'AdminPedidosController@updateFirma');
Route::post('/getMotivoRechazo', 'AdminPedidosController@getMotivoRechazo');
Route::post('/generateReportPl', 'AdminPedidosController@generateReportPl');
Route::post('/generateReportPlV2', 'AdminPedidosController@generateReportPlV2');
Route::post('/getRoleUser', 'AdminPedidosController@getRoleUser');
Route::post('/facturas', 'AdminPedidosController@getFacturas');

Route::post('/changeStatusAnticipo', 'AdminPedidosController@changeStatusAnticipo');
Route::post('/addNewAnticipo', 'AdminPedidosController@addNewAnticipo');
Route::get('api/getListAnticipos', 'AdminPedidosController@getListAnticipos');
Route::post('/getTotalAnticipos', 'AdminPedidosController@getTotalAnticipos');
Route::post('/deleteAnticipo', 'AdminPedidosController@deleteAnticipo');
Route::post('/marcarComisionPagada', 'AdminPedidosController@marcarComisionPagada');
Route::post('/getListEstatus', 'AdminPedidosController@getListEstatus');
Route::get('/api/getIncidencias', 'AdminPedidosController@getIncidencias');
Route::post('/getSelectIncidencias', 'AdminPedidosController@getSelectIncidencias');
Route::post('/saveIncidencia', 'AdminPedidosController@saveIncidencia');
Route::post('/saveIncidenciaFinal', 'AdminPedidosController@saveIncidenciaFinal');
Route::post('/deleteIncidencia', 'AdminPedidosController@deleteIncidencia');
Route::post('/getIncidenciaById', 'AdminPedidosController@getIncidenciaById');
Route::post('/updateIncidencia', 'AdminPedidosController@updateIncidencia');
Route::post('/changeStatusIncidencia', 'AdminPedidosController@changeStatusIncidencia');
Route::post('/redirectOp', 'AdminPedidosController@redirectOpportunity');
Route::post('/getTripaProduccion', 'AdminPedidosController@getTripaProduccion');
Route::post('/knowIfWasSigned', 'AdminPedidosController@knowIfWasSigned');

// Ruta para actualizar los precios de las oportunidades
Route::post('/updatePrices', 'OportunidadesController@updatePrices');
Route::post('api/getListSolicitudes', 'OportunidadesController@getListSolicitudes');
Route::post('/addNewChange', 'OportunidadesController@addNewChange');
Route::post('/updateChangeEstatus', 'OportunidadesController@updateChangeEstatus');
Route::post('/deleteSolicitudCambios', 'OportunidadesController@deleteSolicitudCambios');
Route::post('/getTotalSolicitudes', 'OportunidadesController@getTotalSolicitudes');
Route::post('/getAllPreviousCambiosMinimos', 'OportunidadesController@getAllPreviousCambiosMinimos');
Route::post('/getCambioMinimoContent', 'OportunidadesController@getCambioMinimoContent');

Route::post('/getMotivosRechazoDocumento', 'OportunidadesController@getMotivosRechazoDocumento');
Route::post('/getMotivoRechazoDoc', 'OportunidadesController@getMotivoRechazoDoc');

Route::post('/solicitarFt', 'OportunidadesController@solicitarInformacionFichaTecnica');
Route::post('/getTripaAutSinCam', 'OportunidadesController@getTripaAutSinCam');

Route::post('/setRepetitivo', 'OportunidadesController@setRepetitivo');

// Rutas nuevas para el modulo de incidencias
// Ruta para obtener los vendedores que seran para la parte reactiva
Route::get('/getVendedoresR', 'AdminPedidosController@getVendedoresR')->name('getVendedoresR');
Route::get('api/getIncidenciasByVendedor', 'AdminPedidosController@getIncidenciasByVendedor')->name('getIncidenciasByVendedor');
Route::post('/getVendedoresIncList', 'AdminPedidosController@getVendedoresIncList')->name('getVendedoresIncList');
Route::post('/saveCatMotivosIncidencias', 'AdminPedidosController@saveCatMotivosIncidencias')->name('saveCatMotivosIncidencias');
Route::post('/updateCatMotivosIncidencias', 'AdminPedidosController@updateCatMotivosIncidencias')->name('updateCatMotivosIncidencias');
Route::post('/getCatMotIncById', 'AdminPedidosController@getCatMotIncById')->name('getCatMotIncById');
Route::get('api/getCatMotivoIncidencias', 'AdminPedidosController@getCatMotivoIncidencias')->name('getCatMotivoIncidencias');
Route::post('getDateRegisterIncidencia', 'AdminPedidosController@getDateRegisterIncidencia')->name('getDateRegisterIncidencia');
Route::post('deleteCatMotInc', 'AdminPedidosController@deleteCatMotInc')->name('deleteCatMotInc');
Route::post('changeStatusCatMotInc', 'AdminPedidosController@changeStatusCatMotInc')->name('changeStatusCatMotInc');
Route::post('getQuincenaByDate', 'AdminPedidosController@getQuincenaByDate')->name('getQuincenaByDate');

Route::post('marcarPedidosFirmadosDireccion', 'AdminPedidosController@marcarPedidosFirmadosDireccion')->name('marcarPedidosFirmadosDireccion');
Route::get('api/getHistoricoDetails', 'AdminPedidosController@getHistoricoDetails')->name('getHistoricoDetails');

Route::post('api/marcarPedidosLiberados', 'AdminPedidosController@marcarPedidosLiberados')->name('marcarPedidosLiberados');
Route::post('getVendedoresMotivosList', 'AdminPedidosController@getVendedoresMotivosList')->name('getVendedoresMotivosList');

Route::post('solicitarCambiosOportunidad', 'OportunidadesController@solicitarCambiosOportunidad')->name('solicitarCambiosOportunidad');

Route::get('/no-autenticado', 'NoAutenticadoController@index')->name('no-autenticado');

Route::post('/actualizarSolicitarPresupuesto', 'ClientesController@actualizarSolicitarPresupuesto')->name('actualizarSolicitarPresupuesto');

Route::post('/getNotaRechazoPresupuesto', 'OportunidadesController@getNotaRechazoPresupuesto')->name('getNotaRechazoPresupuesto');
Route::post('/getUltimaNotaRechazoPresupuesto', 'OportunidadesController@getUltimaNotaRechazoPresupuesto')->name('getUltimaNotaRechazoPresupuesto');


use Illuminate\Support\Facades\App;

Route::get('/test', function () {
    $dompdf = App::make("dompdf.wrapper");
    // Configuramos a tamaño carta
    $dompdf->setPaper('letter');
    // Cargamos la vista
    $dompdf->loadView("pdf/presupuesto-elementos");
    return $dompdf->stream(); //Para visualizar en el navegador
});

/**
 * Seccion de documentos de oportunidades
 * 
 * @return mixed
 */
Route::get('/documentos-oportunidades', 'DocumentosOportunidadesController@obtenerListadoDocumentos')->name('documentos-oportunidades');
Route::post('/subir-archivo-oportunidad', 'DocumentosOportunidadesController@subirArchivoOportunidad')->name('subir-archivo-oportunidad');
Route::get('/oportunidad-realizado-estatus', 'DocumentosOportunidadesController@oportunidadRealizadoEstatus')->name('oportunidad-realizado-estatus');
Route::post('/enviar-tripa-preprensa', 'DocumentosOportunidadesController@enviarTripaPreprensa')->name('enviar-tripa-preprensa');
Route::post('/obtener-cantidades-tripa', 'OportunidadesController@obtenerCantidadesTripa')->name('obtener-cantidades-tripa');
Route::post('/actualizar-estatus-oport-prepedido', 'DocumentosOportunidadesController@actualizarEstatusOportPrepedido')->name('actualizar-estatus-oport-prepedido');
Route::post('/asignar-folio-preprensa-liberar', 'DocumentosOportunidadesController@asignarFolioPreprensaLiberar')->name('asignar-folio-preprensa-liberar');
Route::get('/obtener-checklist-op', 'DocumentosOportunidadesController@obtenerChecklistOp')->name('obtener-checklist-op');
Route::post('/guardar-checklist-op', 'DocumentosOportunidadesController@guardarChecklistOp')->name('guardar-checklist-op');
Route::get('/ver-checklist-op', 'DocumentosOportunidadesController@verChecklistOp')->name('ver-checklist-op');
Route::post('/rechazar-tripa-produccion', 'DocumentosOportunidadesController@rechazarTripaProduccion')->name('rechazar-tripa-produccion');
Route::post('/rechazar-tripa-preprensa', 'DocumentosOportunidadesController@rechazarTripaPreprensa')->name('rechazar-tripa-preprensa');
Route::post('/validar-folio-metrics/{folio}', 'OportunidadesController@validarFolioMetrics')->name('validar-folio-metrics');
Route::post('/obtener-nota-produccion', 'DocumentosOportunidadesController@obtenerNotaProduccion')->name('obtener-nota-produccion');

Route::get('/api/listadoRechazoOport', 'OportunidadesController@listadoRechazoOport')->name('listadoRechazoOport');

Route::get('/obtenerCategoriaFSCporFolio', 'PresupuestosEPController@obtenerValoresFSC')->name('obtener-valores-fsc');
Route::get('/obtenerMedidasEP', 'PresupuestosEPController@obtenerMedidasEP')->name('obtener-medidas-ep');

Route::get('/obtenerListaExcelesEPs/{idOportunidad}', 'ExcelEPController@obtenerListaExcelesEPs')->name('obtener-lista-excel-es-ep');
Route::post('/guardar-exceles-ep', 'ExcelEPController@guardarExcelesEP')->name('guardar-exceles-ep');
Route::get('/obtenerListaExcelesEPVista/{idOportunidad}', 'ExcelEPController@obtenerListaExcelesEPVista')->name('obtener-lista-excel-es-ep-vista');
Route::post('/guardarExcelEPVista', 'ExcelEPController@guardarExcelEPVista')->name('guardar-excel-ep-vista');
Route::get('/descargarExcelEPVista/{idArchivo}', 'ExcelEPController@descargarExcelEPVista')->name('descargar-excel-ep-vista');
Route::post('/obtenerExcelesEPsPorGrupo/{idGrupoEP}', 'ExcelEPController@obtenerExcelesEPsPorGrupo')->name('obtener-excel-es-ep-por-grupo');
Route::post('/descargarExcelesEPsPorGrupoZip/{idGrupoEP}', 'ExcelEPController@descargarExcelesEPsPorGrupoZip')->name('descargar-excel-es-ep-por-grupo-zip');
Route::post('/solicitarCambiosExcelEPs/{idOportunidad}', 'ExcelEPController@solicitarCambiosExcelEPs')->name('solicitar-cambios-excel-es-eps');
Route::post('/cambiarEstatusEEP', 'ExcelEPController@cambiarEstatusEEP')->name('cambiar-estatus-eep');

Route::post('/cambiarEstatusExcelEP', 'ExcelEPController@cambiarEstatusExcelEP')->name('cambiar-estatus-excel-ep');
Route::post('/obtenerEstatusOportunidad', 'ElementosPresupuestadosV2Controller@obtenerEstatusOportunidad')->name('obtener-estatus-oportunidad');