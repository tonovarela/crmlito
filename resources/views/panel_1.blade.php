<?php

if (!isset($_GET['usuario'])) {
    $_SESSION['Usuario'] = '';
} else {
    $_SESSION['Usuario'] = $_GET['usuario'];
}
?>
@extends('layout.master')
@section('content')

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
    <div class="card card-dashboard card-primary card-outline card-outline-tabs">
        @if ($Rol != 1014)
            <div class="row card-header p-0 border-bottom-0" style="padding-left: 0.5% !important;">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist" style="width: 80%;">

                    @foreach ($permisos as $PermisosModulos)
                        @if ($PermisosModulos->idModulo == 16)
                            <li class="nav-item" id="liDireccion" hidden>
                                <a class="dashboard nav-link " id="custom-tabs-four-home-tab" data-toggle="pill"
                                    href="#Dashboard-Direccion" role="tab" aria-controls="custom-tabs-four-home"
                                    aria-selected="false">DIRECCION</a>
                            </li>
                        @elseif ($PermisosModulos->idModulo == 13)
                            <li class="nav-item" id="liCotizaciones">
                                <a class="dashboard nav-link active" id="Dashboard_Cotizaciones-tab" data-toggle="pill"
                                    href="#Dashboard_Cotizaciones" role="tab" aria-controls="Dashboard_Cotizaciones"
                                    aria-selected="true">Cotizaciones</a>
                            </li>
                        @elseif ($PermisosModulos->idModulo == 14)
                            <li class="nav-item" id="liVentas">
                                <a class="dashboard nav-link" id="Dashboard_Ventas-tab" data-toggle="pill"
                                    href="#Dashboard_Ventas" onclick="mostrarVentas()" role="tab" aria-controls="Dashboard_Ventas"
                                    aria-selected="false">Ventas</a>
                            </li>
                        @elseif ($PermisosModulos->idModulo == 15)
                            <li class="nav-item" id="liAgenda">
                                <a class="dashboard nav-link" id="Dashboard_Agenda-tab" data-toggle="pill"
                                    href="#Dashboard_Agenda" onclick="mostrarAgenda()" role="tab"
                                    aria-controls="Dashboard_Agenda" aria-selected="false"> Agenda</a>
                            </li>
                        @endif
                    @endforeach
                    <li class="nav-item">
                        <a class="dashboard nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill"
                            href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings"
                            aria-selected="false" hidden>Settings</a>
                    </li>

                </ul>
                <ul class="nav nav-tabs" style="width: 20%;">
                    <li>
                        <h2 class="text-right" style="float:right;">
                            <?php setlocale(LC_TIME, 'spanish');
                            echo ucfirst(strftime('%B ')); ?>
                        </h2>
                    </li>
                </ul>
            </div>
        @endif
        <div class="card-body" style="padding-top: 1.25rem !important;">
            <div class="tab-content" id="custom-tabs-four-tabContent">


                @if ($Rol == 1014)
                    <div class="col-12 ">
                        @component('sections.dashboard.cards_sin_info')
                        @endcomponent
                    </div>
                @endif

                <div class="tab-pane fade" id="Dashboard-Direccion " role="tabpanel "
                    aria-labelledby="Dashboard-Direccion-tab" hidden>
                    <!--      ------------------------------------------- ----------------------------   DASHBOARDS DIRECCION       ----------------------------------------------------               -->
                </div>
                <!--      ------------------------------------------- ------------------------       DASHBOARDS COTINZACIONES/COSTUMERS----------------------------------------------------               -->
                @foreach ($permisos as $PermisosModulos)
                    @if ($PermisosModulos->idModulo == 13)
                        <div class="tab-pane fade show active" id="Dashboard_Cotizaciones" role="tabpanel"
                            aria-labelledby="Dashboard_Cotizaciones-tab">

                            <div class="col-12 mb-2">
                                @component('sections.dashboard.cards_Estatus', ['my_array' => $cards])
                                @endcomponent
                            </div>

                            <div id="graficasCotizaciones">

                                <div class="row col-12 " style="padding-left: 3%;">
                                    <div class="col-sm-6" id="dash-eficiencia " style="padding: 0% !important;">

                                        <!-- Eficiencia -->
                                        @component('sections.dashboard.cards_Eficiencia', ['my_array' => $Eficiencia])
                                        @endcomponent
                    @endif
                    @endforeach @foreach ($permisos as $PermisosModulos)
                        @if ($PermisosModulos->idModulo == 13)
                            <!-- LISTA de oportunidades por atender -->
                            @component('sections.dashboard.cards_List', ['my_array' => $List])
                            @endcomponent

            </div>
            <div class="col-sm-6" id="dash-tiempo " style="padding: 0% !important;">

                <!-- Tiempos -->
                @component('sections.dashboard.cards_Tiempos', ['my_array' => $cards])
                @endcomponent
                @endif
                @endforeach @foreach ($permisos as $PermisosModulos)
                    @if ($PermisosModulos->idModulo == 13)
                        <!-- Oportuniades por Semana -->
                        <br> @component('sections.dashboard.cards_Semana')
                        @endcomponent

            </div>
        </div>
    </div>

    </div>
