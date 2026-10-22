serve = base_path;
rutaActual = document.location.href;

$(document).ajaxStart(function () {
    loaderON();
});

$(document).ajaxStop(function () {
    loaderOut();
});

$(document).ready(function () {
    var idrol = $('#idRol').val();
    var detector = new MobileDetect(window.navigator.userAgent);

    if (idrol == 1 || idrol == 8) {
        esPrspecto = true;
    } else {
        esPrspecto = false;
    }

    if (detector.mobile() == null) {
        visible = true;
        extendido = "colvis";
        titulo_extendido = "Mostrar / Ocultar Columnas";
        dom_tabla = "lpB";
        // if (idrol == 1010) {
        //     contactos_li.style.display = 'block';            
        // } else {
        //     contactos_li.style.display = 'block';
        //     sucursales_li.style.display = 'block';
        //     requisitos_li.style.display = 'block';
        // }
    } else {
        visible = false;
        extendido = undefined;
        titulo_extendido = "";
        dom_tabla = "lp";
        // if (idrol == 1010) {
        //     contactos_li.style.display = 'none';
        // } else {
        //     contactos_li.style.display = 'none';
        //     sucursales_li.style.display = 'none';
        //     requisitos_li.style.display = 'none';
        // }
    }

    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";
    if (rutaActual.indexOf('edit') > -1) {
        tblOportunidades(1);
    }


    $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'auto',
        delay: { "show": 0, "hide": 0 },
        fallbackPlacement: 'flip',
        boundary: 'scrollParent',
        trigger: 'hover',
    });
    // $("#nvoCliente").tooltip({ placement: "top" });
    // $("#btn-filters").tooltip({ placement: "top" });

    dataTableClientes = $("#tableClientes").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        rowId: "ClienteINT",
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
            url: serve + "api/clientes",
            type: "GET",
            dataType: "json"
        },
        columns: [
            {
                data: "ClienteINT", // 0
                name: "ClienteINT",
                title: "Numero"
            },
            {
                data: "Posicion", // 1
                render: function (data, type, row) {
                    // console.log(toString(data).includes("CLIENTE"));
                    if (data === "CLIENTE INACTIVO" || data === "Cliente Sin Actividad" || data === "Cliente Sin Venta" || data === "Cliente sin Venta y con Cotizacion"
                    ) {
                        return (
                            '<span class="badge badge-info pl-2 pr-2 pt-1 pb-1" title="CLIENTE INACTIVO"><strong>CI</strong></span>'
                            // '<span class="rounded-circle pl-2 pr-2 pt-1 pb-1" title="CLIENTE INACTIVO" style="background-color: #938dff"><strong>CI</strong></span>'
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
                name: "Posicion",
                title: "Tipo"
            },
            {
                data: "TblComNum", // 0
                name: "TblComNum",
                title: "Tabla comisón",
                render: function (data, type, row) {
                    if (data === "T2") {
                        return (
                            '<span class="badge badge-success pl-2 pr-2 pt-1 pb-1" title="TABLA DE COMISIÓN 2"><strong>T2</strong></span>'
                        );
                    } else if (data === "T1") {
                        return (
                            '<span class="badge badge-warning pl-2 pr-2 pt-1 pb-1" title="TABLA DE COMISIÓN 1"><strong>T1</strong></span>'
                        );
                    }
                } 
            },
            {
                data: "EsProspecto", // 2
                render: function (data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 8) {
                        if (data == "SI") {
                            $checked = 'checked ';
                            $disabled = 'disabled ';
                            $title = ' data-bs-toggle="tooltip" title="Ya es Prospecto"';
                            $onclick = '';
                        } else {
                            $checked = '';
                            $disabled = '';
                            $title = ' data-bs-toggle="tooltip" title="Convertir en Prospecto"';
                            $onclick = 'onclick="convertirEnProspecto(' + row.idCliente + ')"';
                        }

                        return ('<div class="custom-switch"><input type="checkbox" id="EsProspectoChk-' + row.idCliente + '"' + $disabled + ' class="custom-control-input"' + $checked + '><label' + $title + ' for="EsProspectoChk-' + row.idCliente + '"' + $onclick + ' class="custom-control-label" style="cursor: pointer"></label></div>');
                    } else {
                        if (data == "SI") {
                            $checked = 'checked ';
                            $disabled = 'disabled ';
                            $title = ' title=""';
                            $onclick = '';
                        } else {
                            $checked = '';
                            $disabled = 'disabled ';
                            $title = ' title=""';
                            $onclick = '';
                        }
                        return ('<div class="custom-switch"><input type="checkbox" id="EsProspectoChk-' + row.idCliente + '"' + $disabled + ' class="custom-control-input"' + $checked + '><label' + $title + ' for="EsProspectoChk-' + row.idCliente + '"' + $onclick + ' class="custom-control-label"></label></div>');
                    }
                },
                name: "EsProspecto",
                title: "Prospecto",
                visible: false,
            },
            {
                data: "esTop", // 3
                render: function (data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0) {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="actualizarTop(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                            } else {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="actualizarTop(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                            }
                        } else {
                            if (data == 0) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data == 0) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                        // if ($.trim(row.inputLogin) == $.trim(row.Login)) {} else {
                        //     if (data == 0) {
                        //         return (
                        //             '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="actualizarTop(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                        //         );
                        //     } else {
                        //         return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="actualizarTop(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                        //     }
                        // }
                    }
                },
                name: "esTop",
                title: "Top",
                visible: false
            },
            {
                data: "puedeDescargarPresupuesto", // 3
                render: function (data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 10 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0 || data == null) {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="actualizarSolicitarPresupuesto(' + row.ClienteINT + ', true)" class="custom-control-label" style="cursor: pointer"></label></div>');
                            } else {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="actualizarSolicitarPresupuesto(' + row.ClienteINT + ', false)" class="custom-control-label" style="cursor: pointer"></label></div>');
                            }
                        } else {
                            if (data == 0 || data == null) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data == 0 || data == null) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                        // if ($.trim(row.inputLogin) == $.trim(row.Login)) {} else {
                        //     if (data == 0) {
                        //         return (
                        //             '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="actualizarTop(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                        //         );
                        //     } else {
                        //         return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="actualizarTop(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                        //     }
                        // }
                    }
                },
                name: "puedeDescargarPresupuesto",
                title: "Desc. Pres.",
                visible: (idrol == 1 || idrol == 10 || idrol == 8) ? true : false
            },

            {
                data: "conBuroC", // 4
                render: function (data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data == 0) {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="actualizarBuro(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                            } else {
                                return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="actualizarBuro(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                            }
                        } else {
                            if (data == 0) {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                            } else {
                                return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data == 0) {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                        } else {
                            return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                        // if ($.trim(row.inputLogin) == $.trim(row.Login)) {} else {
                        //     if (data == 0) {
                        //         return (
                        //             '<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="actualizarBuro(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                        //         );
                        //     } else {
                        //         return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="actualizarBuro(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                        //     }
                        // }
                    }
                },
                name: "conBuroC",
                title: "Buró"
            },
            {
                data: "Estatus", // 5
                render: function (data, type, row) {
                    if (row.EsProspecto == 'SI') {
                        if ($.trim(data) === "BAJA") {
                            return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>');
                        } else {
                            return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>');
                        }
                    } else {
                        if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                if ($.trim(data) === "BAJA") {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="eliminarCliente(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                                } else {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="eliminarCliente(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                                }
                            } else {
                                if ($.trim(data) === "BAJA") {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        } else {
                            if ($.trim(row.inputLogin) == $.trim(row.Login)) {
                                if ($.trim(data) === "BAJA") {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="eliminarCliente(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                                } else {
                                    return ('<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="eliminarCliente(' + row.ClienteINT + ')" class="custom-control-label" style="cursor: pointer"></label></div>');
                                }
                            } else {
                                if ($.trim(data) === "BAJA") {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>';
                                } else {
                                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                                }
                            }
                        }
                    }
                },
                name: "Estatus",
                title: "Estatus"
            }, {
                data: "Sucursales", // 6
                render: function (data, type, row) {
                    if (data == 0 || data == null) {
                        return '<div class="text-center"><i class="fas fa-times text-red"></i></div>';
                    } else {
                        return '<div class="text-center"><i class="fas fa-check text-green"></i></div>';
                    }
                },
                title: "Sucs"
            }, {
                data: "RFC", // 7
                name: "RFC",
                title: "RFC"
            }, {
                data: "razonsocial", // 8
                render: function (data, type, row) {
                    if (row.EsProspecto == 'SI') {
                        return data;
                    } else {
                        if (row.inputRol == 1 || row.inputRol == 2 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8 || row.inputRol == 11 || row.inputRol == 1015 || row.inputRol == 1012 || row.inputRol == 1011 || row.inputRol == 1010 || row.inputRol == 1017 ||
                        row.inputRol == 1013) {
                            if ($.trim(row.inputEditar) === "Editar") {
                                return ('<a href="clientes/' + row.ClienteINT + '/edit" onclick="loaderON()" data-bs-toggle="tooltip" title="Editar Cliente">' + data + "</a>");
                            } else {
                                return data;
                            }
                        } else {
                            if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) || $.trim(row.LoginAuxiliar2) == $.trim(row.inputLogin) || $.trim(row.LoginAuxiliar3) == $.trim(row.inputLogin) || $.trim(row.LoginAuxiliar4) == $.trim(row.inputLogin) || $.trim(row.idAgente2) == $.trim(row.idUsuario) || $.trim(row.idAgente3) == $.trim(row.idUsuario) || (row.Agente3 != null && $.trim(row.Agente3) == $.trim(row.agenteActualLogin))) {
                                if ($.trim(row.inputEditar) === "Editar") {
                                    return ('<a href="clientes/' + row.ClienteINT + '/edit" onclick="loaderON()" data-bs-toggle="tooltip" title="Editar Cliente">' + data + "</a>");
                                } else {
                                    return data;
                                }
                            } else {
                                return data;
                            }
                        }
                    }
                },
                name: "razonsocial",
                title: "Razon Social"
            }, {
                data: "LITONombreComercial", // 9
                name: "LITONombreComercial",
                title: "Nombre"
            }, {
                data: "Giro", // 10
                name: "Giro",
                title: "Giro"
            }, {
                data: "SubGiro", // 11
                name: "SubGiro",
                title: "SubGiro"
            }, {
                data: "Situacion", // 12
                name: "Situacion",
                title: "Situacion"
            }, {
                data: "NombreAgente", // 13
                width: 500,
                title: "Agente"
            }, {
                data: "Telefonos", // 14
                name: "Telefonos",
                title: "Telefono 1"
            }, {
                data: "Telefonos2", // 15
                name: "Telefonos2",
                title: "Telefono 2"
            }, {
                data: "fecAlta", // 16
                name: "fecAlta",
                title: "Fecha Alta"
            },
        ],
        pageLength: 50,
        columnDefs: [{
            className: "dt-center",
            targets: [0, 1, 2, 3, 4, 5]
        }, 
        {
            targets: [2,3,4,6,7,11,12,17],
            searchable: false,
        },
        // {
        //     targets: [
        //         1,
        //         2,
        //         3,
        //         4,
        //         5,
        //         7,
        //         8,
        //         9,
        //         10,
        //         11,
        //         13,
        //         14,
        //         15,
        //         16
        //     ],
        //     visible: visible
        // }, 
        {
            targets: [17],
            render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)
        }, {
            targets: -4,
            render: function (data, type, row, meta) {

                if (row.inputRol == 7 || row.inputRol == 1 || row.inputRol == 8) {
                    var $select = $('<select id="agentes_grid_' + row.ClienteINT + '" class="form-control text-uppercase" style="width: 230px; height: 25px !important; padding-top: 2px"><option selected disabled>SIN ASIGNAR</option></select>');
                    $.each(agente, function (k, v) {
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
        },],
        // bLengthChange: true,
        // bInfo: true,
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
            title: "Clientes | CANDY CRM",
            text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
            exportOptions: {
                orthogonal: "export",
                columns: "thead th:not(.noExport)",
                modifer: {
                    serverSide: false,
                    page: "all"
                }
            },
            filename: "Clientes",
            autoFilter: true,
            sheetName: "Lista Clientes",
            messageTop: null,
            body: true,
            footer: false
        },],
        "drawCallback": function (settings) {
            $('[data-bs-toggle="tooltip"]').tooltip({
                trigger: 'hover',
            });
        },
        initComplete: function (settings, json) {
            loaderOut();
        }
    });

    selectRow("tableClientes");

    $("#tableClientes tbody").on("change", "select", function () {
        agente = $(this).find(":selected").val();
        data = dataTableClientes.row($(this).parents("tr")).data();
        id = data["ClienteINT"];
        nombre_agente = data["NombreAgente"];
        cambiar_agente(id, agente, nombre_agente);
    });

    $("#tableClientes").DataTable().on("draw", function () {
        ajustarTablas();
    });
});

