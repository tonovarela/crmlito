<div class="modal fade" id="modal-llamada" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 mt-1">
                    <form method="post" class="form-horizontal" data-toggle="validator">
                        {{ csrf_field() }} {{ method_field('POST') }}
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
                        <input type="hidden" value="{{ $id_usuario }}" name="idUsuarioUltimoCambio"
                            id="idUsuarioUltimoCambio">
                        <input type="hidden" name="idLlamadasReuniones" id="idLlamadasReuniones">
                        <input type="hidden" id="estatus">
                        <input type="hidden" id="idRol" value="{{ $rol }}">
                        <input type="hidden" id="agente_id" value="{{ $agente_id }}">
                        <input type="hidden" id="cancelado">
                        <input type="hidden" id="tipo" name="tipo">
                        <div id="tabGeneralLlamada" class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <h6 id="tituloLlamada" class="text-bold pt-1 pl-1" style="cursor: move">Agregar
                                            Llamada</h6>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div>
                                    <div>

                                        <div class="row">
                                            <div id="info_general" class="col-12">
                                                <div id="isCancelled" class="canceling-cont" style="display: none">
                                                    <div id="isCancelledDiv" class="cancelingModal">CANCELADA</div>
                                                </div>
                                                <div id="esRealizada" class="canceling-cont" style="display: none">
                                                    <div id="isRealizadaDiv" class="cancelingModal" style="color: #369BDF">REALIZADA</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label id="labelAsuntoLugar" class="mt-2"
                                                                for="asuntoLugar">AsuntoLugar
                                                                &nbsp;</label>
                                                            <input type="text"
                                                                class="form-control col-12 text-uppercase"
                                                                id="asuntoLugar" name="asuntoLugar" placeholder="Asunto"
                                                                autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="divDescripcion" style="display: none">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="descripcion">Descripción</label>
                                                            <input type="text" class="form-control text-uppercase"
                                                                id="descripcion" name="descripcion"
                                                                placeholder="Descripción" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="relacionadoCon">Cliente</label>
                                                            <select id="relacionadoCon" name="relacionadoCon"
                                                                class="form-control select2 select2-height"
                                                                style="width: 100%; text-transform: uppercase">
                                                                <option value="" selected disabled>Cliente</option>
                                                            @foreach ($combo_clientes as $cliente)
                                                                {{-- @if ($cliente->idCliente == $cliente->idProspecto)
                                                                    <option value="{{ $cliente->idProspecto }}">
                                                                        {{ $cliente->razonsocial }}
                                                                    </option>
                                                                @else --}}
                                                                    <option value="{{ $cliente->idProspecto }}">
                                                                        {{ $cliente->razonsocial }}
                                                                    </option>
                                                                    
                                                                {{-- @endif --}}
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="divContacto">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="id_contacto">Contacto</label>
                                                                <select id="id_contacto" name="id_contacto"
                                                                    class="form-control select2-height"
                                                                    style="width: 100%; text-transform: uppercase">
                                                                    <option value="" selected disabled>Contacto</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label for="tel_contacto">Teléfono</label>
                                                                <select id="tel_contacto" name="tel_contacto"
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
                                                            <label id="fecInicioLabel" for="fecInicioInput">Fecha</label>
                                                            <div class="input-group" id="fecInicio">
                                                                <input id="fecInicioInput" name="fecInicio" type="text"
                                                                    class="form-control datetimepicker-input" placeholder="Fecha" />
                                                                <div class="input-group-append" data-target="#fecInicioInput"
                                                                    data-toggle="datetimepicker">
                                                                    <label class="labelCalendar" for="fecInicioInput">
                                                                        <div class="divCalendar input-group-text"><i class="fa fa-calendar"></i>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" id="divDireccion" style="display: none">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label id="direccionLabel" for="direccion">Tipo</label>
                                                            <select id="direccion" name="direccion"
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
                                                            <label for="tipoRepeticion">Tipo Repetición</label>
                                                            <select id="tipoRepeticion" name="tipoRepeticion"
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
                                                            <label for="observaciones">Observaciones</label>
                                                            <textarea name="observaciones" id="observaciones" rows="3" class="form-control text-uppercase"
                                                                placeholder="Observaciones"
                                                                autocomplete="off"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1" id="notaCancelacionDiv"
                                                    style="display: none; margin-top: -15px">
                                                    <div class="col-12">
                                                        <label class="ml-2">Motivo Cancelación : </label>
                                                        <strong id="notaCancelacion" class="text-red"></strong>
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
                                <span id="sbtnCancelarLla" class="col-3 center">
                                    <a id="btnCancelarLla" onclick="CancelarLlamada()"
                                        class="btn btn-danger btn-sm btn-block">Cancelar</a>
                                </span>
                                <span id="sbtnRealizada" class="col-3 center">
                                    <a id="btnRealizada" onclick="realizada()"
                                        class="btn btn-primary btn-sm btn-block">Realizada</a>
                                </span>
                                <span id="sbtnSaveLla" class="col-3"><button id="btnSaveLla" type="submit"
                                        class="btn btn-primary btn-sm btn-block"
                                        onclick="closeMLlamada()">Guardar</button></span>
                                <span id="sbtnBackLla" class="col-3 center">
                                    <button id="btnBackLla" type="button" class="btn btn-secondary btn-sm btn-block"
                                        data-dismiss="modal" onclick="closeMLlamada()"></button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
