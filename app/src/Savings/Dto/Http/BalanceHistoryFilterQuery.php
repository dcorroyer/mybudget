<?php

declare(strict_types=1);

namespace App\Savings\Dto\Http;

use App\Savings\Enum\PeriodsEnum;
use App\Shared\Api\Dto\Contract\QueryFilterInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class BalanceHistoryFilterQuery implements QueryFilterInterface
{
    #[Assert\Type(PeriodsEnum::class)]
    #[OA\Property(description: 'Period for balance history', type: 'string', enum: ['3', '6', '12'], example: '12')]
    private ?PeriodsEnum $period = null;

    /**
     * @var array<int>|null
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

    public function getPeriod(): ?PeriodsEnum
    {
        return $this->period;
    }

    public function setPeriod(?PeriodsEnum $period): void
    {
        $this->period = $period;
    }
}
