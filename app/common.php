<?php
// 应用公共文件
use app\common\facade\RemoveXss;
use think\facade\App;
use think\facade\Request;

/**
 * 获取部署目录
 * @return string
 */
function get_deploy_path(): string {
    $documentRoot = Request::server('document_root');
    $rootPath = App::getRootPath();
    $strPos = mb_strlen($documentRoot);
    $path = substr($rootPath, $strPos);
    return rtrim($path, '/');
}

/**
 * 获取缓存失效时间
 * @param int $initValue
 * @param int $randomMax
 * @return int
 */
function get_cache_expire(int $initValue, int $randomMax = 300): int {
    if ($randomMax <= 10) {
        $randomMax = 20;
    }
    return mt_rand(10, $randomMax) + $initValue;
}

//过滤xss
function remove_xss(string $val): string {
    return RemoveXss::xss($val);
}

//过滤html
function remove_html(string $val): string {
    return RemoveXss::html($val);
}

/**
 * 获取用户ip
 * @return mixed|string
 */
function get_real_ip(): string {
    $forwarded = request()->header("x-forwarded-for");
    if ($forwarded) {
        $ip = explode(',', $forwarded)[0];
    } else {
        $ip = request()->ip();
    }
    return $ip;
}
