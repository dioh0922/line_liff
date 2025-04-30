<?php
require_once(dirname(__FILE__)."/../vendor/autoload.php");

class GeminiCall{
  private $url = "https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s";
  private $model = "";
  private $key = "";
  private $geminiUrl = "";

  public function __construct() {
    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../env");
    $env->load();
    $this->key = $_ENV["GEMINI_API"];
    $this->model = $_ENV["GEMINI_MODEL"];
    $this->geminiUrl = sprintf($this->url, $this->model, $this->key);
  }

  public function callText($str){
    $requestData = [
      "contents" => [
        [
          "parts" => [
            [
              "text" => $str 
            ]
          ]
        ]
      ]
    ];
  
    return $this->call($requestData);
  }

  public function call($request){
    // cURLセッションの初期化
    $jsonData = json_encode($request);
    $ch = curl_init();

    // cURLオプションの設定
    curl_setopt($ch, CURLOPT_URL, $this->geminiUrl);  // リクエスト先のURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // レスポンスを文字列として返す
    curl_setopt($ch, CURLOPT_POST, true);  // POSTリクエスト
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);  // HTTP/2を明示的に指定
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Content-Type: application/json',  // Content-Typeをapplication/jsonに設定
      'Content-Length: ' . strlen($jsonData)  // コンテンツ長を設定
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);  // 送信するデータ
    $result = "";
    $response = curl_exec($ch);
    if($response === false){
      $result = curl_error($ch);
    }else{
      $responseData = json_decode($response);
      $result = $responseData->candidates[0]->content->parts[0]->text;
    }
    // cURLセッションを終了
    curl_close($ch);
    return $result;
  }
}