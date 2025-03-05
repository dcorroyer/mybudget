<?php

declare(strict_types=1);

namespace App\Savings\Dto\Response;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'BalanceHistoryResponse', description: 'Balance history data',)]
class BalanceHistoryResponse
{
    /**
     * @param array<AccountPartialResponse> $accounts
     * @param array<BalanceResponse>        $balances
     */
    public function __construct(
        #[OA\Property(
            description: 'List of accounts',
            type: 'array',
            items: new OA\Items(ref: new Model(type: AccountPartialResponse::class))
        )]
        public readonly array $accounts,

        #[OA\Property(
            description: 'List of balances by date',
            type: 'array',
            items: new OA\Items(ref: new Model(type: BalanceResponse::class))
        )]
        public readonly array $balances,
    ) {
    }
}
