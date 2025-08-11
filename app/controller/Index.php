<?php
declare (strict_types = 1);

namespace app\controller;

use app\BaseController;
use think\Request;
use think\facade\View;
use think\Config;
use think\facade\Route;

use think\facade\Db;
use app\model\HeaderModel;

// require  __DIR__.'/../../../common/common.php';

function getRandomStringOrProvided($str = null) {
    // 主语
    $subjects = ["李柯燚","苏佳男","于冬博","郝维东","水库浪子","WD"];
    // 地点状语
    $locationAdverbials = ["在图书馆里","在山顶","在学校的操场上","在家里","在咖啡馆的角落","在公园的湖边","在超市的收银台旁","在海边","在办公室的电脑前","在火车站的候车室"];
    $locationAdverbials = ["在家里","在学校","在图书馆","在超市","在公园","在电影院","在咖啡馆","在医院","在火车站","在飞机场","在办公室","在餐馆","在海滩","在山上","在湖边","在体育馆","在游乐园","在博物馆","在地铁站","在公交站"];

    // 谓语
    $predicates = ["看着","听着","笑着","跳着","吃着","走着","想着","说着","睡着","唱着"];
    // ,"说话","吃饭","睡觉","跑步","唱歌","写字","笑","哭","思考","看书","游泳","开车","打电话","洗衣服","做饭","跳舞","旅行","上班","玩游戏","购物"];
    // 宾语
    $objects = ["一本书","那只蝴蝶","美丽的风景","午餐","他的建议","电影的结局","一朵云","明天的计划","老师的表扬","妈妈的拥抱","夜晚的星星","一杯咖啡","今天的报纸","游戏的规则","爸爸的旧照片","朋友的来信","窗外的小鸟","冰淇淋的味道","未来的梦想","昨晚的晚餐"];

    // 检查$str是否为空或未定义
    if (is_null($str) || $str === '') {
        // 生成随机字符串
        $randomString = $subjects[array_rand($subjects, 1)] . $locationAdverbials[array_rand($locationAdverbials, 1)] . $predicates[array_rand($predicates, 1)] . $subjects[array_rand($subjects, 1)] . "。\n";

        return $randomString;
    }

    // 返回字符串
    return $str;
}

class Index extends BaseController
{
    // http://127.0.0.1:8010/tp6/public/index/index/config/123
    public function index($config='default')
    {
        $ret = 'Version' . \think\facade\App::version() . ' ' . $config;
        return $ret;
    }

    // 使用tp6模板功能,返回入厂申请
    public function reportwork()
    {
        if(Request()->isPost()){
            return 'post';
        }else{
            return View::fetch();
        }
    }

    // https://...../tp6/public/index/tuozhuaipaixu
    public function tuozhuaipaixu()
    {
        $hm = new HeaderModel();
        return $hm->order('id')->limit(10)->findOrEmpty();

        if(Request()->isPost()){
            // 更新old_header_name

            return 'post';
        }else{
            // $model = new HeaderModel();
            // $model->save([
            //     "header_name"=>"1654",
            //     "update_time"=>"123",
            //     "old_header_name"=>""
            // ]);
            // $data = $model->queryAll();
            // dump($data);
            // return 123;


            $query = Db::query("SELECT * FROM baobei_header ORDER BY id DESC LIMIT 1");
            dump($query);
            return 123;
            // return View::fetch();
        }
    }

