<?php

declare(strict_types=1);

namespace App\Savings\Event;

use App\Savings\Entity\Account;
use App\Savings\Entity\Transaction;
use Symfony\Contracts\EventDispatcher\Event;

class TransactionDeletedEvent extends Event
{
    public function __construct(
        private readonly Transaction $transaction,
        private readonly Account $account,
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
}
