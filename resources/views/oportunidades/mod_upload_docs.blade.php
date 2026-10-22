<div class="modal fade" id="mod_carga_archivos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documentos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="FileDocAdd" class="name-doc font-weight-bold mb-2">Nombre del Documento <span class="text-danger">*</span></label>
                    <input id="idTipoDocumentoCandy" name="idOportDocs" type="text" hidden>
                    <div class="custom-file" id="inputFileDocAdd">
                        <input type="file" class="custom-file-input" id="FileDocAdd">
                        <label class="custom-file-label" for="FileDocAdd" id="labelFileDocAdd">Elegir archivo</label>
                    </div>
                    {{--  <div class="custom-control custom-checkbox mt-2" id="seccion-sol-prepedido">
                        <input type="checkbox" class="custom-control-input" id="solicitarPrepedido">
                        <label class="custom-control-label mt-2 pt-1" for="solicitarPrepedido">Solicitar Pre-pedido</label>
                    </div>  --}}
                    <div id="previewArchivo" class="preview-container"></div>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-success" id="subirArchivoOportBtn" onclick="subirArchivoOport()">
                    <i class="fas fa-cloud-upload-alt mr-1"></i> Subir
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>