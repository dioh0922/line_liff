<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $task = ORM::for_table("wish_list")->create();
    $task->item_name = $_POST["item"];
    $task->wish_detail = $_POST["detail"];
    $task->where("is_delete", 0);
    $task->save();

    $lists = ORM::for_table("wish_list")
    ->select("id", "id")
    ->select("date", "date")
    ->select("item_name", "item")
    ->select("wish_detail", "detail")
    ->where("is_delete", 0)
    ->order_by_desc("date")
    ->find_array();

    $response = ["result" => 1];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>