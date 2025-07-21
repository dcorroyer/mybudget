<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event;

use App\Savings\Event\TransactionUpdatedEvent;
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
final class TransactionUpdatedEventTest extends TestCase
{
    use Factories;

    #[TestDox('When creating TransactionUpdatedEvent with oldDate, it should store all data')]
    #[Test]
    public function construct_WithTransactionAccountAndOldDate_StoresCorrectData(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $oldDate = new \DateTimeImmutable('2024-01-15');

        // ACT
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real(), $oldDate);

        // ASSERT
        self::assertInstanceOf(Event::class, $event);
        self::assertSame($transaction->_real(), $event->getTransaction());
        self::assertSame($account->_real(), $event->getAccount());
        self::assertSame($oldDate, $event->getOldDate());
    }

    #[TestDox('When creating TransactionUpdatedEvent without oldDate, it should handle null')]
    #[Test]
    public function construct_WithoutOldDate_HandlersNullCorrectly(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);

        // ACT
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real());

        // ASSERT
        self::assertInstanceOf(Event::class, $event);
        self::assertSame($transaction->_real(), $event->getTransaction());
        self::assertSame($account->_real(), $event->getAccount());
        self::assertNull($event->getOldDate());
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
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real());

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
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real());

        // ACT
        $result = $event->getAccount();

        // ASSERT
        self::assertSame($account->_real(), $result);
    }

    #[TestDox('When calling getOldDate with set date, it should return the old date')]
    #[Test]
    public function getOldDate_WithSetDate_ReturnsOldDate(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $oldDate = new \DateTimeImmutable('2024-01-15');
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real(), $oldDate);

        // ACT
        $result = $event->getOldDate();

        // ASSERT
        self::assertSame($oldDate, $result);
    }

    #[TestDox('When calling getOldDate without set date, it should return null')]
    #[Test]
    public function getOldDate_WithoutSetDate_ReturnsNull(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real());

        // ACT
        $result = $event->getOldDate();

        // ASSERT
        self::assertNull($result);
    }
}
