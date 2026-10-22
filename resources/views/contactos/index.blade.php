@extends('layout.master') @section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-2">
                    <h1>Contactos</h1>
                </div>
                <div class="col-sm-8 justify-content-md-center">
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
                        @if ($idrol == '1013')
                        <li style="margin-right: 5px;">
                            <a href="{{ route('Clientes') }}" onclick="loaderON()"
                                class="button btn btn-block btn-primary btn-sm" title="Clientes" id="clientes">
                                <i class="fas fas fa-user-tie"></i>
                            </a>
                        </li>
                        <li style="margin-right: 5px;">
                            <a href="{{ route('Sucursales') }}" onclick="loaderON()"
                                class="button btn btn-block btn-primary btn-sm" title="Sucursales" id="Sucursales">
                                <i class="fas fas fa-store"></i>
                            </a>
                        </li>
                        @elseif ($crear === 'Crear')
                            <button onclick="agregarContactoCte()" type="button"
                                class="btn-flotante button btn btn-primary" title="Agregar Contacto" id="addCto">
                                <i class="fas fas fa-plus-circle"></i>
                            </button>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Clientes') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Clientes" id="clientes">
                                    <i class="fas fas fa-user-tie"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Sucursales') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Sucursales" id="Sucursales">
                                    <i class="fas fas fa-store"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Requisitos') }}" class="button btn btn-block btn-primary btn-sm"
                                    title="Requisitos" id="requisitos">
                                    <i class="fas fas fa-list-ol"></i>
                                </a>
                            </li>
                        @elseif ($idrol == '1010')
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Clientes') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Clientes" id="clientes">
                                    <i class="fas fas fa-user-tie"></i>
                                </a>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
        @component('sections.Filtros')
            @slot('tabla')
                tableContactosAll
            @endslot
            @slot('datatable')
                tableContactosAll
            @endslot
        @endcomponent
        <div class="mt-2">
            <table id="tableContactosAll" class="table table-striped compact nowrap tblnombre" onclick="ajustarTablas()">
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
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script src=" {{ asset('js/contactos.js?n=1') }} " defer></script>
    @include('contactos.createContacto')
@endsection
