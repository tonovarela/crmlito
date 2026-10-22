@extends('layout.master')
@section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-2">
                    <h1>Usuarios</h1>
                </div>
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                        {{-- @if ($crear === 'Crear')
                            <button type="button" class="btn-flotante button btn btn-primary"
                                title="Nuevo Cliente" data-toggle="modal"
                                data-target="#modal-create-cliente" id="nvoCliente" onclick="openModal()">
                                <i class="fas fas fa-plus-circle"></i>
                            </button>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Contactos') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm"
                                    title="Contactos" id="Contactos">
                                    <i class="fas fas fa-address-card"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Sucursales') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm"
                                    title="Sucursales" id="Sucursales">
                                    <i class="fas fas fa-store"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;">
                                <a href="{{ route('Requisitos') }}" class="button btn btn-block btn-primary btn-sm"
                                    title="Requisitos" id="requisitos">
                                    <i class="fas fas fa-list-ol"></i>
                                </a>
                            </li>
                        @endif --}}
                    </ol>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <table id="tblUsuarios" class="table table-hover table-striped nowrap compact">
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
                <tfoot></tfoot>
            </table>
        </div>
    </div>
    @include('usuarios.permisos')
    @include('usuarios.appsLito')
    @include('usuarios.firma_modal')
    <script src=" {{ asset('js/usuarios.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_agente.js?n=1') }} " defer></script>
@endsection
