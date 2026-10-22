<!-- LINE CHART -->
<div class="col-12">
    <div class="card " style="height: 18rem;">
        <div class="card-header">
            <h3 class="card-title">Ventas</h3>

            <div class="card-tools tools-dashboard">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button> -->
            </div>
        </div>
        <div class="card-body">
            <div class="row col-s12">
                <div style="width: 4%"></div>
                <div style="width: 42%">
                    <div class="chart" id='linechars'>
                        <canvas id="lineChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        
                    </div>
                </div>
                <div style="width: 8%"></div>
                <div style="width: 42%">
                    <div class="chart" id='chartAcumulado'>
                        <canvas id="lineChartAcomulado"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <div style="width: 4%"></div>

            </div>

        </div>
        <!-- /.card-body -->
    </div>
</div>

<!-- /.card -->
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/chartjs-plugin-doughnutlabel.js') }} "></script>
<script src=" {{ asset('js/dashboards_VtasVsMeta.js?n=1') }} " defer></script>
