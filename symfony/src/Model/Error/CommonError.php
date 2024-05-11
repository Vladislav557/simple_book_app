<?php

namespace App\Model\Error;

use Symfony\Component\Validator\Constraints\NotBlank;
use OpenApi\Attributes as OA;

class CommonError
{
    #[OA\Property(type: 'string')]
    #[NotBlank]
    private string $error;

    public function __construct(string $error)
    {
        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }
}