serve = base_path;

var id = $("#idOportunidad").val(),
    group = 2,
    formDataOport = "",
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
var collapsedGroups = {};
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});
// ------- CLICKS----/
$("#btnSaveDocsCte").click(function () {
    loaderON();
    addDocumento();
});
// -----CHECKS----/
$("input[name='FileDoc']").change(function () {
    formDataOport = new FormData();
    var idTipoDocumento = $("#idTipoDocumento").val();
    // alert(idDocumento);
    formDataOport.append("idOportunidad", id);
    formDataOport.append("idTipoDocumento", idTipoDocumento);
    formDataOport.append("_token", CSRF_TOKEN);
    formDataOport.append("_method", "POST");
    // formDataOport.append("fileOport", $("input[type=file, name='FileDoc']")[0].files[0]);
    formDataOport.append("fileOport", $("input[name='FileDoc']")[0].files[0]);
});
// ------FUNCIONES---/

window.onload = () => {
    window.addEventListener("storage", function(event) {
        if (event.key === "candyCRM") {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: "Tripa Modificada" });
            tableDocsOport.draw();
        }
    })
};

function tblDocsOport() {
    var groupColumn = 0;
    
    let customers = ["1", "4", "8", "14", "17"];
    let cotizadores = ["1", "4", "8", "17", "20"];
    let vendedores = ["2", "3", "4", "14", "8", "20"];

    tableDocsOport = $("#tblDocs").DataTable({
        // orderFixed: {
        //     pre: [0, 'asc'],
        //     post: [1, 'asc']
        // },
        destroy: true,
        processing: true,
        pageLength: 150,
        // stateSave: true,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        // colReorder: true,
        bLengthChange: true,
        autoWidth: true,
        order: [
            [14, "asc"],
            [12, "asc"],
            [11, "asc"],
            [13, "desc"],
        ],
        columnDefs: [
            {
                className: "dt-center",
                targets: [4, 5, 8, 9, 10]
            }, {
                visible: false,
                targets: [0, 1, 2, 6, 7, 8, 9, 11, 12, 13, 14]
            }, {
                targets: [4, 7],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
            },
            { orderable: false, targets: [0, 1, 2, 3, 4, 5, 7, 8, 9, 10] },
        ],
        ajax: {
            url: serve + "api/oportunidades/docs",
            type: "GET",
            dataType: "json",
            data: {
                idOportunidad: id
            }
        },
        columns: [
            {
                data: "idTipoDocumento", // 0
                name: "idTipoDocumento",
                title: "#"
            },
            {
                data: "idDocumento", // 1
                name: "idDocumento",
                title: "id Documento"
            },
            {
                data: "descTipoDocumento", // 2
                name: "descTipoDocumento",
                title: "Tipo Documento"
            },
            {
                data: "descDocumento", // 3
                name: "descDocumento",
                render: function (data, type, row) {
                    if (row.idTipoDocumento == 13) {
                        if (row.inputRol == 1 || row.inputRol == 8 || row.user == 12520) {
                            if (row.tipoTripa == 4) {
                                return data;
                            } else {
                                return ('<div class="row mb-1"><div style="width: 80%"><input id="descDocOld_' + row.idDocumento + '" type="hidden" value="' + data + '" /><input id="descDoc_' + row.idDocumento + '" type="text" class="form-control" onkeyup="mostrarBtnSave(' + row.idDocumento + ')" style="border: none; height: 25px !important" value="' + data + '" /></div><div style="width: 20%; text-align: center"><i id="saveDoc_' + row.idDocumento + '" class="fas fa-save cursor-pointer mt-1" style="font-size: 20px; display: none" onclick="actualizarNombreDocumento(' + row.idDocumento + ')"></i></div></div>');
                            }
                        } else {
                            return data;
                        }
                    } else {
                        return data;
                    }
                },
                title: "Documento",
                className: 'pl-6',
            },
            {
                data: "fecRegistro", // 4
                name: "fecRegistro",
                title: "Fecha Registro"
            },
            {
                data: "Login", // 5
                name: "Login",
                title: "Usuario Registro"
            },
            {
                data: "Nombre", // 6
                name: "Nombre",
                title: "Autoriza",
            },
            {
                data: "fecAutoriza", // 7
                name: "fecAutoriza",
                title: "Fecha Autoriza",
            },
            {
                data: "NoDocs", // 8
                name: "NoDocs",
                title: "dfsd"
            },
            {
                data: "NoDocs", // 9
                name: "NoDocs",
                title: "# Docs"
            },
            {
                data: "idDocumento", // 10
                render: function (data, type, row) {
                    visible_a = ''
                    visible_ac = ''
                    visible_e = ''
                    visible_l = ''
                    visible_r = 'hidden'
                    visible_p = ''

                    visible_mtr = 'hidden'
                    
                    visible_v = ''
                    visible_t = ''
                    visible_all = ''
                    titulo_r = ''
                    onclick_r = ''
                    onclick_p = ''
                    img_src_r = 'src=""'
                    img_src = 'src=""'
                    titulo = ''
                    check = '';
                    checkc = '';
                    cursor_pointer = 'cursor-pointer'
                    cursor_pointer_r = 'cursor-pointer'

                    if (row.idTipoDocumento == 1) {
                        return ('<a class="fas fa-download icondocsdown" onclick="visorDocumentos(' + data + ', ' + row.inputRol + ')" ></a>' +
                            '<i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocs(' + data + ')"></i>');
                    } else if (row.idTipoDocumento == 12) {
                        return ('<a class="fas fa-eye icondocsdown" onclick="visorDocumentos(' + data + ', ' + row.inputRol + ')" ></a>' +
                            '<i class="fas fa-trash-alt icondocsdelete" onclick="modal_deleteDocs(' + data + ')"></i>');
                    } else if (row.idTipoDocumento == 13) {

                        if (row.inputRol == 8 || row.inputRol == 1 || row.inputRol == 10 || row.inputRol == 7 || row.inputRol == 3 || row.inputRol == 2) {

                            // 1 = Producción
                            // 2 = Autorizado S/Cambios
                            // 3 = Por Autorizar
                            // 4 = Rechazado
                            // 5 = Autorizado C/Cambios

                            if (row.oportunidad_cancelada == 1) {
                                visible_a = 'hidden'
                                visible_e = 'hidden'
                                visible_l = 'hidden'
                                visible_p = 'hidden'
                                visible_r = 'hidden'
                                visible_v = ''
                                visible_t = 'hidden'
                                visible_ac = 'hidden';
                                // visible_all = 'hidden'
                            } else {
                                if (row.tipoTripa == 2) {
                                    check = 'checked';
                                    visible_p = ''
                                    img_src_r = 'src=""'
                                    visible_r = 'hidden'
                                    titulo_r = ''
                                    onclick_r = ''
                                    visible_e = 'hidden'
                                    visible_l = 'hidden'
                                    check_p = '';
                                    visible_a = '';
                                    visible_ac = 'hidden';
                                    img_src = 'src="' + serve + 'img/produccion.png" width="20px"'
                                    onclick_p = 'onclick="visorDocumentos(' + data + ', ' + row.tipoTripa + ')"'
                                    titulo = 'A Producción'
                                } else if (row.tipoTripa == 3) {
                                    check = '';
                                    visible_p = 'hidden'
                                    img_src_r = 'src="' + serve + 'img/cerrar.png" width="15px"'
                                    visible_r = ''
                                    visible_a = 'hidden';
                                    visible_ac = 'hidden';
                                    titulo_r = 'Rechazar'
                                    onclick_r = 'onclick="rechazarDocumento(' + data + ')"'
                                } else if (row.tipoTripa == 1) {
                                    check_p = 'checked';
                                    visible_a = 'hidden'
                                    visible_e = 'hidden'
                                    visible_l = 'hidden'
                                    visible_ac = 'hidden'
                                    img_src = 'src="' + serve + 'img/no_produccion.png" width="20px"'
                                    titulo = 'Rechazar Producción'
                                    onclick_p = 'onclick="produccionTripa(' + data + ', ' + row.tipoTripa + ')"'
                                } else if (row.tipoTripa == 4) {
                                    img_src_r = 'src="' + serve + 'img/prohibido.png" width="15px"'
                                    titulo_r = 'Rechazado'
                                    visible_a = 'hidden'
                                    visible_e = 'hidden'
                                    visible_l = 'hidden'
                                    visible_p = 'hidden'
                                    onclick_r = ''
                                    onclick_p = ''

                                    // Ver detalles de motivo de rechazo
                                    visible_mtr = ''

                                    cursor_pointer_r = ''
                                    visible_ac = 'hidden';
                                } else if (row.tipoTripa == 5) {
                                    checkc = 'checked';
                                    visible_a = 'hidden';
                                    visible_p = ''
                                    img_src_r = 'src=""'
                                    visible_r = 'hidden'
                                    titulo_r = ''
                                    onclick_r = ''
                                    onclick_p = ''
                                    visible_e = 'hidden'
                                    visible_l = 'hidden'
                                    visible_p = 'hidden'
                                    check_p = '';
                                    img_src = 'src="' + serve + 'img/produccion.png" width="20px"'
                                    titulo = 'A Producción'
                                } else {
                                    visible_a = 'hidden'
                                    visible_e = 'hidden'
                                    visible_l = 'hidden'
                                    visible_p = 'hidden'
                                    visible_r = 'hidden'
                                    visible_v = 'hidden'
                                    visible_t = 'hidden'
                                    visible_all = 'hidden'
                                }
                            }

                            return (
                                '<div class="row mb-1"' + visible_all + '>' +
                                '<div class="col-2 ' + cursor_pointer_r + '" title="' + titulo_r + '"' + visible_r + '>' +
                                '<a style="color: #F8696B"' + onclick_r + '><img ' + img_src_r + '></a>' +
                                '</div>' +
                                '<div class="col-2 cursor-pointer" title="' + titulo + '"' + visible_p + '>' +
                                // '<a class="' + cursor_pointer + '" onclick="produccionTripa(' + data + ', ' + row.tipoTripa + ')" ><img ' + img_src + '></a>' +
                                '<a class="' + cursor_pointer + '" ' + onclick_p + ' ><img ' + img_src + '></a>' +
                                '</div>' +
                                '<div class="custom-switch col-2 cursor-pointer" title="Autorizar S/Cambios"' + visible_a + '>' +
                                '<input  id="valido_' + data + '" type="checkbox"' + check + ' class="custom-control-input cursor-pointer">' +
                                '<label onclick="validarTripa(' + data + ', ' + row.tipoTripa + ')" class="custom-control-label cursor-pointer"></label>' +
                                '</div>' +
                                '<div class="custom-switch col-2 cursor-pointer" title="Autorizar C/Cambios"' + visible_ac + '>' +
                                '<input  id="validoc_' + data + '" type="checkbox"' + checkc + ' class="custom-control-input cursor-pointer">' +
                                '<label onclick="validarTripaC(' + data + ', ' + row.tipoTripa + ')" class="custom-control-label cursor-pointer"></label>' +
                                '</div>' +
                                '<div class="col-2"' + visible_e + '>' +
                                '<a class="' + cursor_pointer + '" title="Editar" style="color: #000" onclick="editDocument(' + data + ')" ><img src="' + serve + 'img/lapiz.png" width="20px"></a>' +
                                '</div>' +
                                '<div class="col-2"' + visible_l + '>' +
                                '<a class="' + cursor_pointer + '" title="Copiar Liga" style="color: #000" onclick="copiarLigaTripa(' + data + ')" ><img src="' + serve + 'img/enlace.png" width="20px"></a>' +
                                '</div>' +
                                '<div class="col-2"' + visible_v + '>' +
                                '<a class="' + cursor_pointer + '" title="Visualizar" onclick="visorDocumentos(' + data + ', ' + row.inputRol + ')" ><img src="' + serve + 'img/ojo.png" width="25px"></a>' +
                                '</div>' +
                                '<div class="col-2"' + visible_t + '>' +
                                '<a class="' + cursor_pointer + '" title="Eliminar" onclick="modal_deleteDocs(' + data + ')"><img src="' + serve + 'img/eliminar.png" width="20px"></a>' +
                                // '<i class="icondocsdelete" title="Eliminar" onclick="modal_deleteDocs(' + data + ')"></i>' +
                                '</div>' +

                                '<div class="col-2"' + visible_mtr + '>' +
                                '<a class="' + cursor_pointer + '" title="Ver motivo rechazo" onclick="showMotiveRejected(' + data + ')"><span class="solar--notes-bold"></span></a>' +
                                '</div>' +
                                
                                '</div>');
                        } else if ((row.inputRol == 2 || row.inputRol == 10) && (row.user == 12454 || row.user == 2149 || row.user == 2193)) {
                            let fAcctions = '<div class="row mb-1"><div class="col-2"><a class="' + cursor_pointer + '" title="Visualizar" onclick="visorDocumentos(' + data + ', ' + row.inputRol + ')" ><img src="' + serve + 'img/ojo.png" width="25px"></a></div>' +
                                '<div class="col-2"><a class="' + cursor_pointer + '" title="Eliminar" onclick="modal_deleteDocs(' + data + ')"><img src="' + serve + 'img/eliminar.png" width="20px"></a></div>';

                            if (row.user == 12454 && row.tipoTripa == 3) {
                                fAcctions += '<div class="col-2"><a class="' + cursor_pointer + '" title="Copiar Liga" style="color: #000" onclick="copiarLigaTripa(' + data + ')" ><img src="' + serve + 'img/enlace.png" width="20px"></a></div>';
                            }

                            if (row.usuario_revisa == row.user && (row.user == 12454 || row.user == 2149 || row.user == 2193) && row.tipoTripa == 3) {
                                fAcctions += '<div class="col-2"><a class="' + cursor_pointer + '" title="Editar" style="color: #000" onclick="editDocument(' + data + ')" ><img src="' + serve + 'img/lapiz.png" width="20px"></a></div>';
                            }

                            fAcctions += '</div>';

                            return (fAcctions);
                        } else {
                            return ('<a class="' + cursor_pointer + '" title="Visualizar" onclick="visorDocumentos(' + data + ', ' + row.inputRol + ')" ><img src="' + serve + 'img/ojo.png" width="25px"></a>' +
                                '<a class="' + cursor_pointer + '" title="Eliminar" onclick="modal_deleteDocs(' + data + ')"><img src="' + serve + 'img/eliminar.png" width="20px"></a>');
                        }
                    } else {
                        let mainStr = '<a class="' + cursor_pointer + '" title="Visualizar" onclick="visorDocumentos(' + data + ', ' + row.inputRol + ')" ><img src="' + serve + 'img/ojo.png" width="25px"></a>';

                        if ((row.inputRol == 3 && (customers.includes(row.idTipoDocumento))) || ((row.inputRol == 10 || row.inputRol == 2) && (cotizadores.includes(row.idTipoDocumento))) || ((vendedores.includes(row.idTipoDocumento)) && row.inputRol == 4) || row.inputRol == 8 || row.inputRol == 1 || (row.user == 12515 && row.idUsuarioReg == row.user)
                        ) {
                            if ((row.idTipoDocumento != 20 && (row.inputRol != 3 || row.inputRol != 4 || row.inputRol != 2)) || (row.inputRol == 1 || row.inputRol == 8)) {
                                mainStr += '<a class="' + cursor_pointer + '" title="Eliminar" onclick="modal_deleteDocs(' + data + ')"><img src="' + serve + 'img/eliminar.png" width="20px"></a>';
                            }
                        }

                        return mainStr;
                    }
                },
                name: "idDocumento",
                title: "Acción",
                width: '250px'
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
            dataSrc: ['descTipoDocumento', 'tipoTripa'],
            startRender: function (rows, group, level) {
                collapsed = !!collapsedGroups[all];
                var all;
                noelmentos = rows.data().pluck("NoDocs");
                tripas = rows.data().pluck("tipoTripa");
                contador = noelmentos[0];
                firstRowData = rows.data().pluck("idTipoDocumento");


                if (level === 0) {
                    top = group;
                    parent = top + group;
                    all = parent;
                    if (!!collapsedGroups[top]) {
                        return;
                    }
                } else if (level === 1) {
                    top = group;
                    parent = top + parent;
                    all = parent;
                    if (!!collapsedGroups[top]) {
                        return;
                    }
                } else {
                    if (!!collapsedGroups[parent]) {
                        return;
                    }
                    all = top + parent + group;
                }

                var asignado = noelmentos[0];

                var validos = rows.data().filter(function (data, index) {
                    return data['tipoTripa'];
                }).pluck('tipoTripa').count();

                var a_produccion = rows.data().filter(function (data, index) {
                    return data['produccion'];
                }).pluck('produccion').count();

                collapsed = !!collapsedGroups[all];

                rows.nodes().each(function (r) {
                    r.style.display = collapsed ? 'none' : '';
                });

                let rowcount = 0;

                rowname = (group === 'No group' ? 'Sin Grupo' : group);
                rowcount = (group === 'No group' ? rows.count() - 1 : rows.count());
                pendientes = (asignado > 0 ? asignado + ' SIN ASIGNAR' : '');

                if (collapsed) {
                    clases = 'fas fa-chevron-circle-up mr-2';
                } else {
                    clases = 'fas fa-chevron-circle-down mr-2';
                }

                

                if (noelmentos[0] >= 1) {
                    if (level == 0) {
                        if ((rows.data()[0].inputRol == 3 && (!customers.includes(rows.data()[0].idTipoDocumento))) || ((rows.data()[0].inputRol == 10 || rows.data()[0].inputRol == 2) && !(cotizadores.includes(rows.data()[0].idTipoDocumento))) || (!(vendedores.includes(rows.data()[0].idTipoDocumento)) && rows.data()[0].inputRol == 4) || rows.data()[0].idTipoDocumento == 13) {
                            elem = $("<tr id='tr" + group + "' />").append('<td colspan="4" title="'+rows.data()[0].descripcionTooltip+'" class="rowcolap pl-3 text-bold bg-gray-l0" style="border-radius: 10px 0 0 10px"><i id="ord' + group + '" class="' + clases + '" style="color: #6C757D !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                        } else if (rows.data()[0].idTipoDocumento == 16) {
                            if (rows.data()[0].inputRol == 1013 || rows.data()[0].inputRol == 1 || rows.data()[0].inputRol == 8) {
                                elem = $("<tr id='tr" + group + "' />").append('<td colspan="3" title="'+rows.data()[0].descripcionTooltip+'" class="rowcolap pl-3 text-bold bg-gray-l0" style="border-radius: 10px 0 0 10px"><i id="ord' + group + '" class="' + clases + '" style="color: #6C757D !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td><td  class="rowcolap text-center bg-select-right-l0"><i class="'+ ((rows.data()[0].oportEstatus >= 8 && rows.data()[0].idTipoDocumento == 3 && rows.data()[0].inputRol != 4) || (rows.data()[0].idTipoDocumento == 20 && (rows.data()[0].inputRol == 3 || rows.data()[0].inputRol == 2 || rows.data()[0].inputRol == 4)) ? 'd-none' : '') +' fas fa-upload icondocsup" onclick="modal_upDocs(' + firstRowData[0] + ')" data-id="' + firstRowData[0] + '"></i></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                            } else {
                                elem = $("<tr id='tr" + group + "' />").append('<td colspan="4" title="'+rows.data()[0].descripcionTooltip+'" class="rowcolap pl-3 text-bold bg-gray-l0" style="border-radius: 10px 0 0 10px"><i id="ord' + group + '" class="' + clases + '" style="color: #6C757D !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                            }
                        } else {
                            elem = $("<tr id='tr" + group + "' />").append('<td colspan="3" title="'+rows.data()[0].descripcionTooltip+'" class="rowcolap pl-3 text-bold bg-gray-l0" style="border-radius: 10px 0 0 10px"><i id="ord' + group + '" class="' + clases + '" style="color: #6C757D !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td><td  class="rowcolap text-center bg-select-right-l0"><i class="'+ ((rows.data()[0].oportEstatus >= 8 && rows.data()[0].idTipoDocumento == 3 && rows.data()[0].inputRol != 4) || (rows.data()[0].idTipoDocumento == 20 && (rows.data()[0].inputRol == 3 || rows.data()[0].inputRol == 2 || rows.data()[0].inputRol == 4)) ? 'd-none' : '') +' fas fa-upload icondocsup" onclick="modal_upDocs(' + firstRowData[0] + ')" data-id="' + firstRowData[0] + '"></i></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                        }

                    } else if (level == 1) {
                        if (rows.data()[0].idTipoDocumento == 13) {
                            if (rows.data()[0].tipoTripa == 1) {
                                cat = 'Producción'
                            } else if (rows.data()[0].tipoTripa == 2) {
                                cat = 'Autorizado S/Cambios'
                            } else if (rows.data()[0].tipoTripa == 3) {
                                cat = 'Por Autorizar'
                            } else if (rows.data()[0].tipoTripa == 4) {
                                cat = 'Rechazado'
                            } else if (rows.data()[0].tipoTripa == 5) {
                                cat = 'Autorizado C/Cambios'
                            } else {
                                cat = ''
                            }
                            elem = $('<tr id="tr' + group + '" class="pl-5" />').append('<td colspan="4" title="'+rows.data()[0].descripcionTooltip+'" class="rowcolap text-bold bg-select bg-gray-l1 pl-5"><i id="ord' + group + '" class="' + clases + '" style="color: #A7AAAC !important"></i>' + cat + '<span id="totalR' + cat + '">' + ' (' + rowcount + ')' + '</span></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                        } else {
                            elem = '';
                        }
                        // } else if (level == 3) {
                        //     if (rows.data()[0].idTipoDocumento == 13) {
                        //         if (rows.data()[0].produccion == 0 && rows.data()[0].Valido == 0 && rows.data()[0].rechazado == 0) {
                        //             cat = 'Por Autorizar'
                        //         } else if (rows.data()[0].produccion == 0 && rows.data()[0].Valido == 1 && rows.data()[0].rechazado == 0) {
                        //             cat = 'Autorizado'
                        //         } else if (rows.data()[0].produccion == 1 && rows.data()[0].Valido == 1 && rows.data()[0].rechazado == 0) {
                        //             cat = 'Producción'
                        //         } else if (rows.data()[0].produccion == 0 && rows.data()[0].Valido == 0 && rows.data()[0].rechazado == 1) {
                        //             cat = 'Rechazado'
                        //         } else {
                        //             cat = ''
                        //         }
                        //         elem = $('<tr id="tr' + group + '" class="pl-5" />').append('<td colspan="4" class="rowcolap text-bold bg-select bg-gray-l1 pl-5"><i id="ord' + group + '" class="' + clases + '" style="color: #A7AAAC !important"></i>' + cat + '<span id="totalR' + cat + '">' + ' (' + rowcount + ')' + '</span></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                        //     } else {
                        //         elem = '';
                        //     }

                    }

                } else {

                    if (level == 0) {
                        if (asignado === 0) {
                            elem = '';
                        } if ((rows.data()[0].inputRol == 3 && (!customers.includes(rows.data()[0].idTipoDocumento))) || ((rows.data()[0].inputRol == 10 || rows.data()[0].inputRol == 2) && !(cotizadores.includes(rows.data()[0].idTipoDocumento))) || (!(vendedores.includes(rows.data()[0].idTipoDocumento)) && rows.data()[0].inputRol == 4) || rows.data()[0].idTipoDocumento == 13) {
                            elem = $("<tr id='tr" + group + "' />").append('<td colspan="4" title="'+rows.data()[0].descripcionTooltip+'"  class="rowcolap pl-3 text-bold bg-gray-l0" style="border-radius: 10px 0 0 10px"><i id="ord' + group + '" class="' + clases + '" style="color: #6C757D !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                        } else {
                            elem = $("<tr id='tr" + group + "' />").append('<td colspan="3" title="'+rows.data()[0].descripcionTooltip+'"  class="rowcolap pl-3 text-bold bg-gray-l0" style="border-radius: 10px 0 0 10px"><i id="ord' + group + '" class="' + clases + '"></i>' + group + '<span id="totalR' + group + '" class="text-white">' + ' (' + asignado + ')' + '</span></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                        }
                    } else {
                        elem = '';
                    }

                    if ((rows.data()[0].inputRol == 3 && (!customers.includes(rows.data()[0].idTipoDocumento))) || ((rows.data()[0].inputRol == 10 || rows.data()[0].inputRol == 2) && !(cotizadores.includes(rows.data()[0].idTipoDocumento))) || (!(vendedores.includes(rows.data()[0].idTipoDocumento)) && rows.data()[0].inputRol == 4) || rows.data()[0].idTipoDocumento == 13) {
                        elem1 = $("<tr/>").append('<td colspan="3"  class="rowcolap pl-3 text-bold bg-gray-l0" title="'+rows.data()[0].descripcionTooltip+'" style="border-radius: 10px 0 0 10px"><i class="fas fa-minus-circle mr-2" style="color: #929294 !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td><td  class="rowcolap text-center bg-select-right-l0"></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                    } else {
                        // PAra admins aqui cae
                        elem1 = $("<tr/>").append('<td colspan="3"  class="rowcolap pl-3 text-bold bg-gray-l0"  title="'+rows.data()[0].descripcionTooltip+'" style="border-radius: 10px 0 0 10px"><i class="fas fa-minus-circle mr-2" style="color: #929294 !important"></i>' + group + '<span id="totalR' + group + '">' + ' (' + asignado + ')' + '</span></td><td  class="rowcolap text-center bg-select-right-l0"><i class="fas fa-upload icondocsup '+ ((rows.data()[0].oportEstatus >= 8 && rows.data()[0].idTipoDocumento == 3 && rows.data()[0].inputRol != 4) || (rows.data()[0].idTipoDocumento == 20 && (rows.data()[0].inputRol == 3 || rows.data()[0].inputRol == 2 || rows.data()[0].inputRol == 4)) ? 'd-none' : '') +'" onclick="modal_upDocs(' + firstRowData[0] + ')" data-id="' + firstRowData[0] + '"></i></td>').attr("data-name", group.replace(" ", "")).toggleClass("collapsed", collapsed);
                    }


                }

                if (rows.data()[0].idTipoDocumento == 13) {
                    $(rows.nodes()).hide(); // todos  colapsados
                    // if (rows.data()[0].tipoTripa == 4) {
                    //     $(rows.nodes()).hide(); // todos  colapsados
                    // } else {
                    //     $(rows.nodes()).show(); // todos  expandidos
                    // }
                } else {
                    $(rows.nodes()).hide(); // todos  colapsados
                }

                if (elem != '') {
                    elem.on('click', function (e) {
                        e.preventDefault();

                        collapsedGroups[all] = !collapsedGroups[all];

                        var ord = document.getElementById('ord' + group);
                        var tr = document.getElementById('tr' + group).getElementsByTagName('td');

                        if (level == 0) {

                            if ($(rows.nodes()).is(":hidden")) {
                                $(rows.nodes()).show();

                                ord.classList.remove('fa-chevron-circle-down');
                                ord.classList.add('fa-chevron-circle-up');
                            } else {
                                $(rows.nodes()).hide();

                                ord.classList.add('fa-chevron-circle-down');
                                ord.classList.remove('fa-chevron-circle-up');
                            }
                            collapsedGroups[all] = !collapsedGroups[all];
                        } else {
                            if ($(rows.nodes()).is(":hidden")) {
                                $(rows.nodes()).show();

                                ord.classList.remove('fa-chevron-circle-down');
                                ord.classList.add('fa-chevron-circle-up');
                                for (var i = 1; i < tr.length; i++) {
                                    tr[i].classList.add('bg-level1');
                                }
                            } else {
                                $(rows.nodes()).hide();

                                ord.classList.add('fa-chevron-circle-down');
                                ord.classList.remove('fa-chevron-circle-up');
                                for (var i = 1; i < tr.length; i++) {
                                    tr[i].classList.add('bg-level1');
                                }
                            }
                            collapsedGroups[all] = !collapsedGroups[all];

                        }
                    });

                    if (noelmentos[0] >= 1) {
                        return elem;
                    } else {
                        return elem1;
                    }
                }
            }
        },
        language: {
            url: "../../js/spanish-DT.json"
        },
        dom: '<"top">rt<"bottom"><"clear">',
        select: true,

        initComplete: function (settings, json) {
            loaderOut();
        }
    });
}

function modal_upDocs(data) {
    OpenModalDoc();
    $(".lblDocsUp").text("");
    $("#idTipoDocumento").val(data);
}

function modal_downDocs(data) { // console.log(data);
}

function modal_deleteDocs(data) {
    var idDocumentodelete = data;
    deleteDoc(idDocumentodelete);
}

function modal_mailDocs(data) {
    OpenModalMailDoc();
    $(".lblDocsUp_").text("");
    $("#idTipoDocumento_").val(data);
}

function OpenModalDoc() { // $(".modPapel :input").val('');
    $("#mod_UpDocs").modal("show");
    // $('.sltPapel').val(0); //lo setea
    // $('.sltPapel').trigger('change'); //lo setea
}

function OpenModalMailDoc() {
    $("#mod_mailDocs").modal("show");
}

function addDocumento() {
    $.ajax({
        url: serve + "api/upDocsOportunidades",
        async: false,
        type: "POST",
        data: formDataOport,
        processData: false,
        contentType: false,
        success: function (data) {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: "Archivo Guardado" });
            $("#mod_UpDocs").modal("hide");
            loaderOut();
            $("#mod_UpDocs :input").val("");
            // tableDocsOport.ajax.reload();
            generarListaDocumentos();
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: "Ocurrió un error, vuelve a intentarlo" });
        }
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
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "deleteDocsOportunidades/",

                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idDocumentodelete: idDocumentodelete,
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

                    Msg.fire({ title: "Documento Eliminado" });
                    $("#mod_UpDocs").modal("hide");
                    loaderOut();
                    $("#mod_UpDocs :input").val("");
                    // tableDocsOport.ajax.reload();
                    generarListaDocumentos();
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error, vuelve a intentarlo" });
                }
            });
        }
    });
}

// var message = "¡No se permite descargar el archivo!";

function disableRightclick(evt) {
    if (evt.button == 2) { // alert(message);
        evt.preventDefault();
        // Swal.fire(message);
        // return false;
    }
}

function loadpdf() {
    document.getElementById("frameVisorDocumentos").style.display = "block";
    document.getElementById("frameVisorDocumentos").style.visibility = "visible";

    document.getElementById("nomenu").style.display = "block";
    document.getElementById("nomenu").style.visibility = "visible";

    document.getElementById("loading").style.visibility = "hidden";
}

function loadfunctions() {
    window.addEventListener("keyup", keydetect, false);
}

window.addEventListener("storage", function(event) {
    if (event.key === "candyCRM") {

        console.log((event.newValue));
    }
})

// function keydetect(e) {
//     if (e.keyCode == "44") 
//         copytoclipboard("No esta permitido copiar y pegar este PDF sin permiso del autor. Disculpe las molestias.");



// }

// function copytoclipboard(str) {
//     try {
//         if (navigator.userAgent.toLowerCase().indexOf("firefox") < 0 && navigator.userAgent.toLowerCase().indexOf("chrome") < 0 && navigator.userAgent.toLowerCase().indexOf("webkit") < 0) 
//             window.clipboardData.setData("Text", str);
//          else {

//             try {

//                 if (navigator.userAgent.toLowerCase().indexOf("firefox") < 0) {

//                     var textArea = document.createElement("textarea");

//                     textArea.style.position = "fixed";
//                     textArea.style.top = 0;
//                     textArea.style.left = 0;
//                     textArea.style.width = "2em";
//                     textArea.style.height = "2em";
//                     textArea.style.padding = 0;
//                     textArea.style.border = "none";
//                     textArea.style.outline = "none";
//                     textArea.style.boxShadow = "none";
//                     textArea.style.background = "transparent";

//                     textArea.value = str;

//                     document.body.appendChild(textArea);
//                     textArea.focus();
//                     textArea.select();

//                     document.execCommand('copy');
//                     document.body.removeChild(textArea);

//                 } else 
//                     alert(str);



//             } catch (ex) {}
//         }
//     } catch (e) {}
// }

const visorPDF = document.getElementById("frameVisorDocumentos");

// var msg = "¡El botón derecho está desactivado para este sitio !";
// function disableIE() {
//     if (document.all) { alert(msg); return false; }
// }

// function disableNS(e) {
//     if (document.layers || (document.getElementById && !document.all)) {
//         if (e.which == 2 || e.which == 3) { alert(msg); return false; }
//     }
// }

// if (document.layers) {
//     document.captureEvents(Event.MOUSEDOWN); document.onmousedown = disableNS;
// } else {
//     document.onmouseup = disableNS; document.oncontextmenu = disableIE;
// }

// document.oncontextmenu = ev => {
//     ev.preventDefault();
//     console.log("Prevented to open menu!");
// }

function replaceImagesWithBase64(html, attachments) {
    // Crear un DOMParser para manipular el HTML
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');

    // Buscar todas las imágenes en el HTML
    const images = doc.querySelectorAll('img');

    images.forEach((img) => {
        const src = img.getAttribute('src');

        // Verificar si el src contiene "cid:"
        if (src && src.startsWith('cid:')) {
            const cid = src.replace('cid:', '');

            // Buscar el adjunto correspondiente en la lista
            const attachment = attachments.find(att => att.fileName === cid.split('@')[0]);

            if (attachment) {
                // Generar la URL en base64
                const base64Image = `data:${attachment.mimeType};base64,${attachment.content}`;

                // Reemplazar el src con la imagen en base64
                img.setAttribute('src', base64Image);
            }
        }
    });

    // Devolver el HTML modificado
    return doc.body.innerHTML;
}

/**
 * Función para abrir el visor de documentos.
 *
 * Cuando el segundo parámetro es 2 (botón "Enviar a producción"), primero se
 * valida que el checklist de la oportunidad ya esté finalizado; si no lo
 * está, se muestra el modal del checklist antes de continuar con el visor.
 *
 * @param {number} idDocumento
 * @param {number|string} rol
 * @param {number|string} tp // tripa prod
 * @return void
 */
function visorDocumentos(idDocumento, rol, tp = 1) {
    if (tp == 1) {
        _visorDocumentosOriginal(idDocumento, rol);
        return;
    }

    let idOportunidad = $('#idOportunidad').val();

    $.ajax({
        url: serve + "obtener-checklist-op",
        type: "GET",
        data: {
            idDocumento: idDocumento,
            idOportunidad: idOportunidad
        },
        success: function(resp) {
            if (resp.response) {
                if (resp.bloqueado) {
                    Swal.fire('Cliente bloqueado', resp.message || 'El cliente de la oportunidad está bloqueado.', 'warning');
                } else if (resp.finalizado) {
                    _visorDocumentosOriginal(idDocumento, rol);
                } else {
                    abrirModalChecklistOP(idDocumento, idOportunidad, resp, function() {
                        _visorDocumentosOriginal(idDocumento, rol);
                    });
                }
            } else {
                Swal.fire('Error', resp.message || 'Ocurrió un error al obtener el checklist.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al obtener el checklist.', 'error');
        }
    });
}

function _visorDocumentosOriginal(idDocumento, rol) {
    loaderModalON();

    // 1. Slidebar Button: Use nosidebar=1 to hide Slidebar button.
    // 2. PageAction Buttons: Use nopageaction=1 to hide PageAction buttons.
    // 3. ZoomArea Buttons: Use nozoom=1 to hide ZoomArea buttons.
    // 4. FullScreen Button: Use nofullscreen=1 to hide FullScreen button
    // 5. OpenFile Button: Use noopen=1 to hide OpenFile button.
    // 6. Print Button: Use noprint=1 to hide Print button.
    // 7. Download Button: Use nodownload=1 to hide Download button.
    // 8. ViewBookmark Button: Use noviewbookmark=1 to hide ViewBookmark Button.
    // 9. Full Toolbar: Use notoolbar=1 to hide Full Toolbar.

    // 10. Find Button: Use nofind=1 to hide Find button. (New in Reader 2)
    // 11. More Tools Button: Use nomoretools=1 to hide More Tools Button. (New in Reader 2)

    loading = document.getElementById('loading');
    var detector = new MobileDetect(window.navigator.userAgent);
    $.ajax({
        url: serve + "getDocumentoOport",
        type: "GET",
        data: {
            _method: "GET",
            idDocumento: idDocumento
        },
        success: function (data) {
            // try {
            extension = data.Extension;
            byteCharacters = atob(data.Documento);
            descDocumento = data.descDocumento;
            var dateToday = new Date();
            if (extension.match(/^.*vnd.*$/)) {
                window.location.href = serve + "api/downDocsOportunidades/" + idDocumento;
                loaderModalOut();
            } else if (extension.match(/^.*stream$/)) {

                // Si la descripción del documento contiene ".dxf" solo lo descargará, no lo mostrará en el visor.
                if (descDocumento.toLowerCase().includes('.dxf')) {
                    window.location.href = serve + "api/downDocsOportunidades/" + idDocumento;
                    loaderModalOut();
                }

                $("#modal-visor-documentos").trigger("reset");
                $("#modal-visor-documentos").modal("show");
                $(".tituloVisorDocumentos").text(descDocumento);
                $("#idDocumento").val(idDocumento);
                $("#idOportunidadPdf").val(data.idOportunidad);
                $('#frameVisorCorreos')[0].style.display = 'block';
                $('#frameVisorDocumentos')[0].style.display = 'none';

                // axios.get('http://192.168.2.222/lectoremail/api/documento/' + data.idDocumento)
                // axios.get('/lectoremail/api/documento/' + data.idDocumento)
                axios.get('/crmlector/api/email/' + data.idDocumento)
                // axios.get('https://9hg6jpkv-8084.usw3.devtunnels.ms/api/email/' + data.idDocumento)
                    .then(function (response) {
                        var options = {
                            weekday: "long",
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                        };
                        fecha_doc = response.data.date;
                        fecha = new Date(fecha_doc);
                        fecha_local = new Date(fecha.getTime() + (fecha.getTimezoneOffset() * 60000)).toLocaleDateString("es-MX", options);
                        $('.msg-info').show();
                        $('.msg-oport .msg-from').html(response.data.from.email);

                        if (response.data.to.length == 0) {
                            $('.msg-oport .msg-to').html('');
                        } else {
                            $('.msg-oport .msg-to').html(response.data.to[0].email.replace('<', '(').replace('>', ')'));
                        }
                        if (response.data.cc.length == 0) {
                            $('.msg-oport .msg-cc').html('');
                        } else {
                            $('.msg-oport .msg-cc').html(response.data.cc[0].email.replace('<', '(').replace('>', ')'));
                        }
                        $('.msg-oport .msg-date').html(fecha_local);
                        $('.msg-oport .msg-subject').html(response.data.subject);
                        $('.msg-oport .msg-body').html(replaceImagesWithBase64(response.data.body, response.data.attachments));

                        $('.msg-oport .msg-attachment').html(jQuery.map(response.data.attachments, function (attachment, i) {
                            var file_reader = response.data.attachments[i];
                            // console.log(file_reader);
                            byteCharactersEml = atob(file_reader.content);
                            var byteNumbersEml = new Array(byteCharactersEml.length);
                            for (var i = 0; i < byteCharactersEml.length; i++) {
                                byteNumbersEml[i] = byteCharactersEml.charCodeAt(i);
                            }
                            var buffer = new Uint8Array(byteNumbersEml);
                            // var ext_1 = file_reader.fileName.slice(-5);
                            // var ext = ext_1.split('.');
                            mime_type = file_reader.mimeType;
                            // mime_type = getMimeType(ext[1]);

                            var fileUrl = URL.createObjectURL(new File([buffer], file_reader.fileName,
                                { type: mime_type ? mime_type : "application/vnd.ms-excel" }));
                            if (fileUrl != null) {
                                return '<i class="fas fa-paperclip text-md mr-1"></i><a href="' + fileUrl + '" target="_blank">' + file_reader.fileName + '</a>';
                            }
                        }).join('<br/>'));

                    })
                    .catch(function (error) {
                        $('.msg-info').hide();
                        // función para capturar el error
                        console.log(error);
                    })
                    .then(function () {
                        // función que siempre se ejecuta
                    });

                // $("#modal-visor-documentos").trigger("reset");
                // $("#modal-visor-documentos").modal("show");
                // $(".tituloVisorDocumentos").text(descDocumento);
                // $("#idDocumento").val(idDocumento);
                // $("#idOportunidadPdf").val(data.idOportunidad);

                // var byteNumbers = new Array(byteCharacters.length);
                // for (var i = 0; i < byteCharacters.length; i++) {
                //     byteNumbers[i] = byteCharacters.charCodeAt(i);
                // }
                // var byteArray = new Uint8Array(byteNumbers);
                // var blob = new Blob([byteArray], { type: extension });

                // var file_msg = new File([blob], descDocumento, { type: extension })

                // $('#frameVisorDocumentos')[0].style.display = 'none';
                // // $('#frameVisorOffice')[0].style.display = 'none';
                // $('#frameVisorCorreos')[0].style.display = 'block';
                // var selectedFile = file_msg;
                // if (!selectedFile) {
                //     $('.msg-info, .incorrect-type').hide();
                //     return;
                // }
                // if (selectedFile.name.indexOf('.msg') == -1) {
                //     $('.msg-info').hide();
                //     $('.incorrect-type').show();
                //     return;
                // }
                // $('.msg-oport .msg-file-name').html(selectedFile.name);
                // $('.incorrect-type').hide();

                // // read file...
                // var fileReader = new FileReader();
                // fileReader.onload = function (evt) {

                //     var buffer = evt.target.result;
                //     var msgReader = new MSGReader(buffer);
                //     var fileData = msgReader.getFileData();
                //     console.log(fileData);
                //     if (!fileData.error) {
                //         // $('.msg-oport .msg-from').html(formatEmail({ name: fileData.senderName, email: fileData.senderEmail }));
                //         $('.msg-oport .msg-from').html(getMsgFrom(fileData.headers));
                //         $('.msg-oport .msg-to').html(getMsgTo(fileData.headers));
                //         $('.msg-oport .msg-cc').html(getMsgCC(fileData.headers));
                //         // $('#msg_cc')[0].style.display = 'block';

                //         // $('.msg-oport .msg-to').html(jQuery.map(fileData.recipients, function (recipient, i) {
                //         //     return getMsgTo(recipient);
                //         // }).join('<br/>'));
                //         $('.msg-oport .msg-date').html(getMsgDate(fileData.headers));
                //         $('.msg-oport .msg-subject').html(fileData.subject);
                //         $('.msg-oport .msg-body').html(fileData.body);
                //         // $('.msg-oport .msg-body').html(
                //         //     fileData.body ? fileData.body.substring(0, Math.min(1000, fileData.body.length))
                //         //         + (fileData.body.length > 1000 ? '...' : '') : '');
                //         if (fileData.bodyHTML) {
                //             $('.msg-oport .msg-body-html').html(fileData.bodyHTML).closest('div.field-block').show();
                //         } else {
                //             // $('.msg-oport .msg-body-html').html(fileData.body).closest('div.field-block').show();
                //             $('.msg-oport .msg-body-html').closest('div.field-block').hide();
                //         }
                //         $('.msg-oport .msg-attachment').html(jQuery.map(fileData.attachments, function (attachment, i) {
                //             var file_reader = msgReader.getAttachment(i);
                //             var fileUrl = URL.createObjectURL(new File([file_reader.content], attachment.fileName,
                //                 { type: attachment.mimeType ? attachment.mimeType : "application/octet-stream" }));
                //             if (fileUrl != null) {
                //                 // return '<iframe src="http://docs.google.com/gview?url=' + fileUrl +'&embedded=true" style="width:500px; height:500px;" frameborder="0"></iframe>';
                //                 return '<i class="fas fa-paperclip text-md mr-1"></i><a href="' + fileUrl + '" target="_blank">' + attachment.fileName + '</a>';
                //             }
                //         }).join('<br/>'));
                //         $('.msg-info').show();

                //     } else {
                //         $('.msg-info').hide();
                //         $('.incorrect-type').show();
                //     }
                // };
                // fileReader.readAsArrayBuffer(selectedFile);
                // document.onmousedown = disableRightclick;
                // loaderModalOut();
            } else if (extension.match(/^.*rfc822$/)) {

                $("#modal-visor-documentos").trigger("reset");
                $("#modal-visor-documentos").modal("show");
                $(".tituloVisorDocumentos").text(descDocumento);
                $("#idDocumento").val(idDocumento);
                $("#idOportunidadPdf").val(data.idOportunidad);
                $('#frameVisorCorreos')[0].style.display = 'block';
                $('#frameVisorDocumentos')[0].style.display = 'none';

                // axios.get('http://192.168.2.222/lectoremail/api/documento/' + data.idDocumento)
                // axios.get('/lectoremail/api/documento/' + data.idDocumento)
                axios.get('/crmlector/api/email/' + data.idDocumento)
                    .then(function (response) {
                        var options = {
                            weekday: "long",
                            year: "numeric",
                            month: "long",
                            day: "numeric",
                        };
                        fecha_doc = response.data.date;
                        fecha = new Date(fecha_doc);
                        fecha_local = new Date(fecha.getTime() + (fecha.getTimezoneOffset() * 60000)).toLocaleDateString("es-MX", options);
                        $('.msg-info').show();
                        $('.msg-oport .msg-from').html(response.data.from[0].email.replace('<', '(').replace('>', ')'));
                        $('.msg-oport .msg-to').html(response.data.to.email.replace('<', '(').replace('>', ')'));
                        if (response.data.cc.length == 0) {
                            $('.msg-oport .msg-cc').html('');
                        } else {
                            $('.msg-oport .msg-cc').html(response.data.cc[0].email.replace('<', '(').replace('>', ')'));
                        }
                        $('.msg-oport .msg-date').html(fecha_local);
                        $('.msg-oport .msg-subject').html(response.data.subject);
                        $('.msg-oport .msg-body').html(response.data.body);
                        $('.msg-oport .msg-attachment').html(jQuery.map(response.data.attachments, function (attachment, i) {
                            var file_reader = response.data.attachments[i];
                            console.log(file_reader);
                            byteCharactersEml = atob(file_reader.content);
                            var byteNumbersEml = new Array(byteCharactersEml.length);
                            for (var i = 0; i < byteCharactersEml.length; i++) {
                                byteNumbersEml[i] = byteCharactersEml.charCodeAt(i);
                            }
                            var buffer = new Uint8Array(byteNumbersEml);
                            // var ext_1 = file_reader.filename.slice( -5);
                            // var ext = ext_1.split('.');
                            mime_type = file_reader.mimetype;
                            // mime_type = getMimeType(ext[1]);

                            var fileUrl = URL.createObjectURL(new File([buffer], file_reader.filename,
                                { type: mime_type ? mime_type : "application/vnd.ms-excel" }));
                            if (fileUrl != null) {
                                return '<i class="fas fa-paperclip text-md mr-1"></i><a href="' + fileUrl + '" target="_blank">' + file_reader.filename + '</a>';
                            }
                        }).join('<br/>'));

                    })
                    .catch(function (error) {
                        $('.msg-info').hide();
                        // función para capturar el error
                        console.log(error);
                    })
                    .then(function () {
                        // función que siempre se ejecuta
                    });

            } else {
                $("#modal-visor-documentos").modal("show");
                $(".tituloVisorDocumentos").text(descDocumento);
                $("#idDocumento").val(idDocumento);
                $("#idOportunidadPdf").val(data.idOportunidad);
                $('#frameVisorCorreos')[0].style.display = 'none';
                $('#frameVisorDocumentos')[0].style.display = 'block';
                $('#notaRechazo')[0].style.display = 'none';

                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray], { type: extension });

                blob = window.URL.createObjectURL(blob);

                descargarImageOport = document.getElementById('descargarImageOport');

                if (data.idTipoDocumento != 13) {
                    $('#isCancelledDoc')[0].style.display = 'none';
                    enviarCorreo = document.getElementById('enviarCorreo');
                    $('#btnAProduccion')[0].style.display = 'none';
                    if (data.idTipoDocumento == 1) {
                        $.ajax({
                            url: serve + 'documentosValidados',
                            type: 'GET',
                            data: {
                                _method: "GET",
                                idOportunidad: data.idOportunidad,
                            },
                            success: function (dataAu) {
                                // console.log(dataAu, data);
                                idRol = $('#idRol').val();
                                // visorPDF.src = blob + "#toolbar=1&nosidebar=1";

                                if (idRol == "1" || idRol == "8") {
                                    loading.style.visibility = "hidden";
                                }

                                if (dataAu.length > 0) {
                                    if ($('#Estatus').val() != 12 && $('#Estatus').val() != 10 && $('#Cancelada').val() != 1) {
                                        if (data.Autorizado === '0') {
                                            $('#autorizar')[0].disabled = false;
                                            $('#autorizarLabel')[0].textContent = 'Autorizar';
                                            $('#isCancelledDoc')[0].style.display = 'block';
                                            $("#fecAutoriza").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));
                                            $('#autorizarDiv')[0].style.display = 'block';
                                            $('#autorizar')[0].checked = false;
                                            enviarCorreo.style.display = 'none'
                                        } else {
                                            $('#autorizar')[0].disabled = true;
                                            $('#autorizarLabel')[0].textContent = 'Autorizar';
                                            $('#isCancelledDoc')[0].style.display = 'none';
                                            $('#autorizarDiv')[0].style.display = 'none';
                                            $('#autorizar')[0].checked = true;
                                            enviarCorreo.style.display = 'block'
                                        }
                                    } else {
                                        $('#autorizarDiv')[0].style.display = 'none';
                                    }
                                } else {
                                    $('#autorizarDiv')[0].style.display = 'none';
                                    enviarCorreo.style.display = 'none';
                                }

                                if (!(idRol == "1" || idRol == "8" || idRol == "4" || idRol == "9")) {
                                    $('#autorizarDiv')[0].style.display = 'none';
                                    enviarCorreo.style.display = 'none';
                                }

                            },
                        });
                    } else {
                        // visorPDF.src = "/crmlito/pdfjsa/web/viewer.html?file=" + blob;
                        visorPDF.src = blob + "#toolbar=1&nosidebar=1";
                        $('#autorizarDiv')[0].style.display = 'none';
                        enviarCorreo.style.display = 'none'
                    }
                    loading.style.visibility = "visible";
                    if (detector.mobile() == null) {
                        $('#btnCerrarVisor')[0].classList = 'col-md-4 offset-md-4';
                    } else {
                        $('#btnCerrarVisor')[0].classList = 'col-3 offset-5';
                    }
                } else {
                    if (rol == 2 && ($('#idRol').val() == 4 || $('#idRol').val() == 9 || $('#idRol').val() == 1 || $('#idRol').val() == 8)) {
                        $('#btnCerrarVisor')[0].classList = 'col-md-4 offset-md-1';
                        $('#btnAProduccion')[0].style.display = 'block';
                    } else {
                        $('#btnCerrarVisor')[0].classList = 'col-md-4 offset-md-4';
                        $('#btnAProduccion')[0].style.display = 'none';
                    }

                    $('#autorizarDiv')[0].style.display = 'none';
                    $('#isCancelledDoc')[0].style.display = 'none';
                    $('#enviarCorreo')[0].style.display = 'none';
                    loading.style.visibility = "hidden";


                    // if (data.Autorizado == 1) {
                    //     visorPDF.src = blob + "#toolbar=1&nosidebar=1";
                    //     $('#autorizar')[0].disabled = true;
                    //     $('#autorizarLabel')[0].textContent = 'Autorizado';
                    //     $('#isCancelledDoc')[0].style.display = 'none';
                    // } else {
                    //     visorPDF.src = blob + "#toolbar=0&nosidebar=0";
                    //     $('#autorizar')[0].disabled = false;
                    //     $('#autorizarLabel')[0].textContent = 'Autorizar';
                    //     $('#isCancelledDoc')[0].style.display = 'block';
                    //     $("#fecAutoriza").val(dateFormat(dateToday, 'dd-mm-yyyy H:MM:ss'));
                    // }

                    // if (data.Autorizado == 0) {
                    //     $('#autorizar')[0].checked = false;
                    // } else {
                    //     $('#autorizar')[0].checked = true;
                    // }

                    // if (rol == 1 || rol == 8) {
                    //     $('#autorizarDiv')[0].style.display = 'block';
                    // } else {
                    //     $('#autorizarDiv')[0].style.display = 'none';
                    // }

                    // if (data.idTipoDocumento == 1 && data.Autorizado == 0) {
                    //     enviarCorreo.style.display = 'block'
                    // } else {
                    //     enviarCorreo.style.display = 'none'
                    // }

                }

                if (extension.match(/^.*image.*$/)) {
                    descargarImageOport.style.display = 'block'
                    $('#idDocumento').val(idDocumento);
                } else {
                    // $('#idDocumento').val(idDocumentoEstr);
                    descargarImageOport.style.display = 'none'
                }

                if (data.idTipoDocumento == 1) {
                    if (data.puedeDescargarPresupuesto == 1) {
                        // visorPDF.src = "/crmlito/pdfjsa/web/viewer.html?file=" + blob + "&notoolbar=0";
                        visorPDF.src = blob + "#toolbar=1&nosidebar=1";
                    } else {
                        // visorPDF.src = "/crmlito/pdfjsa/web/viewer.html?file=" + blob + "&notoolbar=1";
                        visorPDF.src = blob + "#toolbar=0&nosidebar=1";
                    }

                    $('#autorizarDiv')[0].style.display = 'block';

                    if (detector.mobile() == null) {
                        loading.style.marginTop = 0;
                        loading.style.height = '98%';
                    } else {
                        $('#btnCerrarVisor')[0].classList = 'col-3 offset-5';
                        loading.style.visibility = "hidden";
                    }

                    if (data.Autorizado == 1) {
                        $('#autorizarPres').prop('disabled', true);
                        $('#autorizarPres').prop('checked', true);
                        $('#autorizarLabel').text('Autorizado');
                        $('#rechazarPres').prop('disabled', true);
                        $('#rechazarPres').prop('checked', false);
                        // $('#isCancelledDoc')[0].style.display = 'block';
                        // $('#isCancelledDocDiv2').html('<span class="text-success">Presupuesto Autorizado</span>');
                        descargarImageOport.style.display = 'none'
                        $('#notaRechazo')[0].style.display = 'none';
                        $('#enviarCorreo')[0].style.display = 'block';
                    } else if (data.Autorizado == 0 && data.idUsuarioAutoriza != null && data.idUsuarioAutoriza != 0) {
                        $('#autorizarPres').prop('disabled', true);
                        $('#autorizarPres').prop('checked', false);
                        $('#autorizarLabel').text('Autorizar');
                        $('#rechazarPres').prop('disabled', true);
                        $('#rechazarPres').prop('checked', true);
                        $('#rechazarLabel').text('Rechazado');
                        $('#enviarCorreo')[0].style.display = 'none';
                        $('#isCancelledDoc')[0].style.display = 'block';
                        $('#isCancelledDocDiv2').html('<span class="text-danger">Presupuesto Rechazado</span>');
                        $('#notaRechazo')[0].style.display = 'block';
                        descargarImageOport.style.display = 'none'
                    } else {
                        $('#autorizarPres').prop('disabled', false);
                        $('#autorizarPres').prop('checked', false);
                        $('#autorizarLabel').text('Autorizar');
                        $('#rechazarPres').prop('disabled', false);
                        $('#rechazarPres').prop('checked', false);
                        $('#rechazarLabel').text('Rechazar');
                        $('#isCancelledDoc')[0].style.display = 'block';
                        $('#isCancelledDocDiv2').html('<span class="text-danger">SIN AUTORIZAR</span>');
                        $('#notaRechazo')[0].style.display = 'none';
                        descargarImageOport.style.display = 'none'
                    }

                } else {
                    // visorPDF.src = "/crmlito/pdfjsa/web/viewer.html?file=" + blob;
                    visorPDF.src = blob + "#toolbar=1&nosidebar=1";
                    if (detector.mobile() == null) {
                        loading.style.marginTop = '60px';
                        loading.style.height = '89%';
                    } else {
                        $('#btnCerrarVisor')[0].classList = 'col-3 offset-5';
                        loading.style.visibility = "hidden";
                    }
                    $('#autorizarDiv')[0].style.display = 'none';
                }

                // visorPDF.src = blob;

                // window.frames["frameVisorDocumentos"].oncontextmenu = function () {
                //     return false;
                // };

                // $("#frameVisorDocumentos")[0].oncontextmenu = function () {
                //     return false;
                // };

                // $("#frameVisorDocumentos")[0].oncontextmenu = 0;

                // visorPDF.src = blob + '#toolbar=0';

                document.onmousedown = disableRightclick;
                // var msg = "¡El botón derecho está desactivado para este sitio !";

                // function disableIE() {
                //     if (document.all) {
                //         alert(msg);
                //         return false;
                //     }
                // }
                // function disableNS(e) {
                //     if (document.layers || (document.getElementById && !document.all)) {
                //         if (e.which == 2 || e.which == 3) {
                //             alert(msg);
                //             return false;
                //         }
                //     }
                // }
                // if (document.layers) {
                //     document.captureEvents(Event.MOUSEDOWN);
                //     document.onmousedown = false;
                // } else {
                //     document.onmouseup = disableNS;
                //     document.oncontextmenu = disableIE;
                // }
                // document.oncontextmenu = ev => {
                //     ev.preventDefault();
                //     console.log("Prevented to open menu!");
                // }


                loaderModalOut();
            }

            // } catch (error) {
            //     Swal.fire('Falta Información para este Documento');
            // }
            // if (data.document == 'undefined') {
            // } else {
            // }
        },
        error: function () { }
    });
}

$("#mod_mailDocs").on("shown.bs.modal", function () {
    cuerpoDoc_ifr = document.getElementById('cuerpoDoc_ifr');
    // cuerpoDoc_ifr.focus = true;
});

function mailDocumentosOport() { // loaderModalON();

    idDocumento = $('#idDocumento').val();
    $("#cuentapara").val("");
    $("#cuentaccmail").val("");
    $("#cuerpoDoc").val("");

    cuerpoDoc = $('#cuerpoDoc');
    for (var i = 0; i < $("input[name='cuenta_cc']").length; i++) {
        ($("input[name='cuenta_cc']"))[i].checked = false;
    }

    $.ajax({
        url: serve + "getDocumentoOportMail",
        type: "GET",
        data: {
            _method: "GET",
            idDocumento: idDocumento
        },
        success: function (data) {
            loaderModalOut();

            extension = data.Extension;
            byteCharacters = atob(data.Documento);
            descDocumento = data.descDocumento;
            correo = data.Correo;
            asunto = data.nomOportunidad;
            archivo = data.Documento;
            Nombre = data.Nombre;
            password = data.PswMail;
            id_oport = data.idOportunidad;
            firma = data.firma;
            cuentapara = data.CorreoPara;
            $("#mod_mailDocs").modal("show");
            $("#cuentaAgente").val(correo);
            $("#subjetDoc").val(asunto.toUpperCase());
            $("#docAdjunto").val(descDocumento);
            $("#archivoAdjunto").val(archivo);
            $("#Nombre").val(Nombre);
            $("#password").val(password);
            $("#id_oport").val(id_oport);
            $("#firma").val(firma);
            $("#cuentapara").val(cuentapara);
            if (data.tipo == 'OPORT') {

                tinymce.init({
                    selector: '#cuerpoDoc',
                    statusbar: false,
                    height: 250,
                    language: 'es',
                    menubar: false,
                    // theme: "silver",
                    skin: 'oxide-dark',
                    importcss_append: true,
                    toolbar: 'undo redo | blocks | ' +
                        'bold italic forecolor backcolor | alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent | ',
                    content_style: '.ui-tooltip{ display: none !important; }',
                    init_instance_callback: function (editor) {

                        // console.log(editor.theme);
                        editor.setContent(
                            '<p><span>&nbsp;</span></p>' +
                            '<p><strong><span style="font-family: Helvetica,sans-serif; font-size: 12pt; color: #001e82;">Saludos.</span></strong></p>' +
                            '<p><strong><span style="font-family: Helvetica,sans-serif; font-size: 12pt; color: #001e82;">' + iniciales(Nombre) + '</span></strong>' +
                            // '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: black;">&nbsp;</span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 15pt; color: #001e82;">|&nbsp;</span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #858583;">Ejecutivo de Ventas</span></p>' +
                            '<p><span style="font-family: Helvetica,sans-serif; font-size: 9pt; color: #858583;">T: +52 (55)&nbsp;</span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #001e82;">2122 5600&nbsp;ext. ' + data.Ext + ' </span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 15.0pt; color: #001e82;">|</span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #001e82;"> </span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 9.0pt; color: #858583;">C: +52 (55)&nbsp;</span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #001e82;">' + data.numCelular + '</span></p>' +
                            '<p><strong><span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #002060;">Litoprocess S.A. de C.V.</span></strong>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #858583;">' +
                            '<br>Calzada San Francisco&nbsp;Cuautlalpan 102A<br>53569 Naucalpan, Estado&nbsp;de México</span></p>' +
                            '<p><span style="font-family: Helvetica,sans-serif; font-size: 11pt; color: #858583;"><a href="http://www.litoprocess.com/" target="_blank" rel="noopener"><span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #858583;">www.litoprocess.com</span></a></span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: black;">&nbsp;</span></p>' +
                            '<p><span style="font-size: 10.5pt; font-family: Helvetica,sans-serif; color: #858583;">' +
                            '<img id="_x0000_i1025" src="https://ci3.googleusercontent.com/mail-sig/AIorK4zDUeIK6vvUo9uZufZ1Tme-bXa4xfvgCFDyR9wkKo3jAamIZicemqNeS7B1bmmN7fKnZka_B_c" border="0" data-mce-src="https://ci3.googleusercontent.com/mail-sig/AIorK4zDUeIK6vvUo9uZufZ1Tme-bXa4xfvgCFDyR9wkKo3jAamIZicemqNeS7B1bmmN7fKnZka_B_c"></span></p>' +
                            '<p style="text-align: justify;"><span style="font-family: Helvetica,sans-serif; font-size: 7pt; color: #858583;">Este mensaje de datos&nbsp;contiene información&nbsp;confidencial que puede&nbsp;constituir un secreto&nbsp;industrial y está dirigido&nbsp;solamente para la&nbsp;persona indicada. Si usted&nbsp;no es la persona para&nbsp;quien se dirige, debe&nbsp;abstenerse de reenviarlo,&nbsp;distribuirlo o copiarlo.&nbsp;Favor de notificar de&nbsp;inmediato por correo&nbsp;electrónico al remitente,&nbsp;en caso de recibir este&nbsp;mensaje por error y&nbsp;eliminarlo de su sistema.&nbsp;Si usted no es el&nbsp;destinatario, se le notifica&nbsp;que la revelación, uso,&nbsp;apoderamiento, copia,&nbsp;distribución y/o tomar&nbsp;cualquier acción basada&nbsp;en el contenido de esta&nbsp;información, está&nbsp;estrictamente prohibido. Para conocer nuestro&nbsp;Aviso de Privacidad,&nbsp;relacionado con el&nbsp;tratamiento de sus datos&nbsp;personales, favor de&nbsp;consultar la página:&nbsp;</span>' +
                            '<span style="font-family: Helvetica,sans-serif; font-size: 7pt; color: #858583;"><a href="http://www.litoprocess.com/" target="_blank" rel="noopener"><span style="font-family: "Helvetica",sans-serif; font-size: 7.0pt; color: #1155cc;">www.litoprocess.com</span></a></span></p>',

                            { format: 'html' }
                        );

                        $("#cuerpoDoc_ifr").append("<style> .ui-tooltip{ display: none !important; } </style>");
                    },
                    // plugins: 'link image',
                    setup: (editor) => {
                        // editor.on('focus', () => {

                        //     editor.setContent(
                        //         '<p><span>&nbsp;</span></p>' +
                        //         '<p><strong><span style="font-family: Helvetica,sans-serif; font-size: 12pt; color: #001e82;">Saludos.</span></strong></p>' +
                        //         '<p><strong><span style="font-family: Helvetica,sans-serif; font-size: 12pt; color: #001e82;">' + iniciales(Nombre) + '</span></strong>' +
                        //         // '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: black;">&nbsp;</span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 15pt; color: #001e82;">|&nbsp;</span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #858583;">Sales Executive</span></p>' +
                        //         '<p><span style="font-family: Helvetica,sans-serif; font-size: 9pt; color: #858583;">T: +52 (55)&nbsp;</span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #001e82;">2122 5600&nbsp;ext. ' + data.Ext + ' </span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 15.0pt; color: #001e82;">|</span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #001e82;"> </span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 9.0pt; color: #858583;">C: +52 (55)&nbsp;</span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #001e82;">' + data.numCelular + '</span></p>' +
                        //         '<p><strong><span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #002060;">Litoprocess S.A. de C.V.</span></strong>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #858583;">' +
                        //         '<br>Calzada San Francisco&nbsp;Cuautlalpan 102A<br>53569 Naucalpan, Estado&nbsp;de México</span></p>' +
                        //         '<p><span style="font-family: Helvetica,sans-serif; font-size: 11pt; color: #858583;"><a href="http://www.litoprocess.com/" target="_blank" rel="noopener"><span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: #858583;">www.litoprocess.com</span></a></span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 10.5pt; color: black;">&nbsp;</span></p>' +
                        //         '<p><span style="font-size: 10.5pt; font-family: Helvetica,sans-serif; color: #858583;">' +
                        //         '<img id="_x0000_i1025" src="https://ci3.googleusercontent.com/mail-sig/AIorK4zDUeIK6vvUo9uZufZ1Tme-bXa4xfvgCFDyR9wkKo3jAamIZicemqNeS7B1bmmN7fKnZka_B_c" border="0" data-mce-src="https://ci3.googleusercontent.com/mail-sig/AIorK4zDUeIK6vvUo9uZufZ1Tme-bXa4xfvgCFDyR9wkKo3jAamIZicemqNeS7B1bmmN7fKnZka_B_c"></span></p>' +
                        //         '<p style="text-align: justify;"><span style="font-family: Helvetica,sans-serif; font-size: 7pt; color: #858583;">Este mensaje de datos&nbsp;contiene información&nbsp;confidencial que puede&nbsp;constituir un secreto&nbsp;industrial y está dirigido&nbsp;solamente para la&nbsp;persona indicada. Si usted&nbsp;no es la persona para&nbsp;quien se dirige, debe&nbsp;abstenerse de reenviarlo,&nbsp;distribuirlo o copiarlo.&nbsp;Favor de notificar de&nbsp;inmediato por correo&nbsp;electrónico al remitente,&nbsp;en caso de recibir este&nbsp;mensaje por error y&nbsp;eliminarlo de su sistema.&nbsp;Si usted no es el&nbsp;destinatario, se le notifica&nbsp;que la revelación, uso,&nbsp;apoderamiento, copia,&nbsp;distribución y/o tomar&nbsp;cualquier acción basada&nbsp;en el contenido de esta&nbsp;información, está&nbsp;estrictamente prohibido. Para conocer nuestro&nbsp;Aviso de Privacidad,&nbsp;relacionado con el&nbsp;tratamiento de sus datos&nbsp;personales, favor de&nbsp;consultar la página:&nbsp;</span>' +
                        //         '<span style="font-family: Helvetica,sans-serif; font-size: 7pt; color: #858583;"><a href="http://www.litoprocess.com/" target="_blank" rel="noopener"><span style="font-family: "Helvetica",sans-serif; font-size: 7.0pt; color: #1155cc;">www.litoprocess.com</span></a></span></p>',

                        //         { format: 'html' }
                        //     );
                        // });
                        editor.on('blur', () => {

                            cuerpoDoc[0].value = tinymce.activeEditor.getContent();
                        });
                    },
                });

                var iniciales = function (str) {
                    //split the above string into an array of strings 
                    //whenever a blank space is encountered
                    const lower = str.toLowerCase();
                    const arr = lower.split(" ");
                    //loop through each element of the array and capitalize the first letter.
                    for (var i = 0; i < arr.length; i++) {
                        arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
                    }
                    //Join all the elements of the array back into a string 
                    //using a blankspace as a separator 
                    const str2 = arr.join(" ");
                    // console.log(str2);
                    return str2
                    //Outptut: I Have Learned Something New Today
                }
                $("#divCC")[0].style.display = 'block';
            } else {
                $("#divCC")[0].style.display = 'none';
            }

            $("#modal-visor-documentos").modal("hide");
        }
    });

}

function enviarDocumento() {
    archivoAdjunto = $("#archivoAdjunto").val();
    cuentaAgente = $("#cuentaAgente").val();
    cuentapara = $("#cuentapara").val();
    cuentacc = $("#cuentaccmail").val();
    subjetDoc = $("#subjetDoc").val();
    docAdjunto = $("#docAdjunto").val();
    cuerpoDoc = $("#cuerpoDoc");
    Nombre = $("#Nombre").val();
    password = $("#password").val();
    id_oport = $("#id_oport").val();
    firma = $("#firma").val();
    cuenta_cc = $("input[name='cuenta_cc']");
    let documentosAdicionales = document.getElementById("documentosAdicionales");
    let archivos = documentosAdicionales.files; // Esto es un FileList

    $("input[name='cuenta_cc']")[0].checked = false;

    // console.log(cuentacc);

    if (cuentapara == null || cuentapara == "") {
        Swal.fire({ text: "¡Capturar el destinantario!", allowEnterKey: true, target: document.getElementById("mod_mailDocs") }).then(() => {
            $("#cuentapara").trigger("focus");
        });
    } else {
        loaderON();
        let formDataEnvioDocumentoMail = new FormData();
        formDataEnvioDocumentoMail.append('_method', 'POST')
        formDataEnvioDocumentoMail.append('_token', CSRF_TOKEN)
        formDataEnvioDocumentoMail.append('archivoAdjunto', archivoAdjunto)
        formDataEnvioDocumentoMail.append('cuentaAgente', cuentaAgente)
        formDataEnvioDocumentoMail.append('cuentapara', cuentapara)
        formDataEnvioDocumentoMail.append('cuentacc', cuentacc)
        formDataEnvioDocumentoMail.append('subjetDoc', subjetDoc)
        formDataEnvioDocumentoMail.append('docAdjunto', docAdjunto)
        formDataEnvioDocumentoMail.append('cuerpoDoc', cuerpoDoc[0].value)
        formDataEnvioDocumentoMail.append('Nombre', Nombre)
        formDataEnvioDocumentoMail.append('password', password)
        formDataEnvioDocumentoMail.append('id_oport', id_oport)
        formDataEnvioDocumentoMail.append('firma', firma)
        formDataEnvioDocumentoMail.append('ruta_firma', serve)
        for (let i = 0; i < archivos.length; i++) {
            formDataEnvioDocumentoMail.append('documentosAdicionales[]', archivos[i]);
        }

        $.ajax({
            url: serve + "enviarDocumentoMail",
            type: "POST",
            // data: $("#mod_mailDocs form").serialize(),
            data: formDataEnvioDocumentoMail,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.status === 'success') {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Msg.fire({ title: "Documento Enviado." });

                    $.ajax({
                        url: serve + "insertarCorreo",
                        type: "POST",
                        data: {
                            _method: "POST",
                            _token: CSRF_TOKEN,
                            archivoAdjunto: archivoAdjunto,
                            cuentaAgente: cuentaAgente,
                            cuentapara: cuentapara,
                            cuentacc: cuentacc,
                            subjetDoc: subjetDoc,
                            docAdjunto: docAdjunto,
                            cuerpoDoc: cuerpoDoc[0].value,
                            Nombre: Nombre,
                            id_oport: id_oport,
                        },
                        success: function (data) {
                            archivoAdjunto = "";
                            cuentaAgente = "";
                            cuentapara = "";
                            cuentacc = "";
                            subjetDoc = "";
                            docAdjunto = "";
                            cuerpoDoc = "";
                            Nombre = "";
                            password = "";
                            id_oport = "";
                            firma = "";

                            $('#documentosAdicionales').val('');

                            $("#mod_mailDocs").modal("hide");

                        },
                        error: function () {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Msg.fire({ title: "El correo no se pudo insertar." });
                            loaderOut();
                        }
                    });
                } else {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "El correo no se pudo enviar." });
                }

                loaderOut();
            },
            error: function () {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000
                });
                Msg.fire({ title: "El documento no se pudo enviar." });
                loaderOut();
            }
        });

    }
}

