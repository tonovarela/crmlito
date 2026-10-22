serve = base_path;

$(document).ready(function() {
    idRol = $('#idRol').val();
    mostrarGeneralEnvio();
});

function mostrarGeneralEnvio() {

    $("#general-envio").addClass("active");
    $("#general-envio").addClass("show");

    $("#general-envio-tab").addClass("active");
    $("#documentos-envio-tab").removeClass("active");

    $("#documentos-envio").removeClass("active");
    $("#documentos-envio").removeClass("show");

    document.getElementById("general-envio").style.display = "block";
    document.getElementById("documentos-envio").style.display = "none";

    if (idRol == 1014) {
        document.getElementById("sbtnSaveEnv").style.display = "none";
        document.getElementById("sbtnCancelarkEnv").style.display = "none";

        $("#sbtnBackEnv").addClass("col-md-12");
    } else {
        document.getElementById("sbtnSaveEnv").style.display = "block";
        document.getElementById("sbtnCancelarkEnv").style.display = "block";
        $("#sbtnBackEnv").removeClass("col-md-6");
        $("#sbtnSaveEnv").removeClass("col-md-6");

        $("#sbtnBackEnv").removeClass("col-md-12");
        $("#sbtnBackEnv").addClass("col-md-4");
        $("#sOportBtnCancelar").addClass("col-4");
    }



    ajustarTablas();
}

function mostrarDocumentosEnvio() {
    tblDocsEnv();


    $("#general-envio").removeClass("active");
    $("#general-envio").removeClass("show");

    $("#documentos-envio").addClass("active");
    $("#documentos-envio").addClass("show");

    document.getElementById("general-envio").style.display = "none";
    document.getElementById("documentos-envio").style.display = "block";
    document.getElementById("sbtnSaveEnv").style.display = "none";
    document.getElementById("sbtnCancelarkEnv").style.display = "none";
    $("#sbtnBackEnv").addClass("col-md-12");

    ajustarTablas();
}

var nomoportunidad = document.getElementById("nomOportunidad");
var idcliente = document.getElementById("idCliente");
var btnSaveEnv = document.getElementById("btnSaveEnv");
var retencion = document.getElementById("Retencion");
var notaretencion = document.getElementById("notaRetencion");

var preregi = document.getElementById("prereg_i");
var preregh5 = document.getElementById("prereg_h5");
var preregspan = document.getElementById("prereg_span");

var solcoti = document.getElementById("solcot_i");
var solcoth5 = document.getElementById("solcot_h5");
var solcotspan = document.getElementById("solcot_span");

var resulfini = document.getElementById("resulfin_i");
var resulfinh5 = document.getElementById("resulfin_h5");
var resulfinspan = document.getElementById("resulfin_span");

var cottermi = document.getElementById("cotterm_i");
var cottermh5 = document.getElementById("cotterm_h5");
var cottermspan = document.getElementById("cotterm_span");

var idtipo = document.getElementById("idtipo");
var idOportRel = document.getElementById("idOportunidadRelacion");

var totDestinos = document.getElementById("totDestinos");
var tamPieza = document.getElementById("tamPieza");
var pesoPieza = document.getElementById("pesoPieza");
var idUnidadPeso = document.getElementById("idUnidadPeso");

var idEquipo = document.getElementById("idEquipo");
var idAgente = document.getElementById("idAgente");

var infoEnvio = document.getElementById("infoEnvio");
var opcionesNo = document.getElementById("opcionesNo");
var infoFactura = document.getElementById("infoFactura");

var isCancelled = document.getElementById('isCancelled');
var nota_cancelacion = document.getElementById('nota_cancelacion');
var nota_cancelacion_value = document.getElementById('nota_cancelacion_value');

