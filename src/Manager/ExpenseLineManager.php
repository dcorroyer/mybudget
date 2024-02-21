<?php

namespace App\Manager;

use App\Dto\Expense\Payload\ExpensePayload;
use App\Service\ExpenseService;
use Doctrine\ORM\EntityManager;

class ExpenseLineManager
{
    public function __construct(
        private ExpenseService $expenseService,
        private EntityManager $entityManager
    )
    {
    }

    public function createAndPersist(ExpensePayload $payload)
    {
        $expenseLines = $this->expenseService->create($payload);

        $this->entityManager->flush();
    }
}