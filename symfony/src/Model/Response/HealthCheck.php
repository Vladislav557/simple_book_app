<?php

namespace App\Model\Response;

class HealthCheck
{
    private string $status;

    /**
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}