<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $tasks = ORM::for_table("dev_task_list")
    ->select("todo_title", "title")
    ->select("todo_detail", "detail")
    ->where_raw('is_completed = 0 AND is_deleted = 0')
    ->find_array();

    $response = ["result" => 1, "lists" => $tasks];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>