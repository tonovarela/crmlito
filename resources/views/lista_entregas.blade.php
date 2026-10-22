@extends('layout.master')
@section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <button type="button" class="btn-flotante button btn btn-primary" title="Aceptar" id="actualizarEntregas"
            onclick="location.reload()">
            <i class="fas fas fa-check-circle"></i>
        </button>

        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-lg-12">
                    <h1>ENTREGAS DE LA SEMANA PASADA</h1>
                </div>
                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            {{-- <div class="card-header" style="height: 50px !important">
                <h4 class="text-center">DEL 27/02/2023 AL 05/03/2023</h4>
            </div> --}}
            <div class="card-body mt-2" style="height: 78vh">
                <table id="tblListaEntregas" class="table table-striped nowrap compact tblnombre">
                    <thead>
                        <tr class="bg-gray">
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Numero Orden</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Nombre Trabajo</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Cliente</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Cant Entregar</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Cant Entregado</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Fecha Entregar</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">Fecha Entregado</th>
                            <th rowspan="2" style="vertical-align: middle; text-align: center">A Tiempo</th>
                            <th colspan="2" style="text-align: center">¿ENTREGA A TIEMPO?</th>
                        </tr>
                        <tr class="bg-gray">
                            <th style="text-align: center">SI</th>
                            <th style="text-align: center">NO</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th colspan="9" class="text-red text-bold">
                                <p style="font-size: 20px; text-align: left"><i class="fas fa-info-circle"
                                        title="TIENES QUE CONTESTAR COMPLETAMENTE LA ENCUESTA, EN CASO CONTRARIO NO PODRÁS UTILIZAR NINGÚN MODULO DEL SISTEMA."></i>
                                    ES NECESARIO CONTESTAR ESTA ENCUESTA PARA SEGUIR
                                    UTILIZANDO EL SISTEMA.</p>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script src=" {{ asset('js/lista_entregas.js?n=1') }} " defer></script>
@endsection
