@extends('layout.master')
@section('content')
    <div id="formClienteEdit" class="col-md-12 mt-2">
        <form method="post" action="{{ route('clientes.update', $cliente[0]->ClienteINT) }}" role="form">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" name="contactoPrincipal" id="contactoPrincipal" value="{{ session('ctoPrincipal') }}">
            <input type="hidden" value="{{ $cliente[0]->ClienteINT }}" name="idClienteINT" id="idClienteINT">
            <input type="hidden" value="0" name="idProspecto" id="idProspecto">
            <input type="hidden" value="{{ $idrol }}" name="idRol" id="idRol">
            <input type="hidden" value="{{ $cliente[0]->idUsuarioAutLimiteCredito }}" name="idUsuarioAutCredito"
                id="idUsuarioAutCredito">
            <input type="hidden" value="{{ $cliente[0]->fecModificacionLimiteCredito }}"
                name="fecModificacionLimiteCredito" id="fecModificacionLimiteCredito">
            <div>
                <div class="rounded-pill bg-gray">
                    <div class="row mb-2">
                        <div class="col-12 text-center">
                            <h6 class="text-bold pt-1 pl-1">Editar Cliente</h6>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="input-group input-group-sm">
                        <h6 class="col-md-1 text-right text-bold text-red pr-1 pt-2">&nbsp;No. {{ $cliente[0]->ClienteINT }}
                            -
                            &nbsp;
                        </h6>
                        <label class="mt-2" for="razonsocial">Razon Social <b class="asterisco">*</b>
                            &nbsp;</label>
                        <input type="text" class="form-control col-lg-9 col-xl-10" id="razonsocial" name="razonsocial"
                            placeholder="Requerido" autocomplete="off" autofocus value="{{ $cliente[0]->razonsocial }}">
                        <div class="input-group-append col-md-4 col-lg-3 col-xl-2 mt-1">
                            <span class="col-6" id="sbtnSave"><button type="submit" id="btnSave"
                                    class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                            <span class="col-6 center" id="sbtnBack"><a id="btnBack" onclick="regresar()"
                                    class="btn btn-secondary btn-sm btn-block">Regresar</a></span>
                        </div>
                    </div>
                </div>
                <div>
                    <ul class="nav nav-tabs col-sm-12" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item" id="nav_item_general" style="width: 13%">
                            <a class="nav-link active" id="general-tab" onclick="mostrarGeneral()" data-toggle="pill"
                                role="tab" aria-controls="general-tab" aria-selected="true"
                                style="cursor: pointer">General</a>
                        </li>
                        <li class="nav-item" id="nav_item_contactos" style="width: 13%">
                            <a class="nav-link" id="contactos-tab" onclick="mostrarContactos()" data-toggle="pill"
                                role="tab" aria-controls="contactos-tab" aria-selected="false"
                                style="cursor: pointer">Contactos</a>
                        </li>
                        <li class="nav-item" id="nav_item_sucursales" style="width: 13%">
                            <a class="nav-link" id="sucursales-tab" onclick="mostrarSucursales()" data-toggle="pill"
                                role="tab" aria-controls="sucursales-tab" aria-selected="false"
                                style="cursor: pointer">Sucursales</a>
                        </li>
                        <li class="nav-item" id="nav_item_oportunidades" style="width: 14%">
                            <a class="nav-link" id="oportunidades-tab" onclick="mostrarOportunidades()"
                                data-toggle="pill" role="tab" aria-controls="oportunidades-tab"
                                aria-selected="false" style="cursor: pointer">Oportunidades</a>
                        </li>
                        <li class="nav-item" id="nav_item_documentos" style="width: 14%">
                            <a class="nav-link" id="documentos-tab" onclick="mostrarDocumentos()" data-toggle="pill"
                                role="tab" aria-controls="documentos-tab" aria-selected="false"
                                style="cursor: pointer">Documentos</a>
                        </li>
                        @if ($idrol == 5 || $idrol == 1 || $idrol == 8 || $idrol == 1010 || $idrol == 1017)
                            <li class="nav-item" id="nav_item_datos_admin" style="width: 16%">
                                <a class="nav-link" id="datos-admin-tab" onclick="mostrarDatosAdmin()"
                                    data-toggle="pill" role="tab" aria-controls="datos-admin-tab"
                                    aria-selected="false" style="cursor: pointer">Datos Administrativos</a>
                            </li>
                        @endif
                        <li class="nav-item" id="nav_item_requisitos" style="width: 17%">
                            <a class="nav-link" id="requisitos-especiales-tab" onclick="mostrarRequisitos()"
                                data-toggle="pill" role="tab" aria-controls="requisitos-especiales-tab"
                                aria-selected="false" style="cursor: pointer">Requisitos Especiales</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="div_contactos_cliente_editar">
                        <div class="tab-pane fade show active" id="general" role="tabpanel"
                            aria-labelledby="general-tab">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="rounded-pill col-sm-12 bg-gray mb-1">
                                        <h6 class="text-bold text-center"></h6>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row mb-1">
                                            <div class="col-lg-4">
                                                <label for="LITONombreComercial">Nombre Comercial <b
                                                        class="asterisco">*</b></label>
                                                <div class="input-group">
                                                    <input id="LITONombreComercial" name="LITONombreComercial"
                                                        type="text" class="form-control"
                                                        placeholder="Nombre Comercial" autocomplete="off"
                                                        value="{{ $cliente[0]->LITONombreComercial }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="empresaWeb">Empresa WEB</label>
                                                <select id="empresaWeb" name="empresaWeb" class="form-control">
                                                    <option selected disabled>Empresa WEB</option>
                                                    @foreach ($empresa_web_combo as $empresa_web)
                                                        @if ($empresa_web->Opcion  == $cliente[0]->EmpresaWEB)
                                                            <option value="{{ $empresa_web->Opcion }}" selected>
                                                                {{ $cliente[0]->EmpresaWEB }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $empresa_web->Opcion }}">
                                                                {{ $empresa_web->Opcion }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="WebUserID">WEB User ID</label>
                                                <div class="input-group">
                                                    <input type="text" id="WebUserID" name="WebUserID"
                                                        type="text" class="form-control" placeholder="WEB User ID"
                                                        autocomplete="off" value="{{ $cliente[0]->WebUserID }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-2">
                                                <label for="DirInternet">Pagina WEB</label>
                                                <div class="input-group">
                                                    <input type="text" id="DirInternet" name="DirInternet"
                                                        type="text" class="form-control" placeholder="Página WEB"
                                                        autocomplete="off" value="{{ $cliente[0]->DirInternet }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="fecAniversario">Aniversario</label>
                                                <div class="input-group">
                                                    <input id="fecAniversario" name="fecAniversario" type="date"
                                                        class="form-control" placeholder="Aniversario" autocomplete="off"
                                                        value="{{ $cliente[0]->fecAniversario }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-2">
                                                <label for="idSituacionA">Situación</label>
                                                <select id="idSituacionA" name="idSituacionA" class="form-control">
                                                    <option selected disabled>Situación</option>
                                                    @foreach ($cat_situacion as $situacion)
                                                        @if ($situacion->idSituacionA == $cliente[0]->idSituacionA)
                                                            <option value="{{ $situacion->idSituacionA }}" selected>
                                                                {{ $cliente[0]->Situacion }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $situacion->idSituacionA }}">
                                                                {{ $situacion->descSituacionA }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="Agente">Agente <b class="asterisco">*</b></label>
                                                <select id="Agente" name="Agente" class="form-control"
                                                    style="width: 100%">
                                                    <option selected disabled>Agente</option>
                                                    @foreach ($agentes as $agente)
                                                        @if ($agente->agente == $cliente[0]->Agente)
                                                            <option value="{{ $agente->agente }}" selected>
                                                                {{ $cliente[0]->Agente }} -
                                                                {{ $cliente[0]->NombreAgente }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $agente->agente }}">
                                                                {{ $agente->agente }} - {{ $agente->nombre }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2">
                                                <label for="AgenteSecundario">Agente Secundario</label>
                                                <select id="AgenteSecundario" name="AgenteSecundario" class="form-control"
                                                    style="width: 100%">
                                                    <option selected disabled>Agente</option>
                                                    @foreach ($agentes as $agente)
                                                        @if (trim($agente->agente) == $cliente[0]->Agente3)
                                                            <option value="{{ $agente->agente }}" selected>
                                                                {{ $cliente[0]->Agente3 }} -
                                                                {{ $agente->nombre }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $agente->agente }}">
                                                                {{ $agente->agente }} - {{ $agente->nombre }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-2 col-lg-2 mt-4">
                                                <div class="custom-control custom-switch">
                                                    @if ($cliente[0]->conBuroC == 1)
                                                        <input id="conBuroC" name="conBuroC" type="checkbox"
                                                            class="custom-control-input" checked>
                                                    @else
                                                        <input id="conBuroC" name="conBuroC" type="checkbox"
                                                            class="custom-control-input">
                                                    @endif
                                                    <label class="custom-control-label" for="conBuroC">Buró</label>
                                                </div>
                                            </div>
                                            <div class="col-2 col-lg-2 mt-4">
                                                <div class="custom-control custom-switch">
                                                    @if ($cliente[0]->esTop == 1)
                                                        <input id="esTop" name="esTop" type="checkbox"
                                                            class="custom-control-input" checked>
                                                    @else
                                                        <input id="esTop" name="esTop" type="checkbox"
                                                            class="custom-control-input">
                                                    @endif
                                                    <label class="custom-control-label" for="esTop">Top</label>
                                                </div>
                                            </div>
                                            {{--  <div class="col-2 col-lg-2 mt-4">
                                                <div class="custom-control custom-switch">
                                                    <input id="puedeDescargarPresupuesto" name="puedeDescargarPresupuesto" type="checkbox"
                                                        class="custom-control-input"
                                                        @if ($cliente[0]->puedeDescargarPresupuesto == 1) checked @endif
                                                        
                                                        >
                                                    <label class="custom-control-label" for="puedeDescargarPresupuesto">¿Se puede descargar presupuesto?</label>
                                                </div>
                                            </div>  --}}
                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-lg-1">
                                                <label for="Lada1">Lada 1</label>
                                                <div class="input-group">
                                                    <input id="Lada1" name="Lada1" type="text"
                                                        class="form-control" placeholder="Lada 1" autocomplete="off"
                                                        value="{{ $cliente[0]->Lada1 }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="Telefonos">Teléfonos <b class="asterisco">*</b></label>
                                                <div class="input-group">
                                                    <input id="Telefonos" name="Telefonos" type="text"
                                                        class="form-control" placeholder="Teléfonos" autocomplete="off"
                                                        value="{{ $cliente[0]->Telefonos }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                <label for="Extencion1">Ext 1</label>
                                                <div class="input-group">
                                                    <input id="Extencion1" name="Extencion1" type="text"
                                                        class="form-control" placeholder="Ext 1" autocomplete="off"
                                                        value="{{ $cliente[0]->Extencion1 }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                <label for="Lada2">Lada 2</label>
                                                <div class="input-group">
                                                    <input id="Lada2" name="Lada2" type="text"
                                                        class="form-control" placeholder="Lada 2" autocomplete="off"
                                                        value="{{ $cliente[0]->Lada2 }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="Telefonos2">Teléfonos 2</label>
                                                <div class="input-group">
                                                    <input id="Telefonos2" name="Telefonos2" type="text"
                                                        class="form-control" placeholder="Teléfonos2" autocomplete="off"
                                                        value="{{ $cliente[0]->Telefonos2 }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-1">
                                                <label for="Extencion2">Ext 2</label>
                                                <div class="input-group">
                                                    <input id="Extencion2" name="Extencion2" type="text"
                                                        class="form-control" placeholder="Ext 2" autocomplete="off"
                                                        value="{{ $cliente[0]->Extencion2 }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-1">
                                            @if ($idrol == 5 || $idrol == 1 || $idrol == 8 || $idrol == 1010 || $id_usuario == 62)
                                                <div class="col-lg-3">
                                                    <label for="eMail1">Email 1 <b class="asterisco">*</b></label>
                                                    <div class="input-group">
                                                        <input id="eMail1" name="eMail1" type="text"
                                                            class="form-control" placeholder="Email 1" autocomplete="off"
                                                            value="{{ $cliente[0]->eMail1 }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="Giro">Giro <b class="asterisco">*</b></label>
                                                    <select id="Giro" name="Giro" class="form-control"
                                                        style="width: 100%">
                                                        <option selected disabled>Giro</option>
                                                        @foreach ($cat_giros as $giro)
                                                            @if ($giro->Giro == $cliente[0]->Giro)
                                                                <option value="{{ $giro->Giro }}" selected>
                                                                    {{ $cliente[0]->Giro }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $giro->Giro }}">
                                                                    {{ $giro->Giro }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="SubGiro">Sub Giro <b class="asterisco">*</b></label>
                                                    <select id="SubGiro" name="SubGiro" class="form-control"
                                                        style="width: 100%">
                                                        <option selected disabled>Sub Giro</option>
                                                        @foreach ($cat_subgiros as $subgiro)
                                                            @if ($subgiro->subgiro == $cliente[0]->SubGiro)
                                                                <option value="{{ $subgiro->subgiro }}" selected>
                                                                    {{ $cliente[0]->SubGiro }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $subgiro->subgiro }}">
                                                                    {{ $subgiro->subgiro }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="ClaveUsoCFDI">Clave Uso CFDI</label>
                                                    <select id="ClaveUsoCFDI" name="ClaveUsoCFDI" class="form-control"
                                                        style="width: 100%">
                                                        <option selected disabled>Clave Uso CFDI</option>
                                                        @foreach ($uso_cfdi as $cfdi)
                                                            @if ($cfdi->ClaveUsoCFDI == $cliente[0]->ClaveUsoCFDI)
                                                                <option value="{{ $cfdi->ClaveUsoCFDI }}" selected>
                                                                    {{ $cfdi->ClaveUsoCFDI }} -
                                                                    {{ $cfdi->Descripcion }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $cfdi->ClaveUsoCFDI }}">
                                                                    {{ $cfdi->ClaveUsoCFDI }} -
                                                                    {{ $cfdi->Descripcion }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @else
                                                <div class="col-lg-4">
                                                    <label for="eMail1">Email 1 <b class="asterisco">*</b></label>
                                                    <div class="input-group">
                                                        <input id="eMail1" name="eMail1" type="text"
                                                            class="form-control" placeholder="Email 1" autocomplete="off"
                                                            value="{{ $cliente[0]->eMail1 }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="Giro">Giro <b class="asterisco">*</b></label>
                                                    <select id="Giro" name="Giro" class="form-control"
                                                        style="width: 100%">
                                                        <option selected disabled>Giro</option>
                                                        @foreach ($cat_giros as $giro)
                                                            @if ($giro->Giro == $cliente[0]->Giro)
                                                                <option value="{{ $giro->Giro }}" selected>
                                                                    {{ $cliente[0]->Giro }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $giro->Giro }}">
                                                                    {{ $giro->Giro }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="SubGiro">Sub Giro <b class="asterisco">*</b></label>
                                                    <select id="SubGiro" name="SubGiro" class="form-control"
                                                        style="width: 100%">
                                                        <option selected disabled>Sub Giro</option>
                                                        @foreach ($cat_subgiros as $subgiro)
                                                            @if ($subgiro->subgiro == $cliente[0]->SubGiro)
                                                                <option value="{{ $subgiro->subgiro }}" selected>
                                                                    {{ $cliente[0]->SubGiro }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $subgiro->subgiro }}">
                                                                    {{ $subgiro->subgiro }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-lg-12 mt-1 pb-2">
                                            <div class="mt-1 mb-1 row">
                                                <div class="rounded-pill col-sm-12 bg-gray">
                                                    <h6 class="text-bold text-center">Dirección</h6>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="row mb-1">
                                                        <div class="col-md-5">
                                                            <label for="Direccion">Calle <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="Direccion" name="Direccion" type="text"
                                                                    class="form-control" placeholder="Calle"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Direccion }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="DireccionNumero">Número Exterior <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="DireccionNumero" name="DireccionNumero"
                                                                    type="text" class="form-control"
                                                                    placeholder="Número Exterior" autocomplete="off"
                                                                    value="{{ $cliente[0]->DireccionNumero }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="DireccionNumeroInt">Número Interior</label>
                                                            <div class="input-group">
                                                                <input id="DireccionNumeroInt" name="DireccionNumeroInt"
                                                                    type="text" class="form-control"
                                                                    placeholder="Número Interior" autocomplete="off"
                                                                    value="{{ $cliente[0]->DireccionNumeroInt }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="CodigoPostalLista">Códigos Postales</label>
                                                            <div class="input-group">
                                                                <input id="CodigoPostalLista" type="text"
                                                                    class="form-control" placeholder="Código Postal"
                                                                    autocomplete="off" autofill="off">
                                                                <span class="input-group-text"><i
                                                                        class="fas fa-search"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-1">
                                                        <div class="col-md-2">
                                                            <label for="CodigoPostal">Código Postal <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="CodigoPostal" name="CodigoPostal"
                                                                    type="text" class="form-control"
                                                                    placeholder="Código Postal" autocomplete="off"
                                                                    value="{{ $cliente[0]->CodigoPostal }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Alcaldia">Alcaldí­a <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="Alcaldia" name="Alcaldia" type="text"
                                                                    class="form-control" placeholder="Alcaldí­a"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Alcaldia }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Colonia">Colonia <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="Colonia" name="Colonia" type="text"
                                                                    class="form-control" placeholder="Colonia"
                                                                    autocomplete="off" value="{{ $cliente[0]->Colonia }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Poblacion">Localidad <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="Poblacion" name="Poblacion" type="text"
                                                                    class="form-control" placeholder="Localidad"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->Poblacion }}"
                                                                    @if ($idrol != 5) readonly @endif>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Estado">Estado <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="Estado" name="Estado" type="text"
                                                                    class="form-control" placeholder="Estado"
                                                                    autocomplete="off" value="{{ $cliente[0]->Estado }}"
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="Pais">Paí­s <b class="asterisco">*</b></label>
                                                            <select id="Pais" name="Pais" class="form-control"
                                                                style="width: 100%">
                                                                <option disabled selected>País</option>
                                                                @foreach ($paises as $pais)
                                                                    @if (utf8_decode($pais->Descripcion) === utf8_decode($cliente[0]->Pais))
                                                                        <option value="{{ $pais->Descripcion }}" selected>
                                                                            {{ $cliente[0]->Pais }}
                                                                        </option>
                                                                    @else
                                                                        <option value="{{ $pais->Descripcion }}">
                                                                            {{ $pais->Descripcion }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card bg-transparent">
                                                <div class="bg-gray rounded-pill" style="height: 28px !important">
                                                    <h6 class="text-bold text-center">
                                                        Más Información
                                                    </h6>
                                                    <!-- card tools -->
                                                    {{-- <div class="card-tools" style="margin-top: -27px">
                                                        <button type="button" class="btn btn-secondary btn-sm"
                                                            data-card-widget="collapse" title="Collapse"
                                                            style="height: 28px !important">
                                                        </button>
                                                    </div> --}}
                                                    <!-- /.card-tools -->
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-1">
                                                        <div class="col-md-4">
                                                            <label for="fecAlta">Fecha Alta</label>
                                                            <div class="input-group">
                                                                @if ($cliente[0]->fecAlta == null)
                                                                    <input id="fecAlta" type="text"
                                                                        class="form-control" autocomplete="off"
                                                                        placeholder="dd/mm/aaaa" readonly>
                                                                @else
                                                                    <input id="fecAlta" type="text"
                                                                        class="form-control" autocomplete="off"
                                                                        value="{{ \Carbon\Carbon::parse($cliente[0]->fecAlta)->format('d/m/Y H:i') }}"
                                                                        readonly>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="idUsuarioAlta">Usuario Alta</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="idUsuarioAlta" placeholder="Usuario Alta"
                                                                    autocomplete="off"
                                                                    value="{{ $cliente[0]->NombreAlta }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="fecTop">Fecha Top</label>
                                                            <div class="input-group">
                                                                <input id="fecTop" name="fecTop" type="date"
                                                                    class="form-control" autocomplete="off"
                                                                    value="{{ $cliente[0]->fecTop }}" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-1">
                                                        <div class="col-md-4">
                                                            <label for="fecUltCambio">Fecha Ult. Cambio</label>
                                                            <div class="input-group">
                                                                @if ($cliente[0]->fecUltCambio == null)
                                                                    <input id="fecUltCambio" type="text"
                                                                        class="form-control" autocomplete="off"
                                                                        placeholder="dd/mm/aaaa" readonly>
                                                                @else
                                                                    <input id="fecUltCambio" type="text"
                                                                        class="form-control" autocomplete="off"
                                                                        value="{{ \Carbon\Carbon::parse($cliente[0]->fecUltCambio)->format('d/m/Y H:i') }}"
                                                                        readonly>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="idUsuarioUltCambio">Usuario Ult. Cambio</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="idUsuarioUltCambio" name="idUsuarioUltCambio"
                                                                    placeholder="Usuario Ult. Cambio" autocomplete="off"
                                                                    value="{{ $cliente[0]->NombreUltCambio }}" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="Posicion">Situación</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    id="Posicion" name="Posicion"
                                                                    placeholder="Situación" autocomplete="off"
                                                                    value="{{ $cliente[0]->Posicion }}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.card-body-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 mt-2">
                                    <div class="rounded-pill col-sm-12 bg-gray">
                                        <h6 class="text-bold text-center">Bitácora</h6>
                                    </div>






                                    <div id="time_line" class="col-12 mt-2"
                                        style="border: 1px solid #ddd; border-radius: 10px;">

                                        <div id="scrollbarTimeLine" class="row mt-2 mb-1" style="height: 65vh;">
                                            <div class="col-12">
                                                <div class="timeline mt-1">
                                                    {{-- @if ($oportunidad[0]->fecCreacion == null) --}}
                                                    <div>
                                                        <i class="fas fa-check bg-green mt-1"></i>
                                                        <div class="timeline-item" style="margin-right: 0 !important">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;
                                                                    {{ \Carbon\Carbon::parse($cliente[0]->fecAlta)->format('d-m-Y H:i') }}
                                                                </span>
                                                                <span style="float: right">Creado</span>
                                                            </h5>
                                                        </div>
                                                        {{-- <div class= pl-2"mt-1 timeline-item">
                                                            <a href="#">Reunion 1</a>
                                                        </div> --}}
                                                    </div>
                                                    @foreach ($llamadas_reuniones as $llamada_reunion)
                                                        <div>
                                                            <i
                                                                class="cursor-pointer fas {{ $llamada_reunion->icono }} {{ $llamada_reunion->color }} mt-1" title="{{ $llamada_reunion->asuntoLugar }}" onclick="editarLlamada({{ $llamada_reunion->idLlamadasReuniones }}, 0, '{{ $llamada_reunion->tipo }}'), loaderON()"></i>
                                                            <div class="timeline-item cursor-pointer" style="margin-right: 0 !important" title="{{ $llamada_reunion->asuntoLugar }}" onclick="editarLlamada({{ $llamada_reunion->idLlamadasReuniones }}, 0, '{{ $llamada_reunion->tipo }}'), loaderON()">
                                                                <h5 class="timeline-header">
                                                                    <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                    <span class="text-primary text-bold">&nbsp;
                                                                        {{ \Carbon\Carbon::parse($llamada_reunion->fecCreacion)->format('d-m-Y H:i') }}
                                                                    </span>
                                                                    <span
                                                                        style="float: right">{{ $llamada_reunion->tipo }}</span>
                                                                </h5>
                                                            </div>
                                                            {{-- <div class="mt-1 pl-2 timeline-item">
                                                                <li>
                                                                    <a onclick="editarLlamada({{ $llamada_reunion->idLlamadasReuniones }}, 1, '{{ $llamada_reunion->tipo }}'), loaderON()" class="cursor-pointer">{{ $llamada_reunion->asuntoLugar }}</a>
                                                                </li>
                                                            </div> --}}
                                                        </div>
                                                    @endforeach
                                                    {{-- <div>
                                                        <i class="fas fa-envelope bg-info mt-1"></i>
                                                        <div class="timeline-item" style="margin-right: 0 !important">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;28/04/2023 14:32</span>
                                                                <span style="float: right">Correo</span>
                                                            </h5>
                                                        </div>
                                                        <div class="mt-1 pl-2 timeline-item">
                                                            <li>
                                                                <a href="#">Correo 1 Correo 1 Correo 1 Correo 1
                                                                    Correo 1 Correo 1</a>
                                                            </li>
                                                        </div>
                                                    </div> --}}
                                                    <div class="mb-5">
                                                        <i class="fas fa-clock bg-red"></i>
                                                    </div>
                                                    {{-- @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecCreacion)->format('d-m-Y') }}
                                                                </span>
                                                                <span>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecCreacion)->format('H:i:s') }}
                                                                </span>
                                                                <span style="float: right">Pre Registro</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif --}}
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                    </div>







                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="contactos" role="tabpanel" aria-labelledby="contactos-tab">
                            <button onclick="agregarContacto()" type="button"
                                class="btn-flotante button btn btn-primary" title="Agregar Contacto">
                                <i class="fas fas fa-plus-circle"></i>
                            </button>
                            <div>
                                <table id="tableContactosCliente" class="table table-striped compact nowrap">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th>ID</th>
                                            <th>Tratamiento</th>
                                            <th>Nombre</th>
                                            <th>Cargo</th>
                                            <th>Departamento</th>
                                            <th>Teléfonos</th>
                                            <th>Email</th>
                                            <th>Es Envío</th>
                                            <th>Enviar CFD</th>
                                            <th>Contacto CXP</th>
                                            <th>Principal</th>
                                            <th>TH</th>
                                            <th>Activo</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="sucursales" role="tabpanel" aria-labelledby="sucursales-tab">
                            <button onclick="agregarSucursalCte()" type="button"
                                class="btn-flotante button btn btn-primary" title="Agregar Sucursal">
                                <i class="fas fas fa-plus-circle"></i>
                            </button>
                            <div>
                                <table id="tableSucursalesCliente" class="table table-striped compact nowrap">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="oportunidades" role="tabpanel"
                            aria-labelledby="oportunidades-tab">
                            @if ($crearOpor == 'Crear')
                                <a type="button" class="btn-flotante button btn btn-primary" title="Agregar Oportunidad"
                                    href="{{ route('Nueva Oportunidad') }}" onclick="loaderON()">
                                    <i class="fas fas fa-plus-circle"></i>
                                </a>
                            @endif
                            <div>
                                <table id="tableOportunidadesCliente" class="table table-striped compact nowrap">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot></tfoot>
                                </table>

                            </div>
                        </div>
                        <div class="tab-pane fade" id="documentos" role="tabpanel" aria-labelledby="documentos-tab">
                            @component('sections.DocumentosEnvios')
                            @endcomponent
                        </div>
                        <div class="tab-pane fade" id="requisitos_especiales" role="tabpanel"
                            aria-labelledby="requisitos-esoeciales-tab">
                            <div class="row">
                                <div class="card bg-transparent col-12 pb-3">
                                    <div class="bg-gray border-0 rounded-pill text center" style="height: 28px !important">
                                        <h6 class="text-center text-bold">
                                            Requisitos Especiales
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="selectReqEspeciales"
                                                        class="bg-gray col-12 ronded-lg p-1">Para
                                                        Entrega :</label>
                                                    <select id="selectReqEspeciales" name="selectReqEspeciales_Entrega[]"
                                                        class="duallistbox" multiple="multiple">
                                                        @foreach ($requisitos_entregas as $requisito)
                                                            <option value="{{ $requisito->idRequisito }}" selected>
                                                                {{ $requisito->descRequisito }}
                                                            </option>
                                                        @endforeach
                                                        @foreach ($requisitos_entregas_disponibles as $requisito_entrega_disponible)
                                                            <option
                                                                value="{{ $requisito_entrega_disponible->idRequisito }}">
                                                                {{ $requisito_entrega_disponible->descRequisito }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="selectReqEspecialesP"
                                                        class="bg-gray col-12 ronded-lg p-1">Para
                                                        Factura :</label>
                                                    <select id="selectReqEspecialesP" name="selectReqEspeciales_Pedido[]"
                                                        class="duallistbox" multiple="multiple">
                                                        @foreach ($requisitos_facturas as $requisito)
                                                            <option value="{{ $requisito->idRequisito }}" selected>
                                                                {{ $requisito->descRequisito }}
                                                            </option>
                                                        @endforeach
                                                        @foreach ($requisitos_facturas_disponibles as $requisito_factura_disponible)
                                                            <option
                                                                value="{{ $requisito_factura_disponible->idRequisito }}">
                                                                {{ $requisito_factura_disponible->descRequisito }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="selectReqEspecialesF"
                                                        class="bg-gray col-12 ronded-lg p-1">Para
                                                        CXC :</label>
                                                    <select id="selectReqEspecialesF" name="selectReqEspecialesF_Cobro[]"
                                                        class="duallistbox" multiple="multiple">
                                                        @foreach ($requisitos_cxc as $requisito)
                                                            <option value="{{ $requisito->idRequisito }}" selected>
                                                                {{ $requisito->descRequisito }}
                                                            </option>
                                                        @endforeach
                                                        @foreach ($requisitos_cxc_disponibles as $requisito_cxc_disponible)
                                                            <option value="{{ $requisito_cxc_disponible->idRequisito }}">
                                                                {{ $requisito_cxc_disponible->descRequisito }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="datos_admin" role="tabpanel" aria-labelledby="datos-admin-tab">
                            <div class="row mb-2">
                                <div class="rounded-pill col-sm-12 bg-gray">
                                    <h6 class="text-bold text-center">Datos Administrativos</h6>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="rounded-pill col-sm-12 bg-gray">
                                    <h6 class="text-bold text-center">Dirección Fiscal</h6>
                                    <span id="spanDirFiscal">
                                        <div class="custom-control custom-switch" style="margin-top: -25px">
                                            <input id="igualDirCom" type="checkbox" class="custom-control-input">
                                            <label class="custom-control-label" for="igualDirCom">Misma que
                                                Dirección Comercial</label>
                                        </div>
                                    </span>
                                </div>
                                <div class="col-12">
                                    <div class="row mb-1">
                                        <div class="col-md-5">
                                            <label for="DireccionFiscal">Calle</label>
                                            <div class="input-group">
                                                <input id="DireccionFiscal" name="DireccionFiscal" type="text"
                                                    class="form-control" placeholder="Calle" autocomplete="off"
                                                    value="{{ $cliente[0]->DirecccionFiscal }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="DireccionFiscalNumero">Número Exterior</label>
                                            <div class="input-group">
                                                <input id="DireccionFiscalNumero" name="DireccionFiscalNumero"
                                                    type="text" class="form-control" placeholder="Número Exterior"
                                                    autocomplete="off" value="{{ $cliente[0]->DireccionFiscalNumero }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="DireccionFiscalNumeroInt">Número Interior</label>
                                            <div class="input-group">
                                                <input id="DireccionFiscalNumeroInt" name="DireccionFiscalNumeroInt"
                                                    type="text" class="form-control" placeholder="Número Interior"
                                                    autocomplete="off"
                                                    value="{{ $cliente[0]->DireccionFiscalNumeroInt }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="CPFiscalLista">Códigos Postales</label>
                                            <div class="input-group">
                                                <input id="CPFiscalLista" type="text" class="form-control"
                                                    placeholder="Código Postal" autocomplete="off" autofill="off">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-2">
                                            <label for="CPFiscal">Código Postal</label>
                                            <div class="input-group">
                                                <input id="CPFiscal" name="CPFiscal" type="text"
                                                    class="form-control" placeholder="Código Postal" autocomplete="off"
                                                    value="{{ $cliente[0]->CPFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="AlcaldiaFiscal">Alcaldí­a</label>
                                            <div class="input-group">
                                                <input id="AlcaldiaFiscal" name="AlcaldiaFiscal" type="text"
                                                    class="form-control" placeholder="Alcaldí­a" autocomplete="off"
                                                    value="{{ $cliente[0]->AlcaldiaFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="ColoniaFiscal">Colonia</label>
                                            <div class="input-group">
                                                <input id="ColoniaFiscal" name="ColoniaFiscal" type="text"
                                                    class="form-control" placeholder="Colonia" autocomplete="off"
                                                    value="{{ $cliente[0]->ColoniaFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="PoblacionFiscal">Localidad</label>
                                            <div class="input-group">
                                                <input id="PoblacionFiscal" name="PoblacionFiscal" type="text"
                                                    class="form-control" placeholder="Poblacion" autocomplete="off"
                                                    value="{{ $cliente[0]->PoblacionFiscal }}"
                                                    @if ($idrol != 5) readonly @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="EstadoFiscal">Estado</label>
                                            <div class="input-group">
                                                <input id="EstadoFiscal" name="EstadoFiscal" type="text"
                                                    class="form-control" placeholder="Estado" autocomplete="off"
                                                    value="{{ $cliente[0]->EstadoFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="PaisFiscal">Paí­s</label>
                                            <!-- <label for="PaisFiscal">Paí­s <b class="asterisco">*</b></label> -->
                                            <select id="PaisFiscal" name="PaisFiscal" class="form-control"
                                                style="width: 100%">
                                                <option selected disabled>País</option>
                                                @foreach ($paises as $pais)
                                                    @if (strtoupper(utf8_decode($pais->Descripcion)) == strtoupper(utf8_decode($cliente[0]->PaisFiscal)))
                                                        <option value="{{ $pais->Descripcion }}" selected>
                                                            {{ $cliente[0]->PaisFiscal }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $pais->Descripcion }}">
                                                            {{ $pais->Descripcion }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>


                                            <!-- <div class="input-group">
                                                                <input id="PaisFiscal" name="PaisFiscal" type="text" class="form-control"
                                                                    placeholder="Pais" autocomplete="off"
                                                                    value="{{ $cliente[0]->PaisFiscal }}" readonly>
                                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="rounded-pill col-sm-12 bg-gray mb-1">
                                    <h6 class="text-bold text-center"></h6>
                                </div>
                                <div class="col-3">
                                    <label for="RFC">R.F.C.</label>
                                    <div class="input-group">
                                        <input id="RFC" name="RFC" type="text"
                                            class="form-control text-uppercase" placeholder="R.F.C." autocomplete="off"
                                            value="{{ $cliente[0]->RFC }}" onchange="validarInput(this)">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="NumRegIdTrib">Registro Id Tributario</label>
                                    <div class="input-group">
                                        <input id="NumRegIdTrib" name="NumRegIdTrib" type="text" class="form-control"
                                            placeholder="Registro Id Tributario" autocomplete="off"
                                            value="{{ $cliente[0]->NumRegIdTrib }}">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <label for="CreditoCondiciones">Condiciones</label>
                                    <select id="CreditoCondiciones" name="CreditoCondiciones" class="form-control"
                                        style="width: 100%">
                                        <option selected disabled>Condiciones</option>
                                        @foreach ($cat_condiciones as $condiciones)
                                            @if ($condiciones->Condicion == $cliente[0]->CreditoCondiciones)
                                                <option value="{{ $condiciones->Condicion }}" selected>
                                                    {{ $cliente[0]->CreditoCondiciones }}
                                                </option>
                                            @else
                                                <option value="{{ $condiciones->Condicion }}">
                                                    {{ $condiciones->Condicion }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="FiscalRegimen">Régimen</label>
                                    <select id="FiscalRegimen" name="FiscalRegimen" class="form-control"
                                        style="width: 100%">
                                        <option selected disabled>Régimen</option>
                                        @foreach ($cat_regimen as $regimen)
                                            @if ($regimen->idRegimen == $cliente[0]->FiscalRegimen)
                                                <option value="{{ $regimen->idRegimen }}" selected>
                                                    {{ $cliente[0]->FiscalRegimen }} -
                                                    {{ $cliente[0]->Regimen }}
                                                </option>
                                            @else
                                                <option value="{{ $regimen->idRegimen }}">
                                                    {{ $regimen->idRegimen }} -
                                                    {{ $regimen->Descripcion }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="rounded-pill col-sm-12 bg-gray mb-1">
                                    <h6 class="text-bold text-center"></h6>
                                </div>
                                <div class="col-4">
                                    <label for="limiteCredito">Límite de Crédito</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-bold">$</span>
                                        </div>
                                        <input id="limiteCredito" name="limiteCredito" type="text"
                                            class="form-control" placeholder="Límite de Crédito" autocomplete="off"
                                            value="{{ $cliente[0]->CreditoLimite }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="diasRevision">Días de Revisión</label>
                                    <div class="input-group">
                                        <input id="diasRevision" name="diasRevision" type="text" class="form-control"
                                            placeholder="Días de Revisión" autocomplete="off"
                                            value="{{ $cliente[0]->diasRevision }}">
                                    </div>
                                </div>
                                <div class="col-4 text-left" style="margin-top: 30px">
                                    <div class="custom-control custom-switch">
                                        @if (
                                            $cliente[0]->BloquearMorosos == 'SI' ||
                                                $cliente[0]->BloquearMorosos == 'Si' ||
                                                $cliente[0]->BloquearMorosos == 'Sí')
                                            <input id="BloquearMorosos" name="BloquearMorosos" type="checkbox"
                                                class="custom-control-input" checked>
                                        @else
                                            <input id="BloquearMorosos" name="BloquearMorosos" type="checkbox"
                                                class="custom-control-input">
                                        @endif
                                        <label class="custom-control-label" for="BloquearMorosos" role="button">Bloquear
                                            Morosos</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="LimiteDeCredito">Límite de Saldo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-bold">$</span>
                                        </div>
                                        <input id="LimiteDeCredito" name="LimiteDeCredito" type="text"
                                            class="form-control" placeholder="Límite de Saldo" autocomplete="off"
                                            value="{{ number_format($cliente[0]->LimiteDeCredito, 2) }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="idMetodoRevision">Método de Revisión</label>
                                    <select id="idMetodoRevision" name="idMetodoRevision" class="form-control">
                                        <option selected disabled>Método de Revisión</option>
                                        @foreach ($cat_metodo_revision as $metodo_revision)
                                            @if ($metodo_revision->idMetodoRevision == $cliente[0]->idMetodoRevision)
                                                <option value="{{ $metodo_revision->idMetodoRevision }}" selected>
                                                    {{ $cliente[0]->MetodoRevision }}
                                                </option>
                                            @else
                                                <option value="{{ $metodo_revision->idMetodoRevision }}">
                                                    {{ $metodo_revision->descMetodoRevision }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4 text-left" style="margin-top: 30px">
                                    <div class="custom-control custom-switch">
                                        @if ($cliente[0]->addendaEspecial == 1)
                                            <input id="addendaEspecial" name="addendaEspecial" type="checkbox"
                                                class="custom-control-input" checked>
                                        @else
                                            <input id="addendaEspecial" name="addendaEspecial" type="checkbox"
                                                class="custom-control-input">
                                        @endif
                                        <label class="custom-control-label" role="button" for="addendaEspecial">Req.
                                            Addenda
                                            Especial</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-4">
                                    <label for="CreditoLimitePedidos">Límite de Pedido</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-bold">$</span>
                                        </div>
                                        <input id="CreditoLimitePedidos" name="CreditoLimitePedidos" type="text"
                                            class="form-control" placeholder="Límite de Pedido" autocomplete="off"
                                            value="{{ number_format($cliente[0]->LimiteCredito, 2) }}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="idEntregaComplementoPago">Entrega complemento pago</label>
                                    <select id="idEntregaComplementoPago" name="idEntregaComplementoPago"
                                        class="form-control">
                                        <option selected disabled>Entrega complemento pago</option>
                                        @foreach ($cat_ent_compl_pago as $complemento_pago)
                                            @if ($complemento_pago->idEntregaComplementoPago == $cliente[0]->idEntregaComplementoPago)
                                                <option value="{{ $complemento_pago->idEntregaComplementoPago }}"
                                                    selected>
                                                    {{ $cliente[0]->descEntregaComplementoPago }}
                                                </option>
                                            @else
                                                <option value="{{ $complemento_pago->idEntregaComplementoPago }}">
                                                    {{ $complemento_pago->descEntregaComplementoPago }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="rounded-pill col-sm-12 bg-gray mb-1">
                                    <h6 class="text-bold text-center"></h6>
                                </div>
                                <div class="col-4">
                                    <label for="urlPortal">URL Portal</label>
                                    <div class="input-group">
                                        <input id="urlPortal" name="urlPortal" type="text" class="form-control"
                                            placeholder="URL Portal" autocomplete="off"
                                            value="{{ $cliente[0]->urlPortal }}" readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label for="usuarioPortal">Usuario Portal</label>
                                    <div class="input-group">
                                        <input id="usuarioPortal" name="usuarioPortal" type="text"
                                            class="form-control" placeholder="Usuario Portal" autocomplete="off"
                                            value="{{ $cliente[0]->usuarioPortal }}" readonly>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <label for="cvePortal">Clave Portal</label>
                                    <div class="input-group">
                                        <input id="cvePortal" name="cvePortal" type="text" class="form-control"
                                            placeholder="Clave Portal" autocomplete="off"
                                            value="{{ $cliente[0]->cvePortal }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label for="manualPortal">Manual Portal</label>
                                    <div class="input-group">
                                        <input id="manualPortal" name="manualPortal" type="text" class="form-control"
                                            placeholder="Manual Portal" autocomplete="off"
                                            value="{{ $cliente[0]->manualPortal }}" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src=" {{ asset('js/clientes_Docs.js') }} " defer></script>
    <script src=" {{ asset('js/llamadas.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/clientes_Oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/clientes.js?n=1') }} " defer></script>
    @include('modals.visorPDF')
    @include('clientes.mod_UpDocs')
    @include('contactos.create')
    @include('sucursales.create')
    @endsection @section('scripts')
    @include('partials.loading')
    @include('agenda.llamadas.addOrEdit');
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var detector = new MobileDetect(window.navigator.userAgent);

        if (detector.mobile() == null) {
            nav_item_general.style.width = '13%';
            nav_item_contactos.style.width = '13%';
            nav_item_sucursales.style.width = '13%';
            nav_item_oportunidades.style.visibility = 'visible';
            nav_item_documentos.style.visibility = 'visible';
            // nav_item_datos_admin.style.visibility = 'visible';
            nav_item_requisitos.style.visibility = 'visible';
            nav_item_oportunidades.style.width = '14%';
            nav_item_documentos.style.width = '14%';
            // nav_item_datos_admin.style.width = '16%';
            nav_item_requisitos.style.width = '17%';
        } else {
            nav_item_general.style.width = '33%';
            nav_item_contactos.style.width = '33%';
            nav_item_sucursales.style.width = '33%';
            nav_item_oportunidades.style.visibility = 'hidden';
            nav_item_documentos.style.visibility = 'hidden';
            // nav_item_datos_admin.style.visibility = 'hidden';
            nav_item_requisitos.style.visibility = 'hidden';
            nav_item_oportunidades.style.width = '0';
            nav_item_documentos.style.width = '0';
            // nav_item_datos_admin.style.width = '0';
            nav_item_requisitos.style.width = '0';
        }

        CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

        var idrol = document.getElementById('idRol')
        var igualdir = document.getElementById('igualDirCom')
        var codigos = document.getElementById('CodigoPostalLista');

        var calle = document.getElementById('Direccion');
        var numero = document.getElementById('DireccionNumero');
        var numeroint = document.getElementById('DireccionNumeroInt');
        var cp = document.getElementById('CodigoPostal');
        var alcaldia = document.getElementById('Alcaldia');
        var colonia = document.getElementById('Colonia');
        var poblacion = document.getElementById('Poblacion');
        var estado = document.getElementById('Estado');
        var pais = document.getElementById('Pais');

        var rsocial = document.getElementById('razonsocial');
        var nombrecomercial = document.getElementById('LITONombreComercial');
        var agte = document.getElementById('Agente');
        var tels = document.getElementById('Telefonos');
        var email = document.getElementById('eMail1');
        var giro = document.getElementById('Giro');
        var subgiro = document.getElementById('SubGiro');

        var codigosF = document.getElementById('CPFiscalLista');
        var calleF = document.getElementById('DireccionFiscal');
        var numeroF = document.getElementById('DireccionFiscalNumero');
        var numerointF = document.getElementById('DireccionFiscalNumeroInt');
        var cpF = document.getElementById('CPFiscal');
        var alcaldiaF = document.getElementById('AlcaldiaFiscal');
        var coloniaF = document.getElementById('ColoniaFiscal');
        var poblacionF = document.getElementById('PoblacionFiscal');
        var estadoF = document.getElementById('EstadoFiscal');
        var paisF = document.getElementById('PaisFiscal');

        var rfc = document.getElementById('RFC');
        var condiciones = document.getElementById('CreditoCondiciones');
        var regimen = document.getElementById('FiscalRegimen');

        var cpslista = document.getElementById('CodigoPostalLista');
        var btnsave = document.getElementById('btnSave');

        var idMetodoRevision = document.getElementById('idMetodoRevision');

        var urlPortal = document.getElementById('urlPortal');
        var usuarioPortal = document.getElementById('usuarioPortal');
        var cvePortal = document.getElementById('cvePortal');
        var manualPortal = document.getElementById('manualPortal');

        var limiteCredito = document.getElementById('limiteCredito');
        var LimiteDeCredito = document.getElementById('LimiteDeCredito');
        var CreditoLimitePedidos = document.getElementById('CreditoLimitePedidos');

        var cpSuc = document.getElementById('CodigoPostalSuc');
        var alcaldiaSuc = document.getElementById('DelegacionSuc');
        var coloniaSuc = document.getElementById('ColoniaSuc');
        var poblacionSuc = document.getElementById('PoblacionSuc');
        var estadoSuc = document.getElementById('EstadoSuc');
        var paisSuc = document.getElementById('PaisSuc');

        $('[data-bs-toggle="tooltip"]').tooltip();

        $(document).ready(function() {

            $("#DireccionSuc").bind('keypress', function(event) {
                soloAlfanumericos(event);
            });

            $('#DireccionSuc').bind('paste', function(e) {
                e.preventDefault();
            });

            $("#DireccionNumeroSuc").bind('keypress', function(event) {
                soloAlfanumericos(event);
            });

            $('#DireccionNumeroSuc').bind('paste', function(e) {
                e.preventDefault();
            });

            $("#DireccionNumeroIntSuc").bind('keypress', function(event) {
                soloAlfanumericos(event);
            });

            $('#DireccionNumeroIntSuc').bind('paste', function(e) {
                e.preventDefault();
            });

            validarRequeridos();

            usuarioAutCre();

            activarDatosPortalCliente();

            var id = $('#idClienteINT').val();
            var contacto_principal = $('#contactoPrincipal').val();
            tableContactos = $('#tableContactosCliente').DataTable({
                destroy: true,
                deferRender: true,
                processing: true,
                pageLength: 50,
                scrollY: '60vh',
                scrollX: true,
                serverSide: true,
                bLengthChange: true,
                autoWidth: false,
                stateSave: true,
                order: [0, "desc"],
                columnDefs: [{
                    targets: [8, 9, 10, 11],
                    searchable: false,
                    orderable: false,
                    className: 'dt-body-center text-center',
                }, {
                    className: 'dpass',
                    targets: [11],
                }],
                fixedColumns: {
                    leftColumns: 3,
                },
                colReorder: false,
                ajax: {
                    url: "{{ route('api.contactos') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        idClienteINT: id,
                        contacto_principal: contacto_principal,
                    },
                },
                columns: [{
                    data: 'idContactoINT',
                    name: 'idContactoINT',
                }, {
                    data: 'Tratamiento',
                    name: 'Tratamiento',
                }, {
                    data: 'Nombre',
                    "render": function(data, type, row) {
                        return '<a class="text-primary" type="button" onclick="editarContacto(' +
                            row.idContacto + ', ' + row.cto_principal +
                            ' )" title="Editar Contacto">' +
                            data + '</a>';
                    },
                    name: 'Nombre',
                }, {
                    data: 'Cargo',
                    name: 'Cargo',
                }, {
                    data: 'Departamento',
                    name: 'Departamento',
                }, {
                    data: 'Telefonos',
                    name: 'Telefonos',
                }, {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: "esEnvio",
                    render: function(data, type, row) {
                        if (row.inputRol == 1 || row.inputRol == 1013 || row.inputRol == 1015) {
                            if (data == 1) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="sucEnvio' + row.idContacto + '" checked><label onclick="contactoEnvio(' + row.idContacto + ', 0)" class="custom-control-label" style="cursor: pointer"></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="sucEnvio' + row.idContacto + '"><label onclick="contactoEnvio(' + row.idContacto + ', 1)" class="custom-control-label" style="cursor: pointer"></label></div>';
                            }
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="sucEnvio' + row.idContacto + '" disabled ' + (data == 1 ? 'checked' : '') + '><label class="custom-control-label" disabled></label></div>';
                        }
                    },
                    name: "esEnvio",
                    title: "Es Envío",
                }, {
                    data: 'enviar_cfd',
                    name: 'enviar_cfd',
                }, {
                    data: 'contacto_cxp',
                    name: 'contacto_cxp',
                }, {
                    data: 'principal',
                    name: 'principal',
                }, {
                    data: 'cto_activo',
                    name: 'cto_activo',
                }, {
                    data: 'idContactoINT',
                    "render": function(data) {
                        return '';
                    },
                }, ],
                language: {
                    url: "{{ asset('js/spanish-DT.json') }}"
                },
                dom: '<"top">rt<"bottom"lpB><"clear">',
                select: true,
                buttons: [{
                    extend: 'colvis',
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
                    text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                }, {
                    extend: 'excelHtml5',
                    title: 'Contactos | CANDY CRM',
                    text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                    exportOptions: {
                        orthogonal: 'export',
                        columns: "thead th:not(.noExport)",
                    },
                    filename: 'Contactos',
                    autoFilter: true,
                    sheetName: 'Lista',
                    messageTop: null,
                    body: true,
                    footer: false,
                }, ],
                lengthMenu: [
                    [15, 25, 50, 100],
                    [15, 25, 50, 100]
                ],
                "drawCallback": function(settings) {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
            });

            tableSucursales = $('#tableSucursalesCliente').DataTable({
                destroy: true,
                deferRender: true,
                processing: true,
                pageLength: 50,
                scrollY: false,
                scrollX: true,
                serverSide: true,
                bLengthChange: true,
                autoWidth: true,
                order: [0, "desc"],
                ajax: {
                    url: "{{ route('api.sucursales') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        idClienteINT: id,
                    },
                },
                columns: [{
                        data: "idClienteINT",
                        name: "idClienteINT",
                        title: "ID Cliente",
                    },
                    {
                        data: "razonsocial",
                        name: "razonsocial",
                        title: "Cliente",
                    },
                    {
                        data: "idSucursal",
                        name: "idSucursal",
                        title: "Id Sucursal",
                    },
                    {
                        data: "Estatus",
                        render: function(data, type, row) {
                            if (
                                row.inputRol == 1 ||
                                row.inputRol == 5 ||
                                row.inputRol == 7 ||
                                row.inputRol == 8 ||
                                row.inputRol == 1010 ||
                                row.inputRol == 1011
                            ) {
                                if ($.trim(row.inputEditar) === "Editar") {
                                    if ($.trim(data) === "BAJA") {
                                        return (
                                            '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="eliminarSucursal(' +
                                            row.idSucursal +
                                            ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                        );
                                    } else {
                                        return (
                                            '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="eliminarSucursal(' +
                                            row.idSucursal +
                                            ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                        );
                                    }
                                } else {
                                    if ($.trim(data) === "BAJA") {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                    } else {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                    }
                                }
                            } else {
                                if ($.trim(row.inputLogin) == $.trim(row.Login)) {
                                    if ($.trim(data) === "BAJA") {
                                        return (
                                            '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="eliminarSucursal(' +
                                            row.idSucursal +
                                            ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                        );
                                    } else {
                                        return (
                                            '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="eliminarSucursal(' +
                                            row.idSucursal +
                                            ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                        );
                                    }
                                } else {
                                    if ($.trim(data) === "BAJA") {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                    } else {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                    }
                                }
                            }
                        },
                        name: "Estatus",
                        title: "Estatus",
                    },
                    {
                        data: "principal",
                        render: function(data, type, row) {
                            if (
                                row.inputRol == 1 ||
                                row.inputRol == 5 ||
                                row.inputRol == 7 ||
                                row.inputRol == 8 ||
                                row.inputRol == 1010
                            ) {
                                if ($.trim(row.inputEditar) === "Editar") {
                                    if ($.trim(row.Estatus) === "BAJA") {
                                        if (data == 0) {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                        } else {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                        }
                                    } else {
                                        if (data == 0) {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="sucCtePrincipal(' +
                                                row.idSucursal +
                                                ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                        } else {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="sucCtePrincipal(' +
                                                row.idSucursal +
                                                ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                        }
                                    }
                                } else {
                                    if (data == 0) {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                    } else {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                    }
                                }
                            } else {
                                if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row
                                        .inputLogin) == $.trim(row.LoginAuxiliar)) {
                                    if ($.trim(row.inputEditar) === "Editar") {
                                        if ($.trim(row.Estatus) === "BAJA") {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label"></label></div>';
                                        } else {
                                            if (data == 0) {
                                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="sucCtePrincipal(' +
                                                    row.idSucursal +
                                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                            } else {
                                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="sucCtePrincipal(' +
                                                    row.idSucursal +
                                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                            }
                                        }
                                    } else {
                                        if ($.trim(data) == 0) {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                        } else {
                                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                        }
                                    }
                                } else {
                                    if ($.trim(data) == 0) {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                    } else {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                    }
                                }
                            }
                        },
                        name: "principal",
                        title: "Principal",
                    },
                    {
                        data: "Nombre",
                        render: function(data, type, row) {
                            if (
                                row.inputRol == 1 ||
                                row.inputRol == 5 ||
                                row.inputRol == 7 ||
                                row.inputRol == 8 ||
                                row.inputRol == 1010 ||
                                row.inputRol == 1011
                            ) {
                                if ($.trim(row.inputEditar) === "Editar") {
                                    return (
                                        '<a class="text-primary text-uppercase" type="button" onclick="editarSucursalCte(' +
                                        row.idSucursal +
                                        ", " +
                                        row.idClienteINT +
                                        ' )" title="Editar Sucursal">' +
                                        data +
                                        "</a>"
                                    );
                                } else {
                                    return data;
                                }
                            } else {
                                if (
                                    $.trim(row.inputLogin) == $.trim(row.Login) ||
                                    $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin)
                                ) {
                                    if ($.trim(row.inputEditar) === "Editar") {
                                        return (
                                            '<a class="text-primary text-uppercase" type="button" onclick="editarSucursalCte(' +
                                            row.idSucursal +
                                            ", " +
                                            row.idClienteINT +
                                            ' )" title="Editar Sucursal">' +
                                            data +
                                            "</a>"
                                        );
                                    } else {
                                        return data;
                                    }
                                } else {
                                    return data;
                                }
                            }
                        },
                        name: "Nombre",
                        title: "Nombre Sucursal",
                    },
                    {
                        data: "Encargado",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Encargado",
                        title: "Encargado",
                    },
                    {
                        data: "eMail1",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-lowercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "eMail1",
                        title: "Correo",
                    },
                    {
                        data: "Direccion",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Direccion",
                        title: "Calle",
                    },
                    {
                        data: "DireccionNumero",
                        render: function(data, type, row) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "DireccionNumero",
                        title: "Número Ext.",
                    },
                    {
                        data: "DireccionNumeroInt",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "DireccionNumeroInt",
                        title: "Número Int.",
                    },
                    {
                        data: "Colonia",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Colonia",
                        title: "Colonia",
                    },
                    {
                        data: "Delegacion",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Delegacion",
                        title: "Delegación",
                    },
                    {
                        data: "Poblacion",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Poblacion",
                        title: "Localidad",
                    },
                    {
                        data: "Estado",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Estado",
                        title: "Estado",
                    },
                    {
                        data: "Pais",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Pais",
                        title: "País",
                    },
                    {
                        data: "CodigoPostal",
                        render: function(data) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "CodigoPostal",
                        title: "Código Postal",
                    },
                    {
                        data: "Telefonos",
                        render: function(data, type, row) {
                            if (data != null) {
                                return '<span class="text-uppercase">' + data + "</span>";
                            } else {
                                return data;
                            }
                        },
                        name: "Telefonos",
                        title: "Telefonos",
                    },
                ],
                columnDefs: [{
                    className: "dt-center",
                    targets: [0, 2, 3, 4],
                }, ],
                fixedColumns: {
                    leftColumns: 5,
                },
                language: {
                    url: "{{ asset('js/spanish-DT.json') }}"
                },
                dom: '<"top">rt<"bottom"lpB><"clear">',
                select: true,
                buttons: [{
                        extend: "colvis",
                        columns: ":gt(0)",
                        text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                    },
                    {
                        extend: "excelHtml5",
                        title: "Sucursales Cliente | CANDY CRM",
                        text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                        exportOptions: {
                            orthogonal: "export",
                            columns: "thead th:not(.noExport)",
                        },
                        filename: "Sucursales Cliente",
                        autoFilter: true,
                        sheetName: "Lista",
                        messageTop: null,
                        body: true,
                        footer: false,
                    },
                ],
                bInfo: true,
                lengthMenu: [
                    [50, 100, 150, 200],
                    [50, 100, 150, 200],
                ],
                "drawCallback": function(settings) {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                initComplete: function(settings, json) {
                    // loaderOut();
                },
            });

            $(window).resize(function() {
                var height = window.innerHeight;
                if (height >= 320 && height <= 651) {
                    $('#tableContactosCliente').closest('.dataTables_scrollBody').css('height', '58vh');
                    $('#tableSucursalesCliente').closest('.dataTables_scrollBody').css('height', '58vh');
                    $('#tableOportunidadesCliente').closest('.dataTables_scrollBody').css('height',
                        '58vh');
                } else if (height >= 652 && height <= 768) {
                    $('#tableContactosCliente').closest('.dataTables_scrollBody').css('height', '61vh');
                    $('#tableSucursalesCliente').closest('.dataTables_scrollBody').css('height', '61vh');
                    $('#tableOportunidadesCliente').closest('.dataTables_scrollBody').css('height',
                        '61vh');
                } else if (height >= 769 && height <= 992) {
                    $('#tableContactosCliente').closest('.dataTables_scrollBody').css('height', '70vh');
                    $('#tableSucursalesCliente').closest('.dataTables_scrollBody').css('height', '70vh');
                    $('#tableOportunidadesCliente').closest('.dataTables_scrollBody').css('height',
                        '70vh');
                } else if (height >= 993 && height <= 1024) {
                    $('#tableContactosCliente').closest('.dataTables_scrollBody').css('height', '71vh');
                    $('#tableSucursalesCliente').closest('.dataTables_scrollBody').css('height', '71vh');
                    $('#tableOportunidadesCliente').closest('.dataTables_scrollBody').css('height',
                        '71vh');
                } else if (height >= 1025 && height <= 1366) {
                    $('#tableContactosCliente').closest('.dataTables_scrollBody').css('height', '75vh');
                    $('#tableSucursalesCliente').closest('.dataTables_scrollBody').css('height', '75vh');
                    $('#tableOportunidadesCliente').closest('.dataTables_scrollBody').css('height',
                        '75vh');
                } else {
                    $('#tableContactosCliente').closest('.dataTables_scrollBody').css('height', '77vh');
                    $('#tableSucursalesCliente').closest('.dataTables_scrollBody').css('height', '77vh');
                    $('#tableOportunidadesCliente').closest('.dataTables_scrollBody').css('height',
                        '77vh');
                }
                tableContactos.draw();
                tableSucursales.draw();
                // tableOportunidadesCliente.draw();
            });

            $("#CodigoPostalLista").autocomplete({
                delay: 0,
                minLength: 4,
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('codigosP') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    var codigo = JSON.stringify(ui.item.value)
                    $.ajax({
                        url: "{{ route('buscarCP') }}",
                        dataType: "json",
                        data: {
                            id_Codigo: codigo
                        },
                        success: function(data) {
                            cp.value = data[0].Codigo;
                            alcaldia.value = data[0].Municipio;
                            colonia.value = data[0].Asentamiento;
                            if (data[0].Localidad == null) {
                                poblacion.value = "";
                            } else {
                                poblacion.value = data[0].Localidad;
                            }
                            estado.value = data[0].Estado;
                            pais.value = data[0].Pais;
                        },
                        error: function(data) {
                            cp.value = "";
                            alcaldia.value = "";
                            colonia.value = "";
                            poblacion.value = "";
                            estado.value = "";
                            pais.value = "";
                        }

                    });
                    return false;
                }
            });

            $("#CPFiscalLista").autocomplete({
                delay: 0,
                minLength: 4,
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('codigosP') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    var codigo = JSON.stringify(ui.item.value)
                    $.ajax({
                        url: "{{ route('buscarCP') }}",
                        dataType: "json",
                        data: {
                            id_Codigo: codigo
                        },
                        success: function(data) {
                            cpF.value = data[0].Codigo;
                            alcaldiaF.value = data[0].Municipio;
                            coloniaF.value = data[0].Asentamiento;
                            if (data[0].Localidad == null) {
                                poblacionF.value = "";
                            } else {
                                poblacionF.value = data[0].Localidad;
                            }
                            estadoF.value = data[0].Estado;
                            paisF.value = data[0].Pais;
                        },
                        error: function(data) {
                            cpF.value = "";
                            alcaldiaF.value = "";
                            coloniaF.value = "";
                            poblacionF.value = "";
                            estadoF.value = "";
                            paisF.value = "";
                        }
                    });
                    return false;
                }
            });

            $("#CodigoPostalListaSucCte").autocomplete({
                delay: 0,
                minLength: 4,
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('codigosP') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    var codigo = JSON.stringify(ui.item.value)
                    $.ajax({
                        url: "{{ route('buscarCP') }}",
                        dataType: "json",
                        data: {
                            id_Codigo: codigo
                        },
                        success: function(data) {
                            cpSuc.value = data[0].Codigo;
                            alcaldiaSuc.value = data[0].Municipio;
                            coloniaSuc.value = data[0].Asentamiento;
                            if (data[0].Localidad == null) {
                                poblacionSuc.value = "";
                            } else {
                                poblacionSuc.value = data[0].Localidad;
                            }
                            estadoSuc.value = data[0].Estado;
                            paisSuc.value = data[0].Pais;

                            $("#ColoniaSuc").prop('required', false);
                            $("#DelegacionSuc").prop('required', false);
                            $("#PoblacionSuc").prop('required', false);
                            $("#EstadoSuc").prop('required', false);
                            $("#PaisSuc").prop('required', false);
                            $("#CodigoPostalSuc").prop('required', false);

                        },
                        error: function(data) {
                            cpSuc.value = "";
                            alcaldiaSuc.value = "";
                            coloniaSuc.value = "";
                            poblacionSuc.value = "";
                            estadoSuc.value = "";
                            paisSuc.value = "";

                            $("#ColoniaSuc").prop('required', true);
                            $("#DelegacionSuc").prop('required', true);
                            $("#PoblacionSuc").prop('required', false);
                            $("#EstadoSuc").prop('required', true);
                            $("#PaisSuc").prop('required', true);
                            $("#CodigoPostalSuc").prop('required', true);
                        }

                    });
                    return false;
                }
            });


            $('#modal-create-contacto').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
            });

            $("#modal-create-contacto").draggable({
                handle: ".titulo"
            });

            $('#modal-create-contacto').on('shown.bs.modal', function() {
                $('#Tratamiento').trigger('focus');
            });

            $('#modal-create-sucursal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
            });

            $("#modal-create-sucursal").draggable({
                handle: ".titulo"
            });

            $('#modal-create-sucursal').on('shown.bs.modal', function() {
                $('#NombreSuc').trigger('focus');
            });
        });

        passAutCre = "";

        function usuarioAutCre() {
            $.ajax({
                url: "{{ url('usuariosAutCredito') }}",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    if (data.length == 0) {
                        limiteCredito.readOnly = true;
                    } else {
                        limiteCredito.readOnly = false;
                        passAutCre = data[0].PasswordAutCredito;
                    }
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: 'center',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({
                        title: 'Ocurrió un error',
                    });
                }
            });
        }

        var mensaje = '';

        function agregarContacto() {
            mensaje = 'Contacto Agregado';
            save_method = "add";
            $('imput[name=_method]').val('POST');
            $('#modal-create-contacto').modal('show');
            $('.titulo').text('Agregar Contacto');
        }

        function editarContacto(id, ctoPrincipal) {
            mensaje = 'Contacto Actualizado';
            save_method = 'edit';
            $('imput[name=_method]').val('PATCH');
            $.ajax({
                url: "{{ url('contactos') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    $('#idCOntacto').val(data.idContacto);
                    $('#idContactoINT').val(data.idContactoINT);
                    $('#Tratamiento').val(data.Tratamiento);
                    $('#Nombre').val(data.Nombre);
                    $('#Cargo').val(data.Cargo);
                    $('#Departamento').val(data.Departamento);
                    $('#TelefonosCto').val(data.Telefonos);
                    $('#email').val(data.email);
                    if (data.CFD_Enviar == '0') {
                        $('#CFD_Enviar').prop('checked', false);
                    } else if (data.CFD_Enviar == '1') {
                        $('#CFD_Enviar').prop('checked', true);
                    }
                    if (data.contactoCXP == '0') {
                        $('#contactoCXP').prop('checked', false);
                    } else if (data.contactoCXP == '1') {
                        $('#contactoCXP').prop('checked', true);
                    }
                    if (data.idContactoINT == ctoPrincipal) {
                        $('#principal').prop('checked', true);
                    } else {
                        $('#principal').prop('checked', false);
                    }

                    $('#modal-create-contacto').modal('show');
                    $('.titulo').text('Editar Contacto');
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: 'center',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({
                        title: 'Ocurrió un error',
                    });
                }
            });
        }

        $(function() {
            $('#modal-create-contacto form').validator().on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#idContactoINT').val();
                    if (save_method == 'add') {
                        url = "{{ url('contactos') }}";
                        type = "POST";
                    } else {
                        url = "{{ url('contactos') . '/' }}" + id;
                        type = "PUT";
                    }
                    $.ajax({
                        url: url,
                        type: type,
                        data: $('#modal-create-contacto form').serialize(),
                        success: function(data) {
                            $('#modal-create-contacto').trigger("reset");
                            $('#modal-create-contacto').modal('hide');
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableContactos.draw();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: 'Ocurrió un error',
                            });
                        }
                    });
                    return false;
                }
            });
        });

        function updatePrincipal(id, principal, contacto) {
            let mensaje = 'Contacto establecido';
            let id_usuario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Contactos',
                text: "Activar/Desactivar Contacto Principal?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#999',
                cancelButtonText: 'No',
                confirmButtonText: 'Si',
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('update.principal') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            idCliente: id,
                            idUsuario: id_usuario.value,
                            Principal: principal,
                            idContacto: contacto,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableContactos.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: 'Ocurrió un error',
                            });
                        }
                    })
                }
            })

        }

        function updateContactoCXP(idCto, idCte, cxp) {
            let mensaje = 'Contacto Actualizado';
            let id_usuario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Contactos',
                text: "Activar/Desactivar Contacto CXP?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#999',
                cancelButtonText: 'No',
                confirmButtonText: 'Si',
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('update.contactocxp') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            idContacto: idCto,
                            idCliente: idCte,
                            cxp: cxp,
                            idUsuario: id_usuario.value,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableContactos.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: 'Ocurrió un error',
                            });
                        }
                    })
                }
            })

        }

        function updateEnviarCFD(idCto, idCte, cfd) {
            let mensaje = 'Contacto Actualizado';
            let id_usuario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Contactos',
                text: "Activar/Desactivar Enviar CFD?",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#999',
                cancelButtonText: 'No',
                confirmButtonText: 'Si',
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('update.contactocfd') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            idContacto: idCto,
                            idCliente: idCte,
                            cfd: cfd,
                            idUsuario: id_usuario.value,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableContactos.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: 'Ocurrió un error',
                            });
                        }
                    })
                }
            })

        }

        function updateActivo(idCto, idCte, activo) {
            if (activo == 0) {
                mensaje = 'Contacto Activo';
                text = 'Activar Contacto?'
            } else {
                mensaje = 'Contacto Inactivo';
                text = 'Inactivar Contacto?'
            }

            let id_usuario = document.getElementById('idUsuario');
            // Swal.fire({
            //     title: 'Contactos',
            //     text: text,
            //     showCancelButton: true,
            //     confirmButtonColor: '#3085d6',
            //     cancelButtonColor: '#999',
            //     cancelButtonText: 'No',
            //     confirmButtonText: 'Si',
            //     allowEnterKey: false,
            //     stopKeydownPropagation: true,
            //     reverseButtons: true,
            //     returnFocus: true
            // }).then((result) => {
            //     if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('update.activo') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            idContacto: idCto,
                            idCliente: idCte,
                            activo: activo,
                            title: mensaje,
                            idUsuario: id_usuario.value,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableContactos.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'warning',
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: 'Ocurrió un error',
                            });
                        }
                    })
            //     }
            // })

        }

        const $btnSave = document.querySelector("#btnSave");

        $('#formClienteEdit').submit(function(evento) {
            $btnSave.disabled = true;
        });

        $(document).ready(function() {
            loaderOut();

            if (idrol.value == 5 || idrol.value == 8 || idrol.value == 1 || idrol.value == 1017) {
                calleF.readOnly = false;
                numeroF.readOnly = false;
                numerointF.readOnly = false;
                // cpF.readOnly = false;
                // alcaldiaF.readOnly = false;
                // coloniaF.readOnly = false;
                // poblacionF.readOnly = false;
                // estadoF.readOnly = false;
                // paisF.readOnly = false;
                codigosF.disabled = false;
                rfc.readOnly = false;
                condiciones.readOnly = false;
                regimen.readOnly = false;
                document.getElementById("spanDirFiscal").style.display = "block";
            } else if (idrol.value == 1010) {

                $btnSave.disabled = false;

                rsocial.disabled = true;
                document.getElementById('oportunidades-tab').style.display = 'none';

                nodes_datos = document.getElementById('div_contactos_cliente_editar').getElementsByTagName('*');
                for (var i = 0; i < nodes_datos.length; i++) {
                    nodes_datos[i].disabled = true;
                }

                nodes_usocfdi = document.getElementById('ClaveUsoCFDI').getElementsByTagName('option');
                for (var i = 1; i < nodes_usocfdi.length; i++) {
                    nodes_usocfdi[i].disabled = false;
                }

                ClaveUsoCFDI.disabled = false;

            } else {
                calleF.readOnly = true;
                numeroF.readOnly = true;
                numerointF.readOnly = true;
                // cpF.readOnly = true;
                // alcaldiaF.readOnly = true;
                // coloniaF.readOnly = true;
                // poblacionF.readOnly = true;
                // estadoF.readOnly = true;
                // paisF.readOnly = true;
                codigosF.disabled = true;
                rfc.readOnly = true;
                condiciones.readOnly = true;
                regimen.readOnly = true;
                document.getElementById("spanDirFiscal").style.display = "none";
            }

            ajustarTablas();

            igualdir.addEventListener('change', function() {
                igualDireccionComercial(igualdir.checked);
            });

            function igualDireccionComercial(check) {
                if (check == true) {
                    calleF.value = calle.value;
                    numeroF.value = numero.value;
                    numerointF.value = numeroint.value;
                    cpF.value = cp.value;
                    alcaldiaF.value = alcaldia.value;
                    coloniaF.value = colonia.value;
                    poblacionF.value = poblacion.value;
                    estadoF.value = estado.value;
                    paisF.value = pais.value;
                    calleF.readOnly = true;
                    numeroF.readOnly = true;
                    numerointF.readOnly = true;
                    cpF.readOnly = true;
                    alcaldiaF.readOnly = true;
                    coloniaF.readOnly = true;
                    poblacionF.readOnly = false;
                    estadoF.readOnly = true;
                    paisF.readOnly = true;
                    codigosF.disabled = true;
                } else {
                    calleF.value = '';
                    numeroF.value = '';
                    numerointF.value = '';
                    cpF.value = '';
                    alcaldiaF.value = '';
                    coloniaF.value = '';
                    poblacionF.value = '';
                    estadoF.value = '';
                    paisF.value = '';
                    calleF.readOnly = false;
                    numeroF.readOnly = false;
                    numerointF.readOnly = false;
                    cpF.readOnly = false;
                    alcaldiaF.readOnly = false;
                    coloniaF.readOnly = false;
                    poblacionF.readOnly = false;
                    estadoF.readOnly = false;
                    paisF.readOnly = false;
                    codigosF.disabled = false;
                }
            }
        });

        limiteCredito.addEventListener('change', function() {
            if (this.value == "") {} else {
                Swal.fire({
                    title: 'Password de Autorización',
                    input: 'password',
                    showCancelButton: true,
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value != passAutCre) {
                            Swal.fire({
                                title: 'Password Incorrecto',
                                text: 'Intentalo de nuevo',
                            }).then((result) => {
                                $(this).trigger('focus');
                                this.value = "";
                            })
                        } else {
                            // this.value = formatCurrency("en-US", "USD", 2, this.value);
                            // this.value = addCommas(this.value);
                            $('#idUsuarioAutCredito').val($('#idUsuario').val());
                            $('#diasRevision').trigger('focus');
                        }
                    } else {
                        $(this).trigger('focus');
                        this.value = "";
                    }
                })
            }
        });

        // LimiteDeCredito.addEventListener('change', function() {
        //         this.value = addCommas(this.value);
        // });

        // CreditoLimitePedidos.addEventListener('change', function() {
        //         this.value = addCommas(this.value);
        // });

        // function formatCurrency (locales, currency, fractionDigits, number) {
        //     var formatted = new Intl.NumberFormat(locales, {
        //         style: 'currency',
        //         currency: currency,
        //         minimumFractionDigits: fractionDigits
        //     }).format(number);
        //     return formatted;
        // }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }

            if (x1 != "") {
                x3 = '.00';
            } else {
                x3 = '';
            }
            return x1 + x2 + x3;
        }

        function formatoMoneda(v) {
            v = v.toString();
            v = v.replace(/([^0-9\.]+)/g, '');
            v = v.replace(/^[\.]/, '');
            v = v.replace(/[\.][\.]/g, '');
            v = v.replace(/\.(\d)(\d)(\d)/g, '.$1$2');
            v = v.replace(/\.(\d{1,2})\./g, '.$1');
            v = v.toString().split('').reverse().join('').replace(/(\d{3})/g, '$1,');
            v = v.split('').reverse().join('').replace(/^[\,]/, '');
            return v;
        }

        rsocial.addEventListener('change', function() {
            validarRequeridos();
        });

        nombrecomercial.addEventListener('change', function() {
            validarRequeridos();
        });

        agte.addEventListener('change', function() {
            validarRequeridos();
        });

        tels.addEventListener('change', function() {
            validarRequeridos();
        });

        email.addEventListener('change', function() {
            validarRequeridos();
        });

        giro.addEventListener('change', function() {
            validarRequeridos();
        });

        subgiro.addEventListener('change', function() {
            validarRequeridos();
        });

        cpslista.addEventListener('change', function() {
            validarRequeridos();
        });

        calle.addEventListener('change', function() {
            validarRequeridos();
        });

        numero.addEventListener('change', function() {
            validarRequeridos();
        });

        cp.addEventListener('change', function() {
            validarRequeridos();
        });

        colonia.addEventListener('change', function() {
            validarRequeridos();
        });

        alcaldia.addEventListener('change', function() {
            validarRequeridos();
        });

        poblacion.addEventListener('change', function() {
            validarRequeridos();
        });

        estado.addEventListener('change', function() {
            validarRequeridos();
        });

        pais.addEventListener('change', function() {
            validarRequeridos();
        });

        idMetodoRevision.addEventListener('change', function() {
            activarDatosPortalCliente();
        });

        paisF.addEventListener('change', function() {
            habilitarCamposFiscales();
            if ($('#PaisFiscal').val() !== "MÉXICO" && $('#PaisFiscal').val() !== "PAÍS") {
                $('#NumRegIdTrib').prop('disabled', false);
            } else {
                $('#NumRegIdTrib').prop('disabled', true);
            }
        });

        CPFiscalLista.addEventListener('change', function() {
            habilitarCamposFiscales();
        });

        function activarDatosPortalCliente() {
            if (idMetodoRevision.value == "5") {
                urlPortal.readOnly = false;
                usuarioPortal.readOnly = false;
                cvePortal.readOnly = false;
                manualPortal.readOnly = false;
            } else {
                urlPortal.readOnly = true;
                usuarioPortal.readOnly = true;
                cvePortal.readOnly = true;
                manualPortal.readOnly = true;

                urlPortal.value = "";
                usuarioPortal.value = "";
                cvePortal.value = "";
                manualPortal.value = "";
            }
        }

        function validarRequeridos() {

            if (idrol.value != 7) {
                if (rsocial.value == "" || nombrecomercial.value == "" || agte.value == "Agente" || tels.value == "" || giro
                    .value == "Giro" || subgiro.value == "Sub Giro" ||
                    email.value == "" || calle.value == "" || numero.value == "" || cp.value == "" ||
                    colonia.value == "" || alcaldia.value == "" || poblacion.value == "" || estado.value == "" || pais
                    .value ==
                    "País") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (rsocial.value == "") {
                    rsocial.style.borderColor = 'red';
                } else {
                    rsocial.style.borderColor = 'green';
                }

                if (nombrecomercial.value == "") {
                    nombrecomercial.style.borderColor = 'red';
                } else {
                    nombrecomercial.style.borderColor = 'green';
                }

                if (agte.value == "Agente") {
                    agte.style.borderColor = 'red';
                } else {
                    agte.style.borderColor = 'green';
                }

                if (tels.value == "") {
                    tels.style.borderColor = 'red';
                } else {
                    tels.style.borderColor = 'green';
                }

                if (email.value == "") {
                    email.style.borderColor = 'red';
                } else {
                    email.style.borderColor = 'green';
                }

                if (giro.value == "Giro") {
                    giro.style.borderColor = 'red';
                } else {
                    giro.style.borderColor = 'green';
                }

                if (subgiro.value == "Sub Giro") {
                    subgiro.style.borderColor = 'red';
                } else {
                    subgiro.style.borderColor = 'green';
                }

                if (calle.value == "") {
                    calle.style.borderColor = 'red';
                } else {
                    calle.style.borderColor = 'green';
                }

                if (numero.value == "") {
                    numero.style.borderColor = 'red';
                } else {
                    numero.style.borderColor = 'green';
                }

                if (cp.value == "") {
                    cp.style.borderColor = 'red';
                } else {
                    cp.style.borderColor = 'green';
                }

                if (colonia.value == "") {
                    colonia.style.borderColor = 'red';
                } else {
                    colonia.style.borderColor = 'green';
                }

                if (alcaldia.value == "") {
                    alcaldia.style.borderColor = 'red';
                } else {
                    alcaldia.style.borderColor = 'green';
                }

                if (poblacion.value == "") {
                    poblacion.style.borderColor = 'red';
                } else {
                    poblacion.style.borderColor = 'green';
                }

                if (estado.value == "") {
                    estado.style.borderColor = 'red';
                } else {
                    estado.style.borderColor = 'green';
                }

                if (pais.value == "País") {
                    pais.style.borderColor = 'red';
                } else {
                    pais.style.borderColor = 'green';
                }
            } else {
                if (rsocial.value == "" || nombrecomercial.value == "" || agte.value == "Agente") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (rsocial.value == "") {
                    rsocial.style.borderColor = 'red';
                } else {
                    rsocial.style.borderColor = 'green';
                }

                if (nombrecomercial.value == "") {
                    nombrecomercial.style.borderColor = 'red';
                } else {
                    nombrecomercial.style.borderColor = 'green';
                }

                if (agte.value == "Agente") {
                    agte.style.borderColor = 'red';
                } else {
                    agte.style.borderColor = 'green';
                }

            }

            if ($('#Pais').val() === "MÉXICO" || $('#Pais').val() === "PAÍS") {
                CodigoPostal.readOnly = true;
                alcaldia.readOnly = true;
                colonia.readOnly = true;
                poblacion.readOnly = false;
                estado.readOnly = true;

                // $('#CodigoPostal').val('');
                // $('#Alcaldia').val('');
                // $('#Colonia').val('');
                // $('#Poblacion').val('');
                // $('#Estado').val('');
                // $('#CodigoPostalLista').val('');
            } else {
                CodigoPostal.readOnly = false;
                alcaldia.readOnly = false;
                colonia.readOnly = false;
                poblacion.readOnly = false;
                estado.readOnly = false;
            }
        }

        function habilitarCamposFiscales() {

            if ($('#PaisFiscal').val() !== "MÉXICO" || $('#PaisFiscal').val() === "PAÍS") {
                CPFiscal.readOnly = false;
                AlcaldiaFiscal.readOnly = false;
                ColoniaFiscal.readOnly = false;
                PoblacionFiscal.readOnly = false;
                EstadoFiscal.readOnly = false;

                // $('#CPFiscal').val('');
                // $('#AlcaldiaFiscal').val('');
                // $('#ColoniaFiscal').val('');
                // $('#PoblacionFiscal').val('');
                // $('#EstadoFiscal').val('');
                // $('#CPFiscalLista').val('');
            } else {
                CPFiscal.readOnly = true;
                AlcaldiaFiscal.readOnly = true;
                ColoniaFiscal.readOnly = true;
                PoblacionFiscal.readOnly = false;
                EstadoFiscal.readOnly = true;

            }

        }

        function agregarSucursalCte() {
            mensaje = "Sucursal Agregada";
            save_method = "add";
            $("imput[name=_method]").val("POST");
            $("#modal-create-sucursal").modal("show");
            // document.getElementById("mismaDireccion_check_validateSuc").style.display = "none";

            $("#DireccionSuc").prop('readOnly', false);
            $("#DireccionNumeroSuc").prop('readOnly', false);
            $("#DireccionNumeroIntSuc").prop('readOnly', false);
            $("#CodigoPostalListaSucCte").prop('readOnly', false);

            $("#DireccionSuc").prop('required', true);
            $("#DireccionNumeroSuc").prop('required', true);
            $("#ColoniaSuc").prop('required', true);
            $("#DelegacionSuc").prop('required', true);
            $("#PoblacionSuc").prop('required', false);
            $("#EstadoSuc").prop('required', true);
            $("#PaisSuc").prop('required', true);
            $("#CodigoPostalSuc").prop('required', true);

            id_Cliente_INT = document.getElementById('idClienteINT');

            // $.ajax({
            //     url: serve + 'getDireccionFiscal/' + id_Cliente_INT.value,
            //     type: 'GET',
            //     dataType: 'JSON',
            //     success: function(r) {
            //         $("#mismaDireccion_checkSuc").prop("disabled", true);
            //         document.getElementById("mismaDireccion_check_validateSuc").style.display =
            //             "block";
            //         $('#mismaDireccionSuc').val(0);
            //     },
            //     error: function() {
            //         $("#mismaDireccion_checkSuc").prop("disabled", false);
            //         document.getElementById("mismaDireccion_check_validateSuc").style.display =
            //             "none";
            //     }
            // });


            $(".tituloSuc").text("Agregar Sucursal");
        }

        function editarSucursalCte(id, idCliente) {
            mensaje = "Sucursal Actualizada";
            save_method = "edit";
            // labelValidate = document.getElementById("mismaDireccion_check_validateSuc");
            $("imput[name=_method]").val("PUT");
            $.ajax({
                url: "{{ url('sucursales') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // $("#mismaDireccion_checkSuc").prop("disabled", true);
                    // document.getElementById("mismaDireccion_check_validateSuc").style.display = "none";

                    // $.ajax({
                    //     url: serve + 'getDireccionFiscal/' + data.idClienteINT,
                    //     type: 'GET',
                    //     dataType: 'JSON',
                    //     success: function(r) {
                    //         $("#mismaDireccion_checkSuc").prop("disabled", true);
                    //         document.getElementById("mismaDireccion_check_validateSuc").style.display =
                    //             "block";
                    //     },
                    //     error: function(){
                    //         $("#mismaDireccion_checkSuc").prop("disabled", false);
                    //     }
                    // });

                    $("#idSucursal").val(data.idSucursal);
                    $("#idSucursalINT").val(data.idSucursalINT);
                    $("#idClienteINTSuc").val(data.idClienteINT);
                    $("#NombreSuc").val(data.Nombre);
                    $("#EncargadoSuc").val(data.Encargado);
                    $("#eMail1Suc").val(data.eMail1);
                    $("#DireccionSuc").val(data.Direccion);
                    $("#DireccionNumeroSuc").val(data.DireccionNumero);
                    $("#DireccionNumeroIntSuc").val(data.DireccionNumeroInt);
                    $("#ColoniaSuc").val(data.Colonia);
                    $("#DelegacionSuc").val(data.Delegacion);
                    $("#PoblacionSuc").val(data.Poblacion);
                    $("#EstadoSuc").val(data.Estado);
                    $("#PaisSuc").val(data.Pais);
                    $("#CodigoPostalSuc").val(data.CodigoPostal);
                    $("#TelefonosSuc").val(data.Telefonos);
                    // $("#mismaDireccionSuc").val(data.mismaDireccion);

                    // if (data.mismaDireccion == 1) {
                    //     $("#mismaDireccion_checkSuc").prop("checked", true);
                    //     $("#DireccionSuc").prop('readOnly', true);
                    //     $("#DireccionNumeroSuc").prop('readOnly', true);
                    //     $("#DireccionNumeroIntSuc").prop('readOnly', true);
                    //     $("#ColoniaSuc").prop('readOnly', true);
                    //     $("#DelegacionSuc").prop('readOnly', true);
                    //     $("#PoblacionSuc").prop('readOnly', true);
                    //     $("#EstadoSuc").prop('readOnly', true);
                    //     $("#PaisSuc").prop('readOnly', true);
                    //     $("#CodigoPostalSuc").prop('readOnly', true);
                    //     $("#CodigoPostalListaSucCte").prop('readOnly', true);
                    //     $("#mismaDireccion_check_validateSuc").val('Esta es la sucursal con la dirección fiscal');

                    // } else {
                    //     $("#mismaDireccion_checkSuc").prop("checked", false);
                    //     $("#DireccionSuc").prop('readOnly', false);
                    //     $("#DireccionNumeroSuc").prop('readOnly', false);
                    //     $("#DireccionNumeroIntSuc").prop('readOnly', false);
                    //     $("#CodigoPostalListaSucCte").prop('readOnly', false);
                    //     // $("#ColoniaSuc").prop('readOnly', false);
                    //     // $("#DelegacionSuc").prop('readOnly', false);
                    //     // $("#PoblacionSuc").prop('readOnly', false);
                    //     // $("#EstadoSuc").prop('readOnly', false);
                    //     // $("#PaisSuc").prop('readOnly', false);
                    //     // $("#CodigoPostalSuc").prop('readOnly', false);
                    // }

                    $("#modal-create-sucursal").modal("show");
                    $(".tituloSuc").text("Editar Sucursal");
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (id == null) {
                        titleMsg = "Id Nulo";
                    } else {
                        titleMsg = "Ocurrió un error";
                    }
                    Msg.fire({
                        title: titleMsg,
                    });
                },
            });
        }

        var idClienteINT = document.getElementById('idClienteINT');
        var idClienteINTSuc = document.getElementById('idClienteINTSuc');
        // var direccion_chek = document.getElementById('mismaDireccion_checkSuc');
        var id_Cliente_INT = idClienteINT.value;

        // direccion_chek.addEventListener("change", function() {
        //     id_Cliente_INT = idClienteINT.value;
        //     direccionPrincipal(direccion_chek.checked, id_Cliente_INT);
        // });

        // function direccionPrincipal(check, id_Cliente_INT) {

        //     idCliente = id_Cliente_INT;

        //     if (check == true) {

        //         $('#mismaDireccionSuc').val(1);
        //         $("#CodigoPostalListaSucCte").val('');
        //         $("#CodigoPostalListaSucCte").prop('readOnly', true);
        //         $("#saveBtnSuc").prop('disable', false);

        //         $.ajax({
        //             url: "{{ url('direccionPrincipal') . '/' }}" + idCliente,
        //             type: "GET",
        //             dataType: "JSON",
        //             success: function(data) {

        //                 $("#DireccionSuc").val(data.DirecccionFiscal);
        //                 $("#DireccionNumeroSuc").val(data.DireccionFiscalNumero);
        //                 $("#DireccionNumeroIntSuc").val(data.DireccionFiscalNumeroInt);
        //                 $("#ColoniaSuc").val(data.ColoniaFiscal);
        //                 $("#DelegacionSuc").val(data.AlcaldiaFiscal);
        //                 $("#PoblacionSuc").val(data.PoblacionFiscal);
        //                 $("#EstadoSuc").val(data.EstadoFiscal);
        //                 $("#PaisSuc").val(data.PaisFiscal);
        //                 $("#CodigoPostalSuc").val(data.CPFiscal);

        //                 $("#DireccionSuc").prop('readOnly', true);
        //                 $("#DireccionNumeroSuc").prop('readOnly', true);
        //                 $("#DireccionNumeroIntSuc").prop('readOnly', true);

        //                 $("#DireccionSuc").prop('required', false);
        //                 $("#DireccionNumeroSuc").prop('required', false);
        //                 $("#ColoniaSuc").prop('required', false);
        //                 $("#DelegacionSuc").prop('required', false);
        //                 $("#PoblacionSuc").prop('required', false);
        //                 $("#EstadoSuc").prop('required', false);
        //                 $("#PaisSuc").prop('required', false);
        //                 $("#CodigoPostalSuc").prop('required', false);

        //             },
        //             error: function() {
        //                 const Msg = Swal.mixin({
        //                     toast: true,
        //                     position: "center",
        //                     icon: "warning",
        //                     showConfirmButton: false,
        //                     timer: 3000,
        //                 });

        //                 if (idCliente == null) {
        //                     titleMsg = "Id Nulo";
        //                 } else {
        //                     titleMsg = "Ocurrió un error";
        //                 }
        //                 Msg.fire({
        //                     title: titleMsg,
        //                 });
        //             },
        //         });
        //     } else {

        //         $('#mismaDireccionSuc').val(0);

        //         $("#CodigoPostalListaSucCte").prop('readOnly', false);

        //         $("#DireccionSuc").prop('readOnly', false);
        //         $("#DireccionNumeroSuc").prop('readOnly', false);
        //         $("#DireccionNumeroIntSuc").prop('readOnly', false);


        //         $("#DireccionSuc").prop('required', true);
        //         $("#DireccionNumeroSuc").prop('required', true);
        //         $("#ColoniaSuc").prop('required', true);
        //         $("#DelegacionSuc").prop('required', true);
        //         $("#PoblacionSuc").prop('required', true);
        //         $("#EstadoSuc").prop('required', true);
        //         $("#PaisSuc").prop('required', true);
        //         $("#CodigoPostalSuc").prop('required', true);

        //         $("#DireccionSuc").val("");
        //         $("#DireccionNumeroSuc").val("");
        //         $("#DireccionNumeroIntSuc").val("");
        //         $("#ColoniaSuc").val("");
        //         $("#DelegacionSuc").val("");
        //         $("#PoblacionSuc").val("");
        //         $("#EstadoSuc").val("");
        //         $("#PaisSuc").val("");
        //         $("#CodigoPostalSuc").val("");

        //     }
        // }

        function contactoEnvio(idContacto, check) {
            let checkValue = check;

            let formdata = new FormData();
            formdata.append("_token", CSRF_TOKEN);
            formdata.append("esEnvio", checkValue);
            formdata.append("idContacto", idContacto);

            Swal.fire({
                title: "Contactos",
                text: (checkValue == 1 ? "Marcar" : "Desmarcar") + ' como "Contacto de Envío" el Contacto Seleccionado?',
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                allowEnterKey: false,
                stopKeydownPropagation: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: serve + "actualizarEnvio",
                        type: "POST",
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data.success) {
                                const Msg = Swal.mixin({
                                    toast: true,
                                    position: "center",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 3000,
                                });

                                Msg.fire({
                                    title: 'Contacto ' + (checkValue == 1 ? "marcado" : "desmarcado") + ' como "Contacto de Envío"',
                                });
                                tableContactos.ajax.reload();
                            }
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: "Ocurrió un error, vuelve a intentarlo",
                            });
                            tableContactos.ajax.reload();
                        }
                    });
                } else {
                    document.getElementById("contactoEnvio" + idContacto).checked = !checkValue;
                }
            });
        }

        function eliminarSucursal(id) {
            mensaje = "Sucursal Actualizada";
            Swal.fire({
                title: "Sucursales",
                text: "Activar/Desactivar Sucursal Seleccionada?",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('sucursal.darDeBajaSucursal') }}",
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id,
                            _token: CSRF_TOKEN,
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableSucursales.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: "Ocurrió un error",
                            });
                        },
                    });
                }
            });
        }

        $(function() {
            $("#modal-create-sucursal form").validator().on("submit", function(e) {
                if (!e.isDefaultPrevented()) {
                    var id = $("#idSucursal").val();
                    if (save_method == "add") {
                        url = "{{ url('sucursales') }}";
                        method = "POST";
                    } else {
                        url = "{{ url('sucursales') . '/' }}" + id;
                        method = "PUT";
                    }
                    $.ajax({
                        url: url,
                        method: method,
                        dataType: "json",
                        data: $("#modal-create-sucursal form").serialize(),
                        success: function() {
                            $("#modal-create-sucursal").trigger("reset");
                            $("#modal-create-sucursal").modal("hide");
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableSucursales.ajax.reload();
                        },
                        error: function() {
                            $("#modal-create-sucursal").trigger("reset");
                            $("#modal-create-sucursal").modal("hide");
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableSucursales.ajax.reload();
                        },
                    });
                    return false;
                }
            });
        });

        function sucCtePrincipal(id) {
            mensaje = "Sucursal Actualizada";
            Swal.fire({
                title: "Sucursales",
                text: "Activar/Desactivar principal de la Sucursal Seleccionada?",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('sucPrincipal') }}",
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id,
                            _token: CSRF_TOKEN,
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            tableSucursales.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: "Ocurrió un error",
                            });
                        },
                    });
                }
            });
        }

        function soloAlfanumericos(event) {
            var regex = new RegExp("^[a-zA-Z0-9 ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        }
    </script>
@endsection
