<div class="row col-sm-12">
    <div class="card" style="width: 100%;">
        <div class="card-header">
            <h3 class="card-title">Tiempo por Estatus</h3>

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
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-responsive">
                        <canvas id="pieChart" style="display: block;height: 178px;width: 400px;"></canvas>
                        <!-- height="150" -->
                    </div>
                    <!-- ./chart-responsive -->
                </div>
                <!-- /.col -->
                <div class="col-md-4">
                    <ul class="chart-legend clearfix" id="pie-label"> 
                    </ul>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer bg-light p-0">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                                <!-- United States of America
                                <span class="float-right text-danger">
                                  <i class="fas fa-arrow-down text-sm"></i>
                                  12%</span>-->
                              </a> 
                </li>
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                                India
                                <span class="float-right text-success">
                                  <i class="fas fa-arrow-up text-sm"></i> 4%
                                </span>
                              </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                                China
                                <span class="float-right text-warning">
                                  <i class="fas fa-arrow-left text-sm"></i> 0%
                                </span>
                              </a>
                </li> -->
            </ul>
        </div>
        <!-- /.footer -->
    </div>
</div>
<script src=" {{ asset('js/dashboards_Tiempos.js?n=1') }} " defer></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
<script src="{{ asset('js/chartjs-plugin-doughnutlabel.js') }} "></script>