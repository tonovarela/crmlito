@extends('layout.master')
<?php
$_SESSION['idClienteDocs'] = 0;
?>
@section('content')
    <div class="container-fluid">

        @include('partials.loading')

        <div class="content-header">
            <div class="row mb-1">
                <div class="w-100">
                    <div class="rounded bg-gray">
                        <div class="row mb-1">
                            <div class="col-12 text-center">
                                <h6 class="text-bold pt-1 pl-1">Documentos Administrativos</h6>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row mb-1">
                <div class="w-100">
                    <div class="rounded bg-gray" style="height: 42px">
                        <div class="row">
                            <div class="col-lg-6 pt-1">
                                <div class="input-group">
                                    <h6 class="text-left text-bold text-white pl-4 pt-2 pr-5">Cliente
                                    </h6>
                                    <select id="idCliente" name="idCliente" class="form-control select2 text-uppercase">
                                        <option selected disabled>Seleccionar Cliente...</option>
                                        @foreach ($combo_clientes as $cliente)
                                            <option value="{{ $cliente->ClienteINT }}">
                                                {{ $cliente->ClienteINT }} - {{ $cliente->razonsocial }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="text-right" style="width: 5%">
                                <button onclick="refreshList()" id="btnFresh" class="btn btn-secondary btn-sm"
                                    title="Refrescar Lista" style="display: none"><i
                                        class="fas fa-sync-alt text-sm"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <table id="tblPedidosCliente" class="table table-hover table-striped nowrap compact tblnombre">
                    <thead>
                        <tr class="bg-gray">
                            <th>Mov. ID</th>
                            <th>Cliente</th>
                            <th>Nombre</th>
                            <th>Fecha Emision</th>
                            <th>Concepto</th>
                            <th>Referencia</th>
                            <th>Agente</th>
                            <th>Condición</th>
                            <th>Orden Compra</th>
                            <th>Importe</th>
                            <th>Impuestos</th>
                            <th>Precio Total</th>
                            <th width="10px">Entrega</th>
                            <th width="10px">Factura</th>
                            <th width="10px">CXC</th>
                        </tr>
                    </thead>
                    <tfoot>
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @include('documentos_administrativos.documentos_mod')
    @include('documentos_administrativos.mod_UpDocsAdmins')
    @include('documentos_administrativos.visorPDF')

    <script src=" {{ asset('js/documentos_administrativos.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/documentos_administrativos_Docs.js?n=1') }} " defer></script>
@endsection

@section('scripts')
@endsection
