@extends('layout.master') @section('content')
    @include('partials.loading')

    <div class="container-fluid">
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-3">
                    <h1>Oportunidades</h1>
                    <input type="hidden" id="idUsuario" value="{{ $id_usuario }}">
                </div>
                <div class="col-sm-9 justify-content-md-center">
                    <input type="hidden" id="idRolOport" value="{{ $idrol }}">
                    <div class="col-sm-11 input-group input-group-sm ">
                        {{-- <input type="text" id="searchOport" class="form-control text-uppercase" placeholder="Buscar..."> --}}
                        <input type="text" id="searchOportFolio" class="form-control ml-1 text-uppercase"
                            placeholder="Folio">
                        <select id="searchOportSTOport" class="form-control ml-1 text-uppercase" style="border-radius: 5px">
                            <option value="">ST Oport</option>
                            <option value="ATRASADO">ATRASADO</option>
                            <option value="CANCELADO">CANCELADO</option>
                            <option value="A TIEMPO">A TIEMPO</option>
                            <option value="SIN INICIAR">SIN INICIAR</option>
                        </select>
                        <input type="text" id="searchOportNombre" class="form-control ml-1 text-uppercase"
                            placeholder="Nombre">
                        <input type="text" id="searchOportOMTC" class="form-control ml-1 text-uppercase"
                            placeholder="OMTC">
                        <input type="text" id="searchOportCliente" class="form-control ml-1 text-uppercase"
                            placeholder="Cliente">
                        @if (($idrol == 4 || $idrol == 9) && $id_usuario != 13582)
                            <select id="searchOportAsignado" class="form-control ml-1 text-uppercase"
                                style="border-radius: 5px; display: none">
                                <option value="">Asignado</option>
                            </select>
                        @else
                            <select id="searchOportAsignado" class="form-control ml-1 text-uppercase"
                                style="border-radius: 5px; display: block">
                                <option value="">Asignado</option>
                                @foreach ($Asignados as $Asignado)
                                    <option value="{{ trim($Asignado->Nombre) }}">
                                        {{ trim($Asignado->Nombre) }}</option>
                                @endforeach
                            </select>
                        @endif
                        
                        @if ($idrol == 1 || ($idrol == 10 && $id_usuario == 12520))
                            <select id="searchOportEnCancha" class="form-control ml-1 text-uppercase"
                                style="border-radius: 5px; display: block">
                                <option value="">En cancha</option>
                                @foreach ($cotizadores as $coti)
                                    <option value="{{ $coti->nombre }}">
                                        {{ $coti->nombre }}</option>
                                @endforeach
                            </select>
                        @else
                            <select id="searchOportEnCancha" class="form-control ml-1 text-uppercase"
                                style="border-radius: 5px; display: none">
                                <option value="">Asignado</option>
                            </select>
                        @endif


                        <select id="searchOportST" class="form-control ml-1 text-uppercase" style="border-radius: 5px">
                            <option value="">ST</option>
                            @foreach ($STS as $ST)
                                <option value="{{ $ST->descEstatus }}">
                                    {{ $ST->descEstatus }}</option>
                            @endforeach
                        </select>

                        <select id="searchOportPrepedido" class="form-control ml-1 text-uppercase" style="border-radius: 5px">
                            <option value="">PREPEDIDO</option>
                            <option value="1">SI</option>
                            <option value="2">NO</option>
                        </select>
                        <span class="input-group-append">
                            <button type="button" onclick="aplicarFiltroOport()" id="btn-searchEstr"
                                class="btn btn-primary btn-flat ml-2 rounded-btn" title="Aplicar">
                                <i class="nav-icon fas fa-search"></i>
                            </button>
                            <button type="button" onclick="LimpiarFiltroOport()" id="btn-cleanEstr"
                                class="btn btn-danger btn-flat ml-2 rounded-btn" title="Limpiar">
                                <i class="nav-icon fas fa-search-minus"></i>
                            </button>
                            <a href="{{ route('Correos') }}" onclick="loaderON()"
                                class="btn btn-primary btn-flat ml-2 rounded-btn" title="Correos" id="Correos">
                                <i class="fas fas fa-envelope"></i>
                            </a>
                        </span>
                    </div>
                </div>
                @if ($crearOpor === 'Crear')
                    <a id="btnAgregarOportunidad" href="{{ route('Nueva Oportunidad') }}" onclick="loaderON()"
                        type="button" class="btn-flotante button btn btn-primary" title="Agregar Oportunidad">
                        <i class="fas fas fa-plus-circle"></i>
                    </a>
                @endif
                {{-- <div class="col-6 justify-content-md-center">
                    <div class="col-sm-11 input-group input-group-sm ">
                        <span type="text" id="evento" hidden>
                            @isset($_GET['evento']){{ $_GET['evento'] }}@endif
                            </span>
                            <input type="text" id="searchOport" class="form-control" placeholder="Buscar ..."
                                value="@isset($_GET['evento']){{ $_GET['evento'] }} @endif">
                    <span class="input-group-append"><button type="button" id="btn-search"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                </div> --}}
            </div>
        </div>
    </div>
    @component('sections.Filtros')
        @slot('tabla')
            tblOportunidades
            @endslot @slot('datatable')
            tblOportunidades
        @endslot
    @endcomponent

    <div class="mt-2">
        <table id="tblOportunidades" class="table table-hover table-striped nowrap compact tblnombre">

            <thead>
                <tr class="bg-gray">
                    <th></th>
                    <th style="min-width: 3rem !important; word-wrap: break-Word">Folio</th>
                    <th></th>
                    <th style="min-width: 4rem !important; word-wrap: break-Word">ST Oport</th>
                    <th></th>
                    <th></th>
                    <th class='thname'>Nombre</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class='thname'>Cliente</th>
                    <th class='thname'>Cotizado por</th>
                    <th class='thname'>Atendido por</th>
                    <th class='thname'>Revisado por</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="min-width: 5rem !important; word-wrap: break-Word">ST</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                {{-- <tr class="bg-gray">
                    <th>Tipo</th>
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
                    <th>ArchAut</th>
                    <th>Fecha Entrega</th>
                    <th>Fec Arc Aut</th>
                </tr> --}}
            </thead>
            <tbody>
            </tbody>
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

    <style>
        .w-12 {
            min-width: 12px !important;
        }
    </style>


    <script src=" {{ asset('js/oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_Docs.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_agente.js?n=1') }} " defer></script>
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
@endsection
