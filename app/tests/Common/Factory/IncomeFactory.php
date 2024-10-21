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
    #[\Override]
    public static function class(): string
    {
        return Income::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'amount' => self::faker()->randomFloat(2, 2000, 2500),
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
        // ->afterInstantiate(function(Income $income): void {})
    }
}
