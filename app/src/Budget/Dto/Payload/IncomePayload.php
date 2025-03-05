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
    #[OA\Property(description: 'Income name', type: 'string', example: 'Salaire')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[OA\Property(description: 'Income amount', type: 'number', format: 'float', example: 2500)]
    public float $amount;
}
