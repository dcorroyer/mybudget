<?php

declare(strict_types=1);

namespace App\Transaction\Dto\Response;

use App\Account\Dto\Response\AccountPartialResponse;
use App\Shared\Api\Dto\Contract\ResponseInterface;
use App\Transaction\Enum\TransactionTypesEnum;

class TransactionResponse implements ResponseInterface
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
