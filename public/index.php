<?php
declare(strict_types=1);
require_once __DIR__."/../vendor/autoload.php";
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . "/../public/Views");
$twig = new Environment($loader);
echo $twig->render("create.twig", /*(new MainController())->index()*/);
/*
$dsn= "mysql:host=localhost;port=3306; dbname=ArticleDB; user=root; password=pass; charset=utf8mb4;";
$pdo=new PDO($dsn);

$sql = "DELETE FROM Articles
  WHERE id = 3";



$pdo->query($sql);
$statement=$pdo->prepare("select * from Articles");

$statement->execute();
$posts=$statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($posts as $id){
    echo $id["id"];
}

var_dump($posts);
*/


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('POST', '/article/create', 'get_all_users_handler');
    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');

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

            (new \App\Controllers\ArticleController())->create($_POST["title"],$_POST["content"]);

        // ... call $handler with $vars
        break;
}