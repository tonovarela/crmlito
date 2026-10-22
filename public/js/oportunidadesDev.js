var minDate = moment().startOf("month").format("YYYY-MM");
var maxDate = moment().endOf("month").format("YYYY-MM-DD");
var evento = $("#evento").text();
var dateToday = new Date();

$(document).ajaxStart(function () {
  loaderON();
});

$(document).ajaxStop(function () {
  loaderOut();
});

$.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
  var min = minDate;
  var max = maxDate;
  var date = new Date(data[1]);

  if (
    (min === null && max === null) ||
    (min === null && date <= max) ||
    (min <= date && max === null) ||
    (min <= date && date <= max)
  ) {
    return true;
  }
  return false;
});

$(document).ready(function () {
  // $('#searchGral').keyup(function() {
  //     $("#tblOportunidades").DataTable().draw();
  // });

  obtenerSolicitudesOP();
  obtenerOportunidadPTVeinteDias();
  obtenerOportunidadSolOPCincoDias();

  $("#numOrdenMTC").prop("disabled", true);
  $("#idCotizacionAutorizada").prop("disabled", true);
  $("#idMotivoPerdida").prop("disabled", true);

  CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

  tableElePreOpor = $("#tableElePreOpor").DataTable({
    language: {
      url: serve + "js/spanish-DT.json",
    },

    dom: '<"top"i>rt<"bottom"><"clear">',
  });

  var id = $("#idOportunidad").val();
  const FROM_PATTERN = "YYYY-MM-DD HH:mm:ss.SSS";
  const TO_PATTERN = "DD/MM/YYYY HH:mm";

  const FROM_PATTERN_SHORT = "YYYY-MM-DD";
  const TO_PATTERN_SHORT = "DD/MM/YYYY";

  $("#tblOporRetenidas").DataTable({
    processing: true,
    serverSide: true,
    select: true,
    scrollX: true,
    searchable: false,
    orderable: false,
    scrollCollapse: true,
    ajax: {
      url: serve + "api/oportRetenidas/" + id,
      type: "GET",
      dataType: "json",
    },
    language: {
      url: serve + "js/spanish-DT.json",
    },
    dom: '<"top">rt<"bottom"><"clear">',
    columnDefs: [
      {
        targets: [1, 2],
        render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
      },
    ],
    columns: [
      {
        data: "notaRetencion",
        name: "notaRetencion",
        title: "Nota",
        searchable: false,
        orderable: false,
        className: "dt-body-center text-left",
      },
      {
        data: "fecRetencionInicio",
        name: "fecRetencionInicio",
        title: "Fecha Inicio",
        searchable: false,
        orderable: false,
        className: "dt-body-center text-center",
      },
      {
        data: "fecRetencionFin",
        name: "fecRetencionFin",
        title: "Fecha Fin",
        searchable: false,
        orderable: false,
        className: "dt-body-center text-center",
      },
    ],
  });

  tblTEntrega = $("#tblTEntrega").DataTable({
    processing: true,
    serverSide: true,
    select: false,
    hovered: false,
    scrollX: true,
    searchable: false,
    order: [[1, "desc"]],
    scrollCollapse: true,
    // rowId: "idTiempoEntregaOprotunidad",
    ajax: {
      url: serve + "api/tiempoEntrega/" + id,
      type: "GET",
      dataType: "json",
    },
    language: {
      url: serve + "js/spanish-DT.json",
    },
    dom: '<"top">rt<"bottom"><"clear">',
    columnDefs: [
      {
        targets: [0, 1, 2, 3, 4, 5, 6, 7],
        orderable: false,
      },
      {
        targets: [5, 6],
        render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
      },
      {
        targets: [4],
        render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
      },
    ],
    rowCallback: function (row, data) {
      if (data.estatus == 0) {
        $("td", row).css("color", " red");
        $("td:eq(0)", row).addClass("fas fa-times-circle text-center text-md");
        $("td:eq(0)", row).attr("title", "Inactiva");
      } else {
        if (data.fecArchivoAutorizado != null) {
          if (
            moment(data.FechaAutoriza, "YYYY-MM-DD") <
            moment(data.fecArchivoAutorizado, "YYYY-MM-DD")
          ) {
            $("td:eq(0)", row).addClass(
              "fas fa-copyright text-center text-yellow text-md"
            );
            $("td:eq(0)", row).attr("title", "Caducada");
          } else {
            $("td", row).css("font-weight", "bold");
            $("td:eq(0)", row).addClass(
              "fas fa-check-circle text-center text-md text-green"
            );
            $("td:eq(0)", row).attr("title", "Activa");
          }
        } else {
          if (moment(data.FechaAutoriza, "YYYY-MM-DD") < moment()) {
            $("td:eq(0)", row).addClass(
              "fas fa-copyright text-center text-yellow text-md"
            );
            $("td:eq(0)", row).attr("title", "Caducada");
          } else {
            $("td", row).css("font-weight", "bold");
            $("td:eq(0)", row).addClass(
              "fas fa-check-circle text-center text-md text-green"
            );
            $("td:eq(0)", row).attr("title", "Activa");
          }
        }
      }
    },
    columns: [
      {
        data: "icon",
        render: function (data) {
          return "<i></i>";
        },
      },
      {
        data: "idTiempoEntregaOprotunidad",
        render: function (data, type, row) {
          if (row.Comentarios == null) {
            return '<span class="text-uppercase" title=" ">' + data + "</span>";
          } else {
            return (
              '<span class="text-uppercase" title="' +
              "COMENTARIOS: " +
              row.Comentarios.toUpperCase() +
              '">' +
              data +
              "</span>"
            );
          }
        },
        title: "id",
        className: "dt-body-center text-left",
      },
      {
        data: "Autorizo",
        render: function (data, type, row) {
          if (row.Comentarios == null) {
            return '<span class="text-uppercase" title=" ">' + data + "</span>";
          } else {
            return (
              '<span class="text-uppercase" title="' +
              "COMENTARIOS: " +
              row.Comentarios.toUpperCase() +
              '">' +
              data +
              "</span>"
            );
          }
        },
        title: "Autorizó",
      },
      {
        data: "DiasEntrega",
        name: "DiasEntrega",
        title: "Días Entrega",
        className: "dt-body-center text-center",
      },
      {
        data: "fecRegistro",
        name: "fecRegistro",
        title: "Fecha Registro",
        className: "dt-body-center text-center",
      },
      {
        data: "FechaAutoriza",
        name: "FechaAutoriza",
        title: "Fecha Archivo",
        className: "dt-body-center text-center",
      },
      {
        data: "FechaEntrega",
        name: "FechaEntrega",
        title: "Fecha Entrega",
        className: "dt-body-center text-center",
      },
      {
        data: "Comentarios",
        render: function (data) {
          if (data == null) {
            return "<span></span>";
          } else {
            return (
              '<span class="text-uppercase" title="' +
              data.toUpperCase() +
              '">' +
              data +
              "</span>"
            );
          }
        },
        title: "Comentarios",
      },
    ],
  });

  ///----------------------------------------------------------------------------------------------------------------------------///
  ////-------------------------------------------------GRID TBL_OPORTUNIDADES----------------------------------------------------///
  ///-------Ing. Brigette Gutiérrez.---------------------------------------------------------------------------------------------///
  ///----------------------------------------------------------------------------------------------------------------------------///

  tblOportunidades = $("#tblOportunidades").DataTable({
    destroy: true,
    pageResize: true,
    searchable: true,
    processing: true,
    serverSide: true,
    rowId: "idOportunidad",
    select: false,
    scrollX: true,
    scrollCollapse: true,
    stateSave: true,
    async: false,
    // fixedColumns: {
    //     leftColumns: 3,
    // },
    order: [[0, "desc"]],
    ajax: {
      url: serve + "api/oportunidades",
      type: "GET",
      dataType: "json",
    },
    language: {
      url: serve + "js/spanish-DT.json",
    },
    dom: '<"top">rt<"bottom"lpBi><"clear">',
    buttons: [
      {
        extend: "colvis",
        text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
        collectionLayout: "fixed two-column",
      },
      {
        extend: "excelHtml5",
        title: "Oportunidades | CANDY CRM",
        text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
        exportOptions: {
          orthogonal: "export",
          columns: "thead th:not(.noExport)",
          modifer: {
            serverSide: false,
            page: "all",
          },
        },
        filename: "Oportunidades",
        autoFilter: true,
        sheetName: "Lista Oportunidades",
        messageTop: null,
        body: true,
        footer: false,
      },
    ],
    columnDefs: [
      {
        targets: [1, 15],
        render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN),
      },
      {
        targets: [6],
        render: $.fn.dataTable.render.moment(
          FROM_PATTERN_SHORT,
          TO_PATTERN_SHORT
        ),
      },
      {
        targets: [18],

        render: $.fn.dataTable.render.moment(FROM_PATTERN, TO_PATTERN_SHORT),
      },
      {
        targets: 5,
        render: function (data, type, row, meta) {
          if (row.Cancelada == "1" || row.idEstatus == 10) {
            if (data == null || data == "") {
              return '<span class="text-red">SIN ASIGNAR</span>';
            } else {
              return (
                '<span class="text-red" title="' +
                data +
                '">' +
                data +
                "</span>"
              );
            }
          } else if (row.idEstatus == 12) {
            return (
              '<span class="text-green" title="' +
              data +
              '">' +
              data +
              "</span>"
            );
          } else {
            if (
              row.inputRol == 10 ||
              row.inputRol == 11 ||
              row.inputRol == 8 ||
              row.inputRol == 1
            ) {
              var $select = $(
                '<select id="cotizadores_grid_' +
                  row.idOportunidad +
                  '" class="form-control sltTblOpor" title="' +
                  data +
                  '" style="font-size: .7rem !important;" ><option selected disabled>SIN ASIGNAR</option></select>'
              );
              $.each(cotizador, function (k, v) {
                var $option = $("<option></option>", {
                  text: v,
                  value: k,
                });
                if (data == v) {
                  $option.attr("selected", "selected");
                }
                $select.append($option);
              });
              return $select.prop("outerHTML");
            } else {
              if (data == null || data == "") {
                return "<span>SIN ASIGNAR</span>";
              } else {
                return "<span>" + data + "</span>";
              }
            }
          }
        },
      },
    ],
    columns: [
      {
        data: "folio",
        render: function (data, type, row) {
          if (row.Maquila == "on") {
            return (
              '<div class="rounded bg-primary" title="Con Maquila"><strong>' +
              data +
              "</strong></div>"
            );
          } else {
            return "<div><strong>" + data + "</strong></div>";
          }
        },
        width: "1rem",
        title: "Folio",
        searchable: true,
        className: "dt-body-center text-center",
      },
      {
        data: "fecCreacion",
        name: "fecCreacion",
        width: "2rem",
        className: "dt-body-center text-center",
        title: "Creación",
      },
      {
        data: "ST_Oport",
        render: function (data, type, row) {
          if (data == "ATRASADO") {
            return (
              '<div class="rounded" style="background-color: #ec7b75"><strong class="text-white">' +
              data +
              "</strong></div>"
            );
          } else if (data == "A TIEMPO") {
            return (
              '<div class="rounded" style="background-color: #84C876"><strong class="text-white">' +
              data +
              "</strong></div>"
            );
          } else if (data == "CANCELADO") {
            return (
              '<div class="rounded" style="background-color: #EE0E12"><strong class="text-white">' +
              data +
              "</strong></div>"
            );
          } else if (data == "SIN INICIAR") {
            return (
              '<div class="rounded" style="background-color: #369BDF"><strong class="text-white">' +
              data +
              "</strong></div>"
            );
          } else {
            return data;
          }
        },
        width: "2rem",
        className: "dt-body-center text-center",
        title: "ST Oport",
      },
      {
        data: "fecTope",
        name: "fecTope",
        width: "2rem",
        className: "dt-body-center text-center",
        title: "Fec Tope",
      },
      {
        data: "nomOportunidad",
        name: "nomOportunidad",
        render: function (data, type, row) {
          if ($.trim(row.inputEditar) === "Editar") {
            if (row.Cancelada == "1") {
              color_text = "text-red";
            } else {
              color_text = "";
            }

            return (
              '<a title="Editar Oportunidad" onclick="editarOportunidad(' +
              row.idOportunidad +
              '), loaderON()" class="' +
              color_text +
              ' text-uppercase cursor-pointer">' +
              data +
              "</a>"
              // '<a href="oportunidades/' +
              // row.idOportunidad +
              // '/edit" title="Editar Oportunidad" onclick="loaderON()" class="text-red text-uppercase">' +
              // data +
              // "</a>"
            );
          } else {
            return data;
          }
        },
        width: "5rem",
        title: "Nombre",
      },
      {
        data: "Atendido",
        name: "Atendido",
        width: "2rem",
        title: "Atendido",
        className: "dt-body-left text-left",
      },
      {
        data: "fecPrevCiere",
        name: "fecPrevCiere",
        width: "1rem",
        className: "dt-body-center text-center",
        title: "Prev Cierre",
      },
      {
        data: "numOrdenMTC",
        name: "numOrdenMTC",
        title: "OP MTC",
        width: "1rem",
        className: "dt-body-center text-center",
      },
      {
        data: "razonsocial",
        name: "razonsocial",
        render: function (data, type, row) {
          if (
            row.inputRol == 1 ||
            row.inputRol == 5 ||
            row.inputRol == 7 ||
            row.inputRol == 2 ||
            row.inputRol == 11 ||
            row.inputRol == 8
          ) {
            if ($.trim(row.inputEditar) === "Editar") {
              return (
                '<a href="clientes/' +
                row.ClienteINT +
                '/edit" title="Editar Cliente" onclick="loaderON()">' +
                data +
                "</a>"
              );
            } else {
              return data;
            }
          } else {
            if (
              $.trim(row.inputLogin) == $.trim(row.Login) ||
              $.trim(row.LoginAuxiliar) == $.trim(row.inputLogin) // ||
              // $.trim(row.LoginCustomer) == $.trim(row.inputLogin)
            ) {
              if ($.trim(row.inputEditar) === "Editar") {
                return (
                  '<a href="clientes/' +
                  row.ClienteINT +
                  '/edit" title="Editar Cliente">' +
                  data +
                  "</a>"
                );
              } else {
                return data;
              }
            } else {
              return data;
            }
          }
        },
        width: "4rem",
        title: "Cliente",
      },
      {
        data: "Agente",
        // name: "Agente",
        title: "Asignado",
        width: "2rem",
        className: "dt-body-left text-left",
      },
      {
        data: "desTipoOportundidad",
        name: "desTipoOportundidad",
        title: "Tipo",
        width: "2rem",
        className: "dt-body-center text-center",
      },
      {
        data: "descProbabilidad",
        name: "descProbabilidad",
        title: "Prob.",
        width: "2rem",
        className: "dt-body-center text-center",
      },
      {
        data: "Valor",
        // render: $.fn.dataTable.render.number(',', '.', 2, '$ '),
        render: function (data, type, row) {
          return "$" + data;
        },
        name: "Valor",
        title: "Valor",
        width: "2rem",
        className: "dt-body-center text-right",
      },
      {
        data: "descEstatus",
        render: function (data, type, row) {
          if (row.Cancelada == "1") {
            return '<strong class="text-red">Cancelada</strong>';
          } else {
            return data;
          }
        },
        // name: "descEStatus",
        title: "ST",
        width: "1rem",
        className: "dt-body-center text-center",
      },
      {
        data: "valorEstatus",
        render: function (data, type, row) {
          if (row.Cancelada == 1) {
            return (
              '<div class="progress rounded" style="width: 8rem" title="' +
              row.descEstatus +
              '"><div class="progress-bar text-bold" role="progressbar" style="width: 100%; background-color: red; color: #000" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>'
            );
          } else if (row.idEstatus == 10) {
            return (
              '<div class="progress rounded" style="width: 8rem" title="' +
              row.descEstatus +
              '"><div class="progress-bar text-bold" role="progressbar" style="width: 100%; background-color: ' +
              row.colorEstatus +
              '; color: #000" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div></div>'
            );
          } else {
            return (
              '<div class="progress rounded" style="width: 8rem" title="' +
              row.descEstatus +
              '"><div class="progress-bar text-bold" role="progressbar" style="width: ' +
              row.valorEstatus +
              "%; background-color: " +
              row.colorEstatus +
              '; color: #000" aria-valuenow="' +
              row.valorEstatus +
              '" aria-valuemin="0" aria-valuemax="100">' +
              row.valorEstatus +
              "%</div></div>"
            );
          }
        },
        name: "valorEstatus",
        title: "Progreso",
      },
      {
        data: "fecOrdenConcluida",
        render: function (data, type, row) {
          if (row.Cancelada == "1") {
            return moment(row.fecCancelada).format(TO_PATTERN);
          } else {
            if (data == null || data == "") {
              return data;
            } else {
              return moment(data).format(TO_PATTERN);
            }
          }
        },
        name: "fecOrdenConcluida",
        // render: function(data, type, row){
        //   if (row.Cancelada == null) {
        //     return '';
        //   } else {
        //     return data
        //   }
        // },
        title: "Concluida",
        width: "3rem",
        className: "dt-body-center text-center",
      },
      {
        data: "solicitudFechaEntrega",
        render: function (data, type, row) {
          if (row.archivoAutorizado == 1) {
            if (row.FechaEntrega != null || row.DiasEntrega != null) {
              return '<span class="bg-green rounded-circle pl-2 pr-2 pt-1 pb-1" title="Asignada"><i class="text-bold">A</i></span>';
            } else if (data == 1) {
              return '<span class="bg-primary rounded-circle pl-2 pr-2 pt-1 pb-1" title="Solicitada"><i class="text-bold">S</i></span>';
            } else {
              return '<span title="Sin Status"><i class="fas fa-times-circle text-lg text-red"></i></span>';
            }
          } else {
            if (
              dateFormat(row.FechaAutoriza, "yyyy-mm-dd") <
              dateFormat(dateToday, "yyyy-mm-dd")
            ) {
              return '<span class="bg-warning rounded-circle pl-2 pr-2 pt-1 pb-1" title="Caducado por falta de archivo autorizado"><i class="text-bold text-white">C</i></span>';
            } else if (row.FechaEntrega != null || row.DiasEntrega != null) {
              return '<span class="bg-green rounded-circle pl-2 pr-2 pt-1 pb-1" title="Asignada"><i class="text-bold">A</i></span>';
            } else if (data == 1) {
              return '<span class="bg-primary rounded-circle pl-2 pr-2 pt-1 pb-1" title="Solicitada"><i class="text-bold">S</i></span>';
            } else {
              return '<span title="Sin Status"><i class="fas fa-times-circle text-lg text-red"></i></span>';
            }
          }
        },
        title: "STFecEnt",
        className: "dt-body-center text-center",
      },
      {
        data: "archivoAutorizado",
        render: function (data, type, row) {
          if (data == 1) {
            // return (row.fecArchivoAutorizado + ' / ' + row.DiasEntrega);
            return '<span><i class="fas fa-check-circle text-lg text-green"></i></span>';
          } else {
            return '<span><i class="fas fa-times-circle text-lg text-red"></i></span>';
          }
        },
        title: "ArchAut",
        className: "dt-body-center text-center",
      },
      {
        data: "FechaEntrega",
        render: function (data, type, row) {
          if (row.archivoAutorizado == 1) {
            if (data != null) {
              return data;
            } else if (row.DiasEntrega != null) {
              return row.DiasEntrega + " DIAS";
            } else {
              return "";
            }
          } else {
            if (
              dateFormat(row.FechaAutoriza, "yyyy-mm-dd") <
              dateFormat(dateToday, "yyyy-mm-dd")
            ) {
              return '<span title="Solicitar nueva fecha de entrega"><i class="text-bold">Solicitar nueva fecha de entrega</i></span>';
            } else {
              if (data != null) {
                return data;
              } else if (row.DiasEntrega != null) {
                return row.DiasEntrega + " DIAS";
              } else {
                return "";
              }
            }
          }
        },
        title: "Fecha Entrega",
        width: "3rem",
        className: "dt-body-center text-center",
      },
    ],
    pageLength: 50,
    lengthMenu: [
      [50, 100, 150, 200, -1],
      [50, 100, 150, 200, "TODOS"],
    ],
    initComplete: function (settings, json) {
      if (window.sessionStorage) {
        evento = sessionStorage.getItem("evento");
        if (evento == null) {
          loaderON();
          var texto = $("#searchGral").val();
          if ($("#searchGral").val() != "") {
            loaderON();
            $("#evento").text("");
            filterColumn(13, texto.trim());
            $.fn.dataTable.ext.search.pop();
          }
        } else {
          //                var texto = evento;
          filterGlobalCards(evento.trim());
        }
        sessionStorage.removeItem("evento");
      } else {
        throw new Error("Tu Browser no soporta sessionStorage!");
      }

      //evento = $("#evento").text();
      // console.log(evento);
      loaderOut();
    },
  });

  $("#tblOportunidades tbody").on("change", "td", function () {
    cotizador = $(this).find(":selected").val();
    data = tblOportunidades.row($(this).parents("tr")).data();
    id = data["idOportunidad"];
    nombre_cotizador = data["Atendido"];
    id_usuario = data["Id_Usuario"];
    cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario);
  });

  selectRow("tblOportunidades");

  $("#tblOportunidades")
    .DataTable()
    .on("draw", function () {
      ajustarTablas();
    });

  // $('#tblOportunidades').ajaxComplete(loaderOut());

  tableEntregasOportunidades = $("#tableEntregasOportunidades").DataTable({
    destroy: true,
    processing: true,
    serverSide: true,
    scrollY: true,
    select: true,
    pageResize: true,
    order: [[0, "asc"]],
    ajax: {
      url: serve + "apiSucursalesEntrega",
      type: "GET",
      dataType: "json",
      data: {
        idOportunidad: id,
      },
    },
    language: {
      url: serve + "js/spanish-DT.json",
    },
    dom: '<"top">rt<"bottom"><"clear">',
    columns: [
      {
        data: "Nombre",
        title: "Sucursal",
        className: "text-uppercase",
      },
      {
        data: "cantidad",
        title: "Cantidad",
        className: "text-right",
      },
      {
        data: "fecEntrega",
        title: "Fecha Entrega",
        className: "text-right",
      },
      {
        data: "idSucEntrega",
        render: function (data, type, row) {
          return (
            '<a onclick="eliminarCantidad(' +
            data +
            ')" class="pointer" title="Eliminar Cantidad"><i class="fas fa-trash text-red"></i></a>'
          );
        },
        title: "Eliminar",
        className: "dt-body-center text-center",
      },
    ],
    pageLength: -1,
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();
      nb_cols = api.columns().nodes().length;
      var j = 1;
      while (j < 2) {
        var pageTotal = api
          .column(j)
          .data()
          .reduce(function (a, b) {
            return Number(a) + Number(b);
          }, 0);

        var numFormat = $.fn.dataTable.render.number(",", ".", 0, "").display;

        $(api.column(0).footer()).html("Cantidad Total");
        $(api.column(j).footer()).html(numFormat(pageTotal));
        j++;
      }
    },
    drawCallback: function (settings) {
      $('[data-toggle="tooltip"]').tooltip();
    },
    columnDefs: [
      {
        targets: [2],
        render: $.fn.dataTable.render.moment(
          FROM_PATTERN_SHORT,
          TO_PATTERN_SHORT
        ),
      },
      {
        targets: [1],
        render: $.fn.dataTable.render.number(",", ".", 0),
      },
    ],
  });
});

