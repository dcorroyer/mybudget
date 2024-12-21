<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use App\Account\Dto\Response\AccountPartialResponse;
use My\RestBundle\Contract\ResponseInterface;

class BalanceHistoryResponse implements ResponseInterface
{
    /**
     * @param array<AccountPartialResponse> $accounts
     * @param array<BalanceResponse>        $balances
     */
    public function __construct(
        public readonly array $accounts,
        public readonly array $balances,
    ) {
    }
}
