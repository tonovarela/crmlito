var minDate = moment().startOf("month").format("YYYY-MM");
var maxDate = moment().endOf("month").format("YYYY-MM-DD");
var evento = $("#evento").text();
var dateToday = new Date();

$(document).ajaxStart(function () {
    loaderON();
});

$(document).ajaxStop(function () {
    loaderOut();
});

// $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
//     var min = minDate;
//     var max = maxDate;
//     var date = new Date(data[1]);

//     if (
//         (min === null && max === null) ||
//         (min === null && date <= max) ||
//         (min <= date && max === null) ||
//         (min <= date && date <= max)
//     ) {
//         return true;
//     }
//     return false;
// });

$(document).ready(function () {
    // $('#searchGral').keyup(function() {
    //     $("#tblOportunidades").DataTable().draw();
    // });

    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";

    const FROM_PATTERN_SHORT = "YYYY-MM-DD";
    const TO_PATTERN_SHORT = "DD/MM/YYYY";

    buscarOport();

    obtenerSolicitudesOP();
    obtenerOportunidadPTVeinteDias();
    obtenerOportunidadSolOPCincoDias();

    $("#idCotizacionAutorizada").prop("disabled", true);
    $("#idMotivoPerdida").prop("disabled", true);

    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

    tableElePreOpor = $("#tableElePreOpor").DataTable({
        language: {
            url: serve + "js/spanish-DT.json",
        },

        dom: '<"top"i>rt<"bottom"><"clear">',
    });

    var id = $("#idOportunidad").val();

    $("#tblOporRechazos").DataTable({
        processing: true,
        serverSide: true,
        select: true,
        scrollX: true,
        searchable: false,
        orderable: false,
        scrollCollapse: true,
        ajax: {
            url: serve + "/api/listadoRechazoOport",
            data: function (d) {
                d.oportunidadID = id;
            },
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columnDefs: [{
            targets: [2],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
        },],
        columns: [{
            data: "motivo",
            name: "motivo",
            title: "Motivo",
            searchable: false,
            orderable: false,
            className: "dt-body-center text-left",
        },
        {
            data: "usuario",
            name: "usuario",
            title: "Usuario",
            searchable: false,
            orderable: false,
            className: "dt-body-center text-left",
        },
        {
            data: "fechaCreacion",
            name: "fechaCreacion",
            title: "Fecha Creación",
            searchable: false,
            orderable: false,
            className: "dt-body-center text-center",
        },
        ],
    });

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
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            orderable: false,
        },
        {
            targets: [5, 6, 7, 8],
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
                $("td:eq(0)", row).addClass("fas fa-times-circle text-center text-md");
                $("td:eq(0)", row).attr("title", "Inactiva");
            } else {
                if (data.fecArchivoAutorizado != null) {
                    if (
                        moment(data.FechaAutoriza, "YYYY-MM-DD") <
                        moment(data.fecArchivoAutorizado, "YYYY-MM-DD")
                    ) {
                        $("td:eq(0)", row).addClass(
                            "fas fa-copyright text-center text-yellow text-md"
                        );
                        $("td:eq(0)", row).attr("title", "Caducada");
                    } else {
                        $("td", row).css("font-weight", "bold");
                        $("td:eq(0)", row).addClass(
                            "fas fa-check-circle text-center text-md text-green"
                        );
                        $("td:eq(0)", row).attr("title", "Activa");
                    }
                } else {
                    if (moment(data.FechaAutoriza, "YYYY-MM-DD") < moment()) {
                        $("td:eq(0)", row).addClass(
                            "fas fa-copyright text-center text-yellow text-md"
                        );
                        $("td:eq(0)", row).attr("title", "Caducada");
                    } else {
                        $("td", row).css("font-weight", "bold");
                        $("td:eq(0)", row).addClass(
                            "fas fa-check-circle text-center text-md text-green"
                        );
                        $("td:eq(0)", row).attr("title", "Activa");
                    }
                }
            }
        },
        columns: [{
            data: "icon", // 0
            render: function (data) {
                return "<i></i>";
            },
        },
        {
            data: "idTiempoEntregaOprotunidad", // 1
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
            data: "Autorizo", // 2
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
            data: "DiasEntrega", // 3
            name: "DiasEntrega",
            title: "Días Entrega",
            className: "dt-body-center text-center",
            visible: false,
        },
        {
            data: "fecRegistro", // 4
            name: "fecRegistro",
            title: "Fecha Registro",
            className: "dt-body-center text-center",
        },
        {
            data: "FechaAutoriza", // 5
            name: "FechaAutoriza",
            title: "Fecha Archivo",
            className: "dt-body-center text-center",
            visible: false,
        },
        {
            data: "FechaEntrega", // 6
            name: "FechaEntrega",
            title: "Fecha Entrega",
            className: "dt-body-center text-center",
            visible: false,
        },
        {
            data: "FechaPrimeraEntrega", // 7
            name: "FechaPrimeraEntregaa",
            title: "Fecha Primera Entrega",
            className: "dt-body-center text-center",
        },
        {
            data: "FechaUltimaEntrega", // 8
            name: "FechaUltimaEntrega",
            title: "Fecha ültima Entrega",
            className: "dt-body-center text-center",
        },
        {
            data: "Comentarios", // 9
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

    // $("#tblOportunidades tbody").on("change", "td", function () {
    //     customer = $(this).find(":selected").val();
    //     data = tblOportunidades.row($(this).parents("tr")).data();
    //     id = data["idOportunidad"];
    //     nombre_customer = data["Atendido"];
    //     id_usuario = data["Id_Usuario"];
    //     cambiar_customer(id, customer, nombre_customer, id_usuario);
    // });

    // $("#tblOportunidades tbody").on("change", "td", function () {
    //     cotizador = $(this).find(":selected").val();
    //     data = tblOportunidades.row($(this).parents("tr")).data();
    //     id = data["idOportunidad"];
    //     nombre_cotizador = data["Cotizado"];
    //     id_usuario = data["Id_Usuario"];
    //     cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario);
    // });

    $("#tblOportunidades tbody").on("change", "select", function () {
        customer = $(this).find(":selected").val();
        cotizador = $(this).find(":selected").val();
        revisado = $(this).find(":selected").val();

        data = tblOportunidades.row($(this).parents("tr")).data();
        
        // console.log(data);
        id = data["idOportunidad"];
        let id_usuario = data["Id_Usuario"];
        cancelado = data["Cancelado"];

        nombre_customer = $('#customer_grid_' + id).find(":selected").text();
        nombre_atendiendo = data["Atendido"];

        nombre_cotizador = $('#cotizadores_grid_' + id).find(":selected").text();
        nombre_cotizando = data["Cotizado"];

        nombre_revisado = $('#revisado_grid_' + id).find(":selected").text();
        nombre_revisando = data["Revisado"];

        // console.log('Atendiendo ' + nombre_atendiendo);
        // console.log(customer);
        // console.log('Atendido ' + nombre_customer);

        // console.log('Cotizando ' + nombre_cotizando);
        // console.log(customer);
        // console.log('Cotizador ' + nombre_cotizador);

        // console.log('Revisando ' + nombre_revisando);
        // console.log(customer);
        // console.log('Revisado ' + nombre_revisado);

        if (nombre_customer !== nombre_atendiendo) {
            cambiar_customer(id, customer, nombre_customer, id_usuario);
        }

        if (nombre_cotizador !== nombre_cotizando) {
            cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario);
        }

        if (nombre_revisado != '' && nombre_revisado !== nombre_revisando) {
            cambiar_revisado(id, revisado, nombre_revisado, id_usuario);
        }



        // $('#customer_grid_' + id)[0].on('change', function () {
        //     nombre_cotizador = data["Cotizado"];
        //     id_usuario = data["Id_Usuario"];
        //     cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario);
        // })

        // $('#cotizadores_grid_' + id)[0].on('change', function () {
        //     nombre_customer = data["Atendido"];
        //     id_usuario = data["Id_Usuario"];
        //     cambiar_customer(id, customer, nombre_customer, id_usuario);
        // })
    });

    // $('#tblOportunidades tbody').on('click', 'tr', function () {
    //     var data = tblOportunidades.row(this).data();
    //     alert('You clicked on ' + data.folio + '\'s row');
    // });

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

    id_oportunidad_estr = $('#idOportunidad').val();

    tblDetalleAsignadosOport = $('#tblDetalleAsignadosOport').DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "Id_Ticket",
        select: false,
        scrollX: true,
        scrollCollapse: true,
        stateSave: false,
        async: false,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "apiEstructural",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: id_oportunidad_estr,
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columnDefs: [
            {
                targets: [1],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
            {
                targets: [7],
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
            },
            {
                targets: [1, 7, 9, 2],
                className: "dt-body-center text-center",
            },
            {
                targets: 6,
                render: function (data, type, row, meta) {
                    if (data == null || data == "") {
                        return "<span>SIN ASIGNAR</span>";
                    } else {
                        return "<span>" + data + "</span>";
                    }
                },
            },
        ],
        columns: [
            {
                data: 'master', // 0
                title: "Folio",
                className: 'text-bold dt-body-center text-center',
                width: '5%',
            },
            {
                data: 'FechaRegistro', // 1
                title: "Registro",
                width: '5%',
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
                width: '10%',
            },
            {
                data: 'Solicitante', // 3
                title: "Solicita",
                width: '10%',
            },
            {
                data: 'Cliente', // 4
                title: "Cliente",
                width: '10%',
            },
            {
                data: 'Tarea', // 5
                render: function (data, type, row) {
                    if (row.Estado == 'PENDIENTE DE ATENDER') {
                        $title = 'Editar Proyecto';
                    } else {
                        $title = 'Visualizar Proyecto';
                    }
                    return (
                        '<a class="text-primary" type="button" onclick="editarTicketOport(' +
                        row.Id_Ticket +
                        ' )" title="' + $title + '">' +
                        data +
                        "</a>"
                    );
                },
                title: "Titulo",
                width: '15%',
            },
            {
                data: 'Asignado', // 6
                title: "Operador",
                width: '15%',
            },
            {
                data: 'FechaCompromiso', // 7
                title: "Compromiso",
                width: '10%',
            },
            {
                data: 'Estado', // 8
                title: "Estado",
                width: '15%',
            },
            {
                data: 'master', // 8
                title: "Crg. Archivos",
                width: '15%',
                render: function (data, type, row) {
                    return `<i class="fas fa-upload icondocsup" onclick="modal_upDocsEstrOport(11, ${row.Id_Ticket})"></i>`;
                },
                visible: false
            },
            {
                data: 'horas', // 9
                title: "Hrs",
                width: '5%',
            },
        ],
        pageLength: 50,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, "TODOS"],
        ],
        initComplete: function (settings, json) {
            ajustarTablas();
        },
    });

    selectRow("tblDetalleAsignadosOport");

    tblPresupuestosRelacion = $("#tblPresupuestosRelacion").DataTable({
        destroy: true,
        processing: false,
        serverSide: true,
        scrollY: '20vh',
        select: true,
        pageResize: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "apiNumPresupuestos",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: $('#idOportunidadPresupuesto').val(),
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columns: [{
            data: "numPresupuesto", // 0
            title: "N° Presupuesto",
            className: "text-uppercase",
        },
        {
            data: "importe", // 1
            title: "Importe",
            className: "text-right",
        },
        {
            data: "aplica", // 2
            render: function (data, type, row) {
                if (data == 0) {
                    checked = '';
                } else {
                    checked = 'checked';
                }
                return ('<div class="custom-switch"><input type="checkbox" id="aplicaChk-' + row.idrelOportunidadPresupuesto + '" class="custom-control-input"' + checked + '><label for="aplicaChk-' + row.idrelOportunidadPresupuesto + '" onclick="activarPresupuesto(' + row.idrelOportunidadPresupuesto + ', ' + data + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
            },
            title: "Aplica",
            className: "text-center",
        },
        ],
        pageLength: -1,
        columnDefs: [
            {
                targets: [1],
                render: $.fn.dataTable.render.number(",", ".", 2),
            },
        ],
    });

    tblCambiosMenores = $("#tblCambiosMenores").DataTable({
        destroy: true,
        processing: false,
        serverSide: true,
        select: true,
        pageResize: true,
        // order: [
        //     [0, "asc"]
        // ],
        ajax: {
            url: serve + "apiOportunidadCambios",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: $('#idOportunidadCambios').val(),
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columns: [{
            data: "cambio", // 0
            title: "Cambio",
            className: "text-uppercase",
            width: '40%',
        },
        {
            data: "realizar", // 1
            render: function (data, type, row) {
                if (data == 0) {
                    checked = '';
                } else {
                    checked = 'checked';
                }

                disable = '';
                disabled = '';
                pointer_events = '';
                onclick_realizar = 'onclick="activarRealizar(' + row.idOportunidadCambiosMinimos + ', ' + data + ', ' + row.idCambio + ')"';

                if (row.rol != 4 && row.rol != 9 && row.rol != 1 && row.rol != 8) {
                    disable = 'disable';
                    disabled = 'disabled';
                    pointer_events = 'pointer-events: none;'
                    onclick_realizar = '';
                }

                return ('<div class="custom-switch" ><input ' + disabled + ' type="checkbox" id="realizarChk-' + row.idOportunidadCambiosMinimos + '" class="custom-control-input ' + disable + '"' + checked + ' style="' + pointer_events + '"><label for="realizarChk-' + row.idOportunidadCambiosMinimos + '" ' + onclick_realizar + ' class="custom-control-label ' + disable + '" style="cursor: pointer; ' + pointer_events + '"></label></div>');
            },
            title: "Realizar",
            className: "text-center",
            width: '10%',
        },
        {
            data: "valor", // 2
            render: function (data, type, row) {
                if (row.captura == 1 && row.realizar == 1) {
                    disabled = '';
                    required = '';
                    if (data == '') {
                        border = 'border-color: red';
                    } else {
                        border = 'border-color: green';
                    }
                } else {
                    disabled = 'disabled';
                    required = 'required';
                    border = '';
                }
                return ('<div><input ' + disabled + ' type="text" ' + required + ' id="captura_' + row.idOportunidadCambiosMinimos + '" value="' + data + '" onchange="asignarValorCambio(' + row.idOportunidadCambiosMinimos + ', ' + data + ')" class="form-control" style="height: 25px; ' + border + '"></div>');
            },
            title: "Valor",
            className: "text-center",
            width: '40%',
        },
        {
            data: "realizado", // 3
            render: function (data, type, row) {
                if (data == 0) {
                    checked = '';
                } else {
                    checked = 'checked';
                }

                display = '';

                if (row.realizar == 0) {
                    disabled = 'disabled';
                    class_disabled = 'disable';
                    pointer_events = 'pointer-events: none';
                    onclick_realizado = '';
                } else if (row.realizar == 1) {
                    disabled = '';
                    class_disabled = '';
                    pointer_events = '';
                    onclick_realizado = ' onclick="activarRealizado(' + row.idOportunidadCambiosMinimos + ', ' + data + ')"';
                    if (row.rol == 4 || row.rol == 9) {
                        display = 'display: none;'
                    }
                }

                return ('<div class="custom-switch" style="' + display + '"><input ' + disabled + ' type="checkbox" id="realizadoChk-' + row.idOportunidadCambiosMinimos + '" class="custom-control-input ' + class_disabled + '"' + checked + ' style="' + pointer_events + '"><label for="realizadoChk-' + row.idOportunidadCambiosMinimos + '" ' + onclick_realizado + ' class="custom-control-label ' + class_disabled + '" style="cursor: pointer; ' + pointer_events + '"></label></div>');
            },
            title: "Realizado",
            className: "text-center",
            width: '10% !important',
        },
        ],
        pageLength: -1,
        columnDefs: [
            {
                targets: [0, 1, 2, 3],
                orderable: false,
            },
        ],
    });

    tblOPMetricsRelacion = $("#tblOPMetricsRelacion").DataTable({
        destroy: true,
        processing: false,
        serverSide: true,
        scrollY: '20vh',
        select: true,
        pageResize: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "apiNumOPMetrics",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: $('#idOportunidadOPMetrics').val(),
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columns: [{
            data: "numOPMetrics", // 0
            title: "N° OP Metrics",
            className: "text-uppercase",
        },
        {
            data: "aplica", // 2
            render: function (data, type, row) {
                if (data == 0) {
                    checked = '';
                } else {
                    checked = 'checked';
                }
                return ('<div class="custom-switch"><input type="checkbox" id="aplicaChkOP-' + row.idrelOportunidadOPMetrics + '" class="custom-control-input"' + checked + '><label for="aplicaChkOP-' + row.idrelOportunidadOPMetrics + '" onclick="activarOPMetrics(' + row.idrelOportunidadOPMetrics + ', ' + data + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
            },
            title: "Aplica",
            className: "text-center",
        },
        ],
        pageLength: -1,
        // columnDefs: [
        //     {
        //         targets: [1],
        //         render: $.fn.dataTable.render.number(",", ".", 2),
        //     },
        // ],
    });

    // getCountSolicitudes()

    if ($('#idRol').val() == 1020) {
        $('#documentos-oport-tab').trigger('click');
    }
});

$("#searchOportFolio").keypress(function (e) {
    if (e.which == 13) { // loaderON();
        let texto = $(this).val();
        // if (texto != "") {
        loaderON();
        filterColumnOport(1, texto.trim());
        $.fn.dataTable.ext.search.pop();
        // }
    }
});

$("#searchOportSTOport").on('change', function () {
    let texto = $(this).val();
    // if (texto != "") {
    loaderON();
    filterColumnOport(5, texto.trim());
    $.fn.dataTable.ext.search.pop();
    // }
});

$("#searchOportNombre").keypress(function (e) {
    if (e.which == 13) { // loaderON();
        let texto = $(this).val();
        // if (texto != "") {
        loaderON();
        filterColumnOport(8, texto.trim());
        $.fn.dataTable.ext.search.pop();
        // }
        // buscarOport();
    }
});

$("#searchOportOMTC").keypress(function (e) {
    if (e.which == 13) { // loaderON();
        let texto = $(this).val();
        // if (texto != "") {
        loaderON();
        filterColumnOport(13, texto.trim());
        $.fn.dataTable.ext.search.pop();
        // }
        // buscarOport();
    }
});

$("#searchOportCliente").keypress(function (e) {
    if (e.which == 13) { // loaderON();
        let texto = $(this).val();
        // if (texto != "") {
        loaderON();
        filterColumnOport(14, texto.trim());
        $.fn.dataTable.ext.search.pop();
        // }
        // buscarOport();
    }
});

$("#searchOportAsignado").on('change', function () {
    let texto = $(this).val();
    // if (texto != "") {
    loaderON();
    filterColumnOport(15, texto.trim());
    $.fn.dataTable.ext.search.pop();
    // }
});

$("#searchOportEnCancha").on('change', function () {
    let texto = $(this).val();
    // if (texto != "") {
    loaderON();
    filterColumnOport(25, texto.trim());
    $.fn.dataTable.ext.search.pop();
    // }
});

$("#searchOportST").on('change', function (e) {
    let texto = $(this).val();
    // if (texto != "") {
    loaderON();
    filterColumnOport(3, texto.trim());
    $.fn.dataTable.ext.search.pop();
    // }
});

$("#searchOportPrepedido").on('change', function (e) {
    let texto = $(this).val();
    // if (texto != "") {
    loaderON();
    filterColumnOport(26, texto.trim());
    $.fn.dataTable.ext.search.pop();
    // }
});

function aplicarFiltroOport() {
    let textof = $("#searchOportFolio").val();
    let textosto = $("#searchOportSTOport").val();
    let texton = $("#searchOportNombre").val();
    let textoomtc = $("#searchOportOMTC").val();
    let textoc = $("#searchOportCliente").val();
    let textoa = $("#searchOportAsignado").val();
    let textoe = $("#searchOportEnCancha").val();
    let textost = $("#searchOportST").val();
    let textop = $("#searchOportPrepedido").val();
    // if (texto != "") {
    loaderON();

    if (textof != '') {
        filterColumnOport(1, textof.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textosto != '') {
        filterColumnOport(5, textosto.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (texton != '') {
        filterColumnOport(8, texton.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textoomtc != '') {
        filterColumnOport(13, textoomtc.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textoc != '') {
        filterColumnOport(14, textoc.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textoa != '') {
        filterColumnOport(15, textoa.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textoe != '') {
        filterColumnOport(25, textoe.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textost != '') {
        filterColumnOport(3, textost.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    if (textop != '') {
        filterColumnOport(26, textop.trim());
        $.fn.dataTable.ext.search.pop();
        return;
    }
    
    buscarOport();

}

function LimpiarFiltroOport() {
    // $('#searchOport').val(null);
    $('#searchOportFolio').val(null);
    $('#searchOportSTOport').val(null);
    $('#searchOportNombre').val(null);
    $('#searchOportOMTC').val(null);
    $('#searchOportCliente').val(null);
    $('#searchOportAsignado').val(null);
    $('#searchOportEnCancha').val(null);
    $('#searchOportST').val(null);
    $('#searchOportPrepedido').val(null);
    
    $('#tblOportunidades thead tr').remove();

    filterColumnOport(1, '');
    filterColumnOport(5, '');
    filterColumnOport(8, '');
    filterColumnOport(13, '');
    filterColumnOport(14, '');
    filterColumnOport(15, '');
    filterColumnOport(25, '');
    filterColumnOport(3, '');
    filterColumnOport(26, '');

    $.fn.dataTable.ext.search.pop();



    // buscarOport();
}

function filterColumnOport(i, texto) {
    $("#tblOportunidades").DataTable().column(i).search(texto.trim(), false, true).draw();
}

// $('#tblOportunidades thead tr').clone(true).addClass('filters').appendTo('#tblOportunidades thead');

function buscarOport() {
    ///----------------------------------------------------------------------------------------------------------------------------///
    ///--------------------------------------------------GRID TBL_OPORTUNIDADES----------------------------------------------------///
    ///----------------------------------------------------------------------------------------------------------------------------///
    ///----------------------------------------------------------------------------------------------------------------------------///

    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";

    const FROM_PATTERN_SHORT = "YYYY-MM-DD";
    const TO_PATTERN_SHORT = "DD/MM/YYYY";
    const TO_PATTERN_SHORT_2 = "MM/DD/YYYY";

    var texto = $('#searchOport').val();
    let roleOportunidades = $('#idRolOport').val();
    let idUsuario = $('#idUsuario').val();

    tblOportunidades = $("#tblOportunidades").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "idOportunidad",
        select: false,
        scrollY: false,
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        stateSave: true,
        order: [
            [1, "desc"]
        ],
        ajax: {
            url: serve + "api/oportunidades",
            type: "GET",
            dataType: "json",
            data: {
                texto: texto,
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [{
            extend: "colvis",
            columns: [0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
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
        columnDefs: [
            {
                targets: [18],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
            {
                targets: [2, 12],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
            },
            // {
            //     targets: [9],
            //     render: $.fn.dataTable.render.moment(
            //         FROM_PATTERN_SHORT,
            //         TO_PATTERN_SHORT
            //     ),
            // },
            {
                targets: [12, 7, 25, 26],
                visible: false,
            },
            {
                targets: [21, 22],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
            },
            {
                targets: [0, 2, 4, 6, 7, 9, 10, 11, 12, 16, 17, 18, 19, 20, 21, 22, 23],
                searchable: false,
            },
            {
                targets: 9,
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
                            row.inputRol == 1023 ||
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
            {
                targets: 10,
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
                            row.inputRol == 1023 ||
                            row.inputRol == 11 ||
                            row.inputRol == 8 ||
                            row.inputRol == 1
                        ) {
                            var $select = $(
                                '<select id="customer_grid_' +
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
            {
                targets: 11,
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
                    } else if (row.idEstatus == 5) {
                        if (
                            row.inputRol == 10 ||
                            row.inputRol == 1023 ||
                            row.inputRol == 11 ||
                            row.inputRol == 8 ||
                            row.inputRol == 1
                        ) {
                            var $select = $(
                                '<select id="revisado_grid_' +
                                row.idOportunidad +
                                '" class="form-control sltTblOpor" title="' +
                                data +
                                '" style="font-size: .7rem !important;" ><option selected disabled>SIN ASIGNAR</option></select>'
                            );
                            $.each(revisa, function (k, v) {
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
                    } else {

                        return (
                            '<span title="' +
                            data +
                            '">' +
                            data +
                            "</span>"
                        );

                        // if (
                        //     row.inputRol == 10 ||
                        //     row.inputRol == 1023 ||
                        //     row.inputRol == 11 ||
                        //     row.inputRol == 8 ||
                        //     row.inputRol == 1
                        // ) {
                        //     var $select = $(
                        //         '<select id="revisado_grid_' +
                        //         row.idOportunidad +
                        //         '" class="form-control sltTblOpor" title="' +
                        //         data +
                        //         '" style="font-size: .7rem !important;" ><option selected disabled>SIN ASIGNAR</option></select>'
                        //     );
                        //     $.each(revisa, function (k, v) {
                        //         var $option = $("<option></option>", {
                        //             text: v,
                        //             value: k,
                        //         });
                        //         if (data == v) {
                        //             $option.attr("selected", "selected");
                        //         }
                        //         $select.append($option);
                        //     });
                        //     return $select.prop("outerHTML");
                        // } else {
                        //     if (data == null || data == "") {
                        //         return "<span>SIN ASIGNAR</span>";
                        //     } else {
                        //         return "<span>" + data + "</span>";
                        //     }
                        // }
                    }
                },
            },
        ],
        columns: [
        {
            data: "tipo", // 0
            render: function (data, type, row) {
                if (data === "CLIENTE INACTIVO" || data === "Cliente Sin Actividad" || data === "Cliente Sin Venta" || data === "Cliente sin Venta y con Cotizacion"
                ) {
                    return (
                        '<span class="badge badge-info pl-2 pr-2 pt-1 pb-1" title="CLIENTE INACTIVO"><strong>CI</strong></span>'
                    );
                } else if (data === "CLIENTE ACTIVO") {
                    return (
                        '<span class="badge badge-success pl-2 pr-2 pt-1 pb-1" title="CLIENTE ACTIVO"><strong>CA</strong></span>'
                    );
                } else if (data === "CLIENTE") {
                    return (
                        '<span class="badge badge-success pl-2 pr-2 pt-1 pb-1" title="CLIENTE"><strong>C</strong></span>'
                    );
                } else if (data === "PROSPECTO INACTIVO" || data === "Prospecto Sin Actividad") {
                    return (
                        '<span class="badge badge-danger pl-2 pr-2 pt-1 pb-1" title="PROSPECTO INACTIVO"><strong>PI</strong></span>'
                    );
                } else if (data === "PROSPECTO ACTIVO") {
                    return (
                        '<span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="PROSPECTO ACTIVO"><strong>PA</strong></span>'
                    );
                } else if (data === "PROSPECTO") {
                    return (
                        '<span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="PROSPECTO"><strong>P</strong></span>'
                    );
                } else {
                    return (
                        '<span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="PROSPECTO"><strong>P</strong></span>'
                    );
                }
            },
            title: "Tipo C",
            width: "1rem",
            searchable: false,
            className: "dt-body-center text-center",
        },
        {
            data: "folio", // 1
            render: function (data, type, row) {
                if (row.Maquila == "on") {
                    return (
                        '<div class="rounded bg-primary box-table btn_con_maquila"><strong>' +
                        data +
                        "</strong></div>"
                    );
                } else {
                    return "<div><strong>" + data + "</strong></div>";
                }
            },
            width: "60px",
            title: "Folio",
            searchable: true,
            className: "dt-body-center text-center",
        },
        {
            data: "fecCreacion", // 2
            name: "fecCreacion",
            width: "2rem",
            className: "dt-body-center text-center",
            title: "Creación",
        },
        {
            data: "descEstatus", // 17 -> 4
            render: function (data, type, row) {
                if (row.Cancelada == "1") {
                    return '<strong class="text-red">Cancelada</strong>';
                } else {
                    return data;
                }
            },
            // name: "descEStatus",
            title: "ST",
            width: "2rem",
            className: "dt-body-center text-center",
        },
        {
            data: "valorEstatus", // 18 -> 5
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
            data: "ST_Oport", // 6
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
            data: "fecTope", // 7
            name: "fecTope",
            className: "dt-body-center text-center w-12",
            title: "Fec Tope",
        },
        {
            data: 'Premio', // 8
            render: function(data, type, row){
                if (data == 0 || data == null || data == '') {
                    return '<span></span>';
                } else {
                    return '<span><i class="fas fa-medal text-gold fa-2x"></i></span>';
                }
            },
            title: 'Premio',
            className: 'dt-body-center',
            visible: false
        },
        {
            data: "nomOportunidad", // 9
            name: "nomOportunidad",
            render: function (data, type, row) {
                if ($.trim(row.inputEditar) === "Editar") {
                    return (
                        '<a href="oportunidades/' +
                        row.idOportunidad +
                        '/edit" onclick="loaderON()" class="text-uppercase box-table btn_editar_oportunidad">' +
                        data +
                        "</a>"
                    );
                } else {
                    return '<span class="text-uppercase">' + data + '</span>';
                }
            },
            width: "5rem",
            title: "Nombre",
        },
        {
            data: "Cotizado", // 10
            name: "Cotizado",
            width: "2rem",
            title: "Cotizado por",
            className: "dt-body-center text-center",
        },
        {
            data: "Atendido", // 11
            name: "Atendido",
            width: "2rem",
            title: "Atendido por",
            className: "dt-body-center text-center",
        },
        {
            data: "Revisado", // 12
            name: "Revisado",
            width: "2rem",
            title: "Revisado por",
            className: "dt-body-center text-center",
        },
        {
            data: "fecReqPreCot", // 13
            name: "fecReqPreCot",
            width: "1rem",
            className: "dt-body-center text-center",
            title: "Req Pre/Cot",
            visible: false
        },
        {
            data: "ops", // 14
            name: "ops",
            title: "OP MTC",
            width: "1rem",
            render: function (data, type, row) {
                if (data == null || data == "") {
                    return row.numOrdenMTC;
                } else {
                    return data;
                }
            },
            className: "dt-body-center text-center",
        },
        {
            data: "razonsocial", // 15
            name: "razonsocial",
            render: function (data, type, row) {
                // if (row.ProspectoActivo == 0 || row.CreaOportunidades == 0 || row.EsProspecto == 1) {
                // if (row.EsCliente == 1 && row.ProspectoActivo == 0) {
                //     return '<span class="text-uppercase">' + data + '</span>';
                // } else {
                if (
                    row.inputRol == 1 ||
                    row.inputRol == 5 ||
                    row.inputRol == 7 ||
                    row.inputRol == 2 ||
                    row.inputRol == 11 ||
                    row.inputRol == 8
                ) {
                    if ($.trim(row.inputEditar) === "Editar") {
                        // if (row.tipo == 'P') {
                        //     // return row.razonsocialP;
                        //     return (
                        //         '<a href="prospectos/' +
                        //         row.idProspecto +
                        //         '/edit" class="text-uppercase box-table btn_editar_cliente_opor" onclick="loaderON()">' +
                        //         row.razonsocialP +
                        //         "</a>"
                        //     );
                        // } else {
                        //     return (
                        //         '<a href="clientes/' +
                        //         row.ClienteINT +
                        //         '/edit" class="text-uppercase box-table btn_editar_cliente_opor" onclick="loaderON()">' +
                        //         data +
                        //         "</a>"
                        //     );
                        // }
                        return (
                            '<a href="clientes/' +
                            row.ClienteINT +
                            '/edit" class="text-uppercase box-table btn_editar_cliente_opor" onclick="loaderON()">' +
                            data +
                            "</a>"
                        );
                    } else {
                        return data;
                    }
                } else {
                    // Descomentar en caso de fallor en la edicion de clientes
                    // if (
                    //     $.trim(row.inputLogin) == $.trim(row.Login) ||
                    //     $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) ||
                    //     $.trim(row.LoginAgente) == $.trim(row.inputLogin) ||
                    //     $.trim(row.idAgente2) == $.trim(row.idUsuario) ||
                    //     $.trim(row.idAgente3) == $.trim(row.idUsuario)
                    // ) {
                    //     if ($.trim(row.inputEditar) === "Editar") {
                    //         // if (row.tipo == 'P') {
                    //         //     return (
                    //         //         '<a href="prospectos/' +
                    //         //         row.idProspecto +
                    //         //         '/edit" class="text-uppercase box-table btn_editar_cliente_opor" onclick="loaderON()">' +
                    //         //         row.razonsocialP +
                    //         //         "</a>"
                    //         //     );
                    //         // } else {
                    //         //     return (
                    //         //         '<a href="clientes/' +
                    //         //         row.ClienteINT +
                    //         //         '/edit" class="text-uppercase box-table btn_editar_cliente_opor">' +
                    //         //         data +
                    //         //         "</a>"
                    //         //     );
                    //         // }
                    //         return (
                    //             '<a href="clientes/' +
                    //             row.ClienteINT +
                    //             '/edit" class="text-uppercase box-table btn_editar_cliente_opor">' +
                    //             data +
                    //             "</a>"
                    //         );
                    //     } else {
                    //         return data;
                    //     }
                    // } else {
                    //     return data;
                    // }

                    if (row.inputRol == 4 || row.inputRol == 9) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return (
                                '<a href="clientes/' +
                                row.ClienteINT +
                                '/edit" class="text-uppercase box-table btn_editar_cliente_opor">' +
                                data +
                                "</a>"
                            );
                        }
                    } else {
                        if (
                            $.trim(row.inputLogin) == $.trim(row.Login) ||
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) ||
                            $.trim(row.LoginAgente) == $.trim(row.inputLogin) ||
                            $.trim(row.idAgente2) == $.trim(row.idUsuario) ||
                            $.trim(row.idAgente3) == $.trim(row.idUsuario)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                // if (row.tipo == 'P') {
                                //     return (
                                //         '<a href="prospectos/' +
                                //         row.idProspecto +
                                //         '/edit" class="text-uppercase box-table btn_editar_cliente_opor" onclick="loaderON()">' +
                                //         row.razonsocialP +
                                //         "</a>"
                                //     );
                                // } else {
                                //     return (
                                //         '<a href="clientes/' +
                                //         row.ClienteINT +
                                //         '/edit" class="text-uppercase box-table btn_editar_cliente_opor">' +
                                //         data +
                                //         "</a>"
                                //     );
                                // }
                                return (
                                    '<a href="clientes/' +
                                    row.ClienteINT +
                                    '/edit" class="text-uppercase box-table btn_editar_cliente_opor">' +
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
                }
                // }
            },
            width: "4rem",
            title: "Cliente",
        },
        {
            data: "Agente", // 16
            // name: "Agente",
            title: "Asignado",
            width: "2rem",
            className: "dt-body-left text-left",
        },
        {
            data: "desTipoOportundidad", // 17
            name: "desTipoOportundidad",
            title: "Tipo",
            width: "2rem",
            className: "dt-body-center text-center",
        },
        {
            data: "descProbabilidad", // 18
            name: "descProbabilidad",
            title: "Prob.",
            width: "2rem",
            className: "dt-body-center text-center",
            visible: false,
        },
        {
            data: "Valor", // 19
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
            data: "fecOrdenConcluida", // 20
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
            data: "archivoAutorizado", // 21
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
            data: "FechaEntrega", // 22
            render: function (data, type, row) {
                let dataFormat

                if (data != null) {
                    dataFormat = data.split('-')
                    data = dataFormat[2] + '/' + dataFormat[1] + '/' + dataFormat[0]
                }
                
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
        {
            data: 'fecArchivoAutorizado', // 23
            title: 'Fec Arc Aut'
        },
        {
            data: 'inputSolicitarFt', // 24
            title: 'Sol. F.T',
            className: "dt-body-center text-center",
            searchable: false,
        },
        {
            data: 'tienePrepedido', // 24
            title: 'Tiene Prepedido',
            className: "dt-body-center text-center w-12",
            searchable: false,
        },
        {
            data: 'enCancha', // 24
            title: 'En Cancha',
            className: "dt-body-center text-center",
            visible: false,
        },
        {
            data: 'solicita_prepedido_f', // 26
            title: 'Solicita Prepedido',
            searchable: true,
            className: "dt-body-center text-center",
        },
        ],
        pageLength: 50,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, "TODOS"],
        ],
        initComplete: function (settings, json) {
            // ====================================================================================================

            if (window.sessionStorage) {
                evento = sessionStorage.getItem("evento");
                if (evento == null) {
                    loaderON();
                    var texto = $("#searchOportST").val();
                    if ($("#searchOportST").val() != "") {
                        loaderON();
                        $("#evento").text("");
                        filterColumn(4, texto.trim());
                        $.fn.dataTable.ext.search.pop();
                    }
                } else {
                    //                var texto = evento;
                    loaderON();
                    filterGlobalCards(3, evento.trim());
                }
                sessionStorage.removeItem("evento");
                $("#searchOportST").val(texto);
            } else {
                throw new Error("Tu Browser no soporta sessionStorage!");
            }

            loaderOut();
        },
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin Oportunidades</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' Oportunidades</strong>';
        },
    });

    // // $('#tblOportunidades thead tr').remove('#tblOportunidades thead');
    // $('#tblOportunidades thead tr').clone(true).appendTo('#tblOportunidades thead');
    // $('#tblOportunidades thead tr:eq(1) th').each(function(i){
    //     var title = $(this).text();
    //     $(this).html('<input type="text" placeholder="' + title + '">');
    //     $('input', this).on('keypress', function(e){
    //         if (e.which == 13) {
    //             if (tblOportunidades.column(i).search() !== this.value ) {
    //                 tblOportunidades
    //                 .column(i)
    //                 .search(this.value)
    //                 .draw();
    //             }
    //         }

    //     });
    // });
    // $('#tblOportunidades thead tr').remove('#tblOportunidades thead');

}

/**
 * Función para liberar la oportunidad
 * 
 * @param {*} idOportunidad
 * @param {*} type 1 = liberar, 0 = rechazar
 * @param {*} isCancelled
 * @return {void}
 */
function liberarOportunidad(idOportunidad, type, isCancelled = 0) {
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
        title: "Oportunidades",
        text: type == 1 ? "Liberar la oportunidad Seleccionada?" : "Cancelar la oportunidad Seleccionado?",
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
            formDataUpdOportunidad = new FormData();
            formDataUpdOportunidad.append("_token", CSRF_TOKEN);
            formDataUpdOportunidad.append("_method", "POST");
            formDataUpdOportunidad.append("idOportunidad", idOportunidad);
            formDataUpdOportunidad.append("tipo", type);

            if (type == 0) {
                formDataUpdOportunidad.append("motivo", result.value);
            } else {
                formDataUpdOportunidad.append("motivo", '');
            }

            $.ajax({
                url: serve + "liberarOportunidad",
                async: true,
                type: "POST",
                data: formDataUpdOportunidad,
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
                            title: type == 1 ? "Oportunidad Liberada" : "Oportunidad Rechazada",
                        });

                        tblOportunidades.draw(false);
                    }
                }
            });
        } else {
            // Resetear el radio button
            let radioButton =  document.getElementById(`inp${idOportunidad}-${type}`);

            if (radioButton) {
                if (isCancelled == 0) {
                    // remove the checked attribute
                    radioButton.removeAttribute('checked');
                    radioButton.checked = false;
                } else {
                    let radioButtonCancelled =  document.getElementById(`inp${idOportunidad}-0`);
                    radioButtonCancelled.checked = true;
                    radioButton.removeAttribute('checked');
                }

            }
        }
    });

}

let myVar = setInterval(autoReloadPage, 600000);

function autoReloadPage() {
    tblOportunidades.ajax.reload();
}

let myVar2 = setInterval(obtenerSolicitudesOP, 600000);

function autoReloadNoty() {
    obtenerSolicitudesOP();
}

var mensaje = "";
var icono = "";
$(function () {
    $("#formOporCreate form")
        .validator()
        .on("submit", function (e) {
            if (!e.isDefaultPrevented()) {
                mensaje = "Oportunidad Creada";
                icono = "success";
                // regresar();
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
        .on("submit", function (e) {
            if (!e.isDefaultPrevented()) {
                loaderON();
                mensaje = "Oportunidad Actualizada";
                icono = "success";

                // $.ajax({
                //     url: serve + 'update/' + $('#idOportunidad').val(),
                //     type: 'PUT',
                //     dataType: 'JSON',
                //     data: $('formOporEdit form').serialize(),
                //     success: function (data) {
                //         mensaje = "Oportunidad Actualizada";
                //         icono = "success";
                //     },
                // });
                // regresar();
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
                cancelButtonText: "Cancelar",
                confirmButtonText: "Guardar",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                target: document.getElementById("formOporEdit"),
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

$("#numOPMetrics").autocomplete({
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

function cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario) {
    mensaje = "Oportunidad Actualizada";
    // console.log(id_usuario);
    Swal.fire({
        title: "Oportunidades",
        text: "Actualizar 'Cotizado por' de la Oportunidad Seleccionada?",
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
                    window.location.href = "oportunidades";
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
            window.location.href = "oportunidades";
        }
    });
}

function cambiar_customer(id, customer, nombre_customer, id_usuario) {
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
                url: serve + "cambiarCustomer",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    customer: customer,
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
                    window.location.href = "oportunidades";
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
            $("#customer_grid_" + id)
                .find("option")
                .attr("selected", false);
            $(
                "#customer_grid_" +
                id +
                '  option:contains("' +
                nombre_customer +
                '")'
            ).attr("selected", true);
            window.location.href = "oportunidades";
        }
    });
}

function cambiar_revisado(id, revisado, nombre_revisado, id_usuario) {
    mensaje = "Oportunidad Actualizada";
    // console.log(id_usuario);
    Swal.fire({
        title: "Oportunidades",
        text: "Actualizar 'Revisado por' de la Oportunidad Seleccionada?",
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
                url: serve + "cambiarRevisado",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    revisado: revisado,
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
                    window.location.href = "oportunidades";
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
            $("#revisado_grid_" + id)
                .find("option")
                .attr("selected", false);
            $(
                "#revisado_grid_" +
                id +
                '  option:contains("' +
                nombre_revisado +
                '")'
            ).attr("selected", true);
            window.location.href = "oportunidades";
        }
    });
}

function mostrarGeneralOport() {
    idrol = document.getElementById("idRol");
    permisoEstructural = document.getElementById('permisoEstructural');
    $("#general-oport-tab").addClass("active");
    $("#general-oport").addClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#estructural-oport-tab").removeClass("active");
    $("#estructural-oport").removeClass("show");

    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    // $("#correos-oport-tab").removeClass("active");
    // $("#correos-oport").removeClass("show");

    // $("#entregas-oport-tab").removeClass("active");
    // $("#entregas-oport").removeClass("show");
    // document.getElementById("btnClonarOportunidad").style.display = "block";

    if (permisoEstructural.value == 'SinAcceso') {
        document.getElementById("estructural-oport-tab").style.display = "none";
        document.getElementById("estructural-oport-tab-li").style.display = "none";
    }

    if (idrol.value == 1013) {
        if ($('#idUsuario').val() == '12449') {
            document.getElementById("documentos-oport-tab").style.display = "block";
        } else {
            document.getElementById("documentos-oport-tab").style.display = "none";
        }

        // document.getElementById("correos-oport-tab").style.display = "none";
        // document.getElementById("entregas-oport-tab").style.display = "none";

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
            if (idRol.value == 1013 || idRol.value == 8 || idRol.value == 1) {
                $('#archivoAutorizado').prop('disabled', false);
                $('#archivoAutorizado').addClass('pointer');
                $('#archivoAutorizadoLabel').addClass('pointer');
            } else {
                $('#archivoAutorizadoLabel').prop('disabled', true);
                $('#archivoAutorizadoLabel').removeClass('pointer');
            }
        } else {
            $("#archivoAutorizado").prop("disabled", false);
            $("#archivoAutorizadoLabel").addClass("pointer");
        }
    } else if (idrol.value == 1014) {
        document.getElementById("elementos-oport-tab").style.display = "none";
        document.getElementById("estructural-oport-tab").style.display = "none";
        document.getElementById("documentos-oport-tab").style.display = "none";
        // document.getElementById("correos-oport-tab").style.display = "none";
        // document.getElementById("entregas-oport-tab").style.display = "none";
        // document.getElementById("btnClonarOportunidad").style.display = "none";

        var nodes_general = document
            .getElementById("tabGeneral")
            .getElementsByTagName("*");
        for (var i = 0; i < nodes_general.length; i++) {
            nodes_general[i].disabled = true;
        }

        $("#div-btnrelacionar").addClass("disable");

        $("#btnCancelar").attr("onclick", "");
        $("#btnCancelar").off("click");

        $("#btnSolFecEntrega").attr("onclick", "");
        $("#btnSolFecEntrega").off("click");
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

        if (permisoEstructural.value == 'SinAcceso') {
            document.getElementById("estructural-oport-tab").style.display = "none";
            document.getElementById("estructural-oport-tab-li").style.display = "none";
        }

    } else {
        document.getElementById("elementos-oport-tab").style.display = "block";

        if (permisoEstructural.value == 'SinAcceso') {
            document.getElementById("estructural-oport-tab").style.display = "none";
            document.getElementById("estructural-oport-tab-li").style.display = "none";
        }

        document.getElementById("documentos-oport-tab").style.display = "block";
        // document.getElementById("correos-oport-tab").style.display = "block";
        // document.getElementById("entregas-oport-tab").style.display = "block";

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
    document.getElementById("estructural-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "none";
    // document.getElementById("correos-oport-tab").style.display = "none";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "block";
    document.getElementById("sOportBtnCancelar").style.display = "block";

    tblElementosPresupuestados.draw();
}

function mostrarElementosOport() {
    permisoEstructural = document.getElementById('permisoEstructural');

    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").addClass("active");
    $("#elementos-oport").addClass("show");

    $("#estructural-oport-tab").removeClass("active");
    $("#estructural-oport").removeClass("show");

    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    // $("#correos-oport-tab").removeClass("active");
    // $("#correos-oport").removeClass("show");

    // $("#entregas-oport-tab").removeClass("active");
    // $("#entregas-oport").removeClass("show");

    if (permisoEstructural.value == 'SinAcceso') {
        document.getElementById("estructural-oport-tab").style.display = "none";
        document.getElementById("estructural-oport-tab-li").style.display = "none";
    }

    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "block";
    document.getElementById("estructural-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "none";
    // document.getElementById("correos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    // document.getElementById("btnClonarOportunidad").style.display = "none";

    $("#sOportBtnBack").addClass("col-12");
    tblElementosPresupuestados.draw();

    $('#historicoCambiosContainer').html('');
}

function mostrarEstructuralOport() {
    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#estructural-oport-tab").addClass("active");
    $("#estructural-oport").addClass("show");


    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    // $("#correos-oport-tab").removeClass("active");
    // $("#correos-oport").removeClass("show");

    // $("#entregas-oport-tab").removeClass("active");
    // $("#entregas-oport").removeClass("show");

    document.getElementById("estructural-oport-tab-li").style.display = "block";
    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "none";
    document.getElementById("estructural-oport").style.display = "block";
    document.getElementById("documentos-oport").style.display = "none";
    // document.getElementById("correos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    // document.getElementById("btnClonarOportunidad").style.display = "none";

    $("#sOportBtnBack").addClass("col-12");
    tblDetalleAsignadosOport.ajax.reload();
    ajustarTablas();
}

function mostrarDocumentosOport() {
    permisoEstructural = document.getElementById('permisoEstructural');
    tblDocsOport();
    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#estructural-oport-tab").removeClass("active");
    $("#estructural-oport").removeClass("show");

    $("#documentos-oport-tab").addClass("active");
    $("#documentos-oport").addClass("show");

    // $("#correos-oport-tab").removeClass("active");
    // $("#correos-oport").removeClass("show");

    // $("#entregas-oport-tab").removeClass("active");
    // $("#entregas-oport").removeClass("show");

    if (permisoEstructural.value == 'SinAcceso') {
        document.getElementById("estructural-oport-tab").style.display = "none";
        document.getElementById("estructural-oport-tab-li").style.display = "none";
    }

    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "none";
    document.getElementById("estructural-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "block";
    // document.getElementById("correos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    // document.getElementById("btnClonarOportunidad").style.display = "none";
    $("#sOportBtnBack").addClass("col-12");

    tblElementosPresupuestados.draw();
}

// function mostrarCorreosOport() {
//     permisoEstructural = document.getElementById('permisoEstructural');
//     tblDocs();
//     $("#general-oport-tab").removeClass("active");
//     $("#general-oport").removeClass("show");

//     $("#elementos-oport-tab").removeClass("active");
//     $("#elementos-oport").removeClass("show");

//     $("#estructural-oport-tab").removeClass("active");
//     $("#estructural-oport").removeClass("show");

//     $("#documentos-oport-tab").removeClass("active");
//     $("#documentos-oport").removeClass("show");

//     $("#correos-oport-tab").addClass("active");
//     $("#correos-oport").addClass("show");

//     $("#entregas-oport-tab").removeClass("active");
//     $("#entregas-oport").removeClass("show");

//     if (permisoEstructural.value == 'SinAcceso') {
//         document.getElementById("estructural-oport-tab").style.display = "none";
//         document.getElementById("estructural-oport-tab-li").style.display = "none";
//     }

//     document.getElementById("general-oport").style.display = "none";
//     document.getElementById("elementos-oport").style.display = "none";
//     document.getElementById("estructural-oport").style.display = "none";
//     document.getElementById("documentos-oport").style.display = "none";
//     document.getElementById("correos-oport").style.display = "block";
//     document.getElementById("entregas-oport").style.display = "none";
//     document.getElementById("sOportBtnSave").style.display = "none";
//     document.getElementById("sOportBtnCancelar").style.display = "none";
//     document.getElementById("sOportBtnTEntrega").style.display = "none";
//     document.getElementById("btnClonarOportunidad").style.display = "none";
//     $("#sOportBtnBack").addClass("col-12");

//     tblElementosPresupuestados.draw();
// }

function mostrarEntregasOport() {
    permisoEstructural = document.getElementById('permisoEstructural');
    $("#general-oport-tab").removeClass("active");
    $("#general-oport").removeClass("show");

    $("#elementos-oport-tab").removeClass("active");
    $("#elementos-oport").removeClass("show");

    $("#estructural-oport-tab").removeClass("active");
    $("#estructural-oport").removeClass("show");

    $("#documentos-oport-tab").removeClass("active");
    $("#documentos-oport").removeClass("show");

    // $("#correos-oport-tab").removeClass("active");
    // $("#correos-oport").removeClass("show");

    $("#entregas-oport-tab").addClass("active");
    $("#entregas-oport").addClass("show");

    if (permisoEstructural.value == 'SinAcceso') {
        document.getElementById("estructural-oport-tab").style.display = "none";
        document.getElementById("estructural-oport-tab-li").style.display = "none";
    }

    document.getElementById("general-oport").style.display = "none";
    document.getElementById("elementos-oport").style.display = "none";
    document.getElementById("estructural-oport").style.display = "none";
    document.getElementById("documentos-oport").style.display = "none";
    // document.getElementById("correos-oport").style.display = "none";
    document.getElementById("entregas-oport").style.display = "block";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";
    document.getElementById("sOportBtnTEntrega").style.display = "none";
    // document.getElementById("btnClonarOportunidad").style.display = "none";
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
        $("#scrollbarTabsModalRound").css("height", "76vh");
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
    var FechaPrimeraEntregaInput = document.getElementById("FechaPrimeraEntregaInput");
    var FechaUltimaEntregaInput = document.getElementById("FechaUltimaEntregaInput");

    var dias = document.getElementById("dias");
    var fecha = document.getElementById("fecha");

    // if (dias.checked == false && fecha.checked == false) {
    //     Swal.fire("¡Selecciona una opción!");
    // } else {
    // if (dias.checked == true && DiasEntrega.value == "") {
    //     Swal.fire("¡Captura los Días de Entrega!");
    // } else if (fecha.checked == true && FechaAutoriza.value == "") {
    //     Swal.fire("¡Captura la Fecha del Archivo!");
    // } else if (fecha.checked == true && FechaEntrega.value == "") {
    //     Swal.fire("¡Captura la Fecha de Entrega!");
    if (FechaPrimeraEntregaInput.value == "") {
        Swal.fire("¡Captura la Primera Fecha de Entrega!");
        saveBtnTEntrega.disabled = false;
    } else if (FechaUltimaEntregaInput.value == "") {
        Swal.fire("¡Captura la Última Fecha de Entrega!");
        saveBtnTEntrega.disabled = false;
    } else if (Comentarios.value == "") {
        Swal.fire("¡Captura los Comentarios!");
        saveBtnTEntrega.disabled = false;
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
                FechaPrimeraEntrega: FechaPrimeraEntregaInput.value,
                FechaUltimaEntrega: FechaUltimaEntregaInput.value,
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
    // }
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
        [6, 2],
        [20, 3],
        [6, 4],
        [7, 4],
        [1, 5],
        [16, 9],
        [20, 11],
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

function obtenerSolicitudesOP() {
    $.ajax({
        url: serve + "obtenerOportunidadSolOPCincoDias",
        type: "GET",
        dataType: "json",
        success: function (data) {
            data.forEach((element) => {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    icon: "info",
                    showConfirmButton: false,
                    timer: 5000,
                });

                Msg.fire({
                    title: "Solicitó OP para la Oportunidad " + element.folio,
                });
            });
        },
        error: function () { },
    });
}

function obtenerOportunidadPTVeinteDias() {
    $.ajax({
        url: serve + "obtenerOportunidadPTVeinteDias",
        type: "GET",
        dataType: "json",
        success: function (data) {
            btnAgregarOportunidad = document.getElementById("btnAgregarOportunidad");
            const Msg = Swal.mixin({
                toast: false,
                position: "center",
                icon: "error",
                showConfirmButton: false,
                customClass: {
                    popup: "colored-toast",
                },
            });
            if (btnAgregarOportunidad != null) {
                if (data.length > 0) {
                    $("#btnAgregarOportunidad").addClass("disabled-agregar");
                    Msg.fire({
                        title: data[0].Nombre +
                            "\nLa Oportunidad No. " +
                            data[0].folio +
                            '\n tiene más de 20 días sin definir\n como "GANADO" o "PERDIDO".\n No podrás agregar una nueva oportunidad.',
                        html: `<div class="text-warning mt-3">
                            <a href="${serve}oportunidades/${data[0].idOportunidad}/edit" class="btn btn-info text-white">
                                Editar Oportunidad
                            </a>
                        </div>`,
                    });
                    // flasher.toastr.info('Tienes al menos una Oportunidad\n con mas de 20 días sin marcar\n como GANADO o PERDIDO.\n No podrás agregar una nueva oportunidad.', data.AgenteVentas);
                } else {
                    $("#btnAgregarOportunidad").removeClass("disabled-agregar");
                    // btnAgregarOportunidad.style.cursor = 'pointer !important';
                }
            }
        },
        error: function () { },
    });
}

function obtenerOportunidadSolOPCincoDias() {
    $.ajax({
        url: serve + "obtenerOportunidadSolOPCincoDias",
        type: "GET",
        dataType: "json",
        success: function (data) {
            const Msg = Swal.mixin({
                toast: false,
                position: "center",
                icon: "error",
                showConfirmButton: true,
                customClass: {
                    popup: "colored-toast",
                },
            });
            if (data.length > 0) {
                // if (data[0].idRol == 3 || data[0].idRol == 1016) {
                if (data[0].idRol == 3) {
                    $("#btnAgregarOportunidad").addClass("disabled-agregar");
                    Msg.fire({
                        title: data[0].Customer +
                            "\nLa Oportunidad No. " +
                            data[0].folio +
                            "\n tiene más de 5 días de que\n solicitó OP.\n Favor de atenderla.",
                    });
                } else {
                    $("#btnAgregarOportunidad").removeClass("disabled-agregar");
                }
            }
        },
        error: function () { },
    });
}

function clonarOportunidad() {
    idOportunidad = $("#idOportunidad").val();

    let idU = $('#idUsuario').val()

    Swal.fire({
        title: "Oportunidades",
        html:
            '<div>¿Clonar esta Oportunidad?</div><br>' +
            '<div class="text-warning">' +
            '<i class="fas fa-exclamation-triangle"> </i>' +
            ' Se creará con un folio nuevo y <br>' +
            ' se asociaran los elementos presupuestados.' +
            '</div>' +
            '</div>',
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
            if (result.isConfirmed) {
                loaderON();
                precios = true;
                $.ajax({
                    url: serve + "clonarOportunidad",
                    type: "POST",
                    data: {
                        idOportunidad: idOportunidad,
                        precios: precios,
                        // recotizacion: recotizacion,
                        // sin_cambios: sin_cambios,
                        // con_cambios: con_cambios,
                        _method: "POST",
                        _token: CSRF_TOKEN,
                    },
                    success: function () {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 3000,
                        });
                        Msg.fire({
                            title: "Oportunidad Clonada",
                        });
                        // window.location.href = serve + "oportunidades";
                    },
                    error: function () { },
                });
            } else {
                loaderOut();
            }
        } else {
            loaderOut();
        }
    });
}

// function cambiarTipoOportunidad(valor) {
//     if (valor == 0) {
//         // $('#divReferenciaCotizacion').prop('hidden', true);
//         $('#divConSinCambios').prop('hidden', true);
//         $('#idTipoOportunidadEstatusSin').prop('checked', true);
//     } else {
//         // $('#divReferenciaCotizacion').prop('hidden', false);
//         $('#divConSinCambios').prop('hidden', false);
//         $('#idTipoOportunidadEstatusSin').prop('checked', true);
//     }
// }

// $('input:radio[name="idTipoOportunidadEstatus"]').on('change', function () {
//     valor = $(this).val();
//     console.log(valor);
// })

function agregarTicketOport(tipo, accion) {

    $('#selectFolio').show();
    $('#folioOportunidadText').hide();

    $("#modal-create-ticket").modal("show");
    $("#modal-create-ticket").find("form").trigger("reset");
    clonarTicket.style.display = 'none';
    let modalEstr = document.querySelector("#modal-create-ticket");
    modalEstr.classList.add("showModal");

    document.getElementById('isCancelledESTR').style.display = 'none';

    let valueOportunidad = $('#idOportunidad').val() + ' - ' + $('#idClienteEnt').val() + ' - ' + $('#razonsocialOport').val();
    let texto = $('#oportFolio').val() + ' - ' + $('#nomOportunidad').val();

    $("#btnBackTicket").text("Regresar");
    var dateToday = new Date();
    clientes = $('#idClienteEstr');
    barnices = $('#barnices');

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

    if ($('#folioOportunidad option[value="' + valueOportunidad + '"]').length) {
        $('#folioOportunidad').val(valueOportunidad).trigger('change');
    }

    $('#documentos-ticket-tab').addClass('d-none');

    setTimeout(function () {
        // Bloquear el folioOportunidad select2
        $('#folioOportunidad').prop('disabled', true);
    }, 1000);

    // $.ajax({
    //     url: serve + 'obtenerClientesCombo',
    //     type: 'GET',
    //     dataType: 'JSON',
    //     success: function (r) {
    //         id_cliente = $('#idCliente').val();
    //         clientes.find("option").remove();

    //         $(r).each(function (i, v) {
    //             var $option = $("<option></option>", {
    //                 text: v.razonsocial,
    //                 value: v.idClienteINT,
    //             });
    //             if (id_cliente == v.idCliente) {
    //                 $option.attr("selected", "selected");
    //             }
    //             clientes.append($option);
    //         });
    //         validarRequeridosEstr();
    //     },
    //     error: function (r) {
    //         Swal.fire("Ocurrio un error en el servidor ..");
    //     },
    // });

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

    nomOportunidadEstr.readOnly = false;
    idClienteEstr.disabled = true;
    idClienteEstr.value = $('#idClienteEnt').val();
    $('#nomOportunidadEstr').val($('#nomOportunidad').val());
    $('#nomOport').val($('#nomOportunidad').val());

    $('#idClienteINTEstr').val($('#idClienteEnt').val());
    $('#idUsuarioEstr').val($('#idUsuario').val());
    $('#agenteEstr').val($('#Agente').val());
    $('#idAgenteEstr').val($('#idAgente').val());
    $('#idOportunidadEstr').val($('#idOportunidad').val());

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

    // setTimeout(function () {
    //     $('#folioOportunidad').val().trigger('change');
    // }, 1000);

    // loaderModalOut();
    // $("#modal-create-ticket").modal("show");
    mostrarGeneralTicket();
    $('#btnSaveTicket').prop('disabled', true);
}

function guardarTicketOport() {
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

                setTimeout(() => {
                    tblDetalleAsignadosOport.ajax.reload()
                    tblDetalleAsignadosOport.draw();

                    document.getElementById('estructural-oport-tab').click();
                }, 1000);
            }
        },
    });
}

function cambiar_estatus(id, estatus) {
    $.ajax({
        url: serve + 'cambiarEstatusESTR',
        type: "PATCH",
        data: {
            _method: "PATCH",
            id: id,
            estatus: estatus,
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
                title: 'Estatus Cambiado',
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
                title: "Ocurrió un error",
            });
        },
    });
}

function cambiar_operador(id, operador) {
    $.ajax({
        url: serve + 'cambiarOperadorESTR',
        type: "PATCH",
        data: {
            _method: "PATCH",
            id: id,
            operador: operador,
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

function editarTicketOport(id) {
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

            $('#documentos-ticket-tab').removeClass('d-none');

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

                if ($('#folioOportunidad option[value="' + valueOportunidad + '"]').length) {
                    $('#folioOportunidad').val(valueOportunidad).trigger('change');
                }

                setTimeout(function () {
                    // Bloquear el folioOportunidad select2
                    $('#folioOportunidad').prop('disabled', true);
                }, 1000);
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


function saveElemento(idOportunidad) {
    loaderON();
    numPresupuestoMTC = $("#numPresupuesto").val();
    addValorOportunidad(idOportunidad, numPresupuestoMTC);

}

function addValorOportunidad(idOportunidad, numPresupuestoMTC) {

    $.ajax({
        url: serve + "api/addValorOportunidad",
        type: "PATCH",
        data: {
            _method: "PATCH",
            idOportunidad: idOportunidad,
            numPresupuestoMTC: numPresupuestoMTC,
            // _token: CSRF_TOKEN,
        },
        success: function (data) {
            loaderOut();
            $("#editPresupuesto" + idOportunidad).show();
            $("#savePresupuesto" + idOportunidad).hide();
            $("#noPresupuesto-" + idOportunidad).prop('readonly', true);
            $("#tblElementosPresupuestados").DataTable().ajax.reload();
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: 'Presupuesto Actualizado',
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
                title: 'Error al Actualizar',
            });
            // window.location.href = serve + "Elementos Presupuestados";
        },
    });

}

function openModalPresupuesto() {
    $('#mod_Presupuesto_relacion').modal('show');
    // $("#btnAddPresupuestoModal").prop('disabled', true);
    tblPresupuestosRelacion.ajax.reload();
}

// function openModalCambiosMenores() {
//     $('#mod_cambios_menores').modal('show');
//     $("#btnAddCambiosMenores").prop('disabled', true);
//     // $('#idStatusPreTerm')[0].checked = true;
//     // $('#idStatusGanado')[0].checked = false;
//     // $('#idStatusPerdido')[0].checked = false;
//     tblCambiosMenores.ajax.reload();
// }

function openModalOPMetrics() {
    $('#mod_OPMetrics_relacion').modal('show');
    $("#btnAddOPMetricsModal").prop('disabled', true);
    tblOPMetricsRelacion.ajax.reload();
}

$("#mod_Presupuesto_relacion").on("shown.bs.modal", function () {
    $("#numPresupuesto").trigger("focus");
});

$("#mod_cambios_menores").on("shown.bs.modal", function () {
    if ($('#cambiosRealizadosEdit').val() == 0) {
        $('#oportunidadTitulo').text('Editar Oportunidad');
    } else {
        if ($('#cambiosMinimos').val() > 0) {
            // $('#oportunidadTitulo').text('Editar Oportunidad');
            $('#oportunidadTitulo').text('Editar Oportunidad (Agregaste Cambios Mínimos, GUARDA la Oportunidad)');
        } else {
            $('#oportunidadTitulo').text('Editar Oportunidad (Agregaste Cambios Mínimos, GUARDA la Oportunidad)');
        }
    }
});

$("#mod_cambios_menores").on("hidden.bs.modal", function () {
    if ($('#cambiosRealizadosEdit').val() == 0) {
        $('#oportunidadTitulo').text('Editar Oportunidad');
    } else {
        if ($('#cambiosMinimos').val() > 0) {
            // $('#oportunidadTitulo').text('Editar Oportunidad');
            $('#oportunidadTitulo').text('Editar Oportunidad (Agregaste Cambios Mínimos, GUARDA la Oportunidad)');
        } else {
            $('#oportunidadTitulo').text('Editar Oportunidad (Agregaste Cambios Mínimos, GUARDA la Oportunidad)');
            Swal.fire('Agregaste Cambios Mínimos, recuerda GUARDAR la Oportunidad');
        }
    }
});


$("#mod_OPMetrics_relacion").on("shown.bs.modal", function () {
    $("#numOPMetrics").trigger("focus");
});

function agregarImporte() {
    presupuesto = $('#numPresupuesto').val();
    valor = $('#importe').val();
    idOportunidad = $('#idOportunidad').val();
    tipo = $('#tipo').val();

    $.ajax({
        url: serve + 'agregarPresupuesto',
        type: 'POST',
        data: {
            presupuesto: presupuesto,
            valor: valor,
            idOportunidad: idOportunidad,
            tipo: tipo,
        },
        success: function (data) {
            // valor_nuevo = parseFloat(valor);
            // valor_actual = parseFloat($('#Valor').val().replace(',', ''));
            // valor_acumulado = valor_nuevo + valor_actual;
            // console.log('Valor Actual ' + valor_actual);
            // console.log('Valor Acumulado ' + valor_acumulado);

            tblPresupuestosRelacion.ajax.reload();
            $('#numPresupuesto').val('');
            $('#importe').val('');
            // $('#Valor').val(valor_acumulado);
            // $("#btnAddPresupuestoModal").prop('disabled', true);
        },
    });
}

function agregarOPMetrics() {
    opMetrics = $('#numOPMetrics').val();
    // valor = $('#importe').val();
    idOportunidad = $('#idOportunidad').val();
    tipo = $('#tipo').val();

    $.ajax({
        url: serve + 'agregarOPMetrics',
        type: 'POST',
        data: {
            opMetrics: opMetrics,
            // valor: valor,
            idOportunidad: idOportunidad,
            tipo: tipo,
        },
        success: function (data) {
            // valor_nuevo = parseFloat(valor);
            // valor_actual = parseFloat($('#Valor').val().replace(',', ''));
            // valor_acumulado = valor_nuevo + valor_actual;
            // console.log('Valor Actual ' + valor_actual);
            // console.log('Valor Acumulado ' + valor_acumulado);

            tblOPMetricsRelacion.ajax.reload();
            $('#numOPMetrics').val('');
            // $('#importe').val('');
            // $('#Valor').val(valor_acumulado);
            $("#btnAddOPMetricsModal").prop('disabled', true);
        },
    });
}

function closeMPresupuestos() {
    $('#numPresupuesto').val('');
    $('#importe').val('');
    // $("#btnAddPresupuestoModal").prop('disabled', true);
}

function closeMElementos() {
    // Limpiamos el formulario para agregar un cambio de especificación
    $('#divCambioEspecificaciones').addClass('d-none');
    $('#campoCambioEspecificaciones').val('');
    $('#pdfViewerCambios').attr('src', '');
    $('#nombreElementoCE').text('');

    $('#guardarCambioEspecificaciones').addClass('d-none');
    $('#cancelarCambioEspecificaciones').addClass('d-none');

    $('#modal_cambio_especificaciones').modal('hide');
}

function closeMOPMetrics() {
    $('#numOPMetrics').val('');
    // $('#importe').val('');
    $("#btnAddOPMetricsModal").prop('disabled', true);
}

function activarPresupuesto(id, check) {
    if (check == 0) {
        checked = 1;
    } else {
        checked = 0;
    }

    $.ajax({
        url: serve + 'activarPresupuesto',
        type: 'PATCH',
        data: {
            checked: checked,
            id: id,
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            tblPresupuestosRelacion.ajax.reload();
            if (data == 0) {
                $('#idStatusPreTerm').prop('checked', true);
                $("#oportBtnSave").prop('disabled', true);

                Swal.fire({
                    title: '¡Presupuesto Terminado!',
                    text: '¡Se tiene que capturar el Valor del Presupuesto para cambiarlo a Ganado!',
                    confirmButtonText: 'Aceptar',
                }).then((result) => {
                });

            } else {
                $("#oportBtnSave").prop('disabled', false);
            }
            $('#Valor').val(data);
        },
    })
}

function activarRealizar(id, check, id_cambio) {
    cerrarCambios = document.getElementById('cerrarcambios');
    captura = document.getElementById('captura_' + id);

    if (check == 0) {
        checked = 1;
        if (id_cambio >= 6) {
            cerrarCambios.disabled = false;
        } else {
            cerrarCambios.disabled = true;
        }

        captura.style.borderColor = 'red';
    } else {
        checked = 0;
        cerrarCambios.disabled = false;
        captura.style.borderColor = '';
    }

    $.ajax({
        url: serve + 'activarRealizar',
        type: 'PATCH',
        data: {
            checked: checked,
            id: id,
            idOportunidad: $('#idOportunidad').val(),
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            tblCambiosMenores.ajax.reload();
            $('#cambiosRealizados').val(data);
            $('#cambiosRealizadosEdit').val(data);
        },
    })
}

function asignarValorCambio(id, valor) {
    let captura = document.getElementById('captura_' + id);
    let valor_campo = $('#captura_' + id).val();
    let cerrarCambios = document.getElementById('cerrarcambios');

    if (valor_campo == null || valor_campo == '') {
        cerrarCambios.disabled = true;
        captura.style.borderColor = 'red';
    } else {
        cerrarCambios.disabled = false;
        captura.style.borderColor = 'green';
    }

    $.ajax({
        url: serve + 'asignarValorCambio',
        type: 'PATCH',
        data: {
            id: id,
            valor: valor_campo,
            idOportunidad: $('#idOportunidad').val(),
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            tblCambiosMenores.ajax.reload();
            // $('#cambiosRealizados').val(data);
            // $('#cambiosRealizadosEdit').val(data);
        },
    })
}

function activarRealizado(id, check) {
    if (check == 0) {
        checked = 1;
    } else {
        checked = 0;
    }

    $.ajax({
        url: serve + 'activarRealizado',
        type: 'PATCH',
        data: {
            checked: checked,
            id: id,
            idOportunidad: $('#idOportunidad').val(),
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            tblCambiosMenores.ajax.reload();
            $('#cambiosRealizados').val(data);
            $('#cambiosRealizadosEdit').val(data);
        },
    })
}

function activarOPMetrics(id, check) {
    if (check == 0) {
        checked = 1;
    } else {
        checked = 0;
    }

    $.ajax({
        url: serve + 'activarOPMetrics',
        type: 'PATCH',
        data: {
            checked: checked,
            id: id,
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            console.log(data);
            tblOPMetricsRelacion.ajax.reload();
            if (data == '') {
                // $('#idStatusSolOP').prop('checked', true);
                $('#oportBtnSave').addClass('disabled');
                // $('#btnAddOPMetrics').addClass('disabled');
                $('#oportBtnSave').prop('disabled', true);
            } else {
                $('#oportBtnSave').removeClass('disabled');
                $('#oportBtnSave').prop('disabled', false);
                // $('#btnAddOPMetrics').removeClass('disabled');
            }
            $('#numOrdenMTC').val(data);
        },
    })
}

/**
 * Función para solicitar la ficha tecnica de la oportunidad
 * 
 * @param {*} idOportunidad 
 */
function solicitarFichaTecnica(idOportunidad) {

    formDataSolNomTrb = new FormData();
    formDataSolNomTrb.append("_token", CSRF_TOKEN);
    formDataSolNomTrb.append("_method", "POST");
    formDataSolNomTrb.append("idOportunidad", idOportunidad);

    $.ajax({
        url: serve + "getTripaAutSinCam",
        async: true,
        type: "POST",
        data: formDataSolNomTrb,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                // Creamos el formulario que se enviara para realizar la solicitud de la ficha técnica
                formDataSolFt = new FormData();
                formDataSolFt.append("_token", CSRF_TOKEN);
                formDataSolFt.append("_method", "POST");
                formDataSolFt.append("idOportunidad", idOportunidad);
                formDataSolFt.append("nomTrabajo", data.nomTrabajo);
    
                // Ejecutamos la acción de ajax
                $.ajax({
                    url: serve + "solicitarFt",
                    async: true,
                    type: "POST",
                    data: formDataSolFt,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.response) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });
    
                            Msg.fire('Se ha notificado sobre la ficha técnica');
                            tblOportunidades.ajax.reload();
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



// Asignamos al input de cambios cuando se presione enter se guarde el reporte
$('#addNewMinimalChange').on('keydown', function (event) {
    if (event.key == 'Enter') {
        addNewSolicitudCambios();
    }
});

/**
 * Función para validar que cambios menores se deben de colocar
 * 
 * @param {*} tipo
 * @return void
 */
function openModalCambiosMenores(tipo = 1) {
    // Obtenemos el estatus de la oportunidad
    let estatus = $('#Estatus').val()

    // openModalCambioPrecios(tipo);

    // Swal.close();

    Swal.fire({
        title: "¿Qué solicitud de cambios desea realizar?",
        html: `
            <div
                style="position: relative; display: flex !important; width: 100%; justify-content: center !important; align-items: center !important; align-content: center !important; font-size: 16px;">
                <div style="
                margin-right: 5px;
                ">
                    <div class="tooltip2">
                        <button id="swal-cambio-precios" class="swal2-confirm swal2-styled w-full" style="background-color:#000d9d;">Cambio
                            de precios</button>
                        <span class="tooltip2text">Se deberá poner el precio actual ya enviado y el precio nuevo requerido por el
                            cliente para volverse a revisar</span>
                    </div>
                </div>
                <div style="
                margin-right: 5px;
                ">
                    <div class="tooltip2">
                        <button id="swal-cambios-minimos" class="swal2-confirm swal2-styled w-full" style="background-color:#990b96;">Cambios
                            Mínimos</button>
                        <span class="tooltip2text">Se deberán poner los cambios Mayores relacionados a los procesos o
                            especificaciones</span>
                    </div>
                </div>
                <div style="
                ">
                    <div class="tooltip2">
                        <button id="swal-cambios-especificaciones" class="swal2-confirm swal2-styled w-full" style="background-color:#009d24;">Solicitud de cambios</button>
                        <span class="tooltip2text">Se deberán poner cambios que no requieren mayor esfuerzo por parte de los
                            cotizadores</span>
                    </div>
                </div>
            </div>
        `,
        showConfirmButton: false,
        showDenyButton: false,
        showCancelButton: false,
        didOpen: () => {
            document.getElementById('swal-cambio-precios').onclick = () => {
                // Acción para "Cambio de precios"
                openModalCambioPrecios(tipo);

                Swal.close();
            };
            document.getElementById('swal-cambios-minimos').onclick = () => {
                openModalCambiosMenoresV2(tipo);

                Swal.close();
            };
            document.getElementById('swal-cambios-especificaciones').onclick = () => {
                // Acción para "Cambio de especificaciones"
                Swal.close();
                openModalCambioEspecificaciones(tipo);
            };
        }
    });
}

/**
 * Función para mostrar la nota de rechazo del presupuesto
 * 
 * @return void
 */
function mostrarNotaRechazoOport() {
    let oportunidadId = $('#idOportunidad').val();
    $.ajax({
        url: serve + 'getUltimaNotaRechazoPresupuesto',
        type: "POST",
        data: {
            _method: "POST",
            _token: CSRF_TOKEN,
            idOportunidad: oportunidadId
        },
        success: function (data) {
            if (data.response) {
                let motivo = data.motivo ? data.motivo : 'No hay nota de rechazo disponible';
                Swal.fire({
                    title: "Nota de Rechazo",
                    text: motivo,
                    icon: "info",
                    confirmButtonText: "Cerrar",
                    customClass: {
                        confirmButton: 'btn btn-primary',
                    }
                })
            }
        }
    })
}

/**
 * Función para realizar el cambio de precios de un elemento de la oportunidad
 * 
 * @return void
 */
function openModalCambioPrecios(tipo = 1) {
    // Limpiamos el modal
    $('#modal_cambio_precios').modal('show');
    tblCambiosPreciosFn(tipo)
}

/**
 * Función para realizar cambios menores de la oportunidad
 * 
 * @return void
 */
function openModalCambiosMenoresV2(tipo = 1) {
    // Limpiamos el modal
    $('#modal_cambios_menores_v2').modal('show');
    tblCambiosMenoresFn(tipo)
}

/**
 * Función para realizar el cambio de precios de un elemento de la oportunidad
 * 
 * @return void
 */
function openModalCambioEspecificaciones(tipo = 1) {
    // Limpiamos el modal
    $('#modal_cambio_especificaciones').modal('show');
    tblCambioEspecificacionesFn(tipo)

    if (tipo == 2) {
        $('#campoCambioEspecificaciones').prop('disabled', true);
    }
}

/**
 * Función para obtener los detalles de los cambios minimos de la oportunidad en la primera versión
 * 
 * @return void
 */
function getCambiosMinimosDetalles() {
    // Limpiamos la seccion de los detalles
    $('#content-details').html('')
    
    // Creamos el formulario que obtendra los campos contables de los cambios
    formDataGetPreviousCambiosMinimos = new FormData();
    formDataGetPreviousCambiosMinimos.append("_token", CSRF_TOKEN);
    formDataGetPreviousCambiosMinimos.append("_method", "POST");
    formDataGetPreviousCambiosMinimos.append("idOportunidad", $('#idOportunidad').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getAllPreviousCambiosMinimos",
        async: true,
        type: "POST",
        data: formDataGetPreviousCambiosMinimos,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data) {
                if (data.finalList != '') {
                    // Agregamos la cadena al camponente de los detalles
                    $('#content-details').append(data.finalList)
                } else {
                    $('#btn-show-hide-cm').html('')
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

            Msg.fire({ title: "Ocurrió un error, al realizar la operación" });
        }
    })
}

/**
 * Función para mostrar/ocultar el listado de cambios minimos
 * 
 * @retunr void
 */
function showCambiosMinimosNote() {
    $('#content-cambios-minimos').toggle('slow')
}

/**
 * Función para obtener el conteo completo de las solicitudes de una oportunidad
 * 
 * @return void
 */
function getCountSolicitudes() {
    // Creamos el formulario que obtendra los campos contables de los cambios
    formDataGetTotalSolicitudes = new FormData();
    formDataGetTotalSolicitudes.append("_token", CSRF_TOKEN);
    formDataGetTotalSolicitudes.append("_method", "POST");
    formDataGetTotalSolicitudes.append("idOportunidad", $('#idOportunidad').val());

    addNewSolicitudCambiosAsk = false

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getTotalSolicitudes",
        async: true,
        type: "POST",
        data: formDataGetTotalSolicitudes,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data) {
                // Mostramos los valores de cada campo en su respectivo elemento
                $('#countCambiosRealizados').html(data.totalCambiosRealizados)
                $('#countCambiosPorRealizar').html(data.totalCambiosAbiertos)

                if ($('#idUsuario').val() == 12456 || $('#idUsuario').val() == 3) {
                    // Validar cambios realizados
                    if (data.totalCambiosAbiertos == 0 && data.totalCambiosRealizados != 0) {
                        $('#oportunidadTitulo').text('Editar Oportunidad Solicitud de Cambios');
                        addNewSolicitudCambiosAsk = false
                    } else if (data.totalCambiosAbiertos != 0) {
                        $('#oportunidadTitulo').text('Editar Oportunidad (Solicitudes nuevas por atender)');
                        addNewSolicitudCambiosAsk = true
                    } else {
                        $('#oportunidadTitulo').text('Editar Oportunidad');
                    }

                    // Agregar el valor de la bandera al input y validar en back
                    $('#flagAddNewRequestChanges').val(addNewSolicitudCambiosAsk)
                    $('#cambiosRealizadosEdit').val(addNewSolicitudCambiosAsk)
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

            Msg.fire({ title: "Ocurrió un error, al realizar la operación" });
        }
    })
}

/**
 * Función para obtener la tabla de cambios minimos
 * 
 * @return void
 */
function tblCambiosSolicitadosTbl() {
    tblCambiosSolicitados = $("#tblCambiosSolicitados").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: false,
        scrollX: true,
        scrollCollapse: false,
        deferRender: true,
        ordering: false,
        order: [
            [1, "desc"]
        ],
        ajax: {
            url: serve + "api/getListSolicitudes",
            data: {
                idOportunidadP : $('#idOportunidad').val()
            },
            type: "POST",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columns: [
            {
                data: 'cambioDescripcion',
                title: 'Solicitud'
            },
            {
                data: 'realizado',
                className: 'dt-center',
                title: 'Realizado'
            },
            {
                data: 'solicitante',
                title: 'Usuario solicita'
            },
            {
                data: 'solicitud_created_at',
                title: 'Fecha de creación',
                className: 'text-center'
            },
            {
                data: 'acciones',
                className: 'text-center',
                title: 'Acciones'
            }
        ],
        pageLength: 5,
        lengthMenu: [
            [5, 25, 50, 100, 150, 200, -1],
            [5, 25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para realizar la función de guardado de un registro de solicitud de cambios
 * 
 * @return mixed
 */
function addNewSolicitudCambios() {
    // Creamos el formulario que enviara el cambio a la tabla de cambios
    formDataAddNewChange = new FormData();
    formDataAddNewChange.append("_token", CSRF_TOKEN);
    formDataAddNewChange.append("_method", "POST");
    formDataAddNewChange.append("cambio", $('#addNewMinimalChange').val());
    formDataAddNewChange.append("idOportunidad", $('#idOportunidad').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "addNewChange",
        async: true,
        type: "POST",
        data: formDataAddNewChange,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos que no hayan errores en la validación
            if (data.errores > 0) {
                $('#msg-errors-new-change').html('')
                $('#msg-errors-new-change').append(data.error_cambio)
                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire('Se agrego el nuevo cambio');

                // Refrescamos el contador
                getCountSolicitudes()

                // Refrescamos la tabla de cambios minimos
                tblCambiosSolicitados.ajax.reload();

                // Limpiamos el input del guardado de cambios
                $('#addNewMinimalChange').val('')

                // Limpiamos los mensajes de error
                $('#msg-errors-new-change').html('')
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

            Msg.fire({ title: "Ocurrió un error, al realizar la operación" });
        }
    })
}

/**
 * Función para realizar la actualización de estatus de los cambios registrados
 * 
 * @param idSolCam
 * @param element
 * @param type El tipo es para diferenciar 1 realizar, 2 realizado
 * @return void
 */
function changeStatusAction(idSolCam, element, type) {

    Swal.fire({
        title: "¿Marcar como relizado?",
        text: "Una vez que lo marques como realizado, no podrá ser desmarcado nuevamente",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que enviara el cambio a la tabla de cambios
            formDataUpdateEstatusChange = new FormData();
            formDataUpdateEstatusChange.append("_token", CSRF_TOKEN);
            formDataUpdateEstatusChange.append("_method", "POST");
            formDataUpdateEstatusChange.append("idSolCom", idSolCam);
            formDataUpdateEstatusChange.append("element", element.checked);
            formDataUpdateEstatusChange.append("type", type);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "updateChangeEstatus",
                async: true,
                type: "POST",
                data: formDataUpdateEstatusChange,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se realizó el cambio');

                        // Refrescamos la tabla de cambios minimos
                        tblCambiosSolicitados.ajax.reload();

                        // Actualizamos los contadores
                        getCountSolicitudes()
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

                    Msg.fire({ title: "Ocurrió un error, al actualizar" });
                }
            })
        } else {
            // Refrescamos la tabla de cambios minimos
            tblCambiosSolicitados.ajax.reload();

            // Actualizamos los contadores
            getCountSolicitudes()
        }
    });
}

/**
 * Función para eliminar el cambio que se ha agregado y no ha sido realizado
 * 
 * @param idSolCam
 * @return mixed
 */
function eliminarSolicitudCambio(idSolCam) {
    // Preguntamos si quiere eliminar el cambio
    Swal.fire({
        title: "¿Quieres eliminar este cambio?",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar",
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            // Creamos el formulario que eliminara el cambio seleccionado
            formDataDeleteSolicitudCambios = new FormData();
            formDataDeleteSolicitudCambios.append("_token", CSRF_TOKEN);
            formDataDeleteSolicitudCambios.append("_method", "POST");
            formDataDeleteSolicitudCambios.append("idSolCom", idSolCam);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "deleteSolicitudCambios",
                async: true,
                type: "POST",
                data: formDataDeleteSolicitudCambios,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se eliminó el cambio solicitado');

                        // Refrescamos la tabla de cambios minimos
                        tblCambiosSolicitados.ajax.reload();

                        // Actualizamos los contadores
                        getCountSolicitudes()
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

                    Msg.fire({ title: "Ocurrió un error, al eliminar" });
                }
            })

            // Refrescamos la tabla de cambios minimos
            tblCambiosSolicitados.ajax.reload();
        }
    });
}

/**
 * Función para abrir la ventana flotante con el valor del antiguo cambio mínimo
 * 
 * @param idCambioMinimo
 * @return void
 */
function showCompleteDetail(idCambioMinimo) {
    // Creamos el formulario que obtendra los campos contables de los cambios
    formDataGetCambiosMinimos = new FormData();
    formDataGetCambiosMinimos.append("_token", CSRF_TOKEN);
    formDataGetCambiosMinimos.append("_method", "POST");
    formDataGetCambiosMinimos.append("idCambioMinimo", idCambioMinimo);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getCambioMinimoContent",
        async: true,
        type: "POST",
        data: formDataGetCambiosMinimos,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                Swal.fire({
                    title: data.titulo,
                    text: data.cambio,
                    icon: "info"
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

            Msg.fire({ title: "Ocurrió un error, al realizar la operación" });
        }
    })
}

/**
 * Función para mostrar la tabla de cambio de precios
 * 
 * @return void
 */
function tblCambioEspecificacionesFn(tipo = 1) {
    let idOportunidad = $("#idOportunidadCambios").val();
    let tblCambioEspecificaciones = $("#tblCambioEspecificaciones").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: false,
        autoWidth: true,
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        ajax: {
            url: serve + "get-list-relation-EPV2-activos/" + idOportunidad,
            type: "GET",
            dataType: "json",
        },
        buttons: [
            {
            },
        ],
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {
                targets: [5], // fecha pedido
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
                type: 'date'
            },
        ],
        columns: [
            {
                data: 'folio',
                className: 'text-center',
            },
            {
                data: 'nombre_proyecto',
                className: 'text-center',
                render: function(data, type, row) {
                    // return `
                    //     ${row.tiene_cambio_especificaciones == 1 ? '<i style="border-radius: 50px; background-color: #009d24; color: #fff; padding: 3px; font-size: 8px;">CE</i>' : ''}
                    //     <a href="${serve}elementosp/${row.id}/edit/3">${data}</a>
                    // `;
                    // return `
                    //     ${row.tiene_cambio_especificaciones == 1 ? '<i style="border-radius: 50px; background-color: #009d24; color: #fff; padding: 3px; font-size: 8px;">CE</i>' : ''}
                    //     <span onclick="agregarCambioEspecificacion('${row.id}', '${data}', '${tipo}')" style="cursor: pointer; color: #007bff;">${data}</span>
                    // `;

                    return `${data}`;
                }
            },
            {
                data: 'plantilla',
                className: 'text-center',
            },
            {
                data: 'tipo_producto',
                className: 'text-center',
            },
            {
                data: 'realizado',
                className: 'text-center',
                title: 'Realizado',
                render: function(data, type, row) {
                    if ((idRol.value == 1 || idRol.value == 2 || idRol.value == 3 || idRol.value == 10 || idRol.value == 8) && tipo == 1) {
                        if (row.tiene_cambio_especificaciones == 0 || row.tiene_cambio_especificaciones == null) {
                            return ``;
                        } else {
                            return `
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input id="realizado_${row.id}"
                                            name="realizado_${row.id}" 
                                            onchange="marcarRealizadoCE(${row.id}, this);"
                                            ${row.tiene_cambio_especificaciones == 2 ? 'checked' : ''}
                                            ${row.tiene_cambio_especificaciones == 2 ? 'disabled' : ''}
                                            value="${row.resolvio_cambio_especificaciones == 0 ? 1 : 0}"
                                            type="checkbox" class="custom-control-input">
                                        <label class="custom-control-label"
                                            for="realizado_${row.id}"></label>
                                    </div>
                                </div>
                            `;
                        }
                    } else {
                        if (tipo == 2) {
                            return (row.tiene_cambio_especificaciones == 2) ? 'Realizado' : 'No realizado';
                        }

                        return ``;
                    }
                }
            },
            {
                data: 'fecha_registro',
                className: 'text-center',
            },
            {
                data: 'noResultados',
                visible: false,
            }
        ],
        pageLength: -1,
        lengthMenu: [
            [-1],
            ["Todos"],
        ],
        initComplete: function() {
            this.api().columns.adjust();

            // Ajustamos el alto de la tabla
            $('.dataTables_scrollBody').css('height', '120px');
            $('.dataTables_scrollBody').css('overflow-y', 'auto !important');
            $('.dataTables_scroll').css('overflow-y', 'auto !important');
        },
    });
}

/**
 * Función para mostrar el campo de cambio de especificaciones y el pdf
 * @param {number} idEP
 * @param {nombreEP} nombreEP
 * @return void
 */
function agregarCambioEspecificacion(idEP, nombreEP = '', tipo = 1) {
    loaderON();

    let idRol = document.getElementById('idRol');

    // Limpiamos el campo de cambios
    $('#divCambioEspecificaciones').removeClass('d-none');
    $('#guardarCambioEspecificaciones').removeClass('d-none');
    $('#cancelarCambioEspecificaciones').removeClass('d-none');
    $('#campoCambioEspecificaciones').val('');
    $('#nombreElementoCE').html(nombreEP);

    if (tipo == 2 || (idRol.value != 1 && idRol.value != 4 && idRol.value != 9 && idRol.value != 8 && idRol.value != 10 && idRol.value != 11)) {
        $('#guardarCambioEspecificaciones').addClass('d-none');
        $('#campoCambioEspecificaciones').prop('disabled', true);
        $('#cancelarCambioEspecificaciones').off('click').removeAttr('onclick');
        $('#cancelarCambioEspecificaciones').on('click', cancelarCambioEspecificacionesTipo2);
    } else {
        // Restaura el evento original si lo necesitas
        $('#cancelarCambioEspecificaciones').off('click').removeAttr('onclick');
        $('#cancelarCambioEspecificaciones').on('click', cancelarCambioEspecificaciones);
    }

    let dominio = document.domain;
    let liga = '';

    $('#idElementoCEM').val(idEP);

    if (dominio == "127.0.0.1") {
        liga = 'https://servicios.litoprocess.com/crmlito/generarPdfElementoInter/' + idEP + '/pdf';
        // liga = 'http://127.0.0.1:8002/generarPdfElementoInter/' + idEP + '/pdf';
    } else if (dominio == "192.168.2.222") {
        liga = base_path + 'generarPdfElementoInter/' + idEP + '/pdf';
    } else {
        liga = base_path + 'generarPdfElementoInter/' + idEP + '/pdf';
    }

    // Cargamos el PDF correspondiente
    $('#pdfViewerCambios').attr('src', liga);

    $('#cerrarYAplicarCambioEspecificaciones').addClass('d-none');

    $('#pdfViewerCambios').on('load', function() {
        loaderOut();
    });

    let formDataCE = new FormData();
    formDataCE.append("_token", CSRF_TOKEN);
    formDataCE.append("_method", "POST");
    formDataCE.append("id", idEP);

    // Obtenemos los comentarios de un cambio de especificaciones si ya ha sido registrado
    $.ajax({
        url: serve + 'getCambioEspecificacionesByIdEP',
        async: true,
        type: "POST",
        data: formDataCE,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                $('#campoCambioEspecificaciones').val(data.mensaje);
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

            Msg.fire({ title: "Ocurrió un error, al obtener el cambio de especificaciones" });
        }
    });

    loaderOut();
}

/**
 * Función para cerrar y aplicar los cambios de especificaciones
 * 
 * @return void
 */
function cerrarYAplicarCambioEspecificaciones() {
    loaderON();
    let oportunidadId = $('#idOportunidad').val();

    let formDataMarcarRealizado = new FormData();
    formDataMarcarRealizado.append("_token", CSRF_TOKEN);
    formDataMarcarRealizado.append("_method", "POST");
    formDataMarcarRealizado.append("id", oportunidadId);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "cerrarYAplicarCambioEspecificaciones",
        async: true,
        type: "POST",
        data: formDataMarcarRealizado,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire('Se aplicaron los cambios de especificaciones');

                tblCambioEspecificacionesFn()

                $('#modal_cambio_especificaciones').modal('hide');

                // Refrescamos la pagina
                setTimeout(() => {
                    location.reload();
                }, 500);
            }

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

            Msg.fire({ title: "Ocurrió un error, al actualizar el precio anterior" });

            loaderOut();
        }
    })
}

