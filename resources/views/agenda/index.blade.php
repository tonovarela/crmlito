@extends('layout.master')

@section('content')
    <div class="card card-dashboard card-primary card-outline card-outline-tabs">
        @include('partials.loading')
        {{-- <div class="loanding">
            <div id="circle">
                <div class="loader">
                    <div class="loader">
                        <div class="loader">
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row card-header p-0 border-bottom-0" style="padding-left: 0.5% !important;">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist" style="width: 100%;">

                <li class="nav-item">
                    <a class="dashboard nav-link active cursor-pointer" id="llamadas-tab" data-toggle="pill"
                        onclick="mostrarLlamadas()" role="tab" aria-controls="llamadas" aria-selected="true">Llamadas
                        &nbsp;&nbsp;<i class="fas fa-phone-alt"></i></a>
                </li>
                <li class="nav-item">
                    <a class="dashboard nav-link cursor-pointer" id="reuniones-tab" data-toggle="pill"
                        onclick="mostrarReuniones()" role="tab" aria-controls="reuniones" aria-selected="false">Reuniones
                        &nbsp;&nbsp;<i class="fas fa-calendar-plus"></i></a>
                </li>

            </ul>
        </div>

        <div class="tab-pane fade show active" id="llamadas" role="tabpanel" aria-labelledby="llamadas-tab">

            <div class="content-header">
                <div class="row mb-1 mt-1">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-6 justify-content-md-center">
                        <div class="col-sm-11 input-group input-group-sm ">
                            <input type="text" id="searchGral" class="form-control" placeholder="Buscar ...">
                            <span class="input-group-append"><button type="button" id="btn-search"
                                    class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item" style="margin-right: 5px;">
                                <button type="button" class="btn btn-block btn-primary btn-sm" id="btn-filters"
                                    title="Mostrar Filtros">
                                    <i class="nav-icon fas fa-filter"></i>
                                </button>
                            </li>
                            @if ($crearLlamada === 'Crear')
                                <button
                                    onclick="agregarLlamada('{{ $folio }}', 'Llamada', 'Asunto'), loaderON()"
                                    type="button" class="btn-flotante button btn btn-primary" title="Agregar Llamada">
                                    <i class="fas fas fa-plus-circle"></i>
                                </button>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            @component('sections.Filtros')
                @slot('tabla')
                    tblLlamadas
                @endslot
                @slot('datatable')
                    tblLlamadas
                @endslot
            @endcomponent
            <div>
                <table id="tblLlamadas" class="table table-hover table-striped nowrap compact tblnombre">
                    <thead>
                        <tr class="bg-gray">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="tab-pane fade" id="reuniones" role="tabpanel" aria-labelledby="reuniones-tab">
            <div class="content-header p-0 pt-1">
                <div class="row mb-2">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-6 justify-content-md-center">
                        <div class="col-sm-11 input-group input-group-sm ">
                            <input type="text" id="searchGral2" class="form-control" placeholder="Buscar ...">
                            <span class="input-group-append"><button type="button" id="btn-search-2"
                                    class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item" style="margin-right: 5px;">
                                <button type="button" class="btn btn-block btn-primary btn-sm" id="btn-filters-2"
                                    title="Mostrar Filtros">
                                    <i class="nav-icon fas fa-filter"></i>
                                </button>
                            </li>
                            @if ($crearReunion === 'Crear')
                                <button
                                    onclick="agregarLlamada('{{ $folio }}', 'Reunión', 'Lugar'), loaderON()"
                                    type="button" class="btn-flotante button btn btn-primary" title="Agregar Reunión">
                                    <i class="fas fas fa-plus-circle"></i>
                                </button>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>

            @component('sections.Filtros2')
                @slot('tabla')
                    tblReuniones
                @endslot
                @slot('datatable')
                    tblReuniones
                @endslot
            @endcomponent

            <div style="margin-top: -5px">
                <table id="tblReuniones" class="table table-hover table-striped nowrap compact tblnombre">
                    <thead>
                        <tr class="bg-gray">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('agenda.llamadas.addOrEdit')
    <script src="{{ asset('js/llamadas.js?n=1') }}" defer></script>
@endsection
