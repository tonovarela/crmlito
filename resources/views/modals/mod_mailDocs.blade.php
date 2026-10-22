<div class="modal fade" id="mod_mailDocs" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="modal-visor">
    @include('partials.loadingModal')
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header rounded bg-gray">
                <div class="col-12">
                    <h4 class="modal-title text-center">Enviar Documento</h4>
                </div>
            </div>
            <div class="modal-body">
                <form accept="multipart/form-data">
                    <input type="hidden" name="archivoAdjunto" id="archivoAdjunto">
                    <input type="hidden" name="Nombre" id="Nombre">
                    <input type="hidden" name="password" id="password">
                    <input type="hidden" name="id_oport" id="id_oport">
                    <input type="hidden" name="firma" id="firma">
                    <div class="p-3">
                        <div class="row mb-2">
                            <div class="col-12 input-group">
                                <label for="cuentaAgente" class="name-doc mr-2 mt-1">De</label>
                                <input type="text" name="cuentaAgente" id="cuentaAgente" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 input-group">
                                <label for="cuentapara" class="name-doc mr-2 mt-1">Para</label>
                                <input type="mail" name="cuentapara" id="cuentapara" class="form-control" required
                                    readonly>
                            </div>
                        </div>
                        <div id="divCC" class="row mb-2" style="display: none">
                            <div class="col-12 input-group">
                                <label for="cuentaccmail" class="name-doc mr-2 mt-2">CC</label>
                                <div id="divChkCC" style="display: flex;">
                                    @if ($cuentas_cc != null)
                                        @foreach ($cuentas_cc as $cuenta_cc)
                                            {{-- @if ($cuenta_cc->CorreoCC != $cuentapara) --}}
                                            <div class="custom-control custom-switch m-2">
                                                <input id="cuenta_cc_{{ $cuenta_cc->CorreoCC }}" name="cuenta_cc"
                                                    type="checkbox" class="custom-control-input cursor-pointer"
                                                    onclick="asignarCC('{{ $cuenta_cc->CorreoCC }}')">
                                                <label id="cuenta_ccLabel" class="custom-control-label pt-1"
                                                    for="cuenta_cc_{{ $cuenta_cc->CorreoCC }}"
                                                    style="font-size: 10px">{{ $cuenta_cc->NombreCC }}
                                                    ({{ $cuenta_cc->CorreoCC }})
                                                </label>
                                            </div>
                                            {{-- @endif --}}
                                        @endforeach
                                    @else
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 input-group">
                                <label for="cuentaccmail"
                                    class="name-doc mr-2 mt-1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <input type="text" name="cuentaccmail" id="cuentaccmail" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 input-group">
                                <label for="subjetDoc" class="name-doc mr-2 mt-1">Asunto</label>
                                <input type="text" name="subjetDoc" id="subjetDoc" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 input-group">
                                <label for="docAdjunto" class="name-doc mr-2 mt-1">Documento Adjunto</label>
                                <input type="text" name="docAdjunto" id="docAdjunto" class="form-control" readonly
                                    required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 input-group">
                                <label for="documentosAdicionales" class="name-doc mr-2 mt-1">Documentos
                                    Adicionales</label>
                                <input type="file" multiple name="documentosAdicionales" id="documentosAdicionales"
                                    class="form-control" readonly required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="cuerpoDoc" class="name-doc">Mensaje</label>
                                {{-- <textarea name="cuerpoDoc" id="cuerpoDoc2" hidden></textarea> --}}
                                <textarea id="cuerpoDoc" cols="30" rows="10" class="form-control" required></textarea>
                                {{-- <textarea name="cuerpoDoc" id="cuerpoDoc">
                                <p><span>&nbsp;</span></p>
                                <p><strong><span style="font-size: 12pt; color: #001e82;">Saludos.</span></strong></p>
                            </textarea> --}}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer ">
                <!--justify-content-between -->
                <button type="button" class="btn btn-default " data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="enviarDocumento()"
                    id="btnSendDocs">Enviar</button>
            </div>
        </div>
    </div>
</div>
