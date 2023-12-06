<?php
    require_once("./vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";charset=utf8;dbname=".$_ENV["DB_DB"]);
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
        "travel" => 4,
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

                $str = "【映画】最後に見た5つは\n";
                foreach($current as $idx => $key){
                    $str .= "・".$key["title"];
                    if($idx !== array_key_last($current)){
                        $str .= "\n";
                    }
                }

                $client = new \GuzzleHttp\Client();
                $config = new \LINE\Clients\MessagingApi\Configuration();
                $config->setAccessToken($_ENV["ACCESSTOKEN"]);
                $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
                client: $client,
                config: $config,
                );
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(['type' => 'text','text' => $str]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    'to' => $_ENV["UID"],
                    'messages' => [$message],
                ]);
                break; 
            case "travel":
                $month = date("Y-m-01 00:00:00");
                $next_month = date('Y-m-d', strtotime('first day of next month', strtotime(date('Y-m-d'))));
            
                $current = ORM::for_table("travel_todo")
                ->select("destination")
                ->where_raw('`done_date` >= ? AND `done_date` < ? AND is_deleted = 0 AND is_done = 1', array($month, $next_month))
                ->order_by_desc("done_date")
                ->find_many();

                $str = "【旅行】今月は\n";
                foreach($current as $idx => $key){
                    $str .= "・".$key["destination"];
                    if($idx !== array_key_last($current)){
                        $str .= "\n";
                    }
                }

                $client = new \GuzzleHttp\Client();
                $config = new \LINE\Clients\MessagingApi\Configuration();
                $config->setAccessToken($_ENV["ACCESSTOKEN"]);
                $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
                client: $client,
                config: $config,
                );
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(['type' => 'text','text' => $str]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    'to' => $_ENV["UID"],
                    'messages' => [$message],
                ]);
                break;
            case "todo":
                $current = ORM::for_table("dev_task_list")
                ->select("todo_title", "title")
                ->where_raw('is_deleted = 0 AND is_completed = 0')
                ->order_by_desc("todo_title")
                ->find_many();

                $str = "【タスク】\n";
                foreach($current as $idx => $key){
                    $str .= "・".$key["title"];
                    if($idx !== array_key_last($current)){
                        $str .= "\n";
                    }
                }

                $client = new \GuzzleHttp\Client();
                $config = new \LINE\Clients\MessagingApi\Configuration();
                $config->setAccessToken($_ENV["ACCESSTOKEN"]);
                $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
                client: $client,
                config: $config,
                );
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(['type' => 'text','text' => $str]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    'to' => $_ENV["UID"],
                    'messages' => [$message],
                ]);
                
                break;
            default:
                break;
        }
    }

?>