/**
 * Función para cancelar el registro de un cambio de especificación
 * 
 * @return void
 */
function cancelarCambioEspecificaciones() {
    // Limpiamos el campo de cambios
    $('#divCambioEspecificaciones').addClass('d-none');
    $('#guardarCambioEspecificaciones').addClass('d-none');
    $('#cancelarCambioEspecificaciones').addClass('d-none');
    $('#campoCambioEspecificaciones').val('');
    $('#nombreElementoCE').html('');
    $('#pdfViewerCambios').attr('src', '');

    validarBotonCerrarYAplicarCE()
}

/**
 * Función para cancelar el registro de un cambio de especificación (tipo 2)
 * 
 * @return void
 */
function cancelarCambioEspecificacionesTipo2() {
    // Limpiamos el campo de cambios
    $('#divCambioEspecificaciones').addClass('d-none');
    $('#guardarCambioEspecificaciones').addClass('d-none');
    $('#cancelarCambioEspecificaciones').addClass('d-none');
    $('#campoCambioEspecificaciones').val('');
    $('#nombreElementoCE').html('');
    $('#pdfViewerCambios').attr('src', '');
}

/**
 * Función para validar si se muestra el botón de cerrar y aplicar cambios y se oculta el de cerrar (normal)
 * 
 * @return void
 */
