<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\Response\HealthCheck;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;


class HealthCheckController extends AbstractController
{
    /**
     * @Route("/books/api/v1/health", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="ok",
     *     @Model(type=HealthCheck::class)
     * )
     * @OA\Tag(name="HealthCheck", description="Проверка статуса приложения")
     */
    public function healthCheck(): Response
    {
        return $this->json(
            ['success' => true],
            Response::HTTP_OK
        );
    }
}
