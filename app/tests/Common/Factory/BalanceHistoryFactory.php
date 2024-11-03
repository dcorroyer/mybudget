<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Entity\BalanceHistory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<BalanceHistory>
 */
final class BalanceHistoryFactory extends PersistentProxyObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return BalanceHistory::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'account' => AccountFactory::new(),
            'balance' => self::faker()->randomFloat(),
            'date' => self::faker()->dateTime(),
            'transaction' => TransactionFactory::new(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    #[\Override]
    protected function initialize(): static
    {
        return $this;
        // ->afterInstantiate(function(BalanceHistory $balanceHistory): void {})
    }
}
