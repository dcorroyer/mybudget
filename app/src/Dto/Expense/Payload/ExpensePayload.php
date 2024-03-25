<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Dto\ExpenseCategory\Payload\ExpenseCategoryPayload;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ExpensePayload implements PayloadInterface
{
    #[Assert\NotBlank]
    private ExpenseCategoryPayload $category;

    /**
     * @var array<int, ExpenseLinePayload>
     */
    #[Assert\NotBlank]
    private array $expenseLines = [];

    public function getCategory(): ExpenseCategoryPayload
    {
        return $this->category;
    }

    public function setCategory(ExpenseCategoryPayload $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return ExpenseLinePayload[]
     */
    public function getExpenseLines(): array
    {
        return $this->expenseLines;
    }

    /**
     * @param ExpenseLinePayload[] $expenseLines
     */
    public function setExpenseLines(array $expenseLines): self
    {
        $this->expenseLines = $expenseLines;

        return $this;
    }
}
