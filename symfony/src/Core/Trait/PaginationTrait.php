<?php

namespace App\Core\Trait;

use OpenApi\Attributes as OA;

trait PaginationTrait
{
    #[OA\Property(type: 'integer')]
    private int $skip = 0;
    #[OA\Property(type: 'integer')]
    private int $limit = 20;

    public function getSkip(): int
    {
        return $this->skip;
    }

    public function setSkip(int $skip): self
    {
        $this->skip = $skip;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}