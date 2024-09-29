<?php
require(dirname(__FILE__)."/vendor/autoload.php");
$env = Dotenv\Dotenv::createImmutable(dirname(__FILE__)."/../env");
$env->load();

use \Phpmig\Adapter;
use \Pimple\Container;

$container = new Container();

$container["db"] = function () {
    $dbh = new PDO(sprintf('mysql:dbname=%s;host='.$_ENV["DB_HOST"], $_ENV["DB_DB"]), $_ENV["DB_USER"], $_ENV["DB_PASS"]);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
};

// replace this with a better Phpmig\Adapter\AdapterInterface
$container['phpmig.adapter'] = function ($c) {
    return new Adapter\PDO\Sql($c['db'], 'migrations');
};
$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

// You can also provide an array of migration files
// $container['phpmig.migrations'] = array_merge(
//     glob('migrations_1/*.php'),
//     glob('migrations_2/*.php')
// );

return $container;