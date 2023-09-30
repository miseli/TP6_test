<?php
declare (strict_types = 1);

namespace app\service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\VarDumper\VarDumper;

define("TokenPublicKey", env('app.tokenpublickey', '123456'));

class JwtService extends \think\Service
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
        //
    }

    static public function sign($data='', $t=5184000/*过期时间(秒)*/, $key=TokenPublicKey){

        $payload = array(
            'iss' => 'http://example.org',      //签发机构
            'aud' => 'http://example.com',      //接收机构
            'sub' => 'http://example.com',      //所面向的用户
            'iat' => time(),                    //签发时间
            'nbf' => time(),                    //此时间以前jwt不可用
            'exp' => time()+$t,                 //过期时间
            'data' => $data
        );
        try{
            $jwt = JWT::encode($payload, $key, 'HS256');
            header('Authorization: '.$jwt);
            // setcookie('Authorization', $jwt);
            return $jwt;
        }catch(Exception $e){
            if(PHP_VERSION >= 5.4)
            {
                http_response_code('500');
            }else{
                header('HTTP/1.1 500 No Content');
            }
            exit;
        }
    }

    static public function verify($jwt=null, $key=TokenPublicKey, $withCookie=false)
    {
        $ret = null;
        if(!isset($jwt))


            if(isset($_COOKIE['Authorization']) && $withCookie)
            {
                $jwt = $_COOKIE['Authorization'];
            }else{
                $headers = getallheaders();
                if(isset($headers['Authorization'])){
                    // print($_SERVER['HTTP_AUTHORIZATION']);
                    $jwt = $headers['Authorization'];
                }else{
                    return $ret;
                }
            }

        try{
            JWT::$leeway = 2592000*2;//对时间的缓冲
            // $decoded = JWT::decode($jwt, $key, array('HS256'));
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            JWT::$leeway = 0;
            return $decoded;
        }catch(\Exception $e){
            return '非法标识';
        }
        return $ret;

    }
}
