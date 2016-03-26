<?php

namespace App\Http\Controllers\Api;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Transformers\UserTransformer;

class AuthApiController extends ApiController
{

    public function __construct()
    {
        
    }

    public function login(AuthLoginRequest $request)
    {
        $credentials = [
            'password' => $request->input('password'),
        ];

        if (is_numeric($request->input('login'))) {
            $credentials['mobile'] = $request->input('login');
        } else {
            $credentials['email'] = $request->input('login');
        }
        $remember = (bool) $request->input('remember_me', false);
        try {
            if (Sentinel::authenticate($credentials, $remember)) {
                $user = Sentinel::getUser();
                return $this->response->item($user, new UserTransformer);
            }
            return $this->response->errorUnauthorized('用户名或密码错误');
        } catch (NotActivatedException $e) {
            return $this->response->errorUnauthorized('账号尚未激活');
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();
            return $this->response->errorUnauthorized("您尝试登录次数过多，为了保护账号安全，请您等待 {$delay} 秒后再试");
        }
    }

    public function logout()
    {
        Sentinel::logout();
        return $this->response->array(['message' => '退出登录成功']);
    }
}
