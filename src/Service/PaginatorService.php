<?php

declare(strict_types=1);

namespace App\Service;

use App\Adapter\PaginationQueryParamsInterface;
use App\Contract\ORMFilterInterface;
use App\Dto\PaginationQueryParams;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

class PaginatorService
{
    public function __construct(
        private readonly PaginatorInterface $paginator
    ) {
    }

    public function paginate(
        QueryBuilder $query,
        PaginationQueryParamsInterface $paginationQueryParams = null,
        ORMFilterInterface $filter = null,
        Criteria $extraCriteria = null
    ): SlidingPagination {
        $paginationQueryParams ??= new PaginationQueryParams();
        if ($filter) {
            $this->applyFilter($query, $filter);
        }

        if ($extraCriteria) {
            $query->addCriteria($extraCriteria);
        }

        $pagination = $this->paginator->paginate(
            $query,
            $paginationQueryParams->getPage(),
            $paginationQueryParams->getLimit()
        );

        if (! $pagination instanceof SlidingPagination) {
            throw new \Exception();
        }

        return $pagination;
    }

    private function applyFilter(QueryBuilder $query, ORMFilterInterface $filter): void
    {
        $query->addCriteria($filter->getCriteria());
    }
}
