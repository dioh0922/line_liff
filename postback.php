<?php
    require_once("./vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../env");
    $env->load();
    ORM::configure("mysql:host=localhost;charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $json = json_decode(file_get_contents('php://input'));
    $param_list = [
        "action" => 0,
        "mode" => 1
    ];
    $mode_list = [
        "reminder" => 1,
        "todo" => 2,
        "movie" => 3,
        "none" => 99
    ];

    if($json != null){
        
        $data = $json->events[0]->postback->data;
        $params = explode("=", $data);
        if(!array_key_exists($params[0], $param_list)){
            // dataに含まれているべき内容のみの場合は実行する
            return;
        }

        switch($params[1]){
            case "movie":
                // 映画のpostbackイベントなら最新日付の1つを返す
                $current = ORM::for_table("moviedata")
                ->select("title")
                ->order_by_desc("date")
                ->limit(5)
                ->find_many();

                $str = "最後に見たのは\n";
                foreach($current as $key){
                    $str .= "・".$key["title"]."\n";
                }

                $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV["ACCESSTOKEN"]);
                $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV["SECRET"]]);        
                $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($str);
                $response = $bot->pushMessage($_ENV["UID"], $textMessageBuilder);
                break; 
            default:
                break;
        }
    }

?>