    //https://....../tp6/public/index/index2
    public function index2()
    {
        View::assign('list', [
            [
                'name'  => 'OA办公平台',
                'url' => 'https://oa.fire5.us.kg/',
                'user' => '',
                'pwd' => ''
            ],
            [
                'name'  => '综合管理平台',
                'url' => 'https://zonghe.fire5.us.kg/',
                'user' => '20421',
                'pwd' => 'A@135246'
            ],
            [
                'name'  => '安全管理平台',
                'url' => 'https://anquan.fire5.us.kg/',
                'user' => '20790',
                'pwd' => 'h0094600'
            ],
            [
                'name'  => 'HTTP文件管理平台',
                'url' => 'https://file.fire5.us.kg/',
                'user' => 'LIU',
                'pwd' => ''
            ],
            // [
            //     'name'  => 'FTP文件管理平台',
            //     'url' => 'ftp://ftp.fire5.us.kg:2121',
            //     'user' => 'LIU',
            //     'pwd' => ''
            // ],
            [
                'name'  => 'web管理平台',
                'url' => 'https://web.fire5.us.kg/',
                'user' => '',
                'pwd' => ''
            ],
            [
                'name'  => '中国人事考试网',
                'url' => 'http://www.cpta.com.cn/',
                'user' => 'zhuanshil',
                'pwd' => '000000Qb'
            ],
            [
                'name'  => '中注册会计师',
                'url' => 'https://cpaexam.cicpa.org.cn/default.shtml',
                'user' => '',
                'pwd' => ''
            ],
        ]);

        return View::fetch();
        // return View::fetch('template/2index');
    }

    public function excelwordmiddle(Request $request){
        // header('Access-Control-Allow-Origin: *');
        if($request->isPost()){
            $params = $request->param();
            $url = trim($params['url']);
            exec('start "" "'.$url.'"');
        }
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function hehe(){
        $encrypt = $this->_getsmsParams();
        $decrypt = $this->_decodesmsParams($encrypt);
        echo $encrypt;
        echo '<p>';
        echo $decrypt;
    }

    private function _getsmsParams($date = null, $key = 'abcdefg123456hi0') {
        if(!isset($date))
            $date =  date('Y-m-d H:i:s');
        $smsParams = openssl_encrypt($date, 'AES-128-ECB', $key);
        return $smsParams;
    }

    private function _decodesmsParams($encrypt, $key = 'abcdefg123456hi0') {
        return openssl_decrypt($encrypt, 'AES-128-ECB', $key);
    }

    public function DESencrypt($data, $key, $bs64 = true){

        $ret = openssl_encrypt($data, 'DES-ECB', $key);
        if(!$bs64)
            $ret = bin2hex(base64_decode($ret));
        return $ret;
    }

    public function DESdecrypt($data, $key, $bs64=true){

        if(!$bs64){
            $data = hex2bin($data);
            $data = base64_encode($data);
        }

        return openssl_decrypt($data, 'DES-ECB', $key);
    }
    public function test1(){
        $ret = ['a'=>1, 'b'=>2];
        return json($ret);
    }

    public function text2image($value = null, $outname = 'img', $fontsize = 20, $x = 0, $y = 50, $angle = 0, $viewnow = true)
    {

        // fontsize:30,width:40;  fontsize:20,width:27;


        if ($fontsize == 20) {

        } else if ($fontsize == 30) {

        }

        $value = getRandomStringOrProvided($value);
        $signatureText = $value;
        $imageWidth = 540;
        $imageHeight = 200;

        $signatureImage = imagecreatetruecolor($imageWidth, $imageHeight);
        $backgroundColor = imagecolorallocate($signatureImage, 255, 255, 255);
        $textColor = imagecolorallocate($signatureImage, 0, 0, 0);

        imagefill($signatureImage, 0, 0, $backgroundColor);
        imagettftext($signatureImage, $fontsize, $angle,$x, $y, $textColor, "c:/windows/fonts/simsun.ttc", $signatureText);
        // imagettftext($signatureImage, $fontsize, $angle,$x, $y, $textColor, "c:/windows/fonts/arial.ttf", $signatureText);

        // 输出合并后的图像到浏览器或保存到文件
        if($viewnow){
            \app\third_lib\ImportExportSetting::view_set($outname, 'jpg');
        }else{
            \app\third_lib\ImportExportSetting::export_set($outname, 'jpg');
        }
        imagejpeg($signatureImage);

        // 释放内存
        imagedestroy($signatureImage);
        unset($signatureImage);
        // imagepng($signatureImage, "G:/www/signature.png");
        // imagedestroy($signatureImage);
    }
}
