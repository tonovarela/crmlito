$("#saveNote").click(function(event) {
    event.preventDefault();
    AddNota();
});
// ---------------------------------F U N C I O N E S------------------------
function obtNotas() {
    idElementoP = $("#idElementoP1").val();
    $.ajax({
        data: { idElementoP: idElementoP },
        url: (serve + 'obtNotas/'),
        type: "GET",
        dataType: "json",
        success: function(r) {
            if (r.length > 0) {
                $(r).each(function(iE, vEdit) { // indice, valor
                    loaderOut();
                    $(".card-notes")
                        .append(
                            // ' <div class="callout callout-danger">' +

                            '<div class="row col-lg-12 col-sm-12 mb-1"><div class="user-block user-block-notes col-md-5 col-lg-3 col-xl-2">' +
                            // '<img class="img-circle img-bordered-sm"  alt=" User Image " src="../../img/user.png">' +
                            ' <span class="username user-notes ">' +
                            '<a class="a-notes" id="usuario-notes">' + vEdit.Nombre + '</a>' +
                            '</span>' +
                            '<span class="description date-notes" id="fecha-notes"> ' + vEdit.FechaRegistro + '</span>' +
                            ' </div>' +

                            '<div class="col-md-5 col-lg-3 col-xl-10"><textarea  type="text" id="description-notes" class="form-control" readonly>' + vEdit.Nota + '</textarea ></div></div>'

                            //+ '</div>'
                        )
                });

            } else {
                loaderOut();
            }

        },

        error: function(r) {
            loaderOut();
            alert("Ocurrio un error en el servidor ..");
        },
    });

}

function AddNota() {
    loaderON();
    Nota = $("#Nota").val()
    mensaje = 'Nota agregada';
    idElementoP = $("#idElementoP1").val();
    idElementoP = parseInt(idElementoP);

    $.ajax({
        url: serve + "Addnotas/",
        type: "PATCH",
        data: {
            _method: "PATCH",
            Nota: Nota,
            idElemento: idElementoP,
            _token: CSRF_TOKEN,
        },
        success: function(data) {
            // console.log(data);
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
            loaderOut();
            $(".card-notes")
                .append(
                    // ' <div class="callout callout-danger">' +

                    '<div class="row col-lg-12 col-sm-12 mb-1"><div class="user-block user-block-notes col-md-5 col-lg-3 col-xl-2">' +
                    // '<img class="img-circle img-bordered-sm"  alt=" User Image " src="../../img/user.png">' +
                    '<span class="username user-notes ">' +
                    '<a class="a-notes" id="usuario-notes">' + data[0].Nombre + '</a>' +
                    '</span>' +
                    '<span class="description date-notes" id="fecha-notes">' + data[0].FechaRegistro + '</span>' +
                    ' </div>' +

                    '<div class="col-md-5 col-lg-3 col-xl-10"><textarea  type="text" id="description-notes" class="form-control"  readonly>' + data[0].Nota + '</textarea ></div></div>'

                    // '</div>'
                )

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
                title: "Ocurrió un error",
            });
            loaderOut();
        },
    });

}