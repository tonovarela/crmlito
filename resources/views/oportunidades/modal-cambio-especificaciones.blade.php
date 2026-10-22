<div class="modal fade" id="modal_cambio_especificaciones" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-keyboard="false"  aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header bg-gray"> <input type="text" id="nameColumn" readonly hidden>
                <h5 class="modal-title" id="staticBackdropLabel">Cambio de especificaciones</h5>
            </div>
            <div class="modal-body modPapel">
                <div class="row justify-content-md-center">
                    <div class="col-lg-12 p-3">
                        <table id="tblCambioEspecificaciones" class="table table-hover table-striped nowrap compact" style="width: 100% !important;">
                            <thead class="bg-gray" style="color: #fff !important; width: 100% !important;">
                                <tr>
                                    <td>Folio</td>
                                    <td>Nombre proyecto</td>
                                    <td>Categoría</td>
                                    <td>Tipo de Producto</td>
                                    <td>Realizado</td>
                                    <td>Creado</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-none col-lg-12" id="divCambioEspecificaciones">
                        <div class="px-3 py-2">
                            <p class="text-lg" style="text-align: left; margin-bottom: 0 !important;">
                                Solicitud de cambio de especificaciones del elemento: <span id="nombreElementoCE" style="color: #0082ce; font-weight: bold;"></span>
                            </p>
                        </div>
                        <div>
                            <input type="hidden" id="idElementoCEM">
                        </div>
                        <div class="px-3 py-2">
                            <label for="campoCambioEspecificaciones">Describe los cambios solicitados:</label>
                            <textarea class="form-control block" style="height: auto !important;" id="campoCambioEspecificaciones" rows="3" placeholder="Escribe aquí todos los cambios solicitados"></textarea>
                        </div>

                        <div class="px-3 py-2">
                            <iframe id="pdfViewerCambios" src="" width="100%" height="350px"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success rounded-btn btn-sm d-none" id="guardarCambioEspecificaciones" onclick="guardarCambioEspecificaciones()">Guardar Cambios</button>

                    <button type="button" class="btn btn-success rounded-btn btn-sm d-none" id="cerrarYAplicarCambioEspecificaciones" onclick="cerrarYAplicarCambioEspecificaciones()">Cerrar y Aplicar Cambios</button>

                    <button type="button" class="btn btn-primary rounded-btn btn-sm d-none" id="cancelarCambioEspecificaciones" onclick="cancelarCambioEspecificaciones(1)">Cancelar</button>

                    <button type="button" class="btn btn-secondary rounded-btn btn-sm" onclick="closeMElementos()" id='cerrarcambiosEP'>Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .heroicons--plus-circle-16-solid {
            display: inline-block;
            width: 34px;
            height: 34px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%2317A2B8' fill-rule='evenodd' d='M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14m.75-10.25v2.5h2.5a.75.75 0 0 1 0 1.5h-2.5v2.5a.75.75 0 0 1-1.5 0v-2.5h-2.5a.75.75 0 0 1 0-1.5h2.5v-2.5a.75.75 0 0 1 1.5 0' clip-rule='evenodd'/%3E%3C/svg%3E");
        }

        .content-d-form {
            display: flex;
            justify-content: space-between;
            align-items: end;
        }

        .custom-switch .custom-control-input:disabled:checked~.custom-control-label::before {
            padding-bottom: -2px;
        }

        .custom-control-input-mda {
            position: relative !important;
            padding-top: -2px;
            left: -6px !important;
            z-index: 1 !important;
            width: 2rem !important;
            cursor: pointer;
        }

        .content-static {
            display: flex;
            justify-content: center;
            align-items: start;
        }

        #tblCambiosSolicitados tr td:first-child {
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .dataTables_scrollBody {
            overflow-y: hidden !important;
        }

        .text-adap {
            color: #0082ce;
            width: 230px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .loanding {
            z-index: 9999;
        }
    </style>
</div>