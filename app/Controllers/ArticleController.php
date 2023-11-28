<?php
declare(strict_types=1);

namespace App\Controllers;

use App\RedirectResponse;
use App\Response;
use App\Services\DeleteArticleService;
use App\Services\IndexArticleService;
use App\Services\ShowArticleService;
use App\Services\StoreArticleService;
use App\Services\UpdateArticleService;
use App\ViewResponse;

class ArticleController
{

    public function index(): Response
    {
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
        $article = (new StoreArticleService())->handle(
            $_POST['title'],
            $_POST['content'],
            $_FILES['image']['tmp_name']
        );
        $_SESSION["flush"] = "Article created";
        return new RedirectResponse("/article/$article");
    }

    public function edit(int $id): Response
    {
        $article = (new ShowArticleService())->handle($id);
        return (new ViewResponse("edit", ["article" => $article]));
    }

    public function update(int $id): Response
    {

        (new UpdateArticleService())->handle(
            $id,
            $_POST['title'],
            $_POST['content'],
            $_FILES['image']['tmp_name']
        );
        return new RedirectResponse("/");

    }

    public function delete(int $id): RedirectResponse
    {
        (new DeleteArticleService())->handle($id);
        return new RedirectResponse("/");
    }
}
