<?php
namespace app\controller;

use app\BaseController;
use QRcode;

class Liu
{
    public function index()
    {
        return 'Version ' . \think\facade\App::version();
    }

    // http://127.0.0.1:8010/tp6/public/index.php/Liu/createQRcode/name/123456
    public function createQRcode($name = 'http://192.168.0.110:8010/123.html')
    {
        // Header("Content-type: image/png");
        header("Content-Type: image/png; charset=utf-8");
        echo QRcode::png($name);
        exit;
        // return 'hello,' . $name;
    }
}
