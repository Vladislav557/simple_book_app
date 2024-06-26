<?php

namespace App\Service;

use App\Entity\Book;
use App\Exception\BookAlreadyExistsException;
use App\Exception\BookNotFoundException;
use App\Interface\BookServiceInterface;
use App\Model\DTO\BookDTO;
use App\Model\Request\BookCreateRequest;
use App\Model\Request\BookEditRequest;
use App\Model\Request\BookListRequest;
use App\Model\Response\BookListResponse;
use App\Repository\BookRepository;

readonly class BookService implements BookServiceInterface
{
    public function __construct(
        private BookRepository $bookRepository
    )
    {
    }

    /**
     * @throws BookNotFoundException
     */
    public function get(int $id): BookDTO
    {
        $book = $this->bookRepository->find($id);
        if (is_null($book)) {
            throw new BookNotFoundException();
        }
        return $this->mapToModel($book);
    }

    /**
     * @throws BookNotFoundException
     */
    public function delete(int $id): void
    {
        $book = $this->bookRepository->find($id);
        if (is_null($book)) {
            throw new BookNotFoundException();
        }
        $this->bookRepository->remove($book, true);
    }

    public function list(BookListRequest $request): BookListResponse
    {
        $books = $this
            ->bookRepository
            ->findBy(
                criteria: [],
                orderBy: [$request->getSortField() => $request->getSortDirection()],
                limit: $request->getLimit(),
                offset: $request->getSkip()
            );
        $models = array_map(fn (Book $b) => $this->mapToModel($b), $books);
        $count = $this->bookRepository->count();
        return new BookListResponse($count, $models);
    }

    /**
     * @throws BookAlreadyExistsException
     */
    public function create(BookCreateRequest $request): BookDTO
    {
        if ($this->bookRepository->isExists($request->getTitle(), $request->getAuthor())) {
            throw new BookAlreadyExistsException();
        }
        $book = new Book();
        $book
            ->setTitle($request->getTitle())
            ->setAuthor($request->getAuthor())
            ->setPublishedAt($request->getPublishedAt());
        $this->bookRepository->save($book, true);
        return $this->mapToModel($book);
    }

    /**
     * @throws BookAlreadyExistsException|BookNotFoundException
     */
    public function edit(BookEditRequest $request, int $id): BookDTO
    {
        $book = $this->bookRepository->find($id);
        if (is_null($book)) {
            throw new BookNotFoundException();
        }
        if (!$this->bookRepository->canUpdate($id, $request->getTitle(), $request->getAuthor())) {
            throw new BookAlreadyExistsException();
        }
        $book
            ->setTitle($request->getTitle())
            ->setAuthor($request->getAuthor())
            ->setPublishedAt($request->getPublishedAt());
        $this->bookRepository->save($book, true);
        return $this->mapToModel($book);
    }

    private function mapToModel(Book $book): BookDTO
    {
        return new BookDTO($book->getId(), $book->getTitle(), $book->getAuthor(), $book->getPublishedAt());
    }
}