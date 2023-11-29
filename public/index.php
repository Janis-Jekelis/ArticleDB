<?php
declare(strict_types=1);
require_once __DIR__ . "/../vendor/autoload.php";

use App\RedirectResponse;
use App\Repositories\Test;
use App\ViewResponse;
use Doctrine\DBAL\DriverManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Repositories\MySqlArticleDatabase;
use App\Repositories\Repository;

session_start();
$loader = new FilesystemLoader(__DIR__ . "/../public/Views");
$twig = new Environment($loader);
if (isset($_SESSION["flush"])) $twig->addGlobal("flush", ["success" => $_SESSION["flush"]]);

$container = new \DI\Container();
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions([
    Repository::class => DI\create(MySqlArticleDatabase::class),
    \App\Response::class => DI\create(ViewResponse::class)

]);
$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/article/create', ["App\Controllers\ArticleController", "create"]);
    $r->addRoute('POST', '/article/create', ["App\Controllers\ArticleController", "store"]);

    $r->addRoute('GET', "/", ["App\Controllers\ArticleController", "index"]);

    $r->addRoute('GET', '/article/{id:\d+}', ["App\Controllers\ArticleController", "show"]);
    $r->addRoute('POST', '/article/{id:\d+}/delete', ["App\Controllers\ArticleController", "delete"]);
    $r->addRoute('GET', '/article/{id:\d+}/edit', ["App\Controllers\ArticleController", "edit"]);
    $r->addRoute('POST', '/article/{id:\d+}/edit', ["App\Controllers\ArticleController", "update"]);

    $r->addRoute('GET', "/test", ["App\Controllers\ArticleController", "index"]);

});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$class, $method] = $handler;
        $intVars = [];
        foreach ($vars as $key => $value) {
            $intVars[$key] = (int)$value;
        }
        if (strpos($_SERVER["REQUEST_URI"], "test")) {
            $container->set(Repository::class, DI\create(Test::class));
            $container->set(Repository::class, function () {
                return new Test(DriverManager::getConnection(require __DIR__ . "/../dbalConfig.php"));
            });
        }

        $response = ($container->get($class))->{$method}(...array_values($intVars));

        switch (true) {
            case $response instanceof ViewResponse:
                echo $twig->render($response->getViewName() . ".twig", $response->getData());
                break;
            case $response instanceof RedirectResponse:
                header("Location: {$response->getLocation()}");
        }
        if ($method == "show" && isset($_SESSION["flush"])) unset($_SESSION["flush"]);
        break;
}