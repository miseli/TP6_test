<?php
namespace app\controller;

use app\BaseController;
use QRcode;

class Liu
{
    public function index()
    {
        return 'Version' . \think\facade\App::version();
    }

    public function asdf($name = 'ThinkPHP6')
    {
        // Header("Content-type: image/png");
        header("Content-Type: image/png; charset=utf-8");
        echo QRcode::png('some othertext 1234');
        exit;
        // return 'hello,' . $name;
    }
}
