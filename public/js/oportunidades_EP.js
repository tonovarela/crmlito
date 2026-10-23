rutaActual = document.location.href;
var serve = base_path;
var tblElementosPresupuestados = '',
    tblElementosPresupuestadosRelation = '',
    height = window.innerHeight,
    eventoTbl = '',
    someObj = {};
EPrelations = [];
someObj.EPrelations1 = [];
$("#cancelar").prop("disabled", false);

const TAB_ELEMENTOS_OPORT_KEY = 'trigger_elementos_oport_tab_after_reload';

function recargarYAbrirTabElementosOport() {
    sessionStorage.setItem(TAB_ELEMENTOS_OPORT_KEY, '1');
    location.reload();
}

// $(document).ready(function() {
//     if (sessionStorage.getItem(TAB_ELEMENTOS_OPORT_KEY) === '1') {
//         sessionStorage.removeItem(TAB_ELEMENTOS_OPORT_KEY);
//         $('#elementos-oport-tab').trigger('click');
//     }
// });

if (rutaActual.indexOf('edit') > -1) {
    eventoTbl = $("#idOportunidad").val();
    tblIndex(serve, eventoTbl);
} else {
    eventoTbl = 0;
    tblIndex(serve, eventoTbl);
}

//---------------------------------B O T O N E S------------------------------///

$("#btnRelacionar").click(function() {
    countCheckbox = $('input:checkbox:checked').length;
    idOportunidad = $("#idOportunidad").val();
    $(".RelCheck:checkbox:checked").each(function() {
        idElementoP = ($(this).attr("id")).split('-');
        someObj.EPrelations1.push({
            "idElementoP": idElementoP[1],
            "idOportunidad": idOportunidad
        });
        // AddRalation(someObj);

    });
    AddRalation(someObj);
    // console.log(someObj);

});

///----------------------------------FUNCIONES--------------------------------///

function OpenModal(data) {
    eventoTbl = 0;
    nametable = 'tblElementosPresupuestadosRelation';
    $('#mod_EP_relation').modal('show');
    tblEP_relation(serve, eventoTbl);
}

function AddRalation(EPrelations) {
    mensaje = "Relación Exitosa";

    $.ajax({
        url: serve + "EPrelation",
        type: "PATCH",
        data: {
            // _method: "PATCH",
            EPrelations: EPrelations,
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
            tblElementosPresupuestados.ajax.reload();
            $('#mod_EP_relation').modal('hide');;
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
                title: "Ocurrió un error al Relacionar el elemento",
            });
        },
    });

}

//------------------------------- Tabla de elementos presupuestados relacionados ---------------------------------///
function tblEP_relationV2() {
    let idOportunidad = $("#idOportunidad").val();
    let idRolEP = $("#idRol").val();

    // Validamos el estatus actual de la oportunidad para mostrar la sección de cambios de Excel
    if ($('#seccionCambiosExcel').length) {
        let formDataSCE = new FormData();
        formDataSCE.append('_token', CSRF_TOKEN);
        formDataSCE.append('idOportunidad', idOportunidad);

        $.ajax({
            url: serve + 'obtenerEstatusOportunidad',
            type: 'POST',
            data: formDataSCE,
            processData: false,
            contentType: false,
            success: function(resp) {
                const estatusActual = Number(resp && resp.estatus);
                const seVeraBoton = (estatusActual === 7 || estatusActual === 8);
                const mostrarSeccion = resp.tieneRechazoAlMenosUnaVez;
                const finalizoCambios = resp.finalizoCambios;
                
                // $('#btnRegistroMultipresupuesto').toggle(finalizoCambios)
                $('#seccionCambiosExcel').toggle(mostrarSeccion);
                $('#btnNuevoPresupuestoExcelV2').toggle(seVeraBoton && finalizoCambios);

                const mostrarSeccionCambios = Number(resp.tipoOportunidad) !== 1;

                $('#seccionCambios, #div-btnrelacionar').toggle(mostrarSeccionCambios);
            },
            error: function() {
                $('#seccionCambiosExcel').hide();
            }
        });
    }

    let btns = [];

    if (($('#Estatus').val() == 7) && (idRolEP == 3 || idRolEP == 2 || idRolEP == 5 || idRolEP == 8 || idRolEP == 1 || idRolEP == 10)) {
        btns.push({
            text: '<i class="fas fa-check-double" title="Activar/Desactivar multi-selección"></i>',
            action: function (e, dt, node, config) {
                if ($('#activarSeleccion').val() == 0) {
                    $('#activarSeleccion').val(1)
                } else {
                    $('#activarSeleccion').val(0)
                    // Desmarcamos todos los checkbox
                    $('input[data-id="markDirection"]').prop('checked', false)
                    // Ocultamos el botón de crear presupuesto múltiple
                    $('#btnNuevoPresupuesto').addClass('d-none');
                }

                tblEP_relationV2();
            }
        });
    }

    let tblElementosV2 = $('#elementosV2').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '70vh',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: true,
        autoWidth: true,
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        ajax: {
            url: serve + "get-list-relation-EPV2/" + idOportunidad,
            type: "GET",
            dataType: "json",
        },
        buttons: [
            ...btns
        ],
        order: [
            [1, 'asc']
        ],
        rowCallback: function(row, data) {
            // Si el campo observaciones_presupuesto tiene valor, agrega el tooltip
            if (data.observaciones_presupuesto && data.observaciones_presupuesto.trim() !== '') {
                row.setAttribute('title', 'Este elemento presupuestado contiene observaciones adicionales que serán incorporadas al presupuesto correspondiente.');
            } else {
                row.removeAttribute('title');
            }
        },
        columnDefs: [
            {
                targets: [12], // fecha pedido
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
                type: 'date'
            },
            {
                targets: [13],
                visible: $('#activarSeleccion').val() == 1 ? false : true,
            },
            {
                targets: [13],
                orderable: false,
            }
        ],
        pageLength: 12,
        lengthMenu: [
            [12],
            [12]
        ],
        columns: [
            {
                data: 'folio',
                render: function (data, type, row) {
                    if ($('#activarSeleccion').val() == 1) {
                        return `
                            <div class="text-center">
                                <input type="checkbox" data-id="markDirection" value="${row.id}" class="cursor-pointer" data-plantilla="${row.plantilla_id}" />
                            </div>
                        `;
                    } else {
                        return ``;
                    }
                }
            },
            {
                data: 'folio',
                className: 'text-center',
            },
            {
                data: 'nombre_proyecto',
                className: 'text-center',
                render: function(data, type, row) {
                    return `
                        ${row.tiene_cambio_especificaciones == 1 ? '<i style="border-radius: 50px; background-color: #7f9353; color: #fff; padding: 3px; font-size: 8px;">CE</i>' : ''}
                        ${row.tiene_cambio_precios == 1 ? '<i style="border-radius: 50px; background-color: #5b64c6; color: #fff; padding: 3px; font-size: 8px;">CP</i>' : ''}
                        ${row.tiene_cambios_minimos == 1 ? '<i style="border-radius: 50px; background-color: #990b96; color: #fff; padding: 3px; font-size: 8px;">CM</i>' : ''}
                        ${row.tiene_cambio_texto == 1 ? '<i style="border-radius: 50px; background-color: #a34800; color: #fff; padding: 3px; font-size: 8px;">CT</i>' : ''}
                        <a href="${serve}elementosp/${row.id}/edit/2" class="text-blue-500 hover:underline">${data}</a>
                    `;
                }
            },
            {
                data: 'vendedor',
                className: 'text-center',
            },
            {
                data: 'plantilla',
                className: 'text-center',
            },
            {
                data: 'tipo_producto',
                className: 'text-center',
            },
            {
                data: 'oportunidad',
                className: 'text-center',
            },
            {
                data: 'cliente',
                className: 'text-center',
            },
            {
                data: 'id',
                className: 'text-center',
                title: 'Pdf',
                render: function(data, type, row) {
                    return `<a href="${serve}elementosp/generarPdfElemento/${row.id}/pdf" style="font-size: 1.2em;" target="_blank"><i class="fas fa-file-pdf text-info"></i></a>`;
                }
            },
            {
                data: 'id',
                className: 'text-center',
                title: 'Clonar',
                render: function(data, type, row) {
                    if (row.oportEstatus <= 2  && (idRolEP != 3 && idRolEP != 2)) {
                        return `
                            <i><img src="${serve}img/clon.png" class="cursor-pointer" width="20" onclick="clonarElemento('${row.id}', '${row.nombre_proyecto}', '${row.folio}')" ></i>`;
                    } else {
                        return ``;
                    }
                }
            },
            {
                data: 'estatus',
                className: 'text-center',
                render: function(data, type, row) {
                    if ((row.oportEstatus <= 2 && (idRolEP != 3 && idRolEP != 2)) || (idRolEP == 1)) {
                        return `
                        <div class="custom-control custom-switch">
                            <input id="estatusEP${row.id}" name="estatusEP"
                                type="checkbox" class="custom-control-input"
                                ${row.estatus == 1 ? 'checked' : ''} onclick="changeEstatusEP(${row.id}, this)"
                                />
                            <label class="custom-control-label"
                                for="estatusEP${row.id}"></label>
                        </div>
                        `;
                    } else {
                        return ``;
                    }
                }
            },
            {
                data: 'usuario',
                className: 'text-center',
            },
            {
                data: 'fecha_registro',
                className: 'text-center',
            },
            {
                data: 'id',
                className: 'text-center' + ($('#Estatus').val() != 7 || $('#finalizoCambios').val() == 1 ? ' d-none' : '') ,
                render: function(data, type, row) {
                    if ((row.oportEstatus == 7 && (idRolEP == 2)) || (idRolEP == 1 || idRolEP == 8 || idRolEP == 10)) {
                        let cambioPrioridad = row.cambio_prioridad
                        let campo = 0;
                        let campoResolvio = 0;
                        
                        if (cambioPrioridad == 2) {
                            campo = row.tiene_cambio_especificaciones
                            campoResolvio = row.resolvio_cambio_especificaciones
                        }

                        if (cambioPrioridad == 1) {
                            campo = row.tiene_cambios_minimos
                            campoResolvio = row.resolvio_cambios_minimos
                        }

                        if (cambioPrioridad == 0 || cambioPrioridad == null) {
                            return ``;
                        }

                        let preSolicitoCambios = $('#preSolicitoCambios').val();
                        let solicitoCambios = $('#solicitoCambios').val();

                        if (solicitoCambios == 0 && preSolicitoCambios == 1) {
                            return ``;
                        }

                        if (campoResolvio == 1) {
                            return `
                            <div class="custom-control custom-switch">
                                <input id="finalizarCambio${row.id}" name="finalizarCEP"
                                    type="checkbox" class="custom-control-input"
                                    checked disabled
                                    />
                                <label class="custom-control-label"
                                    for="finalizarCambio${row.id}"></label>
                            </div>`;
                        } else {
                            return `
                            <div class="custom-control custom-switch">
                                <input id="finalizarCambio${row.id}" name="finalizarCEP"
                                    type="checkbox" class="custom-control-input"
                                    ${campoResolvio == 1 ? 'checked' : ''} onclick="finalizarCambio(${row.id}, this)"
                                    />
                                <label class="custom-control-label"
                                    for="finalizarCambio${row.id}"></label>
                            </div>
                            `;
                        }
                    } else {
                        return ``;
                    }
                }
            },
            // Para presupuestos
            {
                data: 'id',
                className: 'text-center',
                render: function(data, type, row) {
                    if (($('#Estatus').val() == 7) && (idRolEP == 3 || idRolEP == 2 || idRolEP == 5 || idRolEP == 8 || idRolEP == 1 || idRolEP == 10)) {

                        let cambioPrioridad = row.cambio_prioridad

                        if (cambioPrioridad == 2 || cambioPrioridad == 1) {
                            return ``;
                        }

                        if ($('#activarSeleccion').val() == 0) {
                            return `
                                <button class="btn btn-info btn-xs btnRegisterNuevoPresupuesto" onclick="abrirModalRegistroPresupuesto(2, ${row.id})" title="Registrar presupuesto" type="button">
                                    Crear Presupuesto
                                </button>
                            `;
                        } else {
                            return ``;
                        }
                    } else {
                        return ``;
                    }

                    // Desbloquear despues y eliminar abajo
                    // return `
                    //     <button class="btn btn-info btn-xs btnRegisterNuevoPresupuesto" onclick="abrirModalRegistroPresupuesto(2, ${row.id})" title="Registrar presupuesto" type="button">
                    //         Crear Presupuesto
                    //     </button>
                    // `;
                },
            },
        ],
        initComplete: function() {
            this.api().columns.adjust();

            // Ajustamos el alto de la tabla según si hay filas o no
            const tieneFilas = this.api().rows().count() > 0;
            $('.dataTables_scrollBody').css('height', tieneFilas ? '170px' : 'auto');
            $('.dataTables_scrollBody').css('overflow-y', 'auto !important');
            $('.dataTables_scroll').css('overflow-y', 'auto !important');

            getUltimoFolioEP();
        },
    });

    $('#elementosV2 tbody').on('click', 'tr', function () {
        var data = tblElementosV2.row(this).data();
        if (data != undefined) {
            mostrarHistoricoCambios(data.id);
        }
    });

    let tblElementosExcel = $('#elementosExcel').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '70vh',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: true,
        autoWidth: true,
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        ajax: {
            url: serve + "obtenerListaExcelesEPs/" + idOportunidad,
            type: "GET",
            dataType: "json",
        },
        buttons: [
        ],
        order: [
            [1, 'asc']
        ],
        columnDefs: [
            {
                targets: [5],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
                type: 'date'
            }
        ],
        pageLength: 12,
        lengthMenu: [
            [12],
            [12]
        ],
        columns: [
            {
                data: 'folio',
                className: 'text-center',
            },
            {
                data: 'totalEps',
                className: 'text-center',
            },
            {
                data: 'usuario',
                className: 'text-center',
            },
            {
                data: 'estatusNombre',
                className: 'text-center',
            },
            {
                data: 'solicitoCambio',
                className: 'text-center',
                render: function(data, type, row) {
                    let estatus = $('#Estatus').val();
                    if (row.solicitoCambio == 1 && estatus == 7) {
                        return `
                            <div class="custom-control custom-switch">
                                <input id="estatusEEP${row.id}" name="estatusEEP"
                                    type="checkbox" class="custom-control-input"
                                    ${row.finalizoCambio == 1 ? 'checked' : ''} onclick="changeEstatusEEP(${row.id}, this)"
                                    />
                                <label class="custom-control-label"
                                    for="estatusEEP${row.id}"></label>
                            </div>
                        `;
                    } else {
                        return ``;
                    }
                }
            },
            {
                data: 'fechaRegistro',
                className: 'text-center',
            },
            // Para presupuestos
            {
                data: 'id',
                className: 'text-center',
                render: function(data, type, row) {
                    const estatusGrupo = Number(row.estatus || 0);
                    const grupoActivo = estatusGrupo === 1;
                    const tieneCambiosPendientes = Number(row.solicitoCambio || 0) === 1 && Number(row.finalizoCambio || 0) !== 1;
                    const puedeGenerarPresupuesto = grupoActivo && !tieneCambiosPendientes;
                    const botonDescarga = grupoActivo
                        ? `<button class="btn btn-success btnDownloadFiles" onclick="downloadFiles(${row.id}, ${estatusGrupo})" title="Descargar archivos" type="button" style="width:20px;height:20px;padding:0;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;line-height:1;">
                            <i class="fas fa-file-download" style="font-size: 12px;"></i>
                        </button>`
                        : '';
                    const botonGenerarPresupuesto = puedeGenerarPresupuesto
                        ? `
                            <button class="btn btn-info btn-xs btnRegisterNuevoPresupuesto" onclick="abrirModalMultipresupuestoExcel(${row.id})" title="Generar presupuesto" type="button">
                                Generar Presupuesto
                            </button>
                        `
                        : '';
                    // const botonEliminar = 

                    // Botones para visualizar los archivos relacionados y boton para descargar los archivos relacionados
                    return `
                        <button class="btn btn-info btnViewFiles" onclick="viewFiles(${row.id}, ${estatusGrupo})" title="Ver archivos" type="button" style="width:20px;height:20px;padding:0;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;line-height:1;">
                            <i class="fas fa-eye" style="font-size: 12px;"></i>
                        </button>
                        ${botonDescarga}
                        ${botonGenerarPresupuesto}
                    `;
                },
            },
        ],
        initComplete: function() {
            this.api().columns.adjust();

             // Ajustamos el alto de la tabla
            const tieneFilas = this.api().rows().count() > 0;
            $('.dataTables_scrollBody').css('height', tieneFilas ? '170px' : 'auto');
            $('.dataTables_scrollBody').css('overflow-y', 'auto !important');
            $('.dataTables_scroll').css('overflow-y', 'auto !important');
        },
    });

    $('#elementosExcelVista').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '70vh',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: true,
        autoWidth: true,
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        ajax: {
            url: serve + "obtenerListaExcelesEPVista/" + idOportunidad,
            type: "GET",
            dataType: "json",
        },
        buttons: [
        ],
        order: [
            [7, 'desc']
        ],
        columnDefs: [
            {
                targets: [7],
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
                type: 'date'
            }
        ],
        pageLength: 12,
        lengthMenu: [
            [12],
            [12]
        ],
        columns: [
            {
                data: 'nombreArchivo',
                className: 'text-left',
                defaultContent: ''
            },
            {
                data: 'extension',
                className: 'text-center',
                defaultContent: ''
            },
            {
                data: 'pesoFormateado',
                className: 'text-center',
                defaultContent: '0 KB'
            },
            {
                data: 'id',
                className: 'text-center',
                render: function(data, type, row) {
                    if (type !== 'display') {
                        return data;
                    }

                    if (row.estatus != 1) {
                        return ``;
                    }

                    return `
                        <button class="btn btn-success btn-xs" type="button" onclick="descargarArchivoExcelVista(${row.id})">
                            <i class="fas fa-file-download"></i>
                        </button>
                    `;
                }
            },
            {
                data: 'Nombre',
                className: 'text-center',
                defaultContent: ''
            },
            {
                data: 'estatusNombre',
                className: 'text-center',
                defaultContent: ''
            },
            {
                data: 'solicitoCambios',
                className: 'text-center',
                render: function(data, type, row) {
                    let estatus = $('#Estatus').val();
                    let role = $('#idRol').val();

                    if (row.solicitoCambios == 1 && estatus == 7 && (role == 2 || role == 10 || role == 1 || role == 8)) {
                        return `
                            <div class="custom-control custom-switch">
                                <input id="estatusEEP${row.id}" name="estatusEEP"
                                    type="checkbox" class="custom-control-input"
                                    ${row.finalizoCambios == 1 ? 'checked' : ''} onclick="changeEstatusExcelEP(${row.id}, this)"
                                    />
                                <label class="custom-control-label"
                                    for="estatusEEP${row.id}"></label>
                            </div>
                        `;
                    } else {
                        return ``;
                    }
                }
            },
            {
                data: 'fechaRegistro',
                className: 'text-center',
                defaultContent: ''
            }
        ],
        initComplete: function() {
            this.api().columns.adjust();

            const tieneFilas = this.api().rows().count() > 0;
            $('.dataTables_scrollBody').css('height', tieneFilas ? '170px' : 'auto');
            $('.dataTables_scrollBody').css('overflow-y', 'auto !important');
            $('.dataTables_scroll').css('overflow-y', 'auto !important');
        },
    });
}

