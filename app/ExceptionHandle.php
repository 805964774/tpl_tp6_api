<?php

namespace app;

use app\common\constant\ErrorNums;
use app\common\exception\AppException;
use ChengYi\abstracts\PoPo;
use ChengYi\exception\ChengYiException;
use ChengYi\exception\RateLimitException;
use ChengYi\util\SnowFlake;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\db\exception\PDOException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\App;
use think\helper\Str;
use think\Request;
use think\Response;
use Throwable;
use TypeError;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
        TypeError::class,
        AppException::class,
        RateLimitException::class,
        ChengYiException::class,
    ];

    private $exceptionToCode = [
        TypeError::class => ErrorNums::PARAM_ILLEGAL,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void {
        if ($this->isIgnoreReport($exception)) {
            return;
        }
        // 收集异常数据
        $data = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $this->getMessage($exception),
            'code' => $this->getCode($exception),
        ];
        $log = "[{$data['code']}]{$data['message']}[{$data['file']}:{$data['line']}]";

        if ($this->app->config->get('log.record_trace')) {
            $log .= PHP_EOL . $exception->getTraceAsString();
        }

        try {
            $this->app->log->record($log, 'error');
        } catch (Exception $e) {
        }
        // 使用内置的方式记录异常日志
        // parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response {
//        if (!$request->isJson()) {
//            return parent::render($request, $e);
//        }
        $respCode = 200;
        $header = [];
        switch ($e) {
            // 参数类型错误
            case $e instanceof TypeError:
                $trace = $e->getTrace();
                $parentClass = $trace[2]['class'] ?? '';
                if ($parentClass != PoPo::class) {
                    break;
                }
                $funcName = $trace[0]['function'] ?? '';
                if (Str::startsWith($funcName, "set")) {
                    $fileName = mb_substr($funcName, 3);
                    $responseData = $this->getResponseContent($e, ErrorNums::PARAM_ILLEGAL, Str::snake($fileName) . '类型错误');
                }
                break;
            // 验证异常
            case $e instanceof ValidateException:
                $responseData = $this->getResponseContent($e, ErrorNums::PARAM_ILLEGAL, $e->getError());
                break;
            // http 异常
            case $e instanceof HttpException:
                $responseData = $this->getResponseContent($e, $e->getStatusCode());
                break;
            // 限流
            case $e instanceof RateLimitException:
                $responseData = $this->getResponseContent($e, ErrorNums::TOO_MANY_REQUEST);
                break;
            // 项目异常和cheng yi包异常
            case $e instanceof AppException:
            case $e instanceof ChengYiException:
                $responseData = $this->getResponseContent($e, $e->getCode());
                break;
            // 数据库异常
            case $e instanceof PDOException:
                $responseData = $this->getResponseContent($e, ErrorNums::DB_ERROR, 'sys error');
                break;
        }
        if (!isset($responseData)) {
            $responseData = $this->getResponseContent($e, ErrorNums::SYS_ERROR);
        }
        return response($responseData, $respCode, $header, 'json');
    }

    /**
     * 获取响应信息
     * @param \Throwable $e
     * @param int $code
     * @param string $message
     * @return array
     */
    private function getResponseContent(Throwable $e, int $code, string $message = ''): array {
        if (empty($message)) {
            $message = $e->getMessage();
        }
        $responseData = [];
        $responseData['code'] = $code;
        $responseData['msg'] = $message;
        $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
        if (App::isDebug()) {
            $responseData['err_msg'] = $e->getMessage();
            $responseData['file'] = $e->getFile();
            $responseData['line'] = $e->getLine();
            $responseData['trace'] = $e->getTrace();
        }
        return $responseData;
    }
}