let myVar = setInterval(autoReloadPage, 600000);

function autoReloadPage() {
  tblOportunidades.ajax.reload();
}

let myVar2 = setInterval(obtenerSolicitudesOP, 600000);

function autoReloadNoty() {
  obtenerSolicitudesOP();
}

var mensaje = "";
var icono = "";
$(function () {
  $("#formOporCreate form")
    .validator()
    .on("submit", function (e) {
      if (!e.isDefaultPrevented()) {
        mensaje = "Oportunidad Creada";
        icono = "success";
        regresar();
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

  $("#formOporEdit form")
    .validator()
    .on("submit", function (e) {
      if (!e.isDefaultPrevented()) {
        loaderON();
        mensaje = "Oportunidad Actualizada";
        icono = "success";
        regresar();
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

function CancelarOportunidad(id) {
  mensaje = "Oportunidad Cancelada";
  id_suario = document.getElementById("idUsuario");
  Swal.fire({
    title: "Oportunidades",
    text: "Cancelar la Oportunidad Seleccionada?",
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
      const { value: text } = Swal.fire({
        input: "textarea",
        inputLabel: "Motivo Cancelación",
        inputPlaceholder: "Captura el motivo de Cancelación de la Oportunidad",
        inputAttributes: {
          "aria-label": "Captura el motivo de Cancelación de la Oportunidad",
        },
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Guardar",
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#999",
        target: document.getElementById("formOporEdit"),
      }).then((result) => {
        if (result.isConfirmed) {
          // console.log(result);
          if (result.value != "") {
            $.ajax({
              url: serve + "cancelarOportunidad",
              type: "PATCH",
              data: {
                _method: "PATCH",
                id: id,
                id_usuario: id_suario.value,
                motivoCancelacion: result.value,
                _token: CSRF_TOKEN,
              },
              success: function (data) {
                const Msg = Swal.mixin({
                  toast: true,
                  position: "center",
                  icon: "warning",
                  showConfirmButton: false,
                  timer: 3000,
                });

                Msg.fire({
                  title: mensaje,
                });
              },
              error: function () {
                const Msg = Swal.mixin({
                  toast: true,
                  position: "center",
                  icon: "warning",
                  showConfirmButton: false,
                  timer: 3000,
                });

                Msg.fire({
                  title: mensaje,
                });
                window.location.href = serve + "oportunidades";
              },
            });
          } else {
            Swal.fire("¡Debes capturar el motivo!");
          }
        } else {
          loaderOut();
        }
      });

      //   if (text) {
      //     Swal.fire(text)
      //   }
    } else {
      loaderOut();
    }
  });
}

$("#numOrdenMTC").autocomplete({
  delay: 0,
  minLength: 4,
  source: function (request, response) {
    $.ajax({
      url: serve + "obtenerOPs",
      type: "GET",
      dataType: "json",
      data: {
        _token: CSRF_TOKEN,
        search: request.term,
      },
      success: function (data) {
        response(data);
      },
    });
  },
});

function cambiar_cotizador(id, cotizador, nombre_cotizador, id_usuario) {
  mensaje = "Oportunidad Actualizada";
  // console.log(id_usuario);
  Swal.fire({
    title: "Oportunidades",
    text: "Actualizar 'Atendido por' de la Oportunidad Seleccionada?",
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
      $.ajax({
        url: serve + "cambiarCotizador",
        type: "PATCH",
        data: {
          _method: "PATCH",
          id: id,
          cotizador: cotizador,
          id_usuario: id_usuario,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
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
          window.location.href = "oportunidades";
        },
        error: function () {
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
        },
      });
    } else {
      $("#cotizadores_grid_" + id)
        .find("option")
        .attr("selected", false);
      $(
        "#cotizadores_grid_" +
          id +
          '  option:contains("' +
          nombre_cotizador +
          '")'
      ).attr("selected", true);
      window.location.href = "oportunidades";
    }
  });
}

function mostrarGeneralOport() {
  idrol = document.getElementById("idRol");

  $("#general-oport-tab").addClass("active");
  $("#general-oport").addClass("show");

  $("#elementos-oport-tab").removeClass("active");
  $("#elementos-oport").removeClass("show");

  $("#documentos-oport-tab").removeClass("active");
  $("#documentos-oport").removeClass("show");

  $("#entregas-oport-tab").removeClass("active");
  $("#entregas-oport").removeClass("show");
  document.getElementById("btnClonarOportunidad").style.display = "block";

  if (idrol.value == 1013) {
    document.getElementById("documentos-oport-tab").style.display = "none";
    document.getElementById("entregas-oport-tab").style.display = "none";

    var nodes_general = document
      .getElementById("tabGeneral")
      .getElementsByTagName("*");
    for (var i = 0; i < nodes_general.length; i++) {
      nodes_general[i].disabled = true;
    }

    $("#div-btnrelacionar").addClass("disable");

    $("#sOportBtnCancelar").addClass("disable");
    $("#sOportBtnSave").addClass("disable");
    $("#btnCancelar").addClass("disable");

    document.getElementById("sOportBtnTEntrega").style.display = "block";

    $("#sOportBtnBack").removeClass("col-12");

    $("#sOportBtnSave").addClass("col-3");
    $("#sOportBtnBack").addClass("col-4");
    $("#sOportBtnCancelar").addClass("col-3");

    if (archivoAutorizado.checked == true) {
      $("#archivoAutorizado").prop("disabled", false);
      $("#archivoAutorizado").addClass("pointer");
      $("#archivoAutorizadoLabel").addClass("pointer");
    } else {
      $("#archivoAutorizado").prop("disabled", false);
      $("#archivoAutorizadoLabel").addClass("pointer");
    }
  } else if (idrol.value == 1014) {
    document.getElementById("elementos-oport-tab").style.display = "none";
    document.getElementById("documentos-oport-tab").style.display = "none";
    document.getElementById("entregas-oport-tab").style.display = "none";
    document.getElementById("btnClonarOportunidad").style.display = "none";

    var nodes_general = document
      .getElementById("tabGeneral")
      .getElementsByTagName("*");
    for (var i = 0; i < nodes_general.length; i++) {
      nodes_general[i].disabled = true;
    }

    $("#div-btnrelacionar").addClass("disable");

    $("#btnCancelar").attr("onclick", "");
    $("#btnCancelar").off("click");

    $("#btnSolFecEntrega").attr("onclick", "");
    $("#btnSolFecEntrega").off("click");
    $("#btnSolFecEntrega").addClass("disable");

    $("#oportBtnSave").prop("disabled", true);
    $("#sOportBtnCancelar").addClass("disable");
    $("#btnCancelar").addClass("disable");
    // $("#sOportBtnSave").addClass("disable");

    document.getElementById("sOportBtnTEntrega").style.display = "none";
    document.getElementById("sOportBtnSave").style.display = "none";
    document.getElementById("sOportBtnCancelar").style.display = "none";

    $("#sOportBtnBack").removeClass("col-12");

    $("#sOportBtnSave").addClass("col-4");
    $("#sOportBtnBack").addClass("col-4");
    $("#sOportBtnCancelar").addClass("col-4");
  } else if (idrol.value == 8 || idrol.value == 1) {
    document.getElementById("sOportBtnTEntrega").style.display = "block";

    $("#sOportBtnBack").removeClass("col-12");

    $("#sOportBtnSave").addClass("col-3");
    $("#sOportBtnBack").addClass("col-4");
    $("#sOportBtnCancelar").addClass("col-3");
  } else {
    document.getElementById("elementos-oport-tab").style.display = "block";
    document.getElementById("documentos-oport-tab").style.display = "block";
    document.getElementById("entregas-oport-tab").style.display = "block";

    $("#sOportBtnCancelar").removeClass("disable");
    $("#sOportBtnSave").removeClass("disable");

    document.getElementById("sOportBtnTEntrega").style.display = "none";

    $("#sOportBtnBack").removeClass("col-12");

    $("#sOportBtnSave").addClass("col-4");
    $("#sOportBtnBack").addClass("col-4");
    $("#sOportBtnCancelar").addClass("col-4");
  }

  document.getElementById("general-oport").style.display = "block";
  document.getElementById("elementos-oport").style.display = "none";
  document.getElementById("documentos-oport").style.display = "none";
  document.getElementById("entregas-oport").style.display = "none";
  document.getElementById("sOportBtnSave").style.display = "block";
  document.getElementById("sOportBtnCancelar").style.display = "block";

  tblElementosPresupuestados.draw();
}

function mostrarElementosOport() {
  $("#general-oport-tab").removeClass("active");
  $("#general-oport").removeClass("show");

  $("#elementos-oport-tab").addClass("active");
  $("#elementos-oport").addClass("show");

  $("#documentos-oport-tab").removeClass("active");
  $("#documentos-oport").removeClass("show");

  $("#entregas-oport-tab").removeClass("active");
  $("#entregas-oport").removeClass("show");

  document.getElementById("general-oport").style.display = "none";
  document.getElementById("elementos-oport").style.display = "block";
  document.getElementById("documentos-oport").style.display = "none";
  document.getElementById("entregas-oport").style.display = "none";
  document.getElementById("sOportBtnSave").style.display = "none";
  document.getElementById("sOportBtnCancelar").style.display = "none";
  document.getElementById("sOportBtnTEntrega").style.display = "none";
  document.getElementById("btnClonarOportunidad").style.display = "none";

  $("#sOportBtnBack").addClass("col-12");
  tblElementosPresupuestados.draw();
}

function mostrarDocumentosOport() {
  tblDocs();
  $("#general-oport-tab").removeClass("active");
  $("#general-oport").removeClass("show");

  $("#elementos-oport-tab").removeClass("active");
  $("#elementos-oport").removeClass("show");

  $("#documentos-oport-tab").addClass("active");
  $("#documentos-oport").addClass("show");

  $("#entregas-oport-tab").removeClass("active");
  $("#entregas-oport").removeClass("show");

  document.getElementById("general-oport").style.display = "none";
  document.getElementById("elementos-oport").style.display = "none";
  document.getElementById("documentos-oport").style.display = "block";
  document.getElementById("entregas-oport").style.display = "none";
  document.getElementById("sOportBtnSave").style.display = "none";
  document.getElementById("sOportBtnCancelar").style.display = "none";
  document.getElementById("sOportBtnTEntrega").style.display = "none";
  document.getElementById("btnClonarOportunidad").style.display = "none";
  $("#sOportBtnBack").addClass("col-12");

  tblElementosPresupuestados.draw();
}

function mostrarEntregasOport() {
  $("#general-oport-tab").removeClass("active");
  $("#general-oport").removeClass("show");

  $("#elementos-oport-tab").removeClass("active");
  $("#elementos-oport").removeClass("show");

  $("#documentos-oport-tab").removeClass("active");
  $("#documentos-oport").removeClass("show");

  $("#entregas-oport-tab").addClass("active");
  $("#entregas-oport").addClass("show");

  document.getElementById("general-oport").style.display = "none";
  document.getElementById("elementos-oport").style.display = "none";
  document.getElementById("documentos-oport").style.display = "none";
  document.getElementById("entregas-oport").style.display = "block";
  document.getElementById("sOportBtnSave").style.display = "none";
  document.getElementById("sOportBtnCancelar").style.display = "none";
  document.getElementById("sOportBtnTEntrega").style.display = "none";
  document.getElementById("btnClonarOportunidad").style.display = "none";
  $("#sOportBtnBack").addClass("col-12");
  // $("#sOportBtnBack").removeClass("col-12");
  // $("#sOportBtnBack").addClass("col-6");
  // $("#sOportBtnSave").addClass("col-6");

  ajustarTablaEntregasOport();

  tableEntregasOportunidades.draw();
}

$(window).resize(function () {
  ajustarTablaEntregasOport();
});

function ajustarTablaEntregasOport() {
  var height = window.innerHeight;

  if (height >= 320 && height <= 651) {
    $("#tableEntregasOportunidades")
      .closest(".dataTables_scrollBody")
      .css("height", "45vh");
    $("#scrollbarTabsModalRound").css("height", "68vh");
  } else if (height >= 652 && height <= 768) {
    $("#tableEntregasOportunidades")
      .closest(".dataTables_scrollBody")
      .css("height", "49vh");
    $("#scrollbarTabsModalRound").css("height", "72vh");
  } else if (height >= 769 && height <= 992) {
    $("#tableEntregasOportunidades")
      .closest(".dataTables_scrollBody")
      .css("height", "53vh");
    $("#scrollbarTabsModalRound").css("height", "76vh");
  } else if (height >= 993 && height <= 1024) {
    $("#tableEntregasOportunidades")
      .closest(".dataTables_scrollBody")
      .css("height", "53vh");
    $("#scrollbarTabsModalRound").css("height", "76vh");
  } else if (height >= 1025 && height <= 1366) {
    $("#tableEntregasOportunidades")
      .closest(".dataTables_scrollBody")
      .css("height", "54vh");
    $("#scrollbarTabsModalRound").css("height", "77vh");
  } else {
    $("#tableEntregasOportunidades")
      .closest(".dataTables_scrollBody")
      .css("height", "55vh");
    $("#scrollbarTabsModalRound").css("height", "78vh");
  }
}

function habilitarSucursal(idSucursalEnt) {
  var seleccionar = document.getElementById("chk" + idSucursalEnt);

  seleccionar.addEventListener("change", function () {
    habilitarCantidad(idSucursalEnt, seleccionar.checked);
  });
}

function habilitarCantidad(idSucursalEnt, check) {
  if (check) {
    $("#cantidadSuc" + idSucursalEnt)
      .prop("disabled", false)
      .focus();
  } else {
    $("#cantidadSuc" + idSucursalEnt).prop("disabled", true);
    $("#cantidadSuc" + idSucursalEnt).val(0);
  }
}

function guardarCantidad(idSucursalEnt) {
  cantidad = $("#cantidadSuc" + idSucursalEnt).val();
  seleccionado = $("#chk" + idSucursalEnt).val();

  // console.log(seleccionado);

  if (seleccionado == "on") {
    seleccionado = 1;
  } else {
    seleccionado = 0;
  }

  $.ajax({
    url: serve + "guardarCantidad",
    type: "PATCH",
    data: {
      _method: "PATCH",
      idSucursalEnt: idSucursalEnt,
      cantidad: cantidad,
      seleccionado: seleccionado,
      _token: CSRF_TOKEN,
    },
    success: function (data) {
      const Msg = Swal.mixin({
        toast: true,
        position: "center",
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
      });

      Msg.fire({
        title: "Cantidad Agregada",
      });
      tableEntregasOportunidades.ajax.reload();
    },
    error: function () {
      const Msg = Swal.mixin({
        toast: true,
        position: "center",
        icon: "warning",
        showConfirmButton: false,
        timer: 3000,
      });

      Msg.fire({
        title: "Error al Agregar...",
      });
    },
  });
}

function editarOportunidad(idOportunidad) {
  loaderModalON();
  $.ajax({
    url: serve + "editarOportunidad",
    type: "GET",
    data: {
      idOportunidad: idOportunidad,
    },
    success: function (data) {
      oportBtnSave.disabled = true;
      idMotivoPerdida.disabled = true;
      numOrdenMTC.disabled = true;

      $("#btnCancelar").removeClass("disable");
      $("#btnSolFecEntrega").removeClass("disable");
      $("#tblOporRetenidas").removeClass("disable");
      $("#div-btnrelacionar").removeClass("disable");
  
      $("#modal-oportunidades-edit form").trigger("reset");

      archivo_autorizado = data.archivoAutorizado;
      $.ajax({
        url: serve + "obtenerRol",
        type: "GET",
        success: function (dataRol) {
          idRol.value = dataRol;

          if (dataRol == 1013 || dataRol == 1 || dataRol == 8) {
            sOportBtnTEntrega.style.display = "block";
          } else {
            sOportBtnTEntrega.style.display = "none";
          }

          if (archivo_autorizado == "1") {
            if (dataRol == 1013 || dataRol == 1 || dataRol == 8) {
              archivoAutorizado.checked = true;
              $("#archivoAutorizado").on("click", function () {
                desautorizarArchivo(idOportunidad);
              });
            } else {
              archivoAutorizado.checked = true;
              archivoAutorizado.disabled = true;
            }
          } else {
            archivoAutorizado.checked = false;
            archivoAutorizado.disabled = false;
            $("#archivoAutorizado").on("click", function () {
              autorizarArchivo(idOportunidad);
            });
          }

          if (dataRol == 2 || dataRol == 10 || dataRol == 1 || dataRol == 8) {
            if (
              data.idEstatus == 8 ||
              data.idEstatus == 9 ||
              data.idEstatus == 10 ||
              data.idEstatus == 11 ||
              data.idEstatus == 12
            ) {
              idAtendido.disabled = true;
            } else {
              idAtendido.disabled = false;
            }
          } else {
            idAtendido.disabled = true;
          }
        },
      });
      console.log(data);
      idUsuario.value = data.Id_Usuario;
      Estatus.value = data.idEstatus;
      idOportunidadEdit.value = data.idOportunidad;
      Cancelada.value = data.Cancelada;
      idClienteEnt.value = data.ClienteINT;
      idCliente.value = data.idCliente;
      FechaAutoriza.value = data.FechaAutoriza;
      nomOportunidad.value = data.nomOportunidad;
      $("#numFolioEdit").text("No. " + data.folio + " - ");
      $("#idClienteEdit").val(data.razonsocial);

      $.ajax({
        url: serve + "obtenerTipoOportunidad",
        type: "GET",
        success: function (r) {
          var idTipo = $("#idTipo");
          idTipo.find("option").remove();

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.desTipoOportundidad,
              value: v.idTipoOportunidad,
            });
            if (data.idTipoOportunidad == v.idTipoOportunidad) {
              $option.attr("selected", "selected");
            }
            idTipo.append($option);
          });
        },
      });

      validarEstatus(data.idEstatus, data.idMotivoPerdida, data.Cancelada);

      // if (data.solicitudFechaEntrega == "0") {
      //   if (data.idEstatus == 12) {
      //     $("#divBtnSolFecEntrega").addClass("disable");
      //   } else {
      //     $("#divBtnSolFecEntrega").removeClass("disable");
      //   }
      // } else {
      //   $("#divBtnSolFecEntrega").addClass("disable");
      // }

      $.ajax({
        url: serve + "obtenerMotivoPerdida",
        type: "GET",
        success: function (r) {
          // console.log(r);
          var idMotivoPerdida = $("#idMotivoPerdida");
          idMotivoPerdida.find("option").remove();

          idMotivoPerdida.append(
            "<option selected disabled>Motivo Perdida</option>"
          );

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.descMotivoPerdida,
              value: v.idMotivoPerdida,
            });
            if (data.idMotivoPerdida == v.idMotivoPerdida) {
              $option.attr("selected", "selected");
            }
            idMotivoPerdida.append($option);
          });
        },
      });

      numOrdenMTC.value = data.numOrdenMTC;

      if (data.fecCreacion == null) {
        $("#tlPreRegistro").addClass("bg-red");
        $("#tlPreRegistro").addClass("fa-times");
        $("#tlPreRegistro").removeClass("fa-check");
        $("#tlPreRegistro").removeClass("bg-green");
      } else {
        $("#tlPreRegistro").removeClass("bg-red");
        $("#tlPreRegistro").removeClass("fa-times");
        $("#tlPreRegistro").addClass("fa-check");
        $("#tlPreRegistro").addClass("bg-green");
        $("#tlPreRegistroHeader").addClass("bg-blue");
        $("#tlPreRegistroHeader").addClass("text-bold");
        $("#tlPreRegistroFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecCreacion, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecSolCotizacion == null) {
        $("#tlSolCot").addClass("bg-red");
        $("#tlSolCot").addClass("fa-times");
        $("#tlSolCot").removeClass("fa-check");
        $("#tlSolCot").removeClass("bg-green");
        $("#tlSolCotHeader").removeClass("bg-blue");
        $("#tlSolCotHeader").removeClass("text-bold");
        $("#tlSolCotFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlSolCot").removeClass("bg-red");
        $("#tlSolCot").removeClass("fa-times");
        $("#tlSolCot").addClass("fa-check");
        $("#tlSolCot").addClass("bg-green");
        $("#tlSolCotHeader").addClass("bg-blue");
        $("#tlSolCotHeader").addClass("text-bold");
        $("#tlSolCotFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecSolCotizacion, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecListoCotizar == null) {
        $("#tlListoCot").addClass("bg-red");
        $("#tlListoCot").addClass("fa-times");
        $("#tlListoCot").removeClass("fa-check");
        $("#tlListoCot").removeClass("bg-green");
        $("#tlListoCotHeader").removeClass("bg-blue");
        $("#tlListoCotHeader").removeClass("text-bold");
        $("#tlListoCotFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlListoCot").removeClass("bg-red");
        $("#tlListoCot").removeClass("fa-times");
        $("#tlListoCot").addClass("fa-check");
        $("#tlListoCot").addClass("bg-green");
        $("#tlListoCotHeader").addClass("bg-blue");
        $("#tlListoCotHeader").addClass("text-bold");
        $("#tlListoCotFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecListoCotizar, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecCotizando == null) {
        $("#tlCotizando").addClass("bg-red");
        $("#tlCotizando").addClass("fa-times");
        $("#tlCotizando").removeClass("fa-check");
        $("#tlCotizando").removeClass("bg-green");
        $("#tlCotizandoHeader").removeClass("bg-blue");
        $("#tlCotizandoHeader").removeClass("text-bold");
        $("#tlCotizandoFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlCotizando").removeClass("bg-red");
        $("#tlCotizando").removeClass("fa-times");
        $("#tlCotizando").addClass("fa-check");
        $("#tlCotizando").addClass("bg-green");
        $("#tlCotizandoHeader").addClass("bg-blue");
        $("#tlCotizandoHeader").addClass("text-bold");
        $("#tlCotizandoFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecCotizando, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecRevision == null) {
        $("#tlRevision").addClass("bg-red");
        $("#tlRevision").addClass("fa-times");
        $("#tlRevision").removeClass("fa-check");
        $("#tlRevision").removeClass("bg-green");
        $("#tlRevisionHeader").removeClass("bg-blue");
        $("#tlRevisionHeader").removeClass("text-bold");
        $("#tlRevisionFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlRevision").removeClass("bg-red");
        $("#tlRevision").removeClass("fa-times");
        $("#tlRevision").addClass("fa-check");
        $("#tlRevision").addClass("bg-green");
        $("#tlRevisionHeader").addClass("bg-blue");
        $("#tlRevisionHeader").addClass("text-bold");
        $("#tlRevisionFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecRevision, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecEsperaVobo == null) {
        $("#tlVoBo").addClass("bg-red");
        $("#tlVoBo").addClass("fa-times");
        $("#tlVoBo").removeClass("fa-check");
        $("#tlVoBo").removeClass("bg-green");
        $("#tlVoBoHeader").removeClass("bg-blue");
        $("#tlVoBoHeader").removeClass("text-bold");
        $("#tlVoBoFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlVoBo").removeClass("bg-red");
        $("#tlVoBo").removeClass("fa-times");
        $("#tlVoBo").addClass("fa-check");
        $("#tlVoBo").addClass("bg-green");
        $("#tlVoBoHeader").addClass("bg-blue");
        $("#tlVoBoHeader").addClass("text-bold");
        $("#tlVoBoFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecEsperaVobo, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecListoPresupuesto == null) {
        $("#tlListoPres").addClass("bg-red");
        $("#tlListoPres").addClass("fa-times");
        $("#tlListoPres").removeClass("fa-check");
        $("#tlListoPres").removeClass("bg-green");
        $("#tlListoPresHeader").removeClass("bg-blue");
        $("#tlListoPresHeader").removeClass("text-bold");
        $("#tlListoPresFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlListoPres").removeClass("bg-red");
        $("#tlListoPres").removeClass("fa-times");
        $("#tlListoPres").addClass("fa-check");
        $("#tlListoPres").addClass("bg-green");
        $("#tlListoPresHeader").addClass("bg-blue");
        $("#tlListoPresHeader").addClass("text-bold");
        $("#tlListoPresFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecListoPresupuesto, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecPresupTerminado == null) {
        $("#tlPresTerm").addClass("bg-red");
        $("#tlPresTerm").addClass("fa-times");
        $("#tlPresTerm").removeClass("fa-check");
        $("#tlPresTerm").removeClass("bg-green");
        $("#tlPresTermHeader").removeClass("bg-blue");
        $("#tlPresTermHeader").removeClass("text-bold");
        $("#tlPresTermFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlPresTerm").removeClass("bg-red");
        $("#tlPresTerm").removeClass("fa-times");
        $("#tlPresTerm").addClass("fa-check");
        $("#tlPresTerm").addClass("bg-green");
        $("#tlPresTermHeader").addClass("bg-blue");
        $("#tlPresTermHeader").addClass("text-bold");
        $("#tlPresTermFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecPresupTerminado, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecResultadoF == null) {
        $("#tlResultadoF").addClass("bg-red");
        $("#tlResultadoF").addClass("fa-times");
        $("#tlResultadoF").removeClass("fa-check");
        $("#tlResultadoF").removeClass("bg-green");
        $("#tlResultadoFHeader").removeClass("bg-blue");
        $("#tlResultadoFHeader").removeClass("text-bold");
        $("#tlResultadoFFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlResultadoF").removeClass("bg-red");
        $("#tlResultadoF").removeClass("fa-times");
        $("#tlResultadoF").addClass("fa-check");
        $("#tlResultadoF").addClass("bg-green");
        $("#tlResultadoFHeader").addClass("bg-blue");
        $("#tlResultadoFHeader").addClass("text-bold");
        $("#tlResultadoFFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecResultadoF, "dd-mm-yyyy HH:MM:ss")
        );
      }

      if (data.fecOrdenConcluida == null) {
        $("#tlOPConcluida").addClass("bg-red");
        $("#tlOPConcluida").addClass("fa-times");
        $("#tlEnd").addClass("bg-red");
        $("#tlEnd").removeClass("bg-green");
        $("#tlEnd").addClass("fa-clock");
        $("#tlOPConcluida").removeClass("fa-check");
        $("#tlOPConcluida").removeClass("bg-green");
        $("#tlOPConcluidaHeader").removeClass("bg-blue");
        $("#tlOPConcluidaHeader").removeClass("text-bold");
        $("#tlOPConcluidaFecha").html('<i class="fas fa-clock"></i>');
      } else {
        $("#tlOPConcluida").removeClass("bg-red");
        $("#tlOPConcluida").removeClass("fa-times");
        $("#tlOPConcluida").addClass("fa-check");
        $("#tlOPConcluida").addClass("bg-green");
        $("#tlEnd").removeClass("bg-red");
        $("#tlEnd").addClass("bg-green");
        $("#tlEnd").addClass("fa-clock");
        $("#tlOPConcluidaHeader").addClass("bg-blue");
        $("#tlOPConcluidaHeader").addClass("text-bold");
        $("#tlOPConcluidaFecha").html(
          '<i class="fas fa-clock">&nbsp;</i>' +
            dateFormat(data.fecOrdenConcluida, "dd-mm-yyyy HH:MM:ss")
        );
      }

      $.ajax({
        url: serve + "obtenerPrevision",
        type: "GET",
        success: function (r) {
          var idExclusion = $("#idExclusion");
          idExclusion.find("option").remove();

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.descExclusionOport,
              value: v.idExclusionOport,
            });
            if (data.idExclusionOport == v.idExclusionOport) {
              $option.attr("selected", "selected");
            }
            idExclusion.append($option);
          });
        },
      });

      Valor.value = data.Valor;

      $.ajax({
        url: serve + "obtenerProbabilidad",
        type: "GET",
        success: function (r) {
          var idProbabilidad = $("#idProbabilidad");
          idProbabilidad.find("option").remove();

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.descProbabilidad,
              value: v.idProbabilidad,
            });
            if (data.idProbabilidad == v.idProbabilidad) {
              $option.attr("selected", "selected");
            }
            idProbabilidad.append($option);
          });
        },
      });

      $.ajax({
        url: serve + "obtenerEquipos",
        type: "GET",
        success: function (r) {
          var idEquipo = $("#idEquipo");
          idEquipo.find("option").remove();

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.descEquipo,
              value: v.idEquipo,
            });
            if (data.idEquipo == v.idEquipo) {
              $option.attr("selected", "selected");
            }
            idEquipo.append($option);
          });
        },
      });

      $.ajax({
        url: serve + "getEquipos",
        type: "GET",
        data: {
          idEquipo: data.idEquipo,
        },
        success: function (r) {
          var idAgente = $("#idAgente");
          idAgente.find("option").remove();

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.Nombre,
              value: v.Id_Usuario,
            });
            if (data.id_Agente == v.Id_Usuario) {
              $option.attr("selected", "selected");
            }
            idAgente.append($option);
          });
          validarRequeridosOpor();
        },
      });

      $.ajax({
        url: serve + "getCotizadores",
        type: "GET",
        success: function (r) {
          var idAtendido = $("#idAtendido");
          idAtendido.find("option").remove();

          idAtendido.append("<option selected disabled>Atendido por</option>");

          $(r).each(function (i, v) {
            var $option = $("<option></option>", {
              text: v.Nombre,
              value: v.Id_Usuario,
            });
            if (data.idAtendido == v.Id_Usuario) {
              $option.attr("selected", "selected");
            }
            idAtendido.append($option);
          });
          loaderModalOut();
        },
      });

      fecPrevCiereInput.value = data.fecPrevCiere;

      if (data.Maquila == "on") {
        Maquila.checked = true;
      } else {
        Maquila.checked = false;
      }

      notaRetencion.readOnly = true;
      notaRetencion.style.borderColor = "silver";
      Retencion.checked = false;

      if (data.notaRetencion != null) {
        Retencion.checked = true;
        notaRetencion.readOnly = true;
        validarRequeridosOporNoActivos();
      }

      notaRetencion.value = data.notaRetencion;
      fecAlta.value = dateFormat(data.fecCreacion, "dd-mm-yyyy HH:MM");
      Creado.value = data.Creado;
      if (data.fecUltimoCambio != null) {
        fecUltimoCambio.value = dateFormat(
          data.fecUltimoCambio,
          "dd-mm-yyyy HH:MM"
        );
      } else {
        fecUltimoCambio.value = null;
      }
      idUsuarioUltCambio.value = data.UsuarioUltCambio;
      nota.value = data.nota;

      validarRequeridosOpor();

      $("#modal-oportunidades-edit").modal("show");

      if (numOrdenMTC.value == "Orden Metrics" || numOrdenMTC.value == "") {
        tabEntregasOport.style.display = "none";
      } else {
        tabEntregasOport.style.display = "block";
      }

      if (
        dateFormat($("#FechaAutoriza").val(), "yyyy-mm-dd") <
        dateFormat(dateToday, "yyyy-mm-dd")
      ) {
        $("#divBtnSolFecEntrega").removeClass("disable");
      }

      // if (notaretencion.value == "" && Retencion.checked == "undefinided" || notaretencionVal == null && retencionCheck == "undefinided") {
      //     $("#notaRetencion").prop("readonly", true);
      // } else {
      //     $("#notaRetencion").prop("readonly", true);
      // }
    },
    error: function () {},
  });
}

