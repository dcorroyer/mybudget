<?php

declare(strict_types=1);

namespace App\Controller\Tracking;

use App\Entity\Tracking;
use App\Serializable\SerializationGroups;
use App\Service\TrackingService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\NotFoundResponse;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trackings')]
#[OA\Tag(name: 'Trackings')]
class GetTrackingController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'get_tracking',
        summary: 'get tracking',
        responses: [
            new successResponse(
                responseClassFqcn: Tracking::class,
                groups: [SerializationGroups::TRACKING_GET],
                description: 'Tracking get',
            ),
            new notfoundResponse(description: 'Tracking not found'),
        ],
    )]
    #[Route('/{id}', name: 'api_trackings_get', methods: Request::METHOD_GET)]
    public function __invoke(int $id, TrackingService $trackingService): JsonResponse
    {
        return $this->successResponse(data: $trackingService->get($id), groups: [SerializationGroups::TRACKING_GET]);
    }
}
