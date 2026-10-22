<div class="modal fullscreen-modal fade" id="modal-oportunidades-edit" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel">
    @include('partials.loadingModal')
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">


                <div id="formOporEditModal" class="col-12 mt-2">
                    <form method="post" role="form">
                        {{ csrf_field() }} {{ method_field('PUT') }}
                        <input type="hidden" name="idUsuario" id="idUsuario">
                        <input type="hidden" name="Estatus" id="Estatus">
                        <input type="hidden" name="idOportunidad" id="idOportunidadEdit">
                        <input type="hidden" name="Cancelada" id="Cancelada">
                        <input type="hidden" id="idClienteEnt" name="idClienteEnt">
                        <input type="hidden" id="idCliente">
                        <input type="hidden" id="FechaAutoriza" name="FechaAutoriza">

                        <input type="hidden" id="idRol">
                        <div class="card-body fullscreen-card">
                            <div class="rounded bg-gray">
                                <div class="row mb-2">
                                    <div class="col-12 text-center">
                                        <h6 class="text-bold pt-1 pl-1">Editar Oportunidad</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="input-group input-group-sm">
                                    <h6 id="numFolioEdit" class="text-right text-bold text-red pr-1 pt-2"></h6>
                                    <label class="mt-2" for="nomOportunidad">Oportunidad &nbsp;</label>
                                    <input type="text" class="form-control col-md-6 col-lg-7 col-xl-8 text-uppercase"
                                        id="nomOportunidad" name="nomOportunidad" autocomplete="off" readonly>
                                    <div class="input-group-append col-md-6 col-lg-5 col-xl-4">
                                        <span class="col-2 center" id="sOportBtnTEntrega" title="Tiempo de Entrega"
                                            style="display: none"><a id="btnTEntrega"
                                                onclick="openTiempoEntregaModal(), loaderModalON()"
                                                class="btn btn-info btn-sm btn-block"><i
                                                    class="fas fa-calendar-alt"></i></a></span>
                                        <span class="col-3 center" id="sOportBtnCancelar"><a id="btnCancelar"
                                                href="#" onclick="CancelarOportunidad(), loaderON()"
                                                class="btn btn-danger btn-sm btn-block">Cancelar</a></span>
                                        <span class="col-3" id="sOportBtnSave"><button id="oportBtnSave" type="submit"
                                                class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                                        <span class="col-4 center" id="sOportBtnBack"><a
                                                class="btn btn-secondary btn-sm btn-block"
                                                data-dismiss="modal">Regresar</a></span>
                                    </div>

                                </div>
                            </div>
                            <div id="tabGeneral">
                                <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                                    <li class="nav-item w-25">
                                        <a class="nav-link active" id="general-oport-tab" data-toggle="pill"
                                            style="cursor: pointer" onclick="mostrarGeneralOport()" role="tab"
                                            aria-controls="general-oport-tab" aria-selected="true">General</a>
                                    </li>
                                    <li class="nav-item w-25">
                                        <a class="nav-link" id="elementos-oport-tab" data-toggle="pill"
                                            style="cursor: pointer" onclick="mostrarElementosOport()" role="tab"
                                            aria-controls="elementos-oport-tab" aria-selected="false">Elementos
                                            Presupuestados</a>
                                    </li>
                                    <li class="nav-item w-25">
                                        <a class="nav-link" id="documentos-oport-tab"
                                            onclick="mostrarDocumentosOport()" data-toggle="pill" role="tab"
                                            aria-controls="documentos-oport-tab" aria-selected="false"
                                            style="cursor: pointer">Documentos</a>
                                    </li>
                                    <li id="tabEntregasOport" class="nav-item w-25">
                                        <a class="nav-link" id="entregas-oport-tab" onclick="mostrarEntregasOport()"
                                            data-toggle="pill" role="tab" aria-controls="entregas-oport-tab"
                                            aria-selected="false" style="cursor: pointer">Entregas</a>
                                    </li>
                                    <a id="btnClonarOportunidad" onclick="clonarOportunidad(), loaderON()"
                                        type="button" class="btn-flotante button btn btn-primary-clone"
                                        title="Clonar Oportunidad">
                                        {{-- <i class="fas fas fa-copy"></i> --}}
                                        <i><img src="{{ asset('img/clon.png') }}" width="28"></i>
                                    </a>
                                </ul>
                                <div id="scrollbarTabsModalRound" class="tab-content">
                                    <div class="tab-pane fade show active" id="general-oport" role="tabpanel"
                                        aria-labelledby="general-oport-tab">
                                        <div id="isCancelled" class="canceling-cont" style="display: none">
                                            <div class="canceling">CANCELADA</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-7 col-sm-12 mt-2">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="idClienteEdit">Cliente</label>
                                                            <input id="idClienteEdit" name="idCliente"
                                                                class="form-control" style="width: 100%" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="idTipo">Tipo de Oportunidad</label>
                                                            <select id="idTipo" name="idTipo"
                                                                class="form-control" style="width: 100%;">
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <label for="idEstatus">Estatus</label>

                                                <div class="row" id="listEstatus">
                                                    <div class="col-3" style="margin-bottom: -20px">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusPre" name="idStatus" value="1"
                                                                    disabled>
                                                                <label for="idStatusPre"
                                                                    class="custom-control-label">Pre
                                                                    registro</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusSolCot" name="idStatus"
                                                                    value="2" disabled>
                                                                <label for="idStatusSolCot"
                                                                    class="custom-control-label">Solicitud de
                                                                    cotización</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusListoCot" name="idStatus"
                                                                    value="3" disabled>
                                                                <label for="idStatusListoCot"
                                                                    class="custom-control-label">Listo
                                                                    p/cotizar</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusCot" name="idStatus" value="4"
                                                                    disabled>
                                                                <label for="idStatusCot"
                                                                    class="custom-control-label">Cotizando</label>
                                                            </div>
                                                            <div class="col-12 text-right">
                                                                <label style="font-size: 8px"> *Customers /
                                                                    Cotizadores</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-3" style="margin-bottom: -20px">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusRev" name="idStatus" value="5"
                                                                    disabled>
                                                                <label for="idStatusRev"
                                                                    class="custom-control-label">Revisión</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusVoBo" name="idStatus" value="6"
                                                                    disabled>
                                                                <label for="idStatusVoBo"
                                                                    class="custom-control-label">Espera Vo.
                                                                    Bo.</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusListoPre" name="idStatus"
                                                                    value="7" disabled>
                                                                <label for="idStatusListoPre"
                                                                    class="custom-control-label">Listo p
                                                                    /presupuesto</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusPreTerm" name="idStatus"
                                                                    value="8" disabled>
                                                                <label for="idStatusPreTerm"
                                                                    class="custom-control-label">Presupuesto
                                                                    terminado</label>
                                                            </div>
                                                            <div id="divBtnSolFecEntrega" class="pt-2 text-left">
                                                                <a id="btnSolFecEntrega"
                                                                    onclick="solicitarFechaEntrega()"
                                                                    class="btn btn-info btn-sm rounded pointer"
                                                                    style="font-size: 10px !important"><i
                                                                        class="fas fa-calendar"></i>
                                                                    Solicitar Fecha Entrega
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-3" style="margin-bottom: -20px">
                                                        <div class="form-group border-left border-right">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusGanado" name="idStatus"
                                                                    value="9" disabled>
                                                                <label for="idStatusGanado"
                                                                    class="custom-control-label">Ganado</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusPerdido" name="idStatus"
                                                                    value="10" disabled>
                                                                <label for="idStatusPerdido"
                                                                    class="custom-control-label">Perdido</label>
                                                            </div>
                                                            <label for="idMotivoPerdida">Motivo Perdida</label>
                                                            <select id="idMotivoPerdida" name="idMotivoPerdida"
                                                                class="form-control" style="width: 100%;">
                                                            </select>
                                                            <div class="col-12 text-center">
                                                                <label style="font-size: 8px"> *Vendedores</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-3" style="margin-bottom: -20px">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusSolOP" name="idStatus" value="11"
                                                                    disabled>
                                                                <label for="idStatusSolOP"
                                                                    class="custom-control-label">Solicitud
                                                                    OP </label><label style="font-size: 8px">&nbsp;
                                                                    *Vendedores</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="idStatusOPCon" name="idStatus" value="12"
                                                                    disabled>
                                                                <label for="idStatusOPCon"
                                                                    class="custom-control-label">OP
                                                                    Concluida </label><label
                                                                    style="font-size: 8px">&nbsp;
                                                                    *Cotizadores</label>
                                                            </div>
                                                            <label for="numOrdenMTC">Orden Metrics</label>
                                                            <div class="input-group">
                                                                <input id="numOrdenMTC" name="numOrdenMTC"
                                                                    type="text" class="form-control text-uppercase"
                                                                    placeholder="Orden Metrics" autocomplete="off">
                                                            </div>
                                                            <div class="form-group pb-2">
                                                                <div class="custom-control custom-switch">
                                                                    <input id="archivoAutorizado"
                                                                        name="archivoAutorizado" type="checkbox"
                                                                        class="custom-control-input">
                                                                    <label id="archivoAutorizadoLabel"
                                                                        class="custom-control-label pt-1"
                                                                        for="archivoAutorizado"
                                                                        style="font-size: 10px">Archivo
                                                                        Autorizado</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idExclusion">Previsión</label>
                                                            <select id="idExclusion" name="idExclusion"
                                                                class="form-control" style="width: 100%;">
                                                                {{-- @foreach ($previsiones as $prevision)
                                                                @if ($prevision->idExclusionOport == $oportunidad[0]->idExclusionOport)
                                                                <option value="{{ $prevision->idExclusionOport }}" selected>
                                                                    {{ $oportunidad[0]->descExclusionOport }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $prevision->idExclusionOport }}">
                                                                    {{ $prevision->descExclusionOport }}
                                                                </option>
                                                                @endif
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="Valor">Valor de la Oportunidad</label>
                                                            <div class="input-group">
                                                                <input id="Valor" name="Valor" type="text"
                                                                    class="form-control" style="cursor: not-allowed"
                                                                    placeholder="$0.00" disabled autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idProbabilidad">Probabilidad de Ganar</label>
                                                            <select id="idProbabilidad" name="idProbabilidad"
                                                                class="form-control" style="width: 100%;">
                                                                {{-- @foreach ($probabilidades as $probabilidad)
                                                                @if ($probabilidad->idProbabilidad == $oportunidad[0]->idProbabilidad)
                                                                <option value="{{ $probabilidad->idProbabilidad }}" selected>
                                                                    {{ $oportunidad[0]->descProbabilidad }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $probabilidad->idProbabilidad }}">
                                                                    {{ $probabilidad->descProbabilidad }}
                                                                </option>
                                                                @endif
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Equipos</label>
                                                            <select id="idEquipo" name="idEquipo"
                                                                class="form-control" style="width: 100%;">
                                                                {{-- @foreach ($equipos as $equipo)
                                                                @if ($equipo->idEquipo == $oportunidad[0]->idEquipo)
                                                                <option value="{{ $equipo->idEquipo }}" selected>
                                                                    {{ $oportunidad[0]->descEquipo }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $equipo->idEquipo }}">
                                                                    {{ $equipo->descEquipo }}
                                                                </option>
                                                                @endif
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idAgente">Asignado a</label>
                                                            <select id="idAgente" name="idAgente"
                                                                class="form-control" style="width: 100%;">
                                                                {{-- @foreach ($combo_vendedores as $vendedor)
                                                                @if ($vendedor->Id_Usuario == $idEquipo_idAgente)
                                                                <option value="{{ $vendedor->Id_Usuario }}" selected>
                                                                    {{ $oportunidad[0]->Agente }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $vendedor->Id_Usuario }}">
                                                                    {{ $vendedor->Nombre }}
                                                                </option>
                                                                @endif
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idAtendido">Atendido por</label>
                                                            <select id="idAtendido" name="idAtendido"
                                                                class="form-control" style="width: 100%;">
                                                                <option selected disabled>Atendido por</option>
                                                                {{-- @foreach ($combo_cotizadores as $cotizador)
                                                                @if ($cotizador->Id_Usuario == $oportunidad[0]->idAtendido)
                                                                <option value="{{ $cotizador->Id_Usuario }}" selected>
                                                                    {{ $oportunidad[0]->Atendido }}
                                                                </option>
                                                                @else
                                                                <option value="{{ $cotizador->Id_Usuario }}">
                                                                    {{ $cotizador->Nombre }}
                                                                </option>
                                                                @endif
                                                                @endforeach --}}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Fecha Prevista de Cierre</label>
                                                            <div class="input-group date" id="fecPrevCiere">
                                                                {{-- @if ($oportunidad[0]->fecPrevCiere == null) --}}
                                                                <input id="fecPrevCiereInput" name="fecPrevCiere"
                                                                    type="text"
                                                                    class="form-control datetimepicker-input"
                                                                    data-target="#fecPrevCiere"
                                                                    placeholder="Requerido" />
                                                                {{-- @else
                                                                <input id="fecPrevCiereInput" name="fecPrevCiere" type="text" class="form-control datetimepicker-input" data-target="#fecPrevCiere" placeholder="Requerido" value="{{ \Carbon\Carbon::parse($oportunidad[0]->fecPrevCiere)->format('d/m/Y') }}" /> --}}
                                                                {{-- @endif --}}
                                                                <div class="input-group-append"
                                                                    data-target="#fecPrevCiere"
                                                                    data-toggle="datetimepicker">
                                                                    <label class="labelCalendar"
                                                                        for="fecPrevCiereInput">
                                                                        <div class="divCalendar input-group-text"><i
                                                                                class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 mt-1">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch mt-4">
                                                                {{-- @if ($oportunidad[0]->Maquila == 'on')
                                                                <input id="Maquila" name="Maquila" type="checkbox" class="custom-control-input" checked> @else --}}
                                                                <input id="Maquila" name="Maquila" type="checkbox"
                                                                    class="custom-control-input">
                                                                {{-- @endif --}}
                                                                <label class="custom-control-label pointer"
                                                                    for="Maquila">Maquila</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3 mt-1">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch mt-4">
                                                                <input id="Retencion" name="Retencion"
                                                                    type="checkbox" class="custom-control-input">
                                                                <label class="custom-control-label pointer"
                                                                    for="Retencion">Retenida</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="notaRetencion">Nota Retención</label>
                                                            <div class="input-group">
                                                                <input id="notaRetencion" name="notaRetencion"
                                                                    type="text" class="form-control text-uppercase"
                                                                    placeholder="Nota Retención" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-3">
                                                        <label for="fecCreacion">Fecha Alta</label>
                                                        <div class="input-group">
                                                            {{-- @if ($oportunidad[0]->fecCreacion == null) --}}
                                                            <input id="fecAlta" type="text" class="form-control"
                                                                autocomplete="off" placeholder="dd/mm/aaaa" disabled>
                                                            {{-- @else
                                                            <input id="fecCreacion" type="text" class="form-control" autocomplete="off" value="{{ \Carbon\Carbon::parse($oportunidad[0]->fecCreacion)->format('d/m/Y H:i') }}" disabled>
                                                            @endif --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="Creado">Usuario Alta</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="Creado"
                                                                placeholder="Usuario Alta" autocomplete="off"
                                                                disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="fecUltimoCambio">Fecha Ult. Cambio</label>
                                                        <div class="input-group">
                                                            {{-- @if ($oportunidad[0]->fecUltimoCambio == null) --}}
                                                            <input id="fecUltimoCambio" type="text"
                                                                class="form-control" autocomplete="off"
                                                                placeholder="dd/mm/aaaa" disabled>
                                                            {{-- @else
                                                            <input id="fecUltimoCambio" type="text" class="form-control" autocomplete="off" value="{{ \Carbon\Carbon::parse($oportunidad[0]->fecUltimoCambio)->format('d/m/Y H:i') }}" disabled>
                                                            @endif --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="idUsuarioUltCambio">Usuario Ult. Cambio</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                id="idUsuarioUltCambio" name="idUsuarioUltCambio"
                                                                placeholder="Usuario Ult. Cambio" autocomplete="off"
                                                                disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-12">
                                                        <label for="nota">Nota</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="nota"
                                                                name="nota" placeholder="Nota" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="time_line" class="col-lg-5 col-sm-12 mt-2"
                                                style="border: 1px solid #ddd; border-radius: 10px">

                                                <div id="scrollbarTimeLine" class="row mt-2 mb-1">
                                                    <div class="col-12">
                                                        <div class="timeline mt-1">
                                                            <div>
                                                                <i id="tlPreRegistro" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlPreRegistroHeader"
                                                                        class="timeline-header">
                                                                        <span id="tlPreRegistroFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Pre Registro</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlSolCot" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlSolCotHeader" class="timeline-header">
                                                                        <span id="tlSolCotFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Solicitud de
                                                                            Cotización</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlListoCot" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlListoCotHeader" class="timeline-header">
                                                                        <span id="tlListoCotFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right"> Listo
                                                                            p/cotizar</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlCotizando" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlCotizandoHeader"
                                                                        class="timeline-header">
                                                                        <span id="tlCotizandoFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Cotizando</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlRevision" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlRevisionHeader" class="timeline-header">
                                                                        <span id="tlRevisionFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Revision</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlVoBo" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlVoBoHeader" class="timeline-header">
                                                                        <span id="tlVoBoFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Espera Vo.
                                                                            Bo.</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlListoPres" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlListoPresHeader"
                                                                        class="timeline-header">
                                                                        <span id="tlListoPresFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Listo
                                                                            p/presupuesto</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlPresTerm" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlPresTermHeader" class="timeline-header">
                                                                        <span id="tlPresTermFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Presupuesto
                                                                            terminado</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlResultadoF" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlResultadoFHeader"
                                                                        class="timeline-header">
                                                                        <span id="tlResultadoFFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">Resultado
                                                                            final</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <i id="tlOPConcluida" class="fas"></i>
                                                                <div class="timeline-item">
                                                                    <h5 id="tlOPConcluidaHeader"
                                                                        class="timeline-header">
                                                                        <span id="tlOPConcluidaFecha"><i
                                                                                class="fas fa-clock"></i>&nbsp;</span>
                                                                        <span style="float: right">OP
                                                                            Concluida</span>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                            <div class="mb-5">
                                                                <i id="tlEnd" class="fas"></i>
                                                            </div>
                                                        </div>
                                                        <div id="notaCan" style="display: none">
                                                            <label class="ml-5">Nota Cancelación : </label>
                                                            <strong id="NotaCancelacion" class="text-red"></strong>
                                                        </div>
                                                        <div class="mt-1 pl-5">
                                                            Tiempos de Entrega
                                                            <table id="tblTEntregaEdit" class="table compact nowrap">
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
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                        <div class="mt-1 pl-5">
                                                            Retenciones
                                                            <table id="tblOporRetenidasEdit"
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

                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="elementos-oport" role="tabpanel"
                                        aria-labelledby="elementos-oport-tab">

                                        <div class="col-12" id="div-btnrelacionar">
                                            <ol class="breadcrumb float-sm-right" style="padding: 0% !important;">
                                                <li class="breadcrumb-item" style="margin-right: 5px;">
                                                    <a onclick="OpenModal('mod_EP_relation')" type="button"
                                                        class="btn btn-block btn-primary btn-sm" id="btn-relacinar"
                                                        title="Relacionar">
                                                        <i class="far fa-copy"></i> Relacionar
                                                    </a>
                                                </li>

                                                <a type="button" class="btn-flotante button btn btn-primary"
                                                    title="Nuevo Elemento Presupuestado" href="#"
                                                    onclick="loaderON()">
                                                    <i class="fas fas fa-plus-circle"></i>
                                                </a>

                                            </ol>
                                        </div>
                                        {{-- @component('sections.EP_tblIndex')
                                        @endcomponent --}}
                                    </div>
                                    <div class="tab-pane fade" id="documentos-oport" role="tabpanel"
                                        aria-labelledby="documentos-oport-tab">
                                        {{-- @component('sections.Documentos')
                                        @endcomponent --}}
                                    </div>
                                    <div class="tab-pane fade" id="entregas-oport" role="tabpanel"
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
                                                        <select id="idSucursal" name="idSucursal"
                                                            class="form-control"
                                                            style="width: 100%; text-transform: uppercase">
                                                            <option selected disabled>Sucursal</option>
                                                            {{-- @foreach ($sucursales_combo_entregas as $sucursal)
                                                            <option value="{{ $sucursal->idSucursal }}" style="text-transform: uppercase !important">
                                                                {{ $sucursal->Nombre }}
                                                            </option>
                                                            @endforeach --}}
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="cantidadSucursal">Cantidad</label>
                                                        <div class="input-group">
                                                            <input id="cantidadSucursal" name="cantidadSucursal"
                                                                type="text" class="form-control"
                                                                placeholder="Cantidad" autocomplete="off"
                                                                value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Fecha Entrega</label>
                                                        <div class="input-group date" data-target-input="nearest"
                                                            id="fecEntrega">
                                                            <input id="fecEntregaInput" name="fecEntrega"
                                                                type="text"
                                                                class="form-control datetimepicker-input"
                                                                data-target="#fecEntrega"
                                                                placeholder="Fecha Entrega" />
                                                            <div class="input-group-append" data-target="#fecEntrega"
                                                                data-toggle="datetimepicker">
                                                                <div class="input-group-text"><i
                                                                        class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="totalCantidadOP">Total OP</label>
                                                        <div class="input-group">
                                                            <input id="totalCantidadOP" type="text"
                                                                class="form-control text-center text-bold" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 pt-4 text-right">
                                                        <a onclick="agregarCantidad()"
                                                            class="btn btn-primary btn-sm rounded-circle pointer"
                                                            title="Agregar Cantidad"><i
                                                                class="fas fa-plus-circle"></i></a>
                                                    </div>
                                                </div>
                                                <table id="tableEntregasOportunidadesEdit"
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
                    @include('oportunidades.tiempo_entrega')
                </div>




            </div>
        </div>
    </div>
</div>
