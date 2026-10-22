<div class="modal fade" id="mod_NuevaIncidencia">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titleIncidencia">Nueva incidencia</h4>
                <input type="hidden" id="vendedorSeleccionado" name="vendedorSeleccionado" value="">
                <input type="hidden" id="vendedorSeleccionadoNombre" name="vendedorSeleccionadoNombre" value="">
            </div>

            <div class="row">
                <div class="col-lg">
                    <div class="containerDate">
                        <p class="mb-0"><strong class="text-secondary">Vendedor:</strong> <span id="vendedorNombre"></span></p>
                    </div>
                </div>
            </div>

            <div class="modal-body">
                <div class="px-5 py-2">
                    <div>
                        <form id="formAddNewAnticipo" method="POST">
                            {{ csrf_field() }} {{ method_field('POST') }}

                            <div class="row">
                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="incidenciaSelect">Selecciona la incidencia </label>
                                            <div id="incidenciaForm"></div>
                                        </div>
                                    </div>
                                    <div id="msg-incidenciaInput"></div>
                                </div>

                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="monto">Monto de la incidencia </label>
                                            <input type="number" accept="any" class="form-control" id="monto" name="monto" placeholder="Monto de la incidencia" required>
                                        </div>
                                    </div>
                                    <div id="msg-monto-incidencia"></div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="periodo">Quincena </label>
                                            <select class="form-control" id="selectQuincena" name="selectQuincena" required>
                                                <option value="">Selecciona la quincena</option>
                                                @foreach ($quincenasMajor2025 as $quincenMajor)
                                                    <option value="{{ $quincenMajor->QUINCENA }}">{{ $quincenMajor->QUINCENA }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="msg-quincena-incidencia"></div>
                                </div>
                            </div>

                            {{--  <div class="row mt-4">
                                <div class="col-lg">
                                    <div class="d-custom-f">
                                        <div style="width: 100%;">
                                            <label for="periodo">Fecha max de pago </label>
                                            <input type="date" accept="any" onchange="getQuincenaByDate()" class="form-control" id="periodo" name="periodo" placeholder="Periodo de la incidencia" required>
                                        </div>
                                    </div>
                                    <div id="msg-periodo-incidencia"></div>
                                </div>
                            </div>  --}}

                            <div class="mx-auto mt-4 w-full d-flex ">
                                <button type="button" onclick="saveIncidenciaFinal()" class="custom-btn ml-auto mr-2" id="btnSaveIncidenciaFinal">Guardar</button>
                                <button type="button" onclick="clearInputsUpdateIncidencias()" class="custom-btn-cancel d-none" id="btnSaveIncidenciaFinalCancelar">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{--  Tabla con el listado de incidencias relacionadas al vendedor  --}}
                <div class="px-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="pb-2" style="color: #6C757D;">Listado Incidencias (Vendedor)</label>
                    </div>
                    <table id="tblIncidenciasVendedor" class="table table-hover table-striped nowrap compact" style="width: 100% !important;">
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
                        <tbody>
                        </tbody>
                        <tfoot>
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
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-default " data-dismiss="modal" id="closeTableInc" onclick="closeModalIncidencias()">Cerrar</button>
            </div>
        </div>
    </div>

    <div class="loanding">
        <div id="circle">
            <div class="loader"></div>
        </div>
    </div>
    
    <style>

        .list-group label {
            padding: 0.375rem 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
            cursor: pointer;
        }

        .list-group label:hover {
            background-color: #e9ecef;
        }

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

        .list-group {
            display: flex;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
            height: 200px;
            overflow-y: scroll;
        }
        
        .list-group-f {
            width: 300px;
            display: flex;
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
            height: 200px;
            overflow-y: scroll;
        }

        .w-full {
            width: 100%;
        }

        .mr-8 {
            margin-right: 2rem;
        }

        .containerDate {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #f8f9fa;
            color: #495057;
            margin: 10px auto;
            width: 100%;
        }
    </style>
</div>