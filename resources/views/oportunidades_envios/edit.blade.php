@extends('layout.master')

@section('content')
    <div id="formOporEdit" class="col-12 mt-2">
        <form method="post" action="{{ route('oportunidades.update', $oportunidad[0]->idOportunidad) }}" role="form">
            {{ csrf_field() }} {{ method_field('PUT') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" value="{{ $oportunidad[0]->idEstatus }}" name="Estatus" id="Estatus">
            <input type="hidden" value="{{ $oportunidad[0]->idOportunidad }}" name="idOportunidad">
            <div class="card-body">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <label class="mt-2" for="nomOportunidad">Nombre Oportunidad &nbsp;</label>
                        <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="nomOportunidad"
                            name="nomOportunidad" autocomplete="off" disabled
                            value="{{ $oportunidad[0]->nomOportunidad }}">
                        <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                            <span class="col-md-6"><button id="btnSave" type="submit"
                                    class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                            <span class="col-md-6 center"><a href="{{ route('Oportunidades') }}"
                                    class="btn btn-secondary btn-sm btn-block">Regresar</a></span>
                        </div>
                    </div>
                </div>
                <div>
                    <ul class="nav nav-tabs col-12" id="custom-content-below-tab" role="tablist">
                        <li class="nav-item col-4">
                            <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill"
                                href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home"
                                aria-selected="true">General</a>
                        </li>
                        <li class="nav-item col-4">
                            <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill"
                                href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile"
                                aria-selected="false">Elementos Presupuestados</a>
                        </li>
                        <span class="col-4">
                            <div class="rounded-pill bg-blue" style="float: right">
                                <strong class="p-2 text-lg">
                                    Op
                                    {{ $oportunidad[0]->idOportunidad }}
                                </strong>
                            </div>
                        </span>
                    </ul>
                    <div id="scrollbarTabsModalRound" class="tab-content">
                        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
                            aria-labelledby="custom-content-below-home-tab">
                            <div class="row">
                                <div id="infGeneral" class="col-lg-7 col-sm-12 mt-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="idCliente">Cliente</label>
                                                <select id="idCliente" name="idCliente" class="form-control"
                                                    style="width: 100%" disabled>
                                                    <option value="{{ $oportunidad[0]->idCliente }}" selected>
                                                        {{ $oportunidad[0]->razonsocial }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="idTipo">Tipo de Oportunidad</label>
                                                <select id="idTipo" name="idTipo" class="form-control" style="width: 100%;">
                                                    @foreach ($tipos as $tipo)
                                                        @if ($tipo->idTipoOportunidad == $oportunidad[0]->idTipoOportunidad)
                                                            <option value="{{ $tipo->idTipoOportunidad }}" selected>
                                                                {{ $oportunidad[0]->desTipoOportundidad }}</option>
                                                        @else
                                                            <option value="{{ $tipo->idTipoOportunidad }}">
                                                                {{ $tipo->desTipoOportundidad }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="idEstatus">Estatus</label>

                                    <div class="row" id="listEstatus">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 1)
                                                        <input class="custom-control-input" type="radio" id="idStatusPre"
                                                            name="idStatus" checked value="1">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusPre"
                                                            name="idStatus" value="1">
                                                    @endif
                                                    <label for="idStatusPre" class="custom-control-label">Pre
                                                        registro</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 2)
                                                        <input class="custom-control-input" type="radio" id="idStatusSolCot"
                                                            name="idStatus" checked value="2">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusSolCot"
                                                            name="idStatus" value="2">
                                                    @endif
                                                    <label for="idStatusSolCot" class="custom-control-label">Solicitud de
                                                        cotización</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 3)
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusListoCot" name="idStatus" checked value="3">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusListoCot" name="idStatus" value="3">
                                                    @endif
                                                    <label for="idStatusListoCot" class="custom-control-label">Listo
                                                        p/cotizar</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 4)
                                                        <input class="custom-control-input" type="radio" id="idStatusCot"
                                                            name="idStatus" checked value="4">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusCot"
                                                            name="idStatus" value="4">
                                                    @endif
                                                    <label for="idStatusCot" class="custom-control-label">Cotizando</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 5)
                                                        <input class="custom-control-input" type="radio" id="idStatusRev"
                                                            name="idStatus" checked value="5">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusRev"
                                                            name="idStatus" value="5">
                                                    @endif
                                                    <label for="idStatusRev" class="custom-control-label">Revisión</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 6)
                                                        <input class="custom-control-input" type="radio" id="idStatusVoBo"
                                                            name="idStatus" checked value="6">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusVoBo"
                                                            name="idStatus" value="6">
                                                    @endif
                                                    <label for="idStatusVoBo" class="custom-control-label">Espera Vo.
                                                        Bo.</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 7)
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusListoPre" name="idStatus" checked value="7">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusListoPre" name="idStatus" value="7">
                                                    @endif
                                                    <label for="idStatusListoPre" class="custom-control-label">Listo p
                                                        /presupuesto</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 8)
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusPreTerm" name="idStatus" checked value="8">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusPreTerm" name="idStatus" value="8">
                                                    @endif
                                                    <label for="idStatusPreTerm" class="custom-control-label">Presupuesto
                                                        terminado</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3 border">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 9)
                                                        <input class="custom-control-input" type="radio" id="idStatusGanado"
                                                            name="idStatus" checked value="9">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusGanado"
                                                            name="idStatus" value="9">
                                                    @endif
                                                    <label for="idStatusGanado" class="custom-control-label">Ganado</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 10)
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusPerdido" name="idStatus" checked value="10">
                                                    @else
                                                        <input class="custom-control-input" type="radio"
                                                            id="idStatusPerdido" name="idStatus" value="10">
                                                    @endif
                                                    <label for="idStatusPerdido"
                                                        class="custom-control-label">Perdido</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-group">
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 11)
                                                        <input class="custom-control-input" type="radio" id="idStatusSolOP"
                                                            name="idStatus" checked value="11">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusSolOP"
                                                            name="idStatus" value="11">
                                                    @endif
                                                    <label for="idStatusSolOP" class="custom-control-label">Solicitud
                                                        OP</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    @if ($oportunidad[0]->idEstatus == 12)
                                                        <input class="custom-control-input" type="radio" id="idStatusOPCon"
                                                            name="idStatus" checked value="12">
                                                    @else
                                                        <input class="custom-control-input" type="radio" id="idStatusOPCon"
                                                            name="idStatus" value="12">
                                                    @endif
                                                    <label for="idStatusOPCon" class="custom-control-label">OP
                                                        Concluida</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idExclusion">Previsión</label>
                                                <select id="idExclusion" name="idExclusion" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($previsiones as $prevision)
                                                        @if ($prevision->idExclusionOport == $oportunidad[0]->idExclusionOport)
                                                            <option value="{{ $prevision->idExclusionOport }}" selected>
                                                                {{ $oportunidad[0]->descExclusionOport }}</option>
                                                        @else
                                                            <option value="{{ $prevision->idExclusionOport }}">
                                                                {{ $prevision->descExclusionOport }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="Valor">Valor de la Oportunidad</label>
                                                <div class="input-group">
                                                    <input id="Valor" name="Valor" type="text" class="form-control"
                                                        style="cursor: not-allowed" placeholder="$0.00" disabled
                                                        autocomplete="off" value="{{ $oportunidad[0]->Valor }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idProbabilidad">Probabilidad de Ganar</label>
                                                <select id="idProbabilidad" name="idProbabilidad" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($probabilidades as $probabilidad)
                                                        @if ($probabilidad->idProbabilidad == $oportunidad[0]->idProbabilidad)
                                                            <option value="{{ $probabilidad->idProbabilidad }}" selected>
                                                                {{ $oportunidad[0]->descProbabilidad }}</option>
                                                        @else
                                                            <option value="{{ $probabilidad->idProbabilidad }}">
                                                                {{ $probabilidad->descProbabilidad }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4">
                                                    @if ($oportunidad[0]->Maquila == 'on')
                                                        <input id="Maquila" name="Maquila" type="checkbox"
                                                            class="custom-control-input" checked>
                                                    @else
                                                        <input id="Maquila" name="Maquila" type="checkbox"
                                                            class="custom-control-input">
                                                    @endif
                                                    <label class="custom-control-label" for="Maquila">Marcar si es
                                                        Maquila</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Equipos</label>
                                                <select id="idEquipo" name="idEquipo" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($equipos as $equipo)
                                                        <option value="{{ $equipo->idEquipo }}">
                                                            {{ $equipo->descEquipo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idAgente">Asignado a</label>
                                                <select id="idAgente" name="idAgente" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($combo_vendedores as $vendedor)
                                                        @if ($vendedor->agente == $oportunidad[0]->id_Agente)
                                                            <option value="{{ $vendedor->agente }}" selected>
                                                                {{ $oportunidad[0]->Agente }}</option>
                                                        @else
                                                            <option value="{{ $vendedor->agente }}">
                                                                {{ $vendedor->nombre }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idAtendido">Atendido por</label>
                                                <select id="idAtendido" name="idAtendido" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($combo_cotizadores as $cotizador)
                                                        @if ($cotizador->Id_Usuario == $oportunidad[0]->idAtendido)
                                                            <option value="{{ $cotizador->Id_Usuario }}" selected>
                                                                {{ $oportunidad[0]->Atendido }}</option>
                                                        @else
                                                            <option value="{{ $cotizador->Id_Usuario }}">
                                                                {{ $cotizador->Nombre }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="numOrdenMTC">Num Orden Metrics</label>
                                                <div class="input-group">
                                                    <input id="numOrdenMTC" name="numOrdenMTC" type="text"
                                                        class="form-control" placeholder="Num Orden Metrics"
                                                        autocomplete="off" value="{{ $oportunidad[0]->numOrdenMTC }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="notaRetencion">Nota Retención</label>
                                                <div class="input-group">
                                                    <input id="notaRetencion" name="notaRetencion" type="text"
                                                        class="form-control" placeholder="Nota Retención" autocomplete="off"
                                                        value="{{ $oportunidad[0]->notaRetencion }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idMotivoPerdida">Motivo Perdida</label>
                                                <select id="idMotivoPerdida" name="idMotivoPerdida" class="form-control"
                                                    style="width: 100%;">
                                                    <option selected disabled>Motivo Perdida</option>
                                                    @foreach ($motivoPerdida as $motivo)
                                                        @if ($motivo->idMotivoPerdida == $oportunidad[0]->idMotivoPerdida)
                                                            <option value="{{ $motivo->idMotivoPerdida }}" selected>
                                                                {{ $oportunidad[0]->descMotivoPerdida }}</option>
                                                        @else
                                                            <option value="{{ $motivo->idMotivoPerdida }}">
                                                                {{ $motivo->descMotivoPerdida }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
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
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Pre Registro</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
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
                                                        <i class="fas fa-times bg-dark"></i>
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
                                                        <i class="fas fa-check bg-blue"></i>
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
                                                        <i class="fas fa-times bg-dark"></i>
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
                                                        <i class="fas fa-check bg-blue"></i>
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
                                                        <i class="fas fa-times bg-dark"></i>
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
                                                        <i class="fas fa-check bg-blue"></i>
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

                                                @if ($oportunidad[0]->fecRevision == null)
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Revision</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecRevision)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecRevision)->format('H:i:s') }}</span>
                                                                <span style="float: right">Revision</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecEsperaVobo == null)
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Espera Vo. Bo.</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecEsperaVobo)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecEsperaVobo)->format('H:i:s') }}</span>
                                                                <span style="float: right">Espera Vo. Bo.</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecListoPresupuesto == null)
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Listo p/presupuesto</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecListoPresupuesto)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecListoPresupuesto)->format('H:i:s') }}</span>
                                                                <span style="float: right">Listo p/presupuesto</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecPresupTerminado == null)
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Presupuesto terminado</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecPresupTerminado)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecPresupTerminado)->format('H:i:s') }}</span>
                                                                <span style="float: right">Presupuesto terminado</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecResultadoF == null)
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">Resultado final</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecResultadoF)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecResultadoF)->format('H:i:s') }}</span>
                                                                <span style="float: right">Resultado final</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($oportunidad[0]->fecOrdenConcluida == null)
                                                    <div>
                                                        <i class="fas fa-times bg-dark"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header">
                                                                <span><i class="fas fa-clock"></i>&nbsp;</span>
                                                                <span>&nbsp;</span>
                                                                <span style="float: right">OP Concluida</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-clock bg-gray"></i>
                                                    </div>
                                                @else
                                                    <div>
                                                        <i class="fas fa-check bg-blue"></i>
                                                        <div class="timeline-item">
                                                            <h5 class="timeline-header bg-blue text-bold">
                                                                <span><i class="fas fa-clock"></i>
                                                                    {{ \Carbon\Carbon::parse($oportunidad[0]->fecOrdenConcluida)->format('d-m-Y') }}</span>
                                                                <span>{{ \Carbon\Carbon::parse($oportunidad[0]->fecOrdenConcluida)->format('H:i:s') }}</span>
                                                                <span style="float: right">OP Concluida</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <i class="fas fa-clock bg-blue"></i>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel"
                            aria-labelledby="custom-content-below-profile-tab">
                            <div id="elePre">
                                <table id="tableElePreOpor" class="table table-striped table-bordered compact nowrap"
                                    style="width:100%">
                                    <thead>
                                        <th>Id Elemento</th>
                                        <th>Id Tipo Item</th>
                                        <th>Nombre</th>
                                        <th>Presupuesto</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            var omtc = document.getElementById('numOrdenMTC');

            $('input:radio[name="idStatus"]').change(function() {

                console.log(this.value);

                if (this.checked && this.value == '9') {
                    omtc.disabled = false;
                } else {
                    omtc.disabled = true;
                    omtc.value = '';
                }
            });

            var estatus = $('#Estatus').val();

            if (estatus == 1) {
                $("#idStatusPre").prop('disabled', false);
                $("#idStatusSolCot").prop('disabled', false);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }

            if (estatus == 2) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', false);
                $("#idStatusListoCot").prop('disabled', false);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 3) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', false);
                $("#idStatusCot").prop('disabled', false);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 4) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', false);
                $("#idStatusRev").prop('disabled', false);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 5) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', false);
                $("#idStatusVoBo").prop('disabled', false);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 6) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', false);
                $("#idStatusListoPre").prop('disabled', false);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 7) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', false);
                $("#idStatusPreTerm").prop('disabled', false);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 8) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', false);
                $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', false);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 9) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', false);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 11) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', false);
                $("#idStatusOPCon").prop('disabled', false);
            }

            if (estatus == 10 || estatus == 12) {
                $("#btnSave").prop('disabled', true);

                // $("#infGeneral").addClass("disable");

                var nodes = document.getElementById("infGeneral").getElementsByTagName('*');
                for (var i = 0; i < nodes.length; i++) {
                    nodes[i].disabled = true;
                }

                $("#elePre").addClass("disable");

                var nodesEle = document.getElementById("elePre").getElementsByTagName('*');
                for (var j = 0; j < nodesEle.length; j++) {
                    nodesEle[j].disabled = true;
                }
            }
        });
    </script>
@endsection
