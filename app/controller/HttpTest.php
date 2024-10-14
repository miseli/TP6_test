<?php
// http://localhost:8010/tp6/public/httptest
namespace app\controller;

class HttpTest
{
    public function index()
    {
      // 使用示例
      $thirdPartyUrl = 'http://127.0.0.1:8010/200k.php';
      $getResponse = $this->httpGet($thirdPartyUrl);
      dump($getResponse);
      // $postResponse = $this->httpPost($thirdPartyUrl, ['key' => 'value']);
    }

    // 发送GET请求
    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // 发送POST请求
    private function httpPost($url, $data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}






// composer require guzzlehttp/guzzle
// use GuzzleHttp\Client;

// // 创建GuzzleHttp客户端实例
// $client = new Client();

// // 发送GET请求
// $response = $client->request('GET', $thirdPartyUrl);
// $getBody = $response->getBody()->getContents();

// // 发送POST请求
// $response = $client->request('POST', $thirdPartyUrl, [
//     'form_params' => ['key' => 'value']
// ]);
// $postBody = $response->getBody()->getContents();