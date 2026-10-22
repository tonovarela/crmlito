<?php

namespace App\Http\Controllers;

use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ExcelEPController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;
    
    /**
     * Función para obtener la lista de Exceles de EPs asociados a una oportunidad específica.
     * 
     * @param int $idOportunidad El ID de la oportunidad para la cual se desean obtener los Exceles de EPs.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que contiene la lista de Exceles de EPs asociados a la oportunidad.
     */
    public function obtenerListaExcelesEPs($idOportunidad)
    {
        try {
            $list = DB::table('v_EEP')
                ->where('idOportunidad', $idOportunidad)
                ->get();

            return DataTables::of($list)->make(true);
        } catch (\Exception $e) {
            // Manejo de errores: Devuelve una respuesta JSON con el mensaje de error y un código de estado 500
            return response()->json(['error' => 'Error al obtener la lista de Exceles de EPs'], 500);
        }
    }

    /**
     * Función para guardar las imagenes de los Exceles de EPs asociados a una oportunidad específica.
     * 
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene los datos de las imágenes y el ID de la oportunidad.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que indica el éxito o fracaso de
     */
    public function guardarExcelesEP(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');
            $imagenes = $request->file('files');
            $idUsuario = $this->idUsuario(); // Obtener el ID del usuario desde el trait PermisosTrait

            DB::beginTransaction();

            // Desactivar grupos anteriores de la misma oportunidad
            DB::table('grupos_eep')
                ->where('idOportunidad', $idOportunidad)
                ->update(['estatus' => 0]);

            // Crear nuevo grupo como activo
            $grupoId = DB::table('grupos_eep')->insertGetId([
                'folio' => DB::table('grupos_eep')->max('folio') + 1,
                'idUsuario' => $idUsuario,
                'idOportunidad' => $idOportunidad,
                'estatus' => 1,
            ]);

            foreach ($imagenes as $imagen) {
                // Convertir la imagen a base64
                $imagenBase64 = base64_encode(file_get_contents($imagen->getRealPath()));
                // Guardar la imagen en la base de datos
                DB::table('elementos_excel')->insert([
                    'folio' => DB::table('elementos_excel')->max('folio') + 1,
                    'archivo' => $imagenBase64,
                    'nombreArchivo' => $imagen->getClientOriginalName(),
                    'peso' => $imagen->getSize(),
                    'idGrupoEEP' => $grupoId,
                    'extension' => $imagen->getClientOriginalExtension(),
                ]);
            }

            DB::commit();

            $this->bitacora('Excel EP', 'Agregar Documentos', ' Documentos agregados ', $grupoId, $idUsuario, 5);

            return response()->json([
                'response' => true,
                'message' => 'Archivos de EP guardados exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            // Manejo de errores: Devuelve una respuesta JSON con el mensaje de error y un código de estado 500
            return response()->json(['error' => 'Error al guardar los Exceles de EPs'], 500);
        }
    }

    /**
     * Obtiene el listado de archivos Excel EP desde la vista v_ExcelEP.
     *
     * @param int $idOportunidad
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerListaExcelesEPVista($idOportunidad)
    {
        try {
            $list = DB::table('v_ExcelEP')
                ->select('id', 'nombreArchivo', 'extension', 'peso', 'estatus', 'estatusNombre', 'solicitoCambios', 'finalizoCambios', 'fechaRegistro', 'idOportunidad', 'folio', 'idUsuario', 'Nombre') 
                ->where('idOportunidad', $idOportunidad)
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($item) {
                    $peso = (int) ($item->peso ?? 0);
                    $item->pesoFormateado = $peso < 1024
                        ? $peso . ' B'
                        : number_format($peso / 1024, 2) . ' KB';

                    return $item;
                });

            return DataTables::of($list)->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener la lista de archivos Excel EP.'], 500);
        }
    }

    /**
     * Guarda archivos Excel EP en base64 dentro de la tabla excelesEp.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function guardarExcelEPVista(Request $request)
    {
        try {
            $idOportunidad = $request->input('idOportunidad');
            $archivos = $request->file('files', []);
            $idUsuario = $this->idUsuario();

            if (!is_array($archivos)) {
                $archivos = $archivos ? [$archivos] : [];
            }

            if (empty($archivos)) {
                return response()->json([
                    'response' => false,
                    'message' => 'Debes seleccionar un archivo Excel.'
                ], 422);
            }

            if (count($archivos) !== 1) {
                return response()->json([
                    'response' => false,
                    'message' => 'Solo se permite cargar un archivo Excel por vez.'
                ], 422);
            }

            $extensionesPermitidas = ['xls', 'xlsx', 'xlsm', 'xlsb'];

            DB::beginTransaction();

            // Marcar como inactivos los registros activos previos de la misma oportunidad
            DB::table('excelesEp')
                ->where('idOportunidad', $idOportunidad)
                ->where('estatus', 1)
                ->update(['estatus' => 0]);

            $archivo = $archivos[0];
            $extension = strtolower($archivo->getClientOriginalExtension());

            if (!in_array($extension, $extensionesPermitidas, true)) {
                DB::rollBack();

                return response()->json([
                    'response' => false,
                    'message' => 'Solo se permiten formatos de Excel (.xls, .xlsx, .xlsm, .xlsb).'
                ], 422);
            }

            $archivoBase64 = base64_encode(file_get_contents($archivo->getRealPath()));

            DB::table('excelesEp')->insert([
                'nombreArchivo' => $archivo->getClientOriginalName(),
                'extension' => $extension,
                'archivo' => $archivoBase64,
                'estatus' => 1,
                'peso' => $archivo->getSize(),
                'solicitoCambios' => 1,
                'finalizoCambios' => 0,
                'idOportunidad' => $idOportunidad,
                'idUsuario' => $idUsuario,
                'folio' => DB::table('excelesEp')->max('folio') + 1,
            ]);

            /* Seccion de solicitud de cambios */
            DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->update([
                    'solicitoCambios' => 1,
                    'finalizoCambios' => 0,
                    'flagCambioEspecificaciones' => 1,
                    'preSolicitoCambios' => 0,
                    'fueRechazadoPres' => 1
                ]);

            (new OportunidadesController())->cambiarAListoParaCotizar($idOportunidad);

            /* Fin Seccion de solicitud de cambios */

            DB::commit();

            $this->bitacora('Excel EP', 'Agregar Documentos Vista', ' Documentos de Excel EP guardados en excelesEp ', null, $idUsuario, 5);

            return response()->json([
                'response' => true,
                'refresh' => true,
                'message' => 'Archivos Excel EP guardados exitosamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'response' => false,
                'message' => 'Error al guardar los archivos Excel EP.'
            ], 500);
        }
    }

    /**
     * Descarga un archivo individual de Excel EP (base64) desde v_ExcelEP.
     *
     * @param int $idArchivo
     * @return \Illuminate\Http\Response
     */
    public function descargarExcelEPVista($idArchivo)
    {
        try {
            $archivo = DB::table('v_ExcelEP')
                ->where('id', $idArchivo)
                ->first();

            if (!$archivo) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontro el archivo solicitado.'
                ], 404);
            }

            $contenido = base64_decode($archivo->archivo, true);

            if ($contenido === false) {
                return response()->json([
                    'response' => false,
                    'message' => 'El contenido del archivo no es valido.'
                ], 422);
            }

            $extension = strtolower(trim($archivo->extension ?? ''));
            $nombreArchivo = trim($archivo->nombreArchivo ?? 'archivo_excel_ep');

            if ($extension !== '' && !preg_match('/\.' . preg_quote($extension, '/') . '$/i', $nombreArchivo)) {
                $nombreArchivo .= '.' . $extension;
            }

            $nombreArchivo = preg_replace('/[^A-Za-z0-9_\-. ]/', '_', $nombreArchivo);

            $mimeMap = [
                'xls' => 'application/vnd.ms-excel',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
                'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
            ];

            $mime = $mimeMap[$extension] ?? 'application/octet-stream';

            return response($contenido, 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
                'Content-Length' => strlen($contenido),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al descargar el archivo Excel EP.'
            ], 500);
        }
    }

    /**
     * Funcion para obtener los archivos de Excel de EPs asociados a un grupo específico.
     * 
     * @param int $idGrupoEP El ID del grupo de Exceles de EPs para el cual se desean obtener los archivos.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que contiene la lista de archivos de Excel de EPs asociados al grupo.
     */
    public function obtenerExcelesEPsPorGrupo($idGrupoEP)
    {
        try {
            $grupo = DB::table('grupos_eep')
                ->select('id', 'estatus')
                ->where('id', $idGrupoEP)
                ->first();

            if (!$grupo) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontro el grupo solicitado.'
                ], 404);
            }

            $exceles = DB::table('elementos_excel')
                ->where('idGrupoEEP', $idGrupoEP)
                ->get();

            return response()->json([
                'response' => true,
                'estatusGrupo' => (int) $grupo->estatus,
                'data' => $exceles
            ]);
        } catch (\Exception $e) {
            // Manejo de errores: Devuelve una respuesta JSON con el mensaje de error y un código de estado 500
            return response()->json(['error' => 'Error al obtener los Exceles de EPs por grupo'], 500);
        }
    }

    /**
     * Funcion para descargar todos los archivos de un grupo EEP en formato ZIP.
     *
     * @param int $idGrupoEP
     * @return \Illuminate\Http\Response
     */
    public function descargarExcelesEPsPorGrupoZip($idGrupoEP)
    {
        try {
            if (!class_exists(\ZipArchive::class)) {
                return response()->json([
                    'response' => false,
                    'message' => 'La extension ZipArchive no esta habilitada en el servidor.'
                ], 500);
            }

            $grupo = DB::table('grupos_eep')
                ->select('id', 'estatus')
                ->where('id', $idGrupoEP)
                ->first();

            if (!$grupo) {
                return response()->json([
                    'response' => false,
                    'message' => 'No se encontro el grupo solicitado.'
                ], 404);
            }

            if ((int) $grupo->estatus !== 1) {
                return response()->json([
                    'response' => false,
                    'message' => 'El grupo se encuentra inactivo y no permite descargas.'
                ], 403);
            }

            $archivos = DB::table('elementos_excel')
                ->where('idGrupoEEP', $idGrupoEP)
                ->orderBy('folio', 'asc')
                ->get();

            if ($archivos->isEmpty()) {
                return response()->json([
                    'response' => false,
                    'message' => 'No hay archivos para este grupo.'
                ], 404);
            }

            $zipFilename = 'grupo_eep_' . $idGrupoEP . '_' . date('Ymd_His') . '.zip';

            $candidateDirectories = [];

            // En Windows, ZipArchive puede fallar al crear archivos en rutas con caracteres Unicode.
            // Por eso priorizamos rutas ASCII conocidas.
            if (DIRECTORY_SEPARATOR === '\\') {
                $candidateDirectories[] = 'C:\\Windows\\Temp\\candy_temp_zip';
                $candidateDirectories[] = 'C:\\Temp\\candy_temp_zip';

                $envTemp = getenv('TEMP');
                $envTmp = getenv('TMP');

                if (!empty($envTemp)) {
                    $candidateDirectories[] = rtrim($envTemp, '\\/') . DIRECTORY_SEPARATOR . 'candy_temp_zip';
                }

                if (!empty($envTmp)) {
                    $candidateDirectories[] = rtrim($envTmp, '\\/') . DIRECTORY_SEPARATOR . 'candy_temp_zip';
                }
            }

            $candidateDirectories[] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'candy_temp_zip';
            $candidateDirectories[] = storage_path('app/temp');

            // Quitar duplicados conservando orden
            $candidateDirectories = array_values(array_unique($candidateDirectories));

            $zip = null;
            $zipPath = null;
            $zipStatus = null;

            foreach ($candidateDirectories as $candidateDirectory) {
                if (!is_dir($candidateDirectory)) {
                    @mkdir($candidateDirectory, 0755, true);
                }

                if (!is_writable($candidateDirectory)) {
                    continue;
                }

                $tmpFile = tempnam($candidateDirectory, 'eep_zip_');

                if ($tmpFile === false) {
                    continue;
                }

                $candidateZipPath = $tmpFile . '.zip';
                @unlink($tmpFile);

                $zipTry = new \ZipArchive();
                $statusTry = $zipTry->open($candidateZipPath, \ZipArchive::CREATE);

                if ($statusTry === true) {
                    $zip = $zipTry;
                    $zipPath = $candidateZipPath;
                    $zipStatus = true;
                    break;
                }

                $zipStatus = $statusTry;
                @unlink($candidateZipPath);
            }

            if ($zipStatus !== true || $zip === null || $zipPath === null) {
                $zipErrors = [
                    \ZipArchive::ER_EXISTS => 'ER_EXISTS',
                    \ZipArchive::ER_INCONS => 'ER_INCONS',
                    \ZipArchive::ER_INVAL => 'ER_INVAL',
                    \ZipArchive::ER_MEMORY => 'ER_MEMORY',
                    \ZipArchive::ER_NOENT => 'ER_NOENT',
                    \ZipArchive::ER_NOZIP => 'ER_NOZIP',
                    \ZipArchive::ER_OPEN => 'ER_OPEN',
                    \ZipArchive::ER_READ => 'ER_READ',
                    \ZipArchive::ER_SEEK => 'ER_SEEK',
                ];

                $zipStatusLabel = $zipErrors[$zipStatus] ?? 'DESCONOCIDO';

                return response()->json([
                    'response' => false,
                    'message' => 'No se pudo crear el archivo ZIP. Codigo ZipArchive: ' . $zipStatus . ' (' . $zipStatusLabel . ')'
                ], 500);
            }

            $nombresRegistrados = [];

            $archivosAgregados = 0;

            foreach ($archivos as $index => $archivo) {
                $contenido = base64_decode($archivo->archivo, true);

                if ($contenido === false) {
                    continue;
                }

                $extension = strtolower(trim($archivo->extension ?? ''));
                $nombreArchivo = trim($archivo->nombreArchivo ?? '');

                if ($nombreArchivo === '') {
                    $nombreArchivo = 'archivo_' . ($index + 1);
                }

                if ($extension !== '' && !preg_match('/\.' . preg_quote($extension, '/') . '$/i', $nombreArchivo)) {
                    $nombreArchivo .= '.' . $extension;
                }

                $nombreArchivo = preg_replace('/[^A-Za-z0-9_\-. ]/', '_', $nombreArchivo);

                $nombreFinal = $nombreArchivo;
                $contador = 1;

                while (isset($nombresRegistrados[$nombreFinal])) {
                    $pathInfo = pathinfo($nombreArchivo);
                    $base = $pathInfo['filename'] ?? ('archivo_' . ($index + 1));
                    $ext = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';
                    $nombreFinal = $base . '_' . $contador . $ext;
                    $contador++;
                }

                $nombresRegistrados[$nombreFinal] = true;
                $zip->addFromString($nombreFinal, $contenido);
                $archivosAgregados++;
            }

            $zip->close();

            if ($archivosAgregados === 0) {
                @unlink($zipPath);

                return response()->json([
                    'response' => false,
                    'message' => 'No fue posible agregar archivos validos al ZIP.'
                ], 422);
            }

            return response()->download($zipPath, $zipFilename, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json([
                'response' => false,
                'message' => 'Error al generar el ZIP de archivos del grupo.'
            ], 500);
        }
    }

    /**
     * Funcion para solicitar cambios y cambiar banderas en la oportunidad relacionada al grupo EEP.
     * 
     * @param int $idOportunidad El ID de la oportunidad para la cual se desean solicitar cambios.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que indica el éxito o fracaso de la solicitud de cambios.
     */
    public function solicitarCambiosExcelEPs($idOportunidad)
    {
        try {
            $idUsuario = $this->idUsuario(); // Obtener el ID del usuario desde el trait PermisosTrait

            DB::table('Oportunidades')
                ->where('idOportunidad', $idOportunidad)
                ->update([
                    'solicitoCambios' => 1,
                    'finalizoCambios' => 0,
                    'flagCambioEspecificaciones' => 1,
                    'preSolicitoCambios' => 0,
                ]);
        
            // obtenemos el ultimo grupo activo de la oportunidad para marcarlo como inactivo
            $grupoActivo = DB::table('grupos_eep')
                ->where('idOportunidad', $idOportunidad)
                ->where('estatus',1)
                ->orderBy('id', 'desc')
                ->first();

            DB::table('grupos_eep')
                ->where('id', $grupoActivo->id)
                ->update(['solicitoCambio' => 1, 'finalizoCambio' => 0]);

            (new OportunidadesController())->cambiarAListoParaCotizar($idOportunidad);

            $this->bitacora('Excel EP', 'Solicitud de Cambios', ' Se solicitan cambios en los documentos de EP ', null, $idUsuario, 5);

            return response()->json([
                'response' => true,
                'refresh' => true,
                'message' => 'Solicitud de cambios enviada exitosamente.'
            ]);
        } catch (\Exception $e) {
            // Manejo de errores: Devuelve una respuesta JSON con el mensaje de error y un código de estado 500
            return response()->json(['error' => 'Error al solicitar cambios en los Exceles de EPs'], 500);
        }
    }

    /**
     * Función para cambiar el estatus del excel de EPs como cambio ya finalizado desde la vista de cambios de Excel
     * 
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el ID del grupo EEP para el cual se desea cambiar el estatus.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que indica el éxito o fracaso de la operación.
     */
    public function cambiarEstatusExcelEP(Request $request)
    {
        try {
            $idExcel = $request->input('idExcelEp');
            $idUsuario = $this->idUsuario(); // Obtener el ID del usuario desde el trait PermisosTrait

            $excel = DB::table('excelesEp')
                ->where('id', $idExcel)
                ->first();

            DB::table('Oportunidades')
                ->where('idOportunidad', $excel->idOportunidad)
                ->update([
                    'solicitoCambios' => 0,
                    'finalizoCambios' => 1,
                    'flagCambioEspecificaciones' => 0,
                    'preSolicitoCambios' => 0,
                ]);

            DB::table('excelesEp')
                ->where('id', $idExcel)
                ->update(['solicitoCambios' => 0, 'finalizoCambios' => 1]);

            $this->bitacora('Excel EP', 'Finalizar Cambios', ' Se finalizan los cambios en el Excel EP ', null, $idUsuario, 5);

            return response()->json([
                'response' => true,
                'refresh' => true,
                'message' => 'Estatus de cambios actualizado exitosamente.'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Manejo de errores: Devuelve una respuesta JSON con el mensaje de error y un código de estado 500
            return response()->json(['error' => 'Error al cambiar el estatus del Excel EP'], 500);
        }
    }

    /**
     * Función para cambiar el estatus de los grupos EEP como cambio ya finalizado
     * 
     * @param \Illuminate\Http\Request $request La solicitud HTTP que contiene el ID del grupo EEP para el cual se desea cambiar el estatus.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON que indica el éxito o fracaso de la operación.
     */
    public function cambiarEstatusEEP(Request $request)
    {
        try {
            $idGrupoEP = $request->input('idGrupoEP');
            $idUsuario = $this->idUsuario(); // Obtener el ID del usuario desde el trait PermisosTrait

            $grupo = DB::table('grupos_eep')
                ->where('id', $idGrupoEP)
                ->first();

            DB::table('Oportunidades')
                ->where('idOportunidad', $grupo->idOportunidad)
                ->update([
                    'solicitoCambios' => 0,
                    'finalizoCambios' => 1,
                    'flagCambioEspecificaciones' => 0,
                    'preSolicitoCambios' => 0,
                ]);

            DB::table('grupos_eep')
                ->where('id', $idGrupoEP)
                ->update(['solicitoCambio' => 0, 'finalizoCambio' => 1]);

            $this->bitacora('Excel EP', 'Finalizar Cambios', ' Se finalizan los cambios en los documentos de EP ', null, $idUsuario, 5);

            return response()->json([
                'response' => true,
                'refresh' => true,
                'message' => 'Estatus de cambios actualizado exitosamente.'
            ]);
        } catch (\Exception $e) {
            // Manejo de errores: Devuelve una respuesta JSON con el mensaje de error y un código de estado 500
            return response()->json(['error' => 'Error al cambiar el estatus del Excel EP'], 500);
        }
    }
}
