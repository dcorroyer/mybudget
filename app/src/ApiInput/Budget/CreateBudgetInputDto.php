<?php

declare(strict_types=1);

namespace App\ApiInput\Budget;

use App\ApiInput\Budget\Dependencies\ExpenseInputDto;
use App\ApiInput\Budget\Dependencies\IncomeInputDto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateBudgetInputDto
{
    #[Assert\NotBlank]
    public \DateTimeInterface $date;

    /** @var ?array<int, IncomeInputDto> $incomes */
    #[Assert\NotBlank]
    public ?array $incomes = null;

    /** @var ?array<int, ExpenseInputDto> $expenses */
    #[Assert\NotBlank]
    public ?array $expenses = null;
}
