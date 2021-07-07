<?php


namespace app\common\exception;


use app\common\constant\ErrorNums;
use Exception;
use Throwable;

class AppException extends Exception
{
    public function __construct(int $code = ErrorNums::SYS_ERROR, string $message = null, Throwable $previous = null) {
        if (is_null($message)) {
            $message = ErrorNums::getInstance()->getMessage($code);
        }
        parent::__construct($message, $code, $previous);
    }
}
