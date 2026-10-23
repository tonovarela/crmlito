// Configuramos el CSRF token
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
var role
// Define los patrones de fecha
const FROM_PATTERN_SHORT = "YYYY-MM-DD";
const TO_PATTERN_SHORT = "DD/MM/YYYY";

const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
const TO_PATTERN = "DD/MM/YYYY HH:mm";

// Variable que almacenara los vendedores seleccionados
var vendedoresSelected = []

// Bandera para sabeer si se esta editando un motivo
var editandoMotivo = false

// Configuramos ajax
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

$('#carouselHistory').carousel({
    interval: false
})

// Al iniciar la aplicación
window.onload = () => {

    // Mostramos los tabs de forma ordenada
    $('#pedidosLiberados').hide()
    $('#listPedidosParaLiberar').hide()
    $('#incidenciasPanel').hide()

    let filtersIncidencias = document.getElementById('filtersIncidencias')

    // Mostrar el menu de filtros
    filtersIncidencias.classList.add('d-none')
    filtersIncidencias.classList.remove('d-flex')

    $('#listMain').show()
    $('#tblActual').val('tbl1')

    // Ocultamos el filtro de tipo de comisiones liberadas
    $('#compFilterComPagadas').hide()

    // Ocultamos el componente que visualizara los filtros adicionales
    $('#moreFilters').hide()

    // Ocultamos el contenedor de filtros adicionales
    $('#contentAddFilters').addClass('d-none')

    // general
    tblAdminPedidosTbl()

    // listo para liberar
    tblAdminPedidosListosLiberarTbl()

    // liberados
    tblAdminPedidosLiberadosTbl()

    // Incidencias
    tblIncidenciasTbl()

    // Llamamos el rol del usuario
    getRoleUser()

    // Rellenamos el select con los estatus que aparecen de los registros
    getListFilterAdminPedidos(1)

    // Limpiamos los filtros guardados en el localStorage
    cleanFilters()
}

/**
 * Función para obtener los vendedores
 * 
 * @return void
 */
function getVendedoresListInc() {
    // Rellenamos el select con los vendedores
    $.get('/getVendedoresR', function(data) {
        let finalStr = '';

        // Iteramos los vendedores para agregarlos con el nombre en la etiqueta y el id en el onclick
        data.vendedores.forEach((element, index) => {
            let idVendedor = element.AgentePedido.replaceAll('.', '');
            finalStr += `<div class="btn btn-primary my-1 vendedor" data-id="btn-${idVendedor}" data-value="${element.AgentePedido}">${element.NombreCteAgente}</div>`
        });

        // Creamos un div para colocar la informacion de los vendedores
        $('#no-seleccionados').html(finalStr)
    });
}

/**
 * Función para limpiar los filtros guardados en el localStorage
 * 
 * @return void
 */
function cleanFilters() {
    // Limpiamos los filtros guardados en el localStorage
    localStorage.removeItem('tbl1')
    localStorage.removeItem('tbl2')
    localStorage.removeItem('tbl3')
}

/**
 * Función para obtener el rol del usuario que esta logueado
 * 
 * @return mixed
 */
function getRoleUser() {
    role = $.ajax({
        url: serve + "getRoleUser",
        async: true,
        type: "POST",
        processData: false,
        contentType: false,
        success: function (data) {
            // Función que ocultara todo lo necesario de acuerdo al rol
            canSeeUserLoggued(data.role)
            return data.role
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: "Ocurrió un error, al obtener la información" });
        }
    })

    return role
}

/**
 * Función para mostrar/ocultar secciones de la vista para ciertos usuarios
 * 
 * @param {*} roleId
 * @return mixed
 */
function canSeeUserLoggued(roleId) {
    // Validamos lo que los usuarios pueden visualizar
    if (roleId == 4) {
        // Asignamos el valor del role
        role = roleId

        // Ocultamos el boton para filtrar por vendedores
        $('#ContFilterVendedor').hide();
    }
}

// Asignamos al input de búsqueda por cliente para que cuando se precione el enter realice la búsqueda
$('.searcherFilters').on('keydown', function (event) {
    if (event.key == 'Enter') {
        aplicarFiltroAdmPed();
    }
});

// Asignamos al input de búsqueda por cliente para que cuando se precione el enter realice la búsqueda (incidencias)
$('.searcherFiltersIncidencias').on('keydown', function (event) {
    if (event.key == 'Enter') {
        aplicarFiltroIncidencias();
    }
});

// Asignamos cuando se cambie el select de estatus se realice la búsqueda
$('#filterEstatus').on('change', function (event) {
    aplicarFiltroAdmPed();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de estatus se realice la búsqueda
$('#filterFechaPago').on('change', function (event) {
    aplicarFiltroAdmPed();
})

// Asignamos cuando se cambie el select de estatus se realice la búsqueda
$('#filterComPagadas').on('change', function (event) {
    aplicarFiltroAdmPed();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de vendedores se realice la búsqueda
$('#filterVendedor').on('change', function (event) {
    aplicarFiltroAdmPed();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de vendedores se realice la búsqueda (Incidencias)
$('#filterVendedorIncidencia').on('change', function (event) {
    aplicarFiltroIncidencias();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de estatus se realice la búsqueda (Incidencias)
$('#filterEstatusIncidencia').on('change', function (event) {
    aplicarFiltroIncidencias();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de vendedores se realice la búsqueda (Incidencias)
$('#filterMotivoIncidencia').on('change', function (event) {
    aplicarFiltroIncidencias();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de estatus de comision se realice la búsqueda
$('#filterEstatusCon').on('change', function (event) {
    aplicarFiltroAdmPed();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de estatus de comision se realice la búsqueda
$('#filterEstatusCom').on('change', function (event) {
    aplicarFiltroAdmPed();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de estatus de comision se realice la búsqueda
$('#filterQuincena').on('change', function (event) {
    aplicarFiltroIncidencias();

    // Verifica si el valor no es nulo o vacío
    if ($(this).val() !== null && $(this).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(this).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(this).css('background-color', ''); // Esto restablece al color base
    }
})

// Asignamos cuando se cambie el select de estatus de comision se realice la búsqueda
function applyFiltersStatus(select) {
    aplicarFiltroAdmPed();

    // Verifica si el valor no es nulo o vacío
    if ($(`#${select}`).val() !== null && $(`#${select}`).val() !== '') {
        // Cambia el color cuando el valor es diferente de nulo
        $(`#${select}`).css('background-color', 'yellow'); // Cambia 'yellow' al color que desees
    } else {
        // Regresa al color base cuando el valor es nulo o vacío
        $(`#${select}`).css('background-color', ''); // Esto restablece al color base
    }
}

/**
 * Función para aplicar los filtros de la tabla de pedidos
 * 
 * @param {*} num
 * @returns
 */
const truncateToTwoDecimals = (num) => {
    return Math.floor(num * 100) / 100;
};

/**
 * Función para alojar la tabla de pedidos
 * 
 */
function tblAdminPedidosTbl() {
    // Tabla de pedidos
    tblAdminPedidos = $("#tblAdminPedidos").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: true,
        order: [
            [1, "desc"],
        ],
        ajax: {
            url: serve + "api/getPedidos/tbl3",
            data: {
                'tblActual': $('#tblActual').val(),
                'filterCliente': $('#filterCliente').val(),
                'filterVendedor': $('#filterVendedor').val(),
                'filterTrabajo': $('#filterTrabajo').val(),
                'filterEstatus': $('#filterEstatus').val(),
                'filterOP': $('#filterOP').val(),
                'filterPedido': $('#filterPedido').val(),
                'filterFactura': $('#filterFactura').val(),
                'filterEstatusCom': $('#filterEstatusCom').val(),
                'filterFechaPago': $('#filterFechaPago').val(),
            },
            type: "POST",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columnDefs: [
            { orderable: false, targets: [0, 24] },
            {
                visible: false,
                targets: [25,26,27,28,29,30,31,32]
            },
            {
                targets: [15], // fecha pedido
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                targets: [22], // Fec. cobro fac
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                targets: [23], // Fec. pagado
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
        ],
        columns: [
            {
                title: '',
                render: function (data, type, row) {
                    return '<i id="icon' + row.IDPedido + '" class="fas fa-chevron-circle-right fa-2x text-gray cursor-pointer"></i>'
                }
            },
            {
                data: 'MovIDPedido',
                title: 'Pedido'
            },
            {
                data: 'OP',
                title: 'OP'
            },
            {
                data: 'ConceptoPedido',
                title: 'Concepto pedido'
            },
            {
                data: 'Trabajo',
                title: 'Trabajo'
            },
            {
                data: 'NombreCtePedido',
                title: 'Cliente'
            },
            {
                data: 'NombreCteAgente',
                title: 'Vendedor'
            },
            {
                data: 'ImportePedido',
                title: '$. Pedido. Org.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'ImporteVentaPerdida',
                title: '$. Vent. Perd',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-danger">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'TNotaCredito',
                title: '$. Nota Credito',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-danger">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FinalImportePedido',
                title: '$. Pedido. Final.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FinalImporteComision',
                title: '$. Com',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'Comision',
                title: '%. Com',
                className: 'text-center',
                render: function (a) {
                    console.log(a);
                    return '<span class="text-black">'+new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))+' %</span>'
                }
            },
            {
                data: 'porcentaje', //12
                title: '%. Com. Mod.',
                className: 'text-center',
                render: function (a) {
                    if (a == null) {
                        return '';
                    }

                    return '<span class="text-black">'+new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))+' %</span>'
                }
            },
            {
                data: 'ImporteComisionModificada', //13
                title: '$. Com. Mod.',
                className: 'text-center',
                render: function (a) {
                    if (a == null) {
                        return '';
                    }
                    
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FechaPedido',
                title: 'Fecha Pedido'
            },
            {
                data: 'importeRetencion',
                title: '$ Retención',
                className: 'text-center',
                render: function (a) {
                    // Formateamos el valor a moneda a modo de que no se redoandee el valor al valor proximo
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(truncateToTwoDecimals(Number(a)))+'</span>'
                }
            },
            {
                data: 'descRetencion',
                title: '% Retención',
                className: 'text-center'
            },
            {
                data: 'Anticipo',
                className: 'text-center',
                title: 'A cuenta'
            },
            {
                data: 'Saldo',
                className: 'text-center',
                title: 'Saldo',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'Estatus',
                title: 'Estatus'
            },
            {
                data: 'estatusCom',
                title: 'Estatus Com.'
            },
            {
                data: 'FechaCobroFactura',
                title: 'Fec. cobro fac'
            },
            {
                data: 'fechaPagado',
                title: 'Fec. de pago'
            },
            {
                data: 'acciones',
                title: 'Acciones'
            },
            {
                data: 'totalImportePedido',
            },
            {
                data: 'totalComisionImporte',
            },
            {
                data: 'AnticipoFooter',
            },
            {
                data: 'SaldoFooter',
            },
            {
                data: 'totalImportePerdido',
            },
            {
                data: 'totalRetenciones'
            },
            {
                data: 'totalNotaCredito'
            },
            {
                data: 'totalComisionImporteFinal'
            }
        ],
        rowCallback: function (row, data) {
            $(row).data('facturasVisible', false);
            $(row).on('click', `i#icon${data.IDPedido}`, function (e) {
                var $this = $(row);
                var facturasVisible = $this.data('facturasVisible');
                var facturasContainer = $this.next('.facturas-container');
                var iconElement = $(row).find('i#icon' + data.IDPedido);

                if (facturasVisible) {
                    facturasContainer.slideUp(function () {
                        $(this).remove();
                    });
                    $this.data('facturasVisible', false);
                    iconElement.removeClass('fas fa-chevron-circle-down fa-2x text-gray cursor-pointer').addClass('fas fa-chevron-circle-right fa-2x text-gray cursor-pointer');
                    $this.removeClass('active-row');
                } else {

                    let idPedido = null
                    let typeIdPedido = 1 // 1 - va el texto de factura , 2 va el id de factura

                    if ($('#filterFactura').val() != null && $('#filterFactura').val() != '') {
                        idPedido = $('#filterFactura').val()
                        typeIdPedido = 1
                    } else {
                        idPedido = data.IDPedido
                        typeIdPedido = 2
                    }
                    
                    formDataFacturas = new FormData();
                    formDataFacturas.append("_token", CSRF_TOKEN);
                    formDataFacturas.append("_method", "POST");
                    formDataFacturas.append("idPedido", idPedido);
                    formDataFacturas.append("typeIdPedido", typeIdPedido);

                    $.ajax({

                        async: true,
                        type: "POST",
                        url: serve + 'facturas',
                        data: formDataFacturas,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            var html = `
                            <table style="width: 97%; margin-left: auto; margin-bottom: 0;" class="table table-hover nowrap compact tblnombre text-left w-full tblStr">
                                <thead>
                                    <tr style="border-bottom: 2px solid #C8D9EA;">
                                        <!--<th style="border-top-left-radius: 10px !important; border-bottom-left-radius: 10px !important;"></th>-->
                                        <th style="border-top-left-radius: 10px !important; border-bottom-left-radius: 10px !important;">N° Factura</th>
                                        <th>Mov Factura</th>
                                        <th>$ factura</th>
                                        <th>$ cobro</th>
                                        <th>Nota de crédito</th>
                                        <th>Condicion</th>
                                        <th>Vencimiento</th>
                                        <th>Dias Venc</th>
                                        <th>Fecha factura</th>
                                        <th>Estatus comisión</th>
                                        <th style="border-top-right-radius: 10px !important; border-bottom-right-radius: 10px !important;">Fecha cobro</th>
                                    </tr>
                                </thead>
                                <tbody>
                            `;
                            let facturas = response.facturas

                            facturas.forEach(function (pedido, index) {
                                html += '<tr class="opened" data-pedido-index="' + index + '">';
                                //html += '<td><i class="icon fas fa-chevron-circle-right fa-2x text-gray"></i></td>';
                                html += '<td>' + pedido.MovIDFactura + '</td>';
                                html += '<td>' + pedido.MovFactura + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteFactura).toFixed(2)) + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteCobro).toFixed(2)) + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteAplicacion).toFixed(2)) + '</td>';
                                html += '<td>' + pedido.Condicion + '</td>';
                                html += '<td>' + pedido.Vencimiento + '</td>';
                                html += '<td>' + pedido.AlCorriente + '</td>';
                                html += '<td>' + pedido.FechaFactura + '</td>';
                                html += '<td>' + (pedido.EstatusComision == null ? 'No pagada' : pedido.EstatusComision) + '</td>';
                                html += '<td>' + pedido.FechaCobro + '</td>';
                                html += '</tr>';
                            });

                            html += '</tbody>';

                            // Sección del footer
                            html += '<tfoot>';

                            html += `
                            <tr style="background-color: #539fd6; color:#fff;">
                                <td colspan="2"><strong>Totales: </strong></td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteFactura).toFixed(2))}</td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteCobro).toFixed(2))}</td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteAplicacion).toFixed(2))}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            `;

                            html += '</tfoot></table>';

                            if (facturasContainer.length) {
                                facturasContainer.html(html);
                                facturasContainer.slideDown();
                            } else {
                                facturasContainer = $('<tr class="facturas-container"><td colspan="26" style="padding: 0;"></td></tr>');
                                facturasContainer.find('td').append(html);
                                $this.after(facturasContainer);
                                facturasContainer.slideDown();
                            }
                            $this.addClass('active-row'); // add active row class
                            $this.data('facturasVisible', true);
                        }
                    });

                    iconElement.removeClass('fas fa-chevron-circle-right fa-2x text-gray cursor-pointer').addClass('fas fa-chevron-circle-down fa-2x text-gray cursor-pointer');
                }
            });
        },
        footerCallback: function ( row, data) {
            var api = this.api();
            // Función para convertir valores a enteros
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Sumar totales de cada columna
            var totalImporte = Number(api.column(25).data()[0]);

            var totalCosto = Number(api.column(26).data()[0]);

            var totalAnticipo = Number(api.column(27).data()[0]);

            var totalSaldo =  Number(api.column(28).data()[0]);

            var totalPerdido =  Number(api.column(29).data()[0]);

            var totalRetenciones =  Number(api.column(30).data()[0]);

            var totalNotaCredito =  Number(api.column(31).data()[0]);

            var totalComisionImporteFinal =  Number(api.column(32).data()[0]);

            // Actualizar el footer con los totales
            $(api.column(7).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalImporte.toFixed(2)));
            $(api.column(8).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPerdido.toFixed(2)));
            $(api.column(9).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalNotaCredito.toFixed(2)));
            $(api.column(10).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalComisionImporteFinal.toFixed(2)));
            $(api.column(11).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalCosto.toFixed(2)));
            $(api.column(16).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalRetenciones.toFixed(2)));
            $(api.column(18).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalAnticipo.toFixed(2)));
            $(api.column(19).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalSaldo.toFixed(2)));
        },
        pageLength: 25,
        lengthMenu: [
            [5, 25, 50, 100, 150, 200, -1],
            [5, 25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

var activarSeleccion = false

/**
 * Función para alojar la tabla de pedidos listos para liberar
 * 
 */
function tblAdminPedidosListosLiberarTbl() {
    // Buttons 
    let buttonsLPL = [
        {
            extend: "colvis",
            columns: [0, 1, 2],
            text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
            collectionLayout: "fixed two-column",
        },
    ];

    // Validamos si el usuario tiene permisos para liberar pedidos
    if ($('#roleUser').val() == 1 || $('#roleUser').val() == 5 || $('#roleUser').val() == 2) {
        buttonsLPL.push(// Boton para marcar todos los pedidos como firmados por dirección y solo lo pueda ver dirección
            {
                text: '<i class="fas fa-check-double" title="Activar/Desactivar multi-seleccioón"></i>',
                action: function (e, dt, node, config) {
                    if ($('#activarSeleccion').val() == 0) {
                        $('#activarSeleccion').val(1)
                    } else {
                        $('#activarSeleccion').val(0)

                        // Desmarcamos todos los checkbox
                        $('input[data-id="markDirection"]').prop('checked', false)
                    }

                    tblAdminPedidosListosLiberarTbl()
                }
        });

        if ($('#activarSeleccion').val() == 1) {
            // Mostramos el boton para continuar con el proceso de liberación
            buttonsLPL.push(// Boton para marcar todos los pedidos como firmados de acuerdo a la selección
                {
                    text: '<i class="fas fa-check" title="Marcar pedidos como liberados"></i>',
                    action: function (e, dt, node, config) {
                        // Validamos si hay pedidos seleccionados
                        if ($('input[data-id="markDirection"]:checked').length > 0) {
                            // Mostramos el modal para confirmar la liberación de los pedidos
                            Swal.fire({
                                title: '¿Estás seguro de liberar los pedidos seleccionados?',
                                text: "¡No podrás revertir esta acción!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: '¡Sí, liberar!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Creamos un array para almacenar los pedidos seleccionados
                                    let pedidos = []

                                    // Recorremos los checkbox seleccionados
                                    $('input[data-id="markDirection"]:checked').each(function (index, element) {
                                        // Agregamos los pedidos al array
                                        pedidos.push($(element).val())
                                    })

                                    // Codificamos el array a json
                                    let pedidosString = pedidos.join(',')

                                    // Creamos el formulario que se enviara para realizar la actualización de la firma
                                    formDataUpdFirmasUnique = new FormData();
                                    formDataUpdFirmasUnique.append("_token", CSRF_TOKEN);
                                    formDataUpdFirmasUnique.append("_method", "POST");
                                    formDataUpdFirmasUnique.append("idPedidos", pedidosString);
                                    formDataUpdFirmasUnique.append("role", $('#roleUser').val());

                                    // Enviamos los pedidos a liberar
                                    $.ajax({
                                        url: serve + "api/marcarPedidosLiberados",
                                        async: true,
                                        type: "POST",
                                        data: formDataUpdFirmasUnique,
                                        processData: false,
                                        contentType: false,
                                        success: function (data) {
                                            if (data.response) {
                                                // Mostramos un mensaje de éxito
                                                const Msg = Swal.mixin({
                                                    toast: true,
                                                    position: "center",
                                                    icon: "success",
                                                    showConfirmButton: false,
                                                    timer: 3000
                                                });

                                                Msg.fire({ title: "Pedidos liberados con éxito" });

                                                // Desactivamos la selección de pedidos
                                                $('#activarSeleccion').val(0)
                                                
                                                // Recargamos la tabla de pedidos
                                                tblAdminPedidosListosLiberarTbl()
                                            }
                                        },
                                        error: function () {
                                            // Mostramos un mensaje de error
                                            const Msg = Swal.mixin({
                                                toast: true,
                                                position: "center",
                                                icon: "warning",
                                                showConfirmButton: false,
                                                timer: 3000
                                            });

                                            Msg.fire({ title: "Ocurrió un error al liberar los pedidos" });
                                        }
                                    });
                                }
                            })
                        } else {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: "warning",
                                showConfirmButton: false,
                                timer: 3000
                            });

                            Msg.fire({ title: "No hay pedidos seleccionados" });
                        }
                    }
                });

        }
    }

    // Tabla de pedidos
    tblAdminPedidosListosLiberar = $("#tblAdminPedidosListosLiberar").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: true,
        order: [
            [2, "desc"]
        ],
        ajax: {
            url: serve + "api/getPedidos/tbl3",
            data: {
                'tblActual': $('#tblActual').val(),
                'filterCliente': $('#filterCliente').val(),
                'filterVendedor': $('#filterVendedor').val(),
                'filterTrabajo': $('#filterTrabajo').val(),
                'filterEstatus': $('#filterEstatus').val(),
                'filterOP': $('#filterOP').val(),
                'filterFactura': $('#filterFactura').val(),
                'filterPedido': $('#filterPedido').val(),
                'filterEstatusCom': $('#filterEstatusCom').val(),
                'filterFechaPago': $('#filterFechaPago').val(),

                'filterFirmasVendedor': $('#filterFirmasVendedor').val(),
                'filterFirmasGerentes': $('#filterFirmasGerentes').val(),
                'filterFirmasDireccion': $('#filterFirmasDireccion').val(),

                'typeTbl': 2
            },
            type: "POST",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: buttonsLPL,
        columnDefs: [
            { 
                orderable: false,
                targets: [0, 1, 21, 22, 23, 24, 28] 
            },
            {
                visible: false,
                targets: [29,30,31,32, 33,34,35,36]
            },
            {
                targets: [16], // fecha pedido
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                targets: [27], // Fec. cobro fac
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
        ],
        columns: [
            {
                title: '', //0
                render: function (data, type, row) {
                    return '<i id="icon' + row.IDPedido + '" class="fas fa-chevron-circle-right fa-2x text-gray cursor-pointer"></i>'
                }
            },
            {
                title: '', //0
                render: function (data, type, row) {
                    if ($('#activarSeleccion').val() == 1) {
                        
                        // Validamos el rol y el estatus de la firma de dirección
                        if ($('#roleUser').val() == 1 || $('#roleUser').val() == 2 || $('#roleUser').val() == 5) {

                            // validamos si el pedido puede ser firmado, y que antes se valido los anticipos contra el saldo
                            if (row.puedeSeleccionar == 1) {
                                if ($('#roleUser').val() == 2 || $('#roleUser').val() == 5) {
                                    if (row.firmaGerenteVal == 2) {
                                        return '<input type="checkbox" data-id="markDirection" value="' + row.IDPedido + '" class="cursor-pointer">';
                                    }
                                }
    
                                if ($('#roleUser').val() == 1 || $('#roleUser').val() == 5) {
                                    if (row.firmaDireccionVal == 2) {
                                        return '<input type="checkbox" data-id="markDirection" value="' + row.IDPedido + '" class="cursor-pointer">';
                                    }
                                }
                            }
                            return '';
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                }
            },
            {
                data: 'MovIDPedido', //1
                title: 'Pedido'
            },
            {
                data: 'OP', //2
                title: 'OP'
            },
            {
                data: 'ConceptoPedido', //3
                title: 'Concepto pedido'
            },
            {
                data: 'Trabajo', //4
                title: 'Trabajo'
            },
            {
                data: 'NombreCtePedido', //5
                title: 'Cliente'
            },
            {
                data: 'NombreCteAgente', //6
                title: 'Vendedor'
            },
            {
                data: 'ImportePedido', //7
                title: '$. Pedido Org.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'ImporteVentaPerdida', //8
                title: '$. Vent. Perd',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-danger">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'TNotaCredito', //9
                title: '$. Nota Credito',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-danger">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FinalImportePedido',
                title: '$. Pedido. Final.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'Comision', //10
                title: '%. Com',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">'+new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))+' %</span>'
                }
            },
            {
                data: 'FinalImporteComision', //11
                title: '$. Com',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'porcentaje', //12
                title: '%. Com. Mod.',
                className: 'text-center',
                render: function (a) {
                    if (a == null) {
                        return '';
                    }

                    return '<span class="text-black">'+new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))+' %</span>'
                }
            },
            {
                data: 'ImporteComisionModificada', //13
                title: '$. Com. Mod.',
                className: 'text-center',
                render: function (a) {
                    if (a == null) {
                        return '';
                    }
                    
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FechaPedido', //14
                title: 'Fec pedido'
            },
            {
                data: 'importeRetencion', //15
                title: '$ Retención',
                className: 'text-center',
                render: function (a) {
                    // Formateamos el valor a moneda a modo de que no se redoandee el valor al valor proximo
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(truncateToTwoDecimals(Number(a)))+'</span>'
                }
            },
            {
                data: 'descRetencion', //16
                title: '% Retención',
                className: 'text-center'
            },
            {
                data: 'Anticipo', //17
                className: 'text-center',
                title: 'A cuenta'
            },
            {
                data: 'Saldo', //18
                className: 'text-center',
                title: 'Saldo',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'firmaVendedor', //19
                className: 'dt-center',
                title: 'F. Vendedor'
            },
            {
                data: 'firmaGerente', //20
                className: 'dt-center',
                title: 'F. Gerente'
            },
            {
                data: 'firmaDireccion', //21
                className: 'dt-center',
                title: 'F. Dirección'
            },
            {
                data: 'CheckRetencion', //22
                className: 'dt-center',
                title: 'Retenido'
            },
            {
                data: 'Estatus', //23
                title: 'Estatus'
            },
            {
                data: 'estatusCom', //24
                title: 'Estatus Com.'
            },
            {
                data: 'FechaCobroFactura', //25
                title: 'Fec. cobro fac'
            },
            {
                data: 'acciones', //26
                title: 'Acciones'
            },
            {
                data: 'totalImportePedido', //27
            },
            {
                data: 'totalComisionImporte', //28
            },
            {
                data: 'AnticipoFooter', //29
            },
            {
                data: 'SaldoFooter', //30
            },
            {
                data: 'totalImportePerdido', //31
            },
            {
                data: 'totalRetenciones' //32
            },
            {
                data: 'totalNotaCredito' //33
            },
            {
                data: 'totalComisionImporteFinal'
            }
        ],
        rowCallback: function (row, data) {
            $(row).data('facturasVisible', false);
            $(row).on('click', `i#icon${data.IDPedido}`, function (e) {
                var $this = $(row);
                var facturasVisible = $this.data('facturasVisible');
                var facturasContainer = $this.next('.facturas-container');
                var iconElement = $(row).find('i#icon' + data.IDPedido);

                if (facturasVisible) {
                    facturasContainer.slideUp(function () {
                        $(this).remove();
                    });
                    $this.data('facturasVisible', false);
                    iconElement.removeClass('fas fa-chevron-circle-down fa-2x text-gray cursor-pointer').addClass('fas fa-chevron-circle-right fa-2x text-gray cursor-pointer');
                    $this.removeClass('active-row');
                } else {

                    let idPedido = null
                    let typeIdPedido = 1 // 1 - va el texto de factura , 2 va el id de factura

                    if ($('#filterFactura').val() != null && $('#filterFactura').val() != '') {
                        idPedido = $('#filterFactura').val()
                        typeIdPedido = 1
                    } else {
                        idPedido = data.IDPedido
                        typeIdPedido = 2
                    }
                    
                    formDataFacturas = new FormData();
                    formDataFacturas.append("_token", CSRF_TOKEN);
                    formDataFacturas.append("_method", "POST");
                    formDataFacturas.append("idPedido", idPedido);
                    formDataFacturas.append("typeIdPedido", typeIdPedido);

                    $.ajax({

                        async: true,
                        type: "POST",
                        url: serve + 'facturas',
                        data: formDataFacturas,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            var html = `
                            <table style="width: 97%; margin-left: auto; margin-bottom: 0;" class="table table-hover nowrap compact tblnombre text-left w-full">
                                <thead>
                                    <tr style="border-bottom: 2px solid #C8D9EA;">
                                        <th style="border-top-left-radius: 10px !important; border-bottom-left-radius: 10px !important;">N° Factura</th>
                                        <th>Mov Factura</th>
                                        <th>$ factura</th>
                                        <th>$ cobro</th>
                                        <th>Nota de crédito</th>
                                        <th>Condicion</th>
                                        <th>Vencimiento</th>
                                        <th>Dias Venc</th>
                                        <th>Fecha factura</th>
                                        <th>Estatus comisión</th>
                                        <th style="border-top-right-radius: 10px !important; border-bottom-right-radius: 10px !important;">Fecha cobro</th>
                                    </tr>
                                </thead>
                                <tbody>
                            `;
                            let facturas = response.facturas

                            facturas.forEach(function (pedido, index) {
                                html += '<tr class="opened" data-pedido-index="' + index + '">';
                                html += '<td>' + pedido.MovIDFactura + '</td>';
                                html += '<td>' + pedido.MovFactura + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteFactura).toFixed(2)) + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteCobro).toFixed(2)) + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteAplicacion).toFixed(2)) + '</td>';
                                html += '<td>' + pedido.Condicion + '</td>';
                                html += '<td>' + pedido.Vencimiento + '</td>';
                                html += '<td>' + pedido.AlCorriente + '</td>';
                                html += '<td>' + pedido.FechaFactura + '</td>';
                                html += '<td>' + (pedido.EstatusComision == null ? 'No pagada' : pedido.EstatusComision) + '</td>';
                                html += '<td>' + pedido.FechaCobro + '</td>';
                                html += '</tr>';
                            });

                            html += '</tbody>';

                            // Sección del footer
                            html += '<tfoot>';

                            html += `
                            <tr style="background-color: #539fd6; color:#fff;">
                                <td colspan="2"><strong>Totales: </strong></td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteFactura).toFixed(2))}</td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteCobro).toFixed(2))}</td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteAplicacion).toFixed(2))}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            `;

                            html += '</tfoot></table>';

                            if (facturasContainer.length) {
                                facturasContainer.html(html);
                                facturasContainer.slideDown();
                            } else {
                                facturasContainer = $('<tr class="facturas-container"><td colspan="29" style="padding: 0;"></td></tr>');
                                facturasContainer.find('td').append(html);
                                $this.after(facturasContainer);
                                facturasContainer.slideDown();
                            }
                            $this.addClass('active-row'); // add active row class
                            $this.data('facturasVisible', true);
                        }
                    });

                    iconElement.removeClass('fas fa-chevron-circle-right fa-2x text-gray cursor-pointer').addClass('fas fa-chevron-circle-down fa-2x text-gray cursor-pointer');
                }
            });

            $(row).on('click', `input[data-id="markDirection"]`, function (e) {
                // Color de fondo del row y dams al hover el mismo color #60c8d3
                if ($(this).is(':checked')) {
                    $(this).closest('tr').css('background-color', '#60c8d3')
                } else {
                    $(this).closest('tr').css('background-color', '')
                }
            });
        },
        footerCallback: function ( row, data) {
            var api = this.api();
            // Función para convertir valores a enteros
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Sumar totales de cada columna
            var totalImporte = Number(api.column(29).data()[0]);

            var totalCosto = Number(api.column(30).data()[0]);

            var totalAnticipo = Number(api.column(31).data()[0]);

            var totalSaldo =  Number(api.column(32).data()[0]);

            var totalPerdido =  Number(api.column(33).data()[0]);

            var totalRetenciones =  Number(api.column(34).data()[0]);

            var totalNotaCredito =  Number(api.column(35).data()[0]);

            var totalComisionImporteFinal =  Number(api.column(36).data()[0]);

            // Actualizar el footer con los totales
            $(api.column(8).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalImporte.toFixed(2)));
            $(api.column(9).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPerdido.toFixed(2)));
            $(api.column(10).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalNotaCredito.toFixed(2)));
            $(api.column(11).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalComisionImporteFinal.toFixed(2)));
            $(api.column(13).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalCosto.toFixed(2)));
            $(api.column(17).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalRetenciones.toFixed(2)));
            $(api.column(19).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalAnticipo.toFixed(2)));
            $(api.column(20).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalSaldo.toFixed(2)));
        },
        pageLength: 25,
        lengthMenu: [
            [25, 50, 100, 150, 200, -1],
            [25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para marcar todos los pedidos como firmados por dirección
 * 
 * @returns void
 */
function marcarPedidosFirmadosDireccion() {
    formdatamark = new FormData();
    formdatamark.append("_token", CSRF_TOKEN);
    formdatamark.append("_method", "POST");

    $.ajax({
        async: true,
        type: "POST",
        url: serve + 'marcarPedidosFirmadosDireccion',
        data: formdatamark,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                Swal.fire({
                    title: 'Pedidos marcados correctamente',
                    text: 'Los pedidos han sido marcados como firmados',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });

                tblAdminPedidosListosLiberarTbl()
            }
        }
    });
}