var muestra = document.getElementById("muestra");

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$(document).ready(function() {

    $('[data-bs-toggle="tooltip"]').tooltip();

    var idClienteSelec2 = $('#idCliente').select2({
        language: 'es',
    });

    var notaretencionVal = $("#notaRetencion").val();
    var retencionCheck = $("#Retencion").checked;

    infoEnvio.style.display = "none";
    opcionesNo.style.display = "none";
    infoFactura.style.display = "none";

    if (
        (notaretencionVal == "" && retencionCheck == "undefinided") ||
        (notaretencionVal == null && retencionCheck == "undefinided")
    ) {
        $("#notaRetencion").prop("readonly", true);
        notaretencion.style.borderColor = "silver";
    } else {
        $("#notaRetencion").prop("readonly", true);
        notaretencion.style.borderColor = "silver";
    }

    retencion.addEventListener("change", function() {
        activarNotaRetencion(retencion.checked);
    });

    function activarNotaRetencion(check) {

        var estatus = $("#estatus").val();

        if (check == true) {
            $("#notaRetencion").prop("readonly", false);
            $("#notaRetencion").prop("disabled", false);
            $("#notaRetencion").prop("required", true);

            if (notaretencion.value == "") {
                notaretencion.style.borderColor = "red";
            } else {
                notaretencion.style.borderColor = "green";
            }

            if (estatus == "1") {
                $("#idStatusSolCot").prop("disabled", true);
            } else if (estatus == "2") {
                $("#idStatusSolCot").prop("disabled", false);
                $("#idStatusSolCot").prop("readonly", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", true);
                $("#idStatusCotCanc").prop("disabled", true);
            } else if (estatus == "6") {
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotTerm").prop("readonly", true);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", true);
                $("#idStatusCotCanc").prop("disabled", true);
            } else if (estatus == "7") {
                $("#idStatusCotAprob").prop("disabled", false);
                $("#idStatusCotAprob").prop("readonly", true);
                $("#idCotizacionAutorizada").prop("disabled", false);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotCanc").prop("disabled", true);
            } else if (estatus == "8") {
                $("#idStatusCotCanc").prop("disabled", false);
                $("#idStatusCotCanc").prop("readonly", true);
                $("#idCotizacionAutorizada").prop("readonly", true);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", true);
            }

        } else {
            btnSaveEnv.disabled = false;
            btnSaveEnv.style.pointerEvents = null;
            notaretencion.value = "";
            $("#notaRetencion").prop("readonly", true);
            $("#notaRetencion").prop("required", false);
            notaretencion.style.borderColor = "silver";

            if (estatus == "1") {
                $("#idStatusSolCot").prop("disabled", false);
            } else if (estatus == "2") {
                $("#idStatusSolCot").prop("disabled", false);
                $("#idStatusSolCot").prop("readonly", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", true);
                $("#idStatusCotCanc").prop("disabled", true);
                btnSaveEnv.disabled = false;
                btnSaveEnv.style.pointerEvents = null;
            } else if (estatus == "6") {
                $("#idStatusCotTerm").prop("disabled", false);
                $("#idStatusCotTerm").prop("readonly", true);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", false);
                $("#idStatusCotCanc").prop("disabled", false);
                btnSaveEnv.disabled = true;
                btnSaveEnv.style.pointerEvents = 'none';
            } else if (estatus == "7") {
                $("#idStatusCotAprob").prop("disabled", false);
                $("#idStatusCotAprob").prop("readonly", true);
                $("#idCotizacionAutorizada").prop("disabled", false);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotCanc").prop("disabled", true);
                btnSaveEnv.disabled = true;
                btnSaveEnv.style.pointerEvents = 'none';
            } else if (estatus == "8") {
                $("#idStatusCotCanc").prop("disabled", false);
                $("#idStatusCotCanc").prop("readonly", true);
                $("#idCotizacionAutorizada").prop("readonly", true);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", true);
                btnSaveEnv.disabled = true;
                btnSaveEnv.style.pointerEvents = 'none';
            }

        }
    }

    notaretencion.addEventListener("change", function() {
        validarRequeridosOporNoActivos();
    });

    function validarRequeridosOporNoActivos() {
        if (notaretencion.value == "") {
            notaretencion.style.borderColor = "red";
        } else {
            notaretencion.style.borderColor = "green";
            btnSaveEnv.disabled = false;
            btnSaveEnv.style.pointerEvents = null;
        }
    }

    $("#modal-create-envio").on("shown.bs.modal", function() {
        $("#nomOportunidad").trigger("focus");
    });

    validarRequeridosOpor();

    nomoportunidad.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    idClienteSelec2.on("change", function() {
        validarRequeridosOpor();
        idClienteChange(idcliente.value);
    });

    idtipo.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    totDestinos.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    tamPieza.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    pesoPieza.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    idUnidadPeso.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    idEquipo.addEventListener("change", function() {
        idAgente.value = "Requerido";
        validarRequeridosOpor();
    });

    idAgente.addEventListener("change", function() {
        validarRequeridosOpor();
    });

    omtc.addEventListener("change", function() {
        validarRequeridosOMTC();
    });

    numOrdenMTC.addEventListener("change", function() {
        validarRequeridosOMTC();
    });

    muestra.addEventListener("change", function() {
        validarMuestra(muestra.checked);
    });

    opcConSeguro.addEventListener("change", function() {
        validarConSeguro(opcConSeguro.checked);
    });

    idOportRel.addEventListener("change", function() {
        console.log(idOportRel.value);
        if (idOportRel.required == true) {
            btnSaveEnv.style.pointerEvents = null;
            validarValorOport(idOportRel.value);
        }
    });

    tblEnvios = $("#tblEnvios").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "Agente",
        select: false,
        deferRender: true,
        scrollX: true,
        scrollCollapse: true,
        order: [0, 'desc'],
        // fixedColumns: {
        //     leftColumns: 1,
        // },
        ajax: {
            url: serve + "api/envios",
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpB><"clear">',
        buttons: [{
                extend: "colvis",
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
            {
                extend: "excelHtml5",
                title: "Envíos | CANDY CRM",
                text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
                exportOptions: {
                    orthogonal: "export",
                    columns: "thead th:not(.noExport)",
                    modifer: {
                        serverSide: false,
                        page: "all",
                    },
                },
                filename: "Envíos",
                autoFilter: true,
                sheetName: "Lista Envíos",
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
        columnDefs: [{
            targets: [1, 9],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
        }, ],
        columns: [{
                data: "Folio",
                width: 60,
                title: "Folio",
                searchable: true,
                className: "dt-body-center text-center",
            },
            {
                data: "fecCreacion",
                title: "Creación",
                width: 10,
                className: "dt-body-center text-center",
            },
            {
                data: "nomOportunidad",
                name: "nomOportunidad",
                title: "Nombre",
                render: function(data, type, row) {
                    if ($.trim(row.inputEditar) === "Editar") {
                        return (
                            '<a href="#" onclick="editarEnvio(' +
                            row.idOportunidad +
                            '), loaderON()" title="Editar Envío" class="text-uppercase">' +
                            data +
                            "</a>"
                        );
                    } else {
                        return data;
                    }
                },
            },
            {
                data: "idCotizacionAutorizada",
                title: "Num Cotización",
                width: 35,
            },
            {
                data: "numOrdenMTC",
                title: "OP Metrics",
                width: 35,
            },
            {
                data: "numFactura",
                title: "N° Factura",
                width: 35,
            },
            {
                data: "importeFactura",
                title: "Imp. Factura",
                width: 35,
            },
            {
                data: "razonsocial",
                render: function(data, type, row) {
                    if ($.trim(row.inputEditarClientes) === "EditarClientes") {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return (
                                '<a href="clientes/' +
                                row.ClienteINT +
                                '/edit" title="Editar Cliente" onclick="loaderON()">' +
                                data +
                                "</a>"
                            );
                        } else {
                            return data;
                        }
                    } else {
                        if (
                            $.trim(row.inputLogin) == $.trim(row.Login) ||
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                return (
                                    '<a href="clientes/' +
                                    row.ClienteINT +
                                    '/edit" title="Editar Cliente" class="text-uppercase">' +
                                    data +
                                    "</a>"
                                );
                            } else {
                                return data;
                            }
                        } else {
                            return data;
                        }
                    }
                },
                title: "Cliente",
                width: 30,
            },
            {
                data: "Agente",
                title: "Asignado a",
                width: 30,
            },
            {
                data: "descEstatus",
                render: function(data, type, row) {
                    if (row.Cancelada == "1") {
                        return '<strong class="text-red">' + data + '</strong>';
                    } else if (row.idEstatus == 7) {
                        return '<strong class="text-green">' + data + "</strong>";
                    } else if (row.idEstatus == 8) {
                        return '<strong class="text-red">' + data + "</strong>";
                    } else {
                        return data;
                    }
                },
                title: "Estatus",
                width: 30,
                className: "dt-body-center text-center",
            },
            {
                data: 'valorEstatus',
                render: function(data, type, row) {
                    if (row.Cancelada == 1) {
                        return '<div class="progress rounded" style="width: 200px" title="' + row.descEstatus + '"><div class="progress-bar text-bold" role="progressbar" style="width: 100%; background-color: red; color: #000" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>'
                    } else {
                        return '<div class="progress rounded" style="width: 200px" title="' + row.descEstatus + '"><div class="progress-bar text-bold" role="progressbar" style="width: ' + data + '%; background-color: ' + row.colorEstatus + '; color: #000" aria-valuenow="' + data + '" aria-valuemin="0" aria-valuemax="100">' + data + '%</div></div>'
                    }
                },
                title: 'Progreso',
            },
            {
                data: "fecResultadoF",
                render: function(data, type, row) {
                    if (row.Cancelada == "1") {
                        return moment(row.fecCancelada).format(TO_PATTERN);
                    } else {
                        if (data == null || data == "") {
                            return data;
                        } else {
                            return moment(data).format(TO_PATTERN);
                        }
                    }
                },
                title: "Terminada",
                width: 30,
                className: "dt-body-center text-center",
            },
        ],
        pageLength: 50,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, 'TODOS'],
        ],
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        initComplete: function(settings, json) {
            loaderOut();
        },
    });

    selectRow("tblEnvios");

    $("#tblEnvios")
        .DataTable()
        .on("draw", function() {
            ajustarTablas();
        });
});

let myVar = setInterval(autoReloadPage, 600000);

function autoReloadPage() {
    tblEnvios.ajax.reload();
}