$(function () { });

$(document).ready(function () { // /--------FUNCIONES----//
    const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
    const TO_PATTERN = "DD/MM/YYYY HH:mm";
    produccion_t = document.getElementsByClassName('prodT');

    tblCorreos = $("#tblCorreos").DataTable({
        destroy: true,
        processing: true,
        pageLength: 50,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        colReorder: true,
        bLengthChange: true,
        autoWidth: true,
        order: [
            [5, "desc"]
        ],
        columnDefs: [
            {
                targets: [5],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN)
            }, {
                targets: ["_all"],
                className: "text-bold"
            },
        ],
        ajax: {
            url: serve + "apiCorreos",
            type: "GET",
            dataType: "json"
        },
        columns: [
            {
                data: "De",
                name: "De",
                title: "De"
            },
            {
                data: "Para",
                name: "Para",
                title: "Para"
            },
            {
                data: "asunto",
                name: "asunto",
                title: "Asunto"
            },
            {
                data: "Mensaje",
                name: "Mensaje",
                title: "Mensaje",
                visible: false,
            }, {
                data: "adjunto",
                name: "adjunto",
                title: "Adjunto"
            }, {
                data: "Fecha",
                name: "Fecha",
                title: "Fecha"
            }, {
                data: "Oportunidad",
                render: function (data, type, row) {
                    return ('<a href="oportunidades/' + row.idOportunidad + '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-uppercase">' + data + "</a>");
                },
                name: "Oportunidad",
                title: "Oportunidad"
            },
        ],
        language: {
            url: serve + "js/spanish-DT.json"
        },
        dom: '<"top">rt<"bottom"><"clear">',
        select: true,

        initComplete: function (settings, json) {
            loaderOut();
        }
    });

    // $("#autorizar").on("change", function () {
    //     id_documento = $('#idDocumento').val();
    //     fecAutoriza = $('#fecAutoriza').val();
    //     idOportunidadPdf = $('#idOportunidadPdf').val();

    //     if ($(this)[0].checked == true) {
    //         msg = 'Archivo Autorizado';
    //     } else {
    //         msg = 'Archivo No Autorizado';
    //     }

    //     $.ajax({
    //         url: serve + 'autorizarArchivo',
    //         type: "PATCH",
    //         data: {
    //             _method: "PATCH",
    //             id_documento: id_documento,
    //             checked: $(this)[0].checked,
    //             fecAutoriza: fecAutoriza,
    //             idOportunidadPdf: idOportunidadPdf,
    //             _token: CSRF_TOKEN
    //         },
    //         success: function () {
    //             const Msg = Swal.mixin({
    //                 toast: true,
    //                 position: "center",
    //                 icon: 'success',
    //                 showConfirmButton: false,
    //                 timer: 3000
    //             });

    //             Msg.fire({ title: msg });
    //             tableDocsOport.ajax.reload();

    //             visorDocumentos(id_documento);
    //         },
    //     })

    // });

});

