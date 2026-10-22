@extends('layout.master')

@section('content')
    @include('partials.loading')
    <div id="formOporCreate" class="col-12 mt-2">
        <form method="post" action="{{ route('oportunidades.store') }}" class="form-horizontal" data-toggle="validator">
            {{ csrf_field() }} {{ method_field('POST') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" value="{{ $idEquipo_idAgente }}" name="idEquipo_idAgente" id="idEquipo_idAgente">
            <div>
                <div class="rounded bg-gray">
                    <div class="row mb-2">
                        <div class="col-12 text-center">
                            <h6 class="text-bold pt-1 pl-1">Nueva Oportunidad</h6>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="input-group input-group-sm">
                        <h6 class="text-right text-bold text-red pr-1 pt-2">&nbsp;No.
                            {{ $folio }} - &nbsp;
                        </h6>
                        <label class="mt-2" for="nomOportunidad">Oportunidad &nbsp;</label>
                        <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10 text-uppercase"
                            id="nomOportunidad" name="nomOportunidad" placeholder="Requerido" autocomplete="off" autofocus>
                        <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                            <span class="col-md-6"><button onclick="loaderON()" id="btnSave" type="submit"
                                    class="btn btn-primary btn-sm btn-block" disabled>Guardar</button></span>
                            <span class="col-md-6 center"><a class="btn btn-secondary btn-sm btn-block"
                                    onclick="regresar()">Regresar</a></span>
                        </div>
                    </div>
                </div>
                <div>
                    <div id="scrollbarTabsModalRound" class="tab-content">
                        <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel"
                            aria-labelledby="custom-content-below-home-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="idCliente">Cliente</label>
                                                <select id="idCliente" name="idCliente"
                                                    class="form-control select2 select2-height"
                                                    style="width: 100%; text-transform: uppercase">
                                                    <option selected disabled>Requerido</option>
                                                    @foreach ($combo_clientes as $cliente)
                                                        <option value="{{ $cliente->idCliente }}">
                                                            {{ $cliente->razonsocial }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="idContacto">Contacto</label>
                                                <select id="idContacto" name="idContacto"
                                                    class="form-control select2 select2-height"
                                                    style="width: 100%; text-transform: uppercase">
                                                    <option selected disabled>Requerido</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="idTipo">Tipo de Oportunidad</label>
                                                <select id="idTipo" name="idTipo" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($tipos as $tipo)
                                                        <option value="{{ $tipo->idTipoOportunidad }}">
                                                            {{ $tipo->desTipoOportundidad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if (in_array($rol, [1, 10, 8, 2, 3]) || $id_usuario == 29)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idAgente">Asignado a</label>
                                                <select id="idAgente" name="idAgente" class="form-control"
                                                    style="width: 100%;" required>
                                                    <option value="Requerido" selected disabled>Requerido</option>
                                                    @foreach ($combo_vendedores as $vendedor)
                                                        @if ($vendedor->Id_Usuario == $idEquipo_idAgente)
                                                            <option value="{{ $vendedor->Id_Usuario }}" selected>
                                                                {{ $vendedor->Nombre }}</option>
                                                        @else
                                                            <option value="{{ $vendedor->Id_Usuario }}">
                                                                {{ $vendedor->Nombre }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-lg-3 mt-1">
                                            <div class="form-group">
                                                <div class="tooltip">
                                                    <div class="custom-control custom-switch mt-3">
                                                        <input id="seRequiereMaquila" name="seRequiereMaquila" type="checkbox"
                                                            class="custom-control-input">
                                                        <label class="custom-control-label" for="seRequiereMaquila">Maquila (Compra Venta de Productos)</label>
                                                    </div>

                                                    <span class="tooltiptext">(Se necesita solicitar la compraventa de productos externos)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4">
                                                    <input id="Maquila" name="Maquila" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="Maquila">Marcar si es maquila (lleva algún proceso de maquila)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4" title="Marcar la oportunidad tiene en Excel los elementos presupuestados.">
                                                    <input id="tieneExcel" name="tieneExcel" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="tieneExcel">Marcar si tiene EPS Excel</label>
                                                </div>
                                            </div>
                                        </div>
                                        {{--  <div class="col-lg-3 mt-1">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch mt-4">
                                                    <input id="Premio" name="Premio" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="Premio">Trabajo para
                                                        Premio</label>
                                                </div>
                                            </div>
                                        </div>  --}}
                                        <div class="col-lg-3 mt-1">
                                            <div class="form-group">
                                                <label>Tipo de tabla para ocupar</label>
                                                <div id="tipoTablaComision"><span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="Sin seleccionar"><strong>Sin seleccionar</strong></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-12">
                                            <label for="nota">Nota comercial</label>
                                            <div class="tooltip w-100 mt-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control text-uppercase" id="nota" name="nota"
                                                        placeholder="Nota" autocomplete="off">
                                                    {{--  Tooltip  --}}
                                                    <span class="tooltiptext">Especificar la mayor información posible para poder tomar decisiones de precio, ej: precio referencia, margen anteriores, volúmenes de compra, feedback previo del cliente, etc.</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src=" {{ asset('js/oportunidades.js?n=1') }} " defer></script>
    <script src=" {{ asset('js/oportunidades_Docs.js?n=1') }} " defer></script>
@endsection

@section('scripts')
    <script>
        var nomoportunidad = document.getElementById('nomOportunidad');
        var idcliente = document.getElementById('idCliente');
        var idcontacto = document.getElementById('idContacto');
        // var fecPrevCiereInput = document.getElementById('fecPrevCiereInput');
        var btnsave = document.getElementById('btnSave');
        var nota = document.getElementById('nota');
        var idAgente = document.getElementById('idAgente');

        $(document).ready(function() {

            loaderOut();

            var idcliente = document.getElementById('idCliente');
            var idcontacto = document.getElementById('idContacto');
            var idClienteSelec2 = $('#idCliente').select2({
                language: "es"
            });
            var idContactoSelec2 = $('#idContacto').select2({
                language: "es"
            });

            validarRequeridosOpor();

            nomoportunidad.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            idClienteSelec2.on('change', function() {
                validarRequeridosOpor();
            });

            idContactoSelec2.on('change', function() {
                validarRequeridosOpor();
            });

            if (idAgente) {
                idAgente.addEventListener('change', function() {
                    validarRequeridosOpor();
                });

                idAgente.addEventListener('focusin', function() {
                    validarRequeridosOpor();
                });
            }

            nota.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            function validarRequeridosOpor() {

                var idClienteSelec2 = $('#idCliente').select2("data");
                var idContactoSelec2 = $('#idContacto').select2("data");

                if (nomoportunidad.value == "" || idcliente.value == "Requerido" || idcontacto.value ==
                    "Requerido" || nota.value == "") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (nomoportunidad.value == "") {
                    nomoportunidad.style.borderColor = 'red';
                } else {
                    nomoportunidad.style.borderColor = 'green';
                }

                if (idClienteSelec2[0].element.value != "Requerido") {
                    $('#idCliente').select2().addClass('select2-green')
                } else {
                    $('#idCliente').select2().addClass('select2-red')
                }

                if (idContactoSelec2[0].element.value != "Requerido") {
                    $('#idContacto').select2().addClass('select2-green')
                } else {
                    $('#idContacto').select2().addClass('select2-red')
                }

                if (idAgente) {
                    if (idAgente.value == "Requerido") {
                        idAgente.style.borderColor = 'red';
                    } else {
                        idAgente.style.borderColor = 'green';
                    }
                }

                if (nota.value == "") {
                    nota.style.borderColor = 'red';
                } else {
                    nota.style.borderColor = 'green';
                }

            }

            var omtc = document.getElementById('numOrdenMTC');

            $('input:radio[name="idStatus"]').change(function() {

                console.log(this.value);

                if (this.checked && this.value == '9') {
                    omtc.disabled = false;
                } else {
                    omtc.disabled = true;
                    omtc.value = '';
                }
            });

            var estatus = $('#Estatus').val();

            if (estatus == 1) {
                $("#idStatusPre").prop('disabled', false);
                $("#idStatusSolCot").prop('disabled', false);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }

            if (estatus == 2) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', false);
                $("#idStatusListoCot").prop('disabled', false);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 3) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', false);
                $("#idStatusCot").prop('disabled', false);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 4) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', false);
                $("#idStatusRev").prop('disabled', false);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 5) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', false);
                $("#idStatusVoBo").prop('disabled', false);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 6) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', false);
                $("#idStatusListoPre").prop('disabled', false);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 7) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', false);
                $("#idStatusPreTerm").prop('disabled', false);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 8) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', false);
                $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', false);
                $("#idStatusSolOP").prop('disabled', true);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 9) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', false);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', false);
                $("#idStatusOPCon").prop('disabled', true);
            }
            if (estatus == 11) {
                $("#idStatusPre").prop('disabled', true);
                $("#idStatusSolCot").prop('disabled', true);
                $("#idStatusListoCot").prop('disabled', true);
                $("#idStatusCot").prop('disabled', true);
                $("#idStatusRev").prop('disabled', true);
                $("#idStatusVoBo").prop('disabled', true);
                $("#idStatusListoPre").prop('disabled', true);
                $("#idStatusPreTerm").prop('disabled', true);
                $("#idStatusGanado").prop('disabled', true);
                $("#idStatusPerdido").prop('disabled', true);
                $("#idStatusSolOP").prop('disabled', false);
                $("#idStatusOPCon").prop('disabled', false);
            }

            if (estatus == 10 || estatus == 12) {
                $("#btnSave").prop('disabled', true);

                // $("#infGeneral").addClass("disable");

                var nodes = document.getElementById("infGeneral").getElementsByTagName('*');
                for (var i = 0; i < nodes.length; i++) {
                    nodes[i].disabled = true;
                }

                $("#elePre").addClass("disable");

                var nodesEle = document.getElementById("elePre").getElementsByTagName('*');
                for (var j = 0; j < nodesEle.length; j++) {
                    nodesEle[j].disabled = true;
                }
            }

        });

        $(function() {

            $("#idCliente").change(function() {
                var contacto = $("#idContacto");
                var clientes = $(this);
                var agente_id = $('#idUsuario').val();
                var idCliente_idContacto = $('#idCliente_idContacto').val();

                if ($(this).val() != "") {
                    getTypeTablaCliente(clientes.val());

                    $.ajax({
                        data: {
                            idCliente: clientes.val()
                        },
                        url: "{{ route('getContactosOportunidades') }}",
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            clientes.prop("disabled", true);
                        },
                        success: function(r) {
                            clientes.prop("disabled", false);

                            contacto.find("option").remove();

                            contacto.append(
                                '<option value="Requerido" selected disabled>Requerido</option>'
                            );

                            contacto.append(
                                '<option value="0">PROSPECTO</option>'
                            );

                            $(r).each(function(i, v) {
                                contacto.append(
                                    '<option value="' +
                                    v.idContacto +
                                    '">' +
                                    v.Nombre +
                                    "</option>"
                                );
                            });

                            contacto.prop("disabled", false);

                        },
                        error: function(r) {
                            Swal.fire("Ocurrio un error en el servidor ..")
                            clientes.prop("disabled", false);
                        },
                    });
                } else {
                    contacto.find("option").remove();
                    contacto.prop("disabled", true);
                }
            });
        });

        /**
        * Función para obtener y pintar el icono del tipo de tabla de comisiones
        * 
        * @param idCliente
        * @return void
        */
        function getTypeTablaCliente(idCliente)
        {
            formActionGetTbl = new FormData();
            formActionGetTbl.append("_token", CSRF_TOKEN);
            formActionGetTbl.append("_method", "POST");
            formActionGetTbl.append("idCliente", idCliente);

            // Función para actualizar la cotización en la base de candy
            $.ajax({
                url: serve + "getUserTblCom",
                async: true,
                type: "POST",
                data: formActionGetTbl,
                processData: false,
                contentType: false,
                success: function (data) {
                    $('#tipoTablaComision').html('');
                    if (data.tbl) {
                        if (data.tbl === "T2") {
                            $('#tipoTablaComision').append(
                                '<span class="badge badge-success pl-2 pr-2 pt-1 pb-1" title="TABLA DE COMISIÓN 2"><strong>Tabla de comisión 2</strong></span>'
                            );
                        } else if (data.tbl === "T1") {
                            $('#tipoTablaComision').append(
                                '<span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="TABLA DE COMISIÓN 1"><strong>Tabla de comisión 1</strong></span>'
                            );
                        }
                    }
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error, al realizar la acción" });
                }
            })
        }
    </script>
@endsection
