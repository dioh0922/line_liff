<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    ORM::configure('id_column_overrides', array(
      'dev_task_list' => 'todo_title',
    ));    

    $done = ORM::for_table("dev_task_list")
    ->select("*")
    ->where_raw('todo_title = ?', $_POST["title"])
    ->find_one();
    $done->set_expr("completed_date", "NOW()");
    $done->set_expr("is_completed", "1");
    $done->save();

    $response = ["result" => 1];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>