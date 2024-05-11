<?php

namespace App\Model\Request;

use App\Core\Trait\PaginationTrait;
use Symfony\Component\Validator\Constraints\Choice;
use OpenApi\Attributes as OA;

class BookListRequest
{
    use PaginationTrait;

    #[OA\Property(type: 'string')]
    #[Choice(choices: ['title', 'author', 'publishedAt'])]
    private string $sortField = 'publishedAt';
    #[OA\Property(type: 'string')]
    #[Choice(choices: ['ASC', 'DESC'])]
    private string $sortDirection = 'DESC';

    public function getSortField(): string
    {
        return $this->sortField;
    }

    public function setSortField(string $sortField): self
    {
        $this->sortField = $sortField;

        return $this;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    public function setSortDirection(string $sortDirection): self
    {
        $this->sortDirection = $sortDirection;

        return $this;
    }
}