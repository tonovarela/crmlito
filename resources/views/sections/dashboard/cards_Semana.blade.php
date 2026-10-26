<div class="row col-sm-12">
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <h3 class="card-title">Oportunidades por Semana</h3>

            <div class="card-tools tools-dashboard">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                              <i class="fas fa-minus"></i>
                            </button>
                <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                              <i class="fas fa-times"></i>
                            </button> -->
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="chart">
                <canvas id="stackedBarChart" style="min-height: 35vh; height: 35vh; max-height: 35vh; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
<script src=" {{ asset('js/dashboards_Semana.js?n=2') }} " defer></script>