$(window).resize(function () {
    var dataTableClientes = $("#tableClientes").DataTable();
    var height = window.innerHeight;
    if (height >= 320 && height <= 651) {
        $("#tableClientes").closest(".dataTables_scrollBody").css("height", "59vh");
        $("#div_contactos_cliente_editar").css("height", "70vh");
        $("#div_contactos_cliente").css("height", "86vh");
        $("#tableRequisitos").closest(".dataTables_scrollBody").css("height", "65vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableClientes").closest(".dataTables_scrollBody").css("height", "59vh");
        $("#div_contactos_cliente_editar").css("height", "72vh");
        $("#div_contactos_cliente").css("height", "76vh");
        $("#tableRequisitos").closest(".dataTables_scrollBody").css("height", "59vh");
    } else if (height >= 769 && height <= 1024) {
        $("#tableClientes").closest(".dataTables_scrollBody").css("height", "77vh");
        $("#div_contactos_cliente_editar").css("height", "79vh");
        $("#div_contactos_cliente").css("height", "90vh");
        $("#tableRequisitos").closest(".dataTables_scrollBody").css("height", "77vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableClientes").closest(".dataTables_scrollBody").css("height", "80vh");
        $("#div_contactos_cliente_editar").css("height", "82vh");
        $("#div_contactos_cliente").css("height", "92vh");
        $("#tableRequisitos").closest(".dataTables_scrollBody").css("height", "80vh");
    } else if (height >= 1440) {
        $("#tableClientes").closest(".dataTables_scrollBody").css("height", "82vh");
        $("#div_contactos_cliente_editar").css("height", "84vh");
        $("#div_contactos_cliente").css("height", "94vh");
        $("#tableRequisitos").closest(".dataTables_scrollBody").css("height", "82vh");
    }
    dataTableClientes.draw();
});

