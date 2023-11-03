<?php

declare(strict_types=1);

namespace App\Dto;

use App\Adapter\PaginationQueryParamsInterface;
use Symfony\Component\Validator\Constraints as Assert;

readonly class PaginationQueryParams implements PaginationQueryParamsInterface
{
    public function __construct(
        #[Assert\Positive]
        private int $page = 1,
        #[Assert\Positive]
        #[Assert\LessThanOrEqual(100)]
        private int $limit = 20
    ) {
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
