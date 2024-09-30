<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\Expense;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Expense>
 */
final class ExpenseFactory extends PersistentProxyObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Expense::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'amount' => self::faker()->randomFloat(),
            'budget' => BudgetFactory::new(),
            'category' => self::faker()->text(255),
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this;
        // ->afterInstantiate(function(Expense $expense): void {})
    }
}
