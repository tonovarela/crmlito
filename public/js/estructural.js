serve = base_path;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
const FROM_PATTERN_ESTR = "YYYY-MM-DD HH:mm:ss.SSS";
const TO_PATTERN_ESTR = "DD/MM/YYYY HH:mm";

const FROM_PATTERN_ESTR_SHORT = "YYYY-MM-DD";
const TO_PATTERN_ESTR_SHORT = "DD/MM/YYYY";

$(document).ajaxStart(function () {
    loaderModalON();
    loaderON();
});

$(document).ajaxStop(function () {
    loaderModalOut();
    loaderOut();
});

$(document).ready(function () {
    // group = 2;
    collapsedGroups = {};
    collapsedGroupsD = {};
    top = '';
    parent = '';

    btnExpandirEstri = document.getElementById('btn-expandirEstr-i');

    $('.select-multiple').select2();
    var texto = $('#searchEstr').val();
    minDate = new Date();
    id_oportunidad_estr = 0;
    buscarEstr();
    selectRow("tblDetalleAsignados");

    
    if ($('#idRolEstr').val() != 1021) {
        getItemsOperador()
    } else {
        $('#selectOperador').hide();
    }

    
    getItemsEstatus()
});

let nombOportunidad = document.getElementById('nombOportunidad');
let folioOportunidad = document.getElementById('folioOportunidad');
let nomClienteEstr = document.getElementById('nomClienteEstr');

function getItemsOperador() {
    $.ajax({
        url: serve + "getOperadorESTR",
        type: "GET",
        dataType: "json",
        success: function(r) {
            // Selecciona el elemento del select donde se llenarán los operadores
            var selectOperador = $('#selectOperador'); // Cambia 'operadorSelect' por el ID de tu select
    
            // Limpia las opciones existentes
            selectOperador.empty();
    
            // Agrega una opción por defecto
            selectOperador.append('<option value="" selected>Operador</option>');
            selectOperador.append('<option value="SIN ASIGNAR">SIN ASIGNAR</option>');
    
            // Itera sobre los resultados y agrega las opciones al select
            $(r).each(function(i, v) {
                selectOperador.append('<option value="' + v.Nombre + '">' + v.Nombre + '</option>');
            });
        },
        error: function(r) {
            Swal.fire("No se cargaron los Operadores...");
        },
    });
}

function getItemsEstatus() {
    $.ajax({
        url: serve + "getEstatusESTR",
        type: "GET",
        dataType: "json",
        success: function(r) {
            // Selecciona el elemento del select donde se llenarán los operadores
            var selectEstatus = $('#selectEstatus'); // Cambia 'operadorSelect' por el ID de tu select
    
            // Limpia las opciones existentes
            selectEstatus.empty();
    
            // Agrega una opción por defecto
            selectEstatus.append('<option value="" selected>Estado</option>');
            selectEstatus.append('<option value="PENDIENTE DE ASIGNAR">PENDIENTE DE ASIGNAR</option>');
    
            // Itera sobre los resultados y agrega las opciones al select
            $(r).each(function(i, v) {
                selectEstatus.append('<option value="' + v.Descripcion + '">' + v.Descripcion + '</option>');
            });
        },
        error: function(r) {
            Swal.fire("No se cargaron los Operadores...");
        },
    });
}

/**
 * Funcion para buscar dentro de la dataTable de tblDetalleAsignados de acuerdo a los 6 filtros
 * 
 * @returns {undefined}
 */
function buscarEstrNuevo() {
    buscarEstr()
}

function mostrarGeneralTicket() {

    // loaderON();
    $("#general-ticket-tab").addClass("active");
    $("#general-ticket").addClass("active");
    $("#general-ticket").addClass("show");

    $("#especificaciones-ticket-tab").removeClass("active");
    $("#especificaciones-ticket").removeClass("active");
    $("#especificaciones-ticket").removeClass("show");

    $("#documentos-ticket-tab").removeClass("active");
    $("#documentos-ticket").removeClass("active");
    $("#documentos-ticket").removeClass("show");

    // tblDocsEstr();
}

function mostrarEspecificacionesTicket() {

    // loaderON();
    $("#general-ticket-tab").removeClass("active");
    $("#general-ticket").removeClass("show");

    $("#especificaciones-ticket-tab").addClass("active");
    $("#especificaciones-ticket").addClass("show");

    $("#documentos-ticket-tab").removeClass("active");
    $("#documentos-ticket").removeClass("show");

}

function mostrarDocumentosTicket() {
    tblDocsEstr();
    $("#general-ticket-tab").removeClass("active");
    $("#general-ticket").removeClass("active");
    $("#general-ticket").removeClass("show");

    $("#especificaciones-ticket-tab").removeClass("active");
    $("#especificaciones-ticket").removeClass("active");
    $("#especificaciones-ticket").removeClass("show");

    $("#documentos-ticket-tab").addClass("active");
    $("#documentos-ticket").addClass("active");
    $("#documentos-ticket").addClass("show");

}

$("#modal-create-ticket").on("shown.bs.modal", function () {
    $("#folioOportunidad").trigger("focus");
});

function agregarTicketDE(tipo, accion) {

    $('#selectFolio').show();
    $('#folioOportunidadText').hide();

    $("#modal-create-ticket").modal("show");
    $("#modal-create-ticket").find("#formCreateEstr")[0].reset();
    clonarTicket.style.display = 'none';
    let modalEstr = document.querySelector("#modal-create-ticket");
    modalEstr.classList.add("showModal");

    // Limpiar los campos de búsqueda
    nombOportunidad.value = '';
    folioOportunidad.value = '';
    nomClienteEstr.value = '';

    setTimeout(function () {
        $('#folioOportunidad').val('').trigger('change');
        $('#folioOportunidad').val(null).trigger('change');
    }, 1000);

    document.getElementById('isCancelledESTR').style.display = 'none';

    $("#btnBackTicket").text("Regresar");
    var dateToday = new Date();
    // barnices = $('#barnices');

    // $.ajax({
    //     url: serve + 'obtenerBarnices',
    //     type: 'GET',
    //     dataType: 'JSON',
    //     success: function (rb) {
    //         barnices.find("option").remove();

    //         barnices.append("<option disabled>Barnices</option>");

    //         $(rb).each(function (i, v) {
    //             var $option_b = $("<option></option>", {
    //                 text: v.descBarniz,
    //                 value: v.descBarniz,
    //             });
    //             barnices.append($option_b);
    //         });
    //     },
    //     error: function (r) {
    //         Swal.fire("Ocurrio un error en el servidor ..");
    //     },
    // });

    mensaje = "Proyecto Agregado";
    save_method = "add";
    $("imput[name=_method]").val("POST");
    $("#fechaEstr").text(dateFormat(dateToday, 'dd/mm/yyyy H:MM:ss'));
    $("#fechaRegistroEstr").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));

    $('#idOportunidadEstr').val(0);
    nomOportunidadEstr.readOnly = false;
    sbtnCancelarkTicket.style.display = 'none';
    $('#sbtnSaveTicket').removeClass('col-lg-4')
    $('#sbtnBackTicket').removeClass('col-lg-4')
    $('#sbtnSaveTicket').addClass('col-lg-6')
    $('#sbtnBackTicket').addClass('col-lg-6')
    $("#tituloTicket").text("ALTA DE PROYECTO");

    $.ajax({
        url: serve + 'obtenerFolio',
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            $("#folio").text('Folio No. ' + data);
        },
    });

    var nodes = document.getElementById("formCreateEstr").getElementsByTagName('*');
    for (var i = 0; i < nodes.length; i++) {
        nodes[i].disabled = false;
    }

    validarRequeridosEstr();

    // $("#tituloTicket").text("EDITAR PROYECTO");
    // nomOportunidadEstr.readOnly = false;
    // nomOportunidadEstr.disabled = false;
    mostrarGeneralTicket();

}

function editarTicketDE(id) {
    btnBackTicket = document.getElementById("btnBackTicket");
    $("#btnBackTicket").text("Regresar");
    sbtnCancelarkTicket.style.display = 'none';
    clonarTicket.style.display = 'block';
    $('#sbtnSaveTicket').removeClass('col-lg-4')
    $('#sbtnBackTicket').removeClass('col-lg-4')
    $('#sbtnSaveTicket').addClass('col-lg-6')
    $('#sbtnBackTicket').addClass('col-lg-6')
    var dateToday = new Date();

    mensaje = "Proyecto Actualizado";
    save_method = "edit";
    $("imput[name=_method]").val("PUT");

    $.ajax({
        url: serve + 'obtenerTicket',
        type: 'GET',
        dataType: 'JSON',
        data: {
            id: id,
        },
        success: function (data) {
            let modalEstr = document.querySelector("#modal-create-ticket");
            modalEstr.classList.add("showModal");

            let valueOportunidad = data.idOportunidad + ' - ' + data.id_cliente + ' - ' + data.nombreCliente;
            let texto = data.folioOport + ' - ' + data.nomOportunidad;

            $("#modal-create-ticket").modal("show");
            $("#modal-create-ticket").find("#formCreateEstr")[0].reset();
            $("#fechaEstr").text(dateFormat(data.fecha_registro, 'dd/mm/yyyy HH:MM'));
            $("#fechaCambioEstr").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));
            $("#nomOport").val(data.nomOportunidad);
            $("#nomOportunidadEstr").val(data.nombre);
            $("#folio").text('Folio No. ' + data.master);
            $("#idTicketEstr").val(data.folio);
            $("#idOportunidadEstr").val(data.idOportunidad);
            $("#Categoria").val(data.Categoria);

            if (data.Estado == 'CANCELADO') {
                document.getElementById('isCancelledESTR').style.display = 'block';
            } else {
                document.getElementById('isCancelledESTR').style.display = 'none';
            };

            if (data.canceladoOportunidad == 0 && (data.estatusOportunidad != 10 && data.estatusOportunidad != 12)) {
                $('#selectFolio').show();
                $('#folioOportunidadText').hide();

                setTimeout(function () {
                    $('#folioOportunidad').val('').trigger('change');

                    $('#folioOportunidad').val(valueOportunidad).trigger({
                        type: 'select2:select',
                        params: {
                            data: {
                                id: valueOportunidad,
                                text: texto
                            }
                        }
                    });

                    $('#folioOportunidad').val(valueOportunidad).trigger('change');
                }, 2000);
            } else {
                // Cambiamos el select de folio de oportunidad a un campo de texto con el valor del folio
                $('#selectFolio').hide();
                $('#folioOportunidadText').val(texto).show();
                $('#nombOportunidad').val(data.nomOportunidad);
                $('#nomClienteEstr').val(data.nombreCliente);
            }

            $('#idClienteINTEstr').val(data.id_cliente);
            // barnices = $('#barnices');
            id_cliente_INT = data.id_cliente;
            // data_barnices = data.barnices;

            // $.ajax({
            //     url: serve + 'obtenerBarnices',
            //     type: 'GET',
            //     dataType: 'JSON',
            //     success: function (rb) {
            //         barnices.find("option").remove();

            //         $(rb).each(function (i, v) {
            //             var $option_b = $("<option></option>", {
            //                 text: v.descBarniz,
            //                 value: v.descBarniz,
            //             });

            //             if (data_barnices != null) {
            //                 $(data_barnices.split(', ')).each(function (ib, vb) {
            //                     if (vb == v.descBarniz) {
            //                         $option_b.attr("selected", "selected");
            //                     }
            //                 });
            //             }
            //             barnices.append($option_b);
            //         });
            //     },
            //     error: function (r) {
            //         Swal.fire("Ocurrio un error en el servidor ..");
            //     },
            // });

            // if (data.cotizacion == 1) {
            //     $("#cotizacion")[0].checked = true;
            // }
            if (data.dum == 1) {
                $("#dummy")[0].checked = true;
            }
            if (data.render == 1) {
                $("#render")[0].checked = true;
            }
            if (data.trazo == 1) {
                $("#trazo")[0].checked = true;
            }
            if (data.render_dinamico == 1) {
                $("#render_dinamico")[0].checked = true;
            }
            // if (data.instructivo == 1) {
            //     $("#instructivo")[0].checked = true;
            // }
            $("#cant1").val(data.cant1);
            $("#cant2").val(data.cant2);
            $("#cant3").val(data.cant3);
            // $("#cant4").val(data.cant4);
            // $("#cant5").val(data.cant5);
            $("#descripcion").val(data.descripcion);
            $("#descripcion").attr('title', data.descripcion);

            $('#tipo_producto').val(data.Categoria + '-' + data.producto);
            // $("#otroProductotexto").val(data.otroProductotexto);

            if (data.conAcetato == 1) {
                $("#conAcetato")[0].checked = true;
            }
            if (data.suajado == 1) {
                $("#suajado")[0].checked = true;
            }
            $("#materiales1").val(data.materiales1);
            $("#materiales2").val(data.materiales2);
            $("#materiales3").val(data.materiales3);
            $("#materiales4").val(data.materiales4);
            $("#puntos1").val(data.puntos1);
            $("#puntos2").val(data.puntos2);
            $("#puntos3").val(data.puntos3);
            $("#puntos4").val(data.puntos4);
            $("#flauta1").val(data.flauta1);
            $("#flauta2").val(data.flauta2);
            $("#flauta3").val(data.flauta3);
            $("#flauta4").val(data.flauta4);

            // if (data.tipo_impresion == 'OFFSET') {
            //     ($("input[name='tipo_impresion']"))[0].checked = true;
            // } else if (data.tipo_impresion == 'CAMA PLANA') {
            //     ($("input[name='tipo_impresion']"))[1].checked = true;
            // }

            // if (data.medida == '4x0') {
            //     ($("input[name='medida']"))[0].checked = true;
            // } else if (data.medida == '4x4') {
            //     ($("input[name='medida']"))[1].checked = true;
            // }
            // $("#barnices")[0].value = data.barnices;
            $("#otrosAcabados").val(data.otrosAcabados);
            $("#mgFrente").val(data.mgFrente);
            $("#mgFondo").val(data.mgFondo);
            $("#mgAlto").val(data.mgAlto);
            $("#ecpDescr1").val(data.ecpDescr1);
            $("#ecpDescr2").val(data.ecpDescr2);
            $("#ecpDescr3").val(data.ecpDescr3);
            $("#ecpDescr4").val(data.ecpDescr4);
            $("#ecpAlto1").val(data.ecpAlto1);
            $("#ecpAlto2").val(data.ecpAlto2);
            $("#ecpAlto3").val(data.ecpAlto3);
            $("#ecpAlto4").val(data.ecpAlto4);
            $("#ecpCant1").val(data.ecpCant1);
            $("#ecpCant2").val(data.ecpCant2);
            $("#ecpCant3").val(data.ecpCant3);
            $("#ecpCant4").val(data.ecpCant4);
            $("#ecpFondo1").val(data.ecpFondo1);
            $("#ecpFondo2").val(data.ecpFondo2);
            $("#ecpFondo3").val(data.ecpFondo3);
            $("#ecpFondo4").val(data.ecpFondo4);
            $("#ecpFrente1").val(data.ecpFrente1);
            $("#ecpFrente2").val(data.ecpFrente2);
            $("#ecpFrente3").val(data.ecpFrente3);
            $("#ecpFrente4").val(data.ecpFrente4);
            // if (data.esp1 == 1) {
            //     $("#esp1")[0].checked = true;
            // }
            // if (data.esp2 == 1) {
            //     $("#esp2")[0].checked = true;
            // }
            // if (data.esp3 == 1) {
            //     $("#esp3")[0].checked = true;
            // }
            // if (data.esp4 == 1) {
            //     $("#esp4")[0].checked = true;
            // }

            if (data.cajamaster == 1) {
                $("#cajamaster")[0].checked = true;
            }
            if (data.bolsa == 1) {
                $("#bolsa")[0].checked = true;
            }
            // if (data.cajaindividual == 1) {
            //     $("#cajaindividual")[0].checked = true;
            // }
            // if (data.emplayado == 1) {
            //     $("#emplayado")[0].checked = true;
            // }
            // if (data.enpaquetes == 1) {
            //     $("#enpaquetes")[0].checked = true;
            // }
            // if (data.individual == 1) {
            //     $("#individual")[0].checked = true;
            // }

            if (data.armado == 1) {
                $("#armado")[0].checked = true;
            }
            if (data.desplegado == 1) {
                $("#desplegado")[0].checked = true;
            }
            // if (data.instructivos == 1) {
            //     $("#instructivos")[0].checked = true;
            // }
            // if (data.transporte == 1) {
            //     $("#transporte")[0].checked = true;
            // }
            // if (data.ametropolitana == 1) {
            //     $("#ametropolitana")[0].checked = true;
            // }
            // if (data.foraneo == 1) {
            //     $("#foraneo")[0].checked = true;
            // }
            $("#especifique").val(data.especifique);

            if (data.tipo_medida_general == 1) {
                $('#tipoMedidaGeneral2').prop('checked', true);
            } else if (data.tipo_medida_general == 2) {
                $('#tipoMedidaGeneral1').prop('checked', true);
            }

            if (data.tipo_medida_producto == 1) {
                $('#tipoMedidaProducto2').prop('checked', true);
            } else if (data.tipo_medida_producto == 2) {
                $('#tipoMedidaProducto1').prop('checked', true);
            }

            // Seccion de entregados por cliente
            $('#entPlano').prop('checked', data.entPlano == 1);
            $('#entRender').prop('checked', data.entRender == 1);
            $('#entFoto').prop('checked', data.entFoto == 1);
            $('#entMuestraFisica').prop('checked', data.entMuestraFisica == 1);
            $('#entProducto').prop('checked', data.entProducto == 1);

            $('#aConsideracion').prop('checked', data.consideracionDisign == 1);

            var dateToday = new Date();

            validarEstado(data);
            loaderModalOut();
            mostrarGeneralTicket();

            validarRequeridosEstr();

            let idUser = $('#idUsuario').val();

            $('#usuarioPerteneceTicket').val(data.id_agente);

            if (data.Estado == 'CERRADO' || data.Estado == 'CANCELADO' || data.Estado == 'RECHAZADO') {
                $('#btnSaveTicket').attr('disabled', true);
            }

            // Validamos si el rol es 1024 blockeamos todo el formulario
            if ($('#idRolEstr').val() == 1024 || ($('#idRolEstr').val() == 1026 && idUser != data.id_agente)) {
                setTimeout(function () {
                    var nodes = document.getElementById("formCreateEstr").getElementsByTagName('*');
                    for (var i = 0; i < nodes.length; i++) {
                        nodes[i].disabled = true;
                    }

                    // Removemos la deshabilitacion del boton de cerrar
                    $('#btnBackTicket').removeAttr('disabled');

                    clonarTicket.style.display = 'none'
                }, 1000);
            }
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Ocurrió un error",
            });
            tblDetalleAsignados.draw(false);
        },
    });

}

