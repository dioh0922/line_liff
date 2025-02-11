<?php
  require_once(dirname(__FILE__)."/../../vendor/autoload.php");
  use GeminiAPI\Client;
  use GeminiAPI\Resources\Parts\TextPart;

  $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
  $env->load();

  $data = json_decode(file_get_contents("php://input"), false);
  try{
    $client = new Client($_ENV["GEMINI_API"]);
    $response = $client->geminiPro()->generateContent(
      new TextPart(file_get_contents("php://input")),
      new TextPart('日本語で回答してください。\n'),
      new TextPart('valueは調味料の分量を表します。それぞれを重量に変換してください。大さじは17ml、小さじは5mlとして計算してください。'),
      new TextPart('変換した結果は次のjson構造を使いJSON文字列として返してください。{\"id\": id, \"gram\":重量} 配列として返してください'),
    );
    $replyMessage = $response->text();
  }catch(Exception $e){
      $replyMessage = "エラーになりました";
      $log->error($e);
  }
  echo $replyMessage;
  
