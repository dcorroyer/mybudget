<?php

declare(strict_types=1);

namespace App\Shared\Api\Doctrine\Filter\Operator\ComparisonOperator;

use App\Shared\Api\Doctrine\Filter\FilterDefinition;
use App\Shared\Api\Doctrine\Filter\Operator\OperatorInterface;
use App\Shared\Api\Doctrine\Filter\Trait\QueryBuilderParameterTrait;
use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

class EqualOperator implements OperatorInterface
{
    use QueryBuilderParameterTrait;

    public function operator(): string
    {
        return 'eq';
    }

    public function apply(QueryBuilder $qb, FilterDefinition $definition, string|array $value): void
    {
        Assert::string($value);
        $parameterName = $this->generateRandomParameterName();

        $qb->setParameter($parameterName, $value);

        $alias = $this->getAlias($qb, $definition);

        $comparison = new Comparison("{$alias}.{$definition->field}", Comparison::EQ, ":{$parameterName}");

        $qb->andWhere($comparison);
    }
}
