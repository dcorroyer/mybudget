<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Income\Payload\IncomePayload;
use App\Entity\Budget;
use App\Entity\Income;
use App\Repository\IncomeRepository;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
    ) {
    }

    public function create(IncomePayload $incomePayload, Budget $budget): Income
    {
        $income = new Income();

        $income->setName($incomePayload->getName())
            ->setAmount($incomePayload->getAmount())
            ->setBudget($budget)
        ;

        $this->incomeRepository->save($income);

        return $income;
    }
}
