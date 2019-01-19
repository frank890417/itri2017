<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>家庭電器用電家計簿</title>

     <meta property="og:title" content="家庭電器用電家計簿" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="http://saving.energypark.org.tw/img/og.png" />
    <meta property="og:description" content="電器在我們沒注意時慢慢的老化，成為家中荷包的重擔， 讓我們一起抓出家裡的吃電怪獸吧！" />

    <!-- Styles -->
    
    <link rel="icon" type="image/png" href="/img/favicon.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.css">
<style>
#divMember {
	cursor:pointer;
}
</style>
    <!-- Scripts -->
    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src="https://d3js.org/d3-selection-multi.v1.min.js"></script>
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
    <div id="app" class="section_top">
      <div id='divMember' style="padding-left: 80px; padding-top: 10px; z-index: 200; width:300px; position:fixed;">
      <i class="fa fa-user-circle" style="font-size: 40px;margin-top: 10px;"></i>
      @if (Auth::check())
        <span>{{ Auth::user()->name }}</span>
      @else
        <span>訪客</span>
      @endif    
      </div>      
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          {{ csrf_field() }}
      </form>
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
    <script src="/js/app.js"></script>
    
    {{-- <script type="text/javascript" src="/js/slick.min.js"></script> --}}
    {{-- Script AFTER app.js --}}
    @yield('require_js_after')
    <script>
    if (location.host=="localhost"){
        document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
        ':35729/livereload.js?snipver=1"></' + 'script>');
        
    }
$(function(){
  $("#divMember").click(function(){
    if (MEMBER_DATA.isLogin) {
      $("div.hambergur").click()
    } else {
      location.assign('/login')  
    }    
  })
  
  $(window).scroll(function(){
  	if($(this).scrollTop()>0){
      //alert($(this).scrollTop())
      //$('#divMember').fadeOut('slow');
      $("#divMember").css("position","static")
    }else{
      $('#divMember').css("position","fixed")
    }
  })
})
</script>
  </body>

</html>