/**
 * Función para alojar la tabla de pedidos liberados
 * 
 */
function tblAdminPedidosLiberadosTbl() {
    // Tabla de pedidos liberados
    tblAdminPedidosLiberados = $("#tblAdminPedidosLiberados").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        stateSave: true,
        order: [
            [1, "desc"]
        ],
        ajax: {
            url: serve + "api/getPedidos/tbl3",
            data: { // Utilizar POST cuando las tablas tengan mas de 35 columnas
                '_token': CSRF_TOKEN,
                '_method': 'POST',
                'tblActual': $('#tblActual').val(),
                'filterCliente': $('#filterCliente').val(),
                'filterVendedor': $('#filterVendedor').val(),
                'filterTrabajo': $('#filterTrabajo').val(),
                'filterComPagadas': $('#filterComPagadas').val(),
                'filterOP': $('#filterOP').val(),
                'filterEstatus': $('#filterEstatus').val(),
                'filterFactura': $('#filterFactura').val(),
                'filterPedido': $('#filterPedido').val(),
                'filterEstatusCom': $('#filterEstatusCom').val(),
                'filterFechaPago': $('#filterFechaPago').val(),
                'typeTbl': 3
            },
            type: "POST",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columnDefs: [
            { orderable: false, targets: [20, 21,22,23,29] },
            {
                visible: false,
                targets: [30,31,32,33,34,35,36,37]
            },
            {
                targets: [15], // fecha pedido
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                targets: [27], // Fec. pagado
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                targets: [28], // Fec. cobro fac
                render: $.fn.dataTable.render.moment(FROM_PATTERN_SHORT, TO_PATTERN_SHORT),
                type: 'date'
            },
        ],
        columns: [
            {
                title: '',
                render: function (data, type, row) {
                    return '<i id="icon' + row.IDPedido + '" class="fas fa-chevron-circle-right fa-2x text-gray cursor-pointer"></i>'
                }
            },
            {
                data: 'MovIDPedido', //1
                title: 'Pedido'
            },
            {
                data: 'OP', //2
                title: 'OP'
            },
            {
                data: 'ConceptoPedido', //3
                title: 'Concepto pedido'
            },
            {
                data: 'Trabajo', //4
                title: 'Trabajo'
            },
            {
                data: 'NombreCtePedido', //5
                title: 'Cliente'
            },
            {
                data: 'NombreCteAgente', //6
                title: 'Vendedor'
            },
            {
                data: 'ImportePedido', //7
                title: '$. Pedido Org.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'ImporteVentaPerdida', //8
                title: '$. Vent. Perd',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-danger">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'TNotaCredito', //9
                title: '$. Nota Credito',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-danger">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FinalImportePedido',
                title: '$. Pedido. Final.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'Comision', //10
                title: '%. Com.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">'+new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))+' %</span>'
                }
            },
            {
                data: 'FinalImporteComision', //11
                title: '$. Com.',
                className: 'text-center',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'porcentaje', //12
                title: '%. Com. Mod.',
                className: 'text-center',
                render: function (a) {
                    if (a == null) {
                        return '';
                    }

                    return '<span class="text-black">'+new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))+' %</span>'
                }
            },
            {
                data: 'ImporteComisionModificada', //13
                title: '$. Com. Mod.',
                className: 'text-center',
                render: function (a) {
                    if (a == null) {
                        return '';
                    }

                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'FechaPedido', //14
                title: 'Fec pedido'
            },
            {
                data: 'importeRetencion', //15
                title: '$ Retención',
                className: 'text-center',
                render: function (a) {
                    // Formateamos el valor a moneda a modo de que no se redoandee el valor al valor proximo
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(truncateToTwoDecimals(Number(a)))+'</span>'
                }
            },
            {
                data: 'descRetencion', //16
                title: '% Retención',
                className: 'text-center'
            },
            {
                data: 'Anticipo', //17
                className: 'text-center',
                title: 'A cuenta'
            },
            {
                data: 'Saldo', //18
                className: 'text-center',
                title: 'Saldo',
                render: function (a) {
                    return '<span class="text-black">$ '+new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))+'</span>'
                }
            },
            {
                data: 'firmaVendedor', //19
                className: 'dt-center',
                title: 'F. Vendedor'
            },
            {
                data: 'firmaGerente', //20
                className: 'dt-center',
                title: 'F. Gerente'
            },
            {
                data: 'firmaDireccion', //21
                className: 'dt-center',
                title: 'F. Dirección'
            },
            {
                data: 'Rechazar', //22
                className: 'dt-center',
                title: 'Rechazar'
            },
            {
                data: 'Estatus', //23
                title: 'Estatus'
            },
            {
                data: 'comPagada', //24
                className: 'dt-center',
                title: 'Com. Pag.'
            },
            {
                data: 'estatusCom', //25
                title: 'Estatus Com.'
            },
            {
                data: 'fechaPagado', //26
                title: 'Fecha pagado.'
            },
            {
                data: 'FechaCobroFactura', //27
                title: 'Fec. cobro fac'
            },
            {
                data: 'acciones', //28
                title: 'Acciones'
            },
            {
                data: 'totalImportePedido', //29
            },
            {
                data: 'totalComisionImporte', //30
            },
            {
                data: 'AnticipoFooter', //31
            },
            {
                data: 'SaldoFooter', //32
            },
            {
                data: 'totalImportePerdido', //33
            },
            {
                data: 'totalRetenciones' // 34
            },
            {
                data: 'totalNotaCredito' // 35
            },
            {
                data: 'totalComisionImporteFinal'
            }
        ],
        rowCallback: function (row, data) {
            $(row).data('facturasVisible', false);
            $(row).on('click', `i#icon${data.IDPedido}`, function (e) {
                var $this = $(row);
                var facturasVisible = $this.data('facturasVisible');
                var facturasContainer = $this.next('.facturas-container');
                var iconElement = $(row).find('i#icon' + data.IDPedido);

                if (facturasVisible) {
                    facturasContainer.slideUp(function () {
                        $(this).remove();
                    });
                    $this.data('facturasVisible', false);
                    iconElement.removeClass('fas fa-chevron-circle-down fa-2x text-gray cursor-pointer').addClass('fas fa-chevron-circle-right fa-2x text-gray cursor-pointer');
                    $this.removeClass('active-row');
                } else {

                    let idPedido = null
                    let typeIdPedido = 1 // 1 - va el texto de factura , 2 va el id de factura

                    if ($('#filterFactura').val() != null && $('#filterFactura').val() != '') {
                        idPedido = $('#filterFactura').val()
                        typeIdPedido = 1
                    } else {
                        idPedido = data.IDPedido
                        typeIdPedido = 2
                    }
                    
                    formDataFacturas = new FormData();
                    formDataFacturas.append("_token", CSRF_TOKEN);
                    formDataFacturas.append("_method", "POST");
                    formDataFacturas.append("idPedido", idPedido);
                    formDataFacturas.append("typeIdPedido", typeIdPedido);

                    $.ajax({

                        async: true,
                        type: "POST",
                        url: serve + 'facturas',
                        data: formDataFacturas,
                        processData: false,
                        contentType: false,

                        success: function (response) {
                            var html = `
                            <table style="width: 97%; margin-left: auto; margin-bottom: 0;" class="table table-hover nowrap compact tblnombre text-left w-full">
                                <thead>
                                    <tr style="border-bottom: 2px solid #C8D9EA;">
                                        <th style="border-top-left-radius: 10px !important; border-bottom-left-radius: 10px !important;">N° Factura</th>
                                        <th>Mov Factura</th>
                                        <th>$ factura</th>
                                        <th>$ cobro</th>
                                        <th>Nota de crédito</th>
                                        <th>Condicion</th>
                                        <th>Vencimiento</th>
                                        <th>Dias Venc</th>
                                        <th>Fecha factura</th>
                                        <th>Estatus comisión</th>
                                        <th style="border-top-right-radius: 10px !important; border-bottom-right-radius: 10px !important;">Fecha cobro</th>
                                    </tr>
                                </thead>
                                <tbody>
                            `;
                            let facturas = response.facturas

                            facturas.forEach(function (pedido, index) {
                                html += '<tr class="opened" data-pedido-index="' + index + '">';
                                html += '<td>' + pedido.MovIDFactura + '</td>';
                                html += '<td>' + pedido.MovFactura + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteFactura).toFixed(2)) + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteCobro).toFixed(2)) + '</td>';
                                html += '<td>$ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(pedido.ImporteAplicacion).toFixed(2)) + '</td>';
                                html += '<td>' + pedido.Condicion + '</td>';
                                html += '<td>' + pedido.Vencimiento + '</td>';
                                html += '<td>' + pedido.AlCorriente + '</td>';
                                html += '<td>' + pedido.FechaFactura + '</td>';
                                html += '<td>' + (pedido.EstatusComision == null ? 'No pagada' : pedido.EstatusComision) + '</td>';
                                html += '<td>' + pedido.FechaCobro + '</td>';
                                html += '</tr>';
                            });

                            html += '</tbody>';

                            // Sección del footer
                            html += '<tfoot>';

                            html += `
                            <tr style="background-color: #539fd6; color:#fff;">
                                <td colspan="2"><strong>Totales: </strong></td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteFactura).toFixed(2))}</td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteCobro).toFixed(2))}</td>
                                <td>$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(response.tImporteAplicacion).toFixed(2))}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            `;

                            html += '</tfoot></table>';

                            if (facturasContainer.length) {
                                facturasContainer.html(html);
                                facturasContainer.slideDown();
                            } else {
                                facturasContainer = $('<tr class="facturas-container"><td colspan="31" style="padding: 0;"></td></tr>');
                                facturasContainer.find('td').append(html);
                                $this.after(facturasContainer);
                                facturasContainer.slideDown();
                            }
                            $this.addClass('active-row'); // add active row class
                            $this.data('facturasVisible', true);
                        }
                    });

                    iconElement.removeClass('fas fa-chevron-circle-right fa-2x text-gray cursor-pointer').addClass('fas fa-chevron-circle-down fa-2x text-gray cursor-pointer');
                }
            });
        },
        footerCallback: function ( row, data) {
            var api = this.api();
            // Función para convertir valores a enteros
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Sumar totales de cada columna
            var totalImporte = Number(api.column(30).data()[0]);

            var totalCosto = Number(api.column(31).data()[0]);

            var totalAnticipo = Number(api.column(32).data()[0]);

            var totalSaldo =  Number(api.column(33).data()[0]);

            var totalPerdido =  Number(api.column(34).data()[0]);

            var totalRetenciones =  Number(api.column(35).data()[0]);

            var totalNotaCredito =  Number(api.column(36).data()[0]);

            var totalComisionImporteFinal =  Number(api.column(37).data()[0]);

            // Actualizar el footer con los totales
            $(api.column(7).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalImporte.toFixed(2)));
            $(api.column(8).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPerdido.toFixed(2)));
            $(api.column(9).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalNotaCredito.toFixed(2)));
            $(api.column(10).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalComisionImporteFinal.toFixed(2)));
            $(api.column(12).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalCosto.toFixed(2)));
            $(api.column(16).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalRetenciones.toFixed(2)));
            $(api.column(18).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalAnticipo.toFixed(2)));
            $(api.column(19).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalSaldo.toFixed(2)));
        },
        pageLength: 25,
        lengthMenu: [
            [25, 50, 100, 150, 200, -1],
            [25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para obtener la tabla con el listado de anticipos relacionados por pedido
 * 
 * @param {*} idComisionCandy
 * @return void
 */
function tblListAnticiposComisiones(idComisionCandy = null) {
    tblListAnticiposComisionesTbl = $("#tblAnticiposRegistrados").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: true,
        scrollY: false,
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: false,
        order: [
            [1, "desc"]
        ],
        ajax: {
            url: serve + "api/getListAnticipos",
            data: {
                'idComisionCandy': idComisionCandy,
            },
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columns: [
            {
                data: 'montoAnticipo',
                className: 'dt-center',
                title: 'A cuenta de comisión',
                render: function(a) {
                    return `$ ${new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(a).toFixed(2))}`
                }
            },
            {
                data: 'usuario',
                className: 'dt-center',
                title: 'Usuario que registro'
            },
            {
                data: 'motivo',
                className: 'dt-center',
                title: 'Motivo'
            },
            {
                data: 'fecha_pago_ant',
                className: 'dt-center',
                title: 'Fec. de pagó'
            },
            {
                data: 'created_at',
                className: 'dt-center',
                title: 'Fecha de registro'
            },
            {
                data: 'pagarComision',
                className: 'dt-center',
                title: 'Estado'
            },
            {
                data: 'eliminarComision',
                className: 'dt-center',
                title: ''
            },
        ],
        pageLength: 5,
        lengthMenu: [
            [5, 25, 50, 100, 150, 200, -1],
            [5, 25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para obtener la tabla con el listado de las incidencias registradas
 * 
 * @return void
 */
function tblIncidenciasTbl() {
    tblIncidencias = $("#tblIncidencias").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "api/getIncidencias",
            data: {
                'filterVendedorIncidencia': $('#filterVendedorIncidencia').val(),
                'filterMotivoIncidencia': $('#filterMotivoIncidencia').val(),
                'filterEstatusIncidencia': $('#filterEstatusIncidencia').val(),
                'filterQuincena': $('#filterQuincena').val(),
            },
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columnDefs: [
            { 
                orderable: false, 
                targets: [6]
            },
            {
                targets: [4], // Fecha de registro
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                visible: false,
                targets: [7]
            }
        ],
        columns: [
            {
                data: 'nombreVendedor',
                className: 'dt-center',
                title: 'Vendedor'
            },
            {
                data: 'incidencia',
                className: 'dt-center',
                title: 'Incidencia'
            },
            {
                data: 'quincena',
                className: 'dt-center',
                title: 'Quincena'
            },
            {
                data: 'createdAt',
                className: 'dt-center',
                title: 'Fecha de registro'
            },
            {
                data: 'monto',
                className: 'dt-center',
                title: 'Monto',
                render: function(a) {
                    // validar si el monto es negativo
                    if (a < 0) {
                        return '<span class="text-danger">$ ' + new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2)) + '</span>'
                    } else {
                        return '<span class="text-success">$ ' + new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2)) + '</span>'
                    }
                }
            },
            {
                data: 'estatusInc',
                className: 'dt-center',
                title: 'Estatus'
            },
            {
                data: 'acciones',
                className: 'dt-center',
                title: 'Acciones'
            },
            {
                data: 'totalIncidencias',
                className: 'dt-center',
            },
        ],
        pageLength: 25,
        lengthMenu: [
            [25, 50, 100, 150, 200, -1],
            [25, 50, 100, 150, 200, "TODOS"],
        ],
        footerCallback: function ( row, data) {
            var api = this.api();

            // Sumar totales de cada columna
            var totalImporte = Number(api.column(7).data()[0]);

            // Actualizar el footer con los totales
            $(api.column(4).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalImporte.toFixed(2)));
        },
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para obtener la tabla con el listado de las incidencias registradas
 * 
 * @return void
 */
function tblIncidenciasVendedorTbl() {
    tblIncidenciasVendedor = $("#tblIncidenciasVendedor").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "api/getIncidenciasByVendedor",
            data: {
                'idVendedor': $('#vendedorSeleccionado').val(),
            },
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columnDefs: [
            { 
                orderable: false, 
                targets: [6]
            },
            {
                targets: [4], // Fecha de registro
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
                type: 'date'
            },
            {
                visible: false,
                targets: [7]
            }
        ],
        columns: [
            {
                data: 'nombreVendedor',
                className: 'dt-center',
                title: 'Vendedor'
            },
            {
                data: 'incidencia',
                className: 'dt-center',
                title: 'Incidencia'
            },
            {
                data: 'quincena',
                className: 'dt-center',
                title: 'Quincena'
            },
            {
                data: 'createdAt',
                className: 'dt-center',
                title: 'Fecha de registro'
            },
            {
                data: 'monto',
                className: 'dt-center',
                title: 'Monto',
                render: function(a) {
                    // validar si el monto es negativo
                    if (a < 0) {
                        return '<span class="text-danger">$ ' + new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2)) + '</span>'
                    } else {
                        return '<span class="text-success">$ ' + new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2)) + '</span>'
                    }
                }
            },
            {
                data: 'estatusInc',
                className: 'dt-center',
                title: 'Estatus'
            },
            {
                data: 'acciones',
                className: 'dt-center',
                title: 'Acciones'
            },
            {
                data: 'totalIncidencias',
                className: 'dt-center',
            },
        ],
        pageLength: 5,
        lengthMenu: [
            [5, 25, 50, 100, 150, 200, -1],
            [5, 25, 50, 100, 150, 200, "TODOS"],
        ],
        footerCallback: function ( row, data) {
            var api = this.api();

            // Sumar totales de cada columna
            var totalImporte = Number(api.column(7).data()[0]);

            // Actualizar el footer con los totales
            $(api.column(4).footer()).html('Total: $ ' + new Intl.NumberFormat('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalImporte.toFixed(2)));
        },
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para obtener la tabla con el listado del catalogo de incidencias
 * 
 * @return void
 */
function tblCatMotivosIncidenciasTbl() {
    tblCatMotivoIncidencias = $("#tblCatMotivosIncidencias").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: false,
        scrollY: '460px',
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        order: [
            [0, "desc"]
        ],
        ajax: {
            url: serve + "api/getCatMotivoIncidencias",
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columnDefs: [
            { 
                orderable: false, 
                targets: [7]
            },
            {
                targets: [6], // Fecha de registro
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
                type: 'date'
            }
        ],
        columns: [
            {
                data: 'incidencia',
                className: 'dt-center',
                title: 'Incidencia'
            },
            {
                data: 'estatus',
                className: 'dt-center',
                title: 'Estatus',
                render: function(a,b,c) {
                    return '<div class="custom-switch"><input type="checkbox" class="custom-control-input cursor-pointer" onclick="changeStatusMotivoInc('+ c.idMotivoIncidencia +', this)" ' + (a == 1 ? 'checked' : '') + ' style="cursor: auto;" ><label class="custom-control-label"></label></div>'
                }
            },
            {
                data: 'recurrente',
                className: 'dt-center',
                title: 'Recurrencia',
                render: function(a) {
                    switch (a) {
                        case '1':
                            return 'Si'
                        case '2':
                            return 'No'
                        default:
                                return 'No'
                    }
                }
            },
            {
                data: 'frecuencia',
                className: 'dt-center',
                title: 'Frecuencia',
                render: function(a, b, c) {
                    if (c.recurrente == 0 || c.recurrente == null) {
                        return 'Sin frecuencia'
                    }

                    switch (a) {
                        case '1':
                            return 'Quincenal'
                        case '2':
                            return 'Mensual';
                        default:
                            return 'Sin frecuencia';
                    }
                }
            },
            {
                data: 'tipo',
                className: 'dt-center',
                title: 'Tipo',
                render: function(a, b, c) {

                    if (c.recurrente == 0) {
                        return 'Sin tipo'
                    }

                    switch (a) {
                        case '1':
                            return 'Fijo'
                        case '2':
                            return 'Manual';
                        default:
                            return 'Sin tipo';
                    }
                }
            },
            {
                data: 'monto',
                className: 'dt-center',
                title: 'Monto',
                render: function(a, b, c) {

                    if (c.recurrente == 0) {
                        return 'No aplica'
                    }
                    
                    switch (c.tipo) {
                        case '1':
                            return '$ ' + new Intl.NumberFormat('es-MX').format(Number(a).toFixed(2))
                        case '2':
                            return 'Manual';
                        default:
                            return 'No aplica';
                    }
                }
            },
            {
                data: 'createdAt',
                className: 'dt-center',
                title: 'Fecha de registro'
            },
            
            {
                data: 'acciones',
                className: 'dt-center',
                title: 'Acciones'
            },
        ],
        pageLength: 25,
        lengthMenu: [
            [25, 50, 100, 150, 200, -1],
            [25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para obtener la tabla con el listado del historico por comisión
 * 
 * @param idPedido
 * @return void
 */
function tblHistoricoDetails(idPedido) {
    tblHistoricoDetailsTbl = $("#tblHistoricoDetails").DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        select: true,
        scrollY: false,
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        ordering: true,
        order: [
            [1, "desc"]
        ],
        ajax: {
            url: serve + "api/getHistoricoDetails",
            data: {
                'idPedido': idPedido,
            },
            type: "GET",
            dataType: "json",
        },
        language: {
            url: serve + "js/spanish-DT.json",
        },
        dom: '<"top">rt<"bottom"lpBi><"clear">',
        buttons: [
            {
                extend: "colvis",
                columns: [0, 1, 2],
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
                collectionLayout: "fixed two-column",
            },
        ],
        columnDefs: [
            {
                targets: [5], // Fecha de registro
                render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
                type: 'date'
            }
        ],
        columns: [
            {
                data: 'section',
                title: 'Sección',
            },
            {
                data: 'previousChange',
                title: 'Valor previo'
            },
            {
                data: 'newChange',
                title: 'Nuevo cambio'
            },
            {
                data: 'pedido',
                title: 'Pedido'
            },
            {
                data: 'Nombre',
                title: 'Usuario'
            },
            {
                data: 'created_at',
                title: 'Fecha de registro'
            }
        ],
        pageLength: 15,
        lengthMenu: [
            [5, 15, 25, 50, 100, 150, 200, -1],
            [5, 15, 25, 50, 100, 150, 200, "TODOS"],
        ],
        infoCallback: function (settings, start, end, max, total, pre) {
            if (total == 0) return "<strong class='ml-2'>Sin registros aun</strong>";
            return '<strong class="ml-2">' + end + ' de ' + total + ' registros aun</strong>';
        },
    });
}

/**
 * Función para aplicar el filtro de busqueda
 * 
 * @return void
 */
function aplicarFiltroAdmPed() {
    /*           Inicio conf local storage para filtros            */

    // Guardamos en el local estorage el valor de los filtros de acuerdo al tab seleccionado
    let tbl = $('#tblActual').val();

    // Arreglo de los filtros
    let filters = {
        'filterPedido': $('#filterPedido').val(),
        'filterOP': $('#filterOP').val(),
        'filterFactura': $('#filterFactura').val(),
        'filterCliente': $('#filterCliente').val(),
        'filterTrabajo': $('#filterTrabajo').val(),
        'filterVendedor': $('#filterVendedor').val(),
        'filterComPagadas': $('#filterComPagadas').val(),
        'filterEstatusCom': $('#filterEstatusCom').val(),
        'filterEstatus': $('#filterEstatus').val(),
        'filterFechaPago': $('#filterFechaPago').val(),
        'filterFirmasVendedor': $('#filterFirmasVendedor').val(),
        'filterFirmasGerentes': $('#filterFirmasGerentes').val(),
        'filterFirmasDireccion': $('#filterFirmasDireccion').val(),
    }

    // Guardamos el valor del filtro
    localStorage.setItem(tbl, JSON.stringify(filters));

    /*           Fin conf local storage para filtros            */

    if (tbl == 'tbl1') {
        tblAdminPedidosTbl()
    } else if (tbl == 'tbl2') {
        tblAdminPedidosListosLiberarTbl()
    } else if (tbl == 'tbl3') {
        tblAdminPedidosLiberadosTbl()
    } else {
        console.log('tab seleccionada no encontrada');
    }
}

/**
 * Función para aplicar el filtro de busqueda
 * 
 * @return void
 */
function aplicarFiltroIncidencias() {
    tblIncidenciasTbl()
}

/**
 * Función para limpiar el filtro de búsquedas
 * 
 * @return mixed
 */
function limpiarFiltroAdmPed(tblA) {
    let tbl = $('#tblActual').val();
    clearInputFilters();

    // Limpiamos los filtros guardados en el localStorage
    if (tblA != null) {
        localStorage.removeItem(tblA)
    }

    if (tbl == 'tbl1') {
        tblAdminPedidosTbl()
    } else if (tbl == 'tbl2') {
        tblAdminPedidosListosLiberarTbl()
    } else if (tbl == 'tbl3') {
        tblAdminPedidosLiberadosTbl()
    } else {
        console.log('tab seleccionada no encontrada');
    }
}

/**
 * Función para limpiar el filtro de búsquedas (Incidencias)
 * 
 * @return mixed
 */
function limpiarFiltroIncidencias() {
    clearInputFiltersIncidencias();
    tblIncidenciasTbl()
}

/**
 * Función para limpiar solo los campos de filtros
 * 
 * @return void
 */
function clearInputFilters() {
    $('#filterCliente').val('')
    $('#filterFechaPago').val('')
    $('#filterVendedor').val('')
    $('#filterVendedor').css('background-color', '')
    $('#filterTrabajo').val('')
    $('#filterEstatus').val('')
    $('#filterEstatus').css('background-color', '')
    $('#filterEstatusCom').val('')
    $('#filterEstatusCom').css('background-color', '')
    $('#filterOP').val('')
    $('#filterFactura').val('')
    $('#filterPedido').val('')
    $('#filterEstatusCon').val('')
    $('#filterComPagadas').val('5')
    $('#filterComPagadas').css('background-color', '')
    $('#filterFirmasVendedor').val('')
    $('#filterFirmasVendedor').css('background-color', '')
    $('#filterFirmasGerentes').val('')
    $('#filterFirmasGerentes').css('background-color', '')
    $('#filterFirmasDireccion').val('')
    $('#filterFirmasDireccion').css('background-color', '')
}

/**
 * Función para limpiar solo los campos de filtros (Incidencias)
 * 
 * @return void
 */
function clearInputFiltersIncidencias() {
    $('#filterPedidoIncidencia').val('')
    $('#filterVendedorIncidencia').val('')
    $('#filterVendedorIncidencia').css('background-color', '')
    $('#filterMotivoIncidencia').val('')
    $('#filterMotivoIncidencia').css('background-color', '')
    $('#filterEstatusIncidencia').val('')
    $('#filterEstatusIncidencia').css('background-color', '')
    $('#filterQuincena').val('')
    $('#filterQuincena').css('background-color', '')
}

/**
 * Función para abrir el modal para cambiar la comisión de un pedi
 *  
 * @param {*} idPedidoIntellisis
 * @return void
 */
function openModalChangeComision(idPedidoIntellisis) {
    // Abrimos el modal para cambiar la comisión
    $("#mod_ChangeComision").modal({
        backdrop: 'static',
        show: true
    });

    // Limpiamos los mensajes de error
    $('#msg-errors-impComision').html('')
    $('#msg-errors-motivoActualizacion').html('')
    $('#msg-errors-porcComision').html('')

    // Prevenimos que se accione el formulario con cualquier acción de submit
    $("#formUpdateCom").on('submit', function (event) {
        event.preventDefault()
    })

    // Creamos el formulario que se enviara para realizar el guardado de la información
    formDataComPedidos = new FormData();
    formDataComPedidos.append("_token", CSRF_TOKEN);
    formDataComPedidos.append("_method", "POST");
    formDataComPedidos.append("idPedidoIntellisis", idPedidoIntellisis);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getConsecutivo",
        async: true,
        type: "POST",
        data: formDataComPedidos,
        processData: false,
        contentType: false,
        success: function (data) {
            // Si todo resulta correcto solo agregamos los datos
            $('#idPedidoIntellisis').val(data.IdPedidoIntellisis);
            $('#impComActual').html((data.ImporteComisionOriginal < 0) ? data.ImporteComisionOriginal.toString().replace('-', '- $') : '$' + data.ImporteComisionOriginal);
            $('#impComModificado').html((data.ImporteComisionModificado == null ? 'Sin modificar' : ((data.ImporteComisionModificado < 0) ? data.ImporteComisionModificado.toString().replace('-', '- $') : '$' + data.ImporteComisionModificado)));
            $('#impComision').val(data.ImporteComisionModificado);
            $('#motivoActualizacion').val(data.MotivoActualizacion);
            $('#ultFecActualizacion').html(data.FechaActualización);
            $('#nomCliente').html(data.NombreCliente);
            $('#trabajo').html(data.Trabajo);
            $('#porcComOrg').html(data.PorcentajeComision + ' %');
            $('#porcComMod').html(Number(data.PorcentajeComisionModificado).toFixed(2));
            $('#porcComision').val(Number(data.PorcentajeComisionModificado).toFixed(2));
            $('#importePedido').html("$ " + data.ImportePedido);
            $('#valorPedido').val(data.ImportePedido);
            $('#op').val(data.OP);
            $('#pedido').val(data.Pedido);

            $('#retComPorc').html(data.descPorcRet);
            $('#retPorcentaje').val(data.descPorcRet);
            $('#impRet').html(data.importeRetencion);
            $('#impNoRet').html(data.impNoRet);
            $('#fechaPedido').html(data.fechaPedido);
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
        }
    })
}

/**
 * Función para cerrar el modal de edición
 * 
 */
function closeModalPdd() {
    loaderOut();
    $("#mod_ChangeComision").modal("hide")
}

/**
 * Función para actualizar la comisón
 * 
 * @return void
 */
function updateImporteComision() {
    let idPedidoIntellisis = $('#idPedidoIntellisis').val();
    let comNueva = $('#impComision').val();
    let porcNuevo = $('#porcComision').val();
    let motivoActualizacion = $('#motivoActualizacion').val();
    let op = $('#op').val();
    let pedido = $('#pedido').val();

    formActionComPedidos = new FormData();
    formActionComPedidos.append("_token", CSRF_TOKEN);
    formActionComPedidos.append("_method", "POST");
    formActionComPedidos.append("idPedidoIntellisis", idPedidoIntellisis);
    formActionComPedidos.append("nuevaComision", comNueva);
    formActionComPedidos.append("motivoActualizacion", motivoActualizacion);
    formActionComPedidos.append("porcentaje", Math.abs(porcNuevo));
    formActionComPedidos.append("op", op);
    formActionComPedidos.append("pedido", pedido);

    // Función para actualizar la cotización en la base de candy
    $.ajax({
        url: serve + "updateComision",
        async: true,
        type: "POST",
        data: formActionComPedidos,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos que no hayan errores en la validación
            if (data.errores > 0) {
                $('#msg-errors-impComision').html('')
                $('#msg-errors-motivoActualizacion').html('')
                $('#msg-errors-impComision').append(data.error_nuevaComision)
                $('#msg-errors-motivoActualizacion').append(data.error_motivoActualizacion)
                $('#msg-errors-porcComision').append(data.error_porcentaje)
                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire('Se realizó la actualización correctamente');
                tblAdminPedidos.ajax.reload();
                tblAdminPedidosListosLiberar.ajax.reload();
                tblAdminPedidosLiberados.ajax.reload();
                $("#mod_ChangeComision").modal("hide")
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

            Msg.fire({ title: "Ocurrió un error, al realizar la acción" });
        }
    })
}

/**
 * Función para actualizar el valor de retención en la tabla de listado de pedidos
 * 
 * @param {*} idPedidoIntellisis
 * @param {*} check
 * @param {*} Op
 * @param {*} pedido
 * @param {*} type 1 retenido, 2 rechazado
 * @param {*} typoAnticipo 2 Anticipo, 1 Comisión
 * @return void
 */
function changeRetenido(idPedidoIntellisis, check, Op, pedido, type, typoAnticipo = 1) {

    loaderON();

    if (typoAnticipo == 1) {
        Swal.fire({
            input: "textarea",
            inputLabel: (type == 1 ? (check.checked ? "¿Por qué se retiene?" : "¿Por qué se remueve la retención?") : (check.checked ? "¿Por qué se rechaza?" : "¿Por qué se remueve el rechazo?")),
            inputPlaceholder: "Captura el motivo",
            inputAttributes: {
                "aria-label": "Captura el motivo",
            },
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Guardar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('¡Debes de capturar el motivo!')
                    return false;
                }
                return value;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Creamos el formulario que se enviara para realizar la actualización del campo de "retenido"
                formDataUpdRetenido = new FormData();
                formDataUpdRetenido.append("_token", CSRF_TOKEN);
                formDataUpdRetenido.append("_method", "POST");
                formDataUpdRetenido.append("idPedidoIntellisis", idPedidoIntellisis);
                formDataUpdRetenido.append("retencion", check.checked);
                formDataUpdRetenido.append("op", Op);
                formDataUpdRetenido.append("pedido", pedido);
                formDataUpdRetenido.append("motivoRechazo", result.value);
                formDataUpdRetenido.append("tipo", type);
                formDataUpdRetenido.append("tipoAnticipo", typoAnticipo);
    
                // Ejecutamos la acción de ajax
                $.ajax({
                    url: serve + "updateRetenido",
                    async: true,
                    type: "POST",
                    data: formDataUpdRetenido,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.response) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });
    
                            Msg.fire('Se a rechazado el pedido');
                            tblAdminPedidos.ajax.reload();
                            tblAdminPedidosListosLiberar.ajax.reload();
                            tblAdminPedidosLiberados.ajax.reload();
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
    
                        Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
                    }
                })
            } else {
                loaderOut()
                tblAdminPedidosListosLiberarTbl()
                tblAdminPedidosLiberadosTbl()
            }
        });
    } else {
        Swal.fire({
            input: "textarea",
            inputLabel: "¿Desear cancelar el A cuenta de?",
            inputPlaceholder: "Captura el motivo",
            inputAttributes: {
                "aria-label": "Captura el motivo",
            },
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonText: "Guardar",
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#999",
            preConfirm: (value) => {
                if (!value) {
                    Swal.showValidationMessage('¡Debes de capturar el motivo!')
                    return false;
                }
                return value;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Creamos el formulario que se enviara para realizar la actualización del campo de "retenido"
                formDataUpdCancelacionAnt = new FormData();
                formDataUpdCancelacionAnt.append("_token", CSRF_TOKEN);
                formDataUpdCancelacionAnt.append("_method", "POST");
                formDataUpdCancelacionAnt.append("idPedidoIntellisis", idPedidoIntellisis);
                formDataUpdCancelacionAnt.append("retencion", check.checked);
                formDataUpdCancelacionAnt.append("op", Op);
                formDataUpdCancelacionAnt.append("pedido", pedido);
                formDataUpdCancelacionAnt.append("motivoRechazo", result.value);
                formDataUpdCancelacionAnt.append("tipo", type);
                formDataUpdCancelacionAnt.append("tipoAnticipo", typoAnticipo);
    
                // Ejecutamos la acción de ajax
                $.ajax({
                    url: serve + "updateRetenido",
                    async: true,
                    type: "POST",
                    data: formDataUpdCancelacionAnt,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.response) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });
    
                            Msg.fire('Se a rechazado el pedido');
                            tblAdminPedidos.ajax.reload();
                            tblAdminPedidosListosLiberar.ajax.reload();
                            tblAdminPedidosLiberados.ajax.reload();
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
    
                        Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
                    }
                })
            } else {
                loaderOut()
                tblAdminPedidosListosLiberarTbl()
                tblAdminPedidosLiberadosTbl()
            }
        });
    }
}

