// $(document).ajaxStart(function() {
//     loaderON();
// });

$(function() {

    var label = [],
        Porcentaje = [],
        Color = [];

    obtGraficaSemana();

    function obtGraficaSemana() {
        $.ajax({

            url: (base_path + 'api/obt_DasboardsGraficaPie/'),
            type: "GET",
            dataType: "json",
            success: function(result) {


                $.each(result, function(ind, elem) {
                    label.push(elem.descEstatus);
                    Porcentaje.push(elem.Porcentaje);
                    Color.push(elem.colorEstatus);
                    // Etiquetas
                    $("#pie-label").append("<li><i class='far fa-circle' style='color:" + elem.colorEstatus + "'></i> " + elem.descEstatus + "</li>");
                });
                GraficaPie(label, Porcentaje, Color);




            },
            error: function(r) {
                alert("Ocurrio un error en el servidor ..");
            },
        });
    }

    function GraficaPie(label, Porcentaje, Color) {

        var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
        var pieData = {
            labels: label,
            datasets: [{
                label: '%',
                data: Porcentaje,
                backgroundColor: Color
            }]
        };
        var pieOptions = {
            legend: {
                display: false
            },
            tooltips: {
                enabled: true
            },
            plugins: {
                datalabels: {
                    color: '#111',
                    textAlign: 'center',
                    font: {
                        lineHeight: 1.6
                    },
                    formatter: function(value, pieChartCanvas) {
                        return pieChartCanvas.chart.data.labels[pieChartCanvas.dataIndex] + '\n' + value + '%';
                    }
                }
            }
        };
        var pieChart = new Chart(pieChartCanvas, {
            type: 'doughnut',
            data: pieData,
            options: pieOptions
        });


        // loaderOut()
    }
});