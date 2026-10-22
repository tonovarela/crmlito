var minDate = moment().startOf("month").format("YYYY-MM");
var maxDate = moment().endOf("month").format("YYYY-MM-DD");
var evento = $("#evento").text();
var dateToday = new Date();
var filtrosEstatus = "";
var sumafiltrosEstatus = 0;

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    var min = minDate;
    var max = maxDate;
    var date = new Date(data[1]);

    if (
        (min === null && max === null) ||
        (min === null && date <= max) ||
        (min <= date && max === null) ||
        (min <= date && date <= max)
    ) {
        return true;
    }
    return false;
});

$(document).ready(function () {
    // var preregistroChk = document.getElementById("preregistroChk");
    // $('#searchGral').keyup(function() {
    //     $("#tblOportunidades").DataTable().draw();
    // });
    $("#numOrdenMTC").prop("disabled", true);
    $("#idCotizacionAutorizada").prop("disabled", true);
    $("#idMotivoPerdida").prop("disabled", true);

    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    if (window.sessionStorage) {

        var evento_sesion = sessionStorage.getItem("evento");

        console.log(evento_sesion);

        sessionStorage.removeItem("evento");

    } else {
        throw new Error('Tu Browser no soporta sessionStorage!');
    }

    tableElePreOpor = $("#tableElePreOpor").DataTable({
        language: {
            url: serve + "js/spanish-DT.json",
        },

        dom: '<"top"i>rt<"bottom"><"clear">',
    });

    var id = $("#idOportunidad").val();
    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";

    const FROM_PATTERN_SHORT = "YYYY-MM-DD";
    const TO_PATTERN_SHORT = "DD/MM/YYYY";

    $("#tblOporRetenidas").DataTable({
        processing: true,
        serverSide: true,
        select: true,
        scrollX: true,
        searchable: false,
        orderable: false,
        scrollCollapse: true,
        ajax: {
            url: serve + "api/oportRetenidas/" + id,
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
        },],
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

    tblTEntrega = $("#tblTEntrega").DataTable({
        processing: true,
        serverSide: true,
        select: false,
        hovered: false,
        scrollX: true,
        searchable: false,
        order: [
            [1, "desc"]
        ],
        scrollCollapse: true,
        // rowId: "idTiempoEntregaOprotunidad",
        ajax: {
            url: serve + "api/tiempoEntrega/" + id,
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columnDefs: [{
            targets: [0, 1, 2, 3, 4, 5, 6, 7],
            orderable: false,
        },
        {
            targets: [5, 6],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
        },
        {
            targets: [4],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
        },
        ],
        rowCallback: function (row, data) {
            if (data.estatus == 0) {
                $("td", row).css("color", " red");
                $('td:eq(0)', row).addClass('fas fa-times-circle text-center text-md');
                $('td:eq(0)', row).attr('title', 'Inactiva');
            } else {
                if (data.fecArchivoAutorizado != null) {
                    if (
                        moment(data.FechaAutoriza, "YYYY-MM-DD") <
                        moment(data.fecArchivoAutorizado, "YYYY-MM-DD")
                    ) {
                        $('td:eq(0)', row).addClass('fas fa-copyright text-center text-yellow text-md');
                        $('td:eq(0)', row).attr('title', 'Caducada');
                    } else {
                        $("td", row).css("font-weight", "bold");
                        $('td:eq(0)', row).addClass('fas fa-check-circle text-center text-md text-green');
                        $('td:eq(0)', row).attr('title', 'Activa');
                    }
                } else {
                    if (moment(data.FechaAutoriza, "YYYY-MM-DD") < moment()) {
                        $('td:eq(0)', row).addClass('fas fa-copyright text-center text-yellow text-md');
                        $('td:eq(0)', row).attr('title', 'Caducada');
                    } else {
                        $("td", row).css("font-weight", "bold");
                        $('td:eq(0)', row).addClass('fas fa-check-circle text-center text-md text-green');
                        $('td:eq(0)', row).attr('title', 'Activa');
                    }
                }
            }
        },
        columns: [{
            data: 'icon',
            render: function (data) {
                return ('<i></i>');
            },
        },
        {
            data: "idTiempoEntregaOprotunidad",
            render: function (data, type, row) {
                if (row.Comentarios == null) {
                    return '<span class="text-uppercase" title=" ">' + data + "</span>";
                } else {
                    return (
                        '<span class="text-uppercase" title="' +
                        "COMENTARIOS: " +
                        row.Comentarios.toUpperCase() +
                        '">' +
                        data +
                        "</span>"
                    );
                }
            },
            title: "id",
            className: "dt-body-center text-left",
        },
        {
            data: "Autorizo",
            render: function (data, type, row) {
                if (row.Comentarios == null) {
                    return '<span class="text-uppercase" title=" ">' + data + "</span>";
                } else {
                    return (
                        '<span class="text-uppercase" title="' +
                        "COMENTARIOS: " +
                        row.Comentarios.toUpperCase() +
                        '">' +
                        data +
                        "</span>"
                    );
                }
            },
            title: "Autorizó",
        },
        {
            data: "DiasEntrega",
            name: "DiasEntrega",
            title: "Días Entrega",
            className: "dt-body-center text-center",
        },
        {
            data: "fecRegistro",
            name: "fecRegistro",
            title: "Fecha Registro",
            className: "dt-body-center text-center",
        },
        {
            data: "FechaAutoriza",
            name: "FechaAutoriza",
            title: "Fecha Archivo",
            className: "dt-body-center text-center",
        },
        {
            data: "FechaEntrega",
            name: "FechaEntrega",
            title: "Fecha Entrega",
            className: "dt-body-center text-center",
        },
        {
            data: "Comentarios",
            render: function (data) {
                if (data == null) {
                    return "<span></span>";
                } else {
                    return (
                        '<span class="text-uppercase" title="' +
                        data.toUpperCase() +
                        '">' +
                        data +
                        "</span>"
                    );
                }
            },
            title: "Comentarios",
        },
        ],
    });

    ///----------------------------------------------------------------------------------------------------------------------------///
    ////-------------------------------------------------GRID TBL_OPORTUNIDADES----------------------------------------------------///
    ///-------Ing. Brigette Gutiérrez.---------------------------------------------------------------------------------------------///
    ///----------------------------------------------------------------------------------------------------------------------------///

    $('#preregistroChk').on('change', function () {
        aplicarFiltroEstatus(preregistroChk.checked);
    });

    $('#SolCotChk').on('change', function () {
        aplicarFiltroEstatus(SolCotChk.checked);
    });

    $('#listoCotChk').on('change', function () {
        aplicarFiltroEstatus(listoCotChk.checked);
    });

    $('#cotizandoChk').on('change', function () {
        aplicarFiltroEstatus(cotizandoChk.checked);
    });

    $('#revisionChk').on('change', function () {
        aplicarFiltroEstatus(revisionChk.checked);
    });

    $('#esperaVoBoChk').on('change', function () {
        aplicarFiltroEstatus(esperaVoBoChk.checked);
    });

    $('#listoPreChk').on('change', function () {
        aplicarFiltroEstatus(listoPreChk.checked);
    });

    $('#preTerminadoChk').on('change', function () {
        aplicarFiltroEstatus(preTerminadoChk.checked);
    });

    $('#finalizadoChk').on('change', function () {
        aplicarFiltroEstatus(finalizadoChk.checked);
    });

    $('#canceladoChk').on('change', function () {
        aplicarFiltroEstatus(canceladoChk.checked);
    });

    $("#todosChk").prop("checked", false);

    $('#todosChk').on('change', function () {
        if (todosChk.checked == true) {

            preregistroChk.checked = true;
            SolCotChk.checked = true;
            listoCotChk.checked = true;
            cotizandoChk.checked = true;
            revisionChk.checked = true;
            esperaVoBoChk.checked = true;
            listoPreChk.checked = true;
            preTerminadoChk.checked = true;
            finalizadoChk.checked = true;
            canceladoChk.checked = true;
            sumafiltrosEstatus = 10;

            aplicarFiltroOportunidades(preregistroChk.checked, SolCotChk.checked, listoCotChk.checked,
                cotizandoChk.checked, revisionChk.checked, esperaVoBoChk.checked,
                listoPreChk.checked, preTerminadoChk.checked, finalizadoChk.checked, canceladoChk.checked);

        } else {
            preregistroChk.checked = false;
            SolCotChk.checked = false;
            listoCotChk.checked = false;
            cotizandoChk.checked = false;
            revisionChk.checked = false;
            esperaVoBoChk.checked = false;
            listoPreChk.checked = false;
            preTerminadoChk.checked = false;
            finalizadoChk.checked = false;
            canceladoChk.checked = false;
            sinFiltrosOportunidades();
            sumafiltrosEstatus = 0;
        }
    });

    let myVar = setInterval(autoReloadPage, 600000);

    function autoReloadPage() {
        aplicarFiltroOportunidades(preregistroChk.checked, SolCotChk.checked, listoCotChk.checked, cotizandoChk.checked, revisionChk.checked, esperaVoBoChk.checked, listoPreChk.checked, preTerminadoChk.checked, finalizadoChk.checked, canceladoChk.checked);
        // tblOportunidades.ajax.reload();
    }

    function aplicarFiltroEstatus(check) {

        if (check == true) {
            sumafiltrosEstatus = sumafiltrosEstatus + 1;
            if (sumafiltrosEstatus == 10) {
                todosChk.checked = true;
            } else if (sumafiltrosEstatus < 10) {
                todosChk.checked = false;
            }
        } else {
            if (sumafiltrosEstatus == 0) {
                sumafiltrosEstatus = 0;
                todosChk.checked = false;
                sinFiltrosOportunidades();
            } else {
                sumafiltrosEstatus = sumafiltrosEstatus - 1;
            }
        }

        if (sumafiltrosEstatus <= 0) {
            sinFiltrosOportunidades();
        } else if (sumafiltrosEstatus > 0 && sumafiltrosEstatus <= 10) {
            todosChk.checked = false;
            aplicarFiltroOportunidades(preregistroChk.checked, SolCotChk.checked, listoCotChk.checked,
                cotizandoChk.checked, revisionChk.checked, esperaVoBoChk.checked,
                listoPreChk.checked, preTerminadoChk.checked, finalizadoChk.checked, canceladoChk.checked);
        }

    }

    sinFiltrosOportunidades();

    function sinFiltrosOportunidades() {
        $("#tblOportunidades").DataTable({
            destroy: true,
            pageResize: false,
            processing: true,
            scrollX: true,
            scrollCollapse: true,
            deferRender: true,
            language: {
                url: serve + "js/spanish-DT.json",
            },
            dom: '<"top">rt<"bottom"><"clear">',
        });
    }

    function aplicarFiltroOportunidades(preregistroChk, solCotChk, listoCotChk, cotizandoChk, revisionChk, esperaVoBoChk, listoPreChk, preTerminadoChk, finalizadoChk, canceladoChk) {
        tblOportunidades = $("#tblOportunidades").DataTable({
            destroy: true,
            pageResize: true,
            searchable: true,
            processing: true,
            serverSide: true,
            rowId: "idOportunidad",
            select: false,
            scrollX: true,
            scrollCollapse: true,
            stateSave: true,
            deferRender: true,
            order: [
                [0, "desc"]
            ],
            ajax: {
                url: serve + "api/oportunidades",
                type: "GET",
                dataType: "json",
                data: {
                    preregistroEstatus: preregistroChk,
                    solCotEstatus: solCotChk,
                    listoCotEstatus: listoCotChk,
                    cotizandoEstatus: cotizandoChk,
                    revisionEstatus: revisionChk,
                    esperaVoBoEstatus: esperaVoBoChk,
                    listoPreEstatus: listoPreChk,
                    preTerminadoEstatus: preTerminadoChk,
                    finalizadoEstatus: finalizadoChk,
                    canceladoEstatus: canceladoChk,
                },
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
                targets: [1, 15],
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
                targets: [18],

                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
            },
            {
                targets: 5,
                render: function (data, type, row, meta) {
                    if (row.Cancelada == "1" || row.idEstatus == 10) {
                        if (data == null || data == "") {
                            return '<span class="text-red">SIN ASIGNAR</span>';
                        } else {
                            return (
                                '<span class="text-red" title="' +
                                data +
                                '">' +
                                data +
                                "</span>"
                            );
                        }
                    } else if (row.idEstatus == 12) {
                        return (
                            '<span class="text-green" title="' +
                            data +
                            '">' +
                            data +
                            "</span>"
                        );
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
                                '" class="form-control sltTblOpor" title="' +
                                data +
                                '" style="font-size: .7rem !important;" ><option selected disabled>SIN ASIGNAR</option></select>'
                            );
                            $.each(cotizador, function (k, v) {
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
                data: "folio",
                render: function (data, type, row) {
                    if (row.Maquila == 'on') {
                        return ('<div class="rounded bg-primary" title="Con Maquila"><strong>' + data + '</strong></div>');
                    } else {
                        return ('<div><strong>' + data + '</strong></div>');
                    }
                },
                width: "1rem",
                title: "Folio",
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
                render: function (data, type, row) {
                    if (data == "ATRASADO") {
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
                    } else if (data == "CANCELADO") {
                        return (
                            '<div class="rounded" style="background-color: #EE0E12"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
                    } else if (data == "SIN INICIAR") {
                        return (
                            '<div class="rounded" style="background-color: #369BDF"><strong class="text-white">' +
                            data +
                            "</strong></div>"
                        );
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
                render: function (data, type, row) {
                    if ($.trim(row.inputEditar) === "Editar") {
                        if (row.Cancelada == "1") {
                            return (
                                '<a title="Editar Oportunidad" onclick="editarOportunidad(' + row.idOportunidad + '), loaderON()" class="text-red text-uppercase cursor-pointer">' +
                                data +
                                "</a>"
                            );
                            // return (
                            //     '<a href="oportunidades/' +
                            //     row.idOportunidad +
                            //     '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-red text-uppercase">' +
                            //     data +
                            //     "</a>"
                            // );
                        } else {
                            return (
                                '<a title="Editar Oportunidad" onclick="editarOportunidad(' + row.idOportunidad + '), loaderON()" class="text-uppercase cursor-pointer">' +
                                data +
                                "</a>"
                            );
                            // return (
                            //     '<a href="oportunidades/' +
                            //     row.idOportunidad +
                            //     '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-uppercase">' +
                            //     data +
                            //     "</a>"
                            // );
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
                render: function (data, type, row) {
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
                render: function (data, type, row) {
                    return "$" + data;
                },
                name: "Valor",
                title: "Valor",
                width: "2rem",
                className: "dt-body-center text-right",
            },
            {
                data: "descEstatus",
                render: function (data, type, row) {
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
                render: function (data, type, row) {
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
                render: function (data, type, row) {
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
            {
                data: "solicitudFechaEntrega",
                render: function (data, type, row) {
                    if (row.archivoAutorizado == 1) {
                        if (row.FechaEntrega != null || row.DiasEntrega != null) {
                            return '<span class="bg-green rounded-circle pl-2 pr-2 pt-1 pb-1" title="Asignada"><i class="text-bold">A</i></span>';
                        } else if (data == 1) {
                            return '<span class="bg-primary rounded-circle pl-2 pr-2 pt-1 pb-1" title="Solicitada"><i class="text-bold">S</i></span>';
                        } else {
                            return '<span title="Sin Status"><i class="fas fa-times-circle text-lg text-red"></i></span>';
                        }
                    } else {
                        if (
                            dateFormat(row.FechaAutoriza, "yyyy-mm-dd") <
                            dateFormat(dateToday, "yyyy-mm-dd")
                        ) {
                            return '<span class="bg-warning rounded-circle pl-2 pr-2 pt-1 pb-1" title="Caducado por falta de archivo autorizado"><i class="text-bold text-white">C</i></span>';
                        } else if (row.FechaEntrega != null || row.DiasEntrega != null) {
                            return '<span class="bg-green rounded-circle pl-2 pr-2 pt-1 pb-1" title="Asignada"><i class="text-bold">A</i></span>';
                        } else if (data == 1) {
                            return '<span class="bg-primary rounded-circle pl-2 pr-2 pt-1 pb-1" title="Solicitada"><i class="text-bold">S</i></span>';
                        } else {
                            return '<span title="Sin Status"><i class="fas fa-times-circle text-lg text-red"></i></span>';
                        }
                    }
                },
                title: "STFecEnt",
                className: "dt-body-center text-center",
            },
            {
                data: "archivoAutorizado",
                render: function (data, type, row) {
                    if (data == 1) {
                        // return (row.fecArchivoAutorizado + ' / ' + row.DiasEntrega);
                        return '<span><i class="fas fa-check-circle text-lg text-green"></i></span>';
                    } else {
                        return '<span><i class="fas fa-times-circle text-lg text-red"></i></span>';
                    }
                },
                title: "ArchAut",
                className: "dt-body-center text-center",
            },
            {
                data: "FechaEntrega",
                render: function (data, type, row) {
                    if (row.archivoAutorizado == 1) {
                        if (data != null) {
                            return data;
                        } else if (row.DiasEntrega != null) {
                            return row.DiasEntrega + " DIAS";
                        } else {
                            return "";
                        }
                    } else {
                        if (
                            dateFormat(row.FechaAutoriza, "yyyy-mm-dd") <
                            dateFormat(dateToday, "yyyy-mm-dd")
                        ) {
                            return '<span title="Solicitar nueva fecha de entrega"><i class="text-bold">Solicitar nueva fecha de entrega</i></span>';
                        } else {
                            if (data != null) {
                                return data;
                            } else if (row.DiasEntrega != null) {
                                return row.DiasEntrega + " DIAS";
                            } else {
                                return "";
                            }
                        }
                    }
                },
                title: "Fecha Entrega",
                width: "3rem",
                className: "dt-body-center text-center",
            },
            ],
            pageLength: 50,
            lengthMenu: [
                [50, 100, 150, 200, -1],
                [50, 100, 150, 200, 'TODOS'],
            ],
            initComplete: function (settings, json) {
                evento = $("#evento").text();
                console.log(evento);
                if (evento != "") {
                    loaderON();
                    var texto = $("#searchGral").val();
                    if ($("#searchGral").val() != "") {
                        loaderON();
                        $("#evento").text("");
                        filterColumn(13, texto.trim());
                        $.fn.dataTable.ext.search.pop();
                    }
                } else {
                    // var texto = $("#searchGral").val();
                    filterGlobal(evento.trim());
                }
            },
        });
    }

    $("#tblOportunidades tbody").on("change", "td", function () {
        cotizador = $(this).find(":selected").val();
        data = tblOportunidades.row($(this).parents("tr")).data();
        id = data["idOportunidad"];
        nombre_cotizador = data["Atendido"];
        id_usuario = data["Id_Usuario"];
        cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario);
        aplicarFiltroOportunidades(preregistroChk.checked, SolCotChk.checked, listoCotChk.checked, cotizandoChk.checked, revisionChk.checked, esperaVoBoChk.checked, listoPreChk.checked, preTerminadoChk.checked, finalizadoChk.checked, canceladoChk.checked);
    });

    selectRow("tblOportunidades");

    $("#tblOportunidades")
        .DataTable()
        .on("draw", function () {
            ajustarTablas();
        });

    // $('#tblOportunidades').ajaxComplete(loaderOut());

    tableEntregasOportunidades = $("#tableEntregasOportunidades").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        scrollY: true,
        select: true,
        pageResize: true,
        order: [
            [0, "asc"]
        ],
        ajax: {
            url: serve + "apiSucursalesEntrega",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: id,
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columns: [{
            data: "Nombre",
            title: "Sucursal",
            className: "text-uppercase",
        },
        {
            data: "cantidad",
            title: "Cantidad",
            className: "text-right",
        },
        {
            data: "fecEntrega",
            title: "Fecha Entrega",
            className: "text-right",
        },
        {
            data: "idSucEntrega",
            render: function (data, type, row) {
                return (
                    '<a onclick="eliminarCantidad(' +
                    data +
                    ')" class="pointer" title="Eliminar Cantidad"><i class="fas fa-trash text-red"></i></a>'
                );
            },
            title: "Eliminar",
            className: "dt-body-center text-center",
        },
        ],
        pageLength: -1,
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var j = 1;
            while (j < 2) {
                var pageTotal = api
                    .column(j)
                    .data()
                    .reduce(function (a, b) {
                        return Number(a) + Number(b);
                    }, 0);

                var numFormat = $.fn.dataTable.render.number(",", ".", 0, "").display;

                $(api.column(0).footer()).html("Cantidad Total");
                $(api.column(j).footer()).html(numFormat(pageTotal));
                j++;
            }
        },
        drawCallback: function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        columnDefs: [{
            targets: [2],
            render: $.fn.dataTable.render.moment(
                FROM_PATTERN_SHORT,
                TO_PATTERN_SHORT
            ),
        },
        {
            targets: [1],
            render: $.fn.dataTable.render.number(",", ".", 0),
        },
        ],
    });


});

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
                success: function (data) {
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
                    // window.location.href=document.referrer;
                    // window.location.href = "oportunidades";
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
            // window.location.href=document.referrer;
            // window.location.href = "oportunidades";
        }
    });
}

var mensaje = "";
var icono = "";
// $(function () {
//     $("#formOporCreateModal form").validator().on("submit", function (e) {
//         console.log('si llega');
//         if (!e.isDefaultPrevented()) {
//             mensaje = "Oportunidad Creada";
//             icono = "success";

//             // regresar();
//             // aplicarFiltroOportunidades(preregistroChk.checked, SolCotChk.checked, listoCotChk.checked, cotizandoChk.checked, revisionChk.checked, esperaVoBoChk.checked, listoPreChk.checked, preTerminadoChk.checked, finalizadoChk.checked, canceladoChk.checked);
//             // tblOportunidades.ajax.reload();
//             // closeMOportunidad();
//         } else {
//             mensaje = "Faltan Datos";
//             icono = "warning";
//         }
//         const Msg = Swal.mixin({
//             toast: true,
//             position: "center",
//             icon: icono,
//             showConfirmButton: false,
//             timer: 3000,
//         });
//         Msg.fire({
//             title: mensaje,
//         });
//     });

//     $("#formOporEdit form").validator().on("submit", function (e) {
//         if (!e.isDefaultPrevented()) {
//             loaderON();
//             mensaje = "Oportunidad Actualizada";
//             icono = "success";
//             // regresar();
//         } else {
//             mensaje = "Faltan Datos";
//             icono = "warning";
//         }
//         const Msg = Swal.mixin({
//             toast: true,
//             position: "center",
//             icon: icono,
//             showConfirmButton: false,
//             timer: 3000,
//         });

//         Msg.fire({
//             title: mensaje,
//         });
//         aplicarFiltroOportunidades(preregistroChk.checked, SolCotChk.checked, listoCotChk.checked, cotizandoChk.checked, revisionChk.checked, esperaVoBoChk.checked, listoPreChk.checked, preTerminadoChk.checked, finalizadoChk.checked, canceladoChk.checked);
//     });
// });

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
                cancelButtonText: "Cancelar",
                confirmButtonText: "Guardar",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                target: document.getElementById('formOporEdit'),
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
                            success: function (data) {
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
                            error: function () {
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
                } else {
                    loaderOut();
                }
            });

            //   if (text) {
            //     Swal.fire(text)
            //   }
        } else {
            loaderOut();
        }
    });
}

$("#numOrdenMTC").autocomplete({
    delay: 0,
    minLength: 4,
    source: function (request, response) {
        $.ajax({
            url: serve + "obtenerOPs",
            type: "GET",
            dataType: "json",
            data: {
                _token: CSRF_TOKEN,
                search: request.term,
            },
            success: function (data) {
                response(data);
            },
        });
    },
});

function mostrarGeneralOport() {
    idrol = document.getElementById("idRol");

    $("#general-oport-tab").addClass("active");
    $("#general-oport").addClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    $("#entregas-oport-tab").removeClass("active");
    $("#entregas-oport").removeClass("show");

    if (idrol.value == 1013) {
        document.getElementById("documentos-oport-tab").style.display = "none";
        document.getElementById("entregas-oport-tab").style.display = "none";

        var nodes_general = document
            .getElementById("tabGeneral")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_general.length; i++) {
            nodes_general[i].disabled = true;
        }

        $("#div-btnrelacionar").addClass("disable");

        $("#sOportBtnCancelar").addClass("disable");
        $("#sOportBtnSave").addClass("disable");
        $("#btnCancelar").addClass("disable");

        document.getElementById("sOportBtnTEntrega").style.display = "block";

        $("#sOportBtnBack").removeClass("col-12");

        $("#sOportBtnSave").addClass("col-3");
        $("#sOportBtnBack").addClass("col-4");
        $("#sOportBtnCancelar").addClass("col-3");

        if (archivoAutorizado.checked == true) {
            $("#archivoAutorizado").prop("disabled", false);
            $("#archivoAutorizado").addClass("pointer");
            $("#archivoAutorizadoLabel").addClass("pointer");
        } else {
            $("#archivoAutorizado").prop("disabled", false);
            $("#archivoAutorizadoLabel").addClass("pointer");
        }
    } else if (idrol.value == 1014) {
        document.getElementById("elementos-oport-tab").style.display = "none";
        document.getElementById("documentos-oport-tab").style.display = "none";
        document.getElementById("entregas-oport-tab").style.display = "none";

        var nodes_general = document
            .getElementById("tabGeneral")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_general.length; i++) {
            nodes_general[i].disabled = true;
        }

        $("#div-btnrelacionar").addClass("disable");

        $("#btnCancelar").attr("onclick", "");
        $("#btnCancelar").off('click');

        $("#btnSolFecEntrega").attr("onclick", "");
        $("#btnSolFecEntrega").off('click');
        $("#btnSolFecEntrega").addClass("disable");

        $("#oportBtnSave").prop("disabled", true);
        $("#sOportBtnCancelar").addClass("disable");
        $("#btnCancelar").addClass("disable");
        // $("#sOportBtnSave").addClass("disable");

        document.getElementById("sOportBtnTEntrega").style.display = "none";
        document.getElementById("sOportBtnSave").style.display = "none";
        document.getElementById("sOportBtnCancelar").style.display = "none";

        $("#sOportBtnBack").removeClass("col-12");

        $("#sOportBtnSave").addClass("col-4");
        $("#sOportBtnBack").addClass("col-4");
        $("#sOportBtnCancelar").addClass("col-4");

    } else if (idrol.value == 8 || idrol.value == 1) {
        document.getElementById("sOportBtnTEntrega").style.display = "block";

        $("#sOportBtnBack").removeClass("col-12");

        $("#sOportBtnSave").addClass("col-3");
        $("#sOportBtnBack").addClass("col-4");
        $("#sOportBtnCancelar").addClass("col-3");
    } else {
        document.getElementById("elementos-oport-tab").style.display = "block";
        document.getElementById("documentos-oport-tab").style.display = "block";
        document.getElementById("entregas-oport-tab").style.display = "block";
        document.getElementById("btnClonarOportunidad").style.display = "block";

        $("#sOportBtnCancelar").removeClass("disable");
        $("#sOportBtnSave").removeClass("disable");

        document.getElementById("sOportBtnTEntrega").style.display = "none";

        $("#sOportBtnBack").removeClass("col-12");

        $("#sOportBtnSave").addClass("col-4");
        $("#sOportBtnBack").addClass("col-4");
        $("#sOportBtnCancelar").addClass("col-4");
    }

    document.getElementById("general-oport").style.display = "block";
    document.getElementById("elementos-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "block";
    document.getElementById("sOportBtnCancelar").style.display = "block";

    tblElementosPresupuestados.draw();
}

function mostrarElementosOport() {
    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").addClass("active");
    $("#elementos-oport").addClass("show");

    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    $("#entregas-oport-tab").removeClass("active");
    $("#entregas-oport").removeClass("show");

    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "block";
    document.getElementById("documentos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    document.getElementById("btnClonarOportunidad").style.display = "none";
    $("#sOportBtnBack").addClass("col-12");
    tblElementosPresupuestados.draw();
}

function mostrarDocumentosOport() {
    tblDocs();
    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#documentos-oport-tab").addClass("active");
    $("#documentos-oport").addClass("show");

    $("#entregas-oport-tab").removeClass("active");
    $("#entregas-oport").removeClass("show");

    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "block";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    document.getElementById("btnClonarOportunidad").style.display = "none";

    $("#sOportBtnBack").addClass("col-12");

    tblElementosPresupuestados.draw();
}

function mostrarEntregasOport() {
    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    $("#entregas-oport-tab").addClass("active");
    $("#entregas-oport").addClass("show");

    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "block";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    $("#sOportBtnBack").addClass("col-12");
    // $("#sOportBtnBack").removeClass("col-12");
    // $("#sOportBtnBack").addClass("col-6");
    // $("#sOportBtnSave").addClass("col-6");

    ajustarTablaEntregasOport();

    tableEntregasOportunidades.draw();
}

$(window).resize(function () {
    ajustarTablaEntregasOport();
});

function ajustarTablaEntregasOport() {
    var height = window.innerHeight;

    if (height >= 320 && height <= 651) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "45vh");
        $("#scrollbarTabsModalRound").css("height", "68vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "49vh");
        $("#scrollbarTabsModalRound").css("height", "72vh");
    } else if (height >= 769 && height <= 992) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "53vh");
        $("#scrollbarTabsModalRound").css("height", "72vh");
    } else if (height >= 993 && height <= 1024) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "53vh");
        $("#scrollbarTabsModalRound").css("height", "76vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "54vh");
        $("#scrollbarTabsModalRound").css("height", "77vh");
    } else {
        $("#tableEntregasOportunidades")
            .closest(".dataTables_scrollBody")
            .css("height", "55vh");
        $("#scrollbarTabsModalRound").css("height", "78vh");
    }
}

