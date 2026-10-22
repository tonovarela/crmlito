<!-- Modal -->
<div class="modal fade" id="mod_DE_relation" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray">
                <input type="text" id="nameColumnDE" readonly hidden>
                <h5 class="modal-title" id="staticBackdropLabel">Relacionar Proyecto Diseño Estructural</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-md-center">
                    <h6 class="text-right text-bold pr-1 pt-2">Selecciona el Proyecto a relacionar con la oportunidad
                    </h6>
                    <h6 class="text-right text-bold text-red pr-1 pt-2">&nbsp;No. {{ $oportunidad[0]->folio }}
                        &nbsp;
                    </h6>
                </div>
                <div class="row justify-content-md-center">

                    <div class="col col-lg-11">
                        <table onclick="ajustarTablas()" id="tblTickeDERelation"
                            class="table table-hover table-striped nowrap compact">
                            <thead>
                                <tr class="bg-gray">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    {{-- <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th> --}}
                                </tr>
                            </thead>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-btn btn-sm" data-dismiss="modal"
                        id='cancelarDE'>Cancelar</button>
                    <button type="button" class="btn btn-primary rounded-btn btn-sm" id="btnRelacionarDE">Relacionar</button>
                </div>
            </div>
        </div>
    </div>
</div>
