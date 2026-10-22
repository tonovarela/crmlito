// $(document).ajaxStart(function() {
//     loaderON();
// });

$(function() {
    // var areaChartData = '';

    // let sem = setInterval(autoReloadSemana, 10000);

    // function autoReloadSemana() {
    //     obtGraficaSemana();
    // }

    Año = $('#año_dashboard').val();
    Mes = $('#mes_dashboard').val();

    $("#mes_dashboard").change(function(event) {
        Año = $('#año_dashboard').val();
        obtGraficaSemana(Año)
    });

    $("#año_dashboard").change(function(event) {
        Año = $('#año_dashboard').val();
        obtGraficaSemana(Año)
    });

    obtGraficaSemana(Año);

    // --------------------------FUNCIONES

    function obtGraficaSemana(Año) {
        $.ajax({

            url: (base_path + 'api/obt_DasboardsGraficaBar/'),
            type: "GET",
            dataType: "json",
            data: {
                Año: Año
            },
            success: function(result) {
                var label = [],
                    Ganado = [],
                    Perdido = [],
                    Abiertos = [];

                $.each(result, function(ind, elem) {
                    label.push('S-' + elem.Semana);
                    Ganado.push(elem.Ganado);
                    Perdido.push(elem.Perdido);
                    Abiertos.push(elem.Pendientes);
                });
                // console.log('Ganado,' + Ganado + 'Perdido=' + Perdido + 'Abierto?' + Abiertos);


                var areaChartData = {
                    labels: label,
                    datasets: [{
                            label: 'Ganados',
                            backgroundColor: 'rgba(40,168,69,1)',
                            borderColor: 'rgba(40,168,69,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Ganado
                        },
                        {
                            label: 'Perdidos',
                            backgroundColor: 'rgba(220,53,69,1)',
                            borderColor: 'rgba(220,53,69,1)',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Perdido
                        },
                        {
                            label: 'Abiertos',
                            backgroundColor: 'rgba(23,162,184,1)',
                            borderColor: 'rgba(23,162,184,1)',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            borderWidth: 2,
                            borderRadius: 10,
                            borderSkipped: false,
                            data: Abiertos
                        },
                    ]
                };
                // var barChartCanvas = $('#barChart').get(0).getContext('2d')
                // console.log(areaChartData);
                var barChartData = $.extend(true, {}, areaChartData);
                var temp0 = areaChartData.datasets[0];
                var temp1 = areaChartData.datasets[1];
                barChartData.datasets[0] = temp1;
                barChartData.datasets[1] = temp0;
                //---------------------
                //- STACKED BAR CHART -
                //---------------------
                var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
                var stackedBarChartData = $.extend(true, {}, barChartData);

                var stackedBarChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
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
                                    // callbacks: {
                        //     label: function(tooltipItem, data) {
                        //         return "$" + Number(tooltipItem.yLabel).toFixed(0).replace(/./g, function(c, i, a) {
                        //             return i > 0 && c !== "." && (a.length - i) % 3 === 0 ? "," + c : c;
                        //         });
                        //     }
                        // }
                    },
                    scales: {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true,
                        }]
                    }
                };

                if (window.grafica) {
                    window.grafica.clear();
                    window.grafica.destroy();
                }

                window.grafica = new Chart(stackedBarChartCanvas, {
                    type: 'bar',
                    data: stackedBarChartData,
                    options: stackedBarChartOptions
                });
                loaderOut()
            },
            error: function(r) {
                alert("Ocurrio un error en el grafico Semanas ..");
            },
        });
    }


});