var columedit = 1;
const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
const TO_PATTERN = "DD/MM/YYYY HH:mm";
const FROM_PATTERN_SHORT = "YYYY-MM-DD";
const TO_PATTERN_SHORT = "DD/MM/YYYY";
$("#tblElementosPresupuestados").DataTable().on("draw", function() { ajustarTablas(); });
$("#tblElementosPresupuestadosRelation").DataTable().on("draw", function() { ajustarTablas(); });
rutaActual = document.location.href;
if (rutaActual.indexOf('edit') > -1) {
    $("#div-btnrelacionar").show();
    columedit = 1;
} else {
    $("#div-btnrelacionar").hide();
    columedit = 14;
}
// $(document).ready(function() {
//     $($.fn.dataTable.tables(true)).DataTable()
//         .columns.adjust();
// });
//------FUNCIONES-----------------

function editElemento(idElementoP) {

    $("#noPresupuesto-" + idElementoP).prop('readonly', false).focus();
    $("#editPresupuesto" + idElementoP).hide();
    $("#savePresupuesto" + idElementoP).show();
}

function saveElemento(idElementoP) {
    loaderON();
    numPresupuestoMTC = $("#noPresupuesto-" + idElementoP).val();
    addValorOportunidad(idElementoP, numPresupuestoMTC);

}

function addValorOportunidad(idElementoP, numPresupuestoMTC) {

    $.ajax({
        url: serve + "api/addValorOportunidad",
        type: "PATCH",
        data: {
            _method: "PATCH",
            idElementoP: idElementoP,
            numPresupuestoMTC: numPresupuestoMTC,
            // _token: CSRF_TOKEN,
        },
        success: function(data) {
            loaderOut();
            $("#editPresupuesto" + idElementoP).show();
            $("#savePresupuesto" + idElementoP).hide();
            $("#noPresupuesto-" + idElementoP).prop('readonly', true);
            $("#tblElementosPresupuestados").DataTable().ajax.reload();
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: 'Presupuesto Actualizado',
            });
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
                title: 'Error al Actualizar',
            });
            // window.location.href = serve + "Elementos Presupuestados";
        },
    });

}