function habilitarSucursal(idSucursalEnt) {
    var seleccionar = document.getElementById("chk" + idSucursalEnt);

    seleccionar.addEventListener("change", function () {
        habilitarCantidad(idSucursalEnt, seleccionar.checked);
    });
}

function habilitarCantidad(idSucursalEnt, check) {
    if (check) {
        $("#cantidadSuc" + idSucursalEnt)
            .prop("disabled", false)
            .focus();
    } else {
        $("#cantidadSuc" + idSucursalEnt).prop("disabled", true);
        $("#cantidadSuc" + idSucursalEnt).val(0);
    }
}

function guardarCantidad(idSucursalEnt) {
    cantidad = $("#cantidadSuc" + idSucursalEnt).val();
    seleccionado = $("#chk" + idSucursalEnt).val();

    // console.log(seleccionado);

    if (seleccionado == "on") {
        seleccionado = 1;
    } else {
        seleccionado = 0;
    }

    $.ajax({
        url: serve + "guardarCantidad",
        type: "PATCH",
        data: {
            _method: "PATCH",
            idSucursalEnt: idSucursalEnt,
            cantidad: cantidad,
            seleccionado: seleccionado,
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
                title: "Cantidad Agregada",
            });
            tableEntregasOportunidades.ajax.reload();
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
                title: "Error al Agregar...",
            });
        },
    });
}

