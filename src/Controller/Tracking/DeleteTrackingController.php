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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trackings')]
#[OA\Tag(name: 'Trackings')]
class DeleteTrackingController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_DELETE,
        operationId: 'delete_tracking',
        summary: 'delete tracking',
        responses: [
            new successResponse(
                responseClassFqcn: Tracking::class,
                groups: [SerializationGroups::TRACKING_DELETE],
                description: 'Tracking delete',
            ),
            new NotFoundResponse(description: 'Tracking not found'),
        ],
    )]
    #[Route('/{id}', name: 'app_trackings_delete', methods: Request::METHOD_DELETE)]
    public function __invoke(TrackingService $trackingService, Tracking $tracking): Response
    {
        $trackingService->delete($tracking);

        return $this->createNoContentResponse();
    }
}
