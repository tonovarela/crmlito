var AgenteAgendaLlamada = 'TODOS';
var labeloneAgendaLlamada = [],
    CantidadPlanificadaLlamada = [],
    CantidadCanceladaLlamada = [],
    CantidadRealizadaLlamada = [],
    labeloneAgendaReunion = [],
    CantidadPlanificadaReunion = [],
    CantidadCanceladaReunion = [],
    CantidadRealizadaReunion = [];

$(document).ready(function() {
    // loaderON();

    AgenteAgendaLlamada = $("#sltAgendaAgentes").val();

    obtGraficaLlamadasMes(AgenteAgendaLlamada)
    obtGraficaReunionesMes(AgenteAgendaLlamada);

    
});    

$("#sltAgendaAgentes").change(function(event) {
    // loaderON();
    AgenteAgendaLlamada = $("#sltAgendaAgentes").val();
    if (labeloneAgendaLlamada != '') {
        $("canvas#lineChartAgendaLlamada").remove();
        $("div#linecharsAgendaLlamada").append('<canvas id="lineChartAgendaLlamada" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
        $("canvas#lineChartAgendaReunion").remove();
        $("div#linecharsAgendaReunion").append('<canvas id="lineChartAgendaReunion" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
    }

    obtGraficaLlamadasMes(AgenteAgendaLlamada);
    obtGraficaReunionesMes(AgenteAgendaLlamada);

});

//------------------------------------FUNCIONES

function obtGraficaLlamadasMes(AgenteAgendaLlamada) {
    labeloneAgendaLlamada = [];
    CantidadPlanificadaLlamada = [];
    CantidadCanceladaLlamada = [];
    CantidadRealizadaLlamada = [];
    if (AgenteAgendaLlamada == 'TODOS') { AgenteAgendaLlamada = 0 } else { AgenteAgendaLlamada = $("#sltAgendaAgentes").val() }
    // alert('agente' + Agente)
    $.ajax({
        data: { Agente: AgenteAgendaLlamada },
        url: (base_path + 'api/obt_DasboardsGraficaAgendaLlamadas'),
        type: "GET",
        dataType: "json",
        success: function(result) {
            $.each(result, function(ind, elem) {
                labeloneAgendaLlamada.push(elem.Mes);
                CantidadPlanificadaLlamada.push(elem.Planificada);
                CantidadCanceladaLlamada.push(elem.Cancelada);
                CantidadRealizadaLlamada.push(elem.Realizada);
            });
            GraficaLlamadasMes(labeloneAgendaLlamada, CantidadPlanificadaLlamada, CantidadCanceladaLlamada, CantidadRealizadaLlamada)
        },
        error: function(r) {
            alert("Ocurrio un error en el servidor ..");
        },
    });
}

function obtGraficaReunionesMes(AgenteAgendaLlamada) {
    labeloneAgendaReunion = [];
    CantidadPlanificadaReunion = [];
    CantidadCanceladaReunion = [];
    CantidadRealizadaReunion = [];
    if (AgenteAgendaLlamada == 'TODOS') { AgenteAgendaLlamada = 0 } else { AgenteAgendaLlamada = $("#sltAgendaAgentes").val() }
    // alert('agente' + Agente)
    $.ajax({
        data: { Agente: AgenteAgendaLlamada },
        url: (base_path + 'api/obt_DasboardsGraficaAgendaReuniones'),
        type: "GET",
        dataType: "json",
        success: function(resultReunion) {
            $.each(resultReunion, function(ind, elemReunion) {
                labeloneAgendaReunion.push(elemReunion.Mes);
                CantidadPlanificadaReunion.push(elemReunion.Planificada);
                CantidadCanceladaReunion.push(elemReunion.Cancelada);
                CantidadRealizadaReunion.push(elemReunion.Realizada);
            });
            GraficaReunionesMes(labeloneAgendaReunion, CantidadPlanificadaReunion, CantidadCanceladaReunion, CantidadRealizadaReunion)
        },
        error: function(r) {
            alert("Ocurrio un error en el servidor ..");
        },
    });
}


function GraficaLlamadasMes(labeloneAgendaLlamada, CantidadPlanificadaLlamada, CantidadCanceladaLlamada, CantidadRealizadaLlamada) {

    var areaChartDataAgendaLlamada = {
        labels: labeloneAgendaLlamada, // ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], // 
        datasets: [{
                label: 'Planificada',
                backgroundColor: '#84C876',
                borderColor: '#84C876',
                pointRadius: 5,
                pointColor: '#84C876',
                pointStrokeColor: '#84C876',
                pointHighlightFill: '#84C876',
                pointHighlightStroke: '#84C876',
                fill: false,
                data: CantidadPlanificadaLlamada
            },
            {
                label: 'Cancelada',
                backgroundColor: '#EE0E12',
                borderColor: '#EE0E12',
                pointRadius: 5,
                pointColor: '#EE0E12',
                pointStrokeColor: '#EE0E12',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#EE0E12',
                fill: false,
                data: CantidadCanceladaLlamada
            },
            {
                label: 'Realizada',
                backgroundColor: '#369BDF',
                borderColor: '#369BDF',
                pointRadius: 5,
                pointColor: '#369BDF',
                pointStrokeColor: '#369BDF',
                pointHighlightFill: '#369BDF',
                pointHighlightStroke: '#369BDF',
                fill: false,
                data: CantidadRealizadaLlamada
            },
        ]
    }

    var areaChartOptionsAgendaLlamada = {
        maintainAspectRatio: true,
        responsive: true,
        legend: {
            display: false
        },
        tooltips: {
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    return Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
                        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                    });
                },
                // footer: footer,
            }
        },

        series: {
            shadowSize: 0,
            lines: {
                show: false
            },
            points: {
                show: false
            }
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: true,
                }
            }],
            yAxes: [{

                gridLines: {
                    display: true,
                },
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                        if (parseInt(value) >= 1000) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } //else if (parseInt(value) >= 1000000) { return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "M"); } 
                        else {
                            return value;
                        }
                    },
                    min: 0,
                    stepSize: 10
                }
            }],

        },
        title: {
            display: true,
            text: 'LLAMADAS',
            fontColor: '#80A7CD',
            fontStyle: 'bold'
        }
    }

    // This will get the first returned node in the jQuery collection.
    // new Chart(areaChartCanvas, {
    //     type: 'line',
    //     data: areaChartData,
    //     options: areaChartOptionsAgenda
    // });

    //-------------
    //- LINE CHART -
    //--------------

    var lineChartCanvasAgendaLlamada = $('#lineChartAgendaLlamada').get(0).getContext('2d')
    var lineChartOptionsAgendaLlamada = $.extend(true, {}, areaChartOptionsAgendaLlamada)
    var lineChartDataAgendaLlamada = $.extend(true, {}, areaChartDataAgendaLlamada)
    lineChartDataAgendaLlamada.datasets[0].fill = false;
    lineChartDataAgendaLlamada.datasets[1].fill = false;
    lineChartOptionsAgendaLlamada.datasetFill = false

    var lineChartAgendaLlamada = new Chart(lineChartCanvasAgendaLlamada, {
        type: 'line',
        data: lineChartDataAgendaLlamada,
        options: lineChartOptionsAgendaLlamada
    })
}

