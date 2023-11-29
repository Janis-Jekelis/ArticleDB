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
    private IndexArticleService $indexArticleService;
    private DeleteArticleService $deleteArticleService;
    private ShowArticleService $showArticleService;
    private StoreArticleService $storeArticleService;
    private UpdateArticleService $updateArticleService;

    public function __construct(
        DeleteArticleService $deleteArticleService,
        IndexArticleService  $indexArticleService,
        ShowArticleService   $showArticleService,
        StoreArticleService  $storeArticleService,
        UpdateArticleService $updateArticleService
    )
    {
        $this->indexArticleService = $indexArticleService;
        $this->deleteArticleService = $deleteArticleService;
        $this->showArticleService = $showArticleService;
        $this->storeArticleService = $storeArticleService;
        $this->updateArticleService = $updateArticleService;
    }

    public function index(): Response
    {
        $articles = $this->indexArticleService->handle();
        return new ViewResponse("index", ["articles" => $articles]);

    }

    public function show(int $id): Response
    {
        $article = $this->showArticleService->handle($id);
        return (new ViewResponse("show", ["article" => $article]));
    }

    public function create(): Response
    {
        return new ViewResponse("create");
    }

    public function store(): Response
    {
        $article = $this->storeArticleService->handle(
            $_POST['title'],
            $_POST['content'],
            $_FILES['image']['tmp_name']
        );
        $_SESSION["flush"] = "Article created";
        return new RedirectResponse("/article/$article");
    }

    public function edit(int $id): Response
    {
        $article = $this->showArticleService->handle($id);
        return (new ViewResponse("edit", ["article" => $article]));
    }

    public function update(int $id): Response
    {

        $this->updateArticleService->handle(
            $id,
            $_POST['title'],
            $_POST['content'],
            $_FILES['image']['tmp_name']
        );
        return new RedirectResponse("/");

    }

    public function delete(int $id): RedirectResponse
    {
        $this->deleteArticleService->handle($id);
        return new RedirectResponse("/");
    }
    public function getAllTest()
    {
        $articles = $this->indexArticleService->handle();
        return new ViewResponse("index", ["articles" => $articles]);
    }

}
