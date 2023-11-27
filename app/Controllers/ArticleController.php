<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Article;
use App\RedirectResponse;
use App\Response;
use App\Services\IndexArticleService;
use App\Services\ShowArticleService;
use App\ViewResponse;
use Carbon\Carbon;
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
        /*
        $articles = [];
        $statement = $this->conn->prepare("select * from Articles");
        $result = $statement->executeQuery();
        $posts = $result->fetchAllAssociative();

        foreach ($posts as $article) {
            $articles[] = new Article(
                $article["Title"],
                $article["Description"],
                Carbon::parse($article["Created_at"]),
                (int)$article["id"],
                base64_encode($article["Picture"]),
                $article["Edited_at"]
            );
        }*/
        $articles = (new IndexArticleService())->handle();
        return new ViewResponse("index", ["articles" => $articles]);

    }

    public function show(int $id): Response
    {
        $article = (new ShowArticleService())->handle($id);
        return (new ViewResponse("show", ["article" => $article]));
    }

    public function create(): Response
    {
        return new ViewResponse("create");
    }

    public function store(): Response
    {
        $article = new Article(
            $_POST['title'],
            $_POST['content'],
            (string)(Carbon::now()),


        );
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder
            ->insert('Articles')
            ->values([
                'Title' => ':title',
                'Description' => ':description',
                'Created_at' => ':created',
                'Picture' => ':picture'
            ])
            ->setParameters([
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'created' => $article->getCreatedAt(),
                'picture' => file_get_contents($_FILES['image']['tmp_name'])
            ])
            ->executeQuery();
        $article->setId((int)$this->conn->lastInsertId());
        $_SESSION["flush"] = "Article created";

        return new RedirectResponse("/article/{$article->getId()}");
    }

    public function edit($articleId): Response
    {
        $statement = $this->conn->prepare("select * from Articles WHERE id = :id");
        $statement->bindValue('id', $articleId);
        $result = $statement->executeQuery();
        $posts = $result->fetchAssociative();
        $article = new Article(
            $posts["Title"],
            $posts["Description"],
            $posts["Created_at"],
            (int)$posts["id"]
        );
        return (new ViewResponse("edit", ["article" => $article]));
    }

    public function update(int $id): Response
    {
        $queryBuilder = $this->conn->createQueryBuilder();
        $queryBuilder
            ->update('Articles')
            ->set('Title', ':title')
            ->set('Description', ':description')
            ->set('Edited_at', ':edited')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->setParameters([
                'title' => $_POST['title'],
                'description' => $_POST['content'],
                'id' => $id,
                'edited' => Carbon::now()
            ])
            ->executeQuery();

        return new RedirectResponse("/");

    }

    public function delete(int $id): RedirectResponse
    {
        $this->conn->delete('Articles', ['id' => $id]);
        return new RedirectResponse("/");
    }
}
