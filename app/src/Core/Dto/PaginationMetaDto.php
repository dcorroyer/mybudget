<?php

declare(strict_types=1);

namespace App\Core\Dto;

class PaginationMetaDto
{
    public function __construct(
        public readonly int $total,
        public readonly int $page,
        public readonly int $limit,
    ) {
    }
}
