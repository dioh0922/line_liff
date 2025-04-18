<?php
  require_once(dirname(__FILE__)."/../../vendor/autoload.php");
  require(dirname(__DIR__)."/../lib/GeminiCall.php");

  $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
  $env->load();

  $data = json_decode(file_get_contents("php://input"), false);
  try{

    $request = file_get_contents("php://input");
    $request .= '日本語で回答してください。\n';
    $request .= 'valueは調味料の分量を表します。それぞれを重量に変換してください。大さじは17ml、小さじは5mlとして計算してください。';
    $request .= '変換した結果は{\"id\": id, \"gram\":重量}のjson構造を使い、JSON文字列のみを返してください。 複数ある場合は配列として返してください';

    $gemini = new GeminiCall();

    $response = $gemini->callText($request);
    $replyMessage = preg_replace('/^```json|```$/', '', $response);

  }catch(Exception $e){
      $replyMessage = "エラーになりました";
      $log->error($e);
  }
  echo $replyMessage;
  
