<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");
    require_once("./RichMenuSetting.php");

    if($_SERVER["HTTP_HOST"] != "localhost"){
        echo "localhost only";
        return ;
    }

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();


    $disp = [];

    $setting = new RichMenuSetting();

    var_dump($setting->getEndpointURL());

    $default = json_decode($setting->getDefaultMenuObj());

    $detail = $setting->getMenuDetail($default->richMenuId);
    echo $detail;

?>