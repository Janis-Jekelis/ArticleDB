<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Collections\ArticleCollection;
use App\Models\Article;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Test implements Repository
{
    private Connection $database;

    public function __construct(Connection $connection)
    {
        $this->database = $connection;
    }

    public function getAll(): ArticleCollection
    {
        $title = $this->database->createQueryBuilder()
            ->select('Title')
            ->from('Articles')
            ->where('id=:id')
            ->setParameter('id', 193)
            ->executeQuery()
            ->fetchOne();
        $article = new Article(
            $title,
            " "
        );
        $artcol = new ArticleCollection(
            [
                new Article("Title", "Description",),
                new Article("Title2", "Description",),
                new Article("Title3", "Description",)
            ]
        );
        $artcol->add($article);
        return $artcol;
    }
}