<?php

declare(strict_types=1);

namespace App\Budget\Service;

use App\Budget\Dto\Payload\IncomePayload;
use App\Budget\Entity\Budget;
use App\Budget\Entity\Income;
use App\Budget\Repository\IncomeRepository;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
    ) {
    }

    public function create(IncomePayload $incomePayload, Budget $budget): Income
    {
        $income = new Income();

        $income->setName($incomePayload->name)
            ->setAmount($incomePayload->amount)
            ->setBudget($budget)
        ;

        $this->incomeRepository->save($income);

        return $income;
    }
}
