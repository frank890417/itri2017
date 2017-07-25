<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>家庭電器用電家計簿 - 後台</title>
    <!-- Styles -->
    
    <link rel="icon" type="image/png" href="img/favicon.png" />
  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Scripts -->
    <script>
      window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      ]) !!};
    </script>
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.css">
    <script
      src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/semantic-ui/2.2.10/semantic.min.js"></script> --}}
    <!--新黑體-->
    {{-- <script type="text/javascript" src="//typesquare.com/accessor/zh_tw/apiscript/typesquare.js?HGNrQi080jw%3D" charset="utf-8"></script> --}}

    {{-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/> --}}
    <!-- Add the slick-theme.css if you want default styling -->
    {{-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/> --}}
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
  </head>
  <body>
    <div id="app" class="contianer">

      <div class="row">
        <div class="col-sm-3">
          <ul class="list-group">
            <li v-for="route in routes">
              <router-link class="list-group-item" :to="route.link">@{{route.label}}</router-link>
            </li>
          
          </ul>
        </div>
        <div class="col-sm-9">
          <transition name="fade" mode="out-in">
            <router-view :key="$route.path"></router-view>
          </transition>
        </div>
      </div>
      
      
      {{-- <section_footer></section_footer> --}}
    </div>
    <!-- @yield('content') -->
    @yield('blade_pass_variables')
    {{-- Script BEFORE app.js --}}
    @yield('require_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
{{--     <script src="/js/Rx.min.js"></script> --}}
    <script src="/js/backend.js"></script>
    
    {{-- <script type="text/javascript" src="/js/slick.min.js"></script> --}}
    {{-- Script AFTER app.js --}}
    @yield('require_js_after')
    <script>
    document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
    ':35729/livereload.js?snipver=1"></' + 'script>');
    </script>
  </body>

</html>