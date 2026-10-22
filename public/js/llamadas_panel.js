serve = base_path;
CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$(document).ready(function() {
    validarDatosLlamadasPanel();
});

asuntoLugar = document.getElementById('asuntoLugarPanel');
descripcion = document.getElementById('descripcionPanel');
relacionadoCon = document.getElementById('relacionadoConPanel');
fecInicio = document.getElementById('fecInicioInputPanel');
fecIni = document.getElementById('fecInicioPanel');
direccion = document.getElementById('direccionPanel');
tipoRepeticion = document.getElementById('tipoRepeticionPanel');
observaciones = document.getElementById('observacionesPanel');
tipo = document.getElementById('tipoPanel');
id_contacto = document.getElementById('id_contactoPanel');
tel_contacto = document.getElementById('tel_contactoPanel');
cancelado = document.getElementById('canceladoPanel');
btnSaveLla = document.getElementById('btnSaveLlaPanel');

relacionadoConSelec2 = $('#relacionadoConPanel').select2({
    language: "es"
});

asuntoLugar.addEventListener('change', function() {
    validarDatosLlamadasPanel();
})

descripcion.addEventListener('change', function() {
    validarDatosLlamadasPanel();
})

relacionadoConSelec2.on('change', function() {
    idClienteChangePanel(relacionadoCon.value);
    validarDatosLlamadasPanel();
})

id_contacto.addEventListener('change', function() {
    validarDatosLlamadasPanel();
    idContactoChangePanel(id_contacto.value);
})

tel_contacto.addEventListener('change', function() {
    validarDatosLlamadasPanel();
})

fecInicio.addEventListener('blur', function() {
    validarDatosLlamadasPanel();
})

direccion.addEventListener('change', function() {
    validarDatosLlamadasPanel();
})

tipoRepeticion.addEventListener('change', function() {
    validarDatosLlamadasPanel();
})

var today = new Date();

var mensaje = "";
var dateToday = new Date();

var contacto_Id = null;

function editarLlamada(id) {
    save_method = "edit";
    $("imput[name=_method]").val("PATCH");
    $.ajax({
        url: serve + "llamadas" + "/" + id + "/edit",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            contacto_Id = data.id_contacto;
            idClienteChangePanel(data.id_relacionadoCon);
            idContactoChangePanel(data.id_contacto);

            if (data.tipo == "Llamadas") {
                mensaje = "Llamada Actualizada";
                $("#tituloLlamadaPanel").text("Editar Llamada");
                $("#labelAsuntoLugarPanel").text("Asunto");
                $('#asuntoLugarPanel').prop('placeholder', 'Asunto');
                // document.getElementById('divFechaFin').style.display = 'none';
                document.getElementById('divDescripcionPanel').style.display = 'none';
                document.getElementById('divDireccionPanel').style.display = 'block';
                document.getElementById('divContactoPanel').style.display = 'block';
                $('#direccionLabelPanel').text("Tipo de llamada");
                $('#isCancelledDivPanel').addClass('cancelingModalLlamada');
                $('#isRealizadaDivPanel').addClass('cancelingModalLlamada');
            } else {
                mensaje = "Reunión Actualizada";
                $("#tituloLlamadaPanel").text("Editar Reunión");
                $("#labelAsuntoLugarPanel").text("Lugar");
                $('#asuntoLugarPanel').prop('placeholder', 'Lugar');
                // document.getElementById('divFechaFin').style.display = 'block';
                document.getElementById('divDescripcionPanel').style.display = 'block';
                document.getElementById('divDireccionPanel').style.display = 'none';
                document.getElementById('divContactoPanel').style.display = 'none';
                $('#direccionLabelPanel').text("Tipo de reunión");
                $('#isCancelledDivPanel').removeClass('cancelingModalLlamada');
                $('#isRealizadaDivPanel').removeClass('cancelingModalLlamada');
            }

            $("#modal-llamada-panel").addClass("showModal");
            $("#modal-llamada-panel form").trigger("reset");

            $("#idLlamadasReunionesPanel").val(data.idLlamadasReuniones);
            $("#asuntoLugarPanel").val(data.asuntoLugar);
            $("#descripcionPanel").val(data.descripcion);
            $("#relacionadoConPanel").val(data.id_relacionadoCon);
            $("#fecInicioInputPanel").val(dateFormat(data.fecInicio, "dd-mm-yyyy hh:MM TT"));
            $("#fecFinInputPanel").val(dateFormat(data.fecFin, "dd-mm-yyyy hh:MM TT"));
            // $("#estado").val(data.id_estado);
            $("#direccionPanel").val(data.id_direccion);
            $("#tipoRepeticionPanel").val(data.id_tipoRepeticion);
            $("#observacionesPanel").val(data.observaciones);
            $("#canceladoPanel").val(data.cancelado);
            $('#tipoPanel').val(data.tipo);
            $('#id_contactoPanel').val(data.id_contacto);
            $('#tel_contactoPanel').val(data.tel_contacto);
            $("#modal-llamada-panel").modal("show");
            $("#btnBackLlaPanel").text("Regresar");
            document.getElementById("sbtnCancelarLlaPanel").style.display = 'block';
            document.getElementById("sbtnSaveLlaPanel").style.display = 'block';
            $('#sbtnSaveLlaPanel').removeClass('col-6');
            $('#sbtnSaveLlaPanel').addClass('col-4');
            $('#sbtnBackLlaPanel').removeClass('col-6');
            $('#sbtnBackLlaPanel').addClass('col-4');

            validarDatosLlamadasPanel();

        },
        error: function() {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            if (id == null) {
                titleMsg = "Id Nulo";
            } else {
                titleMsg = "Ocurrió un error";
            }
            Msg.fire({
                title: titleMsg,
            });
        },
    });
}