/**
 * Función para rechazar un documento
 * 
 * @param {*} idDocumento
 * @return void
 */
function rechazarDocumento(idDocumento) {

    // Función para obtener el listado de los motivos de
    $.ajax({
        url: serve + 'getMotivosRechazoDocumento',
        type: "POST",
        data: {
            _method: "POST",
            _token: CSRF_TOKEN
        },
        success: function (response) {
            // Validamos si se obtuvo una respuesta positiva
            if (response.response) {
                // Funcionalidad para preguntar si se puede rechazar la tripa

                // Crear un objeto para almacenar las opciones
                var inputOptions = {};

                // Recorrer las opciones y agregarlas al objeto
                response.catMotivos.forEach(function(opcion) {
                    inputOptions[opcion.idMotivoRechazo] = opcion.descripcion;
                });

                inputOptions[0] = 'Selecciona una opción'

                Swal.fire({
                    title: "¿Quieres rechazar este documento?",
                    text: "¡Ya no podrás revertir este cambio!",
                    input: 'select',
                    inputOptions: inputOptions,
                    showCancelButton: true,
                    confirmButtonText: "Si, rechazar",
                    cancelButtonText: "No, cancelar!",
                    stopKeydownPropagation: true,
                    returnFocus: true,
                    reverseButtons: true,
                    allowOutsideClick: false,
                    preConfirm: (value) => {
                        if (value == 0) {
                            Swal.showValidationMessage('¡Debes de seleccionar el motivo!');
                            return false;
                        }
                        // Si el valor es válido, se devuelve el valor para que se confirme la acción
                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value == 7) {
                            let motivoId = result.value
                            Swal.fire({
                                title: "Motivo por el cual se rechaza el documento",
                                text: "Ingresa el otro motivo para rechazar este documento",
                                input: 'textarea',
                                inputPlaceholder: "Ingresa el motivo",
                                showCancelButton: true,
                                confirmButtonText: "Si, rechazar",
                                cancelButtonText: "No, cancelar!",
                                stopKeydownPropagation: true,
                                returnFocus: true,
                                reverseButtons: true,
                                allowOutsideClick: false,
                                preConfirm: (value) => {
                                    if (value == '' || value == null) {
                                        Swal.showValidationMessage('¡Debes ingresar motivo!');
                                        return false;
                                    }
                                    // Si el valor es válido, se devuelve el valor para que se confirme la acción
                                    return value;
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    rejectDocument(idDocumento, motivoId, result.value)
                                }
                            });
                        } else {
                            rejectDocument(idDocumento, result.value)
                        }
                    }
                });
            }
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "error",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: 'Lo sentimos ocurrió un problema' });
            tableDocsOport.ajax.reload();
        }
    })
}

