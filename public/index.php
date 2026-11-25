<?php
$_SERVER['REQUEST_URI'] = '/users';
$_SERVER['REQUEST_METHOD'] = 'GET';
require_once __DIR__ . "/../vendor/autoload.php";

use App\Domain\UserRepositoryInterface;
use App\Infrastructure\UserRepository;
use App\Presentation\Controllers\UserController;
use Framework\Di\Container;
use Framework\ORM\Database;
use Framework\ORM\Model;
use Framework\Routing\Request;
use Framework\Routing\Router;
use Framework\Routing\TemplateRouteRegexCompilerFactory;

$container = new Container();
$container->singleton(UserRepositoryInterface::class, UserRepository::class);
$container->singleton(TemplateRouteRegexCompilerFactory::class, TemplateRouteRegexCompilerFactory::class);

$router = new Router($container);
$router->controller(UserController::class);

$databaseFile = __DIR__ . '/../database/database.sqlite';
$database = new Database("sqlite:" . $databaseFile, "", "", [
    
]);
Model::setDatabase($database);
try{
    $response = $router->dispatch();
    $response->send();  
}catch(\Exception $e){
    echo "Error occured: " . print($e->getMessage()) . PHP_EOL;
    echo (new Request())->path();   
}