function validarEstatus(id_Estatus, id_MotivoPerdida, cancelada){

  idStatusPre.disabled = true;
  idStatusSolCot.disabled = true;
  idStatusListoCot.disabled = true;
  idStatusCot.disabled = true;
  idStatusRev.disabled = true;
  idStatusVoBo.disabled = true;
  idStatusListoPre.disabled = true;
  idStatusPreTerm.disabled = true;
  idStatusGanado.disabled = true;
  idStatusPerdido.disabled = true;
  idStatusSolOP.disabled = true;
  idStatusOPCon.disabled = true;

  switch (id_Estatus) {
    case "2":
      idStatusSolCot.checked = true;
      idStatusSolCot.disabled = false;
      idStatusListoCot.disabled = false;

      Retencion.disabled = false;
      break;
    case "3":
      idStatusListoCot.checked = true;
      idStatusListoCot.disabled = false;
      if (idAtendido.value == "Atendido por") {
        idStatusCot.disabled = true;
        if (idRol.value == 2 || idRol.value == 10 || idRol.value == 1016) {
          Swal.fire({
            title: "¡No puedes cambiar de estatus!",
            text: "¡Tienes que asignar un Cotizador!",
            confirmButtonText: "Aceptar",
          });
        }
      } else {
        idStatusCot.disabled = false;
      }

      Retencion.disabled = false;
      break;
    case "4":
      idStatusCot.checked = true;
      idStatusCot.disabled = false;
      idStatusRev.disabled = false;

      Retencion.disabled = false;
      break;
    case "5":
      idStatusRev.checked = true;
      idStatusRev.disabled = false;
      idStatusVoBo.disabled = false;

      Retencion.disabled = false;
      break;
    case "6":
      idStatusVoBo.checked = true;
      idStatusVoBo.disabled = false;
      idStatusListoPre.disabled = false;

      Retencion.disabled = false;
      break;
    case "7":
      idStatusListoPre.checked = true;
      idStatusListoPre.disabled = false;
      idStatusPreTerm.disabled = false;

      Retencion.disabled = false;
      break;
    case "8":
      idStatusPreTerm.checked = true;
      idStatusPreTerm.disabled = false;
      idStatusGanado.disabled = false;
      idStatusPerdido.disabled = false;

      Retencion.disabled = true;
      break;
    case "9":
      idStatusGanado.checked = true;
      idStatusGanado.disabled = false;
      idStatusPerdido.disabled = false;
      break;
    case "10":
      idStatusPerdido.checked = true;
      if (data.idMotivoPerdida == 5) {
        idStatusGanado.disabled = false;
      } else {
        idStatusGanado.disabled = true;
      }

      Retencion.disabled = true;
      break;
    case "11":
      idStatusSolOP.checked = true;
      idStatusSolOP.disabled = false;
      idStatusOPCon.disabled = false;

      Retencion.disabled = true;
      break;
    case "12":
      idStatusOPCon.checked = true;
      inhabilitarCampos();
      break;
    default:
      idStatusPre.checked = true;
      idStatusPre.disabled = false;
      idStatusSolCot.disabled = false;
      break;
  }

  if ((id_Estatus == 10 && id_MotivoPerdida != 5) || id_Estatus == 12 || cancelada == 1) {
    console.log('Si llega');
    $("#btnCancelar").addClass("disable");
    $("#btnSolFecEntrega").addClass("disable");

    $("#tblOporRetenidas").addClass("disable");
    $("#div-btnrelacionar").addClass("disable");

    // var nodesDocs = document.getElementById("tblDocs").getElementsByTagName("*");
    // nodesDocsIcon = document.getElementsByClassName("icondocsup");
    // for (var j = 0; j < nodesDocsIcon.length; j++) {
    //   nodesDocsIcon[j].onclick("");
    // }

    var nodesEntrega = document
      .getElementById("entregas-oport")
      .getElementsByTagName("*");
    for (var k = 0; k < nodesEntrega.length; k++) {
      nodesEntrega[k].disabled = false;
    }

    $("#idSucursal").prop("disabled", false);

    var nodesIdSucursal = document
      .getElementById("idSucursal")
      .getElementsByTagName("option");
    for (var i = 1; i < 1; i++) {
      nodesIdSucursal[i].disabled = true;
    }

    // console.log(archivoAutorizado.checked);
    $("#totalCantidadOP").prop("disabled", true);

    if (archivoAutorizado.checked == true) {
      if (idRol.value == 1013 || idRol.value == 8 || idRol.value == 1) {
        $("#archivoAutorizado").prop("disabled", false);
        $("#archivoAutorizado").addClass("pointer");
        $("#archivoAutorizadoLabel").addClass("pointer");
      } else {
        $("#archivoAutorizadoLabel").prop("disabled", true);
        $("#archivoAutorizadoLabel").removeClass("pointer");
      }
    } else {
      $("#archivoAutorizado").prop("disabled", false);
      // $('#archivoAutorizadoLabel').prop('onclick', autorizarArchivo(idOportunidad.value));
      $("#archivoAutorizadoLabel").addClass("pointer");
    }
    // $('#fecEntrega').prop('disabled', false);
    // $('#fecEntregaInput').prop('disabled', false);
  }

  if (cancelada == 1) {
    isCancelled.style.display = "block";
    $("#btnSolFecEntrega").addClass("disable");
    $("#btnTEntrega").addClass("disable");
} else {
    isCancelled.style.display = "none";
  }


}

