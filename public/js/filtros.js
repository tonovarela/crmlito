var countFilter = 1;
nametable = $("#nametable").val();
dataTable = $("#" + nametable).DataTable();
filtros();

$(document).ajaxStart(function() {
    loaderON();
});

$(document).ajaxStop(function() {
    loaderOut();
});

// /------------------------------------------------------> Buscador general <-----------------
$("#searchGral").keypress(function(e) {
    if (e.which == 13) { // loaderON();
        var texto = $(this).val();
        filterGlobal(texto.trim());
    }
});
$("#btn-search").click(function(e) { // loaderON();
    var texto = $("#searchGral").val();
    filterGlobal(texto.trim());
});
// Barra de buscar general
// /------------------------------------------------------> Filtros <------------------------------
$("#btn-filters").click(function() {
    var evento = "0";
    if ($("#card-filtros").hasClass("filter-hide")) {
        combo_Filtros(evento);
        $(this).attr("title", "Ocultar Filtros");
        $("#card-filtros").removeClass("filter-hide").addClass("filter-show");
        $(".searchCliente_0").focus();
    } else {
        clearFilters();
        $(this).attr("title", "Mostrar Filtros");
        $("#card-filtros").removeClass("filter-show").addClass("filter-hide");
    }
});
$(".btn-addF").click(function(e) {
    var noselect = $(this).attr("data-idslt");
    addFiltro(noselect);
});

$(".btn-refresh").click(function() {
    clearFilters();
});
// Limpiar tabla

// -------------------------------------------------------- FUNCIONES
function filterGlobal(texto) { // loaderON();
    $("#" + nametable).DataTable().search("").columns().search("").draw();
    $("#" + nametable).DataTable().search(texto.trim(), false, true).draw();
}

function filterColumn(i, texto) { 
    $("#" + nametable).DataTable().search("").columns().search("").draw();
    $("#" + nametable).DataTable().column(i).search(texto.trim(), false, true).draw();
    if (evento != '') { // loaderON();
        filterDate(1, minDate, maxDate);
    }
    loaderOut()
}

function filterDate(i, minDate, maxDate) {

    // console.log('evento1' + evento)
    // loaderON();
    $("#" + nametable).DataTable().column(i).search(minDate, false, true).draw();

}

function filterGlobalCards(i, texto) {
    loaderON();
    $("#" + nametable).DataTable().search("").columns().search("").draw();
    $("#" + nametable).DataTable().column(i).search(texto.trim(), false, true).draw();
    loaderOut()
}

$("#" + nametable).DataTable().on('draw', function() {
    // loaderOut()
    // console.log('Redraw occurred at: ' + new Date().getTime());
});

function filtros() {
    $(".slt_filtro").change(function() {
        var texto = "",
            columna = $(this).find(":selected").attr("data-columns"),
            noselect = $(this).attr("data-idslt");
        $(".searchCliente_" + noselect).attr("id", "col" + columna + "_filter");
        $(".searchCliente_" + noselect).attr("data-columns", columna);
        $(".Dslt_" + noselect).attr("id", "Del" + columna + "_filter");
        $(".Sslt_" + noselect).attr("id", "Sav" + columna + "_filter");
        $(".Sslt_" + noselect).attr("data-columns", columna);
        texto = $(".searchCliente_" + noselect).val();
        keyup();
    });

    $(".btn-deleteF").click(function(e) {
        var noselect = $(this).attr("data-idslt");
        $("#slt-filtro" + noselect + "").remove();
    });
}

function clearFilters() {
    for (var i = 1; i <= countFilter; i++) {
        $("#slt-filtro" + i).remove();
    }
    $("input").val("");
    $(".searchCliente_0").focus();
    $("#" + nametable).DataTable().search("").columns().search("").draw();
}

function combo_Filtros(evento) {
    var columns = $("#" + nametable).DataTable().settings().init().columns,
        option = "",
        nocolum = 0;
    $("#" + nametable).DataTable().columns().every(function(index) {
        option = '<option value="' + columns[index].name + '" data-columns="' + nocolum + '">' + columns[index].title + "</option>";
        if (evento == "0") {
            $("#slt-filtro").append(option);
        } else {
            $("#slt_filtro" + evento).append(option);
        }
        nocolum++;
    });
}

function addFiltro(noselect) {
    var evento = countFilter;

    var Select = '<div class="input-group-prepend"> <select class="card-primary form-control  select2 slt_filtro"  data-placeholder="Filtro" style="width: 100%;" data-idslt=' + countFilter + ' id="slt_filtro' + countFilter + '">' + '<option selected="selected">Elige un Filtro</option>' + "</select> </div>";
    var input = '<input type="text" class="column_filter form-control searchCliente_' + countFilter + '" placeholder="Buscar ...">';
    var buttonSch = '<span class="input-group-append"><button type="button" class="btn btn-primary btn-flat btn-saveF btn_SearchColumn Sslt_' + countFilter + '"  title="Buscar" data-idslt=' + countFilter + '> <i class="nav-icon fas fa-search"></i></button></span>';
    var buttonDel = '<span class="input-group-append"><button type="button"  class="btn btn-danger btn-flat btn-deleteF Sslt_' + countFilter + '"  title="Eliminar Filtro" data-idslt=' + countFilter + '>  <i class="nav-icon fas fa-minus"></i></button></span>';
    var buttonSav = '<span class="input-group-append"><button type="button" class="btn btn-primary btn-flat btn-saveF Sslt_' + countFilter + '"  title="Guardar Filtro" data-idslt=' + countFilter + ' hidden> <i class="nav-icon fas fa-save"></i></button></span>';
    var div = '<div class="row justify-content-md-center fil slt"  id="slt-filtro' + countFilter + '"> <div class="col-sm-9 input-group input-group-sm justify-content-md-left">' + Select + input + buttonSch + buttonDel + buttonSav + "</div></div>";
    $("#box-filtros").append(div);
    $(".searchCliente_" + countFilter).focus();
    countFilter = countFilter + 1;
    combo_Filtros(evento);
    keyup();
    filtros();
}

function keyup() {
    $("input.column_filter").keypress(function(e) {
        if (e.which == 13) { // loaderON();
            var i = $(this).attr("data-columns"),
                texto = $("#col" + i + "_filter").val();
            i >= 0 ? filterColumn(i, texto) : toast("Debes selecionar una filtro", "warning");
        }
    });
    // $('input.column_filter').on('keyup click', function() //Buscar por columna
    $("button.btn_SearchColumn").click(function() { // loaderON();
        var i = $(this).attr("data-columns"),
            texto = $("#col" + i + "_filter").val();
        i >= 0 ? filterColumn(i, texto) : toast("Debes selecionar una filtro", "warning");
    });
}