function validarRequeridosOpor() {

    if (
        nomoportunidad.value == "" ||
        idcliente.value == "Requerido" ||
        idtipo.value == "Requerido" ||
        totDestinos.value == "" ||
        tamPieza.value == "" ||
        pesoPieza.value == "" ||
        idUnidadPeso.value == "Requerido" ||
        idEquipo.value == "Requerido" ||
        idAgente.value == "Requerido"
    ) {
        btnSaveEnv.disabled = true;
        btnSaveEnv.style.pointerEvents = 'none';
    } else {
        btnSaveEnv.disabled = false;
        btnSaveEnv.style.pointerEvents = null;
    }

    if (nomoportunidad.value == "") {
        nomoportunidad.style.borderColor = "red";
    } else {
        nomoportunidad.style.borderColor = "green";
    }

    if ($('#idCliente').select2("data")[0].element.value != "Requerido") {
        $('#idCliente').select2().addClass('select2-green');
    } else {
        $('#idCliente').select2().addClass('select2-red');
    }

    if (idtipo.value == "Requerido" || idtipo.value == "requerido") {
        idtipo.style.borderColor = "red";
    } else {
        idtipo.style.borderColor = "green";
    }

    if (totDestinos.value == "") {
        totDestinos.style.borderColor = "red";
    } else {
        totDestinos.style.borderColor = "green";
    }

    if (tamPieza.value == "") {
        tamPieza.style.borderColor = "red";
    } else {
        tamPieza.style.borderColor = "green";
    }

    if (pesoPieza.value == "") {
        pesoPieza.style.borderColor = "red";
    } else {
        pesoPieza.style.borderColor = "green";
    }

    if (idUnidadPeso.value == "Requerido" || idUnidadPeso.value == "requerido") {
        idUnidadPeso.style.borderColor = "red";
    } else {
        idUnidadPeso.style.borderColor = "green";
    }

    if (idEquipo.value == "Requerido" || idEquipo.value == "requerido") {
        idEquipo.style.borderColor = "red";
        idAgente.value = "Requerido";
    } else {
        idEquipo.style.borderColor = "green";
    }

    if (idAgente.value == "Requerido" || idAgente.value == "requerido") {
        idAgente.style.borderColor = "red";
    } else {
        idAgente.style.borderColor = "green";
    }


}

function idClienteChange(id) {
    var oportunidades = $("#idOportunidadRelacion");
    var cliente = id;

    if ($(cliente).val() != "Requerido") {
        $.ajax({
            data: {
                idCliente: cliente,
            },
            url: serve + "getOportunidades",
            type: "GET",
            dataType: "json",
            success: function(r) {
                oportunidades.find("option").remove();

                oportunidades.append("<option selected disabled>Oportunidad</option>");

                $(r).each(function(i, v) {
                    oportunidades.append(
                        '<option value="' +
                        v.idOportunidad +
                        '">' +
                        v.nomOportunidad +
                        "</option>"
                    );
                });
            },
            error: function(r) {
                Swal.fire("Ocurrio un error en el servidor ..");
            },
        });
    } else {
        oportunidades.find("option").remove();
    }
}

function OportunidadesAsignadas(id) {
    var oportunidades = $("#idOportunidadRelacion");
    var oportunidad = id;

    oportunidades.required = false;

    if ($(oportunidades).val() != "Oportunidad") {
        $.ajax({
            data: {
                idOportunidad: oportunidad,
            },
            url: serve + "getOportunidadesAsignadas",
            type: "GET",
            dataType: "json",
            success: function(r) {
                oportunidades.find("option").remove();

                oportunidades.append("<option selected disabled>Oportunidad</option>");

                $(r).each(function(i, v) {
                    // indice, valor
                    if (oportunidad == v.idOportunidad) {
                        oportunidades.append(
                            '<option value="' +
                            v.idOportunidad +
                            '" selected>' +
                            v.nomOportunidad +
                            "</option>"
                        );
                    }
                });
            },
            error: function(r) {
                Swal.fire("Ocurrio un error en el servidor ..");
            },
        });
    } else {
        oportunidades.find("option").remove();
    }
}

function validarRequeridosOMTC() {
    if (omtc.value == "No. Cotizacion Autorizada" || numOrdenMTC.value == "") {
        btnSaveEnv.disabled = true;
        btnSaveEnv.style.pointerEvents = 'none';
    } else {
        btnSaveEnv.disabled = false;
        btnSaveEnv.style.pointerEvents = null;
    }

    if (omtc.value == "No. Cotizacion Autorizada") {
        omtc.style.borderColor = "red";
    } else {
        omtc.style.borderColor = "green";
    }

    if (numOrdenMTC.value == "") {
        numOrdenMTC.style.borderColor = "red";
    } else {
        numOrdenMTC.style.borderColor = "green";
    }
}

function validarMuestra(check) {
    var estatus_muestra = $('input:radio[name="idStatus"]').val();
    if (check == true) {
        if (estatus_muestra == 1) {
            btnSaveEnv.disabled = false;
            btnSaveEnv.style.pointerEvents = null;
        } else if (estatus_muestra == 2) {
            btnSaveEnv.disabled = true;
            btnSaveEnv.style.pointerEvents = 'none';
        }
    } else {
        if (estatus_muestra == 1) {
            btnSaveEnv.disabled = true;
            btnSaveEnv.style.pointerEvents = 'none';
        } else if (estatus_muestra == 2) {
            btnSaveEnv.disabled = false;
            btnSaveEnv.style.pointerEvents = null;
        }
    }
}

function validarConSeguro(check) {
    var estatus_seguro = $('input:radio[name="idStatus"]').val();
    if (check == true) {

        Swal.fire({
            text: "¡No olvides seleccionar la Oportunidad!",
            allowEnterKey: true,
            target: document.getElementById('modal-create-envio'),
        }).then(() => {
            $('#idOportunidadRelacion').trigger('focus');
            validarValorOport(idOportunidadRelacion.value);
        });

        idOportunidadRelacion.required = true;

        if (idOportunidadRelacion.value == "Oportunidad") {
            btnSaveEnv.style.pointerEvents = 'none';
        } else {
            btnSaveEnv.style.pointerEvents = null;
        }
        // if (estatus_seguro == 1) {
        //     btnSaveEnv.disabled = false;
        // btnSaveEnv.style.pointerEvents = null;
        // } else if (estatus_seguro == 2) {
        //     btnSaveEnv.disabled = true;
        // }
    } else {
        idOportunidadRelacion.required = false;
        // if (estatus_seguro == 1) {
        //     btnSaveEnv.disabled = true;
        // btnSaveEnv.style.pointerEvents = 'none';
        // } else if (estatus_seguro == 2) {
        //     btnSaveEnv.disabled = false;
        btnSaveEnv.style.pointerEvents = null;
        // }
    }
}

function validarValorOport(idOport) {
    $.ajax({
        data: {
            idOportunidad: idOport,
        },
        url: serve + "getValorOportunidad/",
        type: "GET",
        dataType: "json",
        success: function(result) {
            console.log(result);
            if (result == 0) {
                Swal.fire({
                    text: "¡Esta oportunidad tiene valor cero, tienes que relacionarla con un elemento presupuestado!",
                    allowEnterKey: true,
                    target: document.getElementById('modal-create-envio'),
                }).then(() => {
                    $('#idOportunidadRelacion').val('Oportunidad');
                    $('#idOportunidadRelacion').trigger('focus');
                });
            }
        },
        error: function(result) {
            Swal.fire('No se encntraron Oportunidades');
        },
    });
}

$(function() {
    $("#idEquipo").change(function() {
        var agente = $("#idAgente");
        var equipos = $(this);

        if ($(this).val() != "") {
            getAgentes(equipos.val(), agente);
        } else {
            agente.find("option").remove();
            agente.prop("disabled", true);
        }

        if ($(this).val() == 25 || $(this).val() == 27) {
            muestra.checked = true;
        } else {
            muestra.checked = false;
        }

        validarRequeridosOpor();

    });
});

function getAgentes(idEquipo, agente) {
    idAgente.disabled = true;
    idAgente.style.borderColor = "red";

    $.ajax({
        data: {
            idEquipo: idEquipo,
        },
        url: serve + "getEquipos/",
        type: "GET",
        dataType: "json",
        success: function(r) {
            agente.find("option").remove();

            if (idEquipo == 0) {
                agente.append("<option selected disabled>Requerido</option>");
            } else {
                agente.append("<option selected disabled>Requerido</option>");
                $(r).each(function(i, v) {
                    // indice, valor
                    agente.append(
                        '<option value="' + v.Id_Usuario + '">' + v.Nombre + "</option>"
                    );
                });
            }

            idAgente.disabled = false;
        },
        error: function(r) {
            Swal.fire("¡Seleccionar un Equipo!");
            // validarRequeridosOpor();
        },
    });
}
var omtc = document.getElementById("idCotizacionAutorizada");
var numOrdenMTC = document.getElementById("numOrdenMTC");

