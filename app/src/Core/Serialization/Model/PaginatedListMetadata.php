<?php

declare(strict_types=1);

namespace App\Core\Serialization\Model;

use App\Core\Serialization\ApiSerializationGroups;
use Symfony\Component\Serializer\Annotation as Serializer;

final class PaginatedListMetadata
{
    public const DEFAULT_PER_PAGE = 20;

    #[Serializer\Groups([ApiSerializationGroups::PAGINATED_LIST])]
    private int $total = 0;

    #[Serializer\Groups([ApiSerializationGroups::PAGINATED_LIST])]
    private int $currentPage = 1;

    #[Serializer\Groups([ApiSerializationGroups::PAGINATED_LIST])]
    private int $perPage = self::DEFAULT_PER_PAGE;

    #[Serializer\Groups([ApiSerializationGroups::PAGINATED_LIST])]
    private int $from = 1;

    #[Serializer\Groups([ApiSerializationGroups::PAGINATED_LIST])]
    private int $to = 20;

    #[Serializer\Groups([ApiSerializationGroups::PAGINATED_LIST])]
    private bool $hasMore = false;

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function setFrom(int $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function getTo(): int
    {
        return $this->to;
    }

    public function setTo(int $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getHasMore(): bool
    {
        return $this->hasMore;
    }

    public function setHasMore(bool $hasMore): self
    {
        $this->hasMore = $hasMore;

        return $this;
    }
}
