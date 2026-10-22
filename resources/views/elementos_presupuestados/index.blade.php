@extends('layout.master') @section('content')
@include('partials.loading')
<div class="container-fluid">



    <div class="content-header">
        <div class="row mb-n2">
            <div class="col-sm-4">
                <h1>Elementos Presupuestados</h1>
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
                        <button onclick="ajustarTamaño()" type="button" class="btn btn-block btn-primary btn-sm" id="btn-filters" title="Mostrar Filtros">
                                <i class="nav-icon fas fa-filter"></i>
                            </button>
                    </li>

                    <a type="button" class="btn-flotante button btn btn-primary" title="Nuevo Elemento Presupuestado" href="{{ url('elementos/create') }}/0" onclick="loaderON()">
                        <i class="fas fas fa-plus-circle"></i>
                    </a>

                </ol>
            </div>
        </div>
    </div>
    @component('sections.Filtros')
        @slot('tabla')
        tblElementosPresupuestados
        @endslot
        @slot('datatable')
        tblElementosPresupuestados
        @endslot
    @endcomponent
    
    @component('sections.EP_tblIndex')
    @endcomponent


</div>
<script src=" {{ asset('js/elementos_presupuestados.js?n=1') }} " defer></script>

@endsection