<?php

declare(strict_types=1);

namespace App\Dto\Expense\Payload;

use App\Serializable\SerializationGroups;
use My\RestBundle\Contract\PayloadInterface;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class ExpensePayload implements PayloadInterface
{
    #[Context(
        normalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
        denormalizationContext: [
            DateTimeNormalizer::FORMAT_KEY => 'Y-m-d',
        ],
    )]
    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private \DateTimeInterface $date;

    /**
     * @var array<int, ExpenseLinePayload>
     */
    #[Serializer\Groups([SerializationGroups::EXPENSE_CREATE, SerializationGroups::EXPENSE_UPDATE])]
    private array $expenseLines = [];

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
