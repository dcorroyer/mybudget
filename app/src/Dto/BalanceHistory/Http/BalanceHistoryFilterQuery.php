<?php

declare(strict_types=1);

namespace App\Dto\BalanceHistory\Http;

use App\Enum\PeriodsEnum;
use My\RestBundle\Contract\QueryFilterInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class BalanceHistoryFilterQuery implements QueryFilterInterface
{
    #[Assert\Type(PeriodsEnum::class)]
    #[OA\Property(description: 'Period for balance history', type: 'string', enum: ['3', '6', '12'], example: '12')]
    private ?PeriodsEnum $period;

    /**
     * @var array<int>|null
     */
    #[OA\Parameter(
        description: 'List of account IDs',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        example: [1, 2, 3]
    )]
    private ?array $accountIds;

    /**
     * @return array<int>|null
     */
    public function getAccountIds(): ?array
    {
        return $this->accountIds;
    }

    public function getPeriod(): ?PeriodsEnum
    {
        return $this->period;
    }
}
