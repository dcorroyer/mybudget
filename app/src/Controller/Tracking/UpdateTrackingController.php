<?php

declare(strict_types=1);

namespace App\Controller\Tracking;

use Symfony\Component\Routing\Attribute\Route;
use App\Dto\Tracking\Payload\TrackingPayload;
use App\Dto\Tracking\Payload\UpdateTrackingPayload;
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
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[Route('/trackings')]
#[OA\Tag(name: 'Trackings')]
class UpdateTrackingController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_PUT,
        operationId: 'put_tracking',
        summary: 'put tracking',
        responses: [
            new SuccessResponse(responseClassFqcn: Tracking::class, groups: [SerializationGroups::TRACKING_UPDATE], description: 'Tracking update'),
            new NotFoundResponse(description: 'Tracking not found'),
        ],
        requestBodyClassFqcn: TrackingPayload::class
    )]
    #[Route('/{id}', name: 'api_trackings_update', methods: Request::METHOD_PUT)]
    public function __invoke(
        TrackingService $trackingService,
        Tracking $tracking,
        #[MapRequestPayload] UpdateTrackingPayload $updateTrackingPayload
    ): JsonResponse {
        return $this->successResponse(data: $trackingService->update($updateTrackingPayload, $tracking), groups: [SerializationGroups::TRACKING_UPDATE]);
    }
}
