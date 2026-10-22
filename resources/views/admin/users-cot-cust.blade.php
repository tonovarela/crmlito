@extends('layout.master') 
@section('content')
    @include('partials.loading')

    <div class="container-fluid">
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-6">
                    <h1>Activación y desactivación de cotizadores y customers</h1>
                </div>
                <div class="col-sm-9 justify-content-md-center">
                    <input type="hidden" id="idRolOport" value="{{ $idrol }}">
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2 px-5">
        <div class="py-4">
            <h2>Lista de cotizadores</h2>
        </div>

        <table id="tblUsuariosCot" class="table table-hover table-striped nowrap compact tblnombre text-center">
            <thead>
                <tr class="bg-gray">
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
    <div class="mt-2 px-5">
        <div class="py-4">
            <h2>Lista de customers</h2>
        </div>

        <table id="tblUsuariosCus" class="table table-hover table-striped nowrap compact tblnombre text-center">
            <thead>
                <tr class="bg-gray">
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Accion</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <script src=" {{ asset('js/oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_Docs.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_agente.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/adminUsersCotCust.js?n=1') }} " defer></script>
@endsection 