function agregarCantidad() {
    cantidad = $("#cantidadSucursal").val();
    idSucursalEnt = $("#idSucursal").val();
    idOportunidad = $("#idOportunidad").val();
    idCliente = $("#idClienteEnt").val();
    totalCantidadOP = $("#totalCantidadOP").val();
    fecEntregaInput = $("#fecEntregaInput").val();

    if (
        idSucursalEnt == "Sucursal" ||
        idSucursalEnt == 0 ||
        idSucursalEnt == null
    ) {
        Swal.fire("¡Selecciona una Sucursal!");
    } else if (cantidad == "cantidad" || cantidad == "") {
        Swal.fire("¡Captura la cantidad!");
    } else if (fecEntregaInput == "Fecha Entrega" || fecEntregaInput == "") {
        Swal.fire("¡Captura la Fecha de Entrega!");
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
            success: function (data) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: "Cantidad Agregada",
                });

                limpiarCampos();
            },
            error: function (data) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: "La cantidad a entregar supera la cantidad de la OP",
                });

                limpiarCampos();
            },
        });
    }
}

function limpiarCampos() {
    $("#cantidadSucursal").val(null);
    $("#idSucursal").val("Sucursal");
    $("#fecEntregaInput").val("Fecha Entrega");

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
                    tableEntregasOportunidades.ajax.reload();
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
                },
            });
        }
    });
}