/**
 * Función para enviar la información y rechazar el documento
 * 
 * @param idDocumento
 * @param motivoId
 * @param notes
 * @return void
 */
function rejectDocument(idDocumento, motivoId, notes) {
    notes = notes == undefined ? '' : notes;
    let idOport = document.getElementById("idOportunidad").value;
    $.ajax({
        url: serve + 'rechazarDocumento',
        type: "PATCH",
        data: {
            _method: "PATCH",
            id_documento: idDocumento,
            motivoRejectId: motivoId,
            notes: notes,
            idOportunidad: idOport,
            _token: CSRF_TOKEN
        },
        success: function (data) {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: (data.response ? "success" : 'error'),
                showConfirmButton: false,
                timer: 3000
            });

            if (data.response) {
                Msg.fire({ title: 'Documento Rechazado' });
            } else {
                Msg.fire({ title: 'Lo sentimos ocurrió un problema' });
            }

            tableDocsOport.ajax.reload();
        },
    })
}

/**
 * Función para mostrar el motivo de rechazó de una tripa
 * 
 * @param {*} idDocumento
 * @return void
 */
function showMotiveRejected(idDocumento) {
    $.ajax({
        url: serve + 'getMotivoRechazoDoc',
        type: "POST",
        data: {
            _method: "POST",
            id_documento: idDocumento,
            _token: CSRF_TOKEN
        },
        success: function (data) {
            if (data.response) {
                Swal.fire({
                    title: "Motivo de rechazó",
                    text: data.message,
                });
            }
        },
    })
}

