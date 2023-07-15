<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");
    require_once("./RichMenuSetting.php");

    if(php_sapi_name() != "cli"){
        echo "localhost only";
        return ;
    }

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();


    $disp = [];

    $setting = new RichMenuSetting();
    $json = json_decode($setting->getAllMenu());
    foreach($json as $obj){
        $disp[] = $obj[0]->richMenuId;
        $disp[] = $setting->deleteMenu($obj[0]->richMenuId);
    }

    echo implode("\n", $disp);

?>