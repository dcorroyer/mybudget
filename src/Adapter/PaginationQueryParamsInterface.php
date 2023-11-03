<?php

declare(strict_types=1);

namespace App\Adapter;

interface PaginationQueryParamsInterface
{
    public function getPage(): int;

    public function getLimit(): int;
}
