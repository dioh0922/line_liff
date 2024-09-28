<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    use LINE\LINEBot\Constant\HTTPHeader;
    use LINE\LINEBot\HTTPClient\CurlHTTPClient;
    use LINE\LINEBot;

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    /*
    // LINEに今月の合計を返す
    $credit = ORM::for_table("credit")->create();
    $credit->pay_value = $_POST["value"];
    $credit->pay_detail = $_POST["detail"];
    $credit->set_expr("created_date", "NOW()");
    $credit->save();
    $month = date("Y-m-01");
    $next_month = date('Y-m-d', strtotime('first day of next month', strtotime(date('Y-m-d'))));

    $sum = ORM::for_table("credit")
    ->where_raw('(`created_date` > ? AND `created_date` < ?) AND is_deleted = 0', array($month, $next_month))
    ->sum("pay_value");

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