function descargarArchivoExcelVista(idArchivo) {
    if (!idArchivo) {
        Swal.fire('No se encontró el archivo para descargar.');
        return;
    }

    window.location.href = serve + 'descargarExcelEPVista/' + idArchivo;
}

/**
 * Función para cambiar el estatus del excel de EPs desde la vista de cambios de Excel
 * 
 * @param {number} idGrupoEP - ID del grupo de Excel de EPs
 * @param {HTMLInputElement} input - Elemento checkbox que disparó la acción
 * @return {void}
 */
function changeEstatusExcelEP(idGrupoEP, input) {
    const nuevoEstatus = input.checked ? 1 : 0;
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('idExcelEp', idGrupoEP);

    // Preguntamos al usuario si está seguro de cambiar el estatus del grupo de Excel de EPs
    Swal.fire({
        title: 'Cambiar Estatus',
        text: '¿Estás seguro de cambiar el estatus de este Excel?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "cambiarEstatusExcelEP",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Estatus cambiado correctamente');

                        if (response.refresh) {
                            recargarYAbrirTabElementosOport();
                        }
                    }
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire('Error al cambiar el estatus');
                }
            });
        } else {
            input.checked = !input.checked; // revert the checkbox state
        }
    });
}

/**
 * Función para cambiar el estatus de un grupo de Excel de EPs
 * 
 * @param {number} idGrupoEP - ID del grupo de Excel de EPs
 * @param {HTMLInputElement} input - Elemento checkbox que disparó la acción
 * @return {void}
 */
function changeEstatusEEP(idGrupoEP, input) {
    const nuevoEstatus = input.checked ? 1 : 0;
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('idGrupoEP', idGrupoEP);

    // Preguntamos al usuario si está seguro de cambiar el estatus del grupo de Excel de EPs
    Swal.fire({
        title: 'Cambiar Estatus',
        text: '¿Estás seguro de cambiar el estatus de este grupo de Excel de EPs?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "cambiarEstatusEEP",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.response) {
                        tblEP_relationV2();

                        const Msg = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Estatus cambiado correctamente');

                        if (response.refresh) {
                            location.reload();
                        }
                    }
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire('Error al cambiar el estatus');
                }
            });
        } else {
            input.checked = !input.checked; // revert the checkbox state
        }
    });
}

/**
 * Función para mostrar el histórico de cambios de un elemento presupuestado
 * 
 * @param {number} idEP - ID del elemento presupuestado
 * @return {void}
 */
