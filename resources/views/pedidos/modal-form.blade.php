<div class="modal fade" id="mod_ChangeComision">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar comisión</h4>
            </div>
            <div class="modal-body">
                <div class="px-5 py-2">

                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Nombre cliente:</strong> <span id="nomCliente"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Trabajo:</strong> <span id="trabajo"></span></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Última fecha de actualización:</strong> <span id="ultFecActualizacion"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Importe del pedido:</strong> <span id="importePedido"></span></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Importe de comisión modificado:</strong> <span id="impComModificado"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Importe de comisión original:</strong> <span id="impComActual"></span></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Porcentaje de la comisión modificada:</strong> <span id="porcComMod"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Porcentaje de la comisión original:</strong> <span id="porcComOrg"></span></h6>
                        </div>
                    </div>
                    <div class="row d-none">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Retención de la comisión(%):</strong> <span id="retComPorc"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Importe de lo retenido:</strong> <span id="impRet"></span></h6>
                        </div>
                    </div>
                    <div class="row d-none">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Importe de la comisión sin la retención:</strong> <span id="impNoRet"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Fecha del pedido:</strong></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Fecha del pedido:</strong> <span id="fechaPedido"></span></h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <p><small>Nota* Al realizar el calculo ya te estara restando al total lo que es el porcentaje de la retención.</small></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <form id="formUpdateCom">
                            <div>
                                <input type="hidden" id="idPedidoIntellisis">
                                <input type="hidden" id="valorPedido">
                                <input type="hidden" id="retPorcentaje">
                                <input type="hidden" id="op">
                                <input type="hidden" id="pedido">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="impComision">Actualiza el valor del importe</label>
                                        <input type="number" class="form-control" id="impComision" name="impComision" value="0" onkeyup="calcComisionPar(this)">
                                        <div id="msg-errors-impComision" style="margin-top: 5px"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="porcComision">Actualiza el valor del porcentaje</label>
                                        <input type="number" class="form-control" id="porcComision" name="porcComision" value="0" onkeyup="calcComisionPar(this)">
                                        <div id="msg-errors-porcComision" style="margin-top: 5px"></div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label for="motivoActualizacion">Motivo de la actualización</label>
                                        <textarea class="form-control" id="motivoActualizacion" name="motivoActualizacion" cols="30" rows="10" placeholder="Ingresa el motivo..." style="height: 105px !important;"></textarea>
                                        <div id="msg-errors-motivoActualizacion" style="margin-top: 5px"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer ">
                <div id="spaceToBtnProd"></div>
                <button type="button" onclick="updateImporteComision()" class="btn btn-success">Actualizar comisión</button>
                <button type="button" class="btn btn-default " data-dismiss="modal" id="closeTableWs" onclick="closeModalPdd()">Cerrar</button>
            </div>
        </div>
    </div>

    <div class="loanding">
        <div id="circle">
            <div class="loader"></div>
        </div>
    </div>
    
    <style>
        .title-fc {
            padding: 2rem;
        }

        .px-5 {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }

        .py-2 {
            padding-top: 2rem !important;
            padding-bottom: 2rem !important;
        }

        .mb-2 {
            margin-bottom: 2rem !important;
        }

        .table-corr th,
        .table-corr td
        {
            padding: 0.25rem;
        }
    </style>
</div>