<?php


namespace app\common\constant;

/**
 * 错误码常量
 * Class ErrorNums
 * @package app\common\constant
 */
class ErrorNums
{
    private static $_instance;

    const SUCCESS = 0; //请求成功
    /**
     * 1xx 系统类异常码
     */
    const SYS_ERROR = 10001; // 系统未知错误
    const DB_ERROR = 10002; // 系统数据库错误

    /**
     * 2xx 常规类异常码
     */
    const PARAM_ILLEGAL = 20001; // 参数错误
    const TOO_MANY_REQUEST = 20002; // 过多的请求
    const TOKEN_INVALID = 20003; // token invalid

    /**
     * 3xx 业务逻辑异常
     */
    const SEND_SMS_FAIL = 30001; // 短信发送失败
    const NO_SCAN = 30002; // 未扫码
    const NO_REGISTER = 30003;// 未注册
    const NO_LOGIN = 30004;// 未登录

    private $message = [
        ErrorNums::SUCCESS => 'ok',
        ErrorNums::SYS_ERROR => '系统未知错误',
        ErrorNums::DB_ERROR => '系统未知错误',
        ErrorNums::PARAM_ILLEGAL => '参数错误',
        ErrorNums::TOO_MANY_REQUEST => 'too many request',
        ErrorNums::TOKEN_INVALID => 'token invalid',
        ErrorNums::SEND_SMS_FAIL => '短信发送失败',
        ErrorNums::NO_SCAN => '未扫码',
        ErrorNums::NO_REGISTER => '未注册',
        ErrorNums::NO_LOGIN => '未登录',
    ];

    private function __construct() {
    }

    private function __clone() {
    }

    public static function getInstance(): ErrorNums {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function getMessage(int $code): string {
        return $this->message[$code] ?? '未知错误';
    }
}
