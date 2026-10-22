serve = base_path;
rutaActual = document.location.href;

$(document).ajaxStart(function() {
    loaderON();
});

$(document).ajaxStop(function() {
    loaderOut();
});

$(document).ready(function() {

    rol_p = $('#idRol').val();

    if (rol_p == 1 || rol_p == 5 || rol_p == 7 || rol_p == 8) {
        mostrar = true;
    } else {
        mostrar = false;
    }

    var detector = new MobileDetect(window.navigator.userAgent);

    if (detector.mobile() == null) {
        visible = true;
        extendido = "colvis";
        titulo_extendido = "Mostrar / Ocultar Columnas";
        dom_tabla = "lpB";
    } else {
        visible = false;
        extendido = undefined;
        titulo_extendido = "";
        dom_tabla = "lp";
    }

    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";
    if (rutaActual.indexOf('edit') > -1) {
        tblOportunidades(0);
    }


    $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'auto',
        delay: { "show": 0, "hide": 0 },
        trigger: 'hover',
        show: true
    });

    dataTableProspectos = $("#tableProspectos").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "NumeroProspecto",
        select: false,
        scrollY: false,
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        order: [
            0, "desc"
        ],
        language: {
            url: serve + "js/spanish-DT.json"
        },
        ajax: {
            url: serve + "api/prospectos",
            type: "GET",
            dataType: "json"
        },
        columns: [{
                data: "NumeroProspecto",
                name: "NumeroProspecto",
                title: "Numero"
            },
            {
                data: "Activo",
                render: function(data, type, row) {
                    if (row.EsCliente == 1) {
                        $disabled = 'disabled';
                        $onclick = '';
                    } else {
                        $disabled = '';
                        $onclick = 'onclick="eliminarProspecto(' + row.idProspecto + ', ' + data + ')"';
                    }

                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0) {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                            } else {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                            }
                        } else {
                            if (data == 0) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if ($.trim(row.inputLogin) == $.trim(row.Login)) {
                            if (data == 0) {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                            } else {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                            }
                        } else {
                            if (data == 0) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    }
                },
                name: "Activo",
                title: "Activo"
            },
            {
                data: "Oportunidades",
                render: function(data, type, row) {
                    if (row.EsCliente == 1) {
                        $disabled = 'disabled';
                        $onclick = '';
                    } else {
                        $disabled = '';
                        $onclick = 'onclick="asignaOportunidades(' + row.idProspecto + ', ' + data + ')"';
                    }

                    if (row.Activo == 1) {
                        if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if (data == 0) {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                                } else {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                                }
                            } else {
                                if (data == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        } else {
                            if ($.trim(row.inputLogin) == $.trim(row.Login)) {
                                if (data == 0) {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                                } else {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked ' + $disabled + '><label ' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                                }
                            } else {
                                if (data == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        }
                    } else {
                        if (data == 0) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                    }
                },
                name: "Oportunidades",
                title: "Oportunidades"
            },
            {
                data: "EsCliente",
                render: function(data, type, row) {
                    if (row.EsCliente == 1) {
                        $disabled = 'disabled';
                        $onclick = '';
                    } else {
                        $disabled = '';
                        $onclick = 'onclick="convertirCliente(' + row.idProspecto + ', ' + data + ')"';
                    }

                    if (row.Activo == 1) {
                        if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if (data == 0) {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" ' + $disabled + '><label ' + $onclick + ' id="chkCliente" class="custom-control-label" style="cursor: pointer"></label></div>');
                                } else {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked ' + $disabled + '><label ' + $onclick + ' class="custom-control-label"></label></div>');
                                }
                            } else {
                                if (data == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        } else {
                            if ($.trim(row.inputLogin) == $.trim(row.Login)) {
                                if (data == 0) {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" ' + $disabled + '><label ' + $onclick + ' id="chkCliente" class="custom-control-label" style="cursor: pointer"></label></div>');
                                } else {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked ' + $disabled + '><label ' + $onclick + ' class="custom-control-label"></label></div>');
                                }
                            } else {
                                if (data == 0) {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        }
                    } else {
                        if (data == 0) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                    }
                },
                name: "EsCliente",
                title: "Cliente",
            },
            {
                data: "porcentaje",
                render: function(data) {
                    if (data == null) {
                        return '<strong>0.00%</strong>';
                    } else {
                        return '<strong>' + parseFloat(data).toFixed(2) + ' %' + '</strong>';
                    }
                },
                name: "porcentaje",
                title: "Validación"
            },
            {
                data: "razonsocial",
                render: function(data, type, row) {
                    if (row.EsCliente == 1) {
                        $href = ' href="clientes/' + row.ClienteINT + '/edit"'
                        $btn_editar_class = 'btn_editar_cliente';
                        $title = ' data-bs-toggle="tooltip" title="Editar Cliente"';
                    } else {
                        $href = ' href="prospectos/' + row.idProspecto + '/edit"'
                        $btn_editar_class = 'btn_editar_prospecto';
                        $title = ' data-bs-toggle="tooltip" title="Editar Prospecto"';
                    }

                    if (row.inputRol == 1 || row.inputRol == 2 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8 || row.inputRol == 11 || row.inputRol == 1015 || row.inputRol == 1012 || row.inputRol == 1011 || row.inputRol == 1010 || row.inputRol == 1017) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return ('<a' + $href + ' onclick="loaderON()"' + $title + ' >' + data + "</a>");
                        } else {
                            return data;
                        }
                    } else {
                        if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) || $.trim(row.LoginAuxiliar2) == $.trim(row.inputLogin) || $.trim(row.LoginAuxiliar3) == $.trim(row.inputLogin) || $.trim(row.LoginAuxiliar4) == $.trim(row.inputLogin || $.trim(row.LoginAgente) == $.trim(row.inputLogin))) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                return ('<a' + $href + '  onclick="loaderON()"' + $title + ' >' + data + "</a>");
                            } else {
                                return data;
                            }
                        } else {
                            return data;
                        }
                    }
                },
                name: "razonsocial",
                title: "Razon Social",
                className: "text-uppercase",
            },
            {
                data: "LITONombreComercial",
                name: "LITONombreComercial",
                title: "Nombre",
                className: "text-uppercase",
            }, {
                data: "Giro",
                name: "Giro",
                title: "Giro"
            }, {
                data: "SubGiro",
                name: "SubGiro",
                title: "SubGiro"
            }, {
                data: "NombreAgente",
                width: 500,
                title: "Agente"
            }, {
                data: "Telefonos",
                name: "Telefonos",
                title: "Telefono 1"
            }, {
                data: "Telefonos2",
                name: "Telefonos2",
                title: "Telefono 2"
            }, {
                data: "fecAlta",
                name: "fecAlta",
                title: "Fecha Alta"
            },
        ],
        pageLength: 50,
        columnDefs: [{
                className: "dt-center",
                targets: [0, 1, 2, 3, 4]
            },
            {
                targets: [1, 2, 3, 4],
                visible: mostrar,
            },
            {
                targets: [
                    6,
                    7,
                    8,
                    9,
                    11,
                    12,
                ],
                visible: visible
            }, {
                targets: [12],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)
            }, {
                targets: 9,
                render: function(data, type, row, meta) {

                    if (row.inputRol == 7 || row.inputRol == 1 || row.inputRol == 8) {
                        var $select = $('<select id="agentes_grid_prospecto_' + row.idProspecto + '" class="form-control text-uppercase" style="width: 230px; height: 25px !important; padding-top: 2px"><option selected disabled>SIN ASIGNAR</option></select>');
                        $.each(agente, function(k, v) {
                            var $option = $("<option></option>", {
                                text: v,
                                value: k
                            });
                            if (data == v) {
                                $option.attr("selected", "selected");
                            }
                            $select.append($option);
                        });
                        return $select.prop("outerHTML");
                    } else {
                        if (data == null || data == "") {
                            return '<span>SIN ASIGNAR</span>';
                        } else {
                            return '<span>' + data + '</span>';
                        }
                    }
                }
            },
        ],
        lengthMenu: [
            [
                50,
                100,
                150,
                200, -1
            ],
            [
                50,
                100,
                150,
                200,
                'TODOS'
            ],
        ],
        // autoWidth: true,
        // fixedColumns: {
        //     leftColumns: 0,
        // },
        dom: '<"top"R>rt<"bottom"' + dom_tabla + '><"clear">',
        buttons: [{
            extend: extendido,
            // columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            text: '<i class="fas fa-tasks" title=' + titulo_extendido + '></i>',
            collectionLayout: "fixed two-column"
        }, {
            extend: "excelHtml5",
            title: "Prospectos | CANDY CRM",
            text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
            exportOptions: {
                orthogonal: "export",
                columns: "thead th:not(.noExport)",
                modifer: {
                    serverSide: false,
                    page: "all"
                }
            },
            filename: "Prospectos",
            autoFilter: true,
            sheetName: "Lista Prospectos",
            messageTop: null,
            body: true,
            footer: false
        }, ],
        "drawCallback": function(settings) {
            $('[data-bs-toggle="tooltip"]').tooltip({
                placement: 'auto',
                delay: { "show": 0, "hide": 0 },
                trigger: 'hover',
                show: true
            });
        },
        initComplete: function(settings, json) {
            loaderOut();
        }
    });


    selectRow("tableProspectos");

    $("#tableProspectos tbody").on("change", "select", function() {
        agente = $(this).find(":selected").val();
        data = dataTableProspectos.row($(this).parents("tr")).data();
        id = data["idProspecto"];
        nombre_agente = data["NombreAgente"];
        cambiar_agente(id, agente, nombre_agente);
    });

    $("#tableProspectos").DataTable().on("draw", function() {
        ajustarTablas();
    });

});

