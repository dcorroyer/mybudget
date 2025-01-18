<?php

declare(strict_types=1);

namespace App\Savings\Dto\Http;

use App\Shared\Enum\PeriodsEnum;
use Symfony\Component\Validator\Constraints as Assert;

class BalanceHistoryFilterQuery
{
    #[Assert\Type(PeriodsEnum::class)]
    private ?PeriodsEnum $period = null;

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

    public function getPeriod(): ?PeriodsEnum
    {
        return $this->period;
    }

    public function setPeriod(?PeriodsEnum $period): void
    {
        $this->period = $period;
    }
}
