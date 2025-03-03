<?php

declare(strict_types=1);

namespace App\Budget\Dto\Payload;

use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'ExpensePayload',
    description: 'Data for creating or updating an expense',
    required: ['name', 'amount', 'category']
)]
class ExpensePayload
{
    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    #[OA\Property(description: 'Expense name', example: 'Loyer', type: 'string')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[OA\Property(description: 'Expense amount', example: 800, type: 'number', format: 'float')]
    public float $amount;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    #[OA\Property(description: 'Expense category', example: 'Habitation', type: 'string')]
    public string $category;
}
