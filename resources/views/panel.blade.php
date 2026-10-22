<?php

if (!isset($_GET['usuario'])) {
    $_SESSION['Usuario'] = '';
} else {
    $_SESSION['Usuario'] = $_GET['usuario'];
}
?>
@extends('layout.master')
@section('content')
    <input type="hidden" name="user" value="{{ $datos_usuario[0]->Login }}">
    <div class="card card-dashboard card-primary card-outline card-outline-tabs">
        @if ($Rol == 1014 || $Rol == 5 || $Rol == 6 || $Rol == 1010 || $Rol == 1011 || $Rol == 1012 || $Rol == 1013)
            <div id="cardHeaderPanel" class="row card-header p-0 border-bottom-0" style="padding-left: 0.5% !important;">
            </div>
        @else
            <div id="cardHeaderPanel" class="row card-header p-0 border-bottom-0" style="padding-left: 0.5% !important;">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist" style="width: 70%;">
                    @foreach ($permisos as $PermisosModulos)
                        @if ($PermisosModulos->idModulo == 16)
                            {{-- <li class="nav-item" id="liDireccion" hidden>
                            <a class="dashboard nav-link " id="custom-tabs-four-home-tab" data-toggle="pill"
                                href="#Dashboard-Direccion" role="tab" aria-controls="custom-tabs-four-home"
                                aria-selected="false">DIRECCION</a>
                        </li> --}}
                        @elseif ($PermisosModulos->idModulo == 13)
                            @if ($Rol != 1020 && $Rol != 1021 && $Rol != 1024)
                                <li class="nav-item" id="liCotizaciones">
                                    <a class="dashboard nav-link" onclick="cargarOportunidadesXAtender()"
                                        id="Dashboard_Cotizaciones-tab" data-toggle="pill" href="#Dashboard_Cotizaciones"
                                        role="tab" aria-controls="Dashboard_Cotizaciones"
                                        aria-selected="true">Cotizaciones</a>
                                </li>








                                {{-- ======================== DOSSIER ================== --}}

                                @if ($id_usuario != 13582)
                                <li class="nav-item">
                                    <a class="dashboard nav-link cursor-pointer" id="diario-tab" data-toggle="pill"
                                        onclick="mostrarIndicadorDiario()" role="tab"
                                        aria-controls="dossierDiarioReporte" aria-selected="true">Indicador Diario
                                        &nbsp;&nbsp;</i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="dashboard nav-link cursor-pointer" id="mensual-tab" data-toggle="pill"
                                        onclick="mostrarIndicadorMensual()" role="tab"
                                        aria-controls="dossierMensualReporte" aria-selected="true">Indicador Mensual
                                        &nbsp;&nbsp;</i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="dashboard nav-link cursor-pointer" id="monitorOport-tab" data-toggle="pill"
                                        onclick="mostrarMonitorOportunidades()" role="tab"
                                        aria-controls="dossierMonitorOportunidadesReporte" aria-selected="true">Monitor
                                        Oportunidades &nbsp;&nbsp;</i></a>
                                </li>
                                @endif
                            @endif
                            @if ($Rol == 1 || $Rol == 8 || $Rol == 1020 || $Rol == 1021 || $Rol == 1024)
                                <li class="nav-item">
                                    <a class="dashboard nav-link cursor-pointer" id="monitorEstr-tab" data-toggle="pill"
                                        onclick="mostrarMonitorEstructural()" role="tab"
                                        aria-controls="dossierMonitorEstructuralReporte" aria-selected="true">Monitor
                                        Estructural &nbsp;&nbsp;</i></a>
                                </li>
                            @endif
                        @elseif ($PermisosModulos->idModulo == 14)
                            @if ($Rol != 1020 && $Rol != 1021 && $Rol != 1024)
                                <li class="nav-item {{ $active }}" id="liVentas">
                                    <a class="dashboard nav-link {{ $active }}" id="Dashboard_Ventas-tab"
                                        data-toggle="pill" href="#Dashboard_Ventas" onclick="mostrarVentas()" role="tab"
                                        aria-controls="Dashboard_Ventas" aria-selected="{{ $selected }}">Ventas</a>
                                </li>
                            @endif
                            @if ($Rol != 1 && $Rol != 8 && $Rol != 1020 && $Rol != 1021 && $Rol != 1024)
                                <li class="nav-item">
                                    <a class="dashboard nav-link cursor-pointer" id="monitorOport-tab" data-toggle="pill"
                                        onclick="mostrarMonitorOportunidades()" role="tab"
                                        aria-controls="dossierMonitorOportunidadesReporte" aria-selected="true">Monitor
                                        Oportunidades &nbsp;&nbsp;</i></a>
                                </li>
                            @endif
                        @elseif ($PermisosModulos->idModulo == 15)
                            @if ($Rol != 1020 && $Rol != 1021 && $Rol != 1024)
                                <li class="nav-item" id="liAgenda">
                                    <a class="dashboard nav-link" id="Dashboard_Agenda-tab" data-toggle="pill"
                                        href="#Dashboard_Agenda" onclick="mostrarAgenda()" role="tab"
                                        aria-controls="Dashboard_Agenda" aria-selected="false"> Agenda</a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                </ul>
                <ul class="nav nav-tabs" style="width: 15%; margin: 3px 0 0 -13px">
                    <div style="width: 90%">
                        <select class="form-control" name="año_dashboard" id="año_dashboard"
                            style="font-weight: bold; font-size: 14px !important">
                            <option value="TODOS">Todos los años</option>
                            @foreach ($años as $año_)
                                @if ($año == $año_)
                                    <option value="{{ $año_ }}" selected>{{ $año_ }}</option>
                                @else
                                    <option value="{{ $año_ }}">{{ $año_ }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </ul>
                <ul class="nav nav-tabs" style="width: 15%; margin: 3px 0 0 -13px">
                    <div style="width: 100%">
                        <select class="form-control" name="mes_dashboard" id="mes_dashboard"
                            style="font-weight: bold; font-size: 14px !important">
                            <option value="TODOS">Todos los meses</option>
                            @foreach ($meses as $mes_)
                                @if ($mes == $mes_)
                                    <option value="{{ $mes_ }}" selected>{{ $mes_ }}</option>
                                @else
                                    <option value="{{ $mes_ }}">{{ $mes_ }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </ul>
            </div>
        @endif
        @if (
            $Rol == 1014 ||
                $Rol == 5 ||
                $Rol == 6 ||
                $Rol == 1010 ||
                $Rol == 1011 ||
                $Rol == 1012 ||
                $Rol == 1013 ||
                $Rol == 1017 ||
                $Rol == 1015 ||
                $Rol == 1019 ||
                $Rol == 1022)
            <script>
                año_dashboard = document.getElementById('año_dashboard');
                mes_dashboard = document.getElementById('mes_dashboard');
                custom_tabs_four_tab = document.getElementById('custom-tabs-four-tab');
                card_header = document.getElementById('cardHeaderPanel');
                card_header.style.display = 'none';
                año_dashboard.style.visibility = 'hidden';
                mes_dashboard.style.visibility = 'hidden';
                custom_tabs_four_tab.style.visibility = 'hidden';
            </script>
            {{-- @if ($Rol == 1020 || $Rol == 1021 || $Rol == 1024)

                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist" style="width: 70%;">
                    <li class="nav-item">
                        <a class="dashboard nav-link cursor-pointer" id="monitorEstr-tab" data-toggle="pill"
                            onclick="mostrarMonitorEstructural()" role="tab"
                            aria-controls="dossierMonitorEstructuralReporte" aria-selected="true">Monitor
                            Estructural &nbsp;&nbsp;</i></a>
                    </li>
                </ul>

                <div class="tab-pane fade" id="dossierMonitorEstructuralReporte" role="tabpanel"
                    aria-labelledby="monitorEstr-tab">
                    <div style="width: 100%; margin-top: -10px; height: 82vh;" id="dossierMonitorEstructural">
                    </div>
                </div>
            @else --}}
            <div>
                <img src="{{ asset('img/Logo_LIto_azul.png') }}" width="300" style="margin: 20% 0 0 40%">
            </div>
            {{-- @endif --}}
        @else
            <div class="card-body" style="padding-top: 1.25rem !important;">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!----------------------------- DASHBOARDS DIRECCION -------------------------------->
                    <div class="tab-pane fade" id="Dashboard-Direccion " role="tabpanel "
                        aria-labelledby="Dashboard-Direccion-tab" hidden>
                    </div>
                    @foreach ($permisos as $PermisosModulos)
                        <input type="hidden" id="idRol" value="{{ $Rol }}">

                        <div class="tab-pane fade" id="dossierDiarioReporte" role="tabpanel"
                            aria-labelledby="diario-tab">
                            <div style="width: 100%; margin-top: -10px;" id="dossierDiario">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="dossierMensualReporte" role="tabpanel"
                            aria-labelledby="mensual-tab">
                            <div style="width: 100%; margin-top: -10px;" id="dossierMensual">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="dossierMonitorOportunidadesReporte" role="tabpanel"
                            aria-labelledby="monitorOport-tab">
                            <div style="width: 100%; margin-top: -10px; height: 82vh;" id="dossierMonitorOportunidades">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="dossierMonitorEstructuralReporte" role="tabpanel"
                            aria-labelledby="monitorEstr-tab">
                            <div style="width: 100%; margin-top: -10px; height: 82vh;" id="dossierMonitorEstructural">
                            </div>
                        </div>

                        @if ($PermisosModulos->idModulo == 13)
                            <!----------------------------- DASHBOARDS COTINZACIONES/COSTUMERS ------------------>
                            <div class="tab-pane fade show" id="Dashboard_Cotizaciones" role="tabpanel"
                                aria-labelledby="Dashboard_Cotizaciones-tab">
                                <div class="col-lg-12 mb-2">


                                    @if ($id_usuario != 13582)
                                    @component('sections.dashboard.cards_Estatus')
                                    @endcomponent
                                    @endif

                                    {{-- @component('sections.dashboard.cards_Estatus', ['my_array' => $cards])
                                @endcomponent --}}
                                </div>
                                <div id="graficasCotizaciones">

                                    <div class="row col-lg-12 " style="padding-left: 3%;">
                                        <div class="col-lg-6" style="padding: 0% !important;">
                                            <!-- LISTA de oportunidades por atender -->
                                            @component('sections.dashboard.cards_List', ['my_array' => $List])
                                            @endcomponent
                                        </div>
                                        <div class="col-lg-6" id="dash-tiempo "
                                            style="padding: 0% !important; margin-bottom: 15px;">
                                            <!-- Tiempos -->
                                            {{-- @component('sections.dashboard.cards_Tiempos', ['my_array' => $cards])
                                        @endcomponent --}}

                                            <!-- Eficiencia -->
                                            {{-- @component('sections.dashboard.cards_Eficiencia', ['my_array' => $Eficiencia])
                                        @endcomponent --}}
                                            <!-- Oportuniades por Semana -->
                                            <div class="{{ $id_usuario == 13582 ? 'sr-only' : '' }} ">
                                                @component('sections.dashboard.cards_Semana')
                                                @endcomponent
                                            </div>
                                            <style>
                                                .sr-only {
                                                    position: absolute;
                                                    width: 1px;
                                                    height: 1px;
                                                    padding: 0;
                                                    margin: -1px;
                                                    overflow: hidden;
                                                    clip: rect(0, 0, 0, 0);
                                                    border: 0;
                                                }
                                            </style>
                                        </div>
                                    </div>
                                    <div class="row col-lg-12 " style="padding-left: 3%;">
                                        <div class="col-lg-6" style="padding: 5px 0 0 0  !important;">
                                            <!-- LISTA de Tripas con Cambios -->

                                            @include('sections.dashboard.cards_oport_customers')
                                            {{-- @component('sections.dashboard.cards_oport_customers', ['my_array' => $List_tripas])
                                            @endcomponent --}}
                                        </div>
                                        
                                        <div class="col-lg-6" style="padding: 5px 0 0 0  !important;">
                                            <!-- LISTA de Tripas con notas de correcciones -->
                                            
                                            {{-- Tabla de correcciones --}}
                                            @include('sections.dashboard.cards_oport_corrections')
                                        </div>
                                    </div>
                                    {{-- <div class="row col-lg-12 mt-2" style="padding-left: 3%;">
                                </div> --}}
                                </div>







                            </div>
                            {{-- ========================================== DOSSIER ========================================== --}}
                        @elseif ($PermisosModulos->idModulo == 14)
                            <!---------------------------- DASHBOARDS VENTAS ----------------------------->
                            <div class="tab-pane fade {{ $active }} {{ $show }}" id="Dashboard_Ventas"
                                role="tabpanel" aria-labelledby="Dashboard_Ventas-tab">

                                @if ($Rol == 1 || $Rol == 7 || $Rol == 8)
                                    <!--no cerrar este div aqui-->
                                    <div class="row col-lg-12" style="height:3.5rem">
                                        <div class="col-lg-1" style="margin-top: 0.5rem;">
                                            <h3 class="card-title text-center">Agente:</h3>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <select class="form-control select2" style="width: 100%;" name="sltAgentes"
                                                id='sltAgentes' data-select2-id="sltAgentes">
                                                <option value="TODOS" disabled>Selecciona un Agente</option>
                                                <option value="TODOS">Todos los Agentes</option>
                                                @foreach ($Agentes as $Agente)
                                                    @if ($Agente->Id_Usuario == $datos_usuario[0]->Id_Usuario)
                                                        <option id="{{ $Agente->Id_Usuario }}"
                                                            value="{{ $datos_usuario[0]->Id_Usuario }}" selected>
                                                            {{ $Agente->Nombre }}</option>
                                                    @else
                                                        <option id="{{ $Agente->Id_Usuario }}"
                                                            value="{{ $Agente->Id_Usuario }}">
                                                            {{ $Agente->Nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="row col-lg-12" style="height:3.5rem" hidden>
                                        <div class="col-lg-1" style="margin-top: 0.5rem;">
                                            <h3 class="card-title text-center">Agente:</h3>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <select class="form-control select2" style="width: 100%;" name="sltAgentes"
                                                id='sltAgentes' data-select2-id="sltAgentes">
                                                <option id="{{ $idEquipo_idAgente }}" value="{{ $idEquipo_idAgente }}"
                                                    selected>
                                                </option>

                                            </select>
                                        </div>
                                    </div>
                                @endif


                                {{-- <div class="col-lg-12 mb-2">
                                @component('sections.dashboard.cards_Estatus', ['my_array' => $cards])
                                @endcomponent
                            </div> --}}

                                <div class="col-lg-12 mb-2">
                                    @component('sections.dashboard.cards_EstatusXagente')
                                    @endcomponent
                                </div>


                                <div id="graficasVentas">
                                    <div class="row col-lg-12 ">
                                        @component('sections.dashboard.cards_VtasVsMeta')
                                        @endcomponent
                                    </div>
                                    <div class="row col-lg-12 ">
                                        @component('sections.dashboard.cards_boxVentas')
                                        @endcomponent
                                    </div>
                                    <br>
                                    <div class="row col-lg-12 ">
                                        <div class="col-lg-6">
                                            @component('sections.dashboard.cards_valor')
                                            @endcomponent
                                        </div>


                                        <div class="col-lg-6" style="padding: 0% !important;">
                                            <!-- LISTA de oportunidades por atender -->
                                            @component('sections.dashboard.cards_List_ventas', ['my_array' => $List_ventas])
                                            @endcomponent
                                        </div>




                                        {{-- <div class="col-lg-6">
                                            @include('sections.dashboard.cards_probabilidad')
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        @elseif ($PermisosModulos->idModulo == 16)
                            <!-------------------------- DASHBOARDS AGENDA --------------------------------->
                            <div class="tab-pane fade" id="Dashboard_Agenda" role="tabpanel"
                                aria-labelledby="Dashboard_Agenda-tab">
                                @if ($Rol == 1 || $Rol == 7 || $Rol == 8)
                                    <div class="row col-lg-12" style="height:3.5rem">
                                        <div class="col-lg-1" style="margin-top: 0.5rem;">
                                            <h3 class="card-title text-center">Agente:</h3>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <select class="form-control select2" style="width: 100%;"
                                                name="sltAgendaAgentes" id='sltAgendaAgentes'
                                                data-select2-id="sltAgentes">
                                                <option value="TODOS" disabled>Selecciona un Agente</option>
                                                <option value="TODOS">TODOS LOS AGENTES</option>
                                                @foreach ($Agentes as $Agente)
                                                    @if ($Agente->Id_Usuario == $datos_usuario[0]->Id_Usuario)
                                                        <option id="{{ $Agente->Id_Usuario }}"
                                                            value="{{ $datos_usuario[0]->Id_Usuario }}" selected>
                                                            {{ $Agente->Nombre }}</option>
                                                    @else
                                                        <option id="{{ $Agente->Id_Usuario }}"
                                                            value="{{ $Agente->Id_Usuario }}">
                                                            {{ $Agente->Nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="row col-lg-12" style="height:3.5rem" hidden>
                                        <div class="col-lg-1" style="margin-top: 0.5rem;">
                                            <h3 class="card-title text-center">Agente:</h3>
                                        </div>
                                        <div class="col-lg-6 ">
                                            <select class="form-control select2" style="width: 100%;"
                                                name="sltAgendaAgentes" id='sltAgendaAgentes'
                                                data-select2-id="sltAgentes">
                                                <option id="{{ $idEquipo_idAgente }}" value="{{ $idEquipo_idAgente }}"
                                                    selected>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                {{-- ==========================Cards============================= --}}
                                <div class="row col-lg-12">
                                    <div class="col-lg-6">
                                        @component('sections.dashboard.cards_Llamadas')
                                        @endcomponent
                                    </div>
                                    <div class="col-lg-6">
                                        @component('sections.dashboard.cards_Reuniones')
                                        @endcomponent
                                    </div>
                                </div>

                                <div id="graficasAgenda">
                                    <!--=========================Graficas===========================-->
                                    <div class="row" style="margin-top: -10px">
                                        <div class="col-lg-4 mt-2">

                                            {{-- <div class="row">
                                            <div class="col-lg-12 ">
                                                @component('sections.dashboard.cards_LlamadasMes')
                                                @endcomponent
                                            </div>
                                        </div><br> --}}

                                            <div class="col-lg-12" id="list_llamadas_reuniones">
                                                @component('sections.dashboard.cards_List_Llamadas', [
                                                    'my_array' => $List_Llamadas,
                                                    'id_usuario' => $id_usuario,
                                                    'rol' => $rol,
                                                    'agente_id' => $agente_id,
                                                    'combo_clientes' => $combo_clientes,
                                                    'estados' => $estados,
                                                    'direcciones' => $direcciones,
                                                    'tipoRepeticionLlamadas' => $tipoRepeticionLlamadas,
                                                    'horasRecordatorio' => $horasRecordatorio,
                                                ])
                                                @endcomponent
                                            </div>
                                            <div class="col-lg-12">
                                                @component('sections.dashboard.cards_List_Reuniones', [
                                                    'my_array' => $List_Reuniones,
                                                    'id_usuario' => $id_usuario,
                                                    'rol' => $rol,
                                                    'agente_id' => $agente_id,
                                                    'combo_clientes' => $combo_clientes,
                                                    'estados' => $estados,
                                                    'direcciones' => $direcciones,
                                                    'tipoRepeticionLlamadas' => $tipoRepeticionLlamadas,
                                                    'horasRecordatorio' => $horasRecordatorio,
                                                ])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            @include('agenda.calendario')
                                        </div>
                                    </div>
                                </div>


                                @include('agenda.llamadas.addOrEdit');
                            </div>
                        @else
                            @section('scripts')
                                <script src="{{ asset('js/panel.js?n=1') }}"></script>
                                <script src="{{ asset('js/agenda.js?n=1') }}" defer></script>
                                <script src="{{ asset('js/llamadas.js?n=1') }}" defer></script>
                                <script src="{{ asset('js/dashboards_EstatusXagenteAgenda.js?n=1') }}"></script>
                            @endsection
                        @endif
                    @endforeach
                </div>
            </div>
            @include('partials.loading')
        @endif
    </div>
@endsection
