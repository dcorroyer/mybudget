<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\Budget;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Budget>
 */
final class BudgetFactory extends PersistentProxyObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Budget::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'id' => self::faker()->randomNumber(),
            'date' => self::faker()->dateTime(),
            'name' => self::faker()->text(255),
            'savingCapacity' => self::faker()->randomFloat(),
            'user' => UserFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this;
        // ->afterInstantiate(function(Budget $budget): void {})
    }
}
