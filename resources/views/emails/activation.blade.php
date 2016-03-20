@extends('layouts.email')
@section('content')
<table style="width: 100%; margin: 0; padding: 0;">
    <tr>
        <td>
            <h2 style=" font-size: 18px; line-height: 1.2; color: #555; font-weight: bold; margin: 10px 0; padding: 0;">
                尊敬的用户，欢迎您加入{{sitename()}}!
            </h2>
            <p style=" font-size: 14px; color: #555; line-height: 1.6;  margin: 0 0 10px; padding: 0;">
                为了保证您正常体验{{sitename()}}的服务，请激活账号。
            </p>
            <table style="width: 100%; margin: 0; padding: 0;">
                <tr>
                    <td style="padding: 10px 0;">
                        <p style=" font-size: 14px; line-height: 1.6;  margin: 0 0 10px; padding: 0;text-align: center">
                            <a href="{{config('app.url')}}/activate/email/{{$activation['user_id']}}/{{$activation['code']}}" style="line-height: 2; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 25px; background: #348eda; margin: 0 10px 0 0; padding: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">
                                点击激活账号
                            </a>
                        </p>
                    </td>
                </tr>
            </table>
            <p style=" font-size: 14px; color: #555; line-height: 1.6;  margin: 0 0 10px; padding: 0;">如果以上按钮无法打开，请把下面的链接复制到浏览器地址栏中打开：</p>
            <p style=" font-size: 14px; line-height: 1.6;  margin: 0 0 10px; padding: 0;">
                <a href="{{config('app.url')}}/activate/email/{{$activation['user_id']}}/{{$activation['code']}}" style="line-height: 1.6; color: #348eda; margin: 0; padding: 0;">
                    {{config('app.url')}}/activate/email/{{$activation['user_id']}}/{{$activation['code']}}
                </a>
            </p>            
        </td>
    </tr>
</table>
@stop