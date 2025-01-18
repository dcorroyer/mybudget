<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

class BalanceHistoryResponse
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
