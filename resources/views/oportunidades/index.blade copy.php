@extends('layout.master') 
@section('content') 
@include('partials.loading')

<div class="container-fluid">
    <div class="content-header">
        <div class="row mb-1">
            <div class="col-sm-3">
                <h1>Oportunidades</h1>
            </div>
            <div class="col-sm-5">
                <div class="col-sm-12 input-group input-group-sm ">
                    <span type="text" id="evento" hidden>@isset($_GET['evento']){{$_GET['evento']}}@endif</span>
                    <input type="text" id="searchGral" class="form-control" placeholder="Buscar ..." value="@isset ($_GET['evento']){{$_GET['evento']}} @endif">
                    <span class="input-group-append"><button type="button" id="btn-search"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                </div>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb float-sm-right">
                    @if ($crearOpor === 'Crear')
                    <a onclick="loaderON(), agregarOportunidad()" type="button" class="btn-flotante button btn btn-primary" title="Agregar Oportunidad">
                        <i class="fas fas fa-plus-circle"></i>
                    </a>
                    {{-- <a href="{{ route('Nueva Oportunidad') }}" onclick="loaderON()" type="button" class="btn-flotante button btn btn-primary" title="Agregar Oportunidad">
                        <i class="fas fas fa-plus-circle"></i>
                    </a> --}}
                    @endif
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table compact nowrap tblnombre" style="margin-bottom: 0">
                    <tbody>
                        <tr>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="preregistroChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="preregistroChk" style="padding-top: 4px">Pre registro</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="SolCotChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="SolCotChk" style="padding-top: 4px">Solicitud de Cotización</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="listoCotChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="listoCotChk" style="padding-top: 4px">Listo p/cotizar</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="cotizandoChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="cotizandoChk" style="padding-top: 4px">Cotizando</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="revisionChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="revisionChk" style="padding-top: 4px">Revisión</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="esperaVoBoChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="esperaVoBoChk" style="padding-top: 4px">Espera VoBo</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="listoPreChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="listoPreChk" style="padding-top: 4px">Listo p/presupuesto</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="preTerminadoChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="preTerminadoChk" style="padding-top: 4px">Presupuesto terminado</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="finalizadoChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="finalizadoChk" style="padding-top: 4px">Finalizado</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="canceladoChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="canceladoChk" style="padding-top: 4px">Cancelado</label>
                                </div>
                            </td>
                            <td style="padding: 0 !important">
                                <div class="custom-control custom-switch">
                                    <input id="todosChk" type="checkbox" class="custom-control-input">
                                    <label class="custom-control-label" for="todosChk" style="padding-top: 4px">Todos</label>
                                </div>
                            </td>
                            {{-- <td style="padding: 0.35rem !important"><input type="checkbox" id="preregistroChk" style="margin-top: -6px"><label for="preregistroChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Pre registro</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="SolCotChk" style="margin-top: -6px"><label for="SolCotChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Solicitud de Cotización</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="listoCotChk" style="margin-top: -6px"><label for="listoCotChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Listo p/cotizar</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="cotizandoChk" style="margin-top: -6px"><label for="cotizandoChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Cotizando</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="revisionChk" style="margin-top: -6px"><label for="revisionChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Revisión</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="esperaVoBoChk" style="margin-top: -6px"><label for="esperaVoBoChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Espera VoBo</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="listoPreChk" style="margin-top: -6px"><label for="listoPreChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Listo p/presupuesto</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="preTerminadoChk" style="margin-top: -6px"><label for="preTerminadoChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Presupuesto terminado</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="finalizadoChk" style="margin-top: -6px"><label for="finalizadoChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Finalizado</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="canceladoChk" style="margin-top: -6px"><label for="canceladoChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Cancelado</label></td>
                            <td style="padding: 0.35rem !important"><input type="checkbox" id="todosChk" style="margin-top: -6px"><label for="todosChk" style="vertical-align: text-bottom; margin-left: 2px; margin-bottom: 9px">Todos</label></td> --}}
                        </tr>
                    </tbody>
                </table>                
            </div>
        </div>
    </div>
    @component('sections.Filtros') @slot('tabla') tblOportunidades @endslot @slot('datatable') tblOportunidades @endslot @endcomponent

    <div class="mt-2">
        <table id="tblOportunidades" class="table table-hover table-striped nowrap compact tblnombre">
            <thead>
                <tr class="bg-gray">
                    <th>ID</th>
                    <th>Fecha de Creación</th>
                    <th>ST Oport</th>
                    <th>Fec Tope</th>
                    <th class='thname'>Oportunidad</th>
                    <th>Atendido por</th>
                    <th>CierPrev</th>
                    <th>Num Orden MTC</th>
                    <th class='thname'>Cliente</th>
                    <th>Asignado a</th>
                    <th>Tipo Oportunidad</th>
                    <th>Probabilidad Ganado</th>
                    <th>Valor</th>
                    <th>Estatus</th>
                    <th>Progreso</th>
                    <th>Concluida</th>
                    <th>STFecEnt</th>
                    <th>ArchAut</th>
                    <th>Fecha Entrega</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<script src=" {{ asset('js/oportunidades.js') }} " defer></script>
<script src=" {{ asset('js/oportunidades_Docs.js') }} " defer></script>
<script src=" {{ asset('js/cambiar_agente.js') }} " defer></script>

@include('oportunidades.createModal')
@include('oportunidades.editModal')

@endsection 