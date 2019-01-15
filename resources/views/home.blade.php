@extends('layouts.app')

@section('header')
<style>
.navbar {
display:none;
}
@media (min-width:0px) and (max-width:767px) {
  #divMenu.open{
      left: -100px;
  }
}
@media (min-width:768px) and (max-width:1199px) {
  #divMenu.open{
      left: 0;
  }
}
@media (min-width:1200px){
  #divMenu{ 
    top:50px;  	
  }
  .full_nav_login {
      font-size: 80px!important;
  }
  #divMenu.open{
      left: 20%;
  }
}

#divMenu{
 /*position: fixed;
    width: 100%;
    height: 100%;
    //max-width: 400px;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    left: 0;
    top: 0;*/
    z-index: 100;
    padding: 10vmin;
	padding-left: 80px;
    transition: 1s;	
    left: -100%;
}

.full_nav_login {
    font-size: 48px;
	text-shadow: 2px 2px 5px grey;
    color: white;
    font-weight: 500;
    cursor: pointer;
	list-style: none;
}
.full_nav_login a{
//opacity:1;
font-family:"Roboto","微軟正黑體";
color:rgba(255, 255, 255, 1);
text-decoration: none;
}
.full_nav_login a:hover{
color:#000;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
<div class="row">
<div id="divLayer" style="position:fixed;width:100%;min-height:800px;z-index:10;background:#F5DA4E;opacity:0.9;" disabled>
<div id="divMenu" class="col-xs-12 text-center" style="position:fixed; width:60%; height:80%; z-index:11; background-color:rgba(245, 218, 78, 1);">  <!--background-color:rgba(255, 0, 0, 1);-->
<ul class="full_nav_login text-left text-nowrap"> 
<LI><a href='/' >進入首頁</a></LI>
<!--<LI><a href='/memberrec'>電器診斷歷史資料查詢</a></LI>-->
@if(!Auth::guest() && Auth::user()->admin==1)
<LI><a href="/manage">進入管理平台</a></LI>
@endif
<LI><a href="{{ route('logout') }}"
    onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
    登出 <!--<i class="fa fa-sign-out"></i>-->
</a></LI>
</ul>
</div>

</div>
        <div class="col-md-8 col-md-offset-2 hide">
            <div class="panel panel-default">
                <div class="panel-heading">會員登入</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Hi {{ Auth::user()->name }}， 歡迎您登入！<br>                        
                        <UL>
                        <LI><a href='/' >進入首頁>></a></LI>
                        <LI><a href='/memberrec'>電器診斷歷史資料查詢>></a></LI>
                        @if(!Auth::guest() && Auth::user()->admin==1)
                        <LI><a href="/manage">[進入管理平台]</a></LI>
                        @endif
                        <LI><a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            登出<i class="fa fa-sign-out"></i>
                        </a></LI>
                        </UL>                        
                </div>
            </div>
        </div>
    </div>
    <img src="/img/場景/Kitchen2.png" class="scene" id="imgBG" style="position: absolute; width: 80vw; right: -40vw; top: -10vw; mix-blend-mode: color-burn; pointer-events: none;" >
    <div class="row" >
        <div class="col-sm-4 text-right" style="padding-top:300px;z-index:-1;">
            <img src="/img/thunder.svg" width="45">
            <h4>Comsumption of daily life</h4>
            <h1>家庭電器用電<br class="hidden-xs">家計簿</h1>
            <h5>尋找家中的吃電怪獸</h5><br>
			<a class="btn btn-lg" href="/" style="background-color: #4d4d4d; color: white; transition: 0.5s 0s, font-size 0.5s, line-height 0.5s; border-radius: 0%; letter-spacing: 2px;">前往探索</a>
        </div>  
    </div>
</div>
@endsection
                        
@section("script")
<script>
                        $(function(){
                          $("#divMenu").addClass("open");
                        })
</script>
@endsection
