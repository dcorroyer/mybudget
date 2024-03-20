<?php

declare(strict_types=1);

namespace App\Dto\Budget\Payload;

use App\Trait\Payload\DatePayloadTrait;
use My\RestBundle\Contract\PayloadInterface;

class BudgetPayload implements PayloadInterface
{
    use DatePayloadTrait;

    private int $incomeId;

    private int $expenseId;

    private int $userId;

    public function getIncomeId(): int
    {
        return $this->incomeId;
    }

    public function setIncomeId(int $incomeId): static
    {
        $this->incomeId = $incomeId;

        return $this;
    }

    public function getExpenseId(): int
    {
        return $this->expenseId;
    }

    public function setExpenseId(int $expenseId): static
    {
        $this->expenseId = $expenseId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }
}
