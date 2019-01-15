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
	<link rel="icon" type="image/png" href="img/favicon.png" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

<style>
body {
    background-color: #F5DA4E;
    background-image: url(/img/場景/Kitchen2.png);
    background-repeat: no-repeat;
    background-position-x: right;
    background-position-y: 70px;
}
</style>

	@yield('header')
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top" style="border:none;background-color:transparent;">
            <div class="container">
                <div class="navbar-header hide">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed hide" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fa fa-home"></i>{{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right hide">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">登入</a></li>
                            <li><a href="{{ route('register') }}">註冊</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            登出
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div id="divLayer" style="position:fixed;width:100%;height:100%;background:#F5DA4E;opacity:0.9;" disabled></div>
        @yield('content')
                
    </div>

    <!-- Scripts -->
@if (Auth::check())
<script>    
MEMBER_DATA = {
    users_id: {{ Auth::user()->id }} ,
    email: "{{ Auth::user()->email }}" ,
    isLogin: true,
};
//console.log("auth_id:", MEMBER_DATA.name)
</script>  
@else
<script>    
MEMBER_DATA = {
    users_id: 0 ,
    email: "guest" ,
    isLogin: false,
};
</script> 
@endif                                        
    <script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>