//----- TABLAS--------------------
function tblIndex(serve, eventoTbl) {
    $('#tblElementosPresupuestados').ajaxSend(loaderON());
    tblElementosPresupuestados = $('#tblElementosPresupuestados').DataTable({
        destroy: true,
        processing: false,
        serverSide: true,
        stateSave: true,
        paginate: true,
        order: [
            [0, "desc"]
        ],
        language: {
            url: serve + 'js/spanish-DT.json'
        },
        // ajax: url,

        ajax: {
            url: (serve + "/tblelementos/" + eventoTbl),
            type: 'GET',
            dataType: 'json',
        },
        columns: [{
                data: "idElementoP",
                // render: function(data, type, row) {
                //     let posicion = (data).indexOf(row.idEquipo);
                //     if (posicion !== -1) {

                //         return ("exists");
                //     } else { return data; }
                // },
                name: 'idElementoP',
                width: '2rem',
                title: 'No.'
            },
            {
                data: 'Facturado',
                render: function(data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="ActualizarFacturado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="ActualizarFacturado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                            }
                        } else {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data <= 0) {
                            return (
                                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                            );
                        } else {
                            return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                    }
                },
                name: 'Facturado',
                title: 'Facturado'

            },
            {
                data: 'Sincronizado',
                render: function(data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="ActualizarSincronizado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="ActualizarSincronizado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                            }
                        } else {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data <= 0) {
                            return (
                                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                            );
                        } else {
                            return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                    }
                },
                name: 'Sincronizado',
                title: 'Sincronizado'

            },
            {
                data: 'ElementoPresupuestado',
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 2 ||
                        row.inputRol == 11 ||
                        row.inputRol == 8

                        // row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8 || row.inputRol == 11 || row.inputRol == 2
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return '<a href="' + serve + 'elementos/' + row.idElementoP + '/edit" title="Editar Elemento">' + data + '</a>';
                        } else {
                            return data;
                        }
                    } else {
                        let posicion = (row.inputequipos).indexOf(row.idEquipo);
                        if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) || posicion !== -1) {
                            if ($.trim(row.inputEditar) === "Editar") {

                                return '<a href="' + serve + 'elementos/' + row.idElementoP + '/edit" title="Editar Elemento">' + data + '</a>';
                            } else {

                                return data;
                            }
                        } else {
                            // let posicion = (row.inputequipos).indexOf(row.idEquipo);
                            // if (posicion !== -1) {

                            //     return '<a href="' + serve + 'elementos/' + row.idElementoP + '/edit" title="Editar Elemento">' + data + '</a>';
                            //     // return ("exists");
                            // } else { return 'OK2'; }
                            // return (row.inputLogin + ' ' + row.Login);
                            return data;

                        }
                    }
                },
                name: 'ElementoPresupuestado',
                width: '6rem',
                title: 'Elemento Presupuestado'
            },
            {
                data: 'Descripcion',
                name: 'Descripcion',
                width: '6rem',
                title: 'Descripcion'
            },
            {
                data: 'Estado',
                name: 'Estado',
                width: '2rem',
                title: 'Estado'
            },
            {
                data: 'Cliente',
                name: 'Cliente',
                width: '6rem',
                render: function(data, type, row) {
                    if (row.EsCliente == 1) {
                        return '<span class="text-uppercase">' + data + '</span>';
                    } else {
                        if (data != null) {
                            if (
                                row.inputRol == 1 ||
                                row.inputRol == 5 ||
                                row.inputRol == 7 ||
                                row.inputRol == 2 ||
                                row.inputRol == 11 ||
                                row.inputRol == 8
                            ) {
                                if ($.trim(row.inputEditar) === "Editar") {
                                    if (row.prospecto == 1) {

                                        return (
                                            '<a href="' + serve + 'prospectos/' +
                                            row.idProspecto +
                                            '/edit" title="Editar Prospecto" class="text-uppercase" onclick="loaderON()">' +
                                            data +
                                            "</a>"
                                        );
                                    } else {

                                        return (
                                            '<a href="' + serve + 'clientes/' +
                                            row.ClienteINT +
                                            '/edit" title="Editar Cliente" onclick="loaderON()">' +
                                            data +
                                            "</a>"
                                        );
                                    }
                                } else {
                                    return data;
                                }
                            } else {
                                if (
                                    $.trim(row.inputLogin) == $.trim(row.Login) ||
                                    $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) // ||
                                    // $.trim(row.LoginCustomer) == $.trim(row.inputLogin)
                                ) {
                                    if ($.trim(row.inputEditar) === "Editar") {
                                        if (row.prospecto == 1) {

                                            return (
                                                '<a href="' + serve + 'prospectos/' +
                                                row.idProspecto +
                                                '/edit" title="Editar Prospecto" class="text-uppercase" onclick="loaderON()">' +
                                                data +
                                                "</a>"
                                            );
                                        } else {

                                            return (
                                                '<a href="' + serve + 'clientes/' +
                                                row.ClienteINT +
                                                '/edit" title="Editar Cliente" onclick="loaderON()">' +
                                                data +
                                                "</a>"
                                            );
                                        }
                                    } else {
                                        return data;
                                    }
                                } else {
                                    return data;
                                }
                            }
                        } else {
                            return 'NO ASIGNADO';
                        }
                    }
                },
                title: 'Cliente'
            },
            {
                data: 'fecRegistro',
                name: 'fecRegistro',
                width: '40px',
                title: 'Fecha Registro'
            },
            {
                data: 'Login',
                name: 'Login',
                width: '40px',
                title: 'Agente'
            },
            {
                data: 'numPresupuesto',
                render: function(data, type, row) {
                    if (columedit == 14) { return data; } else { return '<input id="noPresupuesto-' + row.idElementoP + '" name="noPresupuesto-' + row.idElementoP + '" data-id="' + data + '" value="' + data + '" class="inpPto onlyread text-center" readonly />'; }
                },
                name: 'numPresupuesto',
                width: '2rem',
                title: 'No Presupuesto',
                visible: false,
            },
            {
                data: 'Cantidad',
                render: $.fn.dataTable.render.number(',', '.', 0, ''),
                name: 'Cantidad',
                width: '2rem',
                title: 'Cantidad',
                className: "dt-body-center text-right",
            },
            {
                data: 'preUnit',
                render: $.fn.dataTable.render.number(',', '.', 2, '$ '),
                name: 'preUnit',
                width: '2rem',
                title: 'Pre. Unit.',
                className: "dt-body-center text-right",
            },
            {
                data: 'Importe',
                render: $.fn.dataTable.render.number(',', '.', 2, '$ '),
                name: 'Importe',
                width: '2rem',
                title: 'Subtotal',
                className: "dt-body-center text-right",
            },
            {
                data: 'idElementoP',
                render: function(data, type, row) {
                    return '<a href="' + serve + 'api/pdf/generar/' + row.idElementoP + '" target="_blank" ><i class="fas fa-file-pdf text-red btnEP"></i></a>';
                },
                name: 'idElementoP',
                width: '1rem',
                title: 'PDF'
            },
            {
                data: 'idElementoP',
                render: function(data, type, row) {
                    return '<i id="editPresupuesto' + row.idElementoP + '" class="fas fa-edit text-blue btnEP" onclick="editElemento(' + data + ')" ></i><i id="savePresupuesto' + row.idElementoP + '" class="fas fa-save text-green btnEP" onclick="saveElemento(' + data + ')" style="display:none" ></i>';

                },
                name: 'idElementoP',
                width: '1rem',
                title: 'Editar',
                visible: false,
            },
            {
                data: 'folio',
                name: 'folio',
                width: '1rem',
                title: 'Oportunidad',
            },

        ],
        select: false,
        scrollY: false,
        scrollX: true,
        pageLength: 50,
        autoWidth: false,
        columnDefs: [{
                "className": "dt-center",
                "targets": [0, 1, 2, 5, 7, 9, 13, 14, 15]
            },
            {
                "targets": [1, 2],
                "visible": false
            },
            {
                "targets": columedit,
                "visible": false
            },
            {
                targets: [7],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
            // { "width": "20%", "targets": 5 },
            // { "width": "50%", "targets": 0 }
        ],
        bLengthChange: true,
        bInfo: true,
        lengthMenu: [
            [50, 100, 150, 250, -1],
            [50, 100, 150, 250, 'TODOS']
        ],
        // autoWidth: true,
        // fixedColumns: {
        //     leftColumns: 4,
        // },
        dom: '<"top"R>rt<"bottom"lpB><"clear">',
        buttons: [{
            extend: 'colvis',
            columns: [0, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
        }, ],
        "initComplete": function(settings, json) {
            loaderOut();
        },
    });
    // new $.fn.dataTable.FixedColumns( table ); 
    $("#tblElementosPresupuestados").DataTable().on("draw", function() {
        // ajustarTamaño(height);
    });
    $($.fn.dataTable.tables(true)).DataTable()
        // .columns.adjust();

}

