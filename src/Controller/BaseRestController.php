<?php

declare(strict_types=1);

namespace App\Controller;

use App\Utils\ObjectContextMerger;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use My\OpenApiBundle\Serialization\Model\ApiResponse;
use My\OpenApiBundle\Serialization\Model\PaginatedListMetadata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class BaseRestController extends AbstractController
{
    /**
     * @param array<string> $groups
     */
    public function apiResponse(
        mixed $data,
        array $groups = [],
        ObjectNormalizerContextBuilder $context = new ObjectNormalizerContextBuilder(),
        array|object $meta = [],
        int $status = Response::HTTP_OK,
    ): JsonResponse {
        $apiResponse = new ApiResponse(data: $data, meta: $meta);

        $responseContext = $context
            ->withGroups([...$groups]);

        return $this->json(
            data: $apiResponse,
            status: $status,
            context: (array) ObjectContextMerger::mergeContext($context, $responseContext)
        );
    }

    public function paginateResponse(SlidingPagination $pagination, array $groups): JsonResponse
    {
        /** @var array<object> $data */
        $data = $pagination->getItems();

        $page = $pagination->getCurrentPageNumber();
        $itemsPerPage = $pagination->getItemNumberPerPage();
        $total = $pagination->getTotalItemCount();
        $firstItem = count($data) > 0 ? ($page - 1) * $itemsPerPage + 1 : 0;
        $lastItem = count($data) > 0 ? $firstItem + count($data) - 1 : 0;
        $hasMore = $itemsPerPage !== -1 && $total > ($itemsPerPage * $page);

        $meta = (new PaginatedListMetadata())
            ->setCurrentPage($page)
            ->setPerPage($itemsPerPage)
            ->setFrom($firstItem)
            ->setTo($lastItem)
            ->setTotal($total)
            ->setHasMore($hasMore);

        return $this->apiResponse(data: $data, groups: $groups, meta: $meta);
    }
}
