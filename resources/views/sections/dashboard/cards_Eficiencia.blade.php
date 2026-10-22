<div class="card" style="width: 98%;">

    <div class="card-header">
        <h3 class="card-title">Eficiencia Oportunidades</h3>

        <div class="card-tools tools-dashboard">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body" style="margin: 5px 0 5px 10px;">
        <br>
        <div class="row col-sm-12">
            <div class="col-lg-6">
                <div class="info-box mb-3 bg-warning">
                    <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Eficiencia Oportunidades</span>
                        <span class="info-box-number" id="porEficiencia">
                            <h3 id="porEficienciaTitulo">{{ $my_array[0]->Eficiencia }} % </h3>
                        </span>
                    </div>
                </div>
                <div class="info-box mb-3 bg-info">
                    <span class="info-box-icon"><i class="fas fa-file-invoice-dollar"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Oportunidades</span>
                        <span class="info-box-number">
                            {{-- <h3 id="totalOportunidades">{{ number_format($my_array[0]->TotalOpor) }}</h3> --}}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- /.info-box -->
                <div class="info-box mb-3 bg-success">
                    <span class="info-box-icon"><i class="fas fa-smile"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Oportunidades a tiempo</span>
                        <span class="info-box-number" id="oporAtiempo">
                            {{-- <h3 id="oportAtiempoTitulo">{{ number_format($my_array[0]->Atiempo) }}</h3> --}}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box mb-3 bg-danger">
                    <span class="info-box-icon"><i class="fas fa-frown"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Oportunidades Atrasadas</span>
                        <span class="info-box-number" id="oporNotiempo">
                            {{-- <h3 id="oporNotiempoTitulo">{{ number_format($my_array[0]->Atrasadas) }}</h3> --}}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>

            </div>
        </div>
        <br>
    </div>
</div>
