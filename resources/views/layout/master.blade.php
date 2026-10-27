<?php
session_start();
if (!isset($_GET['usuario'])) {
    $_SESSION['Usuario'] = '';
} else {
    $_SESSION['Usuario'] = $_GET['usuario'];
}
?>
<!DOCTYPE html>



<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="environment" content="{{ config('app.env') }}">

       <title>{{ config('app.name', 'Laravel') }} | {{ request()->route()->getName() }} </title>



    @include('partials.styles')

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse">
    <div class="wrapper">

        @include('partials.sidebar')
        <div id="scrollbarDashboardRound" class="content-wrapper" style="margin-bottom: -20px">
            @yield('content')
        </div>

    </div>

    @include('partials.scripts')

    @yield('scripts')


    {{--  @include('asistente.template',["assistantId"=>"asst_5vgQzQsh7wo4KtwsgMMoVaaN"])  --}}
    <script>
        function ajustarTablas() {
            rutaActual = document.location.href, h = 65;
            var height = window.innerHeight;
            if (rutaActual.indexOf('edit') > -1) {
                h = 50;
            } else {
                h = 65
            }
            if (height >= 320 && height <= 540) {
                $('#tableClientes').closest('.dataTables_scrollBody').css('height', '62vh');
                $('#tableProspectos').closest('.dataTables_scrollBody').css('height', '62vh');
                $("#tableContactosAll").closest(".dataTables_scrollBody").css("height", "65vh");
                $('#div_contactos_cliente_editar').css('height', '58vh');
                $('#div_contactos_cliente').css('height', '70vh');
                $('#div_contactos_prospecto_editar').css('height', '48vh');
                $('#div_contactos_prospecto').css('height', '70vh');
                $('#tableRequisitos').closest('.dataTables_scrollBody').css('height', '65vh');
                $('#tblOportunidades').closest('.dataTables_scrollBody').css('height', '64vh');
                $('#tblEnvios').closest('.dataTables_scrollBody').css('height', '65vh');
                $('#tblElementosPresupuestadosRelation').closest('.dataTables_scrollBody').css('height', '55vh');
                $('#tblElementosPresupuestados').closest('.dataTables_scrollBody').css('height', h + 'vh');
                $('#tblDetalleAsignadosOport').closest('.dataTables_scrollBody').css('height', h + 'vh');
                $('#tblDetalleAsignados').closest('.dataTables_scrollBody').css('height','48vh');
                $('#tblTickeDERelation').closest('.dataTables_scrollBody').css('height', '55vh');
                $('#tblUsuarios').closest('.dataTables_scrollBody').css('height', '63vh');
                $('#tblLlamadas').closest('.dataTables_scrollBody').css('height', '63vh');
                $('#tblReuniones').closest('.dataTables_scrollBody').css('height', '63vh');
            } else if (height >= 541 && height <= 768) {
                $('#tableClientes').closest('.dataTables_scrollBody').css('height', '62vh');
                $('#tableProspectos').closest('.dataTables_scrollBody').css('height', '62vh');
                $("#tableContactosAll").closest(".dataTables_scrollBody").css("height", "68vh");
                $('#div_contactos_cliente_editar').css('height', '70vh');
                $('#div_contactos_cliente').css('height', '86vh');
                $('#div_contactos_prospecto_editar').css('height', '70vh');
                $('#div_contactos_prospecto').css('height', '86vh');
                $('#tableRequisitos').closest('.dataTables_scrollBody').css('height', '68vh');
                $('#tblOportunidades').closest('.dataTables_scrollBody').css('height', '65vh');
                $('#tblEnvios').closest('.dataTables_scrollBody').css('height', '68vh');
                $('#tblElementosPresupuestadosRelation').closest('.dataTables_scrollBody').css('height', '58vh');
                $('#tblTickeDERelation').closest('.dataTables_scrollBody').css('height', '58vh');
                $('#tblElementosPresupuestados').closest('.dataTables_scrollBody').css('height', (h + 1) + 'vh');
                $('#tblDetalleAsignadosOport').closest('.dataTables_scrollBody').css('height', h + 'vh');
                $('#tblDetalleAsignados').closest('.dataTables_scrollBody').css('height', '70vh');
                $('#tblUsuarios').closest('.dataTables_scrollBody').css('height', '66vh');
                $('#tblLlamadas').closest('.dataTables_scrollBody').css('height', '61vh');
                $('#tblReuniones').closest('.dataTables_scrollBody').css('height', '63vh');
            } else if (height >= 769 && height <= 790) {
                $('#tableClientes').closest('.dataTables_scrollBody').css('height', '68vh');
                $('#tableProspectos').closest('.dataTables_scrollBody').css('height', '68vh');
                $('#div_contactos_prospecto_editar').css('height', '66vh');
                $('#div_contactos_prospecto').css('height', '76vh');
                $('#scrollbarDashboardRound').css('padding-right', '0');
            } else if (height >= 791 && height <= 1024) {
                $('#tableClientes').closest('.dataTables_scrollBody').css('height', '77vh');
                $('#tableProspectos').closest('.dataTables_scrollBody').css('height', '77vh');
                $("#tableContactosAll").closest(".dataTables_scrollBody").css("height", "77vh");
                $('#div_contactos_cliente_editar').css('height', '79vh');
                $('#div_contactos_cliente').css('height', '90vh');
                $('#div_contactos_prospecto_editar').css('height', '76vh');
                $('#div_contactos_prospecto').css('height', '90vh');
                $('#tableRequisitos').closest('.dataTables_scrollBody').css('height', '77vh');
                $('#tblOportunidades').closest('.dataTables_scrollBody').css('height', '76vh');
                $('#tblEnvios').closest('.dataTables_scrollBody').css('height', '77vh');
                $('#tblElementosPresupuestadosRelation').closest('.dataTables_scrollBody').css('height', '67vh');
                $('#tblTickeDERelation').closest('.dataTables_scrollBody').css('height', '67vh');
                $('#tblElementosPresupuestados').closest('.dataTables_scrollBody').css('height', (h + 8) + 'vh');
                $('#tblDetalleAsignadosOport').closest('.dataTables_scrollBody').css('height', (h + 8) + 'vh');
                $('#tblDetalleAsignados').closest('.dataTables_scrollBody').css('height', '78vh');
                $('#tblUsuarios').closest('.dataTables_scrollBody').css('height', '75vh');
                $('#tblLlamadas').closest('.dataTables_scrollBody').css('height', '75vh');
                $('#tblReuniones').closest('.dataTables_scrollBody').css('height', '75vh');
            } else if (height >= 1025 && height <= 1366) {
                $('#tableClientes').closest('.dataTables_scrollBody').css('height', '80vh');
                $('#tableProspectos').closest('.dataTables_scrollBody').css('height', '80vh');
                $("#tableContactosAll").closest(".dataTables_scrollBody").css("height", "80vh");
                $('#div_contactos_cliente_editar').css('height', '82vh');
                $('#div_contactos_cliente').css('height', '92vh');
                $('#div_contactos_prospecto_editar').css('height', '72vh');
                $('#div_contactos_prospecto').css('height', '92vh');
                $('#tableRequisitos').closest('.dataTables_scrollBody').css('height', '80vh');
                $('#tblOportunidades').closest('.dataTables_scrollBody').css('height', '79vh');
                $('#tblEnvios').closest('.dataTables_scrollBody').css('height', '80vh');
                $('#tblElementosPresupuestadosRelation').closest('.dataTables_scrollBody').css('height', '70vh');
                $('#tblTickeDERelation').closest('.dataTables_scrollBody').css('height', '70vh');
                $('#tblElementosPresupuestados').closest('.dataTables_scrollBody').css('height', (h + 3) + 'vh');
                $('#tblDetalleAsignadosOport').closest('.dataTables_scrollBody').css('height', (h + 2) + 'vh');
                $('#tblDetalleAsignados').closest('.dataTables_scrollBody').css('height', '79vh');
                $('#tblUsuarios').closest('.dataTables_scrollBody').css('height', '78vh');
                $('#tblLlamadas').closest('.dataTables_scrollBody').css('height', '78vh');
                $('#tblReuniones').closest('.dataTables_scrollBody').css('height', '78vh');
                // scrollbarTabsModalRoundEstr.style.height = '75vh';
            } else if (height >= 1440) {
                $('#tableClientes').closest('.dataTables_scrollBody').css('height', '82vh');
                $('#tableProspectos').closest('.dataTables_scrollBody').css('height', '82vh');
                $("#tableContactosAll").closest(".dataTables_scrollBody").css("height", "82vh");
                $('#div_contactos_cliente_editar').css('height', '84vh');
                $('#div_contactos_cliente').css('height', '94vh');
                $('#div_contactos_prospecto_editar').css('height', '74vh');
                $('#div_contactos_prospecto').css('height', '94vh');
                $('#tableRequisitos').closest('.dataTables_scrollBody').css('height', '82vh');
                $('#tblOportunidades').closest('.dataTables_scrollBody').css('height', '81vh');
                $('#tblEnvios').closest('.dataTables_scrollBody').css('height', '82vh');
                $('#tblElementosPresupuestadosRelation').closest('.dataTables_scrollBody').css('height', '72vh');
                $('#tblTickeDERelation').closest('.dataTables_scrollBody').css('height', '72vh');
                $('#tblElementosPresupuestados').closest('.dataTables_scrollBody').css('height', (h + 2) + 'vh');
                $('#tblDetalleAsignadosOport').closest('.dataTables_scrollBody').css('height', (h + 1) + 'vh');
                $('#tblDetalleAsignados').closest('.dataTables_scrollBody').css('height', '72vh');
                $('#tblUsuarios').closest('.dataTables_scrollBody').css('height', '80vh');
                $('#tblLlamadas').closest('.dataTables_scrollBody').css('height', '80vh');
                $('#tblReuniones').closest('.dataTables_scrollBody').css('height', '80vh');
            }

            var filtros = document.getElementById("card-filtros");
        }
    </script>

    <script>
        function closeWindow() {
            var ventana = window.self;
            ventana.opener = window.self;
            ventana.close();
        }
    </script>

    <script>
        // disable right click
        document.addEventListener('contextmenu', event => event.preventDefault());

        document.onkeydown = function(e) {

            // disable F12 key
            if (e.keyCode == 123) {
                // return true;
                return false;
            }

            // disable I key
            if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
                return false;
            }

            // disable J key
            if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
                return false;
            }

            // disable U key
            if (e.ctrlKey && e.keyCode == 85) {
                return false;
            }
        }
    </script>
</body>

</html>