function clonarTicketDE() {
    id = $('#idTicketEstr').val();

    Swal.fire({
        title: "Diseño Estructural",
        html: "<div>¿Clonar este Proyecto?</div><br><div class='text-warning'><i class='fas fa-exclamation-triangle'> </i> Se creará con un folio nuevo.</div>",
        // icon: 'warning',
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
            loaderModalON();
            $("#modal-create-ticket").modal("hide");
            $("#modal-create-ticket").find("#formCreateEstr")[0].reset();

            btnBackTicket = document.getElementById("btnBackTicket");
            $("#btnBackTicket").text("Regresar");
            sbtnCancelarkTicket.style.display = 'none';
            clonarTicket.style.display = 'none';
            $('#sbtnSaveTicket').removeClass('col-lg-4')
            $('#sbtnBackTicket').removeClass('col-lg-4')
            $('#sbtnSaveTicket').addClass('col-lg-6')
            $('#sbtnBackTicket').addClass('col-lg-6')
            var dateToday = new Date();

            mensaje = "Proyecto Clonado";
            save_method = "add";
            $("imput[name=_method]").val("POST");

            var nodesC = document.getElementById("formCreateEstr").getElementsByTagName('*');
            for (var i = 0; i < nodesC.length; i++) {
                nodesC[i].disabled = false;
            }

            $.ajax({
                url: serve + 'obtenerTicket',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    id: id,
                },
                success: function (data) {
                    let modalEstr = document.querySelector("#modal-create-ticket");
                    modalEstr.classList.add("showModal");

                    let valueOportunidad = data.idOportunidad + ' - ' + data.id_cliente + ' - ' + data.nombreCliente;
                    let texto = data.folioOport + ' - ' + data.nomOportunidad;

                    $('#selectFolio').show();
                    $('#folioOportunidadText').hide();

                    if (data.canceladoOportunidad == 0 && (data.estatusOportunidad != 10 && data.estatusOportunidad != 12)) {
                        setTimeout(function () {
                            $('#folioOportunidad').val('').trigger('change');

                            $('#folioOportunidad').val(valueOportunidad).trigger({
                                type: 'select2:select',
                                params: {
                                    data: {
                                        id: valueOportunidad,
                                        text: texto
                                    }
                                }
                            });

                            $('#folioOportunidad').val(valueOportunidad).trigger('change');
                        }, 2000);
                    }

                    $("#modal-create-ticket").modal("show");
                    $("#modal-create-ticket").find("#formCreateEstr")[0].reset();
                    $("#fechaEstr").text(dateFormat(dateToday, 'dd/mm/yyyy HH:MM'));
                    $("#fechaCambioEstr").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));
                    $("#fechaRegistroEstr").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));
                    $("#nomOport").val(null);
                    $("#nomOportunidadEstr").val(data.nombre);
                    $('#nomOportunidadEstr').prop('readonly', false);
                    $('#nomOportunidadEstr').attr('disabled', false);
                    $("#Categoria").val(data.Categoria);

                    $.ajax({
                        url: serve + 'obtenerFolio',
                        type: 'GET',
                        dataType: 'JSON',
                        success: function (data) {
                            $("#folio").text('Folio No. ' + data);
                        },
                    });

                    $("#idTicketEstr").val(null);
                    $("#idOportunidadEstr").val(0);
                    data.Estado = null;

                    document.getElementById('isCancelledESTR').style.display = 'none';

                    id_cliente_INT = data.id_cliente;
                    // barnices = $('#barnices');
                    // data_barnices = data.barnices;

                    // $.ajax({
                    //     url: serve + 'obtenerBarnices',
                    //     type: 'GET',
                    //     dataType: 'JSON',
                    //     success: function (rb) {
                    //         barnices.find("option").remove();

                    //         $(rb).each(function (i, v) {
                    //             var $option_b = $("<option></option>", {
                    //                 text: v.descBarniz,
                    //                 value: v.descBarniz,
                    //             });

                    //             if (data_barnices != null) {
                    //                 $(data_barnices.split(', ')).each(function (ib, vb) {
                    //                     if (vb == v.descBarniz) {
                    //                         $option_b.attr("selected", "selected");
                    //                     }
                    //                 });
                    //             }
                    //             barnices.append($option_b);
                    //         });
                    //     },
                    //     error: function (r) {
                    //         Swal.fire("Ocurrio un error en el servidor ..");
                    //     },
                    // });
                    

                    if (data.dum == 1) {
                        $("#dummy")[0].checked = true;
                    }
                    if (data.render == 1) {
                        $("#render")[0].checked = true;
                    }
                    if (data.trazo == 1) {
                        $("#trazo")[0].checked = true;
                    }
                    if (data.render_dinamico == 1) {
                        $("#render_dinamico")[0].checked = true;
                    }
                    // if (data.instructivo == 1) {
                    //     $("#instructivo")[0].checked = true;
                    // }
                    $("#cant1").val(data.cant1);
                    $("#cant2").val(data.cant2);
                    $("#cant3").val(data.cant3);
                    // $("#cant4").val(data.cant4);
                    // $("#cant5").val(data.cant5);
                    $("#descripcion").val(data.descripcion);

                    $('#tipo_producto').val(data.Categoria + '-' + data.producto);
                    // $("#otroProductotexto").val(data.otroProductotexto);

                    if (data.conAcetato == 1) {
                        $("#conAcetato")[0].checked = true;
                    }
                    if (data.suajado == 1) {
                        $("#suajado")[0].checked = true;
                    }
                    $("#materiales1").val(data.materiales1);
                    $("#materiales2").val(data.materiales2);
                    $("#materiales3").val(data.materiales3);
                    $("#materiales4").val(data.materiales4);
                    $("#puntos1").val(data.puntos1);
                    $("#puntos2").val(data.puntos2);
                    $("#puntos3").val(data.puntos3);
                    $("#puntos4").val(data.puntos4);
                    $("#flauta1").val(data.flauta1);
                    $("#flauta2").val(data.flauta2);
                    $("#flauta3").val(data.flauta3);
                    $("#flauta4").val(data.flauta4);

                    // if (data.tipo_impresion == 'OFFSET') {
                    //     ($("input[name='tipo_impresion']"))[0].checked = true;
                    // } else if (data.tipo_impresion == 'CAMA PLANA') {
                    //     ($("input[name='tipo_impresion']"))[1].checked = true;
                    // }

                    // if (data.medida == '4x0') {
                    //     ($("input[name='medida']"))[0].checked = true;
                    // } else if (data.medida == '4x4') {
                    //     ($("input[name='medida']"))[1].checked = true;
                    // }

                    // $("#barnices")[0].value = data.barnices;
                    $("#otrosAcabados").val(data.otrosAcabados);
                    $("#mgFrente").val(data.mgFrente);
                    $("#mgFondo").val(data.mgFondo);
                    $("#mgAlto").val(data.mgAlto);
                    $("#ecpDescr1").val(data.ecpDescr1);
                    $("#ecpDescr2").val(data.ecpDescr2);
                    $("#ecpDescr3").val(data.ecpDescr3);
                    $("#ecpDescr4").val(data.ecpDescr4);
                    $("#ecpAlto1").val(data.ecpAlto1);
                    $("#ecpAlto2").val(data.ecpAlto2);
                    $("#ecpAlto3").val(data.ecpAlto3);
                    $("#ecpAlto4").val(data.ecpAlto4);
                    $("#ecpCant1").val(data.ecpCant1);
                    $("#ecpCant2").val(data.ecpCant2);
                    $("#ecpCant3").val(data.ecpCant3);
                    $("#ecpCant4").val(data.ecpCant4);
                    $("#ecpFondo1").val(data.ecpFondo1);
                    $("#ecpFondo2").val(data.ecpFondo2);
                    $("#ecpFondo3").val(data.ecpFondo3);
                    $("#ecpFondo4").val(data.ecpFondo4);
                    $("#ecpFrente1").val(data.ecpFrente1);
                    $("#ecpFrente2").val(data.ecpFrente2);
                    $("#ecpFrente3").val(data.ecpFrente3);
                    $("#ecpFrente4").val(data.ecpFrente4);
                    // if (data.esp1 == 1) {
                    //     $("#esp1")[0].checked = true;
                    // }
                    // if (data.esp2 == 1) {
                    //     $("#esp2")[0].checked = true;
                    // }
                    // if (data.esp3 == 1) {
                    //     $("#esp3")[0].checked = true;
                    // }
                    // if (data.esp4 == 1) {
                    //     $("#esp4")[0].checked = true;
                    // }

                    if (data.cajamaster == 1) {
                        $("#cajamaster")[0].checked = true;
                    }
                    if (data.bolsa == 1) {
                        $("#bolsa")[0].checked = true;
                    }
                    // if (data.cajaindividual == 1) {
                    //     $("#cajaindividual")[0].checked = true;
                    // }
                    // if (data.emplayado == 1) {
                    //     $("#emplayado")[0].checked = true;
                    // }
                    // if (data.enpaquetes == 1) {
                    //     $("#enpaquetes")[0].checked = true;
                    // }
                    // if (data.individual == 1) {
                    //     $("#individual")[0].checked = true;
                    // }

                    if (data.armado == 1) {
                        $("#armado")[0].checked = true;
                    }
                    if (data.desplegado == 1) {
                        $("#desplegado")[0].checked = true;
                    }
                    // if (data.instructivos == 1) {
                    //     $("#instructivos")[0].checked = true;
                    // }
                    // if (data.transporte == 1) {
                    //     $("#transporte")[0].checked = true;
                    // }
                    // if (data.ametropolitana == 1) {
                    //     $("#ametropolitana")[0].checked = true;
                    // }
                    // if (data.foraneo == 1) {
                    //     $("#foraneo")[0].checked = true;
                    // }
                    $("#especifique").val(data.especifique);
                    // var dateToday = new Date();

                    if (data.tipo_medida_general == 1) {
                        $('#tipoMedidaGeneral2').prop('checked', true);
                    } else if (data.tipo_medida_general == 2) {
                        $('#tipoMedidaGeneral1').prop('checked', true);
                    }

                    if (data.tipo_medida_producto == 1) {
                        $('#tipoMedidaProducto2').prop('checked', true);
                    } else if (data.tipo_medida_producto == 2) {
                        $('#tipoMedidaProducto1').prop('checked', true);
                    }

                    // Seccion de entregados por cliente
                    $('#entPlano').prop('checked', data.entPlano == 1);
                    $('#entRender').prop('checked', data.entRender == 1);
                    $('#entFoto').prop('checked', data.entFoto == 1);
                    $('#entMuestraFisica').prop('checked', data.entMuestraFisica == 1);
                    $('#entProducto').prop('checked', data.entProducto == 1);

                    $('#aConsideracion').prop('checked', data.consideracionDisign == 1);

                    loaderModalOut();
                    mostrarGeneralTicket();

                    validarRequeridosEstr();

                }
            });
        } else {
            loaderModalOut();
        }
    });
}

