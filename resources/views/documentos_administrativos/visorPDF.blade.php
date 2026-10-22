<div class="modal fade" id="modal-visor" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
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
                                    <h6 class="text-center text-bold pt-2 tituloVisor" style="cursor: move">Visor
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="rounded mb-1">
                            <iframe id="frameVisor" width="100%" style="height: 74vh" allow="fullscreen"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-contactos">
                <div class="text-center input-group-append col-md-12">
                    <span class="col-md-6 offset-md-3">
                        <button type="button" class="rounded-lg btn btn-primary btn-sm btn-block"
                            data-dismiss="modal">Cerrar</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