function editDocument(idDocumento) {

    id = document.getElementById("idOportunidad");
    // dominio = document.domain;
    // id = document.getElementById("idOportunidad");
    // if (dominio == "127.0.0.1") {
    //     window.open('https://servicios.litoprocess.com/editorpdf/#/webviewer/' + idDocumento, '_blank');
    // } else if (dominio == "192.168.2.222") {
    //     window.open('https://' + dominio + '/editorpdf/#/webviewer/' + idDocumento, '_blank');
    // } else {
    //     window.open('https://' + dominio + '/editorpdf/#/webviewer/' + idDocumento, '_blank');
    // }

    // window.open('/editorpdf/#/webviewer/' + idDocumento + '?oport=' + id.value);
    // window.open('https://servicios.litoprocess.com/editorpdf/#/webviewer/' + idDocumento, '_blank');

    // Abrir el visor de documentos en esta misma ventana
    window.open('/editorpdf/#/webviewer/' + idDocumento + '?oport=' + id.value, '_self');
}

const copiarLigaTripa = async (idDocumento) => {

    dominio = document.domain;
    id = document.getElementById("idOportunidad");
    if (dominio == "127.0.0.1") {
        liga = 'https://servicios.litoprocess.com/editorpdf/#/webviewer/' + idDocumento + '?oport=' + id.value;
    } else if (dominio == "192.168.2.222") {
        liga = 'http://' + dominio + '/editorpdf/#/webviewer/' + idDocumento + '?oport=' + id.value;
    } else {
        liga = 'https://' + dominio + '/editorpdf/#/webviewer/' + idDocumento + '?oport=' + id.value;
    }

    // console.log(navigator);
    try {
        await navigator.clipboard.writeText(liga);

        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "success",
            showConfirmButton: false,
            timer: 3000
        });
        Msg.fire({ title: 'Liga Copiada.' });
    } catch (err) {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "error",
            showConfirmButton: false,
            timer: 3000
        });
        Msg.fire({ title: 'No se pudo copiar la liga' });

    }

}