$('input:radio[name="idStatus"]').change(function() {

    estatus_id = $('#estatus').val();

    if (this.checked && this.value == "1") {
        retencion.disabled = false;
        muestra.disabled = false;
        muestra.disabled = true;
        muestra.checked = false;
    } else if (this.checked && this.value == "2") {
        retencion.checked = false;
        retencion.disabled = true;
        notaretencion.disabled = true;
        notaretencion.value = "";
        btnSaveEnv.disabled = false;
        btnSaveEnv.style.pointerEvents = null;
        muestra.disabled = true;
        muestra.checked = false;
    } else if (this.checked && this.value == "6") {
        omtc.disabled = true;
        numOrdenMTC.disabled = true;
        muestra.disabled = true;
        omtc.value = "No. Cotizacion Autorizada";
        numOrdenMTC.value = "";
        omtc.style.borderColor = "silver";
        numOrdenMTC.style.borderColor = "silver";
        btnSaveEnv.disabled = false;
        btnSaveEnv.style.pointerEvents = null;
        infoEnvio.style.display = "none";
        opcionesNo.style.display = "none";
        infoFactura.style.display = "none";
        if (estatus_id !== "6") {
            retencion.disabled = true;
        } else {
            retencion.disabled = false;
        }
    } else if (this.checked && this.value == "7") {
        omtc.disabled = false;
        muestra.disabled = true;
        numOrdenMTC.disabled = false;
        infoEnvio.style.display = "block";
        opcionesNo.style.display = "none";
        infoFactura.style.display = "block";
        $("#servAdecuadoSi").disabled = false;
        $("#servAdecuadoNo").disabled = false;
        btnSaveEnv.disabled = true;
        btnSaveEnv.style.pointerEvents = 'none';
        omtc.style.borderColor = "red";
        numOrdenMTC.style.borderColor = "red";
        if (estatus_id !== "7") {
            retencion.disabled = true;
        } else {
            retencion.disabled = false;
        }
    } else if (this.checked && this.value == "8") {
        omtc.disabled = true;
        muestra.disabled = true;
        numOrdenMTC.disabled = true;
        omtc.value = "No. Cotizacion Autorizada";
        numOrdenMTC.value = "";
        omtc.style.borderColor = "silver";
        numOrdenMTC.style.borderColor = "silver";
        btnSaveEnv.disabled = true;
        btnSaveEnv.style.pointerEvents = 'none';
        infoEnvio.style.display = "none";
        opcionesNo.style.display = "none";
        infoFactura.style.display = "none";
        if (estatus_id !== "8") {
            retencion.disabled = true;
        } else {
            retencion.disabled = false;
        }
    }
});

$('input:radio[name="servAdecuado"]').change(function() {
    if (this.checked && this.value == "NO") {
        opcionesNo.style.display = "block";
    } else {
        opcionesNo.style.display = "none";
    }
    btnSaveEnv.disabled = false;
    btnSaveEnv.style.pointerEvents = null;

    // var nodes_env = document
    //     .getElementById("info_general")
    //     .getElementsByTagName("*");
    // for (var i = 0; i < nodes_env.length; i++) {
    //     nodes_env[i].readOnly = true;
    // }

});

$('#numFactura').change(function() {
    btnSaveEnv.disabled = false;
    btnSaveEnv.style.pointerEvents = null;
});

$('#importeFactura').change(function() {
    btnSaveEnv.disabled = false;
    btnSaveEnv.style.pointerEvents = null;
});

var mensaje = "";
var dateToday = new Date();
const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
const TO_PATTERN = "DD/MM/YYYY HH:mm";

function agregarEnvio(nombre_usuario, folio) {
    // $("#idOportunidad").val(0);
    idOportunidadRelacion.required = false;
    mensaje = "Envío Agregado";
    cancelada = 0;
    save_method = "add";
    // $("#modal-create-envio").addClass("showModal");
    $("imput[name=_method]").val("POST");
    $("#modal-create-envio").modal("show");
    $("#modal-create-envio").find("form").trigger("reset");
    // $("#modal-create-envio").find("form")[0].reset();
    $("#tituloEnvío").text("Nuevo Envío");
    $("#btnBackEnv").text("Regresar");
    $("#folio").text("No. " + folio + " - ");
    $("#fecCreacion").val(dateToday.toLocaleString("es-MX"));
    $("#idUsuarioCreacion").val(nombre_usuario);
    document.getElementById("seccion_retencion").style.display = "none";
    document.getElementById("tabla_retenciones").style.display = "none";
    document.getElementById("time_line").style.display = "none";
    document.getElementById("documentos-envio-tab").style.display = "none";
    document.getElementById("documentos-envio").style.display = "none";
    document.getElementById("isCancelled").style.display = "none";
    $("#info_general").removeClass("col-lg-7");
    $("#info_general").addClass("col-lg-12");
    document.getElementById("seccion_estadisticos").style.display = "none";
    habilitarInfoGeneral();
    addEdit();
    validarRequeridosOpor();
    $("#btnDescargarCot").addClass('disabled');
    // agenteid = $("#agente_id");
    // agente = $("#idAgente");
    // getAgentes(agenteid, agente);
    loaderModalOut();

    var idClienteSelec2 = $('#idCliente').select2();

    $.ajax({
        url: serve + "getClientes/",
        type: "GET",
        dataType: "json",
        data: {
            id: 0,
        },
        success: function(r) {
            idClienteSelec2.find("option").remove();

            idClienteSelec2.append(
                '<option selected disabled>Requerido</option>'
            );

            $(r).each(function(i, v) {
                idClienteSelec2.append(
                    '<option value="' + v.idCliente + '">' + v.razonsocial + "</option>"
                );
            });
        },
        error: function(r) {
            Swal.fire("El campo es requerido ..");
            // validarRequeridosOpor();
        },
    });

}

