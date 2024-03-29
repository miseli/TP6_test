<?php
namespace app\controller;

use app\BaseController;

use think\Request;

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

    public function signature_test($value='')
    {
        $signatureText = "John Doe";
        $imageWidth = 200;
        $imageHeight = 100;

        $signatureImage = imagecreatetruecolor($imageWidth, $imageHeight);
        $backgroundColor = imagecolorallocate($signatureImage, 255, 255, 255);
        $textColor = imagecolorallocate($signatureImage, 0, 0, 0);

        imagefill($signatureImage, 0, 0, $backgroundColor);
        imagettftext($signatureImage, 20, 0, 10, 50, $textColor, "c:/windows/fonts/arial.ttf", $signatureText);

        imagepng($signatureImage, "G:/www/signature.png");
        imagedestroy($signatureImage);
    }
}
