<?php

namespace App\Model\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class BookDTO
{
    #[Type(type: 'integer')]
    #[NotBlank]
    private int $id;
    #[Type(type: 'string')]
    #[NotBlank]
    private string $title;
    #[Type(type: 'string')]
    #[NotBlank]
    private string $author;
    #[Type(type: 'integer')]
    #[NotBlank]
    private int $dateTime;

    public function __construct(int $id, string $title, string $author, int $dateTime)
    {
        $this->id = $id;
        $this->title = $title;
        $this->author = $author;
        $this->dateTime = $dateTime;
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

    public function getDateTime(): int
    {
        return $this->dateTime;
    }
}