// $(document).ajaxStart(function() {
//     loaderON();
// })

// $(document).ajaxStop(function() {
//     loaderOut();
// })

// $(document).ajaxStart(function(){
//     $.LoadingOverlay("show");
// });
// $(document).ajaxStop(function(){
//     $.LoadingOverlay("hide");
// });

$(function() {

    // $.LoadingOverlaySetup({
    //     background      : "rgba(0, 0, 0, 0.5)",
    //     // image           : "img/custom.svg",
    //     // imageAnimation  : "1.5s fadein",
    //     imageColor      : "#fff"
    // });
    
    $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'auto',
        delay: { "show": 0, "hide": 0 },
        fallbackPlacement: 'flip',
        boundary: 'scrollParent',
        trigger: 'hover',
    });

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

    $(".select2").select2({
        theme: "bootstrap4",
    });

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
    // $("#reservationdate").datetimepicker({
    //     format: "DD-MM-YYYY",
    // });

    // $("#reservationdate2").datetimepicker({
    //     format: "DD-MM-YYYY",
    // });

    // $("#reservationdate3").datetimepicker({
    //     format: "DD-MM-YYYY",
    // });

    var today = new Date();
    var date = today.getFullYear() + '-' + (today.getMonth() + 1) + '-' + today.getDate();
    // var time = today.getHours() + ":" + today.getMinutes();
    // var dateTime = dateFormat(today, "yyyy-mm-dd");


    fecha = moment(date, "YYYY-MM-DD").toDate();

    //Date and time picker
    // $(".datetime").datetimepicker({
    //     format: "DD-MM-YYYY hh:mm A",
    //     locale: 'es',
    //     daysOfWeekDisabled: [0, 6],
    //     tooltips: {
    //         today: 'Hoy',
    //         clear: 'Limpiar Selección',
    //         close: 'Cerrar',
    //         selectMonth: 'Seleccionar Mes',
    //         prevMonth: 'Mes Anterior',
    //         nextMonth: 'Próximo Mes',
    //         selectYear: 'Seleccionar Año',
    //         prevYear: 'Año Anterior',
    //         nextYear: 'Próximo Año',
    //         selectDecade: 'Seleccionar Decada',
    //         prevDecade: 'Decada Anterior',
    //         nextDecade: 'Próxima Decada',
    //         prevCentury: 'Siglo Anterior',
    //         nextCentury: 'Próximo Siglo',
    //         incrementHour: 'Incrementa Hora',
    //         pickHour: 'Pick Hora',
    //         decrementHour: 'Disminuye Hora',
    //         incrementMinute: 'Incrementa Minuto',
    //         pickMinute: 'Pick Minuto',
    //         decrementMinute: 'Disminuye Minuto',
    //         incrementSecond: 'Incrementa Segundo',
    //         pickSecond: 'Pick Segundo',
    //         decrementSecond: 'Disminuye Segundo',
    //         selectTime: 'Seleccionar Hora',
    //         selectDate: 'Seleccionar Fecha',
    //         togglePeriod: 'AM / PM',
    //     },
    //     icons: {
    //         time: 'fas fa-clock',
    //         date: 'fas fa-calendar',
    //         up: 'fas fa-arrow-up',
    //         down: 'fas fa-arrow-down',
    //         previous: 'fas fa-chevron-left',
    //         next: 'fas fa-chevron-right',
    //         today: 'fas fa-calendar-check-o',
    //         clear: 'fas fa-trash',
    //         close: 'fas fa-times'
    //     },
    //     sideBySide: false,
    //     calendarWeeks: false,
    //     viewMode: 'days',
    //     toolbar: true,
    //     toolbarPlacement: 'default',
    //     minDate: fecha,
    //     showTodayButton: true,
    //     showClear: true
    // });

    //Date and time picker
    // $(".datepicker").datetimepicker({
    //     format: "DD-MM-YYYY",
    //     locale: 'es',
    //     daysOfWeekDisabled: [0, 6],
    //     tooltips: {
    //         today: 'Hoy',
    //         clear: 'Limpiar Selección',
    //         close: 'Cerrar',
    //         selectMonth: 'Seleccionar Mes',
    //         prevMonth: 'Mes Anterior',
    //         nextMonth: 'Próximo Mes',
    //         selectYear: 'Seleccionar Año',
    //         prevYear: 'Año Anterior',
    //         nextYear: 'Próximo Año',
    //         selectDecade: 'Seleccionar Decada',
    //         prevDecade: 'Decada Anterior',
    //         nextDecade: 'Próxima Decada',
    //         prevCentury: 'Siglo Anterior',
    //         nextCentury: 'Próximo Siglo',
    //         incrementHour: 'Incrementa Hora',
    //         pickHour: 'Pick Hora',
    //         decrementHour: 'Disminuye Hora',
    //         incrementMinute: 'Incrementa Minuto',
    //         pickMinute: 'Pick Minuto',
    //         decrementMinute: 'Disminuye Minuto',
    //         incrementSecond: 'Incrementa Segundo',
    //         pickSecond: 'Pick Segundo',
    //         decrementSecond: 'Disminuye Segundo',
    //         selectTime: 'Seleccionar Hora',
    //         selectDate: 'Seleccionar Fecha',
    //         togglePeriod: 'AM / PM',
    //     },
    //     icons: {
    //         time: 'fas fa-clock',
    //         date: 'fas fa-calendar',
    //         up: 'fas fa-arrow-up',
    //         down: 'fas fa-arrow-down',
    //         previous: 'fas fa-chevron-left',
    //         next: 'fas fa-chevron-right',
    //         today: 'fas fa-calendar-check-o',
    //         clear: 'fas fa-trash',
    //         close: 'fas fa-times'
    //     },
    //     sideBySide: false,
    //     calendarWeeks: false,
    //     viewMode: 'days',
    //     toolbar: false,
    //     toolbarPlacement: 'default',
    // });

    // $('#fecInicio').dateTimePicker({
    //     dateFormat: "DD-MM-YYYY hh:mm A",
    //     locale: 'es',
    //     showTime: true,
    //     selectData: "now",
    //     positionShift: { top: 40, left: 0 },
    //     // title: "Select Date and Time",
    //     buttonTitle: "Seleccionar"
    // });

    $('#fecTopeCot').appendDtpicker({
        locale: 'es',
        futureOnly: true,
        closeOnSelected: true,
        dateFormat: 'DD-MM-YYYY hh:mm',
        todayButton: false,
        closeButton: false,
        autodateOnStart: false,
        // amPmInTimeList: true,
        timelistScroll: true,
        minTime: '09:00',
        maxTime: '18:30',
        minuteInterval: 10,
        calendarMouseScroll: false,
        allowWdays: [1, 2, 3, 4, 5],
    });

    $('#fecReqEntProd').appendDtpicker({
        locale: 'es',
        futureOnly: true,
        dateOnly: true,
        closeOnSelected: true,
        dateFormat: 'DD-MM-YYYY',
        todayButton: false,
        closeButton: false,
        autodateOnStart: false,
        // amPmInTimeList: true,
        timelistScroll: true,
        calendarMouseScroll: false,
        allowWdays: [1, 2, 3, 4, 5],
    });

    $('#fecEntrega').appendDtpicker({
        locale: 'es',
        futureOnly: true,
        dateOnly: true,
        closeOnSelected: true,
        dateFormat: 'DD-MM-YYYY',
        todayButton: false,
        closeButton: false,
        autodateOnStart: false,
        // amPmInTimeList: true,
        timelistScroll: true,
        calendarMouseScroll: false,
        allowWdays: [1, 2, 3, 4, 5],
    });

    $('#fecInicioInput').appendDtpicker({
        locale: 'es',
        futureOnly: true,
        closeOnSelected: true,
        dateFormat: 'DD-MM-YYYY hh:mm',
        todayButton: false,
        closeButton: false,
        autodateOnStart: false,
        // amPmInTimeList: true,
        timelistScroll: true,
        calendarMouseScroll: false,
        minTime: '08:00',
        maxTime: '20:00',
        allowWdays: [1, 2, 3, 4, 5],
        minuteInterval: 10,
        onSelect: function(handler, targetDate) {
            validarDatos();
        },
    });

    // $('.datetimepicker-input').appendDtpicker({
    //     locale: 'es',
    //     futureOnly: true,
    //     closeOnSelected: true,
    //     dateFormat: 'DD-MM-YYYY hh:mm',
    //     todayButton: false,
    //     closeButton: false,
    //     allowWdays: true,
    //     autodateOnStart: true,
    //     amPmInTimeList: true,
    //     timelistScroll: true,
    //     calendarMouseScroll: false,
    //     minTime: '08:00',
    //     maxTime: '19:00',
    //     allowWdays: [1, 2, 3, 4, 5],
    //     onSelect: function(handler) {
    //         validarDatos();
    //         agregarFechaFin();
    //     },
    // });

    // $('#reservationdatetime5').dateTimePicker({
    //     dateFormat: "DD-MM-YYYY hh:mm A",
    //     locale: 'es',
    //     showTime: true,
    //     selectData: "now",
    //     positionShift: { top: 40, left: 0 },
    //     // title: "Select Date and Time",
    //     buttonTitle: "Seleccionar"
    // });


    // $("#reservationdatetime2").datetimepicker({
    //     icons: {
    //         time: "far fa-clock",
    //     },
    //     format: "DD-MM-YYYY hh:mm A",
    // });

    // $("#reservationdatetime3").datetimepicker({
    //     icons: {
    //         time: "far fa-clock",
    //     },
    //     format: "DD-MM-YYYY hh:mm A",
    // });

    // $("#reservationdatetime4").datetimepicker({
    //     icons: {
    //         time: "far fa-clock",
    //     },
    //     format: "DD-MM-YYYY hh:mm A",
    // });

    // //Date range picker
    // $("#reservation").daterangepicker();
    // //Date range picker with time picker
    // $("#reservationtime").daterangepicker({
    //     timePicker: true,
    //     timePickerIncrement: 30,
    //     locale: {
    //         format: "DD-MM-YYYY hh:mm A",
    //     },
    // });
    // //Date range as a button
    // $("#daterange-btn").daterangepicker({
    //         ranges: {
    //             Today: [moment(), moment()],
    //             Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
    //             "Last 7 Days": [moment().subtract(6, "days"), moment()],
    //             "Last 30 Days": [moment().subtract(29, "days"), moment()],
    //             "This Month": [moment().startOf("month"), moment().endOf("month")],
    //             "Last Month": [
    //                 moment().subtract(1, "month").startOf("month"),
    //                 moment().subtract(1, "month").endOf("month"),
    //             ],
    //         },
    //         startDate: moment().subtract(29, "days"),
    //         endDate: moment(),
    //     },
    //     function(start, end) {
    //         $("#reportrange span").html(
    //             start.format("D MMMM, YYYY") + " - " + end.format("D MMMM, YYYY")
    //         );
    //     }
    // );

    // -----------------------Agregar-------------------------------

    // -----------------------Agregar-------------------------------

    // //Timepicker
    // $("#timepicker").datetimepicker({
    //     format: "DD-MM-YYYY hh:mm A",
    // });

    // $(".datetimepicker").datetimepicker({
    //     format: "DD-MM-YYYY hh:mm A",
    //     locale: 'ru'
    // });

    // $("#timepicker2").datetimepicker({
    //     format: "DD-MM-YYYY hh:mm A",
    // });

    //Bootstrap Duallistbox
    $(".duallistbox").bootstrapDualListbox({
        filterTextClear: "Mostrar Todo",
        filterPlaceHolder: "Filtrar",
        moveSelectedLabel: "Mover Seleccionado",
        moveAllLabel: "Mover Todo",
        removeSelectedLabel: "Remover Sellecionado",
        removeAllLabel: "Remover Todo",
        selectedListLabel: "Seleccionados",
        nonSelectedListLabel: "Disponibles",
        showFilterInputs: false,
        selectorMinimalHeight: 200,
        infoText: false,
        infoTextEmpty: "Lista Vacía",
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

})


//------------FUNCIONES-------------------//
function loaderON() {
    // console.log('on')
    // $.LoadingOverlay("show");
    $(".loanding").fadeIn("slow");
}

function loaderOut() {
    // console.log('out')
    // $.LoadingOverlay("hide");
    $(".loanding").fadeOut("slow");
}
//---- BOTONES DE REGRESAR--//BIGG
function regresar() {
    loaderON();
    window.history.go(-1);
}

function regresarOportunidades() {
    // window.location.reload();
    loaderON();
    // Obtiene la ruta actual
    var currentUrl = window.location.href;

    let enviroment = $('meta[name="environment"]').attr('content');

    // Verifica si hay un referrer y si es diferente de la ruta actual
    if (document.referrer && document.referrer !== currentUrl) {
        window.location.href = document.referrer;
    } else {
        if (enviroment === 'development') {
            window.location.href = "https://servicios.litoprocess.com/crmlitotest/oportunidades";
        } else {
            window.location.href = "https://servicios.litoprocess.com/crmlito/oportunidades";
        }
    }
    // window.history.go(-1);
    // window.history.back();
    // window.location.href = "/oportunidades";
}

function loaderModalON() {
    $(".loandingModal").fadeIn("slow");
}

function loaderModalOut() {
    $(".loandingModal").fadeOut("slow");
}

$(document).ready(function() {
    var body = document.body;
    $(window).resize(function() {
        body.classList.add("sidebar-collapse");
    });

    $('input[autofill="off"]').disableAutofill();

    $(document).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function(position, feedback) {
                $(this).css(position);
                $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .appendTo(this);
            }
        }
    });

});

function selectRow(tabla) {
    $('#' + tabla).on('click', 'tr', function() {
        var row = $(this);
        var td = document.getElementsByTagName('tr');

        for (var i = 0; i < td.length; i++) {
            td[i].classList.remove('selected');
        }

        row[0].classList = 'selected';
        $('#'  + tabla).DataTable();
    });
}