function CancelarLlamada() {
    tipo = $('#tipo').val();
    if (tipo == 'Llamadas') {
        mensaje = "Llamada Cancelada";
        text = "llamada";
    } else {
        mensaje = "Reunión Cancelada";
        text = "reunión";
    }
    id_suario = document.getElementById("idUsuario");
    id = document.getElementById("idLlamadasReuniones");

    Swal.fire({
        title: tipo,
        text: "Cancelar la " + text + " Seleccionada?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $('#swal2-input').prop('readonly', false);
            Swal.fire({
                input: "textarea",
                inputLabel: "Motivo Cancelación",
                inputPlaceholder: "Captura el motivo de Cancelación de la " + text,
                inputAttributes: {
                    "aria-label": "Captura el motivo de Cancelación de la " + text,
                },
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar',
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                target: document.getElementById('modal-llamada-panel'),
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: serve + "cancelarLlamada",
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id.value,
                            id_suario: id_suario.value,
                            motivoCancelacion: result.value,
                            _token: CSRF_TOKEN,
                        },
                        success: function(data) {
                            // $("#modal-llamada-panel form")[0].reset();
                            $("#modal-llamada-panel").modal("hide");
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: mensaje,
                            });
                            loaderOut();
                        },
                        error: function() {
                            $("#modal-llamada-panel form")[0].reset();
                            $("#modal-llamada-panel").modal("hide");
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: mensaje,
                            });

                        },
                    });
                }
            });
        }
    });
}

function validarDatosLlamadasPanel() {

    var relacionadoConSelec2 = $('#relacionadoConPanel').select2("data");

    if (tipo.value == 'Reuniones') {

        if (asuntoLugar.value == "" || descripcion.value == "" || relacionadoCon.value == "Cliente" || relacionadoCon.value ==
            "" || fecInicio.value == "" || tipoRepeticion.value == "" ||
            cancelado.value == 1) {
            btnSaveLla.disabled = true;
        } else {
            btnSaveLla.disabled = false;
        }
    } else {
        if (asuntoLugar.value == "" || relacionadoCon.value == "Cliente" || relacionadoCon.value ==
            "" || fecInicio.value == "" || direccion.value == "" || tipoRepeticion.value == "" || id_contacto.value == "Contacto" || tel_contacto.value == "Teléfono" ||
            cancelado.value == 1) {
            btnSaveLla.disabled = true;
        } else {
            btnSaveLla.disabled = false;
        }
    }

    if (asuntoLugar.value == '') {
        asuntoLugar.style.borderColor = 'red';
    } else {
        asuntoLugar.style.borderColor = 'green';
    }

    if (descripcion.value == '') {
        descripcion.style.borderColor = 'red';
    } else {
        descripcion.style.borderColor = 'green';
    }

    if (relacionadoConSelec2[0].id !== "") {
        $('#relacionadoConPanel').select2().addClass('select2-green');
    } else {
        $('#relacionadoConPanel').select2().addClass('select2-red');
    }

    if (fecInicio.value == '') {
        fecInicio.style.borderColor = 'red';
    } else {
        fecInicio.style.borderColor = 'green';
    }

    // if (estado.value == '') {
    //     estado.style.borderColor = 'red';
    // } else {
    //     estado.style.borderColor = 'green';
    // }

    if (direccion.value == '') {
        direccion.style.borderColor = 'red';
    } else {
        direccion.style.borderColor = 'green';
    }

    if (tipoRepeticion.value == '') {
        tipoRepeticion.style.borderColor = 'red';
    } else {
        tipoRepeticion.style.borderColor = 'green';
    }

    if (id_contacto.value == 'Contacto' || id_contacto.value == 'CONTACTO' || id_contacto.value == '') {
        id_contacto.style.borderColor = 'red';
    } else {
        id_contacto.style.borderColor = 'green';
    }

    if (tel_contacto.value == 'Teléfono' || tel_contacto.value == 'TELÉFONO' || tel_contacto.value == '') {
        tel_contacto.style.borderColor = 'red';
    } else {
        tel_contacto.style.borderColor = 'green';
    }
}

