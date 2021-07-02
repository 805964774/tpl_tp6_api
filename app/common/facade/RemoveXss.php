<?php


namespace app\common\facade;


use think\Facade;

/**
 * Class RemoveXss
 * @package app\common\facade
 * @method static html(string $key) 过滤html
 * @method static xss(string $key) 过滤xss攻击字符
 */
class RemoveXss extends Facade
{
    protected static function getFacadeClass(): string {
        return '\app\common\util\RemoveXss';
    }
}
