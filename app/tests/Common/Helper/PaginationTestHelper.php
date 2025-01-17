<?php

declare(strict_types=1);

namespace App\Tests\Common\Helper;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;

class PaginationTestHelper
{
    /**
     * @param array<mixed> $items
     *
     * @return SlidingPagination<mixed>
     */
    public static function getPagination(
        array $items,
        int $currentPageNumber = 1,
        int $numberItemsPerPage = 10
    ): SlidingPagination {
        $pagination = new SlidingPagination([]);
        $pagination->setItems(FoundryArrayHelper::convertProxyToObject($items));
        $pagination->setPaginatorOptions([]);
        $pagination->setCustomParameters([]);
        $pagination->setTotalItemCount(count($items));
        $pagination->setCurrentPageNumber($currentPageNumber);
        $pagination->setItemNumberPerPage($numberItemsPerPage);

        return $pagination;
    }

    public static function getPaginationResponse(SlidingPagination $pagination): array
    {
        /** @var array<object> $data */
        $data = $pagination->getItems();
        $page = $pagination->getCurrentPageNumber();
        $itemsPerPage = $pagination->getItemNumberPerPage();
        $total = $pagination->getTotalItemCount();
        $firstItem = count($data) > 0 ? ($page - 1) * $itemsPerPage + 1 : 0;
        $lastItem = count($data) > 0 ? $firstItem + count($data) - 1 : 0;
        $hasMore = $itemsPerPage !== -1 && $total > ($itemsPerPage * $page);

        return [
            'data' => $pagination->getItems(),
            'meta' => [
                'total' => $total,
                'currentPage' => $page,
                'perPage' => $itemsPerPage,
                'from' => $firstItem,
                'to' => $lastItem,
                'hasMore' => $hasMore,
            ],
        ];
    }
}
