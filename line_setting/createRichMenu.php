<?php
    require_once(dirname(__FILE__)."/../vendor/autoload.php");

    if($_SERVER["HTTP_HOST"] != "localhost"){
        echo "localhost only";
        return ;
    }

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../env");
    $env->load();

    $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($_ENV["ACCESSTOKEN"]);
    $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $_ENV["SECRET"]]);

    // TODO: PHP8版にアップデートする
    // 現状(202305)はhttps://packagist.org/packages/linecorp/line-bot-sdk の7.6.1

    $areaBuilders = [
        new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder(
            new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(0, 0, 800, 810), 
            new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Travel", "https://liff.line.me/1660850624-blZONQVg")
        ),
        new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder(
            new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(800, 0, 800, 810), 
            new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("none", "https://kamata-portfolio.tk")
        ),
        new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder(
            new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(1600, 0, 800, 810), 
            new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("none", "https://kamata-portfolio.tk")
        ),
        new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder(
            new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(0, 810, 800, 810), 
            new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("Credit", "https://liff.line.me/1660850624-blZONQVg")
        ),
        new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder(
            new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(800, 810, 800, 810), 
            new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("none", "https://kamata-portfolio.tk")
        ),
        new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder(
            new \LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder(1600, 810, 800, 810), 
            new \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder("none", "https://kamata-portfolio.tk")
        ),
    ];

    $richMenuBuilder = new \LINE\LINEBot\RichMenuBuilder(
        new \LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder(1620, 2400),
         true, 
         "リマインダー用リッチメニュー",
        "メニューを開く", 
        $areaBuilders
    );


    $response = $bot->createRichMenu($richMenuBuilder);
    $res_json = $response->getJSONDecodedBody();
    var_dump($res_json);
    if(!$response->isSucceeded()){
        echo "Create Faild";
        var_dump($response->getHTTPStatus());
        return ;
    }
    
    $response = $bot->uploadRichMenuImage($res_json->richMenuId, "./image.png", "image/png");

    if(!$response->isSucceeded()){
        echo "Image upload faild";
        var_dump($response->getHTTPStatus());
        return ;
    }
    
?>