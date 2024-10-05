<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    use LINE\LINEBot\Constant\HTTPHeader;
    use LINE\LINEBot\HTTPClient\CurlHTTPClient;
    use LINE\LINEBot;

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $log = new Logger("postback-log");
    $log->pushHandler(new StreamHandler(sprintf("logs/%s.log", date("Ymd")), Logger::DEBUG));

    $log->info("handle event", ["request" => $_POST]);

    $eve = $_POST["event"];
    $target = $eve[0];
    $source = $target["source"];
    $log->info("target source", ["request" => $source]);


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

    echo json_encode($_POST, JSON_UNESCAPED_UNICODE);
?>