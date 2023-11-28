<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\MySqlArticleDatabase;
use App\Repositories\Repository;

class UpdateArticleService
{
    private Repository $repository;

    public function __construct()
    {
        $this->repository = new MySqlArticleDatabase();
    }

    public function handle(
        int     $id,
        string  $title,
        string  $description,
        ?string $picture = null
    )
    {

        $article = $this->repository->getById($id);
        $article->update([
            'Title' => $title,
            'Description' => $description,
            'Picture' => $picture
        ]);
        $this->repository->save($article);


    }
}
