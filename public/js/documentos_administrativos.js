serve = base_path;
$('[data-bs-toggle="tooltip"]').tooltip();

CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
const TO_PATTERN = "DD/MM/YYYY HH:mm";

const FROM_PATTERN_SHORT = "YYYY-MM-DD";
const TO_PATTERN_SHORT = "DD/MM/YYYY";

$(document).ready(function() {
    loaderOut();
});

var idCliente = document.getElementById("idCliente");

var idClienteSelec2 = $("#idCliente").select2({
    language: "es",
});

idClienteSelec2.on("change", function() {
    loaderON();
    var idClienteSelec2 = $("#idCliente").select2("data");
    pedidosCliente(idClienteSelec2[0].id);
    // document.getElementById("btnFresh").style.display = "block";
});

idClienteSelec2.on("focusout", function() {
    var idClienteSelec2 = $("#idCliente").select2("data");
    pedidosCliente(idClienteSelec2[0].id);
});

function pedidosCliente(idcliente) {
    tblPedidosCliente = $("#tblPedidosCliente").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        scrollY: true,
        scrollX: true,
        select: true,
        rowId: "movid",
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "apiPedidos",
            type: "GET",
            dataType: "json",
            data: {
                idCliente: idcliente,
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columns: [{
                data: "movid",
            },
            {
                data: "cliente",
            },
            {
                data: "nombre",
                render: function(data, type, row) {
                    return (
                        '<a href="clientes/' +
                        row.cliente +
                        '/edit" title="Editar Cliente">' +
                        data +
                        "</a>"
                    );
                },
            },
            {
                data: "FechaEmision",
            },
            {
                data: "concepto",
            },
            {
                data: "referencia",
            },
            {
                data: "Agente",
            },
            {
                data: "Condicion",
            },
            {
                data: "referencia",
            },
            {
                data: "importe",
            },
            {
                data: "impuestos",
            },
            {
                data: "precio_total",
            },
            {
                data: "movid",
                render: function(data, type, row) {
                    if (
                        row.DocumentosEntrega == 0 ||
                        row.DocumentosEntrega == null ||
                        row.RequisitosEntrega == 0 ||
                        row.RequisitosEntrega == null
                    ) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-red"></i></a></div>'
                        );
                    } else if (row.RequisitosEntrega != row.DocumentosEntrega) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-yellow"></i></a></div>'
                        );
                    } else if (row.DocumentosEntrega == row.RequisitosEntrega) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-green"></i></a></div>'
                        );
                    }
                },
            },
            {
                data: "movid",
                render: function(data, type, row) {
                    if (
                        row.DocumentosFactura == 0 ||
                        row.DocumentosFactura == null ||
                        row.RequisitosFactura == 0 ||
                        row.RequisitosFactura == null
                    ) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-red"></i></a></div>'
                        );
                    } else if (row.RequisitosFactura != row.DocumentosFactura) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-yellow"></i></a></div>'
                        );
                    } else if (row.DocumentosFactura == row.RequisitosFactura) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-green"></i></a></div>'
                        );
                    }
                },
            },
            {
                data: "movid",
                render: function(data, type, row) {
                    if (
                        row.DocumentosCXC == 0 ||
                        row.DocumentosCXC == null ||
                        row.RequisitosCXC == 0 ||
                        row.RequisitosCXC == null
                    ) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-red"></i></a></div>'
                        );
                    } else if (row.RequisitosCXC != row.DocumentosCXC) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-yellow"></i></a></div>'
                        );
                    } else if (row.DocumentosCXC == row.RequisitosCXC) {
                        return (
                            '<div class="text-center"><a onclick="modal_viewDocs(' +
                            row.cliente +
                            ", " +
                            data +
                            "), agregarRequisitos(" +
                            row.cliente +
                            ", " +
                            data +
                            "), requisitosCliente(" +
                            row.cliente +
                            ", " +
                            data +
                            ')" style="cursor: pointer" data-id="' +
                            row.cliente +
                            '" title="Documentación"><i class="fas fa-arrow-alt-circle-right text-lg text-green"></i></a></div>'
                        );
                    }
                },
            },
        ],
        pageLength: -1,
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            nb_cols = api.columns().nodes().length;
            var j = 9;
            var k = 12;
            while (j < k) {
                var pageTotal = api
                    .column(j)
                    .data()
                    .reduce(function(a, b) {
                        return Number(a) + Number(b);
                    }, 0);

                var numFormat = $.fn.dataTable.render.number(",", ".", 2, "$").display;

                $(api.column(0).footer()).html("Total");
                $(api.column(j).footer()).html(numFormat(pageTotal));
                j++;
            }
        },
        columnDefs: [{
                targets: [3],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
            },
            {
                targets: [9, 10, 11],
                render: $.fn.dataTable.render.number(",", ".", 2, "$"),
                className: "text-right",
            },
            {
                targets: [12, 13, 14],
                orderable: false,
            }
        ],
        "drawCallback": function(settings) {
            $('[data-toggle="tooltip"]').tooltip({ arrow: true });
        },
        initComplete: function(settings, json) {
            loaderOut();
            ajustarTabla();
        },
    });
}

