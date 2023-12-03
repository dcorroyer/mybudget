<?php

declare(strict_types=1);

namespace App\Controller\Tracking;

use App\Dto\Tracking\Payload\TrackingPayload;
use App\Entity\Tracking;
use App\Serializable\SerializationGroups;
use App\Service\TrackingService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\SuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trackings')]
#[OA\Tag(name: 'Trackings')]
class CreateTrackingController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_POST,
        operationId: 'post_tracking',
        summary: 'post tracking',
        responses: [
            new successResponse(
                responseClassFqcn: Tracking::class,
                groups: [SerializationGroups::TRACKING_CREATE],
                responseCode: Response::HTTP_CREATED,
                description: 'Tracking creation',
            ),
        ],
        requestBodyClassFqcn: TrackingPayload::class
    )]
    #[Route('', name: 'api_trackings_create', methods: Request::METHOD_POST)]
    public function __invoke(
        TrackingService $trackingService,
        #[MapRequestPayload] TrackingPayload $trackingPayload
    ): JsonResponse {
        return $this->successResponse(
            data: $trackingService->create($trackingPayload),
            groups: [SerializationGroups::TRACKING_CREATE],
            status: Response::HTTP_CREATED,
        );
    }
}
