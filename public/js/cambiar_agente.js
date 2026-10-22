agente = [];

$.ajax({
    url: "getAgentes",
    type: "GET",
    dataType: "json",
    success: function(r) {

        $(r).each(function(i, v) {
            // indice, valor
            agente.push(v.nombre);
        });
    },
    error: function(r) {
        Swal.fire("No se cargaron los Agentes...");
    },
});

cotizador = [];

$.ajax({
    url: "getCotizadores",
    type: "GET",
    dataType: "json",
    success: function(r) {

        $(r).each(function(i, v) {
            // indice, valor
            cotizador.push(v.Nombre);
        });
    },
    error: function(r) {
        Swal.fire("No se cargaron los Cotizadores...");
    },
});

revisa = [];

$.ajax({
    url: "getRevisa",
    type: "GET",
    dataType: "json",
    success: function(r) {

        $(r).each(function(i, v) {
            // indice, valor
            revisa.push(v.nombreRevisa);
        });
    },
    error: function(r) {
        Swal.fire("No se cargaron los Revisadores...");
    },
});

rol = [];

$.ajax({
    url: "getRoles",
    type: "GET",
    dataType: "json",
    success: function(r) {

        $(r).each(function(i, v) {
            // indice, valor
            rol.push(v.descRol);
        });
    },
    error: function(r) {
        Swal.fire("No se cargaron los Roles...");
    },
});
