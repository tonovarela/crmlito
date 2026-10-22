<div class="modal fade" id="mod_UpDocsEstr" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-keyboard="false"  aria-modal="true">
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
                            <input type="file" class="custom-file-input" id="FileDocEstr" name="FileDocEstr">
                            <label class="custom-file-label lblDocsUp" for="FileDocEstr" style="padding-left: 12.5rem !important">No se eligió archivo</label>
                        </div>
                        {{-- <div class="input-group-append">
                        </div> --}}
                    </div>
                    
                </div>
                
                
            </div>
            <div class="modal-footer ">
                <!--justify-content-between -->
                {{-- <button type="button" class="btn btn-primary rounded-btn btn-md" id="btnSaveDocsEstr">Subir</button> --}}
                <button type="button" class="btn btn-primary rounded-btn btn-md" id="btnSaveDocsEstr">Subir</button>
                <button type="button" class="btn btn-secondary rounded-btn btn-md" id="closeModalEstrOport" data-dismiss="modal">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary" id="btnSaveDocsCte1">Guardar</button> -->
            </div>
        </div>
    </div>

    <style>
        .loanding {
            z-index: 9999;
        }

        .mod_UpDocsEstrOport {
            z-index: 1050;
        }
    </style>
</div>
