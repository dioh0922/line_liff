<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $task = ORM::for_table("tasks")->create();
    $task->summary = $_POST["title"];
    $task->detail = $_POST["todo"];
    $task->set_expr("created_at", "NOW()");
    $task->set_expr("updated_at", "NOW()");
    $task->save();

    $response = ["result" => 1];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>