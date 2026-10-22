//$(function() {

/*
 * DONUT CHART
 * -----------
 */

// $(document).ajaxStart(function() {
//     loaderON();
// });

// $(document).ajaxStop(function() {
//     loaderOut();
// });

var labelPro = [],
    data = [],
    color = [];
var Agente = 'TODOS';
$(document).ready(function() {
    Agente = $("#sltAgentes").val();
    obtGrafica(Agente)
    $("#sltAgentes").change(function(event) {
        // loaderON();
        Agente = $("#sltAgentes").val();
        obtGrafica(Agente)
    });

});


function obtGrafica(Agente) {
    // label = [];
    // data = [];
    // color = [];

    // alert(Agente)
    $.ajax({
        data: {
            Agente: Agente
        },
        url: (base_path + 'api/obt_DasboardsGraficaProbabilidad/'),
        type: "GET",
        dataType: "json",
        success: function(result) {


            $.each(result, function(ind, elem) {
                labelPro.push(elem.label);
                data.push(elem.data);
                color.push(elem.color);
                // Etiquetas
                // $("#pie-label1").append("<li><i class='far fa-circle' style='color:" + elem.color + "'></i> " + elem.label + " Probabilidad</li>");
            });
            GraficaPie(labelPro, data, color, result);




        },
        error: function(r) {
            alert("Ocurrio un error en el servidor ..");
        },
    });
}

function GraficaPie(labelPro, data, color, result) {
    // loaderOut();
    $.plot('#donut-chart', result, {
            series: {
                pie: {
                    show: true,
                    radius: 1,
                    innerRadius: 0.5,
                    label: {
                        show: true,
                        radius: 2 / 3,
                        formatter: labelFormatter,
                        // threshold: 0.1
                    },
                    tooltips: {
                        enabled: true
                    },

                }
            },
            legend: {
                show: false,
                display: true
            },


        })
        /*
         * END DONUT CHART
         */
};
//});

function labelFormatter(labelPro, series, data) {
    return '<div style="font-size:0.7rem; text-align:center; color: #fff; font-weight: 600;">' +
        series.data[0][1] +
        '<br>' + ' Oport.</div>'
}