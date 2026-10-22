$(document).ready(function() {
    serve = base_path;
    reuniones = null;

    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";

    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    tblLlamadas = $("#tblLlamadas").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "idLlamadasReuniones",
        select: false,
        scrollX: true,
        scrollCollapse: true,
        stateSave: false,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "api/Llamadas",
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
                title: "Lamadas | CANDY CRM",
                text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
                exportOptions: {
                    orthogonal: "export",
                    columns: "thead th:not(.noExport)",
                    modifer: {
                        serverSide: false,
                        page: "all",
                    },
                },
                filename: "Llamadas",
                autoFilter: true,
                sheetName: "Lista Llamadas",
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
        columnDefs: [{
            targets: [5, 6],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
        }, ],
        columns: [{
                data: "folio",
                name: "folio",
                width: "1rem",
                title: "Folio",
                searchable: true,
                className: "dt-body-center text-center",
            },
            {
                data: "realizada",
                render: function(data, type, row) {
                    if (row.cancelado == 1 || row.realizada == 1) {

                        if (data == 0 || data == null) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }

                    } else {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0 || data == null) {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="realizada(' +
                                    row.idLlamadasReuniones +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="realizada(' +
                                    row.idLlamadasReuniones +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }

                        }
                    }
                },
                title: "Realizada",
                width: "3rem",
                className: "dt-body-center text-center",
            },
            {
                data: "Situacion",
                render: function(data, type, row) {
                    if (data == "ATRASADA") {
                        return (
                            '<div class="rounded" style="background-color: #ec7b75"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "A TIEMPO") {
                        return (
                            '<div class="rounded" style="background-color: #84C876"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Situación",
                className: "dt-body-center text-center",
            },
            {
                data: "asuntoLugar",
                name: "asuntoLugar",
                render: function(data, type, row) {
                    if ($.trim(row.inputEditar) === "Editar") {
                        if (row.cancelado == "1") {
                            return (
                                '<a title="Editar Llamada" onclick="editarLlamada(' + row.idLlamadasReuniones + '), loaderON()" class="text-red text-uppercase cursor-pointer">' +
                                data +
                                "</a>"
                            );
                        } else {
                            return (
                                '<a title="Editar Llamada" onclick="editarLlamada(' + row.idLlamadasReuniones + '), loaderON()" class="text-uppercase cursor-pointer">' +
                                data +
                                "</a>"
                            );
                        }
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Asunto",
            },
            {
                data: "razonsocial",
                name: "razonsocial",
                width: "1rem",
                title: "Cliente",
                searchable: true,
            },
            {
                data: "fecCreacion",
                name: "fecCreacion",
                width: "2rem",
                className: "dt-body-center text-center",
                title: "Creación",
            },
            {
                data: "fecInicio",
                name: "fecInicio",
                width: "2rem",
                className: "dt-body-center text-center",
                title: "Fecha",
            },
            {
                data: "Direccion",
                name: "Direccion",
                width: "2rem",
                title: "Tipo Llamada",
            },
            {
                data: "Repeticion",
                name: "Repeticion",
                width: "2rem",
                title: "Repeticion",
            },
            {
                data: "Usuario",
                // name: "Agente",
                title: "Usuario",
                width: "2rem",
            },
            {
                data: "Estado",
                render: function(data, type, row) {
                    if (data == "PLANIFICADA") {
                        return (
                            '<div class="rounded" style="background-color: #84C876"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "CANCELADA") {
                        return (
                            '<div class="rounded" style="background-color: #EE0E12"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "REALIZADA") {
                        return (
                            '<div class="rounded" style="background-color: #369BDF"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Estado",
                className: "dt-body-center text-center",
            },
        ],
        pageLength: 50,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, 'TODOS'],
        ],
        // initComplete: function(settings, json) {},
    });

    selectRow("tblLlamadas");

    $("#tblLlamadas")
        .DataTable()
        .on("draw", function() {
            ajustarTablas();
            loaderOut();
        });

    tblReuniones = $("#tblReuniones").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "folio",
        select: false,
        scrollX: true,
        scrollCollapse: true,
        stateSave: false,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "api/Reuniones",
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
                title: "Reuniones | CANDY CRM",
                text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
                exportOptions: {
                    orthogonal: "export",
                    columns: "thead th:not(.noExport)",
                    modifer: {
                        serverSide: false,
                        page: "all",
                    },
                },
                filename: "Reuniones",
                autoFilter: true,
                sheetName: "Lista Reuniones",
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
        columnDefs: [{
            targets: [6, 7],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
        }, ],
        columns: [{
                data: "folio",
                name: "folio",
                width: "1rem",
                title: "Folio",
                searchable: true,
                className: "dt-body-center text-center",
            },
            {
                data: "realizada",
                render: function(data, type, row) {

                    if (row.cancelado == 1 || row.realizada == 1) {

                        if (data == 0 || data == null) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }

                    } else {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0 || data == null) {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="realizada(' +
                                    row.idLlamadasReuniones +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="realizada(' +
                                    row.idLlamadasReuniones +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }

                        }
                    }
                },
                width: "3rem",
                title: "Realizada",
                className: "dt-body-center text-center",
            },
            {
                data: "Situacion",
                render: function(data, type, row) {
                    if (data == "ATRASADA") {
                        return (
                            '<div class="rounded" style="background-color: #ec7b75"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "A TIEMPO") {
                        return (
                            '<div class="rounded" style="background-color: #84C876"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Situación",
                className: "dt-body-center text-center",
            },
            {
                data: "asuntoLugar",
                name: "asuntoLugar",
                render: function(data, type, row) {
                    if ($.trim(row.inputEditar) === "Editar") {
                        if (row.cancelado == "1") {
                            return (
                                '<a title="Editar Reunión" onclick="editarLlamada(' + row.idLlamadasReuniones + '), loaderON()" class="text-red text-uppercase cursor-pointer">' +
                                data +
                                "</a>"
                            );
                        } else {
                            return (
                                '<a title="Editar Reunión" onclick="editarLlamada(' + row.idLlamadasReuniones + '), loaderON()" class="text-uppercase cursor-pointer">' +
                                data +
                                "</a>"
                            );
                        }
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Lugar",
            },
            {
                data: "descripcion",
                name: "descripcion",
                width: "1rem",
                title: "Descripción",
                searchable: true,
            },
            {
                data: "razonsocial",
                name: "razonsocial",
                width: "1rem",
                title: "Cliente",
                searchable: true,
            },
            {
                data: "fecCreacion",
                name: "fecCreacion",
                width: "2rem",
                className: "dt-body-center text-center",
                title: "Creación",
            },
            {
                data: "fecInicio",
                name: "fecInicio",
                width: "2rem",
                className: "dt-body-center text-center",
                title: "Inicio",
            },
            {
                data: "Repeticion",
                name: "Repeticion",
                width: "2rem",
                title: "Repeticion",
            },
            {
                data: "Usuario",
                title: "Usuario",
                width: "2rem",
            },
            {
                data: "Estado",
                render: function(data, type, row) {
                    if (data == "PLANIFICADA") {
                        return (
                            '<div class="rounded" style="background-color: #84C876"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "CANCELADA") {
                        return (
                            '<div class="rounded" style="background-color: #EE0E12"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "REALIZADA") {
                        return (
                            '<div class="rounded" style="background-color: #369BDF"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Estado",
                className: "dt-body-center text-center",
            },
        ],
        pageLength: 50,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, 'TODOS'],
        ],
        initComplete: function(settings, json) {
            mostrarLlamadas();
        },
    });

    selectRow("tblReuniones");

    $("#tblReuniones")
        .DataTable()
        .on("draw", function() {
            ajustarTablas();
            loaderOut();
        });

    validarDatos(reuniones);

});

asuntoLugar = document.getElementById('asuntoLugar');
descripcion = document.getElementById('descripcion');
relacionadoCon = document.getElementById('relacionadoCon');
fecInicio = document.getElementById('fecInicioInput');
fecIni = document.getElementById('fecInicio');
direccion = document.getElementById('direccion');
tipoRepeticion = document.getElementById('tipoRepeticion');
cancelado = document.getElementById('cancelado');
btnSaveLla = document.getElementById('btnSaveLla');
id_contacto = document.getElementById('id_contacto');
tel_contacto = document.getElementById('tel_contacto');
var contacto_Id_llamada = null;
esRealizada = document.getElementById('esRealizada');
isCancelled = document.getElementById('isCancelled');
archivoGuardadoDiv = document.getElementById('archivoGuardadoDiv');
archivoGuardado = document.getElementById('archivoGuardado');
archivoGuardadoMeta = document.getElementById('archivoGuardadoMeta');
archivoGuardadoLink = document.getElementById('archivoGuardadoLink');
archivoGuardadoIcono = document.getElementById('archivoGuardadoIcono');


relacionadoConSelec2 = $('#relacionadoCon').select2({
    language: "es"
});

direccionLabel = document.getElementById('direccionLabel');

asuntoLugar.addEventListener('change', function() {
    validarDatos(reuniones);
})

descripcion.addEventListener('change', function() {
    validarDatos(reuniones);
})

relacionadoConSelec2.on('change', function() {
    // console.log(tipo.value);
    if (tipo.value == 'Llamadas') {
        idClienteLlamadaChange(relacionadoCon.value, contacto_Id_llamada);
    }
    validarDatos(reuniones);

})

id_contacto.addEventListener('change', function() {
    idContactoChange(id_contacto.value);
    validarDatos(reuniones);
})

tel_contacto.addEventListener('change', function() {
    validarDatos(reuniones);
})

fecInicio.addEventListener('blur', function() {
    validarDatos(reuniones);
})

direccion.addEventListener('change', function() {
    validarDatos(reuniones);
})

tipoRepeticion.addEventListener('change', function() {
    validarDatos(reuniones);
})

var today = new Date();
var tipoDoc = null;

function normalizarBase64Archivo(base64, extension) {
    if (!base64) {
        return null;
    }

    var trimmedBase64 = String(base64).trim();
    if (trimmedBase64.indexOf('data:') === 0) {
        return trimmedBase64;
    }

    var mimeTypes = {
        pdf: 'application/pdf',
        doc: 'application/msword',
        docx: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        xls: 'application/vnd.ms-excel',
        xlsx: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    };

    var normalizedExtension = extension ? String(extension).toLowerCase() : '';
    var mimeType = mimeTypes[normalizedExtension] || 'application/octet-stream';
    return 'data:' + mimeType + ';base64,' + trimmedBase64;
}

function obtenerIconoArchivo(extension) {
    var normalizedExtension = extension ? String(extension).toLowerCase() : '';

    switch (normalizedExtension) {
        case 'pdf':
            return {
                icon: 'fas fa-file-pdf',
                color: '#d93025',
                background: '#fce8e6'
            };
        case 'doc':
        case 'docx':
            return {
                icon: 'fas fa-file-word',
                color: '#185abc',
                background: '#e8f0fe'
            };
        case 'xls':
        case 'xlsx':
            return {
                icon: 'fas fa-file-excel',
                color: '#188038',
                background: '#e6f4ea'
            };
        default:
            return {
                icon: 'far fa-file',
                color: '#5f6368',
                background: '#f1f3f4'
            };
    }
}

function formatearPesoArchivo(bytes) {
    var size = Number(bytes);
    if (!size || size <= 0) {
        return '';
    }

    if (size < 1024) {
        return size + ' B';
    }

    if (size < 1024 * 1024) {
        return (size / 1024).toFixed(1) + ' KB';
    }

    return (size / (1024 * 1024)).toFixed(1) + ' MB';
}

function limpiarArchivoGuardado() {
    if (!archivoGuardadoDiv) {
        return;
    }

    archivoGuardadoDiv.style.display = 'none';
    archivoGuardado.textContent = '';
    archivoGuardadoMeta.textContent = '';
    archivoGuardadoLink.removeAttribute('href');
    archivoGuardadoLink.removeAttribute('download');
    archivoGuardadoLink.onclick = null;
    archivoGuardadoIcono.innerHTML = '<i class="far fa-file"></i>';
    archivoGuardadoIcono.style.color = '#1a73e8';
    archivoGuardadoIcono.style.backgroundColor = '#e8f0fe';
}

function mostrarArchivoGuardado(data) {
    var archivoBase64 = data && data.archivo ? data.archivo : null;
    var nombreArchivo = data && data.nombreArchivo ? data.nombreArchivo : '';

    if (!archivoBase64 || !nombreArchivo || !archivoGuardadoDiv) {
        limpiarArchivoGuardado();
        return;
    }

    var extension = data.extension || '';
    var dataUrl = normalizarBase64Archivo(archivoBase64, extension);
    var icono = obtenerIconoArchivo(extension);
    var peso = formatearPesoArchivo(data.peso);
    var extensionLabel = extension ? String(extension).toUpperCase() : 'ARCHIVO';

    archivoGuardado.textContent = nombreArchivo;
    archivoGuardadoMeta.textContent = extensionLabel + (peso ? ' · ' + peso : '');
    archivoGuardadoLink.setAttribute('href', dataUrl);
    archivoGuardadoLink.setAttribute('download', nombreArchivo);
    archivoGuardadoLink.onclick = function() {
        return true;
    };
    archivoGuardadoIcono.innerHTML = '<i class="' + icono.icon + '"></i>';
    archivoGuardadoIcono.style.color = icono.color;
    archivoGuardadoIcono.style.backgroundColor = icono.background;
    archivoGuardadoDiv.style.display = 'block';
}

function limpiarMinutaDropzone() {
    var minutaInput = document.getElementById('minutaFile');
    var minutaLabel = document.getElementById('minutaLlamada');
    var minutaDropzone = document.getElementById('minutaDropzone');

    if (minutaInput) {
        minutaInput.value = '';
    }

    if (minutaLabel) {
        minutaLabel.textContent = '';
    }

    if (minutaDropzone) {
        minutaDropzone.classList.remove('is-dragover');
    }
}

function mostrarLlamadas() {

    $("#llamadas-tab").addClass("active");
    $("#llamadas").addClass("show");

    $("#reuniones-tab").removeClass("active");
    $("#reuniones").removeClass("show");

    tipoDoc = 'Llamadas';
    document.getElementById("llamadas").style.display = "block";
    document.getElementById("reuniones").style.display = "none";

    tblLlamadas.draw();
}

function mostrarReuniones() {
    $("#llamadas-tab").removeClass("active");
    $("#llamadas").removeClass("show");

    $("#reuniones-tab").addClass("active");
    $("#reuniones").addClass("show");

    tipoDoc = 'Reuniones';
    document.getElementById("llamadas").style.display = "none";
    document.getElementById("reuniones").style.display = "block";

    tblReuniones.draw();
}

var mensaje = "";
var dateToday = new Date();

function agregarLlamada(folio, tipo, asunto_lugar) {
    $btnSave = document.querySelector("#btnSaveLla");
    contacto_Id_llamada = null;
    mensaje = tipo + " Agregada";
    cancelada = 0;
    save_method = "add";
    if (tipo == "Llamada") {
        $('#tipo').val('Llamadas');
        document.getElementById('divDescripcion').style.display = 'none';
        document.getElementById('divDireccion').style.display = 'block';
        document.getElementById('divContacto').style.display = 'block';
        $('#direccionLabel').text("Tipo de llamada");
        limpiarMinutaDropzone();
    } else {
        $('#tipo').val('Reuniones');
        document.getElementById('divDescripcion').style.display = 'block';
        document.getElementById('divDireccion').style.display = 'none';
        document.getElementById('divContacto').style.display = 'none';
        $('#direccionLabel').text("Tipo de reunión");
        limpiarMinutaDropzone();
    }

    // $("#modal-llamada").addClass("showModal");
    $('#labelAsuntoLugar').text(asunto_lugar);
    $('#asuntoLugar').prop('placeholder', asunto_lugar);
    $("imput[name=_method]").val("POST");
    $("#modal-llamada").modal("show");
    $("#modal-llamada").find("form").trigger("reset");
    $("#tituloLlamada").text("Nueva " + tipo);
    $("#btnBackLla").text("Regresar");
    $("#folio").text("No. " + folio + " - ");
    $("#fecCreacion").val(dateToday.toLocaleString("es-MX"));

    document.getElementById("sbtnCancelarLla").style.display = 'none';
    document.getElementById("sbtnCancelarLla").style.display = 'none';
    document.getElementById("sbtnRealizada").style.display = 'none';
    document.getElementById("sbtnSaveLla").style.display = 'block';

    $btnSave.disabled = false;


    $('#sbtnSaveLla').removeClass('col-3');
    $('#sbtnSaveLla').addClass('col-6');
    $('#sbtnBackLla').removeClass('col-12');
    $('#sbtnBackLla').removeClass('col-3');
    $('#sbtnBackLla').addClass('col-6');

    $('#relacionadoCon').select2().removeClass('select2-green');
    $('#relacionadoCon').select2().addClass('select2-red');

    limpiarDatos();
    limpiarArchivoGuardado();
    validarDatos(reuniones);
}

function editarLlamada(id, panel, reuniones) {
    is_panel = panel;
    panel_reuniones = reuniones
    save_method = "edit";
    $("imput[name=_method]").val("PATCH");
    $.ajax({
        url: serve + "llamadas" + "/" + id + "/edit",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            // console.log(data);
            limpiarDatos();
            contacto_Id_llamada = data.id_contacto;
            if (data.tipo == "Llamadas") {
                idClienteLlamadaChange(data.id_relacionadoCon, contacto_Id_llamada);
                // idContactoChange(data.id_contacto);
                mensaje = "Llamada Actualizada";
                $("#tituloLlamada").text("Editar Llamada");
                $("#labelAsuntoLugar").text("Asunto");
                $('#asuntoLugar').prop('placeholder', 'Asunto');
                document.getElementById('divDescripcion').style.display = 'none';
                document.getElementById('divDireccion').style.display = 'block';
                document.getElementById('divContacto').style.display = 'block';
                $('#direccionLabel').text("Tipo de llamada");
                limpiarMinutaDropzone();

                // $('#isCancelledDiv').addClass('cancelingModalLlamada');
                // $('#isRealizadaDiv').addClass('cancelingModalLlamada');
            } else {
                mensaje = "Reunión Actualizada";
                $("#tituloLlamada").text("Editar Reunión");
                $("#labelAsuntoLugar").text("Lugar");
                $('#asuntoLugar').prop('placeholder', 'Lugar');
                document.getElementById('divDescripcion').style.display = 'block';
                document.getElementById('divDireccion').style.display = 'none';
                document.getElementById('divContacto').style.display = 'none';
                $('#direccionLabel').text("Tipo de reunión");
                limpiarMinutaDropzone();
                // $('#isCancelledDiv').removeClass('cancelingModalLlamada');
                // $('#isRealizadaDiv').removeClass('cancelingModalLlamada');
            }

            // $("#modal-llamada").addClass("showModal");
            $("#modal-llamada form").trigger("reset");

            $("#idLlamadasReuniones").val(data.idLlamadasReuniones);
            $("#asuntoLugar").val(data.asuntoLugar);
            $("#descripcion").val(data.descripcion);
            $("#relacionadoCon").val(data.id_relacionadoCon);
            $("#fecInicioInput").val(dateFormat(data.fecInicio, "dd-mm-yyyy HH:MM"));
            $("#fecFinInput").val(dateFormat(data.fecFin, "dd-mm-yyyy HH:MM"));
            // $("#estado").val(data.id_estado);
            $("#direccion").val(data.id_direccion);
            $("#tipoRepeticion").val(data.id_tipoRepeticion);
            $("#observaciones").val(data.observaciones);
            $("#cancelado").val(data.cancelado);
            $('#tipo').val(data.tipo);
            $('#id_contacto').val(data.id_contacto);
            $('#tel_contacto').val(data.tel_contacto);
            $("#agenteLlamada").html(data.Nombre);
            mostrarArchivoGuardado(data);
            // if (data.prospecto == 1) {
                $("#prospecto").val(data.prospecto);
            // } else {
            // }
                $("#modal-llamada").modal("show");
            $("#btnBackLla").text("Regresar");
            document.getElementById("sbtnCancelarLla").style.display = 'block';
            document.getElementById("sbtnRealizada").style.display = 'block';
            document.getElementById("sbtnSaveLla").style.display = 'block';
            $('#sbtnSaveLla').removeClass('col-6');
            $('#sbtnSaveLla').addClass('col-3');
            $('#sbtnBackLla').removeClass('col-12');
            $('#sbtnBackLla').removeClass('col-6');
            $('#sbtnBackLla').addClass('col-3');

            if (data.realizada == 1) {
                isCancelled.style.display = 'none';
                esRealizada.style.display = 'block';
                nodes_llamadas = document.getElementById('info_general').getElementsByTagName('*');
                for (var i = 0; i < nodes_llamadas.length; i++) {
                    nodes_llamadas[i].disabled = true;
                }
                document.getElementById("sbtnCancelarLla").style.display = 'none';
                document.getElementById("sbtnSaveLla").style.display = 'none';
                document.getElementById("sbtnRealizada").style.display = 'none';
                document.getElementById("notaCancelacionDiv").style.display = 'none';
                $('#sbtnBackLla').addClass('col-12');

            } else if (data.cancelado == 1) {
                esRealizada.style.display = 'none';
                isCancelled.style.display = 'block';
                nodes_llamadas_1 = document.getElementById('info_general').getElementsByTagName('*');
                for (var i = 0; i < nodes_llamadas_1.length; i++) {
                    nodes_llamadas_1[i].disabled = true;
                }
                document.getElementById("sbtnCancelarLla").style.display = 'none';
                document.getElementById("sbtnSaveLla").style.display = 'none';
                document.getElementById("sbtnRealizada").style.display = 'none';
                document.getElementById("notaCancelacionDiv").style.display = 'block';
                $("#notaCancelacion").text(data.motivoCancelacion);
                $('#sbtnBackLla').addClass('col-12');
            } else if (data.realizada == 0 || data.cancelado == 0) {
                esRealizada.style.display = 'none';
                isCancelled.style.display = 'none';
                nodes_llamadas_2 = document.getElementById('info_general').getElementsByTagName('*');
                for (var i = 0; i < nodes_llamadas_2.length; i++) {
                    nodes_llamadas_2[i].disabled = false;
                }
                document.getElementById("sbtnCancelarLla").style.display = 'block';
                document.getElementById("sbtnSaveLla").style.display = 'block';
                document.getElementById("sbtnRealizada").style.display = 'block';
                document.getElementById("notaCancelacionDiv").style.display = 'none';
                $('#sbtnBackLla').removeClass('col-12');
            }

            validarDatos(panel_reuniones);

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
                target: document.getElementById('modal-llamada'),
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
                            $("#modal-llamada form")[0].reset();
                            $("#modal-llamada").modal("hide");
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 3000,
                            });

                            if (is_panel == 1) {
                                cargarCalendario();
                                $("#list_llamadas_reuniones").load(location.href + " #list_llamadas_reuniones");
                            }

                            Msg.fire({
                                title: mensaje,
                            });
                            loaderOut();
                        },
                        error: function() {
                            $("#modal-llamada form")[0].reset();
                            $("#modal-llamada").modal("hide");
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
                            if (tipo == "Llamadas") {
                                tblLlamadas.ajax.reload();
                            } else {
                                tblReuniones.ajax.reload();
                            }
                        },
                    });
                    if (tipo == "Llamadas") {
                        tblLlamadas.ajax.reload();
                    } else {
                        tblReuniones.ajax.reload();
                    }
                }
            });
        }
    });
}

