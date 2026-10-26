serve = base_path;

año_dashboard = document.getElementById("año_dashboard");
mes_dashboard = document.getElementById("mes_dashboard");

Agente = "TODOS";
año = año_dashboard;
mes = mes_dashboard;

$(document).ajaxStart(function () {
    loaderON();
});

$(document).ajaxStop(function () {
    loaderOut();
});

idRol = document.getElementById('idRol');

$(document).ready(function () {
    // loaderOut();
    if (idRol.value == 1020 || idRol.value == 1021 || idRol.value == 1024) {
        mostrarMonitorEstructural()
    } else {
        cargarOportunidadesXAtender();
        cargarOportunidadesVentasXAtender();
        if ($("#Dashboard_Cotizaciones").hasClass("show")) {
            obtCards(Agente, año.value, mes.value);

            let myVar = setInterval(cargarOportunidadesXAtender, 600000);
            let myVarVentas = setInterval(cargarOportunidadesVentasXAtender, 600000);
        }
    }
    // cards(1, 5292, mes.value, año.value, 5292);


    // ================= DOSSIER ==============================
    // mostrarReportesTR();
});

function totalTripas(){
    $.ajax({
        url: serve + "apiTripasCCambios",
        type: "GET",
        dataType: "json",
        success: function(data){
            $('#totalTripas').text('( ' + data.data[0].totalTripas + ' )');
        }
    });
}

function totalTripasWhitCorrect(){
    $.ajax({
        url: serve + "api/getListCorrecciones",
        type: "GET",
        dataType: "json",
        success: function(data){
            if (data.data && data.data.length != 0) {
                $('#totalTripasCorrect').text('( ' + data.data[0].totalTripasCorrect + ' )');
            }
        }
    });
}