/**
 * Función para colocar los valores de cada filtro almacenado en el local storage por tab
 * 
 * @param {*} tbl
 * @return void
 */
function setFilters(tbl) {
    let filters = JSON.parse(localStorage.getItem(tbl));

    if (filters != null) {
        $('#filterPedido').val(filters.filterPedido)
        $('#filterOP').val(filters.filterOP)
        $('#filterFactura').val(filters.filterFactura)
        $('#filterCliente').val(filters.filterCliente)
        $('#filterTrabajo').val(filters.filterTrabajo)

        $('#filterVendedor').val(filters.filterVendedor)
        $('#filterVendedor').css('background-color', filters.filterVendedor != '' ? 'yellow' : '')
        
        $('#filterEstatusCom').val(filters.filterEstatusCom)
        $('#filterEstatusCom').css('background-color', filters.filterEstatusCom != '' ? 'yellow' : '')
        
        $('#filterComPagadas').val(filters.filterComPagadas)
        $('#filterComPagadas').css('background-color', filters.filterComPagadas != '' ? 'yellow' : '')
        
        $('#filterFechaPago').val(filters.filterFechaPago)

        // Esperamos a que se cargue el select de estatus para asignar el valor
        setTimeout(() => {
            $('#filterEstatus').val(filters.filterEstatus)
            $('#filterEstatus').css('background-color', filters.filterEstatus != '' ? 'yellow' : '')
        }, 500);
    }
}

