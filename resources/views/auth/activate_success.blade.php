@extends('layouts.base')
@section('title')
<title>账号激活成功 - {{sitename()}}</title>
@stop
@section('script')
@stop
@section('body')
<div class="container">
    <div class="form-signin">
        <h4 class="text-danger">您的账号已成功激活</h4>        
        <hr/>
        <a class="btn btn-large btn-primary" href="/login" >登 录</a>
    </div>
</div>
@stop