function validarBotonCerrarYAplicarCE() {
    let formDataCE = new FormData();
    formDataCE.append("_token", CSRF_TOKEN);
    formDataCE.append("_method", "POST");
    formDataCE.append("idOportunidad", $('#idOportunidad').val());

    // Validamos si existen elementos con cambios pendientes
    $.ajax({
        url: serve + 'validarElementosConCambiosPendientes',
        async: true,
        type: "POST",
        data: formDataCE,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                if (data.tieneCambiosPendientes) {
                    $('#cerrarYAplicarCambioEspecificaciones').removeClass('d-none');
                    // $('#cerrarcambiosEP').addClass('d-none');
                } else {
                    $('#cerrarYAplicarCambioEspecificaciones').addClass('d-none');
                    // $('#cerrarcambiosEP').removeClass('d-none');
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
            Msg.fire({ title: "Ocurrió un error, al obtener el cambio de especificaciones" });
        }
    });
}

/**
 * Función para guardar el cambio de especificación
 *
 * @returns void
 */
function guardarCambioEspecificaciones() {
    loaderON();
    let idElemento = $('#idElementoCEM').val();
    let cambioEspecificaciones = $('#campoCambioEspecificaciones').val();

    if (cambioEspecificaciones.trim() == '') {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000
        });
        Msg.fire({ title: "Por favor, describe los cambios solicitados" });
        loaderOut();
        return;
    }

    // Guardamos el cambio de especificación
    let formDataGuardarEP = new FormData();
    formDataGuardarEP.append("_token", CSRF_TOKEN);
    formDataGuardarEP.append("_method", "POST");
    formDataGuardarEP.append("id", idElemento);
    formDataGuardarEP.append("mensaje", cambioEspecificaciones);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "registrarNuevoCambioEspecificaciones",
        async: true,
        type: "POST",
        data: formDataGuardarEP,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire('Se aplicaron los cambios de especificaciones');

                $('#cerrarYAplicarCambioEspecificaciones').removeClass('d-none');
                
                $('#cerrarcambiosEP').addClass('d-none');

                tblCambioEspecificacionesFn()
                cancelarCambioEspecificaciones();
            }

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

            Msg.fire({ title: "Ocurrió un error, al registrar el cambio de especificaciones" });

            loaderOut();
        }
    })
}

