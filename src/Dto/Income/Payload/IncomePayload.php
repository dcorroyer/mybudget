<?php

declare(strict_types=1);

namespace App\Dto\Income\Payload;

use My\RestBundle\Contract\PayloadInterface;

class IncomePayload implements PayloadInterface
{
    /**
     * @var array<int, IncomeLinePayload>
     */
    private array $incomeLines = [];

    /**
     * @return IncomeLinePayload[]
     */
    public function getIncomeLines(): array
    {
        return $this->incomeLines;
    }

    /**
     * @param IncomeLinePayload[] $incomeLines
     */
    public function setIncomeLines(array $incomeLines): self
    {
        $this->incomeLines = $incomeLines;

        return $this;
    }
}
