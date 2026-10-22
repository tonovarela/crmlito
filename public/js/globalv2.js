$(function() {
    $('[data-bs-toggle="tooltip"]').tooltip({
        placement: 'auto',
        delay: { "show": 0, "hide": 0 },
        fallbackPlacement: 'flip',
        boundary: 'scrollParent',
        trigger: 'hover',
    });
})


//------------FUNCIONES-------------------//
function loaderON() {
    // console.log('on')
    // $.LoadingOverlay("show");
    $(".loanding").fadeIn("slow");
}

function loaderOut() {
    // console.log('out')
    // $.LoadingOverlay("hide");
    $(".loanding").fadeOut("slow");
}
//---- BOTONES DE REGRESAR--//BIGG
function regresar() {
    loaderON();
    window.history.go(-1);
}

function regresarOportunidades() {
    // window.location.reload();
    loaderON();
    // Obtiene la ruta actual
    var currentUrl = window.location.href;

    // Verifica si hay un referrer y si es diferente de la ruta actual
    if (document.referrer && document.referrer !== currentUrl) {
        window.location.href = document.referrer;
    } else {
        window.location.href = "https://servicios.litoprocess.com/crmlito/oportunidades";
    }
}

function loaderModalON() {
    $(".loandingModal").fadeIn("slow");
}

function loaderModalOut() {
    $(".loandingModal").fadeOut("slow");
}

$(document).ready(function() {
    var body = document.body;
    $(window).resize(function() {
        body.classList.add("sidebar-collapse");
    });

    $('input[autofill="off"]').disableAutofill();

    $(document).tooltip({
        position: {
            my: "center bottom-20",
            at: "center top",
            using: function(position, feedback) {
                $(this).css(position);
                $("<div>")
                    .addClass("arrow")
                    .addClass(feedback.vertical)
                    .addClass(feedback.horizontal)
                    .appendTo(this);
            }
        }
    });

});