// $('#modal-create-ticket').on('hidden.bs.modal', function (e) {
//     $(this).find('#formCreateEstr')[0].reset();
// });

function closeMEstr() {
    $("#modal-create-ticket").find("#formCreateEstr")[0].reset();
    let mEstr = document.querySelector("#modal-create-ticket");
    mEstr.classList.remove("showModal");
    $("#idTicketEstr").val('');
    $("#idClienteINTEstr").val(0);
    document.getElementById('isCancelledESTR').style.display = 'none';
}

function cambiar_estatus(id) {
    estatus = $('#estatus_' + id).val();
    var dateToday = new Date();
    fecha_cerrado = dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss');

    if (estatus == "CANCELADO") {

        mensaje = "Proyecto Cancelado";

        const { value: text } = Swal.fire({
            input: "textarea",
            inputLabel: "Motivo de la Cancelación",
            inputPlaceholder: "Captura el motivo de la Cancelación",
            inputAttributes: {
                "aria-label": "Captura el motivo de la Cancelación",
            },
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Guardar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value != "") {
                    $.ajax({
                        url: serve + 'cambiarEstatusESTR',
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id,
                            estatus: estatus,
                            fecha_cerrado: fecha_cerrado,
                            motivoCancelacion: result.value,
                            _token: CSRF_TOKEN,
                        },
                        success: function (data) {
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
                            tblDetalleAsignados.draw(false);
                        },
                        error: function () {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: "Ocurrió un error",
                            });
                            tblDetalleAsignados.draw(false);
                        },
                    });
                } else {
                    Swal.fire("¡Debes capturar el motivo!");
                    tblDetalleAsignados.draw(false);
                }
            } else {
                tblDetalleAsignados.draw(false);
            }
        });
    } else {
        if (estatus == "NOTIFICAR A VENTAS") {
            swal.fire({
                title: "Diseño Estructural",
                text: "Ingresa el motivo de la notificación a ventas",
                input: "textarea",
                inputPlaceholder: "Motivo de la notificación a ventas", 
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true,
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage("¡Debes capturar el motivo!");
                    } else {
                        return value;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    cambiarEst(id, estatus, fecha_cerrado, result.value);
                } else {
                    tblDetalleAsignados.draw(false);
                }
            });
        } else if (estatus == "CERRADO") {
            swal.fire({
                title: "Diseño Estructural",
                text: "Ingresa el motivo de cierre del ticket",
                input: "textarea",
                inputPlaceholder: "Motivo de cierre del ticket", 
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true,
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage("¡Debes capturar el motivo!");
                    } else {
                        return value;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    cambiarEst(id, estatus, fecha_cerrado, result.value);
                } else {
                    tblDetalleAsignados.draw(false);
                }
            });
        } else if (estatus == "REABRIR TICKET") {
            // Obtén las opciones del select original
            var opciones = $('#selectMotivoReapertura').html();

            Swal.fire({
                title: "Diseño Estructural",
                text: "Ingresa el motivo de reapertura del ticket",
                html: `
                    <textarea id="motivoReapertura" class="swal2-textarea" placeholder="Motivo de reapertura del ticket"></textarea>
                    <select id="motivoReaperturaSelect" class="swal2-select-custom" placeholder="Motivo de reapertura">
                        ${opciones}
                    </select>
                `,
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                confirmButtonText: "Guardar",
                cancelButtonText: "Cancelar",
                allowEnterKey: false,
                stopKeydownPropagation: true,
                reverseButtons: true,
                returnFocus: true,
                preConfirm: () => {
                    const motivo = document.getElementById('motivoReapertura').value.trim();
                    const motivoSelect = document.getElementById('motivoReaperturaSelect').value;
                    if (!motivo) {
                        Swal.showValidationMessage("¡Debes capturar el motivo!");
                        return false;
                    }
                    if (!motivoSelect) {
                        Swal.showValidationMessage("¡Debes seleccionar un motivo!");
                        return false;
                    }
                    return { motivo, motivoSelect };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Pasa ambos valores a tu función
                    cambiarEst(id, estatus, fecha_cerrado, result.value.motivo, result.value.motivoSelect);
                } else {
                    tblDetalleAsignados.draw(false);
                }
            });
        } else {
            cambiarEst(id, estatus, fecha_cerrado);
        }
    }
}

function cambiarEst(id, estatus, fecha_cerrado, motivo = null, motivoSelect = null) {
    let msg = '';

    $.ajax({
        url: serve + 'cambiarEstatusESTR',
        type: "PATCH",
        data: {
            _method: "PATCH",
            id: id,
            estatus: estatus,
            fecha_cerrado: fecha_cerrado,
            _token: CSRF_TOKEN,
            motivoCancelacion: motivo,
            motivoReapertura: motivoSelect,
        },
        success: function (data) {
            if (estatus == "CERRADO" && data.cotizacion) {
                msg = 'Ticket cerrado, se generó una nueva Oportunidad con base en este Ticket.'
            } else {
                msg = 'Estatus cambiado.'
            }
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: msg,
            });
            tblDetalleAsignados.draw(false);
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Ocurrió un error",
            });
            tblDetalleAsignados.draw(false);
        },
    });
}

function cambiar_operador(id) {
    operador = $('#operador_' + id).val();

    var dateToday = new Date();
    fecha_cambio = dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss');

    $.ajax({
        url: serve + 'cambiarOperadorESTR',
        type: "PATCH",
        data: {
            _method: "PATCH",
            id: id,
            operador: operador,
            fecha_cambio: fecha_cambio,
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: 'Operador Asignado',
            });
            tblDetalleAsignados.draw(false);
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Ocurrió un error",
            });
            tblDetalleAsignados.draw(false);
        },
    });
}

function guardarTicket() {
    var dateToday = new Date();
    if (save_method == 'add') {
        url = serve + 'guardarTicket';
        method = 'POST';
        $("#fechaRegistroEstr").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));
    } else {
        url = serve + 'actualizarTicket';
        method = 'PATCH';
    }

    $.ajax({
        url: url,
        method: method,
        dataType: "json",
        data: $("#modal-create-ticket form").serialize(),
        success: function (data) {
            if (data.response) {
                let modalE = document.querySelector("#modal-create-ticket");
                modalE.classList.remove("showModal");
    
                $("#modal-create-ticket").find("#formCreateEstr")[0].reset();
                $("#modal-create-ticket").modal("hide");
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
                tblDetalleAsignados.draw(false);

                if (tblDetalleAsignadosOport != undefined) {
                    tblDetalleAsignadosOport.ajax.reload()
                    tblDetalleAsignadosOport.draw();
                }
            }
        },
    });
}

 function asignarFechaCompromiso(id, fecha_actual) {
    fecha = $('#fecCompromiso_' + id).val();
    //console.log(fecha_actual);
    const dateToday = new Date();
    fecha_cambio = dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss');
    


    
   if (fecha_actual != null) {

         Swal.fire({
            input: "textarea",
            inputLabel: "Motivo del Cambio de Fecha",
            inputPlaceholder: "Captura el motivo del Cambio de Fecha",
            inputAttributes: {
                "aria-label": "Captura el motivo del Cambio de Fecha",
            },
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Guardar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value != "") {                    
                    $.ajax({
                        url: serve + 'asignarFechaCompromiso',
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id,
                            fecha: fecha,
                            motivoCambioFecha: result.value,
                            fecha_cambio: fecha_cambio,
                            _token: CSRF_TOKEN,
                        },
                        success: function (data) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: 'Fecha Compromiso Asignada',
                            });

                            tblDetalleAsignados.draw(false);

                        },
                        error: function () {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: "Ocurrió un error",
                            });

                            tblDetalleAsignados.draw(false);

                        },
                    });
                } else {
                    Swal.fire("¡Debes capturar el motivo!");
                    tblDetalleAsignados.draw(false);
                }
            } else {
                tblDetalleAsignados.draw(false);
            }
        });
    } else {
        $.ajax({
            url: serve + 'asignarFechaCompromiso',
            type: "PATCH",
            data: {
                _method: "PATCH",
                id: id,
                fecha: fecha,
                motivoCambioFecha: null,
                fecha_cambio: fecha_cambio,
                _token: CSRF_TOKEN,
            },
            success: function (data) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: 'Fecha Compromiso Asignada',
                });

                tblDetalleAsignados.draw(false);

            },
            error: function () {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: "Ocurrió un error",
                });

                tblDetalleAsignados.draw(false);

            },
        });
    }
}

nomOportunidadEstr.addEventListener('change', function () {
    validarRequeridosEstr();
});

nombOportunidad.addEventListener('change', function () {
    validarRequeridosEstr();
});

folioOportunidad.addEventListener('change', function () {
    validarRequeridosEstr();
});

cant1.addEventListener('change', function () {
    validarRequeridosEstr();
});

cant2.addEventListener('change', function () {
    validarRequeridosEstr();
});

cant3.addEventListener('change', function () {
    validarRequeridosEstr();
});

// cant4.addEventListener('change', function () {
//     validarRequeridosEstr();
// });

// cant5.addEventListener('change', function () {
//     validarRequeridosEstr();
// });

var render_dinamico = document.getElementById('render_dinamico');

descripcion.addEventListener('change', function () {
    validarRequeridosEstr();
});

dummy.addEventListener('change', function () {
    validarRequeridosEstr();
});

render.addEventListener('change', function () {
    validarRequeridosEstr();
});

render_dinamico.addEventListener('change', function () {
    validarRequeridosEstr();
});

render_dinamico.addEventListener('change', function () {
    validarRequeridosEstr();
});

// instructivo.addEventListener('change', function () {
//     validarRequeridosEstr();
// });

$('#tipo_producto').on('change', function () {
    validarRequeridosEstr();
});

