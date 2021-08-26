<?php
declare (strict_types=1);

namespace app\listener;

use think\facade\Log;
use think\Response;

class HttpEnd
{

    const MAX_CONTENT_LENGTH = 1024;

    /**
     * 事件监听处理
     *
     * @param Response $event
     */
    public function handle(Response $event) {
        $header = $event->getHeader();
        if (ob_get_length() > self::MAX_CONTENT_LENGTH) {
            $content = substr($event->getContent(),0, self::MAX_CONTENT_LENGTH);
        } else {
            $content = $event->getContent();
        }
        Log::response(json_encode(['header' => $header, 'content' => $content]));
    }
}
