<?php

declare(strict_types=1);

namespace App\Budget\Dto\Http;

class BudgetFilterQuery
{
    private ?int $year = null;
    
    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): void
    {
        $this->year = $year;
    }
}
