<?php

declare(strict_types=1);

namespace App\Tests\Common\Factory;

use App\Account\Entity\Account;
use App\Account\Enum\AccountTypesEnum;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Account>
 */
final class AccountFactory extends PersistentProxyObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Account::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     */
    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'id' => self::faker()->randomNumber(),
            'name' => self::faker()->text(255),
            'type' => self::faker()->randomElement(AccountTypesEnum::cases()),
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
        // ->afterInstantiate(function(Account $account): void {})
    }
}
