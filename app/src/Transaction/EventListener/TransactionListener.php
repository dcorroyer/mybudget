<?php

declare(strict_types=1);

namespace App\Transaction\EventListener;

use App\Savings\Service\BalanceHistoryService;
use App\Transaction\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Transaction::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Transaction::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Transaction::class)]
class TransactionListener
{
    public function __construct(
        private readonly BalanceHistoryService $balanceHistoryService,
    ) {
    }

    public function postPersist(Transaction $transaction): void
    {
        $this->balanceHistoryService->createBalanceHistoryEntry($transaction);
    }

    public function postUpdate(Transaction $transaction): void
    {
        $this->balanceHistoryService->updateBalanceHistoryEntry($transaction);
    }

    public function preRemove(Transaction $transaction): void
    {
        $this->balanceHistoryService->deleteBalanceHistoryEntry($transaction);
    }
}