function cargarOportunidadesXAtender() {
    tblOportXAtender = $("#tblOportXAtender").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        scrollY: "30vh",
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

    totalTripas();

    tblTripasCCambios = $("#tblTripasCCambios").DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        scrollY: "21.5vh",
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
        ajax: {
            url: serve + "apiTripasCCambios",
            type: "GET",
            dataType: "json",
            // data: {
            //     idOportunidad: id
            // }
        },
        columns: [
            {
                data: 'folio',
                render: function(data, type, row){
                    return '<span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><span class="text"><a href="oportunidades/ ' + row.idOportunidad + '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-primary text-uppercase">&nbsp;&nbsp;&nbsp; ' + row.folio + '</a></span>';
                },
            },
            {
                data: 'oportunidad',
                render: function(data, type, row){
                    return '<span class="text text-uppercase"><a href="oportunidades/' + row.idOportunidad + '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-primary text-uppercase">' + row.oportunidad + '</a></span>';
                },
            },
            {
                data: 'estatus'
            },
            {
                data: 'idDocumento',
                render: function(data, type, row){
                    return '<span class="text"><a class="cursor-pointer" title="Visualizar Tripa" onclick="visorDocumentosTripas(' + data + ')" >' + row.tripa + '</a></span>';
                }
            },
            {
                data: 'idDocumento',
                render: function(data, type, row){
                    return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="tripaConCambiosRealizada(' + data + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                },
                className: 'dt-body-center',
            },
        ]
    });

    // llamamos al total de tripas con correcciones
    totalTripasWhitCorrect();

    // Colocamos la tabla de archivos con correcciones
    tblCorrecciones = $('#tblCorrecciones').DataTable({
        destroy: true,
        pageResize: true,
        searchable: true,
        processing: true,
        serverSide: true,
        scrollY: "21.5vh",
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
        ajax: {
            url: serve + "api/getListCorrecciones",
            type: "GET",
            dataType: "json",
        },
        columns: [
            {
                data: 'folio',
                render: function(data, type, row){
                    return '<span class="handle"><i class="fas fa-ellipsis-v"></i><i class="fas fa-ellipsis-v"></i></span><span class="text"><a href="oportunidades/' + row.idOportunidad + '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-primary text-uppercase">&nbsp;&nbsp;&nbsp; ' + row.folio + '</a></span>';
                },
            },
            {
                data: 'oportunidad',
                render: function(data, type, row){
                    return '<span class="text text-uppercase"><a href="oportunidades/' + row.idOportunidad + '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-primary text-uppercase">' + row.oportunidad + '</a></span>';
                },
            },
            {
                data: 'estatus'
            },
            {
                data: 'notas'
            },
            {
                data: 'idDocumento',
                render: function(data, type, row){
                    return '<span class="text"><a class="cursor-pointer" title="Visualizar Tripa" onclick="visorDocumentosTripas(' + data + ')" >' + row.tripa + '</a></span>';
                }
            },
            {
                data: 'fecha',
                render: function(data, type, row){
                    var f = new Date(row.fecha);
                    var pad = function(n){ return n.toString().padStart(2, '0'); };
                    var fechaFormateada = f.getFullYear() + '/' + pad(f.getMonth() + 1) + '/' + pad(f.getDate()) + ' ' + pad(f.getHours()) + ':' + pad(f.getMinutes());
                    return '<span class="text text-uppercase">' + fechaFormateada + '</span>';
                }
            },
            {
                data: 'idOportunidad',
                render: function(data, type, row){
                    if (row.tipo == 1) {
                        return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="tripaConCambiosConCorreccionesRealizada(' + data + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    } else {
                        return '<div class="custom-control custom-switch"><input type="checkbox" class="custom-control-input"><label onclick="archivoCompletadoPorEstatus(' + row.idOportunidad + ', ' + row.estatus_doc + ')" class="custom-control-label" style="cursor: pointer"></label></div>';
                    }
                },
                className: 'dt-body-center',
            },
        ],
    });

    if (idRol.value == 10 || idRol.value == 1 || idRol.value == 8 || idRol.value == 3 || idRol.value == 1016 || idRol.value == 1023 || idRol.value == 2) {
        $("#diario-tab").removeClass("active");
        $("#dossierDiarioReporte").removeClass("show");
        $("#dossierDiarioReporte").removeClass("active");
        $("#mensual-tab").removeClass("active");
        $("#dossierMensualReporte").removeClass("show");
        $("#dossierMensualReporte").removeClass("active");
        $("#Dashboard_Cotizaciones-tab").addClass("active");
        $("#Dashboard_Cotizaciones").addClass("show");
        $("#Dashboard_Cotizaciones").addClass("active");
        $("#dossierMonitorOportunidades").removeClass("height-dossier-hide");
        $("#dossierMonitorOportunidadesReporte").removeClass("show");
        $("#dossierMonitorOportunidadesReporte").removeClass("active");
        $("#monitorOport-tab").removeClass("active");

        $("#dossierMonitorEstructural").removeClass("height-dossier-hide");
        $("#dossierMonitorEstructuralReporte").removeClass("show");
        $("#dossierMonitorEstructuralReporte").removeClass("active");
        $("#monitorEstr-tab").removeClass("active");

        $("#Dashboard_Ventas-tab").removeClass("active");
        $("#Dashboard_Ventas").removeClass("show");
        $("#Dashboard_Ventas").removeClass("active");
        $("#Dashboard_Agenda-tab").removeClass("active");
        $("#Dashboard_Agenda").removeClass("show");
        $("#Dashboard_Agenda").removeClass("active");

    }
    document.getElementById("año_dashboard").style.display = "block";
    document.getElementById("mes_dashboard").style.display = "block";

}

function cargarOportunidadesVentasXAtender() {
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
}
// año_dashboard.addEventListener("change", function () {
//   cards(1, 5292, mes.value, año.value, 5292);
// });

