<?php


namespace app\common\facade;


use think\Facade;

/**
 * Class LeakyBucket
 * @package app\facade
 * @method static setData(array $data): string 保存登录信息
 * @method static getData(string $token, string $name = ''): ?string 获取保持的数据
 * @method static keepLiveLogin(string $token): ?string 登录保持
 */
class JsonWebToken extends Facade
{
    protected static function getFacadeClass(): string {
        return '\app\common\util\JsonWebToken';
    }
}
