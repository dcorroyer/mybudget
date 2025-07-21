<?php

declare(strict_types=1);

namespace App\Savings\Dto\Payload;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(schema: 'AccountPayload', description: 'Data for creating or updating an account', required: ['name'])]
class AccountPayload
{
    #[Assert\NotBlank]
    #[OA\Property(description: 'Account name', type: 'string', example: 'Current Account')]
    public string $name;

    #[Assert\PositiveOrZero]
    #[OA\Property(
        description: 'Initial balance when creating the account',
        type: 'number',
        format: 'float',
        example: 1000.50,
        nullable: true
    )]
    public ?float $initialBalance = null;
}
