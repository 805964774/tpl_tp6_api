<?php
// 事件定义文件
use app\listener\HttpEnd;
use app\listener\HttpRun;

return [
    'bind'      => [
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [
            HttpRun::class
        ],
        'HttpEnd'  => [
            HttpEnd::class
        ],
        'LogLevel' => [],
        'LogWrite' => [],
    ],

    'subscribe' => [
    ],
];
