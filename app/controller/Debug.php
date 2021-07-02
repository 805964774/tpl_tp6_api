<?php


namespace app\controller;


use app\BaseController;
use ChengYi\util\LeakyBucket;
use think\facade\Event;

/**
 * 用于debug相关操作，仅用于测试和联调
 * Class Debug
 * @package app\controller
 */
class Debug extends BaseController
{
    /**
     * 限流
     * @throws \ChengYi\exception\ChengYiException
     */
    public function rateLimit() {
        /**
         * 限流的唯一标识
         * 尽量添加用户信息进行限流，不然所有人共用key的话，就会有大问题
         */
        $key = 1;
        LeakyBucket::getInstance()->rateLimit($key);
    }

    /**
     * 登录
     * 注册同理
     */
    public function login() {
        /**
         * 登录以后的逻辑，或者之前的逻辑，可以放在Event中
         */
        Event::trigger('UserLogin', [
            'type' => 111111, // 事件触发标识
            'param' => [''], // 事件的参数
        ]);
    }
}
