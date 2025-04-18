<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $done = ORM::for_table("wish_list")
    ->select("*")
    ->where('id', $_POST["id"])
    ->find_one();
    $done->set_expr("is_delete", 1);
    $done->save();

    $response = ["result" => 1];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>