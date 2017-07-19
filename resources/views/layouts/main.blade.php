<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="shortcut icon" href="{{ asset('storage/img/kundenportal/ms-icon-70x70.png') }}}">
    <style>
    </style>
</head>
<body>
    <div id="app">
        <div class="container-overflow-wrap">
            <div class="container">
                @include('layouts.nav')
                @yield('content')
            </div>
        </div>
    </div>

    <!-- AJAX -->
    <meta name="_token" content="{{ csrf_token() }}" />
    <!-- AJAX -->
        
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
</body>
</html>
