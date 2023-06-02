<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=localhost;charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

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

    $template = sprintf("今月は合計：%s", number_format($sum));

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV["ACCESSTOKEN"]);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV["SECRET"]]);        
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($template);
    $response = $bot->pushMessage($_ENV["UID"], $textMessageBuilder);

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>