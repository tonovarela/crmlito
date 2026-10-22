@extends('layout.master')

@section('content')
    <div id="formEnvioCreate" class="col-12 mt-2">
        <form method="post" action="{{ route('envios.store') }}" class="form-horizontal" data-toggle="validator">
            {{ csrf_field() }} {{ method_field('POST') }}
            <input type="hidden" value="{{ $id_usuario }}" name="idUsuario" id="idUsuario">
            <input type="hidden" name="idOportunidad" id="idOportunidad">
            <div>
                <div class="rounded bg-gray">
                    <div class="row mb-2">
                        <div class="col-12 text-center">
                            <h6 id="tituloEnvío" class="text-bold pt-1 pl-1">Nuevo Envío</h6>
                        </div>
                    </div>
                </div>
                <div class="mb-1">
                    <div class="input-group input-group-sm">
                        <h6 class="text-right text-bold text-red pr-1 pt-2">&nbsp;No.
                            {{ $folio }} - &nbsp;
                        </h6>
                        <label class="mt-2" for="nomOportunidad">Envío &nbsp;</label>
                        <input type="text" class="form-control col-md-8 col-lg-9 col-xl-10" id="nomOportunidad"
                            name="nomOportunidad" placeholder="Requerido" autocomplete="off" autofocus>
                        <div class="input-group-append col-md-4 col-lg-3 col-xl-2">
                            <span class="col-md-6"><button id="btnSave" type="submit"
                                    class="btn btn-primary btn-sm btn-block" disabled>Guardar</button></span>
                            <span class="col-md-6 center"><a href="{{ route('Envíos') }}"
                                    class="btn btn-secondary btn-sm btn-block">Regresar</a></span>
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idCliente">Cliente</label>
                                                <select id="idCliente" name="idCliente" class="form-control"
                                                    style="width: 100%">
                                                    <option selected disabled>Requerido</option>
                                                    @foreach ($combo_clientes as $cliente)
                                                        <option value="{{ $cliente->idCliente }}">
                                                            {{ $cliente->razonsocial }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idTipo">Tipo de Envío</label>
                                                <select id="idTipo" name="idTipo" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($tipos as $tipo)
                                                        <option value="{{ $tipo->idTipoOportunidad }}">
                                                            {{ $tipo->desTipoOportundidad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="totDestinos">Total Destinos</label>
                                                <input type="text" class="form-control" id="totDestinos"
                                                    name="totDestinos" placeholder="Total Destinos" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="tamPieza">Tamaño Pieza</label>
                                                <input type="text" class="form-control" id="tamPieza" name="tamPieza"
                                                    placeholder="Tamaño Pieza" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pesoPieza">Peso Pieza</label>
                                                <input type="text" class="form-control" id="pesoPieza" name="pesoPieza"
                                                    placeholder="Peso Pieza" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Equipos</label>
                                                <select id="idEquipo" name="idEquipo" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($equipos as $equipo)
                                                        <option value="{{ $equipo->idEquipo }}">
                                                            {{ $equipo->descEquipo }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idAgente">Asignado a</label>
                                                <select id="idAgente" name="idAgente" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($combo_vendedores as $vendedor)
                                                        <option value="{{ $vendedor->Id_Usuario }}">
                                                            {{ $vendedor->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="idAtendido">Atendido por</label>
                                                <select id="idAtendido" name="idAtendido" class="form-control"
                                                    style="width: 100%;">
                                                    @foreach ($combo_cotizadores as $cotizadores)
                                                        <option value="{{ $cotizadores->Id_Usuario }}">
                                                            {{ $cotizadores->Nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="rounded col-sm-12 bg-gray">
                                            <h6 class="text-bold text-center">RELEVANCIA DEL SERVICIO</h6>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="opcMasEconomico" name="opcMasEconomico" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="opcMasEconomico">Mas
                                                        Económico</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="opcMasRapido" name="opcMasRapido" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="opcMasRapido">Mas
                                                        Rápido</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="opcConSeguro" name="opcConSeguro" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="opcConSeguro">Con
                                                        Seguro</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="rounded col-sm-12 bg-gray">
                                            <h6 class="text-bold text-center">CONFIRMAR OPCIÓN DE ENVÍO</h6>
                                        </div>
                                        <div class="col-sm-12 text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input id="opcConfirmarEnvio" name="opcConfirmarEnvio" type="checkbox"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="opcConfirmarEnvio">Confirmar
                                                        Opción envío</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="rounded col-sm-12 bg-gray mb-1">
                                            <h6 class="text-bold text-center">ESTADÍSTICOS ENVÍOS</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecAlta">Fecha Alta</label>
                                            <div class="input-group">
                                                <input id="fecAlta" type="text" class="form-control" autocomplete="off"
                                                    placeholder="dd/mm/aaaa" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="folio">Usuario Alta</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="idUsuarioAlta"
                                                    placeholder="Usuario Alta" autocomplete="off" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecTop">Fecha Top</label>
                                            <div class="input-group">
                                                <input id="fecTop" name="fecTop" type="date" class="form-control"
                                                    autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="fecUltCambio">Fecha Ult. Cambio</label>
                                            <div class="input-group">
                                                <input id="fecUltCambio" type="text" class="form-control"
                                                    autocomplete="off" placeholder="dd/mm/aaaa" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="idUsuarioUltCambio">Usuario Ult. Cambio</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="idUsuarioUltCambio"
                                                    name="idUsuarioUltCambio" placeholder="Usuario Ult. Cambio"
                                                    autocomplete="off" disabled>
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
@endsection

@section('scripts')
    <script>
        var nomoportunidad = document.getElementById('nomOportunidad');
        var idcliente = document.getElementById('idCliente');
        var btnsave = document.getElementById('btnSave');

        $(document).ready(function() {

            loaderOut();

            validarRequeridosOpor();

            nomoportunidad.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            idcliente.addEventListener('change', function() {
                validarRequeridosOpor();
            });

            function validarRequeridosOpor() {

                // console.log(idcliente.value);

                if (nomoportunidad.value == "" || idcliente.value == "Requerido" || idcliente.value ==
                    "requerido") {
                    btnsave.disabled = true;
                } else {
                    btnsave.disabled = false;
                }

                if (nomoportunidad.value == "") {
                    nomoportunidad.style.borderColor = 'red';
                } else {
                    nomoportunidad.style.borderColor = 'green';
                }

                if (idcliente.value == "Requerido" || idcliente.value == "requerido") {
                    idcliente.style.borderColor = 'red';
                } else {
                    idcliente.style.borderColor = 'green';
                }

            }



            // var omtc = document.getElementById('numOrdenMTC');

            // $('input:radio[name="idStatus"]').change(function() {

            //     console.log(this.value);

            //     if (this.checked && this.value == '9') {
            //         omtc.disabled = false;
            //     } else {
            //         omtc.disabled = true;
            //         omtc.value = '';
            //     }
            // });

            // var estatus = $('#Estatus').val();

            // if (estatus == 1) {
            //     $("#idStatusPre").prop('disabled', false);
            //     $("#idStatusSolCot").prop('disabled', false);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }

            // if (estatus == 2) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', false);
            //     $("#idStatusListoCot").prop('disabled', false);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 3) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', false);
            //     $("#idStatusCot").prop('disabled', false);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 4) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', false);
            //     $("#idStatusRev").prop('disabled', false);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 5) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', false);
            //     $("#idStatusVoBo").prop('disabled', false);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 6) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', false);
            //     $("#idStatusListoPre").prop('disabled', false);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 7) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', false);
            //     $("#idStatusPreTerm").prop('disabled', false);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 8) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', false);
            //     $("#idStatusGanado").prop('disabled', false);
            //     $("#idStatusPerdido").prop('disabled', false);
            //     $("#idStatusSolOP").prop('disabled', true);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 9) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', false);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', false);
            //     $("#idStatusOPCon").prop('disabled', true);
            // }
            // if (estatus == 11) {
            //     $("#idStatusPre").prop('disabled', true);
            //     $("#idStatusSolCot").prop('disabled', true);
            //     $("#idStatusListoCot").prop('disabled', true);
            //     $("#idStatusCot").prop('disabled', true);
            //     $("#idStatusRev").prop('disabled', true);
            //     $("#idStatusVoBo").prop('disabled', true);
            //     $("#idStatusListoPre").prop('disabled', true);
            //     $("#idStatusPreTerm").prop('disabled', true);
            //     $("#idStatusGanado").prop('disabled', true);
            //     $("#idStatusPerdido").prop('disabled', true);
            //     $("#idStatusSolOP").prop('disabled', false);
            //     $("#idStatusOPCon").prop('disabled', false);
            // }

            // if (estatus == 10 || estatus == 12) {
            //     $("#btnSave").prop('disabled', true);

            //     // $("#infGeneral").addClass("disable");

            //     var nodes = document.getElementById("infGeneral").getElementsByTagName('*');
            //     for (var i = 0; i < nodes.length; i++) {
            //         nodes[i].disabled = true;
            //     }

            //     $("#elePre").addClass("disable");

            //     var nodesEle = document.getElementById("elePre").getElementsByTagName('*');
            //     for (var j = 0; j < nodesEle.length; j++) {
            //         nodesEle[j].disabled = true;
            //     }
            // }

        });

        $(function() {
            $("#idEquipo").change(function() {
                var agente = $("#idAgente");
                var equipos = $(this);

                if ($(this).val() != "") {
                    $.ajax({
                        data: {
                            idEquipo: equipos.val()
                        },
                        url: "{{ route('getEquipos') }}",
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            equipos.prop("disabled", true);
                        },
                        success: function(r) {
                            equipos.prop("disabled", false);

                            agente.find("option").remove();

                            $(r).each(function(i, v) {
                                // indice, valor
                                agente.append(
                                    '<option value="' +
                                    v.Id_Usuario +
                                    '">' +
                                    v.Nombre +
                                    "</option>"
                                );
                            });

                            agente.prop("disabled", false);

                        },
                        error: function(r) {
                            alert("Ocurrio un error en el servidor ..");
                            equipos.prop("disabled", false);
                        },
                    });
                } else {
                    agente.find("option").remove();
                    agente.prop("disabled", true);
                }
            });
        });

        var mensaje = '';

        $(function() {
            $('#formEnvioCreate form').validator().on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    var id = $('#id').val();
                    if (save_method == 'add') {
                        url = "{{ url('envios') }}";
                        type = "POST";
                    } else {
                        url = "{{ url('envios') . '/' }}" + id;
                        type = "PUT";
                    }

                    $.ajax({
                        url: url,
                        type: type,
                        data: $('#formEnvioCreate form').serialize(),
                        success: function(data) {
                            // $('#formEnvioCreate').trigger("reset");
                            // $('#formEnvioCreate').modal('hide');
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
                            // table.ajax.reload();
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
