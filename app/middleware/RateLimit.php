<?php


namespace app\middleware;


use ChengYi\util\LeakyBucket;
use Closure;
use think\Request;
use think\Response;

/**
 * 统一限流中间件
 * Class RateLimit
 * @package app\middleware
 */
class RateLimit
{
    public function handle(Request $request, Closure $next): Response {
        $ip = get_real_ip();
        $controller = $request->controller();
        $action = $request->action();
        $param = $request->get('s');
        $key = md5($ip . $controller . $action . $param);
        LeakyBucket::getInstance()->rateLimit($key);
        return $next($request);
    }
}