$(window).resize(function() {
    var dataTableProspectos = $("#tableProspectos").DataTable();
    var height = window.innerHeight;
    if (height >= 320 && height <= 651) {
        $("#tableProspectos").closest(".dataTables_scrollBody").css("height", "59vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableProspectos").closest(".dataTables_scrollBody").css("height", "59vh");
    } else if (height >= 769 && height <= 1024) {
        $("#tableProspectos").closest(".dataTables_scrollBody").css("height", "77vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableProspectos").closest(".dataTables_scrollBody").css("height", "80vh");
    } else if (height >= 1440) {
        $("#tableProspectos").closest(".dataTables_scrollBody").css("height", "82vh");
    }
    dataTableProspectos.draw();
});

var mensaje = "";
var icono = "";

$(function() {
    $("#modal_create_prospecto form").validator().on("submit", function(e) {
        if (!e.isDefaultPrevented()) {
            loaderON();
            mensaje = "Prospecto Creado";
            icono = "success";
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
            loaderOut();
        }
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: icono,
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire({ title: mensaje });
    });

    $("#formProspectoEdit form").validator().on("submit", function(e) {
        if (!e.isDefaultPrevented()) {
            loaderON();
            mensaje = "Prospecto Actualizado";
            icono = "success";
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
            loaderOut()
        }
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: icono,
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire({ title: mensaje });
    });

    // // --ING. Brigette Gtz--//
    // filtros();
    // var countFilter = 1;
    // // var tblClientes = $('#tableClientes').DataTable();
    // $(".btn-refresh").click(function () {
    //     clearFilters();
    // }); // Limpiar tabla
    // $("#boton").click(function () {
    //     $("#parrafo").next("p").hide(3000);
    // });
    $("#searchGral").keypress(function(e) {
        if (e.which == 13) { // loaderON();
            var texto = $(this).val();
            filterGlobal(texto);
        }
    });
    $("#btn-search").click(function(e) { // loaderON();
        var texto = $("#searchGral").val();
        filterGlobal(texto);
    });

    function filterGlobal(texto) {
        $("#tableProspectos").DataTable().search("").columns().search("").draw();
        $("#tableProspectos").DataTable().search(texto, false, true).draw();
    }
});

