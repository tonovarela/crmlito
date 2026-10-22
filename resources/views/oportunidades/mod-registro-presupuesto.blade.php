<div class="modal fade" id="modal_registro_presupuesto" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-keyboard="false"  aria-modal="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <div class="modal-header bg-gray"> <input type="text" id="nameColumn" readonly hidden>
                <h5 class="modal-title" id="staticBackdropLabel">Registro para presupuesto (<span id="tipoPresupuestoT"></span>)</h5>
            </div>
            <div class="modal-body modPapel">
                <div class="row justify-content-md-center">
                    <input type="hidden" value="{{ $oportunidad[0]->idOportunidad }}" id="idOportunidadCambiosTexto">
                    <div class="d-flex gap-3 w-full px-3">
                        <div class="form-group form-grp w-100">
                            <h6><strong>Nombre presupuesto: <span class="text-danger">*</span></strong></h6>
                            <input class="form-control" id="tituloPresupuesto" maxlength="500" placeholder="Escribe el nombre del presupuesto aquí...">
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3 p-3" style="max-height: 60vh; overflow-y: auto;">
                        <form action="#" id="formRegistroPresupuesto"></form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary rounded-btn btn-sm  mr-2" id="guardarPresupuesto" onclick="guardarPresupuesto()">
                        Generar presupuesto
                    </button>

                    <button type="button" class="btn btn-secondary rounded-btn btn-sm" data-dismiss="modal" id='cerrarCambiosTextos'>Cerrar</button>
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

        .form-grp {
            margin-bottom: 0 !important;
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
    </style>
</div>