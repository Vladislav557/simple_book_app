<?php

namespace App\Model\Response;

use OpenApi\Attributes as OA;

class HealthCheckResponse
{
    private string $status;

    #[OA\Property(type: 'string')]
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}