// ================================================ Funciones JCG =====================================

CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

function cambiar_agente(id, agente, nombre_agente) {
    mensaje = "Cliente Actualizado";
    id_suario = document.getElementById("idUsuario");
    Swal.fire({
        title: "Prospectos",
        text: "Actualizar Agente del Prospecto Seleccionado?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "cambiarAgenteProspecto",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    agente: agente,
                    id_suario: id_suario.value,
                    _token: CSRF_TOKEN
                },
                success: function(data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    window.location.href = "prospectos";
                    // dataTableClientes.ajax.reload();
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error" });
                }
            });
        } else {
            $("#agentes_grid_prospecto_" + id).find("option").attr("selected", false);
            $("#agentes_grid_prospecto_" + id + '  option:contains("' + nombre_agente + '")').attr("selected", true);
            window.location.href = "prospectos";
            // dataTableClientes.ajax.reload();
        }
    });
}

function openModal() {
    let modal = document.querySelector("#modal_create_prospecto");
    modal.classList.add("showModal");

    document.getElementById('respuestas23-8').style.display = 'none'
    document.getElementById('respuestas24-8').style.display = 'none'
    document.getElementById('respuestas25-8').style.display = 'none'
    document.getElementById('respuestas26-8').style.display = 'none'
    document.getElementById('respuestas27-8').style.display = 'none'
    document.getElementById('respuesta29').style.display = 'none'

    // problemasProvedorActualSiRespuestas.style.display = 'none'
    // proveedorTrabajaSiRespuestas.style.display = 'none'
}

