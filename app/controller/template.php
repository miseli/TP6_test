<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use think\Request;
use think\facade\View;
use think\Config;
use think\facade\Route;
use app\service\CaptchaGenerater;

class Template extends BaseController
{
    public function captcha($config='')
    {
        $default = Config::__make($this->app);
        $default->load('captcha','captcha');

        $captcha = new CaptchaGenerater($default);
        return $captcha->create($config)[0];
    }

    public function captcha_img($config='')
    {
        $src = Route::buildUrl('/captcha' . ($config ? "/{$config}" : ''));
        return "<img src='{$src}' onclick='this.src=\"{$src}?\"+Math.random();' />";
        // return '<img src="'.$src.'" onclick="this.src=\''.$src.'\'+Date.now()"/>';
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // $imgsrc = captcha_src('verify');
        $config = 'verify';
        $imgsrc = Route::buildUrl('/captcha' . ($config ? "/{$config}" : ''));

        // 模板变量赋值
        View::assign('name','ThinkPHP');
        View::assign('email','thinkphp@qq.com');
        // 或者批量赋值

        View::assign([
            'name'  => 'Cube',
            'email' => '123456@cube.com',
            'imgsrc' => $this->captcha_img($config),
        ]);


        // 模板输出
        return View::fetch('1index');
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
