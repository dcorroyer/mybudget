<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Budget\Entity\Expense;
use App\Budget\Enum\PayementMethodEnum;
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
            'name' => self::faker()->text(255),
            'amount' => self::faker()->randomFloat(2, 10, 1000),
            'category' => self::faker()->text(255),
            'paymentMethod' => self::faker()->randomElement([
                PayementMethodEnum::OTHER,
                PayementMethodEnum::BILLS_ACCOUNT,
                PayementMethodEnum::BANK_TRANSFER,
            ]),
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
