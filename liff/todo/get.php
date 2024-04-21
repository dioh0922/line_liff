<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $tasks = ORM::for_table("tasks")
    ->select("summary", "title")
    ->select("detail", "detail")
    ->where("is_delete", 0)
    ->order_by_desc("created_at")
    ->find_array();

    $response = ["result" => 1, "lists" => $tasks];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>