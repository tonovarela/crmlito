<div class="col-12">
    <div class="card" style="box-shadow: none !important">
        {{-- <div class="card-header">
            <h3 class="card-title">Agenda</h3>

            <div class="card-tools tools-dashboard">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
            </div>
        </div> --}}
        <div class="card-body">
            <div class="row mb-2 col-s6">
                <div class="col-sm-12">
                    <div class="chart p-2 m-1" id='linecharsAgendaLlamada' style="border: solid 1px #80A7CD; border-radius: 10px">
                        <canvas id="lineChartAgendaLlamada" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="row mb-1 col-s6">
                <div class="col-sm-12">
                    <div class="chart p-2 m-1" id='linecharsAgendaReunion' style="border: solid 1px #4D6882; border-radius: 10px">
                        <canvas id="lineChartAgendaReunion" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <script src="{{ asset('js/Chart.min.js') }}"></script> --}}
{{-- <script src="{{ asset('js/chartjs-plugin-doughnutlabel.js') }} "></script> --}}
<script src=" {{ asset('js/dashboards_LlamadasMes.js?n=1') }} " defer></script>