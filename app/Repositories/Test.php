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

    public function __construct()
    {
        $this->database = DriverManager::getConnection(require __DIR__ . "/../../dbalConfig.php");
    }

    public function getAll(): ArticleCollection
    {
        return new ArticleCollection(
            [
                new Article("Title", "Description",),
                new Article("Title2", "Description",),
                new Article("Title3", "Description",)
            ]
        );
    }
}