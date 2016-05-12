<?php

# 页面路由组
Route::group(['middleware' => ['web']], function () {
    # 公开页面
    # 主页
    Route::get('/', 'WelcomeController@index');
    # 注册页
    Route::get('register', 'AuthController@getRegister');
    # 邮件注册，csrf防止跨站脚本攻击
    Route::post('register/email', ['middleware' => 'csrf', 'uses' => 'AuthController@postRegisterEmail']);
    # 重发激活邮件
    Route::get('activate/email/resend', 'AuthController@getActivateEmailResend');
    Route::post('activate/email/resend', ['middleware' => 'csrf', 'uses' => 'AuthController@postActivateEmailResend']);
    # 账号激活
    Route::get('activate/email/{user_id}/{activationCode}', 'AuthController@getActivateEmail');
    Route::post('activate', 'AuthController@postActivate');
    Route::get('activate/success', 'AuthController@getActivateSuccess');
    # 重置密码
    Route::get('reset', 'AuthController@getReset');
    Route::post('reset/email', 'AuthController@postEmailReset');
    Route::get('reset/email/{userId}/{remindCode}', 'AuthController@getEmailResetComplete');
    Route::post('reset/email/complete', 'AuthController@postEmailResetComplete');
    Route::get('reset/success', 'AuthController@getResetSuccess');

    # 登录
    Route::get('login', 'AuthController@getLogin');
    Route::post('login', 'AuthController@postLogin');
    # 退出
    Route::get('logout', 'AuthController@getLogout');


    # 普通用户可见页面
    Route::group(['middleware' => ['role:user']], function() {
        Route::get('home', 'HomeController@index');
    });

    # 管理员可见页面
    Route::group(['middleware' => ['role:admin']], function() {
        Route::get('manage', 'ManageController@index');
        Route::get('manage/category', 'ManageController@category');
        Route::get('manage/document', 'ManageController@document');
        Route::get('manage/user', 'ManageController@user');
    });
});

# API路由组
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api'], function ($api) {

        $api->group(['middleware' => ['internal']], function ($api) {
            # 内部请求API
        });

        # 公开API       
        # 登录
        $api->post('auth/login', ['middleware' => 'throttle:10,5', 'uses' => 'AuthApiController@login']);

        $api->group(['middleware' => ['role:user']], function ($api) {
            # 普通用户可用API
        });
        $api->group(['middleware' => ['role:admin']], function ($api) {
            # 管理员可用API
            
            # Document
            # 列表及搜索
            $api->get('document', ['uses' => 'DocumentApiController@getList']);
            # 提示
            $api->get('document/typeahead', ['uses' => 'DocumentApiController@typeahead']);
            # 详情
            $api->get('document/{id}', ['uses' => 'DocumentApiController@getDetail']);
            # 创建
            $api->post('document', ['uses' => 'DocumentApiController@store']);
            # 批量导入
            $api->post('document/import', ['uses' => 'DocumentApiController@import']);
            # 修改
            $api->put('document/{id}', ['uses' => 'DocumentApiController@update']);
            # 删除
            $api->delete('document/{id}', ['uses' => 'DocumentApiController@destroy']);
            
            # User
            # 列表及搜索
            $api->get('user', ['uses' => 'UserApiController@getList']);
            # 提示
            $api->get('user/typeahead', ['uses' => 'UserApiController@typeahead']);
            # 详情
            $api->get('user/{id}', ['uses' => 'UserApiController@getDetail']);
            # 创建
            $api->post('user', ['uses' => 'UserApiController@store']);
            # 修改
            $api->put('user/{id}', ['uses' => 'UserApiController@update']);
            # 删除
            $api->delete('user/{id}', ['uses' => 'UserApiController@destroy']);
            # 角色列表
            $api->get('user/{id}/role', ['uses' => 'UserApiController@getRole']);
            # 添加角色
            $api->post('user/role', ['uses' => 'UserApiController@postRole']);
            # 删除角色
            $api->delete('user/{user_id}/role/{role_id}', ['uses' => 'UserApiController@deleteRole']);
            # 角色列表
            $api->get('role', ['uses' => 'RoleApiController@getList']);
            # 角色提示
            $api->get('role/typeahead', ['uses' => 'RoleApiController@typeahead']);            
            
        });
    });
});
