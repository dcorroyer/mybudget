<?php

declare(strict_types=1);

namespace App\Dto\BalanceHistory\Http;

use App\Enum\PeriodsEnum;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class BalanceHistoryFilterQuery
{
    #[Assert\Type(PeriodsEnum::class)]
    #[OA\Property(
        description: 'Period for balance history',
        type: 'string',
        enum: ['3', '6', '12', 'all'],
        example: '12'
    )]
    public ?PeriodsEnum $period = PeriodsEnum::ALL;

    #[Assert\All([new Assert\Type('integer'), new Assert\Positive()])]
    #[OA\Property(
        description: 'Filter by account IDs',
        type: 'array',
        items: new OA\Items(type: 'integer'),
        example: [1, 2, 3]
    )]
    public ?array $accountIds = null;
}
