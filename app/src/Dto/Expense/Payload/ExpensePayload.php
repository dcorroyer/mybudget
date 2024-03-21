<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ExpensePayload implements PayloadInterface
{
    #[Assert\NotBlank]
    private string $categoryName;

    /**
     * @var array<int, ExpenseLinePayload>
     */
    private array $expenseLines = [];

    public function getCategoryName (): string
    {
        return $this->categoryName;
    }

    public function setCategoryName(string $categoryName): self
    {
        $this->categoryName = $categoryName;

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
