<?php


namespace app\controller;


use think\Response;

/**
 * 错误
 * Class Error
 * @package app\controller
 */
class Error
{

    public function _404(): Response {
        return Response::create('<html lang="zh-cn">
<head><title>404 Not Found</title></head>
<body>
<div style="text-align: center;"><h1>404 Not Found</h1></div>
<hr><div style="text-align: center;">RD-TPL</div>
</body>
</html>', 'html', 404);
    }
}