// function cards(rol, id_usuario, mes, año, agente_id) {
//   tblCards = $("#tblCards").DataTable({
//     destroy: true,
//     pageResize: true,
//     searchable: true,
//     processing: true,
//     scrollY: "55.5vh",
//     scrollX: true,
//     scrollCollapse: true,
//     deferRender: true,
//     // order: [0, "desc"],
//     language: {
//       url: serve + "js/spanish-DT.json",
//     },
//     // lengthMenu: [
//     //   [-1, 50, 100, 150, 200],
//     //   ["TODOS", 50, 100, 150, 200],
//     // ],
//     dom: '<"top">rt<"bottom"><"clear">',
//     ajax: {
//       url: serve + "cards/" + rol + "/" + id_usuario + "/" + mes + "/" + año + "/" + agente_id,
//       type: "GET",
//       dataType: "json",
//     },
//     columnDefs: [
//       {
//         targets: ['_all'],
//         className: "dt-body-center text-left",
//       }
//     ],
//     columns: [
//       {
//         data: "Cantidad",
//       },
//       {
//         data: "color",
//       },
//       {
//         data: "colorEstatus",
//       },
//       {
//         data: "descEstatus",
//       },
//       {
//         data: "icon",
//       },
//       {
//         data: "valorEstatus",
//       },
//     ],
//   });
// }

// ========================== DOSSIER =====================================

function mostrarIndicadorDiario() {
    $("#dossierDiario").removeClass("height-dossier-hide");
    $("#dossierDiario").addClass("height-dossier-visible");
    $("#diario-tab").addClass("active");
    $("#dossierDiarioReporte").addClass("show");
    $("#dossierDiarioReporte").addClass("active");
    $("#dossierMonitorOportunidades").addClass("height-dossier-hide");
    $("#dossierMonitorOportunidades").removeClass("height-dossier-visible");
    $("#mensual-tab").removeClass("active");
    $("#monitorOport-tab").removeClass("active");
    $("#dossierMensualReporte").removeClass("show");
    $("#dossierMensualReporte").removeClass("active");
    $("#dossierMonitorOportunidadesReporte").removeClass("show");
    $("#dossierMonitorOportunidadesReporte").removeClass("active");
    $("#Dashboard_Cotizaciones-tab").removeClass("active");
    $("#Dashboard_Cotizaciones").removeClass("show");
    $("#Dashboard_Cotizaciones").removeClass("active");

    $("#dossierMonitorEstructural").removeClass("height-dossier-hide");
    $("#dossierMonitorEstructuralReporte").removeClass("show");
    $("#dossierMonitorEstructuralReporte").removeClass("active");
    $("#monitorEstr-tab").removeClass("active");

    if (idRol.value == 1 || idRol.value == 8) {
        $("#Dashboard_Ventas-tab").removeClass("active");
        $("#Dashboard_Ventas").removeClass("show");
        $("#Dashboard_Ventas").removeClass("active");
        $("#Dashboard_Agenda-tab").removeClass("active");
        $("#Dashboard_Agenda").removeClass("show");
        $("#Dashboard_Agenda").removeClass("active");
    }

    document.getElementById("año_dashboard").style.display = "none";
    document.getElementById("mes_dashboard").style.display = "none";

}

function mostrarIndicadorMensual() {
    $("#dossierDiario").addClass("height-dossier-hide");
    $("#dossierDiario").removeClass("height-dossier-visible");
    $("#dossierMonitorOportunidades").addClass("height-dossier-hide");
    $("#dossierMonitorOportunidades").removeClass("height-dossier-visible");
    $("#diario-tab").removeClass("active");
    $("#monitorOport-tab").removeClass("active");
    $("#dossierDiarioReporte").removeClass("show");
    $("#dossierDiarioReporte").removeClass("active");
    $("#dossierMonitorOportunidadesReporte").removeClass("show");
    $("#dossierMonitorOportunidadesReporte").removeClass("active");
    $("#mensual-tab").addClass("active");
    $("#dossierMensualReporte").addClass("show");
    $("#dossierMensualReporte").addClass("active");
    $("#Dashboard_Cotizaciones-tab").removeClass("active");
    $("#Dashboard_Cotizaciones").removeClass("show");
    $("#Dashboard_Cotizaciones").removeClass("active");

    $("#dossierMonitorEstructural").removeClass("height-dossier-hide");
    $("#dossierMonitorEstructuralReporte").removeClass("show");
    $("#dossierMonitorEstructuralReporte").removeClass("active");
    $("#monitorEstr-tab").removeClass("active");

    if (idRol.value == 1 || idRol.value == 8) {
        $("#Dashboard_Ventas-tab").removeClass("active");
        $("#Dashboard_Ventas").removeClass("show");
        $("#Dashboard_Ventas").removeClass("active");
        $("#Dashboard_Agenda-tab").removeClass("active");
        $("#Dashboard_Agenda").removeClass("show");
        $("#Dashboard_Agenda").removeClass("active");
    }

    document.getElementById("año_dashboard").style.display = "none";
    document.getElementById("mes_dashboard").style.display = "none";
}