// Arreglo que contendra los ids de los elementos seleccionados para concatenar cambios
var idsEPs = [];

/**
 * Función para mostrar el campo de concatenación de cambios
 * 
 * @param {number} id
 * @param {HTMLElement} element
 */
function mostrarConcatenarCampoCambios(id, element) {
    $('#campoCambios').removeClass('d-none');

    if (element.checked) {
        idsEPs.push(id);
    } else {
        idsEPs = idsEPs.filter(item => item !== id);
    }

    if (idsEPs.length > 0) {
        $('#campoCambios').removeClass('d-none');
    } else {
        $('#campoCambios').addClass('d-none');
    }
}

/**
 * Función para marcar como realizado un cambio de especificación
 * 
 * @param {number} id
 * @param {HTMLElement} element
 * @return void
 */
function marcarRealizadoCE(id, element) {
    Swal.fire({
        title: "¿Marcar como realizado este cambio?",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()  
    }).then((result) => {
        if (result.isConfirmed) {
            let formDataMarcarRealizado = new FormData();
            formDataMarcarRealizado.append("_token", CSRF_TOKEN);
            formDataMarcarRealizado.append("_method", "POST");
            formDataMarcarRealizado.append("id", id);
            formDataMarcarRealizado.append("valor", element.value);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "marcarRealizadoCE",
                async: true,
                type: "POST",
                data: formDataMarcarRealizado,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se marcó como realizado');

                        tblCambioEspecificacionesFn()

                        validarBotonCerrarYAplicarCE()
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

                    Msg.fire({ title: "Ocurrió un error, al actualizar el precio anterior" });
                }
            })
        } else {
            // Refrescamos la tabla de cambios minimos
            tblCambioEspecificacionesFn()
        }
    });
}

