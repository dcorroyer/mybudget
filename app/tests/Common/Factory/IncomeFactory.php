<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\Income;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Income>
 */
final class IncomeFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Income::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    protected function defaults(): array|callable
    {
        return [
            'amount' => self::faker()->randomFloat(),
            'budget' => BudgetFactory::new(),
            'name' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this;
        // ->afterInstantiate(function(Income $income): void {})
    }
}