function validarRequeridosEstr(data) {
    var oportunidadRel = $('#idOportunidadEstr').val();
    var clientesRel = $('#idClienteEstr').val();
    var opNombre = $('#nombOportunidad').val();
    let tipoProducto = $('#tipo_producto').val();
    if (nomOportunidadEstr.value == "" || clientesRel == "Nombre Cliente" || clientesRel == "" || clientesRel == "NULL" || opNombre == '' || opNombre == 'null' || opNombre == null || descripcion.value == ''
        || (tipoProducto == "" || tipoProducto == "NULL" || tipoProducto == null || tipoProducto == 'null')
        || (dummy.checked == false && render.checked == false && trazo.checked == false && render_dinamico.checked == false)
        || ((cant1.value == null && cant2.value == null && cant3.value == null)
            || (cant1.value == '' && cant2.value == '' && cant3.value == '')
            || (cant1.value == 0 && cant2.value == 0 && cant3.value == 0))) {

        $('#btnSaveTicket').prop('disabled', true);
        // agregarDocumento.disabled = true;
    } else {
        $('#btnSaveTicket').prop('disabled', false);
        // agregarDocumento.disabled = false;
    }

    if (nomOportunidadEstr.value == "") {
        nomOportunidadEstr.style.borderColor = 'red';
    } else {
        nomOportunidadEstr.style.borderColor = 'green';
    }
    
    if (nombOportunidad.value == "" || nombOportunidad.value == 'Nombre Oportunidad' || nombOportunidad.value == 'NULL' || nombOportunidad.value == null || nombOportunidad.value == 'null') {
        nombOportunidad.style.borderColor = 'red';
        nombOportunidad.value = '';
    } else {
        nombOportunidad.style.borderColor = 'green';
    }

    if (nomClienteEstr.value == "") {
        nomClienteEstr.style.borderColor = 'red';
    } else {
        nomClienteEstr.style.borderColor = 'green';
    }

    if (folioOportunidad.value == "" || folioOportunidad.value == 'Folio Oportunidad') {
        folioOportunidad.style.borderColor = 'red';
    } else {
        folioOportunidad.style.borderColor = 'green';
    }

    let idClienteEstr = $('#idClienteEstr').val();

    if (idClienteEstr == 'Nombre Cliente' || idClienteEstr == '') {
        $('#idClienteEstr').css('border-color', 'red');
    } else {
        $('#idClienteEstr').css('border-color', 'green');
        $("#idClienteINTEstr").val(idClienteEstr);

    }

    if (dummy.checked == false && render.checked == false && trazo.checked == false && render_dinamico.checked == false) {
        dummyLabel.style.color = 'red';
        renderLabel.style.color = 'red';
        trazoLabel.style.color = 'red';
        render_dinamicoLabel.style.color = 'red';
        // instructivoLabel.style.color = 'red';
    } else {
        dummyLabel.style.color = 'black';
        renderLabel.style.color = 'black';
        trazoLabel.style.color = 'black';
        render_dinamicoLabel.style.color = 'black';
        // instructivoLabel.style.color = 'black';
    }

    if (tipoProducto == "" || tipoProducto == "NULL" || tipoProducto == null || tipoProducto == 'null') {
        $('#tipo_producto').css('border-color', 'red');
    } else {
        $('#tipo_producto').css('border-color', 'green');
    }

    validarCantidades();

    if (descripcion.value == "") {
        descripcion.style.borderColor = 'red';
    } else {
        descripcion.style.borderColor = 'green';
    }
    validarEstado(data);
}

function validarEstado(data) {
    var oportunidadRel = $('#idOportunidadEstr').val();
    var idTicketEstrClon = $("#idTicketEstr").val();

    if (data != null) {
        if (data.Estado == 'PENDIENTE DE ATENDER' || data.Estado == 'PENDIENTE DE ASIGNAR') {
            var nodes = document.getElementById("formCreateEstr").getElementsByTagName('*');
            for (var i = 0; i < nodes.length; i++) {
                nodes[i].disabled = false;
            }
            $("#tituloTicket").text("EDITAR PROYECTO");
            nomOportunidadEstr.readOnly = false;
            nomOportunidadEstr.disabled = false;

            $('#tblDocsEstr').removeClass('disable');

            // if (data.Asignado == null || data.Asignado == '') {
            //     $("#cotizacion")[0].disabled = false;
            // } else {
            //     $("#cotizacion")[0].disabled = true;
            // }

            validarCantidades();
        } else if (data.Estado == 'CANCELADO') {
            var nodes = document.getElementById("formCreateEstr").getElementsByTagName('*');
            for (var i = 0; i < nodes.length; i++) {
                nodes[i].disabled = true;
            }

            btnBackTicket.disabled = false;
            // agregarDocumento.disabled = true;
            $('#tblDocsEstr').addClass('disable');

            $("#tituloTicket").text("VISUALIZAR PROYECTO");

        } else if (oportunidadRel == 0 && idTicketEstrClon == '') {
            var nodes = document.getElementById("formCreateEstr").getElementsByTagName('*');
            for (var i = 0; i < nodes.length; i++) {
                nodes[i].disabled = false;
            }

            btnBackTicket.disabled = false;
            // agregarDocumento.disabled = true;
            nomOportunidadEstr.readOnly = false;
            nomOportunidadEstr.disabled = false;

            $('#tblDocsEstr').removeClass('disable');

            $("#tituloTicket").text("NUEVO PROYECTO");

            validarCantidades();

        } else if (data.Estado == 'ATENDIENDOSE' || data.Estado == 'PAUSADO' || data.Estado == 'DETENIDO' || data.Estado == 'CERRADO') {
            var nodes = document.getElementById("formCreateEstr").getElementsByTagName('*');
            for (var i = 0; i < nodes.length; i++) {
                nodes[i].disabled = true;
            }
            btnBackTicket.disabled = false;
            // agregarDocumento.disabled = false;
            // $("#cotizacion")[0].disabled = true;

            $("#tituloTicket").text("VISUALIZAR PROYECTO");

        }
    }
}

function validarCantidades() {

    if (cant1.value == "" || cant1.value == null || cant1.value == 0) {
        cant1.style.borderColor = 'red';
        cant2.disabled = true;
        cant2.value = '';
    } else {
        cant1.style.borderColor = 'green';
        cant2.disabled = false;
    }

    if (cant2.value == "" || cant2.value == null || cant2.value == 0) {
        cant3.disabled = true;
        cant3.value = '';
    } else {
        cant3.disabled = false;
    }

    // if (cant3.value == "" || cant3.value == null || cant3.value == 0) {
    //     cant4.disabled = true;
    //     cant4.value = '';
    // } else {
    //     cant4.disabled = false;
    // }

    // if (cant4.value == "" || cant4.value == null || cant4.value == 0) {
    //     cant5.disabled = true;
    //     cant5.value = '';
    // } else {
    //     cant5.disabled = false;
    // }

}
var idTicketFolioEstr = $("#folio").val(),
    groupEstr = 2,
    formDataEstr = "",
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
//------- CLICKS----/
$("#btnSaveDocsEstr").click(function () {
    loaderON();

    // Valida que se haya seleccionado un tipo de documento
    let FileDocEstr = $("#FileDocEstr").val();

    if (FileDocEstr == "") {
        loaderOut();
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000,
        });
        Msg.fire({
            title: "Debes seleccionar un archivo",
        });
        return false;
    }

    addDocumentoEstr();
});
//-----CHECKS----/
$("input[name='FileDocEstr']").change(function () {
    const dateToday = new Date();
    let fecha_cambio = dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss')
    let idOportunidad = $('#idOportunidadEstr').val();

    formDataEstr = new FormData();
    var idTipoDocumentoEstr = $("#idTipoDocumentoEstr").val();
    var idTicketEstr = $("#idTicketEstr").val();
    var id_clienteEstr = $("#idClienteINTEstr").val();
    formDataEstr.append("folio", idTicketEstr);
    formDataEstr.append("idTipoDocumento", idTipoDocumentoEstr);
    formDataEstr.append("cliente", id_clienteEstr);
    formDataEstr.append("fileEstr", $("input[type=file]")[0].files[0]);
    formDataEstr.append("fecha_cambio", fecha_cambio);
    formDataEstr.append("idOportunidad", idOportunidad);
    // archivos = [];
    // formData.append("files", archivos);
});
//------FUNCIONES---/

function tblDocsEstr() {
    let idTicketEstr = $("#idTicketEstr").val();
    let id_clienteEstr = $("#idClienteINTEstr").val();

    let idUser = $('#idUsuario').val();
    let usuarioPerteneceTicket = $('#usuarioPerteneceTicket').val();

    tableDocsEstr = $("#tblDocsEstr").DataTable(
        {
            destroy: true,
            processing: true,
            pageLength: 50,
            scrollY: false,
            scrollX: true,
            serverSide: true,
            colReorder: false,
            bLengthChange: false,
            // orderable: false,
            order: [
                [9, "asc"]
            ],
            columnDefs: [
                {
                    className: "dt-center",
                    targets: [4, 5, 7, 8]
                }, {
                    visible: false,
                    targets: [0, 1, 2, 6, 9]
                },
                // {
                //     targets: [0, 3],
                //     className: "dt-center",
                // },
                {
                    targets: [4],
                    render: $.fn.dataTable.render.moment(FROM_PATTERN_ESTR, TO_PATTERN_ESTR),
                },
            ],
            ajax: {
                url: serve + "apiEstructuralDocs",
                type: "GET",
                dataType: "json",
                data: {
                    idTicket: idTicketEstr,
                    IdCliente: id_clienteEstr,
                },
            },
            columns: [
                {
                    data: "idTipoDocumento",
                    name: "idTipoDocumento",
                    title: "#",
                }, {
                    data: "idDocumento",
                    name: "idDocumento",
                    title: "Id Documento",
                },
                {
                    data: "descTipoDocumento",
                    name: "descTipoDocumento",
                    title: "Documento",
                },
                {
                    data: "descDocumento",
                    name: "descDocumento",
                    title: "Documento",
                },
                {
                    data: "fecRegistro",
                    name: "fecRegistro",
                    title: "Fecha Registro",
                },
                {
                    data: "Login",
                    name: "Login",
                    title: "Usuario Registro"
                },
                {
                    data: "NoDocsEstr",
                    name: "NoDocsEstr",
                    title: "dfsd"
                },
                {
                    data: "NoDocsEstr",
                    name: "NoDocsEstr",
                    title: "# Docs"
                },
                {
                    data: "idDocumento",
                    render: function (data, type, row) {
                        // if (row.idTipoDocumento > 0) {
                        //     return ('<a class="fas fa-download icondocsdown" onclick="visorDocumentosEstr(' + data + ')" ></a><i class="fas fa-trash-alt icondocsdelete" onclick="deleteDocEstr(' + data + ')"></i><i class="fas fa-envelope icondocsmail ml-3" onclick="mailDocumentosEstr(' + data + ')"></i>');
                        // } else {
                            if (!($('#idRolEstr').val() == 1024 || ($('#idRolEstr').val() == 1026 && idUser != usuarioPerteneceTicket))) {
                                if (row.rol == 1 || row.rol ==  8 || row.rol == 1022 || row.rol == 1021 || row.rol == 1026 || (row.rol == 1020 && row.idTipoDocumento == 10)) {
                                    return ('<a class="fas fa-download icondocsdown" onclick="visorDocumentosEstr(' + data + ')" ></a><i class="fas fa-trash-alt icondocsdelete" onclick="deleteDocEstr(' + data + ')"></i>');
                                } else {
                                    return ('<a class="fas fa-download icondocsdown" onclick="visorDocumentosEstr(' + data + ')" ></a>');
                                }

                            } else {
                                return ('<a class="fas fa-download icondocsdown" onclick="visorDocumentosEstr(' + data + ')" ></a>');
                            }
                        // }
                    },
                    name: "idDocumento",
                    title: "Acción",
                },
                {
                    data: 'orden',
                    name: 'orden',
                    visible: false,
                }
            ],
            rowGroup: {
                dataSrc: "descTipoDocumento",

                startRender: function (rowsD, groupD, all) {
                    var collapsedD = !!collapsedGroupsD[groupD],
                        noelmentosD = rowsD.data().pluck('NoDocsEstr').reduce(function (a, b) {
                            return a + b * 1;
                        }, 0),
                        contadorD = noelmentosD,
                        firstRowDataD = rowsD.data().pluck("idTipoDocumento");
                        firstRowDataE = rowsD.data().pluck("idTipoDocOport");
                    let actionS = '';

                    // Validamos si el rol es 1024 blockeamos todo el formulario
                    if (!($('#idRolEstr').val() == 1024 || ($('#idRolEstr').val() == 1026 && idUser != usuarioPerteneceTicket))) {
                        actionS = `<i class="fas fa-upload  icondocsup" onclick="modal_upDocsEstr(${firstRowDataD[0]})" data-id="${firstRowDataD[0]}"></i></td>`;
                    }

                    let rol =  rowsD.data()[0].rol;

                    if (((rol == 4 || rol == 9) && rowsD.data()[0].idTipoDocumento == 11)) {
                        actionS = `<i class="fas fa-upload  icondocsup" onclick="modal_upDocsEstr(${firstRowDataD[0]})" data-id="${firstRowDataD[0]}"></i></td>`;
                    }

                    let validationSee = (rol == 1 || rol ==  8 || rol == 1022 || rol == 1021 || rol == 1026 || (rol == 1020 && rowsD.data()[0].idTipoDocumento == 10) || ((rol == 4 || rol == 9 && rowsD.data()[0].idTipoDocumento == 11))) && actionS != '' ? '' : 'd-none';

                    var elemD = $("<tr/>").append('<td colspan="3"  class="rowcolap"><i class="fas fa-plus-circle iconaddDocs icontbl"></i>&nbsp;&nbsp;' + groupD + '</td><td class="rowcolap text-center">' + contadorD + '</td><td  class="rowcolap text-center '+(validationSee)+'">' + actionS + '</td>').attr("data-name", groupD.replace(" ", "")).toggleClass("collapsed", collapsedD);
                    var elem1D = $("<tr/>").append('<td colspan="3"  class="rowcolap"><i class="fas fa-plus-circle icondocsBloq "></i>&nbsp;&nbsp;' + groupD + '</td><td class="rowcolap text-center '+(validationSee)+'">' + contadorD + '</td><td  class="rowcolap text-center '+(validationSee)+'">' + actionS + '</td>').attr("data-name", groupD.replace(" ", ""));
                    $(rowsD.nodes()).hide(); // todos  colapsados
                    elemD.on("click", function (e) {
                        if (collapsedGroupsD[groupD]) {
                            if ($(rowsD.nodes()).is(":hidden")) {
                                $(rowsD.nodes()).show();
                                $(".icontbl").removeClass("fa-plus-circle");
                                $(".icontbl").addClass("fa-minus-circle");
                            } else {
                                $(rowsD.nodes()).hide();
                                $(".icontbl").addClass("fa-plus-circle");
                                $(".icontbl").removeClass("fa-minus-circle");
                            }
                        } else {
                            if ($(rowsD.nodes()).is(":hidden")) {
                                $(rowsD.nodes()).show();
                                $(".icontbl").removeClass("fa-plus-circle");
                                $(".icontbl").addClass("fa-minus-circle");
                            } else {
                                $(rowsD.nodes()).hide();
                                $(".icontbl").addClass("fa-plus-circle");
                                $(".icontbl").removeClass("fa-minus-circle");
                            }
                        } collapsedGroupsD[groupD] = !collapsedGroupsD[groupD];
                    });
                    if (noelmentosD >= 1) {
                        return elemD;
                    } else {
                        return elem1D;
                    }
                }
            },
            language: {
                url: serve + "js/spanish-DT.json",
            },
            dom: '<"top">rt<"bottom"><"clear">',
            select: true,
            bInfo: true,
            lengthMenu: [
                [50, 100, 150, 200, -1],
                [50, 100, 150, 200, 'TODOS'],
            ],

            "initComplete": function (settings, json) { loaderOut(); },
        }
    );
}

