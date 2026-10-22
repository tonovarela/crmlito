<div class="modal fade" id="modal-llamada-panel" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 mt-1">
                    <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuarioPanel">
                    <input type="hidden" value="{{ $id_usuario }}" name="idUsuarioUltimoCambio"
                        id="idUsuarioUltimoCambioPanel">
                    <input type="hidden" name="idLlamadasReuniones" id="idLlamadasReunionesPanel">
                    <input type="hidden" id="estatusPanel">
                    <input type="hidden" id="idRolPanel" value="{{ $rol }}">
                    <input type="hidden" id="agente_idPanel" value="{{ $agente_id }}">
                    <input type="hidden" id="canceladoPanel">
                    <input type="hidden" id="tipoPanel" name="tipo">
                    <div id="tabGeneralLlamadaPanel" class="card-body">
                        <div class="rounded bg-gray">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <h6 id="tituloLlamadaPanel" class="text-bold pt-1 pl-1" style="cursor: move">Agregar
                                        Llamada</h6>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                <div>

                                    <div class="row">
                                        <div id="info_generalPanel" class="col-12">
                                            <div id="isCancelledPanel" class="canceling-cont" style="display: none">
                                                <div class="cancelingModal">CANCELADA</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="labelAsuntoLugarPanel" class="mt-2"
                                                            for="asuntoLugar">AsuntoLugar
                                                            &nbsp;</label>
                                                        <input type="text" class="form-control col-12 text-uppercase"
                                                            id="asuntoLugarPanel" name="asuntoLugar"
                                                            placeholder="Asunto" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="divDescripcionPanel" style="display: none">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="descripcionPanel">Descripción</label>
                                                        <input type="text" class="form-control text-uppercase"
                                                            id="descripcionPanel" name="descripcion"
                                                            placeholder="Descripción" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="relacionadoConPanel">Cliente</label>
                                                        <select id="relacionadoConPanel" name="relacionadoCon"
                                                            class="form-control select2 select2-height"
                                                            style="width: 100%; text-transform: uppercase">
                                                            <option value="" selected disabled>Cliente</option>
                                                            @foreach ($combo_clientes as $cliente)
                                                                <option value="{{ $cliente->idCliente }}">
                                                                    {{ $cliente->razonsocial }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="divContactoPanel">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="id_contactoPanel">Contacto</label>
                                                            <select id="id_contactoPanel" name="id_contacto"
                                                                class="form-control select2-height"
                                                                style="width: 100%; text-transform: uppercase">
                                                                <option value="" selected disabled>Contacto</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="tel_contactoPanel">Teléfono</label>
                                                            <select id="tel_contactoPanel" name="tel_contacto"
                                                                class="form-control select2-height"
                                                                style="width: 100%; text-transform: uppercase">
                                                                <option value="" selected disabled>Teléfono</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="fecInicioLabelPanel">Fecha Inicio</label>
                                                        <div class="input-group date datetime" id="fecInicioPanel"
                                                            data-target-input="nearest">
                                                            <input id="fecInicioInputPanel" name="fecInicio" type="text"
                                                                class="form-control datetimepicker-input"
                                                                data-target="#fecInicioPanel" placeholder="Fecha" />
                                                            <div class="input-group-append"
                                                                data-target="#fecInicioPanel"
                                                                data-toggle="datetimepicker">
                                                                <label class="labelCalendar" for="fecInicioInputPanel">
                                                                    <div class="divCalendar input-group-text"><i
                                                                            class="fa fa-calendar"></i>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="divDireccionPanel" style="display: none">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label id="direccionLabelPanel"
                                                            for="direccionPanel">Tipo</label>
                                                        <select id="direccionPanel" name="direccion"
                                                            class="form-control select2-height"
                                                            style="width: 100%; text-transform: uppercase">
                                                            <option value="" selected disabled>Tipo</option>
                                                            @foreach ($direcciones as $direccion)
                                                                <option
                                                                    value="{{ $direccion->idCatDireccionLlamadas }}">
                                                                    {{ $direccion->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="tipoRepeticionPanel">Tipo Repetición</label>
                                                        <select id="tipoRepeticionPanel" name="tipoRepeticion"
                                                            class="form-control select2-height"
                                                            style="width: 100%; text-transform: uppercase">
                                                            <option value="" selected disabled>Repeticion</option>
                                                            @foreach ($tipoRepeticionLlamadas as $tipoRepeticion)
                                                                <option
                                                                    value="{{ $tipoRepeticion->idCatTipoRepeticionLlamadas }}">
                                                                    {{ $tipoRepeticion->descripcion }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="observacionesPanel">Observaciones</label>
                                                        <textarea name="observaciones" id="observacionesPanel" rows="3" class="form-control text-uppercase"
                                                            placeholder="Observaciones" autocomplete="off"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-1" id="notaCancelacionDivPanel"
                                                style="display: none; margin-top: -15px">
                                                <div class="col-12">
                                                    <label class="ml-2">Motivo Cancelación : </label>
                                                    <strong id="notaCancelacionPanel" class="text-red"></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer modal-footer-contactos">

                        <div class="text-center input-group-append col-12">
                            <span id="sbtnCancelarLlaPanel" class="col-4 center">
                                <a id="btnCancelarLlaPanel" onclick="CancelarLlamada()"
                                    class="btn btn-danger btn-sm btn-block">Cancelar</a>
                            </span>
                            <span id="sbtnSaveLlaPanel" class="col-4">
                                <a id="btnSaveLlaPanel" onclick="updateLlamada()"
                                    class="btn btn-primary btn-sm btn-block">Guardar</a>

                                {{-- <button id="btnSaveLla" type="button"
                                        class="btn btn-primary btn-sm btn-block"
                                        onclick="closeMLlamada()">
                                        Guardar
                                    </button> --}}
                            </span>
                            <span id="sbtnBackLlaPanel" class="col-4 center">
                                <button id="btnBackLlaPanel" type="button" class="btn btn-secondary btn-sm btn-block"
                                    data-dismiss="modal" onclick="closeMLlamada()"></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
