@extends('layouts.base')
@section('title')
<title>注册 - {{sitename()}}</title>
@stop
@section('script')
<script>
    $('input[name="email"]').focus();
    $('.form-signin').on('submit', function (e) {
        $('#email_btn').button('loading');
    })
</script>
@stop
@section('body')
<div class="container">
    <form class="form-signin" method="post" action="/register/email" data-toggle="validator">
        <h2 class="form-signin-heading">注册</h2>
        <hr/>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="text" name="email" value="" class="form-control" placeholder="请填写您的邮箱" required>        
        <button id="email_btn" class="btn btn-large btn-primary" type="submit" data-loading-text="正在发送邮件..." autocomplete="off">立即注册</button>
        <hr/>
        <div class="text-center">
            已有帐号?<a href="/login">点此登录</a><br/>            
        </div>
    </form>    
</div>
@stop