$("#fecPrevCiereInput").appendDtpicker({
  locale: "es",
  futureOnly: true,
  dateOnly: true,
  closeOnSelected: true,
  dateFormat: "DD-MM-YYYY",
  todayButton: false,
  closeButton: false,
  allowWdays: true,
  autodateOnStart: false,
  amPmInTimeList: true,
  timelistScroll: true,
  calendarMouseScroll: false,
  allowWdays: [1, 2, 3, 4, 5],
  onSelect: function (handler, targetDate) {
    validarRequeridosOpor();
  },
});

fecPrevCiereInput.addEventListener("focusout", function () {
  validarRequeridosOpor();
});

idEquipo.addEventListener("change", function () {
  idAgente.value = "Requerido";
  validarRequeridosOpor();
});

idEquipo.addEventListener("focusout", function () {
  validarRequeridosOpor();
});

idAgente.addEventListener("change", function () {
  validarRequeridosOpor();
});

idAgente.addEventListener("focusin", function () {
  validarRequeridosOpor();
});

idAtendido.addEventListener("change", function () {
  if ($("#Estatus").val() == 3) {
    $("#idStatusPre").prop("disabled", true);
    $("#idStatusSolCot").prop("disabled", true);
    $("#idStatusListoCot").prop("disabled", false);
    if (idAtendido.value != "Atendido por") {
      $("#idStatusCot").prop("disabled", false);
    } else {
      $("#idStatusCot").prop("disabled", true);
    }
    $("#idStatusRev").prop("disabled", true);
    $("#idStatusVoBo").prop("disabled", true);
    $("#idStatusListoPre").prop("disabled", true);
    $("#idStatusPreTerm").prop("disabled", true);
    $("#idStatusGanado").prop("disabled", true);
    $("#idStatusPerdido").prop("disabled", true);
    $("#idStatusSolOP").prop("disabled", true);
    $("#idStatusOPCon").prop("disabled", true);
  }
});

