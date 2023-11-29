<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;

class ExpensePayload implements PayloadInterface
{
    /**
     * @var array<int, ExpenseLinePayload>
     */
    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private array $expenseLines = [];

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
