<?php
/**
 * 默认1s请求一次，拦截重复请求
 */
return [
    /**
     * 桶的容量
     */
    'capacity' => 1,

    /**
     * 添加token的速率，单位s
     */
    'inc_rate' => 1,

    /**
     * 获取token的速率，单位s
     */
    'dec_num' => 1,

    /**
     * 缓存失效时间，用户记录上次请求的一些信息，单位s
     */
    'cache_expire' => 60,
];