function eliminarProspecto(id, activo) {

    if (activo == 0) {
        text = "Activar Prospecto Seleccionado?";
        mensaje = "Prospecto Activado";
    } else {
        text = "Desactivar Prospecto Seleccionado?";
        mensaje = "Prospecto Desactivado";
    }

    Swal.fire({
        title: "Prospectos",
        text: text,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "No",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "darDeBajaProspecto",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    activo: activo,
                    _token: CSRF_TOKEN
                },
                success: function(data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    dataTableProspectos.ajax.reload();
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error" });
                }
            });
        }
    });
}

function asignaOportunidades(id, oportunidades) {

    if (oportunidades == 0) {
        text = "Este Prospecto Podrá Asignar Oportunidades?";
        mensaje = "Prospecto Actualizado";
        boton_si = 'SI';
        boton_no = 'NO';
    } else {
        text = "Este Prospecto Ya No Podrá Asignar Oportunidades?";
        mensaje = "Prospecto Actualizado";
        boton_si = 'OK';
        boton_no = 'CANCELAR';
    }

    Swal.fire({
        title: "Prospectos",
        text: text,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: boton_si,
        cancelButtonText: boton_no,
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "asignaOportunidades",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    oportunidades: oportunidades,
                    _token: CSRF_TOKEN
                },
                success: function(data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    dataTableProspectos.ajax.reload();
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error" });
                }
            });
        }
    });
}

