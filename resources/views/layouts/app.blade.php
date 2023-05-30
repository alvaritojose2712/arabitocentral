<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="icon" type="image/png" href="{{ asset('images/icon.ico') }}">
    <title>Arabito Central</title>

    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   
    @yield("scripts")
</head>
<body>
    <div id="app"></div>
    
    @yield('content')
</body>
</html>
