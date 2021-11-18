<?php
declare (strict_types=1);

namespace app\listener;


use ChengYi\util\SnowFlake;
use think\facade\App;
use think\facade\Log;

class HttpRun
{
    const MAX_CONTENT_LENGTH = 1024;

    /**
     * 事件监听处理
     *
     */
    public function handle() {
        $header = App::getInstance()->request->header();
        $input = App::getInstance()->request->param('', '', null);
        if (is_array($input)) {
            $input = json_encode($input);
        }
        if ($input > self::MAX_CONTENT_LENGTH) {
            $input = mb_substr($input, 0, self::MAX_CONTENT_LENGTH);
        }
        $ip = get_real_ip();
        $url = App::getInstance()->request->url();
        // 生成trace_id
        SnowFlake::getInstance()->nextId();
        Log::request(json_encode(['url' => $url, 'header' => $header, 'input' => $input, 'ip' => $ip]));
    }
}
