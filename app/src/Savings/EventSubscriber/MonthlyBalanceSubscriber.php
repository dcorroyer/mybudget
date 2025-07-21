<?php

declare(strict_types=1);

namespace App\Savings\EventSubscriber;

use App\Savings\Event\TransactionCreatedEvent;
use App\Savings\Event\TransactionDeletedEvent;
use App\Savings\Event\TransactionUpdatedEvent;
use App\Savings\Service\MonthlyBalanceService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class MonthlyBalanceSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MonthlyBalanceService $monthlyBalanceService,
    ) {
    }

    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TransactionCreatedEvent::class => 'onTransactionCreated',
            TransactionUpdatedEvent::class => 'onTransactionUpdated',
            TransactionDeletedEvent::class => 'onTransactionDeleted',
        ];
    }

    public function onTransactionCreated(TransactionCreatedEvent $event): void
    {
        $this->monthlyBalanceService->handleTransactionCreated($event->getTransaction());
    }

    public function onTransactionUpdated(TransactionUpdatedEvent $event): void
    {
        $this->monthlyBalanceService->handleTransactionUpdated($event->getTransaction(), $event->getOldDate());
    }

    public function onTransactionDeleted(TransactionDeletedEvent $event): void
    {
        $this->monthlyBalanceService->handleTransactionDeleted($event->getTransaction());
    }
}