/**
 * Función para mostrar la tabla de cambios menores
 * 
 * @return void
 */
function tblCambiosMenoresFn(tipo = 1) {
    let idRol = document.getElementById('idRol');
    let idOportunidad = $("#idOportunidadCambios").val();
    let tblCambiosMenores = $("#tblCambiosMenoresV2").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: false,
        autoWidth: true,
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        ajax: {
            url: serve + "getListRelationsCambiosMenores/" + idOportunidad,
            type: "GET",
            dataType: "json",
        },
        buttons: [
            {
            },
        ],
        order: [
            [0, 'asc']
        ],
        columnDefs: [
            {
                orderable: false,
                targets: [1] //
            }
        ],
        columns: [
            {
                data: 'cambio',
                className: 'text-center',
            },
            {
                data: 'idOportunidadCambiosMinimos',
                className: 'text-center',
                render: function(data, type, row) {
                    // return `
                    //     <input type="checkbox" class="" id="activar_${row.idOportunidadCambiosMinimos}" onchange="habilitarCambiosMinimos(${row.idOportunidadCambiosMinimos}, this, ${(row.realizado == 1)});">
                    // `;
                    return ``;
                },
                visible: (tipo == 1 && (idRol.value == 1 || idRol.value == 10 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9))
            },
            {
                data: 'valor',
                className: 'text-center',
                title: 'Valor',
                render: function(data, type, row) {
                    if ((idRol.value == 1 || idRol.value == 10 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9) && tipo == 1) {
                        return `
                            <input type="text" disabled class="form-control text-center" id="cambio_minimo_${row.idOportunidadCambiosMinimos}" value="${row.valor}" onkeyup="agregarCantidadesCambiosMinimos(${row.idOportunidadCambiosMinimos}, 1);">
                        `;
                    } else {
                        return `
                            <p style="margin: 0; word-wrap: break-word; white-space: break-spaces; width: 430px;">${row.valor}</p>
                        `;
                    }
                }
            },
            {
                data: 'realizado',
                className: 'text-center',
                title: 'Realizado',
                render: function(data, type, row) {
                    if ((idRol.value == 1 || idRol.value == 2 || idRol.value == 3 || idRol.value == 10 || idRol.value == 8) && tipo == 1) {
                        if (row.realizar == 0 || row.realizar == null) {
                            return ``;
                        } else {
                            return `
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input id="realizado_${row.idOportunidadCambiosMinimos}"
                                            name="realizado_${row.idOportunidadCambiosMinimos}" 
                                            onchange="marcarRealizadoCM(${row.idOportunidadCambiosMinimos}, this);"
                                            ${row.realizado == 1 ? 'checked' : ''}
                                            ${row.realizado == 1 ? 'disabled' : ''}
                                            value="${row.realizado == 0 ? 1 : 0}"
                                            type="checkbox" class="custom-control-input">
                                        <label class="custom-control-label"
                                            for="realizado_${row.idOportunidadCambiosMinimos}"></label>
                                    </div>
                                </div>
                            `;
                        }
                    } else {
                        if (tipo == 2) {
                            return (row.realizado == 1) ? 'Realizado' : 'No realizado'
                        }

                        if (row.realizado) {
                            return `
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input id="realizado_${row.idOportunidadCambiosMinimos}"
                                            name="realizado_${row.idOportunidadCambiosMinimos}" 
                                            ${row.realizado == 1 ? 'checked' : ''}
                                            disabled
                                            type="checkbox" class="custom-control-input">
                                        <label class="custom-control-label"
                                            for="realizado_${row.idOportunidadCambiosMinimos}"></label>
                                    </div>
                                </div>
                            `;
                        }else {
                            return ``;
                        }
                    }
                }
            }
        ],
        pageLength: -1,
        lengthMenu: [
            [-1],
            ["Todos"]
        ],
        initComplete: function() {
            this.api().columns.adjust();

            // Ajustamos el alto de la tabla
            $('.dataTables_scrollBody').css('height', '300px');
            $('.dataTables_scrollBody').css('overflow-y', 'auto !important');
            $('.dataTables_scroll').css('overflow-y', 'auto !important');
        },
    });
}

