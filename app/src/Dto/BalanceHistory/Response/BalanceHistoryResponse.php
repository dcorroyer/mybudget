<?php

declare(strict_types=1);

namespace App\Dto\BalanceHistory\Response;

use App\Dto\Account\Response\AccountPartialResponse;

class BalanceHistoryResponse
{
    /**
     * @param array<AccountPartialResponse> $accounts
     * @param array<BalanceResponse> $balances
     */
    public function __construct(
        public readonly array $accounts,
        public readonly array $balances,
    ) {
    }
}
