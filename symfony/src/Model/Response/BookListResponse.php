<?php

namespace App\Model\Response;

use App\Model\DTO\BookDTO;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Attributes as OA;

class BookListResponse
{
    #[OA\Property(type: 'integer')]
    #[NotBlank]
    private int $total;
    #[OA\Property(type: 'array', items: new OA\Items(ref: new Model(type: BookDTO::class)))]
    #[NotBlank]
    private array $books;

    public function __construct(int $total, array $books)
    {
        $this->total = $total;
        $this->books = $books;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getBooks(): array
    {
        return $this->books;
    }
}