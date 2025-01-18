<?php

declare(strict_types=1);

namespace App\Core\Api;

use App\Core\Dto\PaginatedResponseDto;
use App\Core\Serialization\Model\PaginatedListMetadata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class AbstractApiController extends AbstractController
{
    protected function successResponse(
        mixed $data,
        mixed $meta = null,
        array $groups = [],
        ObjectNormalizerContextBuilder $context = new ObjectNormalizerContextBuilder(),
        int $status = Response::HTTP_OK
    ): JsonResponse {
        $responseContext = $context->withGroups($groups);

        $response = [
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return $this->json(data: $response, status: $status, context: $responseContext->toArray());
    }

    public function paginatedResponse(PaginatedResponseDto $pagination): JsonResponse
    {
        /** @var array<object> $data */
        $data = $pagination->data;

        $page = $pagination->meta->page;
        $itemsPerPage = $pagination->meta->limit;
        $total = $pagination->meta->total;
        $firstItem = \count($data) > 0 ? ($page - 1) * $itemsPerPage + 1 : 0;
        $lastItem = \count($data) > 0 ? $firstItem + \count($data) - 1 : 0;
        $hasMore = $itemsPerPage !== -1 && $total > ($itemsPerPage * $page);

        $meta = (new PaginatedListMetadata())
            ->setCurrentPage($page)
            ->setPerPage($itemsPerPage)
            ->setFrom($firstItem)
            ->setTo($lastItem)
            ->setTotal($total)
            ->setHasMore($hasMore)
        ;

        return $this->successResponse(data: $data, meta: $meta);
    }

    public function noContentResponse(): JsonResponse
    {
        return $this->json(data: null, status: 204);
    }
}