function validarTripa(idDocumento, valido) {

    valido_id = $('#valido_' + idDocumento);

    if (valido == 2) {
        validar = 'Marcar como No Autorizado?'
        valido_value = true;
        check = false;
        validado = 'Marcado como No Autorizado'
    } else {
        validar = 'Marcar como Autorizado?'
        valido_value = false;
        check = true;
        validado = 'Marcado como Autorizado'
    }

    Swal.fire({
        title: "Documentos",
        text: validar,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "validarTripa",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idDocumento: idDocumento,
                    check: check,
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

                    Msg.fire({ title: "Documento " + validado });
                    $("#mod_UpDocs").modal("hide");
                    loaderOut();
                    $("#mod_UpDocs :input").val("");
                    generarListaDocumentos();
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error, vuelve a intentarlo" });
                }
            });
        } else {
            // valido_id.checked = valido_value;
        }
    });
}

function validarTripaC(idDocumento, valido) {

    validoc_id = $('#validoc_' + idDocumento);

    if (valido == 5) {
        validar = 'Marcar como No Autorizado?'
        valido_value = true;
        check = false;
        validado = 'Marcado como No Autorizado'
    } else {
        validar = 'Marcar como Autorizado?'
        valido_value = false;
        check = true;
        validado = 'Marcado como Autorizado'
    }

    Swal.fire({
        title: "Documentos",
        text: validar,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
        allowEnterKey: false,
        stopKeydownPropagation: true,
        reverseButtons: true,
        returnFocus: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "validarTripaC",
                type: "PATCH",
                data: {
                    _method: "PATCH",
                    idDocumento: idDocumento,
                    check: check,
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

                    Msg.fire({ title: "Documento " + validado });
                    $("#mod_UpDocs").modal("hide");
                    loaderOut();
                    $("#mod_UpDocs :input").val("");
                    generarListaDocumentos();
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "warning",
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire({ title: "Ocurrió un error, vuelve a intentarlo" });
                }
            });
        } else {
            // valido_id.checked = valido_value;
        }
    });
}


