<?php

declare(strict_types=1);

namespace App\Controller\Tracking;

use App\Dto\Tracking\Http\TrackingFilterQuery;
use App\Entity\Tracking;
use App\Serializable\SerializationGroups;
use App\Service\TrackingService;
use My\RestBundle\Attribute\MyOpenApi\MyOpenApi;
use My\RestBundle\Attribute\MyOpenApi\Response\PaginatedSuccessResponse;
use My\RestBundle\Controller\BaseRestController;
use My\RestBundle\Dto\PaginationQueryParams;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/trackings')]
#[OA\Tag(name: 'Trackings')]
class ListTrackingController extends BaseRestController
{
    #[MyOpenApi(
        httpMethod: Request::METHOD_GET,
        operationId: 'list_tracking',
        summary: 'list tracking',
        responses: [
            new paginatedSuccessResponse(
                responseClassFqcn: Tracking::class,
                groups: [SerializationGroups::TRACKING_LIST],
                description: 'Return the paginated list of trackings'
            ),
        ],
        queryParamsClassFqcn: [TrackingFilterQuery::class, PaginationQueryParams::class],
    )]
    #[Route('', name: 'api_trackings_list', methods: Request::METHOD_GET)]
    public function __invoke(
        TrackingService $trackingService,
        #[MapQueryString] PaginationQueryParams $paginationQueryParams = null,
        #[MapQueryString] TrackingFilterQuery $filter = null,
    ): JsonResponse {
        return $this->paginateResponse(
            $trackingService->paginate($paginationQueryParams, $filter),
            [SerializationGroups::TRACKING_LIST],
        );
    }
}
