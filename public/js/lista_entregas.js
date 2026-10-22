serve = base_path;
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$(document).ready(function () {
    loaderOut();

    const FROM_PATTERN_SHORT = "YYYY-MM-DD";
    const TO_PATTERN_SHORT = "DD-MM-YYYY";

    tblListaEntregas = $("#tblListaEntregas").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: false,
        serverSide: true,
        select: false,
        deferRender: true,
        scrollY: '72vh',
        scrollX: true,
        scrollCollapse: false,
        order: [
            0, "asc"
        ],
        responsive: false,
        orderCellsTop: true,
        fixedHeader: true,
        statusSave: true,
        pageLength: -1,
        rowId: "id",
        ajax: {
            url: serve + "repEntregas",
            type: "GET",
            dataType: "json"
        },
        language: {
            url: serve + "js/spanish-DT.json"
        },
        dom: '<"top">rt<"bottom"><"clear">',
        columnDefs: [{
            targets: [0, 5, 6, 7, 8, 9],
            className: "align-middle dt-body-center text-center font-weight-bold",
            orderable: false
        },
        {
            targets: [1, 2],
            className: "align-middle dt-body-center text-left font-weight-bold",
            orderable: false
        },
        {
            targets: [3, 4],
            className: "align-middle dt-body-center text-right font-weight-bold",
            orderable: false
        },
        {
            targets: [
                6, 5
            ],
            render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT)
        },
        ],
        columns: [{
            data: "NumOrden",
            title: "Numero Orden"
        },
        {
            data: "NombreTrabajo",
            title: "Nombre Trabajo"
        },
        {
            data: "NombreCliente",
            title: "Cliente"
        },
        {
            data: "CantEntregar",
            render: $.fn.dataTable.render.number(",", ".", 0, "", ""),
            title: "Cant Entregar"
        }, {
            data: "CantEntregado",
            render: $.fn.dataTable.render.number(",", ".", 0, "", ""),
            title: "Cant Entregado"
        }, {
            data: "FechaEntregar",
            title: "Fecha Entregar"
        }, {
            data: "FechaEntregado",
            title: "Fecha Entregado"
        }, {
            data: "atiempo",
            render: function (data, type, row) {
                if (data == "NO") {
                    return '<strong class="text-red">' + data + '</strong>';
                } else if (data == "SI") {
                    return '<strong class="text-green">' + data + '</strong>';
                } else {
                    return '<strong>' + data + '</strong>';
                }
            },
            title: "A Tiempo",
            visible: false,
        },
        {
            data: "idEntrega",
            render: function (data, type, row) {
                if (row.respuesta == "NO") {
                    return ('<div class="custom-switch"><input id="no-' + data + '" type="checkbox" onchange="updateEntrega(' + data + ', 0)" class="custom-control-input" checked><label for="no-' + data + '"class="custom-control-label custom-control-label-ent" style="cursor: pointer"></label></div>');
                } else {
                    return ('<div class="custom-switch"><input id="no-' + data + '" type="checkbox" onchange="updateEntrega(' + data + ', 0)" class="custom-control-input"><label for="no-' + data + '"class="custom-control-label custom-control-label-ent" style="cursor: pointer"></label></div>');
                }
            },
            title: "NO"
        },
        {
            data: "idEntrega",
            render: function (data, type, row) {
                if (row.respuesta == "SI") {
                    return ('<div class="custom-switch"><input id="si-' + data + '" type="checkbox" onchange="updateEntrega(' + data + ', 1)" class="custom-control-input" checked><label for="si-' + data + '" class="custom-control-label custom-control-label-ent" style="cursor: pointer"></label></div>');
                } else {
                    return ('<div class="custom-switch"><input id="si-' + data + '" type="checkbox" onchange="updateEntrega(' + data + ', 1)" class="custom-control-input"><label for="si-' + data + '" class="custom-control-label custom-control-label-ent" style="cursor: pointer"></label></div>');
                }
            },
            title: "SI"
        },
        ],
        drawCallback: function (settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        initComplete: function (settings, json) {
            loaderOut();
        }
    });

    selectRow("tblListaEntregas");

})

function updateEntrega(id, si_no) {

    no = $("#no-" + id);
    si = $("#si-" + id);
    no_check = no[0].checked;
    si_check = si[0].checked;

    if (si_no == 0) {
        mensaje = "Calificación Actualizada";
        const { value: text } = Swal.fire({
            input: "textarea",
            inputLabel: "Motivo de la Calificación",
            inputPlaceholder: "Captura el motivo de la Calificación",
            inputAttributes: {
                "aria-label": "Captura el motivo de la Calificación",
            },
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Guardar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value != "") {
                    $.ajax({
                        url: serve + "actualizarEntrega",
                        type: "PATCH",
                        dataType: "JSON",
                        data: {
                            id: id,
                            respuesta: respuesta,
                            motivoCalificacion: result.value,
                            _method: "PATCH",
                            _token: CSRF_TOKEN
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
                            tblListaEntregas.draw();
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
                                title: mensaje,
                            });
                            tblListaEntregas.draw();
                        },
                    });
                } else {
                    Swal.fire("¡Debes capturar el motivo!");
                    tblListaEntregas.draw();
                }
            } else {
                tblListaEntregas.draw();
            }
        });
        if (no_check == false && si_check == true) {
            $("#si-" + id)[0].checked = true;
            $("#no-" + id)[0].checked = false;
            respuesta = 'SI';
        } else if (no_check == true && si_check == false) {
            $("#no-" + id)[0].checked = true;
            $("#si-" + id)[0].checked = false;
            respuesta = 'NO';
        } else if (no_check == true && si_check == true) {
            $("#no-" + id)[0].checked = true;
            $("#si-" + id)[0].checked = false;
            respuesta = 'NO';
        } else if (no_check == false && si_check == false) {
            $("#no-" + id)[0].checked = false;
            $("#si-" + id)[0].checked = false;
            respuesta = null;
        }
    } else if (si_no == 1) {
        if (no_check == false && si_check == true) {
            $("#si-" + id)[0].checked = true;
            $("#no-" + id)[0].checked = false;
            respuesta = 'SI';
        } else if (no_check == true && si_check == false) {
            $("#no-" + id)[0].checked = false;
            $("#si-" + id)[0].checked = true;
            respuesta = 'SI';
        } else if (no_check == true && si_check == true) {
            $("#no-" + id)[0].checked = false;
            $("#si-" + id)[0].checked = true;
            respuesta = 'SI';
        } else if (no_check == false && si_check == false) {
            $("#no-" + id)[0].checked = false;
            $("#si-" + id)[0].checked = false;
            respuesta = null;
        }

        $.ajax({
            url: serve + "actualizarEntrega",
            type: "PATCH",
            dataType: "JSON",
            data: {
                id: id,
                respuesta: respuesta,
                _method: "PATCH",
                _token: CSRF_TOKEN
            },
            success: function (data) { },
            error: function () { }
        });

    }

}