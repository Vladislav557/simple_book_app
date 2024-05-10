<?php

namespace App\Interface;

use App\Model\DTO\BookDTO;
use App\Model\Request\BookCreateRequest;
use App\Model\Request\BookEditRequest;
use App\Model\Request\BookListRequest;
use App\Model\Response\BookListResponse;

interface BookServiceInterface
{
    public function get(int $id): BookDTO;
    public function delete(int $id): void;
    public function list(BookListRequest $request): BookListResponse;
    public function create(BookCreateRequest $request): BookDTO;
    public function edit(BookEditRequest $request, int $id): void;
}