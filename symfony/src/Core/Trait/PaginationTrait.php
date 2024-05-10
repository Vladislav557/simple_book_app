<?php

namespace App\Core\Trait;

use Symfony\Component\Validator\Constraints\Type;

trait PaginationTrait
{
    #[Type(type: 'integer')]
    private int $skip = 0;
    #[Type(type: 'integer')]
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