<?php
namespace app\controller;

use app\BaseController;

class Index extends BaseController
{

    /**
     * hello
     * @return string
     */
    public function index(): string {
        return 'hello';
    }
}
