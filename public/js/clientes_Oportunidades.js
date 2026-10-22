var serve = base_path;

//----- TABLAS--------------------
$(document).ready(function () {
    $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'auto',
        delay: { "show": 0, "hide": 0 },
        fallbackPlacement: 'flip',
        boundary: 'scrollParent',
        trigger: 'hover',
    });
});

function tblOportunidades(tipo) {
    if (tipo == 1) {
        ClienteINT = $("#idClienteINT").val();
    } else {
        ClienteINT = $("#idProspecto").val()
    }
    tableOportunidadesCliente = $('#tableOportunidadesCliente').DataTable({
        processing: true,
        serverSide: true,
        paginate: true,
        order: [
            [0, "desc"]
        ],
        language: {
            url: serve + 'js/spanish-DT.json'
        },
        ajax: {
            url: (serve + "/tblClientesOport/" + ClienteINT),
            type: 'GET',
            dataType: 'json',
            data: {
                tipo: tipo,
            }
        },
        columns: [{
            data: "folio",
            name: 'folio',
            title: 'Folio'
        },
        {
            data: "nomOportunidad", // 5
            name: "nomOportunidad",
            render: function (data, type, row) {
                if ($.trim(row.inputEditar) === "Editar") {
                    if (row.ProspectoActivo == 1 && row.CreaOportunidades == 1) {
                        if (row.EsCliente == 1 && row.ProspectoActivo == 0) {
                            return '<span class="text-uppercase">' + data + '</span>';
                        } else {
                            if (row.Cancelada == "1") {
                                return (
                                    '<a href="' + serve + 'oportunidades/' +
                                    row.idOportunidad +
                                    '/edit" onclick="loaderON()" class="text-uppercase" data-bs-toggle="tooltip" title="Editar Oportunidad">' +
                                    data +
                                    "</a>"
                                );
                            } else {
                                return (
                                    '<a href="' + serve + 'oportunidades/' +
                                    row.idOportunidad +
                                    '/edit" onclick="loaderON()" class="text-uppercase" data-bs-toggle="tooltip" title="Editar Oportunidad">' +
                                    data +
                                    "</a>"
                                );
                            }
                        }
                    }
                    else {
                        if (row.prospecto == 1) {
                            return '<span class="text-uppercase">' + data + '</span>';
                        } else {
                            if (row.Cancelada == "1") {
                                return (
                                    '<a href="' + serve + 'oportunidades/' +
                                    row.idOportunidad +
                                    '/edit" onclick="loaderON()" class="text-uppercase" data-bs-toggle="tooltip" title="Editar Oportunidad">' +
                                    data +
                                    "</a>"
                                );
                            } else {
                                return (
                                    '<a href="' + serve + 'oportunidades/' +
                                    row.idOportunidad +
                                    '/edit" onclick="loaderON()" class="text-uppercase" data-bs-toggle="tooltip" title="Editar Oportunidad">' +
                                    data +
                                    "</a>"
                                );
                            }
                        }
                    }
                } else {
                    return '<span class="text-uppercase">' + data + '</span>';
                }
            },
            width: "5rem",
            title: "Nombre",
        },
        // {
        //     data: "nomOportunidad",
        //     name: 'nomOportunidad',
        //     render: function(data, type, row) {
        //         return '<a href="' + serve + 'oportunidades/' + row.idOportunidad + '/edit" title="Editar Oportunidad" onclick="loaderON()">' + data + '</a>';
        //     },
        //     title: 'Oportunidad'
        // },
        {
            data: "ClienteINT",
            name: 'ClienteINT',
            title: 'ClienteINT'
        },
        {
            data: "razonsocial",
            name: 'razonsocial',
            title: 'Cliente'
        },
        {
            data: "descEstatus",
            name: 'descEstatus',
            title: 'Estatus'
        },
        ],
        stateSave: false,
        select: false,
        scrollY: false,
        scrollX: true,
        pageLength: 15,
        lengthMenu: [
            [-1, 15, 25, 50, 100],
            ["TODOS", 15, 25, 50, 100]
        ],
        columnDefs: [{
            "className": "dt-center",
            "targets": [0, 1, 2, 4]
        },
        {
            "targets": [2],
            "visible": false
        }
        ],
        bLengthChange: true,
        bInfo: true,
        autoWidth: true,
        dom: '<"top"R>rt<"bottom"lpB><"clear">',
        buttons: [{
            extend: 'colvis',
            columns: [0, 1],
            text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
        },],
        "drawCallback": function (settings) {
            $('[data-bs-toggle="tooltip"]').tooltip({
                trigger: 'hover',
            });
        },
        "initComplete": function (settings, json) {
            loaderOut();
        },
    });

}