<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\RedirectResponse;
use App\Response;
use App\ViewResponse;
use Doctrine\DBAL\DriverManager;

class ArticleController
{
    private object $conn;

    public function __construct()
    {
        $this->conn = DriverManager::getConnection(require __DIR__ . "/../../dbalConfig.php");
    }

    public function index(): Response
    {
        $articles = [];
        $statement = $this->conn->prepare("select * from Articles");
        $result = $statement->executeQuery();
        $posts = $result->fetchAllAssociative();
        foreach ($posts as $article) {
            $articles[] = new Article($article["Title"], $article["Description"], (int)$article["id"]);
        }
        return new ViewResponse("index", ["articles" => $articles]);

    }

    public function create(): Response
    {
        return new ViewResponse("create");
    }

    public function store(): Response
    {
        $article = new Article($_POST['title'], $_POST['content']);
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder
            ->insert('Articles')
            ->values([
                'Title' => ':title',
                'Description' => ':description'
            ])
            ->setParameters([
                'title' => $article->getTitle(),
                'description' => $article->getDescription()
            ])
            ->executeQuery();
        $article->setId((int)$this->conn->lastInsertId());
        $_SESSION["flush"] = "Article created";
        return new RedirectResponse("/article/{$article->getId()}");
    }

    public function show(int $articleId ): Response
    {
        $statement = $this->conn->prepare("select * from Articles WHERE id = :id");
        $statement->bindValue('id', $articleId);
        $result = $statement->executeQuery();
        $posts = $result->fetchAssociative();
        $article = new Article($posts["Title"], $posts["Description"], (int)$posts["id"]);
                return (new ViewResponse("show", ["article" => $article]));

    }
    public function edit(int $id):Response
    {
        $statement = $this->conn->prepare("select * from Articles WHERE id = :id");
        $statement->bindValue('id', $id);
        $result = $statement->executeQuery();
        $posts = $result->fetchAssociative();
        $article = new Article($posts["Title"], $posts["Description"], (int)$posts["id"]);
        return (new ViewResponse("edit", ["article" => $article]));
    }

    public function delete(int $id):RedirectResponse
    {
        $this->conn->delete('Articles', ['id' =>$id]);
        return new RedirectResponse("/");
    }
}
