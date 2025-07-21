<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\Savings\Event\TransactionCreatedEvent;
use App\Savings\Event\TransactionDeletedEvent;
use App\Savings\Event\TransactionUpdatedEvent;
use App\Savings\EventSubscriber\MonthlyBalanceSubscriber;
use App\Savings\Service\MonthlyBalanceService;
use App\Tests\Common\Factory\AccountFactory;
use App\Tests\Common\Factory\TransactionFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zenstruck\Foundry\Test\Factories;

/**
 * @internal
 */
#[Group('unit')]
#[Group('event-subscriber')]
#[Group('monthly-balance-subscriber')]
final class MonthlyBalanceSubscriberTest extends TestCase
{
    use Factories;

    private MonthlyBalanceService $monthlyBalanceService;
    private MonthlyBalanceSubscriber $subscriber;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->monthlyBalanceService = $this->createMock(MonthlyBalanceService::class);
        $this->subscriber = new MonthlyBalanceSubscriber($this->monthlyBalanceService);
    }

    #[TestDox('MonthlyBalanceSubscriber should implement EventSubscriberInterface')]
    #[Test]
    public function implementsEventSubscriberInterface(): void
    {
        // ASSERT
        self::assertInstanceOf(EventSubscriberInterface::class, $this->subscriber);
    }

    #[TestDox('When calling getSubscribedEvents, it should return correct event mappings')]
    #[Test]
    public function getSubscribedEvents_ReturnsCorrectEventMappings(): void
    {
        // ACT
        $subscribedEvents = MonthlyBalanceSubscriber::getSubscribedEvents();

        // ASSERT
        $expectedEvents = [
            TransactionCreatedEvent::class => 'onTransactionCreated',
            TransactionUpdatedEvent::class => 'onTransactionUpdated',
            TransactionDeletedEvent::class => 'onTransactionDeleted',
        ];

        self::assertSame($expectedEvents, $subscribedEvents);
    }

    #[TestDox('When transaction is created, it should call monthlyBalanceService with correct transaction')]
    #[Test]
    public function onTransactionCreated_CallsMonthlyBalanceServiceWithCorrectTransaction(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $event = new TransactionCreatedEvent($transaction->_real(), $account->_real());

        $this->monthlyBalanceService
            ->expects($this->once())
            ->method('handleTransactionCreated')
            ->with($transaction->_real())
        ;

        // ACT
        $this->subscriber->onTransactionCreated($event);

        // ASSERT - Verified by expectations
    }

    #[TestDox('When transaction is updated, it should call monthlyBalanceService with transaction and oldDate')]
    #[Test]
    public function onTransactionUpdated_CallsMonthlyBalanceServiceWithTransactionAndOldDate(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $oldDate = new \DateTimeImmutable('2024-01-15');
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real(), $oldDate);

        $this->monthlyBalanceService
            ->expects($this->once())
            ->method('handleTransactionUpdated')
            ->with($transaction->_real(), $oldDate)
        ;

        // ACT
        $this->subscriber->onTransactionUpdated($event);

        // ASSERT - Verified by expectations
    }

    #[TestDox('When transaction is updated without oldDate, it should call monthlyBalanceService with null')]
    #[Test]
    public function onTransactionUpdated_WithoutOldDate_CallsMonthlyBalanceServiceWithNull(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $event = new TransactionUpdatedEvent($transaction->_real(), $account->_real());

        $this->monthlyBalanceService
            ->expects($this->once())
            ->method('handleTransactionUpdated')
            ->with($transaction->_real(), null)
        ;

        // ACT
        $this->subscriber->onTransactionUpdated($event);

        // ASSERT - Verified by expectations
    }

    #[TestDox('When transaction is deleted, it should call monthlyBalanceService with correct transaction')]
    #[Test]
    public function onTransactionDeleted_CallsMonthlyBalanceServiceWithCorrectTransaction(): void
    {
        // ARRANGE
        $account = AccountFactory::createOne();
        $transaction = TransactionFactory::createOne([
            'account' => $account,
        ]);
        $event = new TransactionDeletedEvent($transaction->_real(), $account->_real());

        $this->monthlyBalanceService
            ->expects($this->once())
            ->method('handleTransactionDeleted')
            ->with($transaction->_real())
        ;

        // ACT
        $this->subscriber->onTransactionDeleted($event);

        // ASSERT - Verified by expectations
    }
}