function refreshList() {
    tblPedidosCliente.ajax.reload();
}

function requisitosCliente(idCliente, idPedido) {
    tblRequisitosCliente = $("#tblRequisitosCliente").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        scrollY: true,
        scrollX: true,
        select: true,
        rowId: "descRequisito",
        order: [
            [1, "asc"]
        ],
        ajax: {
            url: serve + "listaRequisitos",
            type: "GET",
            dataType: "json",
            data: {
                idCliente: idCliente,
                idPedido: idPedido,
            },
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columns: [{
                data: "descRequisito",
            },
            {
                data: "idRequisito",
                render: function(data, type, row) {
                    if (row.Entrega == 1 && row.DocEntrega == 1) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocEntrega', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Actualizar Documento"><i class="fas fa-check-circle text-green text-lg"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a id="descargarDocumento" onclick="visorDocumentos(' +
                            row.idDocumentosAsignados +
                            ", 'DocEntrega'" +
                            ')" style="cursor: pointer; color: #000" title="' +
                            row.descDocumentoEntrega +
                            '"><i class="fas fa-download text-lg"></i></a>'
                        );
                    } else if (row.Entrega == 1 && row.DocEntrega == 0) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocEntrega', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Subir Documento"><i class="fas fa-times-circle text-red text-lg"></i></a>'
                        );
                    } else if (row.Entrega == 1 && row.DocEntrega == null) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocEntrega', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Subir Documento"><i class="fas fa-times-circle text-red text-lg"></i></a>'
                        );
                    } else if (row.Entrega == 0) {
                        return '<i class="fas fa-ban text-lg"></i>';
                    }
                },
            },
            {
                data: "idRequisito",
                render: function(data, type, row) {
                    if (row.Factura == 1 && row.DocFactura == 1) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocFactura', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Actualizar Documento"><i class="fas fa-check-circle text-green text-lg"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a id="descargarDocumento" onclick="visorDocumentos(' +
                            row.idDocumentosAsignados +
                            ", 'DocFactura'" +
                            ')" style="cursor: pointer; color: #000" title="' +
                            row.descDocumentoFactura +
                            '"><i class="fas fa-download text-lg"></i></a>'
                        );
                    } else if (row.Factura == 1 && row.DocFactura == 0) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocFactura', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Subir Documento"><i class="fas fa-times-circle text-red text-lg"></i></a>'
                        );
                    } else if (row.Factura == 1 && row.DocFactura == null) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocFactura', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Subir Documento"><i class="fas fa-times-circle text-red text-lg"></i></a>'
                        );
                    } else if (row.Factura == 0) {
                        return '<i class="fas fa-ban text-lg"></i>';
                    }
                },
            },
            {
                data: "idRequisito",
                render: function(data, type, row) {
                    if (row.CXC == 1 && row.DocCXC == 1) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocCXC', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Actualizar Documento"><i class="fas fa-check-circle text-green text-lg"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; <a id="descargarDocumento" onclick="visorDocumentos(' +
                            row.idDocumentosAsignados +
                            ", 'DocCXC'" +
                            ')" style="cursor: pointer; color: #000" title="' +
                            row.descDocumentoCXC +
                            '"><i class="fas fa-download text-lg"></i></a>'
                        );
                    } else if (row.CXC == 1 && row.DocCXC == 0) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocCXC', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Subir Documento"><i class="fas fa-times-circle text-red text-lg"></i></a>'
                        );
                    } else if (row.CXC == 1 && row.DocCXC == null) {
                        return (
                            '<a onclick="modal_upDocs(' +
                            data +
                            ", 'DocCXC', " +
                            row.idCliente +
                            ", " +
                            row.idPedido +
                            ", " +
                            row.idDocumentosAsignados +
                            ')" style="cursor: pointer" title="Subir Documento"><i class="fas fa-times-circle text-red text-lg"></i></a>'
                        );
                    } else if (row.CXC == 0) {
                        return '<i class="fas fa-ban text-lg"></i>';
                    }
                },
            },
        ],
        columnDefs: [{
                targets: [1, 2, 3],
                className: "text-left",
            },
            {
                targets: [0],
                className: "text-bold",
            },
            {
                targets: [0, 1, 2, 3],
                orderable: false,
            },
        ],
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        initComplete: function(settings, json) {
            loaderModalOut();
        },
    });
}

