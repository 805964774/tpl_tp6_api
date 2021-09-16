<?php


namespace app\middleware;


use ChengYi\util\LeakyBucket;
use Closure;
use think\facade\Log;
use think\Request;
use think\Response;

/**
 * 统一限流中间件
 * Class RateLimit
 * @package app\middleware
 */
class RateLimit
{
    /**
     * @param \think\Request $request
     * @param \Closure $next
     * @return \think\Response
     * @throws \ChengYi\exception\ChengYiException
     * @throws \ChengYi\exception\RateLimitException
     */
    public function handle(Request $request, Closure $next): Response {
        $ip = get_real_ip();
        $controller = $request->controller();
        $action = $request->action();
        $param = $request->get('s');
        $url = $request->baseUrl();
        $key = md5($url . $ip . $controller . $action . $param);
        LeakyBucket::getInstance()->rateLimit($key);
        return $next($request);
    }
}