function GraficaReunionesMes(labeloneAgendaReunion, CantidadPlanificadaReunion, CantidadCanceladaReunion, CantidadRealizadaReunion) {

    var areaChartDataAgendaReunion = {
        labels: labeloneAgendaReunion, // ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], // 
        datasets: [{
                label: 'Planificada',
                backgroundColor: '#84C876',
                borderColor: '#84C876',
                pointRadius: 5,
                pointColor: '#84C876',
                pointStrokeColor: '#84C876',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#84C876',
                fill: false,
                data: CantidadPlanificadaReunion
            },
            {
                label: 'Cancelada',
                backgroundColor: '#EE0E12',
                borderColor: '#EE0E12',
                pointRadius: 5,
                pointColor: '#EE0E12',
                pointStrokeColor: '#EE0E12',
                pointHighlightFill: '#fff',
                pointHighlightStroke: '#EE0E12',
                fill: false,
                data: CantidadCanceladaReunion
            },
            {
                label: 'Realizada',
                backgroundColor: '#369BDF',
                borderColor: '#369BDF',
                pointRadius: 5,
                pointColor: '#369BDF',
                pointStrokeColor: '#369BDF',
                pointHighlightFill: '#369BDF',
                pointHighlightStroke: '#369BDF',
                fill: false,
                data: CantidadRealizadaReunion
            },
        ]
    }

    var areaChartOptionsAgendaReunion = {
        maintainAspectRatio: true,
        responsive: true,
        legend: {
            display: false
        },
        tooltips: {
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    return Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
                        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                    });
                },
                // footer: footer,
            }
        },

        series: {
            shadowSize: 0,
            lines: {
                show: false
            },
            points: {
                show: false
            }
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: true,
                }
            }],
            yAxes: [{

                gridLines: {
                    display: true,
                },
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                        if (parseInt(value) >= 1000) {
                            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } //else if (parseInt(value) >= 1000000) { return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "M"); } 
                        else {
                            return value;
                        }
                    },
                    min: 0,
                    stepSize: 10
                }
            }],

        },
        title: {
            display: true,
            text: 'REUNIONES',
            fontColor: '#4D6882',
            fontStyle: 'bold'
        }
    }

    // This will get the first returned node in the jQuery collection.
    // new Chart(areaChartCanvas, {
    //     type: 'line',
    //     data: areaChartData,
    //     options: areaChartOptionsAgenda
    // });

    //-------------
    //- LINE CHART -
    //--------------

    var lineChartCanvasAgendaReunion = $('#lineChartAgendaReunion').get(0).getContext('2d')
    var lineChartOptionsAgendaReunion = $.extend(true, {}, areaChartOptionsAgendaReunion)
    var lineChartDataAgendaReunion = $.extend(true, {}, areaChartDataAgendaReunion)
    lineChartDataAgendaReunion.datasets[0].fill = false;
    lineChartDataAgendaReunion.datasets[1].fill = false;
    lineChartOptionsAgendaReunion.datasetFill = false

    var lineChartAgendaReunion = new Chart(lineChartCanvasAgendaReunion, {
        type: 'line',
        data: lineChartDataAgendaReunion,
        options: lineChartOptionsAgendaReunion
    })
}