$("#idEquipo").change(function () {
  var agente = $("#idAgente");
  var equipos = $(this);

  if ($(this).val() != "") {
    $.ajax({
      data: {
        idEquipo: equipos.val(),
      },
      url: serve + "getEquipos",
      type: "GET",
      dataType: "json",
      beforeSend: function () {
        equipos.prop("disabled", true);
      },
      success: function (r) {
        equipos.prop("disabled", false);

        agente.find("option").remove();

        agente.append(
          '<option value="Requerido" selected disabled>Requerido</option>'
        );

        $(r).each(function (i, v) {
          // indice, valor
          agente.append(
            '<option value="' + v.Id_Usuario + '">' + v.Nombre + "</option>"
          );
        });

        agente.prop("disabled", false);
        validarRequeridosOpor();
        console.log(idAgente.value);
      },
      error: function (r) {
        Swal.fire(
          "Ocurrio un error en el servidor, no se obtuvieron los agentes..."
        );
        equipos.prop("disabled", false);
      },
    });
  } else {
    agente.find("option").remove();
    agente.prop("disabled", true);
  }
});

$('input:radio[name="idStatus"]').change(function () {
  if (this.checked && this.value == "2") {
    $.ajax({
      url: serve + "getEPXOport",
      type: "GET",
      data: {
        idOportunidad: idOportunidadEdit.value,
      },
      success: function (data) {
        if (data.length > 0) {
          eps = data[0].EPs;
        } else {
          eps = 0;
        }

        if (eps == 0) {
          Swal.fire({
            title: "¡No puedes cambiar de estatus!",
            text: "¡Falta crear o relacionar un Elemento Presupuestado!",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            mostrarElementosOport();
          });
          $("#idStatusPre").prop("checked", true);
        }
      },
      error: function (data) {
        eps = 0;
        Swal.fire("Ocurrio un error en el servidor ..");
      },
    });
  } else if (this.checked && this.value == "7") {
    if ($("#Valor").val() <= 0) {
      Swal.fire({
        title: "¡No puedes cambiar de estatus!",
        text: "¡Falta Asignar el folio del Presupuesto de Metrics!",
        confirmButtonText: "Aceptar",
      }).then((result) => {
        mostrarElementosOport();
      });
      $("#idStatusVoBo").prop("checked", true);
    }
  } else if (this.checked && this.value == "8") {
    numOrdenMTC.disabled = true;
    idMotivoPerdida.value = "Motivo Perdida";
    idMotivoPerdida.disabled = true;
    $("#numOrdenMTC").prop("required", false);
    $("#idMotivoPerdida").prop("required", false);
    Retencion.disabled = true;
    Retencion.checked = false;
    notaRetencion.value = "";
    notaRetencion.readOnly = true;
    notaRetencion.required = false;
    notaRetencion.style.borderColor = "silver";

    $.ajax({
      url: serve + "getDocumentosXidOportunidad",
      type: "GET",
      data: {
        idOportunidad: idOportunidadEdit.value,
      },
      success: function (data) {
        if (data.length > 0) {
          documento = data[0].NoDocs;
        } else {
          documento = 0;
        }

        if (documento == 0) {
          Swal.fire({
            title: "¡No puedes cambiar de estatus!",
            text: "¡Falta subir el documento del presupuesto!",
            confirmButtonText: "Aceptar",
          }).then((result) => {
            mostrarDocumentosOport();
          });
          $("#idStatusListoPre").prop("checked", true);
        }
      },
      error: function (data) {
        documento = 0;
        Swal.fire("Ocurrio un error en el servidor ..");
      },
    });
  } else if (this.checked && this.value == "9") {
    numOrdenMTC.disabled = true;
    idMotivoPerdida.value = "Motivo Perdida";
    idMotivoPerdida.disabled = true;
    $("#numOrdenMTC").prop("required", false);
    $("#idMotivoPerdida").prop("required", false);
  } else if (this.checked && this.value == "10") {
    numOrdenMTC.disabled = true;
    // tabEntregasOport.style.display = 'none';
    idMotivoPerdida.disabled = false;
    $("#idMotivoPerdida").prop("required", true);
    $("#numOrdenMTC").prop("required", false);
  } else if (this.checked && this.value == "11") {
    numOrdenMTC.disabled = true;
    idMotivoPerdida.disabled = true;
    $("#idMotivoPerdida").prop("required", false);
    $("#numOrdenMTC").prop("required", false);
  } else if (this.checked && this.value == "12") {
    numOrdenMTC.disabled = false;
    idMotivoPerdida.disabled = true;
    $("#idMotivoPerdida").prop("required", false);
    $("#numOrdenMTC").prop("required", true);
    $("#numOrdenMTC").focus();
    $("#oportBtnSave").prop("disabled", true);
    // tabEntregasOport.style.display = 'block';
  } else {
    numOrdenMTC.disabled = true;
    idMotivoPerdida.disabled = true;
    $("#numOrdenMTC").prop("required", false);
    $("#idMotivoPerdida").prop("required", false);
    Retencion.disabled = false;
  }
});

