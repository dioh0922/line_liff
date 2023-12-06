<?php
    require_once(dirname(__FILE__)."/../../vendor/autoload.php");

    $env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
    $env->load();
    ORM::configure("mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"]."charset=utf8;dbname=".$_ENV["DB_DB"]);
    ORM::configure("username", $_ENV["DB_USER"]);
    ORM::configure("password", $_ENV["DB_PASS"]);

    $credit = ORM::for_table("travel_todo")->create();
    $credit->destination = $_POST["destination"];
    $credit->set_expr("created_date", "NOW()");
    $credit->save();

    $response = ["result" => 1];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>