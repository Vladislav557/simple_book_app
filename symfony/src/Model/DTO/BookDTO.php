<?php

namespace App\Model\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Attributes as OA;

class BookDTO
{
    #[OA\Property(type: 'integer')]
    #[NotBlank]
    private int $id;
    #[OA\Property(type: 'string')]
    #[NotBlank]
    private string $title;
    #[OA\Property(type: 'string')]
    #[NotBlank]
    private string $author;
    #[OA\Property(type: 'integer')]
    #[NotBlank]
    private int $publishedAt;

    public function __construct(int $id, string $title, string $author, int $publishedAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->publishedAt = $publishedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getPublishedAt(): int
    {
        return $this->publishedAt;
    }
}