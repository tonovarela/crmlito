//=======================================================JCG=========================================//
var nomElementoPCreate = $('#frm-EPcreate #nomElementoP');
var idTipoItemCreate = $('#frm-EPcreate #idTipoItem');
var cant1Create = $('#frm-EPcreate #cant1');
var btnSaveCreate = $('#frm-EPcreate #btnSave');

$(document).ready(function() {
    validarRequeridosEP();
});

var idTipoItemSelec2 = $('#frm-EPcreate #idTipoItem').select2({
    language: "es"
});

nomElementoP.addEventListener('change', function() {
    validarRequeridosEP();
});

idTipoItem.addEventListener('change', function() {
    validarRequeridosEP();
});

cant1.addEventListener('change', function() {
    validarRequeridosEP();
});

idTipoItemSelec2.on('change', function() {
    validarRequeridosEP();
});

function validarRequeridosEP() {

    var idTipoItemSelec2 = $('#frm-EPcreate #idTipoItem').select2("data");

    if (nomElementoP.value == "" || idTipoItem.value == "" || cant1.value == " " || cant1.value == 0) {
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
        $('#frm-EPcreate #idTipoItem').select2().addClass('select2-green')
    } else {
        $('#frm-EPcreate #idTipoItem').select2().addClass('select2-red')
    }

    if (cant1.value == " " || cant1.value == 0) {
        cant1.style.borderColor = "red";
    } else {
        cant1.style.borderColor = "green";
    }

}

//=============================================================================================================================================//