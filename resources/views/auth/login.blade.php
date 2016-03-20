@extends('layouts.base')
@section('title')
<title>登录 - {{sitename()}}</title>
@stop
@section('script')
<script>
</script>
@stop
@section('body')
<div class="container">
    <div class="box-auth">
        <h3><i class="icon icon-touch-left-rect"></i> 登 录</h3>
        <hr/>
        @if (count($errors) > 0)
        <div class="msg msg-block msg-large msg-error">
            <div class="msg-con">
                @if ($errors->first('msg'))
                {!! $errors->first('msg') !!}
                @else
                用户名或密码错误。
                @endif
            </div>
            <s class="msg-icon"></s>
        </div>
        @endif
        <form class="form validate" method="post" action="/login">
            <div class="control-group">
                <div class="controls">
                    <div class="input-control control-right">
                        <input class="input-xfat input-large" name="login" value="{{ Input::old('login')}}" type="text" placeholder="您的邮箱"  data-rules="required">
                        <i class="icon icon-touch-email2"></i>
                    </div>                   
                </div>                
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-control control-right">
                        <input class="input-xfat input-large" name="password" value="" type="password" placeholder="您的密码"  data-rules="required">
                        <i class="icon icon-touch-key"></i>
                    </div>                   
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox-pretty inline" data-toggle="checkbox">
                        <input type="checkbox" name="remember_me"><span>记住我</span>
                    </label>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-block btn-xlarge btn-primary" type="submit">登 录</button>
                </div>
            </div>            
        </form>
        <hr/>
        还没有帐号? <a href="/register">马上注册</a>
    </div>    
</div>
@stop