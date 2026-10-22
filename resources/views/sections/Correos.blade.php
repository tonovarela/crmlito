<div class="row mt-1">
    <div class="msg-oport col-lg-12">
        {{-- <div class="info-box wizard-msg">
            1. Choose *.msg file...
        </div> --}}
        <div class="field-block">
            <input type="file" class="src-file" />
        </div>
    
        <div class="msg-info" style="display: none;">
            <div class="info-box wizard-msg">
                (<span class="msg-file-name"></span>)
            </div>
    
            <div class="field-block bg-gray rounded-lg text-center">
                {{-- <div class="field-label">Subject</div> --}}
                <div class="msg-subject text-bold text-lg"></div>
            </div>
    
            <div class="border p-2 bg-white">
                <div class="field-block row mb-1">
                    <div class="field-label text-lg text-bold text-right col-lg-1">De</div>
                    <div class="msg-from text-lg text-bold col-lg-11"></div>
                </div>
                <div class="field-block row mb-1">
                    <div class="field-label col-lg-1 text-right">Para</div>
                    <div class="msg-to col-lg-11"></div>
                </div>
                <div class="field-block row mb-1">
                    <div class="field-label col-lg-1 text-right">Fecha</div>
                    <div class="msg-date col-lg-11"></div>
                </div>
            </div>
            
            
            
            <div class="info-box mb-1 p-2">
                <div class="field-block row mb-2 ml-1">
                    {{-- <div class="field-label">Body</div> --}}
                    <div class="msg-body text-bold p-1"></div>
                </div>
                <div class="field-block" style="display: none;">
                    {{-- <div class="field-label">HTML Body</div> --}}
                    <div class="msg-body-html text-bold p-1"></div>
                </div>
            </div>


            <div class="border p-2 bg-white">
                <div class="field-block">
                    {{-- <div class="field-label">Adjuntos</div> --}}
                    <div class="msg-attachment"></div>
                </div>
            </div>

            <div class="border p-3 bg-gray rounded-lg">
            </div>

        </div>
    </div>
    
    <div class="incorrect-type info-box error-msg col-lg-12" style="display: none;">
        Tienes que seleccionar un archivo MSG
    </div>
    
    <div class="file-api-not-available info-box error-msg col-lg-12" style="display: none;">
        Tu navegador no es compatible
    </div>
</div>