function modal_upDocsEstr(data) {
    OpenModalDocEstr();
    console.log(data);
    $(".lblDocsUp").text("");
    $("#idTipoDocumentoEstr").val(data);
}


function modal_deleteDocsEstr(data) {
    var idDocumentodeleteEstr = data;
    deleteDocEstr(idDocumentodeleteEstr);
}

function OpenModalDocEstr() {
    $("#mod_UpDocsEstr").modal("show");
    // $('.iborrainputfile').text(null);
}

async function addDocumentoEstr() {
    // Mostrar el modal y deshabilitar los botones al iniciar
    $("#mod_UpDocsEstr").modal("show");
    $('#btnSaveDocsEstr').prop('disabled', true);
    $('#closeModalEstrOport').prop('disabled', true);

    try {
        const data = await $.ajax({
            url: serve + "upDocsEstructural",
            type: "POST",
            data: formDataEstr,
            processData: false,
            contentType: false,
        });

        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "success",
            showConfirmButton: false,
            timer: 3000,
        });

        if (data.response == 'error') {
            Msg.fire({
                title: data.error,
                icon: 'error'
            });
            loaderOut();
            // Restaurar botones y mantener el modal abierto
            $('#btnSaveDocsEstr').prop('disabled', false);
            $('#closeModalEstrOport').prop('disabled', false);
            return;
        }

        Msg.fire({
            title: "Documento Guardado",
        });
        loaderOut();
        $("#mod_UpDocsEstr :input").val("");
        tableDocsEstr.ajax.reload();

        // Cerrar el modal y restaurar botones
        $("#mod_UpDocsEstr").modal("hide");
        $('#btnSaveDocsEstr').prop('disabled', false);
        $('#closeModalEstrOport').prop('disabled', false);

    } catch (error) {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000,
        });

        Msg.fire({
            title: "Ocurrió un error al Subir el Archivo, vuelve a intentarlo",
        });

        loaderOut();
        // Restaurar botones y mantener el modal abierto
        $('#btnSaveDocsEstr').prop('disabled', false);
        $('#closeModalEstrOport').prop('disabled', false);
    }
}

function deleteDocEstr(idDocumentodeleteEstr) {
    Swal.fire({
        title: "Documentos",
        text: "Eliminar el Documento Seleccionado?",
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
            $.ajax({
                url: serve + "deleteDocsEstructural",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idDocumentoEstrdelete: idDocumentodeleteEstr,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    Msg.fire({
                        title: "Documento Eliminado",
                    });
                    $("#mod_UpDocsEstr").modal("hide");
                    loaderOut();
                    $("#mod_UpDocsEstr :input").val("");
                    tableDocsEstr.ajax.reload();
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    Msg.fire({
                        title: "Ocurrió un error, vuelve a intentarlo",
                    });
                },
            });
        }
    });
}

function visorDocumentosEstr(idDocumentoEstr) {
    loaderModalON();
    $.ajax({
        url: serve + "getDocumentoEstructural",
        type: "GET",
        data: {
            _method: "GET",
            idDocumento: idDocumentoEstr,
        },
        success: function (data) {

            extensionEstr = data.Extension;
            byteCharactersEstr = atob(data.Documento);
            descDocumentoEstr = data.descDocumento;

            if (extensionEstr.match(/^.*vnd.*$/) || extensionEstr.match(/^.*stream$/)) {

                window.location.href = serve + 'downDocsEstructural/' + idDocumentoEstr;

                loaderModalOut();

            } else {

                $("#modal-visor-documentos").modal("show");
                $('.tituloVisorDocumentos').text(descDocumentoEstr);
                descargarImage = document.getElementById('descargarImage');

                if (extensionEstr.match(/^.*image.*$/)) {
                    descargarImage.style.display = 'block'
                    $('#idDocumento').val(idDocumentoEstr);
                } else {
                    // $('#idDocumento').val(idDocumentoEstr);
                    descargarImage.style.display = 'none'
                }

                var byteNumbersEstr = new Array(byteCharactersEstr.length);
                for (var i = 0; i < byteCharactersEstr.length; i++) {
                    byteNumbersEstr[i] = byteCharactersEstr.charCodeAt(i);
                }
                var byteArrayEstr = new Uint8Array(byteNumbersEstr);
                var blobEstr = new Blob([byteArrayEstr], { type: extensionEstr });

                blobEstr = window.URL.createObjectURL(blobEstr);

                var visorPDFEstr = document.getElementById('frameVisorDocumentos');

                visorPDFEstr.src = blobEstr;

                loaderModalOut();
            }
        },
        error: function () { },
    });
}

function descargarImagen() {
    idDocumentoEstr = $('#idDocumento').val();
    window.location.href = serve + 'downDocsEstructural/' + idDocumentoEstr;
}

var abiertos = 'ABIERTOS';

$("#abiertosCerrados").on('click', function () {
    check = $("#abiertosCerrados")[0].checked;
    if (check == true) {
        abiertos = 'CERRADOS'
    } else {
        abiertos = 'ABIERTOS'
    }
    buscarEstr(0);
    expandirTodo();
});

$("#searchEstr").keypress(function (e) {
    if (e.which == 13) { // loaderON();
        buscarEstr();
    }
});

function aplicarFiltro() {
    buscarEstr();
}

function LimpiarFiltro() {
    //$('#searchEstr').val(null);

    $('#searchFolio').val(null)
    $('#searchLiberado').val(null)
    $('#searchCliente').val(null)
    $('#searchTitulo').val(null)
    $('#selectSituacion').val(null)
    $('#selectOperador').val(null)
    $('#selectEstatus').val(null)
    $('#searchMotivoReapertura').val(null)
    $('#selectAgente').val(null)

    if ($('#idRolEstr').val() != 1021) {
        getItemsOperador()
    } else {
        $('#selectOperador').hide();
    }

    getItemsEstatus()

    buscarEstr()
}

