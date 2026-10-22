var countFilter2 = 1;
nametable2 = $("#nametable2").val();
dataTable2 = $("#" + nametable2).DataTable();
filtros2();


///------------------------------------------------------> Buscador general <-----------------
$("#searchGral2").keypress(function(e) {
    if (e.which == 13) {
        loaderON();
        $("#" + nametable2).DataTable().search("").columns().search("").draw();
        var texto = $(this).val();
        filterGlobal2(texto);
    }
});
$("#btn-search-2").click(function(e) {
    loaderON();
    $("#" + nametable2).DataTable().search("").columns().search("").draw();
    var texto = $("#searchGral2").val();
    filterGlobal2(texto);
}); //Barra de buscar general
///------------------------------------------------------> Filtros <------------------------------
$("#btn-filters-2").click(function() {
    var evento2 = "0";
    if ($("#card-filtros2").hasClass("filter-hide")) {
        combo_Filtros2(evento2);
        $(this).attr("title", "Ocultar Filtros");
        $("#card-filtros2").removeClass("filter-hide").addClass("filter-show");
        $(".searchCliente2_0").focus();
    } else {
        clearFilters2();
        $(this).attr("title", "Mostrar Filtros");
        $("#card-filtros2").removeClass("filter-show").addClass("filter-hide");
    }
});
$(".btn-addF-2").click(function(e) {
    var noselect2 = $(this).attr("data-idslt-2");
    addFiltro2(noselect2);
});

$(".btn-refresh-2").click(function() {
    clearFilters2();
}); //Limpiar tabla

//-------------------------------------------------------- FUNCIONES
function filterGlobal2(texto) {
    loaderON();
    $("#" + nametable2)
        .DataTable()
        .search(texto, false, true)
        .draw();
}

function filterColumn2(i, texto) {
    // console.log('evento2' + evento2)
    $("#" + nametable2)
        .DataTable()
        .column(i)
        .search(texto, false, true)
        .draw();
    if (evento2 != '') {
        loaderON();
        filterDate(1, minDate, maxDate);
    }
}

function filterDate(i, minDate, maxDate) {

    // console.log('evento21' + evento2)
    loaderON();
    $("#" + nametable2)
        .DataTable()
        .column(i)
        .search(minDate, false, true)
        .draw();

}
$("#" + nametable2)
    .DataTable().on('draw', function() {
        loaderOut()
            // console.log('Redraw occurred at: ' + new Date().getTime());
    });

function filtros2() {
    $(".slt_filtro_2").change(function() {
        var texto = "",
            columna = $(this).find(":selected").attr("data-columns"),
            noselect2 = $(this).attr("data-idslt-2");
        $(".searchCliente2_" + noselect2).attr("id", "col" + columna + "_filter");
        $(".searchCliente2_" + noselect2).attr("data-columns", columna);
        $(".Dslt2_" + noselect2).attr("id", "Del" + columna + "_filter");
        $(".Sslt2_" + noselect2).attr("id", "Sav" + columna + "_filter");
        $(".Sslt2_" + noselect2).attr("data-columns", columna);
        texto = $(".searchCliente2_" + noselect2).val();
        keyup2();
    });

    $(".btn-deleteF-2").click(function(e) {
        var noselect2 = $(this).attr("data-idslt-2");
        $("#slt-filtro-2" + noselect2 + "").remove();
    });
}

function clearFilters2() {
    for (var i = 1; i <= countFilter2; i++) {
        $("#slt-filtro-2" + i).remove();
    }
    $("input").val("");
    $(".searchCliente2_0").focus();
    $("#" + nametable2).DataTable().search("").columns().search("").draw();
}

function combo_Filtros2(evento2) {
    var columns = $("#" + nametable2)
        .DataTable().settings().init().columns,
        option = "",
        nocolum = 0;
    $("#" + nametable2)
        .DataTable().columns().every(function(index) {
            option =
                '<option value="' +
                columns[index].name +
                '" data-columns="' +
                nocolum +
                '">' +
                columns[index].title +
                "</option>";
            if (evento2 == "0") {
                $("#slt-filtro-2").append(option);
            } else {
                $("#slt_filtro_2" + evento2).append(option);
            }
            nocolum++;
        });
}

function addFiltro2(noselect2) {
    var evento2 = countFilter2;

    var Select2 =
        '<div class="input-group-prepend"> <select class="card-primary form-control  select2 slt_filtro_2"  data-placeholder="Filtro" style="width: 100%;" data-idslt-2=' +
        countFilter2 +
        ' id="slt_filtro_2' +
        countFilter2 +
        '">' +
        '<option selected="selected">Elige un Filtro</option>' +
        "</select> </div>";
    var input2 =
        '<input type="text" class="column_filter_2 form-control searchCliente_' +
        countFilter2 +
        '" placeholder="Buscar ...">';
    var buttonSch2 =
        '<span class="input-group-append"><button type="button" class="btn btn-primary btn-flat btn-saveF-2 btn_SearchColumn_2 Sslt2_' +
        countFilter2 +
        '"  title="Buscar" data-idslt-2=' +
        countFilter2 +
        '> <i class="nav-icon fas fa-search"></i></button></span>';
    var buttonDel2 =
        '<span class="input-group-append"><button type="button"  class="btn btn-danger btn-flat btn-deleteF-2 Sslt2_' +
        countFilter2 +
        '"  title="Eliminar Filtro" data-idslt-2=' +
        countFilter2 +
        '>  <i class="nav-icon fas fa-minus"></i></button></span>';
    var buttonSav2 =
        '<span class="input-group-append"><button type="button" class="btn btn-primary btn-flat btn-saveF-2 Sslt2_' +
        countFilter2 +
        '"  title="Guardar Filtro" data-idslt-2=' +
        countFilter2 +
        ' hidden> <i class="nav-icon fas fa-save"></i></button></span>';
    var div2 =
        '<div class="row justify-content-md-center fil slt"  id="slt-filtro-2' +
        countFilter2 +
        '"> <div class="col-sm-9 input-group input-group-sm justify-content-md-left">' +
        Select2 +
        input2 +
        buttonSch2 +
        buttonDel2 +
        buttonSav2 +
        "</div></div>";
    $("#box-filtros-2").append(div2);
    $(".searchCliente2_" + countFilter2).focus();
    countFilter2 = countFilter2 + 1;
    combo_Filtros2(evento2);
    keyup2();
    filtros2();
}

function keyup2() {
    $("input.column_filter_2").keypress(function(e) {
        if (e.which == 13) {
            loaderON();
            var i = $(this).attr("data-columns"),
                texto = $("#col" + i + "_filter").val();
            i >= 0 ?
                filterColumn2(i, texto) :
                toast("Debes selecionar una filtro", "warning");
        }
    });
    // $('input.column_filter').on('keyup click', function() //Buscar por columna
    $("button.btn_SearchColumn_2").click(function() {
        loaderON();
        var i = $(this).attr("data-columns"),
            texto = $("#col" + i + "_filter").val();
        i >= 0 ?
            filterColumn(i, texto) :
            toast("Debes selecionar una filtro", "warning");
    });
}