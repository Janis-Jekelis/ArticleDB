<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Models\Article;
use Doctrine\DBAL\DriverManager;
class ArticleController
{
    private object $conn;
    public function __construct()
    {
        $this->conn = DriverManager::getConnection(require __DIR__."/../../dbalConfig.php");
    }

    public function index():array
    {
        $articles=[];
        $statement=$this->conn->prepare("select * from Articles");
        $result=$statement->executeQuery();
        $posts=$result->fetchAllAssociative();
        foreach ($posts as $article){
            $articles[]=new Article($article["Title"],$article["Description"],(int)$article["id"]);
        }
        return ["articles"=>$articles];

    }
    public function create(object $twig, string $method ):void
    {
        echo $twig->render($method . ".twig");
    }
    public function store($title, $description):void
    {

         if($title!=null && $description!=null) {
             $article=new Article($title,$description);

            $this->conn->insert(
                'Articles',
                [
                    'Title'=>$article->getTitle(),
                    'Description'=>$article->getDescription()
                ]
            );
            $article->setId((int)$this->conn->lastInsertId());
            $_SESSION["flush"]="Article created";
            header("Location: http://{$_SERVER["HTTP_HOST"]}/article/{$article->getId()}");

        }
    }
    public function show(int $articleId):array
    {
        $articles=[];
        $statement=$this->conn->prepare("select * from Articles WHERE id = ?");
        $statement->bindValue(1, $articleId);
        $result=$statement->executeQuery();
        $posts=$result->fetchAllAssociative();
        foreach ($posts as $article){
            $articles[]=new Article($article["Title"],$article["Description"],(int)$article["id"]);
        }
        return ["articles"=>$articles];
    }
    public function delete(int $id)
    {
        $this->conn->delete('Articles',['id'=>$id]);
    }
}
