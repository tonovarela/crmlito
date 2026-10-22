<?php

namespace App\Http\Controllers;

use App\Http\Modelos\catPlantillasAlertas;
use App\Mail\SendMail;
use App\Oportunidades;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DocumentosOportunidadesController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;
    public $modulo = 'Oportunidades';
    
    /**
     * Función para obtener los documentos que se relacionan a una oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function obtenerListadoDocumentos(Request $request)
    {
        try {
            $idOportunidad = $request->idOportunidad;
            $permisos =  $this->permisosModulo($this->modulo);
            $id_usuario = $this->idUsuario();
            $idrol = $permisos[0]->idRol;

            // Obtenemos los documentos que puede ver la persona que esta logueda
            $documentos_permitidos_por_rol = DB::table('documentos_roles')
                                        ->where('rol_id', $idrol)
                                        ->where('documento_id', '<>', 13)
                                        ->where('estatus', 1)
                                        ->get();

            // Acciones que puede hacer el rol en los documentos
            $acciones_documentos_por_rol = $documentos_permitidos_por_rol;

            // Obtenemos los ids de los documentos permitidos de las tripas
            $acciones_tripas = DB::table('tripas_roles')
                                        ->where('rol_id', $idrol)
                                        ->where('estatus', 1)
                                        ->get();

            // Documentos permitidos
            $documentos_permitidos = $documentos_permitidos_por_rol->pluck('documento_id')->unique()->toArray();

            // Obtenemos los tipos de documentos que se pueden mostrar
            $documentos_mostrar = DB::table('catTipoDocumentoOport')->whereIn('idTipoDocumento', $documentos_permitidos)->orderBy('indice')->get();

            // Obtenemos los documentos que ya han sido subidos a la oportunidad
            $relacion_documentos_oport = DB::table('relOport_Documento')
                                        ->where('idOportunidad', $idOportunidad)
                                        ->get()->pluck('idDocumento')->toArray();

            $documentos_subidos = DB::table('v_DocumentosLito')
                                    ->select([
                                        'idDocumento',
                                        'descDocumento',
                                        'fecRegistro',
                                        'idTipoDocumento',
                                        'idUsuarioReg',
                                        'Extension',
                                        'Size',
                                        'Valido',
                                        'produccion',
                                        'rechazado',
                                        'antiguo',
                                        'tipoTripa',
                                        'cambiosRealizados',
                                        'isDocValido',
                                        'notasCorrecciones',
                                        'estatusCorrecciones',
                                        'isPrevalido',
                                        'solicito_prepedido',
                                        'realizo_prepedido',
                                        'usuario_registro_doc'
                                    ])
                                    ->whereIn('idDocumento', $relacion_documentos_oport)
                                    ->orderBy('fecRegistro', 'DESC')
                                    ->get();

            $tripas_cargadas = DB::table('v_DocumentosLito')
                                    ->select([
                                        'idDocumento',
                                        'descDocumento',
                                        'fecRegistro',
                                        'idTipoDocumento',
                                        'idUsuarioReg',
                                        'Extension',
                                        'Size',
                                        'Valido',
                                        'produccion',
                                        'rechazado',
                                        'antiguo',
                                        'tipoTripa',
                                        'cambiosRealizados',
                                        'isDocValido',
                                        'notasCorrecciones',
                                        'estatusCorrecciones',
                                        'isPrevalido',
                                        'solicito_prepedido',
                                        'realizo_prepedido',
                                        'usuario_registro_doc',
                                        'cantidad_preprensa',
                                        'folio_preprensa',
                                        DB::raw('(select top(1) nota from notas_produccion_tripas where documento_id = v_DocumentosLito.idDocumento order by fecha_registro desc) AS nota_produccion'),
                                        DB::raw('(select top(1) case when estatus = 2 then 1 else 0 end from checkListOP where idDocumento = v_DocumentosLito.idDocumento order by id desc) AS checklist_finalizado'),
                                    ])
                                    ->whereIn('idDocumento', $relacion_documentos_oport)
                                    ->where('idTipoDocumento', 13)
                                    ->orderBy('fecRegistro', 'DESC')
                                    ->get();

            $notasRechazoDocs = DB::table('v_DocumentosRechazadosMotivo')
                                ->whereIn('idDocumento', $relacion_documentos_oport)
                                ->get()->pluck('idDocumento')->toArray();

            $tipoDocumentoTripa = DB::table('catTipoDocumentoOport')
                                        ->where('idTipoDocumento', 13)
                                        ->first();

            // Agrupamos las tripas cargadas por tipo de tripa
            $tripas_agrupadas = [];
            foreach ($tripas_cargadas as $tripa) {
                $tripas_agrupadas[$tripa->tipoTripa][] = $tripa;
            }

            // Ordenamos las tripas agrupadas por tipo de tripa de acuerdo al siguiente orden
            // 1 = Producción
            // 2 = Autorizado S/Cambios
            // 5 = Autorizado C/Cambios
            // 3 = Por Autorizar
            // 4 = Rechazado
            // 6 = Preprensa
            $orden_tripac = [1, 6, 2, 5, 3, 4];
            $tripas_agrupadas_ordenadas = [];
            foreach ($orden_tripac as $tipo) {
                if (isset($tripas_agrupadas[$tipo])) {
                    $tripas_agrupadas_ordenadas[(string)$tipo] = $tripas_agrupadas[$tipo];
                }
            }

            // Array final de los tipos de documentos con los documentos ya subidos
            $documentos_final = [];

            // Recorremos los tipos de documentos que se pueden mostrar e incluyimos los documentos que ya han sido subidos de ese tipo
            foreach ($documentos_mostrar as $doc_tipo) {
                $documentos_de_tipo = [];
                foreach ($documentos_subidos as $doc_subido) {
                    if ($doc_subido->idTipoDocumento == $doc_tipo->idTipoDocumento) {
                        $documentos_de_tipo[] = $doc_subido;
                    }
                }

                $documentos_final[] = [
                    'id_tipo_documento' => $doc_tipo->idTipoDocumento,
                    'tipo_documento' => $doc_tipo->descTipoDocumento,
                    'tooltip' => $doc_tipo->descripcionTooltip,
                    'total_documentos' => count($documentos_de_tipo),
                    'documentos' => $documentos_de_tipo,
                ];
            }

            // Saber si tiene ordenes de compra
            $tieneOrdenesCompra = collect($documentos_subidos)->where('idTipoDocumento', 14)->first();

            // Saber si la oportunidad tiene orden de metrics
            $tieneOrdenMetrics = DB::table('Oportunidades')
                                        ->where('idOportunidad', $idOportunidad)
                                        ->whereNotNull('ops')
                                        ->first();
            
            // Saber si la oportunidad tiene prepedido solicitado
            $tienePrepedido = DB::table('Oportunidades')
                                        ->where('idOportunidad', $idOportunidad)
                                        ->where('solicita_prepedido', 1)
                                        ->first();

            // Saber si la oportunidad está cancelada
            $oportunidad = DB::table('Oportunidades')
                                ->where('idOportunidad', $idOportunidad)
                                ->where('Cancelada', 1)
                                ->first();

            return response()->json([
                'response' => true,
                'rol' => $idrol,
                'documentos_final' => $documentos_final,
                'acciones_tripas' => $acciones_tripas,
                'tripas_agrupadas' => $tripas_agrupadas_ordenadas,
                'total_tripas_cargadas' => count($tripas_cargadas),
                'acciones_documentos_por_rol' => $acciones_documentos_por_rol,
                'documentos_con_notas' => array_values($notasRechazoDocs),
                'tipo_documento_tripa' => $tipoDocumentoTripa,
                'tiene_ordenes_compra' => $tieneOrdenesCompra != null,
                'tiene_orden_metrics' => $tieneOrdenMetrics != null,
                'tiene_prepedido' => $tienePrepedido != null,
                'es_oportunidad_cancelada' => $oportunidad != null,
                'id_usuario' => $id_usuario
            ], 200);

            /* Fin Seccion para los documentos */
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener los documentos de la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para subir un archivo a una oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function subirArchivoOportunidad(Request $request)
    {
        try {
            $idOportunidad = $request->idOportunidad;
            $idTipoDocumento = $request->idTipoDocumento;
            $idTipoDocumento = (int)$idTipoDocumento;
            $file = $_FILES['documento'];
            $Extencion = $file['type'];
            $Size = $file['size'];
            $descDocumento =  $file['name'];
            $Documento = base64_encode(file_get_contents($_FILES["documento"]["tmp_name"]));
            $idUsuarioReg = $this->idUsuario();

            $solicitarPrepedido = $request->solicitarPrepedido;

            DB::statement(DB::raw(" EXEC sp_updateOportDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idOportunidad,:idTipoDocumento"), [
                ':descDocumento' => $descDocumento,
                ':Documento' => $Documento,
                ':Extencion' => $Extencion,
                ':Size' => $Size,
                ':idUsuarioReg' => $idUsuarioReg,
                ':idOportunidad' => $idOportunidad,
                ':idTipoDocumento' => $idTipoDocumento
            ]);

            // Actualizamos la solicitud de prepedido si aplica tanto a la oportunidad y al documento
            // if ($solicitarPrepedido == 1) {
            //     DB::table('Oportunidades')
            //         ->where('idOportunidad', $idOportunidad)
            //         ->update([
            //             'solicita_prepedido' => 1
            //         ]);

            //     // Obtenemos el id del documento recien insertado
            //     $documento_insertado = DB::table('relOport_Documento')
            //                                 ->where('idOportunidad', $idOportunidad)
            //                                 ->where('idUsuarioRelaciona', $idUsuarioReg)
            //                                 ->orderByDesc('idDocumento')
            //                                 ->first();
                
            //     if ($documento_insertado) {
            //         DB::table('DocumentosOport')
            //         ->where('idDocumento', $documento_insertado->idDocumento)
            //         ->update([
            //             'solicito_prepedido' => 1,
            //             'genero_prepedido' => 1,
            //             'resolvio_prepedido' => 0,
            //         ]);
            //     }
            // }

            $tipo_documento = DB::table('catTipoDocumentoOport')
                                    ->where('idTipoDocumento', $idTipoDocumento)
                                    ->first();

            // // Registro en bitacora
            $this->bitacora($this->modulo, 'Agregar Documento ' . (string)$descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Oportunidad ', $request->idOportunidad, $this->loginUsuario(), 5);

            // // Enviamos el correo al customer para que sepa que se solicito el prepedido
            // if ($solicitarPrepedido == 1) {
            //     $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 26)->first();

            //     $oportunidad = DB::table('Oportunidades')->where('idOportunidad', $idOportunidad)->first();
                
            //     if ($alertasPlantilla != null) {
            //         $customerCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAtendido)->first();

            //         if ($customerCorreo != null) {
            //             $correoCustomer = $customerCorreo->Correo;
            //             $nombre_oportunidad = $oportunidad->nomOportunidad;
            //             $folio = $oportunidad->idOportunidad;
            //             $fecha_oportunidad = $oportunidad->fecCreacion;
            //             $estatus_oportunidad = $alertasPlantilla->ValorCampo;
            //             $subject = $alertasPlantilla->Asunto;
            //             $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
            //             $route = "oportunidades";
            //             $nota = $request->motivo;
            //             $fecha_solicitud = null;
            //             $fecha_asignacion = null;
            //             $fecha_entrega = null;
            //             $fecha_aut_archivo = null;
            //             $diasEntrega = null;
            //             $mensaje = $alertasPlantilla->Mensaje;
            //             $numOrdenMTC = $oportunidad->ops;
            //             $rejectDocument = 2;

            //             // Enviamos el correo al cotizador
            //             Mail::to($correoCustomer)
            //             ->cc([$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA])
            //             ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));

            //             // Pruebas
            //             // Mail::to('cgonzalez@litoprocess.com')->cc(['edmond@litoprocess.com', 'maflores@litoprocess.com'])->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
            //         }
            //     }

            //     // Actualizamos la oportunidad a ganada
            //     $oportunidad = Oportunidades::find($idOportunidad);

            //     $fecha = new \DateTime();

            //     if ($oportunidad->fecSolCotizacion == null) {
            //         $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecListoCotizar == null) {
            //         $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecCotizando == null) {
            //         $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecRevisionTec == null) {
            //         $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecRevision == null) {
            //         $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecEsperaVobo == null) {
            //         $oportunidad->fecEsperaVobo = null;
            //     }
            //     if ($oportunidad->fecListoPresupuesto == null) {
            //         $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecPresupTerminado == null) {
            //         $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecPrepedido == null) {
            //         $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
            //     }
            //     if ($oportunidad->fecSolPreprensa != null) {
            //         $oportunidad->fecSolPreprensa = null;
            //     }
            //     if ($oportunidad->fecSolPreprensaConcluida != null) {
            //         $oportunidad->fecSolPreprensaConcluida = null;
            //     }
            //     if ($oportunidad->fecResultadoF != null) {
            //         $oportunidad->fecResultadoF = null;
            //     }
            //     if ($oportunidad->fecOrdenConcluida != null) {
            //         $oportunidad->fecOrdenConcluida = null;
            //     }

            //     $oportunidad->save();
            // }

            return response()->json([
                'response' => true,
                'refresh' => $solicitarPrepedido == 1,
                'message' => 'Archivo subido correctamente a la oportunidad',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al subir el archivo a la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para marcar que se realizo el prepedido de un documento y actualizar la cantidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function actualizarEstatusOportPrepedido(Request $request)
    {
        try {
            $idOportunidad = $request->idOportunidad;
            $idDocumento = $request->idDocumento;
            $cantidad_preprensa = $request->cantidad_preprensa;
            $oportunidad = Oportunidades::find($idOportunidad);
            $idUsuario = $this->idUsuario();

            DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->update([
                    'solicita_prepedido' => 1
                ]);
            
            DB::table('DocumentosOport')
            ->where('idDocumento', $idDocumento)
            ->update([
                'solicito_prepedido' => 1,
                'genero_prepedido' => 1,
                'resolvio_prepedido' => 0,
                'estatus_doc_id' => 1,
                'cantidad_preprensa' => (int)$cantidad_preprensa
            ]);

            $refresh = false;

            // Registro en bitacora
            $this->bitacora($this->modulo, 'Se actualizó Tripas con cantidad prepedido: ' . $cantidad_preprensa, ' Oportunidad ', $request->idOportunidad, $this->loginUsuario(), 5);

            if ($oportunidad->solicita_prepedido == 0) {

                // Enviamos el correo al customer para que sepa que se solicito el prepedido
                $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 26)->first();
    
                $oportunidad = DB::table('Oportunidades')->where('idOportunidad', $idOportunidad)->first();
                
                if ($alertasPlantilla != null) {
                    $customerCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAtendido)->first();
    
                    if ($customerCorreo != null) {
                        $correoCustomer = $customerCorreo->Correo;
                        $nombre_oportunidad = $oportunidad->nomOportunidad;
                        $folio = $oportunidad->idOportunidad;
                        $fecha_oportunidad = $oportunidad->fecCreacion;
                        $estatus_oportunidad = $alertasPlantilla->ValorCampo;
                        $subject = $alertasPlantilla->Asunto;
                        $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                        $route = "oportunidades";
                        $nota = $request->motivo;
                        $fecha_solicitud = null;
                        $fecha_asignacion = null;
                        $fecha_entrega = null;
                        $fecha_aut_archivo = null;
                        $diasEntrega = null;
                        $mensaje = $alertasPlantilla->Mensaje;
                        $numOrdenMTC = $oportunidad->ops;
                        $rejectDocument = 2;
    
                        // Enviamos el correo al cotizador
                        Mail::to($correoCustomer)
                        ->cc([$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA])
                        ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
    
                        // Pruebas
                        // Mail::to('cgonzalez@litoprocess.com')->cc(['edmond@litoprocess.com', 'maflores@litoprocess.com'])->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
                    }
                }
    
                // Actualizamos la oportunidad a ganada
                $oportunidad = Oportunidades::find($idOportunidad);
    
                $fecha = new \DateTime();
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPrepedido == null) {
                    $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                }
                // if ($oportunidad->fecSolPreprensa != null) {
                //     $oportunidad->fecSolPreprensa = null;
                // }
                // if ($oportunidad->fecSolPreprensaConcluida != null) {
                //     $oportunidad->fecSolPreprensaConcluida = null;
                // }
                // if ($oportunidad->fecResultadoF != null) {
                //     $oportunidad->fecResultadoF = null;
                // }
                // if ($oportunidad->fecOrdenConcluida != null) {
                //     $oportunidad->fecOrdenConcluida = null;
                // }
                $oportunidad->genero_prepedido = 1;
                $oportunidad->estatus_doc_id = 2;
                $oportunidad->save();

                (new OportunidadesController())->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 8);

                $refresh = true;
            } else {
                $refresh = false;
            }

            return response()->json([
                'response' => true,
                'refresh' => $refresh,
                'message' => 'Estatus de la oportunidad actualizado a preprensa.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al actualizar el estatus de la oportunidad a preprensa.',
            ], 500);
        }
    }

    /**
     * Función para marcar como realizado un documento
     * 
     * @param Request $request
     * @return mixed
     */
    public function documentoRealizadoEstatus(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $data = [
                'resolvio_prepedido' => 1,
                'resolvio_preprensa' => 1,
                'resolvio_produccion' => 1,
                'genero_prepedido' => 0,
                'genero_preprensa' => 0,
                'genero_produccion' => 0,
            ];

            // Buscamos el documento y actualizamos el campo
            DB::table('DocumentosOport')
                    ->where('idDocumento', $idDocumento)
                    ->update($data);

            // Guardamos en bitacora
            $this->bitacora($this->modulo, 'Marcar como realizado un documento', ' Documento ', $idDocumento, $this->loginUsuario(), 5);

            return response()->json([
                'response' => true,
                'message' => 'Se marcó como realizado el documento',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al marcar como realizado el documento',
            ], 500);
        }
    }

    /**
     * Función para marcar como realizado una oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function oportunidadRealizadoEstatus(Request $request)
    {
        try {
            $idOportunidad = $request->idOportunidad;
            $estatus = $request->estatus;

            $estatusA = DB::table('cat_estatus_tripas')->where('id', $estatus)->first();

            $data = [
                'resolvio_prepedido' => 1,
                'resolvio_preprensa' => 1,
                'resolvio_produccion' => 1,
                'genero_prepedido' => 0,
                'genero_preprensa' => 0,
                'genero_produccion' => 0,
            ];

            // Buscamos el documento y actualizamos el campo
            DB::table('Oportunidades')
                    ->where('idOportunidad', $idOportunidad)
                    ->update($data);

            // Guardamos en bitacora
            $this->bitacora($this->modulo, 'Marcar como realizado una oportunidad', ' Oportunidad ', $idOportunidad, $this->loginUsuario(), 5);

            $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 30)->first();

            $oportunidad = DB::table('Oportunidades')->where('idOportunidad', $idOportunidad)->first();
            
            if ($alertasPlantilla != null) {
                $customerCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAtendido)->first();
                $vendedorCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->first();

                if ($customerCorreo != null && $vendedorCorreo != null) {
                    $correoCustomer = $customerCorreo->Correo;
                    $nombre_oportunidad = $oportunidad->nomOportunidad;
                    $folio = $oportunidad->idOportunidad;
                    $folioO = $oportunidad->folio;
                    $fecha_oportunidad = $oportunidad->fecCreacion;
                    $estatus_oportunidad = $estatusA->descripcion;
                    $subject = 'Oportunidad ' . $folioO . ' finalizada del estatus ' . $estatusA->descripcion;
                    $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                    $route = "oportunidades";
                    $nota = '';
                    $fecha_solicitud = null;
                    $fecha_asignacion = null;
                    $fecha_entrega = null;
                    $fecha_aut_archivo = null;
                    $diasEntrega = null;
                    $mensaje = 'Se ha marcado como realizado la oportunidad ' . $folioO . ' del estatus ' . $estatusA->descripcion;
                    $numOrdenMTC = $oportunidad->ops;
                    $rejectDocument = 2;

                    // relacion de auxiliares para enviar el correo
                    $auxiliares = DB::table('rel_Agente_Aux')->where('idAgente', $oportunidad->idAgente)->first();

                    $correosAuxiliares = [];

                    if($auxiliares != null){
                        $auxiliarCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $auxiliares->idAuxiliar)->first();
                        $auxiliarCorreo1 = DB::table('v_catUsuarios')->where('Id_Usuario', $auxiliares->idAuxiliar1)->first();
                        $auxiliarCorreo2 = DB::table('v_catUsuarios')->where('Id_Usuario', $auxiliares->idAuxiliar2)->first();
    
                        if($auxiliarCorreo != null){
                            $correosAuxiliares[] = $auxiliarCorreo->Correo;
                        }
                        if($auxiliarCorreo1 != null){
                            $correosAuxiliares[] = $auxiliarCorreo1->Correo;
                        }
                        if($auxiliarCorreo2 != null){
                            $correosAuxiliares[] = $auxiliarCorreo2->Correo;
                        }
                    }

                    $copias = [$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA, $correoCustomer];

                    $copias = array_merge($copias, $correosAuxiliares);

                    // Eliminamos los correos duplicados y nulos
                    $copias = array_filter(array_unique($copias));

                    // Enviamos el correo al vendedor y al customer
                    Mail::to($vendedorCorreo->Correo)
                        ->cc($copias)
                        ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
                }
            }

            return response()->json([
                'response' => true,
                'message' => 'Se marcó como realizado la oportunidad',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al marcar como realizado la oportunidad',
            ], 500);
        }
    }

    /**
     * Enviar tripa a trispas de preprensa 
     * 
     * @param Request $request
     * @return mixed
     */
    public function enviarTripaPreprensa(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $idOportunidad = $request->idOportunidad;
            // Actualizamos la oportunidad a ganada
            $oportunidad = Oportunidades::find($idOportunidad);
            $idUsuario = $this->idUsuario();

            // Buscamos el documento y actualizamos el campo
            DB::table('DocumentosOport')
                    ->where('idDocumento', $idDocumento)
                    ->update([
                        'tipoTripa' => 6, // Tipo tripa preprensa
                        'genero_preprensa' => 1,
                        'resolvio_preprensa' => 0,
                        'estatus_doc_id' => 2,
                    ]);

            DB::table('Oportunidades')
                    ->where('idOportunidad', $idOportunidad)
                    ->update([
                        'ops' => DB::table('v_OportunidadOps')->where('idOportunidad', $idOportunidad)->value('folios')
                    ]);

            $refresh = false;

            if ($oportunidad->solicitud_preprensa == null || $oportunidad->solicitud_preprensa == 0) {
                $fecha = new \DateTime();
    
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                // if ($oportunidad->fecPrepedido == null) {
                //     $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                // }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
    
                $oportunidad->solicitud_preprensa = 1;
                $oportunidad->idEstatus = 15; // En preprensa
                $oportunidad->genero_preprensa = 1;
                $oportunidad->estatus_doc_id = 1;
                $oportunidad->save();

                (new OportunidadesController())->saveOrUpdateBitacora($oportunidad, $fecha->format('d-m-Y H:i:s'), $idUsuario, 9);
    
                // Guardamos en bitacora
                $this->bitacora($this->modulo, 'Enviar tripa de preprensa', ' Documento ', $idDocumento, $this->loginUsuario(), 5);
    
                $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 27)->first();
    
                $oportunidad = DB::table('Oportunidades')->where('idOportunidad', $idOportunidad)->first();
                
                if ($alertasPlantilla != null) {
                    $customerCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAtendido)->first();
    
                    if ($customerCorreo != null) {
                        $correoCustomer = $customerCorreo->Correo;
                        $nombre_oportunidad = $oportunidad->nomOportunidad;
                        $folio = $oportunidad->idOportunidad;
                        $fecha_oportunidad = $oportunidad->fecCreacion;
                        $estatus_oportunidad = $alertasPlantilla->ValorCampo;
                        $subject = $alertasPlantilla->Asunto;
                        $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                        $route = "oportunidades";
                        $nota = $request->motivo;
                        $fecha_solicitud = null;
                        $fecha_asignacion = null;
                        $fecha_entrega = null;
                        $fecha_aut_archivo = null;
                        $diasEntrega = null;
                        $mensaje = $alertasPlantilla->Mensaje;
                        $numOrdenMTC = $oportunidad->ops;
                        $rejectDocument = 2;
    
                        // Enviamos el correo al cotizador
                        Mail::to($correoCustomer)
                        ->cc([$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA])
                        ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
    
                        // Pruebas
                        // Mail::to('cgonzalez@litoprocess.com')->cc(['edmond@litoprocess.com', 'maflores@litoprocess.com'])->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
                    }
                }

                $refresh = true;
            } else {
                $refresh = false;
            }

            // Documentos en prod
            $documentosIds = DB::table('relOport_Documento')->where('idOportunidad', $idOportunidad)->pluck('idDocumento')->toArray();
            $documentosProd = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 1)
                            ->count();
            
            if ($documentosProd >= 1) {
                $oportunidad->fecSolPreprensaConcluida = null;
                $oportunidad->fecResultadoF = null;
                $oportunidad->preprensa_concluida = 0;
                $oportunidad->idEstatus = 16; // En preprensa
                $oportunidad->save();

                $refresh = true;
            }

            return response()->json([
                'response' => true,
                'refresh' => $refresh,
                'message' => 'Se envió la tripa de prepedido.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al enviar la tripa de prepedido.',
            ], 500);
        }
    }

    /**
     * Función para asignar el folio de preprensa a un documento
     * 
     * @param Request $request
     * @return mixed
     */
    public function asignarFolioPreprensaLiberar(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $folio_preprensa = $request->folio_preprensa;
    
            DB::table('DocumentosOport')
                    ->where('idDocumento', $idDocumento)
                    ->update([
                        'folio_preprensa' => $folio_preprensa
                    ]);

            // Id de la oportunidad
            $idOportunidad = DB::table('relOport_Documento')
                                ->where('idDocumento', $idDocumento)
                                ->value('idOportunidad');

            // Asignamos el conjunto de ops a la oportunidad
            DB::table('Oportunidades')
                    ->where('idOportunidad', $idOportunidad)
                    ->update([
                        'ops' => DB::table('v_OportunidadOps')->where('idOportunidad', $idOportunidad)->value('folios')
                    ]);

            $this->bitacora($this->modulo, 'Se asignó folio de preprensa: ' . $folio_preprensa, ' Documento ', $idDocumento, $this->loginUsuario(), 5);

            $alertasPlantilla = catPlantillasAlertas::where('idPlantilla', 29)->first();

            $oportunidad = DB::table('Oportunidades')->where('idOportunidad', $idOportunidad)->first();
            
            if ($alertasPlantilla != null) {
                $customerCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAtendido)->first();
                $vendedorCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->first();
                $documento = DB::table('DocumentosOport')->select('descDocumento')->where('idDocumento', $idDocumento)->first();

                if ($customerCorreo != null && $vendedorCorreo != null) {
                    $correoCustomer = $customerCorreo->Correo;
                    $nombre_oportunidad = $oportunidad->nomOportunidad;
                    $folio = $oportunidad->idOportunidad;
                    $fecha_oportunidad = $oportunidad->fecCreacion;
                    $estatus_oportunidad = $alertasPlantilla->ValorCampo;
                    $subject = $alertasPlantilla->Asunto;
                    $nombre_agente = DB::table('v_catUsuarios')->where('Id_Usuario', $oportunidad->idAgente)->value('Nombre');
                    $route = "oportunidades";
                    $nota = '';
                    $fecha_solicitud = null;
                    $fecha_asignacion = null;
                    $fecha_entrega = null;
                    $fecha_aut_archivo = null;
                    $diasEntrega = null;
                    $mensaje = 'Se asignó el folio de preprensa: ' . $folio_preprensa . ' a tripa.';
                    $numOrdenMTC = $oportunidad->ops;
                    $rejectDocument = 2;

                    // relacion de auxiliares para enviar el correo
                    $auxiliares = DB::table('rel_Agente_Aux')->where('idAgente', $oportunidad->idAgente)->first();

                    $correosAuxiliares = [];

                    if($auxiliares != null){
                        $auxiliarCorreo = DB::table('v_catUsuarios')->where('Id_Usuario', $auxiliares->idAuxiliar)->first();
                        $auxiliarCorreo1 = DB::table('v_catUsuarios')->where('Id_Usuario', $auxiliares->idAuxiliar1)->first();
                        $auxiliarCorreo2 = DB::table('v_catUsuarios')->where('Id_Usuario', $auxiliares->idAuxiliar2)->first();
    
                        if($auxiliarCorreo != null){
                            $correosAuxiliares[] = $auxiliarCorreo->Correo;
                        }
                        if($auxiliarCorreo1 != null){
                            $correosAuxiliares[] = $auxiliarCorreo1->Correo;
                        }
                        if($auxiliarCorreo2 != null){
                            $correosAuxiliares[] = $auxiliarCorreo2->Correo;
                        }
                    }

                    $copias = [$alertasPlantilla->Destinatario, $alertasPlantilla->CopiarA, $correoCustomer];

                    $copias = array_merge($copias, $correosAuxiliares);

                    // Eliminamos los correos duplicados y nulos
                    $copias = array_filter(array_unique($copias));

                    // Enviamos el correo al cotizador
                    Mail::to($vendedorCorreo->Correo)
                        ->cc($copias)
                        ->send(new SendMail($alertasPlantilla, $nombre_oportunidad, $folio, $fecha_oportunidad, $estatus_oportunidad, $subject, $nombre_agente, $route, $nota, $fecha_solicitud, $fecha_asignacion, $fecha_entrega, $fecha_aut_archivo, $diasEntrega, $mensaje, $numOrdenMTC, $rejectDocument));
                }
            }

            return response()->json([
                'response' => true,
                'message' => 'Folio de preprensa asignado al documento',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al asignar el folio de preprensa al documento',
            ], 500);
        }
    }

    /**
     * Función para obtener el checklist de una oportunidad/documento, creándolo si aún no existe
     *
     * @param Request $request
     * @return mixed
     */
    public function obtenerChecklistOp(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $idOportunidad = $request->idOportunidad;
            $idUsuario = $this->idUsuario();

            $idCliente = DB::table('Oportunidades')->where('idOportunidad', $idOportunidad)->value('idCliente');
            $clienteINT = DB::table('v_catClientes')->where('idCliente', $idCliente)->value('ClienteINT');
            $clienteBloqueado = DB::table('v_CtesBloqueados')->where('Cliente', $clienteINT)->exists();

            if ($clienteBloqueado) {
                return response()->json([
                    'response' => true,
                    'bloqueado' => true,
                    'message' => 'El cliente de la oportunidad está bloqueado.',
                ], 200);
            }

            $checklistOp = DB::table('checkListOP')
                            ->where('idDocumento', $idDocumento)
                            ->where('idOportunidad', $idOportunidad)
                            ->orderByDesc('id')
                            ->first();

            // Solo se registra un checklist nuevo (con su bitácora) si no existe uno todavía
            // o si el último quedó en estatus 3 (invalidado)
            if (!$checklistOp || $checklistOp->estatus == 3) {
                $idChecklistOp = DB::table('checkListOP')->insertGetId([
                    'idUsuario' => $idUsuario,
                    'idDocumento' => $idDocumento,
                    'idOportunidad' => $idOportunidad,
                    'estatus' => 1,
                ]);

                DB::table('bitacoraCheckListOport')->insert([
                    'accion' => 'Se inició el checklist',
                    'idUsuario' => $idUsuario,
                    'idChecklistOP' => $idChecklistOp,
                ]);

                $checklistOp = DB::table('checkListOP')->where('id', $idChecklistOp)->first();
            }

            $preguntas = DB::table('preguntasChecklist')->where('estatus', 1)->orderBy('id')->get();

            foreach ($preguntas as $pregunta) {
                $pregunta->marcado = (bool) DB::table('checkListOport')
                                        ->where('idChecklistOP', $checklistOp->id)
                                        ->where('idPregunta', $pregunta->id)
                                        ->value('respuesta');
            }

            return response()->json([
                'response' => true,
                'bloqueado' => false,
                'idChecklistOP' => $checklistOp->id,
                'finalizado' => $checklistOp->estatus == 2,
                'preguntas' => $preguntas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener el checklist de la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para consultar (solo lectura) las respuestas ya registradas del checklist de un documento
     *
     * @param Request $request
     * @return mixed
     */
    public function verChecklistOp(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $idOportunidad = $request->idOportunidad;

            $checklistOp = DB::table('checkListOP')
                            ->where('idDocumento', $idDocumento)
                            ->where('idOportunidad', $idOportunidad)
                            ->orderByDesc('id')
                            ->first();

            if (!$checklistOp) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontró un checklist registrado para este documento.',
                ], 200);
            }

            $respuestas = DB::table('checkListOport')
                            ->join('preguntasChecklist', 'preguntasChecklist.id', '=', 'checkListOport.idPregunta')
                            ->where('checkListOport.idChecklistOP', $checklistOp->id)
                            ->orderBy('preguntasChecklist.id')
                            ->select(
                                'preguntasChecklist.pregunta',
                                'checkListOport.respuesta'
                            )
                            ->get();

            // Usuario y fecha con los que se finalizó el checklist
            $bitacoraFinalizado = DB::table('bitacoraCheckListOport')
                            ->join('v_catUsuarios', 'v_catUsuarios.Id_Usuario', '=', 'bitacoraCheckListOport.idUsuario')
                            ->where('bitacoraCheckListOport.idChecklistOP', $checklistOp->id)
                            ->where('bitacoraCheckListOport.accion', 'Se finalizó el checklist')
                            ->orderByDesc('bitacoraCheckListOport.id')
                            ->select('v_catUsuarios.Nombre', 'bitacoraCheckListOport.fechaRegistro')
                            ->first();

            return response()->json([
                'response' => true,
                'finalizado' => $checklistOp->estatus == 2,
                'usuarioFinalizo' => $bitacoraFinalizado->Nombre ?? null,
                'fechaFinalizo' => $bitacoraFinalizado->fechaRegistro ?? null,
                'respuestas' => $respuestas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al consultar el checklist de la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para guardar las respuestas del checklist y finalizarlo
     *
     * @param Request $request
     * @return mixed
     */
    public function guardarChecklistOp(Request $request)
    {
        try {
            $idChecklistOp = $request->idChecklistOP;
            $idUsuario = $this->idUsuario();
            $respuestas = json_decode($request->respuestas, true) ?: [];

            $checklistOp = DB::table('checkListOP')->where('id', $idChecklistOp)->first();

            if (!$checklistOp) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontró el checklist indicado.',
                ], 404);
            }

            if ($checklistOp->estatus == 2) {
                return response()->json([
                    'response' => false,
                    'message' => 'El checklist ya fue finalizado previamente.',
                ], 200);
            }

            $idsPreguntasActivas = DB::table('preguntasChecklist')->where('estatus', 1)->pluck('id')->toArray();
            $idsPreguntasMarcadas = array_column(array_filter($respuestas, function ($respuesta) {
                return !empty($respuesta['marcado']);
            }), 'idPregunta');

            if (count(array_diff($idsPreguntasActivas, $idsPreguntasMarcadas)) > 0) {
                return response()->json([
                    'response' => false,
                    'message' => 'Debes marcar todas las preguntas del checklist antes de continuar.',
                ], 200);
            }

            foreach ($respuestas as $respuesta) {
                DB::table('checkListOport')
                    ->where('idChecklistOP', $idChecklistOp)
                    ->where('idPregunta', $respuesta['idPregunta'])
                    ->delete();

                DB::table('checkListOport')->insert([
                    'idPregunta' => $respuesta['idPregunta'],
                    'idChecklistOP' => $idChecklistOp,
                    'respuesta' => !empty($respuesta['marcado']) ? 1 : 0,
                ]);
            }

            DB::table('checkListOP')->where('id', $idChecklistOp)->update([
                'estatus' => 2,
            ]);

            DB::table('bitacoraCheckListOport')->insert([
                'accion' => 'Se finalizó el checklist',
                'idUsuario' => $idUsuario,
                'idChecklistOP' => $idChecklistOp,
            ]);

            return response()->json([
                'response' => true,
                'message' => 'Checklist finalizado correctamente.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al guardar el checklist de la oportunidad',
            ], 500);
        }
    }

    /**
     * Función para rechazar una tripa de producción
     *
     * @param Request $request
     * @return mixed
     */
    public function rechazarTripaProduccion(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $idOportunidad = $request->idOportunidad;
            $refresh = false;
            $oportunidad = Oportunidades::find($idOportunidad);

            DB::table('DocumentosOport')
                    ->where('idDocumento', $idDocumento)
                    ->update([
                        'tipoTripa' => 6,
                        'folio_preprensa' => null,
                        'cantidad_preprensa' => null,
                    ]);

            DB::table('checkListOP')
                    ->where('idDocumento', $idDocumento)
                    ->where('idOportunidad', $idOportunidad)
                    ->update([
                        'estatus' => 3, // Rechazado
                    ]);

            $documentosIds = DB::table('relOport_Documento')->where('idOportunidad', $idOportunidad)->pluck('idDocumento')->toArray();

            // Validamos si tiene alguna otra tripa en producción
            $documento = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 1)
                            ->first();

            if ($documento == null) {
                $fecha = new \DateTime();
    
                if ($oportunidad->fecSolCotizacion == null) {
                    $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecListoCotizar == null) {
                    $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecCotizando == null) {
                    $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevisionTec == null) {
                    $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecRevision == null) {
                    $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecEsperaVobo == null) {
                    $oportunidad->fecEsperaVobo = null;
                }
                if ($oportunidad->fecListoPresupuesto == null) {
                    $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecPresupTerminado == null) {
                    $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                }
                // if ($oportunidad->fecPrepedido == null) {
                //     $oportunidad->fecPrepedido = $fecha->format('d-m-Y H:i:s');
                // }
                if ($oportunidad->fecSolPreprensa == null) {
                    $oportunidad->fecSolPreprensa = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecSolPreprensaConcluida != null) {
                    $oportunidad->fecSolPreprensaConcluida = null;
                }
                if ($oportunidad->fecResultadoF != null) {
                    $oportunidad->fecResultadoF = null;
                }
                if ($oportunidad->fecOrdenConcluida != null) {
                    $oportunidad->fecOrdenConcluida = null;
                }
    
                $oportunidad->solicitud_preprensa = 1;
                $oportunidad->preprensa_concluida = 0;
                $oportunidad->idEstatus = 15; // En preprensa
                $oportunidad->genero_preprensa = 1;
                $oportunidad->estatus_doc_id = 15;
                $oportunidad->save();

                $refresh = true;
            } else {
                $totalTripasPreprensa = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 6) // Tipo tripa 6 para preprensa
                            ->count();

                if ($totalTripasPreprensa >= 1) {
                    $oportunidad->idEstatus = 16; // Solicitud de preprensa
                    $oportunidad->fecSolPreprensaConcluida = null;
                    $oportunidad->fecResultadoF = null;
                    $oportunidad->preprensa_concluida = 0;
                    $oportunidad->solicitud_preprensa = 1;
                    $oportunidad->save();
                    $refresh = true;
                }
            }
            
            // Registro en bitacora
            $this->bitacora($this->modulo, 'Rechazo de tripa de producción', ' Documento ', $idDocumento, $this->loginUsuario(), 5);

            return response()->json([
                'refresh' => $refresh,
                'response' => true,
                'message' => 'Tripa de producción rechazada y regresada a preprensa',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al rechazar la tripa de producción',
            ], 500);
        }
    }

    /**
     * Función para rechazar una tripa de preprensa
     * 
     * @param Request $request
     * @return mixed
     */
    public function rechazarTripaPreprensa(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;
            $idOportunidad = $request->idOportunidad;
            $refresh = false;
            $oportunidad = Oportunidades::find($idOportunidad);

            if ($oportunidad->solicita_prepedido != 1) {
                DB::table('DocumentosOport')
                        ->where('idDocumento', $idDocumento)
                        ->update([
                            'tipoTripa' => 2,
                            'folio_preprensa' => null,
                            'cantidad_preprensa' => null
                        ]);
            } else {
                DB::table('DocumentosOport')
                        ->where('idDocumento', $idDocumento)
                        ->update([
                            'tipoTripa' => 2,
                            'folio_preprensa' => null,
                        ]);
            }


            DB::table('Oportunidades')
                    ->where('idOportunidad', $idOportunidad)
                    ->update([
                        'ops' => DB::table('v_OportunidadOps')->where('idOportunidad', $idOportunidad)->value('folios'),
                    ]);

            $documentosIds = DB::table('relOport_Documento')->where('idOportunidad', $idOportunidad)->pluck('idDocumento')->toArray();

            // Validamos si tiene alguna otra tripa en producción
            $documento = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 6)
                            ->first();
            
            $documentoEnPreprensa = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 6)
                            ->first();

            // Validamos que si hay un documento en producción no se regrese a preprensa
            $documento_produccion = DB::table('DocumentosOport')
                            ->whereIn('idDocumento', $documentosIds)
                            ->where('tipoTripa', 1)
                            ->first();

            if ($documentoEnPreprensa == null && $documento_produccion == null) {
                $oportunidad->solicita_prepedido = 0;
                $oportunidad->save();
            }

            $oportunidad = Oportunidades::find($idOportunidad);

            if ($documento_produccion != null || $oportunidad->solicita_prepedido == 1) {
                $documento = 1;
            }

            $fecha = new \DateTime();

            if ($documentoEnPreprensa == null && $documento_produccion != null) {
                if ($oportunidad->fecSolPreprensaConcluida == null) {
                    $oportunidad->fecSolPreprensaConcluida = $fecha->format('d-m-Y H:i:s');
                }
                if ($oportunidad->fecResultadoF == null) {
                    $oportunidad->fecResultadoF = $fecha->format('d-m-Y H:i:s');
                }
                $oportunidad->idEstatus = 11; // En Producción
                $oportunidad->genero_produccion = 1;
                $oportunidad->estatus_doc_id = 3;
                $oportunidad->preprensa_concluida = 1;
                $oportunidad->save();

                $refresh = true;
            } else {
                if ($documento == null) {
                    if ($oportunidad->fecSolCotizacion == null) {
                        $oportunidad->fecSolCotizacion = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecListoCotizar == null) {
                        $oportunidad->fecListoCotizar = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecCotizando == null) {
                        $oportunidad->fecCotizando = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecRevisionTec == null) {
                        $oportunidad->fecRevisionTec = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecRevision == null) {
                        $oportunidad->fecRevision = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecEsperaVobo == null) {
                        $oportunidad->fecEsperaVobo = null;
                    }
                    if ($oportunidad->fecListoPresupuesto == null) {
                        $oportunidad->fecListoPresupuesto = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecPresupTerminado == null) {
                        $oportunidad->fecPresupTerminado = $fecha->format('d-m-Y H:i:s');
                    }
                    if ($oportunidad->fecPrepedido != null) {
                        $oportunidad->fecPrepedido = null;
                    }
                    if ($oportunidad->fecSolPreprensa != null) {
                        $oportunidad->fecSolPreprensa = null;
                    }
                    if ($oportunidad->fecSolPreprensaConcluida != null) {
                        $oportunidad->fecSolPreprensaConcluida = null;
                    }
                    if ($oportunidad->fecResultadoF != null) {
                        $oportunidad->fecResultadoF = null;
                    }
                    if ($oportunidad->fecOrdenConcluida != null) {
                        $oportunidad->fecOrdenConcluida = null;
                    }
                    
                    $oportunidad->solicita_prepedido = null;
                    $oportunidad->solicitud_preprensa = 0;
                    $oportunidad->preprensa_concluida = 0;
                    $oportunidad->idEstatus = 8; // En preprensa
                    $oportunidad->genero_preprensa = 0;
                    $oportunidad->genero_prepedido = 0;
                    $oportunidad->genero_produccion = 0;
                    $oportunidad->save();
    
                    $refresh = true;
                }
            }

            
            // Registro en bitacora
            $this->bitacora($this->modulo, 'Rechazo de tripa de producción', ' Documento ', $idDocumento, $this->loginUsuario(), 5);

            return response()->json([
                'refresh' => $refresh,
                'response' => true,
                'message' => 'Tripa de producción rechazada y regresada a preprensa',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al rechazar la tripa de producción',
            ], 500);
        }
    }

    /**
     * Funcion para obtener la nota de producción de un documento
     * 
     * @param Request $request
     * @return mixed
     */
    public function obtenerNotaProduccion(Request $request)
    {
        try {
            $idDocumento = $request->idDocumento;

            $notaProduccion = DB::table('notas_produccion_tripas')
                                ->where('documento_id', $idDocumento)
                                ->orderBy('fecha_registro', 'desc')
                                ->value('nota');

            return response()->json([
                'response' => true,
                'nota_produccion' => $notaProduccion,
                'message' => 'Nota de producción obtenida correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al obtener la nota de producción',
            ], 500);
        }
    }
}