function editarEnvio(id) {
    mensaje = "Envío Actualizado";
    save_method = "edit";
    // $("#modal-create-envio").addClass("showModal");
    $("imput[name=_method]").val("PATCH");

    // $("#modal-create-envio form")[0].reset();

    $("#modal-create-envio").find("form").trigger("reset");
    $.ajax({
        url: serve + "envios/" + id + "/edit",
        type: "GET",
        dataType: "JSON",
        data: {
            idOportunidad: id,
        },
        success: function(data) {
            $("#folio").text("No. " + data.Folio + " - ");

            $("#idOportunidad").val(data.idOportunidad);
            $("#idEquipo").val(data.idEquipo);

            cancelada = data.Cancelada;

            agente = $("#idAgente");

            $.ajax({
                data: {
                    idEquipo: data.idEquipo,
                },
                url: serve + "getEquipos/",
                type: "GET",
                dataType: "json",
                success: function(r) {
                    agente.find("option").remove();

                    agente.append(
                        '<option selected disabled>Requerido</option>'
                    );

                    $(r).each(function(i, v) {
                        if (v.Id_Usuario == data.id_Agente) {
                            agente.append(
                                '<option value="' +
                                v.Id_Usuario +
                                '" selected>' +
                                v.Nombre +
                                "</option>"
                            );
                        } else {
                            agente.append(
                                '<option value="' + v.Id_Usuario + '">' + v.Nombre + "</option>"
                            );
                        }
                    });
                },
                error: function(r) {
                    Swal.fire("El campo es requerido ..");
                    // validarRequeridosOpor();
                },
            });

            // $("#idCliente").val(data.idCliente);

            var idClienteSelec2 = $('#idCliente').select2();

            $.ajax({
                url: serve + "getClientes/",
                type: "GET",
                dataType: "json",
                data: {
                    id: data.idOportunidad,
                },
                success: function(r) {
                    idClienteSelec2.find("option").remove();

                    idClienteSelec2.append(
                        '<option selected disabled>Requerido</option>'
                    );

                    $(r).each(function(i, v) {
                        if (v.idCliente == data.idCliente) {
                            idClienteSelec2.append(
                                '<option value="' +
                                v.idCliente +
                                '" selected>' +
                                v.razonsocial +
                                "</option>"
                            );
                        } else {
                            idClienteSelec2.append(
                                '<option value="' + v.idCliente + '">' + v.razonsocial + "</option>"
                            );
                        }
                    });
                    if ($('#idCliente').select2("data")[0].element.value != "Requerido") {
                        $('#idCliente').select2().addClass('select2-green')
                    } else {
                        $('#idCliente').select2().addClass('select2-red')
                    }
                },
                error: function(r) {
                    Swal.fire("El campo es requerido ..");
                    // validarRequeridosOpor();
                },
            });


            $("#idAgente").val(data.id_Agente);
            $("#nomOportunidad").val(data.nomOportunidad);
            $("#idUsuario").val(data.Id_Usuario);
            $("#idtipo").val(data.idTipoOportunidad);
            $("#estatus").val(data.idEstatus);

            CotizacionAutorizada = $("#idCotizacionAutorizada");

            $.ajax({
                data: {
                    idOportunidad: data.idOportunidad,
                },
                url: serve + "getFoliosCot/",
                type: "GET",
                dataType: "json",
                success: function(r) {
                    CotizacionAutorizada.find("option").remove();

                    CotizacionAutorizada.append(
                        "<option selected disabled>No. Cotizacion Autorizada</option>"
                    );

                    $(r).each(function(i, v) {
                        if (v.folioCotizacion == data.idCotizacionAutorizada) {
                            CotizacionAutorizada.append(
                                '<option value="' +
                                v.folioCotizacion +
                                '" selected>' +
                                v.folioCotizacion +
                                "</option>"
                            );
                        } else {
                            CotizacionAutorizada.append(
                                '<option value="' +
                                v.folioCotizacion +
                                '">' +
                                v.folioCotizacion +
                                "</option>"
                            );
                        }
                    });
                },
                error: function(r) {
                    Swal.fire("Ocurrio un error en el servidor ..");
                },
            });

            $("#idCotizacionAutorizada").val(data.idCotizacionAutorizada);
            $("#numOrdenMTC").val(data.numOrdenMTC);

            $("#totDestinos").val(data.totDestinos);
            $("#tamPieza").val(data.tamPieza);
            $("#pesoPieza").val(data.pesoPieza * 1);
            $("#idUnidadPeso").val(data.idUnidadPeso);
            $("#idUsuarioCreacion").val(data.idUsuarioCreacion);
            $("#nombreUsuarioCreacion").val(data.Creado);
            $("#fecCreacion").val(data.fecCreacion.toLocaleString());

            OportunidadesAsignadas(data.idOportunidad);

            if (data.idOportunidadRelacion == null) {
                $("#idOportunidadRelacion").val("Oportunidad");
            } else {
                $("#idOportunidadRelacion").val(data.idOportunidadRelacion);
            }

            if ($.trim(data.servicioAdecuado) === "SI") {
                $("#servAdecuadoSi").prop("checked", true);
                opcionesNo.style.display = "none";
            } else if ($.trim(data.servicioAdecuado) === "NO") {
                $("#servAdecuadoNo").prop("checked", true);
                opcionesNo.style.display = "block";
            } else {
                $("#servAdecuadoSi").prop("checked", true);
                opcionesNo.style.display = "none";
            }

            if ($.trim(data.opcionNo) === "FALTA DE SEGUIMIENTO") {
                $("#servAdecuadoNoFS").prop("checked", true);
            } else if (
                $.trim(data.opcionNo) ===
                "EL TRATO/ATENCIÓN POR PARTE DEL PERSONAL DEL ÁREA NO FUE EL REQUERIDO"
            ) {
                $("#servAdecuadoNoAtencion").prop("checked", true);
            } else if (
                $.trim(data.opcionNo) ===
                "NO SE RECIBIÓ LA INFORMACIÓN EN TIEMPO Y FORMA"
            ) {
                $("#servAdecuadoNoTiempo").prop("checked", true);
            } else if ($.trim(data.opcionNo) == "OTRO") {
                $("#servAdecuadoNoOtro").prop("checked", true);
            } else {
                $("#servAdecuadoNoFS").prop("checked", false);
                $("#servAdecuadoNoAtencion").prop("checked", false);
                $("#servAdecuadoNoTiempo").prop("checked", false);
                $("#servAdecuadoNoOtro").prop("checked", false);
            }

            $("#numFactura").val(data.numFactura);
            $("#importeFactura").val(data.importeFactura);

            validarRequeridosOpor();

            addEdit();

            timeLineStatus(data);

            if (data.opcMasEconomico == 1) {
                $("#opcMasEconomico").prop("checked", true);
            } else {
                $("#opcMasEconomico").prop("checked", false);
            }

            if (data.opcMasRapido == 1) {
                $("#opcMasRapido").prop("checked", true);
            } else {
                $("#opcMasRapido").prop("checked", false);
            }

            if (data.opcConSeguro == 1) {
                $("#opcConSeguro").prop("checked", true);
            } else {
                $("#opcConSeguro").prop("checked", false);
            }

            if (data.muestra == 1) {
                $("#muestra").prop("checked", true);
            } else {
                $("#muestra").prop("checked", false);
            }

            if (data.opcConfirmarEnvio == 1) {
                $("#opcConfirmarEnvio").prop("checked", true);
            } else {
                $("#opcConfirmarEnvio").prop("checked", false);
            }

            $("#fecUltCambio").val(data.fecUltimoCambio);

            $("#notaRetencion").val(data.notaRetencion);

            if (data.notaRetencion != null) {
                $("#Retencion").prop("checked", true);
                btnSaveEnv.disabled = true;
                btnSaveEnv.style.pointerEvents = 'none';
            } else {
                $("#Retencion").prop("checked", false);
            }

            if (notaretencion.value != "") {
                btnSaveEnv.disabled = true;
                btnSaveEnv.style.pointerEvents = 'none';
                $("#idStatusPre").prop("disabled", true);
                $("#idStatusSolCot").prop("disabled", true);
                $("#idStatusCotTerm").prop("disabled", true);
                $("#idStatusCotAprob").prop("disabled", true);
                $("#idStatusCotCanc").prop("disabled", true);
                $("#idCotizacionAutorizada").prop("disabled", true);
            }

            if (cancelada == 1) {
                isCancelled.style.display = 'block';
                nota_cancelacion.style.display = 'block';
                $("#nota_cancelacion_value").text(data.NotaCancelacion);
            } else {
                isCancelled.style.display = 'none';
                nota_cancelacion.style.display = 'none';
                $("#nota_cancelacion_value").text("");
            }

            $("#modal-create-envio").modal("show");
            loaderModalOut();
            $("#tituloEnvío").text("Editar Envío");
            $("#btnBackEnv").text("Regresar");
            document.getElementById("seccion_retencion").style.display = "flex";
            document.getElementById("seccion_estadisticos").style.display = "none";
            document.getElementById("tabla_retenciones").style.display = "block";
            document.getElementById("time_line").style.display = "block";
            if (idRol != 1014) {
                document.getElementById("documentos-envio-tab").style.display = "block";
                document.getElementById("documentos-envio").style.display = "block";
            }
            $("#info_general").removeClass("col-lg-12");
            $("#info_general").addClass("col-lg-7");
            $("#info_general").addClass("col-12");
            $("#notaRetencion").prop("readonly", true);
            notaretencion.style.borderColor = "silver";

            $("#tblOporEnvRetenidas").DataTable({
                destroy: true,
                processing: true,
                serverSide: true,
                select: true,
                scrollX: true,
                searchable: false,
                orderable: false,
                scrollCollapse: true,
                ajax: {
                    url: serve + "api/oportEnvRetenidas/" + id,
                    type: "GET",
                    dataType: "json",
                },
                language: {
                    url: serve + "js/spanish-DT.json",
                },
                dom: '<"top">rt<"bottom"><"clear">',
                columnDefs: [{
                    targets: [1, 2],
                    render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
                }, ],
                columns: [{
                        data: "notaRetencion",
                        name: "notaRetencion",
                        title: "Nota",
                        searchable: false,
                        orderable: false,
                        className: "dt-body-center text-left",
                    },
                    {
                        data: "fecRetencionInicio",
                        name: "fecRetencionInicio",
                        title: "Fecha Inicio",
                        searchable: false,
                        orderable: false,
                        className: "dt-body-center text-center",
                    },
                    {
                        data: "fecRetencionFin",
                        name: "fecRetencionFin",
                        title: "Fecha Fin",
                        searchable: false,
                        orderable: false,
                        className: "dt-body-center text-center",
                    },
                ],
            });
        },
        error: function() {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Faltan datos por capturar",
            });
        },
    });
}

