<div class="modal fade" id="modal-visor-documentos" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="modal-visor">
    {{--  @include('partials.loadingModal')  --}}
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <input type="hidden" id="idDocumento">
                <input type="hidden" id="fecAutoriza" name="fecAutoriza">
                <input type="hidden" id="idOportunidadPdf">
                <div class="col-12">
                    <div class="card-body">
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-center text-bold pt-2 tituloVisorDocumentos" style="cursor: move">
                                        Visor
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div id="bodyVisor" class="rounded mb-1" style="position: relative; width: 100%">
                            {{--  <div id="loading" style="position: absolute; left: 0px; top: 0px; width: 98%; height: 89%; margin-top: 55px"></div>  --}}
                            <div id="loading" {{-- style="position: absolute; left: 0px; top: 0px"> --}}
                                style="display: none;">
                            </div>
                            {{-- <embed id="frameVisorDocumentos" width="100%" style="height: 74vh"> --}}
                            <iframe id="frameVisorDocumentos" width="100%" style="height: 74vh"></iframe>
                            {{-- <iframe id="frameVisorDocumentos" width="100%" style="height: 74vh;"></iframe> --}}

                            <div id="frameVisorCorreos" style="display: none">
                                <div class="row mt-1">
                                    <div class="msg-oport col-lg-12">
                                        <div class="field-block" style="display: none">
                                            <input type="file" class="src-file" />
                                        </div>

                                        <div class="msg-info" style="display: none;">
                                            <div class="info-box wizard-msg" style="display: none">
                                                (<span class="msg-file-name"></span>)
                                            </div>


                                            <div class="border p-2 bg-white">
                                                <div class="field-block row mb-1">
                                                    <div class="field-label text-lg text-bold text-right col-lg-1">De
                                                    </div>
                                                    <div class="msg-from text-lg text-bold col-lg-11"></div>
                                                </div>
                                                <div class="field-block row mb-1">
                                                    <div class="field-label col-lg-1 text-right">Para</div>
                                                    <div class="msg-to col-lg-11"></div>
                                                </div>
                                                <div id="msg_cc" class="field-block row mb-1">
                                                    <div class="field-label col-lg-1 text-right">CC</div>
                                                    <div class="msg-cc col-lg-11"></div>
                                                </div>
                                                <div class="field-block row mb-1">
                                                    <div class="field-label col-lg-1 text-right">Fecha</div>
                                                    <div class="msg-date col-lg-11"></div>
                                                </div>
                                            </div>

                                            <div class="field-block bg-gray rounded-lg text-center"
                                                style="display: none">
                                                <div class="msg-subject text-bold text-lg"></div>
                                            </div>
                                            <div class="info-box mb-1 p-2" style="height: 40vh; overflow: auto">
                                                <div class="field-block row mb-2 ml-1">
                                                    <div class="msg-body text-bold p-1"></div>
                                                </div>
                                                <div class="field-block" style="display: none;">
                                                    <div class="msg-body-html text-bold p-1"></div>
                                                </div>
                                            </div>

                                            <div class="border p-2 bg-white">
                                                <div class="field-block">
                                                    <div class="msg-attachment"></div>
                                                </div>
                                            </div>

                                            <div class="border p-3 mb-1 bg-gray rounded-lg">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="incorrect-type info-box error-msg col-lg-12" style="display: none;">
                                        Tienes que seleccionar un archivo MSG
                                    </div>

                                    <div class="file-api-not-available info-box error-msg col-lg-12"
                                        style="display: none;">
                                        Tu navegador no es compatible
                                    </div>
                                </div>
                            </div>


                            {{-- <div id="nomenu"
                                style="position: absolute; left: 0px; top: 0px; width: 98%; height: 100%; visibility: hidden; z-index: 1000;">
                            </div> --}}

                            {{-- <iframe id="frameVisorOffice" style="width:100%; height:74vh;" frameborder="0"></iframe> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-contactos">
                <div class="text-center input-group-append col-md-12">

                    <div id="autorizarDiv" class="col-1 text-right"
                        style="margin-top: 5px; display: none; margin-right: -50px">
                        <input type="hidden" name="autorizar" id="autorizar">
                        <div class="d-flex"
                            style="width: 200px; justify-content: space-around; align-items: center;">
                            <div class="mr-2" style="display: flex; align-items: center;">
                                <input id="autorizarPres" name="autorizar" type="radio" class=""
                                    style="margin-right: 5px;" onclick="autorizarPresupuesto(1)">
                                <label id="autorizarLabel" class="" for="autorizarPres"
                                    style="margin-bottom: 0;">Autorizar</label>
                            </div>
                            <div class="mr-2" style="display: flex; align-items: center;">
                                <input id="rechazarPres" name="autorizar" type="radio" class=""
                                    style="margin-right: 5px;" onclick="autorizarPresupuesto(2)">
                                <label id="rechazarLabel" class="" for="rechazarPres"
                                    style="margin-bottom: 0;">Rechazar</label>
                            </div>
                        </div>
                    </div>


                    <span id="btnCerrarVisor" class="col-md-4 offset-md-1">
                        <button type="button" class="rounded-lg btn btn-primary btn-sm btn-block"
                            data-dismiss="modal">Cerrar</button>
                    </span>

                    <span id="btnAProduccion" class="col-md-4 offset-md-1" style="display: none">
                        <button type="button" onclick="produccionTripa()"
                            class="rounded-lg btn btn-success btn-sm btn-block">A Producción</button>
                    </span>

                    <div id="descargarImage" class="col-1 text-right" style="display: none; margin-right: -50px">
                        <button type="button" onclick="descargarImagen()" class="rounded-lg btn btn-success btn-sm"
                            title="Descargar"><i class="fas fa-download"></i></button>
                    </div>

                    <div id="descargarImageOport" class="col-1 text-right"
                        style="display: none; margin-right: -50px">
                        <button type="button" onclick="descargarImagenOport()"
                            class="rounded-lg btn btn-success btn-sm" title="Descargar"><i
                                class="fas fa-download"></i></button>
                    </div>

                    <div id="enviarCorreo" class="col-1 text-right" style="display: none; margin-right: -50px">
                        <button type="button" onclick="mailDocumentosOport()" class="btn btn-btn-sm bg-dark"
                            style="padding: 3px 10px; border-radius: 10px" title="Enviar por Correo"><i
                                class="fas fa-envelope"></i></button>
                    </div>
                    
                    <div id="notaRechazo" class="col-1 text-right" style="display: none; margin-right: -50px">
                        <button type="button" onclick="mostrarNotaRechazo()" class="btn btn-btn-sm bg-dark"
                            style="padding: 3px 10px; border-radius: 10px; display: none;" title="Mostrar Nota de Rechazo"><i
                                class="fas fa-sticky-note text-info"></i></button>
                    </div>

                    <div id="isCancelledDoc" class="canceling-cont" style="display: none">
                        {{-- <div id="isCancelledDocDiv1" class="cancelingModal" style="margin-top: -4rem">SIN</div> --}}
                        <div id="isCancelledDocDiv2" class="cancelingModal">SIN AUTORIZAR
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