function agregarRequisitos(idCliente, idPedido) {
    $.ajax({
        url: serve + "agregarRequisitos",
        async: false,
        type: "POST",
        data: {
            _method: "POST",
            idCliente: idCliente,
            idPedido: idPedido,
            _token: CSRF_TOKEN,
        },
        success: function(data) {},
        error: function() {},
    });
}

function downDocsAdmin(idRequisito, tipoDocumento, idDocumentosAsignados) {
    $.ajax({
        url: serve + "api/downdocsAdmin",
        async: false,
        type: "GET",
        data: {
            _method: "GET",
            idRequisito: idRequisito,
            tipoDocumento: tipoDocumento,
            idDocumentosAsignados: idDocumentosAsignados,
        },
        success: function(data) {},
        error: function() {},
    });
}

$(window).resize(function() {
    ajustarTabla();
});

function ajustarTabla() {
    var height = window.innerHeight;

    if (height >= 320 && height <= 651) {
        $("#tblPedidosCliente")
            .closest(".dataTables_scrollBody")
            .css("height", "55vh");
    } else if (height >= 652 && height <= 768) {
        $("#tblPedidosCliente")
            .closest(".dataTables_scrollBody")
            .css("height", "62vh");
    } else if (height >= 769 && height <= 992) {
        $("#tblPedidosCliente")
            .closest(".dataTables_scrollBody")
            .css("height", "70vh");
    } else if (height >= 993 && height <= 1024) {
        $("#tblPedidosCliente")
            .closest(".dataTables_scrollBody")
            .css("height", "70vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tblPedidosCliente")
            .closest(".dataTables_scrollBody")
            .css("height", "78vh");
    } else {
        $("#tblPedidosCliente")
            .closest(".dataTables_scrollBody")
            .css("height", "78vh");
    }
}

$("#modal-ver-documentos").draggable({
    handle: ".titulo",
});

$("#modal-visor").draggable({
    handle: ".tituloVisor",
});

function modal_viewDocs(idCliente, idPedido) {
    $("#idClienteINT").val(idCliente);
    $("#idPedido").val(idPedido);
    $("#modal-ver-documentos").modal("show");
    loaderModalON();
}

$("#modal-ver-documentos").on("hidden.bs.modal", function() {
    $(this).find("form").trigger("reset");
});

function visorDocumentos(idDocumentosAsignados, tipoDocumento) {
    loaderModalON();
    $.ajax({
        url: serve + "getDocumento",
        type: "GET",
        data: {
            _method: "GET",
            idDocumentosAsignados: idDocumentosAsignados,
        },
        success: function(data) {

            if (tipoDocumento == 'DocCXC') {
                extension = data.ExtensionCXC;
                byteCharacters = atob(data.DocumentoCXC);
                descDocumento = data.descDocumentoCXC;
            } else if (tipoDocumento == 'DocEntrega') {
                extension = data.ExtensionEntrega;
                byteCharacters = atob(data.DocumentoEntrega);
                descDocumento = data.descDocumentoEntrega;
            } else if (tipoDocumento == 'DocFactura') {
                extension = data.ExtensionFactura;
                byteCharacters = atob(data.DocumentoFactura);
                descDocumento = data.descDocumentoFactura;
            }

            if (extension.match(/^.*vnd.*$/) || extension.match(/^.*stream$/)) {

                window.location.href = serve + 'downDocsAdmin/' + idDocumentosAsignados + '/' + tipoDocumento;

                loaderModalOut();

            } else {

                $("#modal-visor").modal("show");
                $('.tituloVisor').text(descDocumento);

                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray], { type: extension });

                blob = window.URL.createObjectURL(blob);

                var visorPDF = document.getElementById('frameVisor');

                visorPDF.src = blob;

                loaderModalOut();
            }
        },
        error: function() {},
    });

}