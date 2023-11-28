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

    public function save(Article $article): void
    {
        if ($article->getId() != null) {
            $this->update($article);
            return;
        }
        $this->database->createQueryBuilder()
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
                'picture' => $article->savePicture()
            ])
            ->executeQuery();
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

    public function connect(): Connection
    {
        return $this->database;
    }

    private function update(Article $article): void
    {
        $this->database->createQueryBuilder()
            ->update('Articles')
            ->set('Title', ':title')
            ->set('Description', ':description')
            ->set('Edited_at', ':edited')
            ->set('Picture', ':picture')
            ->where('id = :id')
            ->setParameters([
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'id' => $article->getId(),
                'picture' => $article->savePicture(),
                'edited' => $article->getEditedAt()
            ])
            ->executeQuery();

    }

    private function insert(): void
    {

    }
}

