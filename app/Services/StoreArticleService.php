<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Repositories\MySqlArticleDatabase;
use App\Repositories\Repository;

class StoreArticleService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = new MySqlArticleDatabase();
    }

    public function handle(
        string $title,
        string $description,
        string $picture
    ): int
    {
        $article = new Article(
            $title,
            $description,
            null,
            null,
            $picture
        );
        $this->repository->save($article);
        return (int)$this->repository->connect()->lastInsertId();
    }
}