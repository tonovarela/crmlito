<div class="modal fade" id="mod_ConfigIncidencias">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Configuración de incidencias</h4>
            </div>
            <div class="modal-body">
                <div class="px-5 py-2">
                    <div>
                        <form id="formAddNewAnticipo" method="POST">
                            {{ csrf_field() }} {{ method_field('POST') }}
                            <input type="hidden" name="recurrenciaVal" id="recurrenciaVal">
                            <input type="hidden" name="frecuenciaCatIncVal" id="frecuenciaCatIncVal">
                            <input type="hidden" name="tipoVal" id="tipoVal">
                            <div class="row">
                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="incidencia">Incidencia </label>
                                            <input type="text" class="form-control" id="incidencia" name="incidencia" style="padding-top: 10px; padding-bottom: 10px;">
                                        </div>
                                    </div>
                                    <div id="msg-errors-incidencia" style="margin-top: 5px;"></div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="recurrencia">Recurrencia </label>
                                            <div class="d-flex" >
                                                <button type="button" class="btn btn-secondary mr-2 restoreBtnFrm" onclick="markRecurrence(1)" id="recurrenciaSiBtn">Si</button>
                                                <button type="button" class="btn btn-secondary restoreBtnFrm" onclick="markRecurrence(2)" id="recurrenciaNoBtn">No</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="msg-errors-recurrencia" style="margin-top: 5px;"></div>
                                </div>

                                <div class="col-lg d-none" id="frequencyDiv">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="frecuenciaCatInc">Frecuencia </label>
                                            <div class="d-flex" >
                                                <button type="button" class="btn btn-secondary mr-2 restoreBtn restoreBtnFrm" onclick="markFrecuency(1)" id="frecuenciaSiBtn">Quincenal</button>
                                                <button type="button" class="btn btn-secondary restoreBtn restoreBtnFrm" onclick="markFrecuency(2)" id="frecuenciaNoBtn">Mensual</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="msg-errors-frecuenciaCatInc" style="margin-top: 5px;"></div>
                                </div>
    
                                <div class="col-lg d-none" id="typeDiv">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="tipo">Monto (Tipo) </label>
                                            <div class="d-flex" >
                                                <button type="button" class="btn btn-secondary mr-2 restoreBtn restoreBtnFrm" onclick="markTypeMonto(1)" id="tipoSiBtn">Fijo</button>
                                                <button type="button" class="btn btn-secondary restoreBtn restoreBtnFrm" onclick="markTypeMonto(2)" id="tipoNoBtn">Variable</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="msg-errors-tipo" style="margin-top: 5px;"></div>
                                </div>
                            </div>

                            <div class="row mt-4 d-none" id="contentCatMotIncVendedor">
                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="montoCatMotAnt">Monto </label>
                                            <input type="number" value="0" class="form-control" id="montoCatMotAnt" name="montoCatMotAnt" style="padding-top: 10px; padding-bottom: 10px;" oninput="this.value = Math.abs(this.value)">
                                        </div>
                                    </div>
                                    <div id="msg-errors-montoCatMotAnt" style="margin-top: 5px;"></div>
                                </div>
                            </div>

                            <div class="mx-auto mt-4 w-full d-flex ">
                                <button type="button" onclick="guardarCatIncidencia()" class="custom-btn ml-auto mr-2" id="btnCatInc">Guardar</button>
                                <button type="button" onclick="clearInputsConfigIncidencias()" class="custom-btn-cancel d-none" id="btnCatIncCancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{--  Tabla con el listado de anticipos registrados  --}}
                <div class="px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="pb-2" style="color: #6C757D;">Listado Incidencias</label>
                    </div>
                    <table id="tblCatMotivosIncidencias" class="table table-hover table-striped nowrap compact" style="width: 100% !important;">
                        <thead>
                            <tr class="bg-gray">
                                <th></th>
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
                <button type="button" class="btn btn-default " data-dismiss="modal" id="closeTableAtc" onclick="closeModalConfigIncidencias()">Cerrar</button>
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
        
        .custom-btn-cancel {
            display: block;
            padding: 0.375rem 0.75rem;
            background-color: #3b3b3b;
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