<div class="modal fade" id="mod_UpDocs">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documentos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="FileDoc" class="name-doc">Nombre del Documento</label><input id="idTipoDocumento" name="idCteDocs" type="text" hidden>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileDoc" name="FileDoc">
                            <label class="custom-file-label lblDocsUp" for="FileDoc">No se eligió archivo</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success" id="btnSaveDocsCte">Subir</button>
                        </div>
                    </div>
                </div>


            </div>
             <div class="modal-footer "><!--justify-content-between -->
                <button type="button" class="btn btn-default " data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary" id="btnSaveDocsCte1">Guardar</button> -->
            </div>
        </div>
    </div>
</div>