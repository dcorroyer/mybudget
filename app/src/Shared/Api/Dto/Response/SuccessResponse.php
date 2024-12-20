<?php

declare(strict_types=1);

namespace App\Shared\Api\Dto\Response;

use App\Shared\Api\Dto\Adapter\ApiMetaInterface;

class SuccessResponse
{
    /**
     * @param object|array<mixed>|bool|null $data
     */
    private function __construct(
        public object|array|bool|null $data,
        public ?ApiMetaInterface $meta = null,
        public bool $success = true,
    ) {
    }

    /**
     * @param object|array<mixed>|bool|null $data
     */
    public static function new(object|array|bool|null $data, ?ApiMetaInterface $meta = null, bool $success = true): self
    {
        return new self(data: $data, meta: $meta, success: $success);
    }
}
