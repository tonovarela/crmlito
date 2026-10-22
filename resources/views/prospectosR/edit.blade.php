@extends('layout.master')
@section('content')
    @php
        $checked = '';
    @endphp
    <div id="formProspectoEdit" class="col-lg-12">
        <form method="post" action="{{ route('prospectos.update', $prospecto->idProspecto) }}" role="form">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" value="{{ $prospecto->idProspecto }}" name="idProspecto" id="idProspecto">
            <input type="hidden" value="0" name="idClienteINT" id="idClienteINT">
            <input type="hidden" value="{{ $idrol }}" name="idRol" id="idRol">
            <input type="hidden" value="{{ $prospecto->idUsuarioAutLimiteCredito }}" name="idUsuarioAutCredito"
                id="idUsuarioAutCredito">
            <input type="hidden" value="{{ $prospecto->fecModificacionLimiteCredito }}" name="fecModificacionLimiteCredito"
                id="fecModificacionLimiteCredito">
            <div class="pt-2">
                <div class="rounded-pill bg-gray">
                    <div class="row mb-2">
                        <div class="col-12 text-center">
                            <h6 class="text-bold pt-1 pl-1">Editar Prospecto</h6>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="input-group input-group-sm">
                        <h6 class="col-md-1 text-right text-bold text-red pr-1 pt-2">&nbsp;No.
                            {{ $prospecto->NumeroProspecto }}
                            -
                            &nbsp;
                        </h6>
                        <label class="mt-2" for="razonsocial">Razon Social <b class="asterisco">*</b>
                            &nbsp;</label>
                        <input type="text" class="form-control col-lg-8 text-uppercase" id="razonsocial"
                            name="razonsocial" placeholder="Requerido" autocomplete="off" autofocus
                            value="{{ $prospecto->razonsocial }}">
                        <div class="input-group-append col-lg-4 mt-1" style="float: right">
                            @if ($idrol == 1 || $idrol == 8)
                                <span class="col-4" id="sbtnSave"><button type="submit" id="btnSave"
                                        class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                                <span class="col-4 center" id="sbtnBack"><a id="btnBack" onclick="regresar()"
                                        class="btn btn-secondary btn-sm btn-block">Regresar</a></span>
                                <span class="col-4 mt-1" id="sbtnActivar" style="text-align: right">
                                    <div class="custom-switch">
                                        @if ($prospecto->Activo == 0)
                                            <input type="checkbox" class="custom-control-input">
                                        @else
                                            <input type="checkbox" class="custom-control-input" checked>
                                        @endif
                                        <label
                                            onclick="eliminarProspectoEdit({{ $prospecto->idProspecto }}, {{ $prospecto->Activo }})"
                                            class="custom-control-label" style="cursor: pointer">Validar
                                        </label>
                                    </div>
                                </span>
                            @else
                                <span class="col-6" id="sbtnSave"><button type="submit" id="btnSave"
                                        class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                                <span class="col-6 center" id="sbtnBack"><a id="btnBack" onclick="regresar()"
                                        class="btn btn-secondary btn-sm btn-block">Regresar</a></span>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <ul class="nav nav-tabs col-lg-12" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item col-lg-3" id="nav_item_generalP">
                            <a class="nav-link active" id="generalP-tab" onclick="mostrarGeneralP()" data-toggle="pill"
                                role="tab" aria-controls="generalP-tab" aria-selected="true"
                                style="cursor: pointer">General</a>
                        </li>
                        {{-- <li class="nav-item" id="nav_item_contactos" style="width: 13%">
                            <a class="nav-link" id="contactos-tab" onclick="mostrarContactos()" data-toggle="pill"
                                role="tab" aria-controls="contactos-tab" aria-selected="false"
                                style="cursor: pointer">Contactos</a>
                        </li> --}}
                        {{-- <li class="nav-item" id="nav_item_sucursales" style="width: 13%">
                            <a class="nav-link" id="sucursales-tab" onclick="mostrarSucursales()" data-toggle="pill"
                                role="tab" aria-controls="sucursales-tab" aria-selected="false"
                                style="cursor: pointer">Sucursales</a>
                        </li> --}}
                        <li class="nav-item col-lg-3" id="nav_item_oportunidadesP">
                            <a class="nav-link" id="oportunidadesP-tab" onclick="mostrarOportunidadesP()" data-toggle="pill"
                                role="tab" aria-controls="oportunidadesP-tab" aria-selected="false"
                                style="cursor: pointer">Oportunidades</a>
                        </li>
                        <li class="nav-item col-lg-3" id="nav_item_documentosP">
                            <a class="nav-link" id="documentosP-tab" onclick="mostrarDocumentosP()" data-toggle="pill"
                                role="tab" aria-controls="documentosP-tab" aria-selected="false"
                                style="cursor: pointer">Documentos</a>
                        </li>
                        @if ($idrol == 5 || $idrol == 1 || $idrol == 8 || $idrol == 1010 || $idrol == 1017)
                            <li class="nav-item col-lg-3" id="nav_item_datos_adminP">
                                <a class="nav-link" id="datos-adminP-tab" onclick="mostrarDatosAdminP()"
                                    data-toggle="pill" role="tab" aria-controls="datos-adminP-tab"
                                    aria-selected="false" style="cursor: pointer">Datos Administrativos</a>
                            </li>
                        @endif
                        {{-- <li class="nav-item" id="nav_item_requisitos" style="width: 17%">
                            <a class="nav-link" id="requisitos-especiales-tab" onclick="mostrarRequisitos()"
                                data-toggle="pill" role="tab" aria-controls="requisitos-especiales-tab"
                                aria-selected="false" style="cursor: pointer">Requisitos Especiales</a>
                        </li> --}}
                    </ul>
                    <div class="tab-content" id="div_contactos_prospecto_editar">
                        <div class="tab-pane fade show active" id="generalP" role="tabpanel"
                            aria-labelledby="generalP-tab">
                            <div class="row">
                                <div class="col-lg-9">
                                    <div class="col-lg-12 mt-1 pb-2">
                                        <div class="mt-1 mb-1 row">
                                            <div class="rounded-pill col-sm-12 bg-gray mb-1">
                                                <h6 class="text-bold text-center">General</h6>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row mb-1">
                                                    <div class="col-lg-4">
                                                        <label for="LITONombreComercial">Nombre Comercial <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="LITONombreComercial" name="LITONombreComercial"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Nombre Comercial" autocomplete="off"
                                                                value="{{ $prospecto->LITONombreComercial }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="DirInternet">Pagina WEB</label>
                                                        <div class="input-group">
                                                            <input type="text" id="DirInternet" name="DirInternet"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Página WEB" autocomplete="off"
                                                                value="{{ $prospecto->DirInternet }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Agente">Agente <b class="asterisco">*</b></label>
                                                        <select id="Agente" name="Agente" class="form-control"
                                                            style="width: 100%">
                                                            <option selected disabled>Agente</option>
                                                            @foreach ($agentes as $agente)
                                                                @if ($agente->agente == $prospecto->Agente)
                                                                    <option value="{{ $agente->agente }}" selected>
                                                                        {{ $prospecto->Agente }} -
                                                                        {{ $prospecto->NombreAgente }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $agente->agente }}">
                                                                        {{ $agente->agente }} - {{ $agente->nombre }}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-lg-1">
                                                        <label for="Lada1">Lada 1</label>
                                                        <div class="input-group">
                                                            <input id="Lada1" name="Lada1" type="text"
                                                                class="form-control text-uppercase" placeholder="Lada 1"
                                                                autocomplete="off" value="{{ $prospecto->Lada1 }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos">Teléfonos <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Telefonos" name="Telefonos" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Teléfonos" autocomplete="off"
                                                                value="{{ $prospecto->Telefonos }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label for="Extencion1">Ext 1</label>
                                                        <div class="input-group">
                                                            <input id="Extencion1" name="Extencion1" type="text"
                                                                class="form-control text-uppercase" placeholder="Ext 1"
                                                                autocomplete="off" value="{{ $prospecto->Extencion1 }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label for="Lada2">Lada 2</label>
                                                        <div class="input-group">
                                                            <input id="Lada2" name="Lada2" type="text"
                                                                class="form-control text-uppercase" placeholder="Lada 2"
                                                                autocomplete="off" value="{{ $prospecto->Lada2 }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos2">Teléfonos 2</label>
                                                        <div class="input-group">
                                                            <input id="Telefonos2" name="Telefonos2" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Teléfonos2" autocomplete="off"
                                                                value="{{ $prospecto->Telefonos2 }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <label for="Extencion2">Ext 2</label>
                                                        <div class="input-group">
                                                            <input id="Extencion2" name="Extencion2" type="text"
                                                                class="form-control text-uppercase" placeholder="Ext 2"
                                                                autocomplete="off" value="{{ $prospecto->Extencion2 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    @if ($idrol == 5 || $idrol == 1 || $idrol == 8 || $idrol == 1010 || $id_usuario == 63)
                                                        <div class="col-lg-3">
                                                            <label for="eMail1">Email 1 <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="eMail1" name="eMail1" type="text"
                                                                    class="form-control text-lowercase"
                                                                    placeholder="Email" autocomplete="off"
                                                                    value="{{ $prospecto->eMail }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <label for="Giro">Giro <b class="asterisco">*</b></label>
                                                            <select id="Giro" name="Giro" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Giro</option>
                                                                @foreach ($cat_giros as $giro)
                                                                    @if ($giro->Giro == $prospecto->Giro)
                                                                        <option value="{{ $giro->Giro }}" selected>
                                                                            {{ $prospecto->Giro }}
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
                                                            <label for="SubGiro">Sub Giro <b
                                                                    class="asterisco">*</b></label>
                                                            <select id="SubGiro" name="SubGiro" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Sub Giro</option>
                                                                @foreach ($cat_subgiros as $subgiro)
                                                                    @if ($subgiro->subgiro == $prospecto->SubGiro)
                                                                        <option value="{{ $subgiro->subgiro }}" selected>
                                                                            {{ $prospecto->SubGiro }}
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
                                                            <select id="ClaveUsoCFDI" name="ClaveUsoCFDI"
                                                                class="form-control" style="width: 100%">
                                                                <option selected disabled>Clave Uso CFDI</option>
                                                                @foreach ($uso_cfdi as $cfdi)
                                                                    @if ($cfdi->ClaveUsoCFDI == $prospecto->ClaveUsoCFDI)
                                                                        <option value="{{ $cfdi->ClaveUsoCFDI }}"
                                                                            selected>
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
                                                            <label for="eMail1">Email 1 <b
                                                                    class="asterisco">*</b></label>
                                                            <div class="input-group">
                                                                <input id="eMail1" name="eMail1" type="text"
                                                                    class="form-control text-lowercase"
                                                                    placeholder="Email" autocomplete="off"
                                                                    value="{{ $prospecto->eMail }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="Giro">Giro <b class="asterisco">*</b></label>
                                                            <select id="Giro" name="Giro" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Giro</option>
                                                                @foreach ($cat_giros as $giro)
                                                                    @if ($giro->Giro == $prospecto->Giro)
                                                                        <option value="{{ $giro->Giro }}" selected>
                                                                            {{ $prospecto->Giro }}
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
                                                            <label for="SubGiro">Sub Giro <b
                                                                    class="asterisco">*</b></label>
                                                            <select id="SubGiro" name="SubGiro" class="form-control"
                                                                style="width: 100%">
                                                                <option selected disabled>Sub Giro</option>
                                                                @foreach ($cat_subgiros as $subgiro)
                                                                    @if ($subgiro->subgiro == $prospecto->SubGiro)
                                                                        <option value="{{ $subgiro->subgiro }}" selected>
                                                                            {{ $prospecto->SubGiro }}
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-1 pb-2">
                                        <div class="mt-1 mb-1 row">
                                            <div class="rounded-pill col-sm-12 bg-gray">
                                                <h6 class="text-bold text-center">Dirección</h6>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="row mb-1">
                                                    <div class="col-md-5">
                                                        <label for="Direccion">Calle <b class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Direccion" name="Direccion" type="text"
                                                                class="form-control text-uppercase" placeholder="Calle"
                                                                autocomplete="off" value="{{ $prospecto->Direccion }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="DireccionNumero">Número Exterior <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="DireccionNumero" name="DireccionNumero"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Número Exterior" autocomplete="off"
                                                                value="{{ $prospecto->DireccionNumero }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="DireccionNumeroInt">Número Interior</label>
                                                        <div class="input-group">
                                                            <input id="DireccionNumeroInt" name="DireccionNumeroInt"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Número Interior" autocomplete="off"
                                                                value="{{ $prospecto->DireccionNumeroInt }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="CodigoPostalLista">Códigos Postales</label>
                                                        <div class="input-group">
                                                            <input id="CodigoPostalLista" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Código Postal" autocomplete="off"
                                                                autofill="off">
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
                                                            <input id="CodigoPostal" name="CodigoPostal" type="text"
                                                                class="form-control" placeholder="Código Postal"
                                                                autocomplete="off" value="{{ $prospecto->CodigoPostal }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Alcaldia">Alcaldí­a <b class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Alcaldia" name="Alcaldia" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Alcaldí­a" autocomplete="off"
                                                                value="{{ $prospecto->Alcaldia }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Colonia">Colonia <b class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Colonia" name="Colonia" type="text"
                                                                class="form-control text-uppercase" placeholder="Colonia"
                                                                autocomplete="off" value="{{ $prospecto->Colonia }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Poblacion">Localidad <b
                                                                class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Poblacion" name="Poblacion" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Localidad" autocomplete="off"
                                                                value="{{ $prospecto->Poblacion }}"
                                                                @if ($idrol != 5) readonly @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Estado">Estado <b class="asterisco">*</b></label>
                                                        <div class="input-group">
                                                            <input id="Estado" name="Estado" type="text"
                                                                class="form-control text-uppercase" placeholder="Estado"
                                                                autocomplete="off" value="{{ $prospecto->Estado }}"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Pais">Paí­s <b class="asterisco">*</b></label>
                                                        <select id="Pais" name="Pais" class="form-control"
                                                            style="width: 100%">
                                                            <option disabled selected>País</option>
                                                            @foreach ($paises as $pais)
                                                                @if (mb_convert_encoding($pais->Descripcion ?? '', 'ISO-8859-1', 'UTF-8') === mb_convert_encoding($prospecto->Pais ?? '', 'ISO-8859-1', 'UTF-8'))
                                                                    <option value="{{ $pais->Descripcion }}" selected>
                                                                        {{ $prospecto->Pais }}
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
                                        <div class="row mb-1 col-12">
                                            <div class="col-lg-4">
                                                <div class="row mb-1 pr-1">
                                                    <div class="rounded-pill col-lg-12 bg-gray">
                                                        <h6 class="text-bold text-center">Contacto Principal</h6>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="TratamientoContacto">Tratamiento</label>
                                                        <div class="input-group">
                                                            <input id="TratamientoContacto" name="TratamientoContacto"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Tratamiento" autocomplete="off"
                                                                value="{{ $prospecto->TratamientoContacto }}" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="NombreContacto">Nombre</label>
                                                        <div class="input-group">
                                                            <input id="NombreContacto" name="NombreContacto"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Nombre" autocomplete="off"
                                                                value="{{ $prospecto->NombreContacto }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="CargoContacto">Cargo</label>
                                                        <div class="input-group">
                                                            <input id="CargoContacto" name="CargoContacto" type="text"
                                                                class="form-control text-uppercase" placeholder="Cargo"
                                                                autocomplete="off"
                                                                value="{{ $prospecto->CargoContacto }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">
                                                        <label for="DepartamentoContacto">Departamento</label>
                                                        <div class="input-group">
                                                            <input id="DepartamentoContacto" name="DepartamentoContacto"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Departamento" autocomplete="off"
                                                                value="{{ $prospecto->DepartamentoContacto }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos1Contacto">Teléfonos</label>
                                                        <div class="input-group">
                                                            <input id="Telefonos1Contacto" name="Telefonos1Contacto"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Teléfonos" autocomplete="off"
                                                                value="{{ $prospecto->Telefonos1Contacto }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="eMailContacto">Correo</label>
                                                        <div class="input-group">
                                                            <input id="eMailContacto" name="eMailContacto" type="text"
                                                                class="form-control text-lowercase" placeholder="Correo"
                                                                autocomplete="off"
                                                                value="{{ $prospecto->eMailContacto }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="row mb-1 pr-1">
                                                    <div class="rounded-pill col-lg-12 bg-gray">
                                                        <h6 class="text-bold text-center">Contacto CXP</h6>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="TratamientoContactoCXP">Tratamiento</label>
                                                        <div class="input-group">
                                                            <input id="TratamientoContactoCXP"
                                                                name="TratamientoContactoCXP" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Tratamiento" autocomplete="off"
                                                                value="{{ $prospecto->TratamientoContactoCXP }}"
                                                                autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="NombreContactoCXP">Nombre</label>
                                                        <div class="input-group">
                                                            <input id="NombreContactoCXP" name="NombreContactoCXP"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Nombre" autocomplete="off"
                                                                value="{{ $prospecto->NombreContactoCXP }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="CargoContactoCXP">Cargo</label>
                                                        <div class="input-group">
                                                            <input id="CargoContactoCXP" name="CargoContactoCXP"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Cargo" autocomplete="off"
                                                                value="{{ $prospecto->CargoContactoCXP }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">
                                                        <label for="DepartamentoContactoCXP">Departamento</label>
                                                        <div class="input-group">
                                                            <input id="DepartamentoContactoCXP"
                                                                name="DepartamentoContactoCXP" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Departamento" autocomplete="off"
                                                                value="{{ $prospecto->DepartamentoContactoCXP }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos1ContactoCXP">Teléfonos</label>
                                                        <div class="input-group">
                                                            <input id="Telefonos1ContactoCXP" name="Telefonos1ContactoCXP"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Teléfonos" autocomplete="off"
                                                                value="{{ $prospecto->Telefonos1ContactoCXP }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="eMailContactoCXP">Correo</label>
                                                        <div class="input-group">
                                                            <input id="eMailContactoCXP" name="eMailContactoCXP"
                                                                type="text" class="form-control text-lowercase"
                                                                placeholder="Correo" autocomplete="off"
                                                                value="{{ $prospecto->eMailContactoCXP }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="row mb-1">
                                                    <div class="rounded-pill col-lg-12 bg-gray">
                                                        <h6 class="text-bold text-center">Contacto CFDI</h6>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="TratamientoContactoCFDI">Tratamiento</label>
                                                        <div class="input-group">
                                                            <input id="TratamientoContactoCFDI"
                                                                name="TratamientoContactoCFDI" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Tratamiento" autocomplete="off"
                                                                value="{{ $prospecto->TratamientoContactoCFDI }}"
                                                                autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="NombreContactoCFDI">Nombre</label>
                                                        <div class="input-group">
                                                            <input id="NombreContactoCFDI" name="NombreContactoCFDI"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Nombre" autocomplete="off"
                                                                value="{{ $prospecto->NombreContactoCFDI }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="CargoContactoCFDI">Cargo</label>
                                                        <div class="input-group">
                                                            <input id="CargoContactoCFDI" name="CargoContactoCFDI"
                                                                type="text" class="form-control text-uppercase"
                                                                placeholder="Cargo" autocomplete="off"
                                                                value="{{ $prospecto->CargoContactoCFDI }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-lg-4">
                                                        <label for="DepartamentoContactoCFDI">Departamento</label>
                                                        <div class="input-group">
                                                            <input id="DepartamentoContactoCFDI"
                                                                name="DepartamentoContactoCFDI" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Departamento" autocomplete="off"
                                                                value="{{ $prospecto->DepartamentoContactoCFDI }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="Telefonos1ContactoCFDI">Teléfonos</label>
                                                        <div class="input-group">
                                                            <input id="Telefonos1ContactoCFDI"
                                                                name="Telefonos1ContactoCFDI" type="text"
                                                                class="form-control text-uppercase"
                                                                placeholder="Teléfonos" autocomplete="off"
                                                                value="{{ $prospecto->Telefonos1ContactoCFDI }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <label for="eMailContactoCFDI">Correo</label>
                                                        <div class="input-group">
                                                            <input id="eMailContactoCFDI" name="eMailContactoCFDI"
                                                                type="text" class="form-control text-lowercase"
                                                                placeholder="Correo" autocomplete="off"
                                                                value="{{ $prospecto->eMailContactoCFDI }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="row mb-1">
                                                <div class="rounded-pill col-lg-12 bg-gray">
                                                    <h6 class="text-bold text-center">Más Información</h6>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="fecAlta">Fecha Alta</label>
                                                    <div class="input-group">
                                                        @if ($prospecto->fecAlta == null)
                                                            <input id="fecAlta" type="text"
                                                                class="form-control text-uppercase" autocomplete="off"
                                                                placeholder="dd/mm/aaaa" readonly>
                                                        @else
                                                            <input id="fecAlta" type="text" class="form-control"
                                                                autocomplete="off"
                                                                value="{{ \Carbon\Carbon::parse($prospecto->fecAlta)->format('d/m/Y H:i') }}"
                                                                readonly>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="idUsuarioAlta">Usuario Alta</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-uppercase"
                                                            id="idUsuarioAlta" placeholder="Usuario Alta"
                                                            autocomplete="off" value="{{ $prospecto->NombreAlta }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="fecUltCambio">Fecha Ult. Cambio</label>
                                                    <div class="input-group">
                                                        @if ($prospecto->fecUltCambio == null)
                                                            <input id="fecUltCambio" type="text" class="form-control"
                                                                autocomplete="off" placeholder="dd/mm/aaaa" readonly>
                                                        @else
                                                            <input id="fecUltCambio" type="text" class="form-control"
                                                                autocomplete="off"
                                                                value="{{ \Carbon\Carbon::parse($prospecto->fecUltCambio)->format('d/m/Y H:i') }}"
                                                                readonly>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="idUsuarioUltCambio">Usuario Ult. Cambio</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-uppercase"
                                                            id="idUsuarioUltCambio" name="idUsuarioUltCambio"
                                                            placeholder="Usuario Ult. Cambio" autocomplete="off"
                                                            value="{{ $prospecto->NombreUltCambio }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-1 mb-1 row">
                                            <div class="rounded-pill text-center col-sm-12 bg-gray">
                                                <h6 class="text-bold pt-1 pl-1">Validación</h6>
                                            </div>
                                            <div class="row mb-1 ml-1 col-lg-12">
                                                @foreach ($v_Validaciones as $v_Validacion)
                                                    <div class="border rounded mt-1 pl-1 col-lg-4">
                                                        <h6 class="ml-2 mt-2 text-bold d-flex">
                                                            <i class="fas fa-info-circle text-warning mr-2"
                                                                style="margin-top: 2px;"
                                                                title='{{ $v_Validacion->informacion }}'>
                                                            </i>
                                                            {{ $v_Validacion->descripcion }}
                                                        </h6>
                                                        <div class="ml-3" style="margin-top: -10px">
                                                            <div class="form-group">
                                                                @foreach ($validaciones as $validacion)
                                                                    @if ($validacion->numPregunta == $v_Validacion->numPregunta)
                                                                        @if ($validacion->tipoObjeto == 'radio')
                                                                            <div class="custom-control custom-radio"
                                                                                style="display: {{ $validacion->display }}">
                                                                                <input class="custom-control-input"
                                                                                    type="radio"
                                                                                    id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    name="{{ $validacion->nombre }}"
                                                                                    value="{{ $validacion->idCatValidacion }}"
                                                                                    @foreach ($v_ProspectosValidacion as $v_ProspectoValidacion)
                                                                            @if ($validacion->idCatValidacion == $v_ProspectoValidacion->idCatValidacion) checked="{{ $v_ProspectoValidacion->Seleccionado }}" @endif @endforeach>
                                                                                <label
                                                                                    for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    class="custom-control-label">{{ $validacion->valor }}</label>
                                                                            </div>
                                                                        @elseif ($validacion->tipoObjeto == 'radioResp')
                                                                            <div class="float-right col-6 custom-control custom-radio"
                                                                                id="respuestas{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                style="display: none; margin-left: 65px; z-index: 2;">
                                                                                <input class="custom-control-input"
                                                                                    type="radio"
                                                                                    id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    name="{{ $validacion->nombre }}"
                                                                                    value="{{ $validacion->idCatValidacion }}"
                                                                                    @foreach ($v_ProspectosValidacion as $v_ProspectoValidacion)
                                                                            @if ($validacion->idCatValidacion == $v_ProspectoValidacion->idCatValidacion) checked="{{ $v_ProspectoValidacion->Seleccionado }}" @endif @endforeach>
                                                                                <label
                                                                                    for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    class="custom-control-label">{{ $validacion->valor }}</label>
                                                                            </div>
                                                                        @elseif ($validacion->tipoObjeto == 'checkbox')
                                                                            <div class="custom-switch">
                                                                                <input type="checkbox"
                                                                                    id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    name="{{ $validacion->nombre }}"
                                                                                    value="{{ $validacion->idCatValidacion }}"
                                                                                    class="custom-control-input"
                                                                                    style="z-index: 2;"
                                                                                    @foreach ($v_ProspectosValidacion as $v_ProspectoValidacion)
                                                                            @if ($validacion->idCatValidacion == $v_ProspectoValidacion->idCatValidacion) checked="{{ $v_ProspectoValidacion->Checked }}" @endif @endforeach>
                                                                                <label onclick=""
                                                                                    for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                    class="custom-control-label"
                                                                                    style="cursor: pointer">{{ $validacion->valor }}
                                                                                </label>
                                                                            </div>
                                                                        @elseif ($validacion->tipoObjeto == 'text')
                                                                            <div id="respuesta29"
                                                                                style="display: none; position: absolute;margin-left: 60px;">
                                                                                <div class="d-flex col-lg-12">
                                                                                    <label
                                                                                        for="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}"
                                                                                        class="col-lg-4">{{ $validacion->valor }}</label>
                                                                                    <textarea name="{{ $validacion->nombre }}"
                                                                                        id="{{ $validacion->idCatValidacion }}-{{ $validacion->numPregunta }}" rows="3"
                                                                                        class="form-control col-lg-12" style="text-transform: uppercase; z-index: 2;">
                                                                                    @foreach ($v_ProspectosValidacion as $v_ProspectoValidacion)