function convertirCliente(id, escliente) {

    $('#tableProspectos tbody').on('click', '#chkCliente', function() {
        var data = dataTableProspectos.row($(this).parents("tr")).data();
        // NombreContactoCXP_ = data.NombreContactoCXP;
        // Telefonos1ContactoCXP_ = data.Telefonos1ContactoCXP;
        // eMailContactoCXP_ = data.eMailContactoCXP;
        // NombreContactoCFDI_ = data.NombreContactoCFDI;
        // Telefonos1ContactoCFDI_ = data.Telefonos1ContactoCFDI;
        // eMailContactoCFDI_ = data.eMailContactoCFDI;

        // if (NombreContactoCXP_ == "") {
        //     Swal.fire('Falta Capturar el Nombre de Contacto CXP');
        // } else if (Telefonos1ContactoCXP_ == "") {
        //     Swal.fire('Falta Capturar el Teléfono de Contacto CXP');
        // } else if (eMailContactoCXP_ == "") {
        //     Swal.fire('Falta Capturar el Correo de Contacto CXP');
        // } else if (NombreContactoCFDI_ == "") {
        //     Swal.fire('Falta Capturar el Nombre de Contacto CFDI');
        // } else if (Telefonos1ContactoCFDI_ == "") {
        //     Swal.fire('Falta Capturar el Teléfono de Contacto CFDI');
        // } else if (eMailContactoCFDI_ == "") {
        //     Swal.fire('Falta Capturar el Correo de Contacto CFDI');
        // } else {
        // }

        if (escliente == 0) {
            text = "Convertir el Prospecto en Cliente?";
            mensaje = "Prospecto Actualizado";
        } else {
            text = "Regresar el Cliente a Prospecto?";
            mensaje = "Prospecto Actualizado";
        }

        boton_si = 'SI';
        boton_no = 'NO';

        Swal.fire({
            title: "Prospectos",
            text: text,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
            confirmButtonText: boton_si,
            cancelButtonText: boton_no,
            allowEnterKey: false,
            stopKeydownPropagation: true,
            reverseButtons: true,
            returnFocus: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: serve + "convertirCliente",
                    type: "PATCH",
                    data: {
                        _method: "PATCH",
                        id: id,
                        escliente: escliente,
                        _token: CSRF_TOKEN
                    },
                    success: function(data) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire({ title: mensaje });
                        dataTableProspectos.ajax.reload();
                    },
                    error: function() {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "warning",
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire({ title: "Ocurrió un error" });
                    }
                });
            }
        });
    });

}

function closeM() {
    let m = document.querySelector("#modal_create_prospecto");
    m.classList.remove("showModal");
}

function closeMEdit() {
    let m = document.querySelector("#modal-edit-cliente");
    m.classList.remove("showModal");
}

$(document).ready(function() {
    if (document.getElementById('28-8').checked == true) {
        document.getElementById('respuestas23-8').style.display = 'block';
        document.getElementById('respuestas24-8').style.display = 'block';
        document.getElementById('respuestas25-8').style.display = 'block';
        document.getElementById('respuestas26-8').style.display = 'block';
        document.getElementById('respuestas27-8').style.display = 'block';

        document.getElementById('23-8').required = true;
        document.getElementById('24-8').required = true;
        document.getElementById('25-8').required = true;
        document.getElementById('26-8').required = true;
        document.getElementById('27-8').required = true;

    } else {
        document.getElementById('23-8').required = false;
        document.getElementById('24-8').required = false;
        document.getElementById('25-8').required = false;
        document.getElementById('26-8').required = false;
        document.getElementById('27-8').required = false;

    }

    if (document.getElementById('30-9').checked == true) {
        document.getElementById('respuesta29').style.display = 'block';
        document.getElementById('29-9').required = true;
    } else {
        document.getElementById('29-9').required = false;
    }
});

document.addEventListener("keypress", (e) => {
    if (e.keyCode == 13) {
        e.preventDefault();
    }
});

$28 = document.getElementById('28-8');
$49 = document.getElementById('49-8');

