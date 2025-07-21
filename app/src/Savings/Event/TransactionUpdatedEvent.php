<?php

declare(strict_types=1);

namespace App\Savings\Event;

use App\Savings\Entity\Account;
use App\Savings\Entity\Transaction;
use Symfony\Contracts\EventDispatcher\Event;

class TransactionUpdatedEvent extends Event
{
    public function __construct(
        private readonly Transaction $transaction,
        private readonly Account $account,
        private readonly ?\DateTimeInterface $oldDate = null,
    ) {
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function getOldDate(): ?\DateTimeInterface
    {
        return $this->oldDate;
    }
}
