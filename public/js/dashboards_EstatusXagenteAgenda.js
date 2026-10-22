var AgenteCards = 'TODOS';
var divLlamada = '';
var divReunion = '';

serve = base_path;

$(document).ready(function() {
    // loaderON();
    // AgenteCards = $("#sltAgendaAgentes").val();
    // obtCardLlamada(AgenteCards)
    // obtCardReunion(AgenteCards)
    // $("#sltAgendaAgentes").change(function(event) {
    //     AgenteCards = $("#sltAgendaAgentes").val();
    //     obtCardLlamada(AgenteCards);
    //     obtCardReunion(AgenteCards);
    // });


});

// function mostrarCotizacionesPanel(){
//     tblOportXAtender = $('#tblOportXAtender').DataTable({
//         destroy: true,
//         pageResize: true,
//         searchable: true,
//         processing: true,
//         scrollY: '55.5vh',
//         scrollX: true,
//         scrollCollapse: true,
//         deferRender: true,
//         order: [0, "desc"],
//         language: {
//             url: serve + "js/spanish-DT.json",
//         },
//         lengthMenu: [
//             [-1, 50, 100, 150, 200],
//             ['TODOS', 50, 100, 150, 200],
//         ],
//         dom: '<"top">rt<"bottom"><"clear">',
//     });
// }

fecha = new Date();
mesActual = fecha.getMonth() + 1;

function obtCardLlamada(AgenteCards) {
    if (AgenteCards == 'TODOS') { AgenteCards = 0 } else { AgenteCards = $("#sltAgendaAgentes").val() }
    divLlamada = '';
    // $("#cardsAgentLlamadas").empty()
    $.ajax({
        data: {
            Agente: AgenteCards
        },
        url: (base_path + 'api/obt_DasboardsEstatusXagenteLlamadas/'),
        type: "GET",
        dataType: "json",
        success: function(resultCardLlamadas) {

            $("#cardsAgentLlamadas").empty()

            $.each(resultCardLlamadas, function(ind, elemCardLlamada) {

                if (elemCardLlamada.idMes == mesActual) {
                    divLlamada = divLlamada + (
                        '<div class="col-lg-4 col-4 div-card">' +
                        '<div class="small-box " style="background-color: #84C876">' +
                        '<div class="inner">' +
                        '<h3>' + elemCardLlamada.Planificada + '</h3>' +
                        '<p>Planificada</p>' +
                        '</div>' +
                        '<div class="icon">' +
                        '<i class="fas fa-calendar" style="font-size: 4rem !important;"></i>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="col-lg-4 col-4 div-card">' +
                        '<div class="small-box " style="background-color: #EE0E12">' +
                        '<div class="inner">' +
                        '<h3>' + elemCardLlamada.Cancelada + '</h3>' +
                        '<p>Cancelada</p>' +
                        '</div>' +
                        '<div class="icon">' +
                        '<i class="fas fa-ban" style="font-size: 4rem !important;"></i>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="col-lg-4 col-4 div-card">' +
                        '<div class="small-box " style="background-color: #369BDF">' +
                        '<div class="inner">' +
                        '<h3>' + elemCardLlamada.Realizada + '</h3>' +
                        '<p>Realizada</p>' +
                        '</div>' +
                        '<div class="icon">' +
                        '<i class="fas fa-check-double" style="font-size: 4rem !important;"></i>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    )
                }
            });
            $("#cardsAgentLlamadas").append(divLlamada);
            loaderOut()
        }
    })

}

function obtCardReunion(AgenteCards) {
    if (AgenteCards == 'TODOS') { AgenteCards = 0 } else { AgenteCards = $("#sltAgendaAgentes").val() }
    divReunion = '';
    $.ajax({
        data: {
            Agente: AgenteCards
        },
        url: (base_path + 'api/obt_DasboardsEstatusXagenteReuniones/'),
        type: "GET",
        dataType: "json",
        success: function(resultCardReuniones) {
            $("#cardsAgentReuniones").empty()
            $.each(resultCardReuniones, function(ind, elemCardReunion) {
                if (elemCardReunion.idMes == mesActual) {
                    divReunion = divReunion + (
                        '<div class="col-lg-4 col-4 div-card">' +
                        '<div class="small-box " style="background-color: #84C876">' +
                        '<div class="inner">' +
                        '<h3>' + elemCardReunion.Planificada + '</h3>' +
                        '<p>Planificada</p>' +
                        '</div>' +
                        '<div class="icon">' +
                        '<i class="fas fa-calendar" style="font-size: 4rem !important;"></i>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="col-lg-4 col-4 div-card">' +
                        '<div class="small-box " style="background-color: #EE0E12">' +
                        '<div class="inner">' +
                        '<h3>' + elemCardReunion.Cancelada + '</h3>' +
                        '<p>Cancelada</p>' +
                        '</div>' +
                        '<div class="icon">' +
                        '<i class="fas fa-ban" style="font-size: 4rem !important;"></i>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +

                        '<div class="col-lg-4 col-4 div-card">' +
                        '<div class="small-box " style="background-color: #369BDF">' +
                        '<div class="inner">' +
                        '<h3>' + elemCardReunion.Realizada + '</h3>' +
                        '<p>Realizada</p>' +
                        '</div>' +
                        '<div class="icon">' +
                        '<i class="fas fa-check-double" style="font-size: 4rem !important;"></i>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    )
                }
            });
            $("#cardsAgentReuniones").append(divReunion);
            loaderOut()
        }
    })

}