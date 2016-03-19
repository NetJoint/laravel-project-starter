<?php

namespace App\Http\Middleware;

use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Role
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if ($request instanceof \Dingo\Api\Http\InternalRequest) {
            //内部请求忽略角色限制
            return $next($request);
        }
        if (Sentinel::guest()) {
            abort(401, '未登录或登录已过期');
        }
        $user = Sentinel::getUser();
        if (!$user->hasRole(explode('|', $roles))) {
            abort(403, '无权访问');
        }
        return $next($request);
    }

}