function validarDatos(reuniones) {
    var relacionadoConSelec2 = $('#relacionadoCon').select2("data");
    // console.log(relacionadoConSelec2);
    if (tipo.value == 'Reuniones' || reuniones == 'Reuniones') {

        if (asuntoLugar.value == "" || descripcion.value == "" || relacionadoCon.value == "Cliente" || relacionadoCon.value == "" ||
            fecInicio.value == "" || tipoRepeticion.value == "" || cancelado.value == 1) {
            btnSaveLla.disabled = true;
        } else {
            btnSaveLla.disabled = false;
        }
    } else if (tipo.value == 'Llamadas' || reuniones == 'Llamadas') {
        if (asuntoLugar.value == "" || relacionadoCon.value == "Cliente" || relacionadoCon.value == "" || fecInicio.value == "" ||
            direccion.value == "" || tipoRepeticion.value == "" || id_contacto.value == "Contacto" || tel_contacto.value == "Teléfono" || cancelado.value == 1) {
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
        $('#relacionadoCon').select2().addClass('select2-green');
    } else {
        $('#relacionadoCon').select2().addClass('select2-red');
    }

    if (fecInicio.value == '') {
        fecInicio.style.borderColor = 'red';
    } else {
        fecInicio.style.borderColor = 'green';
    }

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

$("#modal-llamada").draggable({
    handle: "#tituloLlamada",
});

$('#modal-llamada').on('shown.bs.modal', function() {
    $('#asuntoLugar').trigger('focus');
    contacto_Id_llamada = null;
});

function closeMLlamada() {
    loaderOut();
}

function limpiarDatos() {
    isCancelled.style.display = 'none';
    esRealizada.style.display = 'none';
    contacto_Id_llamada = null;
    id_contacto.value = 'Contacto';
    limpiarArchivoGuardado();


    nodes_llamadas = document.getElementById('info_general').getElementsByTagName('*');
    for (var i = 0; i < nodes_llamadas.length; i++) {
        nodes_llamadas[i].disabled = false;
    }

    var nodesIdRelacionado = document.getElementById("relacionadoCon").getElementsByTagName('option');
    for (var i = 0; i < 1; i++) {
        nodesIdRelacionado[i].disabled = true;
    }

    var nodesIdDireccion = document.getElementById("direccion").getElementsByTagName('option');
    for (var i = 0; i < 1; i++) {
        nodesIdDireccion[i].disabled = true;
    }

    var nodesIdRepeticion = document.getElementById("tipoRepeticion").getElementsByTagName('option');
    for (var i = 0; i < 1; i++) {
        nodesIdRepeticion[i].disabled = true;
    }

    var nodesIdContacto = document.getElementById("id_contacto").getElementsByTagName('option');
    for (var i = 0; i < 1; i++) {
        nodesIdContacto[i].disabled = true;
    }

    var nodesTelContacto = document.getElementById("tel_contacto").getElementsByTagName('option');
    for (var i = 0; i < 1; i++) {
        nodesTelContacto[i].disabled = true;
    }

    document.getElementById("notaCancelacionDiv").style.display = 'none';
}

$('#modal-llamada').submit(function(evento) {
    $btnSave.disabled = true;
    loaderON();
});

$(function() {
    $("#formLlamada")
        .validator()
        .on("submit", function(e) {
            e.preventDefault();
            if (e.isDefaultPrevented()) {
                $btnSave = document.querySelector("#btnSaveLla");
                $btnSave.disabled = true;
                var id = $("#idLlamadasReuniones").val();
                var tipo = $('#tipo').val();
                if (save_method == "add") {
                    url = serve + "llamadas";
                    method = "POST";
                } else {
                    url = serve + "llamadas/" + id;
                    method = "PUT";
                }
                var formData = new FormData(document.getElementById('formLlamada'));
                var minutaFile = document.getElementById('minutaFile');
                if (minutaFile && minutaFile.files.length > 0) {
                    formData.set('minutaFile', minutaFile.files[0]);
                }
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function() {
                        $("#formLlamada").trigger("reset");
                        $("#modal-llamada").modal("hide");
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

                        if (tipo == "Llamadas") {
                            tblLlamadas.ajax.reload();
                        } else {
                            tblReuniones.ajax.reload();
                        }

                    },
                    error: function() {
                        $("#modal-llamada form").trigger("reset");
                        $("#modal-llamada").modal("hide");
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        if (is_panel == 1) {
                            cargarCalendario();
                            $("#list_llamadas_reuniones").load(location.href + " #list_llamadas_reuniones");
                        }

                        Msg.fire({
                            title: mensaje,
                        });
                        if (tipo == "Llamadas") {
                            tblLlamadas.ajax.reload();
                        } else {
                            tblReuniones.ajax.reload();
                        }
                    },
                });
                return false;
            }
        });
});

