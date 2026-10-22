<div class="modal fade" id="mod_FirmaUsuarios">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gray">
                <h4 class="modal-title">Imagen</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id_usuario" id="id_usuario">
                <div class="form-group">
                    <label for="FileDoc" class="name-doc">Nombre de la Imagen</label><input id="idTipoDocumento" name="idCteDocs" type="text" hidden>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileFirma" name="FileFirma" accept="image/*">
                            <label class="custom-file-label lblDocsUp" id="FileDocLabel" for="FileDoc">No se eligió archivo</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary" id="btnSaveAddFirma">Subir</button>
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