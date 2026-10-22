<div class="modal fade" id="mod_UpDocsEstrOport">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gray">
                <h4 class="modal-title">Documentos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="FileDocEstr" class="name-doc">Nombre del Documento</label><input id="idTipoDocumentoEstr" name="idEstrDocs" type="text" hidden>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileDocEstrOport" name="FileDocEstrOport">
                            <label class="custom-file-label lblDocsUp" for="FileDocEstrOport" style="padding-left: 12.5rem !important">No se eligió archivo</label>
                        </div>
                    </div>
                    
                </div>
                
                
            </div>
            <div class="modal-footer ">
                <!--justify-content-between -->
                {{-- <button type="button" class="btn btn-primary rounded-btn btn-md" id="btnSaveDocsEstr">Subir</button> --}}
                <button type="button" class="btn btn-primary rounded-btn btn-md" onclick="addDocumentoEstrOport()">Subir</button>
                <button type="button" class="btn btn-secondary rounded-btn btn-md" data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary" id="btnSaveDocsCte1">Guardar</button> -->
            </div>
        </div>
    </div>
</div>
