<?php

namespace App\Exception;

use App\Interface\CustomHttpExceptionInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BookAlreadyExistsException extends Exception implements CustomHttpExceptionInterface
{
    private const HTTP_CODE_EXCEPTION = Response::HTTP_BAD_REQUEST;
    private const HTTP_MESSAGE_EXCEPTION = 'book already exists';
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpExceptionCode(): int
    {
        return self::HTTP_CODE_EXCEPTION;
    }

    public function getHttpExceptionMessage(): string
    {
        return self::HTTP_MESSAGE_EXCEPTION;
    }
}