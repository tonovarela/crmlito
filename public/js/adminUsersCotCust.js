var idUser
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

/**
 * Configuramos ajax para funcionamiento optimo
 * 
 */
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    }
});

/**
 * En cuanto cargue la pantalla
 * 
 */
window.onload = () => {
    // tabla de cotizadores
    tblUsuariosCot = $("#tblUsuariosCot").DataTable({
        destroy: true,
        processing: true,
        pageLength: 50,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        autoWidth: true,
        ordering: false,
        columnDefs: [
            {
                targets: ["_all"],
                className: "text-bold"
            },
            {
                className: "dt-center", 
                targets: "_all"
            }
        ],
        ajax: {
            url: serve + "apiGetCotizadores",
            type: "GET",
            dataType: "json"
        },
        columns: [
            {
                data: "Nombre",
                name: "Nombre",
                title: "Usuario"
            },
            {
                data: "active",
                name: "active",
                title: "Estatus"
            },
            {
                data: 'accion',
                name: 'accion',
                title: "Activar o desactivar",
                render: function(data, type, row) {
                    if (row.active == "Activo") {
                        $('#checkUser'+row.Id_Usuario).addClass("btn-deactive");
                    }
                    return data;
                }
            },
        ],
        language: {
            url: serve + "js/spanish-DT.json"
        },
        dom: '<"top">rt<"bottom"><"clear">',
        select: true,

        initComplete: function (settings, json) {
            loaderOut();
        }
    });

    // tabla de customers
    tblUsuariosCus = $("#tblUsuariosCus").DataTable({
        destroy: true,
        processing: true,
        pageLength: 50,
        scrollY: false,
        scrollX: true,
        serverSide: true,
        autoWidth: true,
        ordering: false,
        columnDefs: [
            {
                targets: ["_all"],
                className: "text-bold"
            },
            {
                className: "dt-center", 
                targets: "_all"
            }
        ],
        ajax: {
            url: serve + "apiGetCustomers",
            type: "GET",
            dataType: "json"
        },
        columns: [
            {
                data: "Nombre",
                name: "Nombre",
                title: "Usuario"
            },
            {
                data: "active",
                name: "active",
                title: "Estatus"
            },
            {
                data: 'accion',
                name: 'accion',
                title: "Activar o desactivar",
                render: function(data, type, row) {
                    if (row.active == "Activo") {
                        $('#checkUser'+row.Id_Usuario).addClass("btn-deactive");
                    }
                    return data;
                }
            },
        ],
        language: {
            url: serve + "js/spanish-DT.json"
        },
        dom: '<"top">rt<"bottom"><"clear">',
        select: true,

        initComplete: function (settings, json) {
            loaderOut();
        }
    });
}

/**
 * Función para desactivar o activar a un usuario
 * 
 * @param idUser
 * @param typeAction => tipo de cambio 1 activar, 0 desactivar
 * @param type => tipo de cambio 1 cotizador, 2 customer
 */
function activateUser(idUser, typeAction = 1, type = 1)
{
    console.log(typeAction);
    Swal.fire({
        title: (typeAction == 1 ? "¿Estas seguro de volver activar a este usuario?" : "¿Estas seguro de desactivar a este usuario?"),
        text: (typeAction == 1 ? "Una vez que se active podrá volver a recibir oportunidades" : "Una vez que se desactive no se le podrán asignar oportunidades"),
        icon: (typeAction == 1 ? 'info': "warning"),
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        cancelButtonText: 'Cancelar',
        confirmButtonText: (typeAction == 1 ? "Si, activarlo" : "Si, desactivarlo")
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: serve + 'changeActiveUser',
                type: "POST",
                data: {
                    _method: "POST",
                    _token: CSRF_TOKEN,
                    id_user: idUser,
                    type: type,
                    typeAction: typeAction,
                },
                success: function () {
                    let msg = (typeAction == 1) ? 'Se activo el usuario' : 'Se desactivo el usuario'
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                      });
                      Toast.fire({
                        icon: "success",
                        title: msg
                      });

                      if (type == 1) {
                            tblUsuariosCot.ajax.reload();
                      } else {
                            tblUsuariosCus.ajax.reload();
                      }
                },
            })
        } else {
            if (type == 1) {
                tblUsuariosCot.ajax.reload();
            } else {
                tblUsuariosCus.ajax.reload();
            }
            loaderOut();
        }
    });
}