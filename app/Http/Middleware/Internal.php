<?php

namespace App\Http\Middleware;

use Closure;

class Internal
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request instanceof \Dingo\Api\Http\InternalRequest) {
            return $next($request);
        }
        abort(403, '无权访问');
    }

}
