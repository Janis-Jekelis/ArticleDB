<?php
declare(strict_types=1);
namespace App\Controllers;
use PDO;
class ArticleController
{
    public function index()
    {

    }
    public function create($title, $description)
    {
        $dsn= "mysql:host=localhost;port=3306; dbname=ArticleDB; user=root; password=pass; charset=utf8mb4;";
        $pdo=new PDO($dsn);
        var_dump($title, $description);
        $sql = "INSERT INTO Articles (Title, Description)
        VALUES ('$title', '$description')";

        $pdo->query($sql);

        $statement=$pdo->prepare("select * from Articles");
        $statement->execute();
        $posts=$statement->fetchAll(PDO::FETCH_ASSOC);
        var_dump($posts);

    }
}
