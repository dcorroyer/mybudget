<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Dto\Expense\Response\ExpenseResponse;
use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use My\RestBundle\Helper\DtoToEntityHelper;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $expenseRepository,
        private readonly ExpenseLineService $expenseLineService,
        private readonly DtoToEntityHelper $dtoToEntityHelper,
    ) {
    }

    public function create(ExpensePayload $payload): ExpenseResponse
    {
        $expenseLines = [];

        if ($payload->getExpenseLines() !== null) {
            foreach ($payload->getExpenseLines() as $expenseLine) {
                $expenseLines[] = $this->expenseLineService->create($expenseLine);
            }
        }

        $expense = new Expense();

        /** @var Expense $expense */
        $expense = $this->dtoToEntityHelper->create($payload, $expense);

        foreach ($expenseLines as $expenseLine) {
            $expense->addExpenseLine($expenseLine);
        }

        $this->expenseRepository->save($expense, true);

        return (new ExpenseResponse())
            ->setId($expense->getId())
            ->setDate($expense->getDate())
            ->setAmount($expense->getAmount())
            ->setExpenseLines($expense->getExpenseLines())
            ;
    }
}
