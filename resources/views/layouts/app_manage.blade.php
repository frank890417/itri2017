<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>工研院家庭用電家計簿 管理後台</title>
    <link href="/css/admin_css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/admin_css/datepicker3.css" rel="stylesheet">
    <link href="/css/admin_css/styles.css" rel="stylesheet">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/skins/lightgray/skin.min.css" rel="stylesheet"> -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.6.4/skins/lightgray/content.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <!-- Icons -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/js/admin_js/lumino.glyphs.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/5.27.0/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsoneditor/5.27.0/jsoneditor.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.core.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css">
    <!-- <script src="https://unpkg.com/element-ui/lib/index.js"></script> -->
    <!-- <script src="/js/Rx.min.js"></script> -->
    <!--      
      script(src='/js/admin_js/html5shiv.js')
      script(src='/js/admin_js/respond.min.js')
    -->
    <script>
      window.Laravel = {!! json_encode([
      'csrfToken' => csrf_token(),
      ]) !!};
    </script>
    <style>
      [v-cloak] { display: none }
      
      
    </style>
  </head>
  <body>
    <div id="app" v-cloak>
      <nav role="navigation" class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" data-toggle="collapse" data-target="#sidebar-collapse" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            <div href="#" class="navbar-brand"><span>工研院家庭用電家計簿</span> Admin</div>
            <ul class="user-menu">
              <li class="dropdown pull-right"><a href="#" data-toggle="dropdown" class="dropdown-toggle">
                  <svg class="glyph stroked male-user">
                    <use xlink:href="#stroked-male-user"></use>
                  </svg>  <span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                  <li><a href="#">
                      <svg class="glyph stroked male-user">
                        <use xlink:href="#stroked-male-user"></use>
                      </svg> Profile</a></li>
                  <li><a href="#">
                      <svg class="glyph stroked gear">
                        <use xlink:href="#stroked-gear"></use>
                      </svg> Settings</a></li>
                  <li><a href="#">
                      <svg class="glyph stroked cancel">
                        <use xlink:href="#stroked-cancel"></use>
                      </svg> Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.container-fluid -->
      </nav>
      <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
        <!--          
          .form-group
            input.form-control(type='text', placeholder='Search')
        -->
        <ul class="nav menu">
          <!--,v-bind:class="{'active':(route.path==route.link)}"!-->
          <li v-for="route in routes" :class="{active: $route.path==route.link}">
              <router-link :to="route.link">
                 <svg class="glyph stroked dashboard-dial">
                   <use xlink:href="#stroked-dashboard-dial"></use>
                 </svg> @{{route.label}}
              </router-link>
          </li>
          <!--            
            a(href!="{{ url('manage/detail_info') }}")
              
              //svg.glyph.stroked.calendar
                use(xlink:href='#stroked-calendar')
              |  語系設定與其他
          -->
          {{--            
            a(href='tables.html')
              svg.glyph.stroked.table
                use(xlink:href='#stroked-table')
              |  Tables
          --}}
          {{--            
            a(href='forms.html')
              svg.glyph.stroked.pencil
                use(xlink:href='#stroked-pencil')
              |  Forms
          --}}
          {{--            
            a(href='panels.html')
              svg.glyph.stroked.app-window
                use(xlink:href='#stroked-app-window')
              |  Alerts & Panels
          --}}
          {{--            
            a(href='icons.html')
              svg.glyph.stroked.star
                use(xlink:href='#stroked-star')
              |  Icons
          --}}
          {{--            
            a(href='#')
              span(data-toggle='collapse', href='#sub-item-1')
                svg.glyph.stroked.chevron-down
                  use(xlink:href='#stroked-chevron-down')
              |  Dropdown
            ul#sub-item-1.children.collapse
              li
                a(href='#')
                  svg.glyph.stroked.chevron-right
                    use(xlink:href='#stroked-chevron-right')
                  |  Sub Item 1
              li
                a(href='#')
                  svg.glyph.stroked.chevron-right
                    use(xlink:href='#stroked-chevron-right')
                  |  Sub Item 2
              li
                a(href='#')
                  svg.glyph.stroked.chevron-right
                    use(xlink:href='#stroked-chevron-right')
                  |  Sub Item 3
          --}}
          <li role="presentation" onClick="location.replace('{{ url('manage/detail_info') }}')" class="divider"></li>
          <li><a href="./webCounterQuery"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg>瀏覽次數查詢</a></li>
          <li role="presentation" onClick="location.replace('{{ url('manage/detail_info') }}')" class="divider"></li>
          <li v-if="!user"><a href="/login" >
              <svg class="glyph stroked male-user">
                <use xlink:href="#stroked-male-user"></use>
              </svg>Login</a></li>
          <!--            
            a(href='/register')
              svg.glyph.stroked.male-user
                use(xlink:href='#stroked-male-user')
              |  Register
            
          -->
          <li v-if="user"><a onclick="event.preventDefault();document.getElementById('logout-form').submit();">
              <svg class="glyph stroked male-user">
                <use xlink:href="#stroked-male-user"></use>
              </svg>@{{user.name}} Logout</a></li>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"><span>{{ csrf_field() }}</span></form>
        </ul>
      </div>
      <!-- /.sidebar -->
      <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <!-- @yield('content') -->

        <transition name="fade" mode="out-in">
          <router-view :key="$route.path"></router-view>
        </transition>
        <div class="clearfix"></div>
        <!-- /.row -->
        {{--          
          .col-lg-12
            h1.page-header 新聞管理
        --}}
        <!-- /.row -->
        {{--          
          .col-xs-12.col-md-6.col-lg-3
            .panel.panel-blue.panel-widget
              .row.no-padding
                .col-sm-3.col-lg-5.widget-left
                  svg.glyph.stroked.bag
                    use(xlink:href='#stroked-bag')
                .col-sm-9.col-lg-7.widget-right
                  .large 120
                  .text-muted New Orders
          .col-xs-12.col-md-6.col-lg-3
            .panel.panel-orange.panel-widget
              .row.no-padding
                .col-sm-3.col-lg-5.widget-left
                  svg.glyph.stroked.empty-message
                    use(xlink:href='#stroked-empty-message')
                .col-sm-9.col-lg-7.widget-right
                  .large 52
                  .text-muted Comments
          .col-xs-12.col-md-6.col-lg-3
            .panel.panel-teal.panel-widget
              .row.no-padding
                .col-sm-3.col-lg-5.widget-left
                  svg.glyph.stroked.male-user
                    use(xlink:href='#stroked-male-user')
                .col-sm-9.col-lg-7.widget-right
                  .large 24
                  .text-muted New Users
          .col-xs-12.col-md-6.col-lg-3
            .panel.panel-red.panel-widget
              .row.no-padding
                .col-sm-3.col-lg-5.widget-left
                  svg.glyph.stroked.app-window-with-content
                    use(xlink:href='#stroked-app-window-with-content')
                .col-sm-9.col-lg-7.widget-right
                  .large 25.2k
                  .text-muted Page Views
        --}}
        <!-- /.row -->
        {{--          
          .col-lg-12
            .panel.panel-default
              .panel-heading Site Traffic Overview
              .panel-body
                .canvas-wrapper
                  canvas#line-chart.main-chart(height='200', width='600')
        --}}
        <!-- /.row -->
        {{--          
          .col-xs-6.col-md-3
            .panel.panel-default
              .panel-body.easypiechart-panel
                h4 New Orders
                #easypiechart-blue.easypiechart(data-percent='92')
                  span.percent 92%
          .col-xs-6.col-md-3
            .panel.panel-default
              .panel-body.easypiechart-panel
                h4 Comments
                #easypiechart-orange.easypiechart(data-percent='65')
                  span.percent 65%
          .col-xs-6.col-md-3
            .panel.panel-default
              .panel-body.easypiechart-panel
                h4 New Users
                #easypiechart-teal.easypiechart(data-percent='56')
                  span.percent 56%
          .col-xs-6.col-md-3
            .panel.panel-default
              .panel-body.easypiechart-panel
                h4 Visitors
                #easypiechart-red.easypiechart(data-percent='27')
                  span.percent 27%
        --}}
        <!-- /.row -->
        {{--          
          .col-md-8
            .panel.panel-default.chat
              #accordion.panel-heading
                svg.glyph.stroked.two-messages
                  use(xlink:href='#stroked-two-messages')
                |  Chat
              .panel-body
                ul
                  li.left.clearfix
                    span.chat-img.pull-left
                      img.img-circle(src='http://placehold.it/80/30a5ff/fff', alt='User Avatar')
                    .chat-body.clearfix
                      .header
                        strong.primary-font John Doe
                        small.text-muted 32 mins ago
                      p
                        | Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc. Vivamus luctus convallis mauris, eu gravida tortor aliquam ultricies.
                  li.right.clearfix
                    span.chat-img.pull-right
                      img.img-circle(src='http://placehold.it/80/dde0e6/5f6468', alt='User Avatar')
                    .chat-body.clearfix
                      .header
                        strong.pull-left.primary-font Jane Doe
                        small.text-muted 6 mins ago
                      p
                        | Mauris dignissim porta enim, sed commodo sem blandit non. Ut scelerisque sapien eu mauris faucibus ultrices. Nulla ac odio nisl. Proin est metus, interdum scelerisque quam eu, eleifend pretium nunc. Suspendisse finibus auctor lectus, eu interdum sapien.
                  li.left.clearfix
                    span.chat-img.pull-left
                      img.img-circle(src='http://placehold.it/80/30a5ff/fff', alt='User Avatar')
                    .chat-body.clearfix
                      .header
                        strong.primary-font John Doe
                        small.text-muted 32 mins ago
                      p
                        | Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ante turpis, rutrum ut ullamcorper sed, dapibus ac nunc. Vivamus luctus convallis mauris, eu gravida tortor aliquam ultricies.
              .panel-footer
                .input-group
                  input#btn-input.form-control.input-md(type='text', placeholder='Type your message here...')
                  span.input-group-btn
                    button#btn-chat.btn.btn-success.btn-md Send
          // /.col
          .col-md-4
            .panel.panel-blue
              .panel-heading.dark-overlay
                svg.glyph.stroked.clipboard-with-paper
                  use(xlink:href='#stroked-clipboard-with-paper')
                | To-do List
              .panel-body
                ul.todo-list
                  li.todo-list-item
                    .checkbox
                      input#checkbox(type='checkbox')
                      label(for='checkbox') Make a plan for today
                    .pull-right.action-buttons
                      a(href='#')
                        svg.glyph.stroked.pencil
                          use(xlink:href='#stroked-pencil')
                      a.flag(href='#')
                        svg.glyph.stroked.flag
                          use(xlink:href='#stroked-flag')
                      a.trash(href='#')
                        svg.glyph.stroked.trash
                          use(xlink:href='#stroked-trash')
                  li.todo-list-item
                    .checkbox
                      input#checkbox(type='checkbox')
                      label(for='checkbox') Update Basecamp
                    .pull-right.action-buttons
                      a(href='#')
                        svg.glyph.stroked.pencil
                          use(xlink:href='#stroked-pencil')
                      a.flag(href='#')
                        svg.glyph.stroked.flag
                          use(xlink:href='#stroked-flag')
                      a.trash(href='#')
                        svg.glyph.stroked.trash
                          use(xlink:href='#stroked-trash')
                  li.todo-list-item
                    .checkbox
                      input#checkbox(type='checkbox')
                      label(for='checkbox') Send email to Jane
                    .pull-right.action-buttons
                      a(href='#')
                        svg.glyph.stroked.pencil
                          use(xlink:href='#stroked-pencil')
                      a.flag(href='#')
                        svg.glyph.stroked.flag
                          use(xlink:href='#stroked-flag')
                      a.trash(href='#')
                        svg.glyph.stroked.trash
                          use(xlink:href='#stroked-trash')
                  li.todo-list-item
                    .checkbox
                      input#checkbox(type='checkbox')
                      label(for='checkbox') Drink coffee
                    .pull-right.action-buttons
                      a(href='#')
                        svg.glyph.stroked.pencil
                          use(xlink:href='#stroked-pencil')
                      a.flag(href='#')
                        svg.glyph.stroked.flag
                          use(xlink:href='#stroked-flag')
                      a.trash(href='#')
                        svg.glyph.stroked.trash
                          use(xlink:href='#stroked-trash')
                  li.todo-list-item
                    .checkbox
                      input#checkbox(type='checkbox')
                      label(for='checkbox') Do some work
                    .pull-right.action-buttons
                      a(href='#')
                        svg.glyph.stroked.pencil
                          use(xlink:href='#stroked-pencil')
                      a.flag(href='#')
                        svg.glyph.stroked.flag
                          use(xlink:href='#stroked-flag')
                      a.trash(href='#')
                        svg.glyph.stroked.trash
                          use(xlink:href='#stroked-trash')
                  li.todo-list-item
                    .checkbox
                      input#checkbox(type='checkbox')
                      label(for='checkbox') Tidy up workspace
                    .pull-right.action-buttons
                      a(href='#')
                        svg.glyph.stroked.pencil
                          use(xlink:href='#stroked-pencil')
                      a.flag(href='#')
                        svg.glyph.stroked.flag
                          use(xlink:href='#stroked-flag')
                      a.trash(href='#')
                        svg.glyph.stroked.trash
                          use(xlink:href='#stroked-trash')
              .panel-footer
                .input-group
                  input#btn-input.form-control.input-md(type='text', placeholder='Add new task')
                  span.input-group-btn
                    button#btn-todo.btn.btn-primary.btn-md Add
          // /.col
        --}}
        <!-- /.row -->
      </div>
      <!-- /.main -->
      
    </div>
  </body>
  @yield('blade_pass_variables')
  {{-- Script BEFORE app.js --}}
  <script>

    if (location.host=="localhost"){
        document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
        ':35729/livereload.js?snipver=1"></' + 'script>');
        
    }
  </script>
  @yield('require_js')
  <script src="/js/admin_js/jquery-1.11.1.min.js"></script>
  <script src="/js/admin_js/bootstrap.min.js"></script>
  <script>
    var locale= document.location.host.split(".")[0];
    if (["zh","cn","en"].indexOf(locale)==-1){
      locale="zh";
    }
    var user = {!! json_encode($user) !!}
    $("a[data-lang='locale']").addClass("active");
  </script>
  {{-- script(src='/js/admin_js/chart.min.js') --}}
  <script src="/js/backend.js"></script>
  {{-- script(src='/js/admin_js/chart-data.js') --}}
  {{-- script(src='/js/admin_js/easypiechart.js') --}}
  {{-- script(src='/js/admin_js/easypiechart-data.js') --}}
  {{-- script(src='/js/admin_js/bootstrap-datepicker.js') --}}
  {{--    
    $('#calendar').datepicker({
    });
    !function ($) {
    $(document).on("click","ul.nav li.parent > a > span.icon", function(){
    $(this).find('em:first').toggleClass("glyphicon-minus");
    });
    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
    }(window.jQuery);
    $(window).on('resize', function () {
    if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
    })
    $(window).on('resize', function () {
    if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
    })
    
    
  --}}
  {{-- Script AFTER app.js --}}
  @yield('require_js_after')
  {{-- doctype html --}}
  {{-- html(lang="{{ config('app.locale') }}") --}}
  {{-- head --}}
  {{-- meta(charset='utf-8') --}}
  {{-- meta(http-equiv='X-UA-Compatible', content='IE=edge') --}}
  {{-- meta(name='viewport', content='width=device-width, initial-scale=1') --}}
  {{-- // CSRF Token --}}
  {{-- meta(name='csrf-token', content='{{ csrf_token() }}') --}}
  {{-- title {{ config('app.name', 'Laravel') }} --}}
  {{-- // Styles --}}
  {{-- link(href="{{ asset('css/app.css') }}", rel='stylesheet') --}}
  {{-- // Scripts --}}
  {{-- script. --}}
  {{-- window.Laravel = {!! json_encode([ --}}
  {{-- 'csrfToken' => csrf_token(), --}}
  {{-- ]) !!}; --}}
  {{-- body --}}
  {{-- #app --}}
  {{-- nav.navbar.navbar-default.navbar-static-top --}}
  {{-- .container --}}
  {{-- .navbar-header --}}
  {{-- // Collapsed Hamburger --}}
  {{-- button.navbar-toggle.collapsed(type='button', data-toggle='collapse', data-target='#app-navbar-collapse') --}}
  {{-- span.sr-only Toggle Navigation --}}
  {{-- span.icon-bar --}}
  {{-- span.icon-bar --}}
  {{-- span.icon-bar --}}
  {{-- // Branding Image --}}
  {{-- a.navbar-brand(href="{{ url('/') }}") --}}
  {{-- | 睿軒網站後台(開發中) --}}
  {{-- #app-navbar-collapse.collapse.navbar-collapse --}}
  {{-- // Left Side Of Navbar --}}
  {{-- ul.nav.navbar-nav --}}
  {{-- // Right Side Of Navbar --}}
  {{-- ul.nav.navbar-nav.navbar-right --}}
  {{-- // Authentication Links --}}
  {{-- li --}}
  {{-- a(href="{{ url('manage/news') }}") 新聞管理 --}}
  {{-- @if (Auth::guest()) --}}
  {{-- li --}}
  {{-- a(href="{{ route('login') }}") Login --}}
  {{-- li --}}
  {{-- a(href="{{ route('register') }}") Register --}}
  {{-- @else --}}
  {{-- li.dropdown --}}
  {{-- a.dropdown-toggle(href='#', data-toggle='dropdown', role='button', aria-expanded='false') --}}
  {{-- | {{ Auth::user()->name }} --}}
  {{-- span.caret --}}
  {{-- ul.dropdown-menu(role='menu') --}}
  {{-- li --}}
  {{-- a(href="{{ route('logout') }}", onclick="event.preventDefault();\ --}}
  {{-- document.getElementById('logout-form').submit();") --}}
  {{-- | Logout --}}
  {{-- form#logout-form(action="{{ route('logout') }}", method='POST', style='display: none;') --}}
  {{-- | {{ csrf_field() }} --}}
  {{-- @endif --}}
  {{-- @yield('content') --}}
  {{-- @yield('blade_pass_variables') --}}
  
  {{-- {{-- Script BEFORE app.js --}} --}}
  {{-- @yield('require_js') --}}
  {{-- script(src="/js/app.js") --}}
  {{-- {{-- Script AFTER app.js --}} --}}
  {{-- @yield('require_js_after') --}}
  
  
  
</html>