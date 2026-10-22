@extends('layout.master')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/document_oport.css') }}">
    @include('partials.loading')
    @include('oportunidades.mod_Presupuesto_relacion')
    @include('oportunidades.mod_cambios_menores')
    @include('oportunidades.modal-cambio-precios')
    @include('oportunidades.modal-cambio-especificaciones')
    @include('oportunidades.modal-cambios-menores')
    <div id="formOporEdit" class="col-12 mt-2">
        <input type="hidden" name="{{ url('/') }}" id="baseUrl">
        <input type="hidden" value="{{ $oportunidad[0]->Valor }}" id="valorOportunidad">
        <input type="hidden" value="{{ $oportunidad[0]->flagCambioTexto }}" id="flagCambioTexto">
        <input type="hidden" value="{{ $oportunidad[0]->flagCambioEspecificaciones }}" id="flagCambioEspecificaciones">
        <input type="hidden" value="{{ $oportunidad[0]->solicitud_preprensa }}" id="solicitud_preprensa">
        <input type="hidden" value="{{ $oportunidad[0]->preprensa_concluida }}" id="preprensa_concluida">
        <input type="hidden" value="{{ $oportunidad[0]->notasProduccion }}" id="notasProduccion" name="notasProduccion">
        <input type="hidden" value="{{ $oportunidad[0]->finalizoCambios }}" id="finalizoCambios" name="finalizoCambios">
        <input type="hidden" value="{{ $oportunidad[0]->preSolicitoCambios }}" id="preSolicitoCambios" name="preSolicitoCambios">
        <input type="hidden" value="{{ $oportunidad[0]->solicitoCambios }}" id="solicitoCambios" name="solicitoCambios">
        <input type="hidden" value="{{ $tieneTripasConFolioPreprensa }}" id="tieneTripasConFolioPreprensa" name="tieneTripasConFolioPreprensa">
        <select id="motivoRechazoOport" class="d-none">
            <option value="" selected disabled>Seleccione una opción</option>
            @foreach ($motivosRechazoOportunidad as $motivo)
                <option value="{{ $motivo->id }}">{{ $motivo->descripcion }}</option>
            @endforeach
        </select>

        <form method="post" action="{{ route('oportunidades.update', $oportunidad[0]->idOportunidad) }}" role="form">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" value="{{ $oportunidad[0]->idEstatus }}" name="Estatus" id="Estatus">
            <input type="hidden" value="{{ $oportunidad[0]->idOportunidad }}" name="idOportunidad" id="idOportunidad">
            <input type="hidden" value="{{ $oportunidad[0]->Cancelada }}" name="Cancelada" id="Cancelada">
            <input type="hidden" value="{{ $oportunidad[0]->ClienteINT }}" id="idClienteEnt">
            <input type="hidden" value="{{ $oportunidad[0]->FechaAutoriza }}" id="FechaAutoriza">
            <input type="hidden" value="{{ $oportunidad[0]->esLiberada }}" id="isLiberado">
            <input type="hidden" value="{{ $oportunidad[0]->prospecto }}" id="prospecto" name="prospecto">
            <input type="hidden" value="{{ $oportunidad[0]->Agente }}" id="Agente" name="Agente">
            <input type="hidden" value="{{ $oportunidad[0]->hasFichaT }}" id="hasFichaT" name="hasFichaT">
            <input type="hidden" value="{{ $oportunidad[0]->isRepetitivo }}" id="isRepetitivo" name="isRepetitivo">
            <input type="hidden" value="{{ $oportunidad[0]->razonsocial }}" id="razonsocialOport" name="razonsocialOport">
            <input type="hidden" value="" id="flagAddNewRequestChanges" name="flagAddNewRequestChanges">
            <input type="hidden" value="{{ $permisoEstructural }}" id="permisoEstructural" name="permisoEstructural">
            <input type="hidden" id="cambiosRealizados" name="cambiosRealizados">
            <input type="hidden" value="{{ $oportunidad[0]->cambiosMinimos }}" id="cambiosMinimos">
            <input type="hidden" value="{{ $oportunidad[0]->folio }}" id="oportFolio" name="oportFolio">
            <input type="hidden" id="cambiosRealizadosEdit" name="cambiosRealizadosEdit"
                value="{{ $total_cambios_realizados }}">
            {{-- <input type="hidden" value="{{ $oportunidad[0]->idAgente }}" id="idAgente" name="idAgente"> --}}
            {{-- <input type="hidden" value="{{ $oportunidad[0]->idMotivoPerdida }}" id="idMotivoPerdida"> --}}

            <input type="hidden" value="{{ $idrol }}" id="idRol">
            <div>
                <div class="rounded bg-gray">
                    <div class="row mb-2">
                        <div class="col-12 text-center">
                            <h6 id="oportunidadTitulo" class="text-bold pt-1 pl-1">Editar Oportunidad {{ $oportunidad[0]->solicitoCambios == 1 ? ' - (Cambios solicitados)' : '' }} </h6>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="row input-group input-group-sm">
                        <h6 class="text-right text-bold text-red pr-1 pt-2">&nbsp;No. {{ $oportunidad[0]->folio }}
                            - &nbsp;
                        </h6>
                        <label class="mt-2" for="nomOportunidad">Oportunidad <span class="ar">*</span> &nbsp;</label>
                        @if ($oportunidad[0]->idEstatus < 7 && $oportunidad[0]->Cancelada == 0 && $idrol != 1020)
                            <input type="text" class="form-control col-lg-5 text-uppercase" id="nomOportunidad"
                                name="nomOportunidad" autocomplete="off" value="{{ $oportunidad[0]->nomOportunidad }}">
                        @else
                            <input type="text" class="form-control col-lg-5 text-uppercase" id="nomOportunidad"
                                name="nomOportunidad" autocomplete="off" disabled
                                value="{{ $oportunidad[0]->nomOportunidad }}">
                        @endif
                        <div class="form-group col-lg-2 text-center">
                            {{--  @if ($oportunidad[0]->Premio == 'on')
                                <div id="divPremio" class="custom-control custom-switch mt-4 premio">
                                    <input id="Premio" name="Premio" type="checkbox" onclick="asignarPremio()"
                                        class="custom-control-input premio" checked>
                                    <label class="custom-control-label pointer" for="Premio">Trabajo
                                        para Premio</label>
                                </div>
                            @else
                                <div id="divPremio" class="custom-control custom-switch mt-4 sin_premio">
                                    <input id="Premio" name="Premio" type="checkbox" onclick="asignarPremio()"
                                        class="custom-control-input">
                                    <label class="custom-control-label pointer" for="Premio">Trabajo
                                        para Premio</label>
                                </div>
                            @endif  --}}
                            <div>

                            </div>
                        </div>
                        <div class="input-group-append col-md-6 col-lg-5">
                            @if (($idrol == 1 || $idrol == 8 || $idrol == 10 || $idrol == 2) && ($oportunidad[0]->idEstatus == 3 || $oportunidad[0]->idEstatus == 4) && ($oportunidad[0]->solicitoCambios == 0 || $oportunidad[0]->solicitoCambios == null))
                            <span title="Rechazar">
                                <button type="button" onclick="rechazarOportunidadAdmin('{{ $oportunidad[0]->idOportunidad }}')" class="btn btn-danger btn-sm btn-block"><i class="fas fa-times-circle"></i></button>
                            </span>
                            @endif
                            <span class="col-2 center" id="sOportBtnTEntrega" title="Tiempo de Entrega"
                                style="display: none"><a id="btnTEntrega"
                                    onclick="openTiempoEntregaModal({{ $oportunidad[0]->idOportunidad }}, {{ $id_usuario }}, 0), loaderModalON()"
                                    class="btn btn-info btn-sm btn-block"><i class="fas fa-calendar-alt"></i></a></span>
                            <span class="col-3 center" id="sOportBtnCancelar"><a id="btnCancelar" href="#"
                                    onclick="CancelarOportunidad({{ $oportunidad[0]->idOportunidad }})"
                                    class="btn btn-danger btn-sm btn-block">Cancelar</a></span>
                            <span class="col-3" id="sOportBtnSave"><button id="oportBtnSave" type="submit"
                                    class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                            <span class="col-4 center" id="sOportBtnBack"><a href="{{ route('Oportunidades') }}"
                                    class="btn btn-secondary btn-sm btn-block">Regresar</a></span>
                        </div>

                    </div>
                </div>
                <div id="tabGeneral" style="margin-top: 25px;">
                    <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item {{ $idrol != 1020 ? '' : 'sr-only' }}" style="width: 16.66%">
                            <a class="nav-link active" id="general-oport-tab" data-toggle="pill" style="cursor: pointer"
                                onclick="mostrarGeneralOport()" role="tab" aria-controls="general-oport-tab"
                                aria-selected="true">General</a>
                        </li>
                        <li class="nav-item {{ $idrol == 1020 || ($oportunidad[0]->fueRechazadoPres == 0 && $oportunidad[0]->tieneExcel == 1 && !in_array($oportunidad[0]->idEstatus, [7,8])) ? 'sr-only' : '' }}" style="width: 16.66%">
                            <a class="nav-link" id="elementos-oport-tab" data-toggle="pill" style="cursor: pointer"
                                onclick="mostrarElementosOport(); tblEP_relationV2();" role="tab" aria-controls="elementos-oport-tab"
                                aria-selected="false">Elementos Presupuestados</a>
                        </li>
                        <li class="nav-item {{ $idrol != 1020 ? '' : 'sr-only' }}" style="width: 16.66%" id="estructural-oport-tab-li">
                            <a class="nav-link" id="estructural-oport-tab" data-toggle="pill" style="cursor: pointer"
                                onclick="mostrarEstructuralOport(), ajustarTablas()" role="tab"
                                aria-controls="estructural-oport-tab" aria-selected="false">Diseño Estructural</a>
                        </li>
                        <li class="nav-item" style="width: 16.66%">
                            <a class="nav-link" id="documentos-oport-tab" onclick="mostrarDocumentosOport()"
                                data-toggle="pill" role="tab" aria-controls="documentos-oport-tab"
                                aria-selected="false" style="cursor: pointer">Documentos</a>
                        </li>
                        {{-- <li class="nav-item" style="width: 16.66%">
                            <a class="nav-link" id="correos-oport-tab" onclick="mostrarCorreosOport()"
                                data-toggle="pill" role="tab" aria-controls="correos-oport-tab"
                                aria-selected="false" style="cursor: pointer">Correos</a>
                        </li> --}}
                        {{--  <li id="tabEntregasOport" class="nav-item" style="width: 16.66%; display: none">
                            <a class="nav-link" id="entregas-oport-tab" onclick="mostrarEntregasOport()"
                                data-toggle="pill" role="tab" aria-controls="entregas-oport-tab"
                                aria-selected="false" style="cursor: pointer">Entregas</a>
                        </li>  --}}
                    </ul>
                    {{--  <a id="btnClonarOportunidad" onclick="clonarOportunidad(), loaderON()" type="button"
                        class="btn-flotante button btn btn-primary-clone" title="Clonar Oportunidad">
                        <i><img src="{{ asset('img/clon.png') }}" width="28"></i>
                    </a>  --}}
                    <div id="scrollbarTabsModalRound" class="tab-content">
                        <div class="tab-pane fade show active {{ $idrol != 1020 ? '' : 'sr-only' }}" id="general-oport" role="tabpanel"
                            aria-labelledby="general-oport-tab">
                            <div id="isCancelled" class="canceling-cont" style="display: none">
                                <div class="canceling">CANCELADA</div>
                            </div>
                            <div class="row">
                                <div class="col-lg-7 col-sm-12 mt-2">
                                    <div class="row">
                                        <div class="col-10">
                                            <div class="form-group">
                                                <label for="idCliente">Cliente<span class="ar">*</span></label>
                                                <select id="idCliente" name="idCliente" class="form-control"
                                                    style="width: 100%" disabled>
                                                    <option value="{{ $oportunidad[0]->idCliente }}" selected>
                                                        {{ $oportunidad[0]->razonsocial }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label>Tipo de tabla de comisión</label>
                                                <div id="tipoTablaComision" class="text-center"><span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="Sin seleccionar"><strong>Sin seleccionar</strong></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="idContacto">Contacto<span class="ar">*</span></label>
                                                <select id="idContacto" name="idContacto" class="form-control"
                                                    style="width: 100%; text-transform: uppercase; height: 31px !important;"
                                                    @if(!in_array($oportunidad[0]->idEstatus, [1, 2]))
                                                        @if (!in_array($idrol, [1, 10, 8]))
                                                        disabled
                                                        @endif
                                                    @endif
                                                    >
                                                    <option selected disabled>Requerido</option>
                                                    @foreach ($contactos as $contacto)
                                                        @if ($contacto->idContacto == $oportunidad[0]->idContacto)
                                                            <option value="{{ $contacto->idContacto }}" selected>
                                                                {{ $contacto->Nombre }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $contacto->idContacto }}">
                                                                {{ $contacto->Nombre }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="idTipo">Tipo de Oportunidad<span class="ar">*</span></label>
                                                <select id="idTipo" name="idTipo" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($tipos as $tipo)
                                                        @if ($tipo->idTipoOportunidad == $oportunidad[0]->idTipoOportunidad)
                                                            <option value="{{ $tipo->idTipoOportunidad }}" selected>
                                                                {{ $oportunidad[0]->desTipoOportundidad }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $tipo->idTipoOportunidad }}">
                                                                {{ $tipo->desTipoOportundidad }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-3">
                                            <div class="form-group">
                                                <label for="clasificacionOportunidad">Clasificación</label>
                                                <div class="input-group">
                                                    <input id="clasificacionOportunidad" name="clasificacionOportunidad"
                                                        type="text" class="form-control" readonly autocomplete="off"
                                                        value="{{ $oportunidad[0]->clasificacionOportunidad }}">
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    <label>Estatus <span class="ar">*</span></label>

                                    <div class="row" id="listEstatus">
                                        <div class="col-3" style="margin-bottom: -5px">
                                            <div class="form-group">
                                                @if ($idrol == 4 || $idrol == 9 || $idrol == 8 || $idrol == 1)
                                                    <div class="custom-control custom-radio">
                                                        @if ($oportunidad[0]->idEstatus == 1)
                                                            <input class="custom-control-input" type="radio"
                                                                id="idStatusPre" name="idStatus" checked value="1">
                                                        @else
                                                            <input class="custom-control-input" type="radio"
                                                                id="idStatusPre" name="idStatus" value="1">
                                                        @endif
                                                        <label for="idStatusPre" class="custom-control-label">Pre
                                                            registro</label>
                                                    </div>
                                                @else
                                                    <div class="custom-control custom-radio" hidden>
                                                        @if ($oportunidad[0]->idEstatus == 1)
                                                            <input class="custom-control-input" type="radio"
                                                                id="idStatusPre" name="idStatus" checked value="1">
                                                        @else
                                                            <input class="custom-control-input" type="radio"
                                                                id="idStatusPre" name="idStatus" value="1">
                                                        @endif
                                                        <label for="idStatusPre" class="custom-control-label">Pre
                                                            registro</label>
                                                    </div>
                                                @endif
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 2)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledVentas }} id="idStatusSolCot" name="idStatus"
                                                            checked value="2">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledVentas }} id="idStatusSolCot" name="idStatus"
                                                            value="2">
                                                    @endif
                                                    <label for="idStatusSolCot" class="custom-control-label">Solicitud de
                                                        cotización</label>


                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 3)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusListoCot"
                                                            name="idStatus" checked value="3">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusListoCot"
                                                            name="idStatus" value="3">
                                                    @endif
                                                    <label for="idStatusListoCot" class="custom-control-label">Listo
                                                        p/cotizar</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 4)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusCot" name="idStatus"
                                                            checked value="4">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusCot" name="idStatus"
                                                            value="4">
                                                    @endif
                                                    <label for="idStatusCot"
                                                        class="custom-control-label">Cotizando</label>
                                                </div>
                                                <div class="col-12">
                                                    <label for="fechaTopeOport">Fecha Lim. Licitación <span class="ar d-none" id="fecLimLic">*</span></label>
                                                    <div class="input-group date" id="fechaTopeOport">
                                                        <div class="d-flex">
                                                            @if ($oportunidad[0]->fechaTopeOport == null)
                                                                <input id="fechaTopeOportInput" name="fechaTopeOport"
                                                                    type="text" class="form-control datetimepicker-input"
                                                                    data-target="#fechaTopeOport" placeholder="Fecha Tope"
                                                                    disabled />
                                                            @else
                                                                <input id="fechaTopeOportInput" name="fechaTopeOport"
                                                                    type="text" class="form-control datetimepicker-input"
                                                                    data-target="#fechaTopeOport" placeholder="Fecha Tope"
                                                                    value="{{ \Carbon\Carbon::parse($oportunidad[0]->fechaTopeOport)->format('d/m/Y') }}"
                                                                    disabled />
                                                            @endif
                                                            <div class="input-group-append" data-target="#fechaTopeOport"
                                                                data-toggle="datetimepicker">
                                                                <label class="labelCalendar" for="fechaTopeOportInput">
                                                                    <div class="divCalendar input-group-text"><i
                                                                            class="fa fa-calendar"></i>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 text-right">
                                                    <label style="font-size: 8px"> *Customers / Cotizadores</label>
                                                </div>
{{--  
                                                @if (($oportunidad[0]->idEstatus < 7 || $oportunidad[0]->idEstatus == 13) && !in_array($idrol, [4,9]) && ($oportunidad[0]->flagCambioEspecificaciones == 1 || $oportunidad[0]->flagCambioPrecios == 1 || $oportunidad[0]->flagCambioTexto == 1 || $oportunidad[0]->cambiosMinimos == 1))
                                                <div class="col-12">
                                                    <button class="btn btn-info btn-sm mt-2"
                                                        type="button" id="btnShowCambios" onclick="openModalCambiosMenores(2)">
                                                        <i class="fa fa-info-circle">&nbsp;&nbsp;</i>Mostrar cambios pendientes
                                                    </button>
                                                </div>
                                                @endif  --}}
                                            </div>
                                        </div>

                                        <div class="col-3" style="margin-bottom: -5px">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 13)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusRevTec" name="idStatus"
                                                            checked value="13">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusRevTec" name="idStatus"
                                                            value="13">
                                                    @endif
                                                    <label for="idStatusRevTec" class="custom-control-label">Revisión Técnica</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 5)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusRev" name="idStatus"
                                                            checked value="5">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCotizadores }} id="idStatusRev" name="idStatus"
                                                            value="5">
                                                    @endif
                                                    <label for="idStatusRev" class="custom-control-label">Autorización <span class="ar d-none" id="lblAutorizacion">*</span></label>
                                                    <select class="form-control" name="tipoEspera" id="tipoEspera"
                                                        disabled>
                                                        <option value="" selected disabled>Revisado por...</option>
                                                        @foreach ($revisa_oportunidad_combo as $revisa_oportunidad)
                                                            @if ($revisa_oportunidad->idRevisa == $oportunidad[0]->idRevisado)
                                                                <option selected
                                                                    value="{{ $revisa_oportunidad->nombreRevisa }}">
                                                                    {{ $revisa_oportunidad->nombreRevisa }}</option>
                                                            @else
                                                                <option value="{{ $revisa_oportunidad->nombreRevisa }}">
                                                                    {{ $revisa_oportunidad->nombreRevisa }}</option>
                                                            @endif
                                                        @endforeach
                                                        {{-- <option value="Gustavo Casas">Gustavo Casas</option>
                                                        <option value="Carlos Charabati">Carlos Charabati</option>
                                                        <option value="Nidia Paz">Nidia Paz</option>
                                                        <option value="Silvia González">Silvia González</option> --}}
                                                    </select>
                                                </div>

                                                {{-- <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 6)
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusVoBo" name="idStatus" checked value="6">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusVoBo" name="idStatus" value="6">
                                                    @endif
                                                    <label for="idStatusVoBo" class="custom-control-label">Espera Vo.
                                                        Bo.</label>
                                                </div> --}}
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 7)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledRevisa }} id="idStatusListoPre" name="idStatus"
                                                            checked value="7">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledRevisa }} id="idStatusListoPre" name="idStatus"
                                                            value="7">
                                                    @endif
                                                    <label for="idStatusListoPre" class="custom-control-label">Listo p
                                                        /presupuesto</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 8)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusPreTerm" name="idStatus"
                                                            checked value="8">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusPreTerm" name="idStatus"
                                                            value="8">
                                                    @endif
                                                    <label for="idStatusPreTerm" class="custom-control-label">Presupuesto
                                                        terminado</label>
                                                    {{--  <div class="input-group mt-2">
                                                        <a id="btnAddPresupuesto" onclick="openModalPresupuesto()"
                                                            class="cursor-pointer btn btn-primary btn-sm disable"><i
                                                                class="fas fa-plus-circle">&nbsp;&nbsp;</i>N°
                                                            Presupuesto</a>
                                                    </div>  --}}
                                                    @if ($oportunidad[0]->tieneExcel != 1)
                                                    <div class="input-group mt-2 mb-2">
                                                        <a id="btnAddCambiosMenores" onclick="openModalCambiosMenores(1)"
                                                            class="cursor-pointer btn btn-info btn-sm"><i
                                                        class="fas fa-plus-circle">&nbsp;&nbsp;</i> Cambio de precios </a>
                                                    </div>
                                                    @endif
                                                </div>
                                                {{-- @if ($oportunidad[0]->solicitudFechaEntrega == 0)
                                                    <div id="divBtnSolFecEntrega" class="pt-2 text-left">
                                                        <a id="btnSolFecEntrega" onclick="solicitarFechaEntrega()"
                                                            class="btn btn-info btn-sm rounded pointer"
                                                            style="font-size: 10px !important"><i
                                                                class="fas fa-calendar"></i>
                                                            Solicitar Fecha Entrega
                                                        </a>
                                                    </div>
                                                @else
                                                    <div id="divBtnSolFecEntrega" class="pt-2 text-left disable">
                                                        <a id="btnSolFecEntrega" onclick="solicitarFechaEntrega()"
                                                            class="btn btn-info btn-sm rounded pointer"
                                                            style="font-size: 10px !important" disabled><i
                                                                class="fas fa-calendar"></i>
                                                            Solicitar Fecha Entrega
                                                        </a>
                                                    </div>
                                                @endif --}}
                                            </div>
                                        </div>

                                        <div class="col-3" style="margin-bottom: -5px">
                                            <div class="form-group border-left border-right">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 10)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledVentas }} id="idStatusPerdido" name="idStatus"
                                                            checked value="10">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledVentas }} id="idStatusPerdido" name="idStatus"
                                                            value="10">
                                                    @endif
                                                    <label for="idStatusPerdido"
                                                        class="custom-control-label">Perdido</label>
                                                </div>
                                                <label for="idMotivoPerdida">Motivo Perdida <span class="ar d-none" id="lblMotivoPerdida">*</span></label>
                                                <select id="idMotivoPerdida" name="idMotivoPerdida" class="form-control"
                                                    style="width: 100%;">
                                                    <option selected disabled>Motivo Perdida</option>
                                                    @foreach ($motivoPerdida as $motivo)
                                                        @if ($motivo->idMotivoPerdida == $oportunidad[0]->idMotivoPerdida)
                                                            <option value="{{ $motivo->idMotivoPerdida }}" selected>
                                                                {{ $oportunidad[0]->descMotivoPerdida }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $motivo->idMotivoPerdida }}">
                                                                {{ $motivo->descMotivoPerdida }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <div class="col-12 text-center">
                                                    <label style="font-size: 8px"> *Vendedores</label>
                                                </div>

                                                @if ($idrol == 4 || $idrol == 8 || $idrol == 1)
                                                <div id="divSolicfichatecnica" class="custom-control custom-switch mt-1 sr-only">
                                                    <input id="sFicTec" name="sFicTec" type="checkbox"
                                                        class="custom-control-input"
                                                        disabled

                                                        @if ($oportunidad[0]->hasFichaT == 1)
                                                            checked
                                                        @else
                                                            onclick="solicitarFichaTecnicaOport(this)"
                                                        @endif
                                                        >
                                                    <label class="custom-control-label pointer" for="sFicTec">Trabajo repetitivo <span class="ar">*</span></label>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-3" style="margin-bottom: -5px">
                                            {{--  <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 9)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledVentas }} id="idStatusGanado" name="idStatus"
                                                            checked value="9">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledVentas }} id="idStatusGanado" name="idStatus"
                                                            value="9">
                                                    @endif
                                                    <label for="idStatusGanado"
                                                        class="custom-control-label">Ganado</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 11)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusSolOP" name="idStatus"
                                                            checked value="11">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusSolOP" name="idStatus"
                                                            value="11">
                                                    @endif
                                                    <label for="idStatusSolOP" class="custom-control-label">Solicitud de
                                                        OP </label>
                                                </div>
                                                <div class="custom-control custom-radio sr-only">
                                                    @if ($oportunidad[0]->idEstatus == 14)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusSolOPP" name="idStatus"
                                                            checked value="14">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusSolOPP" name="idStatus"
                                                            value="14">
                                                    @endif
                                                    <label for="idStatusSolOPP" class="custom-control-label">Solicitud de
                                                        OPP </label><label style="font-size: 8px">&nbsp;
                                                        *Preprensa</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 12)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusOPCon" name="idStatus"
                                                            checked value="12">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusOPCon" name="idStatus"
                                                            value="12">
                                                    @endif
                                                    <label for="idStatusOPCon" class="custom-control-label">OP
                                                        Concluida </label><label style="font-size: 8px">&nbsp;
                                                        *Customers</label>
                                                </div>
                                                <label for="numOrdenMTC">Orden Metrics <span class="ar d-none" id="lblOmts">*</span></label>
                                                <div class="input-group">
                                                    <input id="numOrdenMTC" name="numOrdenMTC" type="text"
                                                        class="form-control text-uppercase" placeholder="Orden Metrics"
                                                        autocomplete="off" value="{{ $oportunidad[0]->numOrdenMTC }}">
                                                </div>
                                            </div>  --}}

                                            <div class="form-group">
                                                <div style="margin-top: -30px;">
                                                    <p class="text-center font-bold">Ganado</p>
                                                </div>

                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="idStatusGanado" name="idStatusGanado"
                                                        {{ $oportunidad[0]->solicita_prepedido == 1 ? 'checked' : '' }} disabled
                                                        value="9">
                                                    <label class="custom-control-label" for="idStatusGanado">Prepedido</label>
                                                </div>

                                                {{--  Nuevos estatus  --}}
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->solicitud_preprensa == 1 && ($oportunidad[0]->preprensa_concluida == 0 || $oportunidad[0]->preprensa_concluida == null))
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusSolPP" name="idStatusEnd"
                                                            checked value="15" disabled>
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusSolPP" name="idStatusEnd"
                                                            value="15" disabled>
                                                    @endif
                                                    <label for="idStatusSolPP" class="custom-control-label">Solicitud Preprensa</label>
                                                </div>

                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->preprensa_concluida == 1)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusPPConcluida" name="idStatusEnd"
                                                            checked value="16" disabled>
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusPPConcluida" name="idStatusEnd"
                                                            value="16" disabled>
                                                    @endif
                                                    <label for="idStatusPPConcluida" class="custom-control-label">Preprensa Concluida</label>
                                                </div>
                                                
                                                <div class="mb-2 sr-only">
                                                    <label for="numOrdenMTC" class="mb-0">Orden Metrics <span class="ar d-none" id="lblOmts">*</span></label>
                                                    <div class="input-group">
                                                        <input id="numOrdenMTC" name="numOrdenMTC" type="text"
                                                            class="form-control text-uppercase" placeholder="Orden Metrics" {{ $oportunidad[0]->numOrdenMTC != null ? 'readonly' : '' }}
                                                            autocomplete="off" value="{{ $oportunidad[0]->numOrdenMTC }}">
                                                    </div>
                                                </div>
                                                {{--  Fin nuevos estatus  --}}

                                                <hr style="margin-top: 15px; margin-bottom: 15px;">

                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 11)
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusSolOP" name="idStatus" disabled
                                                            checked value="1">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusSolOP" name="idStatus" disabled
                                                            value="0">
                                                    @endif
                                                    <label for="idStatusSolOP" class="custom-control-label">Solicitud de OP </label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 12)
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusOPCon" name="idStatus" disabled
                                                            checked value="12">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            {{ $disabledCustomers }} id="idStatusOPCon" name="idStatus" disabled
                                                            value="12">
                                                    @endif
                                                    <label for="idStatusOPCon" class="custom-control-label">OP
                                                        Concluida </label><label style="font-size: 8px">&nbsp;
                                                        *Customers</label>
                                                </div>
                                                {{--  Remover en cuanto se ajuste todo  --}}
                                                {{--  <div class="sr-only">
                                                    <label for="numOrdenMTC">Orden Metrics <span class="ar d-none" id="lblOmts">*</span></label>
                                                    <div class="input-group">
                                                        <input id="numOrdenMTC" name="numOrdenMTC" type="text"
                                                            class="form-control text-uppercase" placeholder="Orden Metrics"
                                                            autocomplete="off" value="{{ $oportunidad[0]->numOrdenMTC }}">
                                                    </div>
                                                </div>  --}}
                                                <div class="form-group pb-2 {{ ($idrol == 1013 || $idrol == 8 || $idrol == 1 || $idrol == 3) ? '' : 'sr-only' }}">
                                                    <div class="custom-control custom-switch">
                                                        @if ($oportunidad[0]->archivoAutorizado == 1)
                                                            @if ($idrol == 1013 || $idrol == 8 || $idrol == 1)
                                                                <input id="archivoAutorizado" name="archivoAutorizado"
                                                                    type="checkbox" class="custom-control-input"
                                                                    onclick="desautorizarArchivo({{ $oportunidad[0]->idOportunidad }})"
                                                                    checked>
                                                            @else
                                                                <input id="archivoAutorizado" name="archivoAutorizado"
                                                                    type="checkbox" class="custom-control-input" checked
                                                                    disabled>
                                                            @endif
                                                        @else
                                                            <input id="archivoAutorizado" name="archivoAutorizado"
                                                                type="checkbox" class="custom-control-input"
                                                                onclick="autorizarArchivo({{ $oportunidad[0]->idOportunidad }})"
                                                                disabled>
                                                        @endif
                                                        <label id="archivoAutorizadoLabel"
                                                            class="custom-control-label pt-1" for="archivoAutorizado"
                                                            style="font-size: 10px">Archivo
                                                            Autorizado</label>
                                                        @if ($oportunidad[0]->archivoAutorizado == 1)
                                                            <label class="label-control text-bold"
                                                                style="font-size: 10px">{{ \Carbon\Carbon::parse($oportunidad[0]->fecArchivoAutorizado)->format('d/m/Y') }}</label>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="Valor">Valor de la Oportunidad <span class="ar">*</span></label>
                                                <div class="input-group">
                                                    <input id="Valor" name="Valor" type="text"
                                                        class="form-control" style="cursor: not-allowed"
                                                        placeholder="$0.00" readonly autocomplete="off"
                                                        value="{{ $oportunidad[0]->Valor }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="idCustomer_">Cotizado por <span class="{{ $oportunidad[0]->idEstatus >= 3 ? 'ar' : 'd-none' }}">*</span></label>
                                                <select id="idCustomer_" name="idCustomer_" class="form-control"
                                                    style="width: 100%;">
                                                    <option selected disabled>Cotizado por</option>
                                                    @foreach ($comboCot as $cotizador)
                                                        @if ($cotizador->Id_Usuario == $oportunidad[0]->idCustomer_)
                                                            <option value="{{ $cotizador->Id_Usuario }}" selected>
                                                                {{ $oportunidad[0]->Cotizado }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $cotizador->Id_Usuario }}">
                                                                {{ $cotizador->Nombre }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="idAtendido">Atendido por <span class="{{ $oportunidad[0]->idEstatus >= 3 ? 'ar' : 'd-none' }}">*</span></label>
                                                <select id="idAtendido" name="idAtendido" class="form-control"
                                                    style="width: 100%;">
                                                    <option selected disabled>Atendido por</option>
                                                    @foreach ($comboCus as $customer)
                                                        @if ($customer->Id_Usuario == $oportunidad[0]->idAtendido)
                                                            <option value="{{ $customer->Id_Usuario }}" selected>
                                                                {{ $oportunidad[0]->Atendido }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $customer->Id_Usuario }}">
                                                                {{ $customer->Nombre }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{--  <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Equipos</label>
                                                <select id="idEquipo" name="idEquipo" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($equipos as $equipo)
                                                        @if ($equipo->idEquipo == $oportunidad[0]->idEquipo)
                                                            <option value="{{ $equipo->idEquipo }}" selected>
                                                                {{ $oportunidad[0]->descEquipo }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $equipo->idEquipo }}">
                                                                {{ $equipo->descEquipo }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>  --}}
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="idAgente">Asignado a <span class="ar">*</span></label>
                                                <select id="idAgente" name="idAgente" class="form-control"
                                                    style="width: 100%;" 
                                                    @if($tieneSubAgentes)
                                                        disabled
                                                    @endif>
                                                    @foreach ($combo_vendedores as $vendedor)
                                                        @if ($vendedor->Id_Usuario == $idEquipo_idAgente)
                                                            <option value="{{ $vendedor->Id_Usuario }}" selected>
                                                                {{ $oportunidad[0]->Agente }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $vendedor->Id_Usuario }}">
                                                                {{ $vendedor->Nombre }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4">
                                                    @if ($oportunidad[0]->Maquila == 'on')
                                                        <input id="Maquila" name="Maquila" type="checkbox"
                                                            class="custom-control-input" checked>
                                                    @else
                                                        <input id="Maquila" name="Maquila" type="checkbox"
                                                            class="custom-control-input">
                                                    @endif
                                                    <label class="custom-control-label pointer"
                                                        for="Maquila">Marcar si es maquila </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mt-1 sr-only">
                                            <div class="form-group">
                                                <div class="tooltip mt-2">
                                                    <div class="custom-control custom-switch mt-2">
                                                        @if ($oportunidad[0]->requiereMaquilaExterior == 1)
                                                            <input id="Maquila" name="Maquila" type="checkbox"
                                                                class="custom-control-input" checked>
                                                        @else
                                                            <input id="Maquila" name="Maquila" type="checkbox"
                                                                class="custom-control-input">
                                                        @endif
                                                        <label class="custom-control-label pointer"
                                                            for="Maquila">Maquila (Compra Venta de Productos)</label>
                                                    </div>

                                                    <span class="tooltiptext">(Se necesita solicitar la compraventa de productos externos)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 mt-1">
                                            <label for="usuarioRevisionTec">Usuario Rev. Técnica</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="usuarioRevisionTec"
                                                    name="usuarioRevisionTec" placeholder="Usuario Rev. Técnica"
                                                    autocomplete="off" value="{{ $oportunidad[0]->usuarioRevTec }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        
                                        {{--  <div class="col-sm-3 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4">
                                                    @if ($oportunidad[0]->notaRetencion != '')
                                                        <input id="Retencion" name="Retencion" type="checkbox"
                                                            class="custom-control-input" checked>
                                                    @else
                                                        <input id="Retencion" name="Retencion" type="checkbox"
                                                            class="custom-control-input">
                                                    @endif
                                                    <label class="custom-control-label pointer"
                                                        for="Retencion">Retenida</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 sr-only">
                                            <div class="form-group">
                                                <label for="notaRetencion">Nota Retención</label>
                                                <div class="input-group">
                                                    <input id="notaRetencion" name="notaRetencion" type="text"
                                                        class="form-control" placeholder="Nota Retención"
                                                        autocomplete="off" value="{{ $oportunidad[0]->notaRetencion }}">
                                                </div>
                                            </div>
                                        </div>  --}}
                                    </div>
                                    <div class="row mb-1 mt-3">
                                        <div class="col-md-3">
                                            <label for="fecCreacion">Fecha Alta</label>
                                            <div class="input-group">
                                                @if ($oportunidad[0]->fecCreacion == null)
                                                    <input id="fecAlta" type="text" class="form-control"
                                                        autocomplete="off" placeholder="dd/mm/aaaa" disabled>
                                                @else
                                                    <input id="fecCreacion" type="text" class="form-control"
                                                        autocomplete="off"
                                                        value="{{ \Carbon\Carbon::parse($oportunidad[0]->fecCreacion)->format('d/m/Y H:i') }}"
                                                        disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="Creado">Usuario Alta</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="Creado"
                                                    placeholder="Usuario Alta" autocomplete="off"
                                                    value="{{ $oportunidad[0]->Creado }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="fecUltimoCambio">Fecha Ult. Cambio</label>
                                            <div class="input-group">
                                                @if ($oportunidad[0]->fecUltimoCambio == null)
                                                    <input id="fecUltimoCambio" type="text" class="form-control"
                                                        autocomplete="off" placeholder="dd/mm/aaaa" disabled>
                                                @else
                                                    <input id="fecUltimoCambio" type="text" class="form-control"
                                                        autocomplete="off"
                                                        value="{{ \Carbon\Carbon::parse($oportunidad[0]->fecUltimoCambio)->format('d/m/Y H:i') }}"
                                                        disabled>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="idUsuarioUltCambio">Usuario Ult. Cambio</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="idUsuarioUltCambio"
                                                    name="idUsuarioUltCambio" placeholder="Usuario Ult. Cambio"
                                                    autocomplete="off" value="{{ $oportunidad[0]->UsuarioUltCambio }}"
                                                    disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="nota">Notas Oportunidad <span class="ar">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nota" name="nota"
                                                    placeholder="Nota" autocomplete="off"
                                                    value="{{ $oportunidad[0]->nota }}"
                                                    title="{{ $oportunidad[0]->nota }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="time_line" class="col-lg-5 col-sm-12 mt-2"
                                    style="border: 1px solid #ddd; border-radius: 10px">

                                    <div id="scrollbarTimeLine" class="row mt-2 mb-1">
                                        <div class="col-12">
                                            <div class="timeline mt-1">
                                                @if ($oportunidad[0]->fecCreacion == null)
                                                    <div style="display: {{ $hiddenPreregistro }}">
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Pre Registro</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div style="display: {{ $hiddenPreregistro }}">
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecCreacion)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecCreacion)->format('H:i:s') }}</span>
                                                                <span style="float: right">Pre Registro</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecSolCotizacion == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Solicitud de
                                                                    Cotización</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecSolCotizacion)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecSolCotizacion)->format('H:i:s') }}</span>
                                                                <span style="float: right">Solicitud de
                                                                    Cotización</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecListoCotizar == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right"> Listo p/cotizar</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecListoCotizar)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecListoCotizar)->format('H:i:s') }}</span>
                                                                <span style="float: right">Listo p/cotizar</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecCotizando == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Cotizando</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecCotizando)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecCotizando)->format('H:i:s') }}</span>
                                                                <span style="float: right">Cotizando</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecRevisionTec == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Revisión Técnica</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecRevisionTec)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecRevisionTec)->format('H:i:s') }}</span>
                                                                <span style="float: right">Revisión Técnica</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecRevision == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Autorización</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecRevision)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecRevision)->format('H:i:s') }}</span>
                                                                <span style="float: right">Autorización</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                {{-- @if ($oportunidad[0]->fecEsperaVobo == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Espera Vo.
                                                                    Bo.</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecEsperaVobo)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecEsperaVobo)->format('H:i:s') }}</span>
                                                                <span style="float: right">Espera Vo.
                                                                    Bo.</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif --}}
                                                @if ($oportunidad[0]->fecListoPresupuesto == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Listo
                                                                    p/presupuesto</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecListoPresupuesto)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecListoPresupuesto)->format('H:i:s') }}</span>
                                                                <span style="float: right">Listo
                                                                    p/presupuesto</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecPresupTerminado == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Presupuesto
                                                                    terminado</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecPresupTerminado)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecPresupTerminado)->format('H:i:s') }}</span>
                                                                <span style="float: right">Presupuesto
                                                                    terminado</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($oportunidad[0]->fecSolPreprensa == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Solicitud Preprensa</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecSolPreprensa)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecSolPreprensa)->format('H:i:s') }}</span>
                                                                <span style="float: right">Solicitud Preprensa</span>

                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($oportunidad[0]->fecSolPreprensaConcluida == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Preprensa Concluida</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecSolPreprensaConcluida)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecSolPreprensaConcluida)->format('H:i:s') }}</span>
                                                                <span style="float: right">Preprensa Concluida</span>

                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($oportunidad[0]->fecResultadoF == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Resultado
                                                                    final</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecResultadoF)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecResultadoF)->format('H:i:s') }}</span>
                                                                <span style="float: right">Resultado
                                                                    final</span>

                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecOrdenConcluida == null)
                                                    <div>
                                                        <i class="fas fa-times bg-red"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">OP
                                                                    Concluida</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="mb-5">
                                                        <i class="fas fa-clock bg-red"></i>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecOrdenConcluida)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecOrdenConcluida)->format('H:i:s') }}</span>
                                                                <span style="float: right">Concluida</span>

                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="mb-5">
                                                        <i class="fas fa-clock bg-green"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($oportunidad[0]->NotaCancelacion != null)
                                                <label class="ml-5">Nota Cancelación : </label>
                                                <strong class="text-red">{{ $oportunidad[0]->NotaCancelacion }}</strong>
                                            @endif
                                            <div class="mt-1 pl-5">
                                                Tiempos de Entrega
                                                <table id="tblTEntrega" class="table compact nowrap">
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
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="mt-1 pl-5">
                                                <label>Retenciones</label>
                                                <table id="tblOporRetenidas"
                                                    class="table table-hover table-striped nowrap compact cell-border row-border">
                                                    <thead>
                                                        <tr class="bg-gray">
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="mt-1 pl-5">
                                                <label>Rechazos</label>
                                                <table id="tblOporRechazos"
                                                    class="table table-hover table-striped nowrap compact cell-border row-border">
                                                    <thead>
                                                        <tr class="bg-gray">
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="col-12 pl-5 sr-only">
                                                <label for="notasProduccion">Notas Producción</label>
                                                <div class="input-group">
                                                    <textarea name="notasProduccion" id="notasProduccion" cols="30" rows="10" class="form-control" style="height: 80px !important" readonly>{{ $oportunidad[0]->notasProduccion }}</textarea>
                                                    {{-- <input type="text" class="form-control" id="notasProduccion"
                                                        name="notasProduccion" placeholder="Notas Produccion"
                                                        autocomplete="off"
                                                        value="{{ $oportunidad[0]->notasProduccion }}"
                                                        title="{{ $oportunidad[0]->notasProduccion }}" readonly> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade {{ $idrol != 1020 ? '' : 'sr-only' }}" id="elementos-oport" role="tabpanel"
                            aria-labelledby="elementos-oport-tab">

                            <div class="col-12" id="div-btnrelacionar">
                                <ol class="breadcrumb float-sm-right" style="padding: 0% !important;">
                                    {{--  <li class="breadcrumb-item" style="margin-right: 5px;">
                                        <a onclick="OpenModal('mod_EP_relation')" type="button"
                                            class="btn btn-block btn-primary btn-sm rounded-btn" id="btn-relacinar"
                                            title="Relacionar">
                                            <i class="far fa-copy"></i> Relacionar
                                        </a>
                                    </li>  --}}
                                    
                                    @if (($oportunidad[0]->idEstatus <= 2 && ($idrol == 4 || $idrol == 9)) || ($idrol == 1 || $idrol == 8 || $idrol == 10) && ($total_elementos_oportunidad <= 4 || ($idrol == 1 || $idrol == 8)))
                                    <a type="button" class="btn-flotante button btn btn-primary"
                                        title="Nuevo Elemento Presupuestado"
                                        href="{{ url('elementosp/create') }}/{{ $oportunidad[0]->idOportunidad }}/1">
                                        <i class="fas fas fa-plus-circle"></i>
                                    </a>
                                    @endif
                                </ol>
                            </div>
                            @component('sections.EP_tblIndex', ['oportunidad' => $oportunidad, 'idrol' => $idrol, 'catCategoriasFsc' => $catCategoriasFsc, 'catSubCategoriasFsc' => $catSubCategoriasFsc])
                            @endcomponent
                        </div>
                        <div class="tab-pane fade {{ $idrol != 1020 ? '' : 'sr-only' }}" id="estructural-oport" role="tabpanel"
                            aria-labelledby="estructural-oport-tab">

                            <div class="col-12" id="div-btnrelacionarEstr">
                                <ol class="breadcrumb float-sm-right" style="padding: 0% !important;">

                                    @if ($crearTickets == 'Crear' && $id_usuario != 13582)
                                        <button onclick="agregarTicketOport(1, 1)" type="button" id="agregarProyecto"
                                            data-target="modal" class="btn-flotante button btn btn-primary"
                                            title="Agregar Proyecto">
                                            <i class="fas fas fa-plus-circle"></i>
                                        </button>
                                    @endif

                                    {{-- <a type="button" class="btn-flotante button btn btn-primary"
                                        title="Nuevo Elemento Presupuestado"
                                        href="{{ url('elementos/create') }}/{{ $oportunidad[0]->idOportunidad }}"
                                        onclick="loaderON()">
                                        <i class="fas fas fa-plus-circle"></i>
                                    </a> --}}

                                </ol>
                            </div>

                            @component('sections.DE_tblIndex')
                            @endcomponent

                        </div>
                        <div class="tab-pane fade" id="documentos-oport" role="tabpanel"
                            aria-labelledby="documentos-oport-tab">
                            @component('sections.Documentos')
                            @endcomponent
                        </div>
                        {{-- <div class="tab-pane fade" id="correos-oport" role="tabpanel"
                            aria-labelledby="correos-oport-tab">
                            @component('sections.Correos')
                            @endcomponent
                        </div> --}}
                        <div class="tab-pane fade {{ $idrol != 1020 ? '' : 'sr-only' }}" id="entregas-oport" role="tabpanel"
                            aria-labelledby="entregas-oport-tab">

                            <div class="row justify-content-center">
                                <div id="divEntregas" class="col-md-12">
                                    <div class="row mb-1 mt-2">
                                        <div class="col-md-12">
                                            <h4 class="text-center">
                                                Cantidades por Sucursal
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="idSucursal">Sucursal</label>
                                            <select id="idSucursal" name="idSucursal" class="form-control"
                                                style="width: 100%; text-transform: uppercase">
                                                <option selected disabled>Sucursal</option>
                                                @foreach ($sucursales_combo_entregas as $sucursal)
                                                    <option value="{{ $sucursal->idSucursal }}"
                                                        style="text-transform: uppercase !important">
                                                        {{ $sucursal->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="cantidadSucursal">Cantidad</label>
                                            <div class="input-group">
                                                <input id="cantidadSucursal" name="cantidadSucursal" type="text"
                                                    class="form-control" placeholder="Cantidad" autocomplete="off"
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Fecha Entrega</label>
                                            <div class="input-group date" data-target-input="nearest" id="fecEntrega">
                                                <input id="fecEntregaInput" name="fecEntrega" type="text"
                                                    class="form-control datetimepicker-input" data-target="#fecEntrega"
                                                    placeholder="Fecha Entrega" />
                                                <div class="input-group-append" data-target="#fecEntrega"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="totalCantidadOP">Total OP</label>
                                            <div class="input-group">
                                                <input type="text" value="{{ $total_cantidad }}"
                                                    id="totalCantidadOP" class="form-control text-center text-bold"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pt-4 text-right">
                                            <a onclick="agregarCantidad()"
                                                class="btn btn-primary btn-sm rounded-circle pointer"
                                                title="Agregar Cantidad"><i class="fas fa-plus-circle"></i></a>
                                        </div>
                                    </div>
                                    <table id="tableEntregasOportunidades"
                                        class="table table-striped compact nowrap w-100">
                                        <thead>
                                            <tr class="bg-gray">
                                                <th class="w-75"></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr class="bg-gray text-md">
                                                <th class="w-75"></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @include('estructural.create')
        @include('oportunidades.tiempo_entrega')
    </div>

    @include('modals.mod_mailDocs')
    @include('modals.visorPDF')
    @include('clientes.mod_UpDocs')
    @include('modals.mod_EP_relation')
    @include('modals.mod_DE_relation')
    @include('oportunidades.mod_UpDocsEstr')
    @include('oportunidades.mod_upload_docs')
    @include('oportunidades.mod_excel_ep')
    @include('oportunidades.mod-registro-presupuesto')
    @include('oportunidades.modal-excel-geps')

    <style>
        .ar {
            color: red;
            font-weight: bold;
            margin-left: 2px;
        }

        .swal2-select {
            background-color: #fff;
            border-color: #fff;
        }

        .swal2-select:focus {
            outline: none;
        }

        .swal2-content {
            margin: 0.5rem 0;
        }

        .solar--notes-bold {
            display: inline-block;
            width: 25px;
            height: 25px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23cf2a2a' d='m2.755 14.716l.517 1.932c.604 2.255.907 3.383 1.592 4.114a4 4 0 0 0 2.01 1.16c.976.228 2.104-.074 4.36-.678c2.254-.604 3.382-.906 4.113-1.591q.091-.086.176-.176a9 9 0 0 1-1.014-.15c-.696-.138-1.523-.36-2.501-.622l-.107-.029l-.025-.006c-1.064-.286-1.953-.524-2.663-.78c-.747-.27-1.425-.603-2.002-1.143a5.5 5.5 0 0 1-1.596-2.765c-.18-.769-.128-1.523.012-2.304c.134-.749.374-1.647.662-2.722l.535-1.994l.018-.07c-1.92.517-2.931.823-3.605 1.454a4 4 0 0 0-1.161 2.012c-.228.975.074 2.103.679 4.358'/%3E%3Cpath fill='%23cf2a2a' fill-rule='evenodd' d='m20.83 10.715l-.518 1.932c-.605 2.255-.907 3.383-1.592 4.114a4 4 0 0 1-2.01 1.161q-.145.034-.295.052c-.915.113-2.032-.186-4.064-.73c-2.255-.605-3.383-.907-4.114-1.592a4 4 0 0 1-1.161-2.011c-.228-.976.074-2.103.679-4.358l.517-1.932l.244-.905c.455-1.666.761-2.583 1.348-3.21a4 4 0 0 1 2.01-1.16c.976-.228 2.104.074 4.36.679c2.254.604 3.382.906 4.113 1.59a4 4 0 0 1 1.161 2.012c.228.976-.075 2.103-.679 4.358m-9.778-.91a.75.75 0 0 1 .919-.53l4.83 1.295a.75.75 0 1 1-.389 1.448l-4.83-1.294a.75.75 0 0 1-.53-.918m-.776 2.898a.75.75 0 0 1 .918-.53l2.898.777a.75.75 0 1 1-.388 1.448l-2.898-.776a.75.75 0 0 1-.53-.919' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        .checklist-op-checkbox:focus {
            outline: none;
        }
    </style>

    <script src=" {{ asset('js/oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_Docs.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_EP.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_DE.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/estructural.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_estatus.js?n=1') }} " defer></script>


@endsection


@section('scripts')
    <script>
        $(document).ready(function() {

            doc = window.location.search;
            var dateToday = new Date();


            // $('#fecPrevCiere').datetimepicker({
            //     format: 'DD/MM/YYYY',
            //     autoclose: true,
            //     todayBtn: true,
            // });

            // $('#fecEntrega').datetimepicker({
            //     format: 'DD/MM/YYYY',
            //     autoclose: true,
            //     todayBtn: true,
            // });

            var btnsave = document.getElementById('oportBtnSave');
            var omtc = document.getElementById('numOrdenMTC');
            var btnAddPresupuesto = document.getElementById('btnAddPresupuesto');
            var btnAddCambiosMenores = document.getElementById('btnAddCambiosMenores');
            var motivo_perdida = document.getElementById('idMotivoPerdida');
            //var retencion = document.getElementById('Retencion');
            // var notaretencion = document.getElementById('notaRetencion');
            var btnCancelar = document.getElementById('btnCancelar');
            var isCancelled = document.getElementById('isCancelled');

            var idcliente = document.getElementById('idCliente');
            var idcontacto = document.getElementById('idContacto');
            var nomoportunidad = document.getElementById('nomOportunidad');
            // var fecReqPreCotInput = document.getElementById('fecReqPreCotInput');
            // var fecPrevCiereInput = document.getElementById('fecPrevCiereInput');
            var fechaTopeOportInput = document.getElementById('fechaTopeOportInput');
            //var notaretencionVal = $('#notaRetencion').val();
            // var retencionCheck = $('#Retencion').checked;
            var cancelada = $('#Cancelada').val();
            // $("#notaRetencion").prop("readonly", true);
            var idAtendido = document.getElementById('idAtendido');
            // var idEquipo = document.getElementById('idEquipo');
            var idAgente = document.getElementById('idAgente');
            var idTipo = document.getElementById('idTipo');
            var idOportunidad = document.getElementById('idOportunidad');
            var idRol = document.getElementById('idRol');
            var idUsuario = document.getElementById('idUsuario');
            // var tabEntregasOport = document.getElementById('tabEntregasOport');
            // var btnSolFecEntrega = document.getElementById('btnSolFecEntrega');
            var archivoAutorizado = document.getElementById('archivoAutorizado');
            var nota = document.getElementById('nota');
            var divSolicfichatecnica = document.getElementById('divSolicfichatecnica');
            var isLiberado = document.getElementById('isLiberado').value;
            var solicitud_op = document.getElementById('idStatusSolOP').value;

            let hFt = $('#hasFichaT').val();

            if (hFt == 1) {
                $('#divSolicfichatecnica').show();
                $("#sFicTec").prop('disabled', true);
            }

            getTypeTablaCliente(idcliente.value);

            var idContactoSelec2 = $('#idContacto').select2({
                language: "es"
            });
            // tabEntregasOport.style.display = 'none';

            motivo_perdida.disabled = true;

            $('#FechaPrimeraEntregaInput').appendDtpicker({
                locale: 'es',
                futureOnly: true,
                dateOnly: true,
                closeOnSelected: true,
                dateFormat: 'DD-MM-YYYY',
                todayButton: false,
                closeButton: false,
                allowWdays: true,
                autodateOnStart: false,
                amPmInTimeList: true,
                timelistScroll: true,
                calendarMouseScroll: false,
                // allowWdays: [1, 2, 3, 4, 5],
                onSelect: function(handler, targetDate) {
                    // validarRequeridosOpor();
                },
            });

            $('#FechaUltimaEntregaInput').appendDtpicker({
                locale: 'es',
                futureOnly: true,
                dateOnly: true,
                closeOnSelected: true,
                dateFormat: 'DD-MM-YYYY',
                todayButton: false,
                closeButton: false,
                allowWdays: true,
                autodateOnStart: false,
                amPmInTimeList: true,
                timelistScroll: true,
                calendarMouseScroll: false,
                // allowWdays: [1, 2, 3, 4, 5],
                onSelect: function(handler, targetDate) {
                    // validarRequeridosOpor();
                },
            });

            // $('#fecPrevCiereInput').appendDtpicker({
            //     locale: 'es',
            //     futureOnly: true,
            //     dateOnly: true,
            //     closeOnSelected: true,
            //     dateFormat: 'DD-MM-YYYY',
            //     todayButton: false,
            //     closeButton: false,
            //     allowWdays: true,
            //     autodateOnStart: false,
            //     amPmInTimeList: true,
            //     timelistScroll: true,
            //     calendarMouseScroll: false,
            //     allowWdays: [1, 2, 3, 4, 5],
            //     onSelect: function(handler, targetDate) {
            //         validarRequeridosOpor();
            //     },
            // });

            /*$('#fecReqPreCotInput').appendDtpicker({
                locale: 'es',
                futureOnly: true,
                dateOnly: true,
                closeOnSelected: true,
                dateFormat: 'DD-MM-YYYY',
                todayButton: false,
                closeButton: false,
                allowWdays: true,
                autodateOnStart: false,
                amPmInTimeList: true,
                timelistScroll: true,
                calendarMouseScroll: false,
                allowWdays: [1, 2, 3, 4, 5],
                onSelect: function(handler, targetDate) {
                    validarRequeridosOpor();
                },
            });*/

            $('#fechaTopeOportInput').appendDtpicker({
                locale: 'es',
                futureOnly: true,
                dateOnly: true,
                closeOnSelected: true,
                dateFormat: 'DD-MM-YYYY',
                todayButton: false,
                closeButton: false,
                allowWdays: true,
                autodateOnStart: false,
                amPmInTimeList: true,
                timelistScroll: true,
                calendarMouseScroll: false,
                allowWdays: [1, 2, 3, 4, 5],
                onSelect: function(handler, targetDate) {
                    validarRequeridosOpor();
                },
            });

            validarRequeridosOpor();

            /*if (omtc.value == 'Orden Metrics' || omtc.value == '') {
                tabEntregasOport.style.display = 'none';
            } else {
                tabEntregasOport.style.display = 'block';
            }*/
            nomoportunidad.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            idcliente.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            idContactoSelec2.on('change', function() {
                validarRequeridosOpor();
            });

            /*fecReqPreCotInput.addEventListener('focusout', function() {
                validarRequeridosOpor();
            });*/

            // fecPrevCiereInput.addEventListener('focusout', function() {
            //     validarRequeridosOpor();
            // });

            /*idEquipo.addEventListener('change', function() {
                idAgente.value = "Requerido";
                validarRequeridosOpor();
            });

            idEquipo.addEventListener('focusout', function() {
                validarRequeridosOpor();
            });*/

            idAgente.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            idAgente.addEventListener('focusin', function() {
                validarRequeridosOpor();
            });

            nota.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            idAtendido.addEventListener('change', function() {
                if ($('#Estatus').val() == 3) {
                    $("#idStatusPre").prop('disabled', true);
                    $("#idStatusSolCot").prop('disabled', true);
                    // $("#idStatusListoCot").prop('disabled', false);
                    // $("#clasificacionOportunidad").prop('disabled', false);

                    // if (idAtendido.value != "Atendido por") {
                    //     $("#idStatusCot").prop('disabled', false);
                    // } else {
                    //     $("#idStatusCot").prop('disabled', true);
                    // }

                    // $("#idStatusRev").prop('disabled', true);
                    // $("#idStatusVoBo").prop('disabled', true);
                    // $("#idStatusListoPre").prop('disabled', true);
                    // $("#idStatusPreTerm").prop('disabled', true);
                    // $("#idStatusGanado").prop('disabled', true);
                    // $("#idStatusPerdido").prop('disabled', true);
                    // $("#idStatusSolOP").prop('disabled', true);
                    // $("#idStatusOPCon").prop('disabled', true);
                }
            });

            function validarRequeridosOpor() {
                var idContactoSelec2 = $('#idContacto').select2("data");

                if (nomoportunidad.value == "" || idcliente.value == "Requerido" || idcontacto.value ==
                    "Requerido" || idcontacto.value == "requerido" || idcliente.value ==
                    "requerido" || nota.value == "") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (nomoportunidad.value == "") {
                    nomoportunidad.style.borderColor = 'red';
                } else {
                    nomoportunidad.style.borderColor = 'green';
                }

                if (idcliente.value == "Requerido" || idcliente.value == "requerido") {
                    idcliente.style.borderColor = 'red';
                } else {
                    idcliente.style.borderColor = 'green';
                }

                if (idContactoSelec2[0].element.value != "Requerido") {
                    $('#idContacto').select2().addClass('select2-green')
                } else {
                    $('#idContacto').select2().addClass('select2-red')
                }

                /*if (fecReqPreCotInput.value == "") {
                    fecReqPreCotInput.style.borderColor = 'red';
                } else {
                    fecReqPreCotInput.style.borderColor = 'green';
                }*/

                // if (fecPrevCiereInput.value == "") {
                //     fecPrevCiereInput.style.borderColor = 'red';
                // } else {
                //     fecPrevCiereInput.style.borderColor = 'green';
                // }

                /*if (idEquipo.value == "Requerido") {
                    idEquipo.style.borderColor = 'red';
                } else {
                    idEquipo.style.borderColor = 'green';
                }*/
                if (idAgente.value == "Requerido") {
                    idAgente.style.borderColor = 'red';
                } else {
                    idAgente.style.borderColor = 'green';
                }
                if (nota.value == "") {
                    nota.style.borderColor = 'red';
                } else {
                    nota.style.borderColor = 'green';
                }
            }

            idTipo.addEventListener('change', function() {
                estatusValue = $('input:radio[name="idStatus"]');
                // console.log(estatusValue[2].checked);
                if (idTipo.value == 4) {
                    if (estatusValue[2].checked == true || estatusValue[3].checked == true || estatusValue[
                            4].checked == true || estatusValue[5].checked == true || estatusValue[7]
                        .checked == true || estatusValue[8].checked == true) {
                        fechaTopeOportInput.disabled = false;
                        fechaTopeOportInput.required = true;
                        $('#fecLimLic').removeClass('d-none');
                    } else {
                        fechaTopeOportInput.disabled = true;
                        fechaTopeOportInput.required = false;
                        fechaTopeOportInput.value = '';
                        $('#fecLimLic').addClass('d-none');
                    }
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    fechaTopeOportInput.value = '';
                    $('#fecLimLic').addClass('d-none');
                }
            });

            $('input:radio[name="idStatus"]').change(function() {

                if (this.checked && this.value != '9') {
                    // $('#divSolicfichatecnica').hide();
                    $("#sFicTec").prop('disabled', true);
                    $('#divSolicfichatecnica').show();
                } else {
                    $("#sFicTec").prop('disabled', false);
                }

                // if (this.checked && this.value == '2') {
                //     $.ajax({
                //         url: serve + 'getEPXOport',
                //         type: "GET",
                //         data: {
                //             idOportunidad: idOportunidad.value,
                //         },
                //         success: function(data) {
                //             if (data.length > 0) {
                //                 eps = data[0].EPs;
                //             } else {
                //                 eps = 0;
                //             }

                //             if (eps == 0) {
                //                 Swal.fire({
                //                     title: '¡No puedes cambiar de estatus!',
                //                     text: '¡Falta crear o relacionar un Elemento Presupuestado!',
                //                     confirmButtonText: 'Aceptar',
                //                 }).then((result) => {
                //                     mostrarElementosOport();
                //                 })
                //                 $('#idStatusPre').prop('checked', true);
                //             }
                //         },
                //         error: function(data) {
                //             eps = 0;
                //             Swal.fire("Ocurrio un error en el servidor ..")
                //         },

                //     });
                //     // } else if (this.checked && this.value == '7') {

                //     //     if ($('#Valor').val() <= 0) {
                //     //         Swal.fire({
                //     //             title: '¡No puedes cambiar de estatus!',
                //     //             text: '¡Falta Asignar el folio del Presupuesto de Metrics!',
                //     //             confirmButtonText: 'Aceptar',
                //     //         }).then((result) => {
                //     //             mostrarElementosOport();
                //     //         })
                //     //         $('#idStatusRev').prop('checked', true);
                //     //     }
                if (this.checked && this.value == '2') {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.value = '';
                    fechaTopeOportInput.required = false;
                    $("#tipoEspera").prop('disabled', true);
                    // $("#clasificacionOportunidad").prop('disabled', false);
                    // } else if (this.checked && this.value == '3') {
                    //     $("#clasificacionOportunidad").prop('disabled', false);
                    // } else if (this.checked && this.value == '4') {
                    //     $("#clasificacionOportunidad").prop('disabled', true);
                    // } else if (this.checked && this.value == '5') {
                    //     $("#clasificacionOportunidad").prop('disabled', true);
                } else if (this.checked && this.value == '3' || this.checked && this.value == '4' || this
                    .checked && this.value == '5' || this.checked && this.value == '7' || this.checked &&
                    this.value == '8') {
                    idTipoValue = $('#idTipo').val();

                    if (this.checked && this.value == '3') {
                        $("#tipoEspera").prop('disabled', true);
                    }

                    if (this.checked && this.value == '4') {
                        $("#tipoEspera").prop('disabled', true);
                        $("#tipoEspera").val('');
                    }

                    if (this.checked && this.value == '5') {
                        $("#tipoEspera").prop('disabled', false);
                        $('#lblAutorizacion').removeClass('d-none');
                        if ($("#tipoEspera").val() == null) {
                            $("#tipoEspera").val('GUSTAVO CASAS');
                        }
                    }

                    if (this.checked && this.value == '7') {
                        $("#tipoEspera").prop('disabled', true);
                    }

                    if (this.checked && this.value == '8') {
                        $("#tipoEspera").prop('disabled', true);
                        // $('#btnAddPresupuesto').removeClass('disable');
                        $('#btnAddCambiosMenores').removeClass('disable');
                        $('#idStatusListoPre').removeClass('disable');
                        $('#idStatusListoPre').prop('disabled', false);


                        if ($("#Valor").val() == '0.00') {
                            $("#Valor").prop('required', true);
                            $("#oportBtnSave").prop('disabled', true);
                            /*Swal.fire({
                                title: '¡Presupuesto Terminado!',
                                text: '¡Se tiene que capturar el Valor del Presupuesto para cambiarlo a Ganado!',
                                confirmButtonText: 'Aceptar',
                            }).then((result) => {});*/
                        } else {
                            $("#Valor").prop('required', false);
                            $("#oportBtnSave").prop('disabled', false);
                        }

                        $.ajax({
                            url: serve + 'getCambiosPendientes',
                            type: "GET",
                            data: {
                                idOportunidad: idOportunidad.value,
                            },
                            success: function(data) {
                                if (data.success) {
                                    let textoAMostrar = '';

                                    data.data.forEach(cambio => {
                                        textoAMostrar += `<strong>Folio:</strong> ${cambio.folio}, <strong>Descripción:</strong> ${cambio.descripcion}, <strong>Categoría:</strong> ${getTipoCambio(cambio.tipo_cambio)}<br><br>`;
                                    });

                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.onmouseenter = Swal.stopTimer;
                                            toast.onmouseleave = Swal.resumeTimer;
                                        }
                                    });

                                    Toast.fire({
                                        title: 'Cambios Pendientes',
                                        html: textoAMostrar,
                                    });

                                    // Removemos el checkbox de este estatus
                                    $('#idStatusListoPre').prop('checked', true);
                                    $('#idStatusListoPre').prop('disabled', false);
                                    $('#idStatusListoPre').removeClass('disable');
                                    $('#idStatusPreTerm').prop('checked', false);
                                }
                            },
                            error: function(data) {
                                documento = 0;
                                Swal.fire("Ocurrio un error en el servidor ..")
                            },
                        });
                    }

                    if (idTipoValue == 4) {
                        fechaTopeOportInput.disabled = false;
                        fechaTopeOportInput.required = true;
                        $('#fecLimLic').removeClass('d-none');
                    } else {
                        fechaTopeOportInput.disabled = true;
                        fechaTopeOportInput.required = false;
                        $('#fecLimLic').addClass('d-none');
                    }
                } else if (this.checked && this.value == '6') {
                    $("#tipoEspera").prop('disabled', true);
                } else if (this.checked && this.value == '8') {
                    omtc.disabled = true;
                    motivo_perdida.value = "Motivo Perdida";
                    motivo_perdida.disabled = true;
                    $("#numOrdenMTC").prop('required', false);
                    $("#idMotivoPerdida").prop('required', false);
                    retencion.disabled = true;
                    retencion.checked = false;
                    notaretencion.value = "";
                    notaretencion.readOnly = true;
                    notaretencion.required = false;
                    notaretencion.style.borderColor = 'silver';
                    $("#tipoEspera").prop('disabled', true);
                    $('#btnAddCambiosMenores').removeClass('disable');

                    if ($("#Valor").val() == '0.00') {
                        $("#Valor").prop('required', true);
                        $("#oportBtnSave").prop('disabled', true);
                    } else {
                        $("#Valor").prop('required', false);
                        $("#oportBtnSave").prop('disabled', false);
                    }

                    $.ajax({
                        url: serve + 'getDocumentosXidOportunidad',
                        type: "GET",
                        data: {
                            idOportunidad: idOportunidad.value,
                        },
                        success: function(data) {
                            if (data.length > 0) {
                                documento = data[0].NoDocs;
                            } else {
                                documento = 0;
                            }

                            if (documento == 0) {
                                Swal.fire({
                                    title: '¡No puedes cambiar de estatus!',
                                    text: '¡Falta subir el documento del presupuesto!',
                                    confirmButtonText: 'Aceptar',
                                }).then((result) => {
                                    mostrarDocumentosOport();
                                })
                                $('#idStatusListoPre').prop('checked', true);
                                // } else if ($('#Valor').val() == '') {
                                //     // Swal.fire({
                                //     //     title: '¡No puedes cambiar de estatus!',
                                //     //     text: '¡Falta Asignar el folio del Presupuesto de Metrics!',
                                //     //     confirmButtonText: 'Aceptar',
                                //     // }).then((result) => {
                                //     //     // mostrarElementosOport();
                                //     // })

                                //     $('#btnAddPresupuesto').addClass('disable');

                                //     // $('#idStatusListoPre').prop('checked', true);
                            } else {
                                // $('#btnAddPresupuesto').removeClass('disable');
                                $('#btnAddCambiosMenores').removeClass('disable');
                            }

                        },
                        error: function(data) {
                            documento = 0;
                            Swal.fire("Ocurrio un error en el servidor ..")
                        },

                    });


                } else if (this.checked && this.value == '9') {
                    valor_oportunidad = $('#Valor').val();
                    valor_cambios = $('#cambiosRealizados').val();
                    valor_cambios_edit = $('#cambiosRealizadosEdit').val();
                    $('#lblMotivoPerdida').addClass('d-none');

                    let isMajorMilion = (Number(valor_oportunidad.replaceAll(',','')) >= 1000000)

                    let iU = $('#idUsuario').val()
                    let validations =  false;
                    
                    $("#tipoEspera").prop('disabled', true);
                    if (valor_oportunidad == 0) {
                        Swal.fire({
                            title: '¡No puedes cambiar de estatus!',
                            text: '¡Falta el valor del Presupuesto!',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            $('#idStatusPreTerm').prop('checked', true);
                            $('#idMotivoPerdida').prop('disabled', true);
                        });
                    } else if (validations) {
                        Swal.fire({
                            title: '¡No puedes cambiar de estatus!',
                            text: '¡Los Cambios Mínimos no se han Realizado!',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {
                            $('#idStatusPreTerm').prop('checked', true);
                        });
                    } else {
                        omtc.disabled = true;
                        motivo_perdida.value = "Motivo Perdida";
                        motivo_perdida.disabled = true;
                        $("#numOrdenMTC").prop('required', false);
                        $("#idMotivoPerdida").prop('required', false);
                        // $("#fecReqPreCotInput").prop('disabled', false);
                        $("#nota").prop('disabled', false);
                        $("#Premio").prop('disabled', false);
                    }
                } else if (this.checked && this.value == '10') {
                    omtc.disabled = true;
                    // tabEntregasOport.style.display = 'none';
                    motivo_perdida.disabled = false;
                    $("#idMotivoPerdida").prop('required', true);
                    $("#numOrdenMTC").prop('required', false);
                    $("#tipoEspera").prop('disabled', true);
                    $('#lblMotivoPerdida').removeClass('d-none');
                    $('#lblOmts').addClass('d-none');
                } else if (this.checked && this.value == '11') {
                    omtc.disabled = true;
                    motivo_perdida.disabled = true;
                    $("#idMotivoPerdida").prop('required', false);
                    // $("#numOrdenMTC").prop('required', true);
                    // $("#tipoEspera").prop('disabled', true);
                    $('#lblOmts').removeClass('d-none');
                } else if (this.checked && this.value == '15') {
                    // $("#numOrdenMTC").prop('disabled', false);
                    // $("#numOrdenMTC").prop('required', true);
                    // $("#numOrdenMTC").focus();
                } else if (this.checked && this.value == '16') {
                    // $("#numOrdenMTC").prop('disabled', false);
                    // $("#numOrdenMTC").prop('required', true);
                    // $("#numOrdenMTC").focus();
                } else if (this.checked && this.value == '12') {
                    omtc.disabled = false;
                    motivo_perdida.disabled = true;
                    $("#idMotivoPerdida").prop('required', false);
                    $("#oportBtnSave").prop('disabled', false);
                    $('#lblOmts').removeClass('d-none');
                    $("#numOrdenMTC").prop('disabled', true);
                    // tabEntregasOport.style.display = 'block';
                    $("#tipoEspera").prop('disabled', true);

                    if (idRol.value == 1|| idRol.value == 3 || idRol.value == 8 || idRol.value == 9) {
                        if ($('#hasFichaT').val() == '') {
                            // Agregamos modal con sweetalert para preguntar si el trabajo es repetitivo
                            Swal.fire({
                                title: '¿Solicitar ficha técnica?',
                                text: 'Selecciona una opción',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'Si',
                                cancelButtonText: 'No',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                allowEnterKey: false,
                                stopKeydownPropagation: true,
                                reverseButtons: true,
                                returnFocus: true,
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // si el trabajo es repetitivo
                                    let formDataTRepetitivo = new FormData();
                                    formDataTRepetitivo.append('_token', CSRF_TOKEN);
                                    formDataTRepetitivo.append('_method', 'POST');
                                    formDataTRepetitivo.append('idOportunidad', $('#idOportunidad').val());
                                    formDataTRepetitivo.append('isRepetitivo', result.isConfirmed ? 1 : 0);

                                    let repetitivo = result.isConfirmed ? 1 : 0;

                                    solicitarFichaTecnicaOport(repetitivo);
                                }
                            })
                        }
                    }

                } else {
                    omtc.disabled = true;
                    motivo_perdida.disabled = true;
                    $("#numOrdenMTC").prop('required', false);
                    $("#idMotivoPerdida").prop('required', false);
                    // retencion.disabled = false;
                    $("#tipoEspera").prop('disabled', true);
                }

            });

            /*if (notaretencionVal == "" && retencionCheck == "undefinided" || notaretencionVal == null &&
                retencionCheck == "undefinided") {
                $("#notaRetencion").prop("readonly", true);
            } else {
                $("#notaRetencion").prop("readonly", true);
            }

            retencion.addEventListener('change', function() {
                activarNotaRetencion(retencion.checked);
            });*/

            // $("#tblDetalleAsignadosOport").removeClass("disable");

            /*
            function activarNotaRetencion(check) {
                if (check == true) {
                    $("#notaRetencion").prop("readonly", false);
                    $('#notaRetencion').prop('required', true);

                    if (notaretencion.value == "") {
                        notaretencion.style.borderColor = 'red';
                    } else {
                        notaretencion.style.borderColor = 'green';
                    }

                } else {
                    notaretencion.value = "";
                    $("#notaRetencion").prop("readonly", true);
                    $('#notaRetencion').prop('required', false);
                    notaretencion.style.borderColor = 'silver';
                }
            }

            notaretencion.addEventListener('change', function() {
                validarRequeridosOporNoActivos();
            });

            function validarRequeridosOporNoActivos() {

                if (notaretencion.value == "") {
                    notaretencion.style.borderColor = 'red';
                } else {
                    notaretencion.style.borderColor = 'green';
                }

            }*/

            var estatus = $('#Estatus').val();
            var idMotivoPerdida = $('#idMotivoPerdida').val();

            // $('#btnTEntrega').addClass('disable');

            if ($('#idRol').val() == 2 || $('#idRol').val() == 10 || $('#idRol').val() == 1 || $('#idRol').val() ==
                8) {
                idAtendido.disabled = false;
            } else {
                idAtendido.disabled = true;
            }
            if (estatus == 1) {
                $("#idStatusPre").prop('disabled', false);
                // $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
            }

            if (estatus == 2) {
                $("#idStatusPre").prop('disabled', true);
                // $("#idStatusSolCot").prop('disabled', false);
                // $("#idStatusListoCot").prop('disabled', false);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
            }

            if (estatus == 3) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                // $("#idStatusListoCot").prop('disabled', false);
                $("#tipoEspera").prop('disabled', true);

                if (idAtendido.value != "Atendido por") {
                    // $("#idStatusCot").prop('disabled', false);
                } else {
                    // $("#idStatusCot").prop('disabled', true);
                    if (idRol.value == 2 || idRol.value == 3 || idRol.value == 10 || idRol.value == 1016) {
                        Swal.fire({
                            title: '¡No puedes cambiar de estatus!',
                            text: '¡Solo de puede asignar por jefe de cotizaciones y por administración!',
                            confirmButtonText: 'Aceptar',
                        })
                        $("#idAtendido").prop('disabled', false);
                    }
                }

                idTipoValue = $('#idTipo').val();
                if (idTipoValue == 4) {
                    fechaTopeOportInput.disabled = false;
                    fechaTopeOportInput.required = true;
                    $('#fecLimLic').removeClass('d-none');
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    $('#fecLimLic').addClass('d-none');
                }

                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);

                if ($('#cambiosMinimos').val() > 0) {
                    // $('#btnAddCambiosMenores').removeClass('disable');
                } else {
                    // $('#btnAddCambiosMenores').addClass('disable');
                }

            }
            if (estatus == 4) {
                idTipoValue = $('#idTipo').val();
                if (idTipoValue == 4) {
                    fechaTopeOportInput.disabled = false;
                    fechaTopeOportInput.required = true;
                    $('#fecLimLic').removeClass('d-none');
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    $('#fecLimLic').addClass('d-none');
                }
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                // $("#idStatusCot").prop('disabled', false);
                // $("#idStatusRev").prop('disabled', false);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                if ($('#cambiosMinimos').val() > 0) {
                    // $('#btnAddCambiosMenores').removeClass('disable');
                } else {
                    // $('#btnAddCambiosMenores').addClass('disable');
                }
            }
            if (estatus == 13) {
                idTipoValue = $('#idTipo').val();
                if (idTipoValue == 4) {
                    fechaTopeOportInput.disabled = false;
                    fechaTopeOportInput.required = true;
                    $('#fecLimLic').removeClass('d-none');
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    $('#fecLimLic').addClass('d-none');
                }
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                // $("#idStatusRev").prop('disabled', false);
                // $("#idStatusVoBo").prop('disabled', false);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                $("#tipoEspera").prop('disabled', false);
                // $("#tipoEspera").val('GUSTAVO CASAS');
                if ($('#cambiosMinimos').val() > 0) {
                    // $('#btnAddCambiosMenores').removeClass('disable');
                } else {
                    // $('#btnAddCambiosMenores').addClass('disable');
                }

                // Bloquear el boton de guardado
                $("#oportBtnSave").prop('disabled', true);
                let idUser = $('#idUsuario').val();

                if ($('#idRol').val() == 1 || $('#idRol').val() == 8 || $('#idRol').val() == 10) {
                    $("#oportBtnSave").prop('disabled', false);
                    $("#idStatusRev").prop('disabled', false);
                    $("#tipoEspera").prop('disabled', false);
                    $("#idAgente").prop('disabled', false);
                    // $("#btnAddCambiosMenores").prop('disabled', false);
                    $('#idCustomer_').prop('disabled', false);
                } else {
                    $("#oportBtnSave").prop('disabled', true);
                    $("#idStatusRev").prop('disabled', true);
                    $("#tipoEspera").prop('disabled', true);
                    $("#idAgente").prop('disabled', true);
                    // $("#btnAddCambiosMenores").prop('disabled', true);
                    $('#idCustomer_').prop('disabled', true);
                }
            }
            if (estatus == 5) {
                idTipoValue = $('#idTipo').val();
                $('#lblAutorizacion').removeClass('d-none');
                if (idTipoValue == 4) {
                    fechaTopeOportInput.disabled = false;
                    fechaTopeOportInput.required = true;
                    $('#fecLimLic').removeClass('d-none');
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    $('#fecLimLic').addClass('d-none');
                }
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusRev").prop('disabled', false);
                // $("#idStatusVoBo").prop('disabled', false);
                $("#idStatusListoPre").prop('disabled', false);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                $("#tipoEspera").prop('disabled', false);
                // $("#tipoEspera").val('GUSTAVO CASAS');
                if ($('#cambiosMinimos').val() > 0) {
                    // $('#btnAddCambiosMenores').removeClass('disable');
                } else {
                    // $('#btnAddCambiosMenores').addClass('disable');
                }
            }
            // if (estatus == 6) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', false);
            //     $("#idStatusListoPre").prop('disabled', false);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            if (estatus == 7) {
                idTipoValue = $('#idTipo').val();
                if (idTipoValue == 4) {
                    fechaTopeOportInput.disabled = false;
                    fechaTopeOportInput.required = true;
                    $('#fecLimLic').removeClass('d-none');
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    $('#fecLimLic').addClass('d-none');
                }
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);

                if (idRol.value == 1 || idRol.value == 3 || idRol.value == 2 || idRol.value == 10 || idRol.value == 8) {
                    $("#idStatusCot").prop('disabled', false);
                } else {
                    $("#idStatusCot").prop('disabled', true);
                }
                    
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                // $("#idStatusListoPre").prop('disabled', false);
                // $("#idStatusPreTerm").prop('disabled', false);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);

                $('#btnAddCambiosMenores').removeClass('disable');
                $("#idStatusPreTerm").prop('disabled', false);
                
                /*if ($('#cambiosMinimos').val() > 0 || $('#flagCambioPrecios').val() == 1 || $('#flagCambioEspecificaciones').val() == 1 || $('#flagCambioTexto').val() == 1) {
                    $('#btnAddCambiosMenores').removeClass('disable');
                    $("#idStatusPreTerm").prop('disabled', false);
                } else {
                    $('#btnAddCambiosMenores').addClass('disable');
                    $("#idStatusPreTerm").prop('disabled', false);
                }*/

            }
            if (estatus == 8) {
                idTipoValue = $('#idTipo').val();
                if (idTipoValue == 4) {
                    fechaTopeOportInput.disabled = false;
                    fechaTopeOportInput.required = true;
                    $('#fecLimLic').removeClass('d-none');
                } else {
                    fechaTopeOportInput.disabled = true;
                    fechaTopeOportInput.required = false;
                    $('#fecLimLic').addClass('d-none');
                }
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', false);
                // $("#idStatusGanado").prop('disabled', false);
                // $("#idStatusPerdido").prop('disabled', false);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                // if (idRol == 1023) {
                //     $('#btnAddPresupuesto').removeClass('disable');
                // }
                // $("#btnAddPresupuesto").removeClass('disable');
                $('#btnAddCambiosMenores').removeClass('disable');
                if ($('#idUsuario').val() != 13582) {
                    $("#btnAddPresupuesto").removeClass('disable');
                    $('#btnAddCambiosMenores').removeClass('disable');
                }
                $("#tipoEspera").prop('disabled', true);


                valor_cambios = $('#cambiosRealizados').val();
                valor_cambios_edit = $('#cambiosRealizadosEdit').val();

                // console.log('Cambios ' + valor_cambios);
                // console.log('Cambios Edit ' + valor_cambios_edit);

                if ($("#Valor").val() == '0.00') {
                    $("#Valor").prop('required', true);
                    $("#oportBtnSave").prop('disabled', true);
                    if (cancelada == 0) {
                        /*Swal.fire({
                            title: '¡Presupuesto Terminado!',
                            text: '¡Se tiene que capturar el Valor del Presupuesto para cambiarlo a Ganado!',
                            confirmButtonText: 'Aceptar',
                        }).then((result) => {});*/
                    }
                } else {
                    $("#Valor").prop('required', false);
                    $("#oportBtnSave").prop('disabled', false);
                }
            }

            if (estatus == 9) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                // $("#idStatusListoPre").prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', true);
                // $("#idStatusGanado").prop('disabled', false);
                // $("#idStatusPerdido").prop('disabled', true);
                // $("#idStatusSolOP").prop('disabled', false);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $('#lblMotivoPerdida').addClass('d-none');
            }
            
            if (estatus == 14) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPPConcluida").prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', true);
                // $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $('#lblMotivoPerdida').addClass('d-none');
            }
            
            if ($('#solicitud_preprensa').val() == 1 && estatus > 8) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', false);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                // $("#idStatusPPConcluida").prop('disabled', false);
                // $("#idStatusPreTerm").prop('disabled', true);
                // $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                // $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $('#lblMotivoPerdida').addClass('d-none');
            }

            if (estatus == 15) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                // $("#idStatusPPConcluida").prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', true);
                // $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                // $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $('#lblMotivoPerdida').addClass('d-none');
            }
            
            if (estatus == 16) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPPConcluida").prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', true);
                // $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                // $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $('#lblMotivoPerdida').addClass('d-none');
            }
            
            if (estatus == 17) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPPConcluida").prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', true);
                // $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                // $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $('#lblMotivoPerdida').addClass('d-none');
            }
            
            // if (estatus == 10 && idMotivoPerdida == 5) {
            if (estatus == 10) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                // $("#idStatusListoPre").prop('disabled', true);
                // $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', false);
                // $("#idStatusPerdido").prop('disabled', false);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                $("#btnAddPresupuesto").removeClass('disable');
                // $('#btnAddPresupuesto').removeClass('disable');
                $("#Premio").prop('disabled', true);
                $('#lblMotivoPerdida').removeClass('d-none');
            }
            if (estatus == 11) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusRevTec").prop('disabled', true);
                $('#numOrdenMTC').prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                
                if (idRol.value == 1 || idRol.value == 3 || idRol.value == 2 || idRol.value == 10 || idRol.value == 8) {
                    $("#idStatusPreTerm").prop('disabled', false);
                } else {
                    $("#idStatusPreTerm").prop('disabled', true);
                }
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                // $("#idStatusSolOP").prop('disabled', false);
                // $("#idStatusOPCon").prop('disabled', false);
                // $("#btnCancelar").addClass("disable");
                // $("#Retencion").prop('disabled', true);
                $("#tipoEspera").prop('disabled', true);
                // $('#btnAddPresupuesto').addClass('disable');
            }       

            if ((estatus == 10 && idMotivoPerdida != 5) || estatus == 12 || estatus == 15 || cancelada == '1') {
                $("#oportBtnSave").prop('disabled', true);
                $("#btnCancelar").addClass("disable");
                if ((idRol.value == 3 && $('#idUsuario').val() != 13582) || idRol.value == 1023 || idRol.value == 8 || idRol.value == 1 || idRol
                    .value == 1016) {
                    // $("#btnAddPresupuesto").removeClass("disable");
                } else {
                    // $("#btnAddPresupuesto").addClass("disable");
                }
                // $("#btnAddCambiosMenores").addClass("disable");

                var nodes = document.getElementById("tabGeneral").getElementsByTagName('*');
                for (var i = 0; i < nodes.length; i++) {
                    nodes[i].disabled = true;
                }

                // $('#btnSolFecEntrega').addClass('disable');

                $("#tblOporRetenidas").addClass("disable");
                // $("#tblDetalleAsignadosOport").addClass("disable");
                $("#div-btnrelacionar").addClass("disable");

                $("#div-btnrelacionarEstr").addClass("disable");

                /*var nodesDocs = document.getElementById("tblDocs").getElementsByTagName('*');
                nodesDocsIcon = document.getElementsByClassName('icondocsup');
                for (var j = 0; j < nodesDocsIcon.length; j++) {
                    nodesDocsIcon[j].onclick("");
                }*/

                // var tabEntregasOport = document.getElementById('tabEntregasOport');

                var nodesEntrega = document.getElementById("entregas-oport").getElementsByTagName('*');
                for (var k = 0; k < nodesEntrega.length; k++) {
                    nodesEntrega[k].disabled = false;
                }


                $('#idSucursal').prop('disabled', false);

                var nodesIdSucursal = document.getElementById("idSucursal").getElementsByTagName('option');
                for (var i = 1; i < 1; i++) {
                    nodesIdSucursal[i].disabled = true;
                }

                // console.log(archivoAutorizado.checked);
                $('#totalCantidadOP').prop('disabled', true);

                if (archivoAutorizado.checked == true) {
                    if (idRol.value == 1013 || idRol.value == 8 || idRol.value == 1) {
                        $('#archivoAutorizado').prop('disabled', false);
                        $('#archivoAutorizado').addClass('pointer');
                        $('#archivoAutorizadoLabel').addClass('pointer');
                        // $('#btnTEntrega').removeClass('disable');
                    } else {
                        $('#archivoAutorizadoLabel').prop('disabled', true);
                        $('#archivoAutorizadoLabel').removeClass('pointer');
                        // $('#btnTEntrega').addClass('disable');
                    }
                } else {
                    $('#archivoAutorizado').prop('disabled', false);
                    // $('#archivoAutorizadoLabel').prop('onclick', autorizarArchivo(idOportunidad.value));
                    $('#archivoAutorizadoLabel').addClass('pointer');
                    // $('#btnTEntrega').addClass('disable');
                }
                // $('#fecEntrega').prop('disabled', false);
                // $('#fecEntregaInput').prop('disabled', false);
                $("#idStatusGanado").prop('disabled', false);
                $("#Premio").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);

            }

            if (estatus >= 11) {
                $('#lblOmts').removeClass('d-none');
            }

            if (estatus == 12) {
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $('#lblOmts').removeClass('d-none');
            }

            if (estatus >= 5) {
                $('#lblAutorizacion').removeClass('d-none');
                $('#fecLimLic').removeClass('d-none');
            }

            if (cancelada == '1') {
                $("#oportBtnSave").prop('disabled', true);
                $("#btnCancelar").addClass("disable");
                // $("#btnAddPresupuesto").addClass("disable");

                var nodes = document.getElementById("tabGeneral").getElementsByTagName('*');
                for (var i = 0; i < nodes.length; i++) {
                    nodes[i].disabled = true;
                }

                $("#tblOporRetenidas").addClass("disable");
                $("#tblOporRechazos").addClass("disable");
                $("#div-btnrelacionar").addClass("disable");
                $("#div-btnrelacionarEstr").addClass("disable");

                /*var nodesDocs = document.getElementById("tblDocs").getElementsByTagName('*');
                nodesDocsIcon = document.getElementsByClassName('icondocsup');
                for (var j = 0; j < nodesDocsIcon.length; j++) {
                    nodesDocsIcon[j].onclick("");
                }*/

                var nodesEntrega = document.getElementById("entregas-oport").getElementsByTagName('*');
                for (var k = 0; k < nodesEntrega.length; k++) {
                    nodesEntrega[k].disabled = false;
                }

                $('#idSucursal').prop('disabled', false);

                var nodesIdSucursal = document.getElementById("idSucursal").getElementsByTagName('option');
                for (var i = 1; i < 1; i++) {
                    nodesIdSucursal[i].disabled = true;
                }

                $('#totalCantidadOP').prop('disabled', true);

                if (archivoAutorizado.checked == true) {
                    if (idRol.value == 1013 || idRol.value == 8 || idRol.value == 1) {
                        $('#archivoAutorizado').prop('disabled', false);
                        $('#archivoAutorizado').addClass('pointer');
                        $('#archivoAutorizadoLabel').addClass('pointer');
                        // $('#btnTEntrega').removeClass('disable');
                    } else {
                        $('#archivoAutorizadoLabel').prop('disabled', true);
                        $('#archivoAutorizadoLabel').removeClass('pointer');
                        // $('#btnTEntrega').addClass('disable');
                    }
                } else {
                    $('#archivoAutorizado').prop('disabled', false);
                    // $('#archivoAutorizadoLabel').prop('onclick', autorizarArchivo(idOportunidad.value));
                    $('#archivoAutorizadoLabel').addClass('pointer');
                    // $('#btnTEntrega').addClass('disable');
                }
                // $('#fecEntrega').prop('disabled', false);
                // $('#fecEntregaInput').prop('disabled', false);
                // $("#idStatusGanado").prop('disabled', false);

            }

            if (dateFormat($('#FechaAutoriza').val(), 'yyyy-mm-dd') < dateFormat(dateToday, 'yyyy-mm-dd')) {
                // console.log(dateFormat($('#FechaAutoriza').val(), 'yyyy-mm-dd'));
                // console.log(dateFormat(dateToday, 'yyyy-mm-dd'));
                // $("#divBtnSolFecEntrega").removeClass("disable");
                // $('#archivoAutorizado').prop('disabled', true);
                // } else {
                //     $("#divBtnSolFecEntrega").addClass("disable");
            }


            if (cancelada == 1) {
                isCancelled.style.display = 'block';
                // $("#btnSolFecEntrega").addClass("disable");
                $("#btnTEntrega").addClass("disable");
            } else {
                isCancelled.style.display = 'none';
            }
            
            /*
            if (notaretencion.value != "") {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                // $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }*/

            
            // Modificación para cambio de estatus
            // if ( estatus == 12) { $("#idStatusPreTerm").prop('disabled', false); }

            // console.log(doc);
            if (doc == '?doc') {
                mostrarDocumentosOport();
            } else {
                mostrarGeneralOport();
            }

            if (solicitud_op == 1) {
                $('#idStatusOPCon').prop('disabled', false);
            }

            $('#idStatusOPCon').change(function() {
                $('#idStatusSolOP').prop('disabled', false);
            });

            $('#idStatusSolOP').change(function() {
                $(this).prop('disabled', true);
            });
        
            // Validar si en la url se encuentra el parametro ?fromElemento=1 para mostrar la pestaña de elementos presupuestados
            if (window.location.search.includes('fromElemento=1')) {
                $('#elementos-oport-tab').tab('show');
                $('#elementos-oport-tab').trigger('click');

                // Eliminar el parametro de la url para evitar que al actualizar la pagina se mantenga en la pestaña de elementos presupuestados
                const url = new URL(window.location);
                url.searchParams.delete('fromElemento');
                window.history.replaceState({}, document.title, url.toString());
            }

            function bloquearRadiosPorUsuario() {
                let estatus = $('#Estatus').val();
                let idUsuario = document.getElementById('idUsuario');

                if (estatus <= 8) {
                    if (idUsuario && String(idUsuario.value) == '13582') {

                        var nodes_general = document
                            .querySelectorAll(".form-group *");
                        for (var i = 0; i < nodes_general.length; i++) {
                            nodes_general[i].disabled = true;
                        }
                    }
                }
            }
            bloquearRadiosPorUsuario();

        });

        $(function() {
            /*$("#idEquipo").change(function() {
                var agente = $("#idAgente");
                var equipos = $(this);

                if ($(this).val() != "") {
                    $.ajax({
                        data: {
                            idEquipo: equipos.val()
                        },
                        url: "{{ route('getEquipos') }}",
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            equipos.prop("disabled", true);
                        },
                        success: function(r) {
                            equipos.prop("disabled", false);

                            agente.find("option").remove();

                            agente.append(
                                '<option value="Requerido" selected disabled>Requerido</option>'
                            );

                            $(r).each(function(i, v) {
                                // indice, valor
                                agente.append(
                                    '<option value="' +
                                    v.Id_Usuario +
                                    '">' +
                                    v.Nombre +
                                    "</option>"
                                );
                            });

                            agente.prop("disabled", false);

                        },
                        error: function(r) {
                            Swal.fire("Ocurrio un error en el servidor ..")
                            equipos.prop("disabled", false);
                        },
                    });
                } else {
                    agente.find("option").remove();
                    agente.prop("disabled", true);
                }
            });*/
        });

        $("#numOrdenMTC").change(function(e) {
            e.preventDefault();
            $("#oportBtnSave").prop('disabled', true);
            numOMTC = $("#numOrdenMTC").val();
            $.ajax({
                url: serve + "obtenerOMTCs",
                type: "GET",
                dataType: "json",
                data: {
                    _token: CSRF_TOKEN,
                    search: numOMTC,
                },
                success: function(data) {
                    // Swal.fire("¡Orden existente!");
                    $("#oportBtnSave").prop('disabled', false);
                    $("#numOrdenMTC").prop('required', false);
                },
                error: function() {
                    $("#numOrdenMTC").val('');
                    $("#numOrdenMTC").prop('required', true);
                    $("#numOrdenMTC").focus();
                    Swal.fire("¡La orden capturada no existe!");
                    $("#oportBtnSave").prop('disabled', true);
                }
            });
        });

        ////////// Boton de presupuesto
        /*$("#numPresupuesto").change(function(e) {
            e.preventDefault();
            $("#btnAddPresupuestoModal").prop('disabled', true);
            $("#btnAddCambiosMenoresModal").prop('disabled', true);
            numPresupuesto = $("#numPresupuesto").val();
            $.ajax({
                url: serve + "obtenerNumPresupuestos",
                type: "GET",
                dataType: "json",
                data: {
                    _token: CSRF_TOKEN,
                    search: numPresupuesto,
                },
                success: function(data) {
                    // Swal.fire("¡Orden existente!");
                    $("#btnAddPresupuestoModal").prop('disabled', false);
                    $("#btnAddCambiosMenoresModal").prop('disabled', false);
                    $("#numPresupuesto").prop('required', false);
                    $("#importe").val(data.Importe);
                },
                error: function() {
                    $("#numPresupuesto").val('');
                    // $("#Valor").val(valor_actual);
                    $("#numPresupuesto").prop('required', true);
                    $("#numPresupuesto").focus();
                    Swal.fire("¡El Presupuesto capturado no existe!");
                    $("#btnAddPresupuestoModal").prop('disabled', true);
                    $("#btnAddCambiosMenoresModal").prop('disabled', true);
                    $("#importe").val('');
                }
            });
        });*/

        function mostrarGeneralOport() {
            idrol = document.getElementById("idRol");
            permisoEstructural = document.getElementById('permisoEstructural');
            $("#general-oport-tab").addClass("active");
            $("#general-oport").addClass("show");

            $("#elementos-oport-tab").removeClass("active");
            $("#elementos-oport").removeClass("show");

            $("#estructural-oport-tab").removeClass("active");
            $("#estructural-oport").removeClass("show");

            $("#documentos-oport-tab").removeClass("active");
            $("#documentos-oport").removeClass("show");

            $("#entregas-oport-tab").removeClass("active");
            $("#entregas-oport").removeClass("show");
            // document.getElementById("btnClonarOportunidad").style.display = "block";

            if (permisoEstructural.value == 'SinAcceso') {
                document.getElementById("estructural-oport-tab").style.display = "none";
                document.getElementById("estructural-oport-tab-li").style.display = "none";
            }

            if (idrol.value == 1013) {
                if ($('#idUsuario').val() == '12449') {
                    document.getElementById("documentos-oport-tab").style.display = "block";
                } else {
                    document.getElementById("documentos-oport-tab").style.display = "none";
                }

                document.getElementById("entregas-oport-tab").style.display = "none";

                var nodes_general = document
                    .getElementById("tabGeneral")
                    .getElementsByTagName("*");
                for (var i = 0; i < nodes_general.length; i++) {
                    nodes_general[i].disabled = true;
                }

                $("#div-btnrelacionar").addClass("disable");

                $("#sOportBtnCancelar").addClass("disable");
                $("#sOportBtnSave").addClass("disable");
                $("#btnCancelar").addClass("disable");

                document.getElementById("sOportBtnTEntrega").style.display = "block";

                $("#sOportBtnBack").removeClass("col-12");

                $("#sOportBtnSave").addClass("col-3");
                $("#sOportBtnBack").addClass("col-4");
                $("#sOportBtnCancelar").addClass("col-3");

                if (archivoAutorizado.checked == true) {
                    if (idRol.value == 1013 || idRol.value == 8 || idRol.value == 1) {
                        $('#archivoAutorizado').prop('disabled', false);
                        $('#archivoAutorizado').addClass('pointer');
                        $('#archivoAutorizadoLabel').addClass('pointer');
                    } else {
                        $('#archivoAutorizadoLabel').prop('disabled', true);
                        $('#archivoAutorizadoLabel').removeClass('pointer');
                    }
                } else {
                    $("#archivoAutorizado").prop("disabled", false);
                    $("#archivoAutorizadoLabel").addClass("pointer");
                }
            } else if (idrol.value == 1014) {
                document.getElementById("elementos-oport-tab").style.display = "none";
                document.getElementById("estructural-oport-tab").style.display = "none";
                document.getElementById("documentos-oport-tab").style.display = "none";
                document.getElementById("entregas-oport-tab").style.display = "none";
                // document.getElementById("btnClonarOportunidad").style.display = "none";

                var nodes_general = document
                    .getElementById("tabGeneral")
                    .getElementsByTagName("*");
                for (var i = 0; i < nodes_general.length; i++) {
                    nodes_general[i].disabled = true;
                }

                $("#div-btnrelacionar").addClass("disable");

                $("#btnCancelar").attr("onclick", "");
                $("#btnCancelar").off("click");

                $("#btnSolFecEntrega").attr("onclick", "");
                $("#btnSolFecEntrega").off("click");
                $("#btnSolFecEntrega").addClass("disable");

                $("#oportBtnSave").prop("disabled", true);
                $("#sOportBtnCancelar").addClass("disable");
                $("#btnCancelar").addClass("disable");
                // $("#sOportBtnSave").addClass("disable");

                document.getElementById("sOportBtnTEntrega").style.display = "none";
                document.getElementById("sOportBtnSave").style.display = "none";
                document.getElementById("sOportBtnCancelar").style.display = "none";

                $("#sOportBtnBack").removeClass("col-12");

                $("#sOportBtnSave").addClass("col-4");
                $("#sOportBtnBack").addClass("col-4");
                $("#sOportBtnCancelar").addClass("col-4");
            } else if (idrol.value == 8 || idrol.value == 1) {
                document.getElementById("sOportBtnTEntrega").style.display = "block";

                $("#sOportBtnBack").removeClass("col-12");

                $("#sOportBtnSave").addClass("col-3");
                $("#sOportBtnBack").addClass("col-4");
                $("#sOportBtnCancelar").addClass("col-3");

                if (permisoEstructural.value == 'SinAcceso') {
                    document.getElementById("estructural-oport-tab").style.display = "none";
                    document.getElementById("estructural-oport-tab-li").style.display = "none";
                }

            } else {
                document.getElementById("elementos-oport-tab").style.display = "block";

                if (permisoEstructural.value == 'SinAcceso') {
                    document.getElementById("estructural-oport-tab").style.display = "none";
                    document.getElementById("estructural-oport-tab-li").style.display = "none";
                }

                document.getElementById("documentos-oport-tab").style.display = "block";
                document.getElementById("entregas-oport-tab").style.display = "block";

                $("#sOportBtnCancelar").removeClass("disable");
                $("#sOportBtnSave").removeClass("disable");

                document.getElementById("sOportBtnTEntrega").style.display = "none";

                $("#sOportBtnBack").removeClass("col-12");

                $("#sOportBtnSave").addClass("col-4");
                $("#sOportBtnBack").addClass("col-4");
                $("#sOportBtnCancelar").addClass("col-4");
            }

            document.getElementById("general-oport").style.display = "block";
            document.getElementById("elementos-oport").style.display = "none";
            document.getElementById("estructural-oport").style.display = "none";
            if ($('#idUsuario').val() == '12449') {
                document.getElementById("documentos-oport").style.display = "block";
            } else {
                document.getElementById("documentos-oport").style.display = "none";
            }
            document.getElementById("entregas-oport").style.display = "none";
            document.getElementById("sOportBtnSave").style.display = "block";
            document.getElementById("sOportBtnCancelar").style.display = "block";

            tblElementosPresupuestados.draw();
        }

        function asignarPremio() {
            premio_check = document.getElementById('Premio');
            if (premio_check.checked == false) {
                $('#divPremio').removeClass('premio')
                $('#divPremio').addClass('sin_premio')
            } else {
                $('#divPremio').addClass('premio')
                $('#divPremio').removeClass('sin_premio')
            }
        }

        function getTipoCambio(tipoCambio) {
            switch (tipoCambio) {
                case 1:
                    return "Especificaciones";
                case 2:
                    return "Precios";
                case 3:
                    return "Mínimo";
                case 4:
                    return "Texto";
                default:
                    return "Desconocido";
            }
        }

        /**
        * Función para obtener y pintar el icono del tipo de tabla de comisiones
        * 
        * @param idCliente
        * @return void
        */
        function getTypeTablaCliente(idCliente)
        {
            formActionGetTbl = new FormData();
            formActionGetTbl.append("_token", CSRF_TOKEN);
            formActionGetTbl.append("_method", "POST");
            formActionGetTbl.append("idCliente", idCliente);

            // Función para actualizar la cotización en la base de candy
            $.ajax({
                url: serve + "getUserTblCom",
                async: true,
                type: "POST",
                data: formActionGetTbl,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#tipoTablaComision').html('');
                    if (data.tbl) {
                        if (data.tbl === "T2") {
                            $('#tipoTablaComision').append(
                                '<span class="badge badge-success pl-2 pr-2 pt-1 pb-1" title="TABLA DE COMISIÓN 2"><strong>T2</strong></span>'
                            );
                        } else if (data.tbl === "T1") {
                            $('#tipoTablaComision').append(
                                '<span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="TABLA DE COMISIÓN 1"><strong>T1</strong></span>'
                            );
                        }
                    }
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error, al realizar la acción" });
                }
            })
        }

        /**
        * Función para solicitar la ficha técnica de la oportunidad
        * 
        * @param {*} parametro
        * @return void
        */
        function solicitarFichaTecnicaOport(parametro) {
            // Creamos el formulario que se enviara para realizar la solicitud de la ficha técnica
            formDataSolFt = new FormData();
            formDataSolFt.append("_token", CSRF_TOKEN);
            formDataSolFt.append("_method", "POST");
            formDataSolFt.append("idOportunidad", $('#idOportunidad').val());
            formDataSolFt.append("nomOportunidad", $('#nomOportunidad').val());
            formDataSolFt.append("parametro", parametro);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "solicitarFt",
                async: true,
                type: "POST",
                data: formDataSolFt,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Pasamos el valor del campo de solicitud de ficha tecnica
                        $('#hasFichaT').val(1)
                        $("#sFicTec").prop('disabled', true);

                        Msg.fire('Se ha solicitado la ficha técnica');
                    }
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error, al realizar la acción" });
                }
            })
        }

        if ($('#idRol').val() == 4 || $('#idRol').val() == 9) {
            $('#idCustomer_').prop('disabled', true);
            $('#idAtendido').prop('disabled', true);
        }

        function rechazarOportunidadAdmin(idOportunidad) {
            let idOpt = $('#idOportunidad').val();
            // Clona las opciones del select oculto
            let selectMotivos = document.getElementById('motivoRechazoOport');
            let optionsHtml = '';
            for (let i = 0; i < selectMotivos.options.length; i++) {
                let opt = selectMotivos.options[i];
                optionsHtml += `<option value="${opt.value}" ${opt.disabled ? 'disabled' : ''} ${opt.selected ? 'selected' : ''}>${opt.text}</option>`;
            }

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás deshacer esta acción!",
                html: `
                <div class="swal2-content">
                    <label for="swalMotivoRechazoOport" style="color: white;">Motivo del rechazo:</label>
                    <select id="swalMotivoRechazoOport" class="swal2-select" style="width:100%;margin-bottom:10px;">
                        ${optionsHtml}
                    </select>
                    <textarea id="swalMotivoRechazoOportTxt" class="swal2-textarea placeholder-text-sm" placeholder="Describe el motivo de rechazo de oportunidad, (este texto sera el que se mandara por correo)"></textarea>
                </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, rechazar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'swal2-small-modal'
                },
                preConfirm: () => {
                    const motivoId = document.getElementById('swalMotivoRechazoOport').value;
                    const motivoTxt = document.getElementById('swalMotivoRechazoOportTxt').value.trim();
                    if (!motivoId) {
                        Swal.showValidationMessage('¡Debes seleccionar un motivo!');
                        return false;
                    }
                    if (!motivoTxt) {
                        Swal.showValidationMessage('¡Necesitas escribir el motivo adicional!');
                        return false;
                    }
                    return { motivoId, motivoTxt };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let formDataRechazo = new FormData();
                    formDataRechazo.append("_token", CSRF_TOKEN);
                    formDataRechazo.append("_method", "POST");
                    formDataRechazo.append("idOportunidad", idOpt);
                    formDataRechazo.append("motivo", JSON.stringify(result.value));
                    // Ejecutamos la acción de ajax
                    $.ajax({
                        url: serve + "rechazarOportunidad",
                        type: "POST",
                        data: formDataRechazo,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if (data.response) {
                                const Msg = Swal.mixin({
                                    toast: true,
                                    position: "center",
                                    icon: "success",
                                    showConfirmButton: false,
                                    timer: 3000,
                                });

                                Msg.fire({
                                    title: "La oportunidad ha sido rechazada.",
                                });

                                window.location.reload();
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'No se pudo rechazar la oportunidad.',
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'No se pudo rechazar la oportunidad.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        /*setTimeout(function () {
            if ($('#tieneTripasConFolioPreprensa').val() == 1) {
                if (!$('#idStatusPPConcluida').is(':checked')) {
                    // Habilitamos el radio para poder finalizar la preprensa
                    $('#idStatusPPConcluida').prop('disabled', false);
                }
            } else {
                $('#idStatusPPConcluida').prop('disabled', true);
            }
        }, 1000);*/

        $('#idStatusPPConcluida').change(function () {
            // Habilitamos el botón de guardar
            $('#oportBtnSave').prop('disabled', false);
        });

        /**
        * Función para validar y mostrar el botón de cargar Excel EP según el estatus
        * 
        * @return void
        */
        function validarVisibilidadBtnExcelEP() {
            let estatus = $('#Estatus').val();
            estatus = parseInt(estatus);
            
            let finalizoCambios = $('#finalizoCambios').val();
            let solicitoCambios = $('#solicitoCambios').val();

            if ((estatus === 1 || estatus === 2 || estatus === 7 || estatus === 8) && ((finalizoCambios == 1 && solicitoCambios == 0) || (finalizoCambios == 0 && solicitoCambios == 0))) {
                $('#btnNuevoPresupuestoExcel').show();
            } else {
                $('#btnNuevoPresupuestoExcel').hide();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            let elementA = document.getElementById('agregarProyecto');
            let elementB = document.getElementById('div-btnrelacionarEstr');
            let user = $('#idUsuario').val();
            let estatus = $('#Estatus').val();
            let idMotivoPerdida = $('#idMotivoPerdida').val();

            setTimeout(function () {
                // por usuario
                // if (user == 12503 || user == 12456) {

                // Por oportunidad
                /*if ($('#oportFolio').val() == 32269) {
                    if (elementA && elementB) {
                        elementA.disabled = false;
                        elementB.classList.remove('disable');
                    }
                }*/

                if ((estatus == 10 && idMotivoPerdida == 5) && (idRol.value == 4 || idRol.value == 9 || idRol.value == 1 || idRol.value == 8)) {
                    $('#idStatusPreTerm').prop('disabled', false);
                }
            }, 1000);
        });

        // Validar visibilidad del botón de cargar Excel EP según el estatus
        validarVisibilidadBtnExcelEP();
    </script>
@endsection
