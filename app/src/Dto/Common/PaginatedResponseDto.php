<?php

declare(strict_types=1);

namespace App\Dto\Common;

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