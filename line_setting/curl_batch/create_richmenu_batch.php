<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");
    require_once("./RichMenuSetting.php");

    if(!gethostname() == "localhost"){
        echo "localhost only";
        return ;
    }

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();


    $disp = [];

    $setting = new RichMenuSetting();
    $richMenuId = $setting->createMenu();

    $disp[] = $richMenuId;
    
    $disp[] = "putImg:".$setting->putMenuImg($richMenuId);
    
    $disp[] = "setDefault:".$setting->setDefaultMenu($richMenuId);

    $disp[] = "getDefault:".$setting->getDefaultMenu();

    echo implode("<br>", $disp);

?>