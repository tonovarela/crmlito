<!-- Donut chart -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="far fa-chart-bar"></i> Oportunidades por probabilidad
        </h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button> -->
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8" style="padding-bottom: 5%">
                <div id="donut-chart" style="height: 15rem;"></div>
            </div>

            <div class="col-md-4">
                <ul class="chart-legend clearfix" id="pie-label1" style="  font-size: 1rem;margin-top: 10%;">
                <li><i class='far fa-circle' style='color:#F98370'></i> 25% Probabilidad</li>
                <li><i class='far fa-circle' style='color:#FDD17F'></i> 50% Probabilidad</li>
                <li><i class='far fa-circle' style='color:#C1DA81'></i> 75%  Probabilidad</li>
                <li><i class='far fa-circle' style='color:#63BE7B'></i> 100% Probabilidad</li>    
            </ul>
            </div>
        </div>
    </div>

    <!-- /.card-body-->
</div>
<!-- /.card -->
<script src=" {{ asset('js/dashboards_probabilidad.js?n=1') }} " defer></script>
<script src=" {{ asset('adminlte/plugins/flot/jquery.flot.js') }} " defer></script>
<script src=" {{ asset('adminlte/plugins/flot/plugins/jquery.flot.resize.js') }} " defer></script>
<script src=" {{ asset('adminlte/plugins/flot/plugins/jquery.flot.pie.js') }} " defer></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.0/dist/Chart.min.js"></script> --}}
<script src="{{ asset('js/chartjs-plugin-doughnutlabel.js') }} "></script>