<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Transaction\Entity\Transaction;
use App\Transaction\Enum\TransactionTypesEnum;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Transaction>
 */
final class TransactionFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Transaction::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'id' => self::faker()->randomNumber(),
            'account' => AccountFactory::new(),
            'amount' => self::faker()->randomFloat(),
            'date' => self::faker()->dateTime(),
            'description' => self::faker()->text(255),
            'type' => self::faker()->randomElement(TransactionTypesEnum::cases()),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this;
        // ->afterInstantiate(function(Transaction $transaction): void {})
    }
}
