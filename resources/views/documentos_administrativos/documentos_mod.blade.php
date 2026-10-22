<div class="modal fade" id="modal-ver-documentos" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
    aria-labelledby="myModalLabel">
    @include('partials.loadingModal')
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="post" class="form-horizontal" data-toggle="validator">
                <div class="modal-body">
                    <div class="col-12">
                        <input type="hidden" id="idClienteINT" name="idClienteINT">
                        <input type="hidden" id="idPedido">
                        <div class="card-body">
                            <div class="rounded bg-gray">
                                <div class="row mb-2 mt-1">
                                    <div class="col-sm-12">
                                        <h6 class="text-center text-bold pt-2 titulo" style="cursor: move">Documentos
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded mb-1">
                                <table id="tblRequisitosCliente" class="table table-hover table-striped nowrap"
                                    style="border-radius: .5rem;">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th class="w-25 text-center align-middle"></th>
                                            <th class="w-25 text-center align-middle">Entrega (Remisión)</th>
                                            <th class="w-25 text-center align-middle">Revisión (Factura)</th>
                                            <th class="w-25 text-center align-middle">CXC</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-contactos">
                    <div class="text-center input-group-append col-md-12">
                        <span class="col-md-12">
                            <button type="button" class="rounded-lg btn btn-secondary btn-sm btn-block"
                                data-dismiss="modal">Regresar</button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
