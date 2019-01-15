@extends('layouts.app')

@section('header')
<link href="css/Input.css" rel="stylesheet" />
<style>
#aTitle{
    color: rgb(77, 77, 77);        
}
#aTitle:hover{    
    color: #000;
    text-decoration: none;
}
#aHome{
    font-size: 18px;
    color: rgb(77, 77, 77); 
    transition-duration: 1s;  
}
#aHome:hover{
    color: rgb(0, 0, 0);
    font-size: 20px;
    transition-duration: 1s;
    text-decoration: none;
}
</style>
@endsection

@section('content')
<div class="container-fluid" >
    <div class="row">
        <div class="col-md-8 col-md-offset-2" >
            <div class="panel panel-default" >
                <!-- <div class="panel-heading">會員登入</div> -->

                <div class="panel-body">                
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
		                    <div class="row"><div class="col-sm-12 text-center">
                            <a id="aTitle" href="{{ url('/') }}"><img src="/img/thunder.svg" width="45">
                            <h4>Comsumption of daily life</h4>
                            <h1>家庭電器用電家計簿<br>會員登入</h1> </a>
                        	</div></div>

                        <br>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="group">
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus/>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="email">信箱</label>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="group">
                                    <input id="password" type="password" name="password" required/>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="password">密碼</label>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group hide">
                            <div class="col-xs-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 記住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="padding-top:50px;">
                            <div class="col-xs-4 col-md-5 text-right text-nowrap">
                                <a id="aHome" href="{{ url('/') }}">
                                    <i class="fa fa-home"></i>返回首頁
                                </a>
                            </div>
                            <div class="col-xs-4 col-md-2 text-center">
                                <button type="submit" class="btn btn-default">
                                    登入
                                </button>
                                <a class="btn btn-link hide" href="{{ route('password.request') }}">
                                    忘記密碼?
                                </a>
                            </div>
                            <div class="col-xs-4 col-md-5 text-left">
                                <a class="btn btn-default" href="{{ route('register') }}">
                                    註冊
                                </a>
                            </div>
                        </div> 
                            
@if ($errors->has('error'))
<script>
alert('{{ $errors->first('error') }}');
</script>     
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
<div class="error text-danger">登入失敗：{{ $errors->first('error') }}</div>
                            </div>
                        </div>         
@endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
