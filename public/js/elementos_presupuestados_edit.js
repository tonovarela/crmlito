idElementoP = $("#idElementoP1").val();
rutaActual = document.location.href;

$(document).ajaxStart(function() {
    loaderON();
});

$(document).ajaxStop(function() {
    loaderOut();
});

//=======================================================JCG=========================================//
var nomElementoPEdit = $('#form-EPEdit #nomElementoP');
var idTipoItemEdit = $('#form-EPEdit #idTipoItem');
var cant1Edit = $('#form-EPEdit #cant1');
var cant1_comEdit = $('#form-EPEdit #cant1_com');
var btnSaveEdit = $('#form-EPEdit #btnSave');
//===================================================================================================//

// sltCombos_edit();
combo_InfGral(idElementoP);
if (rutaActual.indexOf('edit') > -1) {
    tblMedidas(idElementoP);
    tblAcabados(idElementoP);
    // EspeficacionesEdit();
}
//--------- BOTONES
$("#numPresupuestoMTC").change(function() {
    numPresupuestoMTC = $(this).val();
    // addValorOportunidad(numPresupuestoMTC);
});
$("#cant1_com").change(function() {
    $("#cant1").val($(this).val());
    $(this).val(numberWithCommas($(this).val()));
});
$("#cant2_com").change(function() {
    $("#cant2").val($(this).val());
    $(this).val(numberWithCommas($(this).val()));
});
$("#cant3_com").change(function() {
    $("#cant3").val($(this).val());
    $(this).val(numberWithCommas($(this).val()));
});
$("#cant4_com").change(function() {
    $("#cant4").val($(this).val());
    $(this).val(numberWithCommas($(this).val()));
});
//----------FUNCIONES


function tblMedidas(idElementoP) {
    grs = 0;
    $.ajax({
        data: {
            evento: 'tblMedidas',
            idElementoP: idElementoP

        },
        url: (serve + '/gettblelementosp/'),
        type: "GET",
        dataType: "json",
        success: function(r) {
            $(r).each(function(i, v) { // indice, valor
                // console.log(v);
                $.each(v, (index, value) => {
                    // console.log(index);console.log(value);console.log(v.numInterior);
                    switch (index) {
                        case 'idTipoPapel':
                            $('#' + index + '_' + v.numInterior + '-id').val(value);
                            break;
                        case 'TipoPapelgrs':
                            $('#' + index + '_' + v.numInterior + '-grs').val(value);
                            grs = value;
                            break;
                        case 'NombrePapel':
                            if (value != null) { $('#idTipoPapel_' + v.numInterior).val(value + ', ' + grs + 'grs.'); }
                            break;
                        case 'largoExtendido':
                        case 'anchoExtendido':
                        case 'largoFinal':
                        case 'anchoFinal':
                            elemento = parseFloat(value);
                            $('#' + index + '_' + v.numInterior).val(elemento);
                            break;
                        default:
                            $('#' + index + '_' + v.numInterior).val(value);
                    }
                });


            });
        },
        error: function(r) {
            // loaderOut();
            alert("Ocurrio un error en el servidor ..");
        },
    });


}

function tblAcabados(idElementoP) {
    $.ajax({
        data: {
            evento: 'tblAcabados',
            idElementoP: idElementoP

        },
        url: (serve + '/gettblelementosp/'),
        type: "GET",
        dataType: "json",
        success: function(r) {
            $(r).each(function(i, v) { // indice, valor
                // console.log(v);
                $.each(v, (index, value) => {
                    // console.log(index);console.log(value);console.log(v.numInterior);
                    input = $('#' + index + '_' + v.numInterior);
                    switch (true) {
                        case input.is(':checkbox') == true:
                            if (value >= 1) { $('#' + index + '_' + v.numInterior).prop('checked', true); } else { $('#' + index + '_' + v.numInterior).prop('checked', false); }
                            break;
                        case input.is(':text') == true:
                            $('#' + index + '_' + v.numInterior).val(value);
                            break;
                        case input.is('select') == true:
                            $('#' + index + '_' + v.numInterior + '  option[value=' + value + ']').attr('selected', true);
                            break;
                    }
                });


            });
        },
        error: function(r) {
            // loaderOut();
            alert("Ocurrio un error en el servidor ..");
        },
    });


}

function combo_InfGral(idElementoP) {
    var str = ['idTipoEmpaque', 'idTipoAcabadoGral'];
    $.each(str, function(ind, elem) {
        $.ajax({
            data: {
                evento: elem,
                idElementoP: idElementoP,
                numInterior: 0

            },
            url: (serve + '/getCombosInfoGral/'),
            type: "GET",
            dataType: "json",
            success: function(r) {
                $(r).each(function(i, v) { // indice, valor
                    switch (elem) {
                        case 'idTipoEmpaque':
                            for (index = 0; index < r.length; index++) {
                                $('#idTipoEmpaque_' + v.idTipoEmpaque).prop('checked', true);
                            }
                            if (v.obsEmpaque != null && v.obsEmpaque != '') {
                                $("#obsEmpaque").val(v.obsEmpaque);
                            }
                            break;
                        case 'idTipoAcabadoGral':
                            for (index = 0; index < r.length; index++) {
                                $('#idTipoAcabadoGral_' + v.idAcabado).prop('checked', true);
                            }
                            break;

                        default:
                            break;
                    }
                    $("." + elem).append('<option value="' + v.id + '">' + v.Descripcion + "</option>");

                });
                // loaderOut();

            },
            error: function(r) {
                alert("Ocurrio un error en el servidor1 ..");
                // loaderOut();
            },
        });
        // $("#sltGramajes").append('<option value="' + elem + '">' + elem + "grs. </option>").focus();
    });
}

