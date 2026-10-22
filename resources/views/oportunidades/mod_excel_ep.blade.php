{{--  <script src="https://cdn.jsdelivr.net/npm/jspreadsheet-ce@5/dist/index.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jspreadsheet-ce@5/dist/jspreadsheet.min.css" type="text/css" />
<script src="https://cdn.jsdelivr.net/npm/jsuites/dist/jsuites.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsuites/dist/jsuites.min.css" type="text/css" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons" />  --}}

<div class="modal fade" id="mod_multipresupuesto_excel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Multi-elementos para presupuesto (Excel)</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{--  <div class="modal-body centered-cells">
                <div id="excelSheet"></div>
            </div>  --}}

            <div class="modal-body">
                <div class="form-group">
                    <div class="custom-file" id="inputFileDocAddP">
                        <input type="file" class="custom-file-input" id="FileDocAddP" multiple accept="image/jpeg,image/png">
                        <label class="custom-file-label" for="FileDocAddP" id="labelFileDocAddP">Elegir archivo</label>
                    </div>
                    <small id="helpFileDocAddP" class="form-text text-muted">
                        Las imagenes se acomodarán de acuerdo al orden en que se seleccionen.
                    </small>
                    {{--  <div class="custom-control custom-checkbox mt-2" id="seccion-sol-prepedido">
                        <input type="checkbox" class="custom-control-input" id="solicitarPrepedidoP">
                        <label class="custom-control-label mt-2 pt-1" for="solicitarPrepedidoP">Solicitar Pre-pedido</label>
                    </div>  --}}
                    <div id="previewArchivoP" class="preview-container"></div>
                </div>

                <div class="form-group">
                    <div>
                        <label for="valorOport">Valor de la oportunidad:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="valorOportAddon" style="padding: 0 .375rem !important;">$</span>
                            </div>
                            <input type="text" class="form-control" id="valorOport" name="valorOport" inputmode="decimal" autocomplete="off" aria-describedby="valorOportAddon" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer ">
                <button type="button" class="btn btn-success" id="guardarMultipresupuestoExcelBtn" onclick="guardarMultipresupuestoExcel()">
                    Generar presupuesto
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="accionCerrarModalCargaMultiPres()">Cerrar</button>
            </div>
        </div>
    </div>
</div>