// produccion_t.mouseover(function(){
//     Swal.fire('Abriste SweetAlert');
// })

function produccionTripa(idDocumento, produccion) {

    if (idDocumento == '' || idDocumento == null) {
        idDocumento = $('#idDocumento').val();
        produccion = 0;
    }

    let estatus_oportunidad = $('#Estatus').val();
    let id_oportunidad = $('#idOportunidad').val();
    let notasProduccion = $('#notasProduccion').val();

    produccion_id = $('#produccion_' + idDocumento);

    if (estatus_oportunidad == 8 || estatus_oportunidad == 11 || estatus_oportunidad == 12 || estatus_oportunidad == 16 || estatus_oportunidad == 14 || estatus_oportunidad == 15 || estatus_oportunidad == 17) {
        if (produccion == 1) {
            produccion_m = 'Desmarcar para Producción?'
            produccion_value = false;
            check_prod = false;
            a_produccion = 'Desmarcado para Producción'
        } else {
            produccion_m = 'Marcar para Producción?'
            produccion_value = true;
            check_prod = true;
            a_produccion = 'Marcado para Produccion'
        }

        Swal.fire({
            title: "Documentos",
            text: produccion_m,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
            confirmButtonText: "Si",
            cancelButtonText: "Cancelar",
            allowEnterKey: false,
            stopKeydownPropagation: true,
            reverseButtons: true,
            returnFocus: true
        }).then((result) => {
            if (result.isConfirmed) {
                $("#modal-visor-documentos").modal("hide");
                const { value: text } = Swal.fire({
                    input: "textarea",
                    inputLabel: "Notas Producción",
                    // inputValue: notasProduccion,
                    inputPlaceholder: "Captura las Notas para Producción",
                    inputAttributes: {
                        "aria-label": "Captura las Notas para Producción",
                    },
                    showCancelButton: true,
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Guardar",
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#999",
                    customClass: 'swal_width',
                    target: document.getElementById("formOporEdit"),
                    preConfirm: (value) => {
                        if (value == '' || value == null) {
                            Swal.showValidationMessage('¡Debes capturar el motivo!');
                            return false;
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value != "") {
                            $.ajax({
                                url: serve + "produccionTripa",
                                type: "PATCH",
                                data: {
                                    _method: "PATCH",
                                    idDocumento: idDocumento,
                                    check: check_prod,
                                    notasProduccion: result.value,
                                    id: id_oportunidad,
                                    _token: CSRF_TOKEN
                                },
                                success: function (data) {
                                    if (data.response) {
                                        const Msg = Swal.mixin({
                                            toast: true,
                                            position: "center",
                                            icon: "success",
                                            showConfirmButton: false,
                                            timer: 3000
                                        });

                                        Msg.fire({ title: "Documento " + a_produccion });
                                        $("#mod_UpDocs").modal("hide");
                                        loaderOut();
                                        $("#mod_UpDocs :input").val("");
                                        // tableDocsOport.ajax.reload();
                                        generarListaDocumentos();

                                        if (data.refresh) {
                                            setTimeout(() => {
                                                location.reload();
                                            }, 1500);
                                        }
                                    }
                                },
                                error: function () {
                                    const Msg = Swal.mixin({
                                        toast: true,
                                        position: "center",
                                        icon: "warning",
                                        showConfirmButton: false,
                                        timer: 3000
                                    });

                                    Msg.fire({ title: "Ocurrió un error, vuelve a intentarlo" });
                                }
                            });
                        } else {
                            Swal.fire("¡Debes capturar el motivo!");
                        }
                    } else {
                        loaderOut();
                    }
                });
            } else {
                loaderOut();
            }

        });
    } else {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "warning",
            showConfirmButton: true,
            target: document.getElementById("formOporEdit"),
        });

        Msg.fire({
            title: 'El Estatus de la Oportunidad tiene que estar en "Presupuesto Terminado" para poder pasar la tripa a Producción'
        }).then((result) => {
            if (result.isConfirmed) {
                loaderOut();
            } else {
                loaderOut();
            }
        });
    }
}

function mostrarBtnSave(idDocumento) {

    valor_anterior = $('#descDocOld_' + idDocumento).val();
    nuevo_valor = $('#descDoc_' + idDocumento).val();

    if (nuevo_valor == valor_anterior) {
        $('#saveDoc_' + idDocumento)[0].style.display = 'none';
    } else {
        $('#saveDoc_' + idDocumento)[0].style.display = 'block';
    }
}

