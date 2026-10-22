@extends('layout.master') @section('content')
    @include('partials.loading')
    <div class="container-fluid">
        <!--
            <div class="loanding">
                <div id="circle">
                    <div class="loader">
                        <div class="loader">
                            <div class="loader">
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-4">
                    <h1>Correos</h1>
                </div>
                <div class="col-6 justify-content-md-center">
                    <div class="col-sm-11 input-group input-group-sm ">
                        <input type="text" id="searchGral" class="form-control" placeholder="Buscar ...">
                        <span class="input-group-append"><button type="button" id="btn-search"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                    </div>
                </div>
                <div class="col-2">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item" style="margin-right: 5px;">
                        <a href="{{ route('Oportunidades') }}" onclick="loaderON()"
                            class="button btn btn-block btn-primary btn-sm" title="Oportunidades" id="Oportunidades">
                            <i class="fas fas fa-search-dollar"></i>
                        </a>
                    </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <table id="tblCorreos" class="table table-hover table-striped nowrap compact tblnombre">
                <thead>
                    <tr class="bg-gray">
                        <th>De</th>
                        <th>Para</th>
                        <th>Asunto</th>
                        <th>Mensaje</th>
                        <th>Adjunto</th>
                        <th>Fecha</th>
                        <th class='thname'>Oportunidad</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script src=" {{ asset('js/oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_Docs.js?n=1') }} " defer></script>
@endsection
