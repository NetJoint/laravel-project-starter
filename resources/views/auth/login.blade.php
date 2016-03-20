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
    <form class="form-signin" method="post" action="/login" data-toggle="validator">
        <h2 class="form-signin-heading">登录</h2>
        <hr/>
        <input id="login" name="login" value="" type="text" placeholder="您的邮箱" class="form-control" data-error="请填写您的邮箱/手机号" required>
        <input name="password" value="" type="password" placeholder="您的密码" class="form-control" data-error="请填写您的密码" required>
<!--        <label class="checkbox">
            <input type="checkbox" name="remember_me" value="1"> 记住我
        </label>-->
        <button class="btn btn-large btn-primary" type="submit">登 录</button>
    </form>
</div>
@stop