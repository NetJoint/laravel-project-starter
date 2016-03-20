@extends('layouts.base')
@section('title')
<title>重发激活邮件 - {{sitename()}}</title>
@stop
@section('script')
<script>
    $('input[name="email"]').focus();
    $('#email_form').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {

        } else {
            $('#email_btn').button('loading');
        }
    })
</script>
@stop
@section('body')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><img src="{{ static_url('img/logo_large.png') }}"/></a>
    </div>
    <div class="login-box-body panel">
        <div class="tab-content" style="margin-top: 20px">
            <form id="email_form" method="post" action="/activate/email/resend" data-toggle="validator" >
                <div class="form-group has-feedback {{$errors->has('email') ? 'has-error' : ''}}">
                    <input name="email" value="{{ Input::old('email')?Input::old('email'):$email}}" type="text" placeholder="您的邮箱" class="form-control" data-error="请填写您的邮箱" required>
                    <span class="help-block with-errors" data-bs.validator.original-content=''>{!! $errors->first('email') !!}</span>
                </div>          
                <div class="row">
                    <div class="col-md-12">
                        <button id="email_btn" class="btn btn-primary btn-block btn-flat btn-lg" type="submit" data-loading-text="正在发送邮件..." autocomplete="off">发送激活邮件</button>
                    </div><!-- /.col -->
                </div>
            </form>        
        </div>
    </div><!-- /.login-box-body -->
</div>
@stop