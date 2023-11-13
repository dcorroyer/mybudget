<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseLineResponse;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use My\RestBundle\Helper\DtoToEntityHelper;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
        private readonly DtoToEntityHelper $dtoToEntityHelper,
    ) {
    }

    public function create(ExpensePayload $payload): ExpenseResponse
    {
        $expense = new Expense();
        $expenseLinesResponse = [];

        /** @var Expense $expense */
        $expense = $this->dtoToEntityHelper->create($payload, $expense);

        $this->expenseRepository->save($expense, true);

        foreach ($expense->getExpenseLines() as $expenseLine) {
            $expenseLinesResponse[] = (new ExpenseLineResponse())
                ->setId($expenseLine->getId())
                ->setName($expenseLine->getName())
                ->setAmount($expenseLine->getAmount())
            ;
        }

        return (new ExpenseResponse())
            ->setId($expense->getId())
            ->setDate($expense->getDate())
            ->setAmount($expense->getAmount())
            ->setExpenseLines($expenseLinesResponse)
            ;
    }
}
