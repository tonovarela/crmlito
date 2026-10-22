@extends('layout.master')

@section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-4">
                    <h1>Oportunidades de Envíos</h1>
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
                        @if ($crearEnv === 'Crear')
                            <button onclick="agregarEnvio('{{ $nombre_usuario }}', '{{ $folio }}'), loaderON()"
                                type="button" class="btn-flotante button btn btn-primary" title="Agregar Envío">
                                <i class="fas fas fa-plus-circle"></i>
                            </button>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
        @component('sections.Filtros')
            @slot('tabla')
                tblEnvios
            @endslot
            @slot('datatable')
                tblEnvios
            @endslot
        @endcomponent
        <div class="mt-2">
            <table id="tblEnvios" class="table table-hover table-striped nowrap compact tblnombre">
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
                        <th></th>
                        {{-- <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th> --}}
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('oportunidades_envios.create')
    <script src=" {{ asset('js/envio.js?n=1') }} " defer></script>
    {{-- <script src=" {{ asset('js/oportunidades.js') }} " defer></script> --}}
    <script src=" {{ asset('js/envios_Docs.js?n=1') }} " defer></script>
@endsection
