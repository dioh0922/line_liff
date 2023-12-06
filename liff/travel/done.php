<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";charset=utf8;dbname=".$_ENV["DB_DB"]);
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

    $client = new \GuzzleHttp\Client();
    $config = new \LINE\Clients\MessagingApi\Configuration();
    $config->setAccessToken($_ENV["ACCESSTOKEN"]);
    $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
    client: $client,
    config: $config,
    );

    $message = new \LINE\Clients\MessagingApi\Model\TextMessage(['type' => 'text','text' => sprintf("%sに行った", $_POST["destination"])]);
    $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
        'to' => $_ENV["UID"],
        'messages' => [$message],
    ]);
    $response = $messagingApi->pushMessage($request);

    $response = ["result" => 1];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>