Retencion.addEventListener("change", function () {
  activarNotaRetencion(Retencion.checked);
});

function activarNotaRetencion(check) {
  if (check == true) {
    $("#notaRetencion").prop("readonly", false);
    $("#notaRetencion").prop("required", true);

    if (notaRetencion.value == "") {
      notaRetencion.style.borderColor = "red";
      oportBtnSave.disabled = true;
    } else {
      notaRetencion.style.borderColor = "green";
      oportBtnSave.disabled = false;
    }
  } else {
    notaRetencion.value = "";
    $("#notaRetencion").prop("readonly", true);
    $("#notaRetencion").prop("required", false);
    notaRetencion.style.borderColor = "silver";
    oportBtnSave.disabled = false;
  }
}

notaRetencion.addEventListener("change", function () {
  validarRequeridosOporNoActivos();
});

function validarRequeridosOporNoActivos() {
  if (notaRetencion.value == "") {
    notaRetencion.style.borderColor = "red";
    oportBtnSave.disabled = true;
  } else {
    notaRetencion.style.borderColor = "green";
    oportBtnSave.disabled = false;
  }
}

function inhabilitarCampos() {
  var nodes_general = document
    .getElementById("tabGeneral")
    .getElementsByTagName("*");
  for (var i = 0; i < nodes_general.length; i++) {
    nodes_general[i].disabled = true;
  }

  oportBtnSave.disabled = true;

}

function habilitarCampos() {
  var nodes_general = document
    .getElementById("tabGeneral")
    .getElementsByTagName("*");
  for (var i = 0; i < nodes_general.length; i++) {
    nodes_general[i].disabled = false;
  }

  oportBtnSave.disabled = false;

}

function validarRequeridosOpor() {
  var btnsave = document.getElementById("oportBtnSave");
  // var omtc = document.getElementById('numOrdenMTC');
  // var motivo_perdida = document.getElementById('idMotivoPerdida');
  // var retencion = document.getElementById('Retencion');
  // var notaretencion = document.getElementById('notaRetencion');
  // var btnCancelar = document.getElementById('btnCancelar');
  // var isCancelled = document.getElementById('isCancelled');

  var idcliente = document.getElementById("idCliente");
  var nomoportunidad = document.getElementById("nomOportunidad");
  var fecPrevCiereInput = document.getElementById("fecPrevCiereInput");
  // var notaretencionVal = $('#notaRetencion').val();
  // var retencionCheck = $('#Retencion').checked;
  // var cancelada = $('#Cancelada').val();
  // $("#notaRetencion").prop("readonly", true);
  // var idAtendido = document.getElementById('idAtendido');
  var idEquipo = document.getElementById("idEquipo");
  var idAgente = document.getElementById("idAgente");
  // var idOportunidad = document.getElementById('idOportunidad');
  // var idRol = document.getElementById('idRol');
  // var tabEntregasOport = document.getElementById('tabEntregasOport');
  // var btnSolFecEntrega = document.getElementById('btnSolFecEntrega');
  // var archivoAutorizado = document.getElementById('archivoAutorizado');

  if (
    nomoportunidad.value == "" ||
    idcliente.value == "Requerido" ||
    idcliente.value == "requerido" ||
    fecPrevCiereInput.value == "" ||
    idEquipo.value == "Requerido" ||
    idAgente.value == "Requerido"
  ) {
    btnsave.disabled = true;
  } else {
    btnsave.disabled = false;
  }

  if (nomoportunidad.value == "") {
    nomoportunidad.style.borderColor = "red";
  } else {
    nomoportunidad.style.borderColor = "green";
  }

  if (idcliente.value == "Requerido" || idcliente.value == "requerido") {
    idcliente.style.borderColor = "red";
  } else {
    idcliente.style.borderColor = "green";
  }

  if (fecPrevCiereInput.value == "") {
    fecPrevCiereInput.style.borderColor = "red";
  } else {
    fecPrevCiereInput.style.borderColor = "green";
  }

  if (idEquipo.value == "Requerido") {
    idEquipo.style.borderColor = "red";
  } else {
    idEquipo.style.borderColor = "green";
  }
  if (idAgente.value == "Requerido" || idAgente.value == "") {
    idAgente.style.borderColor = "red";
  } else {
    idAgente.style.borderColor = "green";
  }
}

function agregarCantidad() {
  cantidad = $("#cantidadSucursal").val();
  idSucursalEnt = $("#idSucursal").val();
  idOportunidad = $("#idOportunidad").val();
  idCliente = $("#idClienteEnt").val();
  totalCantidadOP = $("#totalCantidadOP").val();
  fecEntregaInput = $("#fecEntregaInput").val();

  if (
    idSucursalEnt == "Sucursal" ||
    idSucursalEnt == 0 ||
    idSucursalEnt == null
  ) {
    Swal.fire("¡Selecciona una Sucursal!");
  } else if (cantidad == "cantidad" || cantidad == "") {
    Swal.fire("¡Captura la cantidad!");
  } else if (fecEntregaInput == "Fecha Entrega" || fecEntregaInput == "") {
    Swal.fire("¡Captura la Fecha de Entrega!");
  } else {
    $.ajax({
      url: serve + "agregarCantidad",
      type: "POST",
      data: {
        _method: "POST",
        idCliente: idCliente,
        idOportunidad: idOportunidad,
        idSucursalEnt: idSucursalEnt,
        cantidad: cantidad,
        fecEntregaInput: fecEntregaInput,
        totalCantidadOP: totalCantidadOP,
        _token: CSRF_TOKEN,
      },
      success: function (data) {
        const Msg = Swal.mixin({
          toast: true,
          position: "center",
          icon: "success",
          showConfirmButton: false,
          timer: 3000,
        });

        Msg.fire({
          title: "Cantidad Agregada",
        });

        limpiarCampos();
      },
      error: function (data) {
        const Msg = Swal.mixin({
          toast: true,
          position: "center",
          icon: "warning",
          showConfirmButton: false,
          timer: 3000,
        });

        Msg.fire({
          title: "La cantidad a entregar supera la cantidad de la OP",
        });

        limpiarCampos();
      },
    });
  }
}

function limpiarCampos() {
  $("#cantidadSucursal").val(null);
  $("#idSucursal").val("Sucursal");
  $("#fecEntregaInput").val("Fecha Entrega");

  tableEntregasOportunidades.ajax.reload();
}

