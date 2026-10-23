///---------------------------ING. BRIGETTE ISELA GUTIERREZ GONZALEZ---------//
//-----SEP 2021
//----------------------------------------------------------------------------//

//------FUNCIONES---/
serve = base_path;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

function changeCFD(id) {
    check = $("#CFD_" + id).is(":checked") ? 0 : 1;
    Mensaje = "Actualizar el estatus de Enviar CFD del Contacto Seleccionado?";
    title = "Contacto Actualizado";
    ruta = serve + "updateCFD/";
    addChange(id, Mensaje, ruta, check, title);
}

function changeCXP(id) {
    check = $("#CXP_" + id).is(":checked") ? 0 : 1;
    Mensaje = "Actualizar el estatus de Contacto CXP del Contacto Seleccionado?";
    title = "Contacto Actualizado";
    ruta = serve + "updateCXP/";
    addChange(id, Mensaje, ruta, check, title);
}

function changeActivo(id) {
    check = $("#activo_" + id).is(":checked") ? 0 : 1;
    // check_ = $("#activo_ctecto_" + id).is(":checked") ? 0 : 1;

    if (check == 0) {
        Mensaje = "Inactivar Contacto";
        title = "Contacto Inactivo";
    } else {
        Mensaje = "Activar Contacto";
        title = "Contacto Activo";
    }

    // if (check_ == 0) {
    //     Mensaje = "Inactivar Contacto";
    //     title = "Contacto Activo";
    // } else {
    //     Mensaje = "Activar Contacto";
    //     title = "Contacto Inactivo";
    // }
    
    ruta = serve + "updateActivo/";

    addChange(id, Mensaje, ruta, check, title);
}

function addChange(id, Mensaje, ruta, check, title) {
    // Swal.fire({
    //     title: "Contactos",
    //     text: Mensaje,
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#999",
    //     confirmButtonText: "Si",
    //     cancelButtonText: "No",
    //     allowEnterKey: false,
    //     stopKeydownPropagation: true,
    //     reverseButtons: true,
    //     returnFocus: true,
    // }).then((result) => {
    //     if (result.isConfirmed) {
            $.ajax({
                url: ruta,
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    check: check,
                    idContacto: id,
                    title: title,
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
                        title: title,
                    });
                    tableCtos.ajax.reload();
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
    //     }
    // });
}

