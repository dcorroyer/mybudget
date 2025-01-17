<?php

declare(strict_types=1);

namespace App\Savings\Dto\Transaction\Response;

use App\Savings\Dto\Account\Response\AccountPartialResponse;
use App\Shared\Enum\TransactionTypesEnum;

class TransactionResponse
{
    public function __construct(
        public readonly int $id,
        public readonly string $description,
        public readonly float $amount,
        public readonly TransactionTypesEnum $type,
        public readonly \DateTimeInterface $date,
        public readonly AccountPartialResponse $account,
    ) {
    }
}
