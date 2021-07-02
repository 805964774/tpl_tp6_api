<?php
declare (strict_types = 1);

namespace app\middleware;

/**
 * 检测登录中间件
 * Class CheckLogin
 * @package app\middleware
 */
class CheckLogin
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        //
    }
}