$(document).ready(function() {
    loaderON();
    $("#btn-filters").tooltip({
        placement: "top",
    });

    ///----------------------------------------------------------------------- Datatable Contactos-------------------------------------------------------------///

    tableCtos = $("#tableContactosAll").DataTable({
        destroy: true,
        processing: true,
        pageLength: 50,
        stateSave: false,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        colReorder: true,
        bLengthChange: true,
        autoWidth: true,

        order: [
            [0, "desc"]
        ],
        ajax: {
            url: "api/contactos/all",
            type: "GET",
            dataType: "json",
        },
        columns: [{
                data: "idContactoINT",
                name: "idContactoINT",
                title: "ID",
            },
            {
                data: "CFD_Enviar",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 11 ||
                        row.inputRol == 8 
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CFD_' +
                                    row.idContactoINT +
                                    '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCFD(' +
                                    row.idContactoINT +
                                    ')"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CFD_' +
                                    row.idContactoINT +
                                    '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCFD(' +
                                    row.idContactoINT +
                                    ')"></label></div>'
                                );
                            }
                        } else {
                            return data;
                        }
                    } else {
                        if (
                            $.trim(row.inputLogin) == $.trim(row.Login) ||
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if (data == 0) {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CFD_' +
                                        row.idContactoINT +
                                        '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCFD(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                } else {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CFD_' +
                                        row.idContactoINT +
                                        '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCFD(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                }
                            } else {
                                if (data == 0) {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CFD_' +
                                        row.idContactoINT +
                                        '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCFD(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                } else {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CFD_' +
                                        row.idContactoINT +
                                        '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCFD(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                }
                            }
                        } else {
                            if (data == 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label  class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label  class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }
                        }
                    }
                },
                name: "CFD_Enviar",
                title: "CFD",
            },
            {
                data: "contactoCXP",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 11 ||
                        row.inputRol == 8
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CXP_' +
                                    row.idContactoINT +
                                    '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCXP(' +
                                    row.idContactoINT +
                                    ')"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CXP_' +
                                    row.idContactoINT +
                                    '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCXP(' +
                                    row.idContactoINT +
                                    ')"></label></div>'
                                );
                            }
                        } else {
                            return data;
                        }
                    } else {
                        if (
                            $.trim(row.inputLogin) == $.trim(row.Login) ||
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if (data == 0) {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CXP_' +
                                        row.idContactoINT +
                                        '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCXP(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                } else {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CXP_' +
                                        row.idContactoINT +
                                        '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCXP(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                }
                            } else {
                                if (data == 0) {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CXP_' +
                                        row.idContactoINT +
                                        '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCXP(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                } else {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="CXP_' +
                                        row.idContactoINT +
                                        '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeCXP(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                }
                            }
                        } else {
                            if (data == 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label  class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label  class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }
                        }
                    }
                },
                name: "contactoCXP",
                title: "CXP",
            },
            {
                data: "activo",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 11 ||
                        row.inputRol == 8
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0 || data == null) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activo_' +
                                    row.idContactoINT +
                                    '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeActivo(' +
                                    row.idContactoINT +
                                    ')"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activo_' +
                                    row.idContactoINT +
                                    '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeActivo(' +
                                    row.idContactoINT +
                                    ')"></label></div>'
                                );
                            }
                        } else {
                            return data;
                        }
                    } else {
                        if (
                            $.trim(row.inputLogin) == $.trim(row.Login) ||
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if (data == 0 || data == null) {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activo_' +
                                        row.idContactoINT +
                                        '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeActivo(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                } else {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activo_' +
                                        row.idContactoINT +
                                        '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeActivo(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                }
                            } else {
                                if (data == 0 || data == null) {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activo_' +
                                        row.idContactoINT +
                                        '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="changeActivo(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                } else {
                                    return (
                                        '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" id="activo_' +
                                        row.idContactoINT +
                                        '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="changeActivo(' +
                                        row.idContactoINT +
                                        ')"></label></div>'
                                    );
                                }
                            }
                        } else {
                            if (data == 0 || data == null) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label  class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label  class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }
                        }
                    }
                },
                name: "activo",
                title: "Activo",
            },
            {
                data: "esEnvio",
                render: function(data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 1013 || row.inputRol == 1015) {
                        if (data == 1) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="sucEnvio' + row.idContacto + '" checked><label onclick="contactoEnvio(' + row.idContacto + ', 0)" class="custom-control-label" style="cursor: pointer"></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="sucEnvio' + row.idContacto + '"><label onclick="contactoEnvio(' + row.idContacto + ', 1)" class="custom-control-label" style="cursor: pointer"></label></div>';
                        }
                    } else {
                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="sucEnvio' + row.idContacto + '" disabled ' + (data == 1 ? 'checked' : '') + '><label class="custom-control-label" disabled></label></div>';
                    }
                },
                name: "esEnvio",
                title: "Es Envío",
            },
            {
                data: "Tratamiento",
                name: "Tratamiento",
                title: "Titulo",
            },
            {
                data: "Nombre",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 8 ||
                        row.inputRol == 11 ||
                        row.inputRol == 1010
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return (
                                '<a class="text-primary" type="button" onclick="editarContactoCliente(' +
                                row.idContacto +
                                ", " +
                                row.cto_principal +
                                ", " +
                                row.idClienteINT +
                                ' )" title="Editar Contacto">' +
                                data +
                                "</a>"
                            );
                        } else {
                            return data;
                        }
                    } else {
                        if (
                            $.trim(row.inputLogin) == $.trim(row.Login) ||
                            $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin)
                        ) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                return (
                                    '<a class="text-primary" type="button" onclick="editarContactoCliente(' +
                                    row.idContacto +
                                    ", " +
                                    row.cto_principal +
                                    ", " +
                                    row.idClienteINT +
                                    ' )" title="Editar Contacto">' +
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
                name: "Nombre",
                title: "Nombre",
            },
            {
                data: "Cargo",
                name: "Cargo",
                title: "Cargo",
            },
            {
                data: "Departamento",
                name: "Departamento",
                title: "Departamento",
            },
            {
                data: "Telefonos",
                name: "Telefonos",
                title: "Telefonos",
            },
            {
                data: "email",
                name: "email",
                title: "Email",
            },
            {
                data: "razonsocial",
                name: "razonsocial",
                title: "Cliente",
            },
        ],
        columnDefs: [{
            className: "dt-center",
            targets: [0, 1, 2, 3],
        }, ],
        // fixedColumns: {
        //     leftColumns: 1,
        // },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpB><"clear">',
        select: false,
        buttons: [{
                extend: "colvis",
                columns: ":gt(0)",
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
            },
            {
                extend: "excelHtml5",
                title: "Contactos | CANDY CRM",
                text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                exportOptions: {
                    orthogonal: "export",
                    columns: "thead th:not(.noExport)",
                },
                filename: "Contactos",
                autoFilter: true,
                sheetName: "Lista",
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
        bInfo: true,
        lengthMenu: [
            [50, 100, 150, 200, -1],
            [50, 100, 150, 200, 'TODOS'],
        ],
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        initComplete: function(settings, json) {
            loaderOut();
        },
    });

    selectRow("tableContactosAll");

    $("#tableContactosAll")
        .DataTable()
        .on("draw", function() {
            ajustarTablas();
        });
});
$(window).resize(function() {
    ajustarTablas();

    function ajustarTablas() {
        var datatableCtos = $("#tableContactosAll").DataTable();
        var height = window.innerHeight;
        if (height >= 320 && height <= 651) {
            $("#tableContactosAll")
                .closest(".dataTables_scrollBody")
                .css("height", "65vh");
        } else if (height >= 652 && height <= 768) {
            $("#tableContactosAll")
                .closest(".dataTables_scrollBody")
                .css("height", "68vh");
        } else if (height >= 769 && height <= 1024) {
            $("#tableContactosAll")
                .closest(".dataTables_scrollBody")
                .css("height", "77vh");
        } else if (height >= 1025 && height <= 1366) {
            $("#tableContactosAll")
                .closest(".dataTables_scrollBody")
                .css("height", "80vh");
        } else if (height >= 1440) {
            $("#tableContactosAll")
                .closest(".dataTables_scrollBody")
                .css("height", "82vh");
        }
        datatableCtos.draw();
    }
});

