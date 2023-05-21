<?php
    require_once(dirname(__FILE__)."/../vendor/autoload.php");

    if(gethostname() != "localhost"){
        echo "localhost only";
        return ;
    }

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../env");
    $env->load();

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV["ACCESSTOKEN"]);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV["SECRET"]]);

    // TODO: PHP8版にアップデートする
    // 現状(202305)はhttps://packagist.org/packages/linecorp/line-bot-sdk の7.6.1

    $response = $bot->getRichMenuList()->getRawBody();
    $json = json_decode($response);
    foreach($json->richmenus as $value){
        echo $value->richMenuId."<br>";
        $bot->deleteRichMenu($value->richMenuId);
    }
    echo "done";
?>