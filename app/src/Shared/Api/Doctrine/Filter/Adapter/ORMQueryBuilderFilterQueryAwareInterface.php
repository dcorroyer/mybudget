<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter\Adapter;

use Doctrine\ORM\QueryBuilder;

interface ORMQueryBuilderFilterQueryAwareInterface
{
    public function applyToORMQueryBuilder(QueryBuilder $qb): void;
}
