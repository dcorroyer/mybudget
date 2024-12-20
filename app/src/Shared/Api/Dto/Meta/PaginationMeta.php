<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Meta;

use Knp\Component\Pager\Pagination\PaginationInterface;

readonly class PaginationMeta
{
    public function __construct(
        public int $totalItems,
        public int $currentPage,
        public int $lastPage,
        public int $firstPage,
        public int $maxPerPage,
    ) {
    }

    /**
     * @param PaginationInterface<array-key, mixed> $pagination
     *
     * @phpstan-ignore shipmonk.deadMethod (not used yet)
     */
    public static function fromPagination(PaginationInterface $pagination): self
    {
        $totalItems = $pagination->getTotalItemCount();
        $currentPage = $pagination->getCurrentPageNumber();
        $itemsPerPage = $pagination->getItemNumberPerPage();

        if ($itemsPerPage === 0) {
            $itemsPerPage = 1;
        }

        $lastPage = (int) round($totalItems / $itemsPerPage);
        if ($lastPage === 0) {
            $lastPage = 1;
        }

        return new self(
            totalItems: $totalItems,
            currentPage: $currentPage,
            lastPage: $lastPage,
            firstPage: 1,
            maxPerPage: $itemsPerPage,
        );
    }
}