function timeLineStatus(data) {
    fecha_creacion = dateFormat(data.fecCreacion, "dd-m-yyyy H:MM:ss");
    fecha_SolCotizacion = dateFormat(data.fecSolCotizacion, "dd-m-yyyy H:MM:ss");
    fecha_CotTerminada = dateFormat(data.fecCotTerminada, "dd-m-yyyy H:MM:ss");
    fecha_ResultadoF = dateFormat(data.fecResultadoF, "dd-m-yyyy H:MM:ss");

    $('#aprobada').val("");

    if (data.Cancelada == 1) {
        $("#idStatusCotCanc").prop("checked", true);
        $("#idStatusCotCanc").prop("disabled", false);
        $("#idCotizacionAutorizada").prop("disabled", true);
        preregi.classList.add("bg-green", "fa-check");
        preregi.classList.remove("fa-times", "bg-red");
        preregh5.classList.add("bg-blue", "text-bold");
        $("#prereg_span").text(fecha_creacion);
        $("#prereg_span").removeClass("fas fa-clock");
        solcoti.classList.add("bg-green", "fa-check");
        solcoti.classList.remove("fa-times", "bg-red");
        solcoth5.classList.add("bg-blue", "text-bold");
        $("#solcot_span").text(fecha_SolCotizacion);
        $("#solcot_span").removeClass("fas fa-clock");
        cottermi.classList.add("bg-green", "fa-check");
        cottermi.classList.remove("fa-times", "bg-red");
        cottermh5.classList.add("bg-blue", "text-bold");
        $("#cotterm_span").text(fecha_CotTerminada);
        $("#cotterm_span").removeClass("fas fa-clock");
        resulfini.classList.add("bg-green", "fa-check");
        resulfini.classList.remove("fa-times", "bg-red");
        resulfinh5.classList.add("bg-blue", "text-bold");
        $("#resulfin_span").text(fecha_ResultadoF);
        $("#resulfin_span").removeClass("fas fa-clock");
        $("#final_i").removeClass("fas fa-clock");
        $("#final_i").addClass("fas fa-times");

        var nodes_env = document
            .getElementById("info_general")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_env.length; i++) {
            nodes_env[i].disabled = true;
        }

        $("#nomOportunidad").prop("disabled", true);

        $("#btnSaveEnv").prop('disabled', true);
        btnSaveEnv.style.pointerEvents = 'none';
        $("#btnBackEnv").prop('disabled', false);
        $("#btnCancelar").addClass('disabled');
        $("#btnDescargarCot").addClass('disabled');

        opcionesNo.style.display = "none";
        infoEnvio.style.display = "none";
        infoFactura.style.display = "none";


    } else if (data.idEstatus == 1) {
        habilitarInfoGeneral();

        $("#idStatusPre").prop("checked", true);
        $("#idStatusPre").prop("disabled", false);
        $("#idStatusSolCot").prop("disabled", false);
        preregi.classList.add("bg-green", "fa-check");
        preregi.classList.remove("fa-times", "bg-red");
        preregh5.classList.add("bg-blue", "text-bold");
        $("#prereg_span").text(fecha_creacion);
        $("#prereg_span").removeClass("fas fa-clock");
        $("#nomOportunidad").prop("disabled", false);
        $("#btnDescargarCot").addClass('disabled');
        muestra.disabled = false;
        muestra.checked = false;
    } else if (data.idEstatus == 2) {
        habilitarInfoGeneral();

        muestra.disabled = true;
        muestra.checked = false;
        $("#idStatusSolCot").prop("checked", true);
        $("#idStatusSolCot").prop("disabled", false);
        $("#idStatusCotTerm").prop("disabled", true);
        preregi.classList.add("bg-green", "fa-check");
        preregi.classList.remove("fa-times", "bg-red");
        preregh5.classList.add("bg-blue", "text-bold");
        $("#prereg_span").text(fecha_creacion);
        $("#prereg_span").removeClass("fas fa-clock");
        solcoti.classList.add("bg-green", "fa-check");
        solcoti.classList.remove("fa-times", "bg-red");
        solcoth5.classList.add("bg-blue", "text-bold");
        $("#solcot_span").text(fecha_SolCotizacion);
        $("#solcot_span").removeClass("fas fa-clock");
        $("#nomOportunidad").prop("disabled", false);
        $("#Retencion").removeClass("disabled");
        $("#btnDescargarCot").addClass('disabled');
    } else if (data.idEstatus == 6) {
        habilitarInfoGeneral();

        muestra.disabled = true;
        opcConSeguro.disabled = true;
        opcMasEconomico.disabled = true;
        opcMasRapido.disabled = true;

        $("#idStatusCotTerm").prop("checked", true);
        if (idRol == 1014) {
            $("#idStatusCotTerm").prop("disabled", true);
            $("#idStatusCotAprob").prop("disabled", true);
            $("#idStatusCotCanc").prop("disabled", true);
        } else {
            $("#idStatusCotTerm").prop("disabled", false);
            $("#idStatusCotAprob").prop("disabled", false);
            $("#idStatusCotCanc").prop("disabled", false);
        }
        $("#idCotizacionAutorizada").prop("disabled", true);
        preregi.classList.add("bg-green", "fa-check");
        preregi.classList.remove("fa-times", "bg-red");
        preregh5.classList.add("bg-blue", "text-bold");
        $("#prereg_span").text(fecha_creacion);
        $("#prereg_span").removeClass("fas fa-clock");
        solcoti.classList.add("bg-green", "fa-check");
        solcoti.classList.remove("fa-times", "bg-red");
        solcoth5.classList.add("bg-blue", "text-bold");
        $("#solcot_span").text(fecha_SolCotizacion);
        $("#solcot_span").removeClass("fas fa-clock");
        cottermi.classList.add("bg-green", "fa-check");
        cottermi.classList.remove("fa-times", "bg-red");
        cottermh5.classList.add("bg-blue", "text-bold");
        $("#cotterm_span").text(fecha_CotTerminada);
        $("#cotterm_span").removeClass("fas fa-clock");
        $("#nomOportunidad").prop("disabled", false);
        $("#Retencion").prop("disabled", true);
        $("#btnDescargarCot").removeClass('disabled');


    } else if (data.idEstatus == 7) {

        $("#idStatusCotAprob").prop("checked", true);
        $("#idStatusCotAprob").prop("disabled", false);
        $("#idStatusCotCanc").prop("disabled", true);
        $("#idCotizacionAutorizada").prop("disabled", true);
        preregi.classList.add("bg-green", "fa-check");
        preregi.classList.remove("fa-times", "bg-red");
        preregh5.classList.add("bg-blue", "text-bold");
        $("#prereg_span").text(fecha_creacion);
        $("#prereg_span").removeClass("fas fa-clock");
        solcoti.classList.add("bg-green", "fa-check");
        solcoti.classList.remove("fa-times", "bg-red");
        solcoth5.classList.add("bg-blue", "text-bold");
        $("#solcot_span").text(fecha_SolCotizacion);
        $("#solcot_span").removeClass("fas fa-clock");
        cottermi.classList.add("bg-green", "fa-check");
        cottermi.classList.remove("fa-times", "bg-red");
        cottermh5.classList.add("bg-blue", "text-bold");
        $("#cotterm_span").text(fecha_CotTerminada);
        $("#cotterm_span").removeClass("fas fa-clock");
        resulfini.classList.add("bg-green", "fa-check");
        resulfini.classList.remove("fa-times", "bg-red");
        resulfinh5.classList.add("bg-blue", "text-bold");
        $("#resulfin_span").text(fecha_ResultadoF);
        $("#resulfin_span").removeClass("fas fa-clock");
        $("#final_i").removeClass("bg-red fas fa-clock");
        $("#final_i").addClass("bg-green fas fa-check");

        $('#aprobada').val('SI');

        if (idRol == 8 || idRol == 1) {
            var nodes_env = document
                .getElementById("info_general")
                .getElementsByTagName("*");
            for (var i = 0; i < nodes_env.length; i++) {
                nodes_env[i].disabled = false;
            }

            $("#nomOportunidad").prop("disabled", false);

            $("#numOrdenMTC").prop("disabled", false);

        } else {
            var nodes_env = document
                .getElementById("info_general")
                .getElementsByTagName("*");
            for (var i = 0; i < nodes_env.length; i++) {
                nodes_env[i].disabled = true;
            }
            $("#nomOportunidad").prop("disabled", true);
        }


        $("#btnSaveEnv").prop('disabled', true);
        btnSaveEnv.style.pointerEvents = 'none';
        $("#btnBackEnv").prop("disabled", false);

        $("#btnDescargarCot").removeClass("disabled");
        $("#btnCancelar").addClass('disabled');

        if ($.trim(data.servicioAdecuado) === "SI" || $.trim(data.servicioAdecuado) === "") {
            opcionesNo.style.display = "none";
        } else {
            opcionesNo.style.display = "block";
        }

        infoEnvio.style.display = "block";
        infoFactura.style.display = "block";

        var nodes_env_time = document
            .getElementById("time_line")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_env_time.length; i++) {
            nodes_env_time[i].disabled = true;
        }

        var nodes_info_env = document
            .getElementById("infoEnvio")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_info_env.length; i++) {
            nodes_info_env[i].disabled = false;
        }


    } else if (data.idEstatus == 8) {
        $("#idStatusCotCanc").prop("checked", true);
        $("#idStatusCotCanc").prop("disabled", false);
        $("#idCotizacionAutorizada").prop("disabled", true);
        preregi.classList.add("bg-green", "fa-check");
        preregi.classList.remove("fa-times", "bg-red");
        preregh5.classList.add("bg-blue", "text-bold");
        $("#prereg_span").text(fecha_creacion);
        $("#prereg_span").removeClass("fas fa-clock");
        solcoti.classList.add("bg-green", "fa-check");
        solcoti.classList.remove("fa-times", "bg-red");
        solcoth5.classList.add("bg-blue", "text-bold");
        $("#solcot_span").text(fecha_SolCotizacion);
        $("#solcot_span").removeClass("fas fa-clock");
        cottermi.classList.add("bg-green", "fa-check");
        cottermi.classList.remove("fa-times", "bg-red");
        cottermh5.classList.add("bg-blue", "text-bold");
        $("#cotterm_span").text(fecha_CotTerminada);
        $("#cotterm_span").removeClass("fas fa-clock");
        resulfini.classList.add("bg-green", "fa-check");
        resulfini.classList.remove("fa-times", "bg-red");
        resulfinh5.classList.add("bg-blue", "text-bold");
        $("#resulfin_span").text(fecha_ResultadoF);
        $("#resulfin_span").removeClass("fas fa-clock");
        $("#final_i").removeClass("fas fa-clock");
        $("#final_i").addClass("fas fa-times");

        var nodes_env = document
            .getElementById("info_general")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_env.length; i++) {
            nodes_env[i].disabled = true;
        }

        $("#nomOportunidad").prop("disabled", true);

        $("#btnSaveEnv").prop('disabled', true);
        btnSaveEnv.style.pointerEvents = 'none';
        $("#btnBackEnv").prop('disabled', false);

        $("#btnDescargarCot").addClass("disabled");

        opcionesNo.style.display = "none";
        infoEnvio.style.display = "none";
        infoFactura.style.display = "none";

    } else {
        $("#idStatusPre").prop("checked", true);
        $("#idStatusPre").prop("disabled", false);
        $("#btnDescargarCot").addClass("disabled");
    }
}

