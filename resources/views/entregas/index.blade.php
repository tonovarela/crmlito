@extends('layout.master')

@section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <div class="rounded bg-gray">
            <div class="row mb-1 mt-1">
                <div class="col-12 text-center">
                    <h6 class="text-bold pt-1 pl-1">Entregas</h6>
                </div>
            </div>
        </div>

        <div class="mt-2">
            <table id="tblEntregas" class="table table-hover table-striped nowrap compact tblnombre">
                <thead>
                    <tr class="bg-gray">
                        <th># Oportunidad</th>
                        <th>Sucursal</th>
                        <th>Cantidad</th>
                        <th>Fecha de Entrega</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>

    <script src=" {{ asset('js/entregas.js?n=1') }} " defer></script>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            loaderOut();
        });
    </script>
@endsection