function buscarEstr(getResults = 1) {
    var texto = $('#searchEstr').val();
    var id_oportunidad_estr = 0;
    rol_estr = $('#idRolEstr').val();

    if ((rol_estr == 1 && $('#idUsuario').val() != 2115) || rol_estr == 10 || rol_estr == 8 || (rol_estr == 1026 && $('#idUsuario').val() == 4264)) {
        $mostrar_lib = true;
        $mostrar_cot = true;
    } else {
        $mostrar_lib = false;
        $mostrar_cot = false;
    }

    if (rol_estr == 1020 || rol_estr == 1021) {
        $mostrar_oport = false;
    } else {
        $mostrar_oport = true;
    }

    let buttonsCustom 

    if (rol_estr == 1 || rol_estr == 8 || rol_estr == 1026) {
        buttonsCustom = {
                extend: 'excelHtml5',
                title: 'Estructuras | CANDY CRM',
                text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                exportOptions: {
                    orthogonal: 'export',
                    columns: "thead th:not(.noExport)", // Excluye columnas con la clase "noExport"
                    format: {
                        body: function (data, row, column, node) {
                            // Si el nodo es un input, toma su valor
                            if ($(node).find('input').length) {
                                return $(node).find('input').val();
                            }
                            // Si el nodo es un select, toma el texto seleccionado
                            if ($(node).find('select').length) {
                                return $(node).find('select option:selected').text();
                            }
                            // Si el nodo es un div, toma el texto dentro del div
                            if ($(node).find('div').length) {
                                return $(node).find('div').text();
                            }
                            // Si el nodo es un span, toma el texto dentro del span
                            if ($(node).find('span').length) {
                                return $(node).find('span').text();
                            }
                            // Si el nodo es un a tag, toma el texto dentro del a tag
                            if ($(node).find('a').length) {
                                return $(node).find('a').text();
                            }
                            // De lo contrario, devuelve el texto del nodo
                            return data;
                        },
                    },
                },
                filename: 'Estructuras',
                autoFilter: true,
                sheetName: 'Lista',
                messageTop: null,
                body: true,
                footer: false,
            }
    } else {
        buttonsCustom = {}
    }

    // Validamos si hay filtros activos para obtener resultados
    if (
        ($('#searchFolio').val() != null && $('#searchFolio').val() != '') || 
        ($('#searchLiberado').val() != null && $('#searchLiberado').val() != '') || 
        ($('#searchCliente').val() != null && $('#searchCliente').val() != '') || 
        ($('#searchTitulo').val() != null && $('#searchTitulo').val() != '') || 
        ($('#selectSituacion').val() != null && $('#selectSituacion').val() != '') || 
        ($('#selectOperador').val() != null && $('#selectOperador').val() != '') || 
        ($('#selectEstatus').val() != null && $('#selectEstatus').val() != '') || 
        ($('#selectAgente').val() != null && $('#selectAgente').val() != '') || 
        ($('#searchMotivoReapertura').val() != null && $('#searchMotivoReapertura').val() != '') || 
        ($('#searchOportunidad').val() != null && $('#searchOportunidad').val() != '')
    ) {
        getResults = 1;
    } else {
        getResults = 0;
    }

    tblDetalleAsignados = $('#tblDetalleAsignados').DataTable({
        destroy: true,
        pageResize: false,
        searchable: true,
        processing: false,
        serverSide: true,
        rowId: "Id_Ticket",
        select: false,
        scrollX: true,
        scrollCollapse: true,
        stateSave: false,
        deferRender: true,
        order: [
            [14, "DESC"],
            [15, "ASC"]
        ],
        ajax: {
            url: serve + "apiEstructural",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: id_oportunidad_estr,
                texto: texto,
                abiertos: abiertos,
                getResults: getResults,

                searchFolio: $('#searchFolio').val(),
                searchLiberado: $('#searchLiberado').val(),
                searchCliente: $('#searchCliente').val(),
                searchTitulo: $('#searchTitulo').val(),
                selectSituacion: $('#selectSituacion').val(),
                selectOperador: $('#selectOperador').val(),
                selectEstatus: $('#selectEstatus').val(),
                selectAgente: $('#selectAgente').val(),
                searchMotivoReapertura: $('#searchMotivoReapertura').val(),
                searchOportunidad: $('#searchOportunidad').val(),
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpB><"clear">',
        buttons: [
            buttonsCustom,
        ],
        columnDefs: [
            {
                targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 16, 17, 18,19],
                orderable: false,
            },
            {
                targets: [1],
                render: $.fn.dataTable.render.moment(FROM_PATTERN_ESTR, TO_PATTERN_ESTR),
            },
            {
                targets: [11],
                render: $.fn.dataTable.render.moment(FROM_PATTERN_ESTR_SHORT, TO_PATTERN_ESTR_SHORT),
            },
            {
                targets: [1, 2, 3, 4, 8, 10, 12, 13, 16, 17, 18],
                className: "dt-body-center text-center",
            },
            {
                targets: 9,
                render: function (data, type, row, meta) {
                    if (
                        (row.inputRol == 8 ||
                        row.inputRol == 1 ||
                        row.inputRol == 1020) &&
                        row.liberado == 1 && row.Estado != 'CERRADO' && row.Estado != 'CANCELADO' && row.Estado != 'RECHAZADO'
                    ) {
                        var $select_op = $(
                            '<select id="operador_' +
                            row.Id_Ticket +
                            '" class="form-control select_op" onchange="cambiar_operador(' + row.Id_Ticket + ')"><option selected disabled value="SIN ASIGNAR">SIN ASIGNAR</option></select>'
                        );
                        $.each(operador_estr, function (k, v) {
                            var $option_op = $("<option></option>", {
                                text: v,
                                value: v,
                            });
                            if (data == v) {
                                $option_op.attr("selected", "selected");
                            }

                            $select_op.append($option_op);
                        });

                        return $select_op.prop("outerHTML");
                    } else {
                        if (data == null || data == "") {
                            return "<span>SIN ASIGNAR</span>";
                        } else {
                            return "<span>" + data + "</span>";
                        }
                    }
                },
            },
            {
                targets: 10,
                render: function (data, type, row, meta) {
                    if (
                        row.inputRol == 8 ||
                        row.inputRol == 1 ||
                        row.inputRol == 1020
                    ) {

                        if (data == 'CANCELADO' || data == 'RECHAZADO' || (data != 'CERRADO' && row.liberado != 1)) {
                            return "<span>" + data + "</span>";
                        } else {
                            var $select = $(
                                '<select id="estatus_' +
                                row.Id_Ticket +
                                '" class="form-control select_st" onchange="cambiar_estatus(' + row.Id_Ticket + ')" >'
                                + '<option value="PENDIENTE DE ASIGNAR" disabled selected>PENDIENTE DE ASIGNAR</option></select>'
                            );
                            $.each(estatus_estr, function (k, v) {
                                var $option = $("<option></option>", {
                                    text: v,
                                    value: v,
                                });
                                if (data == v) {
                                    $option.attr("selected", "selected");
                                }
                                if (data == "PENDIENTE DE ATENDER") {
                                    if (v == "PENDIENTE DE ATENDER" || v == "PAUSADO" || v == "CERRADO" || v == 'REABRIR TICKET') {
                                        $option.attr("hidden", "hidden");
                                    }
                                } else if (data == 'PAUSADO') {
                                    if (v == "PENDIENTE DE ATENDER" || v == "REABRIR TICKET") {
                                        $option.attr("hidden", "hidden");
                                    }
                                } else if (data == 'ATENDIENDOSE') {
                                    if (v == "PENDIENTE DE ATENDER" || v == "REABRIR TICKET") {
                                        $option.attr("hidden", "hidden");
                                    }
                                } else if (data == 'CERRADO') {
                                    if (v == "PENDIENTE DE ATENDER" || v == "NOTIFICAR A VENTAS") {
                                        $option.attr("hidden", "hidden");
                                    }
                                }
                                $select.append($option);
                            });
                            if (row.Asignado == 'SIN ASIGNAR') {
                                $select.attr('disabled', 'disabled');
                            }
                            return $select.prop("outerHTML");
                        }
                    } else if (row.inputRol == 1021) {
                        if (data == 'CANCELADO' || data == 'CERRADO' || data == 'RECHAZADO') {
                            return "<span>" + data + "</span>";
                        } else {
                            var $select = $(
                                '<select id="estatus_' +
                                row.Id_Ticket +
                                '" class="form-control select_st" onchange="cambiar_estatus(' + row.Id_Ticket + ')" >'
                                + '<option value="PENDIENTE DE ASIGNAR" disabled selected>PENDIENTE DE ASIGNAR</option></select>'
                            );
                            $.each(estatus_estr, function (k, v) {
                                var $option = $("<option></option>", {
                                    text: v,
                                    value: v,
                                });
                                if (data == v) {
                                    $option.attr("selected", "selected");
                                }
                                if (data == "PENDIENTE DE ATENDER") {
                                    if (v == "PENDIENTE DE ATENDER" || v == "PAUSADO" || v == "CERRADO" || v == 'REABRIR TICKET') {
                                        $option.attr("hidden", "hidden");
                                    }
                                } else if (data == 'PAUSADO') {
                                    if (v == "PENDIENTE DE ATENDER" || v == "REABRIR TICKET") {
                                        $option.attr("hidden", "hidden");
                                    }
                                } else if (data == 'ATENDIENDOSE' || data == 'REABRIR TICKET') {
                                    if (v == "PENDIENTE DE ATENDER") {
                                        $option.attr("hidden", "hidden");
                                    }
                                }
                                $select.append($option);
                            });
                            if (row.Asignado == 'SIN ASIGNAR') {
                                $select.attr('disabled', 'disabled');
                            }
                            return $select.prop("outerHTML");
                        }
                    } else if (row.inputRol == 10) {

                        if (row.liberado == 1) {
                            return "<span>" + data + "</span>";
                        } else {
                            var $select = $(
                                '<select id="estatus_' +
                                row.Id_Ticket +
                                '" class="form-control select_st" onchange="cambiar_estatus(' + row.Id_Ticket + ')" >'
                                + '<option value="PENDIENTE DE ASIGNAR" disabled selected>PENDIENTE DE ASIGNAR</option></select>'
                            );
                            $.each(estatus_estr, function (k, v) {
                                var $option = $("<option></option>", {
                                    text: v,
                                    value: v,
                                });
                                if (data == v) {
                                    $option.attr("selected", "selected");
                                }
                                if (v != 'CANCELADO') {
                                    $option.attr("hidden", "hidden");
                                }
                                $select.append($option);
                            });
                            return $select.prop("outerHTML");

                        }
                    } else {
                        if (data == null || data == "") {
                            return "<span>PENDIENTE DE ASIGNAR</span>";
                        } else {
                            return "<span>" + data + "</span>";
                        }
                    }
                },
            },
        ],
        columns: [
            {
                data: 'master', // 0
                title: "Folio",
                className: 'text-bold dt-body-center text-center',
            },
            {
                data: 'FechaRegistro', // 1
                title: "Registro",
            },
            {
                data: 'Situacion', // 2
                render: function (data, type, row) {
                    if (data == 'VENCE MAÑANA') {
                        return '<div class="bg-warning-estr">' + data + '</div>'
                    } else if (data == 'ATRASADO') {
                        return '<div class="bg-danger-estr">' + data + '</div>'
                    } else if (data == 'VENCE HOY') {
                        return '<div class="bg-info-estr">' + data + '</div>'
                    } else if (data == 'A TIEMPO') {
                        return '<div class="bg-success-estr">' + data + '</div>'
                    } else {
                        return '<div class="bg-not-estr"> --- </div>'
                    }
                },
                title: "Situación",
            },
            {
                data: 'liberado', // 3
                render: function (data, type, row) {
                    let $checked1 = '';
                    let $checked2 = '';
                    let $disabled = '';
                    let $cursor = 'cursor-pointer';
                    let onclick = '';

                    // Verificamos el valor de "data" para determinar qué input marcar
                    if (data == 1) {
                        $checked1 = 'checked'; // Marca el input con terminación -1
                        $checked2 = '';        // No marca el input con terminación -2
                        $disabled = 'disabled'; // Deshabilita ambos inputs
                        $cursor = 'cursor-not-allowed';
                        onclick = '';
                    } else if (data == 0 && (row.Estado != "PAUSADO" && row.Estado != "CERRADO" && row.Estado != "RECHAZADO") && ((rol_estr == 1 && $('#idUsuario').val() != 2115) || (rol_estr == 8) || (rol_estr == 1026 && $('#idUsuario').val() == 4264))) {
                        if ($('#idUsuario').val() == 3433 && row.Id_Ticket == 8341) {
                        }
                        $checked1 = '';        // No marca el input con terminación -1
                        $checked2 = ''; // Marca el input con terminación -2
                        $disabled = '';
                        $cursor = '';
                        onclick = 'onclick="liberarTicket(' + row.Id_Ticket + ', this.value)"';
                        // Resetear el radio button
                        let radioButton =  document.getElementById(`inp${row.Id_Ticket}-0`);
                        if (radioButton) {
                            // remove the checked attribute
                            radioButton.removeAttribute('checked');
                            radioButton.checked = false;
                        }
                    } else if (data == 0 && (row.Estado == "PAUSADO" || row.Estado == "CERRADO" || row.Estado == "RECHAZADO") && ((rol_estr == 1 && $('#idUsuario').val() != 2115) || (rol_estr == 8) || (rol_estr == 1026 && $('#idUsuario').val() == 4264))) {
                        $checked1 = '';        // No marca el input con terminación -1
                        $checked2 = 'checked'; // Marca el input con terminación -2
                        $disabled = '';
                        $cursor = '';
                        onclick = 'onclick="liberarTicket(' + row.Id_Ticket + ', this.value)"';
                    } else {
                        $checked1 = '';        // No marca el input con terminación -1
                        $checked2 = '';        // No marca el input con terminación -2
                        $disabled = '';
                        $cursor = '';
                        onclick = 'onclick="liberarTicket(' + row.Id_Ticket + ', this.value)"';
                    }

                    if ((rol_estr == 1 && $('#idUsuario').val() != 2115) || (rol_estr == 10 && $('#idUsuario').val() != 1097) || rol_estr == 8 || (rol_estr == 1026 && $('#idUsuario').val() == 4264)) {
                        return `
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center mr-1">
                                    <label class="mb-0 mr-1" for="inp${row.Id_Ticket}-1">Liberar</label>
                                    <input type="radio" class="${$cursor}" ${$checked1} ${$disabled} name="${row.Id_Ticket}" id="inp${row.Id_Ticket}-1" ${onclick} value="1" />
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="radio" class="${$cursor}" ${$checked2} ${$disabled} name="${row.Id_Ticket}" id="inp${row.Id_Ticket}-0" ${onclick} value="0" />
                                    <label class="mb-0 ml-1" for="inp${row.Id_Ticket}-0">Rechazar</label>
                                </div>
                            </div>
                        `;
                    } else {
                        if (data == 1) {
                            return '<span><i class="fas fa-check-circle fa-2x text-green_ text-center"></i></span>';
                        } else {
                            // mostramos icono de pregunta
                            if (row.Estado == "PAUSADO" || row.Estado == "CERRADO" || row.Estado == "RECHAZADO") {
                                return '<span><i class="fas fa-times-circle fa-2x text-red_ text-center"></i></span>';
                            } else {
                                return '<span><i class="fa-solid fa-clock-rotate-left fa-2x text-yellow_ text-center"></i></span>';
                            }
                        }
                    }
                },
                title: "Liberado",
            },
            {
                data: 'cotizacion', // 4
                render: function (data, type, row) {
                    if (data == 1) {
                        return '<span><i class="fas fa-check-circle fa-2x text-green_ text-center"></i></span>';
                    } else {
                        return '<span><i class="fas fa-times-circle fa-2x text-red_ text-center"></i></span>';
                    }
                },
                title: "Cotización",
                // visible: $mostrar_cot,
            },
            {
                data: 'Solicitante', // 5
                title: "Solicita",
            },
            {
                data: 'Cliente', // 6
                title: "Cliente",
            },
            {
                data: 'Tarea', // 7
                render: function (data, type, row) {
                    if (row.Estado == 'PENDIENTE DE ATENDER') {
                        $title = 'Editar Proyecto';
                    } else {
                        $title = 'Visualizar Proyecto';
                    }
                    return (
                        '<a class="text-primary" type="button" onclick="editarTicketDE(' +
                        row.Id_Ticket +
                        ' )" title="' + $title + '">' +
                        data +
                        "</a>"
                    );
                },
                title: "Titulo",
            },
            {
                data: 'FolioOportunidad', // 8
                render: function (data, type, row) {
                    if (row.idOportunidad == null) {
                        return '';
                    } else {
                        return (
                            '<a href="oportunidades/' +
                            row.idOportunidad +
                            '/edit" onclick="loaderON()" class="text-uppercase box-table btn_editar_oportunidad">' +
                            data +
                            "</a>"
                        );
                    }
                },
                title: "Oportunidad",
                visible: $mostrar_oport,
            },
            {
                data: 'Asignado', // 9
                title: "Operador",
            },
            {
                data: 'Estado', // 10
                title: "Estado",
            },
            {
                data: 'FechaCompromiso', // 11
                render: function (data, type, row) {
                    
                    if (row.Asignado == 'SIN ASIGNAR') {
                        return '<div class="border-bottom">--</div>';
                    } else {
                        if (
                        
                             row.inputRol == 8 ||
                             row.inputRol == 1 
                            // row.inputRol == 1020 
                           
                        ) {
                            if (data == null) {
                                return '<input id="fecCompromiso_' + row.Id_Ticket + '"'
                                    + '  type="date" class="form-control"'
                                    + 'min="' + dateFormat(minDate, "yyyy-mm-dd") + '"'
                                    + 'onchange="asignarFechaCompromiso(' + row.Id_Ticket + ', ' + data + ')" >';
                            } else {
                                return '<input id="fecCompromiso_' + row.Id_Ticket + '"'
                                    + 'type="date" class="form-control"'
                                    + 'min="' + dateFormat(minDate, "yyyy-mm-dd") + '"'
                                    + 'onchange="asignarFechaCompromiso(' + row.Id_Ticket + ', ' + data + ')" value="' + data + '" >';
                            }
                        } else if (                              
                             row.inputRol == 1020 ||
                             row.inputRol == 1021                                                        
                            ) {
                            if (data == null) {
                                return '<input id="fecCompromiso_' + row.Id_Ticket + '"'
                                    + 'type="date" class="form-control"'
                                    + 'min="' + dateFormat(minDate, "yyyy-mm-dd") + '"'
                                    + 'onchange="asignarFechaCompromiso(' + row.Id_Ticket + ', ' + data + ')" >';
                            } else {
                                return '<div class="border-bottom">' + moment(data).format(TO_PATTERN_ESTR_SHORT) + '</div>';
                            }
                        } else {
                            if (data == null) {
                                return '<div class="border-bottom">---</div>';
                            } else {
                                return '<div class="border-bottom">' + moment(data).format(TO_PATTERN_ESTR_SHORT) + '</div>';
                            }
                        }
                    }
                },
                title: "Compromiso",
            },
            {
                data: 'Id_Ticket', // 13
                render: function (data, type, row) {
                    if (row.idOportunidad != null) {
                        return '<span><i class="fas fa-check-circle fa-2x text-green_ text-center"></i></span>';
                    } else {
                        return '<span><i class="fas fa-times-circle fa-2x text-red_ text-center"></i></span>';
                    }
                },
                title: "Relacionado",
            },
            {
                data: 'Categoria', // 14
                title: 'Categoria',
                visible: false
            },
            {
                data: 'producto', // 15
                title: 'Producto',
                visible: false
            },
            {
                data: 'vendido', // 16
                render: function (data, type, row) {
                    if (data == 'Vendido') {
                        return '<span><i class="fas fa-dollar-sign fa-2x text-gold"></i></span>';
                    } else {
                        return '';
                    }
                },
                title: 'Venta',
            },
            {
                data: 'Id_Ticket', // 17
                render: function (data, type, row) {
                    return '<a title="Imprimir" onclick="generarTicket(' + data + ')" target="_blank" ><i class="fas fa-file-pdf fa-2x text-red btnEP"></i></a>';
                },
                name: 'Id_Ticket',
                width: '1rem',
                title: 'PDF'
            },
            {
                data: 'Id_Ticket', // 18
                render: function (data, type, row) {
                    return '<a title="Bitácora" onclick="abrirBitacoraEstr(' + data + ', ' + row.master + ')"><i class="fas fa-book-open fa-2x text-secondary btnEP"></i></a>';
                },
                name: 'Id_Ticket',
                className: 'text-center',
                width: '1rem',
                title: 'Bitácora'
            },
            {
                data: 'horasInp',
                title: "Horas Compromiso",
            },
            {
                data:"FechaMaxRespuesta",
                title:"Fecha Max Respuesta",
                render:function(data, type, row) {
                if (row.respondioAtiempo==="SI"){
                    return '<div class="bg-success-estr" style="text-align:center">' + data + '</div>';
                }
                return '<div class="bg-danger-estr" style="text-align:center">' +  data + '</div>';
            }
        },

        ],
        pageLength: -1,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, "TODOS"],
        ],
        rowGroup: {
            dataSrc: ['Categoria', 'producto'],
            startRender: function (rows, group, level) {
                collapsed = !!collapsedGroups[all];
                var all;

                if (level === 0) {
                    top = group;
                    parent = top + group;
                    all = parent;
                    if (!!collapsedGroups[top]) {
                        return;
                    }
                } else {
                    if (!!collapsedGroups[parent]) {
                        return;
                    }
                    all = top + parent + group;
                }

                var asignado = rows.data().filter(function (data, index) {
                    return data['Asignado'] === 'SIN ASIGNAR' ? true : false;
                }).pluck('Asignado').count();

                collapsed = !!collapsedGroups[all];

                if (collapsed) {
                    $('#btn-expandirEstr').attr('title', 'Expandir Todo');
                    btnExpandirEstri.src = serve + '/img/flecha-down-w.png';
                } else {
                    $('#btn-expandirEstr').attr('title', 'Colapsar Todo');
                    btnExpandirEstri.src = serve + '/img/flecha-up_w.png';
                }

                rows.nodes().each(function (r) {
                    r.style.display = collapsed ? 'none' : '';
                });

                let rowcount = 0;

                rowname = (group === 'No group' ? 'Sin Grupo' : group);
                rowcount = (group === 'No group' ? rows.count() - 1 : rows.count());
                pendientes = (asignado > 0 ? asignado + ' SIN ASIGNAR' : '');


                if (collapsed) {
                    clases = 'fas fa-chevron-circle-down fa-2x tex-gray-left_';
                } else {
                    clases = 'fas fa-chevron-circle-up fa-2x tex-gray-left_';
                }

                if (level == 0) {
                    groupExpande_0 = (rowcount === 0 ? '<td class="rowcolap" title="Sin registros" align="center"><i id="ord' + group + '"></i></td>' : '<td class="rowcolap" title="Colapsar" align="center"><i id="ord' + group + '" class="' + clases + ' text-gray"></i></td>')
                    elem = $('<tr id="tr' + group + '" />').append(groupExpande_0 + '<td colspan="15" class="rowcolap pl-3 text-bold bg-gray" style="border-radius: 10px 0 0 10px">' + group + '<span id="totalR' + group + '" class="text-white">' + ' (' + rowcount + ')' + '</span></td><td colspan="3" class="rowcolap text-bold bg-gray text-right pr-2" style="border-radius: 0 10px 10px 0"><span">' + pendientes + '</span></td>').attr('data-name', all).toggleClass('collapsed', collapsed);
                } else {
                    groupExpande_1 = (rowcount === 0 ? '<td class="rowcolap" title="Sin registros" align="center"><i id="ord' + group + '"></i></td>' : '<td class="rowcolap" title="Colapsar" align="center"><i id="ord' + group + '" class="' + clases + ' text-gray_ text-gray_left"></i></td>')
                    elem = $('<tr id="tr' + group + '" />').append(groupExpande_1 + '<td colspan="15" class="rowcolap text-bold bg-select-left bg-level1">' + group + '<span id="totalR' + group + '" class="text-white">' + ' (' + rowcount + ')' + '</span></td><td colspan="3" class="rowcolap text-bold bg-select-right text-right pr-2"><span">' + pendientes + '</span></td>').attr('data-name', all).toggleClass('collapsed', collapsed);
                }

                $(rows.nodes()).show(); // todos  expandidos
                // $(rows.nodes()).hide(); // todos  colapsados

                elem.on('click', function (e) {
                    e.preventDefault();

                    collapsedGroups[all] = !collapsedGroups[all];

                    var ord = document.getElementById('ord' + group);
                    var tr = document.getElementById('tr' + group).getElementsByTagName('td');
                    var tri = document.getElementById('tr' + group).getElementsByTagName('i');

                    if (level == 0) {

                        if ($(rows.nodes()).is(":hidden")) {
                            $(rows.nodes()).show();

                            ord.classList.remove('fa-chevron-circle-down');
                            ord.classList.add('fa-chevron-circle-up');
                            tr[0].title = 'Colapsar';
                        } else {
                            $(rows.nodes()).hide();

                            ord.classList.add('fa-chevron-circle-down');
                            ord.classList.remove('fa-chevron-circle-up');
                            tr[0].title = 'Expandir';
                        }
                        collapsedGroups[all] = !collapsedGroups[all];
                    } else {
                        if ($(rows.nodes()).is(":hidden")) {
                            $(rows.nodes()).show();

                            ord.classList.remove('fa-chevron-circle-down');
                            ord.classList.add('fa-chevron-circle-up');
                            tr[0].title = 'Colapsar';
                            tr[0].classList.add('text-gray-left_');
                            tri[0].classList.add('text-gray-left_');

                            for (var i = 1; i < tr.length; i++) {
                                tr[i].classList.add('bg-level1');
                            }
                        } else {
                            $(rows.nodes()).hide();

                            ord.classList.add('fa-chevron-circle-down');
                            ord.classList.remove('fa-chevron-circle-up');
                            tr[0].title = 'Expandir';
                            tr[0].classList.add('text-gray-left_');
                            tri[0].classList.add('text-gray-left_');

                            for (var i = 1; i < tr.length; i++) {
                                tr[i].classList.add('bg-level1');
                            }
                        }
                        collapsedGroups[all] = !collapsedGroups[all];

                    }
                });
                return elem;
            }

        },
        footerCallback: function () {
            var api = this.api();

            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            var asignado = api.column(9, { page: '_all' }).data().filter(function (data, index) {
                return data === 'SIN ASIGNAR' ? 1 : 0;
            }).count();

            var totalTickets = api.column(7, { page: '_all' }).data().filter(function (data, index) {
                return data !== '' ? 1 : 0;
            }).count();

            $(api.column(16).footer()).html('TOTAL TICKETS : ' + totalTickets).css('text-align', 'right').css('font-weight', 'bold').css('font-size', '12px');
            $(api.column(17).footer()).html(' / ').css('text-align', 'center').css('font-weight', 'bold').css('font-size', '12px');
            // $(api.column(8).footer()).html(totalTickets).css('text-align', 'center').css('font-weight', 'bold').css('font-size', '12px');
            $(api.column(18).footer()).html('SIN ASIGNAR : ' + asignado).css('text-align', 'left').css('font-weight', 'bold').css('font-size', '12px');
            // $(api.column(10).footer()).html(asignado).css('text-align', 'center').css('font-weight', 'bold').css('font-size', '12px');

        },
        initComplete: function (settings, json) {
            ajustarTablas();
            loaderOut();

            // Validamos que buttonsCustom no sea un objeto vacío
            if (buttonsCustom && Object.keys(buttonsCustom).length == 0) {
                $('.dt-buttons').hide();
            }

            $('#tblDetalleAsignados_length').hide();
            $('#tblDetalleAsignados_paginate').hide();
        },
        // drawCallback: function () {
        //     alert('Now on page');
        // }
    });
}