@if ($validacion->idCatValidacion == $v_ProspectoValidacion->idCatValidacion)
{{ $v_ProspectoValidacion->texto }}
@endif
@endforeach
                                                                                </textarea>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
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
                                                                    {{ \Carbon\Carbon::parse($prospecto->fecAlta)->format('d-m-Y H:i') }}
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
                                                            <i class="cursor-pointer fas {{ $llamada_reunion->icono }} {{ $llamada_reunion->color }} mt-1"
                                                                title="{{ $llamada_reunion->asuntoLugar }}"
                                                                onclick="editarLlamada({{ $llamada_reunion->idLlamadasReuniones }}, 0, '{{ $llamada_reunion->tipo }}'), loaderON()"></i>
                                                            <div class="timeline-item cursor-pointer"
                                                                style="margin-right: 0 !important"
                                                                title="{{ $llamada_reunion->asuntoLugar }}"
                                                                onclick="editarLlamada({{ $llamada_reunion->idLlamadasReuniones }}, 0, '{{ $llamada_reunion->tipo }}'), loaderON()">
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
                                                                    <a onclick="editarLlamada({{ $llamada_reunion->idLlamadasReuniones }}, 1, '{{ $llamada_reunion->tipo }}'), loaderON()"
                                                                        class="cursor-pointer">{{ $llamada_reunion->asuntoLugar }}</a>
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
                        <div class="tab-pane fade" id="oportunidadesP" role="tabpanel"
                            aria-labelledby="oportunidadesP-tab">
                            @if ($creaOportunidades == 1 && $prospectoActivo == 1)
                                @if (session('crearOpor') == 'Crear')
                                    <a type="button" class="btn-flotante button btn btn-primary"
                                        title="Agregar Oportunidad" href="{{ route('Nueva Oportunidad') }}"
                                        onclick="loaderON()">
                                        <i class="fas fas fa-plus-circle"></i>
                                    </a>
                                @endif
                            @else
                                <a type="button" class="btn-flotante button btn btn-dark disable" title="Agregar Oportunidad">
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
                        <div class="tab-pane fade" id="documentosP" role="tabpanel" aria-labelledby="documentosP-tab">
                            @component('sections.Documentos')
                            @endcomponent
                        </div>
                        <div class="tab-pane fade ml-2" id="datos_adminP" role="tabpanel"
                            aria-labelledby="datos-adminP-tab">
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
                                                    class="form-control text-uppercase" placeholder="Calle"
                                                    autocomplete="off" value="{{ $prospecto->DireccionFiscal }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="DireccionFiscalNumero">Número Exterior</label>
                                            <div class="input-group">
                                                <input id="DireccionFiscalNumero" name="DireccionFiscalNumero"
                                                    type="text" class="form-control text-uppercase"
                                                    placeholder="Número Exterior" autocomplete="off"
                                                    value="{{ $prospecto->DireccionFiscalNumero }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="DireccionFiscalNumeroInt">Número Interior</label>
                                            <div class="input-group">
                                                <input id="DireccionFiscalNumeroInt" name="DireccionFiscalNumeroInt"
                                                    type="text" class="form-control text-uppercase"
                                                    placeholder="Número Interior" autocomplete="off"
                                                    value="{{ $prospecto->DireccionFiscalNumeroInt }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="CPFiscalLista">Códigos Postales</label>
                                            <div class="input-group">
                                                <input id="CPFiscalLista" type="text"
                                                    class="form-control text-uppercase" placeholder="Código Postal"
                                                    autocomplete="off" autofill="off">
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-2">
                                            <label for="CPFiscal">Código Postal</label>
                                            <div class="input-group">
                                                <input id="CPFiscal" name="CPFiscal" type="text"
                                                    class="form-control text-uppercase" placeholder="Código Postal"
                                                    autocomplete="off" value="{{ $prospecto->CPFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="AlcaldiaFiscal">Alcaldí­a</label>
                                            <div class="input-group">
                                                <input id="AlcaldiaFiscal" name="AlcaldiaFiscal" type="text"
                                                    class="form-control text-uppercase" placeholder="Alcaldí­a"
                                                    autocomplete="off" value="{{ $prospecto->AlcaldiaFiscal }}"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="ColoniaFiscal">Colonia</label>
                                            <div class="input-group">
                                                <input id="ColoniaFiscal" name="ColoniaFiscal" type="text"
                                                    class="form-control text-uppercase" placeholder="Colonia"
                                                    autocomplete="off" value="{{ $prospecto->ColoniaFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="PoblacionFiscal">Localidad</label>
                                            <div class="input-group">
                                                <input id="PoblacionFiscal" name="PoblacionFiscal" type="text"
                                                    class="form-control text-uppercase" placeholder="Poblacion"
                                                    autocomplete="off" value="{{ $prospecto->PoblacionFiscal }}"
                                                    @if ($idrol != 5) readonly @endif>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="EstadoFiscal">Estado</label>
                                            <div class="input-group">
                                                <input id="EstadoFiscal" name="EstadoFiscal" type="text"
                                                    class="form-control text-uppercase" placeholder="Estado"
                                                    autocomplete="off" value="{{ $prospecto->EstadoFiscal }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="PaisFiscal">Paí­s</label>
                                            <!-- <label for="PaisFiscal">Paí­s <b class="asterisco">*</b></label> -->
                                            <select id="PaisFiscal" name="PaisFiscal" class="form-control text-uppercase"
                                                style="width: 100%">
                                                <option selected disabled>País</option>
                                                @foreach ($paises as $pais)
                                                    @if (strtoupper(mb_convert_encoding($pais->Descripcion ?? '', 'ISO-8859-1', 'UTF-8')) == strtoupper(mb_convert_encoding($prospecto->PaisFiscal ?? '', 'ISO-8859-1', 'UTF-8')))
                                                        <option value="{{ $pais->Descripcion }}" selected>
                                                            {{ $prospecto->PaisFiscal }}
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
                            <div class="row mb-2">
                                <div class="rounded-pill col-sm-12 bg-gray mb-1">
                                    <h6 class="text-bold text-center"></h6>
                                </div>
                                <div class="col-lg-3">
                                    <label for="RFC">R.F.C.</label>
                                    <div class="input-group">
                                        <input id="RFC" name="RFC" type="text"
                                            class="form-control text-uppercase" placeholder="R.F.C." autocomplete="off"
                                            value="{{ $prospecto->RFC }}" onchange="validarInput(this)">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="NumRegIdTrib">Registro Id Tributario</label>
                                    <div class="input-group">
                                        <input id="NumRegIdTrib" name="NumRegIdTrib" type="text"
                                            class="form-control text-uppercase" placeholder="Registro Id Tributario"
                                            autocomplete="off" value="{{ $prospecto->NumRegIdTrib }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="CreditoCondiciones">Condiciones</label>
                                    <select id="CreditoCondiciones" name="CreditoCondiciones"
                                        class="form-control text-uppercase" style="width: 100%">
                                        <option selected disabled>Condiciones</option>
                                        @foreach ($cat_condiciones as $condiciones)
                                            @if ($condiciones->Condicion == $prospecto->CreditoCondiciones)
                                                <option value="{{ $condiciones->Condicion }}" selected>
                                                    {{ $prospecto->CreditoCondiciones }}
                                                </option>
                                            @else
                                                <option value="{{ $condiciones->Condicion }}">
                                                    {{ $condiciones->Condicion }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="FiscalRegimen">Régimen</label>
                                    <select id="FiscalRegimen" name="FiscalRegimen" class="form-control"
                                        style="width: 100%">
                                        <option selected disabled>Régimen</option>
                                        @foreach ($cat_regimen as $regimen)
                                            @if ($regimen->idRegimen == $prospecto->FiscalRegimen)
                                                <option value="{{ $regimen->idRegimen }}" selected>
                                                    {{ $regimen->idRegimen }} -
                                                    {{ $regimen->Descripcion }}
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
                                <div class="rounded-pill col-lg-12 bg-gray mb-1">
                                    <h6 class="text-bold text-center"></h6>
                                </div>
                                <div class="col-lg-4">
                                    <label for="limiteCredito">Límite de Crédito</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-bold">$</span>
                                        </div>
                                        <input id="limiteCredito" name="limiteCredito" type="text"
                                            class="form-control text-uppercase" placeholder="Límite de Crédito"
                                            autocomplete="off" value="{{ number_format($prospecto->limiteCredito, 2) }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="diasRevision">Días de Revisión</label>
                                    <div class="input-group">
                                        <input id="diasRevision" name="diasRevision" type="text"
                                            class="form-control text-uppercase" placeholder="Días de Revisión"
                                            autocomplete="off" value="{{ $prospecto->diasRevision }}">
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left" style="margin-top: 30px">
                                    <div class="custom-control custom-switch">
                                        @if ($prospecto->BloquearMorosos == 'SI' || $prospecto->BloquearMorosos == 'Si' || $prospecto->BloquearMorosos == 'Sí')
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
                                <div class="col-lg-4">
                                    <label for="LimiteDeCredito">Límite de Saldo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-bold">$</span>
                                        </div>
                                        <input id="LimiteDeCredito" name="LimiteDeCredito" type="text"
                                            class="form-control text-uppercase" placeholder="Límite de Saldo"
                                            autocomplete="off"
                                            value="{{ number_format($prospecto->limiteCredito, 2) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="idMetodoRevision">Método de Revisión</label>
                                    <select id="idMetodoRevision" name="idMetodoRevision" class="form-control">
                                        <option selected disabled>Método de Revisión</option>
                                        @foreach ($cat_metodo_revision as $metodo_revision)
                                            @if ($metodo_revision->idMetodoRevision == $prospecto->idMetodoRevision)
                                                <option value="{{ $metodo_revision->idMetodoRevision }}" selected>
                                                    {{ $metodo_revision->descMetodoRevision }}
                                                </option>
                                            @else
                                                <option value="{{ $metodo_revision->idMetodoRevision }}">
                                                    {{ $metodo_revision->descMetodoRevision }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 text-left" style="margin-top: 30px">
                                    <div class="custom-control custom-switch">
                                        @if ($prospecto->addendaEspecial == 1)
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
                                <div class="col-lg-4">
                                    <label for="CreditoLimitePedidos">Límite de Pedido</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text text-bold">$</span>
                                        </div>
                                        <input id="CreditoLimitePedidos" name="CreditoLimitePedidos" type="text"
                                            class="form-control text-uppercase" placeholder="Límite de Pedido"
                                            autocomplete="off"
                                            value="{{ number_format($prospecto->CreditoLimitePedidos, 2) }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="idEntregaComplementoPago">Entrega complemento pago</label>
                                    <select id="idEntregaComplementoPago" name="idEntregaComplementoPago"
                                        class="form-control">
                                        <option selected disabled>Entrega complemento pago</option>
                                        @foreach ($cat_ent_compl_pago as $complemento_pago)
                                            @if ($complemento_pago->idEntregaComplementoPago == $prospecto->idEntregaComplementoPago)
                                                <option value="{{ $complemento_pago->idEntregaComplementoPago }}"
                                                    selected>
                                                    {{ $complemento_pago->descEntregaComplementoPago }}
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
                                <div class="rounded-pill col-lg-12 bg-gray mb-1">
                                    <h6 class="text-bold text-center"></h6>
                                </div>
                                <div class="col-lg-4">
                                    <label for="urlPortal">URL Portal</label>
                                    <div class="input-group">
                                        <input id="urlPortal" name="urlPortal" type="text"
                                            class="form-control text-uppercase" placeholder="URL Portal"
                                            autocomplete="off" value="{{ $prospecto->urlPortal }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label for="usuarioPortal">Usuario Portal</label>
                                    <div class="input-group">
                                        <input id="usuarioPortal" name="usuarioPortal" type="text"
                                            class="form-control text-uppercase" placeholder="Usuario Portal"
                                            autocomplete="off" value="{{ $prospecto->usuarioPortal }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label for="cvePortal">Clave Portal</label>
                                    <div class="input-group">
                                        <input id="cvePortal" name="cvePortal" type="text" class="form-control"
                                            placeholder="Clave Portal" autocomplete="off"
                                            value="{{ $prospecto->cvePortal }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="manualPortal">Manual Portal</label>
                                    <div class="input-group">
                                        <input id="manualPortal" name="manualPortal" type="text"
                                            class="form-control text-uppercase" placeholder="Manual Portal"
                                            autocomplete="off" value="{{ $prospecto->manualPortal }}" readonly>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src=" {{ asset('js/prospectos.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/clientes_Docs.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/llamadas.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/clientes_Oportunidades.js?n=1') }} " defer></script>
    @endsection @section('scripts')
    @include('modals.visorPDF')
    @include('clientes.mod_UpDocs')
    @include('partials.loading')
    @include('agenda.llamadas.addOrEditProspecto');
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

        var nombreContacto = document.getElementById('NombreContacto');
        var telefonosContacto = document.getElementById('Telefonos1Contacto');
        var emailContacto = document.getElementById('eMailContacto');

        $('[data-bs-toggle="tooltip"]').tooltip();

        $(document).ready(function() {

            validarRequeridos();

            var id = $('#idClienteINT').val();

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

        var mensaje = '';

        const $btnSave = document.querySelector("#btnSave");

        $('#formProspectoEdit').submit(function(evento) {
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

        nombreContacto.addEventListener('change', function() {
            validarRequeridos();
        });

        telefonosContacto.addEventListener('change', function() {
            validarRequeridos();
        });

        emailContacto.addEventListener('change', function() {
            validarRequeridos();
        });

        function validarRequeridos() {

            if (idrol.value != 7) {
                if (rsocial.value == "" || nombrecomercial.value == "" || agte.value == "Agente" || tels.value == "" || giro
                    .value == "Giro" || subgiro.value == "Sub Giro" ||
                    email.value == "" || calle.value == "" || numero.value == "" || cp.value == "" || colonia.value == "" ||
                    alcaldia.value == "" ||
                    poblacion.value == "" || estado.value == "" || pais.value == "" || nombreContacto.value == "" ||
                    telefonosContacto.value == "" ||
                    emailContacto.value == "") {
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

                if (nombreContacto.value == "") {
                    nombreContacto.style.borderColor = 'red';
                } else {
                    nombreContacto.style.borderColor = 'green';
                }

                if (telefonosContacto.value == "") {
                    telefonosContacto.style.borderColor = 'red';
                } else {
                    telefonosContacto.style.borderColor = 'green';
                }

                if (emailContacto.value == "") {
                    emailContacto.style.borderColor = 'red';
                } else {
                    emailContacto.style.borderColor = 'green';
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
            } else {
                CodigoPostal.readOnly = false;
                alcaldia.readOnly = false;
                colonia.readOnly = false;
                poblacion.readOnly = false;
                estado.readOnly = false;
            }
        }

        var idClienteINT = document.getElementById('idClienteINT');
        // var id_Cliente_INT = idClienteINT.value;

        function soloAlfanumericos(event) {
            var regex = new RegExp("^[a-zA-Z0-9 ]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        }

        function eliminarProspectoEdit(id, activo) {
            if (activo == 0) {
                text = "Activar Prospecto Seleccionado?";
                mensaje = "Prospecto Activado";
            } else {
                text = "Desactivar Prospecto Seleccionado?";
                mensaje = "Prospecto Desactivado";
            }
            Swal.fire({
                title: "Prospectos",
                text: text,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Si",
                cancelButtonText: "No",
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('prospecto.darDeBajaProspecto') }}",
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id,
                            activo: activo,
                            _token: CSRF_TOKEN
                        },
                        success: function(data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: mensaje
                            });
                            // dataTableProspectos.ajax.reload();
                            window.history.go(-1);
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({
                                title: "Ocurrió un error"
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
