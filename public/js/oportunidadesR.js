$(function() {
    //Initialize Select2 Elements
    $(".select2").select2({
        theme: "bootstrap4",
    });

    // //Initialize Select2 Elements
    // $('.select2bs4').select2({
    //     theme: 'bootstrap4'
    // })

    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd-mm-yyyy", {
        placeholder: "dd-mm-yyyy",
    });
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm-dd-yyyy", {
        placeholder: "mm-dd-yyyy",
    });
    //Money Euro
    $("[data-mask]").inputmask("dd-mm-yyyy", {
        placeholder: "dd-mm-yyyy",
    });

    //Date picker
    $("#reservationdate").datetimepicker({
        format: "DD-MM-YYYY",
    });

    $("#reservationdate2").datetimepicker({
        format: "DD-MM-YYYY",
    });

    $("#reservationdate3").datetimepicker({
        format: "DD-MM-YYYY",
    });

    //Date and time picker
    $("#reservationdatetime").datetimepicker({
        icons: {
            time: "far fa-clock",
        },
        format: "DD-MM-YYYY hh:mm A",
    });

    $("#reservationdatetime2").datetimepicker({
        icons: {
            time: "far fa-clock",
        },
        format: "DD-MM-YYYY hh:mm A",
    });

    $("#reservationdatetime3").datetimepicker({
        icons: {
            time: "far fa-clock",
        },
        format: "DD-MM-YYYY hh:mm A",
    });

    $("#reservationdatetime4").datetimepicker({
        icons: {
            time: "far fa-clock",
        },
        format: "DD-MM-YYYY hh:mm A",
    });

    //Date range picker
    $("#reservation").daterangepicker();
    //Date range picker with time picker
    $("#reservationtime").daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: "DD-MM-YYYY hh:mm A",
        },
    });
    //Date range as a button
    $("#daterange-btn").daterangepicker({
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
            startDate: moment().subtract(29, "days"),
            endDate: moment(),
        },
        function(start, end) {
            $("#reportrange span").html(
                start.format("D MMMM, YYYY") + " - " + end.format("D MMMM, YYYY")
            );
        }
    );

    // -----------------------Agregar-------------------------------

    // -----------------------Agregar-------------------------------

    //Timepicker
    $("#timepicker").datetimepicker({
        format: "DD-MM-YYYY hh:mm A",
    });

    $("#timepicker2").datetimepicker({
        format: "DD-MM-YYYY hh:mm A",
    });

    //Bootstrap Duallistbox
    $(".duallistbox").bootstrapDualListbox({
        filterTextClear: 'Mostrar Todo',
        filterPlaceHolder: 'Filtrar',
        moveSelectedLabel: 'Mover Seleccionado',
        moveAllLabel: 'Mover Todo',
        removeSelectedLabel: 'Remover Sellecionado',
        removeAllLabel: 'Remover Todo',
        selectedListLabel: 'Seleccionados',
        nonSelectedListLabel: 'Disponibles',
        showFilterInputs: false,
        selectorMinimalHeight: 200,
        infoText: false,
        infoTextEmpty: 'Lista Vacía',
        refresh: true,
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    $(".my-colorpicker2").on("colorpickerChange", function(event) {
        $(".my-colorpicker2 .fa-square").css("color", event.color.toString());
    });

    $("input[data-bootstrap-switch]").each(function() {
        $(this).bootstrapSwitch("state", $(this).prop("checked"));
    });

    $(function() {
        bsCustomFileInput.init();
    });
});

// ====================================================================================== CHART =================================================================
// $(document).ready(function() {
//     $.ajax({
//         url: "php/datos.php",
//         dataType: 'json',
//         contentType: "application/json; charset=utf-8",
//         method: "GET",
//         success: function(data) {
//             var nombre = [];
//             var stock = [];
//             var color = ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'];
//             var bordercolor = ['rgba(255,99,132,1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'];
//             console.log(data);

//             for (var i in data) {
//                 nombre.push(data[i].nombre);
//                 stock.push(data[i].stock);
//             }

//             var chartdata = {
//                 labels: nombre,
//                 datasets: [{
//                     label: nombre,
//                     backgroundColor: color,
//                     borderColor: color,
//                     borderWidth: 2,
//                     hoverBackgroundColor: color,
//                     hoverBorderColor: bordercolor,
//                     data: stock
//                 }]
//             };

//             var mostrar = $("#barChart");

//             var grafico = new Chart(mostrar, {
//                 type: 'bar',
//                 data: chartdata,
//                 options: {
//                     responsive: true,
//                     scales: {
//                         yAxes: [{
//                             ticks: {
//                                 beginAtZero: true
//                             }
//                         }]
//                     }
//                 }
//             });
//         },
//         error: function(data) {
//             console.log(data);
//         }
//     });
// });