/**
 * Función para cambiar de tab y asignar las funciones correspondientes
 * 
 * @param {*} tab
 * @return mixed
 */
function changeTab(tab) {
    let tab1 = document.getElementById('tab1')
    let tab2 = document.getElementById('tab2')
    let tab3 = document.getElementById('tab3')
    let tab4 = document.getElementById('tab4')

    let filtersIncidencias = document.getElementById('filtersIncidencias')

    let mainFilters = document.getElementById('filtersMain')

    if (tab == 'tab-1') {
        // Asignamos la clase activa
        tab1.classList.add('tb-item-active');

        // Validamos si el tab 2 contiene la clase activa se la removemos
        if (tab2.classList.contains('tb-item-active')) {
            tab2.classList.remove('tb-item-active');
        }

        // Validamos si el tab 3 tiene la clase activa se la removemos
        if (tab3.classList.contains('tb-item-active')) {
            tab3.classList.remove('tb-item-active');
        }
        
        // Validamos si el tab4 tiene la clase activa se la removemos
        if (tab4.classList.contains('tb-item-active')) {
            tab4.classList.remove('tb-item-active');
        }

        // Ocultamos la sección de filtro de incidencias
        filtersIncidencias.classList.add('d-none')
        filtersIncidencias.classList.remove('d-flex')

        // Ocultamos la tabla de pedidos liberados
        $('#pedidosLiberados').hide()

        // Ocultamos la tabla de pedidos listos para liberar
        $('#listPedidosParaLiberar').hide()

        // Ocultamos el panel de incidencias
        $('#incidenciasPanel').hide()

        // Mostrar el menu de filtros
        mainFilters.classList.remove('d-none')
        mainFilters.classList.add('d-flex')

        // Mostramos el filtro de estatus
        $('#compFilterEstatus').show()

        // Mostramos el filtro de estatus com cuando sea el tab 1
        $('#compFilterEstatusCom').show()

        // Ocultamos el filtro de tipo de comisiones liberadas
        $('#compFilterComPagadas').hide()

        // Mostramos la tabla de pedidos generales
        $('#listMain').show()

        // Asignamos el valor del tab actual
        $('#tblActual').val('tbl1');

        // Limpiamos los filtros cuando se cambie de tab
        clearInputFilters();

        // Rellenamos el select con los estatus que aparecen de los registros
        getListFilterAdminPedidos(1)

        // Asignamos los valores de los filtros si existen
        setFilters('tbl1')

        // Agregamos el valor de tbl para el boton de limpia filtros
        $('#btn-cleanEstr').attr('onclick', "limpiarFiltroAdmPed('tbl1')")

        // Ocultamos el contenedor de filtros adicionales
        $('#contentAddFilters').addClass('d-none')

        // Mostramos el filtro de fecha de pago
        $('#comFilterFechaPago').show()

        // Refrescamos la tabla después de 1000 milisegundos
        setTimeout(() => {
            tblAdminPedidosTbl();
        }, 600);
    } else if (tab == 'tab-2') {
        // Asignamos la clase activa
        tab2.classList.add('tb-item-active');

        // Validamos si el tab 1 contiene la clase activa se la removemos
        if (tab1.classList.contains('tb-item-active')) {
            tab1.classList.remove('tb-item-active');
        }

        // Validamos si el tab 3 tiene la clase activa se la removemos
        if (tab3.classList.contains('tb-item-active')) {
            tab3.classList.remove('tb-item-active');
        }

        // Validamos si el tab4 tiene la clase activa se la removemos
        if (tab4.classList.contains('tb-item-active')) {
            tab4.classList.remove('tb-item-active');
        }

        // Ocultamos la sección de filtro de incidencias
        filtersIncidencias.classList.add('d-none')
        filtersIncidencias.classList.remove('d-flex')

        // Ocultamos el tab de pedidos liberados
        $('#pedidosLiberados').hide()

        // mostramos la tabla de pedidos listos para liberar
        $('#listPedidosParaLiberar').show()

        // Ocultamos la tabla de pedidos generales
        $('#listMain').hide()

        // Ocultamos el panel de incidencias
        $('#incidenciasPanel').hide()

        // Mostrar el menu de filtros
        mainFilters.classList.remove('d-none')
        mainFilters.classList.add('d-flex')

        // Mostramos el filtro de estatus com cuando sea el tab 2
        $('#compFilterEstatusCom').show()

        // Ocultamos el filtro de tipo de comisiones liberadas
        $('#compFilterComPagadas').hide()

        // Asignamos el valor del tab actual
        $('#tblActual').val('tbl2');

        // Limpiamos los filtros cuando se cambie de tab
        clearInputFilters();

        // Rellenamos el select con los estatus que aparecen de los registros
        getListFilterAdminPedidos(2)

        // Asignamos los valores de los filtros si existen
        setFilters('tbl2')

        // Agregamos el valor de tbl para el boton de limpia filtros
        $('#btn-cleanEstr').attr('onclick', "limpiarFiltroAdmPed('tbl2')")

        // Mostramos el contenedor de filtros adicionales
        $('#contentAddFilters').removeClass('d-none')

        // Ocultamos el filtro de fecha de pago
        $('#comFilterFechaPago').hide()

        // Refrescamos la tabla
        setTimeout(() => {
            tblAdminPedidosListosLiberarTbl();
        }, 600);
    } else if (tab == 'tab-3') {
        // Asignamos la clase activa
        tab3.classList.add('tb-item-active');

        // Validamos si el tab 1 contiene la clase activa se la removemos
        if (tab1.classList.contains('tb-item-active')) {
            tab1.classList.remove('tb-item-active');
        }

        // Validamos si el tab 2 tiene la clase activa se la removemos
        if (tab2.classList.contains('tb-item-active')) {
            tab2.classList.remove('tb-item-active');
        }

        // Validamos si el tab4 tiene la clase activa se la removemos
        if (tab4.classList.contains('tb-item-active')) {
            tab4.classList.remove('tb-item-active');
        }

        // Mostramos el tab de pedidos liberados
        $('#pedidosLiberados').show()

        // Ocultamos la tabla de pedidos listos para liberar
        $('#listPedidosParaLiberar').hide()

        // Ocultamos la tabla de pedidos generales
        $('#listMain').hide()

        // Ocultamos el panel de incidencias
        $('#incidenciasPanel').hide()

        // Mostrar el menu de filtros
        mainFilters.classList.remove('d-none')
        mainFilters.classList.add('d-flex')
        
        // Ocultamos el filtro de estatus cuando sea el tab 3
        $('#compFilterEstatusCom').hide()

        // Ocultamos el filtro de tipo de comisiones liberadas
        $('#compFilterComPagadas').show()

        // Ocultamos la sección de filtro de incidencias
        filtersIncidencias.classList.add('d-none')
        filtersIncidencias.classList.remove('d-flex')

        // Asignamos el valor del tab actual
        $('#tblActual').val('tbl3');

        // Limpiamos los filtros cuando se cambie de tab
        clearInputFilters();

        // Rellenamos el select con los estatus que aparecen de los registros
        getListFilterAdminPedidos(3)

        // Asignamos los valores de los filtros si existen
        setFilters('tbl3')

        // Agregamos el valor de tbl para el boton de limpia filtros
        $('#btn-cleanEstr').attr('onclick', "limpiarFiltroAdmPed('tbl3')")

        // Ocultamos el contenedor de filtros adicionales
        $('#contentAddFilters').addClass('d-none')

        // Mostramos el filtro de fecha de pago
        $('#comFilterFechaPago').show()

        // Refrescamos la tabla
        setTimeout(() => {
            tblAdminPedidosLiberadosTbl()
        }, 600);
    } else {
        // Asignamos la clase activa
        tab4.classList.add('tb-item-active');

        // Mostramos el panel de incidencias
        $('#incidenciasPanel').show()

        // Ocultamos la tabla de pedidos liberados
        $('#pedidosLiberados').hide()

        // Mostramos la sección de filtro de incidencias
        filtersIncidencias.classList.remove('d-none')
        filtersIncidencias.classList.add('d-flex')

        // Ocultamos la tabla de pedidos generales
        $('#listMain').hide()

        // Ocultar el menu de filtros
        mainFilters.classList.remove('d-flex')
        mainFilters.classList.add('d-none')

        // Ocultamos la tabla de pedidos listos para liberar
        $('#listPedidosParaLiberar').hide()

        // Validamos si el tab 1 contiene la clase activa se la removemos
        if (tab1.classList.contains('tb-item-active')) {
            tab1.classList.remove('tb-item-active');
        }

        // Validamos si el tab 2 tiene la clase activa se la removemos
        if (tab2.classList.contains('tb-item-active')) {
            tab2.classList.remove('tb-item-active');
        }
        
        // Validamos si el tab 3 tiene la clase activa se la removemos
        if (tab3.classList.contains('tb-item-active')) {
            tab3.classList.remove('tb-item-active');
        }

        // refrescamos la tabla de incidencias
        tblIncidenciasTbl()
    }
}

/**
 * Función para calcular el porcentaje de la comisión y vicebersa
 * 
 * @param {*} inpt
 * @return mixed
 */
function calcComisionPar(inpt) {
    let valorPrincipal = $('#valorPedido').val();
    let operacion = 0;

    if (inpt.id == 'impComision') {
        operacion = ((inpt.value * 100) / valorPrincipal);
        // operacion = operacion - (operacion * ($('#retPorcentaje').val() / 100));
        $('#porcComision').val(Math.abs(Number(operacion.toFixed(2))))
    } else {
        operacion = ((valorPrincipal * Math.abs(inpt.value)) / 100);
        // operacion = operacion - (operacion * ($('#retPorcentaje').val() / 100));
        $('#impComision').val(Number(operacion.toFixed(2)))
    }
}

/**
 * Función para abrir un modal y visualizar la información de los detalles del pedido
 * 
 * @param {*} idPedidoIntellisis
 * @return void
 */
function openModalDetailsPedido(idPedidoIntellisis) {
    // Abrimos el modal para cambiar la comisión
    $("#mod_DetailsComision").modal({
        backdrop: 'static',
        show: true
    });

    // Creamos el formulario que se enviara para realizar el guardado de la información
    formDataComPedidosDetails = new FormData();
    formDataComPedidosDetails.append("_token", CSRF_TOKEN);
    formDataComPedidosDetails.append("_method", "POST");
    formDataComPedidosDetails.append("idPedidoIntellisis", idPedidoIntellisis);
    formDataComPedidosDetails.append("typeGetData", 1);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getConsecutivo",
        async: true,
        type: "POST",
        data: formDataComPedidosDetails,
        processData: false,
        contentType: false,
        success: function (data) {
            // Si todo resulta correcto solo agregamos los datos
            $('#dnomCliente').html(data.NombreCliente);
            $('#dtrabajo').html(data.Trabajo);
            $('#dfechaUltCobro').html(data.FechaCobro);
            $('#dfechaPedido').html(data.FechaPedido);
            $('#dimportePedido').html("$ " + data.ImportePedido);
            $('#dimporteComisionFinal').html("$ " + data.ImporteComision);
            $('#dporcentajeComisionFinal').html(data.PorcentajeComisionFinal + ' %');
            $('#dimpComModificado').html((data.ImporteComisionModificado == null ? 'Sin modificar' : ((data.ImporteComisionModificado < 0) ? data.ImporteComisionModificado.toString().replace('-', '- $') : '$' + data.ImporteComisionModificado)));
            $('#dimpComActual').html((data.ImporteComisionOriginal < 0) ? data.ImporteComisionOriginal.toString().replace('-', '- $') : '$' + data.ImporteComisionOriginal);
            $('#dporcComMod').html(Number(data.PorcentajeComisionModificado).toFixed(2));
            $('#dporcComOrg').html(data.PorcentajeComision + ' %');
            $('#dnoOp').html(data.OP);
            $('.dpedido').html(data.Pedido);
            $('#dvendedor').html(data.Vendedor);
            $('#dgerente').html(data.Gerente);
            $('#ddireccion').html(data.Direccion);
            $('#dvendedorFecha').html(formatDateSigners(data.VendedorFechaFirma));
            $('#dgerenteFecha').html(formatDateSigners(data.GerenteFechaFirma));
            $('#ddireccionFecha').html(formatDateSigners(data.DireccionFechaFirma));

            $('#dSaldoFinal').html('$ ' + data.saldoFinal);

            $('#dRetencionDescripcion').html(data.retencionDescripcion);
            $('#dRetencionFinal').html('$ ' + data.retencionFinal);

            $('#dretComPorc').html(data.descPorcRet);
            $('#dretPorcentaje').val(data.descPorcRet);
            $('#dimpRet').html('$ '+data.importeRetencion);
            $('#dimpNoRet').html('$ '+data.impNoRet);
            $('#dfechaPedido').html(data.FechaPedido);
            
            tblHistoricoDetails(idPedidoIntellisis);
        },
        error: function () {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000
            });

            Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
        }
    })
}

