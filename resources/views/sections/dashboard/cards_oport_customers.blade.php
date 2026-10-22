@include('modals.visorPDF')
<div class="row col-sm-12">
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i>
                Tripas Con Cambios
                <strong id="totalTripas" style="font-weight: bold">
                    {{-- &nbsp;&nbsp;&nbsp; ( {{ count($my_array) }} ) --}}
                </strong>
            </h3>

            <div class="card-tools">
                <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                </ul>
            </div>
        </div>
        <div class="card-body mt-1" style="padding: 0 10px !important; height: 25vh;">
            <table class="table compact nowrap tblnombre" id="tblTripasCCambios">
                <thead>
                    <tr class="bg-gray">
                        <th width="10%">FOLIO</th>
                        <th width="40%">OPORTUNIDAD</th>
                        <th width="10%">ESTATUS</th>
                        <th width="30%">TRIPA</th>
                        <th width="10%">REALIZADA</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
