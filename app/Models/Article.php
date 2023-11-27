<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;

class Article
{
    private string $title;
    private string $description;
    private Carbon $createdAt;
    private string $picture;
    private ?int $id;
    private ?Carbon $editedAt;

    public function __construct(
        string  $title,
        string  $description,
        ?string $createdAt,
        ?int    $id = null,
        ?string $picture = null,
        ?string $editedAt = null
    )
    {
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt == null ? Carbon::now() : Carbon::parse($createdAt);
        $this->picture = $picture == null ? "https://random.imagecdn.app/300/300" :
            "data:image/jpeg;base64," . base64_encode($picture);
        $this->id = $id;
        $this->editedAt = $editedAt == null ? null : Carbon::parse($editedAt);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getPicture(): string
    {
        return $this->picture;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEditedAt(): ?Carbon
    {
        return $this->editedAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