let dataCambiosMenores = {};

function habilitarCambiosMinimos(id, element, hasSavedOneTime = false) {
    // Verificamos si el elemento está marcado
    if (element.checked) {
        // Habilitar los campos de precio anterior y nuevo
        $('#cambio_minimo_' + id).prop('disabled', false);

        // Creamos la data donde el id es el indice del elemento
        dataCambiosMenores[id] = {
            cambio_minimo: $('#cambio_minimo_' + id).val(),
        };
    } else {
        if (!hasSavedOneTime) {
            // Deshabilitar los campos de precio anterior y nuevo
            $('#cambio_minimo_' + id).prop('disabled', true);
    
            // limpiamos los valores de los campos
            $('#cambio_minimo_' + id).val('');
        }

        // Limpiamos la data del elemento
        if (dataCambiosMenores[id]) {
            delete dataCambiosMenores[id];
        }
    }

    // Validamos si al menos un elemento tiene precios habilitados y si ya tiene valor tanto en precio anterior como nuevo
    let tienePreciosHabilitados = Object.keys(dataCambiosMenores).length > 0 && Object.values(dataCambiosMenores).some(item => item.cambio_minimo);

    if (tienePreciosHabilitados) {
        $('#guardarCambiosMinimos').removeClass('d-none');
    } else {
        $('#guardarCambiosMinimos').addClass('d-none');
    }
}

