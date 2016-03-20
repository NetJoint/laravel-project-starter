<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Router;
use Dingo\Api\Http\RateLimit\Handler;

class Throttle
{
    /**
     * Router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * Rate limit handler instance.
     *
     * @var \Dingo\Api\Http\RateLimit\Handler
     */
    protected $handler;

    /**
     * Create a new rate limit middleware instance.
     *
     * @param \Dingo\Api\Routing\Router         $router
     * @param \Dingo\Api\Http\RateLimit\Handler $handler
     *
     * @return void
     */
    public function __construct(Router $router, Handler $handler)
    {
        $this->router = $router;
        $this->handler = $handler;
    }

    /**
     * Perform rate limiting before a request is executed.
     *
     * @param \Dingo\Api\Http\Request $request
     * @param \Closure                $next
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $limit = 10, $expires = 10)
    {
        $route = $this->router->getCurrentRoute();
        
        $this->handler->rateLimitRequest($request, (int) $limit, (int) $expires);

        if ($this->handler->exceededRateLimit()) {
            abort(403, '您的请求太频繁了，超出了限制，请稍后再试！');
        }

        return $next($request);
    }

}
