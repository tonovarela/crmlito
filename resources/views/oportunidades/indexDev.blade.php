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
                <div class="col-3">
                    <h1>Oportunidades</h1>
                </div>
                <div class="col-6 justify-content-md-center">
                    <div class="col-sm-11 input-group input-group-sm ">
                        <span type="text" id="evento" hidden>
                            @isset($_GET['evento']){{ $_GET['evento'] }}@endif
                            </span>
                            <input type="text" id="searchGral" class="form-control" placeholder="Buscar ..."
                                value="@isset($_GET['evento']){{ $_GET['evento'] }} @endif">
                    <span class="input-group-append"><button type="button" id="btn-search"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                </div>
            </div>
            <div class="col-3">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item" style="margin-right: 5px;">
                        <button type="button" class="btn btn-block btn-primary btn-sm" id="btn-filters" title="Mostrar Filtros">
                                <i class="nav-icon fas fa-filter"></i>
                            </button>
                    </li>
                    <li style="margin-right: 5px;">
                        <a href="{{ route('Correos') }}" onclick="loaderON()"
                            class="button btn btn-block btn-primary btn-sm"
                            title="Correos" id="Correos">
                            <i class="fas fas fa-envelope"></i>
                        </a>
                    </li>
            @if ($crearOpor === 'Crear')
                    <a id="btnAgregarOportunidad" href="{{ route('Nueva Oportunidad') }}" onclick="loaderON()" type="button" class="btn-flotante button btn btn-primary" title="Agregar Oportunidad">
                        <i class="fas fas fa-plus-circle"></i>
                    </a>
                    @endif
                </ol>
            </div>
        </div>
    </div>
    @component('sections.Filtros')
        @slot('tabla')
            tblOportunidades
        @endslot
        @slot('datatable')
            tblOportunidades
        @endslot
    @endcomponent

    <div class="mt-2">
                            <table id="tblOportunidades" class="table table-hover table-striped nowrap compact tblnombre">
                                <thead>
                                    <tr class="bg-gray">
                                        <th>ID</th>
                                        <th>Fecha de Creación</th>
                                        <th>ST Oport</th>
                                        <th>Fec Tope</th>
                                        <th class='thname'>Oportunidad</th>
                                        <th>Atendido por</th>
                                        <th>CierPrev</th>
                                        <th>Num Orden MTC</th>
                                        <th class='thname'>Cliente</th>
                                        <th>Asignado a</th>
                                        <th>Tipo Oportunidad</th>
                                        <th>Probabilidad Ganado</th>
                                        <th>Valor</th>
                                        <th>Estatus</th>
                                        <th>Progreso</th>
                                        <th>Concluida</th>
                                        <th>STFecEnt</th>
                                        <th>ArchAut</th>
                                        <th>Fecha Entrega</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <script src=" {{ asset('js/oportunidades.js') }} " defer></script>
                    <script src=" {{ asset('js/oportunidades_Docs.js') }} " defer></script>
                    <script src=" {{ asset('js/cambiar_agente.js') }} " defer></script>
                    @endsection @section('scripts')
                    <script>
                        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                        $('#modal-create-oportunidad').on('hidden.bs.modal', function() {
                            $(this).find('form').trigger('reset');
                        });

                        $("#modal-create-oportunidad").draggable({
                            handle: ".titulo"
                        });

                        $('#modal-create-oportunidad').on('shown.bs.modal', function() {
                            $('#nomOportunidad').trigger('focus');
                        });

                        var mensaje = '';

                        function agregarOportunidad() {
                            mensaje = 'Oportunidad Agregada';
                            save_method = "add";
                            $('imput[name=_method]').val('POST');
                            $('#modal-create-oportunidad').modal('show');
                            $('.titulo').text('Agregar Oportunidad');
                        }

                        $(function() {
                            $('#modal-create-oportunidad form').validator().on('submit', function(e) {
                                if (!e.isDefaultPrevented()) {
                                    var tblOportunidades = $('#tblOportunidades').DataTable();
                                    // var id = $('#idContactoINT').val();
                                    $.ajax({
                                        url: "{{ url('oportunidades') }}",
                                        type: "POST",
                                        data: $('#modal-create-oportunidad form').serialize(),
                                        success: function(data) {
                                            $('#modal-create-oportunidad').trigger("reset");
                                            $('#modal-create-oportunidad').modal('hide');
                                            const Msg = Swal.mixin({
                                                toast: true,
                                                position: 'center',
                                                icon: 'success',
                                                showConfirmButton: false,
                                                timer: 3000
                                            });

                                            Msg.fire({
                                                title: mensaje,
                                            });

                                            tblOportunidades.ajax.reload();
                                        },
                                        error: function() {
                                            const Msg = Swal.mixin({
                                                toast: true,
                                                position: 'center',
                                                icon: 'warning',
                                                showConfirmButton: false,
                                                timer: 3000
                                            });

                                            Msg.fire({
                                                title: 'Ocurrió un error',
                                            });
                                        }
                                    });
                                    return false;
                                }
                            });
                        });
                    </script>
                    @include('oportunidades.editModal')
                @endsection
