var Agente = 'TODOS';
var labelone = [],
    Meta = [],
    Venta = [],
    Acomulado = [],
    VentaAcomulado = [],
    font_size = 19,
    background_color = '#1E3A8A';

$(document).ready(function() {
    // loaderON();

    Agente = $("#sltAgentes").val();
    Año = $('#año_dashboard').val();
    obtGraficaVentasVsMeta(Agente, Año)

    $("#sltAgentes").change(function(event) {
        // loaderON();
        Agente = $("#sltAgentes").val();
        if ($("#año_dashboard").val() == 'TODOS') {
            Año = 'TODO'
        } else {
            Año = $('#año_dashboard').val();
        }
        if (labelone != '') {
            $("canvas#lineChart").remove();
            $("div#linechars").append('<canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
            $("canvas#lineChartAcomulado").remove();
            $("div#chartAcumulado").append('<canvas id="lineChartAcomulado" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

        }

        obtGraficaVentasVsMeta(Agente, Año)
    });

    $("#año_dashboard").change(function(event) {
        // loaderON();
        Agente = $("#sltAgentes").val();
        if ($("#año_dashboard").val() == 'TODOS') {
            Año = 'TODO'
        } else {
            Año = $('#año_dashboard').val();
        }
        if (labelone != '') {
            $("canvas#lineChart").remove();
            $("div#linechars").append('<canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');
            $("canvas#lineChartAcomulado").remove();
            $("div#chartAcumulado").append('<canvas id="lineChartAcomulado" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>');

        }

        obtGraficaVentasVsMeta(Agente, Año)
    });

    // let sem = setInterval(autoReloadSemana, 10000);

    // function autoReloadSemana() {
    //     obtGraficaVentasVsMeta(Agente);
    // }


});

//------------------------------------FUNCIONES
function obtGraficaVentasVsMeta(Agente, Año) {
    labelone = [];
    Meta = [];
    Venta = [];
    Acomulado = [];
    VentaAcomulado = [];
    if (Agente == 'TODOS') { Agente = 0 } else { Agente = $("#sltAgentes").val() }
    // alert('agente' + Agente)
    $.ajax({
        data: { Agente: Agente, Año: Año },
        url: (base_path + 'api/obt_DasboardsGraficaVentasVsMeta'),
        type: "GET",
        dataType: "json",
        success: function(result) {

            $.each(result, function(ind, elem) {
                labelone.push(elem.Mes);
                Meta.push(elem.Meta);
                Venta.push(elem.Venta);
                Acomulado.push(elem.Acomulado);
                VentaAcomulado.push(elem.VentaAcomulado);
            });
            GraficaVentasAcomulada(labelone, Acomulado, VentaAcomulado)
            GraficaVentasVsMeta(labelone, Meta, Venta)
        },
        error: function(r) {
            alert("Ocurrio un error en el servidor ..");
        },
    });
}

function GraficaVentasVsMeta(labelone, Meta, Venta) {

    var areaChartData = {
        labels: labelone, // ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], // 
        datasets: [{
            label: 'Metas',
            backgroundColor: 'rgba(60,141,188,0.9)',
            borderColor: 'rgba(60,141,188,0.8)',
            pointRadius: 5,
            // pointColor: '#3b8bba',
            pointColor: 'rgba(210, 214, 222, 1)',
            pointStrokeColor: 'rgba(60,141,188,1)',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data: Meta
        }, {
            label: 'Ventas',
            backgroundColor: 'rgba(210, 214, 222, 1)',
            borderColor: 'rgba(210, 214, 222, 1)',
            pointRadius: 5,
            pointColor: '#3b8bba',
            // pointColor: 'rgba(210, 214, 222, 1)',
            pointStrokeColor: '#c1c7d1',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data: Venta
        }, ]
    }

    var areaChartOptions = {
        maintainAspectRatio: true,
        responsive: true,
        legend: {
            display: true
        },
        tooltips: {
            enabled: true,
            titleFontSize: font_size,
            bodyFontSize: font_size,
            backgroundColor: background_color,
            borderColor: '#fff',
            cornerRadius: 10,
            boxPadding: 30,
            borderWidth: 2,
            displayColors: true,
            titleMarginBottom: 10,
            xPadding: 15,
            yPadding: 15,
            callbacks: {
                label: function(tooltipItem, data) {
                    return "$" + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
                        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                    });
                }
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
                    beginAtZero: false,
                    callback: function(value, index, values) {
                        if (parseInt(value) >= 1000) {
                            return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } //else if (parseInt(value) >= 1000000) { return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "M"); } 
                        else {
                            return '$' + value;
                        }
                    }
                }
            }],

        },
        title: {
            display: true,
            text: 'VENTA X MES'
        }
    }

    // This will get the first returned node in the jQuery collection.
    // new Chart(areaChartCanvas, {
    //     type: 'line',
    //     data: areaChartData,
    //     options: areaChartOptions
    // });

    //-------------
    //- LINE CHART -
    //--------------

    var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var lineChartData = $.extend(true, {}, areaChartData)
    lineChartData.datasets[0].fill = false;
    lineChartData.datasets[1].fill = false;
    lineChartOptions.datasetFill = false

    var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
    })
}
///// Tabla de acumulados
function GraficaVentasAcomulada(labelone, Acomulado, VentaAcomulado) {
    var areaChartOptions = {
        maintainAspectRatio: true,
        responsive: true,
        legend: {
            display: true
        },
        tooltips: {
            enabled: true,
            titleFontSize: font_size,
            bodyFontSize: font_size,
            backgroundColor: background_color,
            borderColor: '#fff',
            cornerRadius: 10,
            boxPadding: 30,
            borderWidth: 2,
            displayColors: true,
            titleMarginBottom: 10,
            xPadding: 15,
            yPadding: 15,
            callbacks: {
                label: function(tooltipItem, data) {
                    return "$" + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
                        return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                    });
                }
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
                    beginAtZero: false,
                    callback: function(value, index, values) {
                        if (parseInt(value) >= 1000) {
                            return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } //else if (parseInt(value) >= 1000000) { return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "M"); } 
                        else {
                            return '$' + value;
                        }
                    }
                }
            }],

        },
        title: {
            display: true,
            text: 'VENTA ACUMULADA'
        }
    }
    var lineChartOptions = $.extend(true, {}, areaChartOptions)
    var ctx = $('#lineChartAcomulado').get(0).getContext('2d')
    const mixedChart = new Chart(ctx, {
        title: true,
        type: 'bar',
        data: {
            datasets: [{
                label: 'Venta Acumulada',
                data: VentaAcomulado,
                backgroundColor: 'rgba(210, 214, 222, 1)',
                borderColor: 'rgba(210, 214, 222, 1)',
                // this dataset is drawn below
                order: 2
            }, {
                label: 'Meta Acumulada',
                data: Acomulado,
                type: 'line',
                fill: false,
                // backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                pointRadius: 5,
                // pointColor: '#3b8bba',
                pointColor: 'rgba(210, 214, 222, 1)',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',

                // this dataset is drawn on top
                order: 1
            }],
            labels: labelone,
        },
        options: lineChartOptions
    });
}