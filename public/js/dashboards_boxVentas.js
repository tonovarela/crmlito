var Agente = 'TODOS';

$(document).ready(function() {

    Agente = $("#sltAgentes").val();
    obtBox(Agente)
    $("#sltAgentes").change(function(event) {
        Agente = $("#sltAgentes").val();
        obtBox(Agente)
    });


});

$(document).ajaxSend(function() {
    // loaderON();
})

function obtBox(Agente) {
    $("#clientes").text('');
    $("#hit").text('');
    $("#oportunidades").text('');
    $("#cumplimiento").text('');
    $("#meta").text('');
    $("#venta").text('');
    if (Agente == 'TODOS') { Agente = 0 } else { Agente = $("#sltAgentes").val() }
    // alert(Agente)
    $.ajax({
        data: {
            Agente: Agente
        },
        url: (base_path + 'api/obt_DasboardsboxVentas'),
        type: "GET",
        dataType: "json",
        success: function(result) {
            // console.log(result)
            $.each(result, function(ind, elem) {
                ven = parseFloat(elem.VentaMes).toFixed(2)
                ven = ven.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                met = parseFloat(elem.MetaMes).toFixed(2)
                met = met.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $("#clientes").text(elem.ClientesNvos);
                $("#hit").text(parseFloat(elem.HitRate).toFixed(2));
                $("#oportunidades").text(elem.TotalMes);
                $("#cumplimiento").text(parseFloat(elem.Cumplimiento).toFixed(2));
                $("#venta").text('$ ' + ven);
                $("#meta").text('$ ' + met);

            });
        }
    })

}