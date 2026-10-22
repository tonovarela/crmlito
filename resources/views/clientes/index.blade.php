@extends('layout.master')

@section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-1">
                    <h1>Clientes</h1>
                </div>
                <div class="col-sm-9 justify-content-md-center">
                    <div class="col input-group input-group-md ">
                        <input type="hidden" name="idRol" id="idRol" value="{{ $idrol }}">
                        <input type="text" id="searchGral" class="form-control" placeholder="Buscar ...">
                        <span class="input-group-append"><button type="button" id="btn-search"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                    </div>
                </div>
                <div class="col-sm-2">
                    <ol class="breadcrumb">
                        {{-- <li class="breadcrumb-item" style="margin-right: 5px;">
                            <button onclick="ajustarTamaño()" type="button" class="btn btn-block btn-primary btn-sm"
                                id="btn-filters" title="Mostrar Filtros">
                                <i class="nav-icon fas fa-filter"></i>
                            </button>
                        </li> --}}
                        @if ($crear === 'Crear')
                            @if ($idrol == '1' || $idrol == '8' || $idrol == '1017')
                                <button type="button" class="btn-flotante button btn btn-primary" data-bs-toggle="tooltip" title="Nuevo Cliente"
                                    data-toggle="modal" data-target="#modal-create-cliente" id="nvoCliente"
                                    onclick="openModal()">
                                    <i class="fas fas fa-plus-circle"></i>
                                </button>
                            @endif
                        @endif

                        @if ($idrol == '1013')
                            <li style="margin-right: 5px;" id="sucursales_li">
                                <a href="{{ route('Sucursales') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Sucursales" id="Sucursales">
                                    <i class="fas fas fa-store"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;" id="contactos_li">
                                <a href="{{ route('Contactos') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Contactos" id="Contactos">
                                    <i class="fas fas fa-address-card"></i>
                                </a>
                            </li>
                        @elseif ($crear === 'Crear')
                            <li style="margin-right: 5px;" id="contactos_li">
                                <a href="{{ route('Contactos') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Contactos" id="Contactos">
                                    <i class="fas fas fa-address-card"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;" id="sucursales_li">
                                <a href="{{ route('Sucursales') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Sucursales" id="Sucursales">
                                    <i class="fas fas fa-store"></i>
                                </a>
                            </li>
                            <li style="margin-right: 5px;" id="requisitos_li">
                                <a href="{{ route('Requisitos') }}" class="button btn btn-block btn-primary btn-sm"
                                    title="Requisitos" id="requisitos">
                                    <i class="fas fas fa-list-ol"></i>
                                </a>
                            </li>
                        @elseif ($idrol == '1010')
                            <li style="margin-right: 5px;" id="contactos_li">
                                <a href="{{ route('Contactos') }}" onclick="loaderON()"
                                    class="button btn btn-block btn-primary btn-sm" title="Contactos" id="Contactos">
                                    <i class="fas fas fa-address-card"></i>
                                </a>
                            </li>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
        <div class="filter-hide" id="card-filtros">{{--  --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filtros</h3>
                </div>
                <div class="card-body">
                    <div class="form-group ">
                        <div class="row justify-content-md-center slt" id="slt-filtro0">
                            <div class="col-sm-9 input-group input-group-sm justify-content-md-left">
                                <div class="input-group-prepend">
                                    <select class="card-primary form-control  select2 slt_filtro" data-placeholder="Filtro"
                                        style="width: 100%;" id="slt-filtro" data-idslt='0'>
                                        <option selected="selected">Elige un Filtro</option>
                                    </select>
                                    {{-- </div> --}}
                                </div>
                                <input type="text" class="column_filter form-control searchCliente_0"
                                    placeholder="Buscar ...">
                                <span class="input-group-append"><button type="button"
                                        class="btn btn-primary btn-flat btn-saveF Sslt_0 btn_SearchColumn" title="Buscar"
                                        data-idslt='0'>
                                        <i class="nav-icon fas fa-search"></i></button>
                                </span>
                                <span class="input-group-append"><button type="button"
                                        class="btn btn-danger btn-flat btn-deleteF Dslt_0" title="Eliminar Filtro"
                                        data-idslt='0'> <i class="nav-icon fas fa-minus"></i></button></span>
                                <span class="input-group-append"><button type="button"
                                        class="btn btn-primary btn-flat btn-saveF Sslt_0" title="Guardar Filtro"
                                        data-idslt='0' hidden> <i class="nav-icon fas fa-save"></i></button></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group " id="box-filtros"></div>
                </div>
                <div class="card-footer">
                    <button id="btn-refresh" class="btn btn-primary btn-refresh btn-flat float-right"><i
                            class="nav-icon fas fa-undo"></i> Limpiar </button>
                    <button class="btn btn-success btn-flat btn-addF float-right"><i class="nav-icon fas fa-plus"> </i>
                        Agregar filtro</button>

                </div>
            </div>
        </div>
        <div class="mt-2">
            <table onclick="ajustarTablas()" id="tableClientes"
                class="table table-hover table-striped nowrap compact tblnombre">
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
    @include('clientes.create')
    <script src=" {{ asset('js/cambiar_agente.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/clientes.js?n=1') }} " defer></script>
@endsection

@section('scripts')
    <script>
        $('#modal-create-cliente').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
            $("#selectReqEspeciales").bootstrapDualListbox('refresh', true);
            $("#selectReqEspecialesF").bootstrapDualListbox('refresh', true);
        });

        $('#modal-create-cliente').on('shown.bs.modal', function() {
            $('#razonsocial').trigger('focus');
        });

        var idrol = document.getElementById('idRol');
        var igualdir = document.getElementById('igualDirCom');
        var codigos = document.getElementById('CodigoPostalLista');

        var calle = document.getElementById('Direccion');
        var numero = document.getElementById('DireccionNumero');
        var numeroint = document.getElementById('DireccionNumeroInt');
        var cp = document.getElementById('CodigoPostal');
        var alcaldia = document.getElementById('Alcaldia');
        var colonia = document.getElementById('Colonia');
        var poblacion = document.getElementById('Poblacion');
        var estado = document.getElementById('Estado');
        var pais = document.getElementById('Pais');

        var rsocial = document.getElementById('razonsocial');
        var nombrecomercial = document.getElementById('LITONombreComercial');
        var agte = document.getElementById('Agente');
        var tels = document.getElementById('Telefonos');
        var email = document.getElementById('eMail1');
        var giro = document.getElementById('Giro');
        var subgiro = document.getElementById('SubGiro');

        // var codigosF = document.getElementById('CPFiscalLista');
        // var calleF = document.getElementById('DireccionFiscal');
        // var numeroF = document.getElementById('DireccionFiscalNumero');
        // var numerointF = document.getElementById('DireccionFiscalNumeroInt');
        // var cpF = document.getElementById('CPFiscal');
        // var alcaldiaF = document.getElementById('AlcaldiaFiscal');
        // var coloniaF = document.getElementById('ColoniaFiscal');
        // var poblacionF = document.getElementById('PoblacionFiscal');
        // var estadoF = document.getElementById('EstadoFiscal');
        // var paisF = document.getElementById('PaisFiscal');

        // var rfc = document.getElementById('RFC');
        // var CreditoCondiciones = document.getElementById('CreditoCondiciones');
        // var FiscalRegimen = document.getElementById('FiscalRegimen');

        var cpslista = document.getElementById('CodigoPostalLista');
        var btnsave = document.getElementById('btnSave');

        rsocial.addEventListener('change', function() {
            validarRequeridos();
        });

        nombrecomercial.addEventListener('change', function() {
            validarRequeridos();
        });

        agte.addEventListener('change', function() {
            validarRequeridos();
        });

        tels.addEventListener('change', function() {
            validarRequeridos();
        });

        email.addEventListener('change', function() {
            validarRequeridos();
        });

        giro.addEventListener('change', function() {
            validarRequeridos();
        });

        subgiro.addEventListener('change', function() {
            validarRequeridos();
        });

        cpslista.addEventListener('change', function() {
            validarRequeridos();
        });

        calle.addEventListener('change', function() {
            validarRequeridos();
        });

        numero.addEventListener('change', function() {
            validarRequeridos();
        });

        cp.addEventListener('change', function() {
            validarRequeridos();
        });

        colonia.addEventListener('change', function() {
            validarRequeridos();
        });

        alcaldia.addEventListener('change', function() {
            validarRequeridos();
        });

        poblacion.addEventListener('change', function() {
            validarRequeridos();
        });

        estado.addEventListener('change', function() {
            validarRequeridos();
        });

        pais.addEventListener('change', function() {
            validarRequeridos();
        });

        function validarRequeridos() {

            if (idrol.value != 7) {
                if (rsocial.value == "" || nombrecomercial.value == "" || agte.value == "Agente" || tels.value == "" || giro
                    .value == "Giro" || subgiro.value == "Sub Giro" ||
                    email.value == "" || calle.value == "" || numero.value == "" || cp.value == "" ||
                    colonia.value == "" || alcaldia.value == "" || poblacion.value == "" || estado.value == "" || pais
                    .value ==
                    "") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (rsocial.value == "") {
                    rsocial.style.borderColor = 'red';
                } else {
                    rsocial.style.borderColor = 'green';
                }

                if (nombrecomercial.value == "") {
                    nombrecomercial.style.borderColor = 'red';
                } else {
                    nombrecomercial.style.borderColor = 'green';
                }

                if (agte.value == "Agente") {
                    agte.style.borderColor = 'red';
                } else {
                    agte.style.borderColor = 'green';
                }

                if (tels.value == "") {
                    tels.style.borderColor = 'red';
                } else {
                    tels.style.borderColor = 'green';
                }

                if (email.value == "") {
                    email.style.borderColor = 'red';
                } else {
                    email.style.borderColor = 'green';
                }

                if (giro.value == "Giro") {
                    giro.style.borderColor = 'red';
                } else {
                    giro.style.borderColor = 'green';
                }

                if (subgiro.value == "Sub Giro") {
                    subgiro.style.borderColor = 'red';
                } else {
                    subgiro.style.borderColor = 'green';
                }

                if (calle.value == "") {
                    calle.style.borderColor = 'red';
                } else {
                    calle.style.borderColor = 'green';
                }

                if (numero.value == "") {
                    numero.style.borderColor = 'red';
                } else {
                    numero.style.borderColor = 'green';
                }

                if (cp.value == "") {
                    cp.style.borderColor = 'red';
                } else {
                    cp.style.borderColor = 'green';
                }

                if (colonia.value == "") {
                    colonia.style.borderColor = 'red';
                } else {
                    colonia.style.borderColor = 'green';
                }

                if (alcaldia.value == "") {
                    alcaldia.style.borderColor = 'red';
                } else {
                    alcaldia.style.borderColor = 'green';
                }

                if (poblacion.value == "") {
                    poblacion.style.borderColor = 'red';
                } else {
                    poblacion.style.borderColor = 'green';
                }

                if (estado.value == "") {
                    estado.style.borderColor = 'red';
                } else {
                    estado.style.borderColor = 'green';
                }

                if (pais.value == "") {
                    pais.style.borderColor = 'red';
                } else {
                    pais.style.borderColor = 'green';
                }
            } else {
                if (rsocial.value == "" || nombrecomercial.value == "" || agte.value == "Agente") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (rsocial.value == "") {
                    rsocial.style.borderColor = 'red';
                } else {
                    rsocial.style.borderColor = 'green';
                }

                if (nombrecomercial.value == "") {
                    nombrecomercial.style.borderColor = 'red';
                } else {
                    nombrecomercial.style.borderColor = 'green';
                }

                if (agte.value == "Agente") {
                    agte.style.borderColor = 'red';
                } else {
                    agte.style.borderColor = 'green';
                }

            }

            if ($('#Pais').val() === "MÉXICO" || $('#Pais').val() === "PAÍS") {
                CodigoPostal.readOnly = false;
                alcaldia.readOnly = false;
                colonia.readOnly = false;
                poblacion.readOnly = false;
                estado.readOnly = false;

                // $('#CodigoPostal').val('');
                // $('#Alcaldia').val('');
                // $('#Colonia').val('');
                // $('#Poblacion').val('');
                // $('#Estado').val('');
                // $('#CodigoPostalLista').val('');
            } else if ($('#Pais').val() === "ESTADOS UNIDOS" || $('#Pais').val() === "ESPAÑA") {
                CodigoPostal.readOnly = false;
                alcaldia.readOnly = false;
                colonia.readOnly = false;
                poblacion.readOnly = false;
                estado.readOnly = false;

            } else {
                CodigoPostal.readOnly = true;
                alcaldia.readOnly = true;
                colonia.readOnly = true;
                poblacion.readOnly = false;
                estado.readOnly = true;
            }
        }

        $(document).ready(function() {
            validarRequeridos();
            if (idrol.value == 4) {
                estop.disabled = true;
                buro.disabled = true;
            } else {
                estop.disabled = false;
                buro.disabled = false;
            }

            // if (idrol.value == 5 || idrol.value == 8 || idrol.value == 1) {
            //     calleF.readOnly = false;
            //     numeroF.readOnly = false;
            //     numerointF.readOnly = false;
            //     cpF.readOnly = false;
            //     alcaldiaF.readOnly = false;
            //     coloniaF.readOnly = false;
            //     poblacionF.readOnly = false;
            //     estadoF.readOnly = false;
            //     paisF.readOnly = false;
            //     codigosF.disabled = false;
            //     document.getElementById("spanDirFiscal").style.display = "block";
            //     rfc.readOnly = false;
            //     CreditoCondiciones.readOnly = false;
            //     FiscalRegimen.readOnly = false;
            // } else {
            //     calleF.readOnly = true;
            //     numeroF.readOnly = true;
            //     numerointF.readOnly = true;
            //     cpF.readOnly = true;
            //     alcaldiaF.readOnly = true;
            //     coloniaF.readOnly = true;
            //     poblacionF.readOnly = true;
            //     estadoF.readOnly = true;
            //     paisF.readOnly = true;
            //     codigosF.disabled = true;
            //     document.getElementById("spanDirFiscal").style.display = "none";
            //     rfc.readOnly = true;
            //     CreditoCondiciones.readOnly = true;
            //     FiscalRegimen.readOnly = true;
            // }

            // igualdir.addEventListener('change', function() {
            //     igualDireccionComercial(igualdir.checked);
            // });

            // function igualDireccionComercial(check) {
            //     if (check == true) {
            //         calleF.value = calle.value;
            //         numeroF.value = numero.value;
            //         numerointF.value = numeroint.value;
            //         cpF.value = cp.value;
            //         alcaldiaF.value = alcaldia.value;
            //         coloniaF.value = colonia.value;
            //         poblacionF.value = poblacion.value;
            //         estadoF.value = estado.value;
            //         paisF.value = pais.value;
            //         calleF.readOnly = true;
            //         numeroF.readOnly = true;
            //         numerointF.readOnly = true;
            //         cpF.readOnly = true;
            //         alcaldiaF.readOnly = true;
            //         coloniaF.readOnly = true;
            //         poblacionF.readOnly = true;
            //         estadoF.readOnly = true;
            //         paisF.readOnly = true;
            //         codigosF.disabled = true;
            //     } else {
            //         calleF.value = '';
            //         numeroF.value = '';
            //         numerointF.value = '';
            //         cpF.value = '';
            //         alcaldiaF.value = '';
            //         coloniaF.value = '';
            //         poblacionF.value = '';
            //         estadoF.value = '';
            //         paisF.value = '';
            //         calleF.readOnly = false;
            //         numeroF.readOnly = false;
            //         numerointF.readOnly = false;
            //         cpF.readOnly = false;
            //         alcaldiaF.readOnly = false;
            //         coloniaF.readOnly = false;
            //         poblacionF.readOnly = false;
            //         estadoF.readOnly = false;
            //         paisF.readOnly = false;
            //         codigosF.disabled = false;
            //     }
            // }
        });


        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {

            $("#CodigoPostalLista").autocomplete({
                delay: 0,
                minLength: 4,
                source: function(request, response) {
                    // Fetch data
                    $.ajax({
                        url: "{{ route('codigosP') }}",
                        type: 'GET',
                        dataType: "json",
                        data: {
                            _token: CSRF_TOKEN,
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    var codigo = JSON.stringify(ui.item.value)
                    $.ajax({
                        url: "{{ route('buscarCP') }}",
                        dataType: "json",
                        data: {
                            id_Codigo: codigo
                        },
                        success: function(data) {
                            cp.value = data[0].Codigo;
                            alcaldia.value = data[0].Municipio;
                            colonia.value = data[0].Asentamiento;
                            if (data[0].Localidad == null) {
                                poblacion.value = "";
                            } else {
                                poblacion.value = data[0].Localidad;
                            }
                            estado.value = data[0].Estado;
                            pais.value = data[0].Pais;
                        },
                        error: function(data) {
                            cp.value = "";
                            alcaldia.value = "";
                            colonia.value = "";
                            poblacion.value = "";
                            estado.value = "";
                            pais.value = "";
                        }

                    });
                    return false;
                }
            });

            // $("#CPFiscalLista").autocomplete({
            //     delay: 0,
            //     minLength: 4,
            //     source: function(request, response) {
            //         // Fetch data
            //         $.ajax({
            //             url: "{{ route('codigosP') }}",
            //             type: 'GET',
            //             dataType: "json",
            //             data: {
            //                 _token: CSRF_TOKEN,
            //                 search: request.term
            //             },
            //             success: function(data) {
            //                 response(data);
            //             }
            //         });
            //     },
            //     select: function(event, ui) {
            //         var codigo = JSON.stringify(ui.item.value)
            //         $.ajax({
            //             url: "{{ route('buscarCP') }}",
            //             dataType: "json",
            //             data: {
            //                 id_Codigo: codigo
            //             },
            //             success: function(data) {
            //                 cpF.value = data[0].Codigo;
            //                 alcaldiaF.value = data[0].Municipio;
            //                 coloniaF.value = data[0].Tipo_Asenta + ' ' + data[0]
            //                     .Asentamiento;
            //                 poblacionF.value = data[0].Ciudad;
            //                 estadoF.value = data[0].Estado;
            //                 paisF.value = "MX";
            //             },
            //             error: function(data) {
            //                 cpF.value = "";
            //                 alcaldiaF.value = "";
            //                 coloniaF.value = "";
            //                 poblacionF.value = "";
            //                 estadoF.value = "";
            //                 paisF.value = "";
            //             }
            //         });
            //         return false;
            //     }
            // });
        });

        let ini = document.querySelector('#modal-create-cliente');
        ini.classList.add("hideModal");

        function openModal() {
            let modal = document.querySelector('#modal-create-cliente');
            modal.classList.add("showModal");

        }

        function openModalEdit(id) {
            var modal = document.querySelector('#modal-edit-cliente');
            // modal.classList.add("showModal");



            mensaje = 'Cliente Actualizado';
            save_method = 'edit';
            $('imput[name=_method]').val('PATCH');
            // $('#modal-edit-cliente form')[0].reset();


            $.ajax({
                url: "{{ url('clientes') }}" + '/' + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);
                    // var texto = data.fecha_compra;
                    // var fechaCompra = formato(texto);

                    $('#idUsuarioEdit').val(data.JSON);
                    $('#razonsocialEdit').val(data[0].razonsocial);
                    // $('#descripcion').val(data.descripcion);
                    // $('#categoria').val(data.categoria);
                    // $('#marca').val(data.marca);
                    // $('#modelo').val(data.modelo);
                    // $('#numero_serie').val(data.numero_serie);
                    // $('#numero_licencia').val(data.numero_licencia);
                    // $('#numero_proveedor').val(data.numero_proveedor);
                    // $('#nombre_proveedor').val(data.nombre_proveedor);
                    // $('#numero_factura').val(data.numero_factura);
                    // $('#fecha_compra').val(fechaCompra);

                    // $('#numero_factura').readOnly = true;
                    // $('#fecha_compra').readOnly = true;

                    // $('#ajaxModal').modal('show');
                    // $('.modal-title').text('Editar Producto');

                    // function formato(texto) {
                    //     return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
                    // };
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

        function eliminarCliente(id) {
            mensaje = 'Cliente Actualizado';
            id_suario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Clientes',
                text: 'Activar/Desactivar Cliente Seleccionado?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('darDeBaja') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            id: id,
                            id_suario: id_suario.value,
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
                            dataTableClientes.ajax.reload();
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
                    })
                }
            })

        }

        function actualizarBuro(id) {
            mensaje = 'Cliente Actualizado';
            id_suario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Clientes',
                text: 'Actualizar Buró del Cliente Seleccionado?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('actualizarBuro') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            id: id,
                            id_suario: id_suario.value,
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
                            dataTableClientes.ajax.reload();
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
                    })
                }
            })

        }

        function actualizarTop(id) {
            mensaje = 'Cliente Actualizado';
            id_suario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Clientes',
                text: 'Actualizar Top del Cliente Seleccionado?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('actualizarTop') }}",
                        type: "PATCH",
                        data: {
                            '_method': 'PATCH',
                            id: id,
                            id_suario: id_suario.value,
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
                            dataTableClientes.ajax.reload();
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
                    })
                }
            })

        }

        function closeM() {

            let m = document.querySelector('#modal-create-cliente');
            m.classList.remove("showModal");

        }

        function closeMEdit() {

            let m = document.querySelector('#modal-edit-cliente');
            m.classList.remove("showModal");

        }

        function ajustarTamaño() {
            // var tablaCtes = document.getElementById("tableClientes");
            // var filtros = document.getElementById("card-filtros");

            // var height = tablaCtes.clientHeight - filtros.clientHeight;

            // console.log(tablaCtes.clientHeight);
            // console.log(filtros.clientHeight);
            // console.log(height);

            // $('#tableClientes').closest('.dataTables_scrollBody').css('height', height);

        }

        // var select_entrega = $('select[name="selectReqEspeciales_Entrega[]"]').val();
        // console.log(select_entrega);

        const $btnSave = document.querySelector("#btnSave");

        $('#modal-create-cliente').submit(function(evento) {
            $btnSave.disabled = true;
            loaderON();
        });
    </script>
@endsection
