<?php

declare(strict_types=1);

namespace App\Budget\Dto\Payload;

use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BudgetPayload implements PayloadInterface
{
    #[Assert\NotBlank]
    public \DateTimeInterface $date;

    /**
     * @var ?array<int, IncomePayload> $incomes
     */
    #[Assert\NotBlank]
    public ?array $incomes = null;

    /**
     * @var ?array<int, ExpensePayload> $expenses
     */
    #[Assert\NotBlank]
    public ?array $expenses = null;
}