var DiasEntrega = document.getElementById("DiasEntrega");
var FechaAutoriza = document.getElementById("FechaAutorizaEnt");
var FechaEntrega = document.getElementById("FechaEntregaEnt");

$("#modal-tiempo-entrega").draggable({
    handle: ".titulo",
});

$("#modal-tiempo-entrega").on("shown.bs.modal", function () {
    $("#DiasEntrega").trigger("focus");
    $("#saveBtnTEntrega").prop("disabled", false);
});

function openTiempoEntregaModal(Oportunidad, Usuario, idTiempoEntrega) {
    var idOportunidadTEntrega = document.getElementById("idOportunidadTEntrega");
    var idUsuarioTEntrega = document.getElementById("idUsuarioTEntrega");
    var idTiempoEntregaOprotunidad = document.getElementById(
        "idTiempoEntregaOprotunidad"
    );
    var dias = document.getElementById("dias");
    var fecha = document.getElementById("fecha");
    var comentarios = document.getElementById("Comentarios");

    $("#modal-tiempo-entrega").modal("show");
    $("#modal-tiempo-entrega form").trigger("reset");

    idOportunidadTEntrega.value = Oportunidad;
    idUsuarioTEntrega.value = Usuario;
    idTiempoEntregaOprotunidad.value = idTiempoEntrega;

    DiasEntrega.disabled = true;
    DiasEntrega.required = true;

    FechaAutoriza.required = true;
    FechaEntrega.required = true;

    FechaAutoriza.disabled = true;
    FechaEntrega.disabled = true;

    if (idTiempoEntrega > 0) {
        $.ajax({
            url: serve + "getTiempoEntrega/" + idTiempoEntrega,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                if (data.DiasEntrega != null) {
                    DiasEntrega.value = data.DiasEntrega;
                    FechaAutoriza.value = "";
                    FechaEntrega.value = "";

                    dias.checked = true;
                    DiasEntrega.disabled = false;
                    fecha.checked = false;
                    FechaAutoriza.disabled = true;
                    FechaEntrega.disabled = true;
                } else {
                    DiasEntrega.value = "";
                    FechaAutoriza.value = dateFormat(data.FechaAutoriza, "yyyy-mm-dd");
                    FechaEntrega.value = dateFormat(data.FechaEntrega, "yyyy-mm-dd");

                    dias.checked = false;
                    DiasEntrega.disabled = true;
                    fecha.checked = true;
                    FechaAutoriza.disabled = false;
                    FechaEntrega.disabled = false;
                }
                comentarios.value = data.Comentarios;
            },
            error: function () {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    title: "Algo salió mal...",
                });
            },
        });
    }

    loaderModalOut();
}

