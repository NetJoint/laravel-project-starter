@extends('layouts.base')
@section('title')
<title>注册 - {{sitename()}}</title>
@stop
@section('script')
<script>
    $('input[name="email"]').focus();
    $('.form').on('submit', function (e) {
        $('button:submit',this).button('loading');
    })
</script>
@stop
@section('body')
<div class="container">
    <div class="box-auth">
        <h3><i class="icon icon-touch-user-add"></i> 注 册</h3>
        <hr/>
        @if (count($errors) > 0)
        <div class="msg msg-block msg-large msg-error">
            <div class="msg-con">
                {!! $errors->first('email') !!}
            </div>
            <s class="msg-icon"></s>
        </div>
        @endif
        <form class="form validate" method="post" action="/register/email">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="control-group">
                <div class="controls">
                    <div class="input-control control-right">
                        <input class="input-xfat input-large {{$errors->has('email') ? 'input-error' : ''}}" name="email" value="{{ Input::old('email')}}" type="text" placeholder="您的邮箱"  data-rules="required">
                        <i class="icon icon-touch-email2"></i>
                    </div>                   
                </div>                
            </div>
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-block btn-xlarge btn-primary" type="submit" data-loading-text="正在发送邮件..." autocomplete="off">立即注册</button>
                </div>
            </div>            
        </form>
        <hr/>
        已有帐号?<a href="/login">点此登录</a><br/>
    </div>    
</div>
@stop