// let heightW = window.innerHeight;

// if (heightW <= 768) {
//     // $('#scrollbarTabsModalRound').closest('#creteOpor').css('height', '64vh');
//     $("#scrollbarRound").style.height = "64vh";
// } else if (heightW >= 769 && heightW <= 992) {
//     $("#scrollbarRound").style.height = "69vh";
// } else if (heightW >= 993 && heightW <= 1024) {
//     $("#scrollbarRound").style.height = "75vh";
// } else {
//     $("#scrollbarRound").style.height = "90vh";
// }

// $(window).resize(function() {
//     var height = window.innerHeight;
//     if (height <= 768) {
//         $("#scrollbarRound").closest("#creteOpor").css("height", "64vh");
//     } else if (height >= 769 && height <= 992) {
//         $("#scrollbarRound").closest("#creteOpor").css("height", "69vh");
//     } else if (height >= 993 && height <= 1024) {
//         $("#scrollbarRound").closest("#creteOpor").css("height", "75vh");
//     } else {
//         $("#scrollbarRound").closest("#creteOpor").css("height", "90vh");
//     }
// });

$(document).ready(function() {
    var body = document.body;
    $(window).resize(function() {
        body.classList.add("sidebar-collapse");
    });

    var clienteId = $("#idCliente");

    var revinichk = $("#revIniChk");
    var reviniclock = $("#reviniclock");
    var revinihora = $("#revIniHora");
    var reviniheader = $("#revIniHeader");
    var revinifecha = $("#revIniFecha");
    var revini = $("#revIni");

    var listocotchk = $("#listoCotChk");
    var listocotclock = $("#listoCotClock");
    var listocothora = $("#listoCotHora");
    var listocotheader = $("#listoCotHeader");
    var listocotfecha = $("#listoCotFecha");
    var listocot = $("#listoCot");

    var cotchk = $("#cotChk");
    var cotclock = $("#cotClock");
    var cothora = $("#cotHora");
    var cotheader = $("#cotHeader");
    var cotfecha = $("#cotFecha");
    var cot = $("#cot");

    var status = $("#idEstatus");
    // var etapas = $("#idEtapa");
    var txt = " ";

    // $("#idEstatus").prop("disabled", true);
    // $("#idAgente").prop("disabled", true);
    $("#numOrdenMTC").prop("disabled", true);
    $("#notaRetencion").prop("disabled", true);
    $("#idMotivoPerdida").prop("disabled", true);
    // $("#etapa").prop("disabled", true);

    $("#idCliente").change(function() {
        console.log(clienteId.val());
    });

    // timeLineEstatus(status.val());

    // $("#idEtapa").change(function() {
    //     var status = $("#idEstatus");
    //     var etapas = $(this);

    //     if ($(this).val() != "") {
    //         $.ajax({
    //             data: { idEtapa: etapas.val() },
    //             url: "{{ route('oportunidades.show') }}",
    //             // url: "/getEstatus/",
    //             type: "GET",
    //             dataType: "json",
    //             beforeSend: function() {
    //                 etapas.prop("disabled", true);
    //             },
    //             success: function(r) {
    //                 etapas.prop("disabled", false);

    //                 status.find("option").remove();

    //                 $(r).each(function(i, v) {
    //                     // indice, valor
    //                     status.append(
    //                         '<option value="' +
    //                         v.idEstatus +
    //                         '">' +
    //                         v.descEstatus +
    //                         "</option>"
    //                     );
    //                 });

    //                 status.prop("disabled", false);

    //                 timeLineEstatus(status.val());
    //             },
    //             error: function(r) {
    //                 alert("Ocurrio un error en el servidor ..");
    //                 etapas.prop("disabled", false);
    //             },
    //         });
    //     } else {
    //         status.find("option").remove();
    //         status.prop("disabled", true);
    //     }
    // });

    $("#idEstatus").change(function() {
        // timeLineEstatus(status.val());
    });

    // $("#idEquipo").change(function() {
    //     var agente = $("#idAgente");
    //     var equipos = $(this);

    //     if ($(this).val() != "") {
    //         $.ajax({
    //             data: { idEquipo: equipos.val() },
    //             url: "/getEquipos/",
    //             type: "GET",
    //             dataType: "json",
    //             beforeSend: function() {
    //                 equipos.prop("disabled", true);
    //             },
    //             success: function(r) {
    //                 equipos.prop("disabled", false);

    //                 agente.find("option").remove();

    //                 $(r).each(function(i, v) {
    //                     // indice, valor
    //                     agente.append(
    //                         '<option value="' +
    //                         v.idUsuario +
    //                         '">' +
    //                         v.nombre +
    //                         "</option>"
    //                     );
    //                 });

    //                 agente.prop("disabled", false);

    //             },
    //             error: function(r) {
    //                 alert("Ocurrio un error en el servidor ..");
    //                 equipos.prop("disabled", false);
    //             },
    //         });
    //     } else {
    //         agente.find("option").remove();
    //         agente.prop("disabled", true);
    //     }
    // });

    function timeLineEstatus(sta) {
        // console.log("Estatus " + sta);

        var hoy = new Date();

        var fecha =
            hoy.getDate() + "-" + (hoy.getMonth() + 1) + "-" + hoy.getFullYear();
        var hora = hoy.getHours() + ":" + hoy.getMinutes();

        var fechaYHora = formatoFecha(hoy, "dd-mm-yy");

        function formatoFecha(fecha, formato) {
            const map = {
                dd: fecha.getDate(),
                mm: fecha.getMonth() + 1,
                yy: fecha.getFullYear().toString().slice(-2),
                yyyy: fecha.getFullYear(),
            };

            return formato.replace(/dd|mm|yy|yyy/gi, (matched) => map[matched]);
        }
        // var fechaYHora = hoy.toLocaleDateString();

        // console.log(fechaYHora);

        if (sta == 1) {
            revinichk.removeClass("fas fa-times");
            revinichk.removeClass("bg-dark");
            revinichk.addClass("fas fa-check");
            revinichk.addClass("bg-blue");
            reviniclock.addClass("bg-blue");
            revinihora.text(" " + hora);
            reviniheader.addClass("bg-blue");
            revinifecha.text(fecha);
            revini.addClass("text-bold");

            listocotchk.removeClass("fas fa-check");
            listocotchk.removeClass("bg-blue");
            listocotchk.addClass("fas fa-times");
            listocotchk.addClass("bg-dark");
            listocotclock.removeClass("bg-blue");
            listocothora.text("");
            listocotheader.removeClass("bg-blue");
            listocotfecha.text(txt);
            listocot.removeClass("text-bold");
        } else if (sta == 2) {
            listocotchk.removeClass("fas fa-times");
            listocotchk.removeClass("bg-dark");
            listocotchk.addClass("fas fa-check");
            listocotchk.addClass("bg-blue");
            listocotclock.addClass("bg-blue");
            listocothora.text(hora);
            listocotheader.addClass("bg-blue");
            listocotfecha.text(fecha);
            listocot.addClass("text-bold");

            cotchk.removeClass("fas fa-check");
            cotchk.removeClass("bg-blue");
            cotchk.addClass("fas fa-times");
            cotchk.addClass("bg-dark");
            cotclock.removeClass("bg-blue");
            cothora.text("");
            cotheader.removeClass("bg-blue");
            cotfecha.text(txt);
            cot.removeClass("text-bold");
        } else if (sta == 3) {
            revinichk.removeClass("fas fa-times");
            revinichk.removeClass("bg-dark");
            revinichk.addClass("fas fa-check");
            revinichk.addClass("bg-blue");
            reviniclock.addClass("bg-blue");
            revinihora.text(" " + hora);
            reviniheader.addClass("bg-blue");
            revinifecha.text(fecha);
            revini.addClass("text-bold");

            listocotchk.removeClass("fas fa-times");
            listocotchk.removeClass("bg-dark");
            listocotchk.addClass("fas fa-check");
            listocotchk.addClass("bg-blue");
            listocotclock.addClass("bg-blue");
            listocothora.text(hora);
            listocotheader.addClass("bg-blue");
            listocotfecha.text(fecha);
            listocot.addClass("text-bold");

            cotchk.removeClass("fas fa-times");
            cotchk.removeClass("bg-dark");
            cotchk.addClass("fas fa-check");
            cotchk.addClass("bg-blue");
            cotclock.addClass("bg-blue");
            cothora.text(hora);
            cotheader.addClass("bg-blue");
            cotfecha.text(fecha);
            cot.addClass("text-bold");
        } else {
            // revinichk.removeClass("fas fa-check");
            // revinichk.removeClass("bg-blue");
            // revinichk.addClass("fas fa-times");
            // revinichk.addClass("bg-dark");
            // reviniclock.removeClass("bg-blue");
            // revinihora.text("");
            // reviniheader.removeClass("bg-blue");
            // revinifecha.text(txt);
            // revini.removeClass("text-bold");
        }
    }

    // function timeLineEtapas(eta) {
    //     console.log("Etapa " + eta);
    //     if (eta == 1) {
    //         revinichk.removeClass("fas fa-times");
    //         revinichk.removeClass("bg-dark");
    //         revinichk.addClass("fas fa-check");
    //         revinichk.addClass("bg-blue");
    //         reviniclock.addClass("bg-blue");
    //         revinihora.text("12:00");
    //         reviniheader.addClass("bg-blue");
    //         revinifecha.text("16-06-2021");
    //         revini.addClass("text-bold");
    //     } else {
    //         revinichk.removeClass("fas fa-check");
    //         revinichk.removeClass("bg-blue");
    //         revinichk.addClass("fas fa-times");
    //         revinichk.addClass("bg-dark");
    //         reviniclock.removeClass("bg-blue");
    //         revinihora.text("");
    //         reviniheader.removeClass("bg-blue");
    //         revinifecha.text(txt);
    //         revini.removeClass("text-bold");
    //     }
    // }

    $('#tableElePreOpor').DataTable({
        language: {
            url: "../../js/spanish-DT.json"
        },

        dom: '<"top"i>rt<"bottom"><"clear">',
    });

    $('#tableOporunidades').DataTable({
        language: {
            url: "../../js/spanish-DT.json"
        },
        dom: '<"top"i>Brt<"bottom"><"clear">', // 'Brti', '<"top"Bi>rt<"bottom"><"clear">', 'lBfrtip',
        select: true,
        buttons: [{
                extend: 'colvis',
                columns: ':gt(0)',
                text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
            },
            {
                extend: 'excelHtml5',
                title: 'Oportunidades | CANDY CRM',
                text: '<i class="fas fa-file-excel" title="Exportar a Excel"></i>',
                exportOptions: {
                    orthogonal: 'export',
                    columns: "thead th:not(.noExport)",
                },
                filename: 'Oportunidades',
                autoFilter: true,
                sheetName: 'Lista',
                messageTop: null,
                body: true,
                footer: false,
            },
        ],
    });

});

var mensaje = '';
var icono = '';
// function detalle(id) {
//     $.ajax({
//         url: "{{ url('productos') }}" + '/' + id + "/edit",
//         type: "GET",
//         dataType: "JSON",
//         success: function(data) {
//             $('#ajaxModalDetalle').modal('show');
//             $('.modal-title').text('Detalle Producto');

//             var texto = data.fecha_compra;
//             var fechaCompra = formato(texto);

//             $('#idDetalle').val(data.id);
//             $('#descripcionDetalle').val(data.descripcion);
//             $('#categoriaDetalle').val(data.categoria);
//             $('#marcaDetalle').val(data.marca);
//             $('#modeloDetalle').val(data.modelo);
//             $('#numero_serieDetalle').val(data.numero_serie);
//             $('#numero_licenciaDetalle').val(data.numero_licencia);
//             $('#numero_proveedorDetalle').val(data.numero_proveedor);
//             $('#nombre_proveedorDetalle').val(data.nombre_proveedor);
//             $('#numero_facturaDetalle').val(data.numero_factura);
//             $('#fecha_compraDetalle').val(fechaCompra);

//             $('#idDetalle').disabled = true;
//             $('#descripcionDetalle').disabled = true;
//             $('#categoriaDetalle').disabled = true;
//             $('#marcaDetalle').disabled = true;
//             $('#modeloDetalle').disabled = true;
//             $('#numero_serieDetalle').disabled = true;
//             $('#numero_licenciaDetalle').disabled = true;
//             $('#numero_proveedorDetalle').disabled = true;
//             $('#nombre_proveedorDetalle').disabled = true;
//             $('#numero_facturaDetalle').disabled = true;
//             $('#fecha_compraDetalle').disabled = true;

//             function formato(texto) {
//                 return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3/$2/$1');
//             };
//         },
//         error: function() {
//             const Msg = Swal.mixin({
//                 toast: true,
//                 position: 'center',
//                 icon: 'success',
//                 showConfirmButton: false,
//                 timer: 3000
//             });

//             Msg.fire({
//                 title: 'Ocurrió un error',
//             });
//         }
//     });
// }

$(function() {

    $('#formOporCreate form').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            mensaje = 'Oportunidad Creada';
            icono = 'success';
        } else {
            mensaje = 'Faltan Datos';
            icono = 'warning';
        }
        const Msg = Swal.mixin({
            toast: true,
            position: 'center',
            icon: icono,
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire({
            title: mensaje,
        });
    });


    $('#formOporEdit form').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            mensaje = 'Oportunidad Actualizada';
            icono = 'success';
        } else {
            mensaje = 'Faltan Datos';
            icono = 'warning';
        }
        const Msg = Swal.mixin({
            toast: true,
            position: 'center',
            icon: icono,
            showConfirmButton: false,
            timer: 3000
        });

        Msg.fire({
            title: mensaje,
        });
    });

});