$('input:radio[name="diasOFecha"]').change(function () {
    var fecArchivoAutorizado = document.getElementById("fecArchivoAutorizado");

    if (this.checked && this.value == "dias") {
        $.ajax({
            url: serve + "getOportunidad",
            type: "GET",
            data: {
                idOportunidad: id,
            },
            success: function (data) {
                if (data.fecArchivoAutorizado != null) {
                    fecArchivoAutorizado.value = dateFormat(
                        data.fecArchivoAutorizado,
                        "yyyy-mm-dd"
                    );
                } else {
                    fecArchivoAutorizado.value = null;
                }
            },
        });

        DiasEntrega.disabled = false;

        DiasEntrega.required = true;

        FechaAutoriza.disabled = true;
        FechaEntrega.disabled = false;
        FechaEntrega.readOnly = true;
        // $('#FechaEntrega').prop('readonly', true);

        DiasEntrega.required = true;
        FechaAutoriza.required = false;
        FechaEntrega.required = false;

        FechaAutoriza.value = "";
        FechaEntrega.value = "";

        FechaAutoriza.required = false;
        FechaEntrega.required = false;
    } else {
        DiasEntrega.disabled = true;

        DiasEntrega.required = false;

        FechaEntrega.required = true;

        FechaAutoriza.readOnly = false;
        FechaAutoriza.disabled = false;
        FechaEntrega.readOnly = false;
        FechaEntrega.disabled = false;

        DiasEntrega.required = false;
        FechaAutoriza.required = false;
        FechaEntrega.required = true;

        DiasEntrega.value = "";
        $("#fecArchivoAutorizado").val("");
    }
});

$("#DiasEntrega").change(function () {
    fechaAut = document.getElementById("fecArchivoAutorizado");
    diasEntrega = parseInt(this.value);

    fechEntrega = getDiasHabiles(fechaAut.value, diasEntrega);

    $("#FechaEntregaEnt").val(dateFormat(fechEntrega, "yyyy-mm-dd"));
});

// $('#FechaEntrega').change(function() {
//     fechaAut = document.getElementById('FechaAutoriza');

//     diasHabiles = calculardias(fechaAut.value, this.value);

//     console.log(diasHabiles);

//     $('#DiasEntrega').val(diasHabiles);
// });

$("#saveBtnTEntrega").click(function () {
    addOrEditTiempoEntrega();
});

