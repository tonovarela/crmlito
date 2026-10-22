serve = base_path;
rutaActual = document.location.href;
group = 2;
var collapsedGroups = {};
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");

$(document).ready(function () {
  datatableUsuarios = $("#tblUsuarios").DataTable({
    processing: true,
    serverSide: true,
    paginate: true,
    order: [[0, "desc"]],
    language: {
      url: serve + "js/spanish-DT.json",
    },
    ajax: {
      url: serve + "api/usuarios",
      type: "GET",
      dataType: "json",
    },
    columns: [
      {
        data: "Id_Usuario",
        name: "Id_Usuario",
        width: "30",
        title: "Numero",
      },
      {
        data: "Nombre",
        render: function (data, type, row) {
          if (row.EstatusCRM == 0 || row.EstatusCRM == null) {
            return data;
          } else {
            return (
              '<a class="text-primary" type="button" onclick="permisosUsuario(' +
              row.Id_Usuario +
              ' )" title="Asignar Permisos">' +
              data +
              "</a>"
            );
          }
        },
        name: "Nombre",
        title: "Nombre",
      },
      {
        data: "EstatusCRM",
        render: function (data, type, row) {
          if (data == 0 || data == null) {
            return (
              '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="candy_' +
              row.Id_Usuario +
              '" ><label class="custom-control-label" style="cursor: pointer"  onclick="accesoCandy(' +
              row.Id_Usuario +
              ')"></label></div>'
            );
          } else {
            return (
              '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="candy_' +
              row.Id_Usuario +
              '" checked><label class="custom-control-label" style="cursor: pointer"  onclick="accesoCandy(' +
              row.Id_Usuario +
              ')"></label></div>'
            );
          }
        },
        name: "EstatusCRM",
        width: "30",
        title: "Acceso CANDY",
      },
      {
        data: "EstatusLitoApps",
        render: function (data, type, row) {
          if (data == 0 || data == null) {
            return (
              '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="litoapps_' +
              row.Id_Usuario +
              '" ><label class="custom-control-label" style="cursor: pointer"  onclick="accesoLito(' +
              row.Id_Usuario +
              ')"></label></div>'
            );
          } else {
            return (
              '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="litoapps_' +
              row.Id_Usuario +
              '" checked><label class="custom-control-label" style="cursor: pointer"  onclick="accesoLito(' +
              row.Id_Usuario +
              ')"></label></div>'
            );
          }
        },
        name: "EstatusLitoApps",
        width: "30",
        title: "Acceso LitoApps",
      },
      {
        data: "EstatusLitoApps",
        render: function (data, type, row) {
          if (data == 0 || data == null) {
            return '<a class="btn-default btn-lg" style="background-color: transparent; cursor: not-allowed"><i class="fas fa-ban" style="color: #DF2530"></i></a>';
          } else {
            return (
              '<a class="btn-default btn-lg" style="background-color: transparent; cursor: pointer" onclick="appsLito(' +
              row.Id_Usuario +
              ')"><i class="fas fa-pencil-alt"></i></a>'
            );
          }
        },
        name: "EstatusLitoApps",
        width: "30",
        title: "Apps Lito",
      },
      {
        data: "Agente",
        name: "Agente",
        width: "30",
        title: "Agente",
      },
      {
        data: "Rol",
        name: "Rol",
        width: "150",
        title: "Rol",
      },
      {
        data: "Correo",
        name: "Correo",
        title: "Correo",
      },
      {
        data: "Departamento",
        name: "Departamento",
        title: "Departamento",
      },
      {
        data: "Puesto",
        name: "Puesto",
        title: "Puesto",
      },
      {
        data: "estatus",
        name: "estatus",
        width: "30",
        title: "Estatus",
      },
      {
        data: "Id_Usuario",
        render: function (data, type, row) {
          return (
            '<a class="btn-default btn-lg" style="background-color: transparent; cursor: pointer" onclick="showAddFirma(' +
            data +
            ')"><i class="fas fa-file-signature"></i></a>'
          );
        },
        name: "Id_Usuario",
        title: "Firma",
      },
    ],
    stateSave: false,
    select: true,
    scrollY: false,
    scrollX: true,
    pageLength: 50,
    columnDefs: [
      {
        className: "dt-center",
        targets: [0, 2, 3, 4, 5, 11],
      },
      {
        targets: 6,
        render: function (data, type, row, meta) {
          if (row.EstatusCRM == 0) {
            return '<span class="ml-3">SIN ASIGNAR</span>';
          } else {
            var $select = $(
              '<select id="roles_grid_' +
                row.Id_Usuario +
                '" class="form-control text-uppercase" style="width: 100%; height: 25px !important; padding-top: 2px"><option selected disabled>SIN ASIGNAR</option></select>'
            );
            $.each(rol, function (k, v) {
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
          }
        },
      },
    ],
    bLengthChange: true,
    bInfo: true,
    lengthMenu: [
      [50, 100, 150, 250],
      [50, 100, 150, 250],
    ],
    autoWidth: true,
    fixedColumns: {
      leftColumns: 4,
    },
    dom: '<"top"Rfl>rt<"bottom"pB><"clear">',
    buttons: [
      {
        extend: "colvis",
        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
        text: '<i class="fas fa-tasks" title="Mostrar / Ocultar Columnas"></i>',
      },
      {
        extend: "excelHtml5",
        title: "Usuarios | CANDY CRM",
        text: '<span title="Exportar a Excel"><i class="fas fa-file-excel"></i></span>',
        exportOptions: {
          orthogonal: "export",
          columns: "thead th:not(.noExport)",
          modifer: {
            serverSide: false,
            page: "all",
          },
        },
        filename: "Usuarios",
        autoFilter: true,
        sheetName: "Lista Usuarios",
        messageTop: null,
        body: true,
        footer: false,
      },
    ],
    drawCallback: function (settings) {
      $('[data-bs-toggle="tooltip"]').tooltip();
    },
    initComplete: function (settings, json) {
      loaderOut();
    },
  });

  $("#tblUsuarios")
    .DataTable()
    .on("draw", function () {
      ajustarTablas();
    });

  $("#tblUsuarios tbody").on("change", "select", function () {
    rol = $(this).find(":selected").val();
    data = datatableUsuarios.row($(this).parents("tr")).data();
    id = data["Id_Usuario"];
    nombre_rol = data["Rol"];
    cambiar_rol(id, rol, nombre_rol);
  });

  id_usuario_permisos = 0;

  $("#modal-permisos-usuario").on("shown.bs.modal", function () {
    id_usuario_permisos = document.getElementById("idUsuario");

    tablePermisos = $("#tablePermisos").DataTable({
      destroy: true,
      processing: true,
      serverSide: true,
      paginate: false,
      order: [[0, "asc"]],
      language: {
        url: serve + "js/spanish-DT.json",
      },
      ajax: {
        url: serve + "api/permisos/" + id_usuario_permisos.value,
        type: "GET",
        dataType: "json",
      },
      columns: [
        {
          data: "idModulo",
          name: "idModulo",
          // width: "60",
          title: "idModulo",
        },
        {
          data: "descModulo",
          render: function (data) {
            return '<span class="text-uppercase">' + data + "</span>";
          },
          name: "descModulo",
          // width: "60",
          title: "Módulo",
        },
        {
          data: "descAccion",
          name: "descAccion",
          title: "Acción",
        },
        {
          data: "idPermiso",
          render: function (data, type, row) {
            if (data == 0) {
              return (
                '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="idPermiso_' +
                row.idModulo +
                '" ><label  class="custom-control-label" style="cursor: pointer"  onclick="asignarPermiso(' +
                row.Id_Usuario +
                ", " +
                row.idModulo +
                ", " +
                data +
                ')"></label></div>'
              );
            } else {
              return (
                '<div class="custom-switch"><input type="checkbox" class="custom-control-input" id="idPermiso_' +
                row.idModulo +
                '" checked><label  class="custom-control-label" style="cursor: pointer"  onclick="asignarPermiso(' +
                row.Id_Usuario +
                ", " +
                row.idModulo +
                ", " +
                data +
                ')"></label></div>'
              );
            }
          },
          name: "idPermiso",
          title: "Permiso",
        },
      ],
      rowGroup: {
        dataSrc: "descModulo",

        startRender: function (rows, group) {
          var collapsed = !!collapsedGroups[group],
            noelmentos = rows.data().pluck("idPermiso");
          var conDatos = $("<tr/>")
            // .append('<td colspan="2"  class="rowcolap"><i class="fas fa-plus-circle iconaddDocs icontbl"></i>&nbsp;&nbsp;' + group + ' - ' + rows.count() + '</td>')
            .append(
              '<td colspan="2"  class="rowcolap"><i class="fas fa-plus-circle iconaddDocs icontbl"></i>&nbsp;&nbsp;' +
                group +
                "</td>"
            )
            .attr("data-name", group.replace(" ", ""))
            .toggleClass("collapsed", collapsed);
          var sinDatos = $("<tr/>")
            .append(
              '<td colspan="2"  class="rowcolap"><i class="fas fa-plus-circle icondocsBloq "></i>&nbsp;&nbsp;' +
                group +
                "</td>"
            )
            .attr("data-name", group.replace(" ", ""));
          $(rows.nodes()).hide();
          conDatos.on("click", function (e) {
            if (collapsedGroups[group]) {
              if ($(rows.nodes()).is(":hidden")) {
                $(rows.nodes()).show();
                $(".icontbl").removeClass("fa-plus-circle");
                $(".icontbl").addClass("fa-minus-circle");
              } else {
                $(rows.nodes()).hide();
                $(".icontbl").addClass("fa-plus-circle");
                $(".icontbl").removeClass("fa-minus-circle");
              }
            } else {
              if ($(rows.nodes()).is(":hidden")) {
                $(rows.nodes()).show();
                $(".icontbl").removeClass("fa-plus-circle");
                $(".icontbl").addClass("fa-minus-circle");
              } else {
                $(rows.nodes()).hide();
                $(".icontbl").addClass("fa-plus-circle");
                $(".icontbl").removeClass("fa-minus-circle");
              }
            }
            collapsedGroups[group] = !collapsedGroups[group];
          });
          if (noelmentos[0] >= 1) {
            return conDatos;
          } else {
            return sinDatos;
          }
        },
      },
      stateSave: false,
      select: true,
      scrollY: "22vh",
      responsive: true,
      scrollX: true,
      autoWidth: true,
      dom: '<"top">rt<"bottom"><"clear">',
      columnDefs: [
        { visible: false, targets: [0, 1] },
        { orderable: false, targets: [2, 3] },
        { className: "dt-center text-center", targets: [3] },
      ],
    });
  });
});

function cambiar_rol(id, rol, nombre_rol) {
  mensaje = "Rol de Usuario Actualizado";
  id_suario = document.getElementById("idUsuario");
  Swal.fire({
    title: "Usuarios",
    text: "Actualizar Rol del Usuario Seleccionado?",
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
        url: serve + "cambiarRol",
        type: "PATCH",
        data: {
          _method: "PATCH",
          id: id,
          rol: rol,
          // id_suario: id_suario.value,
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
          window.location.href = "usuarios";
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
      $("#roles_grid_" + id)
        .find("option")
        .attr("selected", false);
      $("#roles_grid_" + id + '  option:contains("' + nombre_rol + '")').attr(
        "selected",
        true
      );
      window.location.href = "usuarios";
    }
  });
}

$("#modal-permisos-usuario").on("hidden.bs.modal", function () {
  $(this).find("form").trigger("reset");
});

function permisosUsuario(id_usuario) {
  $.ajax({
    url: serve + "usuarios" + "/" + id_usuario + "/edit",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $(".nombre").text(data.Nombre);
      $("#idUsuario").val(id_usuario);

      // if (data.idClienteINT != null) {
      //     document.getElementById("SelectCliente").style.display = "none";
      //     $("#idClienteINT").prop("required", false);
      // }
      // $("#idClienteINTCto").val(data.idClienteINT);
      // $("#idContacto").val(data.idContacto);
      // $("#idContactoINT").val(data.idContactoINT);
      // $("#Tratamiento").val(data.Tratamiento);
      // $("#Nombre").val(data.Nombre);
      // $("#Cargo").val(data.Cargo);
      // $("#Departamento").val(data.Departamento);
      // $("#TelefonosCto").val(data.Telefonos);
      // $("#email").val(data.email);
      // if (data.CFD_Enviar == "0") {
      //     $("#CFD_EnviarCto").prop("checked", false);
      //     $("#CFD_Enviar_input").val("");
      // } else if (data.CFD_Enviar == "1") {
      //     $("#CFD_EnviarCto").prop("checked", true);
      //     $("#CFD_EnviarCto").val("on");
      //     $("#CFD_Enviar_input").val("on");
      // }
      // if (data.contactoCXP == "0") {
      //     $("#contactoCXPCto").prop("checked", false);
      //     $("#contactoCXP_input").val("");
      // } else if (data.contactoCXP == "1") {
      //     $("#contactoCXPCto").prop("checked", true);
      //     $("#contactoCXPCto").val("on");
      //     $("#contactoCXP_input").val("on");
      // }
      // if (data.idContactoINT == ctoPrincipal) {
      //     $("#principalCto").prop("checked", true);
      //     $("#principalCto").val("on");
      //     $("#principal_input").val("on");
      // } else {
      //     $("#principalCto").prop("checked", false);
      //     $("#principal_input").val("");
      // }

      // $("#modal-create-contactoCte").modal("show");
      // $(".titulo").text("Editar Contacto");
      $("#modal-permisos-usuario").modal("show");
    },
    error: function () {
      const Msg = Swal.mixin({
        toast: true,
        position: "center",
        icon: "warning",
        showConfirmButton: false,
        timer: 3000,
      });

      if (id_usuario == null) {
        titleMsg = "Id Nulo";
      } else {
        titleMsg = "Ocurrió un error";
      }
      Msg.fire({
        title: titleMsg,
      });
    },
  });
}

function accesoCandy(id_usuario) {
  check = $("#candy_" + id_usuario).is(":checked") ? 0 : 1;
  Mensaje = "Acceso a Candy";
  ruta = serve + "accesoCandy";
  accesoCandyLito(id_usuario, Mensaje, ruta, check);
}

function accesoLito(id_usuario) {
  check = $("#litoapps_" + id_usuario).is(":checked") ? 0 : 1;
  Mensaje = "Acceso a LitoApps";
  ruta = serve + "accesoLito";
  accesoCandyLito(id_usuario, Mensaje, ruta, check);
}

function appsLito(id_usuario) {
  $.ajax({
    url: serve + "appsLito" + "/" + id_usuario,
    type: "GET",
    dataType: "JSON",
    success: function (data) {
      $(".nombre").text(data[0].Nombre);
      $("#idUsuario").val(data[0].Id_Usuario);

      dominio = document.domain;
      if (dominio == "127.0.0.1") {
        ruta_icono = "https://servicios.litoprocess.com/litoapps/icons/";
      } else if (dominio == "192.168.2.222") {
        ruta_icono = "https://" + dominio + "/litoapps/icons/";
      } else {
        ruta_icono = "https://" + dominio + "/litoapps/icons/";
      }

      $("#panel-cotizador div img").prop("src", ruta_icono + "reportar.png");
      $("#panel-accountability div img").prop(
        "src",
        ruta_icono + "rompecabezas.png"
      );
      $("#panel-almacenPT div img").prop("src", ruta_icono + "almacen.png");

      $("#modal-apps-lito").modal("show");
    },
    error: function () {
      const Msg = Swal.mixin({
        toast: true,
        position: "center",
        icon: "warning",
        showConfirmButton: false,
        timer: 3000,
      });

      if (id_usuario == null) {
        titleMsg = "Id Nulo";
      } else {
        titleMsg = "Ocurrió un error";
      }
      Msg.fire({
        title: titleMsg,
      });
    },
  });
}

$("#mod_FirmaUsuarios").on("shown.bs.modal", function () {
  $("#FileDocLabel").text("No se eligió archivo");
  $("#FileFirma").val("");
  $("#FileFirma").trigger("focus");
});

function showAddFirma(id_usuario) {
  $("#mod_FirmaUsuarios").modal("show");
  $("#id_usuario").val(id_usuario);
}

$("input[name='FileFirma']").change(function () {
  formData = new FormData();
  var id_usuario = $("#id_usuario").val();
  formData.append("id_usuario", id_usuario);
  formData.append("file", $("input[type=file]")[0].files[0]);
});

$("#btnSaveAddFirma").click(function () {
  loaderON();
  addDocumento();
});

function addDocumento() {
  if ($("#FileDocLabel").text() == "No se eligió archivo") {
    Swal.fire({
      text: "¡Selecciona un Archivo!",
      allowEnterKey: true,
      target: document.getElementById("mod_FirmaUsuarios"),
    }).then(() => {
      $("#FileFirma").focus();
      $("#FileFirma").val("");
      $("#FileDocLabel").text("No se eligió archivo");
      loaderOut();
    });
  } else {
    $.ajax({
      url: serve + "upFirma",
      async: false,
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      cache: false,
      success: function (data) {
        loaderON();
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
        $("#mod_FirmaUsuarios").modal("hide");
        loaderOut();
        $("#mod_FirmaUsuarios :input").val("");
      },
      error: function () {
        loaderOut();
        const Msg = Swal.mixin({
          toast: true,
          position: "center",
          icon: "warning",
          showConfirmButton: false,
          timer: 3000,
        });
  
        Msg.fire({
          title: "Ocurrió un error al Subir el Archivo, vuelve a intentarlo",
        });
      },
    });
  }
}

function upFirma(id_usuario, firma) {
  $.ajax({
    url: serve + "upFirma",
    type: "POST",
    dataType: "JSON",
    data: {
      id_usuario: id_usuario,
      firma: firma,
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
        title: "Firma Agregada",
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
        title: "No se pudo agregar la firma",
      });
    },
  });
}

function accesoCandyLito(id_usuario, Mensaje, ruta, check) {
  mensajeFun = "Usuario Actualizado";
  Swal.fire({
    title: "Usuarios",
    text: "Actualizar el " + Mensaje + " del Usuario Seleccionado?",
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
        url: ruta,
        type: "PATCH",
        data: {
          _method: "PATCH",
          check: check,
          id_usuario: id_usuario,
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
            title: mensajeFun,
          });
          datatableUsuarios.ajax.reload();
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
        },
      });
    }
  });
}
