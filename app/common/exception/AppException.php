<?php


namespace app\common\exception;


use app\common\constant\ErrorNums;
use Throwable;

class AppException extends \Exception
{
    public function __construct($code = 0, $message = null, Throwable $previous = null) {
        if (is_null($message)) {
            $message = ErrorNums::getInstance()->getMessage($code);
        }
        parent::__construct($message, $code, $previous);
    }
}
