<?php

namespace App\Controller;

use App\Exception\ValidatorFieldException;
use App\Interface\BookServiceInterface;
use App\Interface\CustomHttpExceptionInterface;
use App\Model\DTO\BookDTO;
use App\Model\Error\CommonError;
use App\Model\Request\BookCreateRequest;
use App\Model\Request\BookEditRequest;
use App\Model\Request\BookListRequest;
use App\Model\Response\BookListResponse;
use App\Model\Response\SuccessDeleteResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'Book Controller', description: 'Manage book catalog')]
#[Route(path: '/app/api/v1/catalog')]
class BooksController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface   $serializer,
        private readonly ValidatorInterface    $validator,
        private readonly LoggerInterface       $logger,
        private readonly BookServiceInterface  $bookService
    )
    {
    }

    #[OA\Post(summary: 'Добавление книги')]
    #[OA\RequestBody(
        content: new Model(type: BookCreateRequest::class)
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'ok',
        content: new Model(type: BookDTO::class)
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'книга уже существует',
        content: new Model(type: CommonError::class)
    )]
    #[Route(path: '/book', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $data = [];
        $statusCode = Response::HTTP_BAD_REQUEST;
        try {
            $deserialized = $this
                ->serializer
                ->deserialize($request->getContent(), BookCreateRequest::class, 'json');
            $violations = $this->validator->validate($deserialized);
            if ($violations->count()) {
                throw new ValidatorFieldException($violations->get(0)->getPropertyPath());
            }
            $data = $this->bookService->create($deserialized);
            $statusCode = Response::HTTP_CREATED;
        } catch (CustomHttpExceptionInterface $throwable) {
            $data['error'] = $throwable->getHttpExceptionMessage();
            $statusCode = $throwable->getHttpExceptionCode();
        } catch (Throwable $throwable) {
            $data['error'] = $throwable->getMessage();
            $this->logger->error($throwable->getMessage());
        }
        return $this->json($data, $statusCode);
    }

    #[OA\Put(summary: 'Редактирование книги')]
    #[OA\RequestBody(
        content: new Model(type: BookEditRequest::class)
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'ok',
        content: new Model(type: BookDTO::class)
    )]
    #[OA\Response(
        response: Response::HTTP_BAD_REQUEST,
        description: 'книга уже существует',
        content: new Model(type: CommonError::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'книга не найдена',
        content: new Model(type: CommonError::class)
    )]
    #[Route(path: '/book/{id}', methods: ['PUT'])]
    public function edit(Request $request, int $id): Response
    {
        $data = [];
        $statusCode = Response::HTTP_BAD_REQUEST;
        try {
            $deserialized = $this
                ->serializer
                ->deserialize($request->getContent(), BookEditRequest::class, 'json');
            $violations = $this->validator->validate($deserialized);
            if ($violations->count()) {
                throw new ValidatorFieldException($violations->get(0)->getPropertyPath());
            }
            $data = $this->bookService->edit($deserialized, $id);
            $statusCode = Response::HTTP_OK;
        } catch (CustomHttpExceptionInterface $throwable) {
            $data['error'] = $throwable->getHttpExceptionMessage();
            $statusCode = $throwable->getHttpExceptionCode();
        } catch (Throwable $throwable) {
            $data['error'] = $throwable->getMessage();
            $this->logger->error($throwable->getMessage());
        }
        return $this->json($data, $statusCode);
    }

    #[OA\Delete(summary: 'Удаление книги')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'ok',
        content: new Model(type: SuccessDeleteResponse::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'книга не найдена',
        content: new Model(type: CommonError::class)
    )]
    #[Route(path: '/book/{id}', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $data = ['success' => false];
        $statusCode = Response::HTTP_BAD_REQUEST;
        try {
            $this->bookService->delete($id);
            $data['success'] = true;
            $statusCode = Response::HTTP_OK;
        } catch (CustomHttpExceptionInterface $throwable) {
            $data['error'] = $throwable->getHttpExceptionMessage();
            $statusCode = $throwable->getHttpExceptionCode();
        } catch (Throwable $throwable) {
            $data['error'] = $throwable->getMessage();
            $this->logger->error($throwable->getMessage());
        }
        return $this->json($data, $statusCode);
    }

    #[OA\Get(summary: 'Получение книги')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'ok',
        content: new Model(type: BookDTO::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'книга не найдена',
        content: new Model(type: CommonError::class)
    )]
    #[Route(path: '/book/{id}', methods: ['GET'])]
    public function get(string $id): Response
    {
        $data = [];
        $statusCode = Response::HTTP_BAD_REQUEST;
        try {
            $data = $this->bookService->get($id);
            $statusCode = Response::HTTP_OK;
        } catch (CustomHttpExceptionInterface $throwable) {
            $data['error'] = $throwable->getHttpExceptionMessage();
            $statusCode = $throwable->getHttpExceptionCode();
        } catch (Throwable $throwable) {
            $data['error'] = $throwable->getMessage();
            $this->logger->error($throwable->getMessage());
        }
        return $this->json($data, $statusCode);
    }

    #[OA\Get(summary: 'Получение списка книг')]
    #[OA\Parameter(
        name: 'limit',
        description: 'лимит',
        in: 'query',
        required: false
    )]
    #[OA\Parameter(
        name: 'skip',
        description: 'пропустить',
        in: 'query',
        required: false
    )]
    #[OA\Parameter(
        name: 'sortField',
        description: 'поле для сортировки',
        in: 'query',
        required: false
    )]
    #[OA\Parameter(
        name: 'sortDirection',
        description: 'направление сортировки',
        in: 'query',
        required: false
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'ok',
        content: new Model(type: BookListResponse::class)
    )]
    #[OA\Response(
        response: Response::HTTP_NO_CONTENT,
        description: 'нет книг',
        content: new Model(type: CommonError::class)
    )]
    #[Route('/books', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $data = [];
        $statusCode = Response::HTTP_BAD_REQUEST;
        try {
            $query = new BookListRequest();
            $query
                ->setLimit((int)$request->query->get('limit', 20))
                ->setSkip((int)$request->query->get('skip', 0))
                ->setSortDirection($request->query->get('sortDirection', 'DESC'))
                ->setSortField($request->query->get('sortField', 'publishedAt'));
            $data = $this->bookService->list($query);
            $statusCode = $data->getTotal() === 0 ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        } catch (CustomHttpExceptionInterface $throwable) {
            $data['error'] = $throwable->getHttpExceptionMessage();
            $statusCode = $throwable->getHttpExceptionCode();
        } catch (Throwable $throwable) {
            $data['error'] = $throwable->getMessage();
            $this->logger->error($throwable->getMessage());
        }
        return $this->json($data, $statusCode);
    }
}