function addOrEditTiempoEntrega() {
    $("#saveBtnTEntrega").prop("disabled", true);
    // var DiasEntrega = document.getElementById('DiasEntrega');
    // var FechaAutoriza = document.getElementById('FechaAutoriza');
    // var FechaEntrega = document.getElementById('FechaEntrega');
    // var idOportunidadTEntrega = document.getElementById('idOportunidadTEntrega');
    // var idUsuarioTEntrega = document.getElementById('idUsuarioTEntrega');
    // var idTiempoEntregaOprotunidad = document.getElementById('idTiempoEntregaOprotunidad');

    var Autorizo = document.getElementById("Autorizo");
    var Comentarios = document.getElementById("Comentarios");
    var fecArchivoAutorizado = document.getElementById("fecArchivoAutorizado");

    var dias = document.getElementById("dias");
    var fecha = document.getElementById("fecha");

    if (dias.checked == false && fecha.checked == false) {
        Swal.fire("¡Selecciona una opción!");
    } else {
        if (dias.checked == true && DiasEntrega.value == "") {
            Swal.fire("¡Captura los Días de Entrega!");
        } else if (fecha.checked == true && FechaAutoriza.value == "") {
            Swal.fire("¡Captura la Fecha del Archivo!");
        } else if (fecha.checked == true && FechaEntrega.value == "") {
            Swal.fire("¡Captura la Fecha de Entrega!");
        } else {
            loaderON();

            $.ajax({
                url: serve + "addOrEditTiempoEntrega",
                type: "PATCH",
                data: {
                    idOportunidadTEntrega: idOportunidadTEntrega.value,
                    idUsuarioTEntrega: idUsuarioTEntrega.value,
                    idTiempoEntregaOprotunidad: idTiempoEntregaOprotunidad.value,
                    DiasEntrega: DiasEntrega.value,
                    FechaAutoriza: FechaAutoriza.value,
                    FechaEntrega: FechaEntrega.value,
                    Autorizo: Autorizo.value,
                    Comentarios: Comentarios.value,
                    FechaArchivoAutorizado: fecArchivoAutorizado.value,
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
                        title: "Archivo Guardado",
                    });

                    $("#modal-tiempo-entrega").modal("hide");
                    loaderOut();
                    tblTEntrega.ajax.reload();
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

                    $("#modal-tiempo-entrega").modal("hide");
                    tblTEntrega.ajax.reload();
                    loaderOut();
                },
            });
        }
    }
}

function solicitarFechaEntrega() {
    mensaje = "Solicitud Enviada";
    Swal.fire({
        title: "Solicitar Fecha",
        text: "Deseas solicitar la fecha de entrega para esta Oportunidad?",
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
            $("#swal2-input").prop("readonly", false);
            Swal.fire({
                input: "textarea",
                inputLabel: "Nota",
                inputPlaceholder: "Captura la Nota de la Solicitud",
                inputAttributes: {
                    "aria-label": "Captura la Nota de la Solicitud",
                },
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Guardar",
                // target: document.getElementById('modal-create-envio'),
            }).then((result) => {
                if (result.isConfirmed) {
                    if (result.value == null || result.value == "") {
                        Swal.fire("¡Debes capturar la Nota!");
                    } else {
                        loaderON();
                        $.ajax({
                            url: serve + "solicitarFechaEntrega",
                            type: "PATCH",
                            data: {
                                _method: "PATCH",
                                id: id,
                                notaSolicitud: result.value,
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
                                loaderOut();
                                $("#btnSolFecEntrega").addClass("disable");
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
                                loaderOut();
                            },
                        });
                    }
                }
            });
        }
    });
}

function autorizarArchivo(idOport) {
    mensaje = "Archivo Autorizado";
    Swal.fire({
        title: "Autorizar Archivo",
        text: "Deseas autorizar el archivo para esta Oportunidad?",
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
            loaderON();
            $.ajax({
                url: serve + "autorizarArchivoOportunidad",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idOport: idOport,
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
                    loaderOut();
                    $("#archivoAutorizado").prop("disabled", true);
                    // checkArchivoAutorizado(idOport);
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
                    loaderOut();
                },
            });

            $.ajax({
                url: serve + "GetMaxDiasEntrega",
                type: "GET",
                data: {
                    _method: "GET",
                    idOport: idOport,
                },
                success: function (data) {
                    if (data.DiasEntrega != null) {
                        fechaEntrega = getDiasHabiles(
                            dateFormat(data.FechaArchivoAutorizado, "yyyy-mm-dd"),
                            parseInt(data.DiasEntrega)
                        );
                        $.ajax({
                            url: serve + "updateFechaEntregaDias",
                            type: "PATCH",
                            data: {
                                _method: "PATCH",
                                idOport: data.idOportunidad,
                                DiasEntrega: data.DiasEntrega,
                                fechaEntrega: fechaEntrega,
                                fechaArchivo: data.FechaArchivoAutorizado,
                                _token: CSRF_TOKEN,
                            },
                        });

                        tblTEntrega.ajax.reload();
                        // } else {
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
                    loaderOut();
                },
            });
        } else {
            $("#archivoAutorizado").prop("checked", false);
        }
    });
}

function desautorizarArchivo(idOport) {
    mensaje = "Archivo No Autorizado";
    Swal.fire({
        title: "No se Autoriza Archivo",
        text: "Deseas quitar la autorización de el archivo para esta Oportunidad?",
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
            loaderON();
            $.ajax({
                url: serve + "desautorizarArchivoOportunidad",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idOport: idOport,
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
                    loaderOut();
                    $("#archivoAutorizado").prop("checked", false);
                    $("#archivoAutorizado").prop("disabled", true);
                    tblTEntrega.ajax.reload();
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
                    loaderOut();
                },
            });
        } else {
            $("#archivoAutorizado").prop("checked", true);
        }
    });
}

function getDiasHabiles(fecha, diasAdd) {
    var hoy = new Date();
    var arrFecha = fecha.split("-");
    var fecha = new Date(arrFecha[0], arrFecha[1] - 1, arrFecha[2]);

    // console.log('Fecha GetDias' + fecha);
    if (hoy.getHours() >= 18) {
        fecha.setDate(fecha.getDate() + 1);
    }

    // $.ajax({
    //     url: serve + 'getHollidays',
    //     type: 'GET',
    //     data: {
    //         year: hoy.getFullYear(),
    //     },
    //     success: function(data) {

    //         var getfestivos = data;

    //         festivos = getfestivos.map(function(o) {

    //             return Object.keys(o).reduce(function(array, key) {
    //                 return array.concat([o[key]]);
    //             }, []);
    //         });
    //     }

    // });

    festivos = [
        // Agregamos los festivos (dia, mes)
        [25, 12],
        [1, 1],
        [7, 2],
        [21, 3],
        [14, 4],
        [15, 4],
        [16, 9],
        [21, 11],
        [12, 12],
    ];

    for (var i = 0; i < diasAdd; i++) {
        var diaInvalido = false;
        fecha.setDate(fecha.getDate() + 1); // Sumamos de dia en dia
        for (var j = 0; j < festivos.length; j++) {
            // Verificamos si el dia + 1 es festivo
            var mesDia = festivos[j];
            if (fecha.getMonth() + 1 == mesDia[1] && fecha.getDate() == mesDia[0]) {
                diaInvalido = true;
                break;
            }
        }
        if (fecha.getDay() == 0 || fecha.getDay() == 6) {
            // Verificamos si es sábado o domingo
            diaInvalido = true;
        }

        if (diaInvalido) diasAdd++; // Si es fin de semana o festivo le sumamos un dia
    }
    return (
        fecha.getFullYear() +
        "/" +
        (fecha.getMonth() + 1).toString().padStart(2, "0") +
        "/" +
        fecha.getDate().toString().padStart(2, "0")
    );
}

