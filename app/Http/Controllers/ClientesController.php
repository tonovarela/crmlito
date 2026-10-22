<?php

namespace App\Http\Controllers;

use App\catCtasSituacionA;
use App\catRequisitos;
use App\ClienteRequisitos;
use App\CodigosPostales;
use App\Http\Modelos\catCtasEntregaComplementosPago;
use App\Http\Modelos\catCtasMetodoRevision;
use App\Http\Modelos\catTipoDocumentoOport;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\DocumentosCtes;
use App\Http\Modelos\Prospectos;
use App\Http\Modelos\relCliente_Documento;
use App\Http\Modelos\v_catClientes;
use App\Http\Modelos\v_catContactos;
use App\Http\Vistas\v_Prospectos;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use App\v_catGiros;
use App\v_catSubGiros;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class ClientesController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Cuentas';

    public function index()
    {
        $this->permisos();

        $Login = $this->loginUsuario();

        if ($Login == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {
        $permisos = $this->permisosModulo($this->modulo);
        $id_usuario = $this->idUsuario();
        $id_cliente_int = DB::select('exec spMaxClienteINT');
        $agentes = DB::select('exec GetAgentesComboCliente');
        $cat_situacion = catCtasSituacionA::all();
        $cat_giros = v_catGiros::all();
        $cat_subgiros = v_catSubGiros::all();
        $cat_requisitos = catRequisitos::where('estatusRequisito', 1)->orderBy('idRequisito', 'ASC')->get();
        $cat_regimen = DB::select('exec GetRegimenCliente');
        $cat_condiciones = DB::select('exec GetCondicionesCliente');
        $cat_metodo_revision = catCtasMetodoRevision::all();
        $cat_ent_compl_pago = catCtasEntregaComplementosPago::all();
        $paises = DB::select('exec GetPaisesCombo');
        $list_column = DB::select('exec GetColumClientes');
        $uso_cfdi = DB::select('exec GetClientesUsoCFDI');
        $permisos_count = count($permisos);
        $idrol = $permisos[0]->idRol;
        $empresa_web_combo = DB::table('v_empresaWeb')->get();
        
        session(['rol' => $permisos[0]->idRol]);

        for ($i = 0; $i < $permisos_count; $i++) {

            $crear = session('crear');
            $editar = session('editar');
            $editarClientes = session('editarClientes');

            if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                return view('clientes.index', compact('list_column', 'crear', 'editar', 'editarClientes', 'id_cliente_int', 'agentes', 'cat_situacion', 'cat_giros', 'cat_subgiros', 'id_usuario', 'cat_requisitos', 'cat_regimen', 'cat_condiciones', 'idrol', 'cat_metodo_revision', 'cat_ent_compl_pago', 'paises', 'uso_cfdi', 'empresa_web_combo'));
            } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                return redirect()->route('Dashboard');
            }
        }
        // }
    }

    public function store(Request $request)
    {
        $idUsuario = $this->idUsuario();

        $fecha = new \DateTime();

        $requisitosPedido = [];

        $requisitosPedido = $request->selectReqEspeciales_Pedido;

        $requisitosEntrega = [];

        $requisitosEntrega = $request->selectReqEspeciales_Entrega;

        $requisitosCobro = [];

        $requisitosCobro = $request->selectReqEspecialesF_Cobro;

        $puedeDescargarPresupuesto = $request->puedeDescargarPresupuesto == 'on' ? 1 : 0;

        $buro = 0;
        $top = 0;
        $fecha_top = null;

        if ($request->conBuroC == 'on') {
            $buro = 1;
        } else {
            $buro = 0;
        }

        if ($request->esTop == 'on') {
            $top = 1;
            $fecha_top = $fecha->format('Y-m-d');
        } else {
            $top = 0;
        }

        $credito_especial = 0;
        $credito_con_condiciones = 0;

        if ($request->CreditoCondiciones != "") {
            $credito_especial = 1;
            $credito_con_condiciones = 1;
        }

        DB::statement(
            'exec spInsertCliente ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $request->Agente,
                $request->AgenteSecundario,
                $request->razonsocial,
                $request->LITONombreComercial,
                'CLIENTE',
                'ALTA',
                $request->Direccion,
                $request->DireccionNumero,
                $request->DireccionNumeroInt,
                $request->Alcaldia,
                $request->Colonia,
                $request->Poblacion,
                $request->Estado,
                $request->Pais,
                $request->CodigoPostal,
                $request->DireccionFiscal,
                $request->DireccionFiscalNumero,
                $request->DireccionFiscalNumeroInt,
                $request->AlcaldiaFiscal,
                $request->ColoniaFiscal,
                $request->PoblacionFiscal,
                $request->EstadoFiscal,
                $request->PaisFiscal,
                $request->CPFiscal,
                $request->RFC,
                $request->Lada1,
                $request->Telefonos,
                $request->Extencion1,
                $request->Lada2,
                $request->Telefonos2,
                $request->Extencion2,
                $request->DirInternet,
                $request->eMail1,
                '',
                '',
                $request->CreditoCondiciones,
                $request->FiscalRegimen,
                $request->Giro,
                $request->SubGiro,
                $request->ClaveUsoCFDI,
                $buro,
                $top,
                $fecha_top,
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                null,
                $request->idSituacionA,
                $request->fecAniversario,
                0,
                0,
                $credito_especial,
                $credito_con_condiciones,
                $request->urlPortal,
                $request->usuarioPortal,
                $request->cvePortal,
                $request->manualPortal,
                'PROSPECTO INACTIVO',
                $request->empresaWeb,
                $request->WebUserID,
                $puedeDescargarPresupuesto
            ]
        );

        $id_cliente_int = DB::select('exec spUltimoClienteINT');

        $requisitos_cliente = [];

        $p = count($requisitosPedido);

        $requisitos_lista = catRequisitos::where('estatusRequisito', 1)->get();

        $r = count($requisitos_lista);

        for ($i = 0; $i < $r; $i++) {
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitos_lista[$i]->idRequisito)->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int[0]->idClienteINT,
                'idRequisito' => $descRequisito->idRequisito,
                'descRequisito' => $descRequisito->descRequisito,
                'Factura' => 0,
                'Entrega' => 0,
                'CXC' => 0,
            ];
            ClienteRequisitos::create($requisitos_cliente);
        }

        for ($i = 0; $i < $p; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitosPedido[$i])->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitosPedido[$i])->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int[0]->idClienteINT,
                'idRequisito' => $requisitosPedido[$i],
                'descRequisito' => $descRequisito->descRequisito,
                'Factura' => 1,
            ];

            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        $c = count($requisitosEntrega);

        for ($i = 0; $i < $c; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitosEntrega[$i])->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitosEntrega[$i])->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int[0]->idClienteINT,
                'idRequisito' => $requisitosEntrega[$i],
                'descRequisito' => $descRequisito->descRequisito,
                'Entrega' => 1,
            ];
            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        $cc = count($requisitosCobro);

        for ($i = 0; $i < $cc; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitosCobro[$i])->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitosCobro[$i])->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int[0]->idClienteINT,
                'idRequisito' => $requisitosCobro[$i],
                'descRequisito' => $descRequisito->descRequisito,
                'CXC' => 1,
            ];
            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        $folio = $id_cliente_int[0]->idClienteINT;

        // $request->_token = null;
        // $request->_method = null;

        $cadena = implode('|', $request->all());

        $this->bitacora($this->modulo, 'Agregar ', 'Cliente | ' . (string) $cadena, $folio, $this->loginUsuario(), 5);

        return redirect()->route('Clientes');
    }

    public function edit($id)
    {
        $Login = $this->loginUsuario();

        if ($Login == null) {
            return view('iniciar');
        }
        $fecha = new \DateTime();
        $dia = $fecha->format('D');
        // if (($dia == $this->diaSemanaEN() && count($this->entregasSinRespuesta()) > 0) || ($dia == $this->diaSemanaMX() && count($this->entregasSinRespuesta()) > 0)) {
        //     return view('lista_entregas');
        // } else if (count($this->entregasSinRespuesta()) > 0) {
        //     return view('lista_entregas');
        // } else if ($dia == $this->diaSemanaEN() || $dia == $this->diaSemanaMX() || count($this->entregasSinRespuesta()) == 0) {

        $permisos = $this->permisosModulo($this->modulo);
        $id_usuario = $this->idUsuario();

        $entrega = 'Entrega';
        $factura = 'Factura';
        $cxc = 'CXC';

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $id);
        $contactos = DB::select('exec GetContactosXidCliente @idClienteINT=' . $id);
        $sucursales = DB::select('exec GetSucursalesXidCliente @idClienteINT=' . $id);
        $oportunidades = DB::select('exec GetOportunidadesXCliente @idClienteINT=' . $id . ', @tipo=1');

        $requisitos_entregas = DB::select('exec GetRequisitosEntrega @ClienteINT=' . $id);
        $requisitos_facturas = DB::select('exec GetRequisitosFactura @ClienteINT=' . $id);
        $requisitos_cxc = DB::select('exec GetRequisitosCXC @ClienteINT=' . $id);

        $requisitos_entregas_disponibles = DB::select('exec GetRequisitosDisponiblesEntrega @ClienteINT=' . $id);
        $requisitos_facturas_disponibles = DB::select('exec GetRequisitosDisponiblesFactura @ClienteINT=' . $id);
        $requisitos_cxc_disponibles = DB::select('exec GetRequisitosDisponiblesCXC @ClienteINT=' . $id);

        $agentes = DB::select('exec GetAgentesComboCliente');
        $agente_id = $agentes[0]->idAgente;
        $cat_situacion = catCtasSituacionA::all();
        $cat_giros = v_catGiros::all();
        $cat_subgiros = v_catSubGiros::all();
        $cat_requisitos = catRequisitos::where('estatusRequisito', 1)->orderBy('idRequisito', 'ASC')->get();
        $cat_regimen = DB::select('exec GetRegimenCliente');
        $cat_condiciones = DB::select('exec GetCondicionesCliente');
        $cat_metodo_revision = catCtasMetodoRevision::all();
        $cat_ent_compl_pago = catCtasEntregaComplementosPago::all();
        $paises = DB::select('exec GetPaisesCombo');
        $clientes = DB::table('Clientes')->where('idClienteINT', $id)->get();
        $id_cliente = $clientes[0]->idCliente;
        $combo_clientes = Clientes::orderBy('razonsocial', 'asc')->where('Estatus', 'ALTA')->where('idCliente', $id_cliente)->get();
        // return $combo_clientes;
        $direcciones = DB::table('catDireccionLlamadas')->get();
        $tipoRepeticionLlamadas = DB::table('catTipoRepeticionLlamadas')->get();
        $horasRecordatorio = DB::table('catHoraRecordatorioLlamadas')->get();
        $llamadas_reuniones = DB::table('v_LlamadasReunionesXCliente')->where('id_relacionadoCon', $id_cliente)->orderBy('fecCreacion')->get();
        // return $llamadas_reuniones;

        $count_requisitos = count($cat_requisitos);

        $count_requisitos_entregas = count($requisitos_entregas);
        $count_requisitos_facturas = count($requisitos_facturas);
        $count_requisitos_cxc = count($requisitos_cxc);

        $c = 0;

        $idrol = $permisos[0]->idRol;
        $rol = $permisos[0]->idRol;

        $editarClientes = session('editarClientes');
        $uso_cfdi = DB::select('exec GetClientesUsoCFDI');
        $empresa_web_combo = DB::table('v_empresaWeb')->get();

        // return $empresa_web_combo;

        $crearOpor = session('crearOpor');
        $editarOpor = session('editarOpor');
        $editarOportunidades = session('EditarOportunidades');

        $agenteActual = $this->datosUsuario()[0]->Agente;

        foreach ($permisos as $permiso) {
            if ($editarClientes == 'EditarClientes') {
                return view('clientes.edit', compact('cliente', 'agentes', 'contactos', 'sucursales', 'oportunidades', 'cat_situacion', 'cat_giros', 'cat_subgiros', 'id_usuario', 'cat_requisitos', 'requisitos_entregas', 'requisitos_facturas', 'requisitos_cxc', 'count_requisitos', 'count_requisitos_entregas', 'count_requisitos_facturas', 'count_requisitos_cxc', 'c', 'cat_regimen', 'cat_condiciones', 'idrol', 'requisitos_entregas_disponibles', 'requisitos_facturas_disponibles', 'requisitos_cxc_disponibles', 'cat_metodo_revision', 'cat_ent_compl_pago', 'paises', 'uso_cfdi', 'llamadas_reuniones', 'rol', 'agente_id', 'combo_clientes', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'crearOpor', 'editarOpor', 'editarOportunidades', 'empresa_web_combo'));
            } else {
                if ($Login == $cliente[0]->Login || $Login == $cliente[0]->LoginAuxiliar || $Login == $cliente[0]->LoginAuxiliar2 || $Login == $cliente[0]->LoginAuxiliar3 || $Login == $cliente[0]->LoginAuxiliar4 || $id_usuario == $cliente[0]->idAgente2|| $id_usuario == $cliente[0]->idAgente3 || ($agenteActual != null && $cliente[0]->Agente3 == trim($agenteActual))) {
                    if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Todo') {
                        return view('clientes.edit', compact('cliente', 'agentes', 'contactos', 'sucursales', 'oportunidades', 'cat_situacion', 'cat_giros', 'cat_subgiros', 'id_usuario', 'cat_requisitos', 'requisitos_entregas', 'requisitos_facturas', 'requisitos_cxc', 'count_requisitos', 'count_requisitos_entregas', 'count_requisitos_facturas', 'count_requisitos_cxc', 'c', 'cat_regimen', 'cat_condiciones', 'idrol', 'requisitos_entregas_disponibles', 'requisitos_facturas_disponibles', 'requisitos_cxc_disponibles', 'cat_metodo_revision', 'cat_ent_compl_pago', 'paises', 'uso_cfdi', 'llamadas_reuniones', 'rol', 'agente_id', 'combo_clientes', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'crearOpor', 'editarOpor', 'editarOportunidades', 'empresa_web_combo'));
                    } else if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Ninguno') {
                        return back();
                    }
                } else {
                    return back();
                }
            }
        }
        // }
    }

    public function update(Request $request)
    {
        // return $request;

        $idUsuario = $this->idUsuario();

        $puedeDescargarPresupuesto = $request->puedeDescargarPresupuesto == 'on' ? 1 : 0;

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $request->idClienteINT);

        $permisos = $this->permisosModulo($this->modulo);
        $idrol = $permisos[0]->idRol;

        $requisitosPedido = [];

        $requisitosPedido = $request->selectReqEspeciales_Pedido;

        $requisitosEntrega = [];

        $requisitosEntrega = $request->selectReqEspeciales_Entrega;

        $requisitosCobro = [];

        $requisitosCobro = $request->selectReqEspecialesF_Cobro;

        $fecha = new \DateTime();

        $buro = 0;
        $top = 0;
        $bloquear_morosos = 'NO';
        $fecha_aut_cre = null;
        $addenda_especial = 0;

        $fecha_top = $request->fecTop;

        if ($request->conBuroC == 'on') {
            $buro = 1;
        } else {
            $buro = 0;
        }

        if ($request->esTop == 'on') {
            $top = 1;
            if ($fecha_top == null) {
                $fecha_top = $fecha->format('Y-m-d');
            }
        } else {
            $top = 0;
            $fecha_top = null;
        }

        $credito_especial = 0;
        $credito_con_condiciones = 0;
        $empresaweb = $request->empresaWeb;

        if ($idrol == 5 || $idrol == 1 || $idrol == 8) {
            $LimiteCredito = $request->limiteCredito;
            $CreditoLimitePedidos = $request->CreditoLimitePedidos;
            $LimiteDeCredito = $request->LimiteDeCredito;
            $DireccionFiscal = $request->DireccionFiscal;
            $DireccionFiscalNumero = $request->DireccionFiscalNumero;
            $DireccionFiscalNumeroInt = $request->DireccionFiscalNumeroInt;
            $AlcaldiaFiscal = $request->AlcaldiaFiscal;
            $ColoniaFiscal = $request->ColoniaFiscal;
            $PoblacionFiscal = $request->PoblacionFiscal;
            $EstadoFiscal = $request->EstadoFiscal;
            $PaisFiscal = $request->PaisFiscal;
            $CPFiscal = $request->CPFiscal;
            $RFC = $request->RFC;
            $RIT = $request->NumRegIdTrib;
            $CreditoCondiciones = $request->CreditoCondiciones;
            $UsoCFDI = $request->ClaveUsoCFDI;
            $WebUserID = $cliente[0]->WebUserID;

            if ($request->CreditoCondiciones != "") {
                $credito_especial = 1;
                $credito_con_condiciones = 1;
            }

            $FiscalRegimen = $request->FiscalRegimen;
            $urlPortal = $request->urlPortal;
            $usuarioPortal = $request->usuarioPortal;
            $cvePortal = $request->cvePortal;
            $manualPortal = $request->manualPortal;

            if ($request->BloquearMorosos == 'on') {
                $bloquear_morosos = 'SI';
            }
            $diasRevision = $request->diasRevision;
            $idMetodoRevision = $request->idMetodoRevision;
            $idEntregaComplementoPago = $request->idEntregaComplementoPago;

            if ($request->addendaEspecial == 'on') {
                $addenda_especial = 1;
            }
            $idUsuarioAutCredito = $request->idUsuarioAutCredito;

            if ($request->idUsuarioAutCredito != '') {
                $fecha_aut_cre = $fecha->format('Y-m-d H:i:s');
            } else {
                $fecha_aut_cre = $request->fecModificacionLimiteCredito;
            }
        } else {
            $LimiteCredito = $cliente[0]->CreditoLimite;
            $CreditoLimitePedidos = $cliente[0]->LimiteCredito;
            $LimiteDeCredito = $cliente[0]->LimiteDeCredito;
            $DireccionFiscal = $cliente[0]->DirecccionFiscal;
            $DireccionFiscalNumero = $cliente[0]->DireccionFiscalNumero;
            $DireccionFiscalNumeroInt = $cliente[0]->DireccionFiscalNumeroInt;
            $AlcaldiaFiscal = $cliente[0]->AlcaldiaFiscal;
            $ColoniaFiscal = $cliente[0]->ColoniaFiscal;
            $PoblacionFiscal = $cliente[0]->PoblacionFiscal;
            $EstadoFiscal = $cliente[0]->EstadoFiscal;
            $PaisFiscal = $cliente[0]->PaisFiscal;
            $CPFiscal = $cliente[0]->CPFiscal;
            $RFC = $cliente[0]->RFC;
            $RIT = $cliente[0]->NumRegIdTrib;
            $CreditoCondiciones = $cliente[0]->CreditoCondiciones;
            if ($idrol == 1017 || $idUsuario == 63 || $idrol == 1010) {
                $UsoCFDI = $request->ClaveUsoCFDI;
                $empresaweb = $request->empresaWeb;
                $WebUserID = $request->WebUserID;    
            } else {
                $UsoCFDI = $cliente[0]->ClaveUsoCFDI;
                $empresaweb = $cliente[0]->EmpresaWEB;
                $WebUserID = $cliente[0]->WebUserID;
            }

            if ($CreditoCondiciones != "") {
                $credito_especial = 1;
                $credito_con_condiciones = 1;
            }

            $FiscalRegimen = $cliente[0]->FiscalRegimen;
            $urlPortal = $cliente[0]->urlPortal;
            $usuarioPortal = $cliente[0]->usuarioPortal;
            $cvePortal = $cliente[0]->cvePortal;
            $manualPortal = $cliente[0]->manualPortal;
            $bloquear_morosos = $cliente[0]->BloquearMorosos;
            $diasRevision = $cliente[0]->diasRevision;
            $idMetodoRevision = $cliente[0]->idMetodoRevision;
            $idEntregaComplementoPago = $cliente[0]->idEntregaComplementoPago;
            $addenda_especial = $cliente[0]->addendaEspecial;
            $idUsuarioAutCredito = $cliente[0]->idUsuarioAutLimiteCredito;
            $fecha_aut_cre = $cliente[0]->fecModificacionLimiteCredito;
        }

        $id_cliente = $cliente[0]->idCliente;

        $prospecto = Prospectos::where('idCliente', $id_cliente)->first();

        if ($idrol == 1017 || $idUsuario == 63 || $idrol == 1010) {
            // $UsoCFDI = $request->ClaveUsoCFDI;
            // $empresaweb = $request->empresaWeb;
            // $WebUserID = $request->WebUserID;

            DB::statement('exec spUpdateCamposExtraClienteSAAM ?,?,?,?', [
                $empresaweb,
                $WebUserID,
                $idUsuario,
                $request->idClienteINT,
            ]);

            DB::statement('exec spUpdateClaveUsoCFDI ?,?', [
                $UsoCFDI,
                $request->idClienteINT,
            ]);

            if ($prospecto != null) {
                DB::statement('exec spUpdateClaveUsoCFDIProspecto ?,?', [
                    $UsoCFDI,
                    $prospecto->idProspecto,
                ]);
            }

            if ($idUsuario == 63) {
                DB::statement(
                    'exec spUpdateCliente ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                    [
                        $request->Agente,
                        $request->AgenteSecundario,
                        $request->razonsocial,
                        $request->LITONombreComercial,
                        $request->Direccion,
                        $request->DireccionNumero,
                        $request->DireccionNumeroInt,
                        $request->Alcaldia,
                        $request->Colonia,
                        $request->Poblacion,
                        $request->Estado,
                        $request->Pais,
                        $request->CodigoPostal,
                        $DireccionFiscal,
                        $DireccionFiscalNumero,
                        $DireccionFiscalNumeroInt,
                        $AlcaldiaFiscal,
                        $ColoniaFiscal,
                        $PoblacionFiscal,
                        $EstadoFiscal,
                        $PaisFiscal,
                        $CPFiscal,
                        $RFC,
                        $RIT,
                        $request->Lada1,
                        $request->Telefonos,
                        $request->Extencion1,
                        $request->Lada2,
                        $request->Telefonos2,
                        $request->Extencion2,
                        $request->DirInternet,
                        $request->eMail1,
                        '',
                        '',
                        $CreditoCondiciones,
                        $FiscalRegimen,
                        $request->Giro,
                        $request->SubGiro,
                        $UsoCFDI,
                        $buro,
                        $top,
                        $fecha_top,
                        $idUsuario,
                        $fecha->format('Y-m-d H:i:s'),
                        $request->idSituacionA,
                        $request->fecAniversario,
                        0,
                        0,
                        $credito_especial,
                        $credito_con_condiciones,
                        $urlPortal,
                        $usuarioPortal,
                        $cvePortal,
                        $manualPortal,
                        $LimiteCredito,
                        $CreditoLimitePedidos,
                        $LimiteDeCredito,
                        $diasRevision,
                        $idMetodoRevision,
                        $idEntregaComplementoPago,
                        $bloquear_morosos,
                        $addenda_especial,
                        $idUsuarioAutCredito,
                        $fecha_aut_cre,
                        $empresaweb,
                        $WebUserID,
                        $request->idClienteINT,
                        $puedeDescargarPresupuesto
                    ]
                );
    
                if ($prospecto != null) {
                    $contacto_principal = v_catContactos::where('idCliente', $id_cliente)->where('idContactoINT', $cliente[0]->idContactoPrincipal)->orderBy('idContactoINT', 'DESC')->first();
                    $contacto_cxp = v_catContactos::where('idCliente', $id_cliente)->where('contactoCXP', 1)->orderBy('idContactoINT', 'DESC')->first();
                    $contacto_cfdi = v_catContactos::where('idCliente', $id_cliente)->where('CFD_Enviar', 1)->orderBy('idContactoINT', 'DESC')->first();
    
                    if (count($contacto_principal) == 0) {
                        $tratamiento_principal = null;
                        $nombre_principal = null;
                        $cargo_principal = null;
                        $departamento_principal = null;
                        $telefono_principal = null;
                        $email_principal = null;
                    } else {
                        $tratamiento_principal = $contacto_principal->Tratamiento;
                        $nombre_principal = $contacto_principal->Nombre;
                        $cargo_principal = $contacto_principal->Cargo;
                        $departamento_principal = $contacto_principal->Departamento;
                        $telefono_principal = $contacto_principal->Telefonos;
                        $email_principal = $contacto_principal->email;
                    }
    
                    if (count($contacto_cxp) == 0) {
                        $tratamiento_cxp = null;
                        $nombre_cxp = null;
                        $cargo_cxp = null;
                        $departamento_cxp = null;
                        $telefono_cxp = null;
                        $email_cxp = null;
                    } else {
                        $tratamiento_cxp = $contacto_cxp->Tratamiento;
                        $nombre_cxp = $contacto_cxp->Nombre;
                        $cargo_cxp = $contacto_cxp->Cargo;
                        $departamento_cxp = $contacto_cxp->Departamento;
                        $telefono_cxp = $contacto_cxp->Telefonos;
                        $email_cxp = $contacto_cxp->email;
                    }
    
                    if (count($contacto_cfdi) == 0) {
                        $tratamiento_cfdi = null;
                        $nombre_cfdi = null;
                        $cargo_cfdi = null;
                        $departamento_cfdi = null;
                        $telefono_cfdi = null;
                        $email_cfdi = null;
                    } else {
                        $tratamiento_cfdi = $contacto_cfdi->Tratamiento;
                        $nombre_cfdi = $contacto_cfdi->Nombre;
                        $cargo_cfdi = $contacto_cfdi->Cargo;
                        $departamento_cfdi = $contacto_cfdi->Departamento;
                        $telefono_cfdi = $contacto_cfdi->Telefonos;
                        $email_cfdi = $contacto_cfdi->email;
                    }
    
                    DB::statement(
                        'exec spUpdateProspecto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                            ?, ?, ?, ?, ?, ?, ?, ?, ?',
                        [
                            $request->Agente,
                            $request->razonsocial,
                            $request->LITONombreComercial,
                            strtoupper($request->Direccion),
                            strtoupper($request->DireccionNumero),
                            strtoupper($request->DireccionNumeroInt),
                            strtoupper($request->Alcaldia),
                            strtoupper($request->Colonia),
                            strtoupper($request->Poblacion),
                            strtoupper($request->Estado),
                            $request->Pais,
                            $request->CodigoPostal,
                            strtoupper($DireccionFiscal),
                            strtoupper($DireccionFiscalNumero),
                            strtoupper($DireccionFiscalNumeroInt),
                            strtoupper($AlcaldiaFiscal),
                            strtoupper($ColoniaFiscal),
                            strtoupper($PoblacionFiscal),
                            strtoupper($EstadoFiscal),
                            $PaisFiscal,
                            $CPFiscal,
                            strtoupper($RFC),
                            $RIT,
                            strtoupper($request->Lada1),
                            strtoupper($request->Telefonos),
                            strtoupper($request->Extencion1),
                            strtoupper($request->Lada2),
                            strtoupper($request->Telefonos2),
                            strtoupper($request->Extencion2),
                            strtoupper($request->DirInternet),
                            strtolower($request->eMail1),
                            $CreditoCondiciones,
                            $FiscalRegimen,
                            $request->Giro,
                            $request->SubGiro,
                            $UsoCFDI,
                            $idUsuario,
                            $fecha->format('Y-m-d H:i:s'),
                            // $request->idSituacionA,
                            // $request->fecAniversario,
                            0,
                            0,
                            $credito_especial,
                            $credito_con_condiciones,
                            strtoupper($urlPortal),
                            $usuarioPortal,
                            $cvePortal,
                            $manualPortal,
                            $LimiteCredito,
                            $CreditoLimitePedidos,
                            $LimiteDeCredito,
                            strtoupper($diasRevision),
                            $idMetodoRevision,
                            $idEntregaComplementoPago,
                            $bloquear_morosos,
                            $addenda_especial,
                            $idUsuarioAutCredito,
                            $fecha_aut_cre,
                            $prospecto->idProspecto,
                            strtoupper($tratamiento_principal),
                            strtoupper($nombre_principal),
                            strtoupper($cargo_principal),
                            strtoupper($departamento_principal),
                            strtoupper($telefono_principal),
                            strtolower($email_principal),
    
                            strtoupper($tratamiento_cxp),
                            strtoupper($nombre_cxp),
                            strtoupper($cargo_cxp),
                            strtoupper($departamento_cxp),
                            strtoupper($telefono_cxp),
                            strtolower($email_cxp),
    
                            strtoupper($tratamiento_cfdi),
                            strtoupper($nombre_cfdi),
                            strtoupper($cargo_cfdi),
                            strtoupper($departamento_cfdi),
                            strtoupper($telefono_cfdi),
                            strtolower($email_cfdi),
                        ]
                    );
                }
            }
        } else {
            DB::statement(
                'exec spUpdateCliente ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $request->Agente,
                    $request->AgenteSecundario,
                    $request->razonsocial,
                    $request->LITONombreComercial,
                    $request->Direccion,
                    $request->DireccionNumero,
                    $request->DireccionNumeroInt,
                    $request->Alcaldia,
                    $request->Colonia,
                    $request->Poblacion,
                    $request->Estado,
                    $request->Pais,
                    $request->CodigoPostal,
                    $DireccionFiscal,
                    $DireccionFiscalNumero,
                    $DireccionFiscalNumeroInt,
                    $AlcaldiaFiscal,
                    $ColoniaFiscal,
                    $PoblacionFiscal,
                    $EstadoFiscal,
                    $PaisFiscal,
                    $CPFiscal,
                    $RFC,
                    $RIT,
                    $request->Lada1,
                    $request->Telefonos,
                    $request->Extencion1,
                    $request->Lada2,
                    $request->Telefonos2,
                    $request->Extencion2,
                    $request->DirInternet,
                    $request->eMail1,
                    '',
                    '',
                    $CreditoCondiciones,
                    $FiscalRegimen,
                    $request->Giro,
                    $request->SubGiro,
                    $UsoCFDI,
                    $buro,
                    $top,
                    $fecha_top,
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    $request->idSituacionA,
                    $request->fecAniversario,
                    0,
                    0,
                    $credito_especial,
                    $credito_con_condiciones,
                    $urlPortal,
                    $usuarioPortal,
                    $cvePortal,
                    $manualPortal,
                    $LimiteCredito,
                    $CreditoLimitePedidos,
                    $LimiteDeCredito,
                    $diasRevision,
                    $idMetodoRevision,
                    $idEntregaComplementoPago,
                    $bloquear_morosos,
                    $addenda_especial,
                    $idUsuarioAutCredito,
                    $fecha_aut_cre,
                    $empresaweb,
                    $WebUserID,
                    $request->idClienteINT,
                    $puedeDescargarPresupuesto
                ]
            );

            if ($prospecto != null) {
                $contacto_principal = v_catContactos::where('idCliente', $id_cliente)->where('idContactoINT', $cliente[0]->idContactoPrincipal)->orderBy('idContactoINT', 'DESC')->first();
                $contacto_cxp = v_catContactos::where('idCliente', $id_cliente)->where('contactoCXP', 1)->orderBy('idContactoINT', 'DESC')->first();
                $contacto_cfdi = v_catContactos::where('idCliente', $id_cliente)->where('CFD_Enviar', 1)->orderBy('idContactoINT', 'DESC')->first();

                if (count($contacto_principal) == 0) {
                    $tratamiento_principal = null;
                    $nombre_principal = null;
                    $cargo_principal = null;
                    $departamento_principal = null;
                    $telefono_principal = null;
                    $email_principal = null;
                } else {
                    $tratamiento_principal = $contacto_principal->Tratamiento;
                    $nombre_principal = $contacto_principal->Nombre;
                    $cargo_principal = $contacto_principal->Cargo;
                    $departamento_principal = $contacto_principal->Departamento;
                    $telefono_principal = $contacto_principal->Telefonos;
                    $email_principal = $contacto_principal->email;
                }

                if (count($contacto_cxp) == 0) {
                    $tratamiento_cxp = null;
                    $nombre_cxp = null;
                    $cargo_cxp = null;
                    $departamento_cxp = null;
                    $telefono_cxp = null;
                    $email_cxp = null;
                } else {
                    $tratamiento_cxp = $contacto_cxp->Tratamiento;
                    $nombre_cxp = $contacto_cxp->Nombre;
                    $cargo_cxp = $contacto_cxp->Cargo;
                    $departamento_cxp = $contacto_cxp->Departamento;
                    $telefono_cxp = $contacto_cxp->Telefonos;
                    $email_cxp = $contacto_cxp->email;
                }

                if (count($contacto_cfdi) == 0) {
                    $tratamiento_cfdi = null;
                    $nombre_cfdi = null;
                    $cargo_cfdi = null;
                    $departamento_cfdi = null;
                    $telefono_cfdi = null;
                    $email_cfdi = null;
                } else {
                    $tratamiento_cfdi = $contacto_cfdi->Tratamiento;
                    $nombre_cfdi = $contacto_cfdi->Nombre;
                    $cargo_cfdi = $contacto_cfdi->Cargo;
                    $departamento_cfdi = $contacto_cfdi->Departamento;
                    $telefono_cfdi = $contacto_cfdi->Telefonos;
                    $email_cfdi = $contacto_cfdi->email;
                }

                DB::statement(
                    'exec spUpdateProspecto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?',
                    [
                        $request->Agente,
                        $request->razonsocial,
                        $request->LITONombreComercial,
                        strtoupper($request->Direccion),
                        strtoupper($request->DireccionNumero),
                        strtoupper($request->DireccionNumeroInt),
                        strtoupper($request->Alcaldia),
                        strtoupper($request->Colonia),
                        strtoupper($request->Poblacion),
                        strtoupper($request->Estado),
                        $request->Pais,
                        $request->CodigoPostal,
                        strtoupper($DireccionFiscal),
                        strtoupper($DireccionFiscalNumero),
                        strtoupper($DireccionFiscalNumeroInt),
                        strtoupper($AlcaldiaFiscal),
                        strtoupper($ColoniaFiscal),
                        strtoupper($PoblacionFiscal),
                        strtoupper($EstadoFiscal),
                        $PaisFiscal,
                        $CPFiscal,
                        strtoupper($RFC),
                        $RIT,
                        strtoupper($request->Lada1),
                        strtoupper($request->Telefonos),
                        strtoupper($request->Extencion1),
                        strtoupper($request->Lada2),
                        strtoupper($request->Telefonos2),
                        strtoupper($request->Extencion2),
                        strtoupper($request->DirInternet),
                        strtolower($request->eMail1),
                        $CreditoCondiciones,
                        $FiscalRegimen,
                        $request->Giro,
                        $request->SubGiro,
                        $UsoCFDI,
                        $idUsuario,
                        $fecha->format('Y-m-d H:i:s'),
                        // $request->idSituacionA,
                        // $request->fecAniversario,
                        0,
                        0,
                        $credito_especial,
                        $credito_con_condiciones,
                        strtoupper($urlPortal),
                        $usuarioPortal,
                        $cvePortal,
                        $manualPortal,
                        $LimiteCredito,
                        $CreditoLimitePedidos,
                        $LimiteDeCredito,
                        strtoupper($diasRevision),
                        $idMetodoRevision,
                        $idEntregaComplementoPago,
                        $bloquear_morosos,
                        $addenda_especial,
                        $idUsuarioAutCredito,
                        $fecha_aut_cre,
                        $prospecto->idProspecto,
                        strtoupper($tratamiento_principal),
                        strtoupper($nombre_principal),
                        strtoupper($cargo_principal),
                        strtoupper($departamento_principal),
                        strtoupper($telefono_principal),
                        strtolower($email_principal),

                        strtoupper($tratamiento_cxp),
                        strtoupper($nombre_cxp),
                        strtoupper($cargo_cxp),
                        strtoupper($departamento_cxp),
                        strtoupper($telefono_cxp),
                        strtolower($email_cxp),

                        strtoupper($tratamiento_cfdi),
                        strtoupper($nombre_cfdi),
                        strtoupper($cargo_cfdi),
                        strtoupper($departamento_cfdi),
                        strtoupper($telefono_cfdi),
                        strtolower($email_cfdi),
                    ]
                );
            }
        }

        $id_cliente_int = $request->idClienteINT;

        $requisitos_cliente = [];

        $p = count($requisitosPedido);

        $requisitos_lista = catRequisitos::where('estatusRequisito', 1)->get();

        $r = count($requisitos_lista);

        for ($i = 0; $i < $r; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitos_lista[$i]->idRequisito)->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitos_lista[$i]->idRequisito)->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int,
                'idRequisito' => $descRequisito->idRequisito,
                'descRequisito' => $descRequisito->descRequisito,
                'Factura' => 0,
                'Entrega' => 0,
                'CXC' => 0,
            ];
            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        for ($i = 0; $i < $p; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitosPedido[$i])->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitosPedido[$i])->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int,
                'idRequisito' => $requisitosPedido[$i],
                'descRequisito' => $descRequisito->descRequisito,
                'Factura' => 1,
            ];

            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        $c = count($requisitosEntrega);

        for ($i = 0; $i < $c; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitosEntrega[$i])->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitosEntrega[$i])->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int,
                'idRequisito' => $requisitosEntrega[$i],
                'descRequisito' => $descRequisito->descRequisito,
                'Entrega' => 1,
            ];
            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        $cc = count($requisitosCobro);

        for ($i = 0; $i < $cc; $i++) {
            $requisito_existe = ClienteRequisitos::where('idCliente', $id_cliente_int)->where('idRequisito', $requisitosCobro[$i])->first();
            $descRequisito = $requisitos_lista->where('idRequisito', $requisitosCobro[$i])->first();
            $requisitos_cliente = [
                'idCliente' => $id_cliente_int,
                'idRequisito' => $requisitosCobro[$i],
                'descRequisito' => $descRequisito->descRequisito,
                'CXC' => 1,
            ];
            if ($requisito_existe == null) {
                ClienteRequisitos::create($requisitos_cliente);
            } else {
                $requisito_existe->update($requisitos_cliente);
            }
        }

        $cte_cfd_exist = DB::select('exec GetClienteCFD @ID=' . $id_cliente_int);

        if (count($cte_cfd_exist) == 0) {
            DB::statement(
                'exec spUpdateClienteCFD ?',
                [
                    $id_cliente_int,
                ]
            );
        }

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $request->idClienteINT);

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($cliente[0], true)));

        $this->bitacora($this->modulo, 'Editar ', 'Cliente / ' . $cadena . ' / ', $id_cliente_int, $this->loginUsuario(), 2);

        return redirect()->route('Clientes');
    }

    public function apiClientes()
    {
        $clientesAll = Clientes::all();

        $id_usuario = $this->idUsuario();

        $clientes = Clientes::where('razonsocial', '!=', null)->where('ClienteINT', '!=', null)->limit(count($clientesAll));

        $agenteActual = $this->datosUsuario()[0]->Agente;

        $permisos = $this->permisosModulo($this->modulo);

        $role = ($permisos != null) ? $permisos[0]->idRol : null;

        return DataTables::of($clientes)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editar'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminar'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
            })
            ->addColumn('inputLogin', function () {
                $sesionLogin = trim(session('usuario'));
                $inputLogin = $sesionLogin;

                return $inputLogin;
            })
            ->addColumn('TblComNum', function ($row) {
                return $row->TablaComision;
            })
            ->addColumn('inputRol', function () use ($id_usuario, $role) {
                if ($id_usuario == 36) {
                    $sesionRol = $role;
                } else {
                    $sesionRol = session('rol');
                }

                $inputRol = $sesionRol;

                return $inputRol;
            })
            ->addColumn('idUsuario', function () use ($id_usuario) {
                $idUsuario = $id_usuario;

                return $idUsuario;
            })
            ->addColumn('agenteActualLogin', function () use ($agenteActual) {
                return $agenteActual;
            })
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol', 'idUsuario', 'agenteActualLogin'])
            ->make(true);
    }

    public function codigosAjax(Request $request)
    {
        $codigos = [];
        if ($request->has('q')) {
            $search = $request->q;
            $codigos = CodigosPostales::select("Codigo", "idCodigo")
                ->where('idCodigo', 'LIKE', "%$search%")
                ->get();
        }
        return response()->json($codigos);
    }

    public function find(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $autocomplate = CodigosPostales::orderby('idCodigo', 'asc')->select('idCodigo')->get();
        } else {
            $autocomplate = CodigosPostales::orderby('idCodigo', 'asc')->select('idCodigo')->where('idCodigo', 'like', $search . '%')->get();
        }

        $response = array();
        foreach ($autocomplate as $autocomplate) {
            $response[] = array("value" => $autocomplate->idCodigo, "label" => $autocomplate->idCodigo);
        }

        echo json_encode($response);
        exit;
    }

    public function darDeBaja(Request $request)
    {
        $idUsuario = $this->idUsuario();

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $request->id);

        $fecha = new \DateTime();

        $estatus = $request->estatus;

        if (trim($cliente[0]->Estatus) === "BAJA") {
            $estatus = "ALTA";
        } else {
            $estatus = "BAJA";
        }

        DB::statement(
            'exec spDeleteCliente ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $estatus,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, 'Eliminar ', 'Cliente ', $request->id, $this->loginUsuario(), 3);
    }

    public function actualizarBuro(Request $request)
    {
        $idUsuario = $this->idUsuario();

        ClienteRequisitos::where('idCliente', $request->id)->delete();

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $request->id);

        $fecha = new \DateTime();

        $buro = $request->conBuroC;

        if (trim($cliente[0]->conBuroC) == 0) {
            $buro = 1;
        } else {
            $buro = 0;
        }

        DB::statement(
            'exec spBuroCliente ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $buro,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, 'Actualizar Buró ', 'Cliente ', $request->id, $this->loginUsuario(), 2);
    }

    public function actualizarTop(Request $request)
    {
        $idUsuario = $this->idUsuario();

        $cliente = DB::select('exec GetClienteXId @idClienteINT=' . $request->id);

        $fecha = new \DateTime();

        $top = $request->esTop;

        $fecTop = $fecha;

        if (trim($cliente[0]->esTop) == 0) {
            $top = 1;
        } else {
            $top = 0;
            $fecTop = null;
        }

        DB::statement(
            'exec spTopCliente ?, ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $top,
                $fecTop,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, 'Actualizar Top ', 'Cliente ', $request->id, $this->loginUsuario(), 2);
    }

    public function convertirEnProspecto(Request $request)
    {
        $idUsuario = $this->idUsuario();
        $fecha = new \DateTime();
        $prospecto = $request->check;

        DB::statement(
            'exec spConvertirEnProspecto ?, ?',
            [
                $prospecto,
                $request->id,
            ]
        );

        $id_prospecto = v_Prospectos::where('idCliente', $request->id)->select('idProspecto')->first();
        $idProspecto = DB::table('Prospectos')->max('NumeroProspecto') + 1;

        if ($id_prospecto == null) {
            $cliente = v_catClientes::where('idCliente', $request->id)->first();
            $contacto_principal = v_catContactos::where('idCliente', $request->id)->where('idContactoINT', $cliente->idContactoPrincipal)->orderBy('idContactoINT', 'DESC')->first();
            $contacto_cxp = v_catContactos::where('idCliente', $request->id)->where('contactoCXP', 1)->orderBy('idContactoINT', 'DESC')->first();
            $contacto_cfdi = v_catContactos::where('idCliente', $request->id)->where('CFD_Enviar', 1)->orderBy('idContactoINT', 'DESC')->first();

            if (count($contacto_principal) == 0) {
                $tratamiento_principal = null;
                $nombre_principal = null;
                $cargo_principal = null;
                $departamento_principal = null;
                $telefono_principal = null;
                $email_principal = null;
            } else {
                $tratamiento_principal = $contacto_principal->Tratamiento;
                $nombre_principal = $contacto_principal->Nombre;
                $cargo_principal = $contacto_principal->Cargo;
                $departamento_principal = $contacto_principal->Departamento;
                $telefono_principal = $contacto_principal->Telefonos;
                $email_principal = $contacto_principal->email;
            }

            if (count($contacto_cxp) == 0) {
                $tratamiento_cxp = null;
                $nombre_cxp = null;
                $cargo_cxp = null;
                $departamento_cxp = null;
                $telefono_cxp = null;
                $email_cxp = null;
            } else {
                $tratamiento_cxp = $contacto_cxp->Tratamiento;
                $nombre_cxp = $contacto_cxp->Nombre;
                $cargo_cxp = $contacto_cxp->Cargo;
                $departamento_cxp = $contacto_cxp->Departamento;
                $telefono_cxp = $contacto_cxp->Telefonos;
                $email_cxp = $contacto_cxp->email;
            }

            if (count($contacto_cfdi) == 0) {
                $tratamiento_cfdi = null;
                $nombre_cfdi = null;
                $cargo_cfdi = null;
                $departamento_cfdi = null;
                $telefono_cfdi = null;
                $email_cfdi = null;
            } else {
                $tratamiento_cfdi = $contacto_cfdi->Tratamiento;
                $nombre_cfdi = $contacto_cfdi->Nombre;
                $cargo_cfdi = $contacto_cfdi->Cargo;
                $departamento_cfdi = $contacto_cfdi->Departamento;
                $telefono_cfdi = $contacto_cfdi->Telefonos;
                $email_cfdi = $contacto_cfdi->email;
            }

            // return $contacto_cfdi;
            $credito_especial = 0;
            $credito_con_condiciones = 0;

            if ($cliente->CreditoCondiciones != "") {
                $credito_especial = 1;
                $credito_con_condiciones = 1;
            }

            DB::statement(
                'exec spInsertProspectoCliente ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    $cliente->razonsocial,
                    $cliente->LITONombreComercial,
                    $cliente->DirInternet,
                    $cliente->Agente,
                    $cliente->Lada1,
                    $cliente->Telefonos,
                    $cliente->Extencion1,
                    $cliente->Lada2,
                    $cliente->Telefonos2,
                    $cliente->Extencion2,
                    $cliente->Giro,
                    $cliente->SubGiro,
                    $cliente->Direccion,
                    $cliente->DireccionNumero,
                    $cliente->DireccionNumeroInt,
                    $cliente->Alcaldia,
                    $cliente->Colonia,
                    $cliente->Poblacion,
                    $cliente->Estado,
                    $cliente->Pais,
                    $cliente->CodigoPostal,
                    $idProspecto,
                    $cliente->eMail1,
                    $tratamiento_principal,
                    $nombre_principal,
                    $cargo_principal,
                    $departamento_principal,
                    $telefono_principal,
                    $email_principal,
                    $tratamiento_cxp,
                    $nombre_cxp,
                    $cargo_cxp,
                    $departamento_cxp,
                    $telefono_cxp,
                    $email_cxp,
                    $tratamiento_cfdi,
                    $nombre_cfdi,
                    $cargo_cfdi,
                    $departamento_cfdi,
                    $telefono_cfdi,
                    $email_cfdi,
                    $cliente->DirecccionFiscal,
                    $cliente->DireccionFiscalNumero,
                    $cliente->DireccionFiscalNumeroInt,
                    $cliente->AlcaldiaFiscal,
                    $cliente->ColoniaFiscal,
                    $cliente->PoblacionFiscal,
                    $cliente->EstadoFiscal,
                    $cliente->PaisFiscal,
                    $cliente->CPFiscal,
                    $cliente->RFC,
                    $cliente->NumRegIdTrib,
                    $cliente->CreditoCondiciones,
                    $cliente->FiscalRegimen,
                    0,
                    0,
                    $cliente->ClaveUsoCFDI,
                    $credito_especial,
                    $credito_con_condiciones,
                    $cliente->urlPortal,
                    $cliente->usuarioPortal,
                    $cliente->cvePortal,
                    $cliente->manualPortal,
                    $cliente->LimiteCredito,
                    $cliente->CreditoLimite,
                    $cliente->LimiteDeCredito,
                    $cliente->diasRevision,
                    $cliente->idMetodoRevision,
                    $cliente->idEntregaComplementoPago,
                    $cliente->BloquearMorosos,
                    $cliente->addendaEspecial,
                    $cliente->idUsuarioAutLimiteCredito,
                    $cliente->fecModificacionLimiteCredito,
                    $cliente->idCliente,
                ]
            );
        } else {
            DB::statement('exec spEsCliente ?,?', [
                0,
                $id_prospecto->idProspecto,
            ]);
        }

        $this->bitacora($this->modulo, 'Convertir Prospecto ', 'Cliente ', $request->id, $this->loginUsuario(), 2);
    }

    public function apiCtesDocs(Request $request)
    {
        if ($request->idCliente == null || $request->idCliente == 0) {
            $idCliente = $request->idProspecto;
        } else {
            $idCliente = $request->idCliente;
        }

        $Documentos = DB::select('exec sp_Documentos @evento="DocumentosCliente", @id=' . $idCliente);

        return DataTables::of($Documentos)
            ->make(true);
    }
    public function downdocsclientes($id)
    {
        $result = DocumentosCtes::where('idDocumento', (int) $id)->first();
        $file = $result->Documento;
        $Tipo = $result->Extension;
        $Extencion = explode('/', $Tipo);
        $base64 = base64_decode($file);
        return Response::make($base64, 200, [
            'Content-Type' => $Tipo, 'Content-Disposition' => 'attachment; filename="' . $result->descDocumento . '"',
        ]);
    }

    public function deletedocctes(Request $request)
    {
        $idDocumentodelete = $request->idDocumentodelete;
        $idDocumentodelete = (int) $idDocumentodelete;

        $documento = relCliente_Documento::where('idDocumento', $idDocumentodelete)->first();

        DocumentosCtes::where('idDocumento', $idDocumentodelete)->delete();
        DB::table('relCliente_Documento')->where('idDocumento', $idDocumentodelete)->delete();

        $this->bitacora($this->modulo, 'Eliminar Documento ' . $documento->descDocumento . ' Tipo Documento ' . $documento->descTipoDocumento, ' Cliente ', $documento->idCliente, $this->loginUsuario(), 3);
    }

    public function updocctes(Request $request)
    {
        // return $request;

        $idUsuarioReg = (int) $this->idUsuario();
        if ($request->idCliente == 0) {
            $idCliente = $request->idProspecto;
        } else {
            $idCliente = $request->idCliente;
        }
        $idProspecto = $request->idProspecto;
        $idCliente = (int) $idCliente;
        $idProspecto = (int) $idProspecto;
        $idTipoDocumento = $request->idTipoDocumento;
        $idTipoDocumento = (int) $idTipoDocumento;
        $file = $_FILES['file'];
        $Extencion = $file['type'];
        $Size = $file['size'];
        $descDocumento = $file['name'];
        $archivo = fopen($_FILES["file"]["tmp_name"], 'r');
        $Documento = base64_encode(file_get_contents($_FILES["file"]["tmp_name"]));

        // try{
        DB::statement(DB::raw(" EXEC sp_updateClientesDocs :descDocumento,:Documento, :Extencion,:Size,:idUsuarioReg,:idCliente,:idProspecto,:idTipoDocumento"), [
            ':descDocumento' => $descDocumento,
            ':Documento' => $Documento,
            ':Extencion' => $Extencion,
            ':Size' => $Size,
            ':idUsuarioReg' => $idUsuarioReg,
            ':idCliente' => $idCliente,
            ':idProspecto' => $idProspecto,
            ':idTipoDocumento' => $idTipoDocumento,
        ]);

        $tipo_documento = catTipoDocumentoOport::where('idTipoDocumento', $idTipoDocumento)->first();

        $this->bitacora($this->modulo, 'Agregar Documento ' . (string) $descDocumento . ' Tipo Documento ' . $tipo_documento->descTipoDocumento, ' Cliente ', $request->idCliente, $this->loginUsuario(), 5);
    }

    public function GetAgentes()
    {
        $agentes = DB::select('exec GetAgentesComboCliente');
        return $agentes;
    }

    public function cambiarAgente(Request $request)
    {

        $idUsuario = $this->idUsuario();

        $agentes = DB::select('exec GetAgentesComboCliente');

        $agente = $agentes[$request->agente]->agente;

        $fecha = new \DateTime();

        DB::statement(
            'exec spCambiarAgente ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $agente,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, 'Cambiar Agente ', 'Cliente ', $request->id, $this->loginUsuario(), 2);
    }

    public function tblClientesOport($ClienteINT, Request $request)
    {
        $Login = $this->loginUsuario();
        $permisos = $this->permisosModulo($this->modulo);
        session(['rol' => $permisos[0]->idRol]);
        // $rol = session('rol');
        if ($request->tipo == 1) {
            $tblOportunidades = DB::table('v_OportunidadesGrid')->where('ClienteINT', $ClienteINT);
        } else {
            $tblOportunidades = DB::table('v_OportunidadesGrid')->where('idProspecto', $ClienteINT)->where('prospecto', 1);
        }


        // $tblOportunidades = DB::select('exec GetOportunidadesXCliente @idClienteINT=' . $ClienteINT . ',@tipo=' . $request->tipo);
        return DataTables::of($tblOportunidades)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editarOpor'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminarOpor'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
            })
            ->addColumn('inputEditarCliente', function () {
                $sesionEditarCliente = trim(session('editar'));
                $inputEditarCliente = $sesionEditarCliente;

                return $inputEditarCliente;
            })
            ->addColumn('inputLogin', function () {
                $sesionLogin = trim(session('usuario'));
                $inputLogin = $sesionLogin;

                return $inputLogin;
            })
            ->addColumn('inputRol', function () {
                $sesionRol = session('rol');
                $inputRol = $sesionRol;

                return $inputRol;
            })
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputEditarCliente', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function usuariosAutCredito()
    {
        $login = $this->loginUsuario();
        $usuarios_aut_credito = DB::select('exec GetUsuariosAutCredito @login=' . $login);

        return $usuarios_aut_credito;
    }

    public function getDocumentoCliente(Request $request)
    {
        $idDocumento = $request->idDocumento;

        $result = DocumentosCtes::where('idDocumento', (int) $idDocumento)->first();

        return $result;
    }

    /**
     * Solicitud de actualización de solicitud de presupuesto
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function actualizarSolicitarPresupuesto(Request $request)
    {
        try {
            DB::table('Clientes')->where('idClienteINT', $request->id)
                ->update([
                    'puedeDescargarPresupuesto' => $request->check === "true" ? 1 : 0,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud de presupuesto actualizada correctamente.'
            ]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Lo siento, ocurrió un error al actualizar la solicitud de presupuesto.'
            ]);
        }
    }
}
