serve = base_path;

$(document).ready(function() {
    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";

    const FROM_PATTERN_SHORT = "YYYY-MM-DD";
    const TO_PATTERN_SHORT = "DD/MM/YYYY";

    tblOportunidades = $("#tblOportunidades").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "idOportunidad",
        select: true,
        scrollX: true,
        scrollCollapse: true,
        // fixedColumns: {
        //     leftColumns: 3,
        // },
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "api/oportunidades",
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
                title: "Oportunidades | CANDY CRM",
                text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
                exportOptions: {
                    orthogonal: "export",
                    columns: "thead th:not(.noExport)",
                    modifer: {
                        serverSide: false,
                        page: "all",
                    },
                },
                filename: "Oportunidades",
                autoFilter: true,
                sheetName: "Lista Oportunidades",
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
        columnDefs: [{
                targets: [1, -1],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
            {
                targets: [6],
                render: $.fn.dataTable.render.moment(
                    FROM_PATTERN_SHORT,
                    TO_PATTERN_SHORT
                ),
            },
            {
                targets: 5,
                render: function(data, type, row, meta) {
                    if (row.Cancelada == "1" || row.idEstatus == 10) {
                        if (data == null || data == "") {
                            return '<span class="text-red">SIN ASIGNAR</span>';
                        } else {
                            return '<span class="text-red">' + data + "</span>";
                        }
                    } else if (row.idEstatus == 12) {
                        return '<span class="text-green">' + data + "</span>";
                    } else {
                        if (
                            row.inputRol == 10 ||
                            row.inputRol == 11 ||
                            row.inputRol == 8 ||
                            row.inputRol == 1
                        ) {
                            var $select = $(
                                '<select id="cotizadores_grid_' +
                                row.idOportunidad +
                                '" class="form-control sltTblOpor" style="font-size: .7rem !important;" ><option selected disabled>SIN ASIGNAR</option></select>'
                            );
                            $.each(cotizador, function(k, v) {
                                var $option = $("<option></option>", {
                                    text: v,
                                    value: k,
                                });
                                if (data == v) {
                                    $option.attr("selected", "selected");
                                }
                                $select.append($option);
                            });
                            return $select.prop("outerHTML");
                        } else {
                            if (data == null || data == "") {
                                return "<span>SIN ASIGNAR</span>";
                            } else {
                                return "<span>" + data + "</span>";
                            }
                        }
                    }
                },
            },
        ],
        columns: [{
                data: "idOportunidad",
                name: "idOportunidad",
                width: "1rem",
                title: "ID",
                searchable: true,
                className: "dt-body-center text-center",
            },
            {
                data: "fecCreacion",
                name: "fecCreacion",
                width: "2rem",
                className: "dt-body-center text-center",
                title: "Creación",
            },
            {
                data: "ST_Oport",
                render: function(data, type, row) {
                    if (data == "ATRASADO") {
                        return '<div class="rounded" style="background-color: #ec7b75"><strong class="text-white">' + data + '</strong></div>';
                    } else if (data == "A TIEMPO") {
                        return '<div class="rounded" style="background-color: #84C876"><strong class="text-white">' + data + '</strong></div>';
                    } else if (data == "CANCELADO") {
                        return '<div class="rounded" style="background-color: #EE0E12"><strong class="text-white">' + data + '</strong></div>';
                    } else if (data == "SIN INICIAR") {
                        return '<div class="rounded" style="background-color: #369BDF"><strong class="text-white">' + data + '</strong></div>';
                    } else {
                        return data;
                    }
                },
                width: "2rem",
                className: "dt-body-center text-center",
                title: "ST Oport",
            },
            {
                data: "fecTope",
                name: "fecTope",
                width: "2rem",
                className: "dt-body-center text-center",
                title: "Fec Tope",
            },
            {
                data: "nomOportunidad",
                name: "nomOportunidad",
                render: function(data, type, row) {
                    if ($.trim(row.inputEditar) === "Editar") {
                        if (row.Cancelada == "1") {
                            return (
                                '<a href="oportunidades/' +
                                row.idOportunidad +
                                '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-red text-uppercase">' +
                                data +
                                "</a>"
                            );
                        } else {
                            return (
                                '<a href="oportunidades/' +
                                row.idOportunidad +
                                '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-uppercase">' +
                                data +
                                "</a>"
                            );
                        }
                    } else {
                        return data;
                    }
                },
                width: "5rem",
                title: "Nombre",
            },
            {
                data: "Atendido",
                name: "Atendido",
                width: "2rem",
                title: "Atendido",
                className: "dt-body-left text-left",
            },
            {
                data: "fecPrevCiere",
                name: "fecPrevCiere",
                width: "1rem",
                className: "dt-body-center text-center",
                title: "Prev Cierre",
            },
            {
                data: "numOrdenMTC",
                name: "numOrdenMTC",
                title: "OP MTC",
                width: "1rem",
                className: "dt-body-center text-center",
            },
            {
                data: "razonsocial",
                name: "razonsocial",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 2 ||
                        row.inputRol == 11 ||
                        row.inputRol == 8
                    ) {
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
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) // ||
                            // $.trim(row.LoginCustomer) == $.trim(row.inputLogin)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                return (
                                    '<a href="clientes/' +
                                    row.ClienteINT +
                                    '/edit" title="Editar Cliente">' +
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
                width: "4rem",
                title: "Cliente",
            },
            {
                data: "Agente",
                // name: "Agente",
                title: "Asignado",
                width: "2rem",
                className: "dt-body-left text-left",
            },
            {
                data: "desTipoOportundidad",
                name: "desTipoOportundidad",
                title: "Tipo",
                width: "2rem",
                className: "dt-body-center text-center",
            },
            {
                data: "descProbabilidad",
                name: "descProbabilidad",
                title: "Prob.",
                width: "2rem",
                className: "dt-body-center text-center",
            },
            {
                data: "Valor",
                // render: $.fn.dataTable.render.number(',', '.', 2, '$ '),
                render: function(data, type, row) {
                    return "$" + data;
                },
                name: "Valor",
                title: "Valor",
                width: "2rem",
                className: "dt-body-center text-right",
            },
            {
                data: "descEstatus",
                render: function(data, type, row) {
                    if (row.Cancelada == "1") {
                        return '<strong class="text-red">Cancelada</strong>';
                    } else {
                        return data;
                    }
                },
                // name: "descEStatus",
                title: "ST",
                width: "1rem",
                className: "dt-body-center text-center",
            },
            {
                data: "valorEstatus",
                render: function(data, type, row) {
                    if (row.Cancelada == 1) {
                        return (
                            '<div class="progress rounded" style="width: 8rem" title="' +
                            row.descEstatus +
                            '"><div class="progress-bar text-bold" role="progressbar" style="width: 100%; background-color: red; color: #000" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>'
                        );
                    } else if (row.idEstatus == 10) {
                        return (
                            '<div class="progress rounded" style="width: 8rem" title="' +
                            row.descEstatus +
                            '"><div class="progress-bar text-bold" role="progressbar" style="width: 100%; background-color: ' +
                            row.colorEstatus +
                            '; color: #000" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>'
                        );
                    } else {
                        return (
                            '<div class="progress rounded" style="width: 8rem" title="' +
                            row.descEstatus +
                            '"><div class="progress-bar text-bold" role="progressbar" style="width: ' +
                            row.valorEstatus +
                            "%; background-color: " +
                            row.colorEstatus +
                            '; color: #000" aria-valuenow="' +
                            row.valorEstatus +
                            '" aria-valuemin="0" aria-valuemax="100">' +
                            row.valorEstatus +
                            "%</div></div>"
                        );
                    }
                },
                name: "valorEstatus",
                title: "Progreso",
            },
            {
                data: "fecOrdenConcluida",
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
                name: "fecOrdenConcluida",
                title: "Concluida",
                width: "3rem",
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

            if ($("#searchGral").val() != '') {

                loaderON();
                var texto = $("#searchGral").val();

                filterColumn(13, texto);
                $.fn.dataTable.ext.search.pop();
                filterDate(1, minDate, maxDate);
                // filterGlobal(texto);
            } else {
                loaderOut();
            }

        },
    });

    $("#tblOportunidades")
        .DataTable()
        .on("draw", function() {
            ajustarTablas();
        });

});