function mostrarGeneral() {
    $("#general").addClass("active");
    $("#general").addClass("show");

    $("#contactos").removeClass("active");
    $("#contactos").removeClass("show");

    $("#sucursales").removeClass("active");
    $("#sucursales").removeClass("show");

    $("#oportunidades").removeClass("active");
    $("#oportunidades").removeClass("show");

    $("#documentos").removeClass("active");
    $("#documentos").removeClass("show");

    $("#requisitos_especiales").removeClass("active");
    $("#requisitos_especiales").removeClass("show");

    $("#datos_admin").removeClass("active");
    $("#datos_admin").removeClass("show");

    document.getElementById("general").style.display = "block";
    document.getElementById("contactos").style.display = "none";
    document.getElementById("sucursales").style.display = "none";
    document.getElementById("oportunidades").style.display = "none";
    document.getElementById("documentos").style.display = "none";
    document.getElementById("requisitos_especiales").style.display = "none";
    document.getElementById("datos_admin").style.display = "none";
    document.getElementById("sbtnSave").style.display = "block";
    $("#sbtnBack").removeClass("col-12");
    $("#sbtnBack").addClass("col-6");

    tableContactos.draw();
    tableSucursales.draw();
    tableOportunidadesCliente.draw();
    ajustarTablas();
}