/**
 * Función para liberar el ticket
 * 
 * @param {*} id_ticket
 * @param {*} type 1 = liberar, 0 = rechazar
 * @return {void}
 */
function liberarTicket(id_ticket, type) {
    let adicional = {};

    if (type == 0) {
        adicional = {
                input: 'textarea',
                inputAttributes: {
                    rows: 4,
                    placeholder: 'Escribe el motivo de rechazo',
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('¡Debes de capturar el motivo!')
                        return false;
                    }
                    return value;
                },
                allowOutsideClick: () => !Swal.isLoading()
            }
        }

    let swalConfigs = {
        title: "Diseño Estructural",
        text: type == 1 ? "Liberar el Ticket Seleccionado?" : "Rechazar el Ticket Seleccionado?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "No",
    }

    if (type == 0) {
        swalConfigs.input = adicional.input;
        swalConfigs.inputAttributes = adicional.inputAttributes;
        swalConfigs.preConfirm = adicional.preConfirm;
    }

    Swal.fire(swalConfigs).then((result) => {
        if (result.isConfirmed) {
            formDataUpdTicket = new FormData();
            formDataUpdTicket.append("_token", CSRF_TOKEN);
            formDataUpdTicket.append("_method", "POST");
            formDataUpdTicket.append("id_ticket", id_ticket);
            formDataUpdTicket.append("tipo", type);

            if (type == 0) {
                formDataUpdTicket.append("motivo", result.value);
            } else {
                formDataUpdTicket.append("motivo", '');
            }

            $.ajax({
                url: serve + "liberarTicket",
                async: true,
                type: "POST",
                data: formDataUpdTicket,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        Msg.fire({
                            title: type == 1 ? "Ticket Liberado" : "Ticket Rechazado",
                        });

                        tblDetalleAsignados.draw(false);
                    }
                }
            });
        } else {
            // Resetear el radio button
            let radioButton =  document.getElementById(`inp${id_ticket}-${type}`);

            if (radioButton) {
                // remove the checked attribute
                radioButton.removeAttribute('checked');
                radioButton.checked = false;

            }
        }
    });

}

// $('#tblDetalleAsignados tbody').on('click', 'tr.dtrg-start', function (e) {
//     e.preventDefault();
//     var name = $(this).data('name');
//     collapsedGroups[name] = !collapsedGroups[name];
//     var tblDetalleAsignados2 = $('#tblDetalleAsignados').DataTable();
//     tblDetalleAsignados2.clear();
//     tblDetalleAsignados2.draw(false);
// });

function generarTicket(data) {
    // loaderModalON();
    // $.ajax({
    //     url: serve + "getBriefEstructural",
    //     type: "GET",
    //     data: {
    //         _method: "GET",
    //         idDocumento: data,
    //     },
    //     success: function (data) {

    //         extensionEstr = data.Extension;
    //         byteCharactersEstr = atob(data.Documento);
    //         descDocumentoEstr = data.descDocumento;

    //         if (extensionEstr.match(/^.*vnd.*$/) || extensionEstr.match(/^.*stream$/)) {

    //             window.location.href = serve + 'downDocsEstructural/' + idDocumentoEstr;

    //             loaderModalOut();

    //         } else {

    //             $("#modal-visor-documentos").modal("show");
    //             $('.tituloVisorDocumentos').text(descDocumentoEstr);

    //             var byteNumbersEstr = new Array(byteCharactersEstr.length);
    //             for (var i = 0; i < byteCharactersEstr.length; i++) {
    //                 byteNumbersEstr[i] = byteCharactersEstr.charCodeAt(i);
    //             }
    //             var byteArrayEstr = new Uint8Array(byteNumbersEstr);
    //             var blobEstr = new Blob([byteArrayEstr], { type: extensionEstr });

    //             blobEstr = window.URL.createObjectURL(blobEstr);

    //             var visorPDFEstr = document.getElementById('frameVisorDocumentos');

    //             visorPDFEstr.src = blobEstr;

    //             loaderModalOut();
    //         }
    //     },
    //     error: function () { },
    // });

    window.open(serve + 'api/pdf/generar_ticket/' + data, '_blank');
}

// Variable global para el estado
window.estrCollapsed = window.estrCollapsed ?? false;

function expandirTodo() {
    // Alterna el estado global
    window.estrCollapsed = !window.estrCollapsed;

    let nodes = tblDetalleAsignados.rowGroup().rows().nodes();

    if (window.estrCollapsed) {
        $('#btn-expandirEstr').attr('title', 'Expandir Todo');
        btnExpandirEstri.src = serve + '/img/flecha-down-w.png';
        nodes.each(function (r) {
            r.style.display = 'none';
        });
    } else {
        $('#btn-expandirEstr').attr('title', 'Colapsar Todo');
        btnExpandirEstri.src = serve + '/img/flecha-up_w.png';
        nodes.each(function (r) {
            r.style.display = '';
        });
    }
}

