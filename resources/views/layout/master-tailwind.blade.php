<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="mobile-web-app-capable" content="yes">

    {{--  CSRF Token  --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

        {{--  Fuentes  --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=source-sans-pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet" />


    {{--  Titulo  --}}
    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

    {{--  Assets CSS  --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqueryui/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/Logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/appv2.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Font Awesome --}}
    {{--  <script src="https://kit.fontawesome.com/624de654bd.js" crossorigin="anonymous"></script>  --}}
    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>
    <script src="{{ asset('js/tailwind.min.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/vue.min.js') }}"></script>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            baseUrl: '{{ url('/') }}',
        };
        window.user = {};
        window.EventBus = new Vue();
    </script>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed sidebar-collapse @yield('classBody')">
    <div class="wrapper">
        @include('partials.sidebarv2')
        <div class="content-wrapper" style="margin-bottom: -20px">
            @yield('header-title')

            <div>
                {{--  Contenido  --}}
                @yield('content')
            </div>

            @include('asistente.template',["assistantId"=>"asst_5vgQzQsh7wo4KtwsgMMoVaaN"])
        </div>
    </div>

    @if (auth()->user())
        <script>
            window.user = @json(auth()->user());
        </script>
    @endif

    {{--  Assets JS  --}}
    <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jqueryui/jquery-ui-1.12.1.js') }}"></script>
    <script src="{{ asset('js/globalv2.js?n=1') }}"></script>
    <script src="{{ asset('js/jquery.disable-autofill.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
