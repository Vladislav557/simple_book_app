<?php

namespace App\Controller;

use App\Model\Response\HealthCheckResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;


#[Route(path: '/books/api/v1')]
#[OA\Tag(name: 'Health Check', description: 'Checking of status app')]
class HealthCheckController extends AbstractController
{

    #[OA\Get(summary: 'Проверка работы приложения')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'ok',
        content: new Model(
            type: HealthCheckResponse::class
        )
    )]
    #[Route(path: '/health', methods: ["GET"])]
    public function healthCheck(): Response
    {
        return $this->json(
            new HealthCheckResponse('success'),
            Response::HTTP_OK
        );
    }
}
