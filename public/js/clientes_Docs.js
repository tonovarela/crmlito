///---------------------------ING. BRIGETTE ISELA GUTIERREZ GONZALEZ---------//
//-----SEP 2021
//----------------------------------------------------------------------------//
serve = base_path;
rutaActual = document.location.href;

const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
const TO_PATTERN = "DD/MM/YYYY HH:mm";

var id = $('#idClienteINT').val(),
    idProspecto = $('#idProspecto').val(),
    group = 2,
    formData = '',
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
var collapsedGroups = {};
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
//------- CLICKS----/
$("#btnSaveDocsCte").click(function () {
    loaderON();
    addDocumento();
});
//-----CHECKS----/
$("input[name='FileDoc']").change(function () {
    formData = new FormData();
    var idTipoDocumento = $('#idTipoDocumento').val();
    formData.append('idCliente', id);
    formData.append('idProspecto', idProspecto);
    formData.append('idTipoDocumento', idTipoDocumento);
    formData.append('file', $('input[name=FileDoc]')[0].files[0]);
    // console.log(formData);
});
//------FUNCIONES---/

function tblDocs() {
    tableDocs = $('#tblDocs').DataTable({
        // orderFixed: {
        //     pre: [0, 'asc'],
        //     post: [1, 'asc']
        // },
        destroy: true,
        processing: true,
        // stateSave: true,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        colReorder: true,
        bLengthChange: true,
        autoWidth: true,

        order: [
            [2, "asc"]
        ],
        columnDefs: [
            {
                className: "dt-center",
                targets: [4, 5, 9, 10]
            },
            {
                visible: false,
                targets: [0, 1, 2, 6, 7, 8, 11, 12, 13, 14],
            },
            {
                targets: [4],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
        ],
        ajax: {
            url: serve + "api/clientes/docs",
            type: 'GET',
            dataType: 'json',
            data: {
                idCliente: id,
                idProspecto: idProspecto,
            },
        },
        columns: [{
            data: 'idTipoDocumento', // 0
            name: 'idTipoDocumento',
            title: '#'
        },
        {
            data: 'idDocumento', // 1
            name: 'idDocumento',
            title: 'idDocumento'
        },
        {
            data: 'descTipoDocumento', // 2
            name: 'descTipoDocumento',
            title: 'Tipo Documento'
        },

        {
            data: 'descDocumento', // 3
            name: 'descDocumento',
            title: 'Documento'
        },
        {
            data: 'fecRegistro', // 4
            name: 'fecRegistro',
            title: 'FechaRegistro'
        },
        {
            data: 'Login', // 5
            name: 'Login',
            title: 'UsuarioRegistro'
        },
        {
            data: 'idDocumento', // 6
            visible: false,
        },
        {
            data: 'idDocumento', // 7
            visible: false,
        },
        {
            data: 'NoDocs', // 8
            name: 'NoDocs',
            title: 'dfsd'
        },
        {
            data: 'NoDocs', // 9
            name: 'NoDocs',
            title: '# Docs'
        },
        {
            data: 'idDocumento', // 10
            render: function (data, type, row) {
                // return '<a href="' + serve + 'api/downDocsClientes/' + data + '" ><i class="fas fa-download icondocsdown" download></i></a><i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocs(' + data + ')"></i>';
                return '<a onclick="visorDocumentos(' + data + ')"><i class="fas fa-download icondocsdown" download></i></a><i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocs(' + data + ')"></i>';
            },
            name: 'idDocumento',
            title: 'Accion'
        },
        {
            data: 'tipoTripa' // 11
        },
        {
            data: 'descTipoDocumento' // 12
        },
        {
            data: 'fecRegistro' // 13
        },
        {
            data: 'ordenamiento' // 14
        },

        ],
        rowGroup: {
            dataSrc: 'descTipoDocumento',

            startRender: function (rows, group) {
                var collapsed = !!collapsedGroups[group],
                    // noelmentos = rows.count(),
                    noelmentos = rows.data().pluck('NoDocs'),
                    // contador = 0,
                    contador = noelmentos[0],
                    firstRowData = rows.data().pluck('idTipoDocumento'); // Tomo el valor del campo que necesito
                // if (noelmentos <= 1) { contador = 0; } else { contador = noelmentos; }
                var elem = $('<tr/>')
                    .append('<td colspan="3"  class="rowcolap"><i class="fas fa-plus-circle iconaddDocs icontbl"></i>&nbsp;&nbsp;' + group + '</td><td class="rowcolap text-center">' +
                        contador + '</td><td  class="rowcolap text-center"><i class="fas fa-upload  icondocsup" onclick="modal_upDocs(' + firstRowData[0] + ')" data-id="' + firstRowData[0] + '"></i></td>')
                    .attr('data-name', group.replace(' ', ''))
                    .toggleClass('collapsed', collapsed);
                var elem1 = $('<tr/>')
                    .append('<td colspan="3"  class="rowcolap"><i class="fas fa-plus-circle icondocsBloq "></i>&nbsp;&nbsp;' + group + '</td><td class="rowcolap text-center">' +
                        contador + '</td><td  class="rowcolap text-center"><i class="fas fa-upload  icondocsup" onclick="modal_upDocs(' + firstRowData[0] + ')" data-id="' + firstRowData[0] + '"></i></td>')
                    .attr('data-name', group.replace(' ', ''));
                $(rows.nodes()).hide(); // todos  colapsados
                elem.on('click', function (e) {

                    if (collapsedGroups[group]) {
                        if ($(rows.nodes()).is(':hidden')) {
                            $(rows.nodes()).show();
                            $('.icontbl').removeClass('fa-plus-circle');
                            $('.icontbl').addClass('fa-minus-circle');
                        } else {
                            $(rows.nodes()).hide();
                            $('.icontbl').addClass('fa-plus-circle');
                            $('.icontbl').removeClass('fa-minus-circle');
                        }
                    } else {
                        if ($(rows.nodes()).is(':hidden')) {
                            $(rows.nodes()).show();
                            $('.icontbl').removeClass('fa-plus-circle');
                            $('.icontbl').addClass('fa-minus-circle');
                        } else {
                            $(rows.nodes()).hide();
                            $('.icontbl').addClass('fa-plus-circle');
                            $('.icontbl').removeClass('fa-minus-circle');
                        }
                    }
                    collapsedGroups[group] = !collapsedGroups[group];
                });
                if (noelmentos[0] >= 1) { return elem; } else { return elem1; }
            }

        },
        // fixedColumns: {
        //     leftColumns: 1,
        // },
        language: {
            url: "../../js/spanish-DT.json"
        },
        dom: '<"top">rt<"bottom"lpB><"clear">',
        select: true,
        buttons: [{
            extend: 'colvis',
            columns: ':gt(0)',
            text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
        },
        {
            extend: 'excelHtml5',
            title: 'Contactos | CANDY CRM',
            text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
            exportOptions: {
                orthogonal: 'export',
                columns: "thead th:not(.noExport)",
            },
            filename: 'Contactos',
            autoFilter: true,
            sheetName: 'Lista',
            messageTop: null,
            body: true,
            footer: false,
        },
        ],
        bInfo: true,
        pageLength: 50,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, 'TODOS'],
        ],

        "initComplete": function (settings, json) { loaderOut(); },


    });
}

