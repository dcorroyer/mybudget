<?php

declare(strict_types=1);

namespace App\Budget\Dto\Payload;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(
    schema: 'BudgetPayload',
    description: 'Data for creating or updating a budget',
    required: ['date', 'incomes', 'expenses']
)]
class BudgetPayload
{
    #[Assert\NotBlank]
    #[OA\Property(description: 'Budget date (YYYY-MM-DD)', type: 'string', format: 'date', example: '2023-05-01')]
    public \DateTimeInterface $date;

    /**
     * @var ?array<int, IncomePayload> $incomes
     */
    #[Assert\NotBlank]
    #[OA\Property(
        description: 'List of incomes',
        type: 'array',
        items: new OA\Items(ref: new Model(type: IncomePayload::class))
    )]
    public ?array $incomes = null;

    /**
     * @var ?array<int, ExpensePayload> $expenses
     */
    #[Assert\NotBlank]
    #[OA\Property(
        description: 'List of expenses',
        type: 'array',
        items: new OA\Items(ref: new Model(type: ExpensePayload::class))
    )]
    public ?array $expenses = null;
}
