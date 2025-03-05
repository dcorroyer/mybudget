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
    #[OA\Property(description: 'Expense name', type: 'string', example: 'Loyer')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::FLOAT)]
    #[OA\Property(description: 'Expense amount', type: 'number', format: 'float', example: 800)]
    public float $amount;

    #[Assert\NotBlank]
    #[OA\Property(description: 'Expense category', type: 'string', example: 'Habitation')]
    public string $category;
}
