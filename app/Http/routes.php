<?php

# 页面路由组
Route::group(['middleware' => ['web']], function () {    
    # 主页
    Route::get('/', 'WelcomeController@index');
    # 注册页
    Route::get('/', 'AuthController@getRegister');
    
    
    
    
    

    Route::group(['middleware' => ['role:user']], function() {
        # 普通用户可见页面
        
    });
    
    Route::group(['middleware' => ['role:admin']], function() {
        # 管理员可见页面
        
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