/**
 * Función para formatear la fecha
 * 
 * @param {*} dateSigned
 * @return mixed
 */
function formatDateSigners(dateSigned) {
    if (dateSigned != null) {
        let date = new Date(dateSigned);
        date.setMilliseconds(0);
        let formattedDate = date.toLocaleString('es-MX', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        return formattedDate;
    }

    return null;
}

/**
 * Función para cerrar el modal de detalles de un pedido
 * 
 * @return void
 */
function dCloseModalPdd() {
    loaderOut();
    $("#mod_DetailsComision").modal("hide")
}

/**
 * Función para aprovar o rechazar un pedido
 * 
 * @param {*} idPedidoIntellisis
 * @param {*} check
 * @param {*} Op
 * @param {*} pedido
 * @param {*} typePerson
 * @return void
 */
function approveReject(idPedidoIntellisis, check, Op, pedido, typePerson) {
    // Creamos el formulario que se enviara para realizar la actualización del campo de "retenido"
    formDataUpdFirmas = new FormData();
    formDataUpdFirmas.append("_token", CSRF_TOKEN);
    formDataUpdFirmas.append("_method", "POST");
    formDataUpdFirmas.append("idPedido", idPedidoIntellisis);
    formDataUpdFirmas.append("typePerson", typePerson);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "knowIfWasSigned",
        async: true,
        type: "POST",
        data: formDataUpdFirmas,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                approveRejectPedido(idPedidoIntellisis, check, Op, pedido, typePerson)
            } else {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire({ title: data.message });

                tblAdminPedidosListosLiberar.ajax.reload();
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

            Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
        }
    })
}

/**
 * Función para realizar la aprovación o rechazo de un pedido
 * 
 * @param {*} idPedidoIntellisis
 * @param {*} check
 * @param {*} Op
 * @param {*} pedido
 * @param {*} typePerson
 * @return void
 */
function approveRejectPedido(idPedidoIntellisis, check, Op, pedido, typePerson) {
    loaderON();

    // Creamos el formulario que se enviara para realizar la actualización del campo de "retenido"
    formDataUpdFirmas = new FormData();
    formDataUpdFirmas.append("_token", CSRF_TOKEN);
    formDataUpdFirmas.append("_method", "POST");
    formDataUpdFirmas.append("idPedidoIntellisis", idPedidoIntellisis);
    formDataUpdFirmas.append("signature", check.checked);
    formDataUpdFirmas.append("op", Op);
    formDataUpdFirmas.append("pedido", pedido);
    formDataUpdFirmas.append("typePerson", typePerson);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "updateFirma",
        async: true,
        type: "POST",
        data: formDataUpdFirmas,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire(check.checked ? 'Ha autorizado este pedido' : 'No se aurorizo el pedido');
                tblAdminPedidos.ajax.reload();
                tblAdminPedidosLiberados.ajax.reload();
                tblAdminPedidosListosLiberar.ajax.reload();
            } else {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire({ title: data.message });

                tblAdminPedidosListosLiberar.ajax.reload();
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

            Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
        }
    })
}

/**
 * Función para mostrar el mensaje de rechazo del pedido
 * 
 * @param {*} idComLito
 * @return void
 */
function showNotesRejected(idComLito) {
    // Creamos el formulario que se enviara para realizar la actualización del campo de "retenido"
    formDataNotesReject = new FormData();
    formDataNotesReject.append("_token", CSRF_TOKEN);
    formDataNotesReject.append("_method", "POST");
    formDataNotesReject.append("idComLito", idComLito);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getMotivoRechazo",
        async: true,
        type: "POST",
        data: formDataNotesReject,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.notes) {
                Swal.fire({
                    text: data.notes,
                    icon: "info"
                });
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

            Msg.fire({ title: "Ocurrió un error, al obtener la información" });
        }
    })
}

/**
 * Función para generar el reporte de comisiones liberadas
 * 
 * @return void
 */