function realizada(id) {

    if (id == null) {
        id = $('#idLlamadasReuniones').val();
    }

    if (tipoDoc == 'Llamadas') {
        mensaje = "Llamada Realizada";
        title = tipoDoc;
    } else {
        mensaje = "Reunión Realizada";
        title = tipoDoc;
    }

    is_panel = tipoDoc;

    Swal.fire({
        title: title,
        text: "Marcar como Realizada?",
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
                url: serve + "realizada",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    _token: CSRF_TOKEN,
                },
                success: function(data) {
                    $("#modal-llamada form").trigger("reset");
                    $("#modal-llamada").modal("hide");

                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    if (is_panel == 1) {
                        cargarCalendario();
                        $("#list_llamadas_reuniones").load(location.href + " #list_llamadas_reuniones");
                    }

                    Msg.fire({
                        title: mensaje,
                    });
                    tblLlamadas.ajax.reload();
                    tblReuniones.ajax.reload();
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
                        title: "Ocurrió un error",
                    });
                },
            });
        }
    });
}

function idClienteLlamadaChange(id, contacto_Id_llamada) {
    var contactos = $('#id_contacto');
    var telefonos = $('#tel_contacto');
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
                contactos.append("<option >Prospecto</option>");
                telefonos.append("<option selected disabled>Teléfono</option>");
                telefonos.append("<option >Prospecto</option>");

                $(r).each(function(i, v) {
                    var $option = $("<option></option>", {
                        text: v.Nombre,
                        value: v.idContacto,
                    });
                    if (contacto_Id_llamada == v.idContacto) {
                        $option.attr("selected", "selected");
                    }
                    contactos.append($option);
                });

                idContactoChange(contacto_Id_llamada);
                validarDatos();
            },
            error: function(r) {
                Swal.fire("Ocurrio un error en el servidor ..");
            },
        });
    } else {
        contactos.find("option").remove();
    }
}

function idContactoChange(id) {
    var telefonos = $('#tel_contacto');
    var contacto = id;

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
                telefonos.append("<option>Prospecto</option>");

                $(r).each(function(i, v) {
                    var $option = $("<option></option>", {
                        text: v.Telefonos,
                        value: v.Telefonos,
                    });
                    if (id == v.idContacto) {
                        $option.attr("selected", "selected");
                    }
                    telefonos.append($option);
                });

                validarDatos();
            },
            error: function(r) {
                Swal.fire("No se obtuvieron los telefonos...");
            },
        });
    } else {
        telefonos.find("option").remove();
    }
}