<?php

declare(strict_types=1);

namespace App\Budget\Dto\Payload;

use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'IncomePayload',
    description: 'Data for creating or updating an income',
    required: ['name', 'amount']
)]
class IncomePayload
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    #[OA\Property(description: 'Income name', example: 'Salaire', type: 'string')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[OA\Property(description: 'Income amount', example: 2500, type: 'number', format: 'float')]
    public float $amount;
}
