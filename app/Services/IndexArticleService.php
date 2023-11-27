<?php
declare(strict_types=1);

namespace App\Services;

use App\Collections\ArticleCollection;
use App\Repositories\MySqlArticleDatabase;
use App\Repositories\Repository;

class IndexArticleService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = new MySqlArticleDatabase();
    }

    public function handle(): ArticleCollection
    {
        return $this->repository->getAll();
    }
}
