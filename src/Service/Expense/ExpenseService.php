<?php

declare(strict_types=1);

namespace App\Service\Expense;

use App\Entity\Expense;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function create(Expense $expense): Expense
    {
        $this->em->persist($expense);
        $this->em->flush();

        return $expense;
    }

    public function update(Expense $expense): Expense
    {
        $this->em->flush();

        return $expense;
    }

    public function delete(Expense $expense): void
    {
        $this->em->remove($expense);
        $this->em->flush();
    }
}
