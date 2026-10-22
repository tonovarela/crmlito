<?php

namespace App\Http\Controllers;

use App\Http\Modelos\catMotivosIncidencias;
use App\Http\Modelos\comisionesLito;
use App\Http\Modelos\DocumentosOport;
use App\Http\Modelos\Incidencia;
use App\Http\Modelos\incidenciasMontos;
use App\Http\Modelos\IncidenciaVendedores;
use App\Http\Vistas\v_APedidos;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Modelos\Oportunidades;
use Yajra\DataTables\Facades\DataTables;

class AdminPedidosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;
    public $modulo = 'Admin_Pedidos';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // obtenemos los permisos
            $this->permisos();

            // Obtenemos el login del usuario
            $Login = $this->loginUsuario();

            // Si  no existe el login lo regresamos a la vista de inicio
            if ($Login == null) {
                return view('iniciar');
            }

            // fecha actual
            $fecha = new \DateTime();
            $dia = $fecha->format('D');

            // obtenemos lo esencial del usuario
            $id_usuario = $this->idUsuario();

            // Obtenemos el rol de los permisos
            $idrol = $this->idRol();

            // Obtenemos si puede acceder al modulo
            $canAccessModulo = $this->canAccessModuloComisiones();

            // Obtenemos el tipo de usuario que va acceder
            $tipoUsarioAccede = $this->tipoUsuarioModuloComisiones();

            // Obtenemos el listados de estatus de los pedidos
            $estatusPedidos = DB::table('Lito.dbo.PedidosLito')->select(['EstatusLito'])->groupBy('EstatusLito')->get();

            // Obtenemos el listado de los vendedores que cuentan con pedidos registrados
            $listVendedores = DB::table('v_APedidos')->select(['NombreCteAgente'])->orderBy('NombreCteAgente', 'asc')->groupBy('NombreCteAgente')->get();

            // Obtenemos el listado del catalogo de estatus de los pedidos
            $estatusComisionesCandy = DB::table('catEstatusCom')
                                ->where('activo', 1)
                                ->get();

            /*               Listado para las incidencias                */
            // Obtenemos el listado de los vendedores de las incidencias registradas
            $listVendedoresIncidencias = DB::table('v_listAgentesComisiones')->select(['NombreAgente', DB::raw('TRIM(Agente) as AgentePedido'), 'Id_Usuario'])->orderBy('NombreAgente', 'asc')->groupBy('NombreAgente', 'Agente', 'Id_Usuario')->get();

            // Motivos de las incidencias
            $motivosIncidencias = DB::table('catMotivosIncidencias')
                ->select(['incidencia'])->get();

            // Obtenemos las quincenas
            $quincenas = DB::table('ETL_MSTR.dbo.etl_CatSemanas')
                ->select(['NumQuincena', 'QUINCENA', 'Año'])
                ->where('Año', '>=', 2024)
                ->whereNotNull('NumQuincena')
                ->groupBy('NumQuincena', 'QUINCENA', 'Año')
                ->orderBy('Año', 'asc')
                ->get();

            // Obtenemos las quincenas del 2025 en adelante
            $quincenasMajor2025 = DB::table('ETL_MSTR.dbo.etl_CatSemanas')
                ->select(['NumQuincena', 'QUINCENA', 'Año'])
                ->where('Año', '>=', 2025)
                // ->whereRaw('FECHA > GETDATE()')
                ->whereNotNull('NumQuincena')
                ->groupBy('NumQuincena', 'QUINCENA', 'Año')
                ->orderBy('Año', 'asc')
                ->get();

            // validamos de acuerdo a los permisos que se requieren para poder acceder al módulo
            if ($canAccessModulo == 1) {
                return view('pedidos.main-menu', compact('fecha', 'idrol', 'tipoUsarioAccede', 'quincenasMajor2025', 'estatusPedidos', 'listVendedores', 'quincenas', 'estatusComisionesCandy', 'listVendedoresIncidencias', 'motivosIncidencias'));
            } else {
                return redirect()->route('Dashboard');
            }
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Función para obtener el porcentaje de la comisión
     *
     * @return mixed
     */
    public function getPorcentajeComision($fecha)
    {
        try {
            // Obtenemos el año de la fecha de pedido
            $year = Carbon::parse($fecha)->year;

            // Validamos si el año es menor igual a 2024 el porcentahje de retencion sera del 7.5% en caso contrario sera del 30%
            $porcentaje = $year <= 2024 ? 0.075 : 0.30;

            return $porcentaje;
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Función para obtener el texto del porcentaje de la comisión
     *
     * @return mixed
     */
    public function getPorcentajeDescComision($fecha)
    {
        try {
            // Obtenemos el año de la fecha de pedido
            $year = Carbon::parse($fecha)->year;

            // Validamos si el año es menor igual a 2024 el porcentahje de retencion sera del 7.5% en caso contrario sera del 30%
            $porcentaje = $year <= 2024 ? 7.5 : 30;

            return $porcentaje;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Función para búscar en un array un dato en especifico
     * 
     * @param $key
     * @param $attributte
     * @param $data
     * @return boolean
     */
    public function searchValue($key, $attributte, $data)
    {
        return collect($data)->contains($attributte, $key);
    }

    /**
     * Función para obtener la información de la tabla principal
     * 
     * @param $isLiberado
     * @return mixed
     */
    public function getPedidos(Request $request)
    {
        try {
            // Listado de pedidos
            $pedidos = DB::select('exec getListPedidos ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $this->getFilters($request));

            // Rol del usuario loggueado
            $roleId = $this->idRol();

            // Id del usuario loggueado
            $userId = $this->idUsuario();

            // Obtenemos el tipo de usuario que va acceder
            $tipoUsarioAccede = $this->tipoUsuarioModuloComisiones();

            // Suponiendo que $pedidos es un array
            $pedidosCollection = collect($pedidos);

            // Total de anticipos
            $totalAnticipos = $pedidosCollection->sum('monto');

            // Lita de usuarios que solo tendran EL 0% de retencion solo 2024
            $usuariosSinRetencion = ['R.Z.G', 'M.E.G.', 'C.J.S.'];

            // Lista de usuarios que no tendran retencion sin importar el año
            $usuariosSinRetencionAll = ['J.M.G.'];

            // Total de retenciones de 7.5%
            $totalRetenciones = $pedidosCollection->sum('TOTALRET');

            // Total de saldo
            $totalSaldo = $pedidosCollection->sum('SaldoFinal');

            // Total de Comision Importe
            $totalComisionImporte = $pedidosCollection->sum('FinalImporteComision');

            // Total de Importe Pedido
            $totalImportePedido = $pedidosCollection->sum('ImportePedido');
            
            // Total de Importe Pedido
            $totalImportePerdido = $pedidosCollection->sum('ImporteVentaPerdida');

            // Total de la nota de  credito
            $totalNotaCredito = $pedidosCollection->sum('TNotaCredito');

            // Total del importe de la comision final
            $totalComisionImporteFinal = $pedidosCollection->sum('FinalImportePedido');

            // Regresamos la colleccion con los datos obtenidos desde la consulta
            return DataTables::of($pedidos)
                # Col. Candy
                ->addColumn('Anticipo', function ($row) use ($userId, $tipoUsarioAccede) {
                    // Validamos en que tablas se mostraran las posibilidades de realizar acciones y los usuarios que solo podran acceder
                    if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 2 || $tipoUsarioAccede == 5 || $tipoUsarioAccede == 4) {
                        // Validamos que valor le pasaremos como parametro a la función
                        $comRelCandy = $row->IdComisionLito == null ? 'null' : json_encode($row->IdComisionLito);

                        // bandera para saber si tiene anticipos no pagados
                        $anticipoFlag = $row->AnticiposNoPagados != 0 ? "1" : "0";

                        // Importe del pedido para realizar la operación
                        $importePedido = ($row->TotalCobradoFacturas * $row->PorcentajeComisionFinal) / 100;

                        // Obtenemos el valor final de la comision por la cual se validara ya restando la retencion
                        $retencion = $importePedido * $row->NUMRET;

                        // Obtenemos el saldo final
                        $saldoFinal = $importePedido - $retencion;

                        $totalQuePuedeCargar = $saldoFinal - $row->TotalAnticipos;

                        $puedeAgregarAnticipos = ($row->estatusCom != 6) ? "1" : '0';

                        // Función que realizara al dar click
                        $cadenaOnclick = "onclick='addNewAnticipo(" . json_encode($saldoFinal, 2) . ", " . json_encode($row->IDPedido, 2) . ", " . json_encode($row->OP, 2) . ", " . json_encode($row->MovIDPedido, 2) . ", " .json_encode($row->SaldoFinal, 2). ", " . $comRelCandy . ", " . $anticipoFlag .", ".json_encode($importePedido, 2).", ".json_encode($retencion, 2).", ".json_encode($row->DESCRET, 2).", ".json_encode($totalQuePuedeCargar, 2).", ".$puedeAgregarAnticipos.")' name='anticipo" . $row->IDPedido . "'";

                        // Falta * validar si existe sumatoria colocarla y en caso contrario solo mantener la accion para agregar
                        if ($row->IdComisionLito != null) {
                            // Desarrollo de toda la logica de la generación de anticipos (ejemplo de sumatoria)
                            return '<div class="cursor-pointer" style="color: #0082ce;" ' . $cadenaOnclick . '>' . ($row->monto != null ? '$ ' . number_format($row->monto) : '$ 0') . '</div>';
                        } else {
                            return '<div class="cursor-pointer" style="color: #0082ce;" ' . $cadenaOnclick . '>$ 0</div>';
                        }
                    } else {
                        return '<div>' . ($row->monto != null ? '$ ' . number_format($row->monto) : '$ 0') . '</div>';
                    }
                })
                # Col. Candy
                ->addColumn('AnticipoFooter', function ($row) use ($totalAnticipos) {
                    return $totalAnticipos;
                })
                # Col. Candy
                ->addColumn('Saldo', function ($row) use ($usuariosSinRetencion, $usuariosSinRetencionAll) {
                    // Obtenemos el valor final de la comision por la cual se validara
                    $finalComision = ($row->comisionModificada != null ? $row->comisionModificada : $row->FinalImporteComision);

                    // Operación del saldo
                    $total = $finalComision - $row->monto;


                    // Obtenemos el año de la fecha de pedido
                    $year = Carbon::parse($row->FechaPedido)->year;

                    // Obtener el mes de la fecha de pedido
                    $month = Carbon::parse($row->FechaCobroFactura)->month;

                    // Validamos si el año es menor igual a 2024 el porcentahje de retencion sera del 7.5% en caso contrario sera del 30%
                    $porcentaje = $this->getPorcentajeComision($row->FechaPedido);

                    // tambien restamos el % de la comision
                    /*                              Operacion                                  */
                    
                    if (!in_array(trim($row->AgentePedido), $usuariosSinRetencionAll)) {
                        // Validamos si el pedido esta cobrado total y el año es 2024
                        if (!in_array($row->estatusCom, [6,3]) && $year == 2024) {
                            // Validamos si el usuario no esta en la lista de los que no tendran retencion
                            if (!in_array(trim($row->AgentePedido), $usuariosSinRetencion)) {
                                // Realizamos la operación para obtener el % de la comisión
                                $comision = $finalComision * $porcentaje;
                            } else {
                                // Realizamos la operación para obtener el saldo
                                $comision = 0;
                            }
                        } else {
                            // Validar solo a los 3 que tienen retenciones, para validar si son pedidos de diciembre solamente
                            if (in_array(trim($row->AgentePedido), $usuariosSinRetencion) && $month == 12 && $year == 2024) {
                                // Realizamos la operación para obtener el % de la comisión
                                $comision = 0;
                            } else {
                                // Realizamos la operación para obtener el % de la comisión
                                $comision = $finalComision * $porcentaje;
                            }
                        }
                    } else {
                        // Realizamos la operación para obtener el saldo
                        $comision = 0;
                    }
                    
                    /*                              Fin Operacion                                  */

                    // Realizamos la operación para obtener el saldo
                    $total = $total - $comision;

                    // Solo se realizará la resta del importe de la comisión menos la sumatoria total de los anticipos
                    return $total;
                })
                # Col. Candy
                ->addColumn('importeRetencion', function ($row) use ($usuariosSinRetencion, $usuariosSinRetencionAll) {
                    // Obtenemos el valor final de la comision por la cual se validara
                    $finalComision = ($row->comisionModificada != null ? $row->comisionModificada : $row->FinalImporteComision);

                    // Obtenemos el año de la fecha de pedido
                    $year = Carbon::parse($row->FechaPedido)->year;

                    // Obtener el mes de la fecha de pedido
                    $month = Carbon::parse($row->FechaCobroFactura)->month;

                    // Validamos si el año es menor igual a 2024 el porcentahje de retencion sera del 7.5% en caso contrario sera del 30%
                    $porcentaje = $this->getPorcentajeComision($row->FechaPedido);

                    if (!in_array(trim($row->AgentePedido), $usuariosSinRetencionAll)) {
                        // Validamos si el pedido esta cobrado total y el año es 2024
                        if (!in_array($row->estatusCom, [6,3]) && $year == 2024) {
                            // Validamos si el usuario no esta en la lista de los que no tendran retencion
                            if (!in_array(trim($row->AgentePedido), $usuariosSinRetencion)) {
                                // Realizamos la operación para obtener el 7.5% de la comisión
                                $comision = $finalComision * $porcentaje;
                            } else {
                                // Realizamos la operación para obtener el saldo
                                $comision = 0;
                            }
                        } else {
                            // Validar solo a los 3 que tienen retenciones, para validar si son pedidos de diciembre solamente
                            if (in_array(trim($row->AgentePedido), $usuariosSinRetencion) && $month == 12 && $year == 2024) {
                                // Realizamos la operación para obtener el % de la comisión
                                $comision = 0;
                            } else {
                                // Realizamos la operación para obtener el % de la comisión
                                $comision = $finalComision * $porcentaje;
                            }
                        }
                    } else {
                        // Realizamos la operación para obtener el saldo
                        $comision = 0;
                    }

                    // Regresamos el valor obtenido
                    return $comision;
                })
                # Col. Candy
                ->addColumn('descRetencion', function ($row) use ($usuariosSinRetencion, $usuariosSinRetencionAll) {
                    // Obtenemos el año de la fecha de pedido111
                    $year = Carbon::parse($row->FechaPedido)->year;

                    // Obtener el mes de la fecha de pedido
                    $month = Carbon::parse($row->FechaCobroFactura)->month;

                    if (!in_array(trim($row->AgentePedido), $usuariosSinRetencionAll)) {
                        // Validamos si el pedido esta cobrado total y el año es 2024
                        if (!in_array($row->estatusCom, [6,3]) && $year == 2024) {
                            // Validamos si el usuario no esta en la lista de los que no tendran retencion
                            if (!in_array(trim($row->AgentePedido), $usuariosSinRetencion)) {
                                return $year <= 2024 ? '7.5 %' : '30 %';
                            } else {
                                return '0 %';
                            }
                        } else {
                            // Validar solo a los 3 que tienen retenciones, para validar si son pedidos de diciembre solamente
                            if (in_array(trim($row->AgentePedido), $usuariosSinRetencion) && $month == 12 && $year == 2024) {
                                // Realizamos la operación para obtener el % de la comisión
                                return '0 %';
                            } else {
                                return $year <= 2024 ? '7.5 %' : '30 %';
                            }

                        }
                    } else {
                        return '0 %';
                    }
                })
                # Col. Candy
                ->addColumn('SaldoFooter', function ($row) use ($totalSaldo) {
                    return $totalSaldo;
                })
                # Col. Candy
                ->addColumn('OP', function ($row) {
                    // Ajustamos el diseño para que no se muestre todo el texto en una sola linea
                    return "<div style='width: 100px; white-space: pre-wrap; word-break: break-word;'>" . $row->OP . "</div>";
                })
                # Col. Candy
                ->addColumn('totalComisionImporte', function($row) use ($totalComisionImporte) {
                    return $totalComisionImporte;
                })
                # Col. Candy
                ->addColumn('totalImportePedido', function($row) use ($totalImportePedido) {
                    return $totalImportePedido;
                })
                # Col. Candy
                ->addColumn('totalImportePerdido', function($row) use ($totalImportePerdido) {
                    return $totalImportePerdido;
                })
                # Col. Candy
                ->addColumn('totalNotaCredito', function($row) use ($totalNotaCredito) {
                    return $totalNotaCredito;
                })
                # Col. Candy
                ->addColumn('totalComisionImporteFinal', function($row) use ($totalComisionImporteFinal) {
                    return $totalComisionImporteFinal;
                })
                # Col. Candy
                ->addColumn('totalRetenciones', function ($row) use ($totalRetenciones) {
                    return $totalRetenciones;
                })
                # Col. Intellisis
                ->addColumn('ComisionImporte', function ($row) {
                    // Regresamos el span con la información del importe
                    return $row->ComisionImporte != null ? $row->ComisionImporte : 0;
                })
                # Col. Intellisis
                ->addColumn('ImportePedido', function ($row) {
                    return $row->ImportePedido != null ? $row->ImportePedido : 0;
                })
                # Col. Candy
                ->addColumn('ImporteComisionModificada', function ($row) {
                    // Validamos si existe una cantidad para mostrarla en la tabla
                    $cantidad = $row->comisionModificada != null ? $row->comisionModificada : null;

                    return $cantidad;
                })
                # Col. Intellisis
                ->addColumn('FechaPedido', function ($row) {
                    // Validamos si existe la fecha de pedido para poder darle el formato correspondiente
                    return $row->FechaPedido != null ? $row->FechaPedido : null;
                })
                # Col. Intellisis
                ->addColumn('Comision', function ($row) {
                    // Regresamos el formato de numero del porcentaje de la comision
                    return $row->Comision;
                })
                # Col. Candy
                ->addColumn('porcentaje', function ($row) {
                    // Validamos y regresamos si el porcentaje modificado en candy tendra el formato correspondiente
                    return $row->porcentajeModificado != null ? $row->porcentajeModificado : null;
                })
                # Col. Candy
                ->addColumn('CheckRetencion', function ($row) use ($request, $roleId, $tipoUsarioAccede) {
                    // Validamos que usuarios podran acceder al modulo
                    if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 5) {
                        // Validamos si es la tabla del listado de comisiones 1
                        if ($request->tblActual == 'tbl1') {
                            // Si es, solo regresamos un div vacio
                            return '<div></div>';
                        } else {
                            // Validamos si el registro es liberado
                            if ($request->isLiberado) {
                                // Regresamos el input bloqueado solo con el tipo de accion que tiene (activo o no)
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->retencion ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                            } else {
                                // Validamos que solo estos roles puedan acceder a realizar la acción
                                if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 5) {
                                    // Validamos el tipo de liberacion
                                    $tipo = $row->estatusCom == 2 ? 2 : 1;

                                    // Generamos la cadena que al darle click realice el cambio de retenido
                                    $cadenaOnclick = "onclick='changeRetenido(" . json_encode($row->IDPedido) . ", this, " . json_encode($row->OP) . ", " . json_encode($row->MovIDPedido) . ", 1, ".$tipo.")' name='retenido+" . $row->IDPedido . "'";

                                    // Validamos que la retencion este activa
                                    if ($row->retencion) {
                                        // Si es asi, regresamos el input como checkeado
                                        return '<div class="content-notes"><div class="custom-switch"><input type="checkbox" class="custom-control-input ctrl-input cursor-pointer" checked style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div>' . ($row->motivoRechazo != null ? '<span><i onclick="showNotesRejected(' . $row->IdComisionLito . ')" class="heroicons--clipboard-document-solid cursor-pointer"></i></span>' : '') . '</div>';
                                    } else {
                                        // En caso contrario solo mostramos el input normal
                                        return '<div class="content-notes"><div class="custom-switch"><input type="checkbox" class="custom-control-input ctrl-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div>' . ($row->motivoRechazo != null ? '<span><i onclick="showNotesRejected(' . $row->IdComisionLito . ')" class="heroicons--clipboard-document-solid cursor-pointer"></i></span>' : '') . '</div>';
                                    }
                                } else {
                                    // En caso de que no cincidan los roles que se validaron se regresara un input sin acciones
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->retencion ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                                }
                            }
                        }
                    } else {
                        return '<div class="content-notes">' . ($row->motivoRechazo != null ? '<span><i onclick="showNotesRejected(' . $row->IdComisionLito . ')" class="heroicons--clipboard-document-solid cursor-pointer"></i></span>' : '') . '</div>';
                    }
                })
                # Col. Candy
                ->addColumn('Rechazar', function ($row) use ($request, $tipoUsarioAccede) {
                    // Validamos que ciertos usuarios puedan acceder a realizar estas acciones
                    if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 5) {
                        // Validamos que la tabla actual sea la tbl 1
                        if ($request->tblActual == 'tbl1') {
                            // Si es asi solo regresamos un div vacio
                            return '<div></div>';
                        } else {
                            // Validamos si el valor obtenido esta liberado
                            if ($request->isLiberado) {
                                // Regresamos solo el input con check o  no, y sin acciones
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->retencion ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                            } else {
                                // Validamos el tipo de liberacion
                                $tipo = $row->estatus == 7 ? 2 : 1;

                                // En caso contrario, generamos la cadena con la funcionalidad que realizara la accion de cambiar la retención, asi como la asignacion del valores que le pasaremos
                                $cadenaOnclick = "onclick='changeRetenido(" . json_encode($row->IDPedido) . ", this, " . json_encode($row->OP) . ", " . json_encode($row->MovIDPedido) . ", 2, ".$tipo.")' name='retenido+" . $row->IDPedido . "'";

                                // Validamos si la retencion esta activa
                                if ($row->retencion == 1) {
                                    // De ser asi, solo regresamos el input activo
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input ctrl-input cursor-pointer" checked style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div>';
                                } else {
                                    // En caso contrario, debemos de regresar el input desmarcado
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input ctrl-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div>';
                                }
                            }
                        }
                    } else {
                        // En caso de que no tengan los roles solo regresamos un div vacio
                        return "<div></div>";
                    }
                })
                # Col. Intellisis
                ->addColumn('Estatus', function ($row) {
                    return '<div>' . $row->EstatusLito . '</div>';
                })
                # Col. Candy
                ->addColumn('firmaVendedor', function ($row) use ($request, $tipoUsarioAccede) {
                    // Validamos que y que roles deben de poder acceder
                    if ($tipoUsarioAccede == 3 || $tipoUsarioAccede == 5) {
                        // Validamos si la tabla es la del comisiones liberadas
                        if ($request->tblActual == 'tbl3') {
                            // Si es asi solo regresamos el input de firma sin opciones
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->firmaVendedor == 1 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                        } else {
                            // Generamos la cadena con la funcionalidad de aprobar o rechazara 
                            $cadenaOnclick = "onclick='approveReject(" . json_encode($row->IDPedido) . ", this, " . json_encode($row->OP) . ", " . json_encode($row->MovIDPedido) . ", 1)' name='firma+" . $row->IDPedido . "'";

                            // Validamos si la firma del vendedor ha sido marcada
                            if ($row->firmaVendedor == 1) {
                                // En caso, de que si regresamos la cadena marcada con check y su cadena de funciones
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" checked style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            } else {
                                // En caso contrario solo la regresamos sin marcar
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            }
                        }
                    } else {
                        // En caso de que no cuenten con la firma solo regresamps el input con su estato de marcado o no sin acciones
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->firmaVendedor == 1 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                    }
                })
                # Col. Candy
                ->addColumn('firmaGerente', function ($row) use ($request, $tipoUsarioAccede) {
                    // Validamos que usuarios pueden realizar la funcion de firma de gerente
                    if ($tipoUsarioAccede == 5 || $tipoUsarioAccede == 2) {
                        // Validamos si el tipo de tabla es 3 para solo colocar el input sin acciones y qeu solo sea demostrativo
                        if ($request->tblActual == 'tbl3') {
                            // Regresamos el input sin acciones solo demostrativo
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->firmaGerente == 1 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                        } else {
                            // En caso contrario obtenemos la cadena con la accion para poder aprobar o rechazar el pedido
                            $cadenaOnclick = "onclick='approveReject(" . json_encode($row->IDPedido) . ", this, " . json_encode($row->OP) . ", " . json_encode($row->MovIDPedido) . ", 2)' name='firma+" . $row->IDPedido . "'";

                            // Validamos si la firma esta registrada
                            if ($row->firmaGerente == 1) {
                                // Regresamos el input con la accion y con la opcion para saber si aparecera marcado o no
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" checked style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            } else {
                                // Validamos en caso cobtrario para que aparezca solo con la accion pero desmarcado
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            }
                        }
                    } else {
                        // Validamos para saber si se mostrara activo o no
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->firmaGerente == 1 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                    }
                })
                # Col. Candy
                ->addColumn('firmaDireccion', function ($row) use ($request, $tipoUsarioAccede) {
                    // Validams que usuarios pueden acceder a funcionalidad
                    if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 5) {
                        if ($request->tblActual == 'tbl3') {
                            // Validaremos solo si esta en la tabla 3 para que aparezca con el estatus con el que correspondiente
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->firmaDireccion == 1 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                        } else {
                            // Obtenemos la cadena con la acción que marcara direccion para firmar un pedido
                            $cadenaOnclick = "onclick='approveReject(" . json_encode($row->IDPedido) . ", this, " . json_encode($row->OP) . ", " . json_encode($row->MovIDPedido) . ", 3)' name='firma+" . $row->IDPedido . "'";

                            // Validamos que tipo de firma esta registrada
                            if ($row->firmaDireccion == 1) {
                                // Devolvemos el componente adjuntando la accion solo que desmarcado
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" checked style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            } else {
                                // Devolvemos el componente con la accion solo que desmarcado
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            }
                        }
                    } else {
                        // En caso contrario solo validamos si el inpit aparecera marcado o no
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->firmaDireccion == 1 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                    }
                })
                # Col. Candy
                ->addColumn('comPagada', function ($row) use ($request, $tipoUsarioAccede) {
                    // Validamos que ususarios pueden acceder a realizar la marcacion de pedido pagado
                    if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 4 || $tipoUsarioAccede == 5) {
                        // Validamos el tipo de tabla que esta actualmente
                        if ($request->tblActual == 'tbl3') {
                            // cadena con la función y los parametros a buscar

                            // Validamos el tipo de liberacion
                            $tipo = $row->estatus == 7 ? 2 : 1;

                            $cadenaOnclick = "onclick='markComisionPagada(" . json_encode($row->IdComisionLito) . ", this, ". $tipo .")' name='firma+" . $row->IDPedido . "'";

                            // Validamos si esta en su estatus 3
                            if ($row->estatus == 3) {
                                // Regresamos el componente marcado
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" checked style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            } else {
                                // Regresamos el componente desmarcado
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer mx-auto" style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label"></label></div>';
                            }
                        }
                    } else {
                        // Regresamos el componente de acuerdo al estatus asignado
                        // return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->estatus == 3 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                        return "<div></div>";
                    }
                })
                # Col. Candy
                ->addColumn('estatusCom', function ($row) {
                    // Validamos si tiene un valor de estatus por defecto
                    if ($row->estatusCom != null) {
                        return '<div class="text-center">'.$row->estatusComNombre.'</div>';
                        
                    } else {
                        // En base se encuentra de esta manera
                        if ($row->EstatusLito == 'Cobrado Total       ') {
                            return '<div class="text-center">Listo para liberar</div>';
                        }

                        return '<div class="text-center">Pendiente</div>';
                    }
                })
                # Col. Candy
                ->addColumn('acciones', function ($row) use ($request, $tipoUsarioAccede) {
                    $generales = "<div class='mr-2'><a class='cursor-pointer' style='color: #000' onclick='goToOportunity(" . json_encode($row->IDPedido) . ", " . json_encode($row->OP) . ")' ><span class='heroicons--arrow-top-right-on-square-20-solid'></span></a></div>
                                <div class='mr-2'><a class='cursor-pointer' style='color: #000' onclick='showTripaOpPedido(" . json_encode($row->IDPedido) . "," . json_encode($row->OP) . ")' ><span class='charm--notes'></span></a></div>
                                <div class='mr-2'><a class='cursor-pointer' style='color: #000' onclick='openModalDetailsPedido(" . json_encode($row->IDPedido) . ")' ><img src='" . asset('img/ojo.png') . "' width='20px' /></a></div>";
                    // Validamos que usuarios pueden realizar acciones
                    if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 5 || $tipoUsarioAccede == 2) {
                        // Validamos en que tabla estan posicionados
                        if ($request->tblActual == 'tbl2' || $request->tblActual == 'tbl1') {
                            // Regresamos el componente para poder abrir el modal con la funcion de actualizacion de comision
                            return "
                                <div class='d-flex justify-content-around'>
                                    <div class='mr-2'><a class='cursor-pointer' style='color: #000' onclick='openModalChangeComision(" . json_encode($row->IDPedido) . ")' ><img src='" . asset('img/lapiz.png') . "' width='20px'></a></div>
                                    ".$generales."
                                </div>
                            ";
                            // <div><a class='cursor-pointer' style='color: #000' onclick='addNotes(" . json_encode($row->IDPedido) . ")' ><span class='solar--notes-bold'></span></a></div>
                        } else {
                            // Regresamos el componente para poder abrir el modal con la funcion de actualizacion de comision
                            return "
                                <div class='d-flex justify-content-around'>
                                    ".$generales."
                                </div>
                            ";
                        }
                    } else {
                        // En caso contrario solo mostramos un div vacio
                        return "<div class='d-flex justify-content-around'>".$generales.'</div>';
                    }
                })
                ->addColumn('firmaVendedorVal', function ($row) {
                    return $row->firmaVendedor;
                })
                ->addColumn('firmaGerenteVal', function ($row) {
                    return $row->firmaGerente;
                })
                ->addColumn('firmaDireccionVal', function ($row) {
                    return $row->firmaDireccion;
                })
                ->addColumn('puedeSeleccionar', function ($row) {
                    return $row->AnticipoPendientePg > $row->SaldoFinal ? 2 : 1; // 1 = puede seleccionar, 2 = no puede seleccionar
                })
                ->rawColumns(['Anticipo', 'AnticipoFooter', 'Saldo', 'SaldoFooter', 'totalComisionImporteFinal', 'totalComisionImporte', 'totalImportePedido', 'totalNotaCredito', 'acciones', 'comPagada', 'firmaVendedor', 'firmaGerente', 'firmaDireccion', 'Rechazar', 'PorcentajeComision', 'ImportePedido', 'ImporteComisionModificada', 'CheckRetencion', 'Estatus', 'porcentaje', 'ComisionImporte', 'estatusCom', 'totalImportePerdido','OP','importeRetencion','totalRetenciones', 'descRetencion', 'firmaVendedorVal', 'firmaGerenteVal', 'firmaDireccionVal', 'puedeSeleccionar'])
                ->make(true);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para obtener el rol de la persona logueada
     * 
     * @return mixed
     */
    public function getRoleUser()
    {
        try {
            // Obtenemos el rol del usuario logueado
            $roleId = $this->idRol();

            // Regresamos la respuesta con el rol
            return response()->json([
                'role' => $roleId
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un error";
        }
    }

    /**
     * Función para obtener el array con los filtros que se pasaran al store
     * 
     * @param Request $request
     * @return array
     */
    public function getFilters(Request $request)
    {
        try {
            // Obtenemos el rol del usuario logueado
            $rol = $this->idRol();

            // Obtenemos el nombre del usuario que esta logueado
            $usuarioNombre = ($rol == 4) ? $this->datosUsuario()[0]->Agente : '';

            // Regresamos el array con los filtros para obtener el listado de los pedidos
            return [
                ($request->typeTbl != null ? $request->typeTbl : 1),
                ($request->filterCliente != null ? $request->filterCliente : ''),
                ($request->filterVendedor != null ? $request->filterVendedor : ''),
                ($request->filterTrabajo != null ? $request->filterTrabajo : ''),
                ($request->filterEstatus != null ? $request->filterEstatus : ''),
                ($request->filterComPagadas != null ? (int)$request->filterComPagadas : ''),
                ($request->filterOP != null ? $request->filterOP : ''),
                ($request->filterPedido != null ? $request->filterPedido : ''),
                
                // Seccion factura
                ($request->filterFactura != null ? (int)1 : (int)2),
                ($request->filterFactura != null ? $request->filterFactura : ''),

                $usuarioNombre,
                ($request->filterEstatusCom != null ? $request->filterEstatusCom : ''),

                ($request->filterFechaPago != null ? $request->filterFechaPago : null),

                // Filtros de firmas
                ($request->filterFirmasVendedor != null ? $request->filterFirmasVendedor : ''),
                ($request->filterFirmasGerentes != null ? $request->filterFirmasGerentes : ''),
                ($request->filterFirmasDireccion != null ? $request->filterFirmasDireccion : ''),
            ];
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrio un error en los datos";
        }
    }


    /**
     * Función para obtener el regsitro por medio del consecutivo
     * 
     * @param Request $request
     * @return mixed
     */
    public function getConsecutivo(Request $request)
    {
        try {
            // Obtenemos el regsitro por el consecutivo
            $pedidos = DB::table('v_APedidos')
                ->where('IDPedido', trim($request->idPedidoIntellisis))
                ->first();

            // Buscamos el pedido en la base de Candy
            $pedidoCandy = comisionesLito::where('IdPedidoIntellisis', trim($request->idPedidoIntellisis))
                ->first();

            $comision = ($pedidoCandy != null) ? round($pedidoCandy->comisionModificada, 2) : round($pedidos->FinalImporteComision, 2);

            $comision = $comision - ($comision * $this->getPorcentajeComision($pedidos->FechaPedido));
            
            $lineaDelTiempo = null;
            
            // Validamos si lo que se requiere solo son datos de visualización o la linea del tiempo
            if ($request->typeGetData != null && $request->typeGetData == 1) {
                $lineaDelTiempo = $this->getLineTime($pedidos->IdComisionLito);
            }

            // Regresamos la respuesta con los campos adecuados
            return response()->json([
                'IdPedidoIntellisis' => $pedidos->IDPedido,
                'ImporteComision' => ($pedidos->comisionModificada !== null) ? round($pedidos->comisionModificada, 2) : round($pedidos->FinalImporteComision, 2),
                'ImporteComisionOriginal' => round($pedidos->FinalImporteComision, 2),
                'ImporteComisionModificado' => ($pedidoCandy != null) ? round($pedidoCandy->comisionModificada, 2) : 0,
                'MotivoActualizacion' => ($pedidoCandy != null ? $pedidoCandy->motivo : ''),
                'FechaActualización' => ($pedidoCandy != null ? date('Y/m/d H:i:s', strtotime($pedidoCandy->updated_at)) : 'Sin fecha'),
                'NombreCliente' => $pedidos->NombreCtePedido,
                'Trabajo' => $pedidos->ConceptoPedido,
                'PorcentajeComision' => $pedidos->Comision,
                'PorcentajeComisionModificado' => ($pedidoCandy != null ? $pedidoCandy->porcentajeModificado : 0),
                'PorcentajeComisionFinal' => ($pedidos->porcentajeModificado !== null ? round($pedidos->porcentajeModificado, 2) : round($pedidos->Comision, 2)),
                'ImportePedido' => round($pedidos->FinalImportePedido, 2),
                'OP' => $pedidos->OP,
                'Pedido' => $pedidos->MovIDPedido,
                'NombreVendedor' => $pedidos->NombreCteAgente,
                'FechaPedido' => str_replace('00:00:00.000', '', $pedidos->FechaPedido),
                'Vendedor' => $this->getUserName($pedidoCandy != null ? $pedidoCandy->idVendedor : -1, 1),
                'Gerente' => $this->getUserName($pedidoCandy != null ? $pedidoCandy->idGerente : -1, 2),
                'Direccion' => $this->getUserName($pedidoCandy != null ? $pedidoCandy->idDireccion : -1, 3),
                'VendedorFechaFirma' => ($pedidoCandy != null) ? $pedidoCandy->fechaFirmaVendedor : null,
                'GerenteFechaFirma' => ($pedidoCandy != null) ? $pedidoCandy->fechaFirmaGerente : null,
                'DireccionFechaFirma' => ($pedidoCandy != null) ? $pedidoCandy->fechaFirmaDireccion : null,
                'IdComisionCandy' => $pedidos->IdComisionLito,
                'saldoFinal' => round($pedidos->SaldoFinal, 2),

                // Info de la retencion
                'retencionDescripcion' => $pedidos->DESCRET,
                'retencionFinal' => round($pedidos->TOTALRET, 2),

                // Seccion de retenciones
                'descPorcRet' => $this->getPorcentajeDescComision($pedidos->FechaPedido),
                'importeRetencion' => round($pedidos->FinalImporteComision * $this->getPorcentajeComision($pedidos->FechaPedido), 2),
                'impNoRet' =>  round($pedidos->FinalImporteComision, 2),
                'fechaPedido' => $pedidos->FechaPedido,

                // Linea del tiempo
                'lineaDelTiempo' => $lineaDelTiempo
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrio un problema, favor de verificar más tarde";
        }
    }

    /**
     * Función para obtener la información de la linea del tiempo
     * 
     * @param $idComision
     * @return mixed
     */
    public function getLineTime($idComision)
    {
        try {
            // Obtenemos la información de la linea del tiempo
            $lineaDelTiempo = DB::table('v_BitacoraComisionesLito')
                ->where('comisionLitoId', $idComision)
                ->get();

            // Agrupamos por fecha la información pero con el formato de fecha Y-m-d
            $lineaDelTiempo = $lineaDelTiempo->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });

            $lineaCadena = '';
            $isFirst = true;

            // Validamos si la linea del tiempo tiene información
            if (count($lineaDelTiempo) > 0) {
                // Recorremos la información de la linea del tiempo
                foreach ($lineaDelTiempo as $key => $linea) {
                    $isActive = '';

                    // Validamos si es el primer registro para que aparezca activo
                    $isActive = $isFirst ? 'active' : '';
                    $isFirst = false;

                    $lineaCadena .= '
                        <div class="carousel-item '.$isActive.'" style="width: 100%;">
                            <div class="card" style="margin: 0 32px;">
                                <div>
                                    <label class="text-sm font-bold bg-default date-title">' . $key . '</label>
                                </div>

                                <div>';

                    // Recorremos la información de la linea del tiempo
                    foreach ($linea as $item) {
                        $lineaCadena .= '
                                <div class="mb-1">
                                    <div>
                                        <span class="text-sm font-bold"><strong>' . $item->section . '</strong></span>
                                    </div>
                                    <div>
                                        <p class="text-sm" style="margin: 0 20px;">' . $item->newChange . ' ('.Carbon::parse($item->created_at)->format('H:i:s').')</p>
                                    </div>
                                    <div>
                                        <span class="text-sm">Realizó la acción: '. $item->Nombre .'</span>
                                    </div>
                                </div>';
                    }

                    $lineaCadena .= '
                                </div>
                            </div>
                        </div>';
                }
            } else {
                $lineaCadena .= '<div class=""><div></span> Sin información</div></div>';
            }

            // Regresamos la respuesta con la información de la linea del tiempo
            return $lineaCadena;
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrio un problema al obtener la información mod";
        }
    }

    /**
     * Función para obtener el nombre del usuario por medio del id
     * 
     * @param $id
     * @param $type
     * @return sring
     */
    public function getUserName($id, $type)
    {
        // Obtenemos al usuario por medio del id
        $user = DB::table('v_UsuariosNombre')->select(['Nombre'])->where('Id_Usuario', $id)->first();

        // Obtenemos el tipo de persona, para que en caso de que no se encuentre el usuario tenga un mensaje alternativo
        $typeUser = $this->getTypePerson($type);

        // regresamos el nombre o la cadena que se obtenga
        return $user != null ? $user->Nombre : '';
    }

    /**
     * Función para actualizar la comisión en la base de candy
     * 
     * @param Request $request
     * @return boolean
     */
    public function updateComision(Request $request)
    {
        try {
            // Realizamos las validaciones
            $validator = $this->validateFormUpdatePedidos($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // Buscamos primero candy que se encuentre el registro previo de la comision
            $pedidoCandy = comisionesLito::where('IdPedidoIntellisis', $request->idPedidoIntellisis)
                ->first();

            // Asignamos al usuario que esta realizando la operación
            $idUsuario = $this->idUsuario();

            // Obtenemos el nuevo cambion
            $action = 'la comisión ' . $request->nuevaComision . ' y el porcentaje ' . $request->porcentaje;

            // Obtenemos el registro de la tabla de comisiones en candy
            $comisionCandy = v_APedidos::where('IDPedido', $request->idPedidoIntellisis)
            ->first();

            // Validamos si solo se va registrar en la tabla o se va actualizar
            if ($pedidoCandy != null) {


                /*          Inicio Validacion de la cantidad por anticipos           */

                // Buscamos los anticipos relacionados con la comision registrada en candy
                $comRegistradas = DB::table('AnticipoComisiones')->where('comisionLitoId', $pedidoCandy->IdComisionLito)->get();

                // Obtenemos la comision actual para validar
                $finalComision = $comisionCandy->comisionModificada != null ? $comisionCandy->comisionModificada : $comisionCandy->FinalImporteComision;

                // Validamos si la comision nueva es menor a la que se obtiene de intellisis
                $isMinorComision = $request->nuevaComision < $finalComision;

                // Validamos si este registro cuenta con anticipos
                if (count($comRegistradas) > 0 && $isMinorComision) {
                    return response()->json([
                        'errores' => 1,
                        'error_nuevaComision' => '<span class="text-danger text-sm" style="padding-top: 5px;">Lo sentimos, este pedido ya cuenta con anticipos registrados y no puedes agregar una cantidad mínima.</span><br>'
                    ]);
                }


                /*          Fin Validacion de la cantidad por anticipos              */

                // Actualizamos la comision
                DB::statement('exec sp_updateComisionLito ?, ?, ?, ?', [
                    $request->idPedidoIntellisis,
                    $request->nuevaComision,
                    $request->motivoActualizacion,
                    $request->porcentaje,
                ]);

                // Obtenemos los cambios previos
                $previousChange = ($pedidoCandy->comisionModificada != null && $pedidoCandy->porcentajeModificado != null) ? ' la comisión ' . $pedidoCandy->comisionModificada . ' y el porcentaje ' . $pedidoCandy->porcentajeModificado : null;

                // Guardamos la bitacora de cambios
                $this->saveBitacoraComisiones('Actualización de importe', ($previousChange != null ? 'Dirección actualizó ' . $previousChange . ' del pedido' : null), 'Dirección actualizó por' . $action . ' del pedido, por el motivo de: ' . $request->motivoActualizacion, $idUsuario, $pedidoCandy->IdComisionLito);

                return response()->json(['response' => true]);
            } else {
                DB::statement('exec sp_registerComisionLito ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                    $idUsuario,
                    $request->nuevaComision,
                    $request->consecutivo,
                    $request->motivoActualizacion,
                    NULL,
                    2,
                    $request->porcentaje,
                    ($request->op == 'null' ? NULL : $request->op),
                    ($request->pedido == 'null' ? NULL : $request->pedido),
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    $request->idPedidoIntellisis,
                    $comisionCandy->EstatusLito != 'Cobrado Total       ' ? 1 : 4
                ]);

                $comLito = comisionesLito::where('IdPedidoIntellisis', $request->idPedidoIntellisis)
                    ->first();

                // Guardamos la bitacora de cambios
                $this->saveBitacoraComisiones('Modificación del importe', null, 'Dirección cambio ' . $action . ' del pedido, por el motivo de ' . $request->motivoActualizacion, $idUsuario, $comLito->IdComisionLito);

                return response()->json(['response' => true]);
            }

            // En caso de que falle regresamos un false
            return response()->json(['response' => false]);
        } catch (\Exception $e) {
            return "Ocurrió un problema favor de intentarlo más tarde";
        }
    }

    /**
     * Función para realizar las validaciones de la actualizacion de comisiones
     * 
     * @param $request
     * @return mixed
     */
    public function validateFormUpdatePedidos($request)
    {
        // Realizamos las validaciones
        $validator = Validator::make(
            $request->all(),
            [
                'nuevaComision' => ['required', 'numeric'],
                'porcentaje' => ['required', 'numeric'],
                'motivoActualizacion' => ['required', 'string', 'min:5', 'max:255'],
            ],
            [
                'nuevaComision.required' => 'Se requiere de un número',
                'nuevaComision.numeric' => 'El valor ingresado debe de ser un número',

                'porcentaje.required' => 'Se requiere de un número',
                'porcentaje.numeric' => 'El valor ingresado debe de ser un número',

                'motivoActualizacion.required' => 'Se requiere del motivo',
                'motivoActualizacion.string' => 'Verifica que sea un texto',
                'motivoActualizacion.min' => 'Se requiere que contenga mínimo 5 caracteres',
                'motivoActualizacion.max' => 'No puedes sobrepasar los 255 caracteres',
            ]
        );

        // Validamos si hay errores 
        if ($validator->fails()) {
            $validatorMsg = $validator->errors()->messages();
            $final = [];

            // Colocamos los campos que se van a validar
            $fields = [
                'nuevaComision',
                'porcentaje',
                'motivoActualizacion'
            ];

            // Se ocupara esta cuenta para que dependiendo de cuantos campos se agreguen se puedan ir agregando a la funcion que obtiene la cadena
            $finalResponse = [
                'errores' => count($fields),
            ];

            // Recorrido para llenar los inputs
            foreach ($fields as $field) {
                $final['error_' . $field] = $this->concatMessagesError($validatorMsg, $field);
            }

            // Recorrido para asignar el valor del mensaje al campo
            foreach ($final as $key => $data) {
                $finalResponse[$key] = $data['cadena'];
            }

            // Regresamos la respuesta con los errores
            return response()->json($finalResponse);
        }
    }

    /**
     * Función para optimizar la solución de mensajes de error
     * 
     * @return mixed
     */
    public function concatMessagesError($validatorMsg, $field)
    {
        // Declaramos la cadena
        $cadena = '';

        // Validamos si existe el campo dentro del validador
        if (isset($validatorMsg[$field])) {
            // Recorremos y concatenamos los mensajes
            foreach ($validatorMsg[$field] as $errorMot) {
                $cadena .= '<span class="text-danger text-sm" style="padding-top: 5px;">' . $errorMot . '</span><br>';
            }
        }

        // Regresamos la cadena
        return [
            'cadena' => $cadena
        ];
    }

    /**
     * Función para actualizar la retención de un registro de pedidos
     * 
     * @param Request $request
     * @return mixed
     */
    public function updateRetenido(Request $request)
    {
        try {
            // Buscamos primero candy que se encuentre el registro previo de la comision
            $pedidoCandy = comisionesLito::where('IdPedidoIntellisis', $request->idPedidoIntellisis)
                ->first();

            // Usuario actual logueado
            $idUsuario = $this->idUsuario();

            if ($request->tipoAnticipo == 1) {
                // Valores para la bitacora
                $action = ($request->retencion === "true" ? ($request->motivoRechazo != null ? 'ha rechazado, por motivo de - ' . $request->motivoRechazo . ' -' : 'ha retenido') : 'no ha retenido');
    
                // Validamos si solo se va registrar en la tabla o se va actualizar
                if ($pedidoCandy != null) {
                    // Obtenemos el previo de retención
                    $previousChange = ($pedidoCandy->retencion == 1 && $pedidoCandy->motivoRechazo == null ? 'ha retenido' : ($pedidoCandy->retencion == 1 && $pedidoCandy->motivoRechazo != null ? 'ha rechazado, por motivo de - ' . $pedidoCandy->motivoRechazo . ' -' : ($pedidoCandy->retencion == 2 ? 'no ha retenido' : null)));
    
                    // Actualizamos la retención
                    DB::statement('exec sp_updateRetencionComisionLito ?, ?', [
                        $pedidoCandy->IdComisionLito,
                        $request->retencion === "true" ? 1 : 0,
                    ]);
    
                    // Realizamos la inserción de los motivos
                    DB::statement('exec sp_agregarMotivosRetencionyRechazo ?,?,?,?', [
                        $pedidoCandy->IdComisionLito,
                        $idUsuario,
                        $pedidoCandy->tipo,
                        $request->motivoRechazo
                    ]);
    
                    // Actualizamos tambien el estatus en la tabla de estatus separada
                    DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                        $pedidoCandy->IdPedidoIntellisis,
                        4
                    ]);
    
                    // Validamos si se debe de guardar el motivo de rechazo
                    if ($request->motivoRechazo != null) {
                        DB::statement('exec sp_updateMotivoRechazoComisionLito ?,?', [
                            $pedidoCandy->IdComisionLito,
                            $request->motivoRechazo
                        ]);
                    }
    
                    // Validamos de acuerdo al valor si se desmarca para validar y cambiar el estatus del pedido
                    if (!($request->retencion === "true")) {
                        $this->validateAndLiberate($pedidoCandy->IdComisionLito);
                    }
    
                    // Guardamos la bitacora de cambios
                    $this->saveBitacoraComisiones(($request->motivoRechazo != null ? 'Actualización de rechazó' : 'Actualización de retención'), ($previousChange != null ? 'Dirección ' . $previousChange . ' el pedido' : null), 'Dirección ' . $action . ' el pedido', $idUsuario, $pedidoCandy->IdComisionLito);
    
                    return response()->json(['response' => true]);
                } else {
                    // Registramos la comisión lito
                    DB::statement('exec sp_registerComisionLito ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $idUsuario,
                        null,
                        null,
                        null,
                        $request->retencion === "true" ? 1 : NULL,
                        2,
                        null,
                        ($request->op == 'null' ? NULL : $request->op),
                        ($request->pedido == 'null' ? NULL : $request->pedido),
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        $request->idPedidoIntellisis,
                        4
                    ]);

                    // Obtenemos el resultado de la comision que se guarda en Candy
                    $comLito = comisionesLito::where('IdPedidoIntellisis', $request->idPedidoIntellisis)
                        ->first();
    
                    // Realizamos la inserción de los motivos
                    DB::statement('exec sp_agregarMotivosRetencionyRechazo ?,?,?,?', [
                        $comLito->IdComisionLito,
                        $idUsuario,
                        $comLito->tipo,
                        $request->motivoRechazo
                    ]);
    
                    // Guardamos la bitacora de cambios
                    $this->saveBitacoraComisiones('Creación de retención', null, 'Dirección ' . $action . ' el pedido', $idUsuario, $comLito->IdComisionLito);
    
                    // Regresamos la respuesta positiva
                    return response()->json(['response' => true]);
                }
            } else {
                // Obtenemos el ultimo anticipo registrado NO PAGADO
                $anticipo = DB::table('AnticipoComisiones')
                    ->where('comisionLitoId', $pedidoCandy->IdComisionLito)
                    ->where('estatus', 0)
                    ->orderBy('created_at', 'desc')
                    ->first();

                // Validamos si existe un anticipo registrado
                if ($anticipo == null) {
                    return response()->json([
                        'response' => false,
                        'message' => 'Lo sentimos, no se encontró un anticipo registrado'
                    ]);
                }

                // Actualizamos el anticipo
                DB::table('AnticipoComisiones')
                    ->where('IdAnticipoComisiones', $anticipo->IdAnticipoComisiones)
                    ->update([
                        'estatus' => 3,
                        'motivo_cancelacion' => $request->motivoRechazo
                    ]);

                // Obtenemos los estatus previos
                $previewEstatus = DB::table('comisionesEstatusPreview')
                    ->where('idComisionLito', $pedidoCandy->IdComisionLito)
                    ->first();

                // Validamos si se encuentra el registro
                if ($previewEstatus == null) {
                    return response()->json([
                        'response' => false,
                        'message' => 'Lo sentimos, no se encontró un registro previo de la comisión'
                    ]);
                }

                // Realizamos el procedimiento para cambiar de estatus a la comision
                $pedidoCandy->estatus = $previewEstatus->estatus;
                $pedidoCandy->estatusCom = $previewEstatus->estatusCom;

                // Limpiamos las firmas de la comision
                $pedidoCandy->firmaVendedor = null;
                $pedidoCandy->firmaGerente = null;
                $pedidoCandy->firmaDireccion = null;
                $pedidoCandy->idVendedor = null;
                $pedidoCandy->idGerente = null;
                $pedidoCandy->idDireccion = null;
                $pedidoCandy->fechaFirmaVendedor = null;
                $pedidoCandy->fechaFirmaGerente = null;
                $pedidoCandy->fechaFirmaDireccion = null;

                $pedidoCandy->save();

                // Actualizar el estatus previo
                DB::table('comisionesEstatusPreview')
                    ->where('idRegistro', $previewEstatus->idRegistro)
                    ->update([
                        'aplicado' => 1
                    ]);

                // Guardamos la bitacora de cambios
                $this->saveBitacoraComisiones('Cancelación de anticipo', null, 'Dirección ha cancelado el A cuenta de ', $idUsuario, $pedidoCandy->IdComisionLito);

                return response()->json(['response' => true]);
            }


            // En caso de que falle regresamos un false
            return response()->json(['response' => false]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un error, intentalo más tarde.";
        }
    }

    /**
     * Función para autorizar el pedido de acuerdo al tipo de persona
     *
     * @param Request $request
     * @return void
     **/
    public function updateFirma(Request $request)
    {
        try {
            // Buscamos primero candy que se encuentre el registro previo de la comision
            $comLito = comisionesLito::where('IdPedidoIntellisis', $request->idPedidoIntellisis)
                ->first();

            // Usuario actual logueado
            $idUsuario = $this->idUsuario();

            /* Importante Validacion de cantidades */

            // Buscar el pedido en la base de Candy
            $pc = v_APedidos::where('IDPedido', $request->idPedidoIntellisis)->first();

            // Traemos el ultimo anticipo registrado no pagado
            $anticipo = DB::table('AnticipoComisiones')
                ->where('comisionLitoId', $pc->IdComisionLito)
                ->where('estatus', 0)
                ->orderBy('created_at', 'desc')
                ->first();

            // Validamos si existe un anticipo registrado
            if ($anticipo != null && $pc != null) {
                // Validamos si el anticipo es mayor a la comision

                if ($anticipo->monto > $pc->SaldoFinal) {
                    return response()->json([
                        'response' => false,
                        'message' => 'Lo sentimos, el anticipo registrado es mayor al saldo final'
                    ]);
                }
            }

            // Valores para la bitacora
            $typePerson = $this->getTypePerson($request->typePerson);
            $action = ($request->signature === "true" ? 'aceptó' : 'rechazó');

            // Validar si se encontro
            if ($comLito == null) {
                DB::statement('exec sp_registerComisionLito ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                    $idUsuario,
                    $request->consecutivo,
                    null,
                    null,
                    null,
                    2,
                    null,
                    ($request->op == 'null' ? NULL : $request->op),
                    ($request->pedido == 'null' ? NULL : $request->pedido),
                    ($request->typePerson == 1 ? ($request->signature === "true" ? 1 : 2) : 2),
                    ($request->typePerson == 2 ? ($request->signature === "true" ? 1 : 2) : 2),
                    ($request->typePerson == 3 ? ($request->signature === "true" ? 1 : 2) : 2),
                    null,
                    ($request->typePerson == 1 ? $idUsuario : null),
                    ($request->typePerson == 2 ? $idUsuario : null),
                    ($request->typePerson == 3 ? $idUsuario : null),
                    $request->idPedidoIntellisis,
                    4
                ]);

                // Obtenemos la comision registrada en candy
                $comLito = comisionesLito::where('IdPedidoIntellisis', $request->idPedidoIntellisis)
                    ->first();

                // Guardamos la bitacora de cambios
                $this->saveBitacoraComisiones('Creación de Firma', null, $typePerson . ' ' . $action . ' el pedido', $idUsuario, $comLito->IdComisionLito);

                // regresamos la respuesta definitiva
                return response()->json(['response' => true]);
            } else {
                // Previous
                if ($request->typePerson == 1) {
                    $previousChange = $comLito->firmaVendedor;
                } elseif ($request->typePerson == 2) {
                    $previousChange = $comLito->firmaGerente;
                } elseif ($request->typePerson == 3) {
                    $previousChange = $comLito->firmaDireccion;
                }

                // Asignamos el cambio previo
                $previousChange = $previousChange == 1 ? 'Acepto' : ($previousChange == 1 ? 'Rechazo' : null);

                // Newer
                DB::statement('exec sp_updateComisionLitoFirma ?,?,?,?', [
                    $comLito->IdComisionLito,
                    $request->typePerson,
                    $idUsuario,
                    ($request->signature === "true" ? 1 : 2)
                ]);

                // Acción para validar si estan las tres firmas para cambiar el estatus final definitivo
                $this->validateAndLiberate($comLito->IdComisionLito);

                // Guardamos la bitacora de cambios
                $this->saveBitacoraComisiones('Actualización de firma', ($previousChange != null ? $typePerson . ' ' . $previousChange . ' el pedido' : null), $typePerson . ' ' . $action . ' el pedido', $idUsuario, $comLito->IdComisionLito);

                // Regresamos la respuesta final
                return response()->json(['response' => true]);
            }

            return response()->json(['response' => false]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return "Lo sentimos ocurrió un problema al autorizar el pedido";
        }
    }

    /**
     * Función para validar que las tres firmas se hayan recolectado y actualizar el pedido a liberado
     * 
     * @param $idComisionLitoCandy
     * @return mixed
     */
    public function validateAndLiberate($idComisionLitoCandy)
    {
        try {
            // Obtenemos la comision registrada en candy
            $comLito = comisionesLito::where('IdComisionLito', $idComisionLitoCandy)->first();

            // Validamos que no sea nulo para poder realizar la modificacion del estatus
            if ($comLito != null) {
                // Validamos que el registro contenga las 3 firmas
                if ($comLito->firmaVendedor == 1 && $comLito->firmaGerente == 1 && $comLito->firmaDireccion == 1) {
                    if ($comLito->retencion == null || $comLito->retencion == 0 || $comLito->retencion == 2) {
                        if ($comLito->estatusCom == 2) {
                            $comLito->estatus = 7; // Se ocupara el estatus 5 solo en este tipo de casos
                            $comLito->estatusCom = 3;
                            $comLito->save();

                            // Actualizamos tambien el estatus en la tabla de estatus separada
                            DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                                $comLito->IdPedidoIntellisis,
                                3
                            ]);
                        } else {
                            $comLito->estatus = 1;
                            $comLito->estatusCom = 5;
                            $comLito->save();

                            // Actualizamos tambien el estatus en la tabla de estatus separada
                            DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                                $comLito->IdPedidoIntellisis,
                                5
                            ]);
                        }
                    }
                }
            }

            // En caso contrario regresamos un false
            return false;
        } catch (\Exception $e) {
            return "Lo sentimos, el pedido no pudo liberar";
        }
    }

    /**
     * Función para guardar la bitacora de acciones de cada acción
     * 
     * @param $section
     * @param $previousChange
     * @param $newChange
     * @param $userId
     * @param $comisionLitoId
     * @return mixed
     */
    public function saveBitacoraComisiones($section, $previousChange, $newChange, $userId, $comisionLitoId)
    {
        try {
            // Guardamos la bitacora
            return DB::statement('exec sp_insertBitacoraComisionLito ?, ?, ?, ?, ?', [
                $comisionLitoId,
                $section,
                $previousChange,
                $newChange,
                $userId,
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un error al realizar la operación";
        }
    }

    /**
     * Función para obtener el tipo de persona en formato de texto
     * 
     * @param $type
     * @return string
     */
    public function getTypePerson($type)
    {
        // Validamos que tipo de persona es por medio de un numero identificador
        switch ($type) {
            case 1:
                return 'Vendedor';
            case 2:
                return 'Gerente';
            case 3:
                return 'Direccion';
        }
    }

    /**
     * Función para obtener el motivo de rechazo actual de un pedido
     * 
     * @param Request $request
     * @return mixed
     */
    public function getMotivoRechazo(Request $request)
    {
        try {
            // Obtenemos el motivo de rechazó por medio del id de la comision registrado en candy
            $com = comisionesLito::select(['motivoRechazo'])->where('IdComisionLito', $request->idComLito)->first();

            // Regresamos la respuesta json con el motivo de rechazo
            return response()->json([
                'notes' => $com->motivoRechazo
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema";
        }
    }

    /**
     * Función para generar el reporte de pedidos liberados
     * 
     * @param Request $request
     * @return mixed
     */
    public function generateReportPl(Request $request)
    {
        try {
            // Obtenemos el listado de pedidos de acuerdo a los filtros
            $pedidos = DB::select('exec getListPedidos ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $this->getFilters($request));

            // Mapeamos la data para que se coloquen en un formato en el que el reporte los pueda leer
            $dataB = array_map(function ($item) {
                return array_values(collect($item)->toArray());
            }, $pedidos);

            // Obtenemos solo las facturas que estan relacionadas con los pedidos
            $facturas = DB::table('Lito.dbo.FacturasLito')->select(['IDPedido', 'MovIDFactura', 'MovFactura', 'ImporteFactura', 'ImporteCobro', 'ImporteAplicacion', 'Condicion', DB::raw("FORMAT(Vencimiento, 'dd/MM/yyyy', 'es-ES' ) as Vencimiento"), 'AlCorriente', DB::raw("FORMAT(FechaFactura, 'dd/MM/yyyy', 'es-ES' ) as FechaFactura")])->whereIn('IDPedido', collect($pedidos)->pluck('IDPedido'))->get();

            // Relacionamos cada pedido con sus valores de factura
            foreach ($dataB as $key => $value) {
                // Obtenemos las facturas
                $facturasList = $facturas->where('IDPedido', $value[0])->toArray();

                // Eliminadmos el indice 0
                unset($value[0]);

                if (count($facturasList) > 0) {
                    // Mapeamos la data de cada factura para colocarla a modo de que la libreria pueda generar el excel
                    $value[14] = array_map(function ($item) {
                        $finalListArray = array_values(collect($item)->toArray());
                        unset($finalListArray[0]);
                        return $finalListArray;
                    }, $facturasList);
                } else {
                    $value[14] = [];
                }

                $dataB[$key] = $value;
            }

            // Regresamos la respuesta json con la data final
            if (count($dataB) > 0) {
                return response()->json($dataB);
            } else {
                return response()->json(['response' => false]);
            }
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al generar el reporte";
        }
    }
    
    /**
     * Función para generar el reporte de pedidos liberados V2
     * 
     * @param Request $request
     * @return mixed
     */
    public function generateReportPlV2(Request $request)
    {
        try {
            // Obtenemos el listado de pedidos de acuerdo a los filtros
            if ($request->tblActual != 'tbl3') {
                $pedidos = DB::select('exec getDataReportExcel ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $this->getFilters($request));
            } else {
                $pedidos = DB::select('exec getListPedidos ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', $this->getFilters($request));
            }

            // Obtenemos el rol del usuario logueado
            $rol = $this->idRol();

            // Obtenemos el nombre del usuario que esta logueado
            $usuarioNombre = ($rol == 4) ? $this->datosUsuario()[0]->Agente : '';

            // Obtenemos las incidencias
            if ($request->tblActual != 'tbl3') {
                $incidencias = DB::select('exec getDataReportExcel ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                    6, 
                    '', 
                    ($request->filterVendedor != null ? $request->filterVendedor : ''), 
                    '', 
                    '', 
                    $request->filterComPagadas, 
                    '', 
                    '', 
                    2, 
                    '', 
                    $usuarioNombre, 
                    $request->filterEstatusCom, 
                    $request->filterFechaPago,
                    '',
                    '',
                    ''
                ]);
            } else {
                $incidencias = DB::select('exec getListPedidos ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                    6, 
                    '', 
                    ($request->filterVendedor != null ? $request->filterVendedor : ''), 
                    '', 
                    '', 
                    $request->filterComPagadas, 
                    '', 
                    '', 
                    2, 
                    '', 
                    $usuarioNombre, 
                    $request->filterEstatusCom, 
                    $request->filterFechaPago,
                    '',
                    '',
                    ''
                ]);
            }

            // Obtener los anticipos
            if ($request->tblActual != 'tbl3') {
                $anticipos = DB::select('exec getDataReportExcel ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                    7, 
                    ($request->filterCliente != null ? $request->filterCliente : ''),
                    ($request->filterVendedor != null ? $request->filterVendedor : ''),
                    ($request->filterTrabajo != null ? $request->filterTrabajo : ''),
                    ($request->filterEstatus != null ? $request->filterEstatus : ''),
                    ($request->filterComPagadas != null ? (int)$request->filterComPagadas : ''),
                    ($request->filterOP != null ? $request->filterOP : ''),
                    ($request->filterPedido != null ? $request->filterPedido : ''),
                    
                    // Seccion factura
                    ($request->filterFactura != null ? (int)1 : (int)2),
                    ($request->filterFactura != null ? $request->filterFactura : ''),

                    $usuarioNombre,
                    ($request->filterEstatusCom != null ? $request->filterEstatusCom : ''),

                    ($request->filterFechaPago != null ? $request->filterFechaPago : null),

                    // Filtros de firmas
                    ($request->filterFirmasVendedor != null ? $request->filterFirmasVendedor : ''),
                    ($request->filterFirmasGerentes != null ? $request->filterFirmasGerentes : ''),
                    ($request->filterFirmasDireccion != null ? $request->filterFirmasDireccion : ''),
                ]);
            } else {
                $anticipos = DB::select('exec getListPedidos ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                    7, 
                    ($request->filterCliente != null ? $request->filterCliente : ''),
                    ($request->filterVendedor != null ? $request->filterVendedor : ''),
                    ($request->filterTrabajo != null ? $request->filterTrabajo : ''),
                    ($request->filterEstatus != null ? $request->filterEstatus : ''),
                    ($request->filterComPagadas != null ? (int)$request->filterComPagadas : ''),
                    ($request->filterOP != null ? $request->filterOP : ''),
                    ($request->filterPedido != null ? $request->filterPedido : ''),
                    
                    // Seccion factura
                    ($request->filterFactura != null ? (int)1 : (int)2),
                    ($request->filterFactura != null ? $request->filterFactura : ''),

                    $usuarioNombre,
                    ($request->filterEstatusCom != null ? $request->filterEstatusCom : ''),

                    ($request->filterFechaPago != null ? $request->filterFechaPago : null),

                    // Filtros de firmas
                    ($request->filterFirmasVendedor != null ? $request->filterFirmasVendedor : ''),
                    ($request->filterFirmasGerentes != null ? $request->filterFirmasGerentes : ''),
                    ($request->filterFirmasDireccion != null ? $request->filterFirmasDireccion : ''),
                ]);
            }

            // Unimos los resultados
            $result = array_merge($pedidos, $incidencias, $anticipos);

            // Mapeamos la data para que se coloquen en un formato en el que el reporte los pueda leer
            $dataB = array_map(function ($item) {
                return array_values(collect($item)->toArray());
            }, $result);

            // Regresamos la respuesta json con la data final
            if (count($dataB) > 0) {
                return response()->json($dataB);
            } else {
                return response()->json(['response' => false]);
            }
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al generar el reporte";
        }
    }

    /**
     * Función para guardar el nuevo anticipo
     * 
     * @param Request $request
     * @return mixed
     */
    public function addNewAnticipo(Request $request)
    {
        try {
            // Validamos la información que viene del formulario
            $validator = $this->validateFormStoreAnticipos($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // Obtenemos el usuario loggueado
            $idUsuario = $this->idUsuario();

            // Obtenemos el registro de la tabla de comisiones en candy (previa para buscar y registrar)
            $comisionCandy = v_APedidos::where('IDPedido', $request->idPedidoIntellisis)
                ->first();

            // Validamos si existe el registro en la db
            if ($comisionCandy->IdComisionLito == null) {
                // Ejecutamos el procedimiento
                DB::statement('exec sp_registerComisionLito ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                    $idUsuario,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    2,
                    NULL,
                    ($request->op == 'null' ? NULL : $request->op),
                    ($request->pedido == 'null' ? NULL : $request->pedido),
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    NULL,
                    $request->idPedidoIntellisis,
                    1
                ]);
            }

            // Obtenemos el registro de la tabla de comisiones en candy (final para buscar y colocar)
            $comisionCandy = v_APedidos::where('IDPedido', $request->idPedidoIntellisis)
                ->first();

            // Importe del pedido para realizar la operación
            $importePedido = ($comisionCandy->TotalCobradoFacturas * $comisionCandy->PorcentajeComisionFinal) / 100;

            // Obtenemos el valor final de la comision por la cual se validara ya restando la retencion
            $retencion = $importePedido * $comisionCandy->NUMRET;

            // Obtenemos el saldo final
            $finalComision = $importePedido - $retencion;


            // Primero entre el anticipo con el importe de comisiones
            $isMinorToTheOriginal = $request->anticipo <= $finalComision;

            // Despues validamos contra la sumatoria de todos los anticipos
            $isMinorCloseToAllAnticipes = ($comisionCandy->monto + $request->anticipo) <= $finalComision;

            // Validamos si falla o se regresa un mensaje de error
            if ($isMinorToTheOriginal && $isMinorCloseToAllAnticipes) {
                $ant = round($request->anticipo);
                DB::statement('exec sp_registerAnticipoCandy ?,?,?,?', [
                    $ant,
                    $comisionCandy->IdComisionLito,
                    $idUsuario,
                    $request->motivoA
                ]);

                // Actualizamos el estatus de la comision cada que se agrega un nuevo anticipo
                $comision = comisionesLito::where('IdComisionLito', $comisionCandy->IdComisionLito)->first();

                /* Importante inicio de guardado de estatus previo para que cuando se autorice el anticipo */
                
                DB::table('comisionesEstatusPreview')->insert([
                    'idComisionLito' => $comision->IdComisionLito,
                    'estatus' => $comision->estatus,
                    'estatusCom' => $comision->estatusCom,
                    'aplicado' => 0
                ]);
                
                /* Importante fin de guardado de estatus previo para que cuando se autorice el anticipo */

                // Validamos que exista el registro
                if ($comision != null) {
                    $comision->estatusCom = 2;
                    $comision->save();

                    // Actualizamos tambien el estatus en la tabla de estatus separada
                    DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                        $comision->IdPedidoIntellisis,
                        2
                    ]);
                }

                // Obtenemos el id del anticipo registrado
                $anticipo = DB::table('AnticipoComisiones')
                    ->select(['IdAnticipoComisiones'])
                    ->where('comisionLitoId', $comisionCandy->IdComisionLito)
                    ->where('monto', $ant)
                    ->first();

                // Guardamos la bitacora de cambios
                $this->saveBitacoraComisiones('Creación anticipo', null, 'Se registro un anticipo de: $ ' . $ant . ' | N°: ' . $anticipo->IdAnticipoComisiones . ' | Motivo: ' . $request->motivoA, $idUsuario, $comisionCandy->IdComisionLito);

                // Regresamos la respuesta positiva
                return response()->json([
                    'response' => true,
                    'idComLitoCandy' => $comisionCandy->IdComisionLito
                ]);
            } else {
                // Regresamos el error general que ya no puede registrar anticipos
                return response()->json([
                    'errores' => 1,
                    'error_anticipo' => '<span class="text-danger text-sm" style="padding-top: 5px;">El anticipo sobrepasa en la sumatoria total de anticipos al importe de comision general.</span><br>'
                ]);
            }

            // 2- Validamos que el valor no sea mayor a la de la comision principal (si no regresamos mensajes de error)

            // Regresamos la respuesta json con la afirmacion de que se inserto el valor
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al realizar la operación";
        }
    }

    /**
     * Función para realizar las validaciones de la actualizacion de comisiones
     * 
     * @param $request
     * @return mixed
     */
    public function validateFormStoreAnticipos($request)
    {
        // Realizamos las validaciones
        $validator = Validator::make(
            $request->all(),
            [
                'anticipo' => ['required', 'numeric', 'min:1'],
                'motivoA' => ['required', 'string', 'min:5', 'max:255'],
            ],
            [
                'anticipo.required' => 'Se requiere de una cantidad',
                'anticipo.numeric' => 'El valor ingresado debe de ser numérico',
                'anticipo.min' => 'Se necesita de un número mayor de 0',

                'motivoA.required' => 'Se requiere del motivo',
                'motivoA.string' => 'Verifica que sea un texto',
                'motivoA.min' => 'Se requiere que contenga mínimo 5 caracteres',
                'motivoA.max' => 'No puedes sobrepasar los 255 caracteres',
            ]
        );

        // Validamos si hay errores 
        if ($validator->fails()) {
            $validatorMsg = $validator->errors()->messages();
            $final = [];

            // Colocamos los campos que se van a validar
            $fields = [
                'anticipo',
                'motivoA'
            ];

            // Se ocupara esta cuenta para que dependiendo de cuantos campos se agreguen se puedan ir agregando a la funcion que obtiene la cadena
            $finalResponse = [
                'errores' => count($fields),
            ];

            // Recorrido para llenar los inputs
            foreach ($fields as $field) {
                $final['error_' . $field] = $this->concatMessagesError($validatorMsg, $field);
            }

            // Recorrido para asignar el valor del mensaje al campo
            foreach ($final as $key => $data) {
                $finalResponse[$key] = $data['cadena'];
            }

            // Regresamos la respuesta con los errores
            return response()->json($finalResponse);
        }
    }

    /**
     * Función para obtener el listado de anticipos del registro
     * 
     * @param Request $request
     * @return mixed
     */
    public function getListAnticipos(Request $request)
    {
        try {
            if ($request->idComisionCandy != null) {
                // Obtenemos el listado de los anticipos relacionados con el registro de candy
                $anticiposList = DB::table('v_AAPedidos')->where('comisionLitoId', $request->idComisionCandy)->get();

                // Obtenemos el tipo de usuario que esta logueado
                $tipoUsarioAccede = $this->tipoUsuarioModuloComisiones();

                // Regfresamos la tabla con los valores pasados
                return DataTables::of($anticiposList)
                    ->addColumn('montoAnticipo', function ($row) {
                        return $row->montoAnticipo;
                    })
                    ->addColumn('eliminarComision', function ($row) use ($tipoUsarioAccede) {
                        if ($tipoUsarioAccede == 5 || $tipoUsarioAccede == 1 || $tipoUsarioAccede == 2) {
                            if ($row->estatusCl != 7) {
                                // Boton para eliminar el anticipo
                                $btnDelete = ($row->estatus == 0 ? "<div style='margin-right: 16px;'><a class='cursor-pointer' title='Eliminar' style='color: #000' onclick='eliminarAnticipo(" . json_encode($row->IdAnticipoComisiones) . ")' ><img src='" . asset('img/eliminar.png') . "' width='20px'></a></div>": "<div></div>");
                                return $btnDelete;
                            } else {
                                return '';
                            }
                        } else {
                            return '';
                        }
                    })
                    ->addColumn('pagarComision', function($row) {
                        switch ($row->estatus) {
                            case 1:
                                return '<div class="text-center text-success">Pagado</div>';
                            case 2:
                                return '<div class="text-center text-secondary">Pendiente</div>';
                            case 3:
                                return '<div class="text-center text-danger">Cancelado</div>';
                            default:
                                return '<div class="text-center text-secondary">Pendiente</div>';
                        }
                    })
                    /*->addColumn('pagarComision', function ($row) use ($tipoUsarioAccede, $request) {
                        // Obtenemos el tipo de usuario que esta logueado
                        if ($tipoUsarioAccede == 1 || $tipoUsarioAccede == 4 || $tipoUsarioAccede == 5) {
                            // Cadena con la accion para cambiar de estatus un anticipo
                            $cadenaOnclick = "onclick='changeStatus(" . json_encode($row->IdAnticipoComisiones) . ", this)' name='approve+" . $row->IdAnticipoComisiones . "'";

                            // Div con el componente para actualizar el estatus del anticipo
                            $finalDiv = "<div class='custom-switch'><input type='checkbox' class='custom-control-input cursor-pointer' " . ($row->estatus == 1 ? 'checked' : '') ." style='cursor: auto;' " . ($row->estatus == 1 ? 'disabled' : $cadenaOnclick) . " ><label class='custom-control-label'></label></div>";

                            // Regresamos la renderizacion de los componentes con las acciones
                            return "
                                    <div style='display: flex; justify-content: center; align-items: center;'>
                                        ". $finalDiv . "
                                    </div>
                            ";
                        } else {
                            // Regresamos el componente de acuerdo al estatus asignado
                            // return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" disabled ' . ($row->estatus == 3 ? 'checked' : '') . ' style="cursor: auto;" ><label class="custom-control-label"></label></div>';
                            return "<div></div>";
                        }
                    })*/
                    ->rawColumns(['montoAnticipo', 'eliminarComision', 'pagarComision'])
                    ->make();
            } else {
                return DataTables::of(collect([]))
                    ->make();
            }
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema. Intentelo más tarde";
        }
    }

    /**
     * Función para obtener el total de anticipos de una comision
     * 
     * @param Request $request
     * @return mixed
     */
    public function getTotalAnticipos(Request $request)
    {
        try {
            // Obtenemos el registro de la tabla de comisiones en candy
            $comisionCandy = v_APedidos::where('IdComisionLito', ($request->idComisionCandy != 'null' ? $request->idComisionCandy : null))
                ->first();

            // Importe del pedido para realizar la operación
            $importePedido = ($comisionCandy->TotalCobradoFacturas * $comisionCandy->Comision) / 100;

            // Obtenemos el valor final de la comision por la cual se validara ya restando la retencion
            $retencion = $importePedido * $comisionCandy->NUMRET;

            // Obtenemos el saldo final
            $saldoFinal = $importePedido - $retencion;

            $totalQuePuedeCargar = $saldoFinal - $comisionCandy->TotalAnticipos;

            // Regresamos el array con la respuesta obtenida
            return response()->json([
                'response' => true,
                'total' => $comisionCandy != null ? $comisionCandy->monto : 0,
                'restanteCant' => $totalQuePuedeCargar,
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema.";
        }
    }

    /**
     * Función para obtener el total de los anticipos
     * 
     * @param Request $request
     * @return mixed
     */
    public function changeStatusAnticipo(Request $request)
    {
        try {
            // Buscamos el anticipo
            $anticipo = DB::table('v_AAPedidos')->where('IdAnticipoComisiones', $request->idAnticipo)->first();

            // Validamos que exista el registro
            if ($anticipo != null) {

                // Validamos si es el ultimo anticipo que se marca como pagado

                // 1° Obtenemos el total de anticipos registrados relacionados a la comision
                $countTotalAnticiposRelacionados = DB::table('v_AAPedidos')->where('comisionLitoId', $anticipo->comisionLitoId)->count();
                
                // 2° Obtenemos todos los anticipos que ya marcados como pagados
                $countAnticiposPagados = DB::table('v_AAPedidos')->where('comisionLitoId', $anticipo->comisionLitoId)->where('estatus', 1)->count();

                // Restamos el total de anticipos pagados a lo general
                $total = $countTotalAnticiposRelacionados - $countAnticiposPagados;

                // validamos si es uno y es true quiere decir que el que se va marcar como pagado y actualizaremos el estatus a la comision en candy
                $comision = comisionesLito::where('IdComisionLito', $anticipo->comisionLitoId)->first();

                // Valor del estatus por defecto
                $estatus = 5;

                // Validamos que exista la comision en candy
                if ($comision != null) {
                    // Validamos que si solo esta pendiente el ultimo registro y que este tenga un valor se actualice el estatus
                    if ($total == 1 && $request->value === 'true') {
                        // Validamos el estatus de la comision para verificar si se queda en anticipo pagado o en liberado
                        if ($comision->estatus == 1) {
                            // cambiamos el estatus a comision que sea anticipo pagado
                            $estatus = 5;
                        } else {
                            // cambiamos el estatus a comision que sea anticipo pagado
                            $estatus = 3;
                        }

                    } else {
                        $estatus = 2;
                    }

                    // Comision estatus
                    $comision->estatusCom = $estatus;
                    // Realizamos el guardado
                    $comision->save();

                    // Actualizamos tambien el estatus en la tabla de estatus separada
                    DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                        $comision->IdPedidoIntellisis,
                        $estatus
                    ]);
                }

                // Realizamos la funcion de actualizar el registro
                DB::statement('exec sp_updateEstatusAnticipo ?, ?, ?', [
                    $anticipo->IdAnticipoComisiones,
                    $request->value === 'true' ? 1 : 2,
                    $request->fechaPago
                ]);

                // Regresamos la respuesta positiva
                return response()->json([
                    'response' => true,
                    'idComLitoCandy' => $anticipo->comisionLitoId
                ]);
            }

            return response()->json([
                'response' => false
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema al cambiar el estatus";
        }
    }

    /**
     * Función para eliminar los anticipos
     * 
     * @param Request $request
     * @return mixed
     */
    public function deleteAnticipo(Request $request)
    {
        try {
            // Buscamos el id del anticipo
            $idComLitoCandy = DB::table('AnticipoComisiones')->where('IdAnticipoComisiones', $request->idAnticipo)->select(['comisionLitoId'])->first();

            // Obtenemos el usuario loggueado
            $idUsuario = $this->idUsuario();

            // Comision lito (CANDY)
            $comLitoId = $idComLitoCandy->comisionLitoId;

            // Guardamos la bitacora de cambios
            $this->saveBitacoraComisiones('Eliminación anticipo', null, 'Se eliminó el anticipo: ' . $request->idAnticipo, $idUsuario, $idComLitoCandy->comisionLitoId);

            // Realizamos la eliminación del anticipo
            DB::statement('exec sp_eliminarAnticipo ?', [
                $request->idAnticipo
            ]);

            // Obtenemos las comisiones relacionadas a un anticipo
            $anticiposRegistrados = DB::table('AnticipoComisiones')->where('comisionLitoId', $comLitoId)->get();

            // Comisión de candy
            $comLito = comisionesLito::where('IdComisionLito', $comLitoId)->first();

            // Comision de la vista
            $comView = DB::table('v_APedidos')->where('IDPedido', $comLito->IdPedidoIntellisis)->first();

            // Validamos si existen anticipos registrados y en caso de que no se cambie el estatus a anticipo pagado
            if (count($anticiposRegistrados) == 0) {
                // Validamos que exista el registro
                if ($comLito != null && $comView != null) {
                    // Realizamos la actualización del campo de estatus
                    $comLito->estatusCom = $comView->EstatusLito != 'Cobrado Total       ' ? 1 : 4;
                    $comLito->save();

                    // Actualizamos tambien el estatus en la tabla de estatus separada
                    DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                        $comLito->IdPedidoIntellisis,
                        $comView->EstatusLito != 'Cobrado Total       ' ? 1 : 4
                    ]);
                }
            } else {
                $pendientesAnticipos = DB::table('AnticipoComisiones')->where('comisionLitoId', $comLitoId)->where('estatus', '!=', 1)->get();

                if (count($pendientesAnticipos) > 0) {
                    // Validamos que exista el registro
                    if ($comLito != null && $comView != null) {
                        // Realizamos la actualización del campo de estatus
                        $comLito->estatusCom = 2;
                        $comLito->save();

                        // Actualizamos tambien el estatus en la tabla de estatus separada
                        DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                            $comLito->IdPedidoIntellisis,
                            2
                        ]);
                    }
                } else {
                    if ($comLito != null && $comView != null) {
                        // Realizamos la actualización del campo de estatus a anticipo pagado
                        $comLito->estatusCom = 3;
                        $comLito->save();

                        // Actualizamos tambien el estatus en la tabla de estatus separada
                        DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                            $comLito->IdPedidoIntellisis,
                            3
                        ]);
                    }
                }
            }

            // Obtenemos los anticipos que no se han pagado
            $anticiposNp = DB::table('AnticipoComisiones')->where('comisionLitoId', $comLitoId)->where('estatus', 0)->count();

            /* Importante actualizamos el registro de los estatus del pedido cuando se elimine el anticipo no pagado */
            
            DB::table('comisionesEstatusPreview')->where('idComisionLito', $comLitoId)->where('aplicado', 0)->update(['aplicado' => 1]);
            
            /* Importante (fin) actualizamos el registro de los estatus del pedido cuando se elimine el anticipo no pagado */

            // Regresamos la respuesta para confirmar que se elimino correctamente el registro
            return response()->json([
                'response' => true,
                'idComLitoCandy' => $idComLitoCandy != null ? $idComLitoCandy->comisionLitoId : null,
                'NothasAnticiposNoPagados' => $anticiposNp == 0
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un error";
        }
    }

    /**
     * Función para marcar como pagada la comisión
     * 
     * @param Request $request
     * @return mixed
     */
    public function marcarComisionPagada(Request $request)
    {
        try {
            // Buscamos la comision en candy para despues marcarla
            $comisionCandy = comisionesLito::where('IdComisionLito', $request->idComisionCandy)->first();

            // Validamos si no se encontro el registro
            if ($comisionCandy == null) {
                return response()->json([
                    'response' => false
                ]);
            }

            if ($request->tipo == 2) {
                // Curso de la comision con anticipo

                // Obtenemos los estatus previos de la comision
                $previewEstatus = DB::table('comisionesEstatusPreview')->where('idComisionLito', $request->idComisionCandy)->where('aplicado', 0)->first();

                // Validamos si existe el registro
                if ($previewEstatus == null) {
                    return response()->json([
                        'response' => false
                    ]);
                }

                // Buscamos el ultimo anticipo no pagado de la comision
                $anticipo = DB::table('AnticipoComisiones')->where('comisionLitoId', $request->idComisionCandy)->where('estatus', 0)->first();

                // Validamos si existe el registro
                if ($anticipo == null) {
                    return response()->json([
                        'response' => false,
                        'error' => 'No puedes marcar como pagada la comisión si existen anticipos no pagados'
                    ]);
                }

                // Realizamos el procedimiento para cambiar de estatus a la comision
                $comisionCandy->estatus = $previewEstatus->estatus;
                $comisionCandy->estatusCom = $previewEstatus->estatusCom;

                // Limpiamos las firmas de la comision
                $comisionCandy->firmaVendedor = null;
                $comisionCandy->firmaGerente = null;
                $comisionCandy->firmaDireccion = null;
                $comisionCandy->idVendedor = null;
                $comisionCandy->idGerente = null;
                $comisionCandy->idDireccion = null;
                $comisionCandy->fechaFirmaVendedor = null;
                $comisionCandy->fechaFirmaGerente = null;
                $comisionCandy->fechaFirmaDireccion = null;

                $comisionCandy->save();

                // Cambiamos el estatus en la tabla de estatus separada de la comision
                DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                    $comisionCandy->IdPedidoIntellisis,
                    $previewEstatus->estatusCom
                ]);

                // Actualizamos el estatus del anticipo
                DB::statement('exec sp_updateEstatusAnticipo ?, ?, ?', [
                    $anticipo->IdAnticipoComisiones,
                    1,
                    $this->transformDate($request->fecha)
                ]);

                // Actualizamos el preview de los estatus
                DB::table('comisionesEstatusPreview')->where('idRegistro', $previewEstatus->idRegistro)->update(['aplicado' => 1]);
            } else {
                // Curso normal de la comision

                // Realizamos el procedimiento para cambiar de estatus
                $comisionCandy->estatus = $request->value === "true" ? 3 : 1;
                $comisionCandy->estatusCom = $request->value === "true" ? 6 : 5;
                $comisionCandy->fechaPagado = $request->value === "true" ? $this->transformDate($request->fecha) : null;
                $comisionCandy->save();

                // Actualizamos tambien el estatus en la tabla de estatus separada
                DB::statement('exec sp_updateEstatusComComisionLito ?,?', [
                    $comisionCandy->IdPedidoIntellisis,
                    $request->value === "true" ? 6 : 5
                ]);
            }

            // Regresamos la respuesta positiva para cuando finalice el procedimiento
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema";
        }
    }

    /**
     * Función para obtener el listado de facturas
     * 
     * @param Request $request
     * @return mixed
     */
    public function getFacturas(Request $request)
    {
        try {
            $pedidoField = $request->typeIdPedido == 2 ? 'IDPedido' : 'MovIDFactura';

            $tImporteFactura = DB::select(DB::raw('
            SELECT SUM(ImporteFactura) as tImporteFactura
            FROM (
                SELECT DISTINCT IDFactura, ImporteFactura
                FROM Lito.dbo.FacturasLito
                WHERE ' . $pedidoField . ' = ?
            ) as subquery
        '), [$request->idPedido])[0]->tImporteFactura;

            $tImporteCobro = DB::select(DB::raw('
            SELECT SUM(ImporteCobro) as tImporteCobro
            FROM (
                SELECT DISTINCT IDFactura, ImporteCobro
                FROM Lito.dbo.FacturasLito
                WHERE ' . $pedidoField . ' = ?
            ) as subquery
        '), [$request->idPedido])[0]->tImporteCobro;

            $tImporteAplicacion = DB::select(DB::raw('
            SELECT SUM(ImporteAplicacion) as tImporteAplicacion
            FROM (
                SELECT DISTINCT IDFactura, ImporteAplicacion
                FROM Lito.dbo.FacturasLito
                WHERE ' . $pedidoField . ' = ?
            ) as subquery
        '), [$request->idPedido])[0]->tImporteAplicacion;

            return response()->json([
                'facturas' => DB::table('Lito.dbo.FacturasLito')
                ->select(['IDPedido', 'MovIDFactura', 'MovFactura', 'ImporteFactura', 'ImporteCobro', 'ImporteAplicacion', 'Condicion', DB::raw("FORMAT(Vencimiento, 'dd/MM/yyyy', 'es-ES' ) as Vencimiento"), 'AlCorriente', DB::raw("FORMAT(FechaFactura, 'dd/MM/yyyy', 'es-ES' ) as FechaFactura"), DB::raw("iif(FechaCobro is null, '-', FORMAT(FechaCobro, 'dd/MM/yyyy', 'es-ES' )) as FechaCobro"), 'EstatusComision'])
                ->where($pedidoField, $request->idPedido)->distinct()->get()
                // eliminamos los elementos duplicados
                ->unique('MovIDFactura')
                ->toArray(),
                'tImporteFactura' => $tImporteFactura,
                'tImporteCobro' => $tImporteCobro,
                'tImporteAplicacion' => $tImporteAplicacion,
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema";
        }
    }


    /**
     * Función especial para realizar el guardado de los pedidos que ya estaran de primera instancia
     * 
     * @return mixed
     */
    public function storeAllPedidos()
    {
        try {
            // Obtenemos los ids de los pedidos para realizar el guardado
            $facturas = DB::table('Lito.dbo.FacturasLito')->select(['IDPedido'])->where('EstatusComision', 'Comision Pagada')->groupBy('IDPedido');

            $idsPedidos = $facturas->get()->pluck('IDPedido')->toArray();

            $idUsuario = $this->idUsuario();

            // Recorremos cada pedido para realizar el guardado en candy
            foreach ($idsPedidos as $pedidoId) {
                DB::statement('exec sp_registerComisionLito ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                    $idUsuario,
                    null,
                    null,
                    null,
                    null,
                    1,
                    null,
                    $this->getOpOrMovIdPedido($pedidoId, 1),
                    $this->getOpOrMovIdPedido($pedidoId, 2),
                    1,
                    1,
                    1,
                    null,
                    $idUsuario,
                    $idUsuario,
                    $idUsuario,
                    $pedidoId,
                    6
                ]);
            }

            return "Se regsitraron todos los pedidos";

        } catch (\Exception $e) {
            return 'Ocurrió un error al realizar el guardado de los pedidos';
        }
    }

    /**
     * Función para obtener la op o el mov id pedido por medio del id del pedido
     * 
     * @param $idPedido
     * @param $type 1 - OP, 2 - MovId
     * @return mixed
     */
    public function getOpOrMovIdPedido($pedidoId, $type = 1)
    {
        try {
            // Inicializamos la variable con el resultado final
            $result = null;

            // Buscamos el pedido por ID
            $pedido = DB::table('v_APedidos')->where('IDPedido', $pedidoId)->first();

            // Validamos si existe el pedido
            if ($pedido != null) {
                // Validamos si solicitan la op o el movId
                if ($type == 1) {
                    $result = ($pedido->OP != null ? $pedido->OP : null);
                } else {
                    $result = ($pedido->MovIDPedido != null ? $pedido->MovIDPedido : null);
                }
            }

            // Regresamos el resultado obtenido
            return $result;
        } catch (\Exception $e) {
            return "Ocurrió un problema al obtener los datos";
        }
    }

    /**
     * Función para realizar el cambio de fecha al formato que lo reconoce la db
     * 
     * @param $fecha
     * @return mixed
     */
    public function transformDate($fecha)
    {
        try {
            $date = \DateTime::createFromFormat('Y-m-d', $fecha);
            $date->setTimezone(new \DateTimeZone('America/Guatemala'));
            return $date->format('d-m-Y');
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema";
        }
    }

    /**
     * Función para obtener el listado de estatus disponibles de cada tabla
     * 
     * @param Request $request
     * @return mixed
     */
    public function getListEstatus (Request $request)
    {
        try {
            // Obtenemos los pedidos de acuerdo a las siguientes tablas
            $pedidosStatus = v_APedidos::select([DB::raw('TRIM(EstatusLito) as EstatusLito')])->groupBy('EstatusLito')->get();

            // Listamos los estatus
            $listEstatus = collect($pedidosStatus)->pluck('EstatusLito')->unique()->toArray();

            // Validamos que estatus se marcaran
            if ($request->typeTbl == 2) {
                unset($listEstatus[6], $listEstatus[2],$listEstatus[3],$listEstatus[4],$listEstatus[5],$listEstatus[0]);
            } else if ($request->typeTbl == 3) {
                unset($listEstatus[4],$listEstatus[5],$listEstatus[6]);
            }

            // Empezamos a generar el select
            $select = '<div><label for="filterEstatus">Estatus</label>
                        <select onchange="'. "applyFiltersStatus('filterEstatus')" .'" name="filterEstatus" id="filterEstatus" class="form-control">
                            <option value="">Selecciona una opción</option>';

            // Recorremos cada elemento y lo concatenamos
            foreach ($listEstatus as $value) {
                $select.='<option value="'.$value.'">'.trim($value).'</option>';
            }

            // Regresamos la respuesta con el componente
            return response()->json([
                'response' => true,
                'select' => $select.='</selec></div>'
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para obtener el listado de las incidencias
     * 
     * @param Request $request
     * @return mixed
     */
    public function getIncidencias(Request $request)
    {
        try {
            // Obtenemos el listado de las incidencias
            $incidencias = DB::table('v_IncidenciasAPedidos')
                ->where('incidencia', 'like', '%' . ($request->filterMotivoIncidencia != null ? $request->filterMotivoIncidencia : '') . '%')
                ->when($request->filterVendedorIncidencia != null, function ($query) use ($request) {
                    return $query->where('IdVendedor', $request->filterVendedorIncidencia);
                })
                ->when($request->filterEstatusIncidencia != null, function ($query) use ($request) {
                    return $query->where('estatusInc', $request->filterEstatusIncidencia);
                })
                ->when($request->filterQuincena != null, function ($query) use ($request) {
                    return $query->where('quincena', $request->filterQuincena);
                })
                ->get();

            // Obtenemos el total del montos de las incidencias
            $totalIncidencias = $incidencias->sum('monto');

            // Regresamos la tabla con los valores pasados
            return DataTables::of($incidencias)
                ->addColumn('estatusInc', function ($row) {
                    // Validamos para que si esta marcada ya no se pueda edit
                    if ($row->estatusInc == 0 || $row->estatusInc == null) {
                        $cadenaOnclick = "onclick='changeStatusIncidencia(" . json_encode($row->idIncidencia) . ", this, 1)' name='approve+" . $row->idIncidencia . "'";
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" '.($row->estatusInc == 1 ? 'checked' : '').' style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div>';
                    } else {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" '.($row->estatusInc == 1 ? 'checked' : '').' style="cursor: auto;" disabled><label class="custom-control-label ctrl-label"></label></div>';
                    }
                    
                })
                ->addColumn('vendedoresList', function ($row) {
                    return "<a class='cursor-pointer' title='Mostrar vendedores' style='color: #000' onclick='showVendedoresList(" . json_encode($row->idIncidencia) . ")' ><span class='heroicons--list-bullet-16-solid'></span></a>";
                })
                ->addColumn('totalIncidencias', function ($row) use ($totalIncidencias) {
                    return $totalIncidencias;
                })
                ->addColumn('acciones', function ($row) {

                    // Accion Eliminar
                    $eliminar = $row->estatusInc == 0 ? "<a class='cursor-pointer eliminarIncidenciasGnr' title='Eliminar' style='color: #000' onclick='eliminarIncidencia(" . json_encode($row->idIncidencia) . ", 1)' ><img src='" . asset('img/eliminar.png') . "' width='20px'></a>" : '';

                    // Accion Editar
                    $editar = $row->estatusInc == 0 ? "<a class='cursor-pointer' title='Editar' style='color: #000; margin-left: 16px;' onclick='editarIncidencia(" . json_encode($row->idIncidencia) . ", 1, " . json_encode($row->IdVendedor) . ", " . json_encode($row->nombreVendedor) . ")' ><img src='" . asset('img/lapiz.png') . "' width='20px'></a>" : '';

                    return "<div style='display: flex; justify-content: center; align-items: center;'>
                                " . $eliminar . "
                                " . $editar . "
                            </div>";
                })
                ->rawColumns(['acciones', 'estatusInc', 'vendedoresList', 'totalIncidencias'])
                ->make();
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema";
        }
    }

    /**
     * Función para obtener el listado de las incidencias (Vendedor)
     * 
     * @param Request $request
     * @return mixed
     */
    public function getIncidenciasByVendedor(Request $request)
    {
        try {
            // Obtenemos el listado de las incidencias
            $incidencias = DB::table('v_IncidenciasAPedidos')
                            ->where('idVendedor', $request->idVendedor)
                            ->get();

            // Obtenemos el total del montos de las incidencias
            $totalIncidencias = DB::table('v_IncidenciasAPedidos')
                                ->where('idVendedor', $request->idVendedor)
                                ->sum('monto');

            // Regresamos la tabla con los valores pasados
            return DataTables::of($incidencias)
                ->addColumn('estatusInc', function ($row) {
                    // Validamos para que si esta marcada ya no se pueda edit
                    if ($row->estatusInc == 0 || $row->estatusInc == null) {
                        $cadenaOnclick = "onclick='changeStatusIncidencia(" . json_encode($row->idIncidencia) . ", this, 2)' name='approve+" . $row->idIncidencia . "'";
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" '.($row->estatusInc == 1 ? 'checked' : '').' style="cursor: auto;" ' . $cadenaOnclick . ' ><label class="custom-control-label ctrl-label"></label></div>';
                    } else {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" '.($row->estatusInc == 1 ? 'checked' : '').' style="cursor: auto;" disabled><label class="custom-control-label ctrl-label"></label></div>';
                    }
                    
                })
                ->addColumn('totalIncidencias', function ($row) use ($totalIncidencias) {
                    return $totalIncidencias;
                })
                ->addColumn('acciones', function ($row) {

                    // Accion Eliminar
                    $eliminar = $row->estatusInc == 0 ? "<a class='cursor-pointer eliminarIncidenciasGnr' title='Eliminar' style='color: #000' onclick='eliminarIncidencia(" . json_encode($row->idIncidencia) . ", 2)' ><img src='" . asset('img/eliminar.png') . "' width='20px'></a>" : '';

                    // Accion Editar
                    $editar = $row->estatusInc == 0 ? "<a class='cursor-pointer' title='Editar' style='color: #000; margin-left: 16px;' onclick='editarIncidencia(" . json_encode($row->idIncidencia) . ", 2, null, null)' ><img src='" . asset('img/lapiz.png') . "' width='20px'></a>" : '';

                    return "<div style='display: flex; justify-content: center; align-items: center;'>
                                " . $eliminar . "
                                " . $editar . "
                            </div>";
                })
                ->rawColumns(['acciones', 'estatusInc', 'vendedoresList', 'totalIncidencias'])
                ->make();
        } catch (\Exception $e) {
            return "Lo sentimos ocurrió un problema";
        }
    }

    /**
     * Función para llenar el select de los tipos de incidencias
     * 
     * @param Request $request
     * @return mixed
     */
    public function getSelectIncidencias(Request $request)
    {
        try {
            // Obtenemos el listado de las incidencias
            $incidencias = DB::table('catMotivosIncidencias')
                                ->where('estatus', 1)
                                ->get();

            // Empezamos a generar el select
            $select = '<select name="incidenciaSelect" id="incidenciaSelect" class="form-control" onchange="checkMontoMotInc(this)">
                            <option value="">Selecciona una opción</option>';

            if (count($incidencias) > 0) {
                // Recorremos cada elemento y lo concatenamos
                foreach ($incidencias as $value) {
                    $select.='<option value="'.$value->idMotivoIncidencia.'">'.$value->incidencia.'</option>';
                }
            }

            // Regresamos la respuesta con el componente
            return response()->json([
                'response' => true,
                'select' => $select.='</selec>'
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para guardar una nueva incidencia al catalogo
     * 
     * @param Request $request
     * @return mixed
     */
    public function saveIncidencia(Request $request)
    {
        try {
            // Validamos la información que viene del formulario
            $validator = $this->validateFormStoreIncidencias($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // Realizamos el guardado de la incidencia
            DB::table('catMotivosIncidencias')->insert([
                'incidencia' => $request->incidenciaInput,
                'estatus' => 1,
                'createdAt' => now()->format('d-m-Y H:i:s'),
            ]);

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la incidencia";
        }
    }

    /**
     * Función para registrar la incidencia final
     * 
     * @param Request $request
     * @return mixed
     */
    public function saveIncidenciaFinal(Request $request)
    {
        try {
            // Validamos la información que viene del formulario
            $validator = $this->validateFormStoreIncidenciasFinal($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // Obtenemos el usuario loggueado
            $idUsuario = $this->idUsuario();

            $quincenaPreval = null;

            // Obtenemos la fecha final del periodo de pago
            if ($request->quincena != null) {
                $quincenaPreval = DB::table('ETL_MSTR.dbo.etl_CatSemanas')->where('QUINCENA', $request->quincena)->select([DB::raw('MAX(FECHA) AS maxFecha')])->first();
            }

            // Realizamos el guardado de la incidencia
            $incidencia = new Incidencia();
            $incidencia->monto = $request->monto;
            $incidencia->quincena = $request->quincena;
            $incidencia->idMotivo = $request->motivo;
            $incidencia->usuarioId = $idUsuario;
            $incidencia->IdVendedor = $request->vendedor;
            $incidencia->periodo = ($quincenaPreval != null ? $quincenaPreval->maxFecha : null);
            $incidencia->estatus = 0;
            $incidencia->save();

            // Validamos si ya se registro en la tabla
            $frec = DB::table('catMotivosIncidencias')->where('idMotivoIncidencia', $request->motivo)->first();

            // Buscamos en la tabla de motivosVendedoresIncidencias
            $relMV = DB::table('motivosVendedoresIncidencias')->where('idVendedor', $request->vendedor)->where('idMotivo', $request->motivo)->first();

            if ($relMV == null) {
                DB::statement('exec sp_insertarRelMotivoVendedor ?, ?, ?', [
                    $request->vendedor,
                    $request->motivo,
                    $frec->frecuencia
                ]);
            }

            // Guardamos la bitacora de cambios
            $this->saveBitacoraIncidencias('Nueva Incidencia', null, 'Se registró una nueva incidencia', $idUsuario, $incidencia->idIncidencia);

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la incidencia";
        }
    }

    /**
     * Función para validar los valores de las incidencias
     * 
     * @return mixed
     */
    public function validateFormStoreIncidenciasFinal($request)
    {
        // Realizamos las validaciones
        $validator = Validator::make(
            $request->all(),
            [
                'monto' => ['required', 'numeric'],
                'quincena' => ['required', 'string'],
                'motivo' => ['required', 'numeric', 'not_in:0'],
            ],
            [
                'monto.required' => 'Se requiere de un monto',
                'monto.numeric' => 'El valor ingresado debe de ser numérico',
                'monto.min' => 'Se necesita de un número mayor de 0',
                'quincena.required' => 'Se requiere de una quincena',
                'quincena.string' => 'El valor ingresado debe de ser una cadena',
                'motivo.required' => 'Se requiere de un motivo',
                'motivo.numeric' => 'El valor ingresado debe de ser numérico',
                'motivo.not_in' => 'Se requiere del motivo',
            ]
        );

        // Validamos si hay errores 
        if ($validator->fails()) {
            $validatorMsg = $validator->errors()->messages();
            $final = [];

            // Colocamos los campos que se van a validar
            $fields = [
                'monto',
                'periodo',
                'motivo',
            ];

            // Se ocupara esta cuenta para que dependiendo de cuantos campos se agreguen se puedan ir agregando a la funcion que obtiene la cadena
            $finalResponse = [
                'errores' => count($fields),
            ];

            // Recorrido para llenar los inputs
            foreach ($fields as $field) {
                $final['error_' . $field] = $this->concatMessagesError($validatorMsg, $field);
            }

            // Recorrido para asignar el valor del mensaje al campo
            foreach ($final as $key => $data) {
                $finalResponse[$key] = $data['cadena'];
            }

            // Regresamos la respuesta con los errores
            return response()->json($finalResponse);
        }
    }

    /**
     * Función para eliminar las incidencias
     * 
     * @param Request $request
     * @return mixed
     */
    public function deleteIncidencia(Request $request)
    {
        try {
            // Realizamos la eliminación de la incidencia
            DB::table('incidenciasComisiones')->where('idIncidencia', $request->idIncidencia)->delete();

            // Eliminar los vendedores de la tabla de incidencias vendedores
            IncidenciaVendedores::where('idIncidencia', $request->idIncidencia)->delete();

            // Guardamos la bitacora de cambios
            $this->saveBitacoraIncidencias('Eliminación Incidencia', null, 'Se eliminó la incidencia', $this->idUsuario(), $request->idIncidencia);

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al eliminar la incidencia";
        }
    }

    /**
     * Función para obtener una incidencia por su id
     * 
     * @param Request $request
     * @return mixed
     */
    public function getIncidenciaById(Request $request)
    {
        try {
            // Obtenemos la incidencia por su id
            $incidencia = DB::table('v_IncidenciasAPedidos')->where('idIncidencia', $request->idIncidencia)->first();

            // Regresamos la respuesta con la incidencia
            return response()->json([
                'response' => true,
                'incidencia' => $incidencia,
                'motivoRel' => DB::table('catMotivosIncidencias')->where('idMotivoIncidencia', $incidencia->idMotivo)->first(),
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la incidencia";
        }
    }

    /**
     * Función para actualizar la incidencia
     * 
     * @param Request $request
     * @return mixed
     */
    public function updateIncidencia(Request $request)
    {
        try {
            // Validamos la información que viene del formulario
            $validator = $this->validateFormStoreIncidenciasFinal($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            $quincenaPreval = null;

            $idUsuario = $this->idUsuario();

            // Obtenemos la fecha final del periodo de pago
            if ($request->quincena != null) {
                $quincenaPreval = DB::table('ETL_MSTR.dbo.etl_CatSemanas')->where('QUINCENA', $request->quincena)->select([DB::raw('MAX(FECHA) AS maxFecha')])->first();
            }

            // Geeramos los cambios previos
            $incidencia = DB::table('v_IncidenciasAPedidos')->where('idIncidencia', $request->idIncidencia)->first();
            if ($incidencia != null) {
                $previo = 'Monto: ' . $incidencia->monto . ', Quincena: ' . $incidencia->quincena . ' , Motivo: ' . $incidencia->idMotivo;
            } else {
                $previo = null;
            }

            // Realizamos la actualización de la incidencia
            DB::table('incidenciasComisiones')->where('idIncidencia', $request->idIncidencia)->update([
                'monto' => $request->monto,
                'quincena' => $request->quincena,
                'idMotivo' => $request->motivo,
                'periodo' => ($quincenaPreval != null ? $quincenaPreval->maxFecha : null),
            ]);

            $new = 'Monto: ' . $request->monto . ', Quincena: ' . $request->quincena . ' , Motivo: ' . $request->motivo;

            // Guardamos la bitacora de cambios
            $this->saveBitacoraIncidencias('Actualización Incidencia', 'Datos previos: ' . $previo, 'Se actualizó la incidencia: ' . $new, $idUsuario, $request->idIncidencia);

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la incidencia";
        }
    }

    /**
     * Función para actualizar el estatus de la incidencia (1 - Pagado, 2 o 0 - Pendiente)
     * 
     * @param Request $request
     * @return mixed
     */
    public function changeStatusIncidencia(Request $request)
    {
        try {
            // Realizamos la actualización del estatus de la incidencia
            DB::table('incidenciasComisiones')->where('idIncidencia', $request->idIncidencia)->update([
                'estatus' => $request->value === 'true' ? 1 : 0
            ]);

            $idUsuario = $this->idUsuario();

            // Guardamos la bitacora de cambios
            $this->saveBitacoraIncidencias('Cambio Estatus Incidencia', 'Estatus previo: ' . ($request->value === 'true' ?  'Pendiente' : 'Pagado'), 'Se cambió el estatus de la incidencia a: ' . ($request->value === 'true' ? 'Pagado' : 'Pendiente'), $idUsuario, $request->idIncidencia);

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al cambiar el estatus";
        }
    }

    /**
     * Función para realizar el guardado de la bitacora de las incidencias
     * 
     * @param $seccion
     * @param $cambioPrevio
     * @param $cambioNuevo
     * @param $idUsuario
     * @param $idIncidencia
     */
    public function saveBitacoraIncidencias($seccion, $cambioPrevio, $cambioNuevo, $idUsuario, $idIncidencia)
    {
        try {
            // Realizamos el guardado de la bitacora
            DB::table('bitacoraIncidenciasComisiones')->insert([
                'seccion' => $seccion,
                'cambioPrevio' => $cambioPrevio,
                'cambioNuevo' => $cambioNuevo,
                'usuarioId' => $idUsuario,
                'incidenciaId' => $idIncidencia,
                'created_at' => now()->format('d-m-Y H:i:s'),
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la bitacora";
        }
    }
    
    /**
     * Función para redirigir a la oportunidad
     * 
     * @param Request $request
     * @return mixed
     */
    public function redirectOpportunity(Request $request)
    {
        try {
            // Obtenemos el id de la oportunidad
            if ($request->op != '') {
                $op = $request->op;
            } else {
                $op = DB::table('v_APedidos')->where('IDPedido', $request->idPedido)->first();
                $op = $op->OP;
            }

            // verificamos si la op empieza con P y termina con alguna letra
            if (preg_match('/^P[A-Z]/', $op)) {
                $op = substr($op, 1);

                // Eliminamos la ultima letra de la op si es que la tiene
                if (preg_match('/[A-Z]$/', $op)) {
                    $op = substr($op, 0, -1);
                }
            }
    
            if ($op != null) {
                // Buscar la oportunidad en candy
                $opportunity = Oportunidades::where('numOrdenMTC', 'like', '%' . $op . '%')
                                        ->orderBy('idOportunidad', 'desc')
                                        ->first();
    
                // Validamos si existe la oportunidad
                if ($opportunity != null) {
                    return response()->json([
                        'response' => true,
                        'url' => 'oportunidades/'. $opportunity->idOportunidad .'/edit'
                    ]);
                } else {
                    return response()->json([
                        'response' => false
                    ]);
                }
            } else {
                return response()->json([
                    'response' => false
                ]);
            }
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para mostrar la ultima tripa de acuerdo a la op de la comision (pendiente)
     * 
     * @param Request $request
     * @return mixed
     */
    public function getTripaProduccion(Request $request)
    {
        try {
            // Obtenemos el id de la oportunidad
            if ($request->op != '') {
                $op = $request->op;
            } else {
                $op = DB::table('v_APedidos')->where('IDPedido', $request->idPedido)->first();
                $op = $op->OP;
            }

            // verificamos si la op empieza con P y termina con alguna letra
            if (preg_match('/^P[A-Z]/', $op)) {
                $op = substr($op, 1);

                // Eliminamos la ultima letra de la op si es que la tiene
                if (preg_match('/[A-Z]$/', $op)) {
                    $op = substr($op, 0, -1);
                }
            }
    
            if ($op != null) {
                // Buscar la oportunidad en candy
                $opportunity = Oportunidades::where('numOrdenMTC', 'like', '%' . $op . '%')
                                        ->orderBy('idOportunidad', 'desc')
                                        ->first();

                // Validamos si existe la oportunidad
                if ($opportunity != null) {

                    // Obtenemos la tripa de la oportunidad
                    $tripas = DB::table('relOport_Documento')->where('idOportunidad', $opportunity->idOportunidad)->get()->pluck('idDocumento')->toArray();

                    // Obtenemos los documentos
                    $documento = DB::table('DocumentosOport')
                                                ->whereIn('idDocumento', $tripas)
                                                ->where('idTipoDocumento', 13)
                                                ->where('tipoTripa', 2)
                                                ->orderBy('idDocumento', 'desc')
                                                ->first();

                    if ($documento != null) {
                        return response()->json([
                            'response' => true,
                            'documento' => $documento
                        ]);
                    } else {
                        return response()->json([
                            'response' => false
                        ]);
                    }

                } else {
                    return response()->json([
                        'response' => false
                    ]);
                }
            } else {
                return response()->json([
                    'response' => false
                ]);
            }
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para obtener el listado de vendedores que sera utilizado para la parte reactiva
     * 
     * @return mixed
     */
    public function getVendedoresR()
    {
        try {
            // Obtenemos el listado de los vendedores
            $listVendedoresIncidencias = DB::table('Lito.dbo.PedidosLito')->select(['NombreCteAgente', DB::raw('TRIM(AgentePedido) as AgentePedido')])->orderBy('NombreCteAgente', 'asc')->groupBy('NombreCteAgente', 'AgentePedido')->get();

            // Regresamos la respuesta con el componente
            return response()->json([
                'response' => true,
                'vendedores' => $listVendedoresIncidencias
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para obtener el listado de vendedores que esta relacionado a la incidencia
     * 
     * @param Request $request
     * @return mixed
     */
    public function getVendedoresIncList(Request $request)
    {
        try {
            // Obtenemos el listado de los vendedores
            $listVendedoresIncidencias = DB::table('v_IncidenciasVendedoresList')
                ->where('idIncidencia', $request->idIncidencia)
                ->select(['vendedor', 'idIncidencia', 'nombreVendedor'])
                ->get();

            // Regresamos la respuesta con el componente
            return response()->json([
                'response' => true,
                'vendedores' => json_encode($listVendedoresIncidencias)
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para obtener la tabla con el catalogo de motivos de las incidencias
     * 
     * @param Request $request
     * @return mixed
     */
    public function getCatMotivoIncidencias(Request $request)
    {
        try {
            // Obtenemos el listado de los vendedores
            $listMotivosIncidencias = DB::table('catMotivosIncidencias')->get();

            // Regresamos la datatable con el listado de los motivos
            return DataTables::of($listMotivosIncidencias)
                ->addColumn('acciones', function ($row) {
                    return "<div style='display: flex; justify-content: center; align-items: center;'>
                                <a class='cursor-pointer' title='Editar' style='color: #000; margin-left: 16px;' onclick='editarMotivoIncidencia(" . json_encode($row->idMotivoIncidencia) . ")' ><img src='" . asset('img/lapiz.png') . "' width='20px'></a>
                                <a class='deleteAction cursor-pointer' title='Editar' style='color: red; margin-left: 16px;' onclick='eliminarCatMotivoIncidencia(" . json_encode($row->idMotivoIncidencia) . ")' ><img src='" . asset('img/eliminar.png') . "' width='20px'></a>
                                <a class='cursor-pointer' title='Vendedores relacionados' style='color: #000; margin-left: 16px;' onclick='showVendedoresList(" . json_encode($row->idMotivoIncidencia) . ")' ><i class='clarity--users-line'></i></a>
                            </div>";
                })
                ->rawColumns(['acciones'])
                ->make();
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para realizar el guardado de los motivos de las incidencias
     * 
     * @param Request $request
     * @return mixed
     */
    public function saveCatMotivosIncidencias(Request $request)
    {
        try {
            // Validamos la información que viene del formulario
            $validator = $this->validateFormStoreIncidencias($request);

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // Realizamos el guardado de la incidencia
            $cmotivo = catMotivosIncidencias::create([
                'incidencia' => $request->incidencia,
                'recurrente' => $request->recurrencia,
                'frecuencia' => $request->frecuenciaCatInc,
                'estatus' => 1,
                'tipo' => $request->tipo,
                'monto' => ($request->tipo == 1 ? $request->montoCatMotAnt : null),
            ]);

            $idUsuario = $this->idUsuario();

            // Guardamos la bitacora de cambios
            $this->saveBitacoraIncidencias('Nuevo Motivo Incidencia', null, 'Se registró un nuevo motivo de incidencia', $idUsuario, $cmotivo->idMotivoIncidencia);

            // Regresamos la respuesta positiva
            return [
                'response' => true
            ];
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la incidencia";
        }
    }

        /**
     * Función para realizar las validaciones de la actualizacion de comisiones
     * 
     * @param $request
     * @return mixed
     */
    public function validateFormStoreIncidencias($request)
    {
        // Realizamos las validaciones
        $validator = Validator::make(
            $request->all(),
            [
                'incidencia' => ['required', 'string', 'max:255'],
                'recurrencia' => ['required'],
                'frecuenciaCatInc' => ($request->recurrencia == 1 ? ['required', 'numeric'] : []),
                'tipo' => ($request->recurrencia == 1 ? ['required', 'numeric'] : []),
                'montoCatMotAnt' => ($request->tipo == 1 ? ['required_if:tipo,1', 'numeric'] : []),
            ],
            [
                'incidencia.required' => 'Se requiere de una incidencia',
                'incidencia.string' => 'El valor ingresado debe de ser una cadena',
                'incidencia.max' => 'El valor ingresado es muy largo',
                'recurrencia.required' => 'Se requiere de una recurrencia',
                'recurrencia.numeric' => 'El valor ingresado debe de ser numérico',
                'frecuenciaCatInc.required_if' => 'Se requiere de una frecuencia',
                'frecuenciaCatInc.numeric' => 'El valor ingresado debe de ser numérico',
                'tipo.required_if' => 'Se requiere de un tipo',
                'tipo.numeric' => 'El valor ingresado debe de ser numérico',
                'montoCatMotAnt.required_if' => 'Se requiere de un monto',
                'montoCatMotAnt.numeric' => 'El valor ingresado debe de ser numérico',
            ]
        );

        // Validamos si hay errores 
        if ($validator->fails()) {
            $validatorMsg = $validator->errors()->messages();
            $final = [];

            // Colocamos los campos que se van a validar
            $fields = [
                'incidencia',
                'recurrencia',
                'frecuenciaCatInc',
                'tipo',
                'montoCatMotAnt',
            ];

            // Se ocupara esta cuenta para que dependiendo de cuantos campos se agreguen se puedan ir agregando a la funcion que obtiene la cadena
            $finalResponse = [
                'errores' => count($fields),
            ];

            // Recorrido para llenar los inputs
            foreach ($fields as $field) {
                $final['error_' . $field] = $this->concatMessagesError($validatorMsg, $field);
            }

            // Recorrido para asignar el valor del mensaje al campo
            foreach ($final as $key => $data) {
                $finalResponse[$key] = $data['cadena'];
            }

            // Regresamos la respuesta con los errores
            return response()->json($finalResponse);
        }
    }

    /**
     * Función para obtener la incidencia por su id
     * 
     * @param Request $request
     * @return mixed
     */
    public function getCatMotIncById(Request $request)
    {
        try {
            // Inicializamos la variable del monto
            $monto = null;

            // Obtenemos la incidencia por su id
            $incidencia = catMotivosIncidencias::where('idMotivoIncidencia', $request->idCatMotInc)->first();

            if ($incidencia != null) {
                $monto = $incidencia->monto;
            }

            // Regresamos la respuesta con la incidencia
            return response()->json([
                'response' => true,
                'incidencia' => $incidencia,
                'monto' => $monto
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la incidencia";
        }
    }

    /**
     * Función para actualizar la incidencia v2
     * 
     * @param Request $request
     * @return mixed
     */
    public function updateCatMotivosIncidencias(Request $request)
    {
        try {
            $previo = null;
            $nuevo = null;

            // Validamos la información que viene del formulario
            $validator = $this->validateFormStoreIncidencias($request);

            $idUsuario = $this->idUsuario();

            // Validamos que no hayan mensajes de validacion, en caso contrario retornamos
            if ($validator != null) {
                return $validator;
            }

            // Realizamos la actualización de la incidencia
            $caMot = catMotivosIncidencias::where('idMotivoIncidencia', $request->idCatMotInc)->first();

            if ($caMot != null) {

                $previo = 'Incidencia: ' . $caMot->incidencia . ', Recurrente: ' . $caMot->recurrente . ', Frecuencia: ' . $caMot->frecuencia . ', Tipo: ' . $caMot->tipo . ', Monto: ' . $caMot->monto;

                // Actualizamos la incidencia
                $caMot->incidencia = $request->incidencia;
                $caMot->recurrente = $request->recurrencia;
                $caMot->frecuencia = $request->frecuenciaCatInc;
                $caMot->tipo = $request->tipo;
                $caMot->monto = ($request->tipo == 1 ? $request->montoCatMotAnt : null);
    
                $caMot->save();

                DB::statement('exec sp_actualizarFrecRelMotivoVendedor ?, ?', [
                    $request->idCatMotInc,
                    $request->frecuenciaCatInc
                ]);

                $nuevo = 'Incidencia: ' . $request->incidencia . ', Recurrente: ' . $request->recurrencia . ', Frecuencia: ' . $request->frecuenciaCatInc . ', Tipo: ' . $request->tipo . ', Monto: ' . ($request->tipo == 1 ? $request->montoCatMotAnt : null);

                // Guardamos la bitacora de cambios
                $this->saveBitacoraIncidencias('Actualización Motivo Incidencia', 'Datos previos: ' . $previo, 'Se actualizó el motivo de incidencia: ' . $nuevo, $idUsuario, $request->idCatMotInc);
            }


            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la incidencia";
        }
    }

    /**
     * Función para eliminar el motivo de la incidencia
     * 
     * @param Request $request
     * @return mixed
     */
    public function deleteCatMotInc(Request $request)
    {
        try {
            // Realizamos la eliminación de la incidencia
            $catM = catMotivosIncidencias::where('idMotivoIncidencia', $request->idCatMotInc)->first();
            $idUsuario = $this->idUsuario();
            // Realizamos la eliminación de la incidencia
            if ($catM != null) {
                // Buscamos que no se encuentre en uso
                $incidencias = DB::table('incidenciasComisiones')->where('idMotivo', $catM->idMotivoIncidencia)->count();

                if ($incidencias > 0) {
                    return response()->json([
                        'response' => false,
                        'message' => 'No se puede eliminar el motivo, ya que se encuentra en uso'
                    ]);
                } else {
                    // Eliminamos el motivo
                    $catM->delete();

                    // Guardamos la bitacora de cambios
                    $this->saveBitacoraIncidencias('Eliminación Motivo Incidencia', null, 'Se eliminó el motivo de incidencia', $idUsuario, $request->idCatMotInc);
                }
            }

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al eliminar la incidencia";
        }
    }

    /**
     * Función para actualizar el estatus de un motivo de la incidencia
     * 
     * @param Request $request
     * @return mixed
     */
    public function changeStatusCatMotInc(Request $request)
    {
        try {
            // Realizamos la actualización del estatus de la incidencia
            $catM = catMotivosIncidencias::where('idMotivoIncidencia', $request->idCatMotInc)->first();

            $idUsuario = $this->idUsuario();

            if ($catM != null) {

                // Validamos que no este en uso
                $incidencias = DB::table('incidenciasComisiones')->where('idMotivo', $catM->idMotivoIncidencia)->count();

                if ($incidencias > 0) {
                    return response()->json([
                        'response' => false,
                        'message' => 'No se puede cambiar el estatus del motivo, ya que se encuentra en uso'
                    ]);
                }

                // Actualizamos el estatus
                $catM->estatus = $request->value === 'true' ? 1 : 0;
                $catM->save();

                // Guardamos la bitacora de cambios
                $this->saveBitacoraIncidencias('Cambio Estatus Motivo Incidencia', 'Estatus previo: ' . ($request->value === 'true' ? 'Inactivo' : 'Activo'), 'Se cambió el estatus del motivo de incidencia a: ' . ($request->value === 'true' ? 'Activo' : 'Inactivo'), $idUsuario, $request->idCatMotInc);

                // Regresamos la respuesta positiva
                return response()->json([
                    'response' => true
                ]);
            }

            return response()->json([
                'response' => false
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al cambiar el estatus";
        }
    }

    /**
     * Función para obtener la fecha de la quincena a la que pertenece de acuerdo a la fecha que nos paso por medio del metodo post
     * 
     * @param Request $request
     * @return mixed
     */
    public function getQuincenaByDate(Request $request)
    {
        try {
            if ($request->fecha != null) {
                // Obtenemos la semana actual
                $fecha = DB::table('ETL_MSTR.dbo.etl_CatSemanas')->where('AÑO', date('Y'))
                            ->where('NUMSEMANA', date('W', strtotime($request->fecha)))
                            ->first();
            } else {
                $fecha = null;
            }

            // Regresamos la respuesta con la fecha
            return response()->json([
                'response' => true,
                'fecha' => $fecha != null ? $fecha->Quincena : 'Sin fecha',
                'numQuincena' => $fecha != null ? $fecha->NumQuincena : 'Sin fecha'
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para saber si el pedido ya ha sido firmado
     * 
     * @param Request $request
     * @return mixed
     */
    public function knowIfWasSigned(Request $request)
    {
        try {
            // Obtenemos el pedido
            // $pedido = DB::table('v_APedidos')->where('IDPedido', $request->idPedido)->first();

            // Validamos si el pedido ya ha sido firmado
            // if ($pedido != null) {
            //     // Dirección
            //     if ($request->typePerson == 3) {
            //         if ($pedido->firmaVendedor == 1 && $pedido->firmaGerente == 1) {
            //             return response()->json([
            //                 'response' => true,
            //                 'message' => 'El pedido ya ha sido firmado'
            //             ]);
            //         } else if ($pedido->firmaVendedor == 1 && ($pedido->firmaGerente == 0 || $pedido->firmaGerente == null)) {
            //             return response()->json([
            //                 'response' => false,
            //                 'message' => 'El pedido ya ha sido firmado por el vendedor pero no por el gerente'
            //             ]);
            //         } else if (($pedido->firmaVendedor == 0 || $pedido->firmaVendedor == null) && $pedido->firmaGerente == 1) {
            //             return response()->json([
            //                 'response' => false,
            //                 'message' => 'El pedido ya ha sido firmado por el gerente pero no por el vendedor'
            //             ]);
            //         } else {
            //             return response()->json([
            //                 'response' => false,
            //                 'message' => 'El pedido no ha sido firmado'
            //             ]);
            //         }
            //     }

            //     // Gerente
            //     if ($request->typePerson == 2) {
            //         if ($pedido->firmaVendedor == 1) {
            //             return response()->json([
            //                 'response' => true,
            //                 'message' => 'El pedido ya ha sido firmado por el vendedor'
            //             ]);
            //         } else {
            //             return response()->json([
            //                 'response' => false,
            //                 'message' => 'El pedido no ha sido firmado'
            //             ]);
            //         }
            //     }

            //     // Vendedor
            //     if ($request->typePerson == 1) {
            //         return response()->json([
            //             'response' => true,
            //             'message' => 'El pedido ya ha sido firmado por el vendedor'
            //         ]);
            //     }
            // } else {
            //     return response()->json([
            //         'response' => false,
            //         'message' => 'El pedido no existe'
            //     ]);
            // }
            return response()->json([
                'response' => true,
                'message' => 'El pedido ya ha sido firmado'
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para marcar todos los pedidos como firmados por direccion
     * 
     * @param Request $request
     * @return mixed
     */
    public function marcarPedidosFirmadosDireccion(Request $request)
    {
        try {
            // Obtenemos los pedidos
            $pedidosFirmadosPorVendedorYGerente = DB::table('v_APedidos')->where('firmaVendedor', 1)->where('firmaGerente', 1)->where('EstatusLito', 'Cobrado Total       ')->where('estatus', 2)->get();

            $idUsuario = $this->idUsuario();

            // Valores para la bitacora
            $typePerson = $this->getTypePerson(3);

            // Recorremos los pedidos
            foreach ($pedidosFirmadosPorVendedorYGerente as $pedido) {
                // Actualizamos el pedido
                $comLito = comisionesLito::where('IdComisionLito', $pedido->IdComisionLito)->first();

                if ($comLito != null) {
                    DB::statement('exec sp_updateComisionLitoFirma ?,?,?,?', [
                        $comLito->IdComisionLito,
                        3,
                        $idUsuario,
                        1
                    ]);

                    // Acción para validar si estan las tres firmas para cambiar el estatus final definitivo
                    $this->validateAndLiberate($comLito->IdComisionLito);
    
                    // Guardamos la bitacora de cambios
                    $this->saveBitacoraComisiones('Actualización de firma', null, $typePerson . ' aceptó el pedido', $idUsuario, $comLito->IdComisionLito);
                }
            }

            // Regresamos la respuesta positiva
            return response()->json([
                'response' => true
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la información";
        }
    }

    /**
     * Función para obtener el listado de un historico por pedido
     * 
     * @param Request $request
     * @return mixed
     */
    public function getHistoricoDetails(Request $request)
    {
        try {
            $comision = comisionesLito::where('IdPedidoIntellisis', $request->idPedido)->first();

            if ($comision != null) {
                // Obtenemos el historico
                $historicoGnrl = DB::table('v_BitacoraComisionesLito')->where('comisionLitoId', $comision->IdComisionLito)->get();

                // Regresamos la datatable con el listado de los motivos
                return DataTables::of($historicoGnrl)
                    ->addColumn('pedido', function ($row) use ($comision) {
                        return $comision->IdPedidoIntellisis;
                    })
                    ->rawColumns(['pedido'])
                    ->make();
            } else {
                return DataTables::of([])->make();
            }

        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }

    /**
     * Función para marcar como pedidos liberados, los pedidos seleccionados
     * 
     * @param Request $request
     * @return mixed
     */
    public function marcarPedidosLiberados(Request $request)
    {
        try {
            $listPedidosIds = explode(',', $request->idPedidos);
            $idUsuario = $this->idUsuario();

            foreach ($listPedidosIds as $idPedido) {
                // Buscamos primero candy que se encuentre el registro previo de la comision
                $comLito = comisionesLito::where('IdPedidoIntellisis', $idPedido)
                ->first();

                // V_Apedidos
                $pedidoVap = DB::table('v_APedidos')->where('IDPedido', $idPedido)->first();

                // Valores para la bitacora
                $typePerson = $request->role == 2 ? 'Gerente' : 'Direccion';
                $action = 'aceptó';

                // Validar si se encontro
                if ($comLito == null) {
                    DB::statement('exec sp_registerComisionLito ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                        $idUsuario,
                        null,
                        null,
                        null,
                        null,
                        2,
                        null,
                        $pedidoVap->OP,
                        $pedidoVap->MovIDPedido,
                        2,
                        ($request->role == 2 ? 1 : 2),
                        ($request->role == 1 || $request->role == 5 ? 1 : 2),
                        null,
                        null,
                        ($request->role == 2 ? $idUsuario : null),
                        ($request->role == 1 || $request->role == 5 ? $idUsuario : null),
                        $idPedido,
                        4
                    ]);

                    // Obtenemos la comision registrada en candy
                    $comLito = comisionesLito::where('IdPedidoIntellisis', $idPedido)
                        ->first();

                    // Guardamos la bitacora de cambios
                    $this->saveBitacoraComisiones('Creación de Firma', null, $typePerson . ' ' . $action . ' el pedido', $idUsuario, $comLito->IdComisionLito);

                } else {
                    // Previous
                    if ($request->role == 2) {
                        $previousChange = $comLito->firmaGerente;
                    } elseif ($request->role == 1 || $request->role == 5) {
                        $previousChange = $comLito->firmaDireccion;
                    }

                    // Asignamos el cambio previo
                    $previousChange = $previousChange == 1 ? 'Acepto' : ($previousChange == 1 ? 'Rechazo' : null);

                    // Newer
                    DB::statement('exec sp_updateComisionLitoFirma ?,?,?,?', [
                        $comLito->IdComisionLito,
                        $request->role == 1 || $request->role == 5 ? 3 : 2,
                        $idUsuario,
                        1
                    ]);

                    // Acción para validar si estan las tres firmas para cambiar el estatus final definitivo
                    $this->validateAndLiberate($comLito->IdComisionLito);

                    // Guardamos la bitacora de cambios
                    $this->saveBitacoraComisiones('Actualización de firma', ($previousChange != null ? $typePerson . ' ' . $previousChange . ' el pedido' : null), $typePerson . ' ' . $action . ' el pedido', $idUsuario, $comLito->IdComisionLito);
                }
            }

            return response()->json(['response' => true]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al guardar la información";
        }
    }

    /**
     * Función para obtener el listado de vendedores relacionados a un motivo
     * 
     * @param Request $request
     * @return mixed
     */
    public function getVendedoresMotivosList(Request $request)
    {
        try {
            // Obtenemos el listado de los vendedores
            $listVendedoresMotivos = DB::table('v_relAgenteMotivos')
                ->where('idMotivo', $request->idMotivo)
                ->select(['idMotivo', 'idVendedor', 'agente'])
                ->get();

            // Regresamos la respuesta con el componente
            return response()->json([
                'response' => true,
                'vendedores' => json_encode($listVendedoresMotivos)
            ]);
        } catch (\Exception $e) {
            return "Lo sentimos, ocurrió un problema al obtener la información";
        }
    }
}
