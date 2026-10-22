///---------------------------ING. BRIGETTE ISELA GUTIERREZ GONZALEZ---------//
//-----SEP 2021
//----------------------------------------------------------------------------//

serve = base_path;

var formData = "",
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
//------- CLICKS----/
$("#btnSaveDocsAdmins").click(function() {
    addDocumento();
});
//-----CHECKS----/
$("input[name='FileDocAdmins']").change(function() {
    formData = new FormData();
    var idTipoDocumento = $("#idTipoDocumento").val();
    var tipoDocumento = $("#tipoDocumento").val();
    var idCliente = $("#idCliente").val();
    var idPedido = $("#idPedido").val();
    var idDocumentosAsignados = $("#idDocumentosAsignados").val();

    formData.append("idPedido", idPedido);
    formData.append("idCliente", idCliente);
    formData.append("idTipoDocumento", idTipoDocumento);
    formData.append("tipoDocumento", tipoDocumento);
    formData.append("idDocumentosAsignados", idDocumentosAsignados);
    formData.append("_token", CSRF_TOKEN);
    formData.append("_method", "POST");
    formData.append("file", $("input[type=file]")[0].files[0]);
});
//------FUNCIONES---/
function modal_upDocs(
    idRequisito,
    tipoDoc,
    idCliente,
    idPedido,
    idDocumentosAsignados
) {
    OpenModalDoc();
    $(".lblDocsUp").text("");
    $("#idTipoDocumento").val(idRequisito);
    $("#tipoDocumento").val(tipoDoc);
    $("#idCliente").val(idCliente);
    $("#idPedido").val(idPedido);
    $("#idDocumentosAsignados").val(idDocumentosAsignados);
}

function modal_downDocs(data) {}

function modal_deleteDocs(data) {
    var idDocumentodelete = data;
    deleteDoc(idDocumentodelete);
}

function OpenModalDoc() {
    $("#mod_UpDocsAdmins").modal("show");
    $(".uploadExtensionError").hide();
}

function addDocumento() {
    loaderON();
    $.ajax({
        url: serve + "api/upDocsAdmin",
        async: false,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
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
            $("#mod_UpDocsAdmins").modal("hide");
            $("#mod_UpDocsAdmins :input").val("");
            tblRequisitosCliente.ajax.reload();
            tblPedidosCliente.ajax.reload();
            loaderOut();
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

            $(".uploadExtensionError").hide();

            loaderOut();
        },
    });
}

$(function() {

    const MAXIMO_TAMANIO_BYTES = 10240000; // 1MB = 1 millón de bytes

    const $FileDocAdmins = document.getElementById("FileDocAdmins");

    $FileDocAdmins.addEventListener("change", function() {
        // si no hay archivos, regresamos
        if (this.files.length <= 0) return;

        // Validamos el primer archivo únicamente
        const archivo = this.files[0];
        if (archivo.size > MAXIMO_TAMANIO_BYTES) {
            const tamanioEnMb = MAXIMO_TAMANIO_BYTES / 1024000;

            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 6000,
            });

            Msg.fire({
                title: `El tamaño máximo es de ${tamanioEnMb} MB`,
            });

            // alert(`El tamaño máximo es de ${tamanioEnMb} MB`);
            // Limpiar
            $FileDocAdmins.value = "";
        } else {

            var val = $("input[type=file]").val().toLowerCase();
            var regex = new RegExp(
                "(.*?).(pdf|docx|doc|xls|xlsx|jpg|png|bmp|jpeg|xml|ppt|pptx|txt|csv)$"
            );

            if (!regex.test(val)) {
                $(".uploadExtensionError").show();
                $("#FileDocAdmins").val("");
                event.preventDefault();
            } else {
                // loaderON();
                // addDocumento();
            }




        }
    });

})

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
                url: serve + "deleteDocsAdmins/",

                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idDocumentodelete: idDocumentodelete,
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
                    $("#mod_UpDocsAdmins").modal("hide");
                    loaderOut();
                    $("#mod_UpDocsAdmins :input").val("");
                    tblRequisitosCliente.ajax.reload();
                    tblPedidosCliente.ajax.reload();
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

$(document).ready(function() {});