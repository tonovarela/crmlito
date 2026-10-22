@extends('layout.master')

@section('content')
    @include('partials.loading')
    <div class="container-fluid">
        <button onclick="agregarRequisito()" type="button" class="btn-flotante button btn btn-primary"
            title="Agregar Requisito">
            <i class="fas fas fa-plus-circle"></i>
        </button>
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-4">
                    <h1>Requisitos Especiales</h1>
                </div>
                <div class="col-sm-7 justify-content-md-center">
                    <div class="col-sm-11 input-group input-group-sm ">
                        <input type="text" id="searchxrequisito" class="form-control" placeholder="Buscar ...">
                        <span class="input-group-append"><button type="button" id="btn-refresh"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <ol class="breadcrumb float-sm-right">
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
                            <a href="{{ route('Sucursales') }}" onclick="loaderON()"
                                class="button btn btn-block btn-primary btn-sm" title="Sucursales" id="Sucursales">
                                <i class="fas fas fa-store"></i>
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <table id="tableRequisitos" class="table table-striped compact nowrap">
                <thead>
                    <tr class="bg-gray">
                        <th>ID</th>
                        <th>Descripción</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @include('requisitos.create')
@endsection

@section('scripts')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function() {
            tableRequisitos = $('#tableRequisitos').DataTable({
                processing: true,
                pageLength: 50,
                stateSave: false,
                scrollY: false,
                scrollX: true,
                serverSide: true,
                colReorder: true,
                ajax: {
                    url: "{{ route('api.requisitos') }}",
                    type: 'GET',
                    dataType: 'json',
                },
                columns: [{
                        data: 'idRequisito',
                        name: 'idRequisito',
                    },
                    {
                        data: 'descRequisito',
                        "render": function(data, type, row) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                return '<a class="text-primary" type="button" onclick="editarRequisito(' +
                                    row.idRequisito +
                                    ')" title="Editar Requisito">' +
                                    data + '</a>';
                            } else {
                                return data;
                            }
                        },
                        name: 'descRequisito',
                    },
                    {
                        data: 'estatusRequisito',
                        "render": function(data, type, row) {
                            if (data == 1) {
                                return '<a onclick="updateEstatus(' + row.idRequisito +
                                    ', ' + data +
                                    ')"><div class="custom-control custom-switch"><input id="estatusRequisito" name="estatusRequisito" type="checkbox" class="custom-control-input" checked><label class="custom-control-label"></label></div></a>';
                            } else {
                                return '<a onclick="updateEstatus(' + row.idRequisito +
                                    ', ' + data +
                                    ')"><div class="custom-control custom-switch"><input id="estatusRequisito" name="estatusRequisito" type="checkbox" class="custom-control-input"><label class="custom-control-label"></label></div></a>';
                            }
                        },
                        name: 'estatusRequisito',
                    },
                ],
                language: {
                    url: "{{ asset('js/spanish-DT.json') }}"
                },
                dom: '<"top">rt<"bottom"lpB><"clear">',
                select: true,
                columnDefs: [{
                        "targets": [2],
                        "searchable": false,
                        orderable: false,
                        className: 'dt-body-center text-center noExport',
                        width: '150px'
                    },
                    {
                        targets: [0],
                        width: '100px'
                    }
                ],
                buttons: [{
                        extend: 'colvis',
                        columns: ':gt(0)',
                        text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Requisitos | CANDY CRM',
                        text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                        exportOptions: {
                            orthogonal: 'export',
                            columns: "thead th:not(.noExport)",
                        },
                        filename: 'Requisitos',
                        autoFilter: true,
                        sheetName: 'Lista',
                        messageTop: null,
                        body: true,
                        footer: false,
                    },
                ],
                bLengthChange: true,
                bInfo: true,
                lengthMenu: [
                    [50, 100, 150, 200],
                    [50, 100, 150, 200]
                ],
                "drawCallback": function(settings) {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                initComplete: function(settings, json) {
                    loaderOut();
                },
            });

            $('#tableRequisitos').DataTable().on("draw", function() {
                ajustarTablas();
            })
        });

        $("#modal-create-requisito").draggable({
            handle: ".titulo"
        });

        function filterGlobal(texto) {
            $('#tableRequisitos').DataTable().search(texto, false, true).draw();
        }

        $('#searchxrequisito').on('keyup click', function() {
            var texto = $(this).val();
            filterGlobal(texto);
        }); //Barra de buscar general
        $('input.global_filter').on('keyup click', function() {
            filterGlobal();
        }); //Busqueda general  

        $('#modal-create-requisito').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
        });

        $('#modal-create-requisito').on('shown.bs.modal', function() {
            $('#descRequisito').trigger('focus');
        });

        var mensaje = '';

        function agregarRequisito() {
            mensaje = 'Requisito agregado';
            save_method = "add";
            $('imput[name=_method]').val('post');
            $('#modal-create-requisito').modal('show');
            $('.titulo').text('Agregar Requisito');
        }

        function editarRequisito(id) {
            mensaje = 'Requisito Actualizado';
            save_method = 'edit';
            $('imput[name=_method]').val('PATCH');
            $.ajax({
                url: "{{ url('requisitos') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {

                    $('#idRequisito').val(data.idRequisito);
                    $('#descRequisito').val(data.descRequisito);
                    $('#categoriaRequisito').val(data.categoriaRequisito);
                    if (data.estatusRequisito == '0') {
                        $('#estatusRequisito').prop('checked', false);
                    } else if (data.estatusRequisito == '1') {
                        $('#estatusRequisito').prop('checked', true);
                    }

                    $('#modal-create-requisito').modal('show');
                    $('.titulo').text('Editar Requisito');
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: 'center',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({
                        title: 'Ocurrió un error',
                    });
                }
            });
        }

        function updateEstatus(id, estatus) {
            mensaje = 'Estatus Actualizado';
            console.log(id + ' / ' + estatus);
            $.ajax({
                url: "{{ route('update.estatus') }}",
                type: 'PATCH',
                data: {
                    _method: 'PATCH',
                    id: id,
                    estatus: estatus,
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
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
                    tableRequisitos.draw();
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
        }

        $(function() {
            $('#modal-create-requisito form').validator().on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#idRequisito').val();
                    if (save_method == 'add') {
                        url = "{{ url('requisitos') }}";
                        type = "POST";
                    } else {
                        url = "{{ url('requisitos') . '/' }}" + id;
                        type = "PUT";
                    }
                    $.ajax({
                        url: url,
                        type: type,
                        data: $('#modal-create-requisito form').serialize(),
                        success: function(data) {
                            $('#modal-create-requisito').trigger("reset");
                            $('#modal-create-requisito').modal('hide');
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
                            tableRequisitos.draw();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: 'center',
                                icon: 'success',
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
