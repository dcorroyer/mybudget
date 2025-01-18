<?php

declare(strict_types=1);

namespace App\Core\Dto;

class PaginatedResponseDto
{
    /**
     * @param array<int, mixed> $data
     */
    public function __construct(
        public readonly array $data,
        public readonly PaginationMetaDto $meta,
    ) {
    }
}
