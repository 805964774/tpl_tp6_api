<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
use app\command\curd\Curd;

return [
    // 指令定义
    'commands' => [
        'curd' => Curd::class,
    ],
];
