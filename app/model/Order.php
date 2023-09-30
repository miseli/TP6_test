<?php
namespace app\model;

use think\Model;
use WpOrg\Requests\Session;
use WpOrg\Requests\Requests;
use WpOrg\Requests\Cookie;
use WpOrg\Requests\Hooks;





class Order extends Model
{
    protected $name = 'newtest';
    // protected $table = 'newtest';

    protected static function init() {
        //初始化
    }



































    // http://st2.pjchat.com/index.php?st=10
    // postmenu(key, postdata, "post", cookie)
    public static function postmenu($d, $data, $method, $cookie) {
        // $cookie = 'dcy=13504272173; mima=6f63605a89b31e32ca752a05d3dd9ab7; dcst=10';
        $url = "http://st2.pjchat.com/caidan.php?action=ok&tdate=".$d;
        $headers = [
            'cookie'=> $cookie,
            'Content-Type'=>"application/x-www-form-urlencoded"
        ];
        Requests::post($url, $headers, $data);
    }

    // 接收账号密码,返回登录cookies,此cookies可持续使用
    public static function checkAcountCookies($username, $password) {
        $signCookie = null;

        $url = 'http://st2.pjchat.com/index.php?st=10';
        $headers = [
            "Content-Type"=>"application/x-www-form-urlencoded",
            "Referer"=>"http://st2.pjchat.com/index.php?st=10"
        ];

        $data = "username=".$username."&password=".$password;

        $res = Requests::post($url, $headers, $data);
        $jar_cookes = $res->cookies;
        if($jar_cookes->offsetExists('mima')){
            $mima = $jar_cookes['mima'];
            $signCookie = "dcst=10; mima=".$mima.";dcy=".$username;
        }
        return $signCookie;
    }
}