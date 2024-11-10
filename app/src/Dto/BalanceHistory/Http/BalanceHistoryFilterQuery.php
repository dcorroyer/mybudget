<?php

declare(strict_types=1);

namespace App\Dto\BalanceHistory\Http;

use App\Enum\PeriodsEnum;
use Doctrine\Common\Collections\Criteria;
use My\RestBundle\Contract\ORMFilterInterface;
use My\RestBundle\Contract\QueryFilterInterface;
use My\RestBundle\MyRestBundle;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class BalanceHistoryFilterQuery implements QueryFilterInterface
{
    #[Assert\Type(PeriodsEnum::class)]
    #[OA\Property(description: 'Period for balance history', type: 'string', enum: ['3', '6', '12'], example: '12')]
    public ?PeriodsEnum $period = null;

    /**
     * @var array<int>|null $accountIds
     */
    #[OA\Parameter(
        description: 'List of account IDs',
        schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer'), nullable: true),
        example: [1, 2, 3]
    )]
    private ?array $accountIds = null;

    public function setAccountIds(?array $accountIds): self
    {
        if ($accountIds !== null) {
            $this->accountIds = array_map('intval', $accountIds);
        }

        return $this;
    }

    public function getAccountIds(): ?array
    {
        return $this->accountIds;
    }
}