@elseif ($PermisosModulos->idModulo == 14)
    <!-- <div class="tab-pane fade show active" id="Dashboard_Ventas" role="tabpanel" aria-labelledby="Dashboard_Ventas-tab"> -->

    <!--      ------------------------------------------- ------------------------       DASHBOARDS VENTAS----------------------------------------------------               -->
    @if ($Rol == 1 || $Rol == 7 || $Rol == 8)
        <div class="tab-pane fade" id="Dashboard_Ventas" role="tabpanel" aria-labelledby="Dashboard_Ventas-tab">
            <!--no cerrar este div aqui-->
            <div class="row col-12" style="height:3.5rem">
                <div class="col-sm-1" style="margin-top: 0.5rem;">
                    <h3 class="card-title text-center">Agente:</h3>
                </div>
                <div class="col-sm-6 ">
                    <select class="form-control select2 select2-hidden-accessible " style="width: 100%; "
                        data-select2-id="1 " tabindex="-1 " aria-hidden="true " id='sltAgentes'>
                        <option value="TODOS" disabled>Selecciona un Agente</option>
                        <option value="TODOS">TODOS LOS AGENTES</option>
                        @foreach ($Agentes as $Agente)
                            @if ($Agente->Id_Usuario == $datos_usuario[0]->Id_Usuario)
                                <option id="{{ $Agente->Id_Usuario }}" value="{{ $datos_usuario[0]->Id_Usuario }}"
                                    selected>
                                    {{ $Agente->Nombre }}</option>
                            @else
                                <option id="{{ $Agente->Id_Usuario }}" value="{{ $Agente->Id_Usuario }}">
                                    {{ $Agente->Nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <div class="tab-pane fade show active" id="Dashboard_Ventas" role="tabpanel"
                aria-labelledby="Dashboard_Ventas-tab">
                <!--no cerrar este div aqui-->
                <div class="row col-12" style="height:3.5rem" hidden>
                    <div class="col-sm-1" style="margin-top: 0.5rem;">
                        <h3 class="card-title text-center">Agente:</h3>
                    </div>
                    <div class="col-sm-6 ">
                        <select class="form-control select2 select2-hidden-accessible " style="width: 100%; "
                            data-select2-id="1 " tabindex="-1 " aria-hidden="true " id='sltAgentes'>
                            <option id="{{ $idEquipo_idAgente }}" value="{{ $idEquipo_idAgente }}" selected>
                            </option>

                        </select>
                    </div>
                </div>
    @endif

    <div class="col-12 mb-2">
        @component('sections.dashboard.cards_Estatus', ['my_array' => $cards])
        @endcomponent
    </div>

    {{-- <div class="col-12 mb-2">
        @component('sections.dashboard.cards_EstatusXagente')
        @endcomponent
    </div> --}}

    <div id="graficasVentas">
        <div class="row col-12 ">
            @component('sections.dashboard.cards_VtasVsMeta')
            @endcomponent
        </div><br>
        <div class="row col-12 ">
            <div class="col-sm-6 ">
                @component('sections.dashboard.cards_boxVentas')
                @endcomponent
            </div>
            <div class="col-sm-6 ">
                @include('sections.dashboard.cards_probabilidad')
            </div>
        </div>
    </div>

    </div>

        <!--      ------------------------------------------- ------------------------       DASHBOARDS AGENDA----------------------------------------------------               -->

    <div class="tab-pane fade" id="Dashboard_Agenda" role="tabpanel" aria-labelledby="Dashboard_Agenda-tab">
        @if ($Rol == 1 || $Rol == 7 || $Rol == 8)
            <div class="row col-12" style="height:3.5rem">
                <div class="col-sm-1" style="margin-top: 0.5rem;">
                    <h3 class="card-title text-center">Agente:</h3>
                </div>
                <div class="col-sm-6 ">
                    <select class="form-control" style="width: 100%; " tabindex="-1 " aria-hidden="true "
                        id='sltAgendaAgentes'>
                        <option value="TODOS" disabled>Selecciona un Agente</option>
                        <option value="TODOS">TODOS LOS AGENTES</option>
                        @foreach ($Agentes as $Agente)
                            @if ($Agente->Id_Usuario == $datos_usuario[0]->Id_Usuario)
                                <option id="{{ $Agente->Id_Usuario }}" value="{{ $datos_usuario[0]->Id_Usuario }}"
                                    selected>
                                    {{ $Agente->Nombre }}</option>
                            @else
                                <option id="{{ $Agente->Id_Usuario }}" value="{{ $Agente->Id_Usuario }}">
                                    {{ $Agente->Nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @else
            <div class="row col-12" style="height:3.5rem" hidden>
                <div class="col-sm-1" style="margin-top: 0.5rem;">
                    <h3 class="card-title text-center">Agente:</h3>
                </div>
                <div class="col-sm-6 ">
                    <select class="form-control" style="width: 100%; " tabindex="-1 " aria-hidden="true "
                        id='sltAgendaAgentes'>
                        <option id="{{ $idEquipo_idAgente }}" value="{{ $idEquipo_idAgente }}" selected>
                        </option>

                        {{-- @foreach ($Agentes as $Agente)
                            @if ($Agente->Id_Usuario == $datos_usuario[0]->Id_Usuario)
                                <option id="{{ $Agente->Id_Usuario }}" value="{{ $datos_usuario[0]->Id_Usuario }}"
                                    selected>
                                    {{ $Agente->Nombre }}</option>
                            @else
                                <option id="{{ $Agente->Id_Usuario }}" value="{{ $Agente->Id_Usuario }}">
                                    {{ $Agente->Nombre }}</option>
                            @endif
                        @endforeach --}}
                    </select>
                </div>
            </div>
        @endif
        {{-- ==========================Cards============================= --}}
        <div class="row col-12">
            <div class="col-6">
                @component('sections.dashboard.cards_Llamadas')
                @endcomponent
            </div>
            <div class="col-6">
                @component('sections.dashboard.cards_Reuniones')
                @endcomponent
            </div>
        </div>

        <div id="graficasAgenda">
            {{-- =========================Graficas=========================== --}}
            <div class="row">
                <div class="col-4 mt-2">

                    <div class="row">
                        <div class="col-12 ">
                            @component('sections.dashboard.cards_LlamadasMes')
                            @endcomponent
                        </div>
                    </div><br>

                    <div class="col-12" id="list_llamadas_reuniones">
                        @component('sections.dashboard.cards_List_Llamadas', ['my_array' => $List_Llamadas, 'id_usuario' => $id_usuario, 'rol' => $rol, 'agente_id' => $agente_id, 'combo_clientes' => $combo_clientes, 'estados' => $estados, 'direcciones' => $direcciones, 'tipoRepeticionLlamadas' => $tipoRepeticionLlamadas, 'horasRecordatorio' => $horasRecordatorio])
                        @endcomponent
                    </div>
                    <div class="col-12">
                        @component('sections.dashboard.cards_List_Reuniones', ['my_array' => $List_Reuniones, 'id_usuario' => $id_usuario, 'rol' => $rol, 'agente_id' => $agente_id, 'combo_clientes' => $combo_clientes, 'estados' => $estados, 'direcciones' => $direcciones, 'tipoRepeticionLlamadas' => $tipoRepeticionLlamadas, 'horasRecordatorio' => $horasRecordatorio])
                        @endcomponent
                    </div>
                </div>
                <div class="col-8">
                    @include('agenda.calendario')
                </div>
            </div>
        </div>


        @include('agenda.llamadas.addOrEdit');
    </div>
@else
    @section('scripts')
        <script src="{{ asset('js/panel.js') }}"></script>
        <script src="{{ asset('js/agenda.js') }}" defer></script>
        <script src="{{ asset('js/llamadas.js') }}" defer></script>
        <script src="{{ asset('js/dashboards_EstatusXagenteAgenda.js') }}"></script>
    @endsection
    @endif
    @endforeach

    <div class="tab-pane fade " id="custom-tabs-four-settings " role="tabpanel "
        aria-labelledby="custom-tabs-four-settings-tab ">
        Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales
        tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique.
        Vestibulum consectetur dolor a
        vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at
        consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet
        accumsan
        ex sit amet facilisis.
    </div>
    </div>
    </div>
    <!-- /.card -->
    </div>

    </div>

    <!-- /.End body Dashboards -->


    @include('partials.loading')

@endsection
