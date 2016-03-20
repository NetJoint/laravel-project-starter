@extends('layouts.base')
@section('title')
<title>发送验证邮件成功 - {{sitename()}}</title>
@stop
@section('script')
@stop
@section('body')
<div class="container">
    <div class="box-auth">
        <h3 class="text-success"><i class="icon icon-pc-right-circle"></i> 邮件发送成功</h3>
        <hr/>
        <h4 class="text-danger">发送到邮箱：{{$email}}</h4>
        <h4>请点击验证链接完成验证</h4>
        <a class="btn btn-block btn-xlarge btn-primary" href="{{email_website($email)}}" target="_blank">前往邮箱验证</a>
        <hr/>
        <div class="msg msg-question msg-block">
            <div class="msg-con">
                <strong>没有收到邮件?</strong><br>
                1、检查Email地址有没有写错<br>
                2、看看是否在垃圾邮箱里<br>
                3、点此<a href="/activate/email/resend?email={{$email}}">重新发送验证邮件</a>
            </div>
            <s class="msg-icon"></s>
        </div>
    </div>
</div>
@stop