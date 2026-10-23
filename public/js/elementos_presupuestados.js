if ($("#esNuevo").val() == 'on') { $("#esNuevo").val(1); }
if ($("#conFullfilment").val() == 'on') { $("#conFullfilment").val(1); }
if ($("#conRetoque").val() == 'on') { $("#conRetoque").val(1); }
rutaActual = document.location.href;

var serve = base_path;
var tblElementosPresupuestados = '',
    height = window.innerHeight,
    eventoTbl = '';

// if (rutaActual.indexOf('edit') > -1) {
//     idElementoP = $("#idElementoP1").val();
//     eventoTbl = idElementoP;
//     tblIndex(serve, eventoTbl);

// } else { eventoTbl = 0;
//     tblIndex(serve, eventoTbl); }
$("#tab_2").click(function() {
    // loaderON();
    sltCombos(rutaActual);

});
$("#tab_3").click(function() {
    loaderON();
    if (rutaActual.indexOf('edit') > -1) { obtNotas(); } else { loaderOut(); }

});

loaderOut();

//----------FUNCIONES



function sltCombos(rutaActual) {
    // loaderON();

    var str = ['sltBarniz', 'sltTipoBarniz', 'sltLaminado', 'sltTipoLaminado', 'sltTipoGatefold'];
    var count = 0;
    $.each(str, function(ind, elem) { // console.log('¡Hola :' + elem + '!');

        for (let index = 1; index < 5; index++) {
            if ($("." + elem + "_" + index).length < 2) {
                $("." + elem + "_" + index).empty();
                $("." + elem + "_" + index).append("<option value='0'>Seleccionar...</option>");
                $.ajax({
                    data: {
                        evento: elem
                    },
                    url: (serve + 'getCombos/'),
                    type: "GET",
                    dataType: "json",
                    success: function(r) {
                        loaderON()
                        $(r).each(function(i, v) { // indice, valor
                            if (count >= 111) {
                                if (rutaActual.indexOf('edit') > -1) { sltCombosEdit(); } else { loaderOut(); }

                            }

                            $("." + elem + "_" + index).append('<option value="' + v.id + '">' + v.Descripcion + "</option>");
                            count++;

                        });

                        // }



                    },
                    error: function(r) {
                        loaderOut();
                        alert("Ocurrio un error en el servidor ..");
                    },
                });
            } else { loaderOut(); }

        }


    });

}




function OpenModal(data) {
    $("#nameColumn").val(data);
    $(".modPapel :input").val('');
    $('#mod_TipoPaper').modal('show');
    $('.sltPapel').val(0).trigger('change'); //lo setea
}

function ActualizarFacturado(id) {
    // console.log(id);
}

function ActualizarSincronizado(id) {
    // console.log(id);
}
//---- DOCUMENTE READY

$(document).ready(function(event) {
    CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
    // event.preventDefault();
    loaderOut();

    if (rutaActual.indexOf('edit') > -1) {
        // idElementoP = $("#idElementoP1").val();
        // eventoTbl = idElementoP;
        // tblIndex(serve, eventoTbl);

    } else if (rutaActual.indexOf('create') > -1) {
        // idElementoP = $("#idElementoP1").val();
        // eventoTbl = idElementoP;
        // tblIndex(serve, eventoTbl);

    } else {
        eventoTbl = 0;
        tblIndex(serve, eventoTbl);
    }
    $(".sltClasificacion").change(function(event) {
        // console.log(event.target.value);
        id = event.target.value;
        slt = $("#sltCodigo");
        var evento = 'Clasificacion';
        sltCatalogoPapel(id, evento, slt);
    });
    $("#sltCodigo").change(function(event) {

        // console.log(event.target.value);
        id = event.target.value;
        slt = $("#sltGramaje");
        var evento = 'codigo';
        sltCatalogoPapel(id, evento, slt);
    });

    $("#btnAddSustrato").click(function() {
        var input = $("#nameColumn").val(),
            Descripcion = $("#EPDescripcion").val(),
            Gramaje = $('#sltGramajes').val(),
            idSustrato = $("#idSustrato").val();
        $('#mod_TipoPaper').modal('hide');
        // $("#" + input + "_grs").val(Descripcion + ", " + Gramaje + "grs.");
        $("#" + input).val(Descripcion + ", " + Gramaje + "grs.");
        $("#" + input).attr("data-gr", Gramaje);
        $("#" + input + '-id').val(idSustrato);
        $("#" + input + '-grs').val(Gramaje);

    });
    //----- FUNCIONES -----------------

    function sltCatalogoPapel(id, evento, slt) {
        // console.log('event=' + evento);
        if (id != "") {
            $.ajax({
                data: {
                    evento: evento,
                    id: id
                },
                url: (serve + 'getCatalogoPapel/'),
                type: "GET",
                dataType: "json",
                //beforeSend: function() {
                // equipos.prop("disabled", true);
                //},
                success: function(r) {
                    slt.find("option").remove();
                    slt.append("<option value='0'  selected='selected ' >Seleccionar...</option>");
                    $(r).each(function(i, v) { // indice, valor
                        slt.append('<option value="' + v.Elemento + '">' + v.Elemento + "</option>").focus();
                        if (evento == 'codigo') {
                            $("#EPDescripcion").val(v.descripcion);
                            var str = v.gramajes;
                            var temp = new Array();
                            temp = str.split(";");
                            $.each(temp, function(ind, elem) { // console.log('¡Hola :' + elem + '!');
                                $("#sltGramajes").append('<option value="' + elem + '">' + elem + "grs. </option>").focus();
                            });
                            // alert(v.idSustrato);
                            $("#idSustrato").val(parseInt(v.idSustrato));
                        }
                    });
                    slt.focus();

                },
                error: function(r) {
                    alert("Ocurrio un error en el servidor ..");
                },
            });
        } else {
            // agente.find("option").remove();
            // agente.prop("disabled", true);
        }
    }



});
$(window).resize(function() {
    height = window.innerHeight;
    ajustarTamaño(height);
});

