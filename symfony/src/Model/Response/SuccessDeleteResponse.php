<?php

namespace App\Model\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints\NotBlank;


class SuccessDeleteResponse
{
    #[OA\Property(type: 'boolean')]
    #[NotBlank]
    private bool $success;

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }
}