function calculardias($fecha1, $fecha2) {
    var arrFecha = $fecha1.split("-");
    var $fecha1 = new Date(arrFecha[0], arrFecha[1] - 1, arrFecha[2]);

    var arrFecha2 = $fecha2.split("-");
    var $fecha2 = new Date(arrFecha2[0], arrFecha2[1] - 1, arrFecha2[2]);

    $fecha1.setDate($fecha1.getDate());
    $fecha2.setDate($fecha2.getDate());

    var dif = $fecha2 - $fecha1;
    var dias = Math.floor(dif / (1000 * 60 * 60 * 24));

    // console.log("Días : " + dias);

    if (dias == 7) {
        dias = dias - 2;
    } else { }
    // console.log("Día de la fecha 1 : " + $fecha1.getDay());
    // console.log("Día de la fecha 2 : " + $fecha2.getDay());

    // var aFecha1 = $fecha1.split('-');
    // var aFecha2 = $fecha2.split('-');
    // var $fecha1 = Date(aFecha1[0], aFecha1[1] - 1, aFecha1[2]);
    // var $fecha2 = Date(aFecha2[0], aFecha2[1] - 1, aFecha2[2]);

    // $fecha1.setDate($fecha1.getDate());

    return dias;

    // $dato1 = $fecha1.split('-');
    // $dato2 = $fecha2.split('-');

    // //defino fecha 1
    // $ano1 = $dato1[0];
    // $mes1 = $dato1[1];
    // $dia1 = $dato1[2];

    // //defino fecha 2
    // $ano2 = $dato2[0];
    // $mes2 = $dato2[1];
    // $dia2 = $dato2[2];

    // console.log($dia1 + '/' + $mes1 + '/' + $ano1);
    // console.log($dia2 + '/' + $mes2 + '/' + $ano2);

    // //calculo timestam de las dos fechas
    // $timestamp1 = new Date($ano1, $mes1, $dia1, 0, 0, 0, 0).getTime() / 1000;
    // // $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
    // /* echo ("$timestamp1"."<br>");  */
    // $timestamp2 = new Date($ano2, $mes2, $dia2, 0, 0, 0, 0).getTime() / 1000;
    // // $timestamp2 = mktime(4, 12, 0, $mes2, $dia2, $ano2);
    // /* echo ("$timestamp2"."<br>"); */
    // $segundos_diferencia = $timestamp2 - $timestamp1; //resto a una fecha la otra */
    // /* echo ("$segundos_diferencia"."<br>"); */
    // console.log($timestamp1 + '/' + $timestamp2);

    // $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); //convierto segundos en días
    // $dias_diferencia = Math.round($dias_diferencia); //obtengo el valor absoulto de los días (quito el posible signo negativo)

    // return $dias_diferencia;
}

// public int diasHabiles(Calendar fechaInicial, Calendar fechaFinal, List<Date> listaFechasNoLaborables) {
//     int diffDays = 0;
//     boolean diaHabil = false;
//     //mientras la fecha inicial sea menor o igual que la fecha final se cuentan los dias
//     while (fechaInicial.before(fechaFinal) || fechaInicial.equals(fechaFinal)) {

//        if (!listaFechasNoLaborables.isEmpty()) {
//            for (Date date : listaFechasNoLaborables) {
//                Date fechaNoLaborablecalendar = fechaInicial.getTime();
//                //si el dia de la semana de la fecha minima es diferente de sabado o domingo
//                if (fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SUNDAY && fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SATURDAY && !fechaNoLaborablecalendar.equals(date)) {
//                    //se aumentan los dias de diferencia entre min y max
//                    diaHabil = true;
//                } else {
//                    diaHabil = false;
//                    break;
//                }
//            }
//        } else {
//            if (fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SUNDAY && fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SATURDAY) {
//                //se aumentan los dias de diferencia entre min y max
//                diffDays++;
//            }
//        }
//        if (diaHabil == true) {
//        diffDays++;
//        }
//        //se suma 1 dia para hacer la validacion del siguiente dia.
//        fechaInicial.add(Calendar.DATE, 1);
//   }
//   return diffDays;
// }

// function getDiasHabilesXFechas(fechaInicio, fechaFin) {

//     var fechaInicio = new Date();
//     var fechaFin = new Date();

//     var FechasFestivas = [
//         [1],
//         [7],
//         [21],
//         [14, 15],
//         [40],
//         [40],
//         [40],
//         [40],
//         [16],
//         [40],
//         [21],
//         [12, 25],
//     ];

//     fechaFin.setDate(fechaFin.getDate());
//     var diaSaDo = 0;
//     var diafestivo = 0;
//     var dom = 0;
//     var sab = 6;

//     while (fechaInicio.getDate() < fechaFin.getDate()) {
//         // document.getElementById("a").prepend("dia inicio = " + fechaInicio.getDate() + "---");
//         if (fechaInicio.getDay() == dom || fechaInicio.getDay() == sab) {
//             diaSaDo++;
//         } else {
//             for (var i = 0; i <= FechasFestivas[fechaIncio.getMonth()].length; i++) {
//                 if (fechaInicio.getDate() == FechasFestivas[fechaInicio.getMonth()][i]) {
//                     diafestivo++;
//                 }
//             }
//         }
//         fechaInicio.setDate(fechaInicio.getDate() + 1);
//     }

//     fechaInicio.setDate(fechaInicio.getDate() + diaSaDo + diafestivo);

//     console.log('Fecha Inicio :' + fechaInicio + ', Fecha Fin : ' + fechaFin);

//     // return fechaInicio.getFullYear() + '/' + (fecha.getMonth() + 1).toString().padStart(2, '0') + '/' + fecha.getDate().toString().padStart(2, '0');

// }

// function stringToDate(dateString) {
//     dateString = dateString.split('-');
//     return new Date(dateString[0], dateString[1] - 1, dateString[2]);
// }

// function countWorkDay(sDay, eDay) {
//     var s = stringToDate(sDay),
//         e = stringToDate(eDay);
//     var s_t_w = s.getDay(),
//         e_t_w = e.getDay();

//     // Diferencia en días
//     var diffDay = (e - s) / (1000 * 60 * 60 * 24) + 1;
//     var diffWeekDay = diffDay - (s_t_w == 0 ? 0 : 7 - s_t_w) + e_t_w;
//     // El cálculo tiene varias semanas completas
//     var weeks = Math.floor(diffWeekDay / 7);
//     if (weeks <= 0) { // En la misma semana, es decir, la hora de inicio y finalización no puede ser el domingo y el sábado (el domingo es el primer día de la semana)
//         return e_t_w - s_t_w + (s_t_w ? 1 : 0) + (e_t_w == 6 ? -1 : 0);
//     } else {
//         return weeks * 5 + (e_t_w == 6 ? 5 : e_t_w) + (s_t_w >= 1 && s_t_w <= 5 ? (6 - s_t_w) : 0);
//     }
// }

// console.log(countWorkDay('2017-08-01', '2017-08-06'));

// function countWorkDay(date1, date2){
//     date1 = stringToDate(date1);
//     date2 = stringToDate(date2);
//     delta = (date2-date1) / (1000 * 60 * 60 * 24) + 1; // calcula el tiempo total

//     weeks = 0;
//     for(i = 0; i < delta; i++){
//                      if (date1.getDay () == 0 || date1.getDay () == 6) semanas ++; // agrega 1 si es sábado o domingo
//         date1 = date1.valueOf();
//         date1 += 1000 * 60 * 60 * 24;
//         date1 = new Date(date1);
//     }
//     return delta - weeks;
// }
// console.log(countWorkDay('2017-08-01', '2017-08-06')); // 4





//------------------------------Modal-----------------------------

$('#modal-oportunidades-create').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
});

// $("#modal-oportunidades-create").draggable({
//     handle: "#titulo"
// });

$('#modal-oportunidades-create').on('shown.bs.modal', function () {
    $('#nomOportunidad').trigger('focus');
});

function closeMOportunidad() {
    let m = document.querySelector("#modal-oportunidades-create");
    m.classList.remove("showModal");
    loaderOut();
}

var mensaje = '';

function agregarOportunidad() {
    $("#modal-oportunidades-create").addClass("showModal");
    $("#modal-oportunidades-create").modal("show");
    $("#modal-oportunidades-create").find("form").trigger("reset");
    $("#titulo").text("Nueva Oportunidad");

    // $("#folio").text("No. " + folio + " - ");
    // $("#fecCreacion").val(dateToday.toLocaleString("es-MX"));

    // document.getElementById("sOportBtnSave").style.display = 'block';

    // document.getElementById("custom-content-below-tab").style.display = 'none';
    // document.getElementById("elementos-oport").style.display = 'none';
    // document.getElementById("documentos-oport").style.display = 'none';
    // document.getElementById("entregas-oport").style.display = 'none';

    btnSaveOport.disabled = false;

    // $('#sOportBtnSave').removeClass('col-3');
    // $('#sOportBtnSave').addClass('col-6');
    // $('#sOportBtnBack').removeClass('col-3');
    // $('#sOportBtnBack').addClass('col-6');

    // $('#idCliente').select2().removeClass('select2-green');
    // $('#idCliente').select2().addClass('select2-red');
}

