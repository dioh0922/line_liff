<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    use LINE\LINEBot\Constant\HTTPHeader;
    use LINE\LINEBot;

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $log = new Logger("postback-log");
    $log->pushHandler(new StreamHandler(sprintf("logs/%s.log", date("Ymd")), Logger::DEBUG));

    $log->info("handle event", ["request" => $_POST]);

    $token = $_POST["token"];
    if($token == null){
        $token = $_ENV["UID"];
    }

    // POSTデータを設定
    $postFields = http_build_query([
        "id_token" => $token,
        "client_id" => $_ENV["CHANNELID"]
    ]);

    $url = 'https://api.line.me/oauth2/v2.1/verify';

    // cURLセッションの初期化
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    // リクエストの実行
    $response = curl_exec($ch);

    // エラーチェック
    if (curl_errno($ch)) {
        $log->error('Error: ',["msg" => curl_error($ch)]);
    }

    // cURLセッションを閉じる
    curl_close($ch);

    $log->info("response", ["res" => $response]);
    //firebase/php-jwt
    //jwtのsubがID

    /*
    $client = new \GuzzleHttp\Client();
    $config = new \LINE\Clients\MessagingApi\Configuration();
    $config->setAccessToken($_ENV["ACCESSTOKEN"]);
    $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
    client: $client,
    config: $config,
    );

    $message = new \LINE\Clients\MessagingApi\Model\TextMessage(['type' => 'text','text' => sprintf("今月は合計：%s", number_format($sum))]);
    $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
        'to' => $_ENV["UID"],
        'messages' => [$message],
    ]);
    $response = $messagingApi->pushMessage($request);
    */

    echo $response;
?>