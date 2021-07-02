<?php
namespace app;

use app\common\constant\ErrorNums;
use app\common\exception\AppException;
use ChengYi\exception\ChengYiException;
use ChengYi\exception\RateLimitException;
use ChengYi\util\SnowFlake;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\db\exception\PDOException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\App;
use think\facade\Log;
use think\Response;
use Throwable;

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
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
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
        } catch (\Exception $e) {
        }
        // 使用内置的方式记录异常日志
        // parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            $responseData = ['code' => ErrorNums::PARAM_ILLEGAL, 'msg' => $e->getError()];
            $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
            return response($responseData, 200, [], 'json');
        }

        // 请求异常
        if ($e instanceof HttpException && $request->isAjax()) {
            $responseData = ['code' => $e->getStatusCode(), 'msg' => $e->getMessage()];
            $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
            return response($responseData, $e->getStatusCode(), [], 'json');
        }

        // 限流
        if ($e instanceof RateLimitException) {
            $responseData = ['code' => ErrorNums::TOO_MANY_REQUEST, 'msg' => $e->getMessage()];
            $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
            return response($responseData, 200, [], 'json');
        }

        if ($e instanceof AppException
            || $e instanceof ChengYiException
        ) {
            $responseData = ['code' => $e->getCode(), 'msg' => $e->getMessage()];
            if (App::isDebug()) {
                $responseData['err_msg'] = $e->getMessage();
                $responseData['file'] = $e->getFile();
                $responseData['line'] = $e->getLine();
                $responseData['trace'] = $e->getTrace();
            }
            $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
            return response($responseData, 200, [], 'json');
        }

        if ($e instanceof PDOException) {
            Log::error("数据库异常:" . $e->getMessage() . ',trace:' . $e->getTraceAsString());
            $responseData = ['code' => ErrorNums::DB_ERROR, 'msg' => 'sys error'];
            if (App::isDebug()) {
                $responseData['err_msg'] = $e->getMessage();
                $responseData['file'] = $e->getFile();
                $responseData['line'] = $e->getLine();
                $responseData['trace'] = $e->getTrace();
            }
            $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
            return response($responseData, 200, [], 'json');
        }

        // 其他错误交给系统处理
        $responseData = ['code' => ErrorNums::SYS_ERROR, 'msg' => '系统异常'];
        $responseData['trace_id'] = SnowFlake::getInstance()->getCurrentId();
        if (App::isDebug()) {
            $responseData['err_msg'] = $e->getMessage();
            $responseData['file'] = $e->getFile();
            $responseData['line'] = $e->getLine();
            $responseData['trace'] = $e->getTrace();
        }
        return response($responseData, 200, [], 'json');
    }
}
