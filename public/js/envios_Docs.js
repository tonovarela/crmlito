///---------------------------ING. BRIGETTE ISELA GUTIERREZ GONZALEZ---------//
//-----SEP 2021
//----------------------------------------------------------------------------//
serve = base_path;

var id = $("#idOportunidad").val(),
    group = 2,
    formData = "",
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
var collapsedGroups = {};
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
//------- CLICKS----/
$("#btnSaveDocsCte").click(function() {
    loaderON();
    addDocumentoEnv();
});
//-----CHECKS----/
$("input[name='FileDoc']").change(function() {
    formData = new FormData();
    var idOportunidad = $("#idOportunidad").val();
    var idTipoDocumento = $("#idTipoDocumento").val();
    formData.append("idOportunidadEnv", idOportunidad);
    formData.append("idTipoDocumento", idTipoDocumento);
    formData.append("file", $("input[name='FileDoc']")[0].files[0]);
});
//------FUNCIONES---/

function tblDocsEnv() {
    idOportEnv = $("#idOportunidad").val();
    var groupColumn = 0;

    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";
    


    tableDocs = $("#tblDocs").DataTable({
        destroy: true,
        processing: true,
        pageLength: 50,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        colReorder: true,
        bLengthChange: true,
        autoWidth: true,
        // orderable: false,
        order: [
            [2, "asc"]
        ],
        columnDefs: [
            { className: "dt-center", targets: [4, 5, 9, 10] },
            { visible: false, targets: [0, 1, 2, 6, 7, 8, 11, 12, 13, 14] },
            {
                targets: [4],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
            // { orderable: false, targets: [3, 4, 5, 7, 8] },
        ],
        ajax: {
            url: serve + "api/envios/docs/" + idOportEnv,
            type: "GET",
            dataType: "json",
        },
        columns: [{
                data: "idTipoDocumento", // 0
                name: "idTipoDocumento",
                title: "#",
            },
            {
                data: "idDocumento", // 1
                name: "idDocumento",
                title: "idDocumento",
            },
            {
                data: "descTipoDocumento", // 2
                name: "descTipoDocumento",
                title: "Tipo Documento",
            },

            {
                data: "descDocumento", // 3
                name: "descDocumento",
                title: "Documento",
            },
            {
                data: "fecRegistro", // 4
                name: "fecRegistro",
                title: "Fecha Registro",
            },
            {
                data: "Login", // 5
                name: "Login",
                title: "Usuario Registro",
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
                data: "NoDocs", // 8
                name: "NoDocs",
                title: "dfsd",
            },
            {
                data: "NoDocs", // 9
                name: "NoDocs",
                title: "# Docs",
            },
            {
                data: "idDocumento", // 10
                render: function(data, type, row) {
                    // OP de la oporunidad
                    let opOportunidad = $("#numOrdenMTC").val();
                    let rolUsuario = $("#idRol").val();

                    if (!(rolUsuario == 1 || rolUsuario == 8)) {
                        if (opOportunidad == "" || opOportunidad == null || opOportunidad == undefined) {
                            if (row.idTipoDocumento == 2) {
                                return (
                                    // respaldo
                                    // '<a onclick="visorDocumentos(' + data + ')" ><i class="fas fa-download icondocsdown"></i></a><i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocsEnv(' +
                                    // data +
                                    // ')"></i>'
                                    '<a onclick="visorDocumentos(' + data + ')" ><i class="fas fa-download icondocsdown"></i></a>'
                                );
                            } else {
                                return (// '<a href="' + serve + 'api/downDocsEnvios/' + data + '" ><i class="fas fa-download icondocsdown" onclick="modal_downDocsEnv(' + row.Login + ')"></i></a><i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocsEnv(' +
                                    '<a onclick="visorDocumentos(' + data + ')" ><i class="fas fa-download icondocsdown"></i></a><i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocsEnv(' +
                                    data +
                                    ')"></i>'
                                );
                            }
                        } else {
                            return (
                                '<a onclick="visorDocumentos(' + data + ')" ><i class="fas fa-download icondocsdown"></i></a>'
                            );
                        }
                    } else {
                        return (
                            // respaldo
                            '<a onclick="visorDocumentos(' + data + ')" ><i class="fas fa-download icondocsdown"></i></a><i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocsEnv(' +
                            data +
                            ')"></i>'
                        );
                    }
                },
                name: "idDocumento",
                title: "Accion",
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
            dataSrc: "descTipoDocumento",

            startRender: function(rows, group) {
                var collapsed = !!collapsedGroups[group],
                    // noelmentos = rows.count(),
                    noelmentos = rows.data().pluck("NoDocs"),
                    // contador = 0,
                    contador = noelmentos[0],
                    firstRowData = rows.data().pluck("idTipoDocumento"); // Tomo el valor del campo que necesito}

                // OP de la oporunidad
                let opOportunidad = $("#numOrdenMTC").val();
                // rol del usuario
                let rolUsuario = $("#idRol").val();

                // if (noelmentos <= 1) { contador = 0; } else { contador = noelmentos; }
                let iconUp = '<i class="fas fa-upload  icondocsup" onclick="modal_upDocs(' + firstRowData[0] + ')" data-id="' + firstRowData[0] + '"></i>';

                let elemA = '<td colspan="3"  class="rowcolap"><i class="fas fa-plus-circle iconaddDocs icontbl"></i>&nbsp;&nbsp;' + group + '</td><td class="rowcolap text-center">' +
                        contador + '</td><td  class="rowcolap text-center">'+((opOportunidad == "" || opOportunidad == null || opOportunidad == undefined) || (rolUsuario == 1 || rolUsuario == 8) ? iconUp : '') +'</td>' 

                let elemB = '<td colspan="3"  class="rowcolap"><i class="fas fa-plus-circle icondocsBloq "></i>&nbsp;&nbsp;' + group + '</td><td class="rowcolap text-center">' +
                        contador + '</td><td  class="rowcolap text-center">'+((opOportunidad == "" || opOportunidad == null || opOportunidad == undefined) || (rolUsuario == 1 || rolUsuario == 8) ? iconUp : '') +'</td>'

                var elem = $('<tr/>')
                    .append(elemA)
                    .attr('data-name', group.replace(' ', ''))
                    .toggleClass('collapsed', collapsed);
                var elem1 = $('<tr/>')
                    .append(elemB)
                    .attr('data-name', group.replace(' ', ''));
                $(rows.nodes()).hide(); // todos  colapsados
                elem.on("click", function(e) {
                    if (collapsedGroups[group]) {
                        if ($(rows.nodes()).is(":hidden")) {
                            $(rows.nodes()).show();
                            $(".icontbl").removeClass("fa-plus-circle");
                            $(".icontbl").addClass("fa-minus-circle");
                        } else {
                            $(rows.nodes()).hide();
                            $(".icontbl").addClass("fa-plus-circle");
                            $(".icontbl").removeClass("fa-minus-circle");
                        }
                    } else {
                        if ($(rows.nodes()).is(":hidden")) {
                            $(rows.nodes()).show();
                            $(".icontbl").removeClass("fa-plus-circle");
                            $(".icontbl").addClass("fa-minus-circle");
                        } else {
                            $(rows.nodes()).hide();
                            $(".icontbl").addClass("fa-plus-circle");
                            $(".icontbl").removeClass("fa-minus-circle");
                        }
                    }
                    collapsedGroups[group] = !collapsedGroups[group];
                });
                if (noelmentos[0] >= 1) {
                    return elem;
                } else {
                    return elem1;
                }
            },
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

        "initComplete": function(settings, json) { loaderOut(); },
    });
}

function modal_upDocs(data) {
    OpenModalDoc();
    $("#idTipoDocumento").val(data);
}


function modal_deleteDocsEnv(data) {
    var idDocumentodelete = data;
    deleteDoc(idDocumentodelete);
}

function OpenModalDoc() {
    $("#mod_UpDocs").modal("show");
}

function addDocumentoEnv() {
    $.ajax({
        url: serve + "api/upDocsEnvios",
        async: false,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
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
                title: "Archivo Guardado",
            });
            $("#mod_UpDocs").modal("hide");
            loaderOut();
            $("#mod_UpDocs :input").val("");
            tableDocs.ajax.reload();
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
                title: "Ocurrió un error al Subir el Archivo, vuelve a intentarlo",
            });
        },
    });
}

function deleteDoc(idDocumentodelete) {
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
                url: serve + "deleteDocsEnvios/",

                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idDocumentoEnvdelete: idDocumentodelete,
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
                        title: "Documento Eliminado",
                    });
                    $("#mod_UpDocsEnv").modal("hide");
                    loaderOut();
                    $("#mod_UpDocsEnv :input").val("");
                    tblDocsEnv();
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
                        title: "Ocurrió un error, vuelve a intentarlo",
                    });
                },
            });
        }
    });
}

function visorDocumentos(idDocumento) {
    loaderModalON();
    $.ajax({
        url: serve + "getDocumentoEnvio",
        type: "GET",
        data: {
            _method: "GET",
            idDocumento: idDocumento,
        },
        success: function(data) {

            extension = data.Extension;
            byteCharacters = atob(data.Documento);
            descDocumento = data.descDocumento;

            if (extension.match(/^.*vnd.*$/) || extension.match(/^.*stream$/)) {

                window.location.href = serve + 'api/downDocsEnvios/' + idDocumento;

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
        error: function() {},
    });
}

$(function() {});

$(document).ready(function() {
    ///--------FUNCIONES----//
});