function editarOportunidad(id) {

    console.log(id);

    nomOportunidad = document.getElementById('nomOportunidad');

    $.ajax({
        url: serve + 'getOportunidad',
        type: 'GET',
        dataType: 'JSON',
        data: {
            idOportunidad: id,
        },
        success: function(data) {
            console.log(data);
            $("#modal-oportunidades-edit").addClass("showModal");
            $("#modal-oportunidades-edit").modal("show");
            $("#modal-oportunidades-edit").find("form").trigger("reset");
            $("#titulo").text("Editar Oportunidad");

            $('#Estatus').val(data.idEstatus);
            $('#idUsuario').val(data.Id_Usuario);
            $('#idOportunidad').val(data.idOportunidad);
            $('#Cancelada').val(data.Cancelada);
            $('#idClienteEnt').val(data.ClienteINT);
            $('#FechaAutoriza').val(data.FechaAutoriza);
            // $('#idRol').val(data.idRol);
            $('#numFolio').text(' No. ' + data.idOportunidad + ' - ');

            nomOportunidad.value = data.nomOportunidad;
            $('#idCliente').val(data.idCliente);
            $('#numOrdenMTC').val(data.numOrdenMTC);
            $('#Valor').val(data.Valor);
            $('#notaRetencion').val(data.notaRetencion);
            $('#Creado').val(data.Creado);
            $('#idUsuarioUltCambio').val(data.UsuarioUltCambio);
            $('#nota').val(data.nota);
            $('#NotaCancelacion').text(data.NotaCancelacion);
            $('#totalCantidadOP').val(data.totalCantidadOP);



        }
    });

    // $("#modal-oportunidades-edit").addClass("showModal");
    // $("#modal-oportunidades-edit").modal("show");
    // $("#modal-oportunidades-edit").find("form").trigger("reset");
    // $("#titulo").text("Editar Oportunidad");

}

$(function () {
    $('#modal-oportunidades-create form').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented()) {
            mensaje = "Oportunidad Creada";
            // var tblOportunidades = $('#tblOportunidades').DataTable();
            // var id = $('#idContactoINT').val();
            $.ajax({
                url: serve + 'oportunidades',
                type: "POST",
                data: $('#modal-oportunidades-create form').serialize(),
                success: function (data) {
                    $('#modal-oportunidades-create').trigger("reset");
                    $('#modal-oportunidades-create').modal('hide');
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

                    tblOportunidades.ajax.reload();
                },
                error: function () {
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
            return false;
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
        }
    });
    $('#modal-oportunidades-edit form').validator().on('submit', function (e) {
        if (!e.isDefaultPrevented()) {
            mensaje = "Oportunidad Actualizada";
            // var tblOportunidades = $('#tblOportunidades').DataTable();
            var id = $('#idOportunidad').val();
            $.ajax({
                url: serve + 'oportunidades/' + id,
                type: "PUT",
                data: $('#modal-oportunidades-edit form').serialize(),
                success: function (data) {
                    $('#modal-oportunidades-edit').trigger("reset");
                    $('#modal-oportunidades-edit').modal('hide');
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

                    tblOportunidades.ajax.reload();
                },
                error: function () {
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
            return false;
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
        }
    });

});

var nomoportunidad = document.getElementById('nomOportunidad');
var idcliente = document.getElementById('idCliente');
var fecPrevCiereInput = document.getElementById('fecPrevCiereInput');
var btnsaveOport = document.getElementById('btnSaveOport');
var idEquipo = document.getElementById('idEquipo');
var idAgente = document.getElementById('idAgente');


$(document).ready(function () {

    loaderOut();

    var idcliente = document.getElementById('idCliente');
    var idClienteSelec2 = $('#idCliente').select2({
        language: "es"
    });

    validarRequeridosOpor();

    nomoportunidad.addEventListener('change', function () {
        validarRequeridosOpor();
    });

    idEquipo.addEventListener('change', function () {
        idAgente.value = "Requerido";
        validarRequeridosOpor();
    });

    idEquipo.addEventListener('focusout', function () {
        validarRequeridosOpor();
    });

    idAgente.addEventListener('change', function () {
        validarRequeridosOpor();
    });

    idAgente.addEventListener('focusin', function () {
        validarRequeridosOpor();
    });

    idClienteSelec2.on('change', function () {
        validarRequeridosOpor();
    });

    fecPrevCiereInput.addEventListener('focusout', function () {
        validarRequeridosOpor();
    });

    $('#fecPrevCiereInput').appendDtpicker({
        locale: 'es',
        futureOnly: true,
        dateOnly: true,
        closeOnSelected: true,
        dateFormat: 'DD-MM-YYYY',
        todayButton: false,
        closeButton: false,
        autodateOnStart: false,
        timelistScroll: true,
        calendarMouseScroll: false,
        allowWdays: [1, 2, 3, 4, 5],
        onSelect: function (handler, targetDate) {
            validarRequeridosOpor();
        },
    });

    function validarRequeridosOpor() {

        var idClienteSelec2 = $('#idCliente').select2("data");

        if (nomoportunidad.value == "" || idcliente.value == "Requerido" || fecPrevCiereInput.value == "" ||
            idEquipo.value == "Requerido" || idAgente.value == "Requerido") {
            btnsaveOport.disabled = true;
        } else {
            btnsaveOport.disabled = false;
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

        if (fecPrevCiereInput.value == "") {
            fecPrevCiereInput.style.borderColor = 'red';
        } else {
            fecPrevCiereInput.style.borderColor = 'green';
        }

        if (idEquipo.value == "Requerido") {
            idEquipo.style.borderColor = 'red';
        } else {
            idEquipo.style.borderColor = 'green';
        }
        if (idAgente.value == "Requerido") {
            idAgente.style.borderColor = 'red';
        } else {
            idAgente.style.borderColor = 'green';
        }
    }

    var omtc = document.getElementById('numOrdenMTC');

    $('input:radio[name="idStatus"]').change(function () {

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

$(function () {
    $("#idEquipo").change(function () {
        var agente = $("#idAgente");
        var equipos = $(this);
        var agente_id = $('#idUsuario').val();
        var idEquipo_idAgente = $('#idEquipo_idAgente').val();

        if ($(this).val() != "") {
            $.ajax({
                data: {
                    idEquipo: equipos.val()
                },
                url: serve + 'getEquipos',
                type: "GET",
                dataType: "json",
                beforeSend: function () {
                    equipos.prop("disabled", true);
                },
                success: function (r) {
                    equipos.prop("disabled", false);

                    agente.find("option").remove();

                    agente.append(
                        '<option value="Requerido" selected disabled>Requerido</option>'
                    );

                    $(r).each(function (i, v) {
                        // indice, valor

                        if (v.Id_Usuario == agente_id || v.Id_Usuario ==
                            idEquipo_idAgente) {
                            agente.append(
                                '<option value="' +
                                v.Id_Usuario +
                                '" selected>' +
                                v.Nombre +
                                "</option>"
                            );
                        } else {
                            agente.append(
                                '<option value="' +
                                v.Id_Usuario +
                                '">' +
                                v.Nombre +
                                "</option>"
                            );
                        }
                    });

                    agente.prop("disabled", false);

                },
                error: function (r) {
                    Swal.fire("Ocurrio un error en el servidor ..")
                    equipos.prop("disabled", false);
                },
            });
        } else {
            agente.find("option").remove();
            agente.prop("disabled", true);
        }
    });
});