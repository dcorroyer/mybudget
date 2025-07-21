<?php

declare(strict_types=1);

namespace App\Savings\EventListener;

use App\Savings\Entity\Transaction;
use App\Savings\Event\TransactionCreatedEvent;
use App\Savings\Event\TransactionDeletedEvent;
use App\Savings\Event\TransactionUpdatedEvent;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Transaction::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Transaction::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Transaction::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Transaction::class)]
class TransactionListener
{
    private ?\DateTimeInterface $oldTransactionDate = null;

    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function postPersist(Transaction $transaction): void
    {
        $account = $transaction->getAccount();
        if ($account === null) {
            return;
        }

        $this->eventDispatcher->dispatch(new TransactionCreatedEvent($transaction, $account));
    }

    public function postUpdate(Transaction $transaction): void
    {
        $account = $transaction->getAccount();
        if ($account === null) {
            return;
        }

        $this->eventDispatcher->dispatch(new TransactionUpdatedEvent(
            $transaction,
            $account,
            $this->oldTransactionDate
        ));

        $this->oldTransactionDate = null;
    }

    public function preRemove(Transaction $transaction): void
    {
        $transactionCopy = clone $transaction;
        $account = $transaction->getAccount();
        if ($account === null) {
            return;
        }

        $this->eventDispatcher->dispatch(new TransactionDeletedEvent($transactionCopy, $account));
    }

    public function preUpdate(Transaction $transaction): void
    {
        // Store the old date before the update happens
        $this->oldTransactionDate = $transaction->getDate();
    }
}
