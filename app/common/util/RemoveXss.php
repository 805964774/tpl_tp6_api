<?php


namespace app\common\util;


use HTMLPurifier;
use HTMLPurifier_Config;

class RemoveXss
{
    public function html($val): string {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');
        $obj = new HTMLPurifier($config);
        return $obj->purify($val);
    }

    public function xss($val): string {
        $config = HTMLPurifier_Config::createDefault();
        $obj = new HTMLPurifier($config);
        return $obj->purify($val);
    }
}
