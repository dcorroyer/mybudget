<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Savings\Event\TransactionCreatedEvent;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\Event;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('event')]
#[Group('transaction-event')]
final class TransactionCreatedEventTest extends TestCase
{
    use Factories;

    #[TestDox('When creating TransactionCreatedEvent, it should store transaction and account')]
    #[Test]
    public function construct_WithTransactionAndAccount_StoresCorrectData(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);

        // ACT
        $event = new TransactionCreatedEvent($transaction->_real(), $account->_real());

        // ASSERT
        self::assertInstanceOf(Event::class, $event);
        self::assertSame($transaction->_real(), $event->getTransaction());
        self::assertSame($account->_real(), $event->getAccount());
    }

    #[TestDox('When calling getTransaction, it should return the transaction')]
    #[Test]
    public function getTransaction_ReturnsCorrectTransaction(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $event = new TransactionCreatedEvent($transaction->_real(), $account->_real());

        // ACT
        $result = $event->getTransaction();

        // ASSERT
        self::assertSame($transaction->_real(), $result);
    }

    #[TestDox('When calling getAccount, it should return the account')]
    #[Test]
    public function getAccount_ReturnsCorrectAccount(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $event = new TransactionCreatedEvent($transaction->_real(), $account->_real());

        // ACT
        $result = $event->getAccount();

        // ASSERT
        self::assertSame($account->_real(), $result);
    }
}
