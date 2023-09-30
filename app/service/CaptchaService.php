<?php
declare (strict_types = 1);

namespace app\service;

use think\Route;


class CaptchaService extends \think\Service
{
    /**
     * 注册服务
     *
     * @return mixed
     */
    public function register()
    {
    	//
    }

    /**
     * 执行服务
     *
     * @return mixed
     */
    public function boot()
    {
        $this->registerRoutes(function (Route $route) {
            $route->get('captcha/[:config]', "\\app\\controller\\template@captcha")->ext('html');
        });
    }
}


