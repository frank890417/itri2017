@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">等待認證</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        會員認證信件已經寄出至您的註冊Email，請您收取Email進行認證程序。
                            
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection