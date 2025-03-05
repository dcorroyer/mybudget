<?php

declare(strict_types=1);

namespace App\Shared\Api;

use App\Shared\Dto\PaginatedListMetadataDto;
use App\Shared\Dto\PaginatedResponseDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AbstractApiController extends AbstractController
{
    protected function successResponse(
        mixed $data,
        mixed $meta = null,
        int $status = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return $this->json($response, $status);
    }

    public function paginatedResponse(PaginatedResponseDto $pagination): JsonResponse
    {
        $page = $pagination->meta->page;
        $itemsPerPage = $pagination->meta->limit;
        $total = $pagination->meta->total;
        $firstItem = \count($pagination->data) > 0 ? ($page - 1) * $itemsPerPage + 1 : 0;
        $lastItem = \count($pagination->data) > 0 ? $firstItem + \count($pagination->data) - 1 : 0;
        $hasMore = $itemsPerPage !== -1 && $total > ($itemsPerPage * $page);

        $meta = new PaginatedListMetadataDto(
            total: $total,
            currentPage: $page,
            perPage: $itemsPerPage,
            from: $firstItem,
            to: $lastItem,
            hasMore: $hasMore,
        );

        return $this->successResponse(data: $pagination->data, meta: $meta);
    }

    public function noContentResponse(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
