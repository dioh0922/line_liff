<?php
//TODO: FW化/REST化する

require_once(dirname(__FILE__)."/../../vendor/autoload.php");

$env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../../../env");
$env->load();
ORM::configure("mysql:host=".$_ENV["DB_HOST"].";charset=utf8;dbname=".$_ENV["DB_DB"]);
ORM::configure("username", $_ENV["DB_USER"]);
ORM::configure("password", $_ENV["DB_PASS"]);

$todo = ORM::for_table("travel_todo")
->select("*")
->where_raw('is_done = 0 AND is_deleted = 0')
->find_array();

$response = ["result" => 1, "lists" => $todo];
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>