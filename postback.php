<?php
    require_once("./vendor/autoload.php");
    require_once(dirname(__DIR__)."/lib/GeminiCall.php");
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;
    use GeminiAPI\Client;
    use GeminiAPI\Resources\Parts\TextPart;

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $json = json_decode(file_get_contents("php://input"));
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

    $log = new Logger("postback-log");
    $log->pushHandler(new StreamHandler(sprintf("logs/%s.log", date("Ymd")), Logger::DEBUG));
    $log->info("receive event");
    $log->info("raw_request", ["raw_data" => $json]);


    if($json != null){

        if($json->events[0]->type === "message"){
            //入力してきた内容はmessageイベント

            $today = date("Y-m-d");
            $current = ORM::for_table("g_access_count")
            ->select("access_count")
            ->where("access_day", $today)
            ->where("api_type", 1)
            ->find_one();

            $limit = false;
            $today = date("Y-m-d");
            if($current != null){
                if($current->access_count >= $_ENV["G_ACCESS_LIMIT"]){
                    $limit = true;
                }else{
                    $access = ORM::for_table("g_access_count")
                    ->select("*")
                    ->where("access_day", $today)
                    ->where("api_type", 1)
                    ->find_one();
                    $access->access_count++;
                    $access->save();
                }
            }else{
                $access = ORM::for_table("g_access_count")->create();
                $access->access_day = $today;
                $access->api_type = 1;
                $access->save();
            }

            if(!$limit){
                $client = new \GuzzleHttp\Client();
                $config = new \LINE\Clients\MessagingApi\Configuration();
                $config->setAccessToken($_ENV["ACCESSTOKEN"]);
                $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
                    client: $client,
                    config: $config,
                );
                $log->info("request message", ["message" => $json->events[0]->message->text]);
                try{
                    $gemini = new GeminiCall();
                    $replyMessage = $gemini->callText('日本語で回答してください。\n' . $json->events[0]->message->text);
                }catch(Exception $e){
                    $replyMessage = "エラーになりました";
                    $log->error($e);
                }

            }else{
                $replyMessage = "本日の制限を超えています";
            }

            $message = new \LINE\Clients\MessagingApi\Model\TextMessage(["type" => "text","text" => $replyMessage]);
            $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                'to' => $json->events[0]->source->userId,
                'messages' => [$message],
            ]);
            $log->info("create message", ["message" => $message]);

            $response = $messagingApi->pushMessage($request);
            $log->info("success send message", ["response" => $response]);

            return ;
        }

        $data = $json->events[0]->postback->data;
        $to_user_id = $json->events[0]->source->userId;
        $params = explode("=", $data);
        if(!array_key_exists($params[0], $param_list)){
            // dataに含まれているべき内容のみの場合は実行する
            $log->error("invalid data");        
            return;
        }

        $log->info("handle event", ["event" => $params[1]]);

        switch($params[1]){
            case "movie":

                // 映画のpostbackイベントなら最新日付の1つを返す
                $current = ORM::for_table("moviedata")
                ->select("title")
                ->order_by_desc("date")
                ->limit(5)
                ->find_many();

                $log->debug("success access DB");

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
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(["type" => "text","text" => $str]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    //"to" => $_ENV["UID"],
                    "to" => $to_user_id,
                    "messages" => [$message],
                ]);

                $response = $messagingApi->pushMessage($request);

                $log->info("success send message", ["response" => $response]);

                break; 
            case "travel":

                $month = date("Y-m-01 00:00:00");
                $next_month = date("Y-m-d", strtotime("first day of next month", strtotime(date("Y-m-d"))));
            
                $current = ORM::for_table("travel_todo")
                ->select("destination")
                ->where_raw("`done_date` >= ? AND `done_date` < ? AND is_deleted = 0 AND is_done = 1", array($month, $next_month))
                ->order_by_desc("done_date")
                ->find_many();

                $log->debug("success access DB");

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
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(["type" => "text","text" => $str]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    //"to" => $_ENV["UID"],
                    "to" => $to_user_id,
                    "messages" => [$message],
                ]);

                $response = $messagingApi->pushMessage($request);

                $log->info("success send message", ["response" => $response]);

                break;
            case "todo":
                //TODO: 別の管理プロジェクトとDB統合予定

                $current = ORM::for_table("tasks")
                ->select("summary", "title")
                ->where("is_delete", 0)
                ->order_by_desc("created_at")
                ->find_many();

                $log->debug("success access DB");

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
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(["type" => "text","text" => $str]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    //"to" => $_ENV["UID"],
                    "to" => $to_user_id,
                    "messages" => [$message],
                ]);
                
                $response = $messagingApi->pushMessage($request);

                $log->info("success send message", ["response" => $response]);

                break;
            case "svr":
                $client = new \GuzzleHttp\Client();
                $config = new \LINE\Clients\MessagingApi\Configuration();
                $config->setAccessToken($_ENV["ACCESSTOKEN"]);
                $messagingApi = new \LINE\Clients\MessagingApi\Api\MessagingApiApi(
                    client: $client,
                    config: $config,
                );

                $str = [];
                $cert_log = $_ENV["BATCH_LOG_DIR"] . "/cert.log";
                $datePattern = '/([A-Za-z]{3} \s*\d{1,2} \s*\d{2}:\d{2}:\d{2} \s*[APM]{2} \s*[A-Z]{3} \s*\d{4})/';
                $latestDate = null;
                if(file_exists($cert_log)){
                    $log_str = file_get_contents($cert_log);
                    preg_match_all($datePattern, $log_str, $matches);
    
                    foreach ($matches[0] as $date) {
                        $timestamp = strtotime($date);
                        if ($latestDate === null || $timestamp > strtotime($latestDate)) {
                            $dateTime = DateTime::createFromFormat('M d H:i:s A T Y', $date);
                            $latestDate = $dateTime->format('Y/m/d');
                        }
                    }

                    $str[] = "certbot最新：" . $latestDate;
                }
                

                $mydns_log = $_ENV["BATCH_LOG_DIR"] . "/notice_mydns.jp";
                $datePattern = '/(\d{4}\/\d{2}\/\d{2} \d{2}:\d{2}:\d{2} UTC)/';
                $latestDate = null;
                if(file_exists($mydns_log)){
                    $log_str = file_get_contents($mydns_log);
                    preg_match($datePattern, $log_str, $matches);
                    $dateTime = DateTime::createFromFormat('Y/m/d H:i:s T', $matches[0]);
                    $latestDate = $dateTime->format('Y/m/d');
                    $str[] = "mydns最新：" . $latestDate;
                }
            
                $message = new \LINE\Clients\MessagingApi\Model\TextMessage(["type" => "text","text" => implode("\n", $str)]);
                $request = new \LINE\Clients\MessagingApi\Model\PushMessageRequest([
                    //"to" => $_ENV["UID"],
                    "to" => $to_user_id,
                    "messages" => [$message],
                ]);
                
                $response = $messagingApi->pushMessage($request);
                $log->info("batch current", ["log" => $str]);

                break;
            default:
                $log->info("missing action", ["event" => $params[1]]);
                break;
        }
        $log->info("handle end");
    }else{
        $log->error("empty json", ["data" => $json]);    
    }

?>