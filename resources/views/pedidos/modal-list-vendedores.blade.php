<div class="modal fade" id="mod_VendedoresIncidencia" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="modal-visor">
    @include('partials.loadingModal')
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12">
                    <div class="card-body">
                        <div class="rounded bg-gray">
                            <div class="row mb-2 mt-1">
                                <div class="col-sm-12">
                                    <h6 class="text-center text-bold pt-2 tituloVisorDocumentos" style="cursor: move">
                                        Listado de vendedores relacionados a la incidencia
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div>
                            {{--  Listado de los vendedores  --}}
                            <div id="listVendedoresContainer" class="space-x-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-contactos">
                <div class="text-center input-group-append col-md-12">
                    <span id="btnCerrarList" class="col-md-5 offset-md-3">
                        <button type="button" class="rounded-lg btn btn-primary btn-sm btn-block" data-dismiss="modal">Cerrar</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <style>
        #listVendedoresContainer {
            display: flex !important;
            flex-direction: column;
        }

        .text-band-list {
            color: #007bff;
            font-weight: 600;
            background-color: #fff;
            padding: 0.25rem;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
            text-align: center;
            border: 1px solid #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .space-x-2 {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        .space-y-2 {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
    </style>
</div>

