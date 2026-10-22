<div class="modal fade" id="modal-llamada" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 mt-1">
                    <form method="post" class="form-horizontal" data-toggle="validator" id="formLlamada" enctype="multipart/form-data">
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
                        <input type="hidden" id="prospecto" name="prospecto">
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
                                                                    <option value="{{ $cliente->idCliente }}">
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
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label id="agenteLbl">Agente encargado</label>
                                                            <div class="input-group" id="agIn">
                                                                <label id="agenteLlamada" class="form-input"></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label id="minutaLbl">Minuta</label>
                                                            <div class="input-group" id="minIn">
                                                                <input type="file" id="minutaFile" name="minutaFile" class="form-control-file"
                                                                    style="display: none" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                                                <div id="minutaDropzone" class="minuta-dropzone w-100" role="button" tabindex="0"
                                                                    aria-label="Subir minuta">
                                                                    <div class="minuta-dropzone-title">Arrastra y suelta tu archivo aqui</div>
                                                                    <div class="minuta-dropzone-subtitle">o haz clic para seleccionarlo</div>
                                                                    <label id="minutaLlamada" class="form-input mb-0"></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="archivoGuardadoDiv" style="display: none">
                                                            <a id="archivoGuardadoLink" class="archivo-guardado-card" href="#" download>
                                                                <span id="archivoGuardadoIcono" class="archivo-guardado-icono">
                                                                    <i class="far fa-file"></i>
                                                                </span>
                                                                <span class="archivo-guardado-info">
                                                                    <span class="archivo-guardado-etiqueta">Archivo guardado</span>
                                                                    <strong id="archivoGuardado" class="archivo-guardado-nombre"></strong>
                                                                    <span id="archivoGuardadoMeta" class="archivo-guardado-meta"></span>
                                                                </span>
                                                                <span class="archivo-guardado-accion">
                                                                    <i class="fas fa-download"></i>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mb-1 mt-3">
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

<style>
    .minuta-dropzone {
        border: 2px dashed #6c757d;
        border-radius: 6px;
        padding: 14px 12px;
        text-align: center;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: all .15s ease-in-out;
    }

    .minuta-dropzone:hover,
    .minuta-dropzone:focus,
    .minuta-dropzone.is-dragover {
        border-color: #007bff;
        background-color: #eef6ff;
        outline: none;
    }

    .minuta-dropzone-title {
        font-size: 13px;
        font-weight: 600;
        color: #495057;
    }

    .minuta-dropzone-subtitle {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 6px;
    }

    #minutaLlamada {
        display: block;
        font-size: 12px;
        color: #007bff;
        word-break: break-all;
    }

    #archivoGuardadoDiv {
        margin-top: 10px;
    }

    .archivo-guardado-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border: 1px solid #dadce0;
        border-radius: 12px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 1px 2px rgba(60, 64, 67, 0.1);
        text-decoration: none;
        transition: transform .15s ease-in-out, box-shadow .15s ease-in-out, border-color .15s ease-in-out;
    }

    .archivo-guardado-card:hover,
    .archivo-guardado-card:focus {
        text-decoration: none;
        border-color: #8ab4f8;
        box-shadow: 0 4px 12px rgba(60, 64, 67, 0.16);
        transform: translateY(-1px);
        outline: none;
    }

    .archivo-guardado-icono {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background-color: #e8f0fe;
        color: #1a73e8;
        font-size: 18px;
        flex-shrink: 0;
    }

    .archivo-guardado-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
        flex: 1;
    }

    .archivo-guardado-etiqueta {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #5f6368;
        margin-bottom: 2px;
    }

    .archivo-guardado-nombre {
        color: #202124;
        font-size: 13px;
        line-height: 1.35;
        word-break: break-word;
    }

    .archivo-guardado-meta {
        color: #5f6368;
        font-size: 11px;
        margin-top: 3px;
    }

    .archivo-guardado-accion {
        color: #1a73e8;
        font-size: 16px;
        flex-shrink: 0;
    }
</style>

<script>
    (function() {
        var dropzone = document.getElementById('minutaDropzone');
        var input = document.getElementById('minutaFile');
        var fileLabel = document.getElementById('minutaLlamada');

        if (!dropzone || !input || !fileLabel || dropzone.dataset.bound === '1') {
            return;
        }

        dropzone.dataset.bound = '1';

        function updateLabel() {
            fileLabel.textContent = input.files && input.files.length ? input.files[0].name : '';
        }

        function assignFiles(files) {
            if (!files || !files.length) {
                return;
            }

            var dataTransfer = new DataTransfer();
            dataTransfer.items.add(files[0]);
            input.files = dataTransfer.files;
            updateLabel();
        }

        dropzone.addEventListener('click', function() {
            input.click();
        });

        dropzone.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                input.click();
            }
        });

        input.addEventListener('change', updateLabel);

        ['dragenter', 'dragover'].forEach(function(evtName) {
            dropzone.addEventListener(evtName, function(event) {
                event.preventDefault();
                event.stopPropagation();
                dropzone.classList.add('is-dragover');
            });
        });

        ['dragleave', 'dragend', 'drop'].forEach(function(evtName) {
            dropzone.addEventListener(evtName, function(event) {
                event.preventDefault();
                event.stopPropagation();
                dropzone.classList.remove('is-dragover');
            });
        });

        dropzone.addEventListener('drop', function(event) {
            assignFiles(event.dataTransfer.files);
        });
    })();
</script>