$28.addEventListener("change", function() {
    document.getElementById('respuestas23-8').style.display = 'block';
    document.getElementById('respuestas24-8').style.display = 'block';
    document.getElementById('respuestas25-8').style.display = 'block';
    document.getElementById('respuestas26-8').style.display = 'block';
    document.getElementById('respuestas27-8').style.display = 'block';
    $('#23-8').focus();

    document.getElementById('23-8').required = true;
    document.getElementById('24-8').required = true;
    document.getElementById('25-8').required = true;
    document.getElementById('26-8').required = true;
    document.getElementById('27-8').required = true;
});

$49.addEventListener("change", function() {
    document.getElementById('respuestas23-8').style.display = 'none';
    document.getElementById('respuestas24-8').style.display = 'none';
    document.getElementById('respuestas25-8').style.display = 'none';
    document.getElementById('respuestas26-8').style.display = 'none';
    document.getElementById('respuestas27-8').style.display = 'none';
    $('#23-8')[0].checked = false;
    $('#24-8')[0].checked = false;
    $('#25-8')[0].checked = false;
    $('#26-8')[0].checked = false;
    $('#27-8')[0].checked = false;

    document.getElementById('23-8').required = false;
    document.getElementById('24-8').required = false;
    document.getElementById('25-8').required = false;
    document.getElementById('26-8').required = false;
    document.getElementById('27-8').required = false;
});

$30 = document.getElementById('30-9');
$48 = document.getElementById('48-9');
$29 = document.getElementById('29-9');

$30.addEventListener("change", function() {
    document.getElementById('respuesta29').style.display = 'block';
    document.getElementById('29-9').required = true;
    $('#29-9').focus();
});

$48.addEventListener("change", function() {
    document.getElementById('respuesta29').style.display = 'none';
    document.getElementById('29-9').required = false;
    $('#29-9').val('');
});

function mostrarGeneralP() {
    $("#generalP").addClass("active");
    $("#generalP").addClass("show");

    $("#oportunidadesP").removeClass("active");
    $("#oportunidadesP").removeClass("show");

    $("#documentosP").removeClass("active");
    $("#documentosP").removeClass("show");

    $("#datos_adminP").removeClass("active");
    $("#datos_adminP").removeClass("show");

    document.getElementById("generalP").style.display = "block";
    document.getElementById("oportunidadesP").style.display = "none";
    document.getElementById("documentosP").style.display = "none";
    document.getElementById("datos_adminP").style.display = "none";
    document.getElementById("sbtnSave").style.display = "block";
    $("#sbtnBack").removeClass("col-8");
    $("#sbtnBack").addClass("col-4");

    tableOportunidadesCliente.draw();
    ajustarTablas();
}

function mostrarOportunidadesP() {

    $("#generalP").removeClass("active");
    $("#generalP").removeClass("show");

    $("#oportunidadesP").addClass("active");
    $("#oportunidadesP").addClass("show");

    $("#documentosP").removeClass("active");
    $("#documentosP").removeClass("show");

    $("#datos_adminP").removeClass("active");
    $("#datos_adminP").removeClass("show");

    document.getElementById("generalP").style.display = "none";
    document.getElementById("oportunidadesP").style.display = "block";
    document.getElementById("documentosP").style.display = "none";
    document.getElementById("datos_adminP").style.display = "none";
    document.getElementById("sbtnSave").style.display = "none";
    $("#sbtnBack").addClass("col-8");

    var height = window.innerHeight;

    if (height >= 320 && height <= 651) {
        $("#tableOportunidadesCliente").closest(".dataTables_scrollBody").css("height", "58vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableOportunidadesCliente").closest(".dataTables_scrollBody").css("height", "61vh");
    } else if (height >= 769 && height <= 992) {
        $("#tableOportunidadesCliente").closest(".dataTables_scrollBody").css("height", "70vh");
    } else if (height >= 993 && height <= 1024) {
        $("#tableOportunidadesCliente").closest(".dataTables_scrollBody").css("height", "71vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableOportunidadesCliente").closest(".dataTables_scrollBody").css("height", "71vh");
    } else {
        $("#tableOportunidadesCliente").closest(".dataTables_scrollBody").css("height", "73vh");
    }
    tableOportunidadesCliente.draw();
    ajustarTablas();
}

