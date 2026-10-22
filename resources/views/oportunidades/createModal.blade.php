<div class="modal fullscreen-modal fade" id="modal-oportunidades-create" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">

                
                <div id="formOporCreateModal" class="col-12 mt-2">
                    <form method="post" class="form-horizontal" data-toggle="validator">
                        {{-- <form method="post" action="{{ route('oportunidades.store') }}" class="form-horizontal" data-toggle="validator"> --}}
                            {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
                        <input type="hidden" value="{{ $idEquipo_idAgente }}" name="idEquipo_idAgente" id="idEquipo_idAgente">
                        <div class="card-body fullscreen-card">
                            <div class="rounded bg-gray">
                                <div class="row mb-2">
                                    <div class="col-12 text-center">
                                        <h6 id="titulo" class="text-bold pt-1 pl-1">Nueva Oportunidad</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="input-group input-group-sm">
                                    <h6 class="text-right text-bold text-red pr-1 pt-2">&nbsp;No.
                                        {{ $folio }} - &nbsp;
                                    </h6>
                                    <label class="mt-2" for="nomOportunidad">Oportunidad &nbsp;</label>
                                    <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10 text-uppercase"
                                        id="nomOportunidad" name="nomOportunidad" placeholder="Requerido" autocomplete="off" autofocus>
                                    <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                                        <span class="col-md-6"><button onclick="loaderON()" id="btnSaveOport" type="submit"
                                                class="btn btn-primary btn-sm btn-block" disabled>Guardar</button></span>
                                        <span class="col-md-6 center">
                                            {{-- <a class="btn btn-secondary btn-sm btn-block"
                                                onclick="regresar()">Regresar</a> --}}


                                                <button type="button" class="btn btn-secondary btn-sm btn-block"
                                                data-dismiss="modal" onclick="closeMOportunidad()">Regresar</button>

                                                
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div id="scrollbarTabsModalRound" class="tab-content">
                                    <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
                                        aria-labelledby="custom-content-below-home-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idCliente">Cliente</label>
                                                            <select id="idCliente" name="idCliente"
                                                                class="form-control select2 select2-height"
                                                                style="width: 100%; text-transform: uppercase">
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
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Equipos</label>
                                                            <select id="idEquipo" name="idEquipo" class="form-control"
                                                                style="width: 100%;" required>
                                                                <option value="Requerido" selected disabled>Requerido</option>
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
                                                                style="width: 100%;" required>
                                                                <option value="Requerido" selected disabled>Requerido</option>
                                                                @foreach ($combo_vendedores as $vendedor)
                                                                    @if ($vendedor->Id_Usuario == $idEquipo_idAgente)
                                                                        <option value="{{ $vendedor->Id_Usuario }}" selected>
                                                                            {{ $vendedor->Nombre }}</option>
                                                                    @else
                                                                        <option value="{{ $vendedor->Id_Usuario }}">
                                                                            {{ $vendedor->Nombre }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Fecha Prevista de Cierre</label>
                                                            <div class="input-group" id="fecPrevCiere">
                                                                <input id="fecPrevCiereInput" name="fecPrevCiere" type="text"
                                                                    class="form-control datetimepicker-input"
                                                                    data-target="#fecPrevCiere" placeholder="Requerido" autocomplete="off" />
                                                                <div class="input-group-append" data-target="#fecPrevCiere"
                                                                    data-toggle="datetimepicker">
                                                                    <label class="labelCalendar" for="fecPrevCiereInput">
                                                                        <div class="divCalendar input-group-text"><i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    {{-- <div class="col-md-6">
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
                                                </div> --}}
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            



            </div>
        </div>
    </div>
</div>
