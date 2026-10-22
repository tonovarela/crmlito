<div class="modal fade" id="mod_UpDocsEnv">
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
                    <input id="idOportunidadEnv" name="idOportunidadEnv" type="text" hidden>
                    <label for="FileDocEnv" class="name-doc">Nombre del Documento</label><input id="idTipoDocumento" name="idDocs" type="text" hidden>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileDocEnv" name="FileDocEnv" accept="application/pdf">
                            <label class="custom-file-label lblDocsUp" for="FileDocEnv">No se eligió archivo</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-success" id="btnSaveDocsEnv">Subir</button>
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