function sumarDias(fecha, dias) {
    fecha.setDate(fecha.getDate() + dias);
    return fecha;
}

$("#modal-llamada-panel").draggable({
    handle: "#tituloLlamada",
});

$('#modal-llamada-panel').on('shown.bs.modal', function() {
    $('#asuntoLugar').trigger('focus');
});

function closeMLlamada() {
    loaderOut();
}

function updateLlamada() {
    $btnSave = document.getElementById("btnSaveLlaPanel");
    loaderON();
    var id = $("#idLlamadasReunionesPanel").val();
    $btnSave.disabled = true;
    $.ajax({
        url: serve + "updateLlamada",
        type: 'PATCH',
        data: {
            _method: "PATCH",
            id: id,
            asuntoLugar: asuntoLugar.value,
            descripcion: descripcion.value,
            relacionadoCon: relacionadoCon.value,
            fecInicio: fecInicio.value,
            direccion: direccion.value,
            tipoRepeticion: tipoRepeticion.value,
            observaciones: observaciones.value,
            tipo: tipo.value,
            id_contacto: id_contacto.value,
            tel_contacto: tel_contacto.value,
            _token: CSRF_TOKEN,
        },
        success: function(data) {

            $("#modal-llamada-panel form").trigger("reset");
            $("#modal-llamada-panel").modal("hide");
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: mensaje,
            });

            loaderOut();
            window.location.href = serve;
            // $('#list_llamadas_reuniones').load(location.href + " #list_llamadas_reuniones");

        },
        error: function() {
            $("#modal-llamada-panel form").trigger("reset");
            $("#modal-llamada-panel").modal("hide");
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: mensaje,
            });
            loaderOut();
        },
    });
}

function idClienteChangePanel(id) {
    var contactos = $('#id_contactoPanel');
    var telefonos = $('#tel_contactoPanel');
    var cliente = id;

    if ($(relacionadoCon).val() != "Cliente") {
        $.ajax({
            data: {
                idCliente: cliente,
            },
            url: serve + "getContactos",
            type: "GET",
            dataType: "json",
            success: function(r) {
                contactos.find("option").remove();
                telefonos.find("option").remove();

                contactos.append("<option selected disabled>Contacto</option>");
                telefonos.append("<option selected disabled>Teléfono</option>");

                $(r).each(function(i, v) {
                    var $option = $("<option></option>", {
                        text: v.Nombre,
                        value: v.idContacto,
                    });
                    if (contacto_Id == v.idContacto) {
                        $option.attr("selected", "selected");
                    }
                    contactos.append($option);
                });
                validarDatosLlamadasPanel();
            },
            error: function(r) {
                Swal.fire("Ocurrio un error en el servidor ..");
            },
        });
    } else {
        contactos.find("option").remove();
    }
}

function idContactoChangePanel(id) {
    var telefonos = $('#tel_contactoPanel');
    var contacto = id;

    validarDatosLlamadasPanel();

    if ($(id_contacto).val() != "Contacto") {
        $.ajax({
            data: {
                idContacto: contacto,
            },
            url: serve + "getTelefonosContacto",
            type: "GET",
            dataType: "json",
            success: function(r) {
                telefonos.find("option").remove();
                telefonos.append("<option selected disabled>Teléfono</option>");

                $(r).each(function(i, v) {
                    var $option = $("<option></option>", {
                        text: v.Telefonos,
                        value: v.Telefonos,
                    });
                    if (contacto_Id == v.idContacto) {
                        $option.attr("selected", "selected");
                    }
                    telefonos.append($option);
                });
                validarDatosLlamadasPanel();
            },
            error: function(r) {
                Swal.fire("No se obtuvieron los telefonos...");
            },
        });
    } else {
        telefonos.find("option").remove();
    }
}