function habilitarInfoGeneral() {
    if (idRol == 1014) {
        var nodes_env = document
            .getElementById("info_general")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_env.length; i++) {
            nodes_env[i].disabled = true;
        }


        $('input[name="idStatus"]').prop('disabled', true);

    } else {
        var nodes_env = document
            .getElementById("info_general")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_env.length; i++) {
            nodes_env[i].disabled = false;
        }
    }

    btnSaveEnv.disabled = true;
    btnSaveEnv.style.pointerEvents = 'none';
    var idEquipo_option = document.getElementById("idEquipo").getElementsByTagName("option");
    var idTipo_option = document.getElementById("idtipo").getElementsByTagName("option");
    var idUnidadPeso_option = document.getElementById("idUnidadPeso").getElementsByTagName("option");
    var idCliente_option = document.getElementById("idCliente").getElementsByTagName("option");
    var idAgente_option = document.getElementById("idAgente").getElementsByTagName("option");

    idEquipo_option[0].disabled = true;
    idTipo_option[0].disabled = true;
    idUnidadPeso_option[0].disabled = true;
    idCliente_option[0].disabled = true;
    idAgente_option[0].disabled = true;

    var nodes_estatus = document
        .getElementById("listEstatus")
        .getElementsByTagName("*");
    for (var i = 0; i < nodes_estatus.length; i++) {
        nodes_estatus[i].disabled = true;
    }

    omtc.style.borderColor = "silver";
    numOrdenMTC.style.borderColor = "silver";
    infoEnvio.style.display = "none";
    opcionesNo.style.display = "none";
    infoFactura.style.display = "none";
}

