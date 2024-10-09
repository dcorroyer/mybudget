<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\ExpenseCategory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<ExpenseCategory>
 */
final class ExpenseCategoryFactory extends PersistentProxyObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return ExpenseCategory::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->unique()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this;
        // ->afterInstantiate(function(ExpenseCategory $expenseCategory): void {})
    }
}
