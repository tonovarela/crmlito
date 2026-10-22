@extends('layout.master')

@section('content')
    <div id="formOporCreate" class="col-12 mt-2">
        <form method="post" action="{{ route('oportunidades.store') }}" class="form-horizontal" data-toggle="validator">
            {{ csrf_field() }} {{ method_field('POST') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" name="fecRevInicial" id="fecRevInicial">
            <div class="card-body">
                <div class="form-group">
                    <span>
                        <div class="rounded-pill bg-blue" style="float: left">
                            <strong class="p-2 text-lg">
                                Op
                                {{ $folio[0]->FolioOportunidad + 1 }}
                            </strong>
                        </div>
                    </span>
                    <div class="input-group input-group-sm">
                        <label class="mt-2" for="nomOportunidad">Nombre Oportunidad &nbsp;</label>
                        <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="nomOportunidad"
                            name="nomOportunidad" placeholder="Requerido" required autocomplete="off" autofocus>
                        <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                            <span class="col-md-6"><button type="submit"
                                    class="btn btn-primary btn-sm btn-block">Guardar</button></span>
                            <span class="col-md-6 center"><a href="{{ route('Oportunidades') }}"
                                    class="btn btn-secondary btn-sm btn-block">Cancelar</a></span>
                        </div>
                    </div>
                </div>
                <div>
                    <div id="scrollbarTabsModalRound" class="tab-content" id="custom-content-below-tabContent">
                        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
                            aria-labelledby="custom-content-below-home-tab">
                            <div class="row">
                                <div id="infGeneral" class="col-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idCliente">Cliente</label>
                                                <select id="idCliente" name="idCliente" class="form-control select2"
                                                    style="width: 100%">
                                                    <option selected disabled>Requerido</option>
                                                    @foreach ($combo_clientes as $cliente)
                                                        <option value="{{ $cliente->idCliente }}">
                                                            {{ $cliente->razonsocial }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idTipo">Tipo de Oportunidad</label>
                                                <select id="idTipo" name="idTipo" class="form-control"
                                                    style="width: 100%;">
                                                    <option selected disabled>Requerido</option>
                                                    @foreach ($tipos as $tipo)
                                                        <option value="{{ $tipo->idTipoOportunidad }}">
                                                            {{ $tipo->desTipoOportundidad }}</option>
                                                    @endforeach
                                                </select>
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
                                                        <option value="{{ $prevision->idExclusionOport }}">
                                                            {{ $prevision->descExclusionOport }}</option>
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
                                                        autocomplete="off" value="{{ old('Valor') }}">
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
                                                        <option value="{{ $probabilidad->idProbabilidad }}">
                                                            {{ $probabilidad->descProbabilidad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4">
                                                    <input id="Maquila" name="Maquila" type="checkbox"
                                                        class="custom-control-input">
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
                                                        <option value="{{ $vendedor->Id_Usuario }}">
                                                            {{ $vendedor->Nombre }}</option>
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
                                                    @foreach ($combo_cotizadores as $cotizadores)
                                                        <option value="{{ $cotizadores->Id_Usuario }}">
                                                            {{ $cotizadores->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="numOrdenMTC">Num Orden Metrics</label>
                                                <div class="input-group">
                                                    <input id="numOrdenMTC" name="numOrdenMTC" type="text"
                                                        class="form-control" placeholder="Num Orden Metrics"
                                                        autocomplete="off" value="{{ old('numOrdenMTC') }}">
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="notaRetencion">Nota Retención</label>
                                                <div class="input-group">
                                                    <input id="notaRetencion" name="notaRetencion" type="text"
                                                        class="form-control" placeholder="Nota Retención" autocomplete="off"
                                                        value="{{ old('notaRetencion') }}">
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
                                                        <option value="{{ $motivo->idMotivoPerdida }}">
                                                            {{ $motivo->descMotivoPerdida }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                {{-- <div id="time_line" class="col-lg-5 col-xl-4"
                                    style="border: 1px solid #ddd; border-radius: 10px">

                                    <div id="scrollbarTimeLine" class="row mt-2 mb-1">
                                        <div class="col-md-12">
                                            <div class="timeline mt-1">
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
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        {{-- <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel"
                            aria-labelledby="custom-content-below-profile-tab">
                            ENVIOS
                        </div> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
