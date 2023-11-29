<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Repositories\MySqlArticleDatabase;
use App\Repositories\Repository;

class ShowArticleService
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id): Article
    {
        return $this->repository->getById($id);
    }

}