function EspeficacionesEdit() {
    $.ajax({
        data: {

            evento: elem,
            idElementoP: idElementoP,
            numInterior: index

        },
        url: (serve + '/getCombosInfoGral/'),
        type: "GET",
        dataType: "json",
        success: function(r) {
            count++;

            if (r.length > 0) {
                count1++;
                if (count = 34) { loaderOut(); }
                $(r).each(function(iE, vEdit) { // indice, valor
                    for (n = 0; n < r.length; n++) {
                        idElemento = vEdit.id;
                        $('.' + elem + '_' + index + '  option[value=' + idElemento + ']').attr('selected', true);
                    }
                });

            } else {
                if (count = 19) { loaderOut(); }
            }

        },

        error: function(r) {
            // loaderOut();
            alert("Ocurrio un error en el servidor ..");
        },
    });

}

function sltCombosEdit() {

    var str = ['sltBarniz', 'sltTipoBarniz', 'sltLaminado', 'sltTipoLaminado', 'sltTipoGatefold'];
    var count = 0,
        count1 = 0;

    $.each(str, function(ind, elem) {

        // loaderON();
        for (let index = 1; index < 5; index++) {
            $.ajax({
                data: {

                    evento: elem,
                    idElementoP: idElementoP,
                    numInterior: index

                },
                url: (serve + '/getCombosInfoGral/'),
                type: "GET",
                dataType: "json",
                success: function(r) {
                    count++;

                    if (r.length > 0) {
                        count1++;
                        if (count = 34) { loaderOut(); }
                        $(r).each(function(iE, vEdit) { // indice, valor
                            // console.log('count' + count)

                            for (n = 0; n < r.length; n++) {

                                idElemento = vEdit.id;
                                $('.' + elem + '_' + index + '  option[value=' + idElemento + ']').attr('selected', true);
                            }
                        });

                    } else {
                        if (count = 19) { loaderOut(); }
                    }

                },

                error: function(r) {
                    // loaderOut();
                    alert("Ocurrio un error en el servidor ..");
                },
            });
        }
    });
}

// function addValorOportunidad(numPresupuestoMTC) {

//     idElementoP = $("#idElementoP1").val();
//     $.ajax({
//         url: serve + "api/addValorOportunidad",
//         type: "PATCH",
//         data: {
//             _method: "PATCH",
//             idElementoP: idElementoP,
//             numPresupuestoMTC: numPresupuestoMTC,
//             // _token: CSRF_TOKEN,
//         },
//         success: function(data) {
//             const Msg = Swal.mixin({
//                 toast: true,
//                 position: "center",
//                 icon: "warning",
//                 showConfirmButton: false,
//                 timer: 3000,
//             });

//             // Msg.fire({
//             //     title: 'Presupuesto Actualizado',
//             // });
//         },
//         error: function() {
//             const Msg = Swal.mixin({
//                 toast: true,
//                 position: "center",
//                 icon: "warning",
//                 showConfirmButton: false,
//                 timer: 3000,
//             });

//             // Msg.fire({
//             //     title: 'Presupuesto Actualizado',
//             // });
//             // window.location.href = serve + "oportunidades";
//         },
//     });

// }

function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}


$(document).ready(function() {
    // event.preventDefault();
    // loaderOut();
    validarRequeridosEPEdit();


});

//================================================================================================JCG==========================================//

var idTipoItemSelec2 = $('#form-EPEdit #idTipoItem').select2({
    language: "es"
});

nomElementoP.addEventListener('change', function() {
    validarRequeridosEPEdit();
});

idTipoItem.addEventListener('change', function() {
    validarRequeridosEPEdit();
});

cant1.addEventListener('change', function() {
    validarRequeridosEPEdit();
});

cant1_com.addEventListener('change', function() {
    validarRequeridosEPEdit();
});

idTipoItemSelec2.on('change', function() {
    validarRequeridosEPEdit();
});

function validarRequeridosEPEdit() {

    var idTipoItemSelec2 = $('#form-EPEdit #idTipoItem').select2("data");

    if (nomElementoP.value == "" || idTipoItem.value == "" || cant1.value == "" || cant1.value == 0 || cant1_com.value == "" || cant1_com.value == 0) {
        btnSave.disabled = true;
    } else {
        btnSave.disabled = false;
    }

    if (nomElementoP.value == "") {
        nomElementoP.style.borderColor = "red";
    } else {
        nomElementoP.style.borderColor = "green";
    }

    if (idTipoItemSelec2[0].element.value != "") {
        $('#form-EPEdit #idTipoItem').select2().addClass('select2-green')
    } else {
        $('#form-EPEdit #idTipoItem').select2().addClass('select2-red')
    }

    if (cant1.value == "" || cant1.value == 0) {
        cant1.style.borderColor = "red";
    } else {
        cant1.style.borderColor = "green";
    }

    if (cant1_com.value == "" || cant1_com.value == 0) {
        cant1_com.style.borderColor = "red";
    } else {
        cant1_com.style.borderColor = "green";
    }

}

//=============================================================================================================================================//

$(function() {
    $("#form-EPEdit").validator().on("submit", function(e) {
        if (!e.isDefaultPrevented()) {
            mensaje = "Elemento Presupuestado Actualizado";
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

        Msg.fire({
            title: mensaje,
        });
    });
});