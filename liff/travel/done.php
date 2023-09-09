<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    ORM::configure('id_column_overrides', array(
      'travel_todo' => 'destination',
    ));    

    $done = ORM::for_table("travel_todo")
    ->select("*")
    ->where_raw('destination = ?', $_POST["destination"])
    ->find_one();
    $done->set_expr("done_date", "NOW()");
    $done->set_expr("is_done", "1");
    $done->save();

    $template = sprintf("%sに行った", $_POST["destination"]);

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV["ACCESSTOKEN"]);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV["SECRET"]]);        
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($template);
    $response = $bot->pushMessage($_ENV["UID"], $textMessageBuilder);


    $response = ["result" => 1];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>