function eliminarCantidad(id) {
  mensaje = "Cantidad Eliminada";
  Swal.fire({
    title: "Entregas Oportunidad",
    text: "Eliminar la cantidad Seleccionada?",
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
      $.ajax({
        url: serve + "eliminarCantidad",
        type: "PATCH",
        data: {
          _method: "PATCH",
          id: id,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
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
          tableEntregasOportunidades.ajax.reload();
        },
        error: function () {
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
        },
      });
    }
  });
}

var DiasEntrega = document.getElementById("DiasEntrega");
var FechaAutoriza = document.getElementById("FechaAutorizaEnt");
var FechaEntrega = document.getElementById("FechaEntregaEnt");

$("#modal-tiempo-entrega").draggable({
  handle: ".titulo",
});

$("#modal-tiempo-entrega").on("shown.bs.modal", function () {
  $("#DiasEntrega").trigger("focus");
  $("#saveBtnTEntrega").prop("disabled", false);
});

function openTiempoEntregaModal(Oportunidad, Usuario, idTiempoEntrega) {
  var idOportunidadTEntrega = document.getElementById("idOportunidadTEntrega");
  var idUsuarioTEntrega = document.getElementById("idUsuarioTEntrega");
  var idTiempoEntregaOprotunidad = document.getElementById(
    "idTiempoEntregaOprotunidad"
  );
  var dias = document.getElementById("dias");
  var fecha = document.getElementById("fecha");
  var comentarios = document.getElementById("Comentarios");

  $("#modal-tiempo-entrega").modal("show");
  $("#modal-tiempo-entrega form").trigger("reset");

  idOportunidadTEntrega.value = Oportunidad;
  idUsuarioTEntrega.value = Usuario;
  idTiempoEntregaOprotunidad.value = idTiempoEntrega;

  DiasEntrega.disabled = true;
  DiasEntrega.required = true;

  FechaAutoriza.required = true;
  FechaEntrega.required = true;

  FechaAutoriza.disabled = true;
  FechaEntrega.disabled = true;

  if (idTiempoEntrega > 0) {
    $.ajax({
      url: serve + "getTiempoEntrega/" + idTiempoEntrega,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        if (data.DiasEntrega != null) {
          DiasEntrega.value = data.DiasEntrega;
          FechaAutoriza.value = "";
          FechaEntrega.value = "";

          dias.checked = true;
          DiasEntrega.disabled = false;
          fecha.checked = false;
          FechaAutoriza.disabled = true;
          FechaEntrega.disabled = true;
        } else {
          DiasEntrega.value = "";
          FechaAutoriza.value = dateFormat(data.FechaAutoriza, "yyyy-mm-dd");
          FechaEntrega.value = dateFormat(data.FechaEntrega, "yyyy-mm-dd");

          dias.checked = false;
          DiasEntrega.disabled = true;
          fecha.checked = true;
          FechaAutoriza.disabled = false;
          FechaEntrega.disabled = false;
        }
        comentarios.value = data.Comentarios;
      },
      error: function () {
        const Msg = Swal.mixin({
          toast: true,
          position: "center",
          icon: "success",
          showConfirmButton: false,
          timer: 3000,
        });

        Msg.fire({
          title: "Algo salió mal...",
        });
      },
    });
  }

  loaderModalOut();
}

$('input:radio[name="diasOFecha"]').change(function () {
  var fecArchivoAutorizado = document.getElementById("fecArchivoAutorizado");

  if (this.checked && this.value == "dias") {
    $.ajax({
      url: serve + "getOportunidad",
      type: "GET",
      data: {
        idOportunidad: id,
      },
      success: function (data) {
        if (data.fecArchivoAutorizado != null) {
          fecArchivoAutorizado.value = dateFormat(
            data.fecArchivoAutorizado,
            "yyyy-mm-dd"
          );
        } else {
          fecArchivoAutorizado.value = null;
        }
      },
    });

    DiasEntrega.disabled = false;

    DiasEntrega.required = true;

    FechaAutoriza.disabled = true;
    FechaEntrega.disabled = false;
    FechaEntrega.readOnly = true;
    // $('#FechaEntrega').prop('readonly', true);

    DiasEntrega.required = true;
    FechaAutoriza.required = false;
    FechaEntrega.required = false;

    FechaAutoriza.value = "";
    FechaEntrega.value = "";

    FechaAutoriza.required = false;
    FechaEntrega.required = false;
  } else {
    DiasEntrega.disabled = true;

    DiasEntrega.required = false;

    FechaEntrega.required = true;

    FechaAutoriza.readOnly = false;
    FechaAutoriza.disabled = false;
    FechaEntrega.readOnly = false;
    FechaEntrega.disabled = false;

    DiasEntrega.required = false;
    FechaAutoriza.required = false;
    FechaEntrega.required = true;

    DiasEntrega.value = "";
    $("#fecArchivoAutorizado").val("");
  }
});

$("#DiasEntrega").change(function () {
  fechaAut = document.getElementById("fecArchivoAutorizado");
  diasEntrega = parseInt(this.value);

  fechEntrega = getDiasHabiles(fechaAut.value, diasEntrega);

  $("#FechaEntregaEnt").val(dateFormat(fechEntrega, "yyyy-mm-dd"));
});

// $('#FechaEntrega').change(function() {
//     fechaAut = document.getElementById('FechaAutoriza');

//     diasHabiles = calculardias(fechaAut.value, this.value);

//     console.log(diasHabiles);

//     $('#DiasEntrega').val(diasHabiles);
// });

$("#saveBtnTEntrega").click(function () {
  addOrEditTiempoEntrega();
});

function addOrEditTiempoEntrega() {
  $("#saveBtnTEntrega").prop("disabled", true);
  // var DiasEntrega = document.getElementById('DiasEntrega');
  // var FechaAutoriza = document.getElementById('FechaAutoriza');
  // var FechaEntrega = document.getElementById('FechaEntrega');
  // var idOportunidadTEntrega = document.getElementById('idOportunidadTEntrega');
  // var idUsuarioTEntrega = document.getElementById('idUsuarioTEntrega');
  // var idTiempoEntregaOprotunidad = document.getElementById('idTiempoEntregaOprotunidad');

  var Autorizo = document.getElementById("Autorizo");
  var Comentarios = document.getElementById("Comentarios");
  var fecArchivoAutorizado = document.getElementById("fecArchivoAutorizado");

  var dias = document.getElementById("dias");
  var fecha = document.getElementById("fecha");

  if (dias.checked == false && fecha.checked == false) {
    Swal.fire("¡Selecciona una opción!");
  } else {
    if (dias.checked == true && DiasEntrega.value == "") {
      Swal.fire("¡Captura los Días de Entrega!");
    } else if (fecha.checked == true && FechaAutoriza.value == "") {
      Swal.fire("¡Captura la Fecha del Archivo!");
    } else if (fecha.checked == true && FechaEntrega.value == "") {
      Swal.fire("¡Captura la Fecha de Entrega!");
    } else {
      loaderON();

      $.ajax({
        url: serve + "addOrEditTiempoEntrega",
        type: "PATCH",
        data: {
          idOportunidadTEntrega: idOportunidadTEntrega.value,
          idUsuarioTEntrega: idUsuarioTEntrega.value,
          idTiempoEntregaOprotunidad: idTiempoEntregaOprotunidad.value,
          DiasEntrega: DiasEntrega.value,
          FechaAutoriza: FechaAutoriza.value,
          FechaEntrega: FechaEntrega.value,
          Autorizo: Autorizo.value,
          Comentarios: Comentarios.value,
          FechaArchivoAutorizado: fecArchivoAutorizado.value,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
          const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "success",
            showConfirmButton: false,
            timer: 3000,
          });

          Msg.fire({
            title: "Archivo Guardado",
          });

          $("#modal-tiempo-entrega").modal("hide");
          loaderOut();
          tblTEntrega.ajax.reload();
        },
        error: function () {
          const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "warning",
            showConfirmButton: false,
            timer: 3000,
          });

          Msg.fire({
            title: "Ocurrió un error, vuelve a intentarlo",
          });

          $("#modal-tiempo-entrega").modal("hide");
          tblTEntrega.ajax.reload();
          loaderOut();
        },
      });
    }
  }
}

