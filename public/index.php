<?php
// $_SERVER['REQUEST_URI'] = '/users';
// $_SERVER['REQUEST_METHOD'] = 'GET';
require_once __DIR__ . "/../vendor/autoload.php";

use App\Domain\UserRepositoryInterface;
use App\Infrastructure\UserRepository;
use App\Presentation\Controllers\UserController;
use App\Presentation\ViewRenderer;
use Framework\Di\Container;
use Framework\ORM\Database;
use Framework\ORM\Model;
use Framework\Routing\Response;
use Framework\Routing\Router;
use Framework\Routing\TemplateRouteRegexCompilerFactory;
use Uri\UriException;


$container = new Container();
$container->singleton(UserRepositoryInterface::class, UserRepository::class);
$container->singleton(TemplateRouteRegexCompilerFactory::class, TemplateRouteRegexCompilerFactory::class);
$container->singleton(ViewRenderer::class, ViewRenderer::class, new ViewRenderer(__DIR__ . "/../src/Presentation/Views"));

$router = new Router($container);
$router->controller(UserController::class);

$databaseFile = __DIR__ . '/../database/database.sqlite';
$database = new Database("sqlite:" . $databaseFile, "", "", [
    
]);
Model::setDatabase($database);
try{
    $response = $router->dispatch();
}
catch(UriException $e){
    $response = Response::text("Error occured: " . $e->getMessage(), 404);
}
catch(\RuntimeException $e){
    $response = Response::text("Error occured: " . $e->getMessage(), 400);
}
$response->send();  