function mostrarDocumentosP() {
    tblDocs();
    $("#generalP").removeClass("active");
    $("#generalP").removeClass("show");

    $("#oportunidadesP").removeClass("active");
    $("#oportunidadesP").removeClass("show");

    $("#documentosP").addClass("active");
    $("#documentosP").addClass("show");

    $("#datos_adminP").removeClass("active");
    $("#datos_adminP").removeClass("show");

    document.getElementById("generalP").style.display = "none";
    document.getElementById("oportunidadesP").style.display = "none";
    document.getElementById("documentosP").style.display = "block";
    document.getElementById("datos_adminP").style.display = "none";
    document.getElementById("sbtnSave").style.display = "none";
    $("#sbtnBack").addClass("col-8");

}

function mostrarDatosAdminP() {

    $("#generalP").removeClass("active");
    $("#generalP").removeClass("show");

    $("#oportunidadesP").removeClass("active");
    $("#oportunidadesP").removeClass("show");

    $("#documentosP").removeClass("active");
    $("#documentosP").removeClass("show");

    $("#datos_adminP").addClass("active");
    $("#datos_adminP").addClass("show");

    document.getElementById("generalP").style.display = "none";
    document.getElementById("oportunidadesP").style.display = "none";
    document.getElementById("documentosP").style.display = "none";
    document.getElementById("datos_adminP").style.display = "block";
    document.getElementById("sbtnSave").style.display = "block";
    $("#sbtnBack").removeClass("col-8");
    $("#sbtnBack").addClass("col-4");

}

function validarInput(input) {
    var rfc = input.value.trim().toUpperCase(),
        resultado = document.getElementById("resultado"),
        valido;

    var rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba

    if (rfcCorrecto) {
        valido = "Válido";
        document.getElementById("RFC").classList.remove("is-invalid");
        document.getElementById("RFC").classList.add("is-valid");
    } else {
        valido = "No válido";

        Swal.fire({ // icon: 'error',
            title: "Error",
            text: "RFC NO VALIDO",
            allowEnterKey: false
        }).then(() => {
            $("#RFC").val('');
            document.getElementById("RFC").focus();
            document.getElementById("RFC").classList.remove("is-valid");
            document.getElementById("RFC").classList.add("is-invalid");
        });
    }
}

function rfcValido(rfc, aceptarGenerico = true) {
    const re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
    var validado = rfc.match(re);

    if (!validado)
    // Coincide con el formato general del regex?
        return false;



    // Separar el dígito verificador del resto del RFC
    const digitoVerificador = validado.pop(),
        rfcSinDigito = validado.slice(1).join(""),
        len = rfcSinDigito.length,
        // Obtener el digito esperado
        diccionario = "0123456789ABCDEFGHIJKLMN&OPQRSTUVWXYZ Ñ",
        indice = len + 1;
    var suma,
        digitoEsperado;

    if (len == 12)
        suma = 0;
    else
        suma = 481;

    // Ajuste para persona moral

    for (var i = 0; i < len; i++)
        suma += diccionario.indexOf(rfcSinDigito.charAt(i)) * (indice - i);


    digitoEsperado = 11 - (suma % 11);
    if (digitoEsperado == 11)
        digitoEsperado = 0;
    else if (digitoEsperado == 10)
        digitoEsperado = "A";



    // El dígito verificador coincide con el esperado?
    // o es un RFC Genérico (ventas a público general)?
    if (digitoVerificador != digitoEsperado && (!aceptarGenerico || rfcSinDigito + digitoVerificador != "XAXX010101000"))
        return false;
    else if (!aceptarGenerico && rfcSinDigito + digitoVerificador == "XEXX010101000")
        return false;


    return rfcSinDigito + digitoVerificador;
}