function solicitarFechaEntrega() {
  mensaje = "Solicitud Enviada";
  Swal.fire({
    title: "Solicitar Fecha",
    text: "Deseas solicitar la fecha de entrega para esta Oportunidad?",
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
      $("#swal2-input").prop("readonly", false);
      Swal.fire({
        input: "textarea",
        inputLabel: "Nota",
        inputPlaceholder: "Captura la Nota de la Solicitud",
        inputAttributes: {
          "aria-label": "Captura la Nota de la Solicitud",
        },
        showCancelButton: true,
        cancelButtonText: "Cancelar",
        confirmButtonText: "Guardar",
        // target: document.getElementById('modal-create-envio'),
      }).then((result) => {
        if (result.isConfirmed) {
          if (result.value == null || result.value == "") {
            Swal.fire("¡Debes capturar la Nota!");
          } else {
            loaderON();
            $.ajax({
              url: serve + "solicitarFechaEntrega",
              type: "PATCH",
              data: {
                _method: "PATCH",
                id: id,
                notaSolicitud: result.value,
                _token: CSRF_TOKEN,
              },
              success: function (data) {
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
                $("#btnSolFecEntrega").addClass("disable");
              },
              error: function () {
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
        }
      });
    }
  });
}

function autorizarArchivo(idOport) {
  mensaje = "Archivo Autorizado";
  Swal.fire({
    title: "Autorizar Archivo",
    text: "Deseas autorizar el archivo para esta Oportunidad?",
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
        url: serve + "autorizarArchivoOportunidad",
        type: "PATCH",
        data: {
          _method: "PATCH",
          idOport: idOport,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
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
          $("#archivoAutorizado").prop("disabled", true);
          // checkArchivoAutorizado(idOport);
        },
        error: function () {
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

      $.ajax({
        url: serve + "GetMaxDiasEntrega",
        type: "GET",
        data: {
          _method: "GET",
          idOport: idOport,
        },
        success: function (data) {
          if (data.DiasEntrega != null) {
            fechaEntrega = getDiasHabiles(
              dateFormat(data.FechaArchivoAutorizado, "yyyy-mm-dd"),
              parseInt(data.DiasEntrega)
            );
            $.ajax({
              url: serve + "updateFechaEntregaDias",
              type: "PATCH",
              data: {
                _method: "PATCH",
                idOport: data.idOportunidad,
                DiasEntrega: data.DiasEntrega,
                fechaEntrega: fechaEntrega,
                fechaArchivo: data.FechaArchivoAutorizado,
                _token: CSRF_TOKEN,
              },
            });

            tblTEntrega.ajax.reload();
            // } else {
          }
        },
        error: function () {
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
    } else {
      $("#archivoAutorizado").prop("checked", false);
    }
  });
}

function desautorizarArchivo(idOport) {
  mensaje = "Archivo No Autorizado";
  Swal.fire({
    title: "No se Autoriza Archivo",
    text: "Deseas quitar la autorización de el archivo para esta Oportunidad?",
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
        url: serve + "desautorizarArchivoOportunidad",
        type: "PATCH",
        data: {
          _method: "PATCH",
          idOport: idOport,
          _token: CSRF_TOKEN,
        },
        success: function (data) {
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
          $("#archivoAutorizado").prop("checked", false);
          $("#archivoAutorizado").prop("disabled", true);
          tblTEntrega.ajax.reload();
        },
        error: function () {
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
    } else {
      $("#archivoAutorizado").prop("checked", true);
    }
  });
}

function getDiasHabiles(fecha, diasAdd) {
  var hoy = new Date();
  var arrFecha = fecha.split("-");
  var fecha = new Date(arrFecha[0], arrFecha[1] - 1, arrFecha[2]);

  // console.log('Fecha GetDias' + fecha);
  if (hoy.getHours() >= 18) {
    fecha.setDate(fecha.getDate() + 1);
  }

  // $.ajax({
  //     url: serve + 'getHollidays',
  //     type: 'GET',
  //     data: {
  //         year: hoy.getFullYear(),
  //     },
  //     success: function(data) {

  //         var getfestivos = data;

  //         festivos = getfestivos.map(function(o) {

  //             return Object.keys(o).reduce(function(array, key) {
  //                 return array.concat([o[key]]);
  //             }, []);
  //         });
  //     }

  // });

  festivos = [
    // Agregamos los festivos (dia, mes)
    [25, 12],
    [1, 1],
    [6, 2],
    [20, 3],
    [6, 4],
    [7, 4],
    [1, 5],
    [16, 9],
    [20, 11],
    [12, 12],
  ];

  for (var i = 0; i < diasAdd; i++) {
    var diaInvalido = false;
    fecha.setDate(fecha.getDate() + 1); // Sumamos de dia en dia
    for (var j = 0; j < festivos.length; j++) {
      // Verificamos si el dia + 1 es festivo
      var mesDia = festivos[j];
      if (fecha.getMonth() + 1 == mesDia[1] && fecha.getDate() == mesDia[0]) {
        diaInvalido = true;
        break;
      }
    }
    if (fecha.getDay() == 0 || fecha.getDay() == 6) {
      // Verificamos si es sábado o domingo
      diaInvalido = true;
    }

    if (diaInvalido) diasAdd++; // Si es fin de semana o festivo le sumamos un dia
  }
  return (
    fecha.getFullYear() +
    "/" +
    (fecha.getMonth() + 1).toString().padStart(2, "0") +
    "/" +
    fecha.getDate().toString().padStart(2, "0")
  );
}

function calculardias($fecha1, $fecha2) {
  var arrFecha = $fecha1.split("-");
  var $fecha1 = new Date(arrFecha[0], arrFecha[1] - 1, arrFecha[2]);

  var arrFecha2 = $fecha2.split("-");
  var $fecha2 = new Date(arrFecha2[0], arrFecha2[1] - 1, arrFecha2[2]);

  $fecha1.setDate($fecha1.getDate());
  $fecha2.setDate($fecha2.getDate());

  var dif = $fecha2 - $fecha1;
  var dias = Math.floor(dif / (1000 * 60 * 60 * 24));

  // console.log("Días : " + dias);

  if (dias == 7) {
    dias = dias - 2;
  } else {
  }
  // console.log("Día de la fecha 1 : " + $fecha1.getDay());
  // console.log("Día de la fecha 2 : " + $fecha2.getDay());

  // var aFecha1 = $fecha1.split('-');
  // var aFecha2 = $fecha2.split('-');
  // var $fecha1 = Date(aFecha1[0], aFecha1[1] - 1, aFecha1[2]);
  // var $fecha2 = Date(aFecha2[0], aFecha2[1] - 1, aFecha2[2]);

  // $fecha1.setDate($fecha1.getDate());

  return dias;

  // $dato1 = $fecha1.split('-');
  // $dato2 = $fecha2.split('-');

  // //defino fecha 1
  // $ano1 = $dato1[0];
  // $mes1 = $dato1[1];
  // $dia1 = $dato1[2];

  // //defino fecha 2
  // $ano2 = $dato2[0];
  // $mes2 = $dato2[1];
  // $dia2 = $dato2[2];

  // console.log($dia1 + '/' + $mes1 + '/' + $ano1);
  // console.log($dia2 + '/' + $mes2 + '/' + $ano2);

  // //calculo timestam de las dos fechas
  // $timestamp1 = new Date($ano1, $mes1, $dia1, 0, 0, 0, 0).getTime() / 1000;
  // // $timestamp1 = mktime(0, 0, 0, $mes1, $dia1, $ano1);
  // /* echo ("$timestamp1"."<br>");  */
  // $timestamp2 = new Date($ano2, $mes2, $dia2, 0, 0, 0, 0).getTime() / 1000;
  // // $timestamp2 = mktime(4, 12, 0, $mes2, $dia2, $ano2);
  // /* echo ("$timestamp2"."<br>"); */
  // $segundos_diferencia = $timestamp2 - $timestamp1; //resto a una fecha la otra */
  // /* echo ("$segundos_diferencia"."<br>"); */
  // console.log($timestamp1 + '/' + $timestamp2);

  // $dias_diferencia = $segundos_diferencia / (60 * 60 * 24); //convierto segundos en días
  // $dias_diferencia = Math.round($dias_diferencia); //obtengo el valor absoulto de los días (quito el posible signo negativo)

  // return $dias_diferencia;
}

// public int diasHabiles(Calendar fechaInicial, Calendar fechaFinal, List<Date> listaFechasNoLaborables) {
//     int diffDays = 0;
//     boolean diaHabil = false;
//     //mientras la fecha inicial sea menor o igual que la fecha final se cuentan los dias
//     while (fechaInicial.before(fechaFinal) || fechaInicial.equals(fechaFinal)) {

//        if (!listaFechasNoLaborables.isEmpty()) {
//            for (Date date : listaFechasNoLaborables) {
//                Date fechaNoLaborablecalendar = fechaInicial.getTime();
//                //si el dia de la semana de la fecha minima es diferente de sabado o domingo
//                if (fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SUNDAY && fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SATURDAY && !fechaNoLaborablecalendar.equals(date)) {
//                    //se aumentan los dias de diferencia entre min y max
//                    diaHabil = true;
//                } else {
//                    diaHabil = false;
//                    break;
//                }
//            }
//        } else {
//            if (fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SUNDAY && fechaInicial.get(Calendar.DAY_OF_WEEK) != Calendar.SATURDAY) {
//                //se aumentan los dias de diferencia entre min y max
//                diffDays++;
//            }
//        }
//        if (diaHabil == true) {
//        diffDays++;
//        }
//        //se suma 1 dia para hacer la validacion del siguiente dia.
//        fechaInicial.add(Calendar.DATE, 1);
//   }
//   return diffDays;
// }

// function getDiasHabilesXFechas(fechaInicio, fechaFin) {

//     var fechaInicio = new Date();
//     var fechaFin = new Date();

//     var FechasFestivas = [
//         [1],
//         [7],
//         [21],
//         [14, 15],
//         [40],
//         [40],
//         [40],
//         [40],
//         [16],
//         [40],
//         [21],
//         [12, 25],
//     ];

//     fechaFin.setDate(fechaFin.getDate());
//     var diaSaDo = 0;
//     var diafestivo = 0;
//     var dom = 0;
//     var sab = 6;

//     while (fechaInicio.getDate() < fechaFin.getDate()) {
//         // document.getElementById("a").prepend("dia inicio = " + fechaInicio.getDate() + "---");
//         if (fechaInicio.getDay() == dom || fechaInicio.getDay() == sab) {
//             diaSaDo++;
//         } else {
//             for (var i = 0; i <= FechasFestivas[fechaIncio.getMonth()].length; i++) {
//                 if (fechaInicio.getDate() == FechasFestivas[fechaInicio.getMonth()][i]) {
//                     diafestivo++;
//                 }
//             }
//         }
//         fechaInicio.setDate(fechaInicio.getDate() + 1);
//     }

//     fechaInicio.setDate(fechaInicio.getDate() + diaSaDo + diafestivo);

//     console.log('Fecha Inicio :' + fechaInicio + ', Fecha Fin : ' + fechaFin);

//     // return fechaInicio.getFullYear() + '/' + (fecha.getMonth() + 1).toString().padStart(2, '0') + '/' + fecha.getDate().toString().padStart(2, '0');

// }

// function stringToDate(dateString) {
//     dateString = dateString.split('-');
//     return new Date(dateString[0], dateString[1] - 1, dateString[2]);
// }

// function countWorkDay(sDay, eDay) {
//     var s = stringToDate(sDay),
//         e = stringToDate(eDay);
//     var s_t_w = s.getDay(),
//         e_t_w = e.getDay();

//     // Diferencia en días
//     var diffDay = (e - s) / (1000 * 60 * 60 * 24) + 1;
//     var diffWeekDay = diffDay - (s_t_w == 0 ? 0 : 7 - s_t_w) + e_t_w;
//     // El cálculo tiene varias semanas completas
//     var weeks = Math.floor(diffWeekDay / 7);
//     if (weeks <= 0) { // En la misma semana, es decir, la hora de inicio y finalización no puede ser el domingo y el sábado (el domingo es el primer día de la semana)
//         return e_t_w - s_t_w + (s_t_w ? 1 : 0) + (e_t_w == 6 ? -1 : 0);
//     } else {
//         return weeks * 5 + (e_t_w == 6 ? 5 : e_t_w) + (s_t_w >= 1 && s_t_w <= 5 ? (6 - s_t_w) : 0);
//     }
// }

// console.log(countWorkDay('2017-08-01', '2017-08-06'));

// function countWorkDay(date1, date2){
//     date1 = stringToDate(date1);
//     date2 = stringToDate(date2);
//     delta = (date2-date1) / (1000 * 60 * 60 * 24) + 1; // calcula el tiempo total

//     weeks = 0;
//     for(i = 0; i < delta; i++){
//                      if (date1.getDay () == 0 || date1.getDay () == 6) semanas ++; // agrega 1 si es sábado o domingo
//         date1 = date1.valueOf();
//         date1 += 1000 * 60 * 60 * 24;
//         date1 = new Date(date1);
//     }
//     return delta - weeks;
// }
// console.log(countWorkDay('2017-08-01', '2017-08-06')); // 4

function obtenerSolicitudesOP() {
  $.ajax({
    url: serve + "obtenerOportunidadSolOPCincoDias",
    type: "GET",
    dataType: "json",
    success: function (data) {
      data.forEach((element) => {
        flasher.toastr.info(
          "Solicitó OP para la Oportunidad " + element.folio,
          element.AgenteVentas
        );
      });
    },
    error: function () {},
  });
}

function obtenerOportunidadPTVeinteDias() {
  $.ajax({
    url: serve + "obtenerOportunidadPTVeinteDias",
    type: "GET",
    dataType: "json",
    success: function (data) {
      btnAgregarOportunidad = document.getElementById("btnAgregarOportunidad");
      const Msg = Swal.mixin({
        toast: false,
        position: "center",
        icon: "error",
        showConfirmButton: true,
        customClass: {
          popup: "colored-toast",
        },
      });
      if (btnAgregarOportunidad != null) {
        if (data.length > 0) {
          $("#btnAgregarOportunidad").addClass("disabled-agregar");
          Msg.fire({
            title:
              data[0].Nombre +
              "\nLa Oportunidad No. " +
              data[0].folio +
              '\n tiene más de 20 días sin definir\n como "GANADO" o "PERDIDO".\n No podrás agregar una nueva oportunidad.',
          });
          // flasher.toastr.info('Tienes al menos una Oportunidad\n con mas de 20 días sin marcar\n como GANADO o PERDIDO.\n No podrás agregar una nueva oportunidad.', data.AgenteVentas);
        } else {
          $("#btnAgregarOportunidad").removeClass("disabled-agregar");
          // btnAgregarOportunidad.style.cursor = 'pointer !important';
        }
      }
    },
    error: function () {},
  });
}

function obtenerOportunidadSolOPCincoDias() {
  $.ajax({
    url: serve + "obtenerOportunidadSolOPCincoDias",
    type: "GET",
    dataType: "json",
    success: function (data) {
      const Msg = Swal.mixin({
        toast: false,
        position: "center",
        icon: "error",
        showConfirmButton: true,
        customClass: {
          popup: "colored-toast",
        },
      });
      if (data.length > 0) {
        Msg.fire({
          title:
            data[0].Customer +
            "\nLa Oportunidad No. " +
            data[0].folio +
            "\n tiene más de 5 días de que\n solicitó OP.\n Favor de atenderla.",
        });
      } else {
      }
    },
    error: function () {},
  });
}

function clonarOportunidad() {
  idOportunidad = $("#idOportunidad").val();

  Swal.fire({
    // toast: true,
    title: "Oportunidades",
    html: "<div>¿Clonar esta Oportunidad?</div><br><div class='text-warning'><i class='fas fa-exclamation-triangle'></i>&nbsp;&nbsp;&nbsp;Se creará con un folio nuevo y <br> se asociaran los elementos presupuestados.</div>",
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
        url: serve + "clonarOportunidad",
        type: "POST",
        data: {
          idOportunidad: idOportunidad,
          _method: "POST",
          _token: CSRF_TOKEN,
        },
        success: function () {
          const Msg = Swal.mixin({
            toast: true,
            position: "center",
            icon: "success",
            showConfirmButton: false,
            timer: 3000,
          });
          Msg.fire({
            title: "Oportunidad Clonada",
          });
          window.location.href = serve + "oportunidades";
        },
        error: function () {},
      });
    } else {
      loaderOut();
    }
  });
}
