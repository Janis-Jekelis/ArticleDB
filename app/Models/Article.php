<?php
declare(strict_types=1);
namespace App\Models;
class Article
{
    private int $id;
    private string $title;
    private string $description;

    public function __construct(int $id,string $title,string $description)
    {
        $this->id=$id;
        $this->title=$title;
        $this->description=$description;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
