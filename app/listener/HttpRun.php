<?php
declare (strict_types=1);

namespace app\listener;


use ChengYi\util\SnowFlake;
use think\facade\App;
use think\facade\Log;

class HttpRun
{
    /**
     * 事件监听处理
     *
     */
    public function handle() {
        $header = App::getInstance()->request->header();
        $input = App::getInstance()->request->param();
        $ip = get_real_ip();
        $url = App::getInstance()->request->url();
        // 生成trace_id
        SnowFlake::getInstance()->nextId();
        Log::request(json_encode(['url' => $url,'header' => $header, 'input' => $input, 'ip' => $ip]));
    }
}
