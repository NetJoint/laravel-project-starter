@extends('layouts.base')
@section('title')
<title>账号激活成功 - {{sitename()}}</title>
@stop
@section('script')
@stop
@section('body')
<div class="container">
    <div class="box-auth">
        <h3 class="text-success"><i class="icon icon-pc-right-circle"></i> 账号激活成功</h3>
        <hr/>
        <h3 class="text-danger">欢迎您加入{{sitename()}}!</h3>        
        <a class="btn btn-block btn-xlarge btn-primary" href="/login" >登 录</a>        
    </div>
</div>
@stop