function mostrarContactos() {
    $("#general").removeClass("active");
    $("#general").removeClass("show");

    $("#contactos").addClass("active");
    $("#contactos").addClass("show");

    $("#sucursales").removeClass("active");
    $("#sucursales").removeClass("show");

    $("#oportunidades").removeClass("active");
    $("#oportunidades").removeClass("show");

    $("#documentos").removeClass("active");
    $("#documentos").removeClass("show");

    $("#requisitos_especiales").removeClass("active");
    $("#requisitos_especiales").removeClass("show");

    $("#datos_admin").removeClass("active");
    $("#datos_admin").removeClass("show");

    document.getElementById("general").style.display = "none";
    document.getElementById("contactos").style.display = "block";
    document.getElementById("sucursales").style.display = "none";
    document.getElementById("oportunidades").style.display = "none";
    document.getElementById("documentos").style.display = "none";
    document.getElementById("requisitos_especiales").style.display = "none";
    document.getElementById("datos_admin").style.display = "none";
    document.getElementById("sbtnSave").style.display = "none";
    $("#sbtnBack").addClass("col-12");

    var height = window.innerHeight;

    if (height >= 320 && height <= 651) {
        $("#tableContactosCliente").closest(".dataTables_scrollBody").css("height", "58vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableContactosCliente").closest(".dataTables_scrollBody").css("height", "61vh");
    } else if (height >= 769 && height <= 992) {
        $("#tableContactosCliente").closest(".dataTables_scrollBody").css("height", "70vh");
    } else if (height >= 993 && height <= 1024) {
        $("#tableContactosCliente").closest(".dataTables_scrollBody").css("height", "71vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableContactosCliente").closest(".dataTables_scrollBody").css("height", "71vh");
    } else {
        $("#tableContactosCliente").closest(".dataTables_scrollBody").css("height", "73vh");
    }
    tableContactos.draw();
    ajustarTablas();
}

function mostrarSucursales() {
    $("#general").removeClass("active");
    $("#general").removeClass("show");

    $("#contactos").removeClass("active");
    $("#contactos").removeClass("show");

    $("#sucursales").addClass("active");
    $("#sucursales").addClass("show");

    $("#oportunidades").removeClass("active");
    $("#oportunidades").removeClass("show");

    $("#documentos").removeClass("active");
    $("#documentos").removeClass("show");

    $("#requisitos_especiales").removeClass("active");
    $("#requisitos_especiales").removeClass("show");

    $("#datos_admin").removeClass("active");
    $("#datos_admin").removeClass("show");

    document.getElementById("general").style.display = "none";
    document.getElementById("contactos").style.display = "none";
    document.getElementById("sucursales").style.display = "block";
    document.getElementById("oportunidades").style.display = "none";
    document.getElementById("documentos").style.display = "none";
    document.getElementById("requisitos_especiales").style.display = "none";
    document.getElementById("datos_admin").style.display = "none";
    document.getElementById("sbtnSave").style.display = "none";
    $("#sbtnBack").addClass("col-12");

    var height = window.innerHeight;

    if (height >= 320 && height <= 651) {
        $("#tableSucursalesCliente").closest(".dataTables_scrollBody").css("height", "58vh");
    } else if (height >= 652 && height <= 768) {
        $("#tableSucursalesCliente").closest(".dataTables_scrollBody").css("height", "61vh");
    } else if (height >= 769 && height <= 992) {
        $("#tableSucursalesCliente").closest(".dataTables_scrollBody").css("height", "70vh");
    } else if (height >= 993 && height <= 1024) {
        $("#tableSucursalesCliente").closest(".dataTables_scrollBody").css("height", "71vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tableSucursalesCliente").closest(".dataTables_scrollBody").css("height", "71vh");
    } else {
        $("#tableSucursalesCliente").closest(".dataTables_scrollBody").css("height", "73vh");
    }
    tableSucursales.draw();
    // ajustarTablas();
}

