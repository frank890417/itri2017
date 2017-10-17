<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>家庭電器用電家計簿 - 工研院綠能所</title>

     <meta property="og:title" content="家庭電器用電家計簿 - 工研院綠能所" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="http://saving.energypark.org.tw/img/og.png" />
    <meta property="og:description" content="電器在我們沒注意時慢慢的老化，成為家中荷包的重擔， 讓我們一起抓出家裡的吃電怪獸吧！" />

    <!-- Styles -->
    
    <link rel="icon" type="image/png" href="img/favicon.png" />

    <!-- Scripts -->
    <script>
      window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      ]) !!};
    </script>
    <!--新黑體-->
    {{-- <script type="text/javascript" src="//typesquare.com/accessor/zh_tw/apiscript/typesquare.js?HGNrQi080jw%3D" charset="utf-8"></script> --}}

    {{-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/> --}}
    <!-- Add the slick-theme.css if you want default styling -->
    {{-- <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick-theme.css"/> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body>
    <div id="app">
      {{-- <navbar></navbar> --}}
      <transition name="fade" mode="out-in">
        <router-view :key="$route.path"></router-view>
      </transition>
      {{-- <section_footer></section_footer> --}}
    </div>
    <!-- @yield('content') -->
    @yield('blade_pass_variables')
    {{-- Script BEFORE app.js --}}
    @yield('require_js')
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
{{--     <script src="/js/Rx.min.js"></script> --}}
    <script>
      window.site_info_zh = {!! $site_info_zh !!};
    </script>
    <script src="/js/app.js"></script>
    
    {{-- <script type="text/javascript" src="/js/slick.min.js"></script> --}}
    {{-- Script AFTER app.js --}}
    @yield('require_js_after')
    <script>
    document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
    ':35729/livereload.js?snipver=1"></' + 'script>');
    </script>

  </body>

</html>