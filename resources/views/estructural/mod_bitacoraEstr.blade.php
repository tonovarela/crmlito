<div class="modal fade" id="mod_BitacoraEstr" data-backdrop="static" tabindex="-1" role="dialog" data-keyboard="false"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @include('partials.loading')
            <div class="modal-header bg-gray" id="headerBitacora" style="cursor: move">
                <input type="hidden" id="folioBitacoraEstr">
                <h4 class="modal-title" id="titleBitacoraEstr"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="text-center pt-2" id="nombreTicketEstr"></h4>
                <h6 class="text-left ml-2 p-2 border-left-bitacora" id="descripcionTicketEstr"></h6>
                <table id="tblBitacoraEstr" class="table table-hover table-striped nowrap compact tblnombre">
                    <tfoot>
                        <tr class="bg-gray">
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($idrol != 1024)
            <div class="modal-footer" id="footerBitacora">
                    <input type="text" class="form-control text-uppercase" autocomplete="off" name="movimientoBitacoraEstr" id="movimientoBitacoraEstr" style="width: 86%" placeholder="Escribe una consulta aquí.">
                    <button type="button" class="btn btn-primary rounded-btn btn-md" id="btnSaveMovEstr" onclick="enviarMovimientoEstr()" disabled><i class="fas fa-paper-plane"></i> Enviar</button>
            </div>
            @endif
        </div>
    </div>
</div>
