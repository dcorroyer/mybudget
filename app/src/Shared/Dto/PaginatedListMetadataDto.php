<?php

declare(strict_types=1);

namespace App\Shared\Dto;

final class PaginatedListMetadataDto
{
    public function __construct(
        public readonly int $total,
        public readonly int $currentPage,
        public readonly int $perPage,
        public readonly int $from,
        public readonly int $to,
        public readonly bool $hasMore,
    ) {
    }
}
