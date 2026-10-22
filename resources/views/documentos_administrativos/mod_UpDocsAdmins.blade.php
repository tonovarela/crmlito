<div class="modal fade" id="mod_UpDocsAdmins" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documentos Pedido</h4>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="FileDocAdmins" class="name-doc">Nombre del Documento</label>
                    <input id="idTipoDocumento" name="idDocsAdmins" type="text" hidden>
                    <input id="tipoDocumento" name="tipoDocsAdmins" type="text" hidden>
                    <input id="idCliente" name="idClienteDocsAdmins" type="text" hidden>
                    <input id="idPedido" name="idPedidoDocsAdmins" type="text" hidden>
                    <input id="idDocumentosAsignados" name="idDocumentosAsignadosDocsAdmins" type="text" hidden>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileDocAdmins" name="FileDocAdmins"
                                accept="application/pdf, application/msword,
                            application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
                            application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, 
                            application/vnd.openxmlformats-officedocument.presentationml.presentation, 
                            image/png, image/jpeg, 
                            application/vnd.ms-excel, text/plain, text/xml" required>
                            <label class="custom-file-label lblDocsUp" for="FileDocAdmins">No se eligió archivo</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success" id="btnSaveDocsAdmins">Subir</button>
                        </div>
                    </div>
                    <div class="uploadExtensionError text-red text-bold text-md" style="display: none">¡Archivo no
                        permitido!</div>
                </div>


            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-default " data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>