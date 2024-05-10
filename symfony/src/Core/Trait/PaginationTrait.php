<?php

namespace App\Core\Trait;

use OpenApi\Attributes as OA;

trait PaginationTrait
{
    #[OA\Property(type: 'integer')]
    private int $skip = 0;
    #[OA\Property(type: 'integer')]
    private int $total = 20;

    public function getSkip(): int
    {
        return $this->skip;
    }

    public function setSkip(int $skip): self
    {
        $this->skip = $skip;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
}