// ============================================================= FUNCIONES JCG ==========================================

var mensaje = "";
var CFD_EnviarCto = document.getElementById('CFD_EnviarCto');
var contactoCXPCto = document.getElementById('contactoCXPCto');
var principalCto = document.getElementById('principalCto');

$("#modal-create-contactoCte").on("hidden.bs.modal", function() {
    $(this).find("form").trigger("reset");
});

$("#modal-create-contactoCte").draggable({
    handle: ".titulo",
});

$("#modal-create-contactoCte").on("show.bs.modal", function() {
    $("#Tratamiento").trigger("focus");
});

CFD_EnviarCto.addEventListener('change', function() {
    check = CFD_EnviarCto.checked

    if (check == true) {
        $('#CFD_Enviar_input').val('on');
    } else {
        $('#CFD_Enviar_input').val('');

    }

});

contactoCXPCto.addEventListener('change', function() {
    check = contactoCXPCto.checked

    if (check == true) {
        $('#contactoCXP_input').val('on');
    } else {
        $('#contactoCXP_input').val('');

    }

});

principalCto.addEventListener('change', function() {
    check = principalCto.checked

    if (check == true) {
        $('#principal_input').val('on');
    } else {
        $('#principal_input').val('');

    }

});

function contactoEnvio(idContacto, check) {
    let checkValue = check;

    let formdata = new FormData();
    formdata.append("_token", CSRF_TOKEN);
    formdata.append("esEnvio", checkValue);
    formdata.append("idContacto", idContacto);

    Swal.fire({
        title: "Contactos",
        text: (checkValue == 1 ? "Marcar" : "Desmarcar") + ' como "Contacto de Envío" el Contacto Seleccionado?',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        allowEnterKey: false,
        stopKeydownPropagation: true,
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "actualizarEnvio",
                type: "POST",
                data: formdata,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        Msg.fire({
                            title: 'Contacto ' + (checkValue == 1 ? "marcado" : "desmarcado") + ' como "Contacto de Envío"',
                        });
                        tableCtos.ajax.reload();
                    }
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
                    tableCtos.ajax.reload();
                }
            });
        } else {
            document.getElementById("contactoEnvio" + idContacto).checked = !checkValue;
        }
    });
}

