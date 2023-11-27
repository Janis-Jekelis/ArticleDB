<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Collections\ArticleCollection;
use App\Models\Article;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\DriverManager;
use mysql_xdevapi\Exception;

class MySqlArticleDatabase implements Repository

{
    private Connection $database;

    public function __construct()
    {
        try {
            $this->database = DriverManager::getConnection(require __DIR__ . "/../../dbalConfig.php");
        } catch (ConnectionException $e) {
            throw new Exception ("Cant connect to database");
        }

    }

    public function getAll(): ArticleCollection
    {
        $articleCollection = new ArticleCollection();
        $articles = $this->database->createQueryBuilder()
            ->select('*')
            ->from('Articles')
            ->executeQuery()
            ->fetchAllAssociative();
        foreach ($articles as $article) {
            $articleCollection->add($this->buildArticle($article));
        }
        return $articleCollection;
    }

    public function getById(int $id): Article
    {
        $article = $this->database->createQueryBuilder()
            ->select('*')
            ->from('Articles')
            ->where('id=:id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();
        return $this->buildArticle($article);
    }

    public function delete(Article $article)
    {
        $this->database->createQueryBuilder()
            ->delete('Articles')
            ->where('id = :id')
            ->setParameter('id', $article->getId())
            ->executeQuery();
    }

    private function buildArticle(array $data): Article
    {
        return new Article(
            $data['Title'],
            $data['Description'],
            $data['Created_at'],
            (int)$data['id'],
            $data['Picture'],
            $data['Edited_at']
        );
    }
}

