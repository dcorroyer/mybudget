<?php

declare(strict_types=1);

namespace App\Dto\Transaction\Http;

use OpenApi\Attributes as OA;

class TransactionFilterQuery
{
    /**
     * @var array<int>|null $accountIds
     */
    #[OA\Parameter(
        description: 'List of account IDs',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        example: [1, 2, 3]
    )]
    public ?array $accountIds = null;
}
