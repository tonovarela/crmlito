@extends('layout.master')

@section('content')
    <div class="container-fluid">
        @include('partials.loading')
        <div class="content-header">
            <div class="row mb-n2">
                <div class="col-sm-2">
                    <h1>Prospectos</h1>
                </div>
                <div class="col-sm-7 justify-content-md-center">
                    <input type="hidden" name="rolId" id="rolId" value="{{ $idrol }}">
                    <div class="col-sm-11 input-group input-group-sm ">
                        <input type="text" id="searchGral" class="form-control" placeholder="Buscar ...">
                        <span class="input-group-append"><button type="button" id="btn-search"
                                class="btn btn-primary btn-flat"><i class="nav-icon fas fa-search"></i></button></span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <ol class="breadcrumb">
                        @if ($crear === 'Crear')
                            <button type="button" class="btn-flotante button btn btn-primary" data-bs-toggle="tooltip" title="Nuevo Prospecto"
                                data-toggle="modal" data-target="#modal_create_prospecto" id="nvoProspecto"
                                onclick="openModal()">
                                <i class="fas fas fa-plus-circle"></i>
                            </button>
                        @endif
                    </ol>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <table onclick="ajustarTablas()" id="tableProspectos"
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
                    </tr>
                </thead>
                <tfoot></tfoot>
            </table>
        </div>
    </div>
    @include('prospectos.create')
    <script src=" {{ asset('js/prospectos.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/cambiar_agente.js?n=1') }} " defer></script>
@endsection

@section('scripts')
    <script>
        $('#modal_create_prospecto').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
        });

        $('#modal_create_prospecto').on('shown.bs.modal', function() {
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

        var cpslista = document.getElementById('CodigoPostalLista');
        var btnsave = document.getElementById('btnSave');

        var nombreContacto = document.getElementById('NombreContacto');
        var telefonosContacto = document.getElementById('Telefonos1Contacto');
        var emailContacto = document.getElementById('eMailContacto');

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

        nombreContacto.addEventListener('change', function() {
            validarRequeridos();
        });

        telefonosContacto.addEventListener('change', function() {
            validarRequeridos();
        });

        emailContacto.addEventListener('change', function() {
            validarRequeridos();
        });

        function validarRequeridos() {

            if (idrol.value != 7) {
                if (rsocial.value == "" || nombrecomercial.value == "" || agte.value == "Agente" || tels.value == "" || giro
                    .value == "Giro" || subgiro.value == "Sub Giro" ||
                    email.value == "" || calle.value == "" || numero.value == "" || cp.value == "" || colonia.value == "" ||
                    alcaldia.value == "" ||
                    poblacion.value == "" || estado.value == "" || pais.value == "" || nombreContacto.value == "" ||
                    telefonosContacto.value == "" ||
                    emailContacto.value == "") {
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

                if (nombreContacto.value == "") {
                    nombreContacto.style.borderColor = 'red';
                } else {
                    nombreContacto.style.borderColor = 'green';
                }

                if (telefonosContacto.value == "") {
                    telefonosContacto.style.borderColor = 'red';
                } else {
                    telefonosContacto.style.borderColor = 'green';
                }

                if (emailContacto.value == "") {
                    emailContacto.style.borderColor = 'red';
                } else {
                    emailContacto.style.borderColor = 'green';
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
            // if (idrol.value == 4) {
            //     estop.disabled = true;
            //     buro.disabled = true;
            // } else {
            //     estop.disabled = false;
            //     buro.disabled = false;
            // }

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
        });

        let ini = document.querySelector('#modal_create_prospecto');
        ini.classList.add("hideModal");

        function openModal() {
            let modal = document.querySelector('#modal_create_prospecto');
            modal.classList.add("showModal");

        }

        function openModalEdit(id) {
            // var modal = document.querySelector('#modal-edit-cliente');
            // modal.classList.add("showModal");



            mensaje = 'Prospecto Actualizado';
            save_method = 'edit';
            $('imput[name=_method]').val('PATCH');
            // $('#modal-edit-cliente form')[0].reset();


            $.ajax({
                url: "{{ url('prospectos') }}" + '/' + id + "/edit",
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
            mensaje = 'Prospecto Actualizado';
            id_suario = document.getElementById('idUsuario');
            Swal.fire({
                title: 'Prospectos',
                text: 'Activar/Desactivar Prospecto Seleccionado?',
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
                            dataTableProspectos.ajax.reload();
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

            let m = document.querySelector('#modal_create_prospecto');
            m.classList.remove("showModal");

        }

        // function closeMEdit() {

        //     let m = document.querySelector('#modal-edit-cliente');
        //     m.classList.remove("showModal");

        // }

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

        $('#modal_create_prospecto').submit(function(evento) {
            $btnSave.disabled = true;
            loaderON();
        });
    </script>
@endsection