function mostrarMonitorOportunidades() {
    $("#dossierMonitorOportunidades").removeClass("height-dossier-hide");
    $("#dossierMonitorOportunidades").addClass("height-dossier-visible");
    $("#dossierDiario").removeClass("height-dossier-hide");
    $("#dossierDiario").removeClass("height-dossier-visible");
    $("#diario-tab").removeClass("active");
    $("#mensual-tab").removeClass("active");
    $("#dossierMonitorOportunidades").addClass("height-dossier-hide");
    $("#dossierDiarioReporte").removeClass("show");
    $("#dossierDiarioReporte").removeClass("active");
    $("#dossierMonitorOportunidadesReporte").addClass("show");
    $("#dossierMonitorOportunidadesReporte").addClass("active");
    $("#monitorOport-tab").addClass("active");
    $("#dossierMensualReporte").removeClass("show");
    $("#dossierMensualReporte").removeClass("active");
    $("#Dashboard_Cotizaciones-tab").removeClass("active");
    $("#Dashboard_Cotizaciones").removeClass("show");
    $("#Dashboard_Cotizaciones").removeClass("active");

    $("#dossierMonitorEstructural").removeClass("height-dossier-hide");
    $("#dossierMonitorEstructuralReporte").removeClass("show");
    $("#dossierMonitorEstructuralReporte").removeClass("active");
    $("#monitorEstr-tab").removeClass("active");

    if (idRol.value == 1 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9) {
        $("#Dashboard_Ventas-tab").removeClass("active");
        $("#Dashboard_Ventas").removeClass("show");
        $("#Dashboard_Ventas").removeClass("active");
        $("#Dashboard_Agenda-tab").removeClass("active");
        $("#Dashboard_Agenda").removeClass("show");
        $("#Dashboard_Agenda").removeClass("active");
    }

    document.getElementById("año_dashboard").style.display = "none";
    document.getElementById("mes_dashboard").style.display = "none";
}

function mostrarMonitorEstructural() {
    $("#dossierMonitorEstructural").addClass("height-dossier-hide");
    $("#dossierMonitorEstructuralReporte").addClass("show");
    $("#dossierMonitorEstructuralReporte").addClass("active");
    $("#monitorEstr-tab").addClass("active");

    $("#dossierMonitorOportunidades").removeClass("height-dossier-hide");
    $("#dossierMonitorOportunidades").removeClass("height-dossier-visible");
    $("#dossierDiario").removeClass("height-dossier-hide");
    $("#dossierDiario").removeClass("height-dossier-visible");
    $("#diario-tab").removeClass("active");
    $("#mensual-tab").removeClass("active");
    $("#dossierMonitorOportunidades").addClass("height-dossier-hide");
    $("#dossierDiarioReporte").removeClass("show");
    $("#dossierDiarioReporte").removeClass("active");
    $("#dossierMonitorOportunidadesReporte").removeClass("show");
    $("#dossierMonitorOportunidadesReporte").removeClass("active");
    $("#monitorOport-tab").removeClass("active");
    $("#dossierMensualReporte").removeClass("show");
    $("#dossierMensualReporte").removeClass("active");
    $("#Dashboard_Cotizaciones-tab").removeClass("active");
    $("#Dashboard_Cotizaciones").removeClass("show");
    $("#Dashboard_Cotizaciones").removeClass("active");

    if (idRol.value == 1 || idRol.value == 8 || idRol.value == 4 || idRol.value == 9) {
        $("#Dashboard_Ventas-tab").removeClass("active");
        $("#Dashboard_Ventas").removeClass("show");
        $("#Dashboard_Ventas").removeClass("active");
        $("#Dashboard_Agenda-tab").removeClass("active");
        $("#Dashboard_Agenda").removeClass("show");
        $("#Dashboard_Agenda").removeClass("active");
    }

    document.getElementById("año_dashboard").style.display = "none";
    document.getElementById("mes_dashboard").style.display = "none";
}

