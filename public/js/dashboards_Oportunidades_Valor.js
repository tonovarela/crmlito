// $(document).ajaxStart(function() {
//     loaderON();
// });
var Agente = 'TODOS';
var Mes = 'TODOS';
$(function () {
    // var areaChartData = '';

    // let sem = setInterval(autoReloadSemana, 10000);

    // function autoReloadSemana() {
    //     obtGraficaValor();
    // }

    Año = $('#año_dashboard').val();
    Mes = $('#mes_dashboard').val();
    Agente = $('#sltAgentes').val();

    $("#mes_dashboard").change(function (event) {
        obtGraficaValor(Año, Mes, Agente)
    });

    $("#año_dashboard").change(function (event) {
        obtGraficaValor(Año, Mes, Agente)
    });

    $("#sltAgentes").change(function (event) {
        obtGraficaValor(Año, Mes, Agente)
    });

    obtGraficaValor(Año, Mes, Agente)

    // --------------------------FUNCIONES

    function obtGraficaValor(Año, Mes, Agente) {
        if (Agente == 'TODOS') { Agente = 0 } else { Agente = $("#sltAgentes").val() }
        if (Mes == 'TODOS') { Mes = 'TODOS' } else { Mes = $("#mes_dashboard").val() }
        $.ajax({

            url: (base_path + 'api/obt_DasboardsOportunidadesValor/'),
            type: "GET",
            dataType: "json",
            data: {
                Mes: Mes,
                Año: Año,
                Agente: Agente,
            },
            success: function (result) {
                var label = [],
                    Enero = [],
                    Febrero = [],
                    Marzo = [],
                    Abril = [],
                    Mayo = [],
                    Junio = [],
                    Julio = [],
                    Agosto = [],
                    Septiembre = [],
                    Octubre = [],
                    Noviembre = [],
                    Diciembre = [];

                $.each(result, function (ind, elem) {
                    label.push(elem.descEstatus);
                    Enero.push(elem.Enero);
                    Febrero.push(elem.Febrero);
                    Marzo.push(elem.Marzo);
                    Abril.push(elem.Abril);
                    Mayo.push(elem.Mayo);
                    Junio.push(elem.Junio);
                    Julio.push(elem.Julio);
                    Agosto.push(elem.Agosto);
                    Septiembre.push(elem.Septiembre);
                    Octubre.push(elem.Octubre);
                    Noviembre.push(elem.Noviembre);
                    Diciembre.push(elem.Diciembre);
                });
                // console.log('Ganado,' + Ganado + 'Perdido=' + Perdido + 'Abierto?' + Abiertos);


                var areaChartData = {
                    labels: label,
                    datasets: [
                        {
                            label: 'Enero',
                            backgroundColor: '#0069D9',
                            borderColor: '#0069D9',
                            pointRadius: false,
                            pointColor: '#0069D9',
                            pointStrokeColor: '#0069D9',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#0069D9',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Enero
                        },
                        {
                            label: 'Febrero',
                            backgroundColor: '#F8696B',
                            borderColor: '#F8696B',
                            pointRadius: false,
                            pointColor: '#F8696B',
                            pointStrokeColor: '#F8696B',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#F8696B',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Febrero
                        },
                        {
                            label: 'Marzo',
                            backgroundColor: '#F98370',
                            borderColor: '#F98370',
                            pointRadius: false,
                            pointColor: '#F98370',
                            pointStrokeColor: '#F98370',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#F98370',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Marzo
                        },
                        {
                            label: 'Abril',
                            backgroundColor: '#FA9D75',
                            borderColor: '#FA9D75',
                            pointRadius: false,
                            pointColor: '#FA9D75',
                            pointStrokeColor: '#FA9D75',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#FA9D75',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Abril
                        },
                        {
                            label: 'Mayo',
                            backgroundColor: '#FCB77A',
                            borderColor: '#FCB77A',
                            pointRadius: false,
                            pointColor: '#FCB77A',
                            pointStrokeColor: '#FCB77A',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#FCB77A',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Mayo
                        },
                        {
                            label: 'Junio',
                            backgroundColor: '#FDD17F',
                            borderColor: '#FDD17F',
                            pointRadius: false,
                            pointColor: '#FDD17F',
                            pointStrokeColor: '#FDD17F',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#FDD17F',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Junio
                        },
                        {
                            label: 'Julio',
                            backgroundColor: '#E0E383',
                            borderColor: '#E0E383',
                            pointRadius: false,
                            pointColor: '#E0E383',
                            pointStrokeColor: '#E0E383',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#E0E383',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Julio
                        },
                        {
                            label: 'Agosto',
                            backgroundColor: '#C1DA81',
                            borderColor: '#C1DA81',
                            pointRadius: false,
                            pointColor: '#C1DA81',
                            pointStrokeColor: '#C1DA81',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#C1DA81',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Agosto
                        },
                        {
                            label: 'Septiembre',
                            backgroundColor: '#83C77D',
                            borderColor: '#83C77D',
                            pointRadius: false,
                            pointColor: '#83C77D',
                            pointStrokeColor: '#83C77D',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#83C77D',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Septiembre
                        },
                        {
                            label: 'Octubre',
                            backgroundColor: '#63BE7B',
                            borderColor: '#63BE7B',
                            pointRadius: false,
                            pointColor: '#63BE7B',
                            pointStrokeColor: '#63BE7B',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#63BE7B',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Octubre
                        },
                        {
                            label: 'Noviembre',
                            backgroundColor: '#63BE7B',
                            borderColor: '#63BE7B',
                            pointRadius: false,
                            pointColor: '#63BE7B',
                            pointStrokeColor: '#63BE7B',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#63BE7B',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Noviembre
                        },
                        {
                            label: 'Diciembre',
                            backgroundColor: '#63BE7B',
                            borderColor: '#63BE7B',
                            pointRadius: false,
                            pointColor: '#63BE7B',
                            pointStrokeColor: '#63BE7B',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: '#63BE7B',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Diciembre
                        },
                    ]
                };
                // var barChartCanvas = $('#barChart').get(0).getContext('2d')
                // console.log(areaChartData);
                var barChartData = $.extend(true, {}, areaChartData);
                // var temp0 = areaChartData.datasets[0];
                // var temp1 = areaChartData.datasets[1];
                // var temp2 = areaChartData.datasets[2];
                // var temp3 = areaChartData.datasets[3];
                // var temp4 = areaChartData.datasets[4];
                // var temp5 = areaChartData.datasets[5];
                // var temp6 = areaChartData.datasets[6];
                // var temp7 = areaChartData.datasets[7];
                // var temp8 = areaChartData.datasets[8];
                // var temp9 = areaChartData.datasets[9];
                // barChartData.datasets[0] = temp0;
                // barChartData.datasets[1] = temp1;
                // barChartData.datasets[2] = temp2;
                // barChartData.datasets[3] = temp3;
                // barChartData.datasets[4] = temp4;
                // barChartData.datasets[5] = temp5;
                // barChartData.datasets[6] = temp6;
                // barChartData.datasets[7] = temp7;
                // barChartData.datasets[8] = temp8;
                // barChartData.datasets[9] = temp9;
                //---------------------
                //- STACKED BAR CHART -
                //---------------------
                var stackedBarChartCanvas = $('#stackedBarValorChart').get(0).getContext('2d');
                var stackedBarChartData = $.extend(true, {}, barChartData);
                var year = new Date().getFullYear();

                var stackedBarChartOptions = {
                    responsive: true,
                    maintainAspectRatio: true,
                    legend: {
                        display: false,
                        position: 'right',
                        border: [{
                            display: true,
                        }],
                    },
                    tooltips: {
                        enabled: true,
                        titleFontSize: 16,
                        bodyFontSize: 16,
                        backgroundColor: '#1E3A8A',
                        borderColor: '#fff',
                        cornerRadius: 10,
                        boxPadding: 30,
                        borderWidth: 2,
                        displayColors: true,
                        titleMarginBottom: 10,
                        xPadding: 10,
                        yPadding: 10,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var datasets = data.datasets[tooltipItem.datasetIndex];
                                var datasetLabel = datasets.label || '';
                                return datasetLabel + ": $ " + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function (c, i, a) {
                                    return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                                });
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],

                        yAxes: [{

                            stacked: true,
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function (value, index, values) {
                                    var valor_millares = value / 1000000;
                                    if (parseInt(value) >= 1000 ) {
                                        return '$ ' + valor_millares.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ' M';
                                    // } else if (parseInt(value) >= 1000000) {
                                    //     return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "M");
                                    } else {
                                        return '$ ' + value;
                                    }
                                },
                                // stepSize: 10000,
                            }
                        }],
                    },
                    title: {
                        display: true,
                        text: 'OPORTUNIDADES X VALOR  ' + year
                    }
                };

                if (window.graficaValor) {
                    window.graficaValor.clear();
                    window.graficaValor.destroy();
                }

                window.graficaValor = new Chart(stackedBarChartCanvas, {
                    type: 'bar',
                    data: stackedBarChartData,
                    options: stackedBarChartOptions
                });
                loaderOut()
            },
            error: function (r) {
                alert("Ocurrio un error en el grafico Oportunidades Valor ..");
            },
        });
    }


});