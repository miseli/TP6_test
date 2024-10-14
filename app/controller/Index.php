<?php
namespace app\controller;

use app\BaseController;

use think\Request;


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
    public function index()
    {
        return 'Version' . \think\facade\App::version();
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
