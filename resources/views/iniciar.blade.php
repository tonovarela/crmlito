<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} | Iniciar Sesión</title>

    @include('partials.styles')

</head>

<body>
    <div style="justify-content: center; align-items: center; display: flex; height: 100vh;">
        <h1 class="text-center">Tu sesión ha caducado, abrir nuevamente desde <a
                href="/litoapps">LitoApps</a></h1>
    </div>
</body>

</html>
