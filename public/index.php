<?php
declare(strict_types=1);
require_once __DIR__."/../vendor/autoload.php";


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