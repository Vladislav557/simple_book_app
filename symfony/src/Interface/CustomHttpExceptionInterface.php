<?php

namespace App\Interface;

interface CustomHttpExceptionInterface
{
    public function getHttpExceptionCode(): int;
    public function getHttpExceptionMessage(): string;
}