function mostrarHistoricoCambios(idEP) {
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('id', idEP);

    $.ajax({
        url: serve + "elementosp/historicoCambios",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.response) {
                let cambios = response.cambios;
                let contenedor = $('#historicoCambiosContainer');

                if (cambios.length > 0) {
                    let html = `
                        <h5>Histórico de Cambios</h5>
                        
                        <div style="max-height: 200px; overflow-y: auto;">
                        <table class="table-auto w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Campo</th>
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Valor Anterior</th>
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Valor Nuevo</th>
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Tipo de Cambio</th>
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Estatus</th>
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Ciclo</th>
                                    <th class="px-2 py-0.5 border bg-secondary text-white">Fecha de Cambio</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    cambios.forEach(cambio => {
                        html += `
                            <tr class="${cambio.estatus == 'PENDIENTE' ? 'bg-yellow-100' : ''}">
                                <td class="px-2 py-0.5 border">${cambio.campo}</td>
                                <td class="px-2 py-0.5 border">${cambio.valorAnterior}</td>
                                <td class="px-2 py-0.5 border">${cambio.valorNuevo}</td>
                                <td class="px-2 py-0.5 border">${cambio.tipo}</td>
                                <td class="px-2 py-0.5 border">${cambio.estatus}</td>
                                <td class="px-2 py-0.5 border text-center">${cambio.ciclo}</td>
                                <td class="px-2 py-0.5 border">${formatoFecha(cambio.fechaRegistro)}</td>
                            </tr>
                        `;
                    });

                    html += `
                            </tbody>
                        </table>
                        </div>
                    `;
                    contenedor.html(html);
                } else {
                    contenedor.html('<p>No hay cambios registrados para este elemento presupuestado.</p>');
                }
            }
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Msg.fire({
                icon: 'error',
                title: 'Ocurrió un error en la solicitud.'
            });
        }
    });
}

function formatoFecha(fecha) {
    if (!fecha) return '';
    const opciones = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };
    return new Date(fecha).toLocaleDateString('es-MX', opciones);
}


/**
 * Función para finalizar los cambios de un elemento presupuestado
 * 
 * 
 * @param {number} idEP - ID del elemento presupuestado
 * @param {HTMLInputElement} input - Elemento checkbox que disparó la acción
 * @return {void}
 */
function finalizarCambio(idEP, input) {
    Swal.fire({
        title: 'Finalizar Cambios',
        text: '¿Estás seguro de finalizar los cambios del elemento presupuestado?', 
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, finalizar',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('id', idEP);
            
            $.ajax({
                url: serve + "elementosp/finalizarCambio",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Cambios finalizados correctamente');

                        tblEP_relationV2();

                        if (response.finalizoCambios) {
                            // Refrescamos la pagina
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        }
                    } else {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "top-end",
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        Msg.fire('Ocurrió un error al finalizar los cambios.');
                    }
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Msg.fire('Ocurrió un error en la solicitud.');
                }
            });
        } else {
            input.checked = !input.checked; // revert the checkbox state
        }
    });
}

var ultimoFolio = 0;

/**
 * Función para obtener el ultimo folio de un elemento presupuestado
 * 
 * @return {void}
 */
async function getUltimoFolioEP() {
    try {
        const response = await $.ajax({
            url: serve + "elementosp/ultimo-folio",
            type: "GET",
            dataType: "json",
        });

        if (response.success) {
            ultimoFolio = response.folio;
        } else {
            throw new Error("No se pudo obtener el último folio");
        }
    } catch (error) {
        return null;
    }
}



/**
 * Función para clonar un elemento presupuestado
 * 
 * @param {number} id - ID del elemento presupuestado a clonar
 * @param {string} nombreProyectoOrg - Nombre del proyecto original
 * @param {string} folioOrg - Folio original del elemento presupuestado
 * @return {void}
 */
function clonarElemento(id, nombreProyectoOrg, folioOrg) {
    const ultimoFolioEp = ultimoFolio;

    Swal.fire({
        title: 'Clonar Elemento Presupuestado',
        input: 'text',
        html: `
            <p>Nombre del Proyecto Original: ${nombreProyectoOrg || 'N/A'}</p>
            <p>Folio Original: ${folioOrg || 'N/A'}</p>
            <p>Nuevo Folio ${ultimoFolio}.</p>
        `,
        inputValue: nombreProyectoOrg || '',
        inputLabel: 'Nombre del Proyecto',
        inputPlaceholder: 'Ingrese el nombre del proyecto',
        showCancelButton: true,
        confirmButtonText: 'Clonar',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) {
                return 'El nombre del proyecto es obligatorio';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "elementosp/clonar",
                type: "POST",
                data: {
                    _token: CSRF_TOKEN,
                    _method: "POST",
                    id: id,
                    nombre_proyecto: result.value,
                },
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
                            title: "Elemento clonado correctamente",
                        });
                        tblEP_relationV2();
                    } else {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "warning",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        Msg.fire({
                            title: "Ocurrió un error al clonar el elemento",
                        });
                    }
                },
                error: function() {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    Msg.fire({
                        title: "Error al clonar el elemento",
                    });
                },
            });
        }
    });
}

/**
 * Función para cambiar el estatus de un elemento presupuestado
 * 
 * @param {number} id - ID del elemento presupuestado
 * @return {void}
 */
function changeEstatusEP(idEP, input) {
    let estatus = input.checked ? 1 : 0;

    Swal.fire({
        title: 'Eliminar Elemento',
        text: '¿Estás seguro de eliminar el elemento presupuestado?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + "elementosp/changeEstatusEP",
                type: "POST",
                data: {
                    _token: CSRF_TOKEN,
                    _method: "POST",
                    id: idEP,
                    estatus: estatus,
                },
                success: function(data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        Msg.fire({
                            title: "Elemento eliminado correctamente",
                        });
                        tblEP_relationV2();
                    } else {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: "warning",
                            showConfirmButton: false,
                            timer: 3000,
                        });

                        Msg.fire({
                            title: "Ocurrió un error al eliminar el elemento",
                        });
                    }
                },
                error: function() {
                    input.checked = !input.checked; // revert the checkbox state
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "error",
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    Msg.fire({
                        title: "Error al eliminar el elemento",
                    });
                },
            });
        } else {
            input.checked = !input.checked; // revert the checkbox state
        }
    });
}

/**
 * Función para abrir el modal de registro de presupuesto
 * 
 * @param {number} tipo - Tipo de presupuesto (1: múltiple, 2: individual)
 * @param {number} idEp - ID del elemento presupuestado (opcional)
 * @return {void}
 */
function abrirModalRegistroPresupuesto(tipo, idEp = null) {
    // Validamos que si es múltiple se haya seleccionado al menos 2 elementos
    if (tipo == 1 && $('input[data-id="markDirection"]:checked').length < 2) {
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000,
        });

        Msg.fire({
            title: "Debes seleccionar al menos 2 elementos",
        });
        return;
    }

    $('#modal_registro_presupuesto').modal('show');

    // Obtenemos el listado de los elementos presupuestados seleccionados
    let elementosSeleccionados = [];
    let idOportunidad = $('#idOportunidad').val();

    if (tipo == 1) {
        $('input[data-id="markDirection"]:checked').each(function() {
            elementosSeleccionados.push($(this).val());
        });
    } else if (idEp) {
        elementosSeleccionados.push(idEp);
    }

    let elementosData = [];

    $.ajax({
        url: serve + "getElementosPresupuestadosByIds",
        type: "POST",
        data: {
            _token: CSRF_TOKEN,
            ids: elementosSeleccionados,
            idOportunidad: idOportunidad
        },
        success: function(data) {
            if (data.response) {
                elementosData = data.data;
    
                if (tipo == 1) {
                    $('#tipoPresupuestoT').html('múltiple');
                } else {
                    $('#tipoPresupuestoT').html('individual');
                }

                $('#tituloPresupuesto').val(data.tituloPresupuesto);
    
                // vamos a crear el formulario dinámicamente
                let formularioHtml = ``;
    
                for (let elemento of elementosData) {
                    function formatoCantidad(valor) {
                        let num = parseInt(valor);
                        if (isNaN(num)) num = 0;
                        return num.toLocaleString('es-MX');
                    }

                    // Obtener el HTML de los selects ocultos y clonar para este elemento
                    let categoriasFscHtml = $('#categoriasFsc')
                        .clone()
                        .removeClass('sr-only')
                        .addClass('form-control')
                        .attr('id', 'categoriasFsc' + elemento.id)[0].outerHTML;
                    let subcategoriasFscHtml = $('#subcategoriasFsc')
                        .clone()
                        .removeClass('sr-only')
                        .addClass('form-control')
                        .attr('id', 'subcategoriasFsc' + elemento.id)[0].outerHTML;

                    formularioHtml += `
                        <div id="elementoPresupuestadoForm${elemento.id}" class="elemento-presupuestado-section mb-4">
                            <div class="form-group form-grp">
                                <h6><strong>Elemento Presupuestado: </strong> <span class="text-primary">${elemento.folio} - ${elemento.nombre_proyecto}</span></h6>
                                <div class="content-d-form d-flex">
                                    <p><strong>Familia:</strong> ${elemento.plantilla}</p>
                                    <p><strong>Tipo:</strong> ${elemento.tipo_producto}</p>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-3 w-full">
                                <div class="form-group form-grp w-100">
                                    <label for="folioPresupuesto${elemento.id}">Folio de la tripa: <span class="text-danger">*</span></label>
                                    <input class="form-control" id="folioPresupuesto${elemento.id}" maxlength="500" placeholder="Escribe el folio aquí..." onblur="buscarFolioPresupuesto(this.value, ${elemento.id}); obtenerFSCporFolio(this, ${elemento.id});">
                                </div>
                            </div>

                            <div class="d-flex gap-3 w-full ${elemento.cantidad_cotizar_1 != 0 ? '' : 'sr_only'}">
                                <div class="form-group form-grp w-100">
                                    <label for="cantidad1${elemento.id}">Cantidad 1 (E.P): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled id="cantidad1${elemento.id}" data-id="number-field" maxlength="500" placeholder="Escribe la cantidad aquí..." value="${formatoCantidad(parseInt(elemento.cantidad_cotizar_1)) || 0}">
                                </div>

                                <div class="form-group form-grp w-100">
                                    <label for="precio1${elemento.id}">Precio (Unitario): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="precio1${elemento.id}" data-id="number-field" maxlength="500" placeholder="Escribe el precio aquí...">
                                </div>
                            </div>

                            <div class="d-flex gap-3 w-full ${elemento.cantidad_cotizar_2 != 0 ? '' : 'sr_only'}">
                                <div class="form-group form-grp w-100">
                                    <label for="cantidad2${elemento.id}">Cantidad 2 (E.P): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled id="cantidad2${elemento.id}" data-id="number-field" maxlength="500" placeholder="Escribe la cantidad aquí..." value="${formatoCantidad(parseInt(elemento.cantidad_cotizar_2)) || 0}">
                                </div>

                                <div class="form-group form-grp w-100">
                                    <label for="precio2${elemento.id}">Precio (Unitario): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="precio2${elemento.id}" data-id="number-field" maxlength="500" placeholder="Escribe el precio aquí...">
                                </div>
                            </div>

                            <div class="d-flex gap-3 w-full ${elemento.cantidad_cotizar_3 != 0 ? '' : 'sr_only'}">
                                <div class="form-group form-grp w-100">
                                    <label for="cantidad3${elemento.id}">Cantidad 3 (E.P): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" disabled id="cantidad3${elemento.id}" data-id="number-field" maxlength="500" placeholder="Escribe la cantidad aquí..." value="${formatoCantidad(parseInt(elemento.cantidad_cotizar_3)) || 0}">
                                </div>

                                <div class="form-group form-grp w-100">
                                    <label for="precio3${elemento.id}">Precio (Unitario): <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="precio3${elemento.id}" data-id="number-field" maxlength="500" placeholder="Escribe el precio aquí...">
                                </div>
                            </div>
                    `;

                    var numPresupuesto = '';

                    $.ajax({
                        url: serve + "obtenerFolioPresupuestosEP",
                        data: {
                            folio: elemento.folio
                        },
                        type: "GET",
                        success: function(data) {
                            if (data.response) {
                                numPresupuesto = data.numPresupuesto || '';
                                $('#folioPresupuesto' + elemento.id).val(numPresupuesto);
                                
                                buscarFolioPresupuesto(numPresupuesto, elemento.id);
                            }
                        }
                    });

                    formularioHtml += `
                        <div class="d-flex gap-3 w-full">
                            <div id="cantidadesAdicionalesContainer${elemento.id}" class="w-100"></div>
                        </div>
                        <div class="d-flex gap-3 w-full form-group form-grp w-100">
                            <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="agregarCantidadAdicional('${elemento.id}')">
                                Agregar cantidad adicional
                            </button>
                        </div>

                        <!-- Espacio para validacion e insercion de medidas extendidas y finales -->
                        <div><input type="hidden" id="plantillaID${elemento.id}" value="0"></div>
                        <div id="medidasContainer${elemento.id}" class="w-full">
                        </div>

                        <div class="container-fsc-options sr-only" id="fscOptionsContainer${elemento.id}">
                            <h6 class="mt-3 px-2 font-weight-bold">Información FSC (<small>Esta información se agregará al presupuesto, asi como el logo FSC</small>)</h6>
                            <div><input type="hidden" id="fscIsActive${elemento.id}" value="0"></div>
                            <div class="fsc-selects-container" id="fscSelectsContainer${elemento.id}">
                                <div id="fscTextos${elemento.id}" style="display:none; display: flex; justify-content: space-between;"></div>
                            </div>
                        </div>
                    `;

                    formularioHtml += `
                        </div>
                        <style>
                            .sr_only {
                                position: absolute !important;
                                width: 1px !important;
                                height: 1px !important;
                                padding: 0 !important;
                                margin: -1px !important;
                                overflow: hidden !important;
                                clip: rect(0,0,0,0) !important;
                                white-space: nowrap !important;
                                border: 0 !important;
                            }
                        </style>
                    `;

                    if (elemento != elementosData[elementosData.length - 1]) {
                        formularioHtml += `<hr class="mt-4">`;
                    }
                }

                $('#formRegistroPresupuesto').html(formularioHtml);

                setTimeout(() => {
                    // Pintar medidas para cada elemento después del render
                    for (let elemento of elementosData) {
                        $('#medidasContainer' + elemento.id).html('');
                        obtenerMedidasEP(elemento.id);
                    }

                    $('input[id^="cantidad"], input[id^="precio"], .precio-adicional').each(function() {
                        $(this).attr('inputmode', 'decimal');
                        $(this).on('input', function() {
                            let value = this.value.replace(/[^0-9.]/g, '');
                            value = value.replace(/(\..*)\./g, '$1');
                            if (value.indexOf('.') >= 0) {
                                value = value.replace(/^(\d+)\.(\d{0,3}).*$/, '$1.$2');
                            }
                            let partes = value.split('.');
                            partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            this.value = partes.length > 1 ? partes[0] + '.' + partes[1] : partes[0];
                        });
                        $(this).on('blur', function() {
                            if (this.value !== '') {
                                let num = parseFloat(this.value.replace(/,/g, ''));
                                if (!isNaN(num)) {
                                    this.value = num.toLocaleString('es-MX', { minimumFractionDigits: 3, maximumFractionDigits: 3 });
                                }
                            }
                        });
                        $(this).on('focus', function() {
                            this.value = this.value.replace(/,/g, '');
                        });
                    });

                    // Evento para mostrar/ocultar selects FSC por cada checkbox
                    $('.fsc-checkbox').off('change').on('change', function() {
                        const id = $(this).data('elemento-id');
                        if ($(this).is(':checked')) {
                            $('#fscSelectsContainer' + id).slideDown(150);
                        } else {
                            $('#fscSelectsContainer' + id).slideUp(150);
                        }
                    });
                }, 100);
            }

        },
        error: function() {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "error",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Error al obtener los elementos presupuestados",
            });
        }
    });
}

// Función para obtener las medidas extendidas y finales de un elemento presupuestado por folio
function obtenerMedidasEP(idEp) {
    if (!idEp) return;

    $.ajax({
        url: serve + "obtenerMedidasEP", // Ajusta la ruta si es diferente
        type: "GET",
        dataType: "json",
        data: {
            ep: idEp
        },
        success: function(data) {
            if (data.success) {
                $('#plantillaID' + idEp).val(data.plantilla_id);
                let medidas = data.data;
                let medidasHtml = ``;


                if (data.plantilla_id == 3) {
                    medidasHtml += `
                        <div class="mt-3 px-2">
                            <h6 class="font-weight-bold">Medidas Extendidas</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 33%">Ancho</th>
                                            <th style="width: 33%">Largo</th>
                                            <th style="width: 33%">Alto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAncho${idEp}" maxlength="500" placeholder="Medida extendida (Ancho)" value="${medidas.medida_extendida_ancho || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaLargo${idEp}" maxlength="500" placeholder="Medida extendida (Largo)" value="${medidas.medida_extendida_largo || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAlto${idEp}" maxlength="500" placeholder="Medida extendida (Alto)" value="${medidas.medida_extendida_alto || ''}"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <h6 class="font-weight-bold mt-3">Medidas Finales</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 33%">Ancho</th>
                                            <th style="width: 33%">Largo</th>
                                            <th style="width: 33%">Alto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" id="medidaFinalAncho${idEp}" maxlength="500" placeholder="Medida final (Ancho)" value="${medidas.medida_final_ancho || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalLargo${idEp}" maxlength="500" placeholder="Medida final (Largo)" value="${medidas.medida_final_largo || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalAlto${idEp}" maxlength="500" placeholder="Medida final (Alto)" value="${medidas.medida_final_alto || ''}"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                }

                if (data.plantilla_id == 2) {
                    // Empaques: renderizar en formato tabla, dos apartados (extendidas y finales)
                    // Mostrar Tacón 1 y 2 solo si corresponde
                    const showT1 = medidas.tiene_tacon_1;
                    const showT2 = medidas.tiene_tacon_2;
                    medidasHtml += `
                        <div class="mt-3 px-2">
                            <h6 class="font-weight-bold">Medidas Extendidas</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 20%">Categoría</th>
                                            <th style="width: 26%">Ancho</th>
                                            <th style="width: 26%">Largo</th>
                                            <th style="width: 26%">Alto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>General</td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAnchoGeneral${idEp}" maxlength="500" placeholder="Ancho general" value="${medidas.medida_extendida_ancho_general || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaLargoGeneral${idEp}" maxlength="500" placeholder="Largo general" value="${medidas.medida_extendida_largo_general || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAltoGeneral${idEp}" maxlength="500" placeholder="Alto general" value="${medidas.medida_extendida_alto_general || ''}"></td>
                                        </tr>
                                        ${showT1 ? `
                                        <tr>
                                            <td>Tacón 1</td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAnchoT1${idEp}" maxlength="500" placeholder="Ancho T1" value="${medidas.medida_extendida_ancho_t1 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaLargoT1${idEp}" maxlength="500" placeholder="Largo T1" value="${medidas.medida_extendida_largo_t1 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAltoT1${idEp}" maxlength="500" placeholder="Alto T1" value="${medidas.medida_extendida_alto_t1 || ''}"></td>
                                        </tr>
                                        ` : ''}
                                        ${showT2 ? `
                                        <tr>
                                            <td>Tacón 2</td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAnchoT2${idEp}" maxlength="500" placeholder="Ancho T2" value="${medidas.medida_extendida_ancho_t2 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaLargoT2${idEp}" maxlength="500" placeholder="Largo T2" value="${medidas.medida_extendida_largo_t2 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaExtendidaAltoT2${idEp}" maxlength="500" placeholder="Alto T2" value="${medidas.medida_extendida_alto_t2 || ''}"></td>
                                        </tr>
                                        ` : ''}
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <h6 class="font-weight-bold mt-3">Medidas Finales</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 20%">Categoría</th>
                                            <th style="width: 26%">Ancho</th>
                                            <th style="width: 26%">Largo</th>
                                            <th style="width: 26%">Alto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>General</td>
                                            <td><input type="text" class="form-control" id="medidaFinalAnchoGeneral${idEp}" maxlength="500" placeholder="Ancho general" value="${medidas.medida_final_ancho_general || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalLargoGeneral${idEp}" maxlength="500" placeholder="Largo general" value="${medidas.medida_final_largo_general || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalAltoGeneral${idEp}" maxlength="500" placeholder="Alto general" value="${medidas.medida_final_alto_general || ''}"></td>
                                        </tr>
                                        ${showT1 ? `
                                        <tr>
                                            <td>Tacón 1</td>
                                            <td><input type="text" class="form-control" id="medidaFinalAnchoT1${idEp}" maxlength="500" placeholder="Ancho T1" value="${medidas.medida_final_ancho_t1 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalLargoT1${idEp}" maxlength="500" placeholder="Largo T1" value="${medidas.medida_final_largo_t1 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalAltoT1${idEp}" maxlength="500" placeholder="Alto T1" value="${medidas.medida_final_alto_t1 || ''}"></td>
                                        </tr>
                                        ` : ''}
                                        ${showT2 ? `
                                        <tr>
                                            <td>Tacón 2</td>
                                            <td><input type="text" class="form-control" id="medidaFinalAnchoT2${idEp}" maxlength="500" placeholder="Ancho T2" value="${medidas.medida_final_ancho_t2 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalLargoT2${idEp}" maxlength="500" placeholder="Largo T2" value="${medidas.medida_final_largo_t2 || ''}"></td>
                                            <td><input type="text" class="form-control" id="medidaFinalAltoT2${idEp}" maxlength="500" placeholder="Alto T2" value="${medidas.medida_final_alto_t2 || ''}"></td>
                                        </tr>
                                        ` : ''}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;
                }

                $('#medidasContainer' + idEp).html(medidasHtml);
            }
        },
        error: function() {
            console.error("Error al obtener las medidas extendidas y finales");
        }
    });

}

// Consulta la categoría y subcategoría FSC por folio y muestra en texto plano si existen valores válidos
function obtenerFSCporFolio(input, idEp) {
    if (!input || !idEp) return;
    const folio = $(input).val();
    $.ajax({
        url: serve + "obtenerCategoriaFSCporFolio", // Ajusta la ruta si es diferente
        type: "GET",
        dataType: "json",
        data: {
            folio: folio,
            ep: idEp
        },
        success: function(data) {
            if (data.success) {
                if (data == null || data.data == null) {
                    $('#fscTextos' + idEp).hide();
                    $('#fscIsActive' + idEp).val('0');
                    $('#fscOptionsContainer' + idEp).addClass('sr-only');
                    return;
                }

                // Se espera que data.categoria y data.subcategoria sean los valores
                var categoria = data.data.usr_pureza ? data.data.usr_pureza.trim() : '';
                var subcategoria = data.data.usr_fsc ? data.data.usr_fsc.trim() : '';
                var hayValores = categoria !== '' && subcategoria !== '';
                var fscTextos = $('#fscTextos' + idEp);
                var fscOptionsContainer = $('#fscOptionsContainer' + idEp);
                var fscIsActive = $('#fscIsActive' + idEp);
                if (hayValores) {
                    fscTextos.html(
                        `<div class='form-group form-grp mb-2'><label>Categoría FSC:</label> <span class='font-weight-bold text-primary'>${categoria}</span><input type='hidden' value='${data.idCategoria}' id='categoriasFsc${idEp}'></div>` +
                        `<div class='form-group form-grp mb-2'><label>Subcategoría FSC:</label> <span class='font-weight-bold text-primary'>${subcategoria}</span><input type='hidden' value='${data.idSubCategoria}' id='subcategoriasFsc${idEp}'></div>`
                    ).show();
                    fscIsActive.val('1');
                    fscOptionsContainer.removeClass('sr-only');
                } else {
                    fscTextos.hide();
                    fscIsActive.val('0');
                    fscOptionsContainer.addClass('sr-only');
                }
            } else {
                $('#fscTextos' + idEp).hide();
                $('#fscIsActive' + idEp).val('0');
                $('#fscOptionsContainer' + idEp).addClass('sr-only');
            }
        },
        error: function() {
            // Si hay error, mostrar selects
            $('#fscTextos' + idEp).hide();
            $('#fscIsActive' + idEp).val('0');
            $('#fscOptionsContainer' + idEp).addClass('sr-only');
        }
    });
}

/**
 * Función para remover una cantidad y precio adicional
 *
 * @param {*} btn
 * @param {*} idElemento
 * @return
 */
function removerCantidadAdicional(btn, idElemento) {
    const row = btn.closest('.cantidad-adicional-row');
    if (row) {
        row.remove();
        // Reajusta los números de las etiquetas
        const container = document.getElementById('cantidadesAdicionalesContainer' + idElemento);
        const rows = container.querySelectorAll('.cantidad-adicional-row');
        rows.forEach((r, i) => {
            const label = r.querySelector('.label-cantidad');
            if (label) label.textContent = `Cantidad ${i + 4} (E.P):`;
            const input = r.querySelector('input[id^="cantidad"]');
            if (input) input.id = `cantidad${i + 4}${idElemento}`;
            const precioInput = r.querySelector('input[id^="precio"]');
            if (precioInput) precioInput.id = `precio${i + 4}${idElemento}`;
        });
    }
}

/**
 * Función para agregar una nueva cantidad y precio adicional
 *
 * @param {*} idElemento
 * @returns
 */
function agregarCantidadAdicional(idElemento) {
    const container = document.getElementById('cantidadesAdicionalesContainer' + idElemento);
    if (!container) return;

    let nPresupuesto = $('#folioPresupuesto' + idElemento).val();

    const index = container.querySelectorAll('.cantidad-adicional-row').length + 4;
    const row = document.createElement('div');
    row.className = 'd-flex gap-3 w-full cantidad-adicional-row mb-2';
    row.innerHTML = `
        <div class="form-group form-grp w-100">
            <label class="label-cantidad" for="cantidad${index}${idElemento}">Cantidad ${index} (E.P):</label>
            <input type="text" class="form-control cantidad-adicional" id="cantidad${index}${idElemento}" maxlength="500" placeholder="Escribe la cantidad aquí..." onblur="buscarCantidadPresupuesto('${nPresupuesto}', '${idElemento}', this.value, 'precio${index}${idElemento}')">
        </div>
        <div class="form-group form-grp w-100">
            <label for="precio${index}${idElemento}">Precio (Unitario):</label>
            <input type="text" class="form-control precio-adicional" id="precio${index}${idElemento}" maxlength="500" placeholder="Escribe el precio aquí...">
        </div>
        <div>
            <button type="button" class="btn btn-danger btn-sm btn-remove-adicional text-sm" onclick="removerCantidadAdicional(this, '${idElemento}')" style="margin-top: 34px;">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(row);

    // Aplica la máscara de moneda al nuevo campo de precio
    setTimeout(() => {
        $('#precio' + index + idElemento).attr('inputmode', 'decimal').on('input', function() {
            let value = this.value.replace(/[^0-9.]/g, '');
            value = value.replace(/(\..*)\./g, '$1');
            if (value.indexOf('.') >= 0) {
                value = value.replace(/^(\d+)\.(\d{0,2}).*$/, '$1.$2');
            }
            let partes = value.split('.');
            partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            this.value = partes.length > 1 ? partes[0] + '.' + partes[1] : partes[0];
        }).on('blur', function() {
            if (this.value !== '') {
                let num = parseFloat(this.value.replace(/,/g, ''));
                if (!isNaN(num)) {
                    this.value = num.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        });

        $('#cantidad' + index + idElemento).on('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            let partes = value.split(',');
            partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            this.value = partes[0];
        });
    }, 100);
}

/**
 * Función para buscar la cantidad del presupuesto y obtener su importe
 * 
 * @param {string} value - Folio del presupuesto
 * @param {number} idEp - ID del elemento presupuestado
 * @param {string} cantidad - Cantidad adicional
 * @param {string} precioId - ID del campo de precio a actualizar
 * @return {void}
 */
function buscarCantidadPresupuesto(value, idEp, cantidad, precioId) {
    $.ajax({
        url: serve + "obtenerPrecioPresupuestosEP",
        type: "GET",
        dataType: "json",
        data: {
            _token: CSRF_TOKEN,
            search: value,
            ep: idEp,
            cantidad: cantidad
        },
        success: function(data) {
            if (data.response) {
                let precio = data.precio || 0;

                function formatoPrecio(valor) {
                    let num = parseFloat(valor);
                    if (isNaN(num)) num = 0;
                    return num.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                $('#' + precioId).val(formatoPrecio(precio));
            }
        },
        error: function() {
            Swal.fire("¡El Presupuesto capturado no existe!");
        }
    });
}

/**
 * Función para buscar el folio del presupuesto y obtener su importe
 * 
 * @param {string} value - Folio del presupuesto
 * @param {number} idEp - ID del elemento presupuestado
 * @return {void}
 */
function buscarFolioPresupuesto(value, idEp) {
    numPresupuesto = value;
    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('search', numPresupuesto);
    formData.append('ep', idEp);

    $.ajax({
        url: serve + "obtenerNumPresupuestosEP",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.response) {
                let cantidad1 = data.data.cantidad_1 || 0;
                let cantidad2 = data.data.cantidad_2 || 0;
                let cantidad3 = data.data.cantidad_3 || 0;

                function formatoPrecio(valor) {
                    let num = parseFloat(valor);
                    if (isNaN(num)) num = 0;
                    return num.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                $('#precio1' + idEp).val(formatoPrecio(cantidad1));
                $('#precio2' + idEp).val(formatoPrecio(cantidad2));
                $('#precio3' + idEp).val(formatoPrecio(cantidad3));
            }
        },
        error: function() {
            Swal.fire("¡El Presupuesto capturado no existe!");

            $('#folioPresupuesto' + idEp).val('');
        }
    });
}

// Evento para detectar el cambio en los checkbox de selección múltiple
$(document).on('change', 'input[data-id="markDirection"]', function() {
    // Obtenemos todos los checkboxes seleccionados
    let $checked = $('input[data-id="markDirection"]:checked');
    let $all = $('input[data-id="markDirection"]');
    
    if ($checked.length > 0) {
        // Tomamos la plantilla del primer seleccionado
        let plantillaSeleccionada = $checked.first().attr('data-plantilla');
        // Deshabilitamos los que no sean de esa plantilla
        $all.each(function() {
            if ($(this).attr('data-plantilla') !== plantillaSeleccionada && !$(this).is(':checked')) {
                $(this).prop('disabled', true);
            }
        });
    } else {
        // Si no hay ninguno seleccionado, habilitamos todos
        $all.prop('disabled', false);
    }

    // Mostrar/ocultar botón según la cantidad seleccionada
    if ($checked.length > 1) {
        $('#btnNuevoPresupuesto').removeClass('d-none');
        $('.btnRegisterNuevoPresupuesto').addClass('d-none');
    } else {
        $('#btnNuevoPresupuesto').addClass('d-none');
        $('.btnRegisterNuevoPresupuesto').removeClass('d-none');
    }
});

/**
 * Función para realizar la validación de los campos del presupuesto
 * 
 * @return mixed
 */
function validarCamposPresupuesto() {
    // Validamos que se hayan llenado todos los campos
    let elementosPresupuestados = [];
    let isValid = true;

    $('.elemento-presupuestado-section').each(function() {
        let idEp = $(this).attr('id').replace('elementoPresupuestadoForm', '');
        let folioPresupuesto = $('#folioPresupuesto' + idEp).val().trim();

        let tituloPresupuesto = $('#tituloPresupuesto').val().trim();

        if (tituloPresupuesto === '') {
            isValid = false;
            $('#tituloPresupuesto').addClass('border border-danger');
        } else {
            $('#tituloPresupuesto').removeClass('border border-danger');
        }

        let cantidad1 = $('#cantidad1' + idEp).val().trim();
        let precio1 = $('#precio1' + idEp).val().trim();

        let cantidad2 = $('#cantidad2' + idEp).val().trim();
        let precio2 = $('#precio2' + idEp).val().trim();

        let cantidad3 = $('#cantidad3' + idEp).val().trim();
        let precio3 = $('#precio3' + idEp).val().trim();

        // FSC: ¿checkbox activo?
        let fscActivo = $('#fscIsActive' + idEp).val() == '1';
        let categoriaFsc = null;
        let subcategoriaFsc = null;
        if (fscActivo) {
            categoriaFsc = $('#categoriasFsc' + idEp).val();
            subcategoriaFsc = $('#subcategoriasFsc' + idEp).val();
            // Validar selects FSC
            if (!categoriaFsc || categoriaFsc === "") {
                isValid = false;
                $('#categoriasFsc' + idEp).addClass('border border-danger');
            } else {
                $('#categoriasFsc' + idEp).removeClass('border border-danger');
            }
            if (!subcategoriaFsc || subcategoriaFsc === "") {
                isValid = false;
                $('#subcategoriasFsc' + idEp).addClass('border border-danger');
            } else {
                $('#subcategoriasFsc' + idEp).removeClass('border border-danger');
            }
        } else {
            // Si no está activo, limpiar posibles marcas
            $('#categoriasFsc' + idEp).removeClass('border border-danger');
            $('#subcategoriasFsc' + idEp).removeClass('border border-danger');
        }

        // Validar campos de medidas extendidas y finales (solo si son inputs, no p)
        let plantillaId = $('#plantillaID' + idEp).val();
        let medidasInputs = [];
        if (plantillaId == 3) {
            medidasInputs = [
                '#medidaExtendidaLargo' + idEp,
                '#medidaExtendidaAncho' + idEp,
                '#medidaExtendidaAlto' + idEp,
                '#medidaFinalLargo' + idEp,
                '#medidaFinalAncho' + idEp,
                '#medidaFinalAlto' + idEp
            ];
        } else if (plantillaId == 2) {
            medidasInputs = [
                '#medidaExtendidaAltoGeneral' + idEp,
                '#medidaExtendidaAltoT1' + idEp,
                '#medidaExtendidaAltoT2' + idEp,
                '#medidaExtendidaAnchoGeneral' + idEp,
                '#medidaExtendidaAnchoT1' + idEp,
                '#medidaExtendidaAnchoT2' + idEp,
                '#medidaExtendidaLargoGeneral' + idEp,
                '#medidaExtendidaLargoT1' + idEp,
                '#medidaExtendidaLargoT2' + idEp,
                '#medidaFinalAltoGeneral' + idEp,
                '#medidaFinalAltoT1' + idEp,
                '#medidaFinalAltoT2' + idEp,
                '#medidaFinalAnchoGeneral' + idEp,
                '#medidaFinalAnchoT1' + idEp,
                '#medidaFinalAnchoT2' + idEp,
                '#medidaFinalLargoGeneral' + idEp,
                '#medidaFinalLargoT1' + idEp,
                '#medidaFinalLargoT2' + idEp
            ];
        }
        medidasInputs.forEach(function(selector) {
            let $input = $(selector);
            if ($input) {
                if ($input.length && $input.is('input')) {
                    let val = $input.val().trim();
                    // Acepta 0 como válido
                    if (val === '') {
                        isValid = false;
                        $input.addClass('border border-danger');
                    } else {
                        isValid = true;
                        $input.removeClass('border border-danger');
                        $input.addClass('border border-success');
                    }
                }
            }
        });

        // Validar cada campo y marcar en rojo si está vacío
        if (folioPresupuesto === '') {
            isValid = false;
            $('#folioPresupuesto' + idEp).addClass('border border-danger');
        } else {
            $('#folioPresupuesto' + idEp).removeClass('border border-danger');

            if (folioPresupuesto !== '') {
                $('#folioPresupuesto' + idEp).addClass('border border-success');
            }
        }

        if (cantidad1 === '') {
            isValid = false;
            $('#cantidad1' + idEp).addClass('border border-danger');
        } else {
            $('#cantidad1' + idEp).removeClass('border border-danger');

            if (cantidad1 !== '') {
                $('#cantidad1' + idEp).addClass('border border-success');
            }
        }

        if ((precio1 === '' || precio1 === '0.00') && cantidad1 != 0) {
            isValid = false;
            $('#precio1' + idEp).addClass('border border-danger');
        } else {
            $('#precio1' + idEp).removeClass('border border-danger');
            
            if (precio1 !== '') {
                $('#precio1' + idEp).addClass('border border-success');
            }
        }

        if (cantidad2 === '') {
            isValid = false;
            $('#cantidad2' + idEp).addClass('border border-danger');
        } else {
            $('#cantidad2' + idEp).removeClass('border border-danger');

            if (cantidad2 !== '') {
                $('#cantidad2' + idEp).addClass('border border-success');
            }
        }

        if ((precio2 === '' || precio2 === '0.00') && cantidad2 != 0) {
            isValid = false;
            $('#precio2' + idEp).addClass('border border-danger');
        } else {
            $('#precio2' + idEp).removeClass('border border-danger');

            if (precio2 !== '') {
                $('#precio2' + idEp).addClass('border border-success');
            }
        }

        if (cantidad3 === '') {
            isValid = false;
            $('#cantidad3' + idEp).addClass('border border-danger');
        } else {
            $('#cantidad3' + idEp).removeClass('border border-danger');

            if (cantidad3 !== '') {
                $('#cantidad3' + idEp).addClass('border border-success');
            }
        }

        if ((precio3 === '' || precio3 === '0.00') && cantidad3 != 0) {
            isValid = false;
            $('#precio3' + idEp).addClass('border border-danger');
        } else {
            $('#precio3' + idEp).removeClass('border border-danger');

            if (precio3 !== '') {
                $('#precio3' + idEp).addClass('border border-success');
            }
        }

        // Campos adicionales
        let adicionales = [];
        $('#cantidadesAdicionalesContainer' + idEp + ' .cantidad-adicional-row').each(function() {
            let cantidadAdicional = $(this).find('input[id^="cantidad"]').val().replace(/,/g, '').trim();
            let precioAdicional = $(this).find('input[id^="precio"]').val().replace(/,/g, '').trim();

            // Validación: ambos deben estar llenos
            if (cantidadAdicional !== '' && precioAdicional !== '') {
                $(this).find('input').removeClass('border border-danger');
                $(this).find('input').addClass('border border-success');

                adicionales.push({
                    cantidad: cantidadAdicional,
                    precio: precioAdicional
                });
            } else if (cantidadAdicional === '' && precioAdicional === '') {
                isValid = false;
                $(this).find('input').addClass('border border-danger');
            }
        });

        // Ajustamos las medidas para enviarlas al backend
        let medidas = {};
        // Definir el array de claves en el mismo orden que medidasInputs
        let medidasKeys = [];
        if (plantillaId == 3) {
            medidasKeys = [
                'medida_extendida_largo',
                'medida_extendida_ancho',
                'medida_extendida_alto',
                'medida_final_largo',
                'medida_final_ancho',
                'medida_final_alto'
            ];
        } else if (plantillaId == 2) {
            medidasKeys = [
                'medida_extendida_alto_general',
                'medida_extendida_alto_t1',
                'medida_extendida_alto_t2',
                'medida_extendida_ancho_general',
                'medida_extendida_ancho_t1',
                'medida_extendida_ancho_t2',
                'medida_extendida_largo_general',
                'medida_extendida_largo_t1',
                'medida_extendida_largo_t2',
                'medida_final_alto_general',
                'medida_final_alto_t1',
                'medida_final_alto_t2',
                'medida_final_ancho_general',
                'medida_final_ancho_t1',
                'medida_final_ancho_t2',
                'medida_final_largo_general',
                'medida_final_largo_t1',
                'medida_final_largo_t2'
            ];
        }
        medidasInputs.forEach(function(selector, idx) {
            let $input = $(selector);
            let key = medidasKeys[idx];
            if ($input.length && $input.is('input')) {
                medidas[key] = $input.val().trim();
            } else if ($input.length && $input.is('p')) {
                medidas[key] = $input.text().trim();
            }
        });

        // Solo agregar si todos los campos están completos
        if (folioPresupuesto !== '' && precio1 !== '') {
            elementosPresupuestados.push({
                idElementoP: idEp,
                folio_metrics: folioPresupuesto,
                cantidad_1: cantidad1,
                precio_1: precio1,
                cantidad_2: cantidad2,
                precio_2: precio2,
                cantidad_3: cantidad3,
                precio_3: precio3,
                adicionales: adicionales,
                medidasInputs: medidas,
                fsc: {
                    activo: fscActivo,
                    categoria: categoriaFsc,
                    subcategoria: subcategoriaFsc
                },
                plantillaId: plantillaId
            });
        }
    });

    return { isValid, elementosPresupuestados };
}

/**
 * Función para realizar el guardado del presupuesto
 * 
 * @return {void}
 */
function guardarPresupuesto() {
    const validation = validarCamposPresupuesto();

    const Msg = Swal.mixin({
        toast: true,
        position: "center",
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
    });

    if (validation === undefined) {
        Msg.fire({
            icon: "error",
            title: "¡Por favor completa todos los campos requeridos!"
        });
        return;
    }

    if (!validation.isValid) {
        Msg.fire({
            icon: "error",
            title: "¡Por favor completa todos los campos requeridos!"
        });
        return;
    }

    const { elementosPresupuestados } = validation;

    let formData = new FormData();
    formData.append("_token", CSRF_TOKEN);
    formData.append("_method", "POST");
    formData.append("idOportunidad", $("#idOportunidad").val());
    formData.append("idUsuario", $("#idUsuario").val());
    formData.append("elementos", JSON.stringify(elementosPresupuestados));
    formData.append("tituloPresupuesto", $('#tituloPresupuesto').val().trim());

    // Aquí puedes realizar la llamada AJAX para guardar los datos
    $.ajax({
        url: serve + "registrarNuevoPresupuesto",
        type: "POST",
        dataType: "json",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 3000,
                });

                Msg.fire({
                    icon: "success",
                    title: data.message
                });

                $('#valorOportunidad').val(data.valorOportunidad);
                $('#Valor').val(data.valorOportunidad);

                // Cerramos el modal
                $('#modal_registro_presupuesto').modal('hide');

                // Simula el click en la pestaña de documentos
                $('#documentos-oport-tab').trigger('click');

                // Recargamos la tabla
                tblEP_relationV2();

                // Cancelamos la selección múltiple
                $('#activarSeleccion').val(0);
                $('input[data-id="markDirection"]').prop('checked', false);
                $('#btnNuevoPresupuesto').addClass('d-none');
            } else {
                Swal.fire("¡Error al guardar el presupuesto!");
            }
        },
        error: function() {
            Swal.fire("¡Error al realizar la solicitud!");
        }
    });
}

/**
 * Funcion para abrir el modal de multipresupuesto desde Excel
 * 
 * @return void
 */
function abrirModalEPS()
{
    $('#mod_eps').modal('show');

    $('#guardarEPExcelBtn2').hide(); // Mostramos el botón de guardar para cambios
    $('#guardarEPExcelBtn').show(); // Ocultamos el botón de guardar para nuevo registro
}

let archivosGrupoEPS = [];
let indiceArchivoGrupoEPS = 0;
let modoVistaArchivosEPS = 'carrusel';
let idGrupoActualEPS = null;
let puedeDescargarGrupoEPS = false;
let arrastrandoVisorEPS = false;
let inicioArrastreXEPS = 0;
let cambioAplicadoEnArrastreEPS = false;
let direccionCambioCarruselEPS = 0;

function validarDescargaActivaEPS(estatusGrupo = null, mostrarMensaje = true) {
    const activo = estatusGrupo !== null ? Number(estatusGrupo) === 1 : puedeDescargarGrupoEPS;

    if (!activo && mostrarMensaje) {
        Swal.fire('Descarga no permitida', 'El grupo se encuentra inactivo.', 'warning');
    }

    return activo;
}

function actualizarEstadoDescargasEPS() {
    const habilitar = puedeDescargarGrupoEPS === true;

    $('#btnDescargarTodosEPS, #btnDescargarZipEPS').toggle(habilitar);
    $('#btnDescargarActualEPS').toggle(habilitar && modoVistaArchivosEPS === 'carrusel');

    $('.btnDescargarItemGrupoEPS').toggle(habilitar);
}

function obtenerMimeTypePorExtension(extension) {
    const ext = (extension || '').toLowerCase().trim();

    const mimeTypes = {
        jpg: 'image/jpeg',
        jpeg: 'image/jpeg',
        png: 'image/png',
        gif: 'image/gif',
        webp: 'image/webp',
        bmp: 'image/bmp',
        svg: 'image/svg+xml',
        pdf: 'application/pdf'
    };

    return mimeTypes[ext] || 'application/octet-stream';
}

function construirDataUri(archivoBase64, extension) {
    if (!archivoBase64) return '';

    if (archivoBase64.startsWith('data:')) {
        return archivoBase64;
    }

    const mimeType = obtenerMimeTypePorExtension(extension);
    return `data:${mimeType};base64,${archivoBase64}`;
}

function escaparHtml(texto) {
    return String(texto || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function obtenerNombreArchivoSeguro(archivo, index) {
    const extension = (archivo.extension || '').toLowerCase().trim();
    let nombre = (archivo.nombreArchivo || `archivo_${index + 1}`).trim();

    if (extension && !nombre.toLowerCase().endsWith(`.${extension}`)) {
        nombre += `.${extension}`;
    }

    return nombre;
}

function convertirBytesAMb(pesoBytes) {
    const bytes = Number(pesoBytes || 0);
    if (!bytes || Number.isNaN(bytes)) {
        return '0.00 MB';
    }

    return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
}

function descargarArchivoDesdeDataUri(dataUri, nombreArchivo) {
    const link = document.createElement('a');
    link.href = dataUri;
    link.download = nombreArchivo;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function renderModoCarruselEPS() {
    const $contenedor = $('#viewerArchivoEPS');
    const $contador = $('#viewerArchivoEPSCounter');
    const archivo = archivosGrupoEPS[indiceArchivoGrupoEPS];
    const dataUri = construirDataUri(archivo.archivo, archivo.extension);
    const extension = (archivo.extension || '').toLowerCase();
    const esImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].includes(extension);
    const esPdf = extension === 'pdf';
    const claseAnimacion = direccionCambioCarruselEPS > 0
        ? 'eps-carousel-enter-next'
        : (direccionCambioCarruselEPS < 0 ? 'eps-carousel-enter-prev' : 'eps-carousel-enter-fade');

    $('#viewerArchivoEPSNombre').text(archivo.nombreArchivo || 'Archivo sin nombre');
    $('#viewerArchivoEPSMeta').text(
        `Folio: ${archivo.folio || 'N/A'} | Extension: ${archivo.extension || 'N/A'} | Peso: ${convertirBytesAMb(archivo.peso)}`
    );

    $contador.text(`${indiceArchivoGrupoEPS + 1} / ${archivosGrupoEPS.length}`);

    if (esImagen) {
        $contenedor.html(
            `<div class="eps-carousel-frame ${claseAnimacion}">
                <img src="${dataUri}" alt="${escaparHtml(archivo.nombreArchivo)}" style="max-width:100%;max-height:70vh;object-fit:contain;" />
            </div>`
        );
        return;
    }

    if (esPdf) {
        $contenedor.html(
            `<div class="eps-carousel-frame ${claseAnimacion}" style="width:100%;">
                <iframe src="${dataUri}" style="width:100%;height:70vh;border:none;" title="${escaparHtml(archivo.nombreArchivo)}"></iframe>
            </div>`
        );
        return;
    }

    $contenedor.html(
        `<div class="text-center eps-carousel-frame ${claseAnimacion}">
            <p class="text-warning">No se puede previsualizar este tipo de archivo (${escaparHtml(archivo.extension)}).</p>
        </div>`
    );
}

function renderModoListaEPS(horizontal = false) {
    const $contenedor = $('#viewerArchivoEPS');
    const $contador = $('#viewerArchivoEPSCounter');

    $('#viewerArchivoEPSNombre').text('Listado de archivos');
    $('#viewerArchivoEPSMeta').text(`Total: ${archivosGrupoEPS.length} archivo(s)`);
    $contador.text(`${archivosGrupoEPS.length} archivos`);

    let html = `<div style="display:flex; flex-direction:${horizontal ? 'row' : 'column'}; gap:14px; overflow:${horizontal ? 'auto hidden' : 'auto'}; max-height:70vh; padding:4px;">`;

    archivosGrupoEPS.forEach((archivo, idx) => {
        const dataUri = construirDataUri(archivo.archivo, archivo.extension);
        const extension = (archivo.extension || '').toLowerCase();
        const esImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].includes(extension);
        const esPdf = extension === 'pdf';
        const nombreArchivo = obtenerNombreArchivoSeguro(archivo, idx);
        const botonDescargar = puedeDescargarGrupoEPS
            ? `<button type="button" class="btn btn-primary btn-xs btnDescargarItemGrupoEPS" onclick="descargarArchivoGrupoEPSPorIndice(${idx})">Descargar</button>`
            : '';

        html += `<div style="min-width:${horizontal ? '320px' : '100%'}; border:1px solid #ddd; border-radius:8px; background:#fff; padding:10px;">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:8px;">
                <strong style="font-size:13px;">${escaparHtml(nombreArchivo)}</strong>
                ${botonDescargar}
            </div>
            <small class="text-muted d-block" style="margin-bottom:8px;">Folio: ${escaparHtml(archivo.folio || 'N/A')} | Extension: ${escaparHtml(archivo.extension || 'N/A')} | Peso: ${convertirBytesAMb(archivo.peso)}</small>`;

        if (esImagen) {
            html += `<img src="${dataUri}" alt="${escaparHtml(nombreArchivo)}" style="width:100%; max-height:300px; object-fit:contain;" />`;
        } else if (esPdf) {
            html += `<iframe src="${dataUri}" style="width:100%; height:280px; border:none;"></iframe>`;
        } else {
            html += `<p class="text-warning m-0">Sin vista previa para esta extension.</p>`;
        }

        html += `</div>`;
    });

    html += `</div>`;
    $contenedor.html(html);
    actualizarEstadoDescargasEPS();
}

function actualizarBotonesModoVistaEPS() {
    $('#btnModoCarruselEPS').toggleClass('btn-primary', modoVistaArchivosEPS === 'carrusel').toggleClass('btn-default', modoVistaArchivosEPS !== 'carrusel');
    $('#btnModoVerticalEPS').toggleClass('btn-primary', modoVistaArchivosEPS === 'vertical').toggleClass('btn-default', modoVistaArchivosEPS !== 'vertical');
    $('#btnModoHorizontalEPS').toggleClass('btn-primary', modoVistaArchivosEPS === 'horizontal').toggleClass('btn-default', modoVistaArchivosEPS !== 'horizontal');
    const mostrarNavegacion = modoVistaArchivosEPS === 'carrusel';
    $('#btnPrevArchivoEPS').toggle(mostrarNavegacion);
    $('#btnNextArchivoEPS').toggle(mostrarNavegacion);
    $('#viewerArchivoEPS').toggleClass('viewer-carousel-active', mostrarNavegacion);
    actualizarEstadoDescargasEPS();
}

function renderArchivoGrupoEPS() {
    const $contenedor = $('#viewerArchivoEPS');
    const $contador = $('#viewerArchivoEPSCounter');

    if (!archivosGrupoEPS.length) {
        $contador.text('0 / 0');
        $contenedor.html('<p class="text-center text-muted m-0">No hay archivos disponibles para este grupo.</p>');
        return;
    }

    actualizarBotonesModoVistaEPS();

    if (modoVistaArchivosEPS === 'vertical') {
        renderModoListaEPS(false);
        return;
    }

    if (modoVistaArchivosEPS === 'horizontal') {
        renderModoListaEPS(true);
        return;
    }

    renderModoCarruselEPS();
}

function cambiarArchivoGrupoEPS(direccion) {
    if (!archivosGrupoEPS.length) return;

    direccionCambioCarruselEPS = direccion > 0 ? 1 : -1;

    indiceArchivoGrupoEPS += direccion;

    if (indiceArchivoGrupoEPS < 0) {
        indiceArchivoGrupoEPS = archivosGrupoEPS.length - 1;
    }

    if (indiceArchivoGrupoEPS >= archivosGrupoEPS.length) {
        indiceArchivoGrupoEPS = 0;
    }

    renderArchivoGrupoEPS();
}

function viewFiles(idGrupoEP, estatusGrupo = null) {
    if (!idGrupoEP) {
        Swal.fire('No se encontró el grupo de archivos.');
        return;
    }

    $('#viewerArchivoEPS').html('<p class="text-center text-muted m-0">Cargando archivos...</p>');
    $('#viewerArchivoEPSNombre').text('Cargando...');
    $('#viewerArchivoEPSMeta').text('');
    $('#viewerArchivoEPSCounter').text('0 / 0');
    idGrupoActualEPS = idGrupoEP;
    puedeDescargarGrupoEPS = estatusGrupo !== null ? Number(estatusGrupo) === 1 : false;
    modoVistaArchivosEPS = 'carrusel';
    direccionCambioCarruselEPS = 0;
    actualizarBotonesModoVistaEPS();
    actualizarEstadoDescargasEPS();
    $('#mod_view_eps_files').modal('show');

    $.ajax({
        url: serve + 'obtenerExcelesEPsPorGrupo/' + idGrupoEP,
        type: 'POST',
        dataType: 'json',
        data: {
            _token: CSRF_TOKEN
        },
        success: function(resp) {
            if (typeof resp.estatusGrupo !== 'undefined') {
                puedeDescargarGrupoEPS = Number(resp.estatusGrupo) === 1;
                actualizarEstadoDescargasEPS();
            }

            if (resp.response && Array.isArray(resp.data) && resp.data.length > 0) {
                archivosGrupoEPS = resp.data;
                indiceArchivoGrupoEPS = 0;
                renderArchivoGrupoEPS();
            } else {
                archivosGrupoEPS = [];
                indiceArchivoGrupoEPS = 0;
                renderArchivoGrupoEPS();
            }
        },
        error: function() {
            archivosGrupoEPS = [];
            indiceArchivoGrupoEPS = 0;
            renderArchivoGrupoEPS();

            const Msg = Swal.mixin({
                toast: true,
                position: 'top-end',
                icon: 'error',
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: 'Ocurrió un error al obtener los archivos del grupo',
            });
        }
    });
}

function descargarArchivoGrupoEPSPorIndice(indice) {
    if (!validarDescargaActivaEPS()) return;

    if (!archivosGrupoEPS.length || indice < 0 || indice >= archivosGrupoEPS.length) return;

    const archivo = archivosGrupoEPS[indice];
    const dataUri = construirDataUri(archivo.archivo, archivo.extension);
    const nombreArchivo = obtenerNombreArchivoSeguro(archivo, indice);
    descargarArchivoDesdeDataUri(dataUri, nombreArchivo);
}

function descargarArchivoGrupoActual() {
    if (!validarDescargaActivaEPS()) return;

    if (!archivosGrupoEPS.length) {
        Swal.fire('No hay archivos para descargar.');
        return;
    }

    descargarArchivoGrupoEPSPorIndice(indiceArchivoGrupoEPS);
}

function descargarTodosArchivosGrupoSinZip() {
    if (!validarDescargaActivaEPS()) return;

    if (!archivosGrupoEPS.length) {
        Swal.fire('No hay archivos para descargar.');
        return;
    }

    archivosGrupoEPS.forEach((archivo, idx) => {
        setTimeout(() => {
            const dataUri = construirDataUri(archivo.archivo, archivo.extension);
            const nombreArchivo = obtenerNombreArchivoSeguro(archivo, idx);
            descargarArchivoDesdeDataUri(dataUri, nombreArchivo);
        }, idx * 200);
    });
}

function descargarLoteArchivosZip(idGrupoEP = null) {
    if (!validarDescargaActivaEPS()) return;

    const grupo = idGrupoEP || idGrupoActualEPS;

    if (!grupo) {
        Swal.fire('No se encontró el grupo de archivos para descarga ZIP.');
        return;
    }

    $.ajax({
        url: serve + 'descargarExcelesEPsPorGrupoZip/' + grupo,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN
        },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(blob, status, xhr) {
            const contentDisposition = xhr.getResponseHeader('Content-Disposition') || '';
            const match = contentDisposition.match(/filename="?([^";]+)"?/i);
            const nombreArchivo = (match && match[1]) ? match[1] : `archivos_grupo_${grupo}.zip`;
            const blobUrl = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = blobUrl;
            link.download = nombreArchivo;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(blobUrl);
        },
        error: function(xhr) {
            if (xhr && xhr.status === 403) {
                Swal.fire('Descarga no permitida', 'El grupo se encuentra inactivo.', 'warning');
                return;
            }

            Swal.fire('Error', 'No se pudo descargar el lote ZIP.', 'error');
        }
    });
}

function downloadFiles(idGrupoEP, estatusGrupo = null) {
    if (!validarDescargaActivaEPS(estatusGrupo)) return;

    descargarLoteArchivosZip(idGrupoEP);
}

$(document).off('click', '#btnPrevArchivoEPS').on('click', '#btnPrevArchivoEPS', function() {
    cambiarArchivoGrupoEPS(-1);
});

$(document).off('click', '#btnNextArchivoEPS').on('click', '#btnNextArchivoEPS', function() {
    cambiarArchivoGrupoEPS(1);
});

$(document).off('click', '#btnModoCarruselEPS').on('click', '#btnModoCarruselEPS', function() {
    modoVistaArchivosEPS = 'carrusel';
    renderArchivoGrupoEPS();
});

$(document).off('click', '#btnModoVerticalEPS').on('click', '#btnModoVerticalEPS', function() {
    modoVistaArchivosEPS = 'vertical';
    renderArchivoGrupoEPS();
});

$(document).off('click', '#btnModoHorizontalEPS').on('click', '#btnModoHorizontalEPS', function() {
    modoVistaArchivosEPS = 'horizontal';
    renderArchivoGrupoEPS();
});

$(document).off('click', '#btnDescargarActualEPS').on('click', '#btnDescargarActualEPS', function() {
    descargarArchivoGrupoActual();
});

$(document).off('click', '#btnDescargarTodosEPS').on('click', '#btnDescargarTodosEPS', function() {
    descargarTodosArchivosGrupoSinZip();
});

$(document).off('click', '#btnDescargarZipEPS').on('click', '#btnDescargarZipEPS', function() {
    descargarLoteArchivosZip();
});

$(document).off('mousedown', '#viewerArchivoEPS').on('mousedown', '#viewerArchivoEPS', function(e) {
    if (modoVistaArchivosEPS !== 'carrusel' || !archivosGrupoEPS.length) {
        return;
    }

    arrastrandoVisorEPS = true;
    inicioArrastreXEPS = e.clientX;
    cambioAplicadoEnArrastreEPS = false;
    $(this).addClass('viewer-carousel-dragging');
    e.preventDefault();
});

$(document).off('mousemove.epsDrag').on('mousemove.epsDrag', function(e) {
    if (!arrastrandoVisorEPS || modoVistaArchivosEPS !== 'carrusel' || !archivosGrupoEPS.length) {
        return;
    }

    if (cambioAplicadoEnArrastreEPS) {
        return;
    }

    const desplazamientoX = e.clientX - inicioArrastreXEPS;
    const umbral = 60;

    if (Math.abs(desplazamientoX) < umbral) {
        return;
    }

    if (desplazamientoX < 0) {
        cambiarArchivoGrupoEPS(1);
    } else {
        cambiarArchivoGrupoEPS(-1);
    }

    cambioAplicadoEnArrastreEPS = true;
});

$(document).off('mouseup.epsDrag').on('mouseup.epsDrag', function() {
    arrastrandoVisorEPS = false;
    cambioAplicadoEnArrastreEPS = false;
    $('#viewerArchivoEPS').removeClass('viewer-carousel-dragging');
});

$(document).off('mouseleave.epsDrag').on('mouseleave.epsDrag', function() {
    arrastrandoVisorEPS = false;
    cambioAplicadoEnArrastreEPS = false;
    $('#viewerArchivoEPS').removeClass('viewer-carousel-dragging');
});

/**
 * Funcion para previsualizar el archivo seleccionado
 * 
 * @return void
 */
let selectedFilesEPS = [];

// Optimiza imágenes antes de agregarlas (reescalado y compresión)
async function processImageFile(file, maxWidth = 4000, quality = 0.9) {
    return new Promise((resolve) => {
        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = function () {
            let width = img.width;
            let height = img.height;
            if (width > maxWidth) {
                height = Math.round((maxWidth / width) * height);
                width = maxWidth;
            }
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);
            canvas.toBlob(
                (blob) => {
                    const newFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), { type: "image/jpeg" });
                    newFile.previewUrl = URL.createObjectURL(blob);
                    resolve(newFile);
                },
                'image/jpeg',
                quality
            );
            URL.revokeObjectURL(url);
        };
        img.src = url;
    });
}

function renderPreviewEPS() {
    const preview = $('#previewArchivoEPS');
    let html = '<ul id="sortablePreviewEPS" style="list-style:none;padding:0;display:flex;flex-wrap:wrap;">';
    selectedFilesEPS.forEach((file, idx) => {
        let item = `<li class="draggable-preview" draggable="true" data-idx="${idx}" style="margin:4px;cursor:move;position:relative;">`;
        // Botón de eliminar
        item += `<button type="button" class="remove-file-btn" data-remove-idx="${idx}" style="position:absolute;top:2px;right:2px;z-index:2;background:#fff;border:1px solid #ccc;border-radius:50%;width:22px;height:22px;cursor:pointer;">&times;</button>`;
        if (file.type.startsWith('image/')) {
            const url = file.previewUrl || URL.createObjectURL(file);
            item += `<img src="${url}" class="preview-img-p" alt="Previsualización" style="max-width:120px;max-height:120px;">`;
        } else if (file.type === 'application/pdf') {
            const url = file.previewUrl || URL.createObjectURL(file);
            item += `<iframe src="${url}" class="preview-pdf" style="width:120px;height:120px;"></iframe>`;
        } else {
            item += '<span class="text-danger d-block">Formato no soportado</span>';
        }
        item += '</li>';
        html += item;
    });
    html += '</ul>';
    preview.html(html);

    // Drag & drop nativo
    let dragSrcEl = null;
    $('#sortablePreviewEPS').on('dragstart', 'li', function(e) {
        dragSrcEl = this;
        e.originalEvent.dataTransfer.effectAllowed = 'move';
        e.originalEvent.dataTransfer.setData('text/html', this.outerHTML);
        $(this).addClass('dragElem');
    });
    $('#sortablePreviewEPS').on('dragover', 'li', function(e) {
        e.preventDefault();
        e.originalEvent.dataTransfer.dropEffect = 'move';
        return false;
    });
    $('#sortablePreviewEPS').on('drop', 'li', function(e) {
        e.stopPropagation();
        if (dragSrcEl !== this) {
            if ($(dragSrcEl).index() < $(this).index()) {
                $(this).after(dragSrcEl);
            } else {
                $(this).before(dragSrcEl);
            }
            updateSelectedFilesOrder();
        }
        $('.dragElem').removeClass('dragElem');
        return false;
    });
    $('#sortablePreviewEPS').on('dragend', 'li', function() {
        $('.dragElem').removeClass('dragElem');
    });

    // Eliminar archivo
    $('.remove-file-btn').off('click').on('click', function(e) {
        e.stopPropagation();
        const idx = $(this).data('remove-idx');
        selectedFilesEPS.splice(idx, 1);
        renderPreviewEPS();
    });

    function updateSelectedFilesOrder() {
        let newOrder = [];
        $('#sortablePreviewEPS li').each(function() {
            let idx = $(this).attr('data-idx');
            newOrder.push(selectedFilesEPS[idx]);
        });
        selectedFilesEPS = newOrder;
        renderPreviewEPS(); // Re-render para actualizar los índices y botones
    }
}

// Al seleccionar archivos, los agrega a la lista sin reiniciar y optimiza imágenes
$(document).off('change', '#FileDocAddEPS').on('change', '#FileDocAddEPS', async function(e) {
    const files = Array.from(e.target.files);
    for (const file of files) {
        if (!selectedFilesEPS.some(f => f.name === file.name && f.size === file.size)) {
            if (file.type.startsWith('image/')) {
                const processed = await processImageFile(file, 2000, 0.9);
                selectedFilesEPS.push(processed);
            } else {
                file.previewUrl = file.type === 'application/pdf' ? URL.createObjectURL(file) : null;
                selectedFilesEPS.push(file);
            }
        }
    }
    renderPreviewEPS();
    // Limpia el input para permitir volver a seleccionar el mismo archivo si se elimina
    $(this).val('');
});

// Función para cerrar el modal de carga de documentos y limpiar la selección
function accionCerrarModalCargEP() {
    $('#mod_eps').modal('hide');
    $('#FileDocAddEPS').val('');
    selectedFilesEPS = [];
    $('#previewArchivoEPS').html('');
}

/**
 * Función para guardar los documentos de EP
 * 
 * @param tipo
 * @returns {void}
 */
function guardarDocumentosEPS(tipo = 1) {
    const fileInput = document.getElementById('FileDocAddEPS');
    const file = fileInput.files[0];
    const files = fileInput.files;
    
    if (!selectedFilesEPS || selectedFilesEPS.length === 0) {
        $('#labelFileDocAddEPS').addClass('is-invalid');
        return;
    } else {
        $('#labelFileDocAddEPS').removeClass('is-invalid');
    }

    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('idOportunidad', $('#idOportunidad').val());
    for (let i = 0; i < selectedFilesEPS.length; i++) {
        formData.append('files[]', selectedFilesEPS[i]);
    }

    $.ajax({
        url: serve + "guardar-exceles-ep",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                const toastSuccess = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                if (tipo === 1) {
                    toastSuccess.fire({
                        icon: 'success',
                        title: resp.message || 'Archivos de EP guardados exitosamente.'
                    });

                    $('#mod_eps').modal('hide');

                    // Limpiar el formulario
                    $('#FileDocAddEPS').val('');
                    $('#previewArchivoEPS').html('');
                    selectedFilesEPS = [];

                    // Recargar la tabla de EP para mostrar los nuevos archivos
                    tblEP_relationV2();
                }
            } else {
                Swal.fire('Error', resp.message || 'No se pudo guardar los archivos de EP.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'Ocurrió un error al guardar los archivos de EP.', 'error');
        }
    });
}

let selectedFilesEPSVista = [];

function abrirModalExcelEPVista() {
    $('#mod_eps_excel_vista').modal('show');
}

function cerrarModalExcelEPVista() {
    $('#mod_eps_excel_vista').modal('hide');
    $('#FileDocAddEPSVista').val('');
    $('#previewArchivoEPSVista').html('');
    selectedFilesEPSVista = [];
}

function esArchivoExcelValidoVista(file) {
    if (!file || !file.name) {
        return false;
    }

    const allowedExtensions = ['xls', 'xlsx', 'xlsm', 'xlsb'];
    const parts = file.name.split('.');
    const extension = parts.length > 1 ? parts.pop().toLowerCase() : '';

    return allowedExtensions.includes(extension);
}

function formatearPesoArchivoVista(bytes) {
    const size = Number(bytes || 0);

    if (size < 1024) {
        return size + ' B';
    }

    return (size / 1024).toFixed(2) + ' KB';
}

function renderPreviewEPSVista() {
    const preview = $('#previewArchivoEPSVista');

    if (!selectedFilesEPSVista.length) {
        preview.html('');
        return;
    }

    let html = '<ul style="list-style:none;padding:0;margin:0;">';

    selectedFilesEPSVista.forEach((file, idx) => {
        const extension = file.name.includes('.') ? file.name.split('.').pop().toLowerCase() : '';

        html += `
            <li class="d-flex align-items-center justify-content-between border rounded p-2 mb-2" style="gap: 10px;">
                <div>
                    <div class="font-weight-bold text-truncate" style="max-width: 440px;" title="${file.name}">${file.name}</div>
                    <small class="text-muted">Extensión: ${extension || 'N/A'} | Peso: ${formatearPesoArchivoVista(file.size)}</small>
                </div>
                <button type="button" class="btn btn-danger btn-xs" onclick="eliminarArchivoEPSVista(${idx})">Quitar</button>
            </li>
        `;
    });

    html += '</ul>';
    preview.html(html);
}

function eliminarArchivoEPSVista(index) {
    selectedFilesEPSVista.splice(index, 1);
    renderPreviewEPSVista();
}

$(document).off('change', '#FileDocAddEPSVista').on('change', '#FileDocAddEPSVista', function(e) {
    const files = Array.from(e.target.files || []);
    const firstFile = files[0] || null;

    if (!firstFile) {
        selectedFilesEPSVista = [];
        renderPreviewEPSVista();
        $(this).val('');
        return;
    }

    if (files.length > 1) {
        Swal.fire('Solo un archivo', 'Solo se permite cargar un archivo Excel a la vez.', 'warning');
    }

    if (!esArchivoExcelValidoVista(firstFile)) {
        selectedFilesEPSVista = [];
        renderPreviewEPSVista();
        Swal.fire('Formato no permitido', 'Solo se permiten archivos Excel (.xls, .xlsx, .xlsm, .xlsb).', 'warning');
        $(this).val('');
        return;
    }

    selectedFilesEPSVista = [firstFile];
    renderPreviewEPSVista();

    $(this).val('');
});

function guardarDocumentosEPSVista() {
    if (!selectedFilesEPSVista.length) {
        $('#labelFileDocAddEPSVista').addClass('is-invalid');
        return;
    }

    if (selectedFilesEPSVista.length !== 1) {
        $('#labelFileDocAddEPSVista').addClass('is-invalid');
        Swal.fire('Solo un archivo', 'Debes seleccionar solo un archivo Excel.', 'warning');
        return;
    }

    $('#labelFileDocAddEPSVista').removeClass('is-invalid');

    let formData = new FormData();
    formData.append('_token', CSRF_TOKEN);
    formData.append('_method', 'POST');
    formData.append('idOportunidad', $('#idOportunidad').val());

    formData.append('files[]', selectedFilesEPSVista[0]);

    $.ajax({
        url: serve + 'guardarExcelEPVista',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            if (resp.response) {
                const toastSuccess = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });

                toastSuccess.fire({
                    icon: 'success',
                    title: resp.message || 'Archivos Excel guardados correctamente.'
                });

                cerrarModalExcelEPVista();
                tblEP_relationV2();

                if (resp.refresh) {
                    recargarYAbrirTabElementosOport();
                }

                return;
            }

            Swal.fire('Error', resp.message || 'No se pudieron guardar los archivos.', 'error');
        },
        error: function(xhr) {
            const msg = xhr && xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Ocurrió un error al guardar los archivos Excel.';

            Swal.fire('Error', msg, 'error');
        }
    });
}