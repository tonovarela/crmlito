estatus_estr = [];

$.ajax({
    url: serve + "getEstatusESTR",
    type: "GET",
    dataType: "json",
    success: function(r) {
        $(r).each(function(i, v) {
            // indice, valor
            estatus_estr.push(v.Descripcion);
        });
    },
    error: function(r) {
        Swal.fire("No se cargaron los Estatus...");
    },
});

operador_estr = [];

$.ajax({
    url: serve + "getOperadorESTR",
    type: "GET",
    dataType: "json",
    success: function(r) {
        $(r).each(function(i, v) {
            // indice, valor
            operador_estr.push(v.Nombre);
        });
    },
    error: function(r) {
        Swal.fire("No se cargaron los Operadores...");
    },
});