baseRestURL = "/MicroStrategyLibrary";
projectID = "4144A4A74427236922F4B197856EB90B";
dossierMensual = "15FF039A436A8B38EB5327820B7EDCC8";
dossierIDDiario = "C2CBFE264D377645EDA566A2FA955EB7";
dossierMonitorOportunidades = "84AE315F402445CE34B8F2B10643DC6A";
dossierMonitorEstructural = "46BFF0AA49175D21C3B41B8D1846E1A3";

// console.log(baseRestURL);


var dossierUrlMensual = baseRestURL + '/app/' + projectID + '/' + dossierMensual;
var dossierUrlDiario = baseRestURL + '/app/' + projectID + '/' + dossierIDDiario;
var dossierUrlMonitorOportunidades = baseRestURL + '/app/' + projectID + '/' + dossierMonitorOportunidades;
var dossierUrlMonitorEstructural = baseRestURL + '/app/' + projectID + '/' + dossierMonitorEstructural;

microstrategy.dossier.create({
    placeholder: document.getElementById("dossierMensual"),
    url: dossierUrlMensual,

    disableNotification: true,
    enableResponsive: true,
    enableCustomAuthentication: true,
    customAuthenticationType: microstrategy.dossier.CustomAuthenticationType.AUTH_TOKEN,
    getLoginToken: login

}).then(function (dossierMensual) { });

microstrategy.dossier.create({
    placeholder: document.getElementById("dossierDiario"),
    url: dossierUrlDiario,

    disableNotification: true,
    enableResponsive: true,

    enableCustomAuthentication: true,
    customAuthenticationType: microstrategy.dossier.CustomAuthenticationType.AUTH_TOKEN,
    getLoginToken: login

}).then(function (dossierIDDiario) { });

microstrategy.dossier.create({
    placeholder: document.getElementById("dossierMonitorOportunidades"),
    url: dossierUrlMonitorOportunidades,

    disableNotification: true,
    enableResponsive: true,

    enableCustomAuthentication: true,
    customAuthenticationType: microstrategy.dossier.CustomAuthenticationType.AUTH_TOKEN,
    getLoginToken: login

}).then(function (dossierMonitorOportunidades) { });

microstrategy.dossier.create({
    placeholder: document.getElementById("dossierMonitorEstructural"),
    url: dossierUrlMonitorEstructural,

    disableNotification: true,
    enableResponsive: true,

    enableCustomAuthentication: true,
    customAuthenticationType: microstrategy.dossier.CustomAuthenticationType.AUTH_TOKEN,
    getLoginToken: login

}).then(function (dossierMonitorEstructural) { });

function login() {
    let username = "gustavoC";
    let password = "vtas_mstr21#";

    var options = {
        method: 'POST',
        credentials: 'include',
        mode: 'cors',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(
            { "loginMode": 1, "username": username, "password": password }
        )
    };

    return fetch(baseRestURL + '/api/auth/login', options).then(function (response) {
        if (response.ok) {
            return response.headers.get('x-mstr-authToken');
        } else {
            response.json().then(function (json) { // console.log(json);
            });
        }
    }).catch(function (error) { // console.log(error);
    });
};

