<?php

namespace App\Model\Response;

class BookListResponse
{
    private int $total;
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