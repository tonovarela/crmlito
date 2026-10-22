<div class="modal fade" id="mod_Anticipos">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">A cuenta del pedido <span id="tituloModalAnticipo"></span> </h4>
            </div>
            <div class="modal-body">
                <div class="px-5 py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        
                        <input type="hidden" id="importeComision">
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Cantidad total:</strong>$ <span id="cantGeneral"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Cantidad retenida </strong>$ <span id="cantRetenida"></span></h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md">
                            <h6 class="mb-2"><strong>Porcentaje retención:</strong> <span id="porcRet"></span></h6>
                        </div>
                        <div class="col-md">
                            <h6 style="margin-bottom: 1.5rem;"><strong>Saldo máximo para solicitar un acuenta de:</strong>$ <span id="saldoFinalLbl"></span></h6>
                        </div>
                    </div>

                    @if ($tipoUsarioAccede == 5 || $tipoUsarioAccede == 1 || $tipoUsarioAccede == 2)
                    <div>
                        <form id="formAddNewAnticipo" method="POST">
                            {{ csrf_field() }} {{ method_field('POST') }}
                            <div>
                                <input type="hidden" id="idPedidoIntellisisB">
                                <input type="hidden" id="opB">
                                <input type="hidden" id="pedidoB">
                                <input type="hidden" id="idComisionCandy">
                                <div>
                                    <div class="d-custom-f">
                                        <div style="width: 100%">
                                            <div style="width: 90%;">
                                                <label for="anticipo">Ingresa el valor a cuenta de comisión </label>
                                                <input type="number" class="form-control" id="anticipo" name="anticipo" value="0" style="padding-top: 10px; padding-bottom: 10px;">
                                                <div id="msg-errors-anticipo" style="margin-top: 5px;"></div>
                                            </div>
                                            <div style="width: 90%; margin-top: 1rem;">
                                                <label for="motivoA">Ingresa motivo </label>
                                                <textarea class="form-control" id="motivoA" rows="5" name="motivoA" style="padding-top: 10px; padding-bottom: 10px;">
                                                </textarea>
                                                <div id="msg-errors-motivoA" style="margin-top: 5px;"></div>
                                            </div>
                                        </div>
                                        <div class="mx-auto" style="display: flex; align-items: start; justify-content: space-between; flex-direction: column; height: 130px;">
                                            <div>
                                                <p style="color: #6C757D;">Cantidad que se puede poner "A cuenta de": $ <span id="restanteCant"></span></p>
                                            </div>

                                            <button type="button" onclick="crearNuevoAnticipo()" id="btnAddNewAnticipo" class="custom-btn">Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

                {{--  Tabla con el listado de anticipos registrados  --}}
                <div class="px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="pb-2" style="color: #6C757D;">Listado de registros</label>
                        
                        <div class="" style="color: #6C757D;"><p>Total general de registros a cuenta de comisión: <span id="totalGeneralAnticipos" style="color: #1f75c0;"></span></p></div>
                    </div>
                    <table id="tblAnticiposRegistrados" class="table table-hover table-striped nowrap compact" style="width: 100% !important;">
                        <thead>
                            <tr class="bg-gray">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer ">
                <div id="spaceToBtnProd"></div>
                <button type="button" class="btn btn-default " data-dismiss="modal" id="closeTableAtc" onclick="closeModalAnticipos()">Cerrar</button>
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

        .d-custom-f {
            display: flex;
            align-items: end;
        }

        .custom-btn {
            display: block;
            padding: 0.375rem 0.75rem;
            background-color: #28a745;
            color: #fff;
            border-radius: 20px;
            font-size: 14px;
            border: 0;
        }

        .custom-btn:focus {
            outline: none;
        }
    </style>
</div>