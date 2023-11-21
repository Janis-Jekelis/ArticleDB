<?php
declare(strict_types=1);
namespace App\Models;
class Article
{
    private string $title;
    private string $description;
    private ?int $id;

    public function __construct(string $title,string $description,?int $id=null)
    {
        $this->title=$title;
        $this->description=$description;
        $this->id=$id;
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

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