function mostrarOportunidades() {

    $("#general").removeClass("active");
    $("#general").removeClass("show");

    $("#contactos").removeClass("active");
    $("#contactos").removeClass("show");

    $("#sucursales").removeClass("active");
    $("#sucursales").removeClass("show");

    $("#oportunidades").addClass("active");
    $("#oportunidades").addClass("show");

    $("#documentos").removeClass("active");
    $("#documentos").removeClass("show");

    $("#requisitos_especiales").removeClass("active");
    $("#requisitos_especiales").removeClass("show");

    $("#datos_admin").removeClass("active");
    $("#datos_admin").removeClass("show");

    document.getElementById("general").style.display = "none";
    document.getElementById("contactos").style.display = "none";
    document.getElementById("sucursales").style.display = "none";
    document.getElementById("oportunidades").style.display = "block";
    document.getElementById("documentos").style.display = "none";
    document.getElementById("requisitos_especiales").style.display = "none";
    document.getElementById("datos_admin").style.display = "none";
    document.getElementById("sbtnSave").style.display = "none";
    $("#sbtnBack").addClass("col-12");

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

function mostrarDocumentos() {
    tblDocs();
    $("#general").removeClass("active");
    $("#general").removeClass("show");

    $("#contactos").removeClass("active");
    $("#contactos").removeClass("show");

    $("#sucursales").removeClass("active");
    $("#sucursales").removeClass("show");

    $("#oportunidades").removeClass("active");
    $("#oportunidades").removeClass("show");

    $("#documentos").addClass("active");
    $("#documentos").addClass("show");

    $("#requisitos_especiales").removeClass("active");
    $("#requisitos_especiales").removeClass("show");

    $("#datos_admin").removeClass("active");
    $("#datos_admin").removeClass("show");

    document.getElementById("general").style.display = "none";
    document.getElementById("contactos").style.display = "none";
    document.getElementById("sucursales").style.display = "none";
    document.getElementById("oportunidades").style.display = "none";
    document.getElementById("documentos").style.display = "block";
    document.getElementById("requisitos_especiales").style.display = "none";
    document.getElementById("datos_admin").style.display = "none";
    document.getElementById("sbtnSave").style.display = "none";
    $("#sbtnBack").addClass("col-12");

    ajustarTablas();
}

function mostrarRequisitos() {

    $("#general").removeClass("active");
    $("#general").removeClass("show");

    $("#contactos").removeClass("active");
    $("#contactos").removeClass("show");

    $("#sucursales").removeClass("active");
    $("#sucursales").removeClass("show");

    $("#oportunidades").removeClass("active");
    $("#oportunidades").removeClass("show");

    $("#documentos").removeClass("active");
    $("#documentos").removeClass("show");

    $("#requisitos_especiales").addClass("active");
    $("#requisitos_especiales").addClass("show");

    $("#datos_admin").removeClass("active");
    $("#datos_admin").removeClass("show");

    document.getElementById("general").style.display = "none";
    document.getElementById("contactos").style.display = "none";
    document.getElementById("sucursales").style.display = "none";
    document.getElementById("oportunidades").style.display = "none";
    document.getElementById("documentos").style.display = "none";
    document.getElementById("requisitos_especiales").style.display = "block";
    document.getElementById("datos_admin").style.display = "none";
    document.getElementById("sbtnSave").style.display = "block";
    $("#sbtnBack").removeClass("col-12");
    $("#sbtnBack").addClass("col-6");

    ajustarTablas();
}

function mostrarDatosAdmin() {
    idrol = document.getElementById("idRol");

    $("#general").removeClass("active");
    $("#general").removeClass("show");

    $("#contactos").removeClass("active");
    $("#contactos").removeClass("show");

    $("#sucursales").removeClass("active");
    $("#sucursales").removeClass("show");

    $("#oportunidades").removeClass("active");
    $("#oportunidades").removeClass("show");

    $("#documentos").removeClass("active");
    $("#documentos").removeClass("show");

    $("#requisitos_especiales").removeClass("active");
    $("#requisitos_especiales").removeClass("show");

    $("#datos_admin").addClass("active");
    $("#datos_admin").addClass("show");

    document.getElementById("general").style.display = "none";
    document.getElementById("contactos").style.display = "none";
    document.getElementById("sucursales").style.display = "none";
    document.getElementById("oportunidades").style.display = "none";
    document.getElementById("documentos").style.display = "none";
    document.getElementById("requisitos_especiales").style.display = "none";
    document.getElementById("datos_admin").style.display = "block";
    document.getElementById("sbtnSave").style.display = "block";
    $("#sbtnBack").removeClass("col-12");
    $("#sbtnBack").addClass("col-6");

    ajustarTablas();
}

var mensaje = "";
var icono = "";

var estop = document.getElementById("esTop");
var buro = document.getElementById("conBuroC");

$(function () {
    $("#modal-create-cliente form").validator().on("submit", function (e) {
        loaderON();
        if (!e.isDefaultPrevented()) {
            mensaje = "Cliente Creado";
            icono = "success";
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
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

    $("#formClienteEdit form").validator().on("submit", function (e) {
        if (!e.isDefaultPrevented()) {
            mensaje = "Cliente Actualizado";
            icono = "success";
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
        }
        loaderON();
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: icono,
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire({ title: mensaje });
    });

    // --ING. Brigette Gtz--//
    filtros();
    var countFilter = 1;
    // var tblClientes = $('#tableClientes').DataTable();
    $(".btn-refresh").click(function () {
        clearFilters();
    }); // Limpiar tabla
    $("#boton").click(function () {
        $("#parrafo").next("p").hide(3000);
    });
    $("#searchGral").keypress(function (e) {
        if (e.which == 13) { // loaderON();
            var texto = $(this).val();
            filterGlobal(texto);
        }
    });
    $("#btn-search").click(function (e) { // loaderON();
        var texto = $("#searchGral").val();
        filterGlobal(texto);
    }); // Barra de buscar general
    $("#btn-filters").click(function () {
        var evento = "0";
        if ($("#card-filtros").hasClass("filter-hide")) {
            combo_Filtros(evento);
            $(this).attr("title", "Ocultar Filtros");
            $("#card-filtros").removeClass("filter-hide").addClass("filter-show");
            $(".searchCliente_0").focus();
        } else {
            clearFilters();
            $(this).attr("title", "Mostrar Filtros");
            $("#card-filtros").removeClass("filter-show").addClass("filter-hide");
        }
    });
    $(".btn-addF").click(function (e) {
        var noselect = $(this).attr("data-idslt");
        addFiltro(noselect);
    });
    // /// FUNCIONES
    keyup();

    function filterGlobal(texto) {
        $("#tableClientes").DataTable().search("").columns().search("").draw();
        $("#tableClientes").DataTable().search(texto, false, true).draw();
    }

    function filterColumn(i, texto) {
        $("#tableClientes").DataTable().search("").columns().search("").draw();
        $("#tableClientes").DataTable().column(i).search(texto, false, true).draw();
    }

    function filtros() {
        $(".slt_filtro").change(function () { // console.log("change");
            var texto = "",
                columna = $(this).find(":selected").attr("data-columns"),
                noselect = $(this).attr("data-idslt");
            $(".searchCliente_" + noselect).attr("id", "col" + columna + "_filter");
            $(".searchCliente_" + noselect).attr("data-columns", columna);
            $(".Dslt_" + noselect).attr("id", "Del" + columna + "_filter");
            $(".Sslt_" + noselect).attr("id", "Sav" + columna + "_filter");
            $(".Sslt_" + noselect).attr("data-columns", columna);
            texto = $(".searchCliente_" + noselect).val();
            keyup();
        });

        $(".btn-deleteF").click(function (e) {
            var noselect = $(this).attr("data-idslt");
            $("#slt-filtro" + noselect + "").remove();
        });
    }

    function addFiltro(noselect) {
        var evento = countFilter;

        var Select = '<div class="input-group-prepend"> <select class="card-primary form-control  select2 slt_filtro"  data-placeholder="Filtro" style="width: 100%;" data-idslt=' + countFilter + ' id="slt_filtro' + countFilter + '">' + '<option selected="selected">Elige un Filtro</option>' + "</select> </div>";
        var input = '<input type="text" class="column_filter form-control searchCliente_' + countFilter + '" placeholder="Buscar ...">';
        var buttonSch = '<span class="input-group-append"><button type="button" class="btn btn-primary btn-flat btn-saveF btn_SearchColumn Sslt_' + countFilter + '"  title="Buscar" data-idslt=' + countFilter + '> <i class="nav-icon fas fa-search"></i></button></span>';
        var buttonDel = '<span class="input-group-append"><button type="button"  class="btn btn-danger btn-flat btn-deleteF Sslt_' + countFilter + '"  title="Eliminar Filtro" data-idslt=' + countFilter + '>  <i class="nav-icon fas fa-minus"></i></button></span>';
        var buttonSav = '<span class="input-group-append"><button type="button" class="btn btn-primary btn-flat btn-saveF Sslt_' + countFilter + '"  title="Guardar Filtro" data-idslt=' + countFilter + ' hidden> <i class="nav-icon fas fa-save"></i></button></span>';
        var div = '<div class="row justify-content-md-center fil slt"  id="slt-filtro' + countFilter + '"> <div class="col-sm-9 input-group input-group-sm justify-content-md-left">' + Select + input + buttonSch + buttonDel + buttonSav + "</div></div>";
        $("#box-filtros").append(div);
        $(".searchCliente_" + countFilter).focus();
        countFilter = countFilter + 1;
        combo_Filtros(evento);
        keyup();
        filtros();
    }

    function keyup() {
        $("input.column_filter").keypress(function (e) {
            if (e.which == 13) { // loaderON();
                var i = $(this).attr("data-columns"),
                    texto = $("#col" + i + "_filter").val();
                i >= 0 ? filterColumn(i, texto) : toast("Debes selecionar una filtro", "warning");
            }
        });
        // $('input.column_filter').on('keyup click', function() //Buscar por columna
        $("button.btn_SearchColumn").click(function () { // loaderON();
            var i = $(this).attr("data-columns"),
                texto = $("#col" + i + "_filter").val();
            i >= 0 ? filterColumn(i, texto) : toast("Debes selecionar una filtro", "warning");
        });
    }
    const Toast = Swal.mixin({ toast: true, position: "center", showConfirmButton: false, timer: 3000 });

    function toast(titulo, icono) {
        loaderOut();
        Toast.fire({ icon: icono, title: titulo });
    }

    function clearFilters() {
        for (var i = 1; i <= countFilter; i++) {
            $("#slt-filtro" + i).remove();
        }
        $("input").val("");
        $(".searchCliente_0").focus();
        $("#tableClientes").DataTable().search("").columns().search("").draw();
    }

    function combo_Filtros(evento) {
        var columns = dataTableClientes.settings().init().columns,
            option = "",
            nocolum = 0;
        dataTableClientes.columns().every(function (index) {
            option = '<option value="' + columns[index].name + '" data-columns="' + nocolum + '">' + columns[index].title + "</option>";
            if (evento == "0") {
                $("#slt-filtro").append(option);
            } else {
                $("#slt_filtro" + evento).append(option);
            }
            nocolum++;
        });
    }
});

// ================================================ Funciones JCG =====================================

CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

function actualizarTop(id) {
    mensaje = "Cliente Actualizado";
    id_suario = document.getElementById("idUsuario");
    Swal.fire({
        title: "Clientes",
        text: "Actualizar Top del Cliente Seleccionado?",
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
                url: serve + "actualizarTop",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    id_suario: id_suario.value,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    dataTableClientes.ajax.reload();
                },
                error: function () {
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

function cambiar_agente(id, agente, nombre_agente) {
    mensaje = "Cliente Actualizado";
    id_suario = document.getElementById("idUsuario");
    Swal.fire({
        title: "Clientes",
        text: "Actualizar Agente del Cliente Seleccionado?",
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
                url: serve + "cambiarAgente",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    agente: agente,
                    id_suario: id_suario.value,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    window.location.href = "clientes";
                    // dataTableClientes.ajax.reload();
                },
                error: function () {
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
            $("#agentes_grid_" + id).find("option").attr("selected", false);
            $("#agentes_grid_" + id + '  option:contains("' + nombre_agente + '")').attr("selected", true);
            window.location.href = "clientes";
            // dataTableClientes.ajax.reload();
        }
    });
}

function openModal() {
    let modal = document.querySelector("#modal-create-cliente");
    modal.classList.add("showModal");
}

// function openModalEdit(id) {
//     var modal = document.querySelector("#modal-edit-cliente");
//     // modal.classList.add("showModal");
//     mensaje = "Cliente Actualizado";
//     save_method = "edit";
//     $("imput[name=_method]").val("PATCH");
//     // $('#modal-edit-cliente form')[0].reset();

//     $.ajax({
//         url: "{{ url('clientes') }}" + "/" + id + "/edit",
//         type: "GET",
//         dataType: "JSON",
//         success: function(data) {
//             console.log(data);
//             // var texto = data.fecha_compra;
//             // var fechaCompra = formato(texto);

//             $("#idUsuarioEdit").val(data.JSON);
//             $("#razonsocialEdit").val(data[0].razonsocial);
//             // $('#descripcion').val(data.descripcion);
//             // $('#categoria').val(data.categoria);
//             // $('#marca').val(data.marca);
//             // $('#modelo').val(data.modelo);
//             // $('#numero_serie').val(data.numero_serie);
//             // $('#numero_licencia').val(data.numero_licencia);
//             // $('#numero_proveedor').val(data.numero_proveedor);
//             // $('#nombre_proveedor').val(data.nombre_proveedor);
//             // $('#numero_factura').val(data.numero_factura);
//             // $('#fecha_compra').val(fechaCompra);

//             // $('#numero_factura').readOnly = true;
//             // $('#fecha_compra').readOnly = true;

//             // $('#ajaxModal').modal('show');
//             // $('.modal-title').text('Editar Producto');

//             // function formato(texto) {
//             //     return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
//             // };
//         },
//         error: function() {
//             const Msg = Swal.mixin({
//                 toast: true,
//                 position: "center",
//                 icon: "warning",
//                 showConfirmButton: false,
//                 timer: 3000,
//             });

//             Msg.fire({
//                 title: "Ocurrió un error",
//             });
//         },
//     });
// }

function eliminarCliente(id) {
    mensaje = "Cliente Actualizado";
    id_suario = document.getElementById("idUsuario");
    Swal.fire({
        title: "Clientes",
        text: "Activar/Desactivar Cliente Seleccionado?",
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
                url: serve + "darDeBaja",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    id_suario: id_suario.value,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    dataTableClientes.ajax.reload();
                },
                error: function () {
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

function actualizarBuro(id) {
    mensaje = "Cliente Actualizado";
    id_suario = document.getElementById("idUsuario");
    Swal.fire({
        title: "Clientes",
        text: "Actualizar Buró del Cliente Seleccionado?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        cancelButtonText: "No",
        confirmButtonText: "Si",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "actualizarBuro",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id,
                    id_suario: id_suario.value,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    dataTableClientes.ajax.reload();
                },
                error: function () {
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

function closeM() {
    let m = document.querySelector("#modal-create-cliente");
    m.classList.remove("showModal");
}

function closeMEdit() {
    let m = document.querySelector("#modal-edit-cliente");
    m.classList.remove("showModal");
}

function validarInput(input) {
    var rfc = input.value.trim().toUpperCase(),
        resultado = document.getElementById("resultado"),
        valido;

    var rfcCorrecto = rfcValido(rfc); // ⬅️ Acá se comprueba
    // var rfcCorrecto = true; // ⬅️ Acá se comprueba

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
            input.value = '';
            input.focus();
            document.getElementById("RFC").classList.remove("is-valid");
            document.getElementById("RFC").classList.add("is-invalid");
        });
    }
}

function rfcValido(rfc, aceptarGenerico = true) {
    const re = /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
    var validado = rfc.match(re);

    // validar si el rfc es viejito
    /*const pattern = /^[A-Z]{3}[0-9]{6}[A-V]{1}[0-9]{2}$/;
    if (pattern.test(rfc)) {
        return rfc;
    }*/

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

document.addEventListener("keypress", (e) => {
    if (e.keyCode == 13) {
        e.preventDefault();
    }
});

function convertirEnProspecto(id_cliente) {
    mensaje = "El Cliente Ahora es Prospecto";
    id_suario = document.getElementById("idUsuario");
    id_check = document.getElementById("EsProspectoChk-" + id_cliente);
    console.log(id_check.checked);
    Swal.fire({
        title: "Clientes",
        text: "Convertir en Prospecto el Cliente Seleccionado?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        cancelButtonText: "No",
        confirmButtonText: "Si",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {

            // const Msg = Swal.mixin({
            //     toast: true,
            //     position: "center",
            //     icon: "success",
            //     showConfirmButton: false,
            //     timer: 3000
            // });

            // Msg.fire({ title: mensaje });

            $.ajax({
                url: serve + "convertirEnProspecto",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    id: id_cliente,
                    check: id_check.checked,
                    id_suario: id_suario.value,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: mensaje });
                    dataTableClientes.ajax.reload();
                },
                error: function () {
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
            id_check.checked = false;
        }
    });
}

function actualizarSolicitarPresupuesto(id_cliente, input) {
    let mensaje = "Cliente Actualizado";
    Swal.fire({
        title: "Clientes",
        text: "Solicitar Presupuesto del Cliente Seleccionado?",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        cancelButtonText: "No",
        confirmButtonText: "Si",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: serve + "actualizarSolicitarPresupuesto",
                type: "POST",
                data: {
                    _method: "POST",
                    _token: CSRF_TOKEN,
                    id: id_cliente,
                    check: input,
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: 'Se solicitó el presupuesto' });
                    dataTableClientes.ajax.reload();
                },
                error: function () {
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
