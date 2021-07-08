<?php
declare (strict_types=1);

namespace app\listener;

use think\facade\Log;
use think\Response;
use think\response\Json;

class HttpEnd
{
    /**
     * 事件监听处理
     *
     * @param Response $event
     */
    public function handle(Response $event) {
        if ($event instanceof Json || $event->getCode() != 200) {
            $header = $event->getHeader();
            $content = $event->getContent();
            Log::response(json_encode(['header' => $header, 'content' => $content]));
        }
    }
}
