<?php

declare(strict_types=1);

namespace App\Dto\BalanceHistory\Response;

class BalanceHistoryResponse
{
    /**
     * @param array<BalanceHistoryAccountResponse> $accounts
     * @param array<BalanceHistoryBalanceResponse> $balances
     */
    public function __construct(
        public readonly array $accounts,
        public readonly array $balances,
    ) {
    }
}