function timeLineReset() {
    var nodes_i = document.getElementById("time_line").getElementsByTagName("i");
    var nodes_h5 = document
        .getElementById("time_line")
        .getElementsByTagName("h5");

    for (var i = 0; i < nodes_i.length; i++) {
        nodes_i[i].classList.remove("bg-green", "fa-check");
        nodes_i[i].classList.add("fa-times", "bg-red");
    }

    for (var i = 0; i < nodes_h5.length; i++) {
        nodes_h5[i].classList.remove("bg-blue", "text-bold");
    }

    $("#prereg_span").text("");
    $("#prereg_span").addClass("fas fa-clock");

    $("#solcot_span").text("");
    $("#solcot_span").addClass("fas fa-clock");

    $("#cotterm_span").text("");
    $("#cotterm_span").addClass("fas fa-clock");

    $("#resulfin_span").text("");
    $("#resulfin_span").addClass("fas fa-clock");
    $("#final_i").removeClass("fas fa-times");
    $("#final_i").addClass("fas fa-clock");
    // $('#aprobada').val("");
}


function addEdit() {
    var nodes = document.getElementById("listEstatus").getElementsByTagName("*");

    for (var i = 0; i < nodes.length; i++) {
        nodes[i].disabled = true;
    }

    if (save_method == "add") {
        $("#idStatusPre").prop("checked", true);
        $("#idStatusPre").prop("disabled", false);
        $("#idOportunidadRelacion").val("Oportunidad");
        $("#btnCancelar").addClass('disabled');

        $("#nomOportunidad").prop("disabled", false);


        document.getElementById("sbtnCancelarkEnv").style.display = "none";

        $("#sbtnBackEnv").addClass("col-md-6");
        $("#sbtnSaveEnv").addClass("col-md-6");


    } else {
        $("#btnCancelar").removeClass('disabled');
        $("#sbtnBackEnv").addClass("col-md-4");
        $("#sbtnSaveEnv").addClass("col-md-4");
        btnSaveEnv.disabled = true;
        btnSaveEnv.style.pointerEvents = 'none';
    }
}

const $btnSaveEnv = document.querySelector("#btnSaveEnv");

$(function() {
    $("#modal-create-envio form")
        .validator()
        .on("submit", function(e) {
            $btnSaveEnv.disabled = true;
            btnSaveEnv.style.pointerEvents = 'none';
            if (!e.isDefaultPrevented()) {
                var aprobada = $('#aprobada').val();
                var id = $("#idOportunidad").val();
                var servAdecuado = $('input:radio[name="servAdecuado"]').val();
                var numFactura = $("#numFactura").val();
                var importeFactura = $("#importeFactura").val();
                var servAdecuadoNoOpciones = $('input:radio[name="servAdecuadoNoOpciones"]').val();

                if (aprobada === 'SI') {
                    $.ajax({
                        url: serve + "infoEnvio",
                        type: "PATCH",
                        dataType: 'JSON',
                        data: {
                            id: id,
                            servAdecuado: servAdecuado,
                            numFactura: numFactura,
                            importeFactura: importeFactura,
                            servAdecuadoNoOpciones: servAdecuadoNoOpciones,
                            _token: CSRF_TOKEN,
                            _method: "PATCH",
                        },
                        success: function(data) {
                            $("#modal-create-envio form")[0].reset();
                            $("#modal-create-envio").modal("hide");
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
                            $("#modal-create-envio form")[0].reset();
                            $("#modal-create-envio").modal("hide");
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
                            tblEnvios.ajax.reload();
                        },
                    });
                    return false;
                } else {
                    if (save_method == "add") {
                        url = serve + "envios";
                        type = "POST";
                        titleError = "Ocurrio un error...";
                    } else {
                        url = serve + "envios/" + id;
                        type = "PUT";
                        titleError = "Faltan datos por capturar...";
                    }
                    
                    
                    $.ajax({
                        url: url,
                        type: type,
                        data: $("#modal-create-envio form").serialize(),
                        success: function(data) {
                            $("#modal-create-envio form")[0].reset();
                            $("#modal-create-envio").modal("hide");
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
                            tblEnvios.ajax.reload();
                        },
                        error: function() {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            Msg.fire({
                                title: titleError,
                            });
                        },
                    });
                    return false;
                }
            }
        });
});

function closeMEnvio() {
    timeLineReset();
    mostrarGeneralEnvio();
    OportunidadesAsignadas(0);
    loaderOut();
    // $("#modal-create-envio").removeClass("showModal");
}

$("#numOrdenMTC").keydown(function() {
    numOMTC = $("#numOrdenMTC").val();

    if (numOMTC.length < 7) {
        $("#btnSaveEnv").prop('disabled', true);
        btnSaveEnv.style.pointerEvents = 'none';
    }
});

$("#numOrdenMTC").change(function(e) {
    e.preventDefault();
    numOMTC = $("#numOrdenMTC").val();
    $.ajax({
        url: serve + "obtenerOMTCsEnvios",
        type: "GET",
        dataType: "json",
        data: {
            _token: CSRF_TOKEN,
            search: numOMTC,
        },
        success: function(data) {
            Swal.fire("¡Orden existente!");
            $("#btnSaveEnv").prop('disabled', false);
            btnSaveEnv.style.pointerEvents = null;
            $("#numOrdenMTC").prop('required', false);
        },
        error: function() {
            ordenMetrics = document.getElementById('numOrdenMTC');
            $("#numOrdenMTC").val('');
            $("#numOrdenMTC").prop('required', true);
            $("#numOrdenMTC").focus();
            ordenMetrics.style.borderColor = "red";
            Swal.fire("¡La orden capturada no existe!");
            $("#btnSaveEnv").prop('disabled', true);
            btnSaveEnv.style.pointerEvents = 'none';
        }
    });
});

$("#numOrdenMTC").autocomplete({
    delay: 0,
    minLength: 3,
    source: function(request, response) {
        $.ajax({
            url: serve + "obtenerOPsEnvios",
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
});

function CancelarEnvio() {
    loaderModalON();
    mensaje = "Envío Cancelado";
    id_suario = document.getElementById("idUsuario");
    id = document.getElementById("idOportunidad");

    Swal.fire({
        title: "Envíos",
        text: "Cancelar el envío Seleccionado?",
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
                inputPlaceholder: "Captura el motivo de Cancelación de la Oportunidad de Envío",
                inputAttributes: {
                    "aria-label": "Captura el motivo de Cancelación de la Oportunidad de Envío",
                },
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar',
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                target: document.getElementById('modal-create-envio'),
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: serve + "cancelarEnvio",
                        type: "PATCH",
                        data: {
                            _method: "PATCH",
                            id: id.value,
                            id_suario: id_suario.value,
                            motivoCancelacion: result.value,
                            _token: CSRF_TOKEN,
                        },
                        success: function(data) {
                            $("#modal-create-envio form")[0].reset();
                            $("#modal-create-envio").modal("hide");
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
                            loaderOut();
                        },
                        error: function() {
                            $("#modal-create-envio form")[0].reset();
                            $("#modal-create-envio").modal("hide");
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
                            tblEnvios.ajax.reload();
                        },
                    });
                    tblEnvios.ajax.reload();
                    loaderModalOut();
                } else {
                    loaderModalOut();
                }
            });

            //   if (text) {
            //     Swal.fire(text)
            //   }
        } else {
            loaderModalOut();
        }
    });
}

function descargarCot() {
    dominio = document.domain;
    id = document.getElementById("idOportunidad");
    if (dominio == "127.0.0.1") {
        window.open('https://servicios.litoprocess.com/' + ruta + id.value, '_blank');
    } else if (dominio == "192.168.2.222") {
        window.open('http://' + dominio + '/' + ruta + id.value, '_blank');
    } else {
        window.open('https://' + dominio + '/' + ruta + id.value, '_blank');
    }
}