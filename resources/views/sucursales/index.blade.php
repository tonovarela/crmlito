@extends('layout.master')
@section('content')
    <div class="container-fluid">
        <button onclick="agregarSucursalCte()" type="button" class="btn-flotante button btn btn-primary"
            title="Agregar Sucursal" id="addSuc">
            <i class="fas fas fa-plus-circle"></i>
        </button>
        @include('partials.loading')
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-4">
                    <h1>Sucursales</h1>
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
                        @if ($rol == 1013)
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Clientes') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Clientes" id="clientes">
                                    <i class="fas fas fa-user-tie"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Contactos') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Contactos" id="Contactos">
                                    <i class="fas fas fa-address-card"></i>
                                </a>
                            </li>
                        @else
                        <li style="margin-right: 5px;">
                            <a href="{{ route('Clientes') }}" onclick="loaderON()"
                                class="button btn btn-block btn-primary btn-sm" title="Clientes" id="clientes">
                                <i class="fas fas fa-user-tie"></i>
                            </a>
                        </li>
                        <li style="margin-right: 5px;">
                            <a href="{{ route('Contactos') }}" onclick="loaderON()"
                                class="button btn btn-block btn-primary btn-sm" title="Contactos" id="Contactos">
                                <i class="fas fas fa-address-card"></i>
                            </a>
                        </li>
                        <li style="margin-right: 5px;">
                            <a href="{{ route('Requisitos') }}" class="button btn btn-block btn-primary btn-sm"
                                title="Requisitos" id="requisitos">
                                <i class="fas fas fa-list-ol"></i>
                            </a>
                        </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
        @component('sections.Filtros')
            @slot('tabla')
                tableSucursalesAll
            @endslot
            @slot('datatable')
                tableSucursalesAll
            @endslot
        @endcomponent
        <div class="mt-2">
            <table id="tableSucursalesAll" class="table table-striped compact nowrap tblnombre">
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
    <script src=" {{ asset('js/sucursales.js?n=1') }} " defer></script>
    @include('sucursales.createSucursal')
@endsection
