<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" href="{{ url(mix('css/common.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('css/bootstrap.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('css/icofont.css')) }}">
    <script src="{{ url(mix('js/pace.js')) }}"></script>
    <link rel="stylesheet" href="{{ url(mix('css/pace-theme-flash.css')) }}">
    <link rel="stylesheet" href="{{ url(mix('admin/css/style.css')) }}">
    @yield('styles')
    <title>{{ $title->title }} | MI Day Work</title>
</head>
<body style="position: relative">
    <header class="header">
        <div class="logo">
            <i class="icofont-travelling mr-2"></i>
            <span class="font-weight-light">MI </span>
            <span class="font-weight-bold mx-2">Day</span>
            <span class="font-weight-light">Work</span>
            <i class="icofont-runner-alt-1 ml-2"></i>
        </div>
        <div class="menu-toggle mx-3">
            <i class="icofont-navigation-menu"></i>
        </div>
        <div class="spacer"></div>
        <div class="dropdown">
            <div class="dropdown-button">
                <img class="avatar"
                    src="{{ "http://www.gravatar.com/avatar.php?gravatar_id=" . md5(strtolower($user->email)) }}" alt="user">
                <span class="ml-3">
                    {{ $user->name }}
                </span>
                <i class="icofont-simple-down mx-2"></i>
            </div>
            <div class="dropdown-content">
                <ul class="nav-list">
                    <!--<li class="nav-item">
                        <a href="{{ route('profile') }}">
                            <i class="icofont-user mr-2"></i>
                            @lang('auth.profile')
                        </a>
                    </li>-->
                    <li class="nav-item">
                        <a href="{{ url('/painel/logout') }}">
                            <i class="icofont-logout mr-2"></i>
                            @lang('auth.logout')
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>
