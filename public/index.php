<?php
require_once __DIR__ . "/../vendor/autoload.php";

use App\Di\Container;
use App\ORM\Database;
use App\ORM\Model;

$container = new Container();



$databaseFile = __DIR__ . '/database.sqlite';
$database = new Database("sqlite:" . $databaseFile, "", "", [
    
]);
Model::setDatabase($database);

