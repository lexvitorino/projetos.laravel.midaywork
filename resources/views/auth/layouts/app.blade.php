<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | MI Day Work</title>
    <link rel="stylesheet" href="{{ url(mix('css/common.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('css/bootstrap.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('css/icofont.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('auth/css/style.css')) }}">
</head>

<body>
    @yield('content')
    <footer class="footer">
        <span>@lang('auth.developed-with')</span>
        <span><i class="icofont-heart text-danger mx-1"></i></span>
        <span>@lang('auth.by') MI<span class="text-danger">7</span>Dev</span>
    </footer>
    <script src="{{ url(mix('js/jquery.js')) }}"></script>
    <script src="{{ url(mix('js/bootstrap.js')) }}"></script>
</body>

</html>