function agregarContactoCte() {
    mensaje = "Contacto Agregado";
    save_method = "add";
    $("imput[name=_method]").val("POST");
    $("#modal-create-contactoCte form").trigger("reset");
    $("#modal-create-contactoCte").modal("show");
    $(".titulo").text("Agregar Contacto");
    document.getElementById("SelectCliente").style.display = "block";
    $("#idClienteINT").prop("required", true);
}

function editarContactoCliente(id, ctoPrincipal, idCliente) {
    mensaje = "Contacto Actualizado";
    save_method = "edit";
    $("imput[name=_method]").val("PATCH");
    $.ajax({
        url: serve + "contactos" + "/" + id + "/edit",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            if (data.idClienteINT != null) {
                document.getElementById("SelectCliente").style.display = "none";
                $("#idClienteINT").prop("required", false);
            }
            $("#idClienteINTCto").val(data.idClienteINT);
            $("#idContacto").val(data.idContacto);
            $("#idContactoINT").val(data.idContactoINT);
            $("#Tratamiento").val(data.Tratamiento);
            $("#Nombre").val(data.Nombre);
            $("#Cargo").val(data.Cargo);
            $("#Departamento").val(data.Departamento);
            $("#TelefonosCto").val(data.Telefonos);
            $("#email").val(data.email);
            if (data.CFD_Enviar == "0") {
                $("#CFD_EnviarCto").prop("checked", false);
                $("#CFD_Enviar_input").val("");
            } else if (data.CFD_Enviar == "1") {
                $("#CFD_EnviarCto").prop("checked", true);
                $("#CFD_EnviarCto").val("on");
                $("#CFD_Enviar_input").val("on");
            }
            if (data.contactoCXP == "0") {
                $("#contactoCXPCto").prop("checked", false);
                $("#contactoCXP_input").val("");
            } else if (data.contactoCXP == "1") {
                $("#contactoCXPCto").prop("checked", true);
                $("#contactoCXPCto").val("on");
                $("#contactoCXP_input").val("on");
            }
            if (data.idContactoINT == ctoPrincipal) {
                $("#principalCto").prop("checked", true);
                $("#principalCto").val("on");
                $("#principal_input").val("on");
            } else {
                $("#principalCto").prop("checked", false);
                $("#principal_input").val("");
            }

            $("#modal-create-contactoCte").modal("show");
            $(".titulo").text("Editar Contacto");
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

$(function() {
    $("#modal-create-contactoCte form")
        .validator()
        .on("submit", function(e) {
            if (!e.isDefaultPrevented()) {
                var id = $("#idClienteINTCto").val();
                if (save_method == "add") {
                    url = serve + "contactos";
                    method = "POST";
                } else {
                    url = serve + "contactos" + "/" + id;
                    method = "PUT";
                }
                $.ajax({
                    url: url,
                    method: method,
                    dataType: "json",
                    data: $("#modal-create-contactoCte form").serialize(),
                    success: function(data) {
                        $("#modal-create-contactoCte").trigger("reset");
                        $("#modal-create-contactoCte").modal("hide");
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
                        tableCtos.ajax.reload();
                    },
                    error: function() {
                        $("#modal-create-contactoCte").trigger("reset");
                        $("#modal-create-contactoCte").modal("hide");
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
                        tableCtos.ajax.reload();
                    },
                });
                return false;
            }
        });
});