function liberar(id_ticket) {
    Swal.fire({
        title: "Diseño Estructural",
        text: "Liberar el Ticket Seleccionado?",
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
            $.ajax({
                url: serve + "liberar",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id_ticket: id_ticket,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    Msg.fire({
                        title: "Ticket Liberado",
                    });
                    tblDetalleAsignados.draw(false);
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    Msg.fire({
                        title: "Ocurrió un error, vuelve a intentarlo",
                    });
                },
            });
        }
    });
}

$("#mod_mailDocs").on("shown.bs.modal", function () {
    $("#cuentapara").trigger("focus");
});

function mailDocumentosEstr(idDocumento) { // loaderModalON();

    $("#cuentapara").val("");

    $.ajax({
        url: serve + "getDocumentoEstrMail",
        type: "GET",
        data: {
            _method: "GET",
            idDocumento: idDocumento
        },
        success: function (data) {
            loaderModalOut();

            extension = data.Extension;
            byteCharacters = atob(data.Documento);
            descDocumento = data.descDocumento;
            correo = data.Correo;
            asunto = data.nomOportunidad;
            archivo = data.Documento;
            Nombre = data.Nombre;
            password = data.PswMail;
            id_oport = data.idOportunidad;
            firma = data.firma;
            cuentapara = data.CorreoPara;

            // // The Base64 string of a simple PDF file
            // var b64 = data.Documento;

            // // Decode Base64 to binary and show some information about the PDF file (note that I skipped all checks)
            // // var bin = atob(byteCharacters);

            // console.log('File Size:', Math.round(byteCharacters.length / 1024), 'KB');
            // console.log('PDF Version:', byteCharacters.match(/^.PDF-([0-9.]+)/)[1]);
            // // console.log('Create Date:', byteCharacters.match(/<xmp:CreateDate>(.+?)<\/xmp:CreateDate>/)[1]);
            // // console.log('Modify Date:', byteCharacters.match(/<xmp:ModifyDate>(.+?)<\/xmp:ModifyDate>/)[1]);
            // // console.log('Creator Tool:', byteCharacters.match(/<xmp:CreatorTool>(.+?)<\/xmp:CreatorTool>/)[1]);

            // // Embed the PDF into the HTML page and show it to the user
            // var obj = document.createElement('object');
            // obj.style.width = '100%';
            // obj.style.height = '842pt';
            // obj.type = 'application/pdf';
            // obj.data = 'data:application/pdf;base64,' + b64;
            // document.body.appendChild(obj);

            // // Insert a link that allows the user to download the PDF file
            // var link = document.createElement('a');
            // link.innerHTML = 'Download PDF file';
            // link.download = 'file.pdf';
            // link.href = 'data:application/octet-stream;base64,' + b64;
            // document.body.appendChild(link);

            $("#mod_mailDocs").modal("show");
            $("#cuentaAgente").val(correo);
            $("#subjetDoc").val(asunto);
            $("#docAdjunto").val(descDocumento);
            $("#archivoAdjunto").val(archivo);
            $("#Nombre").val(Nombre);
            $("#password").val(password);
            $("#id_oport").val(id_oport);
            $("#firma").val(firma);
            $("#cuentapara").val(cuentapara);

            // if (extension.match(/^.*vnd.*$/) || extension.match(/^.*stream$/)) {

            // window.location.href = serve + 'api/downDocsOportunidades/' + idDocumento;

            //     loaderModalOut();

            // } else {

            //     $("#modal-visor-documentos").modal("show");
            //     $('.tituloVisorDocumentos').text(descDocumento);

            // var byteNumbers = new Array(byteCharacters.length);
            // for (var i = 0; i < byteCharacters.length; i++) {
            //     byteNumbers[i] = byteCharacters.charCodeAt(i);
            // }
            // var byteArray = new Uint8Array(byteNumbers);
            // var blob = new Blob([byteArray], {type: extension});

            // blob = window.URL.createObjectURL(blob);

            // var visorPDF = document.getElementById('frameVisorDocumentos');

            // visorPDF.src = blob;

            //     loaderModalOut();
            // }
        },
        error: function () { }
    });

    // $.ajax({
    //     url: serve + "getDocumentoOport",
    //     type: "GET",
    //     data: {
    //         _method: "GET",
    //         idDocumento: idDocumento,
    //     },
    //     success: function(data) {

    //         extension = data.Extension;
    //         byteCharacters = atob(data.Documento);
    //         descDocumento = data.descDocumento;

    //         if (extension.match(/^.*vnd.*$/) || extension.match(/^.*stream$/)) {

    //             window.location.href = serve + 'api/downDocsOportunidades/' + idDocumento;

    //             loaderModalOut();

    //         } else {

    //             $("#modal-visor-documentos").modal("show");
    //             $('.tituloVisorDocumentos').text(descDocumento);

    //             var byteNumbers = new Array(byteCharacters.length);
    //             for (var i = 0; i < byteCharacters.length; i++) {
    //                 byteNumbers[i] = byteCharacters.charCodeAt(i);
    //             }
    //             var byteArray = new Uint8Array(byteNumbers);
    //             var blob = new Blob([byteArray], { type: extension });

    //             blob = window.URL.createObjectURL(blob);

    //             var visorPDF = document.getElementById('frameVisorDocumentos');

    //             visorPDF.src = blob;

    //             loaderModalOut();
    //         }
    //     },
    //     error: function() {},
    // });
}

function enviarDocumentoEstr() {
    archivoAdjunto = $("#archivoAdjunto").val();
    cuentaAgente = $("#cuentaAgente").val();
    cuentapara = $("#cuentapara").val();
    subjetDoc = $("#subjetDoc").val();
    docAdjunto = $("#docAdjunto").val();
    cuerpoDoc = $("#cuerpoDoc").val();
    Nombre = $("#Nombre").val();
    password = $("#password").val();
    id_oport = $("#id_oport").val();
    firma = $("#firma").val();

    if (cuentapara == null || cuentapara == "") {
        Swal.fire({ text: "¡Capturar el destinantario!", allowEnterKey: true, target: document.getElementById("mod_mailDocs") }).then(() => {
            $("#cuentapara").trigger("focus");
        });
    } else {
        loaderON();
        $.ajax({
            url: serve + "enviarDocumentoMail",
            type: "POST",
            data: {
                _method: "POST",
                _token: CSRF_TOKEN,
                archivoAdjunto: archivoAdjunto,
                cuentaAgente: cuentaAgente,
                cuentapara: cuentapara,
                subjetDoc: subjetDoc,
                docAdjunto: docAdjunto,
                cuerpoDoc: cuerpoDoc,
                Nombre: Nombre,
                password: password,
                id_oport: id_oport,
                firma: firma,
                ruta_firma: serve
            },
            success: function (data) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000
                });
                Msg.fire({ title: "Documento Enviado." });

                loaderOut();
            },
            error: function () {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000
                });
                Msg.fire({ title: "El documento no se pudo enviar." });
                loaderOut();
            }
        });

        archivoAdjunto = "";
        cuentaAgente = "";
        cuentapara = "";
        subjetDoc = "";
        docAdjunto = "";
        cuerpoDoc = "";
        Nombre = "";
        password = "";
        id_oport = "";
        firma = "";

        $("#mod_mailDocs").modal("hide");
    }
}

let myVarEstr = setInterval(autoReloadPage, 600000);

function autoReloadPage() {
    tblDetalleAsignados.ajax.reload();
}

function abrirBitacoraEstr(id) {
    $.ajax({
        url: serve + 'obtenerTicket',
        type: 'GET',
        dataType: 'JSON',
        data: {
            id: id,
        },
        success: function (data) {
            // console.log(data);
            $('#mod_BitacoraEstr').modal('show');
            $('#mod_BitacoraEstr').trigger('reset');
            $('#movimientoBitacoraEstr').val('');
            $('#titleBitacoraEstr').text('Ticket N° ' + data.master);
            $('#nombreTicketEstr').text(data.nombre);
            $('#descripcionTicketEstr').text(data.descripcion);
            $('#folioBitacoraEstr').val(data.folio);

            
            let idUser = $('#idUsuario').val();
            // Validamos si el rol es 1024 blockeamos todo el formulario
            if (($('#idRolEstr').val() == 1026 && idUser != data.id_agente)) {
                // Ocultamos las acciones del footer de la bitácora
                $('#footerBitacora').hide();
            } else {
                // Mostramos las acciones del footer de la bitácora
                $('#footerBitacora').show();
            }
        },
    });

    tblBitacoraEstr = $("#tblBitacoraEstr").DataTable({
        destroy: true,
        processing: true,
        scrollY: '40vh',
        scrollX: true,
        serverSide: true,
        colReorder: false,
        bLengthChange: false,
        order: [
            [2, "asc"]
        ],
        columnDefs: [
            {
                targets: [0, 1],
                orderable: false,
            },
            {
                targets: [2],
                render: $.fn.dataTable.render.moment(FROM_PATTERN_ESTR, TO_PATTERN_ESTR),
            },
        ],
        ajax: {
            url: serve + "apiBitacoraEstructural",
            type: "GET",
            dataType: "json",
            data: {
                id: id,
            },
        },
        columns: [
            {
                data: "usuario",
                name: "usuario",
                className: 'text-bold text-md pl-2'
            }, {
                data: "movimiento",
                name: "movimiento",
                className: 'text-md'
            },
            {
                data: "fecha_movimiento",
                name: "fecha_movimiento",
                className: 'text-md text-right pr-2'
            },
        ],
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        select: false,
        bInfo: true,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, 'TODOS'],
        ],

        "initComplete": function (settings, json) { },
    }
    );

}

$("#mod_BitacoraEstr").on("shown.bs.modal", function () {
    $("#movimientoBitacoraEstr").trigger("focus");
});

$("#mod_BitacoraEstr").draggable({
    handle: "#headerBitacora",
});

selectRow("tblBitacoraEstr");

$('#movimientoBitacoraEstr').keyup(function(){
    if ($(this).val() == '') {
        btnSaveMovEstr.disabled = true;
    } else {
        btnSaveMovEstr.disabled = false;
    }
});

function enviarMovimientoEstr(){
    movimiento = $('#movimientoBitacoraEstr').val();
    id = $('#folioBitacoraEstr').val();

    var dateToday = new Date();
    fechaBitacoraEstr = dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss');


    $.ajax({
        url: serve + 'enviarMovimientoEstr',
        type: 'POST',
        data: {
            movimiento: movimiento,
            id: id,
            fechaBitacoraEstr: fechaBitacoraEstr,
        },
        success: function(data){

            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Consulta Enviada",
            });
            $('#movimientoBitacoraEstr').val('')
            btnSaveMovEstr.disabled = true;
            tblBitacoraEstr.draw();
        }
    })
}

/**
 * Función para guardar las horas de los tickets
 * @param {int} id_ticket - ID del ticket
 * @param {int} horas - Horas a guardar
 * @param {int} horasYaRegistradas - Horas ya registradas
 * @return {void}
 */
function guardarHoras(horas, id_ticket, horasYaRegistradas) {

    if (horasYaRegistradas == horas) {
        return;
    }

    // Creamos el formulario que obtendra la informacion de la incidencia
    formDataStoreHours = new FormData();
    formDataStoreHours.append("_token", CSRF_TOKEN);
    formDataStoreHours.append("_method", "POST");
    formDataStoreHours.append("id_ticket", id_ticket);
    formDataStoreHours.append("horas", horas);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "storeHoursTicket",
        async: true,
        type: "POST",
        data: formDataStoreHours,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: "Horas guardadas correctamente",
                });
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

// Convertimos el folio a un select
$('#folioOportunidad').select2({
    placeholder: "Folio Oportunidad",
    allowClear: true
});

// Cuando se seleccione un folio, coloca el nombre de la oportunidad en el campo nombOportunidad
$('#folioOportunidad').on('select2:select', function (e) {
    // Obtén el texto seleccionado (nombre de la oportunidad)
    var nombreOportunidad = e.params.data.text;
    nombreOportunidad = nombreOportunidad.split(' - ')[1] || (nombreOportunidad != 'Folio Oportunidad' ? nombreOportunidad : '');

    nombreOportunidad = nombreOportunidad != null ? nombreOportunidad.trim() : '';

    // Obtén el id del cliente (asumiendo que el id viene en el value del option, separado por ' - ')
    var clienteOportunidad = e.params.data.id.split(' - ')[1] || e.params.data.id;

    var clienteNombre = e.params.data.id.split(' - ')[2] || e.params.data.id;

    var idOportunidad = e.params.data.id.split(' - ')[0] || e.params.data.id;

    // Coloca el nombre en el campo correspondiente
    $('#nombOportunidad').val(nombreOportunidad.trim());

    $('#idClienteEstr').val(clienteOportunidad.trim());
    $('#nomClienteEstr').val(clienteNombre.trim());
    $('#idOportunidadEstrNew').val(idOportunidad.trim());

    validarRequeridosEstr();
});



'use strict';

; (function (document, window, index) {
    var inputs = document.querySelectorAll('.inputfile');
    Array.prototype.forEach.call(inputs, function (input) {
        var label = input.nextElementSibling,
            labelVal = label.innerHTML;

        input.addEventListener('change', function (e) {
            var fileName = '';
            if (this.files && this.files.length > 1)
                fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}', this.files.length);
            else
                fileName = e.target.value.split('\\').pop();

            if (fileName)
                label.querySelector('span').innerHTML = fileName;
            else
                label.innerHTML = labelVal;
        });
    });
}(document, window, 0));