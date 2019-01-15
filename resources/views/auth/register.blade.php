@extends('layouts.app')

@section('header')
<style>
body {
    background-color: #F5DA4E;
}
</style>
<link href="css/Input.css" rel="stylesheet" />
<style>
#aTitle{
    color: rgb(77, 77, 77);        
}
#aTitle:hover{    
    text-decoration: none;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <!-- <div class="panel-heading">會員註冊</div> -->

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                            <div class="row"><div class="col-sm-12 text-center">
                            <a id="aTitle" href="{{ url('/') }}"><img src="/img/thunder.svg" width="45">
                            <h4>Comsumption of daily life</h4>
                            <h1>家庭電器用電家計簿<br>會員註冊</h1> </a>
                        	</div></div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="group">
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus/>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="name">會員稱呼</label>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="group">
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required/>
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

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="group">
                                    <input id="password-confirm" type="password" name="password_confirmation" required/>
                                    <span class="highlight"></span>
                                    <span class="bar"></span>
                                    <label for="password-confirm">確認密碼</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-default">
                                    註冊
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <a class="btn btn-default" href="./login" >
                                    我已經有帳號了，前往登入
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
