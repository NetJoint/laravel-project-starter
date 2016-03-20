@extends('layouts.base')
@section('title')
<title>账号密码设置 - {{sitename()}}</title>
@stop
@section('script')
<script>
    $('.form').on('submit', function (e) {
        $('button:submit', this).button('loading');
    })
</script>
@stop
@section('body')
<div class="container">
    <div class="box-auth">
        <h3><i class="icon icon-touch-user-info"></i> 账号密码设置</h3>
        <hr/>
        @if (count($errors) > 0)
        <div class="msg msg-block msg-large msg-error">
            <div class="msg-con">
                {!! $errors->first('email') !!}
            </div>
            <s class="msg-icon"></s>
        </div>
        @endif
        <form class="form validate" method="post" action="/activate">
            <input type="hidden" name="id" value="{{$userid}}">
            <input type="hidden" name="code" value="{{$code}}">
            <div class="control-group">
                <div class="controls">
                    <div class="input-control control-right">
                        <input class="input-xfat input-large" name="name" value="{{ Input::old('name')}}" type="text" placeholder="您的昵称" data-rules="required">
                        <i class="icon icon-touch-face"></i>
                    </div>                   
                </div>                
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-control control-right">
                        <input class="input-xfat input-large" id="password" name="password" value="" type="password" placeholder="您的密码" data-rules="required|minlength=6">
                        <i class="icon icon-touch-key"></i>
                    </div>                   
                </div>                
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="input-control control-right">
                        <input class="input-xfat input-large" id="repassword" value="" type="password" placeholder="确认密码" data-rules="required|match=password">
                        <i class="icon icon-touch-key-sign"></i>
                    </div>                   
                </div>                
            </div>
            
            <div class="control-group">
                <div class="controls">
                    <button class="btn btn-block btn-xlarge btn-primary" type="submit" data-loading-text="正在激活用户..." autocomplete="off">完成注册</button>
                </div>
            </div>            
        </form>        
    </div>    
</div>
@stop