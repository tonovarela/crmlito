<!-- Modal -->
<div class="modal fade" id="mod_Presupuesto_relacion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-keyboard="false" aria-modal="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray"> <input type="text" id="nameColumn" readonly hidden>
                <h5 class="modal-title" id="staticBackdropLabel">Agregar N° Presupuesto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>
            <div class="modal-body modPapel">
                {{-- <div class="row justify-content-md-center">
                    <h6 class="text-right text-bold pr-1 pt-2">Selecciona el Elemento a relacionar con la oportunidad</h6>
                    <h6 class="text-right text-bold text-red pr-1 pt-2">&nbsp;No. {{ $oportunidad[0]->folio }} &nbsp;
                    </h6>
                </div> --}}
                <div class="row justify-content-md-center">
                    <div class="input-group p-2">
                        <input class="form-control ml-2" type="text" name="numPresupuesto" id="numPresupuesto" placeholder="N° Presupuesto">
                        <input class="form-control ml-2 mr-2" type="text" name="importe" id="importe" placeholder="Importe" readonly>
                        <button id="btnAddPresupuestoModal" class="btn btn-primary roended-btn btn-sm mr-2" onclick="agregarImporte()"><i class="fas fa-plus-circle"></i></button>
                    </div>
                    <input type="hidden" value="{{ $oportunidad[0]->idOportunidad }}" id="idOportunidadPresupuesto">
                    <div class="col-lg-12 mb-3 p-3">
                        <table onclick="ajustarTablas()" id="tblPresupuestosRelacion" class="table table-hover table-striped nowrap compact">
                            <thead>
                                <tr class="bg-gray">
                                    <th>N° Presupuesto</th>
                                    <th>Importe</th>
                                    <th>Aplica</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray">
                                    <th colspan="3"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-btn btn-sm" data-dismiss="modal" onclick="closeMPresupuestos()" id='cancelar'>Cerrar</button>
                    {{-- <button type="button" class="btn btn-primary rounded-btn btn-sm" id="btnRelacionar">Relacionar</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>