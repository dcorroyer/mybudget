<?php

declare(strict_types=1);

namespace App\Service\Income;

use App\Entity\Income;
use Doctrine\ORM\EntityManagerInterface;

class IncomeService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {
    }

    public function create(Income $income): Income
    {
        $this->em->persist($income);
        $this->em->flush();

        return $income;
    }

    public function update(Income $income): Income
    {
        $this->em->flush();

        return $income;
    }

    public function delete(Income $income): void
    {
        $this->em->remove($income);
        $this->em->flush();
    }
}