function tblEP_relation(serve, eventoTbl) {
    tblElementosPresupuestadosRelation = $('#tblElementosPresupuestadosRelation').DataTable({
        destroy: true,
        pageResize: true,
        processing: false,
        serverSide: true,
        paginate: true,
        searching: true,
        stateSave: false,
        select: false,
        scrollY: false,
        scrollX: true,
        scrollCollapse: true,
        pageLength: 25,
        order: [
            [0, "desc"]
        ],
        language: {
            url: serve + 'js/spanish-DT.json'
        },
        // ajax: url,

        ajax: {
            url: (serve + "/tblelementos/" + eventoTbl),
            type: 'GET',
            dataType: 'json',
        },
        columns: [{
                data: "idElementoP",
                render: function(data, type, row) {
                    return (
                        // '<div class="custom-control custom-checkbox indexRelation"><input class="custom-control-input RelCheck" type="checkbox" id="Checkbox-' + row.idElementoP + ' " val="' + row.idElementoP + ' "><label for="Checkbox-' + row.idElementoP + ' " class="custom-control-label"></label></div>'
                        '<div class="custom-switch indexRelation">' +
                        '<input class="custom-control-input RelCheck" type="checkbox" id="Checkbox-' + row.idElementoP + '" val="' + row.idElementoP + '">' +
                        '<label for="Checkbox-' + row.idElementoP + '"class="custom-control-label cursor-pointer">' +
                        '</label>' +
                        '</div>'
                    );
                },
                name: 'idElementoP',
                title: 'Select'
            },
            {
                data: "idElementoP",
                name: 'idElementoP',
                title: 'Numero'
            },
            {
                data: 'Facturado',
                render: function(data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="ActualizarFacturado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="ActualizarFacturado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                            }
                        } else {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data <= 0) {
                            return (
                                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                            );
                        } else {
                            return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                    }
                },
                name: 'Facturado',
                title: 'Facturado'

            },
            {
                data: 'Sincronizado',
                render: function(data, type, row) {
                    if (row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="ActualizarSincronizado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked><label onclick="ActualizarSincronizado(' + row.idElementoP + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                            }
                        } else {
                            if (data <= 0) {
                                return (
                                    '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                                );
                            } else {
                                return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                            }
                        }
                    } else {
                        if (data <= 0) {
                            return (
                                '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" disabled><label class="custom-control-label" disabled></label></div>'
                            );
                        } else {
                            return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input" checked disabled><label class="custom-control-label" disabled></label></div>';
                        }
                    }
                },
                name: 'Sincronizado',
                title: 'Sincronizado'

            },
            {
                data: 'ElementoPresupuestado',
                render: function(data, type, row) {
                    if (
                        row.inputRol == 1 ||
                        row.inputRol == 5 ||
                        row.inputRol == 7 ||
                        row.inputRol == 2 ||
                        row.inputRol == 11 ||
                        row.inputRol == 8

                        // row.inputRol == 1 || row.inputRol == 5 || row.inputRol == 7 || row.inputRol == 8 || row.inputRol == 11 || row.inputRol == 2
                    ) {
                        if ($.trim(row.inputEditar) === "Editar") {
                            return '<a href="' + serve + 'elementos/' + row.idElementoP + '/edit" title="Editar Elemento">' + data + '</a>';
                        } else {
                            return data;
                        }
                    } else {
                        let posicion = (row.inputequipos).indexOf(row.idEquipo);
                        if ($.trim(row.inputLogin) == $.trim(row.Login) || $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) || posicion !== -1) {
                            if ($.trim(row.inputEditar) === "Editar") {

                                return '<a href="' + serve + 'elementos/' + row.idElementoP + '/edit" title="Editar Elemento">' + data + '</a>';
                            } else {

                                return data;
                            }
                        } else {
                            // let posicion = (row.inputequipos).indexOf(row.idEquipo);
                            // if (posicion !== -1) {

                            //     return '<a href="' + serve + 'elementos/' + row.idElementoP + '/edit" title="Editar Elemento">' + data + '</a>';
                            //     // return ("exists");
                            // } else { return 'OK2'; }
                            // return (row.inputLogin + ' ' + row.Login);
                            return data;

                        }
                    }
                },
                name: 'ElementoPresupuestado',
                title: 'Elemento Presupuestado'
            },
            {
                data: 'Descripcion',
                name: 'Descripcion',
                title: 'Descripcion'
            },
            {
                data: 'Cliente',
                name: 'Cliente',
                title: 'Cliente'
            },
            {
                data: 'preObjetivo',
                name: 'preObjetivo',
                title: 'PrecioUnitario'

            },
            // {
            //     data: 'idElementoP',
            //     name: 'idElementoP',
            //     title: 'Subtotal'
            // },
            {
                data: 'fecRegistro',
                name: 'fecRegistro',
                title: 'Fecha Registro'
            },
            {
                data: 'Login',
                name: 'Login',
                title: 'Agente'
            },

        ],

        columnDefs: [{
                "className": "dt-center",
                "targets": [0, 1, 2, 7]
            },
            {
                "targets": [2, 3, 7],
                "visible": false
            },
            {
                targets: [8],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
        ],
        bLengthChange: true,
        bInfo: true,
        lengthMenu: [
            [25, 50, 100, 150, 250, -1],
            [25, 50, 100, 150, 250, 'TODOS']
        ],
        autoWidth: true,
        // fixedColumns: {
        //     leftColumns: 1,
        // },
        // dom: "Bfrtip",
        dom: 'f<"top"R>rt<"bottom"lp><"clear">',
        buttons: [{
            extend: 'colvis',
            columns: [0, 1, 2, 3, 4, 5, 6, 7],
            text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
        }, ],
        "initComplete": function(settings, json) {
            loaderOut();
        },
    });

    // $("#tblElementosPresupuestadosRelation").DataTable().on("draw", function() {
    //     ajustarTamaño_EP(height);
    // });
}

// function ajustarTamaño_EP(height) {
//     if (height >= 320 && height <= 651) {
//         $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "68vh");

//     } else if (height >= 652 && height <= 768) {
//         $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "68vh");
//     } else if (height >= 769 && height <= 1024) {
//         $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "77vh");
//     } else if (height >= 1025 && height <= 1366) {
//         $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "80vh");
//     } else if (height >= 1440) {
//         $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "82vh");
//     }
//     tblElementosPresupuestados.draw();

// }