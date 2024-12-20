<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter\Trait;

use App\Shared\Api\Doctrine\Filter\FilterDefinition;
use Doctrine\ORM\QueryBuilder;

trait QueryBuilderParameterTrait
{
    protected function generateRandomParameterName(): string
    {
        return uniqid('param_');
    }

    private function getAlias(QueryBuilder $qb, FilterDefinition $definition): string
    {
        $alias = $definition->join?->alias;

        if ($alias === null) {
            $rootAliases = $qb->getRootAliases();
            $alias = $rootAliases[0] ?? 'e';
        }

        return $alias;
    }
}
