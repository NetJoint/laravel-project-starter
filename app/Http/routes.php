<?php

# 页面路由组
Route::group(['middleware' => ['web']], function () {
    # 公开页面
    # 主页
    Route::get('/', 'WelcomeController@index');
    # 注册页
    Route::get('register', 'AuthController@getRegister');
    # 邮件注册，csrf防止跨站脚本攻击
    Route::post('register/email', ['middleware' => 'csrf','uses' => 'AuthController@postRegisterEmail']);
    # 重发激活邮件
    Route::get('activate/email/resend', 'AuthController@getActivateEmailResend');
    Route::post('activate/email/resend', ['middleware' => 'csrf','uses' => 'AuthController@postActivateEmailResend']);
    # 账号激活
    Route::get('activate/email/{id}/{activationCode}', 'AuthController@getActivateEmail');
    Route::post('activate', 'AuthController@postActivate');
    Route::get('activate/success', 'AuthController@getActivateSuccess');

    # 登录
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    # 退出
    Route::get('logout', 'AuthController@getLogout');


    # 普通用户可见页面
    Route::group(['middleware' => ['role:user']], function() {
        
    });

    # 管理员可见页面
    Route::group(['middleware' => ['role:admin']], function() {
        
    });
});

# API路由组
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function ($api) {


        $api->group(['middleware' => ['role:user']], function ($api) {
            # 普通用户可用API
        });

        $api->group(['middleware' => ['role:admin']], function ($api) {
            # 管理员可用API
        });
    });
});
