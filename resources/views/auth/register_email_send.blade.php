@extends('layouts.base')
@section('title')
<title>发送验证邮件成功 - {{sitename()}}</title>
@stop
@section('script')
@stop
@section('body')
<div class="container">
    <h5>邮件已发送到您的邮箱</h5>
    <h5 class="text-danger">{{$email}}</h5>
    <h5>请点击邮箱中的验证链接完成验证</h5>
    <a class="btn btn-large btn-primary" href="{{email_website($email)}}" target="_blank">前往邮箱验证</a>
    <hr/>
    <div class="text-center">
        没有收到邮件?
    </div>
    <div class="margin">
        <ul>
            <li>看看Email地址有没有写错</li>
            <li>看看是否在垃圾邮箱里</li>
<!--            <li>点此<a href="/activate/email/resend?email={{$email}}">重新发送验证邮件</a></li>-->
        </ul>
    </div>  
</div>
@stop