let myVar = setInterval(autoReloadPage, 60000);

function autoReloadPage() {
    tblOportunidades.ajax.reload();
}

var mensaje = "";
var icono = "";
$(function() {
    $("#formOporCreate form")
        .validator()
        .on("submit", function(e) {
            if (!e.isDefaultPrevented()) {
                mensaje = "Oportunidad Creada";
                icono = "success";
                regresar();
            } else {
                mensaje = "Faltan Datos";
                icono = "warning";
            }
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: icono,
                showConfirmButton: false,
                timer: 3000,
            });
            Msg.fire({
                title: mensaje,
            });
        });

    $("#formOporEdit form")
        .validator()
        .on("submit", function(e) {
            if (!e.isDefaultPrevented()) {
                mensaje = "Oportunidad Actualizada";
                icono = "success";
                regresar();
            } else {
                mensaje = "Faltan Datos";
                icono = "warning";
            }
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: icono,
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: mensaje,
            });
        });
});

function CancelarOportunidad(id) {
    mensaje = "Oportunidad Cancelada";
    id_suario = document.getElementById("idUsuario");
    Swal.fire({
        title: "Oportunidades",
        text: "Cancelar la Oportunidad Seleccionada?",
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
            const { value: text } = Swal.fire({
                input: "textarea",
                inputLabel: "Motivo Cancelación",
                inputPlaceholder: "Captura el motivo de Cancelación de la Oportunidad",
                inputAttributes: {
                    "aria-label": "Captura el motivo de Cancelación de la Oportunidad",
                },
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Guardar',
            }).then((result) => {
                if (result.isConfirmed) {
                    // console.log(result);
                    if (result.value != "") {
                        $.ajax({
                            url: serve + "cancelarOportunidad",
                            type: "PATCH",
                            data: {
                                _method: "PATCH",
                                id: id,
                                id_usuario: id_suario.value,
                                motivoCancelacion: result.value,
                                _token: CSRF_TOKEN,
                            },
                            success: function(data) {
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
                            error: function() {
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
                                window.location.href = serve + "oportunidades";
                            },
                        });
                    } else {
                        Swal.fire("¡Debes capturar el motivo!");
                    }
                }
            });

            //   if (text) {
            //     Swal.fire(text)
            //   }
        }
    });
}

function cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario) {
    mensaje = "Oportunidad Actualizada";
    // console.log(id_usuario);
    Swal.fire({
        title: "Oportunidades",
        text: "Actualizar 'Atendido por' de la Oportunidad Seleccionada?",
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
                url: serve + "cambiarCotizador",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    cotizador: cotizador,
                    id_usuario: id_usuario,
                    _token: CSRF_TOKEN,
                },
                success: function(data) {
                    // console.log(data);
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
                    window.location.href = "oportunidades";
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
        } else {
            $("#cotizadores_grid_" + id)
                .find("option")
                .attr("selected", false);
            $(
                "#cotizadores_grid_" +
                id +
                '  option:contains("' +
                nombre_cotizador +
                '")'
            ).attr("selected", true);
            window.location.href = "oportunidades";
        }
    });
}

$(window).resize(function() {
    ajustarTablaEntregasOport();
});

function ajustarTablaEntregasOport() {
    var height = window.innerHeight;

    if (height >= 320 && height <= 651) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "45vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "49vh");
    } else if (height >= 769 && height <= 992) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "53vh");
    } else if (height >= 993 && height <= 1024) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "53vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "54vh");
    } else {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "55vh");
    }
}

function habilitarCantidad(idSucursalEnt, check) {

    if (check) {
        $("#cantidadSuc" + idSucursalEnt).prop('disabled', false).focus();
    } else {
        $("#cantidadSuc" + idSucursalEnt).prop('disabled', true);
        $("#cantidadSuc" + idSucursalEnt).val(0);
    }

}

function agregarCantidad() {

    cantidad = $("#cantidadSucursal").val();
    idSucursalEnt = $("#idSucursal").val();
    idOportunidad = $("#idOportunidad").val();
    idCliente = $("#idClienteEnt").val();
    totalCantidadOP = $("#totalCantidadOP").val();
    fecEntregaInput = $("#fecEntregaInput").val();

    if (idSucursalEnt == "Sucursal" || idSucursalEnt == 0 || idSucursalEnt == null) {
        Swal.fire('¡Selecciona una Sucursal!')
    } else if (cantidad == "cantidad" || cantidad == "") {
        Swal.fire('¡Captura la cantidad!')
    } else if (fecEntregaInput == "Fecha Entrega" || fecEntregaInput == "") {
        Swal.fire('¡Captura la Fecha de Entrega!')
    } else {
        $.ajax({
            url: serve + "agregarCantidad",
            type: "POST",
            data: {
                _method: "POST",
                idCliente: idCliente,
                idOportunidad: idOportunidad,
                idSucursalEnt: idSucursalEnt,
                cantidad: cantidad,
                fecEntregaInput: fecEntregaInput,
                totalCantidadOP: totalCantidadOP,
                _token: CSRF_TOKEN,
            },
            success: function(data) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: 'Cantidad Agregada',
                });

                limpiarCampos();
            },
            error: function(data) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: 'La cantidad a entregar supera la cantidad de la OP',
                });

                limpiarCampos();
            },
        });
    }
}

function limpiarCampos() {
    $("#cantidadSucursal").val(null);
    $("#idSucursal").val('Sucursal');
    $("#fecEntregaInput").val('Fecha Entrega');

    tableEntregasOportunidades.ajax.reload();
}

function eliminarCantidad(id) {
    mensaje = "Cantidad Eliminada";
    Swal.fire({
        title: "Entregas Oportunidad",
        text: "Eliminar la cantidad Seleccionada?",
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
                url: serve + "eliminarCantidad",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    _token: CSRF_TOKEN,
                },
                success: function(data) {
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
                    tableEntregasOportunidades.ajax.reload();
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