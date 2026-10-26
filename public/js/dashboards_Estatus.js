var Agente = "TODOS";
var divCot = "";

// $(document).ajaxStart(function() {
//     loaderON();
// });

// $(document).ajaxStop(function() {
//     loaderOut();
// });

$(document).ready(function() {

    Año = $("#año_dashboard").val();
    Mes = $("#mes_dashboard").val();
    // loaderON();
    $("#mes_dashboard").change(function(event) {
        Año = $("#año_dashboard").val();
        Mes = $("#mes_dashboard").val();
        obtCards(Agente, Año, Mes);
        // actualizarEficiencia(Año, Mes);
    });

    if ($("#Dashboard_Cotizaciones").hasClass("active")) {
        let myVar2 = setInterval(obtCards, 600000);
    }
    if ($("#Dashboard_Ventas").hasClass("active")) {
        let myVar2 = setInterval(obtCards, 600000);
    }

    $("#año_dashboard").change(function(event) {
        Año = $("#año_dashboard").val();
        Mes = $("#mes_dashboard").val();
        obtCards(Agente, Año, Mes);
        // actualizarEficiencia(Año, Mes);
        if ($("#Dashboard_Cotizaciones").hasClass("active")) {}
    });
});

// function actualizarEficiencia() {
//   Año = $("#año_dashboard").val();
//   Mes = $("#mes_dashboard").val();

//     eficiencia(Año, Mes);
// }

function obtCards(Agente, Año, Mes) {
    if (Agente == "TODOS") {
        Agente = 0;
    } else {
        Agente = 0;
    }
    divCot = "";
    $.ajax({
        data: {
            Agente: Agente,
            Año: Año,
            Mes: Mes,
        },
        url: base_path + "api/obt_DasboardsEstatusXagente",
        type: "GET",
        dataType: "json",
        success: function(result) {
            $("#cardsDashboard").empty();
            $.each(result, function(ind, elem) {
                if (elem.idEstatus != 1 && elem.idEstatus != 17) {
                    divCot =
                        divCot +
                        ('<div class="col-md">' +
                            '   <div class="small-box " style="background-color: ' +
                            elem.colorEstatus +
                            ";color: " +
                            elem.color +
                            '; ">' +
                            '      <div class="inner" style="height: 80px !important"> <h3 style="font-size: 2rem;">' +
                            elem.Cantidad +
                            "</h3>" +
                            "         <p style='margin-top: -15px;'>" +
                            elem.descEstatus +
                            "</p>" +
                            "      </div>" +
                            '      <div class="icon">' +
                            '         <i class="' +
                            elem.icon +
                            '" style="font-size: 3rem !important;"></i>' +
                            "      </div>" +
                            '         <a onclick="gotoOport' +
                            "('" +
                            elem.descEstatus +
                            "')" +
                            '" class="small-box-footer cursor-pointer">' +
                            "           Mas info " +
                            '           <i class="fas fa-arrow-circle-right"></i>' +
                            "         </a>" +
                            // '      <a  href="' + base_path + '/oportunidades?evento= ' + elem.descEstatus + '" class="small-box-footer">' +
                            // '          Mas info '+
                            // '          <i class="fas fa-arrow-circle-right"></i>'+
                            // '      </a>' +
                            "   </div>" +
                            "</div>");
                    // '<script>'+
                    // 'function gotoOport(evento)'+
                    // '{'+
                    // '    sessionStorage.setItem("evento", evento.trim());'+
                    // '    window.location.href = "oportunidades";'+
                    // '}'+
                    // '   </script>'
                }
            });
            $("#cardsDashboard").append(divCot);
            // loaderOut()
        },
    });
}

// function eficiencia(Año, Mes) {
//   $.ajax({
//     url: base_path + "eficiencia/" + Mes + "/" + Año,
//     type: "GET",
//     dataType: "json",
//     success: function (data) {
//         $('#porEficienciaTitulo').text(data[0].Eficiencia + " %");
//         $('#totalOportunidades').text(data[0].TotalOpor);
//         $('#oportAtiempoTitulo').text(data[0].Atiempo);
//         $('#oporNotiempoTitulo').text(data[0].Atrasadas);
//     },
//   });
// }