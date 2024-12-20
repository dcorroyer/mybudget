<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter\Operator;

use App\Shared\Api\Doctrine\Filter\FilterDefinition;
use Doctrine\ORM\QueryBuilder;

interface OperatorInterface
{
    public function operator(): string;

    public function apply(QueryBuilder $qb, FilterDefinition $definition, string|array $value): void;
}
