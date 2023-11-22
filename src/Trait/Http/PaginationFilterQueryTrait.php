<?php

declare(strict_types=1);

namespace App\Trait\Http;

trait PaginationFilterQueryTrait
{
    private int $page = 1;

    private int $limit = 20;

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
