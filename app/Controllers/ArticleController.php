<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Article;
use App\PdoConnect;
use PDO;
class ArticleController
{
    public function index():array
    {
        $articles=[];
        $pdo = PdoConnect::pdoConnect();
        $statement=$pdo->prepare("select * from Articles");
        $statement->execute();
        $posts=$statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($posts as $article){
            $articles[]=new Article((int)$article["id"],$article["Title"],$article["Description"]);
        }
        return ["articles"=>$articles];

    }
    public function create($title, $description):void
    {
        if($title!=null && $description!=null) {
            $pdo = PdoConnect::pdoConnect();
            $sql = "INSERT INTO Articles (Title, Description)
                VALUES ('$title', '$description')";
            $pdo->query($sql);
        } else{
            echo "Article must consist of title and description";
        }
    }
    public function show(int $id):array
    {
        $articles=[];
        $pdo = PdoConnect::pdoConnect();
        $statement=$pdo->prepare("select * from Articles WHERE id = {$id}");
        $statement->execute();
        $posts=$statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($posts as $article){
            $articles[]=new Article((int)$article["id"],$article["Title"],$article["Description"]);
        }
        return ["articles"=>$articles];
    }
    public function delete(int $id)
    {
        $pdo = PdoConnect::pdoConnect();
        $sql = "DELETE FROM Articles WHERE id = {$id}";
        $statement=$pdo->prepare($sql);
        $statement->execute();
    }
}