function ajustarTamaño(height) {
    if (height >= 320 && height <= 651) {
        $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "68vh");

    } else if (height >= 652 && height <= 768) {
        $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "68vh");
    } else if (height >= 769 && height <= 1024) {
        $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "77vh");
    } else if (height >= 1025 && height <= 1366) {
        $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "80vh");
    } else if (height >= 1440) { $("#tblElementosPresupuestados").closest(".dataTables_scrollBody").css("height", "82vh"); }
    // tblElementosPresupuestados.draw();

}
$(function() {
    $("#frm-EPcreate form").validator().on("submit", function(e) {
        if (!e.isDefaultPrevented()) {
            mensaje = "Elemento Presupuestado Creado";
            icono = "success";
        } else {
            mensaje = "Faltan Datos";
            icono = "warning";
        }
        const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: icono,
            showConfirmButton: false,
            timer: 3000,
        });
    });
    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd-mm-yyyy", {
        placeholder: "dd-mm-yyyy",
    });
    //Datemask2 mm/dd/yyyy
    $("#datemask2").inputmask("mm-dd-yyyy", {
        placeholder: "mm-dd-yyyy",
    });
    //Money Euro
    $("[data-mask]").inputmask("dd-mm-yyyy", {
        placeholder: "dd-mm-yyyy",
    });

    // $("#reservationdate4").datetimepicker({
    //     format: "DD-MM-YYYY",
    // });

    // $("#reservationdatetime5").datetimepicker({
    //     icons: {
    //         time: "far fa-clock",
    //     },
    //     format: "DD-MM-YYYY hh:mm A",
    // });

});

function limitDecimalPlaces(e, count) {
    if (e.target.value.indexOf('.') == -1) { return; }
    if ((e.target.value.length - e.target.value.indexOf('.')) > count) {
        e.target.value = parseFloat(e.target.value).toFixed(count);
    }
}

function clonarElemento(idElemento) {
  
    Swal.fire({
      title: "Elemento Presupuestado",
      html: "<div>¿Clonar este Elemento?</div><br><div class='text-warning'><i class='fas fa-exclamation-triangle'> </i> Se creará con un folio nuevo y <br> tendrás que asociarlo con la Oportunidad.</div>",
      // icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#999",
      confirmButtonText: "Si",
      cancelButtonText: "No",
      allowEnterKey: false,
      stopKeydownPropagation: true,
      reverseButtons: true,
      returnFocus: true,
    }).then((result) => {
      if (result.isConfirmed) {
        loaderON();
        $.ajax({
          url: serve + "clonarElemento",
          type: "POST",
          data: {
            idElemento: idElemento,
            _method: "POST",
            _token: CSRF_TOKEN,
          },
          success: function () {
            const Msg = Swal.mixin({
              toast: true,
              position: "center",
              icon: 'success',
              showConfirmButton: false,
              timer: 3000,
            });
            Msg.fire({
              title: 'Elemento Clonado',
            });
            window.location.href = serve + "elementos";
        },
          error: function () {},
        });
      } else {
        loaderOut();
      }
    });
  }