function agregarCantidadesCambiosMinimos(id, tipo = 1) {
    if (tipo == 1) {
        dataCambiosMenores[id].cambio_minimo = $('#cambio_minimo_' + id).val();
    }

    if (dataCambiosMenores[id].cambio_minimo) {
        $('#guardarCambiosMinimos').removeClass('d-none');
    }
}

function agregarCambiosMinimos() {
    // Limpiamos los campos de precios que no tengan valor
    Object.keys(dataCambiosMenores).forEach(id => {
        if (!$('#activar_' + id).is(':checked')) {
            $('#cambio_minimo_' + id).prop('disabled', true);
            delete dataCambiosMenores[id];
        }

        if (!dataCambiosMenores[id].cambio_minimo) {
            $('#cambio_minimo_' + id).val('');
            $('#cambio_minimo_' + id).prop('disabled', true);
            $('#activar_' + id).prop('checked', false);
            delete dataCambiosMenores[id].cambio_minimo;
        }

        if (!dataCambiosMenores[id].cambio_minimo) {
            delete dataCambiosMenores[id];
        }
    });

    let formDataUpCambiosMinimos = new FormData();
    formDataUpCambiosMinimos.append("_token", CSRF_TOKEN);
    formDataUpCambiosMinimos.append("_method", "POST");
    formDataUpCambiosMinimos.append("idOportunidad", $('#idOportunidad').val());
    formDataUpCambiosMinimos.append("cambios", JSON.stringify(dataCambiosMenores));
            
    Swal.fire({
        title: "¿Guardar cambios mínimos?",
        html: "Se actualizarán los <b>cambios mínimos</b>.<br> Además el estatus regresará a <b>Listo p/cotizar</b>.<br>Y se refrescará la ventana de edición.",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "guardarCambiosMinimos",
                type: "POST",
                data: formDataUpCambiosMinimos,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se actualizaron los cambios mínimos correctamente');

                        window.location.href = serve + "oportunidades/" + $('#idOportunidad').val() + "/edit";
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un error al guardar los cambios mínimos.",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                }
            });
        }
    });
}

function marcarRealizadoCM(id, valor) {
    Swal.fire({
        title: "¿Marcar como realizado?",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()  
    }).then((result) => {
        if (result.isConfirmed) {
            let formDataMarcarRealizado = new FormData();
            formDataMarcarRealizado.append("_token", CSRF_TOKEN);
            formDataMarcarRealizado.append("_method", "POST");
            formDataMarcarRealizado.append("id", id);
            formDataMarcarRealizado.append("valor", valor.value);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "marcarRealizadoCM",
                async: true,
                type: "POST",
                data: formDataMarcarRealizado,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se marcó como realizado');

                        tblCambiosMenoresFn()
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

                    Msg.fire({ title: "Ocurrió un error, al actualizar cambios mínimos" });
                }
            })
        } else {
            // Refrescamos la tabla de cambios minimos
            tblCambiosMenoresFn();
        }
    });
}

/**
 * Función para mostrar la tabla de cambio de precios
 * 
 * @return void
 */