function actualizarNombreDocumento(idDocumento) {
    $('#saveDoc_' + idDocumento)[0].style.display = 'none';
    nuevo_valor = $('#descDoc_' + idDocumento).val();

    $.ajax({
        url: serve + 'actualizarNombreDocumento',
        type: 'PATCH',
        dataType: 'JSON',
        data: {
            idDocumento: idDocumento,
            nombre: nuevo_valor,
            _token: CSRF_TOKEN,
        },
    })
    const Msg = Swal.mixin({
        toast: true,
        position: "center",
        icon: "success",
        showConfirmButton: false,
        timer: 3000
    });

    Msg.fire({ title: "Nombre Actualizado" });
}

function getMimeType(extension) {
    ext = '.' + extension.toLowerCase();

    if (ext == '.jpg') {
        mimeType = 'image/jpeg';
        return mimeType;
    } else if (ext == '.xlsx') {
        mimeType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        return mimeType;
    } else if (ext == '.xls') {
        mimeType = 'application/vnd.ms-excel';
        return mimeType;
    } else if (ext == '.xlsm') {
        mimeType = 'application/vnd.ms-excel.sheet.macroEnabled.12';
        return mimeType;
    } else if (ext == '.bmp') {
        mimeType = 'image/bmp';
        return mimeType;
    } else if (ext == '.csv') {
        mimeType = 'text/csv';
        return mimeType;
    } else if (ext == '.doc') {
        mimeType = 'application/msword';
        return mimeType;
    } else if (ext == '.docm') {
        mimeType = 'application/vnd.ms-word.document.macroEnabled.12';
        return mimeType;
    } else if (ext == '.docx') {
        mimeType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        return mimeType;
    } else if (ext == '.exe') {
        mimeType = 'application/x-msdownload';
        return mimeType;
    } else if (ext == '.gif') {
        mimeType = 'image/gif';
        return mimeType;
    } else if (ext == '.htm') {
        mimeType = 'text/html';
        return mimeType;
    } else if (ext == '.html') {
        mimeType = 'text/html';
        return mimeType;
    } else if (ext == '.jpe') {
        mimeType = 'image/jpeg';
        return mimeType;
    } else if (ext == '.jpeg') {
        mimeType = 'image/jpeg';
        return mimeType;
    } else if (ext == '.pdf') {
        mimeType = 'application/pdf';
        return mimeType;
    } else if (ext == '.png') {
        mimeType = 'image/png';
        return mimeType;
    } else if (ext == '.ppt') {
        mimeType = 'application/vnd.ms-powerpoint';
        return mimeType;
    } else if (ext == '.pptm') {
        mimeType = 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
        return mimeType;
    } else if (ext == '.pptx') {
        mimeType = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
        return mimeType;
    } else if (ext == '.tif') {
        mimeType = 'image/tif';
        return mimeType;
    } else if (ext == '.tiff') {
        mimeType = 'image/tiff';
        return mimeType;
    } else if (ext == '.txt') {
        mimeType = 'text/plain';
        return mimeType;
    } else if (ext == '.zip') {
        mimeType = 'application/x-zip-compressed';
        return mimeType;
    }
    // switch (ext) {
    //     case '.xls':
    //         mimetype = 'application/vnd.ms-excel';
    //         return mimeType;
    //     case '.xlsx':
    //         mimetype = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    //         return mimeType;
    //     case '.xlsm':
    //         mimetype = 'application/vnd.ms-excel.sheet.macroEnabled.12'
    //         return mimeType;
    //     case '.bmp':
    //         mimetype = 'image/bmp'
    //         return mimeType;
    //     case '.csv':
    //         mimetype = 'text/csv'
    //         return mimeType;
    //     case '.doc':
    //         mimetype = 'application/msword'
    //         return mimeType;
    //     case '.docm':
    //         mimetype = 'application/vnd.ms-word.document.macroEnabled.12'
    //         return mimeType;
    //     case '.docx':
    //         mimetype = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    //         return mimeType;
    //     case '.exe':
    //         mimetype = 'application/x-msdownload'
    //         return mimeType;
    //     case '.gif':
    //         mimetype = 'image/gif'
    //         return mimeType;
    //     case '.htm':
    //     case '.html':
    //         mimetype = 'text/html'
    //         return mimeType;
    //     case '.jpe':
    //     case '.jpeg':
    //     case '.jpg':
    //         mimetype = 'image/jpeg'
    //         return mimeType;
    //     case '.pdf':
    //         mimetype = 'application/pdf'
    //         return mimeType;
    //     case '.png':
    //         mimetype = 'image/png'
    //         return mimeType;
    //     case '.ppt':
    //         mimetype = 'application/vnd.ms-powerpoint'
    //         return mimeType;
    //     case '.pptm':
    //         mimetype = 'application/vnd.ms-powerpoint.presentation.macroEnabled.12'
    //         return mimeType;
    //     case '.pptx':
    //         mimetype = 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    //         return mimeType;
    //     case '.tif':
    //         mimetype = 'image/tif'
    //         return mimeType;
    //     case '.tiff':
    //         mimetype = 'image/tiff'
    //         return mimeType;
    //     case '.txt':
    //         mimetype = 'text/plain'
    //         return mimeType;
    //     case '.xml':
    //         mimetype = 'text/xml'
    //         return mimeType;
    //     case '.xsl':
    //         mimetype = 'text/xml'
    //         return mimeType;
    //     case '.zip':
    //         mimetype = 'application/x-zip-compressed'
    //         return mimeType;
    //     default:
    //         mimetype = 'application/octet-stream'
    //         return mimeType;
    // }
}

function descargarImagenOport() {
    idDocumentoOport = $('#idDocumento').val();
    window.location.href = serve + 'downDocsOport/' + idDocumentoOport;
}

function asignarCC(cc) {
    cuentaccmail = document.getElementById('cuentaccmail');
    valor_cc_anterior = cuentaccmail.value;

    if (valor_cc_anterior.indexOf(cc) == -1) {

        if (valor_cc_anterior == '') {
            valor_cc_nuevo = cc;
            cuentaccmail.value = valor_cc_nuevo;
        } else {
            cuentaccmail.value = valor_cc_anterior + ', ' + cc;
        }

    } else if (valor_cc_anterior.indexOf(cc) == 0) {
        if (valor_cc_anterior.match(', ')) {
            valor_cc_nuevo = valor_cc_anterior.replace(cc + ', ', '');
        } else {
            valor_cc_nuevo = valor_cc_anterior.replace(cc, '');
        }
        cuentaccmail.value = valor_cc_nuevo;
    } else {
        if (valor_cc_anterior == '') {
            cuentaccmail.value = cc;
        } else {
            cuentaccmail.value = valor_cc_anterior.replace(', ' + cc, '');
        }
    }
}

/**
 * Función para autorizar o rechazar un presupuesto
 * 
 * @return void
 */
function autorizarPresupuesto(valor) {
    // Si el presupuesto se aprueba
    if (valor == 1) {
        Swal.fire({
            title: "¿Autorizar Presupuesto?",
            text: "¿Está seguro de que desea autorizar este presupuesto?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, autorizar",
            cancelButtonText: "No, cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                let fecAutoriza = $('#fecAutoriza').val();
                let idOportunidadPdf = $('#idOportunidadPdf').val();
                let msg = 'Archivo Autorizado';
                let id_documento = $('#idDocumento').val();

                $.ajax({
                    url: serve + 'autorizarArchivo',
                    type: "PATCH",
                    data: {
                        _method: "PATCH",
                        _token: CSRF_TOKEN,
                        id_documento: id_documento,
                        checked: 1,
                        fecAutoriza: fecAutoriza,
                        idOportunidadPdf: idOportunidadPdf,
                    },
                    success: function () {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire({ title: msg });
                        tableDocsOport.ajax.reload();

                        // Una vez que se acepte el presupuesto, se bloquean los inputs para que no se puedan editar
                        $('#autorizarPres').prop('disabled', true);
                        $('#rechazarPres').prop('disabled', true);
                        $('#enviarCorreo').show();

                        visorDocumentos(id_documento);
                    },
                })
            } else {
                // Removemos el check del radio
                $('#autorizarPres')[0].checked = false;
            }
        });
    } else {
        $('#modal-visor-documentos').modal('hide');
        
        openModalOpciones();
    }
}

/**
 * Función para mostrar el mensaje de rechazo de presupuesto
 */
function mostrarNotaRechazo() {
    let id_documento = $('#idDocumento').val();

    $.ajax({
        url: serve + 'getNotaRechazoPresupuesto',
        type: "POST",
        data: {
            _method: "POST",
            _token: CSRF_TOKEN,
            id_documento: id_documento,
        },
        success: function (data) {
            if (data.response) {
                let motivo = data.motivo ? data.motivo : 'No hay nota de rechazo disponible';
                Swal.fire({
                    title: "Nota de Rechazo",
                    text: motivo,
                    icon: "info",
                    confirmButtonText: "Cerrar",
                    customClass: {
                        confirmButton: 'btn btn-primary',
                    }
                })
            }
        }
    })
}

function openModalOpciones() {
    // Obtenemos el estatus de la oportunidad
    let estatus = $('#Estatus').val()

    Swal.fire({
        title: "¿Deseas continuar?",
        text: "Al rechazar el presupuesto, se le redigira al listado de EP, para generar uno nuevo.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "No, cancelar",
        allowOutsideClick: false,
        didOpen: () => {
            const confirmButton = Swal.getConfirmButton();
            const cancelButton = Swal.getCancelButton();

            confirmButton.addEventListener('click', () => {
                rechazarPresupuesto();
                $('#elementos-oport-tab').trigger('click');
                tblEP_relationV2();
                Swal.close();
            });

            cancelButton.addEventListener('click', () => {
                Swal.close();
                visorDocumentos($('#idDocumento').val(), $('#idRol').val());
            });
        }
    });
}

function openModalCambioTexto() {
    let idDocumento = $('#idDocumento').val();
    let idRol = $('#idRol').val();

    Swal.fire({
        title: "Indica el texto a corregir",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, realizar cambio",
        cancelButtonText: "No, cancelar",
        input: 'textarea',
        inputAttributes: {
            rows: 4,
            placeholder: 'Ingresa el texto a corregir aquí',
        },
        inputPlaceholder: 'Texto a corregir',
        preConfirm: (value) => {
            if (!value) {
                Swal.showValidationMessage('¡Debes de capturar el texto a corregir!');
                return false;
            }
            return value;
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            guardarMotivoCambioTxto(result)
        } else {
            // Removemos el check del radio
            visorDocumentos(idDocumento, idRol);
        }
    });
}

function guardarMotivoCambioTxto(result) {
    let fecAutoriza = $('#fecAutoriza').val();
    let idOportunidadPdf = $('#idOportunidadPdf').val();
    let msg = 'Archivo Rechazado';
    let id_documento = $('#idDocumento').val();

    $.ajax({
        url: serve + 'autorizarArchivo',
        type: "PATCH",
        data: {
            _method: "PATCH",
            _token: CSRF_TOKEN,
            id_documento: id_documento,
            checked: 0,
            fecAutoriza: fecAutoriza,
            idOportunidadPdf: idOportunidadPdf,
            motivo: result.value
        },
        success: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: msg });
            tableDocsOport.ajax.reload();

            // Una vez que se acepte el presupuesto, se bloquean los inputs para que no se puedan editar
            $('#autorizarPres').prop('disabled', true);
            $('#rechazarPres').prop('disabled', true);
            $('#rechazarPres').prop('checked', true);
            $('#enviarCorreo').hide();

            // Refrescamos la vista completa de la edición de oportunidad
            window.location.href = serve + 'oportunidades/' + idOportunidadPdf + '/edit';
        },
    })
}

/**
 * Función para rechazar el presupuesto, se muestra un modal para ingresar el motivo de rechazo, una vez ingresado se guarda el motivo y se rechaza el presupuesto
 * 
 * @returns void
 */
function rechazarPresupuesto() {
    let fecAutoriza = $('#fecAutoriza').val();
    let idOportunidadPdf = $('#idOportunidadPdf').val();
    let msg = 'Archivo Rechazado';
    let id_documento = $('#idDocumento').val();

    $.ajax({
        url: serve + 'autorizarArchivo',
        type: "PATCH",
        data: {
            _method: "PATCH",
            _token: CSRF_TOKEN,
            id_documento: id_documento,
            checked: 0,
            fecAutoriza: fecAutoriza,
            idOportunidadPdf: idOportunidadPdf,
        },
        success: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: 'success',
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: msg });
            tableDocsOport.ajax.reload();

            // Una vez que se acepte el presupuesto, se bloquean los inputs para que no se puedan editar
            $('#autorizarPres').prop('disabled', true);
            $('#rechazarPres').prop('disabled', true);
            $('#rechazarPres').prop('checked', true);
            $('#enviarCorreo').hide();
        },
    })
}
