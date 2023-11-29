<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MySqlArticleDatabase;
use App\Repositories\Repository;

class DeleteArticleService
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(int $id)
    {
        $article = $this->repository->getById($id);
        $this->repository->delete($article);
    }
}