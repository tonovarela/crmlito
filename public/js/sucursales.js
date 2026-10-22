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

function addChange(id, Mensaje, id_Usuario, ruta, check) {
    Swal.fire({
        title: "Sucursales",
        text: 'Actualizar el estatus de "' + Mensaje + '" de la Sucursal Seleccionada?',
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
                url: ruta,
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    check: check,
                    idContacto: id,
                    id_Usuario: id_Usuario,
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
                        title: "Sucursal Actualizada",
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
        }
    });
}

$(document).ready(function() {

    var idClienteINT = document.getElementById('idClienteINT');
    var idClienteINTSuc = document.getElementById('idClienteINTSuc');
    // var direccion_chek = document.getElementById('mismaDireccion_check');
    var id_Cliente_INT = idClienteINT.value;

    var cp = document.getElementById('CodigoPostal');
    var alcaldia = document.getElementById('Delegacion');
    var colonia = document.getElementById('Colonia');
    var poblacion = document.getElementById('Poblacion');
    var estado = document.getElementById('Estado');
    var pais = document.getElementById('Pais');

    var idClienteSelec2 = $('#idClienteINT').select2({
        language: "es"
    });

    var cpslista = document.getElementById('CodigoPostalListaSuc');
    var btnsave = document.getElementById('saveBtn');

    $("#Direccion").bind('keypress', function(event) {
        soloAlfanumericos(event);
    });

    $('#Direccion').bind('paste', function(e) {
        e.preventDefault();
    });

    $("#DireccionNumero").bind('keypress', function(event) {
        soloAlfanumericos(event);
    });

    $('#DireccionNumero').bind('paste', function(e) {
        e.preventDefault();
    });

    $("#DireccionNumeroInt").bind('keypress', function(event) {
        soloAlfanumericos(event);
    });

    $('#DireccionNumeroInt').bind('paste', function(e) {
        e.preventDefault();
    });

    // cpslista.addEventListener('change', function() {
    //     btnsave.disabled = false;
    // });


    $("#CodigoPostalListaSuc").autocomplete({
        delay: 0,
        minLength: 4,
        source: function(request, response) {
            $.ajax({
                url: serve + 'codigos',
                type: 'GET',
                dataType: "json",
                data: {
                    _token: CSRF_TOKEN,
                    search: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        select: function(event, ui) {
            var codigo = JSON.stringify(ui.item.value)
            $.ajax({
                url: serve + 'buscarcp',
                dataType: "json",
                data: {
                    id_Codigo: codigo
                },
                success: function(data) {
                    cp.value = data[0].Codigo;
                    alcaldia.value = data[0].Municipio;
                    colonia.value = data[0].Asentamiento;
                    if (data[0].Localidad == null) {
                        poblacion.value = "";
                    } else {
                        poblacion.value = data[0].Localidad;
                    }
                    estado.value = data[0].Estado;
                    pais.value = data[0].Pais;
                },
                error: function(data) {
                    cp.value = "";
                    alcaldia.value = "";
                    colonia.value = "";
                    poblacion.value = "";
                    estado.value = "";
                    pais.value = "";
                }

            });
            return false;
        }
    });

    loaderON();
    $("#btn-filtersSucs").tooltip({
        placement: "top",
    });

    ///----------------------------------------------------------- Datatable Sucursales -----------------------------------------------------------///

    tableSucs = $("#tableSucursalesAll").DataTable({
        destroy: true,
        processing: true,
        pageLength: 50,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        bLengthChange: true,
        autoWidth: true,
        order: [0, "desc"],
        ajax: {
            url: serve + "api/sucursales/all",
            type: "GET",
            dataType: "json",
        },
        columns: [{
                data: "idClienteINT",
                name: "idClienteINT",
                title: "ID Cliente",
            },
            {
                data: "razonsocial",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 8 ||
                        row.inputRol == 1015 ||
                        row.inputRol == 1012 ||
                        row.inputRol == 1011 ||
                        row.inputRol == 1013
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return (
                                '<a class="text-uppercase" href="clientes/' +
                                row.idClienteINT +
                                '/edit" title="Editar Cliente">' +
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
                                    '<a class="text-uppercase" href="clientes/' +
                                    row.idClienteINT +
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
                name: "razonsocial",
                title: "Cliente",
            },
            {
                data: "idSucursal",
                name: "idSucursal",
                title: "Id Sucursal",
            },
            {
                data: "Estatus",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 8 ||
                        row.inputRol == 1015 ||
                        row.inputRol == 1012 ||
                        row.inputRol == 1011
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if ($.trim(data) === "BAJA") {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="eliminarSucursal(' +
                                    row.idSucursal +
                                    ", " +
                                    row.input_idUsuario +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="eliminarSucursal(' +
                                    row.idSucursal +
                                    ", " +
                                    row.input_idUsuario +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }
                        } else {
                            if ($.trim(data) === "BAJA") {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row.inputLogin) == $.trim(row.LoginAuxiliar)) {
                            if ($.trim(data) === "BAJA") {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="eliminarSucursal(' +
                                    row.idSucursal +
                                    ", " +
                                    row.input_idUsuario +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return (
                                    '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="eliminarSucursal(' +
                                    row.idSucursal +
                                    ", " +
                                    row.input_idUsuario +
                                    ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            }
                        } else {
                            if ($.trim(data) === "BAJA") {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    }
                },
                name: "Estatus",
                title: "Estatus",
            },
            {
                data: "principal",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 8 ||
                        row.inputRol == 1015 ||
                        row.inputRol == 1012 ||
                        row.inputRol == 1011
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if ($.trim(row.Estatus) === "BAJA") {
                                if (data == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            } else {
                                if (data == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="sucPrincipal(' + row.idSucursal + ', ' + row.input_idUsuario + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="sucPrincipal(' + row.idSucursal + ', ' + row.input_idUsuario + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                }
                            }
                        } else {
                            if (data == 0) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row.inputLogin) == $.trim(row.LoginAuxiliar)) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if ($.trim(row.Estatus) === "BAJA") {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label"></label></div>';
                                } else {
                                    if (data == 0) {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="sucPrincipal(' + row.idSucursal + ', ' + row.input_idUsuario + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                    } else {
                                        return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="sucPrincipal(' + row.idSucursal + ', ' + row.input_idUsuario + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                                    }
                                }
                            } else {
                                if ($.trim(data) == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        } else {
                            if ($.trim(data) == 0) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    }
                },
                name: "principal",
                title: "Principal",
            },
            {
                data: "Nombre",
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 8 ||
                        row.inputRol == 1015 ||
                        row.inputRol == 1012 ||
                        row.inputRol == 1011 ||
                        row.inputRol == 1013
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return (
                                '<a class="text-primary text-uppercase" type="button" onclick="editarSucursalCliente(' +
                                row.idSucursal +
                                ", " +
                                row.idClienteINT +
                                ' )" title="Editar Sucursal">' +
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
                                    '<a class="text-primary text-uppercase" type="button" onclick="editarSucursalCliente(' +
                                    row.idSucursal +
                                    ", " +
                                    row.idClienteINT +
                                    ' )" title="Editar Sucursal">' +
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
                title: "Nombre Sucursal",
            },
            {
                data: "Encargado",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Encargado",
                title: "Encargado",
            },
            {
                data: "eMail1",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-lowercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "eMail1",
                title: "Correo",
            },
            {
                data: "Direccion",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Direccion",
                title: "Calle",
            },
            {
                data: "DireccionNumero",
                render: function(data, type, row) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "DireccionNumero",
                title: "Número Ext.",
            },
            {
                data: "DireccionNumeroInt",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "DireccionNumeroInt",
                title: "Número Int.",
            },
            {
                data: "Colonia",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Colonia",
                title: "Colonia",
            },
            {
                data: "Delegacion",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Delegacion",
                title: "Delegación",
            },
            {
                data: "Poblacion",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Poblacion",
                title: "Población",
            },
            {
                data: "Estado",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Estado",
                title: "Estado",
            },
            {
                data: "Pais",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Pais",
                title: "País",
            },
            {
                data: "CodigoPostal",
                render: function(data) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "CodigoPostal",
                title: "Código Postal",
            },
            {
                data: "Telefonos",
                render: function(data, type, row) {
                    if (data != null) {
                        return '<span class="text-uppercase">' + data + "</span>";
                    } else {
                        return data;
                    }
                },
                name: "Telefonos",
                title: "Telefonos",
            },
        ],
        columnDefs: [{
            className: "dt-center",
            targets: [0, 2, 3, 4],
        }, ],
        // fixedColumns: {
        //     leftColumns: 5,
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
                title: "Sucursales Cliente | CANDY CRM",
                text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                exportOptions: {
                    orthogonal: "export",
                    columns: "thead th:not(.noExport)",
                },
                filename: "Sucursales Cliente",
                autoFilter: true,
                sheetName: "Lista",
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
        bInfo: true,
        lengthMenu: [
            [50, 100, 150, 200],
            [50, 100, 150, 200],
        ],
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip();
        },
        initComplete: function(settings, json) {
            loaderOut();
        },
    });

    selectRow("tableSucursalesAll");

    $("#tableSucursalesAll")
        .DataTable()
        .on("draw", function() {
            ajustarTablasSucs();
        });

    // idClienteSelec2.on("change", function() {

    //     var idClienteSelec2 = $('#idClienteINT').select2("data");


    //     id_Cliente_INT = idClienteSelec2[0].element.value;
    //     direccion_chek.disabled = false;

    //     $.ajax({
    //         url: serve + 'getDireccionFiscal/' + id_Cliente_INT,
    //         type: 'GET',
    //         dataType: 'JSON',
    //         success: function(r) {
    //             $("#mismaDireccion_check").prop("disabled", true);
    //             $("#mismaDireccion_check").prop("checked", false);
    //             document.getElementById("mismaDireccion_check_validate").style.display = "block";

    //             $('#mismaDireccion').val(0);

    //             $("#Direccion").val("");
    //             $("#DireccionNumero").val("");
    //             $("#DireccionNumeroInt").val("");
    //             $("#Colonia").val("");
    //             $("#Delegacion").val("");
    //             $("#Poblacion").val("");
    //             $("#Estado").val("");
    //             $("#Pais").val("");
    //             $("#CodigoPostal").val("");
    //         }
    //     });
    // });

    // direccion_chek.addEventListener("change", function() {
    //     id_Cliente_INT = idClienteINT.value;
    //     direccionPrincipal(direccion_chek.checked, id_Cliente_INT);
    // });

    // function direccionPrincipal(check, id_Cliente_INT) {

    //     if (id_Cliente_INT == "Requerido") {
    //         idCliente = idClienteINTSuc.value;
    //     } else {
    //         idCliente = id_Cliente_INT;
    //     }

    //     if (check == true) {

    //         $('#mismaDireccion').val(1);

    //         $.ajax({
    //             url: serve + "direccionPrincipal/" + idCliente,
    //             type: "GET",
    //             dataType: "JSON",
    //             success: function(data) {

    //                 $("#Direccion").val(data.DirecccionFiscal);
    //                 $("#DireccionNumero").val(data.DireccionFiscalNumero);
    //                 $("#DireccionNumeroInt").val(data.DireccionFiscalNumeroInt);
    //                 $("#Colonia").val(data.ColoniaFiscal);
    //                 $("#Delegacion").val(data.AlcaldiaFiscal);
    //                 $("#Poblacion").val(data.EstadoFiscal);
    //                 $("#Estado").val(data.EstadoFiscal);
    //                 $("#Pais").val(data.PaisFiscal);
    //                 $("#CodigoPostal").val(data.CPFiscal);

    //             },
    //             error: function() {
    //                 const Msg = Swal.mixin({
    //                     toast: true,
    //                     position: "center",
    //                     icon: "warning",
    //                     showConfirmButton: false,
    //                     timer: 3000,
    //                 });

    //                 if (idCliente == null) {
    //                     titleMsg = "Id Nulo";
    //                 } else {
    //                     titleMsg = "Ocurrió un error";
    //                 }
    //                 Msg.fire({
    //                     title: titleMsg,
    //                 });
    //             },
    //         });
    //     } else {

    //         $('#mismaDireccion').val(0);

    //         $("#Direccion").val("");
    //         $("#DireccionNumero").val("");
    //         $("#DireccionNumeroInt").val("");
    //         $("#Colonia").val("");
    //         $("#Delegacion").val("");
    //         $("#Poblacion").val("");
    //         $("#Estado").val("");
    //         $("#Pais").val("");
    //         $("#CodigoPostal").val("");

    //     }
    // }


});

$(window).resize(function() {
    ajustarTablasSucs();
});

function ajustarTablasSucs() {
    var height = window.innerHeight;
    if (height >= 320 && height <= 651) {
        $("#tableSucursalesAll")
            .closest(".dataTables_scrollBody")
            .css("height", "65vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableSucursalesAll")
            .closest(".dataTables_scrollBody")
            .css("height", "68vh");
    } else if (height >= 769 && height <= 1024) {
        $("#tableSucursalesAll")
            .closest(".dataTables_scrollBody")
            .css("height", "77vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableSucursalesAll")
            .closest(".dataTables_scrollBody")
            .css("height", "80vh");
    } else if (height >= 1440) {
        $("#tableSucursalesAll")
            .closest(".dataTables_scrollBody")
            .css("height", "82vh");
    }
}

// ============================================================= FUNCIONES JCG ==========================================

$("#modal-create-sucursalCte").on("hidden.bs.modal", function() {
    $(this).find("form").trigger("reset");
    // $("#principal_check").prop("disabled", true);
    // $("#mismaDireccion_check").prop("disabled", true);
    $("#idClienteINTSuc").val("");
    $("#idClienteINT").val("Requerido");
    $("#Direccion").val("");
    $("#DireccionNumero").val("");
    $("#DireccionNumeroInt").val("");
    $("#Colonia").val("");
    $("#Delegacion").val("");
    $("#Poblacion").val("");
    $("#Estado").val("");
    $("#Pais").val("");
    $("#CodigoPostal").val("");
});

$("#modal-create-sucursalCte").draggable({
    handle: ".titulo",
});

$("#modal-create-sucursalCte").on("shown.bs.modal", function() {
    $("#Nombre").trigger("focus");
});

var mensaje = "";

function agregarSucursalCte() {
    mensaje = "Sucursal Agregada";
    save_method = "add";
    $("imput[name=_method]").val("POST");
    $("#modal-create-sucursalCte form").trigger("reset");
    $("#modal-create-sucursalCte").modal("show");
    $(".titulo").text("Agregar Sucursal");
    document.getElementById("SelectCliente").style.display = "block";
    // document.getElementById("mismaDireccion_check_validate").style.display = "none";
    $("#idClienteINT").prop("required", true);

    var idClienteSelec2 = $('#idClienteINT').select2("data");
    id_Cliente_INT = idClienteSelec2[0].element.value;

    var idCliente_option = document.getElementById("idClienteINT").getElementsByTagName("option");

    // console.log(idClienteSelec2);

    idClienteSelec2[0].selected = true;
    // idCliente_option[0].value("Requerido");

    $("#idClienteINT").val("Requerido");
    // idClienteSelec2.value = "Requerido";

    $("#Direccion").prop("readonly", false);
    $("#DireccionNumero").prop("readonly", false);
    $("#DireccionNumeroInt").prop("readonly", false);
    // $("#Colonia").prop("readonly", false);
    // $("#Delegacion").prop("readonly", false);
    // $("#Poblacion").prop("readonly", false);
    // $("#Estado").prop("readonly", false);
    // $("#Pais").prop("readonly", false);
    // $("#CodigoPostal").prop("readonly", false);
}

function editarSucursalCliente(id, idCliente) {
    mensaje = "Sucursal Actualizada";
    save_method = "edit";
    $("imput[name=_method]").val("PATCH");
    $.ajax({
        url: serve + "sucursales" + "/" + id + "/edit",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            $("#modal-create-sucursalCte form").trigger("reset");

            // $("#principal_check").prop("disabled", true);
            // $("#mismaDireccion_check").prop("disabled", true);
            // document.getElementById("mismaDireccion_check_validate").style.display = "none";
            $("#idClienteINTSuc").val("");
            $("#idClienteINT").val("");
            $("#Direccion").val("");
            $("#DireccionNumero").val("");
            $("#DireccionNumeroInt").val("");
            $("#Colonia").val("");
            $("#Delegacion").val("");
            $("#Poblacion").val("");
            $("#Estado").val("");
            $("#Pais").val("");
            $("#CodigoPostal").val("");

            if (data.idClienteINT != null) {
                document.getElementById("SelectCliente").style.display = "none";
                $("#idClienteINT").prop("required", false);
                $("#idClienteINT").val("Requerido");
                // $("#principal_check").prop("disabled", false);
                // $("#mismaDireccion_check").prop("disabled", false);
            }

            // if (data.mismaDireccion == 1) {
            //     $("#mismaDireccion_check").prop("disabled", true);
            //     $("#mismaDireccion_check").prop("checked", true);
            //     $("#Direccion").prop("readonly", true);
            //     $("#DireccionNumero").prop("readonly", true);
            //     $("#DireccionNumeroInt").prop("readonly", true);
            //     $("#Colonia").prop("readonly", true);
            //     $("#Delegacion").prop("readonly", true);
            //     $("#Poblacion").prop("readonly", true);
            //     $("#Estado").prop("readonly", true);
            //     $("#Pais").prop("readonly", true);
            //     $("#CodigoPostal").prop("readonly", true);
            //     $("#CodigoPostalListaSuc").prop("readonly", true);
            //     $("#mismaDireccion_check_validate").val('Esta es la sucursal con la dirección fiscal');
            // } else {
            //     $("#Direccion").prop("readonly", false);
            //     $("#DireccionNumero").prop("readonly", false);
            //     $("#DireccionNumeroInt").prop("readonly", false);
            //     $("#CodigoPostalListaSuc").prop("readonly", false);
            //     // $("#Colonia").prop("readonly", false);
            //     // $("#Delegacion").prop("readonly", false);
            //     // $("#Poblacion").prop("readonly", false);
            //     // $("#Estado").prop("readonly", false);
            //     // $("#Pais").prop("readonly", false);
            //     // $("#CodigoPostal").prop("readonly", false);
            // }

            // $.ajax({
            //     url: serve + 'getDireccionFiscal/' + data.idClienteINT,
            //     type: 'GET',
            //     dataType: 'JSON',
            //     success: function(r) {
            //         $("#mismaDireccion_check").prop("disabled", true);
            //         document.getElementById("mismaDireccion_check_validate").style.display = "block";
            //     }
            // });

            $("#idClienteINTSuc").val(data.idClienteINT);
            $("#idSucursal").val(data.idSucursal);
            $("#idSucursalINT").val(data.idSucursalINT);
            $("#Nombre").val(data.Nombre);
            $("#Encargado").val(data.Encargado);
            $("#eMail1").val(data.eMail1);
            // $("#mismaDireccion").val(data.mismaDireccion);
            $("#Direccion").val(data.Direccion);
            $("#DireccionNumero").val(data.DireccionNumero);
            $("#DireccionNumeroInt").val(data.DireccionNumeroInt);
            $("#Colonia").val(data.Colonia);
            $("#Delegacion").val(data.Delegacion);
            $("#Poblacion").val(data.Poblacion);
            $("#Estado").val(data.Estado);
            $("#Pais").val(data.Pais);
            $("#CodigoPostal").val(data.CodigoPostal);
            $("#TelefonosSuc").val(data.Telefonos);
            $("#modal-create-sucursalCte").modal("show");
            $(".titulo").text("Editar Sucursal");
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

function eliminarSucursal(id, id_usuario) {
    mensaje = "Sucursal Actualizada";
    Swal.fire({
        title: "Sucursales",
        text: "Activar/Desactivar Sucursal Seleccionada?",
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
                url: serve + "darDeBajaSucursal",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    id_usuario: id_usuario,
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
                    tableSucs.ajax.reload();
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

function sucPrincipal(id, id_usuario) {
    mensaje = "Sucursal Actualizada";
    Swal.fire({
        title: "Sucursales",
        text: "Activar/Desactivar principal de la Sucursal Seleccionada?",
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
                url: serve + "sucPrincipal",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    id_usuario: id_usuario,
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
                    tableSucs.ajax.reload();
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

$(function() {
    $("#modal-create-sucursalCte form")
        .validator()
        .on("submit", function(e) {
            if (!e.isDefaultPrevented()) {
                var id = $("#idSucursal").val();
                if (save_method == "add") {
                    url = serve + "sucursales";
                    method = "POST";
                } else {
                    url = serve + "sucursales/" + id;
                    method = "PUT";
                }
                $.ajax({
                    url: url,
                    method: method,
                    dataType: "json",
                    data: $("#modal-create-sucursalCte form").serialize(),
                    success: function() {
                        $("#modal-create-sucursalCte form").trigger("reset");
                        $("#modal-create-sucursalCte").modal("hide");
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
                        tableSucs.ajax.reload();
                    },
                    error: function() {
                        $("#modal-create-sucursalCte form").trigger("reset");
                        $("#modal-create-sucursalCte").modal("hide");
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
                        tableSucs.ajax.reload();
                    },
                });
                return false;
            }
        });
});

function soloAlfanumericos(event) {
    var regex = new RegExp("^[a-zA-Z0-9 ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}