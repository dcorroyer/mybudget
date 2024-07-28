<?php

namespace App\Tests\Common\Factory;

use App\Entity\Budget;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Budget>
 */
final class BudgetFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Budget::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'date' => self::faker()->dateTime(),
            'name' => self::faker()->text(255),
            'savingCapacity' => self::faker()->randomFloat(),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Budget $budget): void {})
        ;
    }
}
