<?php

namespace App\Controller;

use App\Exception\ValidatorFieldException;
use App\Interface\BookServiceInterface;
use App\Interface\CustomHttpExceptionInterface;
use App\Model\DTO\BookDTO;
use App\Model\Request\BookCreateRequest;
use App\Model\Request\BookListRequest;
use App\Model\Response\BookListResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

#[OA\Tag(name: 'Book Controller', description: 'Manage book catalog')]
#[Route(path: '/app/api/v1/catalog')]
class BooksController extends AbstractController
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
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
    #[Route('/books', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $data = [];
        $statusCode = Response::HTTP_BAD_REQUEST;
        try {
            $denormalized = $this
                ->denormalizer
                ->denormalize($request->query->all(), BookListRequest::class);
            $violations = $this->validator->validate($denormalized);
            if ($violations->count()) {
                throw new ValidatorFieldException($violations->get(0)->getPropertyPath());
            }
            $data = $this->bookService->list($denormalized);
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