function tripaConCambiosRealizada(idDocumento) {
    Swal.fire({
        title: "Tripas",
        text: "Marcar como Realizada la Tripa Seleccionada?",
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
                url: serve + 'tripaCCRealizada',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    idDocumento: idDocumento,
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Msg.fire({ title: 'Tripa Realizada' });
                    totalTripas();
                    tblTripasCCambios.draw();
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Msg.fire({ title: 'Tripa Realizada' });
                    totalTripas();
                    tblTripasCCambios.draw();
                }
            })
        } else {
            console.log('NO');
        }
    })
}

/**
 * Función para marcar que se ha cumplido de acuerdo con el estatus
 *  
 */
function archivoCompletadoPorEstatus(idOportunidad, estatus) {
    $.ajax({
        url: serve + 'oportunidad-realizado-estatus',
        type: 'GET',
        dataType: 'JSON',
        data: {
            idOportunidad: idOportunidad,
            estatus: estatus
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
                Msg.fire({ title: 'Marcado como realizado' });
                totalTripas();
                tblCorrecciones.draw();
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
            Msg.fire({ title: 'La solicitud de prepedido no se pudo realizar' });
            totalTripas();
            tblCorrecciones.draw();
        }
    })
}

/**
 * Función para realizar el cambio de que se realizo la corrección
 *  
 */
function tripaConCambiosConCorreccionesRealizada(idDocumento) {
    Swal.fire({
        title: "Tripas",
        text: "¿Marcar como Realizada la Tripa Seleccionada?",
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
                url: serve + 'tripaCCorreccioneRealizada',
                type: 'GET',
                dataType: 'JSON',
                data: {
                    idDocumento: idDocumento,
                },
                success: function (data) {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Msg.fire({ title: 'Tripa Con Notas Realizada' });
                    totalTripas();
                    tblCorrecciones.draw();
                },
                error: function () {
                    const Msg = Swal.mixin({
                        toast: true,
                        position: "center",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Msg.fire({ title: 'Tripa Con Notas Realizada' });
                    totalTripas();
                    tblCorrecciones.draw();
                }
            })
        } else {
            console.log('NO');
        }
    })
}

const visorPDF = document.getElementById("frameVisorDocumentos");

function visorDocumentosTripas(idDocumento) {
    loaderModalON();

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
            extension = data.Extension;
            byteCharacters = atob(data.Documento);
            descDocumento = data.descDocumento;
            var dateToday = new Date();

            $("#modal-visor-documentos").modal("show");
            $(".tituloVisorDocumentos").text(descDocumento);
            $("#idDocumento").val(idDocumento);
            $("#idOportunidadPdf").val(data.idOportunidad);
            $('#frameVisorCorreos')[0].style.display = 'none';
            $('#frameVisorDocumentos')[0].style.display = 'block';

            var byteNumbers = new Array(byteCharacters.length);
            for (var i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            var byteArray = new Uint8Array(byteNumbers);
            var blob = new Blob([byteArray], { type: extension });

            blob = window.URL.createObjectURL(blob);

            if (data.idTipoDocumento == 1) {
                visorPDF.src = blob + "#toolbar=0&nosidebar=1";
                if (detector.mobile() == null) {
                    loading.style.marginTop = 0;
                    loading.style.height = '98%';
                } else {
                    loading.style.visibility = "hidden";
                }
            } else {
                visorPDF.src = blob + "#toolbar=1&nosidebar=1";
                if (detector.mobile() == null) {
                    loading.style.marginTop = '60px';
                    loading.style.height = '89%';
                } else {
                    loading.style.visibility = "hidden";
                }
            }

            $('#autorizarDiv')[0].style.display = 'none';
            $('#isCancelledDoc')[0].style.display = 'none';
            $('#enviarCorreo')[0].style.display = 'none';
            loading.style.visibility = "hidden";

            descargarImageOport = document.getElementById('descargarImageOport');

            descargarImageOport.style.display = 'none'
            document.onmousedown = disableRightclick;

            loaderModalOut();

        },
        error: function () { }
    });
}

function disableRightclick(evt) {
    if (evt.button == 2) { // alert(message);
        evt.preventDefault();
        // Swal.fire(message);
        // return false;
    }
}


