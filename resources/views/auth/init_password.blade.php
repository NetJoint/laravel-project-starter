@extends('layouts.base')
@section('title')
<title>账号密码设置 - {{sitename()}}</title>
@stop
@section('script')
@stop
@section('body')
<div class="container">
    <form class="form-signin" method="post" action="/activate" data-toggle="validator">
        <h2 class="form-signin-heading">账号密码设置</h2>
        <hr/>
        <input type="hidden" name="id" value="{{$userid}}">
        <input type="hidden" name="code" value="{{$code}}">
        <input id="name" name="name" value="" type="text" placeholder="您的昵称" class="form-control" data-minlength="2" data-minlength-error="昵称至少为2个字符" data-error="请填写您的昵称" required>
        <input id="password" name="password" value="" type="password" placeholder="您的密码" class="form-control" data-minlength="6" data-minlength-error="密码至少6位" data-error="请填写您的密码" required>
        <input value="" type="password" placeholder="确认密码" class="form-control"  data-match-error="两次输入的密码不一致" data-match="#password" data-error="请确认您的密码" required>
       <button class="btn btn-large btn-primary" type="submit">完成注册</button>
    </form>    
</div>
@stop