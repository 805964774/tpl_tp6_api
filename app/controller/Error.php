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
        return response('', 404);
    }
}
