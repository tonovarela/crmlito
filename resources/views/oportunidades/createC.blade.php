<div class="modal fade" id="modal-create-oportunidad" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                {{ csrf_field() }} {{ method_field('POST') }}
                <div class="modal-body" id="modal-body-oportunidad">
                    <div class="col-12">
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
                        {{-- <input type="hidden" name="fecRevInicial" id="fecRevInicial"> --}}
                        <div class="card-body">
                            <div class="rounded bg-gray" style="width: 104%; margin-left: -13px; margin-top: -5px;">
                                <div class="row mb-2 mt-1">
                                    <div class="col-10" style="cursor: all-scroll; float: left">
                                        <h6 class="titulo text-bold pt-3 pl-3">Nueva Oportunidad</h6>
                                    </div>
                                    <div class="col-2" style="float: right">
                                        <strong class="pt-2 pb-2 pr-3 text-lg" style="float: right">
                                            Op
                                            {{ $folio[0]->FolioOportunidad + 1 }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group input-group-sm col-12 mb-1">
                                <label class="mt-2" for="nomOportunidad">Nombre Oportunidad &nbsp;</label>
                                <input type="text" class="form-control" id="nomOportunidad" name="nomOportunidad"
                                    placeholder="Requerido" required autocomplete="off" autofocus>
                            </div>
                            <div>
                                <div class="tab-content" id="custom-content-below-tabContent">
                                    <div class="tab-pane fade show active" id="custom-content-below-home"
                                        role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                        <div class="row">
                                            <div id="infGeneral" class="col-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idCliente">Cliente</label>
                                                            <select id="idCliente" name="idCliente" class="form-control"
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
                                                            <select id="idExclusion" name="idExclusion"
                                                                class="form-control" style="width: 100%;">
                                                                @foreach ($previsiones as $prevision)
                                                                    <option
                                                                        value="{{ $prevision->idExclusionOport }}">
                                                                        {{ $prevision->descExclusionOport }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="Valor">Valor de la Oportunidad</label>
                                                            <div class="input-group">
                                                                <input id="Valor" name="Valor" type="text"
                                                                    class="form-control" style="cursor: not-allowed"
                                                                    placeholder="$0.00" disabled autocomplete="off"
                                                                    value="{{ old('Valor') }}">
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
                                                                @foreach ($probabilidades as $probabilidad)
                                                                    <option
                                                                        value="{{ $probabilidad->idProbabilidad }}">
                                                                        {{ $probabilidad->descProbabilidad }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mt-1">
                                                        <div class="form-group">
                                                            <div class="custom-control custom-switch mt-4">
                                                                <input id="Maquila" name="Maquila" type="checkbox"
                                                                    class="custom-control-input">
                                                                <label class="custom-control-label" for="Maquila">Marcar
                                                                    si es
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
                                                <div class="row mb-1">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="idAtendido">Atendido por</label>
                                                            <select id="idAtendido" name="idAtendido"
                                                                class="form-control" style="width: 100%;">
                                                                @foreach ($combo_cotizadores as $cotizadores)
                                                                    <option value="{{ $cotizadores->Id_Usuario }}">
                                                                        {{ $cotizadores->Nombre }}</option>
                                                                @endforeach
                                                            </select>
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
                </div>
                <div class="modal-footer modal-footer-oportunidades">
                    <div class="text-center input-group-append col-md-12">
                        <button type="submit" class="rounded-lg btn btn-primary btn-sm btn-block" id="saveBtn"
                            value="create">
                            Guardar
                        </button>
                        <span class="col-md-6">
                            <button type="button" class="rounded-lg btn btn-secondary btn-sm btn-block"
                                data-dismiss="modal">Cancelar</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