function modal_upDocs(data) {
    OpenModalDoc();
    $(".lblDocsUp").text("");
    $("#idTipoDocumento").val(data);
}


function modal_deleteDocs(data) {
    var idDocumentodelete = data;
    deleteDoc(idDocumentodelete);

}

function OpenModalDoc() {
    $('#mod_UpDocs').modal('show');
}

function addDocumento() {
    $.ajax({
        url: serve + 'api/upDocsClientes',
        async: false,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
            loaderON();
            const Msg = Swal.mixin({
                toast: true,
                position: 'center',
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({
                title: 'Archivo Guardado',
            });
            $('#mod_UpDocs').modal('hide');
            loaderOut();
            $('#mod_UpDocs :input').val('');
            tableDocs.ajax.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // console.log(jqXHR, textStatus, errorThrown);
            loaderOut();
            const Msg = Swal.mixin({
                toast: true,
                position: 'center',
                icon: 'warning',
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({
                title: 'Ocurrió un error al Subir el Archivo, vuelve a intentarlo',
            });
        }
    });

}

function deleteDoc(idDocumentodelete) {
    // alert(idDocumentodelete) ///revisar esto
    $.ajax({
        url: serve + 'deleteDocsClientes/',

        type: "PATCH",
        data: {
            _method: 'PATCH',
            idDocumentodelete: idDocumentodelete,
            _token: CSRF_TOKEN,
        },
        success: function (data) {
            const Msg = Swal.mixin({
                toast: true,
                position: 'center',
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({
                title: 'Documento Eliminado',
            });
            $('#mod_UpDocs').modal('hide');
            loaderOut();
            $('#mod_UpDocs :input').val('');
            tableDocs.ajax.reload();
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
                title: 'Ocurrió un error, vuelve a intentarlo',
            });
        }
    });

}

function visorDocumentos(idDocumento) {
    loaderModalON();
    $.ajax({
        url: serve + "getDocumentoCliente",
        type: "GET",
        data: {
            _method: "GET",
            idDocumento: idDocumento,
        },
        success: function (data) {

            extension = data.Extension;
            byteCharacters = atob(data.Documento);
            descDocumento = data.descDocumento;

            if (extension.match(/^.*vnd.*$/) || extension.match(/^.*stream$/)) {

                window.location.href = serve + 'api/downDocsClientes/' + idDocumento;

                loaderModalOut();

            } else {

                $("#modal-visor-documentos").modal("show");
                $('.tituloVisorDocumentos').text(descDocumento);

                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray], { type: extension });

                blob = window.URL.createObjectURL(blob);

                var visorPDF = document.getElementById('frameVisorDocumentos');

                visorPDF.src = blob;

                loaderModalOut();
            }
        },
        error: function () { },
    });
}

$(function () {

});

$(document).ready(function () {

    ///--------FUNCIONES----//

});