function tblCambiosPreciosFn(tipo = 1) {
    let idRol = document.getElementById('idRol');
    let idOportunidad = $("#idOportunidadCambios").val();
    let tblCambioPrecios = $("#tblCambioPrecios").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: false,
        autoWidth: true,
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        ajax: {
            url: serve + "get-list-relation-EPV2-activos/" + idOportunidad,
            type: "GET",
            dataType: "json",
        },
        buttons: [
            {
            },
        ],
        order: [
            [0, 'asc']
        ],
        columnDefs: [
            {
                targets: [7], // fecha pedido
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
                type: 'date'
            },
            {
                orderable: false,
                targets: [1] //
            }
        ],
        columns: [
            {
                data: 'folio',
                className: 'text-center',
            },
            {
                data: 'folio',
                className: 'text-center',
                render: function(data, type, row) {
                    if ($('#Estatus').val() == 7 || $('#Estatus').val() == 8) {
                        return `
                            <input type="checkbox" class="" id="activar_${row.id}" onchange="habilitarCambiosPrecios(${row.id}, this, ${(row.tiene_cambio_precios == 2)});">
                        `;
                    } else {
                        return ``;
                    }
                },
                visible: (tipo == 1 && (idRol.value == 1 || idRol.value == 10 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9))
            },
            {
                data: 'nombre_proyecto',
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        ${row.tiene_cambio_precios == 1 ? '<i style="border-radius: 50px; background-color: #000d9d; color: #fff; padding: 3px; font-size: 8px;">CP</i>' : ''}
                        ${data}
                    `;
                }
            },
            {
                data: 'plantilla',
                className: 'text-center',
            },
            {
                data: 'tipo_producto',
                className: 'text-center',
            },
            {
                data: 'id',
                className: 'text-center',
                title: 'Precio Anterior',
                render: function(data, type, row) {
                    if ((idRol.value == 1 || idRol.value == 10 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9) && tipo == 1) {
                        return `
                            <input type="number" step="any" disabled class="form-control text-center" id="precioAnterior_${row.id}" value="${new Intl.NumberFormat('es-MX').format(Number(row.precio_anterior).toFixed(2))}" onkeyup="agregarCantidades(${row.id}, 1);">
                        `;
                    } else {
                        return row.precio_anterior;
                    }
                }
            },
            {
                data: 'id',
                className: 'text-center',
                title: 'Precio Nuevo',
                render: function(data, type, row) {
                    if ((idRol.value == 1 || idRol.value == 10 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9) && tipo == 1) {
                        return `
                            <input type="number" step="any" disabled class="form-control text-center" id="precioNuevo_${row.id}" value="${new Intl.NumberFormat('es-MX').format(Number(row.precio_nuevo).toFixed(2))}" onkeyup="agregarCantidades(${row.id}, 2);">
                        `;
                    } else {
                        return row.precio_nuevo;
                    }
                }
            },
            {
                data: 'realizado',
                className: 'text-center',
                title: 'Realizado',
                render: function(data, type, row) {
                    if ($('#Estatus').val() == 7 || $('#Estatus').val() == 8) {
                        if ((idRol.value == 1 || idRol.value == 2 || idRol.value == 3 || idRol.value == 10 || idRol.value == 8) && tipo == 1) {
                            if (row.tiene_cambio_precios == 0 || row.tiene_cambio_precios == null) {
                                return ``;
                            } else {
                                return `
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input id="realizado_${row.id}"
                                                name="realizado_${row.id}" 
                                                onchange="marcarRealizado(${row.id}, this);"
                                                ${row.tiene_cambio_precios == 2 ? 'checked' : ''}
                                                ${row.tiene_cambio_precios == 2 ? 'disabled' : ''}
                                                value="${row.resolvio_cambio_precios == 0 ? 1 : 0}"
                                                type="checkbox" class="custom-control-input">
                                            <label class="custom-control-label"
                                                for="realizado_${row.id}"></label>
                                        </div>
                                    </div>
                                `;
                            }
                        } else {
                            if (tipo == 2) {
                                return (row.tiene_cambio_precios == 2) ? 'Realizado' : 'No realizado'
                            }
                            return ``;
                        }
                    } else {
                        return ``;
                    }
                }
            },
            {
                data: 'fecha_registro',
                className: 'text-center',
            },
            {
                data: 'noResultados',
                visible: false,
            }
        ],
        pageLength: -1,
        lengthMenu: [
            [-1],
            ["Todos"]
        ],
        initComplete: function() {
            this.api().columns.adjust();

            // Ajustamos el alto de la tabla
            $('.dataTables_scrollBody').css('height', '300px');
            $('.dataTables_scrollBody').css('overflow-y', 'auto !important');
            $('.dataTables_scroll').css('overflow-y', 'auto !important');
        },
    });
}

let dataCambioPrecios = {};

function habilitarCambiosPrecios(id, element, hasSavedOneTime = false) {
    // Verificamos si el elemento está marcado
    if (element.checked) {
        // Habilitar los campos de precio anterior y nuevo
        $('#precioAnterior_' + id).prop('disabled', false);
        $('#precioNuevo_' + id).prop('disabled', false);

        // Creamos la data donde el id es el indice del elemento
        dataCambioPrecios[id] = {
            precioAnterior: $('#precioAnterior_' + id).val(),
            precioNuevo: $('#precioNuevo_' + id).val()
        };
    } else {
        if (!hasSavedOneTime) {
            // Deshabilitar los campos de precio anterior y nuevo
            $('#precioAnterior_' + id).prop('disabled', true);
            $('#precioNuevo_' + id).prop('disabled', true);
    
            // limpiamos los valores de los campos
            $('#precioAnterior_' + id).val('');
            $('#precioNuevo_' + id).val('');
        }

        // Limpiamos la data del elemento
        if (dataCambioPrecios[id]) {
            delete dataCambioPrecios[id];
        }
    }

    // Validamos si al menos un elemento tiene precios habilitados y si ya tiene valor tanto en precio anterior como nuevo
    let tienePreciosHabilitados = Object.keys(dataCambioPrecios).length > 0 && Object.values(dataCambioPrecios).some(item => item.precioAnterior && item.precioNuevo);

    if (tienePreciosHabilitados) {
        $('#guardarCambiosPrecios').removeClass('d-none');
    } else {
        $('#guardarCambiosPrecios').addClass('d-none');
    }
}

function agregarCantidades(id, tipo = 1) {
    if (tipo == 1) {
        dataCambioPrecios[id].precioAnterior = $('#precioAnterior_' + id).val();
    } else if (tipo == 2) {
        dataCambioPrecios[id].precioNuevo = $('#precioNuevo_' + id).val();
    }

    if (dataCambioPrecios[id].precioAnterior && dataCambioPrecios[id].precioNuevo) {
        $('#guardarCambiosPrecios').removeClass('d-none');
    }
}

function guardarCambiosPrecios() {
    // Limpiamos los campos de precios que no tengan valor
    Object.keys(dataCambioPrecios).forEach(id => {
        if (!$('#activar_' + id).is(':checked')) {
            $('#precioAnterior_' + id).prop('disabled', true);
            $('#precioNuevo_' + id).prop('disabled', true);
            delete dataCambioPrecios[id];
        }

        if (!dataCambioPrecios[id].precioAnterior) {
            $('#precioAnterior_' + id).val('');
            $('#precioAnterior_' + id).prop('disabled', true);
            $('#activar_' + id).prop('checked', false);
            delete dataCambioPrecios[id].precioAnterior;
        }
        if (!dataCambioPrecios[id].precioNuevo) {
            $('#precioNuevo_' + id).val('');
            $('#precioNuevo_' + id).prop('disabled', true);
            $('#activar_' + id).prop('checked', false);
            delete dataCambioPrecios[id].precioNuevo;
        }

        if (!dataCambioPrecios[id].precioAnterior && !dataCambioPrecios[id].precioNuevo) {
            delete dataCambioPrecios[id];
        }
    });

    let formDataUpPrecios = new FormData();
    formDataUpPrecios.append("_token", CSRF_TOKEN);
    formDataUpPrecios.append("_method", "POST");
    formDataUpPrecios.append("cambios", JSON.stringify(dataCambioPrecios));
            
    Swal.fire({
        title: "¿Guardar cambios de precios?",
        html: "Se actualizarán los precios anteriores y nuevos de los elementos seleccionados. <br> Además el estatus regresará a <b>Listo p/cotizar</b>.<br>Y se refrescará la ventana de edición.",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "guardarCambiosPrecios",
                type: "POST",
                data: formDataUpPrecios,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se actualizaron los precios correctamente');

                        window.location.href = serve + "oportunidades/" + $('#idOportunidad').val() + "/edit";
                    }
                },
                error: function () {
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un error al guardar los cambios de precios.",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                }
            });
        }
    });
}

function marcarRealizado(id, valor) {
    Swal.fire({
        title: "¿Marcar como realizado?",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()  
    }).then((result) => {
        if (result.isConfirmed) {
            let formDataMarcarRealizado = new FormData();
            formDataMarcarRealizado.append("_token", CSRF_TOKEN);
            formDataMarcarRealizado.append("_method", "POST");
            formDataMarcarRealizado.append("id", id);
            formDataMarcarRealizado.append("valor", valor.value);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "marcarRealizado",
                async: true,
                type: "POST",
                data: formDataMarcarRealizado,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se marcó como realizado');

                        tblCambiosPreciosFn()
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

                    Msg.fire({ title: "Ocurrió un error, al actualizar el precio anterior" });
                }
            })
        } else {
            // Refrescamos la tabla de cambios minimos
            tblCambiosPreciosFn();
        }
    });
}

function actualizarPrecios(id) {
    let precioAnterior = $('#precioAnterior_' + id).val();
    let precioNuevo = $('#precioNuevo_' + id).val();

    if (precioAnterior == '' || precioAnterior == null || precioAnterior == undefined || precioNuevo == '' || precioNuevo == null || precioNuevo == undefined) {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: 'error',
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire('Se requieren de ambos precios y no pueden estar vacíos');
        return;
    }

    Swal.fire({
        title: "¿Actualizar los precios?",
        html: "Una vez que lo actualices, ",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que se enviara para cambiar el precio anterior
            let formDataUpPrecioAnterior = new FormData();
            formDataUpPrecioAnterior.append("_token", CSRF_TOKEN);
            formDataUpPrecioAnterior.append("_method", "POST");
            formDataUpPrecioAnterior.append("id", id);
            formDataUpPrecioAnterior.append("precioAnterior", precioAnterior);
            formDataUpPrecioAnterior.append("precioNuevo", precioNuevo);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "changePrecios",
                async: true,
                type: "POST",
                data: formDataUpPrecioAnterior,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se actualizaron los precios correctamente');

                        window.location.href = serve + "oportunidades/" + $('#idOportunidad').val() + "/edit";
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

                    Msg.fire({ title: "Ocurrió un error, al actualizar el precio anterior" });
                }
            })
        }
    })
}

function updatePrecioAnterior(id, value) {
    if (value == '' || value == null || value == undefined) {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: 'error',
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire('El precio anterior no puede estar vacío');
        return;
    }

    Swal.fire({
        title: "¿Actualizar el precio anterior?",
        html: "Una vez que lo actualices, el estatus regresará a <b>Listo p/cotizar</b>.<br>Y se refrescará la ventana de edición.",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que se enviara para cambiar el precio anterior
            let formDataUpPrecioAnterior = new FormData();
            formDataUpPrecioAnterior.append("_token", CSRF_TOKEN);
            formDataUpPrecioAnterior.append("_method", "POST");
            formDataUpPrecioAnterior.append("id", id);
            formDataUpPrecioAnterior.append("valor", value);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "changePrecioAnterior",
                async: true,
                type: "POST",
                data: formDataUpPrecioAnterior,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Se actualizó el precio anterior correctamente');

                        window.location.href = serve + "oportunidades/" + $('#idOportunidad').val() + "/edit";
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

                    Msg.fire({ title: "Ocurrió un error, al actualizar el precio anterior" });
                }
            })
        }
    })
}

function updatePrecioNuevo(id, value) {
    if (value == '' || value == null || value == undefined) {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: 'error',
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire('El precio nuevo no puede estar vacío');
        return;
    }

    
    Swal.fire({
        title: "¿Actualizar el precio nuevo?",
        html: "Una vez que lo actualices, el estatus regresará a <b>Listo p/cotizar</b>.<br>Y se refrescará la ventana de edición.",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que se enviara para cambiar el precio nuevo
            let formDataUpPrecioNuevo = new FormData();
            formDataUpPrecioNuevo.append("_token", CSRF_TOKEN);
            formDataUpPrecioNuevo.append("_method", "POST");
            formDataUpPrecioNuevo.append("id", id);
            formDataUpPrecioNuevo.append("valor", value);
    
            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "changePrecioNuevo",
                async: true,
                type: "POST",
                data: formDataUpPrecioNuevo,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });
    
                        Msg.fire('Se actualizó el precio nuevo correctamente');
    
                        window.location.href = serve + "oportunidades/" + $('#idOportunidad').val() + "/edit";
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
    
                    Msg.fire({ title: "Ocurrió un error, al actualizar el precio nuevo" });
                }
            })
        }
    })
}

var idDocumentoEst = null;
var idTicketEstr = null;

function modal_upDocsEstrOport(id, ticketId) {
    OpenModalDocEstrOport();
    idDocumentoEst = id;
    idTicketEstr = ticketId;
}

function OpenModalDocEstrOport() {
    $("#mod_UpDocsEstrOport").modal("show");
    // $('.iborrainputfile').text(null);
}

var idTipoDocumentoEstr = null;
var id_clienteEstr = null;
var fecha_cambio = null;
const dateToday2 = new Date();
fecha_cambio = dateFormat(dateToday2, 'dd-mm-yyyy H:MM:ss')

function addDocumentoEstrOport() {
    let formDataEstr = new FormData();
    formDataEstr.append("_token", CSRF_TOKEN);
    formDataEstr.append("_method", "POST");
    formDataEstr.append("fileEstr", $("#FileDocEstrOport")[0].files[0]);
    formDataEstr.append("idTipoDocumento", idDocumentoEst);
    formDataEstr.append("folio", idTicketEstr);
    formDataEstr.append("fecha_cambio", fecha_cambio);
    formDataEstr.append("idOportunidad", $("#idOportunidad").val());

    $.ajax({
        url: serve + "upDocsEstructural",
        async: false,
        type: "POST",
        data: formDataEstr,
        processData: false,
        contentType: false,
        success: function (data) {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Documento Guardado",
            });

            $("#mod_UpDocsEstrOport").modal("hide");
            loaderOut();
            $("#mod_UpDocsEstrOport :input").val("");
            tblDetalleAsignadosOport.ajax.reload();
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
                title: "Ocurrió un error al Subir el Archivo, vuelve a intentarlo",
            });
        },
    });
}

/**
 * Función para validar el estatus y ejecutar la acción correspondiente para cargar Excel EP
 * 
 * @return void
 */
function validarEstatusParaExcel() {
    let estatus = $('#Estatus').val();
    estatus = parseInt(estatus);
    
    // Si es estatus 1 o 2, abrir modal normalmente
    if (estatus === 1 || estatus === 2) {
        abrirModalEPS();
    }
    // Si es estatus 8, solicitar cambios
    else if (estatus == 8 || estatus == 7) {
        solicitarCambiosExcelEPs();
    }
}

/**
 * Función para solicitar cambios en la oportunidad mediante Excel EP y regresar el estatus a listo para cotizar
 * 
 * @return void
 */
function solicitarCambiosExcelEPs() {
    // Primero abrimos el modal para cargar los archivos
    $('#mod_eps').modal('show');
    
    // Asignamos el comportamiento del botón de guardar para que realice la solicitud de cambios
    $('#guardarEPExcelBtn').hide(); // Ocultamos el botón de guardar para carga normal
    $('#guardarEPExcelBtn2').show(); // Mostramos el botón de guardar para cambios
}

/**
 * Función para guardar los documentos de Excel EP y realizar la solicitud de cambios
 * 
 * @return void
 */
function guardarDocumentosEPSConCambios() {
    if (!selectedFilesEPS || selectedFilesEPS.length === 0) {
        $('#labelFileDocAddEPS').addClass('is-invalid');
        return;
    } else {
        $('#labelFileDocAddEPS').removeClass('is-invalid');
    }

    Swal.fire({
        title: "¿Deseas guardar los archivos e iniciar la solicitud de cambios?",
        html: "Se guardarán los archivos Excel EP y después se solicitarán los cambios. La oportunidad regresará al estatus de <b>Listo p/cotizar</b>.",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Primero guardamos los documentos
            guardarDocumentosEPS(2);
            
            setTimeout(() => {
                // Después de guardar, realizamos la solicitud de cambios
                let formDataSolicitarCambios = new FormData();
                formDataSolicitarCambios.append("_token", CSRF_TOKEN);
                formDataSolicitarCambios.append("_method", "POST");
                formDataSolicitarCambios.append("idOportunidad", $('#idOportunidad').val());

                $.ajax({
                    url: serve + "solicitarCambiosExcelEPs" + "/" + $('#idOportunidad').val(),
                    type: "POST",
                    data: formDataSolicitarCambios,
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
                                title: "Cambios solicitados correctamente",
                            });

                            // Recargamos la página después de 2 segundos
                            if (data.refresh) {
                                setTimeout(function() {
                                    location.reload();
                                }, 2000);
                            }

                            $('#mod_eps').modal('hide');
                        }
                    },
                    error: function (error) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "error",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        Msg.fire({
                            title: "Error al solicitar los cambios",
                        });
                    }
                });
            }, 1000);
        }
        // Si cancela, no se realiza nada
    });
}

/**
 * Función para solicitar cambios en la oportunidad y regresar el estatus a listo para cotizar
 * 
 * @return void
 */
function solicitarCambios() {
    Swal.fire({
        title: "¿Solicitar cambios en la oportunidad?",
        html: "La oportunidad regresará al estatus de <b>Listo p/cotizar</b>.<br>Además se refrescará la ventana de edición.",
        icon: 'question',
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            let formDataSolicitarCambios = new FormData();
            formDataSolicitarCambios.append("_token", CSRF_TOKEN);
            formDataSolicitarCambios.append("_method", "POST");
            formDataSolicitarCambios.append("idOportunidad", $('#idOportunidad').val());

            $.ajax({
                url: serve + "solicitarCambiosOportunidad",
                type: "POST",
                data: formDataSolicitarCambios,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Msg.fire('Se solicitó los cambios correctamente');

                        window.location.href = serve + "oportunidades/" + $('#idOportunidad').val() + "/edit";
                    }
                },
                error: function () {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        Msg.fire('Ocurrió un error al solicitar los cambios, inténtalo de nuevo');
                    }
                }
            });
        }
    });
}