<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BEAM-Messenger') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Manifests -->
    <link rel="manifest" href="/manifest.json">
</head>

<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <nav class="navbar fixed-bottom navbar-expand-md navbar-light navbar-laravel">
            <span class="navbar-icon active"><i class="fas fa-home"></i></span>
            <span class="navbar-icon"><i class="fas fa-search"></i></span>
            <span class="navbar-icon"><i class="far fa-comments"></i></span>
            <span class="navbar-icon"><i class="far fa-heart"></i></span>
            <span class="navbar-icon"><i class="far fa-user"></i></span>
    </nav>

    {{ csrf_field() }}

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener("load", function(){
                navigator.serviceWorker.register('service-worker.js').then(function (registration) {
                    console.log('ServiceWorker registration successful with scope:', registration.scope);
                }, function (err) {
                        console.log('ServiceWorker failed:', err);
                });
            });
        }
    </script>
</body>

</html>