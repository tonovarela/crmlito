<div class="row col-sm-12 text-center ml-1 mt-1">
    <div class="col-sm-2">
        <div class="card card_box p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <i class="fas fa-chart-line fa-2x bg-info p-2 rounded"></i>
                    </div>
                    <div class="ml-1">
                        <span style="vertical-align: middle">Hit Rate</span>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <span class="text-bold" id='hit'></span><small class="text-bold" style="padding-top: 7px; margin-left: 3px">%</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card_box p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <i class="fas fa-chart-pie fa-2x bg-danger p-2 rounded"></i>
                    </div>
                    <div class="ml-1">
                        <span style="vertical-align: middle;">Cump. Venta Vs Meta Anual</span>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <span class="text-bold" id='cumplimiento'></span><small class="text-bold" style="padding-top: 7px; margin-left: 3px">%</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card_box p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <i class="fas fa-shopping-cart fa-2x bg-primary p-2 rounded"></i>
                    </div>
                    <div class="ml-1">
                        <span>Venta del Mes</span>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <span class="text-bold" id='venta'>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card_box p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <i class="fas fa-file-invoice-dollar fa-2x bg-teal p-2 rounded"></i>
                    </div>
                    <div class="ml-1">
                        <span>Oportunidades del Mes</span>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <span class="text-bold" id='oportunidades'>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card_box p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <i class="fas fa-users fa-2x bg-warning p-2 rounded"></i>
                    </div>
                    <div class="ml-1">
                        <span>Nuevos Clientes</span>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <span class="text-bold" id='clientes'>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card card_box p-2">
            <div class="card-body">
                <div class="d-flex">
                    <div>
                        <i class="fas fa-medal fa-2x bg-success p-2 rounded"></i>
                    </div>
                    <div class="ml-1">
                        <span>Meta del Mes</span>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <span class="text-bold" id='meta'>
                </div>
            </div>
        </div>
    </div>
</div>
<script src=" {{ asset('js/dashboards_boxVentas.js?n=2') }} " defer></script>
