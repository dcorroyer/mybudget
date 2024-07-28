<?php

declare(strict_types=1);

namespace App\Dto\Budget\Payload;

use App\Dto\Budget\Payload\Dependencies\ExpensePayload;
use App\Dto\Budget\Payload\Dependencies\IncomePayload;
use Symfony\Component\Validator\Constraints as Assert;

class BudgetPayload
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

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param array<int, IncomePayload>|null $incomes
     */
    public function setIncomes(?array $incomes): static
    {
        $this->incomes = $incomes;

        return $this;
    }

    /**
     * @param array<int, ExpensePayload>|null $expenses
     */
    public function setExpenses(?array $expenses): static
    {
        $this->expenses = $expenses;

        return $this;
    }
}
