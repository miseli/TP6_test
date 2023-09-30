<?php
declare (strict_types = 1);

namespace app\controller;

use think\Request;

use app\BaseController;

use app\model\User;
use app\service\JwtService;

class Login extends BaseController
{
    protected $middleware = ['checkToken'];

    public function decode(Request $request){
        $jwt = $request->param('jwt');
        var_dump(JwtService::verify($jwt));
    }

    public function login(Request $request)
    {

        if($request->isPost()){
            $params = $request->param();
            $username = trim($params['username']);
            $password = trim($params['password']);
            // $password = trim(sha1($params['password']));

            $db = new User();
            $index = $db->where('username', $username)->find();

            if(isset($index)){
                if($password==$index['password']){
                    //更新登录时间
                    $index->lastlogin = time();
                    $index->save();

                    //根据id生成token
                    $token = JwtService::sign(['id'=>$index['id']]);
                    return $this->msg(1, '登录成功', $token);
                }else{
                    return $this->msg(0,'密码错误');
                }
            }else{
                return $this->msg(0,'用户不存在');
            }
        }else{
            return $this->msg(0, '非法访问');
        }
    }

    private function msg($code, $msg, $token=''){
        return json_encode(['code'=>$code, 'msg'=>$msg]);
        // return json_encode(['code'=>$code, 'msg'=>$msg, 'token'=>$token]);
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
		return 'Login.php->Login->index';
        //
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
