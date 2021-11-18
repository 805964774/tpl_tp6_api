<?php
// 全局中间件定义文件
use app\middleware\RateLimit;
use ChengYi\middleware\SessionInit;
use think\middleware\AllowCrossDomain;

return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化，如果需要session，开启该中间件
    SessionInit::class,
    // 如果需要跨域，开启该中间件
    AllowCrossDomain::class,
    // 限流中间件
    RateLimit::class,
];
