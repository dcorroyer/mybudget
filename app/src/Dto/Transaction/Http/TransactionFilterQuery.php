<?php

declare(strict_types=1);

namespace App\Dto\Transaction\Http;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class TransactionFilterQuery
{
    /**
     * @var int[]|null
     */
    #[Assert\All([new Assert\Type('integer'), new Assert\Positive()])]
    #[OA\Property(
        description: 'List of account IDs',
        type: 'array',
        items: new OA\Items(type: 'integer'),
        example: [1, 2, 3]
    )]
    public ?array $accountIds = null;
}
