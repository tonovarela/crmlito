<?php

namespace App\Http\Controllers;

use App\Http\Modelos\catCtasEntregaComplementosPago;
use App\Http\Modelos\catCtasMetodoRevision;
use App\Http\Modelos\Clientes;
use App\Http\Modelos\Prospectos;
use App\Http\Vistas\v_Prospectos;
use App\Traits\BitacoraTrait;
use App\Traits\PermisosTrait;
use App\v_catGiros;
use App\v_catSubGiros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProspectosController extends Controller
{
    use PermisosTrait;
    use BitacoraTrait;

    public $modulo = 'Prospectos';

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
            $id_prospecto = DB::table('Prospectos')->max('NumeroProspecto') + 1;
            $agentes = DB::select('exec GetAgentesComboCliente');
            $cat_giros = v_catGiros::all();
            $cat_subgiros = v_catSubGiros::all();
            $paises = DB::select('exec GetPaisesCombo');
            $permisos_count = count($permisos);
            $idrol = $permisos[0]->idRol;
            $validaciones = DB::table('catValidaciones')->where('estatus', 1)->get();
            $v_Validaciones = DB::table('v_Validaciones')->get();
            $uso_cfdi = DB::select('exec GetClientesUsoCFDI');

            for ($i = 0; $i < $permisos_count; $i++) {

                $crear = session('crear_prospectos');
                $editar = session('editar_prospectos');
                $editarProspectos = session('editarProspectos');

                if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Todo') {
                    return view('prospectos.index', compact('crear', 'editar', 'editarProspectos', 'id_prospecto', 'agentes', 'cat_giros', 'cat_subgiros', 'id_usuario', 'idrol', 'paises', 'validaciones', 'v_Validaciones', 'uso_cfdi'));
                } else if ($permisos[$i]->descAccion == 'AccesoModulo' && $permisos[$i]->descNivelPerm == 'Ninguno') {
                    return redirect()->route('Dashboard');
                }
            }
        // }
    }

    public function store(Request $request)
    {

        // return $request;

        $idUsuario = $this->idUsuario();
        $fecha = new \DateTime();
        $id_prospecto = DB::table('Prospectos')->max('NumeroProspecto') + 1;

        DB::statement(
            'exec spInsertProspecto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $request->razonsocial,
                $request->LITONombreComercial,
                $request->DirInternet,
                $request->Agente,
                $request->Lada1,
                $request->Telefonos,
                $request->Extencion1,
                $request->Lada2,
                $request->Telefonos2,
                $request->Extencion2,
                $request->Giro,
                $request->SubGiro,
                $request->Direccion,
                $request->DireccionNumero,
                $request->DireccionNumeroInt,
                $request->Alcaldia,
                $request->Colonia,
                $request->Poblacion,
                $request->Estado,
                $request->Pais,
                $request->CodigoPostal,
                $id_prospecto,
                $request->eMail1,
                $request->TratamientoContacto,
                $request->NombreContacto,
                $request->CargoContacto,
                $request->DepartamentoContacto,
                $request->Telefonos1Contacto,
                $request->eMailContacto,
                $request->TratamientoContactoCXP,
                $request->NombreContactoCXP,
                $request->CargoContactoCXP,
                $request->DepartamentoContactoCXP,
                $request->Telefonos1ContactoCXP,
                $request->eMailContactoCXP,
                $request->TratamientoContactoCFDI,
                $request->NombreContactoCFDI,
                $request->CargoContactoCFDI,
                $request->DepartamentoContactoCFDI,
                $request->Telefonos1ContactoCFDI,
                $request->eMailContactoCFDI,
                0,
            ]
        );

        $idProspecto = DB::table('Prospectos')->max('NumeroProspecto');
        $validaciones = DB::table('catValidaciones')->where('estatus', 1)->get();

        if ($request->proveedorTrabajaQuien != null || $request->proveedorTrabajaQuien != '') {
            DB::statement('exec spInsertValidacion ?,?,?,?', [
                $idProspecto,
                29,
                0,
                $request->proveedorTrabajaQuien
            ]);
        }

        for ($i = 0; $i < count($validaciones); $i++) {
            if ($request->clienteDirecto == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->empresaTrasnacional == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->tipoEmpresa == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->medioContacto == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->impresionComercial == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->empaque == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->display == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->bolsas == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->materialPop == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->medioPresentacion == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->vecesVisitado == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->proveedorActual == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->proveedorActualRespuesta == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->proveedorTrabaja == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->tiempoProyecto == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->impresionComercialCot == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->empaqueCot == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->displayCot == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->bolsasCot == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->materialPopCot == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->maquilaCot == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->frecuanciaCompra == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
            if ($request->diasCredit == $validaciones[$i]->idCatValidacion) {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $idProspecto,
                    $validaciones[$i]->idCatValidacion,
                    $validaciones[$i]->porcentaje,
                    null
                ]);
            }
        }

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($request->all(), true)));
        $this->bitacora($this->modulo, 'Agregar ', 'Candidato | ' . $cadena . ' | ', $id_prospecto, $this->loginUsuario(), 5);
        return redirect()->route('Candidatos');
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
            $prospecto = v_Prospectos::where('idProspecto', $id)->first();
            $agentes = DB::select('exec GetAgentesComboCliente');
            $agente_id = $agentes[0]->idAgente;
            $cat_giros = v_catGiros::all();
            $cat_subgiros = v_catSubGiros::all();
            $paises = DB::select('exec GetPaisesCombo');
            $idrol = $permisos[0]->idRol;
            $rol = $permisos[0]->idRol;
            $editarProspectos = session('editarProspectos');

            $validaciones = DB::table('catValidaciones')->where('estatus', 1)->get();
            $v_Validaciones = DB::table('v_Validaciones')->get();
            $v_ProspectosValidacion = DB::table('v_ProspectosValidacion')->where('idProspecto', $id)->get();

            $cat_condiciones = DB::select('exec GetCondicionesCliente');
            $cat_regimen = DB::select('exec GetRegimenCliente');
            $cat_metodo_revision = catCtasMetodoRevision::all();
            $cat_ent_compl_pago = catCtasEntregaComplementosPago::all();
            $uso_cfdi = DB::select('exec GetClientesUsoCFDI');
            // $clientes = DB::table('Clientes')->where('idClienteINT', $id)->get();
            // $id_cliente = $clientes[0]->idCliente;
            $combo_clientes = Prospectos::orderBy('razonsocial', 'asc')->where('idProspecto', $id)->get();
            // return $combo_clientes;
            $direcciones = DB::table('catDireccionLlamadas')->get();
            $tipoRepeticionLlamadas = DB::table('catTipoRepeticionLlamadas')->get();
            $horasRecordatorio = DB::table('catHoraRecordatorioLlamadas')->get();
            // $llamadas_reuniones = DB::table('v_LlamadasReunionesXCliente')->where('id_relacionadoCon', $id_cliente)->orderBy('fecCreacion')->get();
            $llamadas_reuniones = DB::table('v_LlamadasReunionesXCliente')->where('id_relacionadoCon', $id)->where('prospecto', 1)->orderBy('fecCreacion')->get();
            $creaOportunidades = $prospecto->Oportunidades;
            $prospectoActivo = $prospecto->Activo;

            // return $editarProspectos;
            foreach ($permisos as $permiso) {
                if ($editarProspectos == 'EditarProspectos') {
                    return view('prospectos.edit', compact('prospecto', 'agentes', 'cat_giros', 'cat_subgiros', 'id_usuario', 'idrol', 'paises', 'validaciones', 'v_Validaciones', 'v_ProspectosValidacion', 'cat_condiciones', 'cat_regimen', 'cat_metodo_revision', 'cat_ent_compl_pago', 'uso_cfdi', 'llamadas_reuniones', 'rol', 'agente_id', 'combo_clientes', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'creaOportunidades', 'prospectoActivo'));
                } else {
                    if ($Login == $prospecto->Login || $Login == $prospecto->LoginAuxiliar || $Login == $prospecto->LoginAuxiliar2 || $Login == $prospecto->LoginAuxiliar3 || $Login == $prospecto->LoginAuxiliar4) {
                        if ($permiso->descAccion == 'Editar' && $permiso->descNivelPerm == 'Todo') {
                            return view('prospectos.edit', compact('prospecto', 'agentes', 'cat_giros', 'cat_subgiros', 'id_usuario', 'idrol', 'paises', 'validaciones', 'v_Validaciones', 'v_ProspectosValidacion', 'cat_condiciones', 'cat_regimen', 'cat_metodo_revision', 'cat_ent_compl_pago', 'uso_cfdi', 'llamadas_reuniones', 'rol', 'agente_id', 'combo_clientes', 'direcciones', 'tipoRepeticionLlamadas', 'horasRecordatorio', 'creaOportunidades', 'prospectoActivo'));
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
        $fecha = new \DateTime();
        $id_prospecto = $request->idProspecto;
        $prospecto = Prospectos::where('idProspecto', $id_prospecto)->first();
        $permisos = $this->permisosModulo($this->modulo);
        $idrol = $permisos[0]->idRol;
        $credito_especial = 0;
        $credito_con_condiciones = 0;
        $bloquear_morosos = 'NO';
        $fecha_aut_cre = null;
        $addenda_especial = 0;

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
            $LimiteCredito = $prospecto->CreditoLimite;
            $CreditoLimitePedidos = $prospecto->LimiteCredito;
            $LimiteDeCredito = $prospecto->LimiteDeCredito;
            $DireccionFiscal = $prospecto->DirecccionFiscal;
            $DireccionFiscalNumero = $prospecto->DireccionFiscalNumero;
            $DireccionFiscalNumeroInt = $prospecto->DireccionFiscalNumeroInt;
            $AlcaldiaFiscal = $prospecto->AlcaldiaFiscal;
            $ColoniaFiscal = $prospecto->ColoniaFiscal;
            $PoblacionFiscal = $prospecto->PoblacionFiscal;
            $EstadoFiscal = $prospecto->EstadoFiscal;
            $PaisFiscal = $prospecto->PaisFiscal;
            $CPFiscal = $prospecto->CPFiscal;
            $RFC = $prospecto->RFC;
            $RIT = $prospecto->NumRegIdTrib;
            $CreditoCondiciones = $prospecto->CreditoCondiciones;
            if ($idrol == 1017 || $idUsuario == 63) {
                $UsoCFDI = $request->ClaveUsoCFDI;
            } else {
                $UsoCFDI = $prospecto->ClaveUsoCFDI;
            }

            if ($CreditoCondiciones != "") {
                $credito_especial = 1;
                $credito_con_condiciones = 1;
            }

            $FiscalRegimen = $prospecto->FiscalRegimen;
            $urlPortal = $prospecto->urlPortal;
            $usuarioPortal = $prospecto->usuarioPortal;
            $cvePortal = $prospecto->cvePortal;
            $manualPortal = $prospecto->manualPortal;
            $bloquear_morosos = $prospecto->BloquearMorosos;
            $diasRevision = $prospecto->diasRevision;
            $idMetodoRevision = $prospecto->idMetodoRevision;
            $idEntregaComplementoPago = $prospecto->idEntregaComplementoPago;
            $addenda_especial = $prospecto->addendaEspecial;
            $idUsuarioAutCredito = $prospecto->idUsuarioAutLimiteCredito;
            $fecha_aut_cre = $prospecto->fecModificacionLimiteCredito;
        }

        if ($idrol == 1010) {
            $UsoCFDI = $request->ClaveUsoCFDI;
            DB::statement('exec spUpdateClaveUsoCFDI ?,?', [
                $UsoCFDI,
                $request->idProspecto,
            ]);
        } else {
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
                    $request->idProspecto,
                    strtoupper($request->TratamientoContacto),
                    strtoupper($request->NombreContacto),
                    strtoupper($request->CargoContacto),
                    strtoupper($request->DepartamentoContacto),
                    strtoupper($request->Telefonos1Contacto),
                    strtolower($request->eMailContacto),

                    strtoupper($request->TratamientoContactoCXP),
                    strtoupper($request->NombreContactoCXP),
                    strtoupper($request->CargoContactoCXP),
                    strtoupper($request->DepartamentoContactoCXP),
                    strtoupper($request->Telefonos1ContactoCXP),
                    strtolower($request->eMailContactoCXP),

                    strtoupper($request->TratamientoContactoCFDI),
                    strtoupper($request->NombreContactoCFDI),
                    strtoupper($request->CargoContactoCFDI),
                    strtoupper($request->DepartamentoContactoCFDI),
                    strtoupper($request->Telefonos1ContactoCFDI),
                    strtolower($request->eMailContactoCFDI),
                ]
            );

            // return $request;

            $validaciones = DB::table('catValidaciones')->where('estatus', 1)->get();

            DB::table('ProspectosValidacion')->where('idProspecto', $id_prospecto)->delete();

            if ($request->proveedorTrabajaQuien != null || $request->proveedorTrabajaQuien != '') {
                DB::statement('exec spInsertValidacion ?,?,?,?', [
                    $id_prospecto,
                    29,
                    0,
                    $request->proveedorTrabajaQuien
                ]);
            }

            for ($i = 0; $i < count($validaciones); $i++) {
                if ($request->clienteDirecto == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->empresaTrasnacional == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->tipoEmpresa == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->medioContacto == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->impresionComercial == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->empaque == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->display == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->bolsas == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->materialPop == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->medioPresentacion == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->vecesVisitado == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->proveedorActual == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->proveedorActualRespuesta == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->proveedorTrabaja == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->tiempoProyecto == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->impresionComercialCot == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->empaqueCot == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->displayCot == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->bolsasCot == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->materialPopCot == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->maquilaCot == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->frecuanciaCompra == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
                if ($request->diasCredit == $validaciones[$i]->idCatValidacion) {
                    DB::statement('exec spInsertValidacion ?,?,?,?', [
                        $id_prospecto,
                        $validaciones[$i]->idCatValidacion,
                        $validaciones[$i]->porcentaje,
                        null
                    ]);
                }
            }

            $prospecto = v_Prospectos::where('idProspecto', $request->idProspecto)->first();

            // if ($prospecto->porcentaje >= 80) {
            //     // $req_ = ['id' => $id_prospecto, 'oportunidades' => 1];
            //     // $req = new Request($req_);

            //     $idUsuario = $this->idUsuario();
            //     $fecha = new \DateTime();
            //     $asigna = 1;
            //     $estatus = 'Asigna Oportunidades ';


            //     DB::statement(
            //         'exec spAsignaOportunidadesProspecto ?, ?, ?, ?',
            //         [
            //             $idUsuario,
            //             $fecha->format('Y-m-d H:i:s'),
            //             $asigna,
            //             $id_prospecto,
            //         ]
            //     );

            //     // $this->bitacora($this->modulo, $estatus, 'Prospecto ', $id_prospecto, $this->loginUsuario(), 3);
            // } else {

            //     $idUsuario = $this->idUsuario();
            //     $fecha = new \DateTime();
            //     if ($prospecto->Oportunidades == 1) {
            //         $asigna = 1;
            //     } else {
            //         $asigna = 0;
            //     }
                
            //     $estatus = 'Ya No Asigna Oportunidades ';

            //     DB::statement(
            //         'exec spAsignaOportunidadesProspecto ?, ?, ?, ?',
            //         [
            //             $idUsuario,
            //             $fecha->format('Y-m-d H:i:s'),
            //             $asigna,
            //             $id_prospecto,
            //         ]
            //     );

            //     // $this->bitacora($this->modulo, $estatus, 'Prospecto ', $id_prospecto, $this->loginUsuario(), 3);
            // }
        }

        $cadena = str_replace('{', '', str_replace('}', '', json_encode($request->all(), true)));
        $this->bitacora($this->modulo, 'Editar ', 'Candidato / ' . $cadena . ' / ', $id_prospecto, $this->loginUsuario(), 2);
        return redirect()->route('Candidatos');
    }

    public function apiProspectos()
    {
        $prospectosAll = v_Prospectos::all();

        $prospectos = v_Prospectos::where('EsCliente', 0)->limit(count($prospectosAll));

        session(['rol' => $this->idRol()]);

        return DataTables::of($prospectos)
            ->addColumn('inputEditar', function () {
                $sesionEditar = trim(session('editar_prospectos'));
                $inputEditar = $sesionEditar;

                return $inputEditar;
            })
            ->addColumn('inputEliminar', function () {
                $sesionEliminar = trim(session('eliminar_prospectos'));
                $inputEliminar = $sesionEliminar;

                return $inputEliminar;
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
            ->rawColumns(['inputEditar', 'inputEliminar', 'inputLogin', 'inputRol'])
            ->make(true);
    }

    public function darDeBajaProspecto(Request $request)
    {
        $idUsuario = $this->idUsuario();
        $fecha = new \DateTime();
        $activo = $request->activo;
        $estatus = '';

        if ($activo == 0) {
            $activo = 1;
            $estatus = 'Activar ';
        } else {
            $activo = 0;
            $estatus = 'Desactivar ';
        }

        DB::statement(
            'exec spDeleteProspecto ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $activo,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, $estatus, 'Candidato ', $request->id, $this->loginUsuario(), 3);
    }

    public function asignaOportunidades(Request $request)
    {
        // return $request;

        $idUsuario = $this->idUsuario();
        $fecha = new \DateTime();
        $asigna = $request->oportunidades;
        $estatus = '';

        if ($asigna == 0) {
            $asigna = 1;
            $estatus = 'Asigna Oportunidades ';
        } else {
            $asigna = 0;
            $estatus = 'Ya No Asigna Oportunidades ';
        }

        DB::statement(
            'exec spAsignaOportunidadesProspecto ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $asigna,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, $estatus, 'Prospecto ', $request->id, $this->loginUsuario(), 3);
    }

    public function convertirCliente(Request $request)
    {
        $idUsuario = $this->idUsuario();
        $fecha = new \DateTime();
        $es_cliente = $request->escliente;
        $estatus = '';
        $prospecto = v_Prospectos::where('idProspecto', $request->id)->first();
        // return $prospecto;

        if ($es_cliente == 0) {
            $es_cliente = 1;
            $estatus = 'Es Prospecto, ';
        } else {
            $es_cliente = 0;
            $estatus = 'Ya No Es Prospecto, ';
        }

        DB::statement('exec spEsCliente ?,?', [
            $es_cliente,
            $request->id,
        ]);

        $buro = 0;
        $top = 0;
        $fecha_top = null;
        $credito_especial = 0;
        $credito_con_condiciones = 0;

        $bloquear_morosos = 'NO';
        $fecha_aut_cre = null;
        $addenda_especial = 0;

        if ($prospecto->idCliente == 0) {

            DB::statement(
                'exec spInsertCliente ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $prospecto->Agente,
                    NULL,
                    $prospecto->razonsocial,
                    $prospecto->LITONombreComercial,
                    'CLIENTE',
                    'ALTA',
                    $prospecto->Direccion,
                    $prospecto->DireccionNumero,
                    $prospecto->DireccionNumeroInt,
                    $prospecto->Alcaldia,
                    $prospecto->Colonia,
                    $prospecto->Poblacion,
                    $prospecto->Estado,
                    $prospecto->Pais,
                    $prospecto->CodigoPostal,
                    $prospecto->DireccionFiscal,
                    $prospecto->DireccionFiscalNumero,
                    $prospecto->DireccionFiscalNumeroInt,
                    $prospecto->AlcaldiaFiscal,
                    $prospecto->ColoniaFiscal,
                    $prospecto->PoblacionFiscal,
                    $prospecto->EstadoFiscal,
                    $prospecto->PaisFiscal,
                    $prospecto->CPFiscal,
                    $prospecto->RFC,
                    $prospecto->Lada1,
                    $prospecto->Telefonos,
                    $prospecto->Extencion1,
                    $prospecto->Lada2,
                    $prospecto->Telefonos2,
                    $prospecto->Extencion2,
                    $prospecto->DirInternet,
                    $prospecto->eMail,
                    '',
                    '',
                    $prospecto->CreditoCondiciones,
                    $prospecto->FiscalRegimen,
                    $prospecto->Giro,
                    $prospecto->SubGiro,
                    $prospecto->ClaveUsoCFDI,
                    $buro,
                    $top,
                    $fecha_top,
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    null,
                    null, //situacionA
                    null , //fecAniversario,
                    0,
                    0,
                    $credito_especial,
                    $credito_con_condiciones,
                    $prospecto->urlPortal,
                    $prospecto->usuarioPortal,
                    $prospecto->cvePortal,
                    $prospecto->manualPortal,
                    'PROSPECTO INACTIVO',
                    null,
                    null,
                ]
            );


            $cliente = Clientes::orderBy('idCliente', 'DESC')->first();

            // return $cliente;
            DB::statement('exec spAsignarClienteId ?,?', [
                $cliente->idCliente,
                $request->id,
            ]);

            DB::statement('exec sp_UpdateRelDocsCliente ?,?', [
                $cliente->idCliente,
                $request->id,
            ]);

            $clienteINT = Clientes::where('idCliente', $cliente->idCliente)->first();

            DB::statement('exec spInsertClienteContacto ?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                $clienteINT->ClienteINT,
                strtoupper($prospecto->TratamientoContacto),
                strtoupper($prospecto->NombreContacto),
                strtoupper($prospecto->CargoContacto),
                strtoupper($prospecto->DepartamentoContacto),
                strtoupper($prospecto->Telefonos1Contacto),
                strtolower($prospecto->eMailContacto),
                0,
                0,
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                1
            ]);

            DB::statement('exec spInsertClienteContacto ?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                $cliente->ClienteINT,
                strtoupper($prospecto->TratamientoContactoCXP),
                strtoupper($prospecto->NombreContactoCXP),
                strtoupper($prospecto->CargoContactoCXP),
                strtoupper($prospecto->DepartamentoContactoCXP),
                strtoupper($prospecto->Telefonos1ContactoCXP),
                strtolower($prospecto->eMailContactoCXP),
                0,
                1,
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                0
            ]);

            DB::statement('exec spInsertClienteContacto ?,?,?,?,?,?,?,?,?,?,?,?,?,?', [
                $cliente->ClienteINT,
                strtoupper($prospecto->TratamientoContactoCFDI),
                strtoupper($prospecto->NombreContactoCFDI),
                strtoupper($prospecto->CargoContactoCFDI),
                strtoupper($prospecto->DepartamentoContactoCFDI),
                strtoupper($prospecto->Telefonos1ContactoCFDI),
                strtolower($prospecto->eMailContactoCFDI),
                1,
                0,
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                0
            ]);

        } else {

            DB::statement(
                'exec spConvertirEnProspecto ?, ?',
                [
                    0,
                    $prospecto->idCliente,
                ]
            );

            $cliente = Clientes::where('idCliente', $prospecto->idCliente)->select('ClienteINT', 'idCliente')->first();

            $LimiteCredito = $prospecto->limiteCredito;
            $CreditoLimitePedidos = $prospecto->CreditoLimitePedidos;
            $LimiteDeCredito = $prospecto->LimiteDeCredito;
            $DireccionFiscal = $prospecto->DireccionFiscal;
            $DireccionFiscalNumero = $prospecto->DireccionFiscalNumero;
            $DireccionFiscalNumeroInt = $prospecto->DireccionFiscalNumeroInt;
            $AlcaldiaFiscal = $prospecto->AlcaldiaFiscal;
            $ColoniaFiscal = $prospecto->ColoniaFiscal;
            $PoblacionFiscal = $prospecto->PoblacionFiscal;
            $EstadoFiscal = $prospecto->EstadoFiscal;
            $PaisFiscal = $prospecto->PaisFiscal;
            $CPFiscal = $prospecto->CPFiscal;
            $RFC = $prospecto->RFC;
            $RIT = $prospecto->NumRegIdTrib;
            $CreditoCondiciones = $prospecto->CreditoCondiciones;
            $UsoCFDI = $prospecto->ClaveUsoCFDI;

            if ($prospecto->CreditoCondiciones != "") {
                $credito_especial = 1;
                $credito_con_condiciones = 1;
            }

            $FiscalRegimen = $prospecto->FiscalRegimen;
            $urlPortal = $prospecto->urlPortal;
            $usuarioPortal = $prospecto->usuarioPortal;
            $cvePortal = $prospecto->cvePortal;
            $manualPortal = $prospecto->manualPortal;

            if ($prospecto->BloquearMorosos == 'on') {
                $bloquear_morosos = 'SI';
            }
            $diasRevision = $prospecto->diasRevision;
            $idMetodoRevision = $prospecto->idMetodoRevision;
            $idEntregaComplementoPago = $prospecto->idEntregaComplementoPago;

            if ($prospecto->addendaEspecial == 'on') {
                $addenda_especial = 1;
            }
            $idUsuarioAutCredito = $prospecto->idUsuarioAutLimiteCredito;

            if ($prospecto->idUsuarioAutLimiteCredito != '') {
                $fecha_aut_cre = $fecha->format('Y-m-d H:i:s');
            } else {
                $fecha_aut_cre = $prospecto->fecModificacionLimiteCredito;
            }

            DB::statement(
                'exec spUpdateCliente ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $prospecto->Agente,
                    NULL,
                    $prospecto->razonsocial,
                    $prospecto->LITONombreComercial,
                    $prospecto->Direccion,
                    $prospecto->DireccionNumero,
                    $prospecto->DireccionNumeroInt,
                    $prospecto->Alcaldia,
                    $prospecto->Colonia,
                    $prospecto->Poblacion,
                    $prospecto->Estado,
                    $prospecto->Pais,
                    $prospecto->CodigoPostal,
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
                    $prospecto->Lada1,
                    $prospecto->Telefonos,
                    $prospecto->Extencion1,
                    $prospecto->Lada2,
                    $prospecto->Telefonos2,
                    $prospecto->Extencion2,
                    $prospecto->DirInternet,
                    $prospecto->eMail,
                    '',
                    '',
                    $CreditoCondiciones,
                    $FiscalRegimen,
                    $prospecto->Giro,
                    $prospecto->SubGiro,
                    $UsoCFDI,
                    $buro,
                    $top,
                    $fecha_top,
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    null, // idSituacionA,
                    null, // fecAniversario,
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
                    $cliente->ClienteINT,
                ]
            );

            DB::statement(
                'exec spUpdateClienteContacto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $cliente->idCliente,
                    strtoupper($prospecto->TratamientoContacto),
                    strtoupper($prospecto->NombreContacto),
                    strtoupper($prospecto->CargoContacto),
                    strtoupper($prospecto->DepartamentoContacto),
                    strtoupper($prospecto->Telefonos1Contacto),
                    strtolower($prospecto->eMailContacto),
                    0,
                    0,
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    1,
                    $cliente->idContactoINT,
                ]
            );
    
            DB::statement(
                'exec spUpdateClienteContacto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $cliente->idCliente,
                    strtoupper($prospecto->TratamientoContactoCXP),
                    strtoupper($prospecto->NombreContactoCXP),
                    strtoupper($prospecto->CargoContactoCXP),
                    strtoupper($prospecto->DepartamentoContactoCXP),
                    strtoupper($prospecto->Telefonos1ContactoCXP),
                    strtolower($prospecto->eMailContactoCXP),
                    1,
                    0,
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    0,
                    $cliente->idContactoINT,
                ]
            );
    
            DB::statement(
                'exec spUpdateClienteContacto ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?',
                [
                    $cliente->idCliente,
                    strtoupper($prospecto->TratamientoContactoCFDI),
                    strtoupper($prospecto->NombreContactoCFDI),
                    strtoupper($prospecto->CargoContactoCFDI),
                    strtoupper($prospecto->DepartamentoContactoCFDI),
                    strtoupper($prospecto->Telefonos1ContactoCFDI),
                    strtolower($prospecto->eMailContactoCFDI),
                    0,
                    1,
                    $idUsuario,
                    $fecha->format('Y-m-d H:i:s'),
                    0,
                    $cliente->idContactoINT,
                ]
            );

        }    

        $this->bitacora($this->modulo, $estatus, 'Candidato ', $request->id, $this->loginUsuario(), 3);
    }

    public function cambiarAgente(Request $request)
    {

        $idUsuario = $this->idUsuario();

        $agentes = DB::select('exec GetAgentesComboCliente');

        $agente = $agentes[$request->agente]->agente;

        $fecha = new \DateTime();

        DB::statement(
            'exec spCambiarAgenteProspecto ?, ?, ?, ?',
            [
                $idUsuario,
                $fecha->format('Y-m-d H:i:s'),
                $agente,
                $request->id,
            ]
        );

        $this->bitacora($this->modulo, 'Cambiar Agente ', 'Candidato ', $request->id, $this->loginUsuario(), 2);
    }
}
