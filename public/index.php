<?php
declare(strict_types=1);
require_once __DIR__ . "/../vendor/autoload.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Controllers\ArticleController;
$loader = new FilesystemLoader(__DIR__ . "/../public/Views");
$twig = new Environment($loader);

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/article/create', ["App\Controllers\ArticleController", "create"]);
    $r->addRoute('POST', '/article/create', ["App\Controllers\ArticleController", "create"]);
    $r->addRoute('GET', "/", ["App\Controllers\ArticleController", "index"]);
    $r->addRoute('POST', "/", ["App\Controllers\ArticleController", "index"]);
    $r->addRoute('GET', '/article/{id:\d+}', ["App\Controllers\ArticleController", "show"]);
    $r->addRoute('POST', '/article/{id:\d+}', ["App\Controllers\ArticleController", "show"]);

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
        [$class, $method] = [$handler[0], $handler[1]];

        if ($method == "show") {
            echo $twig->render($method . ".twig", (new $class)->$method((int)($vars["id"])));

        }
        if ($method == "index") {
            echo $twig->render($method . ".twig", (new $class)->$method());
            if($httpMethod == "POST") {
                (new $class)->delete((int)$_POST["delete"]);
                header("Refresh: 0");
            }
        }

        if ($method == "create") {
            echo $twig->render($method . ".twig");
            if ($httpMethod == "POST") (new $class)->$method($_POST["title"], $_POST["content"]);
        }
        break;
}