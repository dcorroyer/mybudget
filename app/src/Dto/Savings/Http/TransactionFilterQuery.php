<?php

declare(strict_types=1);

namespace App\Dto\Savings\Http;

class TransactionFilterQuery
{
    /**
     * @var array<int>|null
     */
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
