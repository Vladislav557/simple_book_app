<?php

namespace App\Entity;

use App\Core\Trait\TimestampTrait;
use App\Repository\BookRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $publishedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?int
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(int $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function updateDate(): void
    {
        $this->updatedAt = new DateTime();
    }
}
