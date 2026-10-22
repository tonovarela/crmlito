<?php

if (!isset($_GET['usuario'])) {
    $_SESSION['Usuario'] = '';
} else {
    $_SESSION['Usuario'] = $_GET['usuario'];
}
?>
    @extends('layout.master') @section('content')

    <!-- Content Header (Page header) -->
    <style type="text/css">
        /* Chart.js */
        
        @keyframes chartjs-render-animation {
            from {
                opacity: .99
            }
            to {
                opacity: 1
            }
        }
        
        .chartjs-render-monitor {
            animation: chartjs-render-animation 1ms
        }
        
        .chartjs-size-monitor,
        .chartjs-size-monitor-expand,
        .chartjs-size-monitor-shrink {
            position: absolute;
            direction: ltr;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
            visibility: hidden;
            z-index: -1
        }
        
        .chartjs-size-monitor-expand>div {
            position: absolute;
            width: 1000000px;
            height: 1000000px;
            left: 0;
            top: 0
        }
        
        .chartjs-size-monitor-shrink>div {
            position: absolute;
            width: 200%;
            height: 200%;
            left: 0;
            top: 0
        }
    </style>
    <input type="hidden" name="usuario" value="<?php echo $_SESSION['Usuario']; ?>">
    <input type="hidden" name="user" value="{{ $datos_usuario[0]->Login }}">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <?php setlocale(LC_TIME, "spanish");echo ucfirst(strftime("%B"));  ?> </h1>
                </div>
                <!-- /.col -->
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div> -->
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- body Dashboard por Perfil, Falta agregar permisos -->
    <section class="content">
        @foreach ($permisos as $PermisosModulos) @if ($PermisosModulos->idModulo==7)
        <div class="col-12">
            @component('sections.dashboard.cards_Estatus', ['my_array' => $cards]) @endcomponent
        </div>
        @endif @endforeach

        <div class="row col-12">
            <div class="row col-sm-6" id="dash-eficiencia">
                @foreach ($permisos as $PermisosModulos) @if ($PermisosModulos->idModulo==8)
                <!-- Eficiencia -->
                @component('sections.dashboard.cards_Eficiencia', ['my_array' => $Eficiencia]) @endcomponent @endif @endforeach @foreach ($permisos as $PermisosModulos) @if ($PermisosModulos->idModulo==9)
                <!-- LISTA de oportunidades por atender -->
                @component('sections.dashboard.cards_List', ['my_array' => $List]) @endcomponent @endif @endforeach

            </div>
            <div class="row col-sm-6" id="dash-tiempo">
                @foreach ($permisos as $PermisosModulos) @if ($PermisosModulos->idModulo==10)
                <!-- Tiempos -->
                @component('sections.dashboard.cards_Tiempos', ['my_array' => $cards]) @endcomponent @endif @endforeach @foreach ($permisos as $PermisosModulos) @if ($PermisosModulos->idModulo==11)
                <!-- Oportuniades por Semana -->
                <br> @component('sections.dashboard.cards_Semana') @endcomponent @endif @endforeach


            </div>
        </div>

    </section>

    <!-- /.End body Dashboards -->

    @endsection @section('scripts') @endsection