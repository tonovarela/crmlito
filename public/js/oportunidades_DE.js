// rutaActual = document.location.href;
var serve = base_path;
var tblDetalleAsignadosOport = '',
    tblTickeDERelation = '',
    height = window.innerHeight,
    eventoTblDE = '',
    ObjDE = {};
DErelations = [];
ObjDE.DErelations1 = [];
$("#cancelar").prop("disabled", false);

// if (rutaActual.indexOf('edit') > -1) {
//     eventoTblDE = $("#idOportunidad").val();
//     tblIndexDE(serve, eventoTblDE);
// } else {
//     eventoTblDE = 0;
//     tblIndexDE(serve, eventoTblDE);
// }
//---------------------------------B O T O N E S------------------------------///

$("#btnRelacionarDE").click(function() {
    countCheckboxDE = $('input:checkbox:checked').length;
    idOportunidadDE = $("#idOportunidad").val();
    $(".RelCheckDE:checkbox:checked").each(function() {
        // console.log($(this));
        idDE = ($(this).attr("value"));
        ObjDE.DErelations1.push({
            "idDE": idDE,
            "idOportunidadDE": idOportunidadDE
        });
    });
    AddRalationDE(ObjDE);
    // console.log(ObjDE);

});

///----------------------------------FUNCIONES--------------------------------///

function OpenModalDE() {
    idCliente = $('#idCliente').val();
    // console.log(idCliente);
    nametableDE = 'tblTickeDERelation';
    $('#mod_DE_relation').modal('show');
    tblDE_relation(serve, idCliente);
}

function AddRalationDE(DErelations) {
    mensaje = "Relación Exitosa";

    $.ajax({
        url: serve + "DErelation",
        type: "PATCH",
        data: {
            // _method: "PATCH",
            DErelations: DErelations,
            _token: CSRF_TOKEN,
        },
        success: function(data) {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: mensaje,
            });
            tblDetalleAsignadosOport.ajax.reload();
            $('#mod_DE_relation').modal('hide');;
        },
        error: function() {
            const Msg = Swal.mixin({
                toast: true,
                position: "center",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000,
            });

            Msg.fire({
                title: "Ocurrió un error al Relacionar el elemento",
            });
        },
    });

}