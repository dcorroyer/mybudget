<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Pagination;

class PaginationMetaDto
{
    public function __construct(
        public readonly int $total,
        public readonly int $page,
        public readonly int $limit,
    ) {
    }
}