function generateReportPlV2() {
    let tblActual = $('#tblActual').val();
    let nombreReporte = '';
    let typeTable;

    switch (tblActual) {
        case 'tbl1':
            nombreReporte = 'Reporte de comisiones generales';
            typeTable = 1;
            break;
        case 'tbl2':
            nombreReporte = 'Reporte de comisiones listas para liberar';
            typeTable = 2;
            break;
        case 'tbl3':
            nombreReporte = 'Reporte de comisiones liberadas';
            typeTable = 4;
            break;
        default:
            typeTable = 4;
        break;
    }

    // Creamos el formulario que se enviara para descargar el excel
    formDataDownloadExcel = new FormData();
    formDataDownloadExcel.append("_token", CSRF_TOKEN);
    formDataDownloadExcel.append("_method", "POST");
    formDataDownloadExcel.append('typeTbl', typeTable);
    formDataDownloadExcel.append('tblActual', tblActual);
    formDataDownloadExcel.append('filterCliente', $('#filterCliente').val());
    formDataDownloadExcel.append('filterVendedor', $('#filterVendedor').val());
    formDataDownloadExcel.append('filterTrabajo', $('#filterTrabajo').val());
    formDataDownloadExcel.append('filterEstatus', $('#filterEstatus').val());
    formDataDownloadExcel.append('filterOP', $('#filterOP').val());
    formDataDownloadExcel.append('filterPedido', $('#filterPedido').val());
    formDataDownloadExcel.append('filterFactura', $('#filterFactura').val());
    formDataDownloadExcel.append('filterComPagadas', $('#filterComPagadas').val());
    formDataDownloadExcel.append('filterFechaPago', $('#filterFechaPago').val());
    formDataDownloadExcel.append('filterEstatusCom', $('#filterEstatusCom').val());

    // campos de firmas
    formDataDownloadExcel.append('filterFirmasVendedor', $('#filterFirmasVendedor').val());
    formDataDownloadExcel.append('filterFirmasGerentes', $('#filterFirmasGerentes').val());
    formDataDownloadExcel.append('filterFirmasDireccion', $('#filterFirmasDireccion').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "generateReportPlV2",
        async: true,
        type: "POST",
        data: formDataDownloadExcel,
        processData: false,
        contentType: false,
        success: function (data) {
            let response = data.response
            if (response == undefined) {

                // Creamos un objeto para agrupar la data por usuario
                const userData = {};
                data.forEach((row) => {
                    const vendedor = row[0];
                    if (!userData[vendedor]) {
                        userData[vendedor] = [];
                    }
                    userData[vendedor].push(row);
                });

                // Creamos un nuevo libro de trabajo
                const workbook = XLSX.utils.book_new();

                // Definimos las cabeceras por defecto
                const headers = [
                    [
                        'Vendedor', 
                        'N° Pedido', 
                        'N° OP', 
                        'Trabajo', 
                        'Cliente', 
                        'Importe pedido', 
                        'Importe Comisión', 
                        'Porcentaje Comisión', 
                        'Venta Perdida', 
                        'Notas de crédito', 
                        'Importe Pedido Final', 
                        'Fec pedido', 
                        '% Ret.', 
                        'Retención', 
                        'A cuenta de', 
                        'Saldo', 
                        'Fecha pago pedido', 
                        'N° Factura', 
                        'Mov Factura', 
                        '$ factura', 
                        '$ cobro', 
                        '$ aplicación', 
                        'Condicion', 
                        'Vencimiento', 
                        'Dias Venc', 
                        'Fecha factura'
                    ]
                ];

                // headers para las incidencias
                const headersIncidencias = [
                    // ['Incidencia', 'Monto', 'Quincena', 'Estado', 'vendedor', 'Fecha de creación']
                    ['Vendedor', 'Fecha de creación', 'Quincena', 'Incidencia', 'Estado', 'Monto']
                ];

                // headers para los acuenta de
                const headersAcuenta = [
                    ['Vendedor', 'Monto', 'Usuario que registro', 'Pedido', 'Estado', 'Fecha de creación', 'Fecha de pago']
                ];

                // Estilo de negritas para las cabeceras
                const headerStyle = {
                    font: { bold: true },
                    fill: {
                        fgColor: { rgb: "92CDDC" } // Color de fondo amarillo
                    },
                    border: {
                        bottom: { style: "thin", color: { rgb: "000000" } },
                    }
                };

                // Estilo de borde inferior para las filas con contenido
                const contentStyle = {
                    border: {
                        bottom: { style: "thin", color: { rgb: "000000" } }
                    }
                };

                // Estilo de fondo gris claro para las columnas después de "N° Factura"
                const grayBackgroundStyle = {
                    fill: {
                        fgColor: { rgb: "D3D3D3" } // Color de fondo gris claro
                    },
                    border: {
                        bottom: { style: "thin", color: { rgb: "000000" } }
                    }
                };

                // Estilo de numero de contabilidad
                const currencyStyle = {
                    numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)', // Formato de contabilidad
                    border: {
                        bottom: { style: "thin", color: { rgb: "000000" } }
                    }
                };

                // Estilos de stripe para las filas
                const stripeStyle1 = {
                    fill: {
                        fgColor: { rgb: "FFFFFF" } // Color de fondo blanco
                    }
                };

                const stripeStyle2 = {
                    fill: {
                        fgColor: { rgb: "F2F2F2" } // Color de fondo gris claro
                    }
                };

                const uniquePedidos = new Set();

                Object.keys(userData).forEach((vendedor) => {

                    const worksheet = XLSX.utils.aoa_to_sheet(headers);
                    // userData[vendedor][0][userData[vendedor][0].length - 1]

                    /******************* Sección de comisiones **************************** */

                    // Aplicar estilo de negritas a las cabeceras
                    headers[0].forEach((header, index) => {
                        const cellAddress = XLSX.utils.encode_cell({ c: index, r: 0 });
                        if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: header };

                        // validar si la celda tiene estilo
                        if (!worksheet[cellAddress].s) worksheet[cellAddress].s
                        worksheet[cellAddress].s = headerStyle;
                    });

                    let indexPerPage = 2; // Inicializa indexPerPage

                    // Datos de comisiones

                    userData[vendedor].forEach((row, rowIndex) => {
                        if (row[row.length - 1] == 1) {
                            const pedido = row[1];
                            if (uniquePedidos.has(pedido)) {
                                // Si el pedido ya existe, establece las columnas 5 y 6 a 0
                                row[5] = 0;
                                row[6] = 0;
                                row[8] = 0;
                                row[9] = 0;
                                row[10] = 0;
                                row[13] = 0;
                                row[14] = 0;
                                row[15] = 0;
                            } else {
                                // Si el pedido no existe, agrégalo al conjunto
                                uniquePedidos.add(pedido);
                            }
    
                            const rowArray = Object.values(row).map((value, index) => {
                                // Define los índices de las columnas que deben ser números
                                const numericColumns = [5, 6, 7, 8, 9, 10, 13, 14, 15, 19, 20, 21];
                                return numericColumns.includes(index) ? parseFloat(value) : value;
                            });
    
                            // Ajustamos el porcentaje con 2 decimales
                            rowArray[7] = parseFloat(rowArray[7].toFixed(2));

                            // Ajustamos la fecha de pago si es nula no se agrega
                            if (rowArray[16] === null || rowArray[16] === '' || rowArray[16] === undefined) {
                                rowArray[16] = '';
                            } else {
                                rowArray[16] = String(rowArray[16]);
                            }
    
                            rowArray.pop();

                            // Agregar la fila a la hoja de cálculo
                            XLSX.utils.sheet_add_aoa(worksheet, [rowArray], { origin: `A${indexPerPage}` });

                            // Aplicar estilo de borde inferior a las celdas de la fila
                            rowArray.forEach((_, colIndex) => {
                                const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                                if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: rowArray[colIndex] };
    
                                if (!worksheet[cellAddress].s) worksheet[cellAddress].s
                                worksheet[cellAddress].s = contentStyle;
    
                                // Aplicar estilo de fondo gris claro a las columnas después de "N° Factura"
                                if (colIndex > 9) { // Índice de "N° Factura" es 9
                                    worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...grayBackgroundStyle };
                                }
    
                                // Aplicar estilo de moneda de contabilidad a las columnas de importe
                                if ([5, 6, 8, 9, 10, 13, 14, 15, 19, 20, 21].includes(colIndex)) { // Índices de las columnas de importe
                                    worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...currencyStyle };
                                }
    
                                // Aplicar estilo de stripe a las filas con pedido único
                                worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...(rowIndex % 2 === 0 ? stripeStyle1 : stripeStyle2) };
                            });
                            indexPerPage++; // Incrementa el índice para la siguiente fila
                        }
                    });

                    // Calcular los totales para las columnas 5 y 6
                    const totalRow = [
                        { t: 's', v: 'Total', s: { font: { bold: true } } },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 'n', f: `SUM(F2:F${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(G2:G${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 's', v: '' },
                        { t: 'n', f: `SUM(I2:I${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(J2:J${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(K2:K${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 'n', f: `SUM(N2:N${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(O2:O${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(P2:P${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 'n', f: `SUM(S2:S${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(T2:T${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 'n', f: `SUM(U2:U${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                    ];

                    // Total fila de saldo
                    let totalSaldo = indexPerPage;

                    // Agregar la fila de totales a la hoja de cálculo
                    XLSX.utils.sheet_add_aoa(worksheet, [totalRow], { origin: `A${indexPerPage+1}` });

                    // Agregar header "exclusivos"
                    indexPerPage += 2;
                    indexPerPage += 1;

                    /******************* Fin Sección de comisiones **************************** */

                    /******************* Sección de incidencias no pagadas **************************** */

                    let inicioFilaInc = indexPerPage;

                    // Definir la celda con estilo
                    const cell2 = { 
                        v: 'Incidencias', 
                        s: {
                            font: { bold: true },
                            fill: {
                                fgColor: { rgb: "C4D79B" } // Color de fondo amarillo
                            },
                            border: {
                                bottom: { style: "thin", color: { rgb: "000000" } },
                            },
                        } 
                    };

                    // Agregar la celda a la hoja de cálculo
                    XLSX.utils.sheet_add_aoa(worksheet, [[cell2]], { origin: `A${indexPerPage}` });

                    // Combinar la celda con las tres celdas a la derecha
                    if (!worksheet['!merges']) worksheet['!merges'] = [];
                    worksheet['!merges'].push({ s: { r: indexPerPage - 1, c: 0 }, e: { r: indexPerPage - 1, c: 3 } });

                    // Aplicar el borde inferior a todas las celdas combinadas
                    for (let colIndex = 0; colIndex <= 3; colIndex++) {
                        const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                        if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: '' };
                        worksheet[cellAddress].s = { ...worksheet[cellAddress].s, border: { bottom: { style: "thin", color: { rgb: "000000" } } } };
                    }

                    indexPerPage += 2; // Aumenta el índice para la siguiente fila

                    XLSX.utils.sheet_add_aoa(worksheet, headersIncidencias, { origin: `A${indexPerPage}` });
                    headersIncidencias[0].forEach((header, colIndex) => {
                        const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                        if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: header };
                        worksheet[cellAddress].s = {
                            font: { bold: true },
                            fill: {
                                fgColor: { rgb: "92CDDC" } // Color de fondo amarillo
                            },
                            border: {
                                bottom: { style: "thin", color: { rgb: "000000" } },
                            },
                            alignment: { horizontal: "center" }
                        };
                    });
                    indexPerPage += 1; // Aumenta el índice para la siguiente fila

                    // Datos de comisiones con "a cuenta de"
                    userData[vendedor].forEach((row, rowIndex) => {
                        if (row[row.length - 1] == 3) {
                            const rowArray = Object.values(row).map((value, index) => {
                                // Define los índices de las columnas que deben ser números
                                const numericColumns = [5]; // Ajusta estos índices según tu orden de columnas
                                return numericColumns.includes(index) ? parseFloat(value) : value;
                            });

                            rowArray.pop();

                            // Agregar la fila a la hoja de cálculo
                            XLSX.utils.sheet_add_aoa(worksheet, [rowArray], { origin: `A${indexPerPage}` });

                            // Aplicar estilo de borde inferior a las celdas de la fila
                            rowArray.forEach((_, colIndex) => {
                                const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                                if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: rowArray[colIndex] };
    
                                if (!worksheet[cellAddress].s) worksheet[cellAddress].s
                                worksheet[cellAddress].s = contentStyle;
    
                                // Aplicar estilo de fondo gris claro a las columnas después de "N° Factura"
                                if (colIndex > 9) { // Índice de "N° Factura" es 9
                                    worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...grayBackgroundStyle };
                                }
    
                                // Aplicar estilo de moneda de contabilidad a las columnas de importe
                                if ([1].includes(colIndex)) { // Índices de las columnas de importe
                                    worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...currencyStyle };
                                }
    
                                // Aplicar estilo de stripe a las filas con pedido único
                                worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...(rowIndex % 2 === 0 ? stripeStyle1 : stripeStyle2) };
                            });
                            indexPerPage++; // Incrementa el índice para la siguiente fila
                        }
                    });

                    let totalIncidencias = indexPerPage - 1;

                    // Calcular los totales para las columnas 5 y 6
                    const totalRow3 = [
                        { t: 's', v: 'Total', s: { font: { bold: true } } },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 'n', f: `SUM(F${inicioFilaInc}:F${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                    ];

                    // Agregar la fila de totales a la hoja de cálculo
                    XLSX.utils.sheet_add_aoa(worksheet, [totalRow3], { origin: `A${indexPerPage}` });

                    // Agregar header "exclusivos"
                    indexPerPage += 2;
                    indexPerPage += 1;

                    /******************* Fin Sección de incidencias no pagadas **************************** */

                    /******************* Sección de anticipos no pagadas **************************** */

                    let inicioFilaAnt = indexPerPage;

                    // Definir la celda con estilo
                    const cell3 = { 
                        v: 'A cuentas de', 
                        s: {
                            font: { bold: true },
                            fill: {
                                fgColor: { rgb: "C4D79B" } // Color de fondo amarillo
                            },
                            border: {
                                bottom: { style: "thin", color: { rgb: "000000" } },
                            },
                        } 
                    };

                    // Agregar la celda a la hoja de cálculo
                    XLSX.utils.sheet_add_aoa(worksheet, [[cell3]], { origin: `A${indexPerPage}` });

                    // Combinar la celda con las tres celdas a la derecha
                    if (!worksheet['!merges']) worksheet['!merges'] = [];
                    worksheet['!merges'].push({ s: { r: indexPerPage - 1, c: 0 }, e: { r: indexPerPage - 1, c: 3 } });

                    // Aplicar el borde inferior a todas las celdas combinadas
                    for (let colIndex = 0; colIndex <= 3; colIndex++) {
                        const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                        if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: '' };
                        worksheet[cellAddress].s = { ...worksheet[cellAddress].s, border: { bottom: { style: "thin", color: { rgb: "000000" } } } };
                    }

                    indexPerPage += 2; // Aumenta el índice para la siguiente fila

                    XLSX.utils.sheet_add_aoa(worksheet, headersAcuenta, { origin: `A${indexPerPage}` });
                    headersAcuenta[0].forEach((header, colIndex) => {
                        const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                        if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: header };
                        worksheet[cellAddress].s = {
                            font: { bold: true },
                            fill: {
                                fgColor: { rgb: "92CDDC" } // Color de fondo amarillo
                            },
                            border: {
                                bottom: { style: "thin", color: { rgb: "000000" } },
                            },
                            alignment: { horizontal: "center" }
                        };
                    });
                    indexPerPage += 1; // Aumenta el índice para la siguiente fila

                    // Datos de comisiones con "a cuenta de"
                    userData[vendedor].forEach((row, rowIndex) => {
                        if (row[row.length - 1] == 4) {
                            const rowArray = Object.values(row).map((value, index) => {
                                // Define los índices de las columnas que deben ser números
                                const numericColumns = [1]; // Ajusta estos índices según tu orden de columnas
                                return numericColumns.includes(index) ? parseFloat(value) : value;
                            });

                            rowArray.pop();

                            // Agregar la fila a la hoja de cálculo
                            XLSX.utils.sheet_add_aoa(worksheet, [rowArray], { origin: `A${indexPerPage}` });

                            // Aplicar estilo de borde inferior a las celdas de la fila
                            rowArray.forEach((_, colIndex) => {
                                const cellAddress = XLSX.utils.encode_cell({ c: colIndex, r: indexPerPage - 1 });
                                if (!worksheet[cellAddress]) worksheet[cellAddress] = { t: 's', v: rowArray[colIndex] };
    
                                if (!worksheet[cellAddress].s) worksheet[cellAddress].s
                                worksheet[cellAddress].s = contentStyle;
    
                                // Aplicar estilo de fondo gris claro a las columnas después de "N° Factura"
                                if (colIndex > 9) { // Índice de "N° Factura" es 9
                                    worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...grayBackgroundStyle };
                                }
    
                                // Aplicar estilo de moneda de contabilidad a las columnas de importe
                                if ([1].includes(colIndex)) { // Índices de las columnas de importe
                                    worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...currencyStyle };
                                }
    
                                // Aplicar estilo de stripe a las filas con pedido único
                                worksheet[cellAddress].s = { ...worksheet[cellAddress].s, ...(rowIndex % 2 === 0 ? stripeStyle1 : stripeStyle2) };
                            });
                            indexPerPage++; // Incrementa el índice para la siguiente fila
                        }
                    });

                    let totalAnticipos = indexPerPage - 1;

                    // Calcular los totales para las columnas 5 y 6
                    const totalRow5 = [
                        { t: 's', v: 'Total', s: { font: { bold: true } } },
                        { t: 'n', f: `SUM(B${inicioFilaAnt}:B${indexPerPage - 1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                    ];

                    // Agregar la fila de totales a la hoja de cálculo
                    XLSX.utils.sheet_add_aoa(worksheet, [totalRow5], { origin: `A${indexPerPage}` });

                    /******************* Fin Sección de anticipos no pagadas **************************** */


                    // Agregar la hoja al libro
                    XLSX.utils.book_append_sheet(workbook, worksheet, vendedor);

                    // Agregar header "exclusivos"
                    indexPerPage += 2;
                    indexPerPage += 1;

                    // Agrgamos el total de la suma de la columna saldo y la clomuna monto
                    const totalRow4 = [
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: '' },
                        { t: 's', v: 'Total final (comisiones + incidencias + A cuenta de)', s: { font: { bold: true } } },
                        { t: 'n', f: `SUM(P${totalSaldo+1},F${totalIncidencias+1},B${totalAnticipos+1})`, s: { font: { bold: true }, numFmt: '_($* #,##0.00_);_($* (#,##0.00);_($* "-"??_);_(@_)' } },
                    ];

                    // Agregar la fila de totales a la hoja de cálculo
                    XLSX.utils.sheet_add_aoa(worksheet, [totalRow4], { origin: `A${indexPerPage}` });
                });

                // Ancho de las columnas
                Object.keys(workbook["Sheets"]).forEach((sheetName) => {
                    workbook["Sheets"][sheetName]["!cols"] = [
                        { wpx: 70 },
                        { wpx: 70 },
                        { wpx: 390 },
                        { wpx: 390 },
                        { wpx: 210 },
                        { wpx: 110 },
                        { wpx: 110 },
                        { wpx: 110 },
                        { wpx: 110 },
                        { wpx: 110 },
                        { wpx: 110 },
                        { wpx: 142 },
                        { wpx: 142 },
                        { wpx: 142 },
                        { wpx: 142 },
                        { wpx: 142 },
                        { wpx: 142 },
                        { wpx: 142 },
                        { wpx: 132 },
                        { wpx: 142 },
                        { wpx: 132 },
                        { wpx: 132 },
                    ];
                });

                // Escribimos el libro de trabajo en un archivo
                XLSX.writeFile(workbook, nombreReporte+'.xlsx');
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

            Msg.fire({ title: "Ocurrió un error, al obtener la información" });
        }
    })
}

/**
 * Función para abrir el modal para agregar un nuevo anticipo
 * 
 * @param {*} importeComisionMaximo
 * @param {*} idPedidoIntellisis
 * @param {*} Op
 * @param {*} pedido
 * @param {*} saldoFinal
 * @param {*} idComisionCandy
 * @param {*} anticiposNoPagados
 * 
 * @param {*} cantidadGeneralFacturas
 * @param {*} cantidadRestadaRetencion
 * @param {*} anticiposNoPagados
 * @param {*} porcRet
 * @param {*} restanteCant
 * @param {*} puedeAgregarAnticipos
 * @return void
 */
function addNewAnticipo(importeComisionMaximo, idPedidoIntellisis, Op, pedido, saldoFinal, idComisionCandy, anticiposNoPagados, cantidadGeneralFacturas, cantidadRestadaRetencion, porcRet, restanteCant, puedeAgregarAnticipos) {
    loaderON();

    $('#cantGeneral').html('')
    $('#cantRetenida').html('')
    $('#porcRet').html('')

    // Limpiamos los valores del formulario antes de abrir el modal
    clearInputsAnticipos()

    // Obtenemos el total de anticipos
    getTotalAnticipos(idComisionCandy)

    // Mostramos el numero del pedido en el titulo del modal 
    $('#tituloModalAnticipo').html(`Pedido: ${pedido}`)

    $('#saldoFinalLbl').append(new Intl.NumberFormat('es-MX').format(Number(importeComisionMaximo).toFixed(2)))
    $('#saldoFinal').append(importeComisionMaximo)
    
    $('#cantGeneral').append(new Intl.NumberFormat('es-MX').format(Number(cantidadGeneralFacturas).toFixed(2)))
    $('#cantRetenida').append(new Intl.NumberFormat('es-MX').format(Number(cantidadRestadaRetencion).toFixed(2)))
    $('#porcRet').append(porcRet)

    // Abrimos el modal para que podamos realizar la creacion del registro en candy
    $("#mod_Anticipos").modal({
        backdrop: 'static',
        show: true
    })

    // Prevenimos que se accione el formulario con cualquier acción de submit
    $("#formAddNewAnticipo").on('submit', function (event) {
        event.preventDefault()
    })

    // Validamos si tiene anticipos no pagados, en caso de que si ocultamos el boton de nuevo registro
    if (anticiposNoPagados == "1" || puedeAgregarAnticipos == "0") {
        $('#btnAddNewAnticipo').hide()
    } else {
        $('#btnAddNewAnticipo').show()
    }

    // Cargamos el listado de anticipos registrados
    tblListAnticiposComisiones(idComisionCandy)

    // Cargamos en los inputs la informacion para el alta de la comisión en candy
    $('#idPedidoIntellisisB').val(idPedidoIntellisis)
    $('#opB').val(Op)
    $('#pedidoB').val(pedido)

    // Validamos si existe el id de la comision en candy
    if (idComisionCandy != undefined) {
        $('#idComisionCandy').val(idComisionCandy)
    }
}

/**
 * Función para limpiar los campos del formulario
 * 
 * @return void
 */
function clearInputsAnticipos() {
    $('#idPedidoIntellisisB').val('')
    $('#opB').val('')
    $('#pedidoB').val('')
    $('#idComisionCandy').val('')
    $('#anticipo').val('')
    $('#motivoA').val('')
    $('#msg-errors-anticipo').html('')
    $('#msg-errors-motivoA').html('')
    $('#saldoFinalLbl').html('')
    $('#importeComisionLbl').html('')
    $("#totalGeneralAnticipos").html("")
    $('#importeComision').val('')
}

// Asignamos al input de cambios cuando se presione enter se guarde el reporte
// $('#anticipo').on('keydown', function (event) {
//     if (event.key == 'Enter') {
//         crearNuevoAnticipo();
//     }
// });

/**
 * Función para realizar el guardado del anticipo
 * 
 * @return void
 */
function crearNuevoAnticipo() {
    // Creamos el formulario que se enviara para registrar el anticipo
    formDataAddAnticipo = new FormData();
    formDataAddAnticipo.append("_token", CSRF_TOKEN);
    formDataAddAnticipo.append("_method", "POST");
    formDataAddAnticipo.append("idPedidoIntellisis", $('#idPedidoIntellisisB').val());
    formDataAddAnticipo.append("op", $('#opB').val());
    formDataAddAnticipo.append("pedido", $('#pedidoB').val());
    formDataAddAnticipo.append("idComisionCandy", $('#idComisionCandy').val());
    formDataAddAnticipo.append("anticipo", $('#anticipo').val());
    formDataAddAnticipo.append("motivoA", $('#motivoA').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "addNewAnticipo",
        async: true,
        type: "POST",
        data: formDataAddAnticipo,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos que no hayan errores en la validación
            if (data.errores > 0) {
                $('#msg-errors-anticipo').html('')
                $('#msg-errors-anticipo').append(data.error_anticipo)

                $('#msg-errors-motivoA').html('')
                $('#msg-errors-motivoA').append(data.error_motivoA)
                return;
            }

            // Validamos si se obtuvo una respuesta
            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se creo correctamente el anticipo
                Msg.fire('Se realizó correctamente el registro');

                // Limpiamos los mensajes de error
                $('#anticipo').val('')
                $('#msg-errors-anticipo').html('')

                $('#motivoA').val('')
                $('#msg-errors-motivoA').html('')

                // Refrescamos la tabla de los anticipos
                tblListAnticiposComisiones(data.idComLitoCandy)

                // Refrescamos el total de anticipos
                getTotalAnticipos(data.idComLitoCandy)

                // Ocultamos el boton de nuevo registro
                $('#btnAddNewAnticipo').hide()
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

            Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
        }
    })
}

/**
 * Función para obtener el total de anticipos y mostrarlo en la tabla
 * 
 * @param {*} idComisionCandy
 * @return void
 */
function getTotalAnticipos(idComisionCandy) {
    // Creamos el formulario que se enviara para registrar el anticipo
    formDataGetTotalAnticipos = new FormData();
    formDataGetTotalAnticipos.append("_token", CSRF_TOKEN);
    formDataGetTotalAnticipos.append("_method", "POST");
    formDataGetTotalAnticipos.append("idComisionCandy", (idComisionCandy != undefined ? idComisionCandy : null));

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getTotalAnticipos",
        async: true,
        type: "POST",
        data: formDataGetTotalAnticipos,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos si se obtuvo una respuesta
            if (data.response) {
                $('#totalGeneralAnticipos').html(new Intl.NumberFormat('es-MX').format(Number(data.total).toFixed(2)))
                $('#restanteCant').html('')
                $('#restanteCant').append(new Intl.NumberFormat('es-MX').format(Number(data.restanteCant).toFixed(2)))
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

            Msg.fire({ title: "Ocurrió un error, al obtener el registro" });
        }
    })
}

/**
 * Función para cerrar el modal de anticipos
 * 
 * @return void
 */
function closeModalAnticipos() {
    loaderOut();
    $("#mod_Anticipos").modal("hide")
    $("#totalGeneralAnticipos").html("")
    clearInputsAnticipos()

    // Finalmente refrescamos las tablas
    tblAdminPedidos.ajax.reload();
    tblAdminPedidosLiberados.ajax.reload();
    tblAdminPedidosListosLiberar.ajax.reload();
}

/**
 * Funcion para poder eliminar un anticipo que este relacionado a un pedido
 * 
 * @param {*} idAnticipo
 * @return void
 */
function eliminarAnticipo(idAnticipo) {
    // Preguntamos si quiere eliminar anticipo
    Swal.fire({
        title: "¿Quieres eliminar este anticipo?",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que eliminara el cambio seleccionado
            formDataDeleteAnticipo = new FormData();
            formDataDeleteAnticipo.append("_token", CSRF_TOKEN);
            formDataDeleteAnticipo.append("_method", "POST");
            formDataDeleteAnticipo.append("idAnticipo", idAnticipo);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "deleteAnticipo",
                async: true,
                type: "POST",
                data: formDataDeleteAnticipo,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se elimino correctamente el anticipo
                        Msg.fire('Se eliminó el registro');

                        // Refrescamos la tabla de los anticipos
                        tblListAnticiposComisiones(data.idComLitoCandy)

                        // Refrescamos el total de anticipos
                        getTotalAnticipos(data.idComLitoCandy)

                        // Validamos si existe el id de la comision en candy
                        if (data.NothasAnticiposNoPagados) {
                            $('#btnAddNewAnticipo').show()
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

                    Msg.fire({ title: "Ocurrió un error, al eliminar" });
                }
            })
        }
    });
}

/**
 * Función para marcar la comisión como pagada
 * 
 * @param {*} idComisionCandy
 * @param {*} check,
 * @param {*} type // Tipo 1 comisiones, 2 anticipos
 * @return void
 */
function markComisionPagada(idComisionCandy, check, type) {

    if (type == 1) {
        Swal.fire({
            title: (check.checked == false ? "¿Quieres marcar como no pagada esta comisión?" : "¿Quieres marcar como pagada esta comisión?"),
            html: (check.checked == false ) ? '<div class="sr-only"><input type="date" id="swal-input-date" class="swal2-input"></div>' : '<label for="swal-input-date">Selecciona la fecha en la que se realizó el pago:</label><input type="date" id="swal-input-date" class="swal2-input">',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: "Marcar",
            preConfirm: () => {
                const input = Swal.getPopup().querySelector('#swal-input-date');
                if (check.checked != false) {
                    if (!input.value) {
                        Swal.showValidationMessage(`Por favor ingresa una fecha`);
                        return false;
                    }
                }
    
                return input.value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Creamos el formulario para marcar como pagada la comision
                formDataMarkComisionPagada = new FormData();
                formDataMarkComisionPagada.append("_token", CSRF_TOKEN);
                formDataMarkComisionPagada.append("_method", "POST");
                formDataMarkComisionPagada.append("idComisionCandy", idComisionCandy);
                formDataMarkComisionPagada.append("value", check.checked);
                formDataMarkComisionPagada.append("fecha", result.value);
                formDataMarkComisionPagada.append("tipo", type);
    
                // Ejecutamos la acción de ajax
                $.ajax({
                    url: serve + "marcarComisionPagada",
                    async: true,
                    type: "POST",
                    data: formDataMarkComisionPagada,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.response) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });
    
                            // Mostramos el mensaje de que se marco la comision
                            Msg.fire((check.checked == true ? 'Se marco como pagada la comisión' : 'Se desmarco la comisión'));
    
                            // Refrescamos la tabla de pedidos liberados
                            tblAdminPedidosLiberadosTbl()
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
    
                        Msg.fire({ title: "Ocurrió un error, al eliminar" });
                    }
                })
            } else {
                // Refrescamos la tabla de pedidos liberados
                tblAdminPedidosLiberados.ajax.reload();
            }
        });
    } else {
        Swal.fire({
            title: (check.checked == false ? "¿Quieres marcar como no pagado este A cuenta de?" : "¿Quieres marcar como pagado este A cuenta de?"),
            html: (check.checked == false ) ? '<div class="sr-only"><input type="date" id="swal-input-date" class="swal2-input"></div>' : '<label for="swal-input-date">Selecciona la fecha en la que se realizó el pago:</label><input type="date" id="swal-input-date" class="swal2-input">',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: "Marcar",
            preConfirm: () => {
                const input = Swal.getPopup().querySelector('#swal-input-date');
                if (check.checked != false) {
                    if (!input.value) {
                        Swal.showValidationMessage(`Por favor ingresa una fecha`);
                        return false;
                    }
                }
    
                return input.value;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Creamos el formulario para marcar como pagada la comision
                formDataMarkAnticipoPagado = new FormData();
                formDataMarkAnticipoPagado.append("_token", CSRF_TOKEN);
                formDataMarkAnticipoPagado.append("_method", "POST");
                formDataMarkAnticipoPagado.append("idComisionCandy", idComisionCandy);
                formDataMarkAnticipoPagado.append("value", check.checked);
                formDataMarkAnticipoPagado.append("fecha", result.value);
                formDataMarkAnticipoPagado.append("tipo", type);
    
                // Ejecutamos la acción de ajax
                $.ajax({
                    url: serve + "marcarComisionPagada",
                    async: true,
                    type: "POST",
                    data: formDataMarkAnticipoPagado,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.response) {
                            const Msg = Swal.mixin({
                                toast: true,
                                position: "center",
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 3000
                            });
    
                            // Mostramos el mensaje de que se marco la comision
                            Msg.fire((check.checked == true ? 'Se marco como pagada la comisión' : 'Se desmarco la comisión'));
    
                            // Refrescamos la tabla de pedidos liberados
                            tblAdminPedidosLiberadosTbl()
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
    
                        Msg.fire({ title: "Ocurrió un error, al eliminar" });
                    }
                })
            } else {
                // Refrescamos la tabla de pedidos liberados
                tblAdminPedidosLiberados.ajax.reload();
            }
        });
    }
    
    // Refrescamos la tabla de pedidos liberados
    tblAdminPedidosLiberados.ajax.reload();
}

/**
 * Función para realizar el cambio de estatus de un anticipo, y cambia a pagado o falta por pagar
 * 
 * @param {*} idAnticipo
 * @param {*} check
 * @return mixed
 */
function changeStatus(idAnticipo, check) {
    Swal.fire({
        title: (check.checked == false ? "¿Quieres marcar como no pagada este 'A cuenta de'?" : "¿Quieres marcar como pagada este 'A cuenta de'?"),
        html: (check.checked == false ) ? '<div class="sr-only"><input type="date" id="swal-input-date" class="swal2-input"></div>' : '<label for="swal-input-date">Selecciona la fecha en la que se realizó el pago:</label><input type="date" id="swal-input-date" class="swal2-input">',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Marcar",
        preConfirm: () => {
            const input = Swal.getPopup().querySelector('#swal-input-date');
            if (check.checked != false) {
                if (!input.value) {
                    Swal.showValidationMessage(`Por favor ingresa una fecha`);
                    return false;
                }
            }

            return input.value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario para marcar como pagada la comision
            formDataChangeStatus = new FormData();
            formDataChangeStatus.append("_token", CSRF_TOKEN);
            formDataChangeStatus.append("_method", "POST");
            formDataChangeStatus.append("idAnticipo", idAnticipo);
            formDataChangeStatus.append("value", check.checked);
            formDataChangeStatus.append("fechaPago", result.value);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "changeStatusAnticipo",
                async: true,
                type: "POST",
                data: formDataChangeStatus,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se marco la comision
                        Msg.fire((check.checked == true ? 'Se marco como pagado el anticipo' : 'Se marco como no pagado el anticipo'));

                        // Refrescamos la tabla de los anticipos
                        tblListAnticiposComisiones(data.idComLitoCandy)
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

                    Msg.fire({ title: "Ocurrió un error, al realizar la acción" });
                }
            })
        } else {
            // Refrescamos la tabla de los anticipos
            tblListAnticiposComisiones(data.idComLitoCandy)
        }
    });

}

/**
 * Función para obtener los estatus que estan relacionados a cada tabla tanto estatus com como estatus de intellisis
 * 
 * @return mixed
 */
function getListFilterAdminPedidos(tbl)
{
    // Creamos el formulario que obtendra el listado para el select de estatus de cada tabla
    formDataGetListStatus = new FormData();
    formDataGetListStatus.append("_token", CSRF_TOKEN);
    formDataGetListStatus.append("_method", "POST");
    formDataGetListStatus.append('tblActual', $('#tblActual').val())
    formDataGetListStatus.append('typeTbl', tbl)

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getListEstatus",
        async: true,
        type: "POST",
        data: formDataGetListStatus,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                $('#compFilterEstatus').html("")
                $('#compFilterEstatus').append(data.select)
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/**
 * Función para registrar las incidencias
 * 
 * @return void
 */
function openModalRegistroIncidencias() {
    loaderON();

    // Abrimos un sweet alert para preguntando el vendedor
    Swal.fire({
        title: '¿Selecciona un vendedor antes de comenzar?',
        html: `
            <select id="vendedorSelect" class="swal2-select swal2-select-custom">
                ${document.querySelector('#filterVendedorIncidencia').innerHTML}
            </select>
        `,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const selectedVendedor = Swal.getPopup().querySelector('#vendedorSelect').value;
            if (selectedVendedor === '') {
                Swal.showValidationMessage('Debes seleccionar un vendedor');
            }
            return selectedVendedor;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const selectedVendedorName = Swal.getPopup().querySelector('#vendedorSelect option:checked').textContent;
            // Asignamos el vendedor seleccionado
            $('#vendedorSeleccionado').val(result.value)
            $('#vendedorSeleccionadoNombre').val(selectedVendedorName)

            // Abrimos el modal para registrar las incidencias
            $("#mod_NuevaIncidencia").modal({
                backdrop: 'static',
                show: true
            })

            // Obtenemos la fecha en la que se realiza el registro de la incidencia
            getDateStarIncidencia()

            // Actualizamos el titulo del modal
            $('#titleIncidencia').html('Nueva incidencia')

            // Actualizamos el titulo del boton y el evento
            $('#btnSaveIncidenciaFinal').html('Guardar')
            $('#btnSaveIncidenciaFinal').attr('onclick', 'saveIncidenciaFinal()')

            $('#btnSaveIncidenciaFinalCancelar').removeClass('d-block')
            $('#btnSaveIncidenciaFinalCancelar').addClass('d-none')

            // Llenamos el select con los tipos de incidencias
            getListIncidencias()

            // Obtenemos la tabla de incidencias para el vendedor seleccionado
            tblIncidenciasVendedorTbl()

            // Limpiamos los campos del formulario
            clearInputsIncidencias()

            loaderOut();
        }
    });

    loaderOut();
}

/**
 * Función para obtener la fecha en la que se realiza el registro de la incidencia
 * 
 * @return void
 */
function getDateStarIncidencia() {
    $('#vendedorNombre').html($('#vendedorSeleccionadoNombre').val())
}

/**
 * Función para obtener la fecha de la quincena de acuerdo con la fecha pasadas
 * 
 * @param {*} date
 * @return void
 */
function getQuincenaByDate(date = null) {
    // Creamos el formulario que obtendra la fecha en la que se realiza el registro de la incidencia
    formDataGetDateIncidencia = new FormData();
    formDataGetDateIncidencia.append("_token", CSRF_TOKEN);
    formDataGetDateIncidencia.append("_method", "POST");
    formDataGetDateIncidencia.append("fecha", (date != null ? date : $('#periodo').val()));

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getQuincenaByDate",
        async: true,
        type: "POST",
        data: formDataGetDateIncidencia,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                $('#fechaTexto').html(data.fecha)
                $('#numQuincena').html(data.numQuincena)
            } else {
                $('#fechaTexto').html('')
                $('#numQuincena').html('')
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

            Msg.fire({ title: "Ocurrió un error, al obtener la fecha" });
        }
    })
}

/**
 * Función para llenar el select con los tipos de incidencias
 * 
 * @return void
 */
function getListIncidencias() {
    // Creamos el formulario que obtendra el listado para el select de estatus de cada tabla
    formDataGetListIncidencias = new FormData();
    formDataGetListIncidencias.append("_token", CSRF_TOKEN);
    formDataGetListIncidencias.append("_method", "POST");

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getSelectIncidencias",
        async: true,
        type: "POST",
        data: formDataGetListIncidencias,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                $('#incidenciaForm').html("")
                $('#incidenciaForm').append(data.select)
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/**
 * Función para seleccionar el pedido y mostrarlo en el input
 * 
 * @param {*} pedido
 * @param {*} pedidoText
 * @return void
 */
function selectPedido(pedido, pedidoText) {
    $('#pedidoId').val(pedido)
    $('#pedidoSelect').val(pedidoText)

    // limpiamos el div
    $('#pedidosDiv').html("")
}

/**
 * Función para realizar el guardado de la incidencia
 * 
 * @return void
 */
function changeSelectIncidencia(select) {

    // Limpiamos los mensajes de error
    $('#msg-incidenciaInput').html('')

    if (select.value == 'otro') {
        // Creamos el input para agregar la incidencia
        $('#otraIncidencia').append('<br><label for="incidencia" class="text-info">Escribe otro tipo de incidencia:</label><div class="d-flex"><input type="text" class="form-control h-full mr-2" id="incidenciaInput" name="incidenciaInput" required><button type="button" class="btn btn-primary" onclick="saveIncidencia()">+</button></div>')

        // Agregamos el boton para guardar la incidencia
        $('#btnSaveIncidencia').append('')
    } else {
        // Eliminamos el input para agregar la incidencia
        $('#otraIncidencia').html('')

        // Eliminamos el boton para guardar la incidencia
        $('#btnSaveIncidencia').html('')
    }
}

/**
 * Función para limpiar los campos del formulario de incidencias
 * 
 * @return void
 */
function clearInputsIncidencias() {
    $('incidenciaForm').html('')

    $('#incidenciaInput').val('')
    $('#msg-incidenciaInput').html('')
    $('#msg-monto-incidencia').html('')
    $('#msg-quincena-incidencia').html('')
    $('#selectQuincena').val('')
    $('#monto').val('')

    $('#monto').prop('readonly', false)

    // Refrescamos el select de incidencias
    getListIncidencias()

    $('#btnSaveIncidenciaFinalCancelar').removeClass('d-block')
    $('#btnSaveIncidenciaFinalCancelar').addClass('d-none')

    $('#fechaTexto').html('')
    $('#numQuincena').html('')

    $('#btnSaveIncidenciaFinal').html('Guardar')

    document.getElementsByClassName('eliminarIncidenciasGnr').forEach(function (element) {
        element.style.display = 'block'
    })

    $('#titleIncidencia').html('Nueva incidencia')
}

/**
 * Función para guardar la incidencia
 * 
 * @return void
 */
function saveIncidencia() {
    // Creamos el formulario que se enviara para registrar la incidencia
    formDataAddIncidencia = new FormData();
    formDataAddIncidencia.append("_token", CSRF_TOKEN);
    formDataAddIncidencia.append("_method", "POST");
    formDataAddIncidencia.append("incidenciaInput", $('#incidenciaInput').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "saveIncidencia",
        async: true,
        type: "POST",
        data: formDataAddIncidencia,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos si hay mensajes de error
            if (data.errores > 0) {
                $('#msg-incidenciaInput').html('')
                $('#msg-incidenciaInput').append(data.error_incidenciaInput)
                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se marco la comision
                Msg.fire('Se guardó la incidencia');

                // Refrescamos el select de incidencias
                getListIncidencias()

                // Reiniciamos el valor del select
                $('#incidenciaInput').val('')
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

            Msg.fire({ title: "Ocurrió un error, al guardar la incidencia" });
        }
    })
}

/**
 * Función para realizar el guardado de la incidencia final
 * 
 * @return void
 */
function saveIncidenciaFinal() {

    // Creamos el formulario que se enviara para registrar la incidencia
    formDataAddIncidenciaFinal = new FormData();
    formDataAddIncidenciaFinal.append("_token", CSRF_TOKEN);
    formDataAddIncidenciaFinal.append("_method", "POST");
    formDataAddIncidenciaFinal.append("monto", $('#monto').val());
    formDataAddIncidenciaFinal.append("motivo", $('#incidenciaSelect').val());
    formDataAddIncidenciaFinal.append("quincena", $('#selectQuincena').val());
    formDataAddIncidenciaFinal.append("vendedor", $('#vendedorSeleccionado').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "saveIncidenciaFinal",
        async: true,
        type: "POST",
        data: formDataAddIncidenciaFinal,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos si hay mensajes de error
            if (data.errores > 0) {
                $('#msg-incidenciaInput').html('')
                $('#msg-incidenciaInput').append(data.error_motivo)
                $('#msg-monto-incidencia').html('')
                $('#msg-monto-incidencia').append(data.error_monto)
                $('#msg-quincena-incidencia').html('')
                $('#msg-quincena-incidencia').append(data.error_quincena)
                $('#msg-vendedor-incidencia').html('')
                $('#msg-vendedor-incidencia').append(data.error_vendedorInc)

                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se marco la comision
                Msg.fire('Se guardó la incidencia');

                // Refrescamos la tabla de los pedidos
                tblIncidenciasTbl()

                // Actualizamos la tabla de incidencias para el vendedor seleccionado
                tblIncidenciasVendedorTbl()

                // Limpiamos los campos del formulario
                clearInputsIncidencias()
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

            Msg.fire({ title: "Ocurrió un error, al guardar la incidencia" });
        }
    })
}

/**
 * Función para obtener los vendedores y mostrarlos en el select
 * 
 * @return void
 */
function getSelectedVendedores() {
    let selectedVendedores = [];
    $('#seleccionados .vendedor').each(function() {
        selectedVendedores.push($(this).data('value'));
    });
    return selectedVendedores;
}

/**
 * Función para cerrar el modal de incidencias
 * 
 * @return void
 */
function closeModalIncidencias() {
    tblIncidenciasTbl()
    loaderOut();
    $("#mod_NuevaIncidencia").modal("hide")
    clearInputsIncidencias()
}

/**
 * Función con sweet alert para preguntar si se quiere eliminar la incidencia
 * 
 * @param {*} idIncidencia
 * @param {*} tbl
 * @return void
 */
function eliminarIncidencia(idIncidencia, tbl = 1) {
    // Preguntamos si quiere eliminar anticipo
    Swal.fire({
        title: "¿Quieres eliminar esta incidencia?",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que eliminara la incidencia seleccionada
            formDataDeleteIncidencia = new FormData();
            formDataDeleteIncidencia.append("_token", CSRF_TOKEN);
            formDataDeleteIncidencia.append("_method", "POST");
            formDataDeleteIncidencia.append("idIncidencia", idIncidencia);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "deleteIncidencia",
                async: true,
                type: "POST",
                data: formDataDeleteIncidencia,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se elimino correctamente el anticipo
                        Msg.fire('Se eliminó el registro');

                        if (tbl == 1) {
                            // Refrescamos la tabla de pedidos liberados
                            tblIncidenciasTbl()
                        } else {
                            tblIncidenciasVendedorTbl()
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

                    Msg.fire({ title: "Ocurrió un error, al eliminar" });
                }
            })
        }
    });
}

/**
 * Función para abrir el modal para editar la incidencia
 * 
 * @param {*} idIncidencia
 * @param {*} tbl
 * @param {*} idVendedor
 * @param {*} nombreVendedor
 * @return void
 */
function editarIncidencia(idIncidencia, tbl = 1, idVendedor = null, nombreVendedor = null) {

    if (idVendedor != null && nombreVendedor != null) {
        $('#vendedorSeleccionado').val(idVendedor)
        $('#vendedorSeleccionadoNombre').val(nombreVendedor)
        $('#vendedorNombre').html(nombreVendedor)

        tblIncidenciasVendedorTbl()
    }

    // Creamos el formulario que obtendra la informacion de la incidencia
    formDataGetIncidencia = new FormData();
    formDataGetIncidencia.append("_token", CSRF_TOKEN);
    formDataGetIncidencia.append("_method", "POST");
    formDataGetIncidencia.append("idIncidencia", idIncidencia);

    clearInputsIncidencias()

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getIncidenciaById",
        async: true,
        type: "POST",
        data: formDataGetIncidencia,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {

                if (tbl == 1) {
                    // Abrimos el modal para editar la incidencia
                    $("#mod_NuevaIncidencia").modal({
                        backdrop: 'static',
                        show: true
                    })

                    // Actualizamos vendedor seleccionado y actyalizamos 
                }

                // Actualizamos el titulo del modal
                $('#titleIncidencia').html('Editar incidencia')

                // Actualizamos el titulo del boton y el evento
                $('#btnSaveIncidenciaFinal').html('Actualizar')
                $('#btnSaveIncidenciaFinal').attr('onclick', 'updateIncidencia('+idIncidencia+')')

                $('#btnSaveIncidenciaFinalCancelar').addClass('d-block')
                $('#btnSaveIncidenciaFinalCancelar').removeClass('d-none')


                // Llenamos el select con los tipos de incidencias
                getListIncidencias()

                // Validamos hasta que se haya cargado por completo el select de incidencias
                setTimeout(() => {
                    // Llenamos los campos del formulario
                    $('#incidenciaSelect').val(data.incidencia.idMotivo)
                    $('#monto').val(data.incidencia.monto)

                    // validamos si se puede editar el monto
                    if (data.motivoRel != null) {
                        if (data.motivoRel.tipo == 1) {
                            $('#monto').prop('readonly', true)
                        } else {
                            $('#monto').prop('readonly', false)
                        }
                    }

                    $('#selectQuincena').val(data.incidencia.quincena)
                }, 2000)

                document.getElementsByClassName('eliminarIncidenciasGnr').forEach(function (element) {
                    element.style.display = 'none'
                })
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/**
 * Función para actualizar la incidencia
 * 
 * @param {*} idIncidencia
 * @return void
 */
function updateIncidencia(idIncidencia) {
    // Creamos el formulario que se enviara para registrar la incidencia
    formDataUpdateIncidencia = new FormData();
    formDataUpdateIncidencia.append("_token", CSRF_TOKEN);
    formDataUpdateIncidencia.append("_method", "POST");
    formDataUpdateIncidencia.append("idIncidencia", idIncidencia);
    formDataUpdateIncidencia.append("monto", $('#monto').val());
    formDataUpdateIncidencia.append("motivo", $('#incidenciaSelect').val());
    formDataUpdateIncidencia.append("quincena", $('#selectQuincena').val());

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "updateIncidencia",
        async: true,
        type: "POST",
        data: formDataUpdateIncidencia,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos si hay mensajes de error
            if (data.errores > 0) {
                $('#msg-incidenciaInput').html('')
                $('#msg-incidenciaInput').append(data.error_motivo)
                $('#msg-monto-incidencia').html('')
                $('#msg-monto-incidencia').append(data.error_monto)
                $('#msg-quincena-incidencia').html('')
                $('#msg-quincena-incidencia').append(data.error_quincena)
                $('#msg-frecuencia').html('')
                $('#msg-frecuencia').append(data.error_frecuencia)
                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se marco la comision
                Msg.fire('Se actualizó la incidencia');

                // Refrescamos la tabla de los pedidos
                tblIncidenciasVendedorTbl()

                // Limpiamos los campos del formulario
                clearInputsIncidencias()
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

            Msg.fire({ title: "Ocurrió un error, al guardar la incidencia" });
        }
    })
}

/**
 * Función para actualizar el estatus de la incidencia (pendiente)
 * 
 * @param {*} idIncidencia
 * @param {*} check
 * @param {*} tbl
 * @return void
 */
function changeStatusIncidencia(idIncidencia, check, tbl) {
    // Creamos el formulario para marcar como pagada la comision
    formDataChangeStatusIncidencia = new FormData();
    formDataChangeStatusIncidencia.append("_token", CSRF_TOKEN);
    formDataChangeStatusIncidencia.append("_method", "POST");
    formDataChangeStatusIncidencia.append("idIncidencia", idIncidencia);
    formDataChangeStatusIncidencia.append("value", check.checked);

    // Preguntamos si quiere eliminar anticipo
    Swal.fire({
        title: (check.checked == false ? "¿Quieres marcar como no pagada esta incidencia?" : "¿Quieres marcar como pagada esta incidencia?"),
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Marcar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "changeStatusIncidencia",
                async: true,
                type: "POST",
                data: formDataChangeStatusIncidencia,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se marco la comision
                        Msg.fire((check.checked == true ? 'Se marco como pagada la incidencia' : 'Se marco como no pagada la incidencia'));

                        if (tbl == 1) {
                            // Refrescamos la tabla de pedidos liberados
                            tblIncidenciasTbl()
                        } else {
                            tblIncidenciasVendedorTbl()
                        }

                    }
                }
            })
        } else {
            if (tbl == 1) {
                // Refrescamos la tabla de pedidos liberados
                tblIncidenciasTbl()
            } else {
                tblIncidenciasVendedorTbl()
            }
        }
    });
}

/**
 * Función para redirigir a la oporunidad de la incidencia con la op
 * 
 * @param {*} idPedido
 * @param {*} op
 * @return void
 */
function goToOportunity(idPedido, op) {
    // Validamos si la op no esta vacia y contiene una m al inicio
    if (op != '' && op != null && op != undefined) {
        if (op.includes('M')) {
            // declaramos la variable que tendra las multiples ops
            let ops = op.split('|');
            ops = ops[1].split(',');

            let values = {};
            ops.forEach((element, index) => {
                values[element] = element;
            });

            // abrimos el modal con sweet alert con el select 
            Swal.fire({
                title: "¿Selecciona la OP para poder verificar y redirigirte a la oportunidad?",
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: "Ir",
                input: 'select',
                inputOptions: values,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                customClass: 'swal_white',
                inputPlaceholder: 'Selecciona una opción',
                // estilos
                customClass: {
                    input: 'form-control'
                },
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve('Debes seleccionar una opción');
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    actionRedirectOportunity(idPedido, result.value)
                }
            });
        } else {
            actionRedirectOportunity(idPedido)
        }
    } else {
        actionRedirectOportunity(idPedido)
    }
}

/**
 * Función para realizar la redirección a la oportunidad
 * 
 * @param {*} idPedido
 * @param {*} op
 * @return void
 */
function actionRedirectOportunity(idPedido, op = '') {
    // Redirigimos a la pagina de la oportunidad
    formDataRedirectOp = new FormData();
    formDataRedirectOp.append("_token", CSRF_TOKEN);
    formDataRedirectOp.append("_method", "POST");
    formDataRedirectOp.append("idPedido", idPedido);
    formDataRedirectOp.append("op", op);
    
    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "redirectOp",
        async: true,
        type: "POST",
        data: formDataRedirectOp,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                window.open(data.url)
            } else {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire({ title: "No hay una oportunidad relacionada para visualizar" });
            }
        }
    });
}

/**
 * Función para abrir el modal que mostrara la ultima tripa pasada a produccion de la op con las oportunidades
 * 
 * @param {*} idPedido
 * @param {*} op
 * @return void
 */
function showTripaOpPedido(idPedido, op) {
    // Validamos si la op no esta vacia y contiene una m al inicio
    if (op != '' && op != null && op != undefined) {
        if (op.includes('M')) {
            // declaramos la variable que tendra las multiples ops
            let ops = op.split('|');
            ops = ops[1].split(',');

            let values = {};
            ops.forEach((element, index) => {
                values[element] = element;
            });

            // abrimos el modal con sweet alert con el select 
            Swal.fire({
                title: "¿Selecciona la OP para poder localizar una tripa?",
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonText: "Ir",
                input: 'select',
                inputOptions: values,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#999",
                customClass: 'swal_white',
                inputPlaceholder: 'Selecciona una opción',
                // estilos
                customClass: {
                    input: 'form-control'
                },
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) {
                            resolve();
                        } else {
                            resolve('Debes seleccionar una opción');
                        }
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    actionShowFileOpPedido(idPedido, result.value)
                }
            });
        } else {
            actionShowFileOpPedido(idPedido)
        }
    } else {
        actionShowFileOpPedido(idPedido)
    }
}

/**
 * Función para mostrar el archivo en el modal
 * 
 * @param {*} idPedido
 * @param {*} op
 * @return void
 */
function actionShowFileOpPedido(idPedido, op = '') {
    // Creamos el formulario que obtendra la informacion de la incidencia
    formDataGetTripaProduccion = new FormData();
    formDataGetTripaProduccion.append("_token", CSRF_TOKEN);
    formDataGetTripaProduccion.append("_method", "POST");
    formDataGetTripaProduccion.append("idPedido", idPedido);
    formDataGetTripaProduccion.append("op", op);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getTripaProduccion",
        async: true,
        type: "POST",
        data: formDataGetTripaProduccion,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                let extension = data.documento['Extension'];
                let byteCharacters = atob(data.documento['Documento']);
                let descDocumento = data.documento['descDocumento'];

                const visorPDF = document.getElementById("frameVisorDocumentos");

                // Abrimos el modal para editar la incidencia
                $("#mod_TripaProduccion").modal({
                    backdrop: 'static',
                    show: true
                })

                $(".tituloVisorDocumentos").text(descDocumento);

                var byteNumbers = new Array(byteCharacters.length);
                for (var i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                var blob = new Blob([byteArray], { type: extension });

                blob = window.URL.createObjectURL(blob);

                visorPDF.src = blob + "#toolbar=1&nosidebar=1";

                $('#btnCerrarVisor')[0].classList = 'col-md-4 offset-md-4';

                loading.style.visibility = "hidden";

                loaderModalOut();

            } else {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 3000
                });

                Msg.fire({ title: "No hay una tripa de producción para visualizar" });
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/*      Seleccion de vendedores       */

/**
 * Función para mostrar los div para agregar o quitar vendedores de seleccion
 * 
 * @param {*} check
 * @return void
 */
function showSelectorsVendedor(check) {
    let vendedoresInc = document.getElementById('vendedoresInc');

    if (check.checked) {
        // Validamos si el div contiene la clase d-none
        if (vendedoresInc.classList.contains('d-none')) {
            vendedoresInc.classList.remove('d-none');
        }

    } else {
        vendedoresInc.classList.add('d-none');
    }
}

/**
 * Función para mostrar el listado de vendedores relacionados a la incidencia
 * 
 * @param {*} idIncidencia
 * @return void
 */
function showVendedoresList(idIncidencia) {
    $('#listVendedoresContainer').html('')
    
    // Mostramos el modal para ver los vendedores
    $("#mod_VendedoresIncidencia").modal({
        backdrop: 'static',
        show: true
    })

    // Creamos el formulario que obtendra la informacion de la incidencia
    formDataGetVendedoresInc = new FormData();
    formDataGetVendedoresInc.append("_token", CSRF_TOKEN);
    formDataGetVendedoresInc.append("_method", "POST");
    formDataGetVendedoresInc.append("idIncidencia", idIncidencia);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getVendedoresIncList",
        async: true,
        type: "POST",
        data: formDataGetVendedoresInc,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                let list = JSON.parse(data.vendedores);

                list.forEach(function(vendedor) {
                    $('#listVendedoresContainer').append('<div class="text-band-list my-1">' + vendedor.nombreVendedor + '</div>');
                });

                loaderOut();
                loaderModalOut();
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/**
 * Función para abrir el modal para configurar las incidencias
 * 
 * @return void
 */
function configIncidencias() {
    // Mostramos el modal para ver los vendedores
    $("#mod_ConfigIncidencias").modal({
        backdrop: 'static',
        show: true
    })

    $('#btnCatInc').html('Registrar')
    $('#btnCatInc').attr('onclick', 'guardarCatIncidencia()')

    tblCatMotivosIncidenciasTbl()
}

// Intercambiamos las clases para mostrar los vendedores
$('#tipo').on('change', function() {
    // validamos si el valor seleccionado es 1
    if (this.value == 1) {
        $('#contentCatMotIncVendedor').removeClass('d-none')
        $('#catMotIncVendedor').val('')
        $('#montoCatMotAnt').val('')
    } else {
        $('#contentCatMotIncVendedor').addClass('d-none')
    }
})

// Generamos la accion para consultar el monto de la incidencia cada que se realice un cambio en el select de motivos de incidencias
function checkMontoMotInc(select) {
    // Creamos el formulario que obtendra la informacion de la incidencia
    formDataGetCatMotInc = new FormData();
    formDataGetCatMotInc.append("_token", CSRF_TOKEN);
    formDataGetCatMotInc.append("_method", "POST");
    formDataGetCatMotInc.append("idCatMotInc", select.value);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getCatMotIncById",
        async: true,
        type: "POST",
        data: formDataGetCatMotInc,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                if (data.incidencia.monto != null) {
                    $('#monto').prop('readonly', true)
                    $('#monto').val(data.incidencia.monto)
                } else {
                    $('#monto').prop('readonly', false)
                    $('#monto').val('')
                }

                // Verificamos si el motivo de la incidencia es de frecuencia 1
                if (data.incidencia.frecuencia == 2) {
                    // Al select de quincenas ocultamos los que en valores contengan cualquier numero impar
                    $('#selectQuincena option').each(function() {
                        if (/\b[13579]-/.test(this.text)) {
                            $(this).addClass('d-none')
                        } else {
                            $(this).removeClass('d-none')
                        }
                    })
                } else {
                    $('#selectQuincena option').each(function() {
                        $(this).removeClass('d-none')
                    })
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/**
 * Función para guardar el registro al catalogo de motivos de incidencias v2
 * 
 * @return void
 */
function guardarCatIncidencia() {
    // Creamos el formulario que se enviara para registrar la incidencia
    formDataCatMotivosIncidencias = new FormData();
    formDataCatMotivosIncidencias.append("_token", CSRF_TOKEN);
    formDataCatMotivosIncidencias.append("_method", "POST");
    formDataCatMotivosIncidencias.append("incidencia", $('#incidencia').val());
    formDataCatMotivosIncidencias.append("recurrencia", $('#recurrenciaVal').val());
    formDataCatMotivosIncidencias.append("tipo", $('#tipoVal').val());
    formDataCatMotivosIncidencias.append("frecuenciaCatInc", $('#frecuenciaCatIncVal').val());
    formDataCatMotivosIncidencias.append("montoCatMotAnt", Number($('#montoCatMotAnt').val()));

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "saveCatMotivosIncidencias",
        async: true,
        type: "POST",
        data: formDataCatMotivosIncidencias,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos si hay mensajes de error
            if (data.errores > 0) {
                $('#msg-errors-incidencia').html('')
                $('#msg-errors-incidencia').append(data.error_incidencia)
                $('#msg-errors-recurrencia').html('')
                $('#msg-errors-recurrencia').append(data.error_recurrencia)
                $('#msg-errors-tipo').html('')
                $('#msg-errors-tipo').append(data.error_tipo)
                $('#msg-errors-frecuenciaCatInc').html('')
                $('#msg-errors-frecuenciaCatInc').append(data.error_frecuenciaCatInc)
                $('#msg-errors-montoCatMotAnt').html('')
                $('#msg-errors-montoCatMotAnt').append(data.error_montoCatMotAnt)
                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se marco la comision
                Msg.fire('Se guardó el registro');

                // Refrescamos la tabla de los pedidos
                tblCatMotivosIncidenciasTbl()

                // Limpiamos los campos del formulario
                clearInputsConfigIncidencias()
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

            Msg.fire({ title: "Ocurrió un error, al guardar la incidencia" });
        }
    })
}

/**
 * Función para cerrar el modal de configuración de incidencias
 * 
 * @return void
 */
function closeModalConfigIncidencias() {
    loaderOut();
    $("#mod_ConfigIncidencias").modal("hide")
    clearInputsConfigIncidencias()
}

/**
 * Función para limpiar los campos del formulario de configuración de incidencias
 * 
 * @return void
 */
function clearInputsConfigIncidencias() {
    $('#incidencia').val('')
    $('#msg-errors-incidencia').html('')
    $('#recurrenciaVal').val('')
    $('#msg-errors-recurrencia').html('')

    $('#tipo').val('')
    $('#msg-errors-tipo').html('')
    
    $('#frecuenciaCatInc').val('')
    $('#msg-errors-frecuenciaCatInc').html('')
    
    $('#montoCatMotAnt').val('')
    $('#msg-errors-montoCatMotAnt').html('')
    
    $('#btnCatInc').html('Registrar')
    $('#btnCatInc').attr('onclick', 'guardarCatIncidencia()')
    $('#btnCatIncCancelar').addClass('d-none')
    $('#contentCatMotIncVendedor').addClass('d-none')

    document.getElementsByClassName('restoreBtnFrm').forEach(function(btn) {
        btn.classList.remove('btn-info');
        btn.classList.add('btn-secondary');
    });

    $('#frequencyDiv').addClass('d-none');
    $('#typeDiv').addClass('d-none');

    // Ocultamos los botones de eliminacion
    document.getElementsByClassName('deleteAction').forEach(function(btn) {
        btn.classList.remove('d-none');
    });

    editandoMotivo = false;
}

/**
 * Función para editar el registro al catalogo de motivos de incidencias v2
 * 
 * @param {*} idCatMotInc
 * @return void
 */
function editarMotivoIncidencia(idCatMotInc) {
    // Creamos el formulario que obtendra la informacion de la incidencia
    formDataGetCatMotInc = new FormData();
    formDataGetCatMotInc.append("_token", CSRF_TOKEN);
    formDataGetCatMotInc.append("_method", "POST");
    formDataGetCatMotInc.append("idCatMotInc", idCatMotInc);

    loaderOut();
    
    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getCatMotIncById",
        async: true,
        type: "POST",
        data: formDataGetCatMotInc,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                clearInputsConfigIncidencias()

                $('#btnCatIncCancelar').removeClass('d-none')

                $('#incidencia').val(data.incidencia.incidencia)

                // Asignamos el valor al campo de recurrencia
                $('#recurrenciaVal').val(data.incidencia.recurrente)
                markRecurrence(data.incidencia.recurrente)

                $('#frecuenciaCatIncVal').val(data.incidencia.frecuencia)
                markFrecuency(data.incidencia.frecuencia)
                
                $('#tipoVal').val(data.incidencia.tipo)
                markTypeMonto(data.incidencia.tipo)

                if (data.incidencia.monto != null) {
                    // Validamos si el div contiene la clase d-none
                    $('#contentCatMotIncVendedor').removeClass('d-none')
                    $('#catMotIncVendedor').val('')
                    
                    // Llenamos los campos del formulario
                    $('#montoCatMotAnt').val(data.incidencia.monto)
                } else {
                    // Agregamos la clase d-none al div
                    $('#contentCatMotIncVendedor').addClass('d-none')
                }
                
                $('#btnCatInc').html('Actualizar')
                $('#btnCatInc').attr('onclick', 'updateCatIncidencia("'+idCatMotInc+'")')

                // Activamos la bandera de edición
                editandoMotivo = true;

                // Ocultamos los botones de eliminacion
                document.getElementsByClassName('deleteAction').forEach(function(btn) {
                    btn.classList.add('d-none');
                });
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

            Msg.fire({ title: "Ocurrió un error, al obtener los registros" });
        }
    })
}

/**
 * Función para actualizar el registro al catalogo de motivos de incidencias v2
 * 
 * @param {*} idCatMotInc
 * @return void
 */
function updateCatIncidencia(idCatMotInc) {
    // Creamos el formulario que se enviara para registrar la incidencia
    formDataUpdateCatMotivosIncidencias = new FormData();
    formDataUpdateCatMotivosIncidencias.append("_token", CSRF_TOKEN);
    formDataUpdateCatMotivosIncidencias.append("_method", "POST");
    formDataUpdateCatMotivosIncidencias.append("idCatMotInc", idCatMotInc);
    formDataUpdateCatMotivosIncidencias.append("incidencia", $('#incidencia').val());
    formDataUpdateCatMotivosIncidencias.append("recurrencia", $('#recurrenciaVal').val());
    formDataUpdateCatMotivosIncidencias.append("tipo", $('#tipoVal').val());
    formDataUpdateCatMotivosIncidencias.append("frecuenciaCatInc", $('#frecuenciaCatIncVal').val());
    formDataUpdateCatMotivosIncidencias.append("montoCatMotAnt", Number($('#montoCatMotAnt').val()));


    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "updateCatMotivosIncidencias",
        async: true,
        type: "POST",
        data: formDataUpdateCatMotivosIncidencias,
        processData: false,
        contentType: false,
        success: function (data) {
            // Validamos si hay mensajes de error
            if (data.errores > 0) {
                $('#msg-errors-incidencia').html('')
                $('#msg-errors-incidencia').append(data.error_incidencia)
                $('#msg-errors-recurrencia').html('')
                $('#msg-errors-recurrencia').append(data.error_recurrencia)
                $('#msg-errors-tipo').html('')
                $('#msg-errors-tipo').append(data.error_tipo)
                $('#msg-errors-frecuenciaCatInc').html('')
                $('#msg-errors-frecuenciaCatInc').append(data.error_frecuenciaCatInc)
                $('#msg-errors-montoCatMotAnt').html('')
                $('#msg-errors-montoCatMotAnt').append(data.error_montoCatMotAnt)
                return;
            }

            if (data.response) {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se marco la comision
                Msg.fire('Se actualizó el registro');

                // Refrescamos la tabla de los pedidos
                tblCatMotivosIncidenciasTbl()

                // Limpiamos los campos del formulario
                clearInputsConfigIncidencias()
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

            Msg.fire({ title: "Ocurrió un error, al guardar la incidencia" });
        }
    })
}

/**
 * Función para cancelar la actualización de una incidencia
 * 
 * @return void
 */
function clearInputsUpdateIncidencias() {
    clearInputsIncidencias()
}

/**
 * Función para marcar y habilitar la recurrencia
 * 
 * @param {*} value
 */
function markRecurrence(value) {
    if (value == 1) {
        $('#recurrenciaVal').val(value)

        $('#frequencyDiv').removeClass('d-none')
        $('#typeDiv').removeClass('d-none')

        $('#recurrenciaSiBtn').removeClass('btn-secondary')
        $('#recurrenciaSiBtn').addClass('btn-info')

        $('#recurrenciaNoBtn').removeClass('btn-info')
        $('#recurrenciaNoBtn').addClass('btn-secondary')
    } else if (value == 2) {
        $('#recurrenciaVal').val(value)

        $('#frequencyDiv').addClass('d-none')
        $('#typeDiv').addClass('d-none')

        $('#frecuenciaCatIncVal').val('')
        $('#tipoVal').val('')

        $('#recurrenciaSiBtn').addClass('btn-secondary')
        $('#recurrenciaSiBtn').removeClass('btn-info')

        $('#recurrenciaNoBtn').addClass('btn-info')
        $('#recurrenciaNoBtn').removeClass('btn-secondary')

        $('#contentCatMotIncVendedor').addClass('d-none')

        $('#montoCatMotAnt').val('')

        // Restauramos a los botones de la clase restoreBtn para remover el btn-info
        document.querySelectorAll('.restoreBtn').forEach(function(btn) {
            btn.classList.remove('btn-info')
            btn.classList.add('btn-secondary') 
        });
    }
}

/**
 * Función para marcar y habilitar la frecuencia
 * 
 * @param {*} value
 * @return void
 */
function markFrecuency(value) {
    $('#frecuenciaCatIncVal').val(value)

    if (value == 1) {
        $('#frecuenciaSiBtn').removeClass('btn-secondary')
        $('#frecuenciaSiBtn').addClass('btn-info')

        $('#frecuenciaNoBtn').removeClass('btn-info')
        $('#frecuenciaNoBtn').addClass('btn-secondary')
    } else if (value == 2) {
        $('#frecuenciaSiBtn').addClass('btn-secondary')
        $('#frecuenciaSiBtn').removeClass('btn-info')

        $('#frecuenciaNoBtn').addClass('btn-info')
        $('#frecuenciaNoBtn').removeClass('btn-secondary')
    }
}

/**
 * Función para marcar y habilitar el tipo
 * 
 * @param {*} value
 * @return void
 */
function markTypeMonto(value) {
    $('#tipoVal').val(value)

    if (value == 1) {
        $('#tipoSiBtn').removeClass('btn-secondary')
        $('#tipoSiBtn').addClass('btn-info')

        $('#tipoNoBtn').removeClass('btn-info')
        $('#tipoNoBtn').addClass('btn-secondary')

        $('#contentCatMotIncVendedor').removeClass('d-none')
    } else if (value == 2) {
        $('#tipoSiBtn').addClass('btn-secondary')
        $('#tipoSiBtn').removeClass('btn-info')

        $('#tipoNoBtn').addClass('btn-info')
        $('#tipoNoBtn').removeClass('btn-secondary')

        $('#contentCatMotIncVendedor').addClass('d-none')
        $('#montoCatMotAnt').val('0')
    }
}

/**
 * Función para eliminar un elemento del catalogo de motivos de incidencias
 * 
 * @param {*} idCatMotInc
 * @return void
 */
function eliminarCatMotivoIncidencia(idCatMotInc) {
    // Preguntamos si quiere eliminar anticipo
    Swal.fire({
        title: "¿Quieres eliminar este registro?",
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Eliminar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Creamos el formulario que eliminara el registro seleccionado
            formDataDeleteCatMotInc = new FormData();
            formDataDeleteCatMotInc.append("_token", CSRF_TOKEN);
            formDataDeleteCatMotInc.append("_method", "POST");
            formDataDeleteCatMotInc.append("idCatMotInc", idCatMotInc);

            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "deleteCatMotInc",
                async: true,
                type: "POST",
                data: formDataDeleteCatMotInc,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se elimino correctamente el anticipo
                        Msg.fire('Se eliminó el registro');

                        // Refrescamos la tabla de los pedidos
                        tblCatMotivosIncidenciasTbl()
                    } else {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'warning',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que no se pudo eliminar el registro
                        Msg.fire('No se pudo eliminar el registro, ya que cuenta con incidencias relacionadas');
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

                    Msg.fire({ title: "Ocurrió un error, al eliminar" });
                }
            })
        }
    });
}

/**
 * Función para cambiar el estatus de la incidencia
 * 
 * @param {*} idCatMotInc
 * @param {*} check
 * @return void
 */
function changeStatusMotivoInc(idCatMotInc, check) {
    // Creamos el formulario para marcar como pagada la comision
    formDataChangeStatusCatMotInc = new FormData();
    formDataChangeStatusCatMotInc.append("_token", CSRF_TOKEN);
    formDataChangeStatusCatMotInc.append("_method", "POST");
    formDataChangeStatusCatMotInc.append("idCatMotInc", idCatMotInc);
    formDataChangeStatusCatMotInc.append("value", check.checked);

    // Preguntamos si quiere eliminar anticipo
    Swal.fire({
        title: (check.checked != false ? "¿Quieres marcar como activo este motivo?" : "¿Quieres marcar como inactivo este motivo?"),
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: "Marcar",
    }).then((result) => {
        if (result.isConfirmed) {
            // Ejecutamos la acción de ajax
            $.ajax({
                url: serve + "changeStatusCatMotInc",
                async: true,
                type: "POST",
                data: formDataChangeStatusCatMotInc,
                processData: false,
                contentType: false,
                success: function (data) {
                    if (data.response) {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se marco la comision
                        Msg.fire((check.checked != true ? 'Se marco como inactivo el motivo' : 'Se marco como activo el motivo'));

                        // Refrescamos la tabla de pedidos liberados
                        tblCatMotivosIncidenciasTbl()
                    } else {
                        const Msg = Swal.mixin({
                            toast: true,
                            position: "center",
                            icon: 'warning',
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // Mostramos el mensaje de que se marco la comision
                        Msg.fire(result.message);
                    }
                }
            })
        } else {
            // Refrescamos la tabla de pedidos liberados
            tblCatMotivosIncidenciasTbl()
        }
    });
}

/**
 * Función para abrir el modal para los filtros de firmas de los pedidos
 * 
 * @return void
 */
function showMoreFilters() {
    // Mostramos el modal para ver los vendedores
    $("#moreFilters").fadeToggle();
}

/**
 * Función para mostrar el listado de pedidos de acuerdo a las firmas
 * 
 * @param {*} check
 * @param {*} sectionUser
 * @return void
 */
function showOnlySigned(check, sectionUser) {
    let tbl = $('#tblActual').val();

    if(sectionUser == 1) {
        if (check.checked) {
            $('#filterFirmasVendedor').val(1)
        } else {
            $('#filterFirmasVendedor').val(2)
        }
    }

    if(sectionUser == 2) {
        if (check.checked) {
            $('#filterFirmasGerentes').val(1)
        } else {
            $('#filterFirmasGerentes').val(2)
        }
    }

    if(sectionUser == 3) {
        if (check.checked) {
            $('#filterFirmasDireccion').val(1)
        } else {
            $('#filterFirmasDireccion').val(2)
        }
    }

    // luego de asignar los valores, refrescamos la tabla
    if (tbl == 'tbl1') {
        tblAdminPedidosTbl()
    } else if (tbl == 'tbl2') {
        tblAdminPedidosListosLiberarTbl()
    } else if (tbl == 'tbl3') {
        tblAdminPedidosLiberadosTbl()
    }
}

/**
 * Funcionalidad para mostrar listado de vendedores relacionados
 * 
 * @param idMotivoIncidencia
 * @return void
 */
function showVendedoresList(idMotivoIncidencia) {
    formShVendedores = new FormData();
    formShVendedores.append("_token", CSRF_TOKEN);
    formShVendedores.append("_method", "POST");
    formShVendedores.append("idMotivo", idMotivoIncidencia);

    // Ejecutamos la acción de ajax
    $.ajax({
        url: serve + "getVendedoresMotivosList",
        async: true,
        type: "POST",
        data: formShVendedores,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.response) {
                // Decodificamos el JSON
                let list = JSON.parse(data.vendedores);

                let count = list.length;
                
                // Creamos una lista HTML con los datos
                let listHtml = '';

                if (count >= 9) {
                    listHtml += '<ul style="list-style-type: none; max-height: 300px; overflow-y: scroll;">';
                } else {
                    listHtml += '<ul style="list-style-type: none;">';
                }

                if (count == 0) {
                    listHtml += '<li>No hay vendedores relacionados</li>';
                } else {
                    // Recorremos la lista de vendedores
                    list.forEach(function(vendedor) {
                        listHtml += '<li style="padding: 5px 0; text-align: center;">' + vendedor.agente + '</li>';
                    });
                }

                listHtml += '</ul>';

                // Mostramos el modal de SweetAlert con la lista
                Swal.fire({
                    title: 'Listado de Vendedores',
                    html: listHtml,
                    icon: 'info',
                    confirmButtonText: 'Cerrar'
                });
            } else {
                const Msg = Swal.mixin({
                    toast: true,
                    position: "center",
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Mostramos el mensaje de que se marco la comision
                Msg.fire(result.message);
            }
        }
    })
}