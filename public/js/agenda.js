SITEURL = base_path;
AgenteAgendaCalendario = $("#sltAgendaAgentes").val();

$(document).ajaxStart(function() {
    loaderON();
});

$(document).ajaxStop(function() {
    loaderOut();
});

$(document).ready(function() {
    $("#sltAgendaAgentes").on("change", function() {
        AgenteAgendaCalendario = $(this).val();
        cargarCalendario();
    });
    cargarLlamadasReuniones();
});

function cargarLlamadasReuniones() {
    tblLlamadasXAtender = $("#tblLlamadasXAtender").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        scrollY: "22vh",
        scrollX: true,
        scrollCollapse: true,
        deferRender: false,
        order: [0, "desc"],
        language: {
            url: serve + "js/spanish-DT.json",
        },
        lengthMenu: [
            [-1, 50, 100, 150, 200],
            ["TODOS", 50, 100, 150, 200],
        ],
        dom: '<"top">rt<"bottom"><"clear">',
    });

    tblReunionesXAtender = $("#tblReunionesXAtender").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        scrollY: "22vh",
        scrollX: true,
        scrollCollapse: true,
        deferRender: false,
        order: [0, "desc"],
        language: {
            url: serve + "js/spanish-DT.json",
        },
        lengthMenu: [
            [-1, 50, 100, 150, 200],
            ["TODOS", 50, 100, 150, 200],
        ],
        dom: '<"top">rt<"bottom"><"clear">',
    });
}

function cargarCalendario() {
    var calendarEl = document.getElementById("calendar");

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev",
            center: "title",
            right: "next",
            // right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: "es",
        timeZone: "America/Mexico_City",
        weekends: false,
        weekNumbers: true,
        navLinks: false,
        buttonText: {
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Lista",
        },
        expandRows: true,
        weekText: "S",
        nowIndicator: true,
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5],
            startTime: "08:00",
            endTime: "19:00",
        },
        events: {
            url: SITEURL + "eventos",
            method: "GET",
            extraParams: {
                Agente: AgenteAgendaCalendario,
            },
            failure: function() {
                Swal.fire("Ocurrió un error en servidor!");
            },
        },
        // eventInteractive: true,
        displayEventTime: true,
        editable: true,
        selectable: true,
        eventDidMount: function(info, element) {
            if (info.event.extendedProps.tipo == "Llamadas") {
                color_fondo = "#80A7CD";
            } else {
                color_fondo = "#4D6882";
            }

            if (info.event.extendedProps.tipo == "Llamadas") {
                info.el.className =
                    "fc-daygrid-event fc-daygrid-dot-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-past fa fa-phone";
                tipo = "LLAMADA : ";
            } else {
                info.el.className =
                    "fc-daygrid-event fc-daygrid-dot-event fc-event fc-event-draggable fc-event-resizable fc-event-start fc-event-end fc-event-past fa fa-user-tie";
                tipo = "REUNIÓN : ";
            }

            element = new Tooltip(info.el, {
                html: true,
                title: '<div style="background-color:' +
                    color_fondo +
                    '; border-radius: 20px; padding: 10px !important; margin: -10px !important"><table class="table nowrap compact tblnombre"><thead><tr><i class="fas fa-circle" style="color: ' +
                    info.event.backgroundColor +
                    '">&nbsp;&nbsp;</i>' +
                    info.event.title +
                    '</tr><tr><th colspan="4">' +
                    "Cliente : " +
                    info.event.extendedProps.Cliente +
                    "</th></tr></thead><tbody><tr><td>" +
                    "Hora : " +
                    info.event.extendedProps.Hora +
                    "</td><td>" +
                    "Situación : " +
                    info.event.extendedProps.Situacion +
                    '</td><td colspan="2">' +
                    'Estado : <span style="background-color:' +
                    info.event.backgroundColor +
                    '; border-radius: 5px; padding: 1px">' +
                    info.event.extendedProps.Estado +
                    "</span></td></tr><tr><td>Contacto : " +
                    info.event.extendedProps.ContactoPrincipal +
                    "</td><td>Teléfono: " +
                    info.event.extendedProps.Telefonos +
                    '</td><td colspan="2"></td></tr></tbody></table></div>',
                placement: "top",
                trigger: "hover",
                container: "body",
            });
            loaderOut();
        },
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            editarLlamada(info.event.id, 1, info.event.extendedProps.tipo);
            loaderON();
        },
        eventInteractive: function() {
            loaderOut();
        },
    });

    calendar.render();
}

// $('.fc-button').on('click', function(e) {
//     console.log($(this).text())
// })

function mostrarAgenda() {
    AgenteCards = $("#sltAgendaAgentes").val();

    $("#Dashboard_Agenda").ajaxStart(
        cargarCalendario(),
        obtCardLlamada(AgenteCards),
        obtCardReunion(AgenteCards)
    );
    $("#Dashboard_Agenda").ajaxStop();

    $("#dossierMonitorOportunidades").removeClass("height-dossier-hide");
    $("#dossierMonitorOportunidadesReporte").removeClass("show");
    $("#dossierMonitorOportunidadesReporte").removeClass("active");
    $("#monitorOport-tab").removeClass("active");

    $("#dossierMonitorEstructural").removeClass("height-dossier-hide");
    $("#dossierMonitorEstructuralReporte").removeClass("show");
    $("#dossierMonitorEstructuralReporte").removeClass("active");
    $("#monitorEstr-tab").removeClass("active");

    document.getElementById("año_dashboard").style.display = "block";
    document.getElementById("mes_dashboard").style.display = "block";

    cargarLlamadasReuniones();

}

function mostrarVentas() {
    tblOportVentasXAtender = $("#tblOportVentasXAtender").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        scrollY: "23.5vh",
        scrollX: true,
        scrollCollapse: true,
        deferRender: true,
        order: [0, "desc"],
        language: {
            url: serve + "js/spanish-DT.json",
        },
        lengthMenu: [
            [-1, 50, 100, 150, 200],
            ["TODOS", 50, 100, 150, 200],
        ],
        dom: '<"top">rt<"bottom"><"clear">',
    });
    tblOportVentasXAtender.draw();

    $("#dossierMonitorOportunidades").removeClass("height-dossier-hide");
    $("#dossierMonitorOportunidadesReporte").removeClass("show");
    $("#dossierMonitorOportunidadesReporte").removeClass("active");
    $("#monitorOport-tab").removeClass("active");

    $("#dossierMonitorEstructural").removeClass("height-dossier-hide");
    $("#dossierMonitorEstructuralReporte").removeClass("show");
    $("#dossierMonitorEstructuralReporte").removeClass("active");
    $("#monitorEstr-tab").removeClass("active");

    document.getElementById("año_dashboard").style.display = "block";
    document.getElementById("mes_dashboard").style.display = "block";

    // $('#Dashboard_Ventas').addClass('active');
    // $('#Dashboard_Ventas').ajaxStart(cargarCalendario());
}