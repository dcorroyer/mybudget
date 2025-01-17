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
    private ?array $accountIds = null;

    /**
     * @return array<int>|null
     */
    public function getAccountIds(): ?array
    {
        return $this->accountIds;
    }

    /**
     * @param array<int|string>|null $accountIds
     */
    public function setAccountIds(?array $accountIds): void
    {
        if ($accountIds === null) {
            $this->accountIds = null;

            return;
        }

        $this->accountIds = array_